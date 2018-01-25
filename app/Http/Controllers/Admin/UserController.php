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

class UserController extends BaseController
{
    public function list()
    {

        $users = User::where('phone','<>','')->orderByDesc('id')->paginate();
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
