<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\KnockoutController;
use App\Http\Controllers\ReportController;

// Public routes (Guests & Logged in users can view)
Route::get('/', function () {
    $tournaments = \App\Models\Tournament::orderBy('created_at', 'desc')->get();
    return view('landing', compact('tournaments'));
})->name('landing');

// Public view for tournament details
Route::get('/public/tournaments/{tournament}', [TournamentController::class, 'publicShow'])->name('public.tournaments.show');
Route::get('/public/tournaments/{tournament}/knockout', [KnockoutController::class, 'publicShowBracket'])->name('public.tournaments.knockout');

// Guest routes for login/register
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        // Teams CRUD
        Route::resource('teams', TeamController::class);

        // Players CRUD
        Route::resource('players', PlayerController::class);

        // Tournaments CRUD (except show which is public/shared)
        Route::resource('tournaments', TournamentController::class)->except(['show']);

        // Groups Management
        Route::get('/tournaments/{tournament}/groups/create', [GroupController::class, 'create'])->name('groups.create');
        Route::post('/tournaments/{tournament}/groups', [GroupController::class, 'store'])->name('groups.store');
        Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
        Route::get('/groups/{group}/teams', [GroupController::class, 'manageTeams'])->name('groups.manage_teams');
        Route::post('/groups/{group}/teams', [GroupController::class, 'updateTeams'])->name('groups.update_teams');

        // Knockout Initialization
        Route::post('/tournaments/{tournament}/knockout/initialize-quarterfinals', [KnockoutController::class, 'initializeQuarterfinals'])->name('knockout.initialize_quarterfinals');
        Route::post('/tournaments/{tournament}/knockout/initialize-semifinals', [KnockoutController::class, 'initializeSemifinals'])->name('knockout.initialize_semifinals');
    });

    // Admin & Panitia shared routes
    Route::middleware('role:admin,panitia')->group(function () {
        // Show tournament details with admin controls
        Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
        Route::get('/tournaments/{tournament}/knockout', [KnockoutController::class, 'showBracket'])->name('tournaments.knockout');

        // Matches CRUD
        Route::get('/tournaments/{tournament}/matches/create', [MatchController::class, 'create'])->name('matches.create');
        Route::post('/tournaments/{tournament}/matches', [MatchController::class, 'store'])->name('matches.store');
        Route::post('/tournaments/{tournament}/matches/generate', [MatchController::class, 'generateGroupMatches'])->name('matches.generate');
        Route::get('/matches/{match}/edit', [MatchController::class, 'edit'])->name('matches.edit');
        Route::put('/matches/{match}', [MatchController::class, 'update'])->name('matches.update');
        Route::delete('/matches/{match}', [MatchController::class, 'destroy'])->name('matches.destroy');

        // Input match score
        Route::get('/matches/{match}/score', [MatchController::class, 'showInputScore'])->name('matches.score');
        Route::post('/matches/{match}/score', [MatchController::class, 'storeScore'])->name('matches.store_score');

        // Download Report PDF
        Route::get('/tournaments/{tournament}/pdf', [ReportController::class, 'generateTournamentPdf'])->name('tournaments.pdf');
    });
});
