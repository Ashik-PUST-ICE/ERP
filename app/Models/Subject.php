<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'name',
        'code',
        'order',
        'status',
    ];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
