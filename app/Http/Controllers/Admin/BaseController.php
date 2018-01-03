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
use Illuminate\Http\Request;

class BaseController extends Controller
{

    protected function _outPut(array $data = [])
    {
        $data['code'] = 200;
        header('Content-Type: application/json');
        die(json_encode($data));
    }

    protected function _outPutSuccess()
    {
        $this->_outPut(['msg'=>'success']);
    }

    protected function _outPutError($msg, array $data = [])
    {
        if (!isset($data['code'])) {
            $data['code'] = 500;
        }
        $data['msg'] = $msg;
        die(json_encode($data));
    }

    protected function _outPutRedirect($url)
    {
        $this->_outPut(['redirect'=>$url]);
    }

    protected function _checkParams($check, $input, $allowEmptys = [])
    {
        $data = Helper::arrayRequiredCheck($check, $input, false, $allowEmptys);
        if ($data === false) {
            return $this->_outPutError('请填写完整信息');
        }
        return $data;
    }

}
