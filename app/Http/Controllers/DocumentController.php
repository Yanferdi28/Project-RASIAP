<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\ArsipUnit;

class DocumentController extends Controller
{
    public function download(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Access denied');
        }

        $arsipUnit = ArsipUnit::find($id);
        if (!$arsipUnit) {
            abort(404, 'Arsip Unit not found');
        }

        // Check if user is authorized to download this specific document
        if (!\App\Helpers\DocumentHelper::canAccessDocument($arsipUnit, $user, 'download')) {
            abort(403, 'Access denied to download this document');
        }

        if (!\App\Helpers\DocumentHelper::documentExists($arsipUnit)) {
            abort(404, 'File not found in storage');
        }

        $fullPath = \App\Helpers\DocumentHelper::getDocumentPath($arsipUnit);
        $filename = \App\Helpers\DocumentHelper::getDocumentFilename($arsipUnit);
        
        return response()->download($fullPath, $filename);
    }
    
    public function view(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Access denied');
        }

        $arsipUnit = ArsipUnit::find($id);
        if (!$arsipUnit) {
            abort(404, 'Arsip Unit not found');
        }

        // Check if user is authorized to view this specific document
        if (!\App\Helpers\DocumentHelper::canAccessDocument($arsipUnit, $user, 'view')) {
            abort(403, 'Access denied to view this document');
        }

        if (!\App\Helpers\DocumentHelper::documentExists($arsipUnit)) {
            abort(404, 'File not found in storage');
        }
        
        $fullPath = \App\Helpers\DocumentHelper::getDocumentPath($arsipUnit);
        $filename = \App\Helpers\DocumentHelper::getDocumentFilename($arsipUnit);
        $mimeType = \App\Helpers\DocumentHelper::getDocumentMimeType($arsipUnit);
        
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
    
    public function show(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Access denied');
        }

        $action = $request->query('action', 'view');
        
        $arsipUnit = ArsipUnit::find($id);
        if (!$arsipUnit) {
            abort(404, 'Arsip Unit not found');
        }

        // Check appropriate permission based on action
        if (!\App\Helpers\DocumentHelper::canAccessDocument($arsipUnit, $user, $action)) {
            $message = $action === 'download' 
                ? 'Access denied to download this document' 
                : 'Access denied to view this document';
            abort(403, $message);
        }

        if (!\App\Helpers\DocumentHelper::documentExists($arsipUnit)) {
            abort(404, 'File not found in storage');
        }
        
        $fullPath = \App\Helpers\DocumentHelper::getDocumentPath($arsipUnit);
        $filename = \App\Helpers\DocumentHelper::getDocumentFilename($arsipUnit);
        
        if ($action === 'download') {
            return response()->download($fullPath, $filename);
        } else {
            $mimeType = \App\Helpers\DocumentHelper::getDocumentMimeType($arsipUnit);
            
            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        }
    }
}