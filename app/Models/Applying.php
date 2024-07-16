<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applying extends Model
{
    use HasFactory;


    protected $table = 'applying';

    protected $fillable = [
        'user_id',
        'topic',
        'items',
        'exam_type',
        'topic_outcomes',
    ];
}
