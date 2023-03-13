<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Log;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($j = 1; $j <= 5; $j++) {
            $judul = [
                "One Piece - " . $j,
                "Naruto - " . $j,
                "Bleach - " . $j,
                "Harry Poter - " . $j,
                "Pesawat Kertas - " . $j,
                "RPUL - " . $j,
                "Matematika - " . $j,
                "Atlas - " . $j,
                "Dilan 1991 - " . $j,
            ];

            $count = count($judul);
            for ($i = 0; $i < $count; $i++) {
                $slug = explode(' ', strtolower($judul[$i]));
                $slug = implode('-', $slug);
                $char = substr($judul[$i], 0, 1);
                $count_kode = Book::where('kode_buku', 'LIKE', $char . '%')->count();
                Log::info($char . '-' . ($count_kode + 1));

                Book::create([
                    'kode_buku' => $char . '-' . ($count_kode + 1),
                    'judul' => $judul[$i],
                    'slug' => $slug,
                    'category_id' => rand(1, 12),
                    'pengarang' => 'Pengarang ' . ($i + 1),
                    'penerbit' => 'Penerbit ' . ($i + 1),
                    'tahun' => rand(1999, 2011),
                    'stok' => rand(5, 8),
                    'path' => '',
                ]);
            }
        }
    }
}
