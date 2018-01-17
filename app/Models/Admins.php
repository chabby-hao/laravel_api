<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Admins
 *
 * @property int $id
 * @property int|null $name
 * @property int|null $pwd
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins wherePwd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admins whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Admins extends Model
{
    //

    protected $guarded = [];


}
