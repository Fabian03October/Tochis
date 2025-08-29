<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestImageController extends Controller
{
    public function test()
    {
        return view('test-image');
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                
                // Información del archivo
                $info = [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'is_valid' => $file->isValid(),
                    'path' => $file->getRealPath(),
                ];

                // Intentar guardar
                $path = $file->store('test-products', 'public');
                
                if ($path) {
                    $info['stored_path'] = $path;
                    $info['full_url'] = Storage::url($path);
                    $info['status'] = 'success';
                    $info['message'] = 'Imagen guardada exitosamente';
                } else {
                    $info['status'] = 'error';
                    $info['message'] = 'No se pudo guardar la imagen';
                }

                return response()->json($info);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se recibió ningún archivo',
                    'files_received' => $request->allFiles()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
