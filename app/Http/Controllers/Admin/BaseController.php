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

    protected function _outPutError($msg, array $data = [])
    {
        if (!isset($data['code'])) {
            $data['code'] = 500;
        }
        $data['msg'] = $msg;
        die(json_encode($data));
    }

    protected function _checkParams($check, $input, $allowEmptys = [])
    {
        $data = Helper::arrayRequiredCheck($check, $input, false, $allowEmptys);
        if ($data === false) {
            return $this->_outPutError('信息填写错误');
        }
        return $data;
    }

}
