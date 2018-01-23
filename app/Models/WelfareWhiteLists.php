<?php

namespace App\Models;

use App\Libs\Helper;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WelfareWhiteLists
 *
 * @property int $id
 * @property int|null $card_id
 * @property string|null $phone
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareWhiteLists whereCardId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareWhiteLists whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareWhiteLists whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareWhiteLists wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareWhiteLists whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WelfareWhiteLists extends Model
{
    //
    protected $guarded = [];

    public static function getPhonesByCardId($cardId)
    {
        $m = self::whereCardId($cardId)->select('phone')->get();
        return $m ? Helper::transToOneDimensionalArray($m->toArray(), 'phone') : [];
    }
}
