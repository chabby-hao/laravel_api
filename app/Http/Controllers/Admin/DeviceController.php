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

class DeviceController extends BaseController
{
    public function list()
    {

        $devices = DeviceInfo::get()->toArray();

        return view('admin.device.list',['devices'=>$devices]);
    }

    public function add(Request $request)
    {
        if($request->isXmlHttpRequest()){
            $arrInput = $request->input();
            $arrCheck = ['device_no','port_no','address'];
            $data = $this->_checkParams($arrCheck, $arrInput, ['address']);

            DeviceService::addDevice($data);

            $this->_outPut($data);


        }
        return view('admin.device.add');
    }


}
