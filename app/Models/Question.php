<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'subject_id',
        'chapter_id',
        'topic_id',
        'question_type_id',
        'question_text',
        'image',
        'correct_answer',
        'options_json',
        'explanation',
        'marks',
        'difficulty',
        'board_id',
        'stem_id',
        'year',
        'status',
        'source',
        'source_type',
        'book_upload_id',
        'created_by',
    ];

    public function academicClass()
    {
        return $this->belongsTo(AcademicClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
}
