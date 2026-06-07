<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bagan Knockout - {{ $tournament->name }}</title>
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

        .pub-container { max-width: 1200px; margin: 0 auto; padding: 40px 24px 80px; }

        /* ── Bracket Layout ── */
        .bracket-wrapper {
            display: flex; justify-content: space-between; align-items: center;
            gap: 20px; overflow-x: auto; padding: 40px 10px;
            min-width: 900px;
        }
        .round-column {
            flex: 1; display: flex; flex-direction: column; justify-content: space-around;
            min-height: 520px; gap: 20px;
        }
        .round-title {
            text-align: center; font-size: 0.95rem; font-weight: 800;
            color: #a78bfa; margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.05em;
        }

        .bracket-match {
            background: rgba(30, 32, 44, 0.75); border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px; width: 100%; max-width: 260px; margin: 0 auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); transition: border-color 0.2s;
        }
        .bracket-match:hover { border-color: rgba(99, 102, 241, 0.4); }

        .bracket-team {
            display: flex; align-items: center; justify-content: space-between;
            padding: 12px 16px; border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .bracket-team:last-child { border-bottom: none; }
        .bracket-team.winner { background: rgba(34, 197, 94, 0.05); }

        .team-info { display: flex; align-items: center; gap: 10px; font-size: 0.88rem; font-weight: 600; color: #cbd5e1; }
        .team-info img { width: 22px; height: 22px; border-radius: 50%; object-fit: cover; }
        .team-info.winner-text { color: #fff; }
        
        .score-box {
            font-size: 1.05rem; font-weight: 900; color: #94a3b8;
            width: 28px; text-align: center;
        }
        .score-box.winner-score { color: #22c55e; }

        .match-meta {
            font-size: 0.72rem; color: #64748b; padding: 6px 16px;
            background: rgba(0, 0, 0, 0.15); display: flex; justify-content: space-between;
            border-top: 1px solid rgba(255, 255, 255, 0.03); border-radius: 0 0 12px 12px;
        }

        .empty-slot {
            display: flex; align-items: center; justify-content: center; height: 78px;
            border: 1px dashed rgba(255, 255, 255, 0.15); border-radius: 12px;
            color: #475569; font-size: 0.85rem; font-weight: 500; font-style: italic;
        }

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
                <h1>Bagan Babak Gugur (Knockout)</h1>
                <div class="pub-hero-meta">
                    <span style="color:#a78bfa; font-weight:700;">{{ $tournament->name }}</span>
                    <span style="color:#94a3b8; font-size:.9rem;">
                        <i class="fa-solid fa-calendar"></i>
                        {{ $tournament->start_date->format('d M Y') }} – {{ $tournament->end_date->format('d M Y') }}
                    </span>
                </div>
            </div>
            <div class="pub-hero-links">
                <a href="{{ route('public.tournaments.show', $tournament->id) }}" class="btn-pub-outline">
                    <i class="fa-solid fa-arrow-left"></i> Lihat Klasemen & Jadwal Grup
                </a>
            </div>
        </div>

        <div style="background: rgba(30,32,44,.4); border: 1px solid rgba(255,255,255,.05); border-radius: 16px; padding: 20px; overflow-x: auto;">
            <div class="bracket-wrapper">
                
                <!-- Column 1: Quarterfinals -->
                <div class="round-column">
                    <div><h3 class="round-title">Perempat Final</h3></div>
                    @for($i = 0; $i < 4; $i++)
                        @php $m = $quarterfinals->values()->get($i); @endphp
                        @if($m)
                            <div class="bracket-match">
                                <div class="bracket-team {{ $m->status === 'played' && $m->winner_id === $m->team1_id ? 'winner' : '' }}">
                                    <div class="team-info {{ $m->status === 'played' && $m->winner_id === $m->team1_id ? 'winner-text' : '' }}">
                                        @if($m->team1->logo)
                                            <img src="{{ asset('storage/' . $m->team1->logo) }}" alt="">
                                        @endif
                                        <span>{{ $m->team1->name }}</span>
                                    </div>
                                    <div class="score-box {{ $m->status === 'played' && $m->winner_id === $m->team1_id ? 'winner-score' : '' }}">
                                        {{ $m->status === 'played' ? $m->team1_score : '-' }}
                                    </div>
                                </div>
                                <div class="bracket-team {{ $m->status === 'played' && $m->winner_id === $m->team2_id ? 'winner' : '' }}">
                                    <div class="team-info {{ $m->status === 'played' && $m->winner_id === $m->team2_id ? 'winner-text' : '' }}">
                                        @if($m->team2->logo)
                                            <img src="{{ asset('storage/' . $m->team2->logo) }}" alt="">
                                        @endif
                                        <span>{{ $m->team2->name }}</span>
                                    </div>
                                    <div class="score-box {{ $m->status === 'played' && $m->winner_id === $m->team2_id ? 'winner-score' : '' }}">
                                        {{ $m->status === 'played' ? $m->team2_score : '-' }}
                                    </div>
                                </div>
                                <div class="match-meta">
                                    <span>QF {{ $i + 1 }}</span>
                                    <span>{{ $m->match_date->format('d/m H:i') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="empty-slot" style="max-width: 260px; width: 100%;">
                                Slot Perempat Final
                            </div>
                        @endif
                    @endfor
                </div>

                <!-- Column 2: Semifinals -->
                <div class="round-column">
                    <div><h3 class="round-title">Semifinal</h3></div>
                    @for($i = 0; $i < 2; $i++)
                        @php $m = $semifinals->values()->get($i); @endphp
                        @if($m)
                            <div class="bracket-match">
                                <div class="bracket-team {{ $m->status === 'played' && $m->winner_id === $m->team1_id ? 'winner' : '' }}">
                                    <div class="team-info {{ $m->status === 'played' && $m->winner_id === $m->team1_id ? 'winner-text' : '' }}">
                                        @if($m->team1->logo)
                                            <img src="{{ asset('storage/' . $m->team1->logo) }}" alt="">
                                        @endif
                                        <span>{{ $m->team1->name }}</span>
                                    </div>
                                    <div class="score-box {{ $m->status === 'played' && $m->winner_id === $m->team1_id ? 'winner-score' : '' }}">
                                        {{ $m->status === 'played' ? $m->team1_score : '-' }}
                                    </div>
                                </div>
                                <div class="bracket-team {{ $m->status === 'played' && $m->winner_id === $m->team2_id ? 'winner' : '' }}">
                                    <div class="team-info {{ $m->status === 'played' && $m->winner_id === $m->team2_id ? 'winner-text' : '' }}">
                                        @if($m->team2->logo)
                                            <img src="{{ asset('storage/' . $m->team2->logo) }}" alt="">
                                        @endif
                                        <span>{{ $m->team2->name }}</span>
                                    </div>
                                    <div class="score-box {{ $m->status === 'played' && $m->winner_id === $m->team2_id ? 'winner-score' : '' }}">
                                        {{ $m->status === 'played' ? $m->team2_score : '-' }}
                                    </div>
                                </div>
                                <div class="match-meta">
                                    <span>SF {{ $i + 1 }}</span>
                                    <span>{{ $m->match_date->format('d/m H:i') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="empty-slot" style="max-width: 260px; width: 100%;">
                                Slot Semifinal
                            </div>
                        @endif
                    @endfor
                </div>

                <!-- Column 3: Final -->
                <div class="round-column">
                    <div><h3 class="round-title">Final</h3></div>
                    @if($final)
                        <div class="bracket-match" style="border-color: rgba(167, 139, 250, 0.4); box-shadow: 0 0 25px rgba(167, 139, 250, 0.15);">
                            <div class="bracket-team {{ $final->status === 'played' && $final->winner_id === $final->team1_id ? 'winner' : '' }}">
                                <div class="team-info {{ $final->status === 'played' && $final->winner_id === $final->team1_id ? 'winner-text' : '' }}">
                                    @if($final->team1->logo)
                                        <img src="{{ asset('storage/' . $final->team1->logo) }}" alt="">
                                    @endif
                                    <span>{{ $final->team1->name }}</span>
                                </div>
                                <div class="score-box {{ $final->status === 'played' && $final->winner_id === $final->team1_id ? 'winner-score' : '' }}">
                                    {{ $final->status === 'played' ? $final->team1_score : '-' }}
                                </div>
                            </div>
                            <div class="bracket-team {{ $final->status === 'played' && $final->winner_id === $final->team2_id ? 'winner' : '' }}">
                                <div class="team-info {{ $final->status === 'played' && $final->winner_id === $final->team2_id ? 'winner-text' : '' }}">
                                    @if($final->team2->logo)
                                        <img src="{{ asset('storage/' . $final->team2->logo) }}" alt="">
                                    @endif
                                    <span>{{ $final->team2->name }}</span>
                                </div>
                                <div class="score-box {{ $final->status === 'played' && $final->winner_id === $final->team2_id ? 'winner-score' : '' }}">
                                    {{ $final->status === 'played' ? $final->team2_score : '-' }}
                                </div>
                            </div>
                            <div class="match-meta" style="background: rgba(167, 139, 250, 0.08);">
                                <span style="color: #a78bfa; font-weight: bold;"><i class="fa-solid fa-crown"></i> Perebutan Juara</span>
                                <span>{{ $final->match_date->format('d/m H:i') }}</span>
                            </div>
                        </div>
                    @else
                        <div class="empty-slot" style="max-width: 260px; width: 100%; height: 90px; border-color: rgba(167, 139, 250, 0.3);">
                            Slot Final
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</body>
</html>
