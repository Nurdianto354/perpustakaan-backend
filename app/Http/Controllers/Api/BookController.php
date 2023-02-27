<?php

namespace App\Http\Controllers\Api;

use App\Exports\BookExport;
use App\Exports\TemplateExport;
use App\Http\Controllers\Controller;
use App\Imports\BookImport;
use App\Models\Book;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    public function getAllBooks()
    {
        try {
            $books = Book::with('category')->latest()->get();
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal mengambil data buku ', $e->getMessage());
        }

        return response()->ok(['books' => $books], 'Sukses mengambil semua data buku');
    }

    public function getBook($id)
    {
        try {
            $book = Book::with('category')->findOrFail($id);
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal mengambil data buku id = ' . $id, $e->getMessage());
        }

        return response()->ok(['book' => $book],  'Sukses mengambil data buku id = ' . $id);
    }

    public function getBooksByCategory($category)
    {
        $books = Book::with('category')->latest()->where('category_id', $category)->get();

        return response()->ok(['books' => $books], 'Sukses mengambil data buku berdasarkan kategori');
    }

    public function create()
    {
        $categories = Category::all();
        $years = [];

        $date = Carbon::now();
        for ($i = 0; $i <= 20; $i++) {
            array_push($years, $date->year - $i);
        }

        return response()->ok(['categories' => $categories, 'years' => $years]);
    }

    public function store(Request $request)
    {
        request()->validate([
            'judul' => 'required',
            'category_id' => 'required',
            'pengarang' => 'required',
            'tahun' => 'required',
            'stok' => 'required|integer',
            'path' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $slug = explode(' ', strtolower($request->input('judul')));
            $slug = implode('-', $slug);
            $char = substr($request->input('judul'), 0, 1);

            $count_kode = Book::where('kode_buku', 'LIKE', $char . '%')->count();

            $penerbit = null;
            if ($request->input('penerbit'))
                $penerbit = $request->input('penerbit');

            $image_path = '/image/book/default-image.png';
            if ($request->file())
                $image_path = $request->file('path')->store('image/book');

            $book = Book::create([
                'kode_buku' => $char . '-' . $count_kode + 1,
                'judul' => $request->input('judul'),
                'slug' => $slug,
                'category_id' => $request->input('category_id'),
                'pengarang' => $request->input('pengarang'),
                'penerbit' => $penerbit,
                'tahun' => $request->input('tahun'),
                'stok' => $request->input('stok'),
                'path' => $image_path,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->internalServerError('Gagal input data buku', $e->getMessage());
        }

        return response()->created(['book' => $book], 'Buku berhasil dibuat');
    }

    public function show($id)
    {
        $categories = Category::all();
        $book = Book::findOrFail($id);
        $years = [];

        $date = Carbon::now();
        for ($i = 0; $i <= 20; $i++) {
            array_push($years, $date->year - $i);
        }

        return response()->ok(['book' => $book, 'year' => $years, 'categories' => $categories]);
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'judul' => 'required',
            'category_id' => 'required',
            'pengarang' => 'required',
            'tahun' => 'required',
            'stok' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $book = Book::findOrFail($id);

            $kode = $book->kode_buku;
            $slug = explode(' ', strtolower($request->input('judul')));
            $slug = implode('-', $slug);

            if ($request->input('judul') !== $book->judul) {
                $char = substr($request->input('judul'), 0, 1);

                $count_kode = Book::where('kode_buku', 'LIKE', $char . '%')->count();
                $kode = $char . '-' . $count_kode + 1;
            }

            $book->update([
                'kode_buku' => $kode,
                'judul' => $request->input('judul'),
                'slug' => $slug,
                'category_id' => $request->input('category_id'),
                'pengarang' => $request->input('pengarang'),
                'tahun' => $request->input('tahun'),
                'stok' => $request->input('stok'),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal mengupdate data buku ' . $book->judul, $e->getMessage());
        }

        return response()->ok(['book' => $book], 'Berhasil mengupdate data buku ' . $book->judul);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $message = 'Berhasil menghapus data buku ' . $book->judul;
        $book->delete();
        return response()->noContent($message);
    }

    public function exportBookPdf()
    {
        try {
            $books = Book::with('category')->latest()->get();

            $data = [
                'data_buku' => $books
            ];

            $pdf = Pdf::loadView('book.viewPdf', $data);
            return $pdf->download('buku_export.pdf');
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal Download Buku Pdf', $e->getMessage());
        }
    }

    public function exportBook()
    {
        try {
            return Excel::download(new BookExport, 'buku_export.xlsx');
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal Export Buku Excel', $e->getMessage());
        }
    }

    public function exportTemplate()
    {
        try {
            return Excel::download(new TemplateExport, 'template_export.xlsx');
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal Download Template Import Buku', $e->getMessage());
        }
    }

    public function importBook(Request $request)
    {
        try {
            if (!$request->file('file_import')) {
                return response()->invalidInput('File import tidak ditemukan');
            }
            $import = new BookImport();
            $import->setStartRow(2);
            Excel::import($import, $request->file('file_import'));
            $books = Book::with('category')->latest()->get();
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal Import Buku', $e->getMessage());
        }
        return response()->ok(['buku' => $books], 'Berhasil Import Buku');
    }
}
