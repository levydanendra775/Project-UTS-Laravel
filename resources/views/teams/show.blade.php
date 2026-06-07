@extends('layouts.app')

@section('title', 'Detail Tim - ' . $team->name)

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('teams.index') }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tim</a>
</div>

<!-- Details Header Card -->
<div class="details-header">
    @if($team->logo)
        <img src="{{ asset('storage/' . $team->logo) }}" alt="Logo {{ $team->name }}" class="details-logo">
    @else
        <div class="details-logo" style="display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 2.5rem; background-color: var(--border-color); color: var(--primary);">
            T
        </div>
    @endif
    
    <div class="details-info">
        <h1 style="margin-bottom: 4px;">{{ $team->name }}</h1>
        <p style="font-size: 1.1rem; color: var(--secondary); font-weight: 500; margin-bottom: 8px;">
            <i class="fa-solid fa-user-tie"></i> Pelatih: {{ $team->coach_name }}
        </p>
        <p style="color: var(--text-secondary);">{{ $team->description ?: 'Belum ada deskripsi untuk tim ini.' }}</p>
    </div>
    
    @if(auth()->user()->isAdmin())
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-warning">
                <i class="fa-solid fa-pen-to-square"></i> Edit Tim
            </a>
        </div>
    @endif
</div>

<!-- Players List in the Team -->
<div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
    <h2>Daftar Pemain Terdaftar ({{ $team->players->count() }})</h2>
    @if(auth()->user()->isAdmin())
        <a href="{{ route('players.create', ['team_id' => $team->id]) }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus"></i> Tambah Pemain ke Tim Ini
        </a>
    @endif
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 80px; text-align: center;">No. Punggung</th>
                <th>Nama Lengkap</th>
                <th>Posisi</th>
                <th>Tanggal Lahir</th>
                <th>Umur</th>
                @if(auth()->user()->isAdmin())
                    <th style="width: 150px; text-align: center;">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($team->players as $player)
                <tr>
                    <td style="text-align: center; font-weight: 800; color: var(--secondary);">
                        #{{ $player->back_number }}
                    </td>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $player->name }}</td>
                    <td>
                        <span class="badge badge-primary">{{ $player->position }}</span>
                    </td>
                    <td>{{ $player->birth_date->format('d M Y') }}</td>
                    <td>{{ $player->birth_date->age }} Tahun</td>
                    @if(auth()->user()->isAdmin())
                        <td>
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('players.edit', $player->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('players.destroy', $player->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pemain ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fa-solid fa-user-slash" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                        Belum ada pemain yang terdaftar dalam tim ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
