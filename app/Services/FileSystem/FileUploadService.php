<?php

namespace App\Services\FileSystem;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileUploadService
{
    /**
     * Upload a file to the S3 disk.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     * @throws \Exception
     */
    public function uploadFileToS3(UploadedFile $file, string $directory = 'uploads'): string
    {
        if (!$file->isValid()) {
            Log::error('Invalid file upload');
            throw new \Exception('Invalid file upload');
        }

        $fileName = time() . '_' . $file->getClientOriginalName();

        try {
            $filePath = $file->storeAs($directory, $fileName, 's3');

            if ($filePath === false) {
                Log::error('S3 file upload failed');
                throw new \Exception('Failed to upload file to S3');
            }

            Log::info('File uploaded to S3', ['file_path' => $filePath]);

            return config('AWS_FILE_LOAD_BASE') . $filePath;
        } catch (\Exception $e) {
            Log::error('Error uploading file to S3: ' . $e->getMessage());
            throw $e;
        }
    }



    /**
     * Upload raw content to S3.
     *
     * @param string $content
     * @param string $path
     * @return string
     * @throws \Exception
     */
    public function uploadContentToS3(string $content, string $path): string
    {
        try {
            $stored = Storage::disk('s3')->put($path, $content);

            if (!$stored) {
                Log::error('Failed to upload content to S3', ['path' => $path]);
                throw new \Exception('Failed to upload content to S3');
            }

            Log::info('File content uploaded to S3', ['file_path' => $path]);

            return config('AWS_FILE_LOAD_BASE') . $path;
        } catch (\Exception $e) {
            Log::error('Error uploading content to S3: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Upload a file to the 'protected' disk.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string
     * @throws \Exception
     */
    public function uploadFileToProtected(UploadedFile $file, string $directory = 'uploads'): string
    {
        if (!$file->isValid()) {
            throw new \Exception('Invalid file upload');
        }

        return $file->store($directory, 'protected');
    }

    /**
     * Read a file from the 'protected' disk.
     *
     * @param string $filename
     * @param string $directory
     * @return StreamedResponse
     * @throws \Exception
     */
    public function readFileFromProtected(string $filename, string $directory = 'uploads'): StreamedResponse
    {
        $filePath = "{$directory}/{$filename}";

        if (!Storage::disk('protected')->exists($filePath)) {
            throw new \Exception('File not found');
        }

        return Storage::disk('protected')->download($filePath);
    }
}
