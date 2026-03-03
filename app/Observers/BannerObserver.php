<?php

namespace App\Observers;

use App\Models\Banner;
use App\Services\ImageProcessor;

class BannerObserver
{
    public function created(Banner $banner): void
    {
        $this->process($banner);
    }

    public function updated(Banner $banner): void
    {
        if ($banner->wasChanged('image')) {
            $this->process($banner);
        }
    }

    private function process(Banner $banner): void
    {
        if (! $banner->image) {
            return;
        }

        $newPath = app(ImageProcessor::class)->processBanner($banner->image);

        if ($newPath !== $banner->image) {
            $banner->updateQuietly(['image' => $newPath]);
        }
    }
}
