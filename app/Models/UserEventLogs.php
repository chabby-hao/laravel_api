<?php

namespace App\Models;

use App\Services\UserService;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserEventLogs
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $phone
 * @property int|null $type 10=用户扫码，20=点击开始充电，30=点击结束充电
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEventLogs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEventLogs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEventLogs wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEventLogs whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEventLogs whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserEventLogs whereUserId($value)
 * @mixin \Eloquent
 */
class UserEventLogs extends Model
{

    const TYPE_SCAN_CODE = 10;
    const TYPE_START_CHARGE = 20;
    const TYPE_END_CHARGE = 30;

    protected $guarded = [];

    protected $table = 'user_event_log';

    public static function getTypeMap($type = null)
    {
        $map = [
            self::TYPE_SCAN_CODE => '用户扫码',
            self::TYPE_START_CHARGE => '用户点击开始充电',
            self::TYPE_END_CHARGE => '用户点击结束充电',
        ];
        return $map === null ? $map : $map[$type];
    }

    public static function addLog($type)
    {
        $model = new UserEventLogs();
        $model->user_id = UserService::getUserId();
        $model->phone = UserService::getUserPhone();
        $model->type = $type;
        return $model->save();
    }

}
