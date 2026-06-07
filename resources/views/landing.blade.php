@extends('layouts.app')

@section('title', 'Sistem Manajemen Turnamen Futsal')

@section('content')
<!-- Public Navbar -->
<nav style="background-color: var(--bg-sidebar); border-bottom: 1px solid var(--border-color); padding: 16px 40px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <div class="sidebar-logo" style="width: 36px; height: 36px; font-size: 1.1rem; font-weight: 800;">F</div>
        <span style="font-weight: 800; letter-spacing: 0.5px; font-size: 1.2rem; background: linear-gradient(to right, var(--primary), var(--secondary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">FUTSAL MANAGER</span>
    </div>
    <div>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-chart-line"></i> Dashboard Admin
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-secondary btn-sm" style="margin-right: 10px;">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
        @endauth
    </div>
</nav>

<!-- Hero Section -->
<header style="background: radial-gradient(circle at center, rgba(6, 182, 212, 0.1) 0%, transparent 60%); padding: 80px 20px; text-align: center; border-bottom: 1px solid var(--border-color);">
    <h1 style="font-size: 3rem; font-weight: 800; margin-bottom: 16px; background: linear-gradient(to right, #ffffff, #94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; letter-spacing: -1px;">
        Turnamen Futsal Terkini
    </h1>
    <p style="font-size: 1.2rem; max-width: 600px; margin: 0 auto 30px; color: var(--text-secondary);">
        Pantau klasemen tim kesayangan Anda, jadwal tanding mendatang, dan hasil pertandingan langsung dari genggaman Anda.
    </p>
</header>

<!-- Tournaments List -->
<div class="public-container">
    <h2 style="margin-bottom: 24px;"><i class="fa-solid fa-trophy" style="color: var(--secondary);"></i> Daftar Turnamen</h2>
    
    @if($tournaments->isEmpty())
        <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius); padding: 60px; text-align: center; color: var(--text-muted);">
            <i class="fa-solid fa-hourglass-empty" style="font-size: 3rem; margin-bottom: 16px; display: block;"></i>
            Belum ada turnamen yang sedang berjalan atau terdaftar.
        </div>
    @else
        <div class="landing-grid">
            @foreach($tournaments as $tournament)
                <div class="tournament-card">
                    <div class="tournament-card-header">
                        @if($tournament->status === 'draft')
                            <span class="badge badge-warning" style="margin-bottom: 12px;">Draft</span>
                        @elseif($tournament->status === 'ongoing')
                            <span class="badge badge-primary" style="margin-bottom: 12px;">Berjalan</span>
                        @else
                            <span class="badge badge-success" style="margin-bottom: 12px;">Selesai</span>
                        @endif
                        <h3 style="margin-top: 4px; font-size: 1.25rem;">{{ $tournament->name }}</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 6px;">
                            <i class="fa-solid fa-calendar-days"></i> {{ $tournament->start_date->format('d M Y') }} s/d {{ $tournament->end_date->format('d M Y') }}
                        </p>
                    </div>
                    
                    <div class="tournament-card-footer">
                        <span style="font-size: 0.85rem; color: var(--text-secondary);">
                            <i class="fa-solid fa-people-group"></i> {{ $tournament->groups()->count() }} Grup
                        </span>
                        <a href="{{ route('public.tournaments.show', $tournament->id) }}" class="btn btn-primary btn-sm">
                            Lihat Detail <i class="fa-solid fa-arrow-right-long"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<footer style="background-color: var(--bg-sidebar); border-top: 1px solid var(--border-color); padding: 30px; text-align: center; color: var(--text-muted); font-size: 0.9rem; margin-top: 60px;">
    &copy; {{ date('Y') }} Futsal Tournament Manager. Built with Laravel & Pure CSS.
</footer>
@endsection
