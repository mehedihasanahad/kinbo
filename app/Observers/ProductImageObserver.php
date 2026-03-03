<?php

namespace App\Observers;

use App\Models\ProductImage;
use App\Services\ImageProcessor;

class ProductImageObserver
{
    public function created(ProductImage $image): void
    {
        $this->process($image);
    }

    public function updated(ProductImage $image): void
    {
        if ($image->wasChanged('path')) {
            $this->process($image);
        }
    }

    private function process(ProductImage $image): void
    {
        if (! $image->path) {
            return;
        }

        $newPath = app(ImageProcessor::class)->processProduct($image->path);

        if ($newPath !== $image->path) {
            // Update without triggering the observer again
            ProductImage::withoutEvents(fn () => $image->updateQuietly(['path' => $newPath]));
        }
    }
}
