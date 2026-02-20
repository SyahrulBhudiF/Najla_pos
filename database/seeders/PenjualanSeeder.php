<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penjualan = [];
        for ($i = 1; $i <= 10; $i++) {
            $penjualan[] = [
                'penjualan_id' => $i,
                'user_id' => 3, // Staff/Kasir
                'pembeli' => 'Pelanggan ' . $i,
                'penjualan_kode' => 'PJN' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'penjualan_tanggal' => now(),
            ];
        }
        DB::table('t_penjualan')->insert($penjualan);
    }
}
