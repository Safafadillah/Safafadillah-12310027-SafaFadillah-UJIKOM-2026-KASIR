<?php
// app/Models/Pembelian.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PembelianDetail;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelians';

    protected $fillable = [
        'nama_pelanggan',
        'tanggal_pembelian',
        'total_harga',
        'dibuat_oleh',
        'no_telp',
        'is_member',
        'poin_didapat',
        'poin_dipakai',
        'total_bayar',
        'kembalian'
    ];

    public function details()
    {
        return $this->hasMany(PembelianDetail::class);
    }
}
