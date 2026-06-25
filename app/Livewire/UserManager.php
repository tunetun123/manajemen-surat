<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $filterRole = '';

    public $name;
    public $email;
    public $password;
    public $role_name;
    public $jabatan;
    public $id_unit;
    public $userId = null;
    public $isOpen = false;
    public $isConfirmDeleteOpen = false;
    public $deleteId = null;

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
    public function updatingFilterRole() { $this->resetPage(); }

    public function render()
    {
        $query = User::with(['roles', 'unitKerja']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterRole) {
            $query->whereHas('roles', function($q) {
                $q->where('name', $this->filterRole);
            });
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return view('livewire.user-manager', [
            'users' => $query->paginate(10),
            'roles' => Role::all(),
            'unitKerjas' => \App\Models\UnitKerja::where('is_active', true)->orderBy('nama_unit')->get()
        ])->layout('layouts.app');
    }

    public function create()
    {
        \Illuminate\Support\Facades\Gate::authorize('create Pengguna');
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
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role_name = '';
        $this->jabatan = '';
        $this->id_unit = '';
        $this->userId = null;
    }

    public function store()
    {
        if ($this->userId) {
            \Illuminate\Support\Facades\Gate::authorize('edit Pengguna');
        } else {
            \Illuminate\Support\Facades\Gate::authorize('create Pengguna');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'role_name' => 'required',
            'jabatan' => 'nullable|string|max:255',
            'id_unit' => 'nullable|exists:unit_kerja,id_unit',
        ];

        if (!$this->userId) {
            $rules['password'] = 'required|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'jabatan' => $this->jabatan,
            'id_unit' => $this->id_unit ?: null,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user = User::updateOrCreate(['id' => $this->userId], $data);
        
        $user->syncRoles([$this->role_name]);

        session()->flash('message',
            $this->userId ? 'Pengguna berhasil diperbarui.' : 'Pengguna berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        \Illuminate\Support\Facades\Gate::authorize('edit Pengguna');
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->jabatan = $user->jabatan;
        $this->id_unit = $user->id_unit;
        $this->role_name = $user->roles->first()->name ?? '';
        $this->password = '';

        $this->openModal();
    }

    public function deleteConfirm($id)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete Pengguna');
        $this->deleteId = $id;
        $this->isConfirmDeleteOpen = true;
    }

    public function delete()
    {
        \Illuminate\Support\Facades\Gate::authorize('delete Pengguna');
        User::find($this->deleteId)->delete();
        session()->flash('message', 'Pengguna berhasil dihapus.');
        $this->isConfirmDeleteOpen = false;
    }
}
