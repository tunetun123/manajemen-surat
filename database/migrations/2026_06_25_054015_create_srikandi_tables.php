<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop old tables
        Schema::dropIfExists('documents');
        Schema::dropIfExists('document_types');
        Schema::dropIfExists('categories');

        // 2. unit_kerja
        Schema::create('unit_kerja', function (Blueprint $table) {
            $table->id('id_unit');
            $table->string('nama_unit');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Alter users
        Schema::table('users', function (Blueprint $table) {
            $table->string('jabatan')->nullable()->after('email');
            $table->unsignedBigInteger('id_unit')->nullable()->after('jabatan');
            $table->foreign('id_unit')->references('id_unit')->on('unit_kerja')->onDelete('set null');
        });

        // 4. kategori_surat
        Schema::create('kategori_surat', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('kode_kategori');
            $table->string('nama_kategori');
            $table->integer('retensi_tahun')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 5. surat_masuk
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id('id_surat_masuk');
            $table->string('nomor_agenda')->nullable();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->date('tanggal_terima');
            $table->string('pengirim');
            $table->string('perihal');
            $table->text('ringkasan')->nullable();
            $table->enum('sifat_surat', ['Biasa', 'Penting', 'Rahasia'])->default('Biasa');
            $table->string('status')->default('Baru');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        // 6. disposisi
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id('id_disposisi');
            $table->unsignedBigInteger('id_surat_masuk');
            $table->unsignedBigInteger('dari_user');
            $table->unsignedBigInteger('kepada_user');
            $table->text('instruksi');
            $table->dateTime('tanggal_disposisi');
            $table->date('batas_waktu')->nullable();
            $table->enum('status', ['Belum Diproses', 'Diproses', 'Selesai'])->default('Belum Diproses');
            $table->timestamps();

            $table->foreign('id_surat_masuk')->references('id_surat_masuk')->on('surat_masuk')->onDelete('cascade');
            $table->foreign('dari_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kepada_user')->references('id')->on('users')->onDelete('cascade');
        });

        // 7. surat_keluar
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id('id_surat_keluar');
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat');
            $table->string('tujuan');
            $table->string('perihal');
            $table->text('isi_ringkas')->nullable();
            $table->enum('sifat_surat', ['Biasa', 'Penting', 'Rahasia'])->default('Biasa');
            $table->enum('status', ['Draft', 'Review', 'Disetujui', 'Dikirim'])->default('Draft');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        // 8. riwayat_persetujuan_surat_keluar
        Schema::create('riwayat_persetujuan_surat_keluar', function (Blueprint $table) {
            $table->id('id_approval');
            $table->unsignedBigInteger('id_surat_keluar');
            $table->unsignedBigInteger('approver_id');
            $table->enum('status', ['Pending', 'Approve', 'Reject'])->default('Pending');
            $table->text('catatan')->nullable();
            $table->dateTime('tanggal_approval')->nullable();
            $table->timestamps();

            $table->foreign('id_surat_keluar')->references('id_surat_keluar')->on('surat_keluar')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 9. lampiran
        Schema::create('lampiran', function (Blueprint $table) {
            $table->id('id_lampiran');
            $table->enum('jenis_surat', ['MASUK', 'KELUAR']);
            $table->unsignedBigInteger('surat_id');
            $table->string('nama_file');
            $table->string('path_file');
            $table->unsignedBigInteger('ukuran_file')->default(0);
            $table->dateTime('uploaded_at');
            $table->timestamps();
            
            // Note: surat_id is polymorphic based on jenis_surat, so no strict foreign key
        });

        // 10. relasi_kategori_surat
        Schema::create('relasi_kategori_surat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            $table->enum('jenis_surat', ['MASUK', 'KELUAR']);
            $table->unsignedBigInteger('surat_id');
            $table->timestamps();

            $table->foreign('kategori_id')->references('id_kategori')->on('kategori_surat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('srikandi_tables');
    }
};
