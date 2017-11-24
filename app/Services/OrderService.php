<?php
namespace App\Services;

use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{

    /**
     * 创建订单
     * @param $userId
     * @return bool|string
     */
    public static function createOrder($userId, $orderAmount)
    {
        $orderNo = date('YmdHis') . mt_rand(10000,99999);
        $order = new Orders();
        $order->order_no = $orderNo;
        $order->order_state = Orders::ORDER_STATE_INIT;
        $order->user_id = $userId;
        $order->order_amount = $orderAmount;
        if($order->save()){
            return $orderNo;
        }
        return false;
    }


    /**
     * 支付完成逻辑处理
     * @param $orderNo
     * @param $thirdNo
     */
    public static function payment($orderNo, $thirdNo)
    {
        DB::beginTransaction();
        $ret = false;
        try{
            $order = Orders::whereOrderNo($orderNo)->first();
            if($order->order_state == Orders::ORDER_STATE_INIT){
                $order->third_no = $thirdNo;
                $order->pay_at = date('Y-m-d H:i:s');
                $order->order_state = Orders::ORDER_STATE_PAY;
                $res = $order->save();
                if($res === true){
                    $userId = $order->user_id;
                    $orderAmount = $order->order_amount;
                    $ret = UserService::addUserBalance($userId, $orderAmount);
                }
            }
        }catch (\Exception $e){
            Log::error('orderService payment error with:' . $e->getMessage() ." orderno: $orderNo");
            DB::rollBack();
            return false;
        }
        //提交
        if($ret){
            DB::commit();
            return true;
        }else{
            DB::rollBack();
            Log::error("orderServcie payment error orderNo: $orderNo");
            return false;
        }
    }

}