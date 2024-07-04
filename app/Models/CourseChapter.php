<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseChapters extends Model
{
    use HasFactory;

    protected $fillable = ['course_information_id', 'main_topic_number', 'main_topic', 'topic_outcomes'];

    public function course()
    {
        return $this->belongsTo(CourseInformation::class, 'course_information_id');
    }
}