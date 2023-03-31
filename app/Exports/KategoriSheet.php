<?php

namespace App\Exports;

use App\Models\Book;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class KategoriSheet implements FromView, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('book.kategori_sheet', [
            'kategori' => Category::all()
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Kode Kategori';
    }
}
