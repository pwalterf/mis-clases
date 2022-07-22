<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function createUser(array $userData): User
    {
        $userData['password'] = Hash::make(Str::random(8));
        return User::create($userData);
    }

    public function updateUser(User $user, array $userData): User
    {
        $user->update($userData);
        return $user->refresh();
    }

    public function destroyUser(User $user): bool
    {
        if (auth()->user()->id === $user->id) {
            return false;
        }
        
        return $user->delete();
    }

    public function restoreUser(User $user): bool
    {
        if (auth()->user()->id === $user->id) {
            return false;
        }
        
        return $user->restore();
    }

    public function assignRole(User $user, string $role): void
    {
        $user->assignRole($role);
    }

    public function destroyStudent(User $user): void
    {
        $user->classroomUsers()->delete();
    }
}