@extends('layouts.app')

@section('content')
    <!-- BREADCRUMB -->
    <div class="mb-2 text-muted d-flex align-items-center gap-2">
        <a href="/admin/dashboard" class="text-muted text-decoration-none"><i class="fas fa-home"></i></a>
        <span>></span>
        <a href="/admin/produk" class="text-muted text-decoration-none">Produk</a>
        <span>></span>
        <span>Tambah Produk</span>
    </div>

    <!-- TITLE -->
    <h3 class="mb-4 fw-bold">
        <i class="text-primary me-2"></i>Tambah Produk
    </h3>

    <div class="card p-4 shadow-sm">

        <form action="/admin/produk" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <!-- NAMA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- GAMBAR -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Gambar Produk <span class="text-danger">*</span></label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">

                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- HARGA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                    <input type="text" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">

                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- STOK -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
                    <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}">

                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="mt-3 d-flex gap-2">
                <a href="/admin/produk" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>

        </form>

    </div>

    <!-- SCRIPT FORMAT RUPIAH -->
    <script>
        const priceInput = document.getElementById('price');
        let isFormatting = false;

        priceInput.addEventListener('input', function(e) {
            if (isFormatting) return;
            isFormatting = true;
            
            let rawValue = this.value;
            
            // Hapus semua karakter non-digit
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
            
            // Pindahkan kursor ke akhir
            this.setSelectionRange(this.value.length, this.value.length);
            
            isFormatting = false;
        });
        
        // Saat fokus, pindahkan kursor setelah "Rp. "
        priceInput.addEventListener('focus', function() {
            if (this.value.startsWith('Rp. ')) {
                this.setSelectionRange(4, 4);
            }
        });
    </script>
@endsection