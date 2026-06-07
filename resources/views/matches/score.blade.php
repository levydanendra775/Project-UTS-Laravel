@extends('layouts.app')

@section('title', 'Input Skor Pertandingan')

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('tournaments.show', $match->tournament_id) }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Turnamen</a>
    <h1 style="margin-top: 12px;">Input Hasil Pertandingan</h1>
    <p style="color: var(--text-secondary);">Turnamen: {{ $match->tournament->name }}</p>
</div>

<div class="card" style="max-width: 700px; margin: 0;">
    <h3 style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; text-align: center;">
        <i class="fa-solid fa-circle-check" style="color: var(--success);"></i> Hasil Akhir Pertandingan
    </h3>

    <form action="{{ route('matches.store_score', $match->id) }}" method="POST" onsubmit="return validateForm();">
        @csrf

        <div style="display: flex; justify-content: space-around; align-items: center; gap: 20px; margin-bottom: 30px;">
            <!-- Team 1 Input -->
            <div style="text-align: center; width: 40%;">
                @if($match->team1->logo)
                    <img src="{{ asset('storage/' . $match->team1->logo) }}" alt="" style="width: 64px; height: 64px; object-fit: cover; border-radius: 8px; margin-bottom: 12px; border: 1px solid var(--border-color);">
                @else
                    <div style="width: 64px; height: 64px; border-radius: 8px; background-color: var(--border-color); display: flex; align-items: center; justify-content: center; font-weight: bold; margin: 0 auto 12px; color: var(--primary);">T1</div>
                @endif
                <label for="team1_score" class="form-label" style="font-weight: 700; font-size: 1.1rem; display: block; margin-bottom: 10px;">
                    {{ $match->team1->name }}
                </label>
                <input type="number" name="team1_score" id="team1_score" class="form-control" style="text-align: center; font-size: 2rem; font-weight: 800; height: 70px;" placeholder="0" min="0" value="{{ old('team1_score', $match->team1_score) }}" required oninput="checkScores()">
                @error('team1_score')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- VS -->
            <div style="font-size: 1.5rem; font-weight: 800; color: var(--text-muted);">
                :
            </div>

            <!-- Team 2 Input -->
            <div style="text-align: center; width: 40%;">
                @if($match->team2->logo)
                    <img src="{{ asset('storage/' . $match->team2->logo) }}" alt="" style="width: 64px; height: 64px; object-fit: cover; border-radius: 8px; margin-bottom: 12px; border: 1px solid var(--border-color);">
                @else
                    <div style="width: 64px; height: 64px; border-radius: 8px; background-color: var(--border-color); display: flex; align-items: center; justify-content: center; font-weight: bold; margin: 0 auto 12px; color: var(--primary);">T2</div>
                @endif
                <label for="team2_score" class="form-label" style="font-weight: 700; font-size: 1.1rem; display: block; margin-bottom: 10px;">
                    {{ $match->team2->name }}
                </label>
                <input type="number" name="team2_score" id="team2_score" class="form-control" style="text-align: center; font-size: 2rem; font-weight: 800; height: 70px;" placeholder="0" min="0" value="{{ old('team2_score', $match->team2_score) }}" required oninput="checkScores()">
                @error('team2_score')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Penalty Winner Selection (Dynamic) -->
        <div class="form-group" id="penalty_winner_container" style="display: none; background-color: rgba(251, 191, 36, 0.05); padding: 16px; border-radius: var(--border-radius); border: 1px solid var(--secondary);">
            <label for="winner_id" class="form-label" style="color: var(--secondary); font-weight: bold;">
                <i class="fa-solid fa-circle-question"></i> Pemenang Adu Penalti
            </label>
            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 12px;">
                Pertandingan babak knockout tidak boleh berakhir seri. Silakan tentukan tim yang memenangkan adu penalti untuk melaju ke babak berikutnya.
            </p>
            <select name="winner_id" id="winner_id" class="form-control" style="background-color: var(--bg-sidebar); cursor: pointer;">
                <option value="">-- Pilih Pemenang Adu Penalti --</option>
                <option value="{{ $match->team1_id }}" {{ old('winner_id', $match->winner_id) == $match->team1_id ? 'selected' : '' }}>
                    {{ $match->team1->name }}
                </option>
                <option value="{{ $match->team2_id }}" {{ old('winner_id', $match->winner_id) == $match->team2_id ? 'selected' : '' }}>
                    {{ $match->team2->name }}
                </option>
            </select>
            @error('winner_id')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions" style="margin-top: 32px; justify-content: center; width: 100%;">
            <a href="{{ route('tournaments.show', $match->tournament_id) }}" class="btn btn-secondary" style="width: 45%; justify-content: center;">Batal</a>
            <button type="submit" class="btn btn-primary" style="width: 45%; justify-content: center;">
                <i class="fa-solid fa-circle-check"></i> Simpan Hasil
            </button>
        </div>
    </form>
</div>

<script>
    function checkScores() {
        var round = "{{ $match->round }}";
        var container = document.getElementById('penalty_winner_container');
        var winnerSelect = document.getElementById('winner_id');
        
        if (round !== 'group') {
            var score1 = parseInt(document.getElementById('team1_score').value);
            var score2 = parseInt(document.getElementById('team2_score').value);
            
            if (!isNaN(score1) && !isNaN(score2) && score1 === score2) {
                container.style.display = 'block';
                winnerSelect.setAttribute('required', 'required');
            } else {
                container.style.display = 'none';
                winnerSelect.removeAttribute('required');
            }
        }
    }
    
    function validateForm() {
        var round = "{{ $match->round }}";
        if (round !== 'group') {
            var score1 = parseInt(document.getElementById('team1_score').value);
            var score2 = parseInt(document.getElementById('team2_score').value);
            var winnerId = document.getElementById('winner_id').value;
            
            if (!isNaN(score1) && !isNaN(score2) && score1 === score2 && !winnerId) {
                alert('Silakan pilih pemenang adu penalti untuk babak knockout.');
                return false;
            }
        }
        return true;
    }
    
    // Run initial check on page load
    checkScores();
</script>
@endsection
