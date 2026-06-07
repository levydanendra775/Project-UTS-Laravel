@extends('layouts.app')

@section('title', 'Bagan Gugur - ' . $tournament->name)

@section('content')
<div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
    <div>
        <a href="{{ route('tournaments.show', $tournament->id) }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Turnamen</a>
        <h1 style="margin-top: 12px;">Bagan Gugur (Knockout Stage)</h1>
        <p style="color: var(--text-secondary);">Turnamen: {{ $tournament->name }}</p>
    </div>
    
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('tournaments.pdf', $tournament->id) }}" class="btn btn-secondary">
            <i class="fa-solid fa-file-pdf" style="color: var(--danger);"></i> Cetak Laporan PDF
        </a>
    </div>
</div>

@if($quarterfinals->isEmpty() && $semifinals->isEmpty() && !$final)
    <!-- Initialization Screen (If knockout not yet started) -->
    @if(auth()->user()->isAdmin())
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 32px; margin-top: 20px;">
            <!-- Start from Quarterfinals (8 Teams) -->
            <div class="card" style="margin: 0; max-width: 100%;">
                <h3 style="color: var(--primary); margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                    <i class="fa-solid fa-sitemap"></i> Mulai Babak Perempat Final (8 Tim)
                </h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 20px;">
                    Pilih 8 tim yang lolos dari Babak Grup untuk diacak ke dalam jadwal tanding Perempat Final.
                </p>
                
                <form action="{{ route('knockout.initialize_quarterfinals', $tournament->id) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Pilih 8 Tim:</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; max-height: 250px; overflow-y: auto; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-sidebar);">
                            @foreach($teams as $team)
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="teams[]" value="{{ $team->id }}" id="q_team_{{ $team->id }}" class="q-team-checkbox" style="width: 16px; height: 16px; accent-color: var(--primary);">
                                    <label for="q_team_{{ $team->id }}" style="font-size: 0.85rem; cursor: pointer; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ $team->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        <span id="q-count-warning" style="font-size: 0.8rem; color: var(--secondary); margin-top: 6px; display: block;">Terpilih: 0 / 8 Tim</span>
                    </div>

                    <div class="form-group">
                        <label for="q_match_date" class="form-label">Tanggal Mulai Kick-Off</label>
                        <input type="datetime-local" name="match_date" id="q_match_date" class="form-control" required>
                    </div>

                    <button type="submit" id="q-submit-btn" class="btn btn-primary" style="width: 100%; justify-content: center;" disabled>
                        <i class="fa-solid fa-circle-play"></i> Inisialisasi Perempat Final
                    </button>
                </form>
            </div>

            <!-- Start from Semifinals (4 Teams) -->
            <div class="card" style="margin: 0; max-width: 100%;">
                <h3 style="color: var(--secondary); margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
                    <i class="fa-solid fa-diagram-predecessor"></i> Mulai Babak Semifinal Langsung (4 Tim)
                </h3>
                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 20px;">
                    Pilih 4 tim (jika tidak menggunakan babak Perempat Final) untuk langsung diacak ke Semifinal.
                </p>
                
                <form action="{{ route('knockout.initialize_semifinals', $tournament->id) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Pilih 4 Tim:</label>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; max-height: 250px; overflow-y: auto; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--bg-sidebar);">
                            @foreach($teams as $team)
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <input type="checkbox" name="teams[]" value="{{ $team->id }}" id="s_team_{{ $team->id }}" class="s-team-checkbox" style="width: 16px; height: 16px; accent-color: var(--secondary);">
                                    <label for="s_team_{{ $team->id }}" style="font-size: 0.85rem; cursor: pointer; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ $team->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        <span id="s-count-warning" style="font-size: 0.8rem; color: var(--secondary); margin-top: 6px; display: block;">Terpilih: 0 / 4 Tim</span>
                    </div>

                    <div class="form-group">
                        <label for="s_match_date" class="form-label">Tanggal Mulai Kick-Off</label>
                        <input type="datetime-local" name="match_date" id="s_match_date" class="form-control" required>
                    </div>

                    <button type="submit" id="s-submit-btn" class="btn btn-warning" style="width: 100%; justify-content: center; color: var(--bg-main);" disabled>
                        <i class="fa-solid fa-circle-play"></i> Inisialisasi Semifinal
                    </button>
                </form>
            </div>
        </div>

        <script>
            // Validation script for checkboxes
            document.querySelectorAll('.q-team-checkbox').forEach(item => {
                item.addEventListener('change', event => {
                    var selected = document.querySelectorAll('.q-team-checkbox:checked').length;
                    document.getElementById('q-count-warning').innerText = 'Terpilih: ' + selected + ' / 8 Tim';
                    
                    if (selected === 8) {
                        document.getElementById('q-submit-btn').removeAttribute('disabled');
                        document.getElementById('q-count-warning').style.color = 'var(--success)';
                    } else {
                        document.getElementById('q-submit-btn').setAttribute('disabled', 'disabled');
                        document.getElementById('q-count-warning').style.color = 'var(--secondary)';
                    }
                });
            });

            document.querySelectorAll('.s-team-checkbox').forEach(item => {
                item.addEventListener('change', event => {
                    var selected = document.querySelectorAll('.s-team-checkbox:checked').length;
                    document.getElementById('s-count-warning').innerText = 'Terpilih: ' + selected + ' / 4 Tim';
                    
                    if (selected === 4) {
                        document.getElementById('s-submit-btn').removeAttribute('disabled');
                        document.getElementById('s-count-warning').style.color = 'var(--success)';
                    } else {
                        document.getElementById('s-submit-btn').setAttribute('disabled', 'disabled');
                        document.getElementById('s-count-warning').style.color = 'var(--secondary)';
                    }
                });
            });
        </script>
    @else
        <div style="background-color: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius); padding: 40px; text-align: center; color: var(--text-muted); margin-top: 20px;">
            <i class="fa-solid fa-sitemap" style="font-size: 3rem; margin-bottom: 12px; display: block;"></i>
            Babak gugur belum diinisialisasi oleh administrator.
        </div>
    @endif
