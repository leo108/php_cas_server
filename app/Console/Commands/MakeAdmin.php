<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin { --password= }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * MakeAdmin constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name     = 'admin';
        $email    = 'admin@admin.com';
        $password = $this->option('password') ?: Str::random(12);
        $this->userRepository->create(
            [
                'name'      => $name,
                'real_name' => 'admin',
                'email'     => $email,
                'password'  => $password,
                'admin'     => true,
                'enabled'   => true,
            ]
        );
        $this->info(sprintf('name: %s, password: %s, email: %s', $name, $password, $email));
    }
}
