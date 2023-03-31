<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;

class BookImport implements ToModel, WithStartRow, WithMultipleSheets
{
    private $setStartRow = 2;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // dd($row[2]);
        $slug = explode(' ', strtolower($row[0]));
        $slug = implode('-', $slug);
        $char = substr($row[0], 0, 1);

        $count_kode = Book::where('kode_buku', 'LIKE', $char . '%')->count();

        $image_path = '/image/book/default-image.png';

        return new Book([
            'kode_buku' => $char . '-' . $count_kode + 1,
            'judul' => $row[0],
            'slug' => $slug,
            'category_id' => $row[1],
            'pengarang' => $row[2],
            'penerbit' => $row[3],
            'tahun' => $row[4],
            'stok' => $row[5],
            'path' => $image_path
        ]);
    }

    public function setStartRow($setStartRow)
    {
        $this->setStartRow = $setStartRow;
    }

    public function startRow(): int
    {
        return $this->setStartRow;
    }

    public function sheets(): array
    {
        return [
            0 => $this,
        ];
    }
}
