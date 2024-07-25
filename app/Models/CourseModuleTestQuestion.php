<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModuleTestQuestion extends Model
{
    use HasFactory;
    protected $fillable = ['course_module_test_id', 'question', 'status', 'option_1', 'option_2', 'option_3', 'option_4', 'answer'];

    public function courseModuleTest()
    {
        return $this->belongsTo(CourseModuleTest::class);
    }
}
