@extends('layouts.app')

@section('content')
    <div class="mb-2 text-muted d-flex align-items-center gap-2">
        <a href="/admin/dashboard" class="text-muted text-decoration-none">
            <i class="fas fa-home"></i>
        </a>
        <span>></span>
        <span>Pembelian</span>
    </div>

    <h3 class="mb-4 fw-bold">Pembelian</h3>

    <div class="card p-4 shadow-sm">

        <!-- EXPORT -->
<div class="mb-4 d-flex gap-2 align-items-center">
<a href="{{ route('admin.pembelian.export', request()->query()) }}" class="btn btn-success">
    Export Pembelian
</a>
<form method="GET" class="d-flex gap-2 align-items-center">
    <input type="date" name="tanggal_awal" class="form-control"
        value="{{ request('tanggal_awal') }}">

    <span>-</span>

    <input type="date" name="tanggal_akhir" class="form-control"
        value="{{ request('tanggal_akhir') }}">

    <button class="btn btn-primary">Filter</button>
</form>
</div>

        <!-- FILTER & SEARCH -->
<form method="GET">
    <div class="row mb-3">

        <!-- LEFT -->
        <div class="col-md-6 d-flex align-items-center gap-2">
            <label>Tampilkan</label>
            <select name="perPage" class="form-select w-auto" onchange="this.form.submit()">
                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
            </select>
            <span>entri</span>
        </div>

        <!-- RIGHT -->
        <div class="col-md-6 d-flex justify-content-end gap-2">

            <input type="text" name="search" class="form-control w-50"
                placeholder="Cari pelanggan..." value="{{ request('search') }}">

            <button class="btn btn-primary">Filter</button>
        </div>

    </div>
</form>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Dibuat Oleh</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pembelians as $item)
                        <tr>
                            <td>
                                {{ ($pembelians->currentPage() - 1) * $pembelians->perPage() + $loop->iteration }}
                            </td>
                            <td>{{ $item->nama_pelanggan }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->translatedFormat('d, F Y') }}</td>
                            <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td>{{ $item->dibuat_oleh }}</td>
                            <td class="text-center">
                                <button class="btn btn-info btn-sm btn-detail" data-id="{{ $item->id }}">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- INFO DATA -->
        <div class="text-muted small mb-2">
            Menampilkan
            {{ $pembelians->firstItem() ?? 0 }}
            hingga
            {{ $pembelians->lastItem() ?? 0 }}
            dari
            {{ $pembelians->total() }} entri
        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-end gap-2">

            @if ($pembelians->onFirstPage())
                <span class="btn btn-secondary btn-sm disabled">Sebelumnya</span>
            @else
                <a href="{{ $pembelians->appends(request()->query())->previousPageUrl() }}" class="btn btn-primary btn-sm">
                    Sebelumnya
                </a>
            @endif

            <span class="btn btn-outline-dark btn-sm">
                {{ $pembelians->currentPage() }}
            </span>

            @if ($pembelians->hasMorePages())
                <a href="{{ $pembelians->appends(request()->query())->nextPageUrl() }}" class="btn btn-primary btn-sm">
                    Selanjutnya
                </a>
            @else
                <span class="btn btn-secondary btn-sm disabled">Selanjutnya</span>
            @endif

        </div>

    </div>

    <!-- MODAL DETAIL -->
    <div class="modal fade" id="modalDetail" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Detail Pembelian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="modalContent">
                    Loading...
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', function() {

                let id = this.getAttribute('data-id');

                fetch(`/admin/pembelian/${id}/detail`)
                    .then(res => res.text())
                    .then(html => {

                        document.getElementById('modalContent').innerHTML = html;

                        let modal = new bootstrap.Modal(document.getElementById('modalDetail'));
                        modal.show();
                    })
                    .catch(() => {
                        document.getElementById('modalContent').innerHTML = 'Gagal memuat data';
                    });

            });
        });
    </script>
@endpush
