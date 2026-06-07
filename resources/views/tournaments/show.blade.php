@extends('layouts.app')

@section('title', 'Turnamen - ' . $tournament->name)

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('tournaments.index') }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Turnamen</a>
</div>

<!-- Details Header -->
<div class="details-header">
    <div class="sidebar-logo" style="width: 70px; height: 70px; font-size: 2rem; border-radius: 12px;">T</div>
    <div class="details-info">
        <h1 style="margin-bottom: 4px;">{{ $tournament->name }}</h1>
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin-top: 8px;">
            @if($tournament->status === 'draft')
                <span class="badge badge-warning">Draft</span>
            @elseif($tournament->status === 'ongoing')
                <span class="badge badge-primary">Berjalan</span>
            @else
                <span class="badge badge-success">Selesai</span>
            @endif
            <span style="color: var(--text-secondary); font-size: 0.9rem;">
                <i class="fa-solid fa-calendar"></i> {{ $tournament->start_date->format('d M Y') }} s/d {{ $tournament->end_date->format('d M Y') }}
            </span>
        </div>
    </div>
    
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('tournaments.pdf', $tournament->id) }}" class="btn btn-secondary">
            <i class="fa-solid fa-file-pdf" style="color: var(--danger);"></i> Cetak Laporan PDF
        </a>
        <a href="{{ route('tournaments.knockout', $tournament->id) }}" class="btn btn-warning">
            <i class="fa-solid fa-sitemap"></i> Bagan Knockout
        </a>
    </div>
</div>

<!-- Tab Navigation -->
<div class="tabs">
    <button class="tab-link active" onclick="openTab(event, 'standings')">
        <i class="fa-solid fa-list-ol"></i> Klasemen Grup
    </button>
    <button class="tab-link" onclick="openTab(event, 'matches')">
        <i class="fa-solid fa-calendar-days"></i> Jadwal & Hasil Pertandingan
    </button>
</div>

<!-- Tab 1: Standings -->
<div id="standings" class="tab-content active">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2>Klasemen Grup Babak Penyisihan</h2>
        @if(auth()->user()->isAdmin())
            <a href="{{ route('groups.create', $tournament->id) }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus"></i> Tambah Grup Baru
            </a>
        @endif
    </div>

    @forelse($tournament->groups as $group)
        <div style="margin-bottom: 40px; background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius); padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                <h3 style="color: var(--secondary); margin-bottom: 0;">{{ $group->name }}</h3>
                
                @if(auth()->user()->isAdmin())
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('groups.manage_teams', $group->id) }}" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-users-gear"></i> Kelola Tim ({{ $group->teams->count() }})
                        </a>
                        <form action="{{ route('groups.destroy', $group->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus grup ini beserta klasemen di dalamnya?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Grup">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="table-responsive" style="margin-bottom: 0; border: none;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 60px; text-align: center;">Pos</th>
                            <th>Nama Tim</th>
                            <th style="text-align: center;">Main (P)</th>
                            <th style="text-align: center;">Menang (W)</th>
                            <th style="text-align: center;">Seri (D)</th>
                            <th style="text-align: center;">Kalah (L)</th>
                            <th style="text-align: center;">Gol (GF)</th>
                            <th style="text-align: center;">Kebobolan (GA)</th>
                            <th style="text-align: center;">Selisih (GD)</th>
                            <th style="text-align: center; font-weight: bold; color: var(--primary);">Poin (PTS)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($group->standings as $index => $standing)
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: {{ $index < 2 ? 'var(--success)' : 'var(--text-muted)' }};">
                                    {{ $index + 1 }}
                                </td>
                                <td style="font-weight: 600; color: var(--text-primary);">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        @if($standing->team->logo)
                                            <img src="{{ asset('storage/' . $standing->team->logo) }}" alt="" class="table-logo" style="width: 24px; height: 24px;">
                                        @endif
                                        {{ $standing->team->name }}
                                    </div>
                                </td>
                                <td style="text-align: center;">{{ $standing->played }}</td>
                                <td style="text-align: center; color: var(--success);">{{ $standing->won }}</td>
                                <td style="text-align: center;">{{ $standing->drawn }}</td>
                                <td style="text-align: center; color: var(--danger);">{{ $standing->lost }}</td>
                                <td style="text-align: center;">{{ $standing->goals_for }}</td>
                                <td style="text-align: center;">{{ $standing->goals_against }}</td>
                                <td style="text-align: center; color: {{ $standing->goals_difference >= 0 ? 'var(--success)' : 'var(--danger)' }}; font-weight: 500;">
                                    {{ $standing->goals_difference > 0 ? '+' : '' }}{{ $standing->goals_difference }}
                                </td>
                                <td style="text-align: center; font-weight: 800; color: var(--primary); font-size: 1.05rem;">
                                    {{ $standing->points }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 30px; color: var(--text-muted);">
                                    Belum ada tim dimasukkan ke grup ini. Silakan kelola tim di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius); padding: 40px; text-align: center; color: var(--text-muted);">
            <i class="fa-solid fa-folder-open" style="font-size: 3rem; margin-bottom: 12px; display: block;"></i>
            Belum ada grup yang dibentuk. Buat grup di atas terlebih dahulu.
        </div>
    @endforelse
