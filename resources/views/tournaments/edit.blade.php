@extends('layouts.app')

@section('title', 'Edit Turnamen - ' . $tournament->name)

@section('content')
<div style="margin-bottom: 32px;">
    <a href="{{ route('tournaments.index') }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Turnamen</a>
    <h1 style="margin-top: 12px;">Edit Turnamen: {{ $tournament->name }}</h1>
</div>

<div class="card" style="max-width: 700px; margin: 0;">
    <h3 style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px;">
        <i class="fa-solid fa-pen-to-square" style="color: var(--secondary);"></i> Perbarui Detail Turnamen
    </h3>

    <form action="{{ route('tournaments.update', $tournament->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Nama Turnamen</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tournament->name) }}" required>
            @error('name')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="status" class="form-label">Status Turnamen</label>
            <select name="status" id="status" class="form-control" style="background-color: var(--bg-sidebar); cursor: pointer;" required>
                <option value="draft" {{ old('status', $tournament->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="ongoing" {{ old('status', $tournament->status) === 'ongoing' ? 'selected' : '' }}>Ongoing (Berjalan)</option>
                <option value="completed" {{ old('status', $tournament->status) === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
            </select>
            @error('status')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="start_date" class="form-label">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $tournament->start_date->format('Y-m-d')) }}" required>
            @error('start_date')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="end_date" class="form-label">Tanggal Selesai</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $tournament->end_date->format('Y-m-d')) }}" required>
            @error('end_date')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('tournaments.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
