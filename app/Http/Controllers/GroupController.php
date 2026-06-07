<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\Standing;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function create(Tournament $tournament)
    {
        return view('groups.create', compact('tournament'));
    }

    public function store(Request $request, Tournament $tournament)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tournament->groups()->create([
            'name' => $request->name,
        ]);

        return redirect()->route('tournaments.show', $tournament->id)->with('success', 'Grup berhasil dibuat!');
    }

    public function destroy(Group $group)
    {
        $tournamentId = $group->tournament_id;
        $group->delete();

        return redirect()->route('tournaments.show', $tournamentId)->with('success', 'Grup berhasil dihapus.');
    }

    public function manageTeams(Group $group)
    {
        $group->load('teams');
        $tournament = $group->tournament;

        // Get teams already assigned in this tournament's other groups to prevent double assignment
        $assignedTeamIds = Standing::where('tournament_id', $tournament->id)
            ->where('group_id', '!=', $group->id)
            ->pluck('team_id')
            ->toArray();

        $teams = Team::whereNotIn('id', $assignedTeamIds)->orderBy('name', 'asc')->get();

        return view('groups.manage_teams', compact('group', 'teams', 'tournament'));
    }

    public function updateTeams(Request $request, Group $group)
    {
        $request->validate([
            'teams' => 'nullable|array',
            'teams.*' => 'exists:teams,id',
        ]);

        $selectedTeams = $request->input('teams', []);

        // Sync teams in pivot table
        $group->teams()->sync($selectedTeams);

        // Ensure each team has a record in standings table
        foreach ($selectedTeams as $teamId) {
            Standing::firstOrCreate([
                'tournament_id' => $group->tournament_id,
                'group_id' => $group->id,
                'team_id' => $teamId,
            ]);
        }

        // Delete standing records for teams that were removed from the group
        Standing::where('group_id', $group->id)
            ->whereNotIn('team_id', $selectedTeams)
            ->delete();

        return redirect()->route('tournaments.show', $group->tournament_id)->with('success', 'Anggota tim grup berhasil diperbarui!');
    }
}
