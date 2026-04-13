@extends('layouts.app')

@section('content')

<div class="mb-2 text-muted d-flex align-items-center gap-2">
    <i class="fas fa-home"></i>
    <span>></span>
    <a href="/admin/produk" class="text-muted text-decoration-none">Produk</a>
    <span>></span>
    <span>Edit Produk</span>
</div>

<h3 class="mb-4 fw-bold">
    <i class="text-primary me-2"></i>Edit Produk
</h3>

<div class="card p-4 shadow-sm">

<form action="/admin/produk/{{ $product->id }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">

        <!-- NAMA -->
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- GAMBAR (opsional) -->
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Gambar Produk Ada yang sebelumnya <span class="text-muted">(opsional jika ingin ganti)</span></label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- HARGA -->
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
            <input type="text" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', 'Rp. ' . number_format($product->price)) }}">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- STOK (DISABLED - TIDAK BISA DIUBAH) -->
        <div class="col-md-6 mb-3">
            <label class="form-label fw-semibold">Stok Saat Ini</label>
            <input type="text" value="{{ $product->stock }}" class="form-control" disabled readonly>
        </div>

    </div>

    <div class="mt-3 d-flex gap-2">
        <a href="/admin/produk" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button class="btn btn-primary">
            <i class="fas fa-save"></i> Update Produk
        </button>
    </div>

</form>

</div>

<!-- SCRIPT FORMAT RUPIAH (SAMA SEPERTI DI CREATE) -->
<script>
    const priceInput = document.getElementById('price');
    let isFormatting = false;

    priceInput.addEventListener('input', function(e) {
        if (isFormatting) return;
        isFormatting = true;
        
        let cursorPosition = this.selectionStart;
        let rawValue = this.value;
        
        // Hapus semua karakter non-digit (termasuk Rp, spasi, titik)
        let numberValue = rawValue.replace(/[^0-9]/g, '');
        
        if (numberValue === '') {
            this.value = '';
            isFormatting = false;
            return;
        }
        
        // Format ke rupiah
        let formatted = new Intl.NumberFormat('id-ID').format(parseInt(numberValue));
        
        // Set nilai dengan prefix Rp.
        this.value = 'Rp. ' + formatted;
        
        // Kembalikan posisi kursor ke akhir
        this.setSelectionRange(this.value.length, this.value.length);
        
        isFormatting = false;
    });
    
    // Event untuk menjaga format saat keluar dari input
    priceInput.addEventListener('blur', function() {
        if (this.value === '' || this.value === 'Rp. ') {
            this.value = '';
        } else if (this.value.match(/^Rp\.\s*$/)) {
            this.value = '';
        }
    });
    
    // Event untuk memudahkan edit: saat fokus, pindahkan kursor setelah Rp.
    priceInput.addEventListener('focus', function() {
        if (this.value.startsWith('Rp. ')) {
            this.setSelectionRange(4, 4);
        }
    });
</script>

@endsection