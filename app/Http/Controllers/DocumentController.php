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

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Access denied');
        }


        $arsipUnit = ArsipUnit::find($id);
        if (!$arsipUnit || !$arsipUnit->dokumen) {
            abort(404, 'Arsip Unit or document not found');
        }



        $storedPath = $arsipUnit->dokumen;
        

        if (!Storage::disk('public')->exists($storedPath)) {
            abort(404, 'File not found in storage');
        }
        

        $fullPath = Storage::disk('public')->path($storedPath);
        $filename = basename($storedPath);
        

        return response()->download($fullPath, $filename);

    }
    
    public function view(Request $request, $id)
    {

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Access denied');
        }


        $arsipUnit = ArsipUnit::find($id);
        if (!$arsipUnit || !$arsipUnit->dokumen) {
            abort(404, 'Arsip Unit or document not found');
        }



        $storedPath = $arsipUnit->dokumen;


        if (!Storage::disk('public')->exists($storedPath)) {
            abort(404, 'File not found in storage');
        }
        

        $fullPath = Storage::disk('public')->path($storedPath);
        $filename = basename($storedPath);
        $mimeType = mime_content_type($fullPath);
        

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
        if (!$arsipUnit || !$arsipUnit->dokumen) {
            abort(404, 'Arsip Unit or document not found');
        }


        $storedPath = $arsipUnit->dokumen;


        if (!Storage::disk('public')->exists($storedPath)) {
            abort(404, 'File not found in storage');
        }
        
        $fullPath = Storage::disk('public')->path($storedPath);
        $filename = basename($storedPath);
        
        if ($action === 'download') {

            return response()->download($fullPath, $filename);
        } else {

            $mimeType = mime_content_type($fullPath);
            

            return response()->file($fullPath, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]);
        }
    }
}