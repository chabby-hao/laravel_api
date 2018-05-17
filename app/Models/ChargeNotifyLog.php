<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChargeNotifyLog extends Model
{

    protected $guarded = [];

    protected $table = 'charge_notify_log';

    public static function addLog($deviceNo, $portNo, $type)
    {
        $model = new self();
        $model->device_no = $deviceNo;
        $model->port_no = $portNo;
        $model->type = $type;
        return $model->save();
    }

}