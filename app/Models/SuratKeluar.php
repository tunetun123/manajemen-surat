<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';
    protected $primaryKey = 'id_surat_keluar';

    protected $fillable = [
        'nomor_surat',
        'tanggal_surat',
        'tujuan',
        'perihal',
        'isi_ringkas',
        'sifat_surat',
        'status',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lampiran()
    {
        return $this->hasMany(Lampiran::class, 'surat_id', 'id_surat_keluar')->where('jenis_surat', 'KELUAR');
    }

    public function kategori()
    {
        return $this->belongsToMany(KategoriSurat::class, 'relasi_kategori_surat', 'surat_id', 'kategori_id', 'id_surat_keluar', 'id_kategori')
                    ->wherePivot('jenis_surat', 'KELUAR');
    }

    public function riwayatPersetujuan()
    {
        return $this->hasMany(RiwayatPersetujuan::class, 'id_surat_keluar', 'id_surat_keluar');
    }
}
