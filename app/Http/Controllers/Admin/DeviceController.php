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

        return view('admin.device.list', ['devices' => $devices]);
    }

    public function add(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $arrInput = $request->input();
            $arrCheck = ['device_no', 'port_no', 'address'];
            $data = $this->_checkParams($arrCheck, $arrInput, ['address']);

            if (!DeviceService::addDevice($data)) {
                $this->_outPutError('无法添加设备，请确认信息是否填写正确');
            }
            $this->_outPutRedirect(URL::action('Admin\DeviceController@list'));
        }
        return view('admin.device.add');
    }

    public function remoteUpgrade(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            //POST
            $arrInput = $request->input();
            $arrCheck = ['device_no', 'slave_file'];
            $data = $this->_checkParams($arrCheck, $arrInput, ['address']);
            $deviceNo = $data['device_no'];
            $slaveFile = $data['slave_file'];

            $match = [];
            if(!preg_match('/^axc_slave_(\d+)\.bin$/', $slaveFile, $match)){
                return $this->_outPutError('请选择正确的文件');
            }
            $version = $match[1];
            $url = env('APP_URL') . '/slave_bin/' . $slaveFile;

            DeviceService::slaveUpgrade($deviceNo, $url, $version);
            return $this->_outPut($request->input());
        }

        $dir = public_path('slave_bin');
        $t = [];
        // Open a known directory, and proceed to read its contents
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if (preg_match('/^.*\.bin$/', $file)) {
                        //echo "filename: $file : filetype: " . filetype($dir .'/'. $file) . "\n";
                        $t[] = $file;
                    }
                    //echo $file . "\n";
                }
                closedir($dh);
            }
        }
        return view('admin.device.remoteUpgarge',[
            'slave_bin_files'=>$t,
        ]);
    }


}
