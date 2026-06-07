<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Group;
use App\Models\Team;
use App\Models\TournamentMatch;
use App\Models\Standing;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function create(Tournament $tournament)
    {
        $tournament->load('groups.teams');
        $teams = Team::orderBy('name', 'asc')->get();
        return view('matches.create', compact('tournament', 'teams'));
    }

    public function store(Request $request, Tournament $tournament)
    {
        $request->validate([
            'group_id' => 'nullable|exists:groups,id',
            'round' => 'required|in:group,quarterfinal,semifinal,final',
            'team1_id' => 'required|exists:teams,id|different:team2_id',
            'team2_id' => 'required|exists:teams,id',
            'match_date' => 'required|date_format:Y-m-d\TH:i',
        ]);

        TournamentMatch::create([
            'tournament_id' => $tournament->id,
            'group_id' => $request->group_id,
            'round' => $request->round,
            'team1_id' => $request->team1_id,
            'team2_id' => $request->team2_id,
            'match_date' => $request->match_date,
            'status' => 'scheduled',
        ]);

        return redirect()->route('tournaments.show', $tournament->id)->with('success', 'Jadwal pertandingan berhasil ditambahkan!');
    }

    public function generateGroupMatches(Tournament $tournament)
    {
        $groups = Group::with('teams')->where('tournament_id', $tournament->id)->get();
        $countGenerated = 0;

        foreach ($groups as $group) {
            $teams = $group->teams;
            $count = $teams->count();

            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $team1 = $teams[$i];
                    $team2 = $teams[$j];

                    // Check if match already exists
                    $exists = TournamentMatch::where('tournament_id', $tournament->id)
                        ->where('group_id', $group->id)
                        ->where('round', 'group')
                        ->where(function ($query) use ($team1, $team2) {
                            $query->where(function ($q) use ($team1, $team2) {
                                $q->where('team1_id', $team1->id)->where('team2_id', $team2->id);
                            })->orWhere(function ($q) use ($team1, $team2) {
                                $q->where('team1_id', $team2->id)->where('team2_id', $team1->id);
                            });
                        })
                        ->exists();

                    if (!$exists) {
                        TournamentMatch::create([
                            'tournament_id' => $tournament->id,
                            'group_id' => $group->id,
                            'round' => 'group',
                            'team1_id' => $team1->id,
                            'team2_id' => $team2->id,
                            'match_date' => $tournament->start_date->startOfDay()->addHours(14 + $countGenerated), // Spread schedule times
                            'status' => 'scheduled',
                        ]);
                        $countGenerated++;
                    }
                }
            }
        }

        return redirect()->route('tournaments.show', $tournament->id)
            ->with('success', "Berhasil menjadwalkan {$countGenerated} pertandingan babak grup secara otomatis!");
    }

    public function edit(TournamentMatch $match)
    {
        $match->load(['tournament', 'team1', 'team2']);
        $tournament = $match->tournament;
        $teams = Team::orderBy('name', 'asc')->get();
        $groups = Group::where('tournament_id', $tournament->id)->get();

        return view('matches.edit', compact('match', 'tournament', 'teams', 'groups'));
    }

    public function update(Request $request, TournamentMatch $match)
    {
        $request->validate([
            'group_id' => 'nullable|exists:groups,id',
            'round' => 'required|in:group,quarterfinal,semifinal,final',
            'team1_id' => 'required|exists:teams,id|different:team2_id',
            'team2_id' => 'required|exists:teams,id',
            'match_date' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $match->update([
            'group_id' => $request->group_id,
            'round' => $request->round,
            'team1_id' => $request->team1_id,
            'team2_id' => $request->team2_id,
            'match_date' => $request->match_date,
        ]);

        return redirect()->route('tournaments.show', $match->tournament_id)->with('success', 'Jadwal pertandingan berhasil diperbarui!');
    }

    public function showInputScore(TournamentMatch $match)
    {
        $match->load(['team1', 'team2', 'tournament']);
        return view('matches.score', compact('match'));
    }

    public function storeScore(Request $request, TournamentMatch $match)
    {
        $request->validate([
            'team1_score' => 'required|integer|min:0',
            'team2_score' => 'required|integer|min:0',
            'winner_id' => 'nullable|exists:teams,id',
        ]);

        $team1_score = $request->team1_score;
        $team2_score = $request->team2_score;
        $winner_id = null;

        if ($match->round === 'group') {
            if ($team1_score > $team2_score) {
                $winner_id = $match->team1_id;
            } elseif ($team2_score > $team1_score) {
                $winner_id = $match->team2_id;
            }
        } else {
            // Knockout requires a winner
            if ($team1_score > $team2_score) {
                $winner_id = $match->team1_id;
            } elseif ($team2_score > $team1_score) {
                $winner_id = $match->team2_id;
            } else {
                // It's a draw, winner_id must be specified (e.g. from penalties)
                $request->validate([
                    'winner_id' => 'required|in:' . $match->team1_id . ',' . $match->team2_id,
                ], [
                    'winner_id.required' => 'Untuk babak knockout yang seri, pemenang adu penalti wajib dipilih.',
                ]);
                $winner_id = $request->winner_id;
            }
        }

        $match->update([
            'team1_score' => $team1_score,
            'team2_score' => $team2_score,
            'winner_id' => $winner_id,
            'status' => 'played',
        ]);

        // If it was a group match, recalculate standings
        if ($match->round === 'group') {
            if ($match->group_id) {
                $this->recalculateGroupStandings($match->group_id);
            }
        } else {
            // Trigger knockout advancement checks
            app(KnockoutController::class)->checkAndGenerateNextRound($match->tournament_id);
        }

        return redirect()->route('tournaments.show', $match->tournament_id)->with('success', 'Skor pertandingan berhasil disimpan!');
    }

    public function destroy(TournamentMatch $match)
    {
        $tournamentId = $match->tournament_id;
        $groupId = $match->group_id;
        $round = $match->round;

        $match->delete();

        if ($round === 'group' && $groupId) {
            $this->recalculateGroupStandings($groupId);
        }

        return redirect()->route('tournaments.show', $tournamentId)->with('success', 'Jadwal pertandingan berhasil dihapus.');
    }

    private function recalculateGroupStandings($groupId)
    {
        // Reset all teams in the group standing to zero
        Standing::where('group_id', $groupId)->update([
            'played' => 0,
            'won' => 0,
            'drawn' => 0,
            'lost' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goals_difference' => 0,
            'points' => 0,
        ]);

        // Get all played group matches
        $matches = TournamentMatch::where('group_id', $groupId)
            ->where('round', 'group')
            ->where('status', 'played')
            ->get();

        foreach ($matches as $match) {
            $t1 = $match->team1_id;
            $t2 = $match->team2_id;
            $s1 = $match->team1_score;
            $s2 = $match->team2_score;

            // Update Team 1
            $st1 = Standing::where('group_id', $groupId)->where('team_id', $t1)->first();
            if ($st1) {
                $st1->played += 1;
                $st1->goals_for += $s1;
                $st1->goals_against += $s2;
                $st1->goals_difference = $st1->goals_for - $st1->goals_against;

                if ($s1 > $s2) {
                    $st1->won += 1;
                    $st1->points += 3;
                } elseif ($s1 == $s2) {
                    $st1->drawn += 1;
                    $st1->points += 1;
                } else {
                    $st1->lost += 1;
                }
                $st1->save();
            }

            // Update Team 2
            $st2 = Standing::where('group_id', $groupId)->where('team_id', $t2)->first();
            if ($st2) {
                $st2->played += 1;
                $st2->goals_for += $s2;
                $st2->goals_against += $s1;
                $st2->goals_difference = $st2->goals_for - $st2->goals_against;

                if ($s2 > $s1) {
                    $st2->won += 1;
                    $st2->points += 3;
                } elseif ($s1 == $s2) {
                    $st2->drawn += 1;
                    $st2->points += 1;
                } else {
                    $st2->lost += 1;
                }
                $st2->save();
            }
        }
    }
}
