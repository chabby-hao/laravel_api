<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;


use App\Libs\Helper;
use App\Libs\MyPage;
use App\Libs\WxApi;
use App\Models\CabinetDoors;
use App\Models\Cabinets;
use App\Models\HostPortInfos;
use App\Services\CabinetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class LogController extends BaseController
{


    public function hostList()
    {
        $paginate = HostPortInfos::orderByDesc('create_time')->paginate();

        return view('admin.log.host_port_info_list', [
            'datas' => $paginate->items(),
            'page_nav' => MyPage::showPageNav($paginate),
        ]);
    }

    public function slaveList()
    {

    }

    public function pluginList()
    {

    }
}
