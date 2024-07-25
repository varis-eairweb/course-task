<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModuleTest extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'title', 'duration', 'instructions'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function courseModuleTestQuestion()
    {
        return $this->hasMany(CourseModuleTestQuestion::class);
    }
}
