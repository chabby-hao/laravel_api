<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cabinets
 *
 * @property int $id
 * @property string|null $address 地址
 * @property int|null $ops 运维状态，0=关闭，1=开启
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $qr
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cabinets whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cabinets whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cabinets whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cabinets whereOps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cabinets whereQr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cabinets whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $cabinet_no 编号
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cabinets whereCabinetNo($value)
 */
class Cabinets extends Model
{

    protected $fillable = [
        'cabinet_no',
        'address',
        'qr',
        'lat',
        'lng',
    ];

    //protected $guarded = [];

    protected $table = 'cabinets';

}
