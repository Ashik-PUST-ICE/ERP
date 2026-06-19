<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'package_id',
        'name',
        'max_questions',
        'max_teachers',
        'max_question_sets',
        'monthly_price',
        'yearly_price',
        'start_date',
        'end_date',
        'order_id',
        'status',
        'is_trail',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
