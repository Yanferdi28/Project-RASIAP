<?php


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
        return $user->hasAnyRole(['admin', 'user']);
    }

    public function update(User $user, ArsipUnit $model): bool
    {

        if ($user->hasRole('operator')) {
            return true;
        }
        

        return $user->hasAnyRole(['admin', 'user']);
    }

    public function delete(User $user, ArsipUnit $model): bool
    {
        return $user->hasRole('admin');
    }

    public function submit(User $user, ArsipUnit $record): bool
    {
        return $user->can('arsipunit.submit') || $record->publish_status === 'draft';
    }

    public function verify(User $user, ArsipUnit $record): bool
    {
        return $user->can('arsipunit.verify') && $record->publish_status === 'submitted';
    }
}
