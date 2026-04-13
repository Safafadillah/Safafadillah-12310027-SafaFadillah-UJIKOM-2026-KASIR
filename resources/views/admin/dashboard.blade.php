@extends('layouts.app')

@section('content')

<div class="mb-3">
    <div class="d-flex align-items-center gap-2 text-muted mb-2">
        <a href="/admin/dashboard" class="text-muted text-decoration-none"><i class="fas fa-home"></i></a>
        <span>></span>
        <span>Dashboard</span>
    </div>
</div>

<div class="d-flex align-items-center gap-2 mb-2">
    <i class="fas fa-hand-wave text-primary" style="font-size: 1.5rem;"></i>
    <h5 class="mb-1">
        Selamat datang, <span class="fw-bold">{{ Auth::user()->role ?? 'Admin' }}</span>
    </h5>
</div>

<h3 class="mb-4 fw-bold">
    <i class="fas fa-chart-simple me-2 text-primary"></i>Dashboard
</h3>

<div class="row g-4">

    <!-- TOTAL PRODUK -->
    <div class="col-md-6">
        <div class="card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-boxes me-1"></i> Total Produk
                    </h6>
                    <h2 class="fw-bold mb-0">{{ $productsCount }}</h2>
                </div>
                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                    <i class="fas fa-boxes fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- TOTAL USER -->
    <div class="col-md-6">
        <div class="card shadow-sm p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-users me-1"></i> Total User
                    </h6>
                    <h2 class="fw-bold mb-0">{{ $usersCount }}</h2>
                </div>
                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                    <i class="fas fa-users fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection