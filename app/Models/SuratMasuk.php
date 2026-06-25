<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';
    protected $primaryKey = 'id_surat_masuk';

    protected $fillable = [
        'nomor_agenda',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima',
        'pengirim',
        'perihal',
        'ringkasan',
        'sifat_surat',
        'status',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function disposisi()
    {
        return $this->hasMany(Disposisi::class, 'id_surat_masuk', 'id_surat_masuk');
    }

    public function lampiran()
    {
        return $this->hasMany(Lampiran::class, 'surat_id', 'id_surat_masuk')->where('jenis_surat', 'MASUK');
    }

    public function kategori()
    {
        return $this->belongsToMany(KategoriSurat::class, 'relasi_kategori_surat', 'surat_id', 'kategori_id', 'id_surat_masuk', 'id_kategori')
                    ->wherePivot('jenis_surat', 'MASUK');
    }
}
