<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientStage extends Model
{
    protected $table = 'client_phases';

    protected $fillable = ['id_user', 'id_phase', 'id_stage'];
}
