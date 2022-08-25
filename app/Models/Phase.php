<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    protected $table = 'phases';

    protected $fillable = ['phase_name'];

    public function stages()
    {
        return $this->hasMany(Stage::class, 'phase_id');
    }
}
