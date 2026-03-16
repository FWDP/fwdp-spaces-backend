<?php

namespace App\Core\Files\Services;

use App\Core\Files\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function upload(
        UploadedFile $file,
        string $disk = 'public',
        ?int $user_id = null
    ): File
    {
        return File::query()->create([
            'disk' => $disk,
            'path' => $file->store('', $disk),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'user_id' => $user_id,
        ]);
    }

    public function delete(File $file): void
    {
        Storage::disk($file->disk)->delete($file->path);

        $file->delete();
    }

    public function url(File $file): string
    {
        return Storage::disk($file->disk)->url($file->path);
    }
}