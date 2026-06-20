<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'name',
        'order',
        'status',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
