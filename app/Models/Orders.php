<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Orders
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $order_no
 * @property string|null $pay_no
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $order_state 10-未付款，20-付款，30-退款
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Orders whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Orders whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Orders whereOrderNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Orders whereOrderState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Orders wherePayNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Orders whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Orders whereUserId($value)
 * @mixin \Eloquent
 * @property float|null $order_amount
 * @property string|null $pay_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Orders whereOrderAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Orders wherePayAt($value)
 * @property string|null $third_no
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Orders whereThirdNo($value)
 */
class Orders extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id', 'order_no','order_state','order_id','pay_no'];

    //订单状态
    const ORDER_STATE_INIT = 10;//初始化订单，未付款
    const ORDER_STATE_PAY = 20; //已支付
    const ORDER_STATE_REFUNDING = 30; //退款中
    const ORDER_STATE_REFUND_SUCCESS = 40;//退款成功


    public static function getOrdersByUserId($userId)
    {
        return self::whereUserId($userId)->get()->toArray();
    }

    /**
     * @param $data,user_id,pay_no
     * @return $this|Model
     */
    public function createOrder($data)
    {
        $order = [
            'order_no'=>date('YmdHis') . mt_rand(1000,9999),
            'order_state'=>self::ORDER_STATE_INIT,
        ];
        $order = array_merge($order, $data);
        $orderMod = new Orders();
        return $orderMod->create($order);
    }


}
