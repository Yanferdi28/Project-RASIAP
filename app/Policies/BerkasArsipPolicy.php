<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BerkasArsip;

class BerkasArsipPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'user', 'operator']);
    }

    public function view(User $user, BerkasArsip $model): bool
    {
        return $user->hasAnyRole(['admin', 'user', 'operator']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'user']);
    }

    public function update(User $user, BerkasArsip $model): bool
    {
        if ($user->hasRole('operator')) {
            return true;
        }

        return $user->hasAnyRole(['admin', 'user']);
    }

    public function delete(User $user, BerkasArsip $model): bool
    {
        return $user->hasAnyRole(['admin', 'user']);
    }

    public function restore(User $user, BerkasArsip $model): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, BerkasArsip $model): bool
    {
        return $user->hasRole('admin');
    }
}