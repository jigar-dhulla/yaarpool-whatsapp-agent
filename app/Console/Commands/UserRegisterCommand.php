<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('user:register
    {name? : The user\'s display name}
    {email? : The email address used to log in}')]
#[Description('Register a web user who can log in to the failed-jobs dashboard.')]
class UserRegisterCommand extends Command
{
    public function handle(): int
    {
        $name = $this->argument('name') ?? text(label: 'Name', required: true);
        $email = Str::lower($this->argument('email') ?? text(label: 'Email', required: true));
        $password = password(label: 'Password', required: true);

        $validator = Validator::make(
            ['name' => $name, 'email' => $email, 'password' => $password],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', Password::defaults()],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create($validator->validated());

        $this->info(sprintf('Registered %s <%s>. They can now log in at /login.', $user->name, $user->email));

        return self::SUCCESS;
    }
}
