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

class DeviceController extends Controller
{
    public function list()
    {

        $devices = DeviceInfo::get()->toArray();

        return view('admin.device.list',['devices'=>$devices]);
    }

    public function add()
    {
        return view('admin.device.add');
    }


}
