@extends('layouts.app')

@section('title', 'Dashboard - Futsal Manager')

@section('content')
<div style="margin-bottom: 32px;">
    <h1>Dashboard Utama</h1>
    <p>Ringkasan statistik dan aktivitas sistem manajemen turnamen futsal.</p>
</div>

<!-- Stat Cards Grid -->
<div class="dashboard-grid">
    <div class="stat-card">
        <span class="stat-label">Total Tim Futsal</span>
        <span class="stat-value">{{ $stats['teams_count'] }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Total Pemain Terdaftar</span>
        <span class="stat-value">{{ $stats['players_count'] }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Turnamen Aktif</span>
        <span class="stat-value">{{ $stats['active_tournaments'] }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Pertandingan Selesai</span>
        <span class="stat-value">{{ $stats['matches_played'] }}</span>
    </div>
    <div class="stat-card">
        <span class="stat-label">Pertandingan Terjadwal</span>
        <span class="stat-value">{{ $stats['matches_scheduled'] }}</span>
    </div>
</div>

<!-- Lists Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 32px;">
    <!-- Upcoming matches -->
    <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius); padding: 24px;">
        <h3 style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: var(--secondary);">
            <i class="fa-solid fa-calendar-days"></i> Pertandingan Mendatang
        </h3>
        
        @if($upcomingMatches->isEmpty())
            <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                <i class="fa-solid fa-calendar-xmark" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                Belum ada jadwal pertandingan terdekat.
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($upcomingMatches as $match)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background-color: var(--bg-sidebar); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; gap: 12px; width: 75%;">
                            <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">
                                {{ $match->team1->name }}
                            </span>
                            <span style="color: var(--text-muted); font-size: 0.8rem;">vs</span>
                            <span style="font-size: 0.85rem; font-weight: 700; color: var(--primary); text-transform: uppercase;">
                                {{ $match->team2->name }}
                            </span>
                        </div>
                        <div style="text-align: right; font-size: 0.75rem; color: var(--text-muted); width: 25%;">
                            <div>{{ $match->match_date->format('d/m/Y') }}</div>
                            <div style="font-weight: 600; color: var(--text-secondary);">{{ $match->match_date->format('H:i') }} WIB</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recent matches -->
    <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius); padding: 24px;">
        <h3 style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px; color: var(--success);">
            <i class="fa-solid fa-square-poll-horizontal"></i> Hasil Pertandingan Terakhir
        </h3>

        @if($recentMatches->isEmpty())
            <div style="text-align: center; padding: 40px 20px; color: var(--text-muted);">
                <i class="fa-solid fa-rectangle-xmark" style="font-size: 2.5rem; margin-bottom: 12px; display: block;"></i>
                Belum ada hasil pertandingan yang diinput.
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($recentMatches as $match)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background-color: var(--bg-sidebar); border-radius: 8px; border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 0.85rem;">
                            <span style="{{ $match->winner_id === $match->team1_id ? 'color:var(--text-primary);' : 'color:var(--text-muted);' }}">
                                {{ $match->team1->name }}
                            </span>
                            <span style="background-color: var(--bg-card); padding: 2px 6px; border-radius: 4px; border: 1px solid var(--border-color); font-weight: 800; color: var(--secondary);">
                                {{ $match->team1_score }}
                            </span>
                            <span style="color: var(--text-muted);">:</span>
                            <span style="background-color: var(--bg-card); padding: 2px 6px; border-radius: 4px; border: 1px solid var(--border-color); font-weight: 800; color: var(--secondary);">
                                {{ $match->team2_score }}
                            </span>
                            <span style="{{ $match->winner_id === $match->team2_id ? 'color:var(--text-primary);' : 'color:var(--text-muted);' }}">
                                {{ $match->team2->name }}
                            </span>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); text-align: right;">
                            <span class="badge badge-success" style="font-size: 0.65rem;">{{ $match->round === 'group' ? 'Grup' : 'Knockout' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
