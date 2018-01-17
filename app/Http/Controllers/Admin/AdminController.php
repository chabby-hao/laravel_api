<?php
/**
 * Created by PhpStorm.
 * User: eme
 * Date: 2017/11/13
 * Time: 上午10:55
 */

namespace App\Http\Controllers\Admin;


use App\Libs\MyPage;
use App\Models\Admins;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class AdminController extends BaseController
{
    public function list()
    {

        $paginate = Admins::orderByDesc('id')->paginate();


        return view('admin.admin.list',[
            'admins'=>$paginate->items(),
            'page_nav'=>MyPage::showPageNav($paginate),
        ]);
    }

    public function add(Request $request)
    {

        if($request->isXmlHttpRequest()){
            //添加管理员
            $check = ['name','pwd'];
            $data = $this->_checkParams($check, $request->input());
            if(AdminService::addAdmin($data['name'], $data['pwd'])){
                $this->_outPutRedirect(URL::action('Admin\AdminController@list'));
            }
            $this->_outPutError('添加失败,请确认信息再次输入');
        }

        return view('admin.admin.add');
    }

    public function login(Request $request)
    {

        if($request->isXmlHttpRequest()){
            $check = ['name','pwd'];
            $data = $this->_checkParams($check, $request->input());
            if(AdminService::login($data['name'], $data['pwd'])){
                return $this->_outPutRedirect(URL::action('Admin\DeviceController@list'));
            }
            return $this->_outPutError('用户名或者密码错误');
        }

        return view('admin.admin.login');
    }

    public function logout()
    {
        AdminService::logout();
        return Redirect::action('Admin\AdminController@login');
    }


}
