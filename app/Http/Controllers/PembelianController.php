<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Product;
use App\Models\PembelianDetail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PembelianController extends Controller
{
    // ================= ADMIN =================

public function index(Request $request)
{
    $perPage = $request->perPage ?? 10;
    $search = $request->search;
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    $pembelians = Pembelian::with('details.product') // penting biar tidak N+1
        ->when($search, function ($query) use ($search) {
            return $query->where('nama_pelanggan', 'like', "%$search%");
        })
        ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
            return $query->whereBetween('tanggal_pembelian', [$tanggal_awal, $tanggal_akhir]);
        })
        ->orderBy('tanggal_pembelian', 'asc')
        ->paginate($perPage);

    return view('admin.pembelian.index', compact('pembelians'));
}

    public function detail($id)
    {
        $p = Pembelian::with('details.product')->findOrFail($id);

        return view('admin.pembelian.detail', compact('p'));
    }

public function exportExcel(Request $request)
{
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    $data = Pembelian::with('details.product')
        ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
            return $query->whereBetween('tanggal_pembelian', [$tanggal_awal, $tanggal_akhir]);
        })
        ->orderBy('tanggal_pembelian', 'asc')
        ->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->fromArray([
        'Nama Pelanggan',
        'No HP',
        'Poin',
        'Produk',
        'Total Harga',
        'Total Bayar',
        'Diskon Poin',
        'Kembalian',
        'Tanggal'
    ], NULL, 'A1');

    $row = 2;

    foreach ($data as $p) {

        $sheet->fromArray([
            $p->nama_pelanggan,
            $p->no_telp,
            $p->poin_didapat ?? 0,
            $p->produk, // 🔥 DARI ACCESSOR
            'Rp. ' . number_format($p->total_harga, 0, ',', '.'),
            'Rp. ' . number_format($p->total_bayar, 0, ',', '.'),
            'Rp. ' . number_format($p->poin_dipakai, 0, ',', '.'),
            'Rp. ' . number_format($p->kembalian, 0, ',', '.'),
            \Carbon\Carbon::parse($p->tanggal_pembelian)->format('d-m-Y')
        ], NULL, 'A' . $row);

        $row++;
    }

    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, 'pembelian_admin.xlsx');
}

    // ================= PETUGAS =================

