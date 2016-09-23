<?php
/**
 * Created by PhpStorm.
 * User: chenyihong
 * Date: 16/8/3
 * Time: 11:28
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Leo108\CAS\Repositories\ServiceRepository;

class HomeController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;
    /**
     * @var ServiceRepository
     */
    protected $serviceRepository;

    /**
     * HomeController constructor.
     * @param UserRepository    $userRepository
     * @param ServiceRepository $serviceRepository
     */
    public function __construct(UserRepository $userRepository, ServiceRepository $serviceRepository)
    {
        $this->userRepository    = $userRepository;
        $this->serviceRepository = $serviceRepository;
    }

    public function indexAction()
    {
        return view(
            'admin.dashboard',
            [
                'user'    => $this->userRepository->dashboard(),
                'service' => $this->serviceRepository->dashboard(),
            ]
        );
    }
}
