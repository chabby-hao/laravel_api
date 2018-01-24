<?php
/**
 * Created by PhpStorm.
 * User: chabby
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;

use App\Libs\MyPage;
use App\Models\WelfareCards;
use App\Models\WelfareDevices;
use App\Models\WelfareWhiteLists;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Readers\LaravelExcelReader;


class ActivityController extends BaseController
{

    public function cardsList()
    {

        $paginate = WelfareCards::orderByDesc('id')->paginate();

        $data = $paginate->items();
        if ($data) {
            /** @var WelfareCards $card */
            foreach ($data as $card) {
                //设备号
                $card->device_no = WelfareDevices::getDeviceNosByCardId($card->id);
                $card->phones = WelfareWhiteLists::getPhonesByCardId($card->id);
            }
        }

        return view('admin.activity.cardsList', [
            'data' => $data,
            'page_nav' => MyPage::showPageNav($paginate),
        ]);
    }

    private function _cardsCheckRequest(Request $request)
    {
        $arrCheck = ['device_no', 'company', 'card_name'];
        $data = $this->_checkParams($arrCheck, $request->input());
        $uploadkey = 'phones';
        $phones = null;
        if ($request->hasFile($uploadkey)) {
            Excel::load($request->file($uploadkey)->getRealPath(), function ($reader) use (&$phones) {
                /** @var LaravelExcelReader $reader */
                $data = $reader->all()->toArray();
                foreach ($data as $row) {
                    $phones[] = $row[0];
                }
            });
        }
        return [
            'device_no'=>$data['device_no'],
            'card_name'=>$data['card_name'],
            'company'=>$data['company'],
            'phones'=>$phones,
        ];
    }

    public function cardsAdd(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            //添加卡片
            $data = $this->_cardsCheckRequest($request);

            if (!ActivityService::addCards($data['device_no'], $data['card_name'], $data['company'], $data['phones'])) {
                $this->_outPutError('添加卡片失败');
            }
            $this->_outPutRedirect(URL::action('Admin\ActivityController@cardsList'));
        }

        return view('admin.activity.cardsAdd');
    }

    public function cardsEdit(Request $request)
    {

        $id = $request->input('id');
        if($request->isXmlHttpRequest()){
            $data = $this->_cardsCheckRequest($request);

            if (!ActivityService::editCards($id, $data['device_no'], $data['card_name'], $data['company'], $data['phones'])) {
                $this->_outPutError('编辑卡片失败');
            }
            $this->_outPutRedirect(URL::action('Admin\ActivityController@cardsList'));
        }

        $card = WelfareCards::find($id);
        $card->device_no = WelfareDevices::getDeviceNosByCardId($card->id);
        $card->phones = WelfareWhiteLists::getPhonesByCardId($card->id);

        return view('admin.activity.cardsEdit', [
            'card' => $card,
        ]);
    }

    public function cardsWhiteListExport(Request $request)
    {
        $id = $request->input('id');
        $card = WelfareCards::find($id);

        $phones = WelfareWhiteLists::getPhonesByCardId($card->id);
        $cellData = [['phone']];
        foreach ($phones as $phone){
            $cellData[] = [$phone];
        }

        Excel::create($card->card_name . '-' . $card->company . '-白名单列表', function ($excel) use ($cellData) {
            $excel->sheet('list', function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->export('xls');
    }


}
