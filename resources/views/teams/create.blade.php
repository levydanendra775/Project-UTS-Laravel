@extends('layouts.app')

@section('title', 'Tambah Tim Baru')

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('teams.index') }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tim</a>
    <h1 style="margin-top: 12px;">Tambah Tim Futsal Baru</h1>
</div>

<div class="card" style="max-width: 700px; margin: 0;">
    <h3 style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-people-group" style="color: var(--primary);"></i> Formulir Pendaftaran Tim
    </h3>

    <form action="{{ route('teams.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Nama Tim</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Garuda FC" value="{{ old('name') }}" required>
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="coach_name" class="form-label">Nama Pelatih</label>
            <input type="text" name="coach_name" id="coach_name" class="form-control" placeholder="Nama Pelatih Tim" value="{{ old('coach_name') }}" required>
            @error('coach_name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="logo" class="form-label">Logo Tim (Opsional)</label>
            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
            <span style="color: var(--text-muted); font-size: 0.8rem; margin-top: 4px; display: block;">File gambar format jpeg, png, jpg, gif (Max 2MB).</span>
            @error('logo')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Deskripsi / Catatan Tim (Opsional)</label>
            <textarea name="description" id="description" class="form-control" rows="4" placeholder="Keterangan singkat tentang tim ini...">{{ old('description') }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('teams.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Tim</button>
        </div>
    </form>
</div>
@endsection
