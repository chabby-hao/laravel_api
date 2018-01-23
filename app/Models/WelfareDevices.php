<?php

namespace App\Models;

use App\Libs\Helper;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WelfareDevices
 *
 * @property int $id
 * @property int|null $card_id
 * @property string|null $device_no
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareDevices whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareDevices whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareDevices whereDeviceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareDevices whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareDevices whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WelfareDevices extends Model
{
    //
    protected $guarded = [];

    public static function getDeviceNosByCardId($cardId)
    {
        $m = self::whereCardId($cardId)->select('device_no')->get();
        return $m ? Helper::transToOneDimensionalArray($m->toArray(), 'device_no') : [];
    }
}
