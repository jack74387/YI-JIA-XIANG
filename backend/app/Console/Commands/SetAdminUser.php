<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SetAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:set-admin {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set a user as admin by email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }
        
        $user->is_admin = true;
        $user->save();
        
        $this->info("User {$user->name} ({$user->email}) is now an admin!");
        
        return 0;
    }
}
