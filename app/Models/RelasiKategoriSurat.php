<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RelasiKategoriSurat extends Model
{
    protected $table = 'relasi_kategori_surat';

    protected $fillable = [
        'kategori_id',
        'jenis_surat',
        'surat_id',
    ];
}
