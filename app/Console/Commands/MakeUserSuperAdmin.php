<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeUserSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-super-admin {email : The email of the user to make super admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a user super admin by their email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            $this->error("No user found with email {$email}");
            return 1;
        }

        $user->is_super_admin = true;
        $user->save();

        $this->info("Successfully made {$email} a super admin!");
        return 0;
    }
}
