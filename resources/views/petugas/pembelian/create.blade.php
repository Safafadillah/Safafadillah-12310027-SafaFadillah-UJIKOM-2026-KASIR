@extends('layouts.app')

@section('content')

    <div class="mb-2 text-muted d-flex align-items-center gap-2">
        <a href="/petugas/pembelian" class="text-muted text-decoration-none">
            <i class="fas fa-home"></i>
        </a>
        <span>></span>
        <a href="/petugas/pembelian" class="text-muted text-decoration-none"><span>Pembelian</span></a>
        <span>></span>
        <span>Pilih Produk</span>
    </div>

<h3 class="mb-4">Pembelian</h3>

<div class="card p-4 shadow-sm">

    <div class="row">

        @foreach ($products as $item)
        <div class="col-md-3 mb-4">
            <div class="card p-3 text-center">

                <div class="d-flex justify-content-center mb-3">
                    <img src="{{ asset('storage/'.$item->image) }}" 
                         style="width: 150px; height: 150px; object-fit: cover; border-radius: 10px; border: 1px solid #ddd;">
                </div>

                <h6 class="mt-2">{{ $item->name }}</h6>
                <small>Stok {{ $item->stock }}</small>
                <div>Rp {{ number_format($item->price) }}</div>

                <div class="d-flex justify-content-center align-items-center gap-2 mt-2">
                    <button class="btn btn-sm btn-secondary minus" data-id="{{ $item->id }}">-</button>
                    <span id="qty-{{ $item->id }}">0</span>
                    <button class="btn btn-sm btn-primary plus" 
                        data-id="{{ $item->id }}" 
                        data-stock="{{ $item->stock }}"
                        data-price="{{ $item->price }}"
                        {{ $item->stock == 0 ? 'disabled' : '' }}>
                        +
                    </button>
                </div>

                @if ($item->stock == 0)
                    <div class="text-danger mt-2">Stok habis</div>
                @else
                    <small>Sub Total Rp <span id="subtotal-{{ $item->id }}">0</span></small>
                @endif

            </div>
        </div>
        @endforeach

    </div>

    <form action="{{ route('petugas.pembelian.store') }}" method="POST" id="form">
        @csrf
        <input type="hidden" name="data" id="data">
        
        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary">Selanjutnya</button>
        </div>
    </form>

</div>

@endsection

@push('scripts')
<script>
let cart = {}

document.querySelectorAll('.plus').forEach(btn => {
    btn.onclick = function(){
        let id = this.dataset.id
        let stock = parseInt(this.dataset.stock)
        let price = parseInt(this.dataset.price)

        if (stock <= 0) {
            return
        }

        cart[id] = cart[id] || {qty:0, price:price}

        if(cart[id].qty < stock){
            cart[id].qty++
        }

        if (cart[id].qty === 0) {
            delete cart[id]
        }

        update(id)
    }
})

document.querySelectorAll('.minus').forEach(btn => {
    btn.onclick = function(){
        let id = this.dataset.id

        if(cart[id]){
            cart[id].qty--
            if(cart[id].qty <= 0) delete cart[id]
        }

        update(id)
    }
})

function update(id){
    let qty = cart[id]?.qty || 0
    let price = cart[id]?.price || 0
    let subtotal = qty * price

    document.getElementById('qty-'+id).innerText = qty
    document.getElementById('subtotal-'+id).innerText = subtotal.toLocaleString('id-ID')
}

// ✅ FIX WAJIB DI SINI
document.getElementById('form').onsubmit = function(e){
    let filteredCart = {}

    Object.entries(cart).forEach(([id, item]) => {
        if (item.qty > 0) {
            filteredCart[id] = item
        }
    })

    if(Object.keys(filteredCart).length === 0){
        alert('Pilih produk dulu!')
        e.preventDefault()
        return
    }

    document.getElementById('data').value = JSON.stringify(filteredCart)
}
</script>
@endpush