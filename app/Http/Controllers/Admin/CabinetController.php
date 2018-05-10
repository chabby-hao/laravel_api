<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;


use App\Libs\MyPage;
use App\Models\ChargeTasks;
use Illuminate\Support\Facades\Input;

class CabinetController extends BaseController
{
    public function add()
    {


        return view('admin.cabinet.add');

        /*return view('admin.charge.list',[
            'charges'=>$paginate->items(),
            'page_nav'=>MyPage::showPageNav($paginate),
        ]);*/
    }


}
