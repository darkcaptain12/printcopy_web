<?php

if (!function_exists('t')) {
    function t($key, $default = null) {
        static $translations = null;
        
        if ($translations === null) {
            $langFile = __DIR__ . '/../../config/i18n/tr.php';
            if (file_exists($langFile)) {
                $translations = require $langFile;
            } else {
                $translations = [];
            }
        }
        
        return $translations[$key] ?? ($default ?? $key);
    }
}
