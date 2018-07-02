<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Appointments
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $cabinet_id
 * @property int|null $expired_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointments whereCabinetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointments whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointments whereUserId($value)
 * @mixin \Eloquent
 * @property int|null $battery_level
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointments whereBatteryLevel($value)
 * @property int $state
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Appointments whereState($value)
 */
class Appointments extends Model
{

    protected $table = 'appointments';
    protected $primaryKey = 'id';
    protected $guarded = [];


}
