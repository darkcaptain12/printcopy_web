<?php
namespace App\Core;

class Upload {
    public static function image($file, $destination = 'uploads/') {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $file['name'];
        $filetmp = $file['tmp_name'];
        $filesize = $file['size'];
        $fileerror = $file['error'];
        
        $fileext = explode('.', $filename);
        $fileactualext = strtolower(end($fileext));

        if (in_array($fileactualext, $allowed)) {
            if ($fileerror === 0) {
                if ($filesize < 5000000) { // 5MB
                    $filenewname = uniqid('', true) . "." . $fileactualext;
                    $targetDir = __DIR__ . '/../../storage/' . $destination;
                    
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    
                    $fileDestination = $targetDir . $filenewname;
                    move_uploaded_file($filetmp, $fileDestination);
                    return $destination . $filenewname;
                }
            }
        }
        return false;
    }
}
