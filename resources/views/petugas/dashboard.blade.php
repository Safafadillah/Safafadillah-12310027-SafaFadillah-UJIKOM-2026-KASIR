@extends('layouts.app')

@section('content')
    <!-- BREADCRUMB -->
    <div class="mb-2 text-muted d-flex align-items-center gap-2">
        <a href="/petugas/dashboard" class="text-muted text-decoration-none"><i class="fas fa-home"></i></a>
        <span>></span>
        <span>Dashboard</span>
    </div>

    <!-- WELCOME -->
    <h5 class="mb-2">
        Selamat datang,
        <span class="fw-bold">{{ Auth::user()->name }}</span>
    </h5>

    <!-- TITLE -->
    <h3 class="mb-4 fw-bold">
        <i class="text-primary"></i>Dashboard
    </h3>

    <div class="card text-center shadow-sm p-4">

        <h5 class="fw-bold">Total Penjualan Hari Ini</h5>

        <h2 class="my-3">
            Rp {{ number_format($totalHariIni ?? 0) }}
        </h2>

        <p class="text-muted">
            Jumlah Total Penjualan Yang terjadi Hari ini
        </p>

        <small class="text-secondary">
            Terakhir diperbarui: {{ now()->timezone('Asia/Jakarta')->translatedFormat('d F Y') }} pukul
            {{ now()->timezone('Asia/Jakarta')->format('H:i') }} WIB
        </small>

    </div>
@endsection