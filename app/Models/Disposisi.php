<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    protected $table = 'disposisi';
    protected $primaryKey = 'id_disposisi';

    protected $fillable = [
        'id_surat_masuk',
        'dari_user',
        'kepada_user',
        'instruksi',
        'tanggal_disposisi',
        'batas_waktu',
        'status',
    ];

    public function suratMasuk()
    {
        return $this->belongsTo(SuratMasuk::class, 'id_surat_masuk', 'id_surat_masuk');
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'dari_user');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'kepada_user');
    }
}
