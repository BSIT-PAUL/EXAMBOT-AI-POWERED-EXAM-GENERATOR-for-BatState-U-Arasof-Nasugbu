<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_detail_id',
        'chapter',
        'topics_reading_list',
        'wks',
        'topic_outcomes',
    ];

    public function courseDetail()
    {
        return $this->belongsTo(CourseDetail::class);
    }
}