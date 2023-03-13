<?php

namespace Database\Seeders;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 37; $i++) {
            Peminjaman::create([
                'id_buku' => rand(1, 45),
                'id_member' => rand(3, 15),
                'tanggal_peminjaman' => Carbon::now()->toDateString(),
                'tanggal_pengembalian' => Carbon::now()->addDays(2)->toDateString(),
                'status' => 1
            ]);
        }
    }
}
