<?php
/**
 * Created by PhpStorm.
 * User: chenyihong
 * Date: 16/8/7
 * Time: 14:16
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    protected $subject = '';

    protected $redirectPath = '/';

    use ResetsPasswords {
        getResetValidationRules as originGetResetValidationRules;
    }

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
        $this->subject        = trans('passwords.email_subject');
        $this->redirectPath   = route('home');
    }

    protected function getResetValidationRules()
    {
        $rule             = $this->originGetResetValidationRules();
        $rule['password'] = $this->userRepository->getPasswordRule(true);

        return $rule;
    }

    protected function getResetValidationCustomAttributes()
    {
        return [
            'password' => trans('auth.new_pwd'),
            'email'    => trans('passwords.email'),
        ];
    }
}
