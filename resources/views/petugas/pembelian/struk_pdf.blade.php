<!DOCTYPE html>
<html>
<head>
    <title>Struk</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td, th { border: 1px solid #000; padding: 5px; }
    </style>
</head>
<body>

@php
    $totalPoin =
        \App\Models\Pembelian::where('no_telp', $pembelian->no_telp)
            ->where('id', '<=', $pembelian->id)
            ->sum('poin_didapat')
        -
        \App\Models\Pembelian::where('no_telp', $pembelian->no_telp)
            ->where('id', '<=', $pembelian->id)
            ->sum('poin_dipakai');

    $totalPoin = max($totalPoin, 0);
@endphp

<h3>Invoice #{{ str_pad($pembelian->id, 5, '0', STR_PAD_LEFT) }}</h3>

<p>No Member: {{ $pembelian->no_telp ?? '-' }}</p>
<p>Member Sejak: {{ $pembelian->created_at->translatedFormat('d F Y') }}</p>
<p>Poin Member: {{ $totalPoin }}</p>

<p>
    Tanggal: 
    {{ $pembelian->created_at->timezone('Asia/Jakarta')->format('d F Y H:i:s') }} WIB
</p>

<table>
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
            <td>Rp {{ number_format($item->price,0,',','.') }}</td>
            <td>{{ $item->qty }}</td>
            <td>Rp {{ number_format($item->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<br>

<p>Poin Digunakan: {{ $pembelian->poin_dipakai }}</p>
<p>Kasir: {{ $pembelian->dibuat_oleh }}</p>

<br>

<p>Total: Rp {{ number_format($pembelian->total_harga,0,',','.') }}</p>
<p>Bayar: Rp {{ number_format($pembelian->total_bayar,0,',','.') }}</p>
<p>Kembalian: Rp {{ number_format($pembelian->kembalian,0,',','.') }}</p>

</body>
</html>