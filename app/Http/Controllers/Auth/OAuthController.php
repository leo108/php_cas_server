<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\BindOauthFailedException;
use App\Exceptions\InvalidArgumentException;
use App\Repositories\UserRepository;
use App\Traits\Response\ShowMessage;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Leo108\CASServer\OAuth\OAuthUser;
use Leo108\CASServer\OAuth\Plugin;
use Leo108\CASServer\OAuth\PluginCenter;

class OAuthController extends Controller
{
    use ShowMessage;
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

        $request->session()->put('referrer.oauth', $request->server('HTTP_REFERER', route('home')));

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
            return $this->bind($request, $oauthUser, $bindUser);
        } else {
            return $this->regOrLogin($request, $oauthUser, $bindUser);
        }
    }

    protected function regOrLogin(Request $request, OAuthUser $oauthUser, User $bindUser = null)
    {
        //register
        if (!$bindUser) {
            if (config('cas_server.allow_register')) {
                $request->session()->set('oauth', $oauthUser);

                return redirect(route('register.get'));
            } else {
                return $this->showMessage(trans('not allowed to register'));
            }
        }

        //refresh db oauth profile
        $this->bind($request, $oauthUser, $bindUser);
        \Auth::guard()->login($bindUser);

        return redirect($this->getReferrerUrl($request));
    }

    protected function bind(Request $request, OAuthUser $oauthUser, User $bindUser = null)
    {
        $user = $request->user() ?: $bindUser;
        if (!$user) {
            throw new InvalidArgumentException('logged-in user and bind user can not be null at the same time');
        }

        if ($bindUser && $request->user() && $bindUser->id != $request->user()->id) {
            throw new BindOauthFailedException(trans('your oauth account has been bind to another account'));
        }

        try {
            foreach ($oauthUser->getBinds() as $type => $id) {
                $this->userRepository->bindOauth($user, $type, $id, $oauthUser->getOriginal());
            }
        } catch (BindOauthFailedException $e) {
            //unbind
            foreach ($oauthUser->getBinds() as $type => $id) {
                $this->userRepository->bindOauth($user, $type, null, null);
            }

            return $this->showMessage(trans('already bind by another account'));
        }

        return redirect($this->getReferrerUrl($request));
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getReferrerUrl(Request $request)
    {
        return $request->session()->pull('referrer.oauth', route('home'));
    }
}
