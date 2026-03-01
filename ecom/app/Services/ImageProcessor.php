<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageProcessor
{
    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Process a product image:
     *   - Scale down to max 1200px wide (preserve aspect ratio)
     *   - Convert to WebP at quality 82
     *   - Replace the original file in-place (same path, .webp extension)
     *
     * Returns the new storage path (may differ if extension changed).
     */
    public function processProduct(string $path): string
    {
        return $this->resizeAndConvert($path, maxWidth: 1200, maxHeight: 1200, quality: 82);
    }

    /**
     * Process a banner image:
     *   - Resize/crop to exactly 1920×700 (cover)
     *   - Convert to WebP at quality 85
     */
    public function processBanner(string $path): string
    {
        return $this->coverAndConvert($path, width: 1920, height: 700, quality: 85);
    }

    /**
     * Process a category image:
     *   - Resize/crop to 600×600 square (cover)
     *   - Convert to WebP at quality 82
     */
    public function processCategory(string $path): string
    {
        return $this->coverAndConvert($path, width: 600, height: 600, quality: 82);
    }

    /**
     * Process a brand logo:
     *   - Scale down to max 400×200 (no upscale, preserve aspect ratio)
     *   - Keep original format (SVGs are skipped)
     *   - Convert raster formats to WebP at quality 85
     */
    public function processBrand(string $path): string
    {
        // Skip SVGs — they are vector and don't benefit from raster processing
        if (str_ends_with(strtolower($path), '.svg')) {
            return $path;
        }

        return $this->resizeAndConvert($path, maxWidth: 400, maxHeight: 200, quality: 85);
    }

    /**
     * Scale down proportionally if larger than maxWidth × maxHeight.
     * Converts to WebP. Returns new path.
     */
    private function resizeAndConvert(string $path, int $maxWidth, int $maxHeight, int $quality): string
    {
        $fullPath = Storage::disk('public')->path($path);

        if (! file_exists($fullPath)) {
            return $path;
        }

        try {
            $image = $this->manager->read($fullPath);

            // Only downscale — never upscale
            if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
                $image->scaleDown(width: $maxWidth, height: $maxHeight);
            }

            $newPath = $this->toWebpPath($path);
            $newFullPath = Storage::disk('public')->path($newPath);

            // Ensure directory exists
            @mkdir(dirname($newFullPath), 0755, true);

            $image->toWebp($quality)->save($newFullPath);

            // Remove old file if extension changed
            if ($newPath !== $path && file_exists($fullPath)) {
                @unlink($fullPath);
            }

            return $newPath;
        } catch (\Throwable) {
            // If processing fails for any reason, keep the original
            return $path;
        }
    }

    /**
     * Cover-fit (crop) to exact dimensions and convert to WebP.
     */
    private function coverAndConvert(string $path, int $width, int $height, int $quality): string
    {
        $fullPath = Storage::disk('public')->path($path);

        if (! file_exists($fullPath)) {
            return $path;
        }

        try {
            $image = $this->manager->read($fullPath);
            $image->cover($width, $height);

            $newPath = $this->toWebpPath($path);
            $newFullPath = Storage::disk('public')->path($newPath);

            @mkdir(dirname($newFullPath), 0755, true);

            $image->toWebp($quality)->save($newFullPath);

            if ($newPath !== $path && file_exists($fullPath)) {
                @unlink($fullPath);
            }

            return $newPath;
        } catch (\Throwable) {
            return $path;
        }
    }

    /**
     * Replace file extension with .webp.
     */
    private function toWebpPath(string $path): string
    {
        return preg_replace('/\.(jpe?g|png|gif|bmp|tiff?)$/i', '.webp', $path) ?? $path;
    }
}
