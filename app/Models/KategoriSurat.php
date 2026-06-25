<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSurat extends Model
{
    protected $table = 'kategori_surat';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'retensi_tahun',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function suratMasuk()
    {
        return $this->belongsToMany(SuratMasuk::class, 'relasi_kategori_surat', 'kategori_id', 'surat_id', 'id_kategori', 'id_surat_masuk')
                    ->wherePivot('jenis_surat', 'MASUK');
    }

    public function suratKeluar()
    {
        return $this->belongsToMany(SuratKeluar::class, 'relasi_kategori_surat', 'kategori_id', 'surat_id', 'id_kategori', 'id_surat_keluar')
                    ->wherePivot('jenis_surat', 'KELUAR');
    }
}
