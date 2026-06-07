<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Player;
use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'teams_count' => Team::count(),
            'players_count' => Player::count(),
            'active_tournaments' => Tournament::where('status', 'ongoing')->count(),
            'matches_played' => TournamentMatch::where('status', 'played')->count(),
            'matches_scheduled' => TournamentMatch::where('status', 'scheduled')->count(),
        ];

        $upcomingMatches = TournamentMatch::with(['team1', 'team2', 'tournament'])
            ->where('status', 'scheduled')
            ->orderBy('match_date', 'asc')
            ->take(5)
            ->get();

        $recentMatches = TournamentMatch::with(['team1', 'team2', 'tournament'])
            ->where('status', 'played')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'upcomingMatches', 'recentMatches'));
    }
}
