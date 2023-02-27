<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_buku',
        'category_id',
        'judul',
        'slug',
        'penerbit',
        'pengarang',
        'tahun',
        'stok',
        'path',
    ];

    public function scopeFilter($query, array $filter)
    {
        $query->when($filter['search'] ?? false, function ($query, $search) {
            return $query->where('judul', 'like', '%' . $search . '%')
                ->orWhere('pengarang', 'like', '%' . $search . '%')
                ->orWhere('kode_buku', 'like', '%' . $search . '%');
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_buku', 'id');
    }
}
