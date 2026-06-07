@extends('layouts.app')

@section('title', 'Tambah Jadwal Pertandingan')

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('tournaments.show', $tournament->id) }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Turnamen</a>
    <h1 style="margin-top: 12px;">Tambah Jadwal Pertandingan</h1>
    <p style="color: var(--text-secondary);">Turnamen: {{ $tournament->name }}</p>
</div>

<div class="card" style="max-width: 700px; margin: 0;">
    <h3 style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-calendar-plus" style="color: var(--primary);"></i> Buat Jadwal Pertandingan Baru
    </h3>

    <form action="{{ route('matches.store', $tournament->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="round" class="form-label">Babak / Round</label>
            <select name="round" id="round" class="form-control" style="background-color: var(--bg-sidebar); cursor: pointer;" required onchange="toggleGroupSelect()">
                <option value="group" {{ old('round') === 'group' ? 'selected' : '' }}>Babak Penyisihan Grup</option>
                <option value="quarterfinal" {{ old('round') === 'quarterfinal' ? 'selected' : '' }}>Perempat Final (Quarter-final)</option>
                <option value="semifinal" {{ old('round') === 'semifinal' ? 'selected' : '' }}>Semifinal</option>
                <option value="final" {{ old('round') === 'final' ? 'selected' : '' }}>Final</option>
            </select>
            @error('round')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="group_select_container">
            <label for="group_id" class="form-label">Pilih Grup (Khusus Babak Penyisihan)</label>
            <select name="group_id" id="group_id" class="form-control" style="background-color: var(--bg-sidebar); cursor: pointer;">
                <option value="">-- Pilih Grup --</option>
                @foreach($tournament->groups as $group)
                    <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                        {{ $group->name }}
                    </option>
                @endforeach
            </select>
            @error('group_id')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label for="team1_id" class="form-label">Tim Home (Tim 1)</label>
                <select name="team1_id" id="team1_id" class="form-control" style="background-color: var(--bg-sidebar); cursor: pointer;" required>
                    <option value="" disabled selected>-- Pilih Tim 1 --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team1_id') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
                @error('team1_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="team2_id" class="form-label">Tim Away (Tim 2)</label>
                <select name="team2_id" id="team2_id" class="form-control" style="background-color: var(--bg-sidebar); cursor: pointer;" required>
                    <option value="" disabled selected>-- Pilih Tim 2 --</option>
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ old('team2_id') == $team->id ? 'selected' : '' }}>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
                @error('team2_id')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="match_date" class="form-label">Tanggal & Waktu Kick-Off</label>
            <input type="datetime-local" name="match_date" id="match_date" class="form-control" value="{{ old('match_date') }}" required>
            @error('match_date')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> Simpan Jadwal</button>
        </div>
    </form>
</div>

<script>
    function toggleGroupSelect() {
        var round = document.getElementById('round').value;
        var container = document.getElementById('group_select_container');
        var groupSelect = document.getElementById('group_id');
        
        if (round === 'group') {
            container.style.display = 'block';
            groupSelect.setAttribute('required', 'required');
        } else {
            container.style.display = 'none';
            groupSelect.removeAttribute('required');
            groupSelect.value = '';
        }
    }
    
    // Call initial function
    toggleGroupSelect();
</script>
@endsection
