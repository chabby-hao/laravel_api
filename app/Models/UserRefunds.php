<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserRefunds
 *
 * @property int $id
 * @property int $user_id
 * @property float $refund_amount
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $state 10-提交退款，20-退款中，30-退款成功
 * @property int|null $desc
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserRefunds whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserRefunds whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserRefunds whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserRefunds whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserRefunds whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserRefunds whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserRefunds whereUserId($value)
 * @mixin \Eloquent
 */
class UserRefunds extends Model
{
    //
    const REFUND_STATE_INIT = 10;//初始化，提交退款
    const REFUND_STATE_PROCESSING = 20;//退款中
    const REFUND_STATE_SUCCESS = 30;//退款成功

    protected $guarded = [];


}
