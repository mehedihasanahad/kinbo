<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\ImageProcessor;

class CategoryObserver
{
    public function created(Category $category): void
    {
        $this->process($category);
    }

    public function updated(Category $category): void
    {
        if ($category->wasChanged('image')) {
            $this->process($category);
        }
    }

    private function process(Category $category): void
    {
        if (! $category->image) {
            return;
        }

        $newPath = app(ImageProcessor::class)->processCategory($category->image);

        if ($newPath !== $category->image) {
            $category->updateQuietly(['image' => $newPath]);
        }
    }
}
