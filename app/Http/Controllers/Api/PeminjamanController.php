<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;

class PeminjamanController extends Controller
{
    public function index(Request $request, $id)
    {
        $data = Peminjaman::with(['book' => function($query) {
            $query->select('id', 'judul', 'category_id', 'penerbit', 'pengarang', 'tahun', 'stok', 'path');
            $query->with(['category' => function($query) {
                $query->select('id', 'nama_kategori');
            }]);
        }])->where('id_member', $id)->orderBy('created_at', 'DESC');
        
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

    public function create(Request $request)
    {
        Log::info($request);
        $book = Book::findOrFail($request->id_buku)->judul;
        
        DB::beginTransaction();
        try {
            if(isset($request->id)) {
                $data = Peminjaman::findOrFail($request->id);
                $data->updated_at = Carbon::now();
                $meesage = "Berhasil, mengembalikan buku ".$book.".";
            } else {
                $data = new Peminjaman();
                $data->created_at = Carbon::now();
                $meesage = "Berhasil, meminjam buku ".$book.".";
            }

            $data->id_buku                = $request->id_buku;
            $data->id_member              = $request->id_member;
            $data->tanggal_peminjaman     = $request->tanggal_peminjaman;
            $data->tanggal_pengembalian   = $request->tanggal_pengembalian;
            $data->status                 = $request->status;
            
            $data->save();

            DB::commit();
            
            return response()->json([
                'status'    => 200,
                'message'   => $meesage
            ]);
        } catch (\Throwable $th) {
            Log::info($th);
            return response()->json([
                'status'    => 200,
                'message'   => "Gagal, meminjam buku ".$book.". Mohon coba kembali."
            ]);
        }
    }
}
