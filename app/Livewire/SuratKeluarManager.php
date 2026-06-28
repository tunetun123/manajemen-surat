<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\SuratKeluar;
use App\Models\KategoriSurat;
use App\Models\Lampiran;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class SuratKeluarManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $sortField = 'tanggal_surat';
    public $sortDirection = 'desc';
    public $filterStatus = '';

    public $nomor_surat;
    public $tanggal_surat;
    public $tujuan;
    public $perihal;
    public $ringkasan;
    public $sifat_surat = 'Biasa';
    public $status = 'Draft';
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
        'tanggal_surat' => 'required|date',
        'tujuan' => 'required|string|max:255',
        'perihal' => 'required|string|max:255',
        'ringkasan' => 'nullable|string',
        'sifat_surat' => 'required|in:Biasa,Penting,Rahasia',
        'status' => 'required|in:Draft,Review,Disetujui,Dikirim',
        'selectedCategories' => 'array',
        'attachments.*' => 'nullable|file|max:10240',
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
    public function updatingFilterStatus() { $this->resetPage(); }

    public function render()
    {
        $query = SuratKeluar::with(['kategori', 'lampiran', 'creator']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%'.$this->search.'%')
                  ->orWhere('tujuan', 'like', '%'.$this->search.'%')
                  ->orWhere('perihal', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.surat-keluar-manager', [
            'suratKeluars' => $query->paginate(10),
            'kategoris' => KategoriSurat::where('is_active', true)->orderBy('nama_kategori')->get()
        ])->layout('layouts.app')->title('Daftar Surat Keluar');
    }

    public function create()
    {
        Gate::authorize('create Surat Keluar');
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
        $this->nomor_surat = '';
        $this->tanggal_surat = '';
        $this->tujuan = '';
        $this->perihal = '';
        $this->ringkasan = '';
        $this->sifat_surat = 'Biasa';
        $this->status = 'Draft';
        $this->selectedCategories = [];
        $this->upload = [];
        $this->attachments = [];
        $this->suratId = null;
    }

    public function store()
    {
        if ($this->suratId) {
            Gate::authorize('edit Surat Keluar');
        } else {
            Gate::authorize('create Surat Keluar');
        }

        $this->validate();

        DB::beginTransaction();
        try {
            $data = [
                'nomor_surat' => $this->nomor_surat,
                'tanggal_surat' => $this->tanggal_surat,
                'tujuan' => $this->tujuan,
                'perihal' => $this->perihal,
                'isi_ringkas' => $this->ringkasan,
                'sifat_surat' => $this->sifat_surat,
                'status' => $this->status,
            ];

            if (!$this->suratId) {
                $data['created_by'] = auth()->id();
                $surat = SuratKeluar::create($data);
            } else {
                $surat = SuratKeluar::findOrFail($this->suratId);
                $surat->update($data);
            }

            // Sync Categories
            $surat->kategori()->syncWithPivotValues($this->selectedCategories, ['jenis_surat' => 'KELUAR']);

            // Upload Attachments
            if (!empty($this->attachments)) {
                foreach ($this->attachments as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('lampiran/keluar', $filename, 'public');

                    Lampiran::create([
                        'jenis_surat' => 'KELUAR',
                        'surat_id' => $surat->id_surat_keluar,
                        'nama_file' => $file->getClientOriginalName(),
                        'path_file' => $path,
                        'ukuran_file' => $file->getSize(),
                        'uploaded_at' => now(),
                    ]);
                }
            }

            DB::commit();

            session()->flash('message', $this->suratId ? 'Surat Keluar berhasil diperbarui.' : 'Surat Keluar berhasil ditambahkan.');
            $this->closeModal();
            $this->resetInputFields();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        Gate::authorize('edit Surat Keluar');
        $surat = SuratKeluar::findOrFail($id);
        $this->suratId = $id;
        $this->nomor_surat = $surat->nomor_surat;
        $this->tanggal_surat = $surat->tanggal_surat;
        $this->tujuan = $surat->tujuan;
        $this->perihal = $surat->perihal;
        $this->ringkasan = $surat->isi_ringkas;
        $this->sifat_surat = $surat->sifat_surat;
        $this->status = $surat->status;
        
        $this->selectedCategories = $surat->kategori->pluck('id_kategori')->toArray();
        $this->upload = [];
        $this->attachments = [];

        $this->openModal();
    }

    public function view($id)
    {
        Gate::authorize('view Surat Keluar');
        $this->viewData = SuratKeluar::with(['kategori', 'lampiran', 'creator'])->findOrFail($id);
        $this->isViewModalOpen = true;
    }

    public function closeViewModal()
    {
        $this->isViewModalOpen = false;
        $this->viewData = null;
    }

    public function deleteLampiran($lampiranId)
    {
        Gate::authorize('edit Surat Keluar');
        $lampiran = Lampiran::findOrFail($lampiranId);
        if (Storage::disk('public')->exists($lampiran->path_file)) {
            Storage::disk('public')->delete($lampiran->path_file);
        }
        $lampiran->delete();
    }

    public function deleteConfirm($id)
    {
        Gate::authorize('delete Surat Keluar');
        $this->deleteId = $id;
        $this->isConfirmDeleteOpen = true;
    }

    public function delete()
    {
        Gate::authorize('delete Surat Keluar');
        $surat = SuratKeluar::findOrFail($this->deleteId);
        
        foreach ($surat->lampiran as $lamp) {
            if (Storage::disk('public')->exists($lamp->path_file)) {
                Storage::disk('public')->delete($lamp->path_file);
            }
            $lamp->delete();
        }
        
        $surat->delete();
        session()->flash('message', 'Surat Keluar berhasil dihapus.');
        $this->isConfirmDeleteOpen = false;
    }
}
