<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\KategoriSurat;
use Illuminate\Support\Facades\Gate;

class KategoriSuratManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'kode_kategori';
    public $sortDirection = 'asc';

    public $kode_kategori;
    public $nama_kategori;
    public $retensi_tahun = 0;
    public $kategoriId = null;
    
    public $isOpen = false;
    public $isConfirmDeactivateOpen = false;
    public $isConfirmDeleteOpen = false;
    public $isInactiveModalOpen = false;
    
    public $deactivateId = null;
    public $deleteId = null;

    protected $rules = [
        'kode_kategori' => 'required|string|max:50',
        'nama_kategori' => 'required|string|max:255',
        'retensi_tahun' => 'required|integer|min:0',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.kategori-surat-manager', [
            'kategoris' => KategoriSurat::where('is_active', true)
                ->where(function($q) {
                    $q->where('kode_kategori', 'like', '%'.$this->search.'%')
                      ->orWhere('nama_kategori', 'like', '%'.$this->search.'%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
            'inactiveKategoris' => KategoriSurat::where('is_active', false)->orderBy('kode_kategori')->get()
        ])->layout('layouts.app');
    }

    public function create()
    {
        Gate::authorize('create Kategori Surat');
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->kode_kategori = '';
        $this->nama_kategori = '';
        $this->retensi_tahun = 0;
        $this->kategoriId = null;
    }

    public function store()
    {
        if ($this->kategoriId) {
            Gate::authorize('edit Kategori Surat');
        } else {
            Gate::authorize('create Kategori Surat');
        }

        $this->validate();

        KategoriSurat::updateOrCreate(['id_kategori' => $this->kategoriId], [
            'kode_kategori' => $this->kode_kategori,
            'nama_kategori' => $this->nama_kategori,
            'retensi_tahun' => $this->retensi_tahun,
            'is_active' => true,
        ]);

        session()->flash('message',
            $this->kategoriId ? 'Kategori surat berhasil diperbarui.' : 'Kategori surat berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        Gate::authorize('edit Kategori Surat');
        $kategori = KategoriSurat::findOrFail($id);
        $this->kategoriId = $id;
        $this->kode_kategori = $kategori->kode_kategori;
        $this->nama_kategori = $kategori->nama_kategori;
        $this->retensi_tahun = $kategori->retensi_tahun;

        $this->openModal();
    }

    public function deactivateConfirm($id)
    {
        Gate::authorize('delete Kategori Surat');
        $this->deactivateId = $id;
        $this->isConfirmDeactivateOpen = true;
    }

    public function deactivate()
    {
        Gate::authorize('delete Kategori Surat'); // Reusing delete permission for deactivate
        $kategori = KategoriSurat::findOrFail($this->deactivateId);
        $kategori->update(['is_active' => false]);
        session()->flash('message', 'Kategori Surat berhasil dinonaktifkan.');
        $this->isConfirmDeactivateOpen = false;
        $this->deactivateId = null;
    }

    public function deleteConfirm($id)
    {
        Gate::authorize('delete Kategori Surat');
        
        // Cek apakah sudah digunakan di tabel relasi_kategori_surat
        $isUsed = \Illuminate\Support\Facades\DB::table('relasi_kategori_surat')->where('id_kategori', $id)->exists();
        
        if ($isUsed) {
            session()->flash('error', 'Kategori ini tidak dapat dihapus karena sudah digunakan pada surat. Silakan gunakan fitur nonaktifkan jika tidak diperlukan lagi.');
            return;
        }

        $this->deleteId = $id;
        $this->isConfirmDeleteOpen = true;
    }

    public function delete()
    {
        Gate::authorize('delete Kategori Surat');
        $kategori = KategoriSurat::findOrFail($this->deleteId);
        
        $isUsed = \Illuminate\Support\Facades\DB::table('relasi_kategori_surat')->where('id_kategori', $kategori->id_kategori)->exists();
        if ($isUsed) {
            session()->flash('error', 'Kategori ini tidak dapat dihapus karena sudah digunakan.');
            $this->isConfirmDeleteOpen = false;
            return;
        }

        $kategori->delete();
        session()->flash('message', 'Kategori Surat berhasil dihapus secara permanen.');
        $this->isConfirmDeleteOpen = false;
    }

    public function openInactiveModal()
    {
        $this->isInactiveModalOpen = true;
    }

    public function closeInactiveModal()
    {
        $this->isInactiveModalOpen = false;
    }

    public function reactivate($id)
    {
        Gate::authorize('delete Kategori Surat');
        $kategori = KategoriSurat::findOrFail($id);
        $kategori->update(['is_active' => true]);
        session()->flash('message', 'Kategori surat berhasil diaktifkan kembali.');
    }
}
