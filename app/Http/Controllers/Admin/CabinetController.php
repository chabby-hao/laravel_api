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
use App\Services\CabinetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CabinetController extends BaseController
{

    /**
     * 添加换电柜
     */
    public function add(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            $data = $this->_checkParams(['cabinet_no', 'cabinet_num', 'address', 'lat', 'lng'], $request->input());
            if ($model = Cabinets::create($data)) {
                $id = $model->id;
                $wxApi = new WxApi();
                $qrInfo = $wxApi->getQrImgForCabinet($id);
                $model->url = $qrInfo['img_url'];
                $url = Helper::getQrUrl($qrInfo['img_path']);
                $model->qr = $url; //return decoded text from QR Code
                if ($model->save()) {
                    $num = $data['cabinet_num'];
                    for ($i = 1; $i <= $num; $i++) {
                        CabinetDoors::create([
                            'cabinet_id' => $id,
                            'door_no' => $i,
                        ]);
                    }
                    $this->_outPutRedirect(URL::action('Admin\CabinetController@list'));
                }
            }
            return $this->_outPutError('添加失败');

        }
        return view('admin.cabinet.add');
    }

    public function list()
    {
        $devices = Cabinets::orderByDesc('id')->paginate();

        return view('admin.cabinet.list', [
            'devices' => $devices->items(),
            'page_nav' => MyPage::showPageNav($devices),
        ]);
    }

    public function doorList(Request $request)
    {

        $id = $request->input('id');
        if (!$id) {
            return $this->_outPutError('非法请求');
        }
        $cabinets = Cabinets::find($id);
        if(!$cabinets){
            return $this->_outPutError('无数据');
        }

        $devices = CabinetDoors::where(['cabinet_id'=>$id])->orderByDesc('id')->paginate();

        $datas = $devices->items();

        foreach ($datas as $data) {
            $doorinfo = CabinetService::getDoorInfo($cabinets->cabinet_no, $data->door_no);
            $data->openState = $doorinfo['openState'] ? '开' : '关';
            $data->hasBattery = $doorinfo['hasBattery'] ? '有' : '无';
            $data->batteryId = $doorinfo['hasBattery'] ? $doorinfo['batteryId'] : '';
        }

        return view('admin.cabinet.doorlist', [
            'cabinet_no'=>$cabinets->cabinet_no,
            'devices' => $datas,
            'page_nav' => MyPage::showPageNav($devices),
        ]);
    }


}
