<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    protected $signature = 'user:reset-password {email?} {--password=12345678}';
    protected $description = 'Reset a user password. If no email given, resets all non-admin users.';

    public function handle()
    {
        $password = $this->option('password');
        $email = $this->argument('email');

        if ($email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("User with email '{$email}' not found.");
                return 1;
            }
            $user->password = Hash::make($password);
            $user->save();
            $this->info("Password reset for {$user->name} ({$user->email})");
            return 0;
        }

        $count = User::where('user_type', '!=', 'admin')->update([
            'password' => Hash::make($password)
        ]);
        $this->info("Password reset to '{$password}' for {$count} user(s).");
        return 0;
    }
}
