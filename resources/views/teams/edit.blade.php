@extends('layouts.app')

@section('title', 'Edit Tim - ' . $team->name)

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('teams.index') }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tim</a>
    <h1 style="margin-top: 12px;">Edit Tim: {{ $team->name }}</h1>
</div>

<div class="card" style="max-width: 700px; margin: 0;">
    <h3 style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-pen-to-square" style="color: var(--secondary);"></i> Perbarui Data Tim
    </h3>

    <form action="{{ route('teams.update', $team->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Nama Tim</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $team->name) }}" required>
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="coach_name" class="form-label">Nama Pelatih</label>
            <input type="text" name="coach_name" id="coach_name" class="form-control" value="{{ old('coach_name', $team->coach_name) }}" required>
            @error('coach_name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" style="display: flex; gap: 20px; align-items: center;">
            <div style="flex-grow: 1;">
                <label for="logo" class="form-label">Perbarui Logo Tim (Opsional)</label>
                <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                <span style="color: var(--text-muted); font-size: 0.8rem; margin-top: 4px; display: block;">File gambar format jpeg, png, jpg, gif (Max 2MB).</span>
                @error('logo')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            
            @if($team->logo)
                <div style="text-align: center;">
                    <span class="form-label" style="display: block; margin-bottom: 4px;">Logo Saat Ini</span>
                    <img src="{{ asset('storage/' . $team->logo) }}" alt="Logo {{ $team->name }}" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Deskripsi / Catatan Tim (Opsional)</label>
            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $team->description) }}</textarea>
            @error('description')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('teams.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
