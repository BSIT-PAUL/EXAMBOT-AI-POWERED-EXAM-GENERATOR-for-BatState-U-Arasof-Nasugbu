<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamTypeClassification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'topic', 'blooms_level', 'exam_type', 'topic_outcomes'
    ];
}
