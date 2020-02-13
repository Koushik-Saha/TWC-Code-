<?php

namespace App\Http\Controllers\RbtMediaManager\Rmm;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class ImageCacheFilter400 implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->resize(400, 400);
    }
}