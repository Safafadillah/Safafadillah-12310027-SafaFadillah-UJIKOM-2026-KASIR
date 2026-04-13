@extends('layouts.app')

@section('content')
    <div class="mb-2 text-muted d-flex align-items-center gap-2">
        <a href="/admin/dashboard" class="text-muted text-decoration-none">
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
                    <a href="/admin/produk" class="btn btn-outline-secondary ms-2">Reset</a>
                @endif
            </form>

            <a href="/admin/produk/create" class="btn btn-success">
                <i class="fas fa-cart-plus"></i> Tambah Produk
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%"></th>
                        <th>Nama Produk</th>
                        <th width="15%">Harga</th>
                        <th width="10%">Stok</th>
                        <th width="25%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/100' }}"
                                    width="80" height="80" style="object-fit: cover; border-radius: 12px;">
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>Rp {{ number_format($item->price) }}</td>
                            <td>{{ $item->stock }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    <a href="/admin/produk/{{ $item->id }}/edit" class="btn btn-sm btn-warning text-white">
                                        Edit
                                    </a>

                                    <button type="button" class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#stokModal{{ $item->id }}">
                                        Update Stok
                                    </button>

                                    <!-- BUTTON HAPUS -->
                                    <button type="button" class="btn btn-sm btn-danger btn-delete"
                                        data-id="{{ $item->id }}">
                                        Hapus
                                    </button>

                                    <!-- FORM DELETE -->
                                    <form id="delete-form-{{ $item->id }}" action="/admin/produk/{{ $item->id }}"
                                        method="POST" style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Data kosong</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL UPDATE STOK --}}
    @foreach ($products as $item)
        <div class="modal fade" id="stokModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="/admin/produk/{{ $item->id }}/update-stock" method="POST" id="form-stok">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Update Stok Produk</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label>Nama Produk</label>
                            <input type="text" value="{{ $item->name }}" class="form-control mb-2" disabled>

                            <label>Stok Baru</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                value="{{ old('stock', $item->stock) }}">

                            @error('stock')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <input type="hidden" name="id" value="{{ $item->id }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var id = "{{ old('id') }}";
                if (id) {
                    var modal = new bootstrap.Modal(document.getElementById('stokModal' + id));
                    modal.show();
                }
            });
        </script>
    @endif
@endsection
