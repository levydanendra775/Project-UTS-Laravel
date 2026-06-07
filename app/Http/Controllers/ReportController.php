<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generateTournamentPdf(Tournament $tournament)
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

        $groupMatches = $tournament->matches->where('round', 'group');
        $quarterfinals = $tournament->matches->where('round', 'quarterfinal');
        $semifinals = $tournament->matches->where('round', 'semifinal');
        $final = $tournament->matches->where('round', 'final')->first();

        // Configure DomPDF options for performance and quality
        $pdf = Pdf::loadView('reports.tournament_pdf', compact(
            'tournament',
            'groupMatches',
            'quarterfinals',
            'semifinals',
            'final'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-turnamen-' . str()->slug($tournament->name) . '.pdf');
    }
}
