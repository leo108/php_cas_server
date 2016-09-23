<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\BindOauthFailedException;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Leo108\CASServer\OAuth\OAuthUser;
use Leo108\CASServer\OAuth\Plugin;
use Leo108\CASServer\OAuth\PluginCenter;

class OAuthController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * OAuthController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login($name, Request $request)
    {
        $plugin = app(PluginCenter::class)->get($name);
        if (is_null($plugin)) {
            return response('', 404);
        }

        /* @var Plugin $plugin */
        return $plugin->gotoAuthUrl($request, route('oauth.callback', ['name' => $name]));
    }

    public function callback($name, Request $request)
    {
        $plugin = app(PluginCenter::class)->get($name);
        if (is_null($plugin)) {
            return response('', 404);
        }

        /* @var Plugin $plugin */
        $oauthUser = $plugin->getOAuthUser($request);
        $bindUser  = null;
        foreach ($oauthUser->getBinds() as $type => $id) {
            if (!$id) {
                continue;
            }
            $bindUser = $this->userRepository->getUserByOauthId($type, $id);
            if ($bindUser) {
                break;
            }
        }

        if ($request->user()) {
            return $this->bind($request, $bindUser, $oauthUser);
        } else {
            return $this->regOrLogin($request, $bindUser, $oauthUser);
        }
    }

    protected function regOrLogin(Request $request, User $bindUser, OAuthUser $oauthUser)
    {
        //register
        if (!$bindUser) {
            if (config('cas_server.allow_register')) {
                $request->session()->set('oauth', $oauthUser);

                return $this->redirect(route('register.get'));
            } else {
                return $this->error(trans('not allowed to register'));
            }
        }

        \Auth::guard()->login($bindUser);

        return $this->reload();
    }

    protected function bind(Request $request, User $bindUser, OAuthUser $oauthUser)
    {
        if ($bindUser) {
            if ($bindUser->id != $request->user()->id) {
                throw new BindOauthFailedException();
            }

            //already bind that account
            return $this->reload();
        }

        try {
            foreach ($oauthUser->getBinds() as $type => $id) {
                $this->userRepository->bindOauth($bindUser, $type, $id);
            }
        } catch (BindOauthFailedException $e) {
            foreach ($oauthUser->getBinds() as $type => $id) {
                $this->userRepository->bindOauth($bindUser, $type, null);
            }

            return $this->error(trans('already bind by another account'));
        }

        return $this->reload();
    }

    /**
     * redirect opener page
     * @param string $url
     * @return Response
     */
    protected function redirect($url)
    {
        return view('auth.oauth.redirect', ['redirect' => $url]);
    }

    /**
     * reload opener page
     * @return Response
     */
    protected function reload()
    {
        return view('auth.oauth.reload');
    }

    /**
     * show error message in opener page
     * @param string $msg
     * @return Response
     */
    protected function error($msg)
    {
        return view('auth.oauth.error', ['msg' => $msg]);
    }
}
