<?php
/**
 * Created by PhpStorm.
 * User: leo108
 * Date: 16/9/18
 * Time: 10:21
 */

namespace App\Repositories;

use App\Exceptions\BindOauthFailedException;
use App\Exceptions\UserException;
use App\Models\UserOauth;
use App\Traits\ValidateInput;
use App\User;
use Illuminate\Support\Str;

class UserRepository
{
    use ValidateInput;
    /**
     * @var User
     */
    protected $user;

    /**
     * @var UserOauth
     */
    protected $userOauth;

    /**
     * UserRepository constructor.
     * @param User      $user
     * @param UserOauth $userOauth
     */
    public function __construct(User $user, UserOauth $userOauth)
    {
        $this->user      = $user;
        $this->userOauth = $userOauth;
    }

    /**
     * @param $id
     * @return User|null
     */
    public function getUserById($id)
    {
        return $this->user->find($id);
    }

    /**
     * @param $name
     * @return User|null
     */
    public function getUserByName($name)
    {
        return $this->user->where('name', $name)->first();
    }

    /**
     * @param $email
     * @return User|null
     */
    public function getUserByEmail($email)
    {
        return $this->user->where('email', $email)->first();
    }

    /**
     * @param string $type
     * @param string $id
     * @return User|null
     */
    public function getUserByOauthId($type, $id)
    {
        return $this->user->whereHas(
            'oauth',
            function ($query) use ($type, $id) {
                $query->where($type, $id);
            }
        )->first();
    }

    /**
     * @param array $data
     * @return User
     */
    public function create($data)
    {
        $data = array_only(
            $data,
            [
                'name',
                'email',
                'password',
                'real_name',
                'enabled',
                'admin',
            ]
        );

        $this->validate(
            $data,
            [
                'real_name' => 'required',
                'email'     => 'required|email|unique:users',
                'name'      => 'required|unique:users',
                'password'  => $this->getPasswordRule(false),
                'enabled'   => 'boolean',
                'admin'     => 'boolean',
            ],
            [],
            [
                'name'      => trans('admin.user.username'),
                'real_name' => trans('admin.user.real_name'),
                'email'     => trans('admin.user.email'),
                'password'  => trans('admin.user.password'),
            ]
        );
        $data['password'] = bcrypt($data['password']);

        $user  = $this->user->create($data);
        $oauth = $this->userOauth->newInstance([]);
        $oauth->user()->associate($user);
        $oauth->save();

        return $user;
    }

    /**
     * @param array $data
     * @param User  $user
     * @return User
     */
    public function update($data, User $user)
    {
        $data = array_only(
            $data,
            [
                'password',
                'real_name',
                'enabled',
                'admin',
            ]
        );

        $rule = [
            'real_name' => 'required',
            'enabled'   => 'boolean',
            'admin'     => 'boolean',
        ];

        $attr = [
            'real_name' => trans('admin.user.real_name'),
        ];

        if (!empty($data['password'])) {
            $rule['password'] = $this->getPasswordRule(false);
            $attr['password'] = trans('admin.user.password');
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $this->validate($data, $rule, [], $attr);
        $user->update($data);

        return $user;
    }

    /**
     * @param User        $user
     * @param string      $type
     * @param string|null $oauthId
     * @param array|null  $profile
     * @return User
     * @throws BindOauthFailedException
     */
    public function bindOauth(User $user, $type, $oauthId, $profile)
    {
        if ($oauthId) {
            $oldUser = $this->getUserByOauthId($type, $oauthId);
            if ($oldUser && $oldUser->id != $user->id) {
                throw new BindOauthFailedException();
            }
        }

        $user->oauth->{$type} = $oauthId;
        $user->oauth->setProfile($type, $profile);
        $user->oauth->save();

        return $user;
    }

    /**
     * @param int    $id
     * @param string $pwd
     * @throws UserException
     * @return User
     */
    public function resetPassword($id, $pwd)
    {
        $user = $this->user->find($id);
        if (!$user) {
            throw new UserException(trans('messages.user.not_exists'));
        }
        $user->password       = bcrypt($pwd);
        $user->remember_token = Str::random(60);
        $user->save();

        return $user;
    }

    /**
     * @param $search
     * @param $enabled
     * @param $admin
     * @param $page
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getList($search, $enabled, $admin, $page, $limit)
    {
        /* @var \Illuminate\Database\Query\Builder $query */
        $query = $this->user->query();
        if ($search) {
            $like = '%'.$search.'%';
            $query->where(
                function ($query) use ($like) {
                    /* @var \Illuminate\Database\Query\Builder $query */
                    $query->where('name', 'like', $like)
                        ->orWhere('real_name', 'like', $like)
                        ->orWhere('email', 'like', $like);
                }
            );
        }

        if (!is_null($enabled)) {
            $query->where('enabled', boolval($enabled));
        }

        if (!is_null($admin)) {
            $query->where('admin', boolval($admin));
        }

        return $query->orderBy('id', 'desc')->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * @param bool $needConfirmed
     * @return string
     */
    public function getPasswordRule($needConfirmed = false)
    {
        $rule = [
            'required',
            'min:6',
        ];
        if ($needConfirmed) {
            $rule[] = 'confirmed';
        }

        return join('|', $rule);
    }

    public function dashboard()
    {
        return [
            'total'  => $this->user->count(),
            'active' => $this->user->where('enabled', true)->count(),
            'admin'  => $this->user->where('admin', true)->count(),
        ];
    }
}
