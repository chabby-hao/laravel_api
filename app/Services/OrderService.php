<?php
namespace App\Services;

use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{

    /**
     * 支付完成逻辑处理
     * @param $orderNo
     * @param $thirdNo
     */
    public function payment($orderNo, $thirdNo)
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
            Log::error('orderService payment error:' . $e->getMessage());
            DB::rollBack();
        }
        //提交
        if($ret){
            DB::commit();
        }else{
            DB::rollBack();
        }
    }

}