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
use App\Models\DeviceInfo;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class DeviceController extends BaseController
{

    public function deviceList()
    {
        $devices = DeviceInfo::groupBy('device_no')->select(['device_no','address'])->paginate();

        return view('admin.device.devicelist', [
            'devices' => $devices->items(),
            'page_nav'=>MyPage::showPageNav($devices),
        ]);
    }

    public function list(Request $request)
    {

        $where = [];

        if($deviceNo = $request->input('device_no')){
            $where['device_no'] = $deviceNo;
        }

        $devices = DeviceInfo::where($where)->orderByDesc('id')->paginate();

        return view('admin.device.list', [
            'devices' => $devices->items(),
            'page_nav'=>MyPage::showPageNav($devices),
        ]);
    }

    public function add(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $arrInput = $request->input();
            $arrCheck = ['device_no', 'port_no', 'address'];
            $data = $this->_checkParams($arrCheck, $arrInput);

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

            if (!DeviceService::isDeviceOnline($deviceNo)) {
                $this->_outPutError('设备不在线');
            }

            $match = [];
            if (!preg_match('/^axc_slave_(\d+)\.bin$/', $slaveFile, $match)) {
                $this->_outPutError('请选择正确的文件');
            }
            $version = $match[1];
            $url = strtr(env('APP_URL'), ['https' => 'http']) . '/slave_bin/' . $slaveFile;

            DeviceService::slaveUpgrade($deviceNo, $url, $version);
            $this->_outPut($request->input());
        }

        $t = $this->_getBinFile();

        return view('admin.device.remoteUpgarge', [
            'slave_bin_files' => $t,
        ]);
    }

    public function slaveBinManage(Request $request)
    {

        $uploadkey = 'bin_file';

        if ($request->isXmlHttpRequest() && $request->hasFile($uploadkey)) {
            //添加升级文件
            if ($request->file($uploadkey)->getMimeType() != 'application/octet-stream') {
                $this->_outPutError('上传文件格式有误');
            }
            $filename = $request->file($uploadkey)->getClientOriginalName();

            if (!preg_match('/^axc_slave_(\d+)\.bin$/', $filename)) {
                $this->_outPutError('请上传正确命名的文件,如 axc_slave_2205.bin');
            }

            $desitination = public_path('slave_bin/' . $filename);
            if (in_array($filename, $this->_getBinFile())) {
                $this->_outPutError('上传文件名与现有文件名发生冲突');
            }
            if (move_uploaded_file($request->file($uploadkey)->getRealPath(), $desitination)) {
                $this->_outPutRedirect(URL::action('Admin\DeviceController@slaveBinManage'));
            } else {
                $this->_outPutError('上传失败');
            }
        } elseif ($request->isXmlHttpRequest()) {
            $this->_outPutError('请先选择文件');
        }

        $t = $this->_getBinFile();
        return view('admin.device.slaveBinManage', [
            'slave_bin_files' => $t,
        ]);
    }

    private function _getBinFile()
    {

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
        return $t;
    }

    /**
     * 远程反向隧道
     */
    public function remoteTunnel(Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->input('open')) {
            //开启
            $this->_checkParams(['user_url', 'device_no', 'port_no'], $request->input());

            $userUrl = $request->input('user_url');
            $deviceNo = $request->input('device_no');
            $portNo = $request->input('port_no');
            if ($portNo < 30000) {
                $this->_outPutError('端口号不小于30000');
            }
            DeviceService::openRemoteTunnel($deviceNo, $portNo, $userUrl);
            return $this->_outPutSuccess();
        } elseif ($request->isXmlHttpRequest() && $request->input('close')) {
            //关闭
            $deviceNo = $request->input('device_no');
            DeviceService::closeRemoteTunnel($deviceNo);
            return $this->_outPutSuccess();
        }

        return view('admin.device.remoteTunnel');
    }

}
