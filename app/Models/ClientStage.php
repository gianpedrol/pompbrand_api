<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientStage extends Model
{
    protected $table = 'table_client_phase_stage';

    protected $fillable = ['id_user', 'id_phase', 'id_stage'];
}
