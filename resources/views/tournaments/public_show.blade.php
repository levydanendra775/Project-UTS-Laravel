<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tournament->name }} – Futsal Manager</title>
    <meta name="description" content="Lihat klasemen dan jadwal pertandingan {{ $tournament->name }} secara publik.">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: var(--bg-dark, #0f1117); min-height: 100vh; color: var(--text-primary, #e2e8f0); }

        /* ── Public Navbar ── */
        .pub-navbar {
            position: sticky; top: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 32px;
            height: 64px;
            background: rgba(15,17,23,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,.07);
        }
        .pub-navbar-brand { display: flex; align-items: center; gap: 12px; font-weight: 700; font-size: 1.1rem; color: var(--primary, #6366f1); text-decoration: none; }
        .pub-navbar-logo  { width: 36px; height: 36px; border-radius: 8px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; color: #fff; }
        .pub-navbar-links { display: flex; align-items: center; gap: 16px; }
        .pub-navbar-links a { color: var(--text-secondary, #94a3b8); text-decoration: none; font-size: 0.9rem; transition: color .2s; }
        .pub-navbar-links a:hover { color: #fff; }
        .pub-btn-login { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff !important; padding: 8px 18px; border-radius: 8px; font-weight: 600; }

        /* ── Page Container ── */
        .pub-container { max-width: 1100px; margin: 0 auto; padding: 40px 24px 80px; }

        /* ── Hero ── */
        .pub-hero {
            display: flex; align-items: center; gap: 24px; flex-wrap: wrap;
            background: linear-gradient(135deg, rgba(99,102,241,.15), rgba(139,92,246,.1));
            border: 1px solid rgba(99,102,241,.3);
            border-radius: 16px; padding: 28px 32px; margin-bottom: 36px;
        }
        .pub-hero-icon { width: 72px; height: 72px; border-radius: 14px; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0; }
        .pub-hero-info h1 { font-size: 1.8rem; font-weight: 800; margin: 0 0 8px; }
        .pub-hero-meta  { display: flex; gap: 14px; flex-wrap: wrap; align-items: center; }
        .pub-hero-links { margin-left: auto; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn-pub-outline { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; border-radius: 8px; border: 1px solid rgba(99,102,241,.5); color: var(--primary, #6366f1); text-decoration: none; font-weight: 600; font-size: 0.85rem; transition: all .2s; }
        .btn-pub-outline:hover { background: rgba(99,102,241,.15); border-color: #6366f1; }
        .btn-pub-primary { background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; border: none; }
        .btn-pub-primary:hover { opacity: .9; }

        /* ── Tabs ── */
        .pub-tabs { display: flex; gap: 6px; border-bottom: 2px solid rgba(255,255,255,.08); margin-bottom: 28px; }
        .pub-tab-btn { background: none; border: none; cursor: pointer; padding: 12px 22px; font-size: 0.95rem; font-weight: 600; color: var(--text-muted, #64748b); border-bottom: 2px solid transparent; margin-bottom: -2px; transition: all .2s; }
        .pub-tab-btn.active { color: var(--primary, #6366f1); border-bottom-color: var(--primary, #6366f1); }

        /* ── Group Card ── */
        .group-card { background: rgba(30,32,44,.6); border: 1px solid rgba(255,255,255,.08); border-radius: 14px; padding: 24px; margin-bottom: 28px; }
        .group-card h3 { font-size: 1.05rem; font-weight: 700; color: #a78bfa; margin: 0 0 18px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,.08); }

        /* ── Standings Table ── */
        .pub-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .pub-table th { text-align: center; padding: 10px 8px; font-weight: 600; color: var(--text-muted, #64748b); font-size: 0.78rem; text-transform: uppercase; letter-spacing: .05em; }
        .pub-table th:nth-child(2) { text-align: left; }
        .pub-table td { text-align: center; padding: 10px 8px; border-bottom: 1px solid rgba(255,255,255,.05); }
        .pub-table td:nth-child(2) { text-align: left; font-weight: 600; }
        .pub-table tr:hover td { background: rgba(99,102,241,.05); }
        .pub-table tr:last-child td { border-bottom: none; }
        .pos-qualify { font-weight: 800; color: #22c55e; }
        .pos-out     { font-weight: 700; color: #64748b; }
        .pts-cell    { font-weight: 800; color: #6366f1; font-size: 1rem; }
        .team-cell   { display: flex; align-items: center; gap: 10px; }
        .gd-pos { color: #22c55e; } .gd-neg { color: #ef4444; }

        /* ── Match Cards ── */
        .pub-match-grid { display: grid; gap: 16px; }
        .pub-match-card { background: rgba(30,32,44,.6); border: 1px solid rgba(255,255,255,.08); border-radius: 12px; overflow: hidden; }
        .pub-match-head { padding: 8px 16px; background: rgba(255,255,255,.03); border-bottom: 1px solid rgba(255,255,255,.06); display: flex; justify-content: space-between; align-items: center; font-size: 0.78rem; color: var(--text-muted); }
        .pub-match-body { padding: 20px 24px; display: flex; align-items: center; gap: 16px; }
        .pub-match-team { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 10px; text-align: center; }
        .pub-match-team-logo { width: 52px; height: 52px; border-radius: 50%; background: rgba(99,102,241,.2); display: flex; align-items: center; justify-content: center; font-weight: 800; color: #6366f1; font-size: 1.2rem; object-fit: cover; }
        .pub-match-team-name { font-weight: 700; font-size: 0.95rem; }
        .pub-match-vs { width: 80px; text-align: center; flex-shrink: 0; }
        .vs-text   { font-size: 1.1rem; font-weight: 800; color: #64748b; }
        .score-display { display: flex; align-items: center; gap: 6px; justify-content: center; }
        .score-num { font-size: 1.8rem; font-weight: 900; color: #e2e8f0; }
        .score-sep { font-size: 1.4rem; font-weight: 300; color: #64748b; }
        .badge-pub { font-size: 0.65rem; font-weight: 700; padding: 3px 8px; border-radius: 99px; }
        .badge-played    { background: rgba(34,197,94,.15); color: #22c55e; border: 1px solid rgba(34,197,94,.3); }
        .badge-scheduled { background: rgba(234,179,8,.15);  color: #eab308; border: 1px solid rgba(234,179,8,.3); }

        /* ── Tab Content ── */
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            .pub-hero { flex-direction: column; padding: 20px; }
            .pub-hero-links { margin-left: 0; }
            .pub-navbar { padding: 0 16px; }
        }
    </style>
</head>
<body>
    <!-- Public Navbar -->
    <nav class="pub-navbar">
        <a href="{{ route('landing') }}" class="pub-navbar-brand">
            <div class="pub-navbar-logo">F</div>
            FUTSAL MANAGER
        </a>
        <div class="pub-navbar-links">
            <a href="{{ route('landing') }}"><i class="fa-solid fa-home"></i> Beranda</a>
            <a href="{{ route('login') }}" class="pub-btn-login"><i class="fa-solid fa-sign-in-alt"></i> Login</a>
        </div>
    </nav>

    <div class="pub-container">
        <!-- Hero -->
        <div class="pub-hero">
            <div class="pub-hero-icon">🏆</div>
            <div class="pub-hero-info">
                <h1>{{ $tournament->name }}</h1>
                <div class="pub-hero-meta">
                    @if($tournament->status === 'ongoing')
                        <span class="badge badge-primary">🔴 Sedang Berlangsung</span>
                    @elseif($tournament->status === 'draft')
                        <span class="badge badge-warning">Draft</span>
                    @else
                        <span class="badge badge-success">✅ Selesai</span>
                    @endif
                    <span style="color:#94a3b8; font-size:.9rem;">
                        <i class="fa-solid fa-calendar"></i>
                        {{ $tournament->start_date->format('d M Y') }} – {{ $tournament->end_date->format('d M Y') }}
                    </span>
                    @if($tournament->location)
                        <span style="color:#94a3b8; font-size:.9rem;"><i class="fa-solid fa-location-dot"></i> {{ $tournament->location }}</span>
                    @endif
                </div>
            </div>
            <div class="pub-hero-links">
                <a href="{{ route('public.tournaments.knockout', $tournament->id) }}" class="btn-pub-outline btn-pub-primary">
                    <i class="fa-solid fa-sitemap"></i> Bagan Knockout
                </a>
            </div>
        </div>

        <!-- Tabs -->
        <div class="pub-tabs">
            <button class="pub-tab-btn active" onclick="switchTab(event, 'tab-standings')">
                <i class="fa-solid fa-list-ol"></i> Klasemen Grup
            </button>
            <button class="pub-tab-btn" onclick="switchTab(event, 'tab-matches')">
                <i class="fa-solid fa-calendar-days"></i> Jadwal & Hasil
            </button>
        </div>

        <!-- Tab: Standings -->
        <div id="tab-standings" class="tab-pane active">
            @forelse($tournament->groups as $group)
                <div class="group-card">
                    <h3><i class="fa-solid fa-layer-group"></i> {{ $group->name }}</h3>
                    <div style="overflow-x:auto;">
                        <table class="pub-table">
                            <thead>
                                <tr>
                                    <th style="width:44px;">Pos</th>
                                    <th style="text-align:left;">Tim</th>
                                    <th title="Main">P</th>
                                    <th title="Menang" style="color:#22c55e;">W</th>
                                    <th title="Seri">D</th>
                                    <th title="Kalah" style="color:#ef4444;">L</th>
                                    <th title="Gol Masuk">GF</th>
                                    <th title="Gol Kemasukan">GA</th>
                                    <th title="Selisih Gol">GD</th>
                                    <th title="Poin" style="color:#6366f1;">PTS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($group->standings as $idx => $st)
                                    <tr>
                                        <td>
                                            <span class="{{ $idx < 2 ? 'pos-qualify' : 'pos-out' }}">{{ $idx + 1 }}</span>
                                        </td>
                                        <td>
                                            <div class="team-cell">
                                                @if($st->team->logo)
                                                    <img src="{{ asset('storage/'.$st->team->logo) }}" style="width:24px;height:24px;border-radius:50%;object-fit:cover;" alt="">
                                                @endif
                                                {{ $st->team->name }}
                                            </div>
                                        </td>
                                        <td>{{ $st->played }}</td>
                                        <td style="color:#22c55e;font-weight:700;">{{ $st->won }}</td>
                                        <td>{{ $st->drawn }}</td>
                                        <td style="color:#ef4444;">{{ $st->lost }}</td>
                                        <td>{{ $st->goals_for }}</td>
                                        <td>{{ $st->goals_against }}</td>
                                        <td class="{{ $st->goals_difference >= 0 ? 'gd-pos' : 'gd-neg' }}">
                                            {{ $st->goals_difference > 0 ? '+' : '' }}{{ $st->goals_difference }}
                                        </td>
                                        <td class="pts-cell">{{ $st->points }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" style="text-align:center;padding:24px;color:#64748b;">Belum ada data klasemen.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($group->standings->count() > 0)
                        <p style="font-size:.78rem;color:#64748b;margin:10px 0 0;"><span style="color:#22c55e;">■</span> 2 tim teratas lolos ke babak berikutnya</p>
                    @endif
                </div>
            @empty
                <div style="text-align:center;padding:60px;color:#64748b;">
                    <i class="fa-solid fa-folder-open" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
                    Belum ada grup yang terbentuk.
                </div>
            @endforelse
        </div>

        <!-- Tab: Matches -->
        <div id="tab-matches" class="tab-pane">
            @if($groupMatches->isEmpty())
                <div style="text-align:center;padding:60px;color:#64748b;">
                    <i class="fa-solid fa-calendar-xmark" style="font-size:3rem;display:block;margin-bottom:12px;"></i>
                    Belum ada jadwal pertandingan.
                </div>
            @else
                <div class="pub-match-grid">
                    @foreach($groupMatches as $match)
                        <div class="pub-match-card">
                            <div class="pub-match-head">
                                <span><i class="fa-solid fa-users"></i> {{ $match->group?->name ?? 'Babak Grup' }}</span>
                                <span>{{ $match->match_date->format('d M Y – H:i') }} WIB</span>
                            </div>
                            <div class="pub-match-body">
                                <div class="pub-match-team">
                                    @if($match->team1->logo)
                                        <img src="{{ asset('storage/'.$match->team1->logo) }}" class="pub-match-team-logo" alt="">
                                    @else
                                        <div class="pub-match-team-logo">{{ strtoupper(substr($match->team1->name, 0, 1)) }}</div>
                                    @endif
                                    <span class="pub-match-team-name">{{ $match->team1->name }}</span>
                                </div>

                                <div class="pub-match-vs">
                                    @if($match->status === 'played')
                                        <div class="score-display">
                                            <span class="score-num">{{ $match->team1_score }}</span>
                                            <span class="score-sep">–</span>
                                            <span class="score-num">{{ $match->team2_score }}</span>
                                        </div>
                                        <div style="margin-top:6px;text-align:center;"><span class="badge-pub badge-played">Selesai</span></div>
                                    @else
                                        <div class="vs-text">VS</div>
                                        <div style="margin-top:6px;text-align:center;"><span class="badge-pub badge-scheduled">Terjadwal</span></div>
                                    @endif
                                </div>

                                <div class="pub-match-team">
                                    @if($match->team2->logo)
                                        <img src="{{ asset('storage/'.$match->team2->logo) }}" class="pub-match-team-logo" alt="">
                                    @else
                                        <div class="pub-match-team-logo">{{ strtoupper(substr($match->team2->name, 0, 1)) }}</div>
                                    @endif
                                    <span class="pub-match-team-name">{{ $match->team2->name }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function switchTab(evt, tabId) {
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.pub-tab-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(tabId).classList.add('active');
            evt.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>
