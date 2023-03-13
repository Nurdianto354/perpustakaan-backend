<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['nama_kategori' => 'Novel', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Cergam', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Komik', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Ensiklopedi', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Nomik', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Antologi', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Dongeng', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Biografi', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Catatan Harian', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Novelet', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Fotografi', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Karya Ilmiah', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Tafsir', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Kamus', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Panduan (how to)', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Atlas', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Buku Ilmiah', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Teks', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Majalah', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['nama_kategori' => 'Buku Digital', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('categories')->insert($categories);
    }
}
