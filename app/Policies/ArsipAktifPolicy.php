<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ArsipAktif;

class ArsipAktifPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'user', 'operator']);
    }

    public function view(User $user, ArsipAktif $model): bool
    {
        return $user->hasAnyRole(['admin', 'user', 'operator']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'user']);
    }

    public function update(User $user, ArsipAktif $model): bool
    {
        if ($user->hasRole('operator')) {
            return true;
        }
        
        return $user->hasAnyRole(['admin', 'user']);
    }

    public function delete(User $user, ArsipAktif $model): bool
    {
        return $user->hasRole('admin');
    }

    public function restore(User $user, ArsipAktif $model): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, ArsipAktif $model): bool
    {
        return $user->hasRole('admin');
    }
}