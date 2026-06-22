<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'title', 'surat_no', 'description', 'category_id', 'document_type_id', 
        'file_path', 'upload_date', 'notes'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
