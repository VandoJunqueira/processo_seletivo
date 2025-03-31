<?php

namespace App\Services\Upload;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    // Allowed image MIME types
    private array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/svg+xml',
    ];

    // Maximum file size in bytes (default: 5MB)
    private int $maxFileSize = 5242880;

    /**
     * Handle the image upload process
     *
     * @param Request $request
     * @param string $name Input field name (default: 'file')
     * @param string $path Subdirectory path (optional)
     * @return JsonResponse
     */
    public function handle(Request $request, string $name = 'file'): JsonResponse
    {
        try {

            Log::info('ImageUploadService', [$request->file('file')]);

            if (!$request->hasFile($name)) {
                return $this->errorResponse('No file was uploaded.', 400);
            }

            /** @var UploadedFile $file */
            $file = $request->file($name);

            $validationError = $this->validateFile($file);
            if ($validationError) {
                return $validationError;
            }

            // Ensure the path ends with a slash


            $file_name = $this->generateFilename($file);
            $file_path = 'uploads/' . $file_name;

            Storage::disk('s3')->put(
                $file_path,
                file_get_contents($file->getRealPath()),
                'public'
            );

            return response()->json([
                'status' => 'success',
                'data' => [
                    'src' => Storage::disk('s3')->url($file_path),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'name' => $file->getClientOriginalName(),
                    'stored_name' => $file_name,
                    'path' => $file_path,
                ]
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar imagem', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->errorResponse('Server error: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Validate the uploaded file
     *
     * @param UploadedFile $file
     * @return JsonResponse|null Returns error response if validation fails, otherwise null
     */
    protected function validateFile(UploadedFile $file): ?JsonResponse
    {
        if (!$file->isValid()) {
            return $this->errorResponse('File upload failed: ' . $file->getErrorMessage(), 422);
        }

        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            return $this->errorResponse(
                'Invalid file type. Allowed types: ' . implode(', ', $this->allowedMimeTypes),
                422
            );
        }

        if ($file->getSize() > $this->maxFileSize) {
            return $this->errorResponse(
                'File too large. Maximum size: ' . $this->formatBytes($this->maxFileSize),
                422
            );
        }

        return null;
    }

    /**
     * Generate a unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        return Str::uuid() . '.' . $file->getClientOriginalExtension();
    }

    /**
     * Format bytes to human-readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * Set allowed MIME types
     *
     * @param array $mimeTypes
     * @return self
     */
    public function setAllowedMimeTypes(array $mimeTypes): self
    {
        $this->allowedMimeTypes = $mimeTypes;
        return $this;
    }

    /**
     * Set maximum file size
     *
     * @param int $bytes
     * @return self
     */
    public function setMaxFileSize(int $bytes): self
    {
        $this->maxFileSize = $bytes;
        return $this;
    }

    /**
     * Return a standardized error response
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function errorResponse(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'error' => [
                'message' => $message,
                'code' => $statusCode
            ]
        ], $statusCode);
    }
}
