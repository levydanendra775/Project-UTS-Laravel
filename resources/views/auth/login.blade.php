@extends('layouts.app')

@section('title', 'Login - Sistem Turnamen Futsal')

@section('content')
<div class="auth-container">
    <div class="card auth-card">
        <div class="auth-header">
            <div class="auth-logo">F</div>
            <h2 class="auth-title">Futsal Tournament</h2>
            <p class="auth-subtitle">Masuk untuk mengelola turnamen Anda</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 20px;">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px; margin-top: 10px;">
                <input type="checkbox" name="remember" id="remember" style="accent-color: var(--primary); cursor: pointer;">
                <label for="remember" style="color: var(--text-secondary); font-size: 0.9rem; cursor: pointer; user-select: none;">Ingat saya</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 20px; padding: 12px;">
                <i class="fa-solid fa-right-to-bracket"></i> Masuk
            </button>
        </form>

        <p class="auth-footer-text">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </p>
        <p style="text-align: center; margin-top: 12px; font-size: 0.85rem;">
            <a href="{{ route('landing') }}" style="color: var(--text-muted);"><i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda</a>
        </p>
    </div>
</div>
@endsection
