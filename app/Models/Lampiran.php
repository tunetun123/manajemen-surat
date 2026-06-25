<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    protected $table = 'lampiran';
    protected $primaryKey = 'id_lampiran';

    protected $fillable = [
        'jenis_surat',
        'surat_id',
        'nama_file',
        'path_file',
        'ukuran_file',
        'uploaded_at',
    ];
}
