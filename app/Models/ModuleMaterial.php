<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleMaterial extends Model
{
    use HasFactory;
    protected $fillable = ['module_id', 'type', 'link'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
