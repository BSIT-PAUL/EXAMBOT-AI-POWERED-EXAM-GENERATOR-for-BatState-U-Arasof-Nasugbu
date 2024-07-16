<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analyzing extends Model
{
    use HasFactory;


    protected $table = 'analyzing';

    protected $fillable = [
        'user_id',
        'topic',
        'items',
        'exam_type',
        'topic_outcomes',
    ];
}
