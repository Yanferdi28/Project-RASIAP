<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use App\Models\ArsipUnit;

class DocumentHelper
{
    /**
     * Check if a document file exists in storage
     */
    public static function documentExists(ArsipUnit $arsipUnit): bool
    {
        if (!$arsipUnit->dokumen) {
            return false;
        }
        
        return Storage::disk('public')->exists($arsipUnit->dokumen);
    }
    
    /**
     * Get the full path to a document file
     */
    public static function getDocumentPath(ArsipUnit $arsipUnit): ?string
    {
        if (!self::documentExists($arsipUnit)) {
            return null;
        }
        
        return Storage::disk('public')->path($arsipUnit->dokumen);
    }
    
    /**
     * Get the document filename
     */
    public static function getDocumentFilename(ArsipUnit $arsipUnit): ?string
    {
        if (!$arsipUnit->dokumen) {
            return null;
        }
        
        return basename($arsipUnit->dokumen);
    }
    
    /**
     * Get the document MIME type
     */
    public static function getDocumentMimeType(ArsipUnit $arsipUnit): ?string
    {
        $path = self::getDocumentPath($arsipUnit);
        
        if (!$path) {
            return null;
        }
        
        return mime_content_type($path);
    }
    
    /**
     * Validate document access for user
     */
    public static function canAccessDocument(ArsipUnit $arsipUnit, $user, string $action = 'view'): bool
    {
        if (!$user) {
            return false;
        }
        
        // Check specific permission based on action
        $permission = $action === 'download' ? 'downloadDocument' : 'viewDocument';
        
        return \Illuminate\Support\Facades\Gate::allows($permission, $arsipUnit);
    }
}