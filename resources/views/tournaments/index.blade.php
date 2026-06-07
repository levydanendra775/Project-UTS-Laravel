@extends('layouts.app')

@section('title', 'Daftar Turnamen')

@section('content')
<div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1>Daftar Turnamen Futsal</h1>
        <p>Kelola ajang kompetisi futsal yang terdaftar.</p>
    </div>
    <a href="{{ route('tournaments.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Buat Turnamen Baru
    </a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Nama Turnamen</th>
                <th>Status</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Grup</th>
                <th style="width: 200px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tournaments as $tournament)
                <tr>
                    <td style="font-weight: 600; color: var(--text-primary);">
                        <a href="{{ route('tournaments.show', $tournament->id) }}" style="font-size: 1.05rem;">
                            {{ $tournament->name }}
                        </a>
                    </td>
                    <td>
                        @if($tournament->status === 'draft')
                            <span class="badge badge-warning">Draft</span>
                        @elseif($tournament->status === 'ongoing')
                            <span class="badge badge-primary">Berjalan</span>
                        @else
                            <span class="badge badge-success">Selesai</span>
                        @endif
                    </td>
                    <td>{{ $tournament->start_date->format('d M Y') }}</td>
                    <td>{{ $tournament->end_date->format('d M Y') }}</td>
                    <td>
                        <span class="badge badge-secondary">{{ $tournament->groups()->count() }} Grup</span>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn btn-secondary btn-sm" title="Jadwal & Klasemen">
                                <i class="fa-solid fa-circle-play"></i>
                            </a>
                            <a href="{{ route('tournaments.knockout', $tournament->id) }}" class="btn btn-secondary btn-sm" style="background-color: var(--secondary-glow); color: var(--secondary);" title="Bagan Knockout">
                                <i class="fa-solid fa-sitemap"></i>
                            </a>
                            <a href="{{ route('tournaments.edit', $tournament->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="{{ route('tournaments.destroy', $tournament->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus turnamen ini? Semua data grup, jadwal, dan klasemen di dalamnya akan terhapus permanen.');">
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
                        Belum ada turnamen futsal yang dibuat.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container">
    {{ $tournaments->links() }}
</div>
@endsection
