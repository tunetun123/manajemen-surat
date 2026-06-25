<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPersetujuan extends Model
{
    protected $table = 'riwayat_persetujuan_surat_keluar';
    protected $primaryKey = 'id_approval';

    protected $fillable = [
        'id_surat_keluar',
        'approver_id',
        'status',
        'catatan',
        'tanggal_approval',
    ];

    public function suratKeluar()
    {
        return $this->belongsTo(SuratKeluar::class, 'id_surat_keluar', 'id_surat_keluar');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
