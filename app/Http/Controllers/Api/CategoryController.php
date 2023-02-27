<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function getAllCategories()
    {
        try {
            $categories = Category::all();
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal mengambil data categories', $e->getMessage());
        }
        return response()->ok(['categories' => $categories], 'Sukses mengambil data categories');
    }

    public function getDetailCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
        } catch (\Exception $e) {
            return response()->internalServerError('Gagal mengambil data category', $e->getMessage());
        }

        return response()->ok(['category' => $category], 'Sukses mengambil data category');
    }

    public function store(Request $request)
    {
        request()->validate([
            'nama_kategori' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $category = Category::create([
                'nama_kategori' => $request->input('nama_kategori')
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->internalServerError('Gagal menambahkan data', $e->getMessage());
        }

        return response()->created(['category' => $category], 'Berhasil menambahkan data');
    }

    public function update(Request $request, $id)
    {
        request()->validate([
            'nama_kategori' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $category = Category::findOrFail($id);

            $category->update([
                'nama_kategori' => $request->input('nama_kategori')
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->internalServerError('Gagal mengubah data', $e->getMessage());
        }

        return response()->ok(['category' => $category], 'Berhasil mengubah data ' . $category->nama_kategori);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $message = 'Berhasil menghapus data category ' . $category->judul;
        $category->delete();

        return response()->noContent($message);
    }
}
