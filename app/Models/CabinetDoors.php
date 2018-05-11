<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CabinetDoors
 *
 * @property int $id
 * @property int|null $cabinet_id 柜子id
 * @property int|null $door_no 门编号
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CabinetDoors whereCabinetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CabinetDoors whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CabinetDoors whereDoorNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CabinetDoors whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\CabinetDoors whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CabinetDoors extends Model
{

    protected $guarded = [];

    protected $table = 'cabinet_doors';

}
