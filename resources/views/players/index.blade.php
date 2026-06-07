@extends('layouts.app')

@section('title', 'Daftar Pemain Futsal')

@section('content')
<div style="margin-bottom: 32px;">
    <h1>Manajemen Pemain</h1>
    <p>Kelola data seluruh pemain futsal terdaftar.</p>
</div>

<div class="action-bar">
    <form action="{{ route('players.index') }}" method="GET" class="search-form">
        <input type="text" name="search" class="form-control" placeholder="Cari nama pemain, tim, posisi..." value="{{ $search }}">
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
        @if($search)
            <a href="{{ route('players.index') }}" class="btn btn-secondary"><i class="fa-solid fa-rotate-left"></i></a>
        @endif
    </form>
    
    <a href="{{ route('players.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Pemain Baru
    </a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 80px; text-align: center;">No. Punggung</th>
                <th>Nama Pemain</th>
                <th>Tim</th>
                <th>Posisi</th>
                <th>Tanggal Lahir</th>
                <th>Umur</th>
                <th style="width: 150px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($players as $player)
                <tr>
                    <td style="text-align: center; font-weight: 800; color: var(--secondary);">
                        #{{ $player->back_number }}
                    </td>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $player->name }}</td>
                    <td>
                        <a href="{{ route('teams.show', $player->team_id) }}" style="font-weight: 600;">
                            {{ $player->team->name }}
                        </a>
                    </td>
                    <td><span class="badge badge-primary">{{ $player->position }}</span></td>
                    <td>{{ $player->birth_date->format('d M Y') }}</td>
                    <td>{{ $player->birth_date->age }} Tahun</td>
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
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fa-solid fa-user-slash" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                        Tidak ada data pemain ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container">
    {{ $players->links() }}
</div>
@endsection
