<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserDevice
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $battery_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereBatteryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereUserId($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereUpdatedAt($value)
 */
class UserDevice extends Model
{
    const STATE_UNUSEFUL = 0;//不可用
    const STATE_USEFUL = 1;//可用
    const STATE_USING = 2;//使用中
    const STATE_OPS = 3;//维护中

    protected $guarded = [];

    protected $table = 'user_device';

}
