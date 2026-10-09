<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

class ImageUploader
{
    private const MAX_BYTES = 2_097_152;

    private const EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public function store(?UploadedFile $file, string $folder, ?string $oldFilename = null): ?string
    {
        if ($file === null || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return $oldFilename;
        }

        if (! $file->isValid() || $file->hasMoved()) {
            throw new RuntimeException($file->getErrorString() ?: 'The uploaded image is invalid.');
        }

        if ($file->getSize() > self::MAX_BYTES) {
            throw new RuntimeException('Images must be 2 MB or smaller.');
        }

        $mime = $file->getMimeType();
        if (! isset(self::EXTENSIONS[$mime])) {
            throw new RuntimeException('Only JPG, PNG, and WebP images are allowed.');
        }

        $directory = FCPATH . 'uploads/' . trim($folder, '/');
        if (! is_dir($directory) && ! mkdir($directory, 0775, true) && ! is_dir($directory)) {
            throw new RuntimeException('The upload directory could not be created.');
        }

        $filename = bin2hex(random_bytes(20)) . '.' . self::EXTENSIONS[$mime];
        $file->move($directory, $filename);
        $path = $directory . DIRECTORY_SEPARATOR . $filename;

        try {
            service('image')->withFile($path)->fit(800, 800, 'center')->save($path, 85);
        } catch (\Throwable $exception) {
            @unlink($path);
            throw new RuntimeException('The image could not be prepared for display.', 0, $exception);
        }

        if ($oldFilename !== null && $oldFilename !== $filename) {
            $this->remove($oldFilename, $folder);
        }

        return $filename;
    }

    public function remove(?string $filename, string $folder): void
    {
        if ($filename === null || basename($filename) !== $filename) {
            return;
        }

        $path = FCPATH . 'uploads/' . trim($folder, '/') . DIRECTORY_SEPARATOR . $filename;
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
