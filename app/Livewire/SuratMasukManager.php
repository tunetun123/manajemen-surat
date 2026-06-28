<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\SuratMasuk;
use App\Models\KategoriSurat;
use App\Models\Lampiran;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SuratMasukManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $sortField = 'tanggal_surat';
    public $sortDirection = 'desc';

    public $nomor_agenda;
    public $nomor_surat;
    public $tanggal_surat;
    public $tanggal_terima;
    public $pengirim;
    public $perihal;
    public $ringkasan;
    public $sifat_surat = 'Biasa';
    public $selectedCategories = [];
    public $upload = []; // For file input
    public $attachments = []; // For storing all selected files
    
    public $suratId = null;
    public $isOpen = false;
    public $isConfirmDeleteOpen = false;
    public $isViewModalOpen = false;
    public $viewData = null;
    public $deleteId = null;

    protected $rules = [
        'nomor_surat' => 'required|string|max:255',
        'nomor_agenda' => 'nullable|string|max:255',
        'tanggal_surat' => 'required|date',
        'tanggal_terima' => 'required|date',
        'pengirim' => 'required|string|max:255',
        'perihal' => 'required|string|max:255',
        'ringkasan' => 'nullable|string',
        'sifat_surat' => 'required|in:Biasa,Penting,Rahasia',
        'selectedCategories' => 'array',
        'attachments.*' => 'nullable|file|max:10240', // max 10MB per file
        'upload.*' => 'nullable|file|max:10240',
    ];

    public function updatedUpload()
    {
        if (is_array($this->upload)) {
            foreach ($this->upload as $file) {
                $this->attachments[] = $file;
            }
        } else {
            $this->attachments[] = $this->upload;
        }
        $this->reset('upload');
    }

    public function removeAttachment($index)
    {
        unset($this->attachments[$index]);
        $this->attachments = array_values($this->attachments);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $query = SuratMasuk::with(['kategori', 'lampiran', 'creator']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%'.$this->search.'%')
                  ->orWhere('pengirim', 'like', '%'.$this->search.'%')
                  ->orWhere('perihal', 'like', '%'.$this->search.'%');
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.surat-masuk-manager', [
            'suratMasuks' => $query->paginate(10),
            'kategoris' => KategoriSurat::where('is_active', true)->orderBy('nama_kategori')->get()
        ])->layout('layouts.app')->title('Daftar Surat Masuk');
    }

    public function create()
    {
        Gate::authorize('create Surat Masuk');
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
        $this->nomor_agenda = '';
        $this->nomor_surat = '';
        $this->tanggal_surat = '';
        $this->tanggal_terima = '';
        $this->pengirim = '';
        $this->perihal = '';
        $this->ringkasan = '';
        $this->sifat_surat = 'Biasa';
        $this->selectedCategories = [];
        $this->upload = [];
        $this->attachments = [];
        $this->suratId = null;
    }

    public function store()
    {
        if ($this->suratId) {
            Gate::authorize('edit Surat Masuk');
        } else {
            Gate::authorize('create Surat Masuk');
        }

        $this->validate();

        DB::beginTransaction();
        try {
            $data = [
                'nomor_agenda' => $this->nomor_agenda,
                'nomor_surat' => $this->nomor_surat,
                'tanggal_surat' => $this->tanggal_surat,
                'tanggal_terima' => $this->tanggal_terima,
                'pengirim' => $this->pengirim,
                'perihal' => $this->perihal,
                'ringkasan' => $this->ringkasan,
                'sifat_surat' => $this->sifat_surat,
            ];

            if (!$this->suratId) {
                $data['created_by'] = auth()->id();
                $data['status'] = 'Baru';
                $surat = SuratMasuk::create($data);
            } else {
                $surat = SuratMasuk::findOrFail($this->suratId);
                $surat->update($data);
            }

            // Sync Categories
            $surat->kategori()->syncWithPivotValues($this->selectedCategories, ['jenis_surat' => 'MASUK']);

            // Upload Attachments
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('lampiran/masuk', $filename, 'public');

                    Lampiran::create([
                        'jenis_surat' => 'MASUK',
                        'surat_id' => $surat->id_surat_masuk,
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $path,
                        'ukuran_file' => $file->getSize(),
                        'uploaded_at' => now(),
                    ]);
                }
            }

            DB::commit();

            session()->flash('message', $this->suratId ? 'Surat Masuk berhasil diperbarui.' : 'Surat Masuk berhasil ditambahkan.');
            $this->closeModal();
            $this->resetInputFields();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        Gate::authorize('edit Surat Masuk');
        $surat = SuratMasuk::findOrFail($id);
        $this->suratId = $id;
        $this->nomor_agenda = $surat->nomor_agenda;
        $this->nomor_surat = $surat->nomor_surat;
        $this->tanggal_surat = $surat->tanggal_surat;
        $this->tanggal_terima = $surat->tanggal_terima;
        $this->pengirim = $surat->pengirim;
        $this->perihal = $surat->perihal;
        $this->ringkasan = $surat->ringkasan;
        $this->sifat_surat = $surat->sifat_surat;
        
        $this->selectedCategories = $surat->kategori->pluck('id_kategori')->toArray();
        $this->upload = [];
        $this->attachments = [];

        $this->openModal();
    }

    public function view($id)
    {
        Gate::authorize('view Surat Masuk');
        $this->viewData = SuratMasuk::with(['kategori', 'lampiran', 'creator'])->findOrFail($id);
        $this->isViewModalOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->viewData = null;
    }

    public function deleteLampiran($lampiranId)
    {
        Gate::authorize('edit Surat Masuk');
        $lampiran = Lampiran::findOrFail($lampiranId);
        if (Storage::disk('public')->exists($lampiran->path_file)) {
            Storage::disk('public')->delete($lampiran->path_file);
        }
        $lampiran->delete();
    }

    public function deleteConfirm($id)
    {
        Gate::authorize('delete Surat Masuk');
        $this->deleteId = $id;
        $this->isConfirmDeleteOpen = true;
    }

    public function delete()
    {
        Gate::authorize('delete Surat Masuk');
        $surat = SuratMasuk::findOrFail($this->deleteId);
        
        // Delete all lampiran physically
        foreach ($surat->lampiran as $lamp) {
            if (Storage::disk('public')->exists($lamp->path_file)) {
                Storage::disk('public')->delete($lamp->path_file);
            }
            $lamp->delete();
        }
        
        $surat->delete();
        session()->flash('message', 'Surat Masuk berhasil dihapus.');
        $this->isConfirmDeleteOpen = false;
    }
}
