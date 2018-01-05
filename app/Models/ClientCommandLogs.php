<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\ClientCommandLogs
 *
 * @property int $id
 * @property string|null $device_no
 * @property int|null $port_no
 * @property int|null $cmd
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientCommandLogs whereCmd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientCommandLogs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientCommandLogs whereDeviceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientCommandLogs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientCommandLogs wherePortNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ClientCommandLogs whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClientCommandLogs extends Model
{
    //

    protected $guarded = [];

    public static function addLog($deviceNo, $portNo, $cmd)
    {
        $model = new ClientCommandLogs();
        $model->device_no = $deviceNo;
        $model->port_no = $portNo;
        $model->cmd = $cmd;
        if(!$model->save()){
            Log::error('DB ERROR insert client_command_logs error : ' . $model->toJson() );
        }
    }
}
