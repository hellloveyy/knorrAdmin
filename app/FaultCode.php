<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FaultCode extends Model
{
    protected $table = 'fault_codes';

    const CATE_ABS = 'ABS';
    const CATE_EBS = 'EBS';

    public function faults()
    {
        return $this->hasMany(Fault::class, 'fault_id');
    }

    public static function getAllCodes()
    {
        return self::get()->map(function (FaultCode $code) {
            return ['id' => $code->id, 'name' => "{$code->cate} / {$code->fault_code}"];
        })->pluck('name', 'id')
            ->toArray();
    }
}