@else
    <!-- Bracket Visualizer Screen -->
    @if(auth()->user()->isAdmin())
        <!-- Reset Button for Admin -->
        <div style="margin-bottom: 24px; text-align: right;">
            <form action="{{ route('knockout.initialize_quarterfinals', $tournament->id) }}" method="POST" onsubmit="return confirm('PERINGATAN! Melakukan reset akan menghapus semua jadwal dan skor babak knockout yang ada saat ini. Lanjutkan?');" style="display: inline-block;">
                @csrf
                <input type="hidden" name="match_date" value="{{ now()->format('Y-m-d\TH:i') }}">
                @foreach($quarterfinals->count() > 0 ? $quarterfinals : $semifinals as $m)
                    <input type="hidden" name="teams[]" value="{{ $m->team1_id }}">
                    <input type="hidden" name="teams[]" value="{{ $m->team2_id }}">
                @endforeach
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-rotate"></i> Reset & Acak Ulang Bagan
                </button>
            </form>
        </div>
    @endif

    <div class="bracket-wrapper">
        <div class="bracket-container">
            
            <!-- 1. Round: Quarterfinals -->
            @if($quarterfinals->isNotEmpty())
                <div class="bracket-round">
                    <div class="bracket-round-title">Perempat Final</div>
                    <div class="bracket-matches">
                        @foreach($quarterfinals as $q)
                            <div class="bracket-match">
                                <!-- Team 1 -->
                                <div class="bracket-match-team {{ $q->status === 'played' && $q->winner_id === $q->team1_id ? 'winner' : '' }}">
                                    <div class="bracket-team-name-logo">
                                        @if($q->team1->logo)
                                            <img src="{{ asset('storage/' . $q->team1->logo) }}" class="bracket-team-logo" alt="">
                                        @endif
                                        <span>{{ $q->team1->name }}</span>
                                    </div>
                                    <span class="bracket-score">{{ $q->status === 'played' ? $q->team1_score : '-' }}</span>
                                </div>
                                
                                <!-- Team 2 -->
                                <div class="bracket-match-team {{ $q->status === 'played' && $q->winner_id === $q->team2_id ? 'winner' : '' }}">
                                    <div class="bracket-team-name-logo">
                                        @if($q->team2->logo)
                                            <img src="{{ asset('storage/' . $q->team2->logo) }}" class="bracket-team-logo" alt="">
                                        @endif
                                        <span>{{ $q->team2->name }}</span>
                                    </div>
                                    <span class="bracket-score">{{ $q->status === 'played' ? $q->team2_score : '-' }}</span>
                                </div>
                                
                                <!-- Match Info & Action -->
                                <div class="bracket-match-info">
                                    <span>{{ $q->match_date->format('d/m H:i') }}</span>
                                    <a href="{{ route('matches.score', $q->id) }}" style="color: var(--secondary); font-weight: 600;" title="Input Hasil">
                                        <i class="fa-solid fa-square-poll-horizontal"></i> Skor
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 2. Round: Semifinals -->
            <div class="bracket-round">
                <div class="bracket-round-title">Semifinal</div>
                <div class="bracket-matches">
                    @if($semifinals->isEmpty())
                        <!-- Placeholder indicating it will be generated -->
                        <div style="color: var(--text-muted); font-size: 0.85rem; border: 1px dashed var(--border-color); border-radius: 8px; padding: 20px; text-align: center; width: 260px;">
                            Menunggu pemenang Perempat Final
                        </div>
                        <div style="color: var(--text-muted); font-size: 0.85rem; border: 1px dashed var(--border-color); border-radius: 8px; padding: 20px; text-align: center; width: 260px; margin-top: 40px;">
                            Menunggu pemenang Perempat Final
                        </div>
                    @else
                        @foreach($semifinals as $s)
                            <div class="bracket-match">
                                <!-- Team 1 -->
                                <div class="bracket-match-team {{ $s->status === 'played' && $s->winner_id === $s->team1_id ? 'winner' : '' }}">
                                    <div class="bracket-team-name-logo">
                                        @if($s->team1->logo)
                                            <img src="{{ asset('storage/' . $s->team1->logo) }}" class="bracket-team-logo" alt="">
                                        @endif
                                        <span>{{ $s->team1->name }}</span>
                                    </div>
                                    <span class="bracket-score">{{ $s->status === 'played' ? $s->team1_score : '-' }}</span>
                                </div>
                                
                                <!-- Team 2 -->
                                <div class="bracket-match-team {{ $s->status === 'played' && $s->winner_id === $s->team2_id ? 'winner' : '' }}">
                                    <div class="bracket-team-name-logo">
                                        @if($s->team2->logo)
                                            <img src="{{ asset('storage/' . $s->team2->logo) }}" class="bracket-team-logo" alt="">
                                        @endif
                                        <span>{{ $s->team2->name }}</span>
                                    </div>
                                    <span class="bracket-score">{{ $s->status === 'played' ? $s->team2_score : '-' }}</span>
                                </div>
                                
                                <!-- Match Info & Action -->
                                <div class="bracket-match-info">
                                    <span>{{ $s->match_date->format('d/m H:i') }}</span>
                                    <a href="{{ route('matches.score', $s->id) }}" style="color: var(--secondary); font-weight: 600;" title="Input Hasil">
                                        <i class="fa-solid fa-square-poll-horizontal"></i> Skor
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- 3. Round: Final -->
            <div class="bracket-round">
                <div class="bracket-round-title">Final</div>
                <div class="bracket-matches">
                    @if(!$final)
                        <div style="color: var(--text-muted); font-size: 0.85rem; border: 1px dashed var(--border-color); border-radius: 8px; padding: 20px; text-align: center; width: 260px;">
                            Menunggu pemenang Semifinal
                        </div>
                    @else
                        <div class="bracket-match" style="border: 2px solid var(--secondary);">
                            <!-- Team 1 -->
                            <div class="bracket-match-team {{ $final->status === 'played' && $final->winner_id === $final->team1_id ? 'winner' : '' }}">
                                <div class="bracket-team-name-logo">
                                    @if($final->team1->logo)
                                        <img src="{{ asset('storage/' . $final->team1->logo) }}" class="bracket-team-logo" alt="">
                                    @endif
                                    <span style="font-weight: bold;">{{ $final->team1->name }}</span>
                                </div>
                                <span class="bracket-score" style="color: var(--secondary); font-weight: 800;">{{ $final->status === 'played' ? $final->team1_score : '-' }}</span>
                            </div>
                            
                            <!-- Team 2 -->
                            <div class="bracket-match-team {{ $final->status === 'played' && $final->winner_id === $final->team2_id ? 'winner' : '' }}">
                                <div class="bracket-team-name-logo">
                                    @if($final->team2->logo)
                                        <img src="{{ asset('storage/' . $final->team2->logo) }}" class="bracket-team-logo" alt="">
                                    @endif
                                    <span style="font-weight: bold;">{{ $final->team2->name }}</span>
                                </div>
                                <span class="bracket-score" style="color: var(--secondary); font-weight: 800;">{{ $final->status === 'played' ? $final->team2_score : '-' }}</span>
                            </div>
                            
                            <!-- Match Info & Action -->
                            <div class="bracket-match-info" style="background-color: var(--border-color);">
                                <span style="font-weight: 700; color: var(--secondary);"><i class="fa-solid fa-crown"></i> Juara</span>
                                <a href="{{ route('matches.score', $final->id) }}" style="color: var(--primary); font-weight: bold;" title="Input Hasil">
                                    <i class="fa-solid fa-square-poll-horizontal"></i> Skor
                                </a>
                            </div>
                        </div>
                        
                        <!-- Champion Announcement Box -->
                        @if($final->status === 'played' && $final->winner)
                            <div style="background: linear-gradient(135deg, rgba(251, 191, 36, 0.1), rgba(6, 182, 212, 0.1)); border: 1px solid var(--secondary); border-radius: var(--border-radius); padding: 16px; text-align: center; margin-top: 20px; width: 260px;">
                                <i class="fa-solid fa-trophy" style="font-size: 2.5rem; color: var(--secondary); margin-bottom: 8px; display: block; animation: bounce 2s infinite;"></i>
                                <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; display: block;">Juara Turnamen</span>
                                <span style="font-size: 1.1rem; font-weight: 800; color: var(--text-primary);">{{ $final->winner->name }}</span>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>
    </div>
@endif

<style>
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
</style>
@endsection
