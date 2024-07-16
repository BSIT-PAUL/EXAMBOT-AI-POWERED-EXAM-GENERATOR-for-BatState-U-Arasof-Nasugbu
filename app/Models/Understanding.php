<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Understanding extends Model
{
    use HasFactory;

    protected $table = 'understanding';

    protected $fillable = [
        'user_id',
        'topic',
        'items',
        'exam_type',
        'topic_outcomes',
    ];
}
