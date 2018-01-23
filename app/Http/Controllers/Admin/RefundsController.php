<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;

use App\Libs\MyPage;
use App\Models\UserRefunds;


class RefundsController extends BaseController
{
    public function list()
    {

        $refunds = UserRefunds::join('users','users.id','=','user_id')
            ->select(['user_refunds.*','users.phone'])->orderByDesc('id')->paginate();

        return view('admin.refund.list',[
            'refunds'=>$refunds->items(),
            'page_nav'=>MyPage::showPageNav($refunds),
        ]);

    }


}
