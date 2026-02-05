<?php
namespace App\Core;

class Seo
{
    public static function meta($title, $description = '', $canonical = '')
    {
        $out = [];
        if ($title) $out[] = "<title>" . htmlspecialchars($title) . "</title>";
        if ($description) $out[] = '<meta name="description" content="' . htmlspecialchars($description) . '">';
        if ($canonical) $out[] = '<link rel="canonical" href="' . htmlspecialchars($canonical) . '">';
        // simple OG tags
        if ($title) $out[] = '<meta property="og:title" content="' . htmlspecialchars($title) . '">';
        if ($description) $out[] = '<meta property="og:description" content="' . htmlspecialchars($description) . '">';
        if ($canonical) $out[] = '<meta property="og:url" content="' . htmlspecialchars($canonical) . '">';
        $out[] = '<meta property="og:type" content="website">';
        $out[] = '<meta name="twitter:card" content="summary_large_image">';
        return implode("\n", $out);
    }
}
