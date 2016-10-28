<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 16/9/17
 * Time: 21:35
 */

namespace App\Interactions;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Leo108\CAS\Contracts\Models\UserModel;
use Leo108\CASServer\OAuth\PluginCenter;
use Symfony\Component\HttpFoundation\Response;
use Leo108\CAS\Contracts\Interactions\UserLogin as Contract;

class UserLogin implements Contract
{
    use AuthenticatesUsers, ValidatesRequests;

    /**
     * @param Request $request
     * @return UserModel|null
     */
    public function login(Request $request)
    {
        if (config('cas_server.disable_pwd_login')) {
            return null;
        }

        $credentials            = $this->getCredentials($request);
        $credentials['enabled'] = true;
        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            return Auth::guard($this->getGuard())->user();
        }

        return null;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showAuthenticateFailed(Request $request)
    {
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @param Request $request
     * @return UserModel|null
     */
    public function getCurrentUser(Request $request)
    {
        return $request->user();
    }

    /**
     * @param Request $request
     * @param string  $jumpUrl
     * @param string  $service
     * @return Response
     */
    public function showLoginWarnPage(Request $request, $jumpUrl, $service)
    {
        return view('auth.login_warn', ['url' => $jumpUrl, 'service' => $service]);
    }

    /**
     * @param Request $request
     * @param array   $errors
     * @return Response
     */
    public function showLoginPage(Request $request, array $errors = [])
    {
        return view(
            'auth.login',
            [
                'errorMsgs' => $errors,
                'plugins'   => app(PluginCenter::class)->getAll(),
                'service'   => $request->get('service', null),
            ]
        );
    }

    /**
     * @param array $errors
     * @return Response
     */
    public function redirectToHome(array $errors = [])
    {
        return redirect()->route('home')->withErrors(['global' => $errors]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function logout(Request $request)
    {
        Auth::guard($this->getGuard())->logout();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showLoggedOut(Request $request)
    {
        return view('auth.logged_out');
    }
}
