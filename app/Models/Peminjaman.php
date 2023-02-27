<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_buku',
        'id_member',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'status',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'id_member', 'id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'id_buku', 'id');
    }
}