public function indexPetugas(Request $request)
{
    $perPage = $request->perPage ?? 10;
    $search = $request->search;
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    $pembelians = Pembelian::with('details.product')
        ->when($search, function ($query) use ($search) {
            return $query->where('nama_pelanggan', 'like', "%$search%");
        })
        ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
            return $query->whereBetween('tanggal_pembelian', [$tanggal_awal, $tanggal_akhir]);
        })
        ->orderBy('tanggal_pembelian', 'asc')
        ->paginate($perPage);

    return view('petugas.pembelian.index', compact('pembelians'));
}

    public function createPetugas()
    {
        $products = Product::all();
        return view('petugas.pembelian.create', compact('products'));
    }

    public function storePetugas(Request $request)
    {
        return $this->penjualan($request);
    }

    public function penjualan(Request $request)
    {
        $data = json_decode($request->data, true);

        if (!$data || count($data) == 0) {
            return redirect()
                ->route('petugas.pembelian.create')
                ->with('error', 'Pilih produk dulu');
        }

        $products = [];
        $total = 0;

        foreach ($data as $id => $item) {
            $product = Product::findOrFail($id);
            $product->qty = $item['qty'];
            $product->subtotal = $item['qty'] * $item['price'];

            $products[] = $product;
            $total += $product->subtotal;
        }

        return view('petugas.pembelian.penjualan', compact('products', 'total'));
    }

    public function detailPetugas($id)
    {
        $p = Pembelian::findOrFail($id);

        return '
            <p><strong>Nama:</strong> ' . $p->nama_pelanggan . '</p>
            <p><strong>Total:</strong> Rp ' . number_format($p->total_harga, 0, ",", ".") . '</p>
            <p><strong>Dibuat Oleh:</strong> ' . $p->dibuat_oleh . '</p>
        ';
    }

    // ================= SIMPAN TRANSAKSI =================

    public function simpan(Request $request)
    {
        DB::beginTransaction();

        try {
            $data = json_decode($request->data, true);
            $total = 0;

            // HITUNG TOTAL
            foreach ($data as $id => $item) {
                $total += $item['qty'] * $item['price'];
            }

            $isMember = $request->status_member == 'member';
            $poinDidapat = 0;
            $poinDipakai = 0;
            $totalPoinSebelumnya = 0;

            if ($isMember) {

                $poinDidapat = floor($total / 100);

                $totalPoinSebelumnya =
                    Pembelian::where('no_telp', $request->no_hp)->sum('poin_didapat')
                    - Pembelian::where('no_telp', $request->no_hp)->sum('poin_dipakai');

                $pernah = Pembelian::where('no_telp', $request->no_hp)->exists();

                if ($pernah && $request->pakai_poin) {

                    $totalSemuaPoin = $totalPoinSebelumnya + $poinDidapat;

                    $poinDipakai = min($totalSemuaPoin, $total);

                    $total -= $poinDipakai;

                    $totalPoinSebelumnya = max($totalPoinSebelumnya, 0);
                }
            }

            // SIMPAN PEMBELIAN
            $pembelian = Pembelian::create([
                'nama_pelanggan' => $isMember ? $request->nama_member : 'NON-MEMBER',
                'tanggal_pembelian' => now(),
                'total_harga'      => $total,
                'dibuat_oleh'      => $request->user()->name,
                'no_telp'          => $isMember ? $request->no_hp : null,
                'is_member'        => $isMember,
                'poin_didapat'     => $poinDidapat,
                'poin_dipakai'     => $poinDipakai,
            ]);

            foreach ($data as $id => $item) {
                $product = Product::findOrFail($id);

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak cukup");
                }

                PembelianDetail::create([
                    'pembelian_id' => $pembelian->id,
                    'product_id'   => $id,
                    'qty'          => $item['qty'],
                    'price'        => $item['price'],
                    'subtotal'     => $item['qty'] * $item['price'],
                ]);

                $product->decrement('stock', $item['qty']);
            }

            $bayar = preg_replace('/[^0-9]/', '', $request->bayar);

            if ($bayar < $total) {
                throw new \Exception("Jumlah bayar kurang");
            }

            $kembalian = $bayar - $total;

            $pembelian->update([
                'total_bayar' => $bayar,
                'kembalian'   => $kembalian,
            ]);

            DB::commit();

            return redirect()->route('petugas.pembelian.struk', [
                'id'          => $pembelian->id,
                'bayar'       => $bayar,
                'kembalian'   => $kembalian,
                'poin'        => $poinDidapat,
                'pakai_poin'  => $poinDipakai
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    // ================= STRUK =================

    public function struk($id)
    {
        $pembelian = Pembelian::findOrFail($id);
        return view('petugas.pembelian.struk', compact('pembelian'));
    }

    // ================= EXPORT EXCEL =================

public function exportExcelPetugas(Request $request)
{
    $tanggal_awal = $request->tanggal_awal;
    $tanggal_akhir = $request->tanggal_akhir;

    $data = Pembelian::with('details.product')
        ->when($tanggal_awal && $tanggal_akhir, function ($query) use ($tanggal_awal, $tanggal_akhir) {
            return $query->whereBetween('tanggal_pembelian', [$tanggal_awal, $tanggal_akhir]);
        })
        ->latest()
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->fromArray([
        'Nama Pelanggan',
        'No HP',
        'Poin Pelanggan',
        'Produk',
        'Total Harga',
        'Total Bayar',
        'Diskon Poin',
        'Kembalian',
        'Tanggal'
    ], NULL, 'A1');

    $row = 2;

    foreach ($data as $p) {

        $produk = $p->details->count()
    ? $p->details->pluck('product.name')->implode(', ')
    : 'Produk tidak tersedia';

        $poin =
            Pembelian::where('no_telp', $p->no_telp)
            ->where('id', '<=', $p->id)
            ->sum('poin_didapat')
            -
            Pembelian::where('no_telp', $p->no_telp)
            ->where('id', '<=', $p->id)
            ->sum('poin_dipakai');

        $poin = max($poin, 0);

        $sheet->fromArray([
            $p->nama_pelanggan,
            $p->no_telp,
            $poin,
            $produk,
            'Rp. ' . number_format($p->total_harga, 0, ',', '.'),
            'Rp. ' . number_format($p->total_bayar, 0, ',', '.'),
            'Rp. ' . number_format($p->poin_dipakai, 0, ',', '.'),
            'Rp. ' . number_format($p->kembalian, 0, ',', '.'),
            \Carbon\Carbon::parse($p->tanggal_pembelian)->format('d-m-Y')
        ], NULL, 'A' . $row);

        $row++;
    }

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, 'pembelian_petugas.xlsx');
}

    // ================= EXPORT PDF =================
    public function downloadPdf($id)
    {
        $pembelian = Pembelian::with('details.product')->findOrFail($id);

        $pdf = Pdf::loadView('petugas.pembelian.struk_pdf', compact('pembelian'));

        return $pdf->download('struk_pembelian_' . $pembelian->id . '.pdf');
    }
}
