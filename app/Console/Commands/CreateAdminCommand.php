<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

#[Signature('velmora:make-admin {email?}')]
#[Description('Create or update a Velmora super administrator account')]
class CreateAdminCommand extends Command
{
    public function handle(): int
    {
        $name = $this->ask('Administrator name');
        $email = $this->argument('email') ?? $this->ask('Administrator email');
        $password = $this->secret('Administrator password (minimum 12 characters)');

        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $password],
            ['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255'], 'password' => ['required', 'string', 'min:12']],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->components->error($error);
            }

            return self::FAILURE;
        }

        User::updateOrCreate(
            ['email' => strtolower((string) $email)],
            ['name' => $name, 'password' => Hash::make((string) $password), 'role' => 'super_admin', 'is_active' => true],
        );

        $this->components->info('Administrator account saved.');

        return self::SUCCESS;
    }
}
