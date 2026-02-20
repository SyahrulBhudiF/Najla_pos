<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barang = [
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'TV01', 'barang_nama' => 'TV LED 32 Inch', 'harga_beli' => 2000000, 'harga_jual' => 2500000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'LP01', 'barang_nama' => 'Laptop Core i5', 'harga_beli' => 7000000, 'harga_jual' => 8500000],
            ['barang_id' => 3, 'kategori_id' => 2, 'barang_kode' => 'SN01', 'barang_nama' => 'Snack Kentang', 'harga_beli' => 8000, 'harga_jual' => 10000],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'RM01', 'barang_nama' => 'Roti Manis', 'harga_beli' => 4000, 'harga_jual' => 6000],
            ['barang_id' => 5, 'kategori_id' => 3, 'barang_kode' => 'JS01', 'barang_nama' => 'Jus Apel 250ml', 'harga_beli' => 5000, 'harga_jual' => 7500],
            ['barang_id' => 6, 'kategori_id' => 3, 'barang_kode' => 'AM01', 'barang_nama' => 'Air Mineral 600ml', 'harga_beli' => 2500, 'harga_jual' => 3500],
            ['barang_id' => 7, 'kategori_id' => 4, 'barang_kode' => 'SB01', 'barang_nama' => 'Sabun Mandi', 'harga_beli' => 3000, 'harga_jual' => 4500],
            ['barang_id' => 8, 'kategori_id' => 4, 'barang_kode' => 'PS01', 'barang_nama' => 'Pasta Gigi', 'harga_beli' => 6000, 'harga_jual' => 8000],
            ['barang_id' => 9, 'kategori_id' => 5, 'barang_kode' => 'SP01', 'barang_nama' => 'Sapu Ijuk', 'harga_beli' => 12000, 'harga_jual' => 18000],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'PL01', 'barang_nama' => 'Piring Keramik', 'harga_beli' => 10000, 'harga_jual' => 15000],
        ];
        DB::table('m_barang')->insert($barang);
    }
}
