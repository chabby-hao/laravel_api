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
use App\Models\Feedbacks;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class UserController extends BaseController
{
    public function list(Request $request)
    {

        $where = [];
        if($phone = $request->input('phone')){
            $where['phone'] = $phone;
        }
        $users = User::where($where)->where('phone','<>','')->orderByDesc('id')->paginate();
        $usersList = $users->items();
        /** @var User $user */
        foreach ($usersList as $user){
            $cardInfo = ActivityService::getCardsByUserId($user->id);
            $user->card_name = $cardInfo ? Helper::transToOneDimensionalArray($cardInfo, 'card_name') : [];
        }

        return view('admin.user.list',[
            'users'=>$usersList,
            'page_nav'=>MyPage::showPageNav($users),
        ]);
    }

    public function presentSet(Request $request)
    {
        $userId = $request->input('id');

        $user = User::find($userId);

        if($request->isXmlHttpRequest()){
            $money = $request->input('present_money');
            $user->present_balance = $money;
            if($user->save()){
                return $this->_outPutRedirect(URL::action('Admin\UserController@list'));
            }
            return $this->_outPutError('设置失败');
        }

        return view('admin.user.presentadd',[
            'user'=>$user,
        ]);
    }

    public function feedback()
    {

        $feedbacks = Feedbacks::join('users',function($join){
            $join->on('users.id','=','user_id');
        })->select(['feedbacks.*','users.phone'])->orderBy('id')->paginate();

        return view('admin.user.feedbacks',[
            'feedbacks'=>$feedbacks->items(),
            'page_nav'=>MyPage::showPageNav($feedbacks),
        ]);

    }

}
