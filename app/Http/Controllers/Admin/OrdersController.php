<?php
/**
 * Created by PhpStorm.
 * User: eme
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;

use App\Libs\MyPage;
use App\Models\Orders;



class OrdersController extends BaseController
{
    public function list()
    {

        $orders = Orders::join('users',function($join){
            $join->on('users.id','=','orders.user_id');
        })->select(['orders.*','users.phone'])->where(['order_state'=>Orders::ORDER_STATE_PAY])->orderByDesc('id')->paginate();

        return view('admin.orders.list',[
            'orders'=>$orders->items(),
            'page_nav'=>MyPage::showPageNav($orders),
        ]);

    }


}
