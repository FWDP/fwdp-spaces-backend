<?php

namespace App\Core\Files\Http\Controllers;

use App\Core\Files\Models\File;
use App\Core\Files\Services\FileService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240'
        ]);

        return response()->json([
            'file' => $this->fileService->upload(
                $request->file('file'),
                'public',
                $request->user()->id),
        ], 201);
    }

    public function show(File $file)
    {
        return response()->json([
            'url' => $this->fileService->url($file),
        ]);
    }

    public function destroy(File $file)
    {
        $this->fileService->delete($file);
        return response()->json(['message' => 'File deleted']);
    }
}
