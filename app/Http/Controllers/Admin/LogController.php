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
use App\Models\PortPluginChanges;
use App\Models\SalvePortInfos;
use App\Services\CabinetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class LogController extends BaseController
{


    public function hostList(Request $request)
    {
        $where = [];
        if($udid = $request->input('device_no')){
            $where['udid'] = $udid;
        }
        if($request->has('port_no')){
            $where['port'] = $request->input('port_no');
        }
        $paginate = HostPortInfos::where($where)->orderByDesc('create_time')->paginate();

        return view('admin.log.host_port_info_list', [
            'datas' => $paginate->items(),
            'page_nav' => MyPage::showPageNav($paginate),
        ]);
    }

    public function slaveList(Request $request)
    {
        $where = [];
        if($udid = $request->input('device_no')){
            $where['udid'] = $udid;
        }
        if($request->has('port_no')){
            $where['port'] = $request->input('port_no');
        }
        $paginate = SalvePortInfos::where($where)->orderByDesc('create_time')->paginate();

        return view('admin.log.salve_port_info_list', [
            'datas' => $paginate->items(),
            'page_nav' => MyPage::showPageNav($paginate),
        ]);
    }

    public function pluginList(Request $request)
    {
        $where = [];
        if($udid = $request->input('device_no')){
            $where['udid'] = $udid;
        }
        if($request->has('port_no')){
            $where['port'] = $request->input('port_no');
        }
        $paginate = PortPluginChanges::where($where)->orderByDesc('id')->paginate();

        return view('admin.log.port_plugin_change_list', [
            'datas' => $paginate->items(),
            'page_nav' => MyPage::showPageNav($paginate),
        ]);
    }
}
