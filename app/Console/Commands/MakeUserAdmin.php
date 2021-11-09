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
    protected $signature = 'admin:add {email : The email address of the user} {--super}';

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
        $super = $this->option('super');

        // while ($pass !== 'pAsSwOrD') {
        //     $this->error('Wrong passphrase!');
        //     $pass = $this->secret('Enter passphrase for super admin access');
        // }

        $role = Role::where('name', 'admin')->first();
        $superAdmin = Role::where('name', 'super admin')->first();
        $user = User::where('email', $email)->first();

        if (is_null($user)) {
            return $this->error('User with specified email address does not exist.');
        }

        $responses = array();

        if ($user->hasRole(['admin'])) { //hasAnyRoles([])
            $responses[] = "User is already an admin!";
        } else {
            $user->assignRole($role);
        }

        if ($super) {
            if ($user->hasRole(['super admin'])) {
                $responses[] = "User is already a super admin!";
            } else {
                $user->assignRole($superAdmin);
            }
        }

        if (count($responses) > 0) {
            foreach ($responses as $response) {
                $this->error($response);
            }

            return 0;
        }

        $this->info("User (".$email.") now has admin priviledges");
    }
}
