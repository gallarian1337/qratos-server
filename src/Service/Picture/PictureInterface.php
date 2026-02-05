<?php

declare(strict_types=1);

namespace App\Service\Picture;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PictureInterface
{
    public function upload(UploadedFile $file): string;
}
