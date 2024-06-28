<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_code', 'course_title', 'semester_year'];

    public function chapters()
    {
        return $this->hasMany(CourseChapter::class);
    }
}
