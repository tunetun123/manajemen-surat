<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Document;
use App\Models\Category;
use App\Models\DocumentType;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DocumentManager extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $filterCategory = '';
    public $filterType = '';

    public $title;
    public $document_number;
    public $category_id;
    public $document_type_id;
    public $description;
    public $file;
    public $documentId = null;

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
    public function updatingFilterCategory() { $this->resetPage(); }
    public function updatingFilterType() { $this->resetPage(); }

    public function render()
    {
        $query = Document::with(['category', 'documentType']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                  ->orWhere('document_number', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }

        if ($this->filterType) {
            $query->where('document_type_id', $this->filterType);
        }

        if (in_array($this->sortField, ['title', 'document_number', 'id'])) {
            $query->orderBy($this->sortField, $this->sortDirection);
        } else {
            $query->orderBy('id', 'desc');
        }

        return view('livewire.document-manager', [
            'documents' => $query->paginate(10),
            'categories' => Category::where('is_active', true)->orderBy('name')->get(),
            'documentTypes' => DocumentType::where('is_active', true)->orderBy('name')->get()
        ])->layout('layouts.app');
    }

    public function create()
    {
        \Illuminate\Support\Facades\Gate::authorize('create Dokumen');
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
        $this->title = '';
        $this->document_number = '';
        $this->category_id = '';
        $this->document_type_id = '';
        $this->description = '';
        $this->file = null;
        $this->documentId = null;
    }

    public function store()
    {
        if ($this->documentId) {
            \Illuminate\Support\Facades\Gate::authorize('edit Dokumen');
        } else {
            \Illuminate\Support\Facades\Gate::authorize('create Dokumen');
        }

        $rules = [
            'title' => 'required|string|max:255',
            'category_id' => 'required',
            'document_type_id' => 'required',
            'document_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];

        // Require file if creating new
        if (!$this->documentId) {
            $rules['file'] = 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240'; // 10MB
        } else {
            $rules['file'] = 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240';
        }

        $this->validate($rules);

        $data = [
            'title' => $this->title,
            'category_id' => $this->category_id,
            'document_type_id' => $this->document_type_id,
            'document_number' => $this->document_number,
            'description' => $this->description,
        ];

        if ($this->file) {
            $path = $this->file->store('documents', 'public');
            $data['file_path'] = $path;
            
            // Delete old file if updating
            if ($this->documentId) {
                $oldDoc = Document::find($this->documentId);
                if ($oldDoc && $oldDoc->file_path && Storage::disk('public')->exists($oldDoc->file_path)) {
                    Storage::disk('public')->delete($oldDoc->file_path);
                }
            }
        }

        Document::updateOrCreate(['id' => $this->documentId], $data);

        session()->flash('message',
            $this->documentId ? 'Dokumen berhasil diperbarui.' : 'Dokumen berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        \Illuminate\Support\Facades\Gate::authorize('edit Dokumen');
        $document = Document::findOrFail($id);
        $this->documentId = $id;
        $this->title = $document->title;
        $this->document_number = $document->document_number;
        $this->category_id = $document->category_id;
        $this->document_type_id = $document->document_type_id;
        $this->description = $document->description;

        $this->openModal();
    }

    public function deleteConfirm($id)
    {
        \Illuminate\Support\Facades\Gate::authorize('delete Dokumen');
        $this->deleteId = $id;
        $this->isConfirmDeleteOpen = true;
    }

    public function delete()
    {
        \Illuminate\Support\Facades\Gate::authorize('delete Dokumen');
        $doc = Document::find($this->deleteId);
        if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
            Storage::disk('public')->delete($doc->file_path);
        }
        $doc->delete();
        session()->flash('message', 'Dokumen berhasil dihapus.');
        $this->isConfirmDeleteOpen = false;
    }
}
