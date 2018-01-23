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
        if($data){
            /** @var WelfareCards $card */
            foreach ($data as $card){
                //设备号
                $card->device_no = WelfareDevices::getDeviceNosByCardId($card->id);
                $card->phones = WelfareWhiteLists::getPhonesByCardId($card->id);
            }
        }

        return view('admin.activity.cardsList',[
            'data'=>$data,
            'page_nav'=>MyPage::showPageNav($paginate),
        ]);
    }

    public function cardsAdd(Request $request)
    {

        if($request->isXmlHttpRequest()){
            //添加卡片
            $arrCheck = ['device_no','company','card_name'];
            $this->_checkParams($arrCheck, $request->input());
            $uploadkey = 'phones';
            $phones = null;
            if($request->hasFile($uploadkey)){
                Excel::load($request->file($uploadkey)->getRealPath(),function($reader) use (&$phones){
                    /** @var LaravelExcelReader $reader */
                    $data = $reader->all()->toArray();
                    foreach ($data as $row){
                        $phones[] = $row[0];
                    }
                });
            }
            if(!ActivityService::addCards($request->input('device_no'), $request->input('card_name') ,$request->input('company'), $phones)){
                $this->_outPutError('添加卡片失败');
            }
            $this->_outPutRedirect(URL::action('Admin\ActivityController@cardsList'));
        }

        return view('admin.activity.cardsAdd');
    }

    public function cardsEdit()
    {

    }

    public function CardsImport()
    {

    }

}
