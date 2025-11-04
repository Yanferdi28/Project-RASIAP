<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\ArsipUnit;

class DocumentController extends Controller
{
    public function download(Request $request, $id)
    {
        // Check if user is authenticated
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Access denied');
        }

        // Find the arsip unit by ID
        $arsipUnit = ArsipUnit::find($id);
        if (!$arsipUnit || !$arsipUnit->dokumen) {
            abort(404, 'Arsip Unit or document not found');
        }

        // --- LOGIKA DISINI DIPERBAIKI ---
        // Path yang tersimpan di database (misal: 'arsip-dokumen/file.pdf')
        $storedPath = $arsipUnit->dokumen;
        
        // Periksa apakah file ada di disk 'public'
        if (!Storage::disk('public')->exists($storedPath)) {
            abort(404, 'File not found in storage');
        }
        
        // Dapatkan full path dan nama file
        $fullPath = Storage::disk('public')->path($storedPath);
        $filename = basename($storedPath);
        
        // Return the file for download
        return response()->download($fullPath, $filename);
        // --- BATAS PERBAIKAN ---
    }
    
    public function view(Request $request, $id)
    {
        // Check if user is authenticated
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Access denied');
        }

        // Find the arsip unit by ID
        $arsipUnit = ArsipUnit::find($id);
        if (!$arsipUnit || !$arsipUnit->dokumen) {
            abort(404, 'Arsip Unit or document not found');
        }

        // --- LOGIKA DISINI DIPERBAIKI ---
        // Path yang tersimpan di database (misal: 'arsip-dokumen/file.pdf')
        $storedPath = $arsipUnit->dokumen;

        // Periksa apakah file ada di disk 'public'
        if (!Storage::disk('public')->exists($storedPath)) {
            abort(404, 'File not found in storage');
        }
        
        // Dapatkan full path dan nama file
        $fullPath = Storage::disk('public')->path($storedPath);
        $filename = basename($storedPath);
        $mimeType = mime_content_type($fullPath);
        
        // Return the file for viewing (inline display)
        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
        // --- BATAS PERBAIKAN ---
    }
}