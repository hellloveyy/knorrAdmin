<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Fault extends Model
{
    protected $table = 'faults';

    public function code()
    {
        return $this->belongsTo(FaultCode::class, 'fault_id');
    }
}
