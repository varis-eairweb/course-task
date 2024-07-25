<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'title', 'status', 'is_testable', 'description'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseModuleTest()
    {
        return $this->hasOne(CourseModuleTest::class);
    }

    public function moduleMaterial()
    {
        return $this->hasMany(ModuleMaterial::class);
    }
}
