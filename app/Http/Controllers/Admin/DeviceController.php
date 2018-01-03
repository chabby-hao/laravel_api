<?php
/**
 * Created by PhpStorm.
 * User: eme
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\Helper;
use App\Models\DeviceInfo;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class DeviceController extends BaseController
{
    public function list()
    {

        $devices = DeviceInfo::orderByDesc('id')->get()->toArray();

        return view('admin.device.list',['devices'=>$devices]);
    }

    public function add(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $arrInput = $request->input();
            $arrCheck = ['device_no','port_no','address'];
            $data = $this->_checkParams($arrCheck, $arrInput, ['address']);

            if(!DeviceService::addDevice($data)){
                $this->_outPutError('无法添加设备，请确认信息是否填写正确');
            }
            $this->_outPutRedirect(URL::action('Admin\DeviceController@list'));
        }
        return view('admin.device.add');
    }


}
