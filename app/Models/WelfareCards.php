<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WelfareCards
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $limit_user 是否限制白名单，0=不限制，1=限制
 * @property string|null $url 二维码链接
 * @property string|null $url_img 二维码图片地址
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareCards whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareCards whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareCards whereLimitUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareCards whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareCards whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareCards whereUrlImg($value)
 * @mixin \Eloquent
 * @property string|null $company
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareCards whereCompany($value)
 * @property string|null $card_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\WelfareCards whereCardName($value)
 */
class WelfareCards extends Model
{
    //
    protected $guarded = [];
}
