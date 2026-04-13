<div class="p-3">

    <div class="d-flex justify-content-between">
        <div>
            <p><strong>Member Status:</strong> 
                {{ $p->is_member ? 'Member' : 'Bukan Member' }}
            </p>

            <p><strong>No. HP:</strong> 
                {{ $p->no_telp ?? '-' }}
            </p>

            <p><strong>Poin Member:</strong> 
                @php
                    $poin =
                        \App\Models\Pembelian::where('no_telp', $p->no_telp)
                            ->where('id', '<=', $p->id) // ✅ WAJIB
                            ->sum('poin_didapat') -
                        \App\Models\Pembelian::where('no_telp', $p->no_telp)
                            ->where('id', '<=', $p->id) // ✅ WAJIB
                            ->sum('poin_dipakai');

                    $poin = max($poin, 0);
                @endphp

                {{ $poin }}
            </p>
        </div>

        <div class="text-end">
            <p><strong>Bergabung Sejak:</strong></p>
            <p>{{ $p->created_at->translatedFormat('d F Y') }}</p>
        </div>
    </div>

    <hr>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody> 
            @foreach($p->details as $d)
            <tr>
                <td>{{ $d->product->name }}</td>
                <td>{{ $d->qty }}</td>
                <td>Rp {{ number_format($d->price) }}</td>
                <td>Rp {{ number_format($d->subtotal) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h6 class="text-end">
        Total: Rp {{ number_format($p->total_harga) }}
    </h6>

    <hr>

    <small>
        Dibuat pada:  {{ $p->created_at->timezone('Asia/Jakarta')->translatedFormat('d F Y H:i:s') }}<br>
        oleh: {{ $p->dibuat_oleh }}
    </small>
</div>