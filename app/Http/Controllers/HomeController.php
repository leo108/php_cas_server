<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * HomeController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAction()
    {
        return view('home');
    }

    public function changePwdAction(Request $request)
    {
        $rule = $this->userRepository->getPasswordRule(false);
        $this->validate($request, ['new' => $rule], [], ['new' => trans('auth.new_pwd')]);

        $old  = $request->get('old');
        $new  = $request->get('new');
        $user = \Auth::user();
        if (!\Hash::check($old, $user->password)) {
            return response()->json(['msg' => trans('message.invalid_old_pwd')], 403);
        }

        $this->userRepository->resetPassword($user->id, $new);

        return response()->json(['msg' => trans('message.change_pwd_ok')]);
    }
}
