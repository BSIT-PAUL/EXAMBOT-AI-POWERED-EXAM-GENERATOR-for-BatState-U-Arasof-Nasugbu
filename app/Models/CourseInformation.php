<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseInformation extends Model
{
    use HasFactory;

    protected $fillable = ['course_code', 'course_title', 'semester_year', 'user_id'];

    public function chapters()
    {
        return $this->hasMany(CourseChapters::class, 'course_information_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}