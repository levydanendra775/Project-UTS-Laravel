@extends('layouts.app')

@section('title', 'Tambah Grup Baru')

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('tournaments.show', $tournament->id) }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Detail Turnamen</a>
    <h1 style="margin-top: 12px;">Tambah Grup Baru</h1>
    <p style="color: var(--text-secondary);">Turnamen: {{ $tournament->name }}</p>
</div>

<div class="card" style="max-width: 600px; margin: 0;">
    <h3 style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-folder-plus" style="color: var(--primary);"></i> Tambah Grup Babak Grup
    </h3>

    <form action="{{ route('groups.store', $tournament->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Nama Grup</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Grup A, Grup B" value="{{ old('name') }}" required autofocus>
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('tournaments.show', $tournament->id) }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Grup</button>
        </div>
    </form>
</div>
@endsection
