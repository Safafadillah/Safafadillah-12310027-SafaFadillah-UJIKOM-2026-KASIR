@extends('layouts.app')

@section('content')

    <div class="mb-2 text-muted d-flex align-items-center gap-2">
        <a href="/petugas/pembelian" class="text-muted text-decoration-none">
            <i class="fas fa-home"></i>
        </a>
        <span>></span>
        <a href="/petugas/pembelian" class="text-muted text-decoration-none"><span>Pembelian</span></a>
        <span>></span>
        <a href="/petugas/pembelian/create" class="text-muted text-decoration-none"><span>Pilih Produk</span></a>
        <span>></span>
        <span>Penjualan</span>
    </div>

    <h3 class="mb-4 fw-bold">Penjualan</h3>

    <div class="card p-4 shadow-sm">
        <div class="row">

            <!-- KIRI -->
            <div class="col-md-8">

                <h5>Produk yang dipilih</h5>

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
                        @php $total = 0; @endphp

                        @foreach ($products as $item)
                            @php $total += $item->subtotal; @endphp
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>Rp {{ number_format($item->price) }}</td>
                                <td>Rp {{ number_format($item->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h5 class="text-end">Total: Rp {{ number_format($total) }}</h5>

            </div>

            <!-- KANAN -->
            <div class="col-md-4">

                <form action="{{ route('petugas.pembelian.simpan') }}" method="POST">
                    @csrf

                    <input type="hidden" name="data"
                        value="{{ json_encode(collect($products)->mapWithKeys(fn($p) => [$p->id => ['qty' => $p->qty, 'price' => $p->price]])->toArray()) }}">
                    <input type="hidden" name="total" value="{{ $total }}">

                    <!-- STATUS MEMBER -->
                    <div class="mb-3">
                        <label class="form-label">Member Status <span class="text-danger">Dapat juga membuat Member</span></label>
                        <select name="status_member" id="status_member" class="form-control">
                            <option value="non">Bukan Member</option>
                            <option value="member">Member</option>
                        </select>
                    </div>

                    <!-- NO HP -->
                    <div class="mb-3 d-none" id="field_hp">
                        <label class="form-label">No Telepon <span class="text-danger">(daftar/gunakan member)</span></label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control">
                    </div>

                    <!-- INFO MEMBER -->
                    <div class="mb-3 d-none" id="member_info">
                        <label>Nama Member (identitas)</label>
                        <input type="text" name="nama_member" id="nama_member" class="form-control">

                        <label class="mt-2">Poin</label>
                        <input type="text" id="poin_member" class="form-control" readonly>

                        <div class="form-check mt-2">
                            <input type="checkbox" name="pakai_poin" id="pakai_poin" class="form-check-input">
                            <label class="form-check-label">Gunakan poin</label>
                        </div>

                        <small class="text-danger" id="info_poin"></small>
                    </div>

                    <!-- TOTAL BAYAR -->
                    <div class="mb-3">
                        <label>Total Bayar</label>
                        <input type="text" name="bayar" id="bayar" class="form-control">
                        <small class="text-danger d-none" id="errorBayar">Jumlah bayar kurang</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="btnSubmit" disabled>Selanjutnya</button>

                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let total = {{ $total }}

        // ================= STATUS MEMBER =================
        document.getElementById('status_member').onchange = function() {
            let isMember = this.value === 'member'

            document.getElementById('field_hp').classList.toggle('d-none', !isMember)
            document.getElementById('member_info').classList.add('d-none')
        }

        // ================= CEK MEMBER =================
        document.getElementById('no_hp').addEventListener('blur', function() {

            let nohp = this.value
            if (!nohp) return

            fetch(`/petugas/cek-member/${nohp}`)
                .then(res => res.json())
                .then(res => {

                    document.getElementById('member_info').classList.remove('d-none')

                    if (res.status === 'lama') {
                        // MEMBER LAMA
                        document.getElementById('nama_member').value = res.nama
                        document.getElementById('nama_member').readOnly = true

                        let poinSekarang = Math.floor(total / 100)

                        // ✅ FIX: jangan biarin negatif
                        let poinLama = Math.max(parseInt(res.poin), 0)

                        let totalPoin = poinLama + poinSekarang
                        
                        document.getElementById('poin_member').value = totalPoin
                        document.getElementById('pakai_poin').disabled = false
                        document.getElementById('info_poin').innerText = ''
                    } else {
                        // ❌ MEMBER BARU
                        document.getElementById('nama_member').value = ''
                        document.getElementById('nama_member').readOnly = false

                        document.getElementById('poin_member').value = Math.floor(total / 100)
                        document.getElementById('pakai_poin').checked = false
                        document.getElementById('pakai_poin').disabled = true
                        document.getElementById('info_poin').innerText =
                            'Poin tidak dapat digunakan pada pembelian pertama'
                    }

                })
        })

        // ================= VALIDASI BAYAR =================
        const bayarInput = document.getElementById('bayar')
        const btnSubmit = document.getElementById('btnSubmit')

        bayarInput.addEventListener('input', function() {

            let angka = this.value.replace(/[^0-9]/g, '')

            if (!angka) {
                this.value = ''
                btnSubmit.disabled = true
                return
            }

            this.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(angka)

            if (parseInt(angka) < total) {
                document.getElementById('errorBayar').classList.remove('d-none')
                btnSubmit.disabled = true
            } else {
                document.getElementById('errorBayar').classList.add('d-none')
                btnSubmit.disabled = false
            }
        })
    </script>
@endpush
