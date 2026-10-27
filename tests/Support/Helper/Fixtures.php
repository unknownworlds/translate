<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

use App\Models\Role;
use App\Models\User;
use Codeception\Module;
use Illuminate\Support\Facades\Hash;

/**
 * Users with specific roles. The dump only ships a single Root user.
 */
class Fixtures extends Module
{
    public const ROOT_EMAIL = 'root@localhost';
    public const ROOT_PASSWORD = 'SuperSecretPassword';
    public const PASSWORD = 'password1234';

    public function haveUser(string $name, array $roles = []): User
    {
        $user = User::create([
            'name' => $name,
            'email' => strtolower(str_replace(' ', '.', $name)).'@localhost',
            'password' => Hash::make(self::PASSWORD),
        ]);

        foreach ($roles as $roleName) {
            $user->assignRole(Role::firstOrCreate(['name' => $roleName]));
        }

        return $user;
    }

    /**
     * amLoggedAs() only works before the first request: the Laravel module boots a fresh application
     * for every following request, which restores the previous user from the session cookie.
     */
    public function switchUserTo(User $user): void
    {
        $laravel = $this->getModule('Laravel');
        $laravel->_request('POST', '/logout');
        $laravel->_request('POST', '/login', [
            'email' => $user->email,
            'password' => $user->email === self::ROOT_EMAIL ? self::ROOT_PASSWORD : self::PASSWORD,
        ]);
    }

    public function grabRootUser(): User
    {
        return User::where('email', self::ROOT_EMAIL)->firstOrFail();
    }
}
