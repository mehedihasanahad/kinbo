<?php

namespace App\Observers;

use App\Models\Brand;
use App\Services\ImageProcessor;

class BrandObserver
{
    public function created(Brand $brand): void
    {
        $this->process($brand);
    }

    public function updated(Brand $brand): void
    {
        if ($brand->wasChanged('logo')) {
            $this->process($brand);
        }
    }

    private function process(Brand $brand): void
    {
        if (! $brand->logo) {
            return;
        }

        $newPath = app(ImageProcessor::class)->processBrand($brand->logo);

        if ($newPath !== $brand->logo) {
            $brand->updateQuietly(['logo' => $newPath]);
        }
    }
}
