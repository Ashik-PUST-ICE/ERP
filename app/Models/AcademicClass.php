<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'institution_id',
        'name',
        'order',
        'status',
    ];

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'class_id');
    }
}
