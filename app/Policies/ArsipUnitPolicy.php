<?php

// app/Policies/ArsipUnitPolicy.php
namespace App\Policies;

use App\Models\User;
use App\Models\ArsipUnit;

class ArsipUnitPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'user', 'operator']);
    }

    public function view(User $user, ArsipUnit $model): bool
    {
        return $user->hasAnyRole(['admin', 'user', 'operator']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'user']); // operator tidak bisa create
    }

    public function update(User $user, ArsipUnit $model): bool
    {
        return $user->hasAnyRole(['admin', 'user']); // operator tidak bisa edit
    }

    public function delete(User $user, ArsipUnit $model): bool
    {
        return $user->hasRole('admin'); // hanya admin
    }
}