</div>

<!-- Tab 2: Matches -->
<div id="matches" class="tab-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
        <h2>Jadwal dan Hasil Pertandingan Babak Grup</h2>
        
        <div style="display: flex; gap: 10px;">
            @if(auth()->user()->isAdmin())
                <form action="{{ route('matches.generate', $tournament->id) }}" method="POST" onsubmit="return confirm('Sistem akan membuat jadwal pertandingan round-robin untuk semua tim di masing-masing grup secara otomatis. Lanjutkan?');">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        <i class="fa-solid fa-wand-magic-sparkles"></i> Jadwalkan Otomatis
                    </button>
                </form>
                <a href="{{ route('matches.create', $tournament->id) }}" class="btn btn-primary">
                    <i class="fa-solid fa-calendar-plus"></i> Tambah Jadwal Manual
                </a>
            @endif
        </div>
    </div>

    @if($groupMatches->isEmpty())
        <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius); padding: 40px; text-align: center; color: var(--text-muted);">
            <i class="fa-solid fa-calendar-xmark" style="font-size: 3rem; margin-bottom: 12px; display: block;"></i>
            Belum ada jadwal pertandingan babak grup yang terdaftar.
        </div>
    @else
        <div class="match-list">
            @foreach($groupMatches as $match)
                <div class="match-card">
                    <div class="match-card-header">
                        <span><i class="fa-solid fa-users"></i> {{ $match->group->name }}</span>
                        <span>{{ $match->match_date->format('d M Y - H:i') }} WIB</span>
                    </div>
                    
                    <div class="match-card-body">
                        <!-- Team 1 -->
                        <div class="match-team">
                            @if($match->team1->logo)
                                <img src="{{ asset('storage/' . $match->team1->logo) }}" alt="" class="match-team-logo">
                            @else
                                <div class="match-team-logo" style="display: flex; align-items: center; justify-content: center; font-weight: bold; background-color: var(--border-color); color: var(--primary);">T</div>
                            @endif
                            <span class="match-team-name">{{ $match->team1->name }}</span>
                        </div>
                        
                        <!-- VS / Score -->
                        <div class="match-vs-score">
                            @if($match->status === 'played')
                                <div class="match-score-played">
                                    <span class="match-score-num">{{ $match->team1_score }}</span>
                                    <span style="color: var(--text-muted); font-weight: bold;">-</span>
                                    <span class="match-score-num">{{ $match->team2_score }}</span>
                                </div>
                            @else
                                <span class="match-score-vs">VS</span>
                                <span class="badge badge-warning" style="font-size: 0.6rem; margin-top: 8px;">Scheduled</span>
                            @endif
                        </div>
                        
                        <!-- Team 2 -->
                        <div class="match-team">
                            @if($match->team2->logo)
                                <img src="{{ asset('storage/' . $match->team2->logo) }}" alt="" class="match-team-logo">
                            @else
                                <div class="match-team-logo" style="display: flex; align-items: center; justify-content: center; font-weight: bold; background-color: var(--border-color); color: var(--primary);">T</div>
                            @endif
                            <span class="match-team-name">{{ $match->team2->name }}</span>
                        </div>
                    </div>
                    
                    <!-- Panitia Actions -->
                    <div class="match-card-footer">
                        <a href="{{ route('matches.score', $match->id) }}" class="btn btn-warning btn-sm" style="font-size: 0.8rem; padding: 4px 10px;">
                            <i class="fa-solid fa-circle-check"></i> Input Skor
                        </a>
                        
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('matches.edit', $match->id) }}" class="btn btn-secondary btn-sm" style="padding: 4px 8px;" title="Edit Jadwal">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('matches.destroy', $match->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal pertandingan ini?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" style="padding: 4px 8px;" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Tab Switching Script -->
<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabName).style.display = "block";
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }
    
    // Set initial display
    document.getElementById('standings').style.display = "block";
    document.getElementById('matches').style.display = "none";
</script>
@endsection
