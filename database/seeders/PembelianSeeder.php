<?php
// database/seeders/PembelianSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembelian;

class PembelianSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        foreach ($data as $item) {
            Pembelian::create($item);
        }
    }
}