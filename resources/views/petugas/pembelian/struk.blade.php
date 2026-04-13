@extends('layouts.app')

@section('content')
    <div class="card p-4">

        <div class="d-flex gap-2 mb-3">
            <a href="#" class="btn btn-sm btn-success">
                Unduh PDF
            </a>
            <a href="/petugas/pembelian" class="btn btn-sm btn-secondary">Kembali</a>
        </div>

        <div class="d-flex justify-content-between align-items-start">

            <div>
                <p><strong>No Member:</strong> {{ $pembelian->no_telp ?? '-' }}</p>
                <p><strong>Member Sejak:</strong> {{ $pembelian->created_at->translatedFormat('d F Y') }}</p>

                <p><strong>Poin Member:</strong>
                    @php
                        $totalPoin =
                            \App\Models\Pembelian::where('no_telp', $pembelian->no_telp)
                                ->where('id', '<=', $pembelian->id)
                                ->sum('poin_didapat') -
                            \App\Models\Pembelian::where('no_telp', $pembelian->no_telp)
                                ->where('id', '<=', $pembelian->id)
                                ->sum('poin_dipakai');

                        $totalPoin = max($totalPoin, 0);
                    @endphp

                    {{ $totalPoin }}
                </p>
            </div>

            <div class="text-end">
                <h5>Invoice #{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</h5>
                <p>{{ $pembelian->created_at->translatedFormat('d F Y') }}</p>
            </div>

        </div>

        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pembelian->details as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>Rp {{ number_format($item->price) }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>Rp {{ number_format($item->subtotal) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <div class="row mt-3">

            <div class="col-md-6">
                <p><strong>Poin Digunakan:</strong> {{ $pembelian->poin_dipakai }}</p>
                <p><strong>Kasir:</strong> {{ $pembelian->dibuat_oleh }}</p>
            </div>

            <div class="col-md-6 text-end">
                <p><strong>Total:</strong> Rp {{ number_format($pembelian->total_harga) }}</p>
                <p><strong>Bayar:</strong> Rp {{ number_format($pembelian->total_bayar) }}</p>
                <p><strong>Kembalian:</strong> Rp {{ number_format($pembelian->kembalian) }}</p>
            </div>

        </div>

    </div>
@endsection
