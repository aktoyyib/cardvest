<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Spatie\Permission\Models\Role;
use App\Models\User;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:add {email : The email address of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a specific user an admin.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $pass = $this->secret('Enter passphrase for super admin access');


        while ($pass !== 'pAsSwOrD') {
            $this->error('Wrong passphrase!');
            $pass = $this->secret('Enter passphrase for super admin access');
        }

        $role = Role::where('name', 'admin')->first();
        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            return $this->error('User with specified email address does not exist.');
        }

        if ($user->hasRole(['admin'])) { //hasAnyRoles([])
            return $this->info('User is already an admin.');
        }
        $user->assignRole($role);

        $this->info("User (".$email.") now has admin priviledges");
    }
}
