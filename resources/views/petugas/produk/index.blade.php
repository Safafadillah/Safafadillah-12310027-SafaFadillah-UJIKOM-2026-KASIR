@extends('layouts.app')

@section('content')

<div class="mb-2 text-muted d-flex align-items-center gap-2">
    <a href="/petugas/dashboard" class="text-muted text-decoration-none">
        <i class="fas fa-home"></i>
    </a>
    <span>></span>
    <span>Produk</span>
</div>

<h3 class="mb-4 fw-bold">
    <i class="text-primary me-2"></i>Produk
</h3>

<div class="card p-4 shadow-sm">

    <div class="d-flex justify-content-between mb-3">
        <form method="GET" class="d-flex" style="width: 300px;">
            <input type="text" name="search" class="form-control me-2" placeholder="Search..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="fas fa-search"></i>
            </button>

            @if (request('search'))
                <a href="/petugas/produk" class="btn btn-outline-secondary ms-2">Reset</a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th width="10%">No</th>
                    <th width="10%"></th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th width="10%">Stok</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <!-- GAMBAR -->
                        <td>
                            <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/100' }}"
                                width="80" height="80"
                                style="object-fit: cover; border-radius: 12px;">
                        </td>

                        <!-- NAMA -->
                        <td>{{ $item->name }}</td>

                        <td>Rp {{ number_format($item->price) }}</td>
                        <td>{{ $item->stock }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Data kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection