<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_title',
        'course_code',
        'semester_year',
    ];

    public function chapters()
    {
        return $this->hasMany(CourseChapter::class);
    }
}
