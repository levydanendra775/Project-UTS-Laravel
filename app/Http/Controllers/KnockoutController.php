<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Team;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;

class KnockoutController extends Controller
{
    public function showBracket(Tournament $tournament)
    {
        $tournament->load([
            'matches' => function ($q) {
                $q->orderBy('match_date', 'asc');
            },
            'matches.team1',
            'matches.team2',
            'matches.winner'
        ]);

        $quarterfinals = $tournament->matches->where('round', 'quarterfinal');
        $semifinals = $tournament->matches->where('round', 'semifinal');
        $final = $tournament->matches->where('round', 'final')->first();

        // Get all teams in tournament to display for initialization
        $teams = Team::orderBy('name', 'asc')->get();

        return view('knockout.bracket', compact('tournament', 'quarterfinals', 'semifinals', 'final', 'teams'));
    }

    /**
     * Public (unauthenticated) view of the knockout bracket.
     */
    public function publicShowBracket(Tournament $tournament)
    {
        $tournament->load([
            'matches' => function ($q) {
                $q->orderBy('match_date', 'asc');
            },
            'matches.team1',
            'matches.team2',
            'matches.winner'
        ]);

        $quarterfinals = $tournament->matches->where('round', 'quarterfinal');
        $semifinals    = $tournament->matches->where('round', 'semifinal');
        $final         = $tournament->matches->where('round', 'final')->first();

        return view('knockout.public_bracket', compact('tournament', 'quarterfinals', 'semifinals', 'final'));
    }

    public function initializeQuarterfinals(Request $request, Tournament $tournament)
    {
        $request->validate([
            'teams' => 'required|array|size:8',
            'teams.*' => 'exists:teams,id',
            'match_date' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $selectedTeams = $request->teams;
        // Shuffle the teams to make matchups fair/random
        shuffle($selectedTeams);

        // Delete any existing knockout matches for this tournament to reset
        TournamentMatch::where('tournament_id', $tournament->id)
            ->whereIn('round', ['quarterfinal', 'semifinal', 'final'])
            ->delete();

        $matchDate = \Carbon\Carbon::parse($request->match_date);

        // Create 4 Quarterfinal matches
        for ($i = 0; $i < 4; $i++) {
            TournamentMatch::create([
                'tournament_id' => $tournament->id,
                'round' => 'quarterfinal',
                'team1_id' => $selectedTeams[$i * 2],
                'team2_id' => $selectedTeams[$i * 2 + 1],
                'match_date' => $matchDate->copy()->addHours($i * 2),
                'status' => 'scheduled',
            ]);
        }

        return redirect()->route('tournaments.knockout', $tournament->id)
            ->with('success', 'Babak Perempat Final berhasil dibuat!');
    }

    public function initializeSemifinals(Request $request, Tournament $tournament)
    {
        $request->validate([
            'teams' => 'required|array|size:4',
            'teams.*' => 'exists:teams,id',
            'match_date' => 'required|date_format:Y-m-d\TH:i',
        ]);

        $selectedTeams = $request->teams;
        shuffle($selectedTeams);

        // Reset all knockout matches
        TournamentMatch::where('tournament_id', $tournament->id)
            ->whereIn('round', ['quarterfinal', 'semifinal', 'final'])
            ->delete();

        $matchDate = \Carbon\Carbon::parse($request->match_date);

        // Create 2 Semifinal matches
        for ($i = 0; $i < 2; $i++) {
            TournamentMatch::create([
                'tournament_id' => $tournament->id,
                'round' => 'semifinal',
                'team1_id' => $selectedTeams[$i * 2],
                'team2_id' => $selectedTeams[$i * 2 + 1],
                'match_date' => $matchDate->copy()->addHours($i * 2),
                'status' => 'scheduled',
            ]);
        }

        return redirect()->route('tournaments.knockout', $tournament->id)
            ->with('success', 'Babak Semifinal berhasil dibuat secara manual!');
    }

    public function checkAndGenerateNextRound($tournamentId)
    {
        $tournament = Tournament::find($tournamentId);
        if (!$tournament) return;

        // 1. Check Quarterfinals
        $qMatches = TournamentMatch::where('tournament_id', $tournamentId)
            ->where('round', 'quarterfinal')
            ->orderBy('id', 'asc')
            ->get();

        if ($qMatches->count() === 4) {
            $allPlayed = $qMatches->every(fn($m) => $m->status === 'played');
            if ($allPlayed) {
                // Check if semifinals already exist
                $semiExist = TournamentMatch::where('tournament_id', $tournamentId)
                    ->where('round', 'semifinal')
                    ->exists();

                if (!$semiExist) {
                    // Generate semifinals
                    // Semifinal 1: Winner Q1 vs Winner Q2
                    // Semifinal 2: Winner Q3 vs Winner Q4
                    $winners = $qMatches->pluck('winner_id')->toArray();
                    $baseDate = $qMatches->max('match_date')->addDay()->startOfDay()->addHours(15);

                    TournamentMatch::create([
                        'tournament_id' => $tournamentId,
                        'round' => 'semifinal',
                        'team1_id' => $winners[0],
                        'team2_id' => $winners[1],
                        'match_date' => $baseDate,
                        'status' => 'scheduled',
                    ]);

                    TournamentMatch::create([
                        'tournament_id' => $tournamentId,
                        'round' => 'semifinal',
                        'team1_id' => $winners[2],
                        'team2_id' => $winners[3],
                        'match_date' => $baseDate->copy()->addHours(2),
                        'status' => 'scheduled',
                    ]);
                }
            }
        }

        // 2. Check Semifinals
        $sMatches = TournamentMatch::where('tournament_id', $tournamentId)
            ->where('round', 'semifinal')
            ->orderBy('id', 'asc')
            ->get();

        if ($sMatches->count() === 2) {
            $allPlayed = $sMatches->every(fn($m) => $m->status === 'played');
            if ($allPlayed) {
                // Check if final already exists
                $finalExist = TournamentMatch::where('tournament_id', $tournamentId)
                    ->where('round', 'final')
                    ->exists();

                if (!$finalExist) {
                    // Generate final
                    // Final: Winner S1 vs Winner S2
                    $winners = $sMatches->pluck('winner_id')->toArray();
                    $baseDate = $sMatches->max('match_date')->addDay()->startOfDay()->addHours(19);

                    TournamentMatch::create([
                        'tournament_id' => $tournamentId,
                        'round' => 'final',
                        'team1_id' => $winners[0],
                        'team2_id' => $winners[1],
                        'match_date' => $baseDate,
                        'status' => 'scheduled',
                    ]);
                }
            }
        }
    }
}
