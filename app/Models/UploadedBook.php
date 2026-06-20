<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadedBook extends Model
{
    protected $fillable = [
        'title', 'file_path', 'file_type', 'extracted_text',
        'class_id', 'subject_id', 'status', 'uploaded_by',
    ];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
