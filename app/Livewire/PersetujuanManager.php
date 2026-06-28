<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SuratKeluar;
use App\Models\RiwayatPersetujuan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class PersetujuanManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'tanggal_surat';
    public $sortDirection = 'desc';
    public $filterStatus = 'Review'; // default filter

    public $suratId = null;
    public $status_persetujuan = 'Approve';
    public $catatan = '';

    public $isProcessOpen = false;
    public $isHistoryOpen = false;
    public $historyData = [];

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
        Gate::authorize('view Persetujuan');

        $query = SuratKeluar::with(['creator', 'riwayatPersetujuan.approver']);

        // By default, only show relevant statuses for approval module
        if ($this->filterStatus) {
            if ($this->filterStatus == 'Ditolak') {
                // If filtering by Ditolak, query from RiwayatPersetujuan instead, since SuratKeluar goes back to Draft
                $query->whereHas('riwayatPersetujuan', function($q) {
                    $q->where('status', 'Reject');
                });
            } else {
                $query->where('status', $this->filterStatus);
            }
        } else {
            $query->whereIn('status', ['Review', 'Disetujui'])
                  ->orWhereHas('riwayatPersetujuan', function($q) {
                      $q->where('status', 'Reject');
                  });
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('nomor_surat', 'like', '%'.$this->search.'%')
                  ->orWhere('perihal', 'like', '%'.$this->search.'%')
                  ->orWhere('tujuan', 'like', '%'.$this->search.'%');
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.persetujuan-manager', [
            'suratKeluars' => $query->paginate(10)
        ])->layout('layouts.app')->title('Persetujuan Surat');
    }

    public function openProcessModal($id)
    {
        Gate::authorize('create Persetujuan');
        $this->suratId = $id;
        
        $latestApproval = RiwayatPersetujuan::where('id_surat_keluar', $id)
                                ->orderBy('tanggal_approval', 'desc')
                                ->first();

        if ($latestApproval) {
            $this->status_persetujuan = $latestApproval->status;
            $this->catatan = $latestApproval->catatan;
        } else {
            $this->status_persetujuan = 'Approve';
            $this->catatan = '';
        }

        $this->isProcessOpen = true;
    }

    public function closeProcessModal()
    {
        $this->isProcessOpen = false;
        $this->suratId = null;
    }

    public function processApproval()
    {
        Gate::authorize('create Persetujuan');

        $this->validate([
            'status_persetujuan' => 'required|in:Approve,Reject',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            // Update Surat Status
            $surat = SuratKeluar::findOrFail($this->suratId);
            
            // If Approved -> Disetujui
            // If Rejected -> Draft (so author can edit again)
            $newStatus = ($this->status_persetujuan === 'Approve') ? 'Disetujui' : 'Draft';
            
            $surat->update([
                'status' => $newStatus
            ]);

            // Create History
            RiwayatPersetujuan::create([
                'id_surat_keluar' => $this->suratId,
                'approver_id' => auth()->id(),
                'status' => $this->status_persetujuan,
                'catatan' => $this->catatan,
                'tanggal_approval' => now()
            ]);

            DB::commit();

            session()->flash('message', 'Persetujuan berhasil diproses.');
            $this->closeProcessModal();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showHistory($id)
    {
        $this->historyData = RiwayatPersetujuan::with('approver')
                                ->where('id_surat_keluar', $id)
                                ->orderBy('tanggal_approval', 'desc')
                                ->get();
        $this->isHistoryOpen = true;
    }

    public function closeHistoryModal()
    {
        $this->isHistoryOpen = false;
    }
}
