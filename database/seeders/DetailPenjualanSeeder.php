<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $detail = [];
        $counter = 1;

        
        for ($i = 1; $i <= 10; $i++) {
            
            for ($j = 1; $j <= 3; $j++) {
                $barang_id = rand(1, 10); 
                
            
                $barang = DB::table('m_barang')->where('barang_id', $barang_id)->first();

                $detail[] = [
                    'detail_id'    => $counter++,
                    'penjualan_id' => $i,
                    'barang_id'    => $barang_id,
                    'harga'        => $barang->harga_jual, 
                    'jumlah'       => rand(1, 5),          
                ];
            }
        }

        DB::table('t_penjualan_detail')->insert($detail);
    }
}
