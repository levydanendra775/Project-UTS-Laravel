<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tournaments = Tournament::orderBy('created_at', 'desc')->paginate(10);
        return view('tournaments.index', compact('tournaments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tournaments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,ongoing,completed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Tournament::create($request->all());

        return redirect()->route('tournaments.index')->with('success', 'Turnamen berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tournament $tournament)
    {
        $tournament->load([
            'groups.teams',
            'groups.standings' => function ($query) {
                $query->orderBy('points', 'desc')
                      ->orderBy('goals_difference', 'desc')
                      ->orderBy('goals_for', 'desc');
            },
            'groups.standings.team',
            'matches' => function ($query) {
                $query->orderBy('match_date', 'asc');
            },
            'matches.team1',
            'matches.team2',
            'matches.winner'
        ]);

        // Separate matches into group matches and knockout matches
        $groupMatches = $tournament->matches->where('round', 'group');
        $knockoutMatches = $tournament->matches->whereIn('round', ['quarterfinal', 'semifinal', 'final']);

        return view('tournaments.show', compact('tournament', 'groupMatches', 'knockoutMatches'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tournament $tournament)
    {
        return view('tournaments.edit', compact('tournament'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tournament $tournament)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,ongoing,completed',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $tournament->update($request->all());

        return redirect()->route('tournaments.index')->with('success', 'Turnamen berhasil diperbarui!');
    }

    /**
     * Public (unauthenticated) view of tournament details.
     */
    public function publicShow(Tournament $tournament)
    {
        $tournament->load([
            'groups.teams',
            'groups.standings' => function ($query) {
                $query->orderBy('points', 'desc')
                      ->orderBy('goals_difference', 'desc')
                      ->orderBy('goals_for', 'desc');
            },
            'groups.standings.team',
            'matches' => function ($query) {
                $query->orderBy('match_date', 'asc');
            },
            'matches.team1',
            'matches.team2',
        ]);

        $groupMatches = $tournament->matches->where('round', 'group');

        return view('tournaments.public_show', compact('tournament', 'groupMatches'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tournament $tournament)
    {
        $tournament->delete();
        return redirect()->route('tournaments.index')->with('success', 'Turnamen berhasil dihapus.');
    }
}
