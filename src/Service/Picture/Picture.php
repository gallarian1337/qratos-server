<?php

declare(strict_types=1);

namespace App\Service\Picture;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Picture implements PictureInterface
{
    public function __construct()
    {
    }

    public function upload(UploadedFile $file): string
    {
        $originalName = $file->getClientOriginalName();
        $originalExtension = $file->guessExtension();

        $newFilename = uniqid() . '.' . $originalExtension;

        try {
            $file->move('uploads', $newFilename);
        } catch (FileException $e) {
            dd($e->getMessage());
        }

        return $newFilename;
    }
}
