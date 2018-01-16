<?php
/**
 * Created by PhpStorm.
 * User: eme
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;

use App\Models\Orders;



class OrdersController extends BaseController
{
    public function list()
    {

        $orders = Orders::getOrdersList();

        return view('admin.orders.list',[
            'orders'=>$orders,
        ]);

    }


}
