@extends('layouts.app')

@section('title', 'Daftar Tim Futsal')

@section('content')
<div style="margin-bottom: 32px;">
    <h1>Manajemen Tim Futsal</h1>
    <p>Kelola data tim futsal peserta turnamen.</p>
</div>

<div class="action-bar">
    <form action="{{ route('teams.index') }}" method="GET" class="search-form">
        <input type="text" name="search" class="form-control" placeholder="Cari nama tim atau pelatih..." value="{{ $search }}">
        <button type="submit" class="btn btn-secondary"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
        @if($search)
            <a href="{{ route('teams.index') }}" class="btn btn-secondary"><i class="fa-solid fa-rotate-left"></i></a>
        @endif
    </form>
    
    <a href="{{ route('teams.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Tim Baru
    </a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 80px;">Logo</th>
                <th>Nama Tim</th>
                <th>Nama Pelatih</th>
                <th>Deskripsi</th>
                <th>Jumlah Pemain</th>
                <th style="width: 200px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teams as $team)
                <tr>
                    <td>
                        @if($team->logo)
                            <img src="{{ asset('storage/' . $team->logo) }}" alt="Logo {{ $team->name }}" class="table-logo">
                        @else
                            <div class="table-logo" style="display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem; background-color: var(--border-color); color: var(--primary);">
                                T
                            </div>
                        @endif
                    </td>
                    <td style="font-weight: 600; color: var(--text-primary);">{{ $team->name }}</td>
                    <td>{{ $team->coach_name }}</td>
                    <td>{{ Str::limit($team->description ?: '-', 40) }}</td>
                    <td><span class="badge badge-primary">{{ $team->players()->count() }} Pemain</span></td>
                    <td>
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('teams.show', $team->id) }}" class="btn btn-secondary btn-sm" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('teams.edit', $team->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('teams.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tim ini beserta semua pemain di dalamnya?');">
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
                    <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                        <i class="fa-solid fa-ban" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                        Tidak ada data tim futsal ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container">
    {{ $teams->links() }}
</div>
@endsection
