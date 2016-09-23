<?php
/**
 * Created by PhpStorm.
 * User: chenyihong
 * Date: 16/8/3
 * Time: 11:32
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\User;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $page    = $request->get('page', 1);
        $limit   = 20;
        $search  = $request->get('search', '');
        $enabled = $request->get('enabled', null);
        if ($enabled === '') {
            $enabled = null;
        }
        $users = $this->userRepository->getList($search, $enabled, null, $page, $limit);
        foreach ($users as $user) {
            $user->load('oauth');
        }

        return view(
            'admin.user',
            [
                'users' => $users,
                'query' => [
                    'search'   => $search,
                    'enabled' => is_null($enabled) ? '' : $enabled,
                ],
            ]
        );
    }

    public function store(Request $request)
    {
        $user = $this->userRepository->create($request->all());

        return response()->json(['msg' => trans('admin.user.add_ok')]);
    }

    public function update(User $user, Request $request)
    {
        $user = $this->userRepository->update($request->all(), $user);

        return response()->json(['msg' => trans('admin.user.edit_ok')]);
    }
}
