<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;

class RoleManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';

    public $name;
    public $roleId = null;
    public $permissions = []; // Array to hold selected permission names
    public $isOpen = false;
    public $isConfirmDeleteOpen = false;
    public $deleteId = null;

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
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
        // Group permissions by their module (e.g. "view Dokumen" -> "Dokumen")
        $allPermissions = Permission::orderBy('name')->get()->groupBy(function($perm) {
            $parts = explode(' ', $perm->name, 2);
            return count($parts) > 1 ? $parts[1] : 'Lainnya';
        });

        return view('livewire.role-manager', [
            'roles' => Role::where('name', 'like', '%'.$this->search.'%')
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate(10),
            'allPermissions' => $allPermissions
        ])->layout('layouts.app')->title('Hak Akses');
    }

    public function create()
    {
        \Illuminate\Support\Facades\Gate::authorize('create Hak Akses');
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
        $this->roleId = null;
        $this->permissions = [];
    }

    public function toggleModule($module)
    {
        $modulePermissions = Permission::all()->filter(function($perm) use ($module) {
            $parts = explode(' ', $perm->name, 2);
            $permModule = count($parts) > 1 ? $parts[1] : 'Lainnya';
            return $permModule === $module;
        })->pluck('name')->toArray();
        
        $allSelected = true;
        foreach ($modulePermissions as $perm) {
            if (!in_array($perm, $this->permissions)) {
                $allSelected = false;
                break;
            }
        }

        if ($allSelected) {
            $this->permissions = array_values(array_diff($this->permissions, $modulePermissions));
        } else {
            $this->permissions = array_values(array_unique(array_merge($this->permissions, $modulePermissions)));
        }
    }

    public function store()
    {
        if ($this->roleId) {
            \Illuminate\Support\Facades\Gate::authorize('edit Hak Akses');
        } else {
            \Illuminate\Support\Facades\Gate::authorize('create Hak Akses');
        }

        $rules = $this->rules;
        if ($this->roleId) {
            $rules['name'] = 'required|string|max:255|unique:roles,name,' . $this->roleId;
        }

        $this->validate($rules);

        $role = Role::updateOrCreate(['id' => $this->roleId], [
            'name' => $this->name,
            'guard_name' => 'web'
        ]);

        // Sync selected permissions
        if ($role->name === 'super_admin') {
            // super_admin always gets all permissions implicitly or explicitly
            $role->syncPermissions(Permission::all());
        } else {
            $role->syncPermissions($this->permissions);
        }

        session()->flash('message',
            $this->roleId ? 'Hak akses berhasil diperbarui.' : 'Hak akses berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        \Illuminate\Support\Facades\Gate::authorize('edit Hak Akses');
        $role = Role::findOrFail($id);
        $this->roleId = $id;
        $this->name = $role->name;
        $this->permissions = $role->permissions->pluck('name')->toArray();

        $this->openModal();
    }

    public function deleteConfirm($id)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete Hak Akses');
        $this->deleteId = $id;
        $this->isConfirmDeleteOpen = true;
    }

    public function delete()
    {
        \Illuminate\Support\Facades\Gate::authorize('delete Hak Akses');
        Role::find($this->deleteId)->delete();
        session()->flash('message', 'Hak akses berhasil dihapus.');
        $this->isConfirmDeleteOpen = false;
    }
}
