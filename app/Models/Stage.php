<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stages';

    protected $fillable = ['phase_id', 'stage'];

    public function users()
    {
        return $this->hasMany(ClientStage::class, 'id_stage');
    }
}
