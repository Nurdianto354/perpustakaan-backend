<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use DB;
use Log;
use Validator;
use Carbon\Carbon;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $data = Category::where('nama_kategori', 'ilike', "%{$request->nama_kategori}%");

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
            $message = "menambahkan data kateogri";
    
            $validator = Validator::make($request->all(), [
                'nama_kategori' => 'required',
            ]);
    
            $category = Category::where('nama_kategori', $request->nama_kategori)->count();

            if($category == 0) {
                $category = Category::create([
                    'nama_kategori' => $request->input('nama_kategori')
                ]);

                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Berhasil '.$message
                ]);
            } else {
                DB::rollBack();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Gagal '.$message.'. Kategori '.$request->nama_kategori.' sudah ada'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal '.$message
            ]);
        }
    }

    public function show($id) {
        $category = Category::findOrFail($id);

        return response()->json([
            'status'    => 200,
            'data'      => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        Log::info($request);
        Log::info($id);
        $message = "memperbarui data kateogri";

        DB::beginTransaction();
        try {
            $category = Category::whereNotIn('id', [$id])->where('nama_kategori', $request['nama_kategori'])->count();

            if($category == 0) {
                $category = Category::findOrFail($id);
                $temp_name = $category->nama_kategori;

                $category->nama_kategori    = $request['nama_kategori'];
                $category->updated_at       = Carbon::now();
                $category->save();

                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Berhasil '.$message.' dari '.$temp_name.' ke '.$request['nama_kategori']
                ]);
            } else {
                DB::rollBack();

                return response()->json([
                    'status'    => 200,
                    'message'   => 'Gagal '.$message.'. Kategori '.$request['nama_kategori'].' sudah ada'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal '.$message
            ]);
        }
    }

    public function destroy($id)
    {
        $message = "menghapus data kateogri";

        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            DB::commit();

            return response()->json([
                'status'    => 200,
                'message'   => 'Berhasil '.$message.' '.$category->nama_kategori
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'    => 400,
                'message'   => 'Gagal '.$message
            ]);
        }
    }
}
