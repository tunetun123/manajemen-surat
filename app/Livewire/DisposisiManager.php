<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class DisposisiManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'tanggal_disposisi';
    public $sortDirection = 'desc';
    public $filterStatus = '';

    public $disposisiId = null;
    public $id_surat_masuk;
    public $kepada_user;
    public $instruksi;
    public $batas_waktu;
    public $status = 'Belum Diproses';

    public $isOpen = false;
    public $isProcessOpen = false;
    public $processStatus = '';

    protected $rules = [
        'id_surat_masuk' => 'required|exists:surat_masuk,id_surat_masuk',
        'kepada_user' => 'required|exists:users,id',
        'instruksi' => 'required|string',
        'batas_waktu' => 'required|date',
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

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }

    public function render()
    {
        $user = auth()->user();
        
        $query = Disposisi::with(['suratMasuk', 'pengirim', 'penerima']);

        // Only show dispositions sent by or received by the current user, unless they are super_admin
        if (!$user->hasRole('super_admin')) {
            $query->where(function($q) use ($user) {
                $q->where('dari_user', $user->id)
                  ->orWhere('kepada_user', $user->id);
            });
        }

        if ($this->search) {
            $query->whereHas('suratMasuk', function($q) {
                $q->where('nomor_surat', 'like', '%'.$this->search.'%')
                  ->orWhere('perihal', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.disposisi-manager', [
            'disposisis' => $query->paginate(10),
            'suratMasuks' => SuratMasuk::orderBy('tanggal_surat', 'desc')->get(),
            'users' => User::where('id', '!=', auth()->id())->orderBy('name')->get()
        ])->layout('layouts.app')->title('Disposisi Surat');
    }

    public function create($suratId = null)
    {
        Gate::authorize('create Disposisi');
        $this->resetInputFields();
        if ($suratId) {
            $this->id_surat_masuk = $suratId;
        }
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
        $this->disposisiId = null;
        $this->id_surat_masuk = '';
        $this->kepada_user = '';
        $this->instruksi = '';
        $this->batas_waktu = '';
        $this->status = 'Belum Diproses';
    }

    public function store()
    {
        Gate::authorize('create Disposisi');

        $this->validate();

        Disposisi::create([
            'id_surat_masuk' => $this->id_surat_masuk,
            'dari_user' => auth()->id(),
            'kepada_user' => $this->kepada_user,
            'instruksi' => $this->instruksi,
            'tanggal_disposisi' => now(),
            'batas_waktu' => $this->batas_waktu,
            'status' => 'Belum Diproses',
        ]);

        // Update surat masuk status if it's still 'Baru'
        $surat = SuratMasuk::find($this->id_surat_masuk);
        if ($surat->status === 'Baru') {
            $surat->update(['status' => 'Didisposisikan']);
        }

        session()->flash('message', 'Disposisi berhasil dibuat.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function openProcessModal($id)
    {
        $disposisi = Disposisi::findOrFail($id);
        
        // Ensure only the receiver can process it, or super_admin
        if ($disposisi->kepada_user !== auth()->id() && !auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Anda tidak berhak memproses disposisi ini.');
            return;
        }

        $this->disposisiId = $id;
        $this->processStatus = $disposisi->status;
        $this->isProcessOpen = true;
    }

    public function closeProcessModal()
    {
        $this->isProcessOpen = false;
    }

    public function processDisposisi()
    {
        $this->validate([
            'processStatus' => 'required|in:Belum Diproses,Diproses,Selesai'
        ]);

        $disposisi = Disposisi::findOrFail($this->disposisiId);
        $disposisi->update(['status' => $this->processStatus]);

        session()->flash('message', 'Status disposisi berhasil diperbarui.');
        $this->closeProcessModal();
    }
}
