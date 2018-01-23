<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WelfareUsers
 *
 * @property int $id
 * @property string $creared_at
 * @property \Carbon\Carbon $updated_at
 * @property string|null $phone
 * @property int|null $card_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareUsers whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareUsers whereCrearedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareUsers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareUsers wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareUsers whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareUsers whereUserId($value)
 */
class WelfareUsers extends Model
{
    //
    protected $guarded = [];
}
