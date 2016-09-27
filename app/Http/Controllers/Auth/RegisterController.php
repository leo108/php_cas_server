<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\BindOauthFailedException;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Leo108\CASServer\OAuth\OAuthUser;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    protected $redirectPath = '';

    /**
     * RegisterController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->redirectPath   = route('home');
        $this->userRepository = $userRepository;
    }

    public function show(Request $request)
    {
        return view('auth.register', ['oauth' => $request->session()->get('oauth', null)]);
    }

    public function register(Request $request)
    {
        $this->validate(
            $request,
            [
                'password' => $this->userRepository->getPasswordRule(true),
            ]
        );
        $user  = $this->userRepository->create($request->only(['real_name', 'email', 'name', 'password']));
        $oauth = $request->session()->get('oauth');
        if ($oauth) {
            /* @var OAuthUser $oauth */
            try {
                foreach ($oauth->getBinds() as $type => $id) {
                    $this->userRepository->bindOauth($user, $type, $id, $oauth->getOriginal());
                }
            } catch (BindOauthFailedException $e) {
                //FIXME should handle this exception
            }
            $request->session()->forget('oauth');
        }

        \Auth::guard($this->getGuard())->login($user);

        return redirect($this->redirectPath());
    }
}
