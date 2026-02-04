<?php

declare(strict_types=1);

namespace App\Enum;

enum PictureTypeMimeEnum: string
{
    case PNG = 'image/png';
    case JPG = 'image/jpg';
    case JPEG = 'image/jpeg';
    case WEBP = 'image/webp';

    public static function match(string $typeMime): bool
    {
        if (
            self::PNG->value === $typeMime ||
            self::JPG->value === $typeMime ||
            self::JPEG->value === $typeMime ||
            self::WEBP->value === $typeMime
        ) {
            return true;
        }

        return false;
    }
}
