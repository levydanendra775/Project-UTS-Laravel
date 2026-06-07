@extends('layouts.app')

@section('title', 'Register - Sistem Turnamen Futsal')

@section('content')
<div class="auth-container">
    <div class="card auth-card">
        <div class="auth-header">
            <div class="auth-logo">F</div>
            <h2 class="auth-title">Daftar Akun Baru</h2>
            <p class="auth-subtitle">Kelola turnamen futsal dengan mudah</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Nama Anda" value="{{ old('name') }}" required autofocus>
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Pilih Peran (Role)</label>
                <select name="role" id="role" class="form-control" style="background-color: var(--bg-sidebar); cursor: pointer;" required>
                    <option value="panitia" {{ old('role') === 'panitia' ? 'selected' : '' }}>Panitia (Skor & Jadwal)</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator (Akses Penuh)</option>
                </select>
                @error('role')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Min. 6 Karakter" required>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ulangi Kata Sandi" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 20px; padding: 12px;">
                <i class="fa-solid fa-user-plus"></i> Daftar Sekarang
            </button>
        </form>

        <p class="auth-footer-text">
            Sudah memiliki akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </p>
    </div>
</div>
@endsection
