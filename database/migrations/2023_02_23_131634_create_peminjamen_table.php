<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeminjamenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_buku');
            $table->foreign('id_buku')->references('id')->on('books')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('id_member');
            $table->foreign('id_member')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('tanggal_peminjaman');
            $table->string('tanggal_pengembalian');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peminjamen');
    }
}
