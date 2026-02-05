<?php
namespace App\Core;

class ImageHelper
{
    /**
     * Normalize image path:
     * - If starts with http/https, return as-is (remote)
     * - If already prefixed with /storage or storage, ensure single /storage/
     * - Otherwise prefix /storage/
     * - If empty, return placeholder if provided, else empty string
     */
    public static function url(?string $path, ?string $placeholder = null): string
    {
        $path = trim((string) $path);
        if ($path === '') {
            return $placeholder ?? '';
        }

        if (preg_match('#^https?://#i', $path)) {
            return $path;
        }

        $clean = ltrim($path, '/');
        if (str_starts_with($clean, 'storage/')) {
            return '/' . $clean;
        }

        return '/storage/' . $clean;
    }
}
