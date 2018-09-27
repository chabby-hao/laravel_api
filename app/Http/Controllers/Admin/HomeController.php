<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libs\Helper;
use App\Libs\MyPage;
use App\Models\DeviceConfig;
use App\Models\DeviceCostDetail;
use App\Models\DeviceInfo;
use App\Services\AdminService;
use App\Services\CommandService;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class HomeController extends BaseController
{

    public function index(Request $request)
    {



        return view('admin.home.index' );
    }


}
