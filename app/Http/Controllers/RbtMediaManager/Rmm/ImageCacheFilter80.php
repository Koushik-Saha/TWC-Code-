<?php

namespace App\Http\Controllers\RbtMediaManager\Rmm;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class ImageCacheFilter80 implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->resize(80, 80);
    }
}