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
use Leo108\CAS\Contracts\Models\UserModel;
use Leo108\CASServer\OAuth\PluginCenter;
use Symfony\Component\HttpFoundation\Response;
use Leo108\CAS\Contracts\Interactions\UserLogin as Contract;

class UserLogin implements Contract
{
    use AuthenticatesUsers, ValidatesRequests {
        login as doLogin;
        logout as doLogout;
    }
    /**
     * @var callable
     */
    protected $authenticated;

    /**
     * @param Request  $request
     * @param callable $authenticated
     * @return Response
     */
    public function login(Request $request, callable $authenticated)
    {
        $this->authenticated = $authenticated;

        return $this->doLogin($request);
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
    }

    /**
     * @param Request $request
     * @param array   $errors
     * @return Response
     */
    public function showLoginPage(Request $request, array $errors = [])
    {
        return view('auth.login', ['errorMsgs' => $errors, 'plugins' => app(PluginCenter::class)->getAll()]);
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
     * @param Request  $request
     * @param callable $beforeLogout
     * @return Response
     */
    public function logout(Request $request, callable $beforeLogout)
    {
        call_user_func_array($beforeLogout, [$request]);

        return $this->doLogout();
    }

    /**
     * @param Request $request
     * @param         $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return call_user_func_array($this->authenticated, [$request, $user]);
    }
}
