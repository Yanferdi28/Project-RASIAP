<?php


namespace App\Policies;

use App\Models\User;
use App\Models\ArsipUnit;

class ArsipUnitPolicy
{
    public function viewAny(User $user): bool
    {
        // Admin, superadmin, and operator can view all records
        if ($user->hasAnyRole(['admin', 'superadmin', 'operator'])) {
            return true;
        }

        // Regular users can only view records from their own unit
        if ($user->hasRole('user') && $user->unit_pengolah_id) {
            return true;
        }

        return false;
    }

    public function view(User $user, ArsipUnit $model): bool
    {
        // Allow access based on role
        if ($user->hasAnyRole(['admin', 'user', 'operator'])) {
            return true;
        }
        
        // Add additional context-based access control if needed
        // For example, users might be allowed to access documents from their own unit
        if ($user->unit_pengolah_id && $model->unit_pengolah_arsip_id === $user->unit_pengolah_id) {
            return true;
        }
        
        return false;
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
        return $user->hasAnyRole(['admin', 'user']);
    }

    public function submit(User $user, ArsipUnit $record): bool
    {
        return $user->can('arsipunit.submit') || $record->publish_status === 'draft';
    }

    public function verify(User $user, ArsipUnit $record): bool
    {
        return $user->can('arsipunit.verify') && $record->publish_status === 'submitted';
    }
    
    public function downloadDocument(User $user, ArsipUnit $model): bool
    {
        // Allow users with appropriate roles to download documents
        if ($user->hasAnyRole(['admin', 'user', 'operator'])) {
            return true;
        }
        
        // Additional context-based access control
        if ($user->unit_pengolah_id && $model->unit_pengolah_arsip_id === $user->unit_pengolah_id) {
            return true;
        }
        
        return false;
    }
    
    public function viewDocument(User $user, ArsipUnit $model): bool
    {
        // Allow users with appropriate roles to view documents
        if ($user->hasAnyRole(['admin', 'user', 'operator'])) {
            return true;
        }
        
        // Additional context-based access control
        if ($user->unit_pengolah_id && $model->unit_pengolah_arsip_id === $user->unit_pengolah_id) {
            return true;
        }
        
        return false;
    }
}
