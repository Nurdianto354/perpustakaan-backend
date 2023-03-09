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
use Validator;
use Log;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $data = Book::with('category')->where('judul', 'ilike', "%{$request->judul}%");

        if(isset($request->category_id)) {
            $data = $data->where('category_id', $request->category_id);
        }

        if(isset($request->page)){
            $data = $data->paginate(6);
            $data->appends($request->only($request->keys()));
        }else{
            $data = $data->get();
        }

        return response()->json([
            'status'    => 200,
            'data'      => $data
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'kode_buku' => 'required',
                'category_id' => 'required',
                'judul' => 'required',
                'penerbit' => 'required',
                'pengarang' => 'required',
                'tahun' => 'required',
                'stok' => 'required|integer',
                'path' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);
            
            $path = "public/assets/image/book/";

            if(isset($request->id)) {
                $message = "memperbarui data buku";

                $book = Book::findOrFail($request->id);
                $book->updated_at   = Carbon::now();

                if($request->path != null) {
                    if (Storage::exists($path.$book->path)) {
                        Storage::delete($path.$book->path);
                    }
        
                    $file       = $request->file('path');
                    $date_time  = Carbon::now();
                    $extension  = $file->getClientOriginalExtension();
                    $name_file  = rand(11111,99999).'-'.$date_time->format('Y-m-d-H-i-s').'.'.$extension;
                    
                    Storage::disk('local')->put($path.$name_file, file_get_contents($file));
                    $book->path         = $name_file;
                }
            } else {
                $message = "menambahkan data buku";
                
                $book = new Book();
                $book->created_at   = Carbon::now();

                if($request->path != null) {
                    $file       = $request->file('path');
                    $date_time  = Carbon::now();
                    $extension  = $file->getClientOriginalExtension();
                    $name_file  = rand(11111,99999).'-'.$date_time->format('Y-m-d-H-i-s').'.'.$extension;

                    Storage::disk('local')->put($path.$name_file, file_get_contents($file));
                    $book->path         = $name_file;
                }
            }

            $book->kode_buku    = $request->kode_buku;
            $book->category_id  = $request->category_id;
            $book->judul        = $request->judul;
            $book->slug         = $request->slug;
            $book->penerbit     = $request->penerbit;
            $book->pengarang    = $request->pengarang;
            $book->tahun        = $request->tahun;
            $book->stok         = $request->stok;
            $book->save();

            DB::commit();

            return response()->json([
                'status'    => 200,
                'message'   => "Berhasil ".$message
            ]);
        } catch (\Throwable $th) {
            Log::info($th);
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal '.$message
            ]);
        }
    }

    public function show($id)
    {
        $data = Book::with('category')->where('id', $id)->first();

        return response()->json([
            'status'    => 200,
            'data'      => $data
        ]);
    }

    public function destroy($id)
    {
        $message = "menghapus data buku";

        DB::beginTransaction();
        try {
            $data = Book::findOrFail($id);

            if($data->path != null) {
                $path = "public/assets/image/book/";

                if (Storage::exists($path.$data->path)) {
                    Storage::delete($path.$data->path);
                }
            }
    
            $data->delete();

            DB::commit();

            return response()->json([
                'status'    => 200,
                'message'   => 'Berhasil '.$message.' '.$data->judul
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal '.$message
            ]);
        }
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
