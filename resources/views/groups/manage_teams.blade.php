@extends('layouts.app')

@section('title', 'Kelola Anggota Tim - ' . $group->name)

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('tournaments.show', $tournament->id) }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Turnamen</a>
    <h1 style="margin-top: 12px;">Kelola Anggota Tim: {{ $group->name }}</h1>
    <p style="color: var(--text-secondary);">Turnamen: {{ $tournament->name }}</p>
</div>

<div class="card" style="max-width: 800px; margin: 0;">
    <h3 style="margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-users-gear" style="color: var(--primary);"></i> Pilih Tim untuk dimasukkan ke {{ $group->name }}
    </h3>
    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">
        * Tim yang sudah ditugaskan ke grup lain di turnamen ini tidak akan muncul di daftar pilihan.
    </p>

    <form action="{{ route('groups.update_teams', $group->id) }}" method="POST">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; margin-bottom: 32px;">
            @forelse($teams as $team)
                @php
                    $isAssigned = $group->teams->contains($team->id);
                @endphp
                <div style="display: flex; align-items: center; gap: 12px; padding: 12px 16px; background-color: var(--bg-sidebar); border-radius: var(--border-radius); border: 1px solid {{ $isAssigned ? 'var(--primary-border)' : 'var(--border-color)' }}; transition: var(--transition);">
                    <input type="checkbox" name="teams[]" value="{{ $team->id }}" id="team_{{ $team->id }}" style="width: 18px; height: 18px; accent-color: var(--primary); cursor: pointer;" {{ $isAssigned ? 'checked' : '' }}>
                    <label for="team_{{ $team->id }}" style="cursor: pointer; font-weight: 500; display: flex; align-items: center; gap: 8px; flex-grow: 1; user-select: none;">
                        @if($team->logo)
                            <img src="{{ asset('storage/' . $team->logo) }}" alt="" style="width: 24px; height: 24px; object-fit: cover; border-radius: 4px;">
                        @endif
                        {{ $team->name }}
                    </label>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 20px;">
                    Tidak ada tim yang tersedia untuk ditambahkan.
                </div>
            @endforelse
        </div>

        <div class="form-actions" style="border-top: 1px solid var(--border-color); padding-top: 20px;">
            <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary" {{ $teams->isEmpty() && $group->teams->isEmpty() ? 'disabled' : '' }}>
                <i class="fa-solid fa-circle-check"></i> Simpan Pilihan
            </button>
        </div>
    </form>
</div>
@endsection
