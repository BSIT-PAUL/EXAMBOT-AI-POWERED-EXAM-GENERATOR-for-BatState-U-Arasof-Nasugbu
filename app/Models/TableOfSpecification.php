<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableOfSpecification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'examination_type',
        'course_code',
        'topic',
        'no_of_hours_a',
        'no_of_hours_b',
        'weight',
        'remembering',
        'remembering_percentage',
        'understanding',
        'understanding_percentage',
        'applying',
        'applying_percentage',
        'analyzing',
        'analyzing_percentage',
        'evaluating',
        'evaluating_percentage',
        'creating',
        'creating_percentage',
        'total_no_of_points',
        'overall_points',
    ];
}
