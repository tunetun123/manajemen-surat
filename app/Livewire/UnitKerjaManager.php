<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Gate;

class UnitKerjaManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'nama_unit';
    public $sortDirection = 'asc';

    public $nama_unit;
    public $keterangan;
    public $unitId = null;
    
    public $isOpen = false;
    public $isConfirmDeactivateOpen = false;
    public $isConfirmDeleteOpen = false;
    public $isInactiveModalOpen = false;
    
    public $deactivateId = null;
    public $deleteId = null;

    protected $rules = [
        'nama_unit' => 'required|string|max:255',
        'keterangan' => 'nullable|string',
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
        return view('livewire.unit-kerja-manager', [
            'units' => UnitKerja::where('is_active', true)
                ->where(function($q) {
                    $q->where('nama_unit', 'like', '%'.$this->search.'%')
                      ->orWhere('keterangan', 'like', '%'.$this->search.'%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
            'inactiveUnits' => UnitKerja::where('is_active', false)->orderBy('nama_unit')->get()
        ])->layout('layouts.app');
    }

    public function create()
    {
        Gate::authorize('create Unit Kerja');
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
        $this->nama_unit = '';
        $this->keterangan = '';
        $this->unitId = null;
    }

    public function store()
    {
        if ($this->unitId) {
            Gate::authorize('edit Unit Kerja');
        } else {
            Gate::authorize('create Unit Kerja');
        }

        $this->validate();

        UnitKerja::updateOrCreate(['id_unit' => $this->unitId], [
            'nama_unit' => $this->nama_unit,
            'keterangan' => $this->keterangan,
            'is_active' => true,
        ]);

        session()->flash('message',
            $this->unitId ? 'Unit Kerja berhasil diperbarui.' : 'Unit Kerja berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        Gate::authorize('edit Unit Kerja');
        $unit = UnitKerja::findOrFail($id);
        $this->unitId = $id;
        $this->nama_unit = $unit->nama_unit;
        $this->keterangan = $unit->keterangan;

        $this->openModal();
    }

    public function deactivateConfirm($id)
    {
        Gate::authorize('delete Unit Kerja');
        $this->deactivateId = $id;
        $this->isConfirmDeactivateOpen = true;
    }

    public function deactivate()
    {
        Gate::authorize('delete Unit Kerja'); // Reusing delete permission for deactivate
        $unit = UnitKerja::findOrFail($this->deactivateId);
        $unit->update(['is_active' => false]);
        session()->flash('message', 'Unit Kerja berhasil dinonaktifkan.');
        $this->isConfirmDeactivateOpen = false;
        $this->deactivateId = null;
    }

    public function deleteConfirm($id)
    {
        Gate::authorize('delete Unit Kerja');
        
        // Cek apakah sudah digunakan di tabel users
        $isUsed = \App\Models\User::where('id_unit', $id)->exists();
        
        if ($isUsed) {
            session()->flash('error', 'Unit Kerja ini tidak dapat dihapus karena sudah digunakan oleh pengguna. Silakan gunakan fitur nonaktifkan jika tidak diperlukan lagi.');
            return;
        }

        $this->deleteId = $id;
        $this->isConfirmDeleteOpen = true;
    }

    public function delete()
    {
        Gate::authorize('delete Unit Kerja');
        $unit = UnitKerja::findOrFail($this->deleteId);
        
        $isUsed = \App\Models\User::where('id_unit', $unit->id_unit)->exists();
        if ($isUsed) {
            session()->flash('error', 'Unit Kerja ini tidak dapat dihapus karena sudah digunakan.');
            $this->isConfirmDeleteOpen = false;
            return;
        }

        $unit->delete();
        session()->flash('message', 'Unit Kerja berhasil dihapus secara permanen.');
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
        Gate::authorize('delete Unit Kerja');
        $unit = UnitKerja::findOrFail($id);
        $unit->update(['is_active' => true]);
        session()->flash('message', 'Unit Kerja berhasil diaktifkan kembali.');
    }
}
