@extends('layouts.app')

@section('title', 'Edit Pemain - ' . $player->name)

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('players.index') }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pemain</a>
    <h1 style="margin-top: 12px;">Edit Pemain: {{ $player->name }}</h1>
</div>

<div class="card" style="max-width: 700px; margin: 0;">
    <h3 style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-user-pen" style="color: var(--secondary);"></i> Perbarui Data Pemain
    </h3>

    <form action="{{ route('players.update', $player->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="team_id" class="form-label">Tim Futsal</label>
            <select name="team_id" id="team_id" class="form-control" style="background-color: var(--bg-sidebar); cursor: pointer;" required>
                @foreach($teams as $team)
                    <option value="{{ $team->id }}" {{ old('team_id', $player->team_id) == $team->id ? 'selected' : '' }}>
                        {{ $team->name }}
                    </option>
                @endforeach
            </select>
            @error('team_id')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="name" class="form-label">Nama Pemain</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $player->name) }}" required>
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="back_number" class="form-label">Nomor Punggung (1 - 99)</label>
            <input type="number" name="back_number" id="back_number" class="form-control" min="1" max="99" value="{{ old('back_number', $player->back_number) }}" required>
            @error('back_number')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="position" class="form-label">Posisi Pemain</label>
            <select name="position" id="position" class="form-control" style="background-color: var(--bg-sidebar); cursor: pointer;" required>
                <option value="Goalkeeper" {{ old('position', $player->position) === 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper (Kiper)</option>
                <option value="Defender" {{ old('position', $player->position) === 'Defender' ? 'selected' : '' }}>Defender (Anchor / Bertahan)</option>
                <option value="Flank" {{ old('position', $player->position) === 'Flank' ? 'selected' : '' }}>Flank (Sayap)</option>
                <option value="Pivot" {{ old('position', $player->position) === 'Pivot' ? 'selected' : '' }}>Pivot (Penyerang)</option>
                <option value="Universal" {{ old('position', $player->position) === 'Universal' ? 'selected' : '' }}>Universal (Serba Bisa)</option>
            </select>
            @error('position')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="birth_date" class="form-label">Tanggal Lahir</label>
            <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date', $player->birth_date->format('Y-m-d')) }}" required>
            @error('birth_date')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('players.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
