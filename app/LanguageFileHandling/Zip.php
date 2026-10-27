<?php

namespace App\LanguageFileHandling;

use RuntimeException;
use ZipArchive;

class Zip
{
    /**
     * Packs the files of a directory into a flat archive (like `zip -j`), replacing an existing one.
     */
    public static function directory(string $sourceDir, string $outputFile): void
    {
        if (is_file($outputFile)) {
            unlink($outputFile);
        }

        $zip = new ZipArchive();
        if ($zip->open($outputFile, ZipArchive::CREATE) !== true) {
            throw new RuntimeException('Cannot create '.$outputFile);
        }

        foreach (glob($sourceDir.'/*') as $file) {
            if (is_file($file)) {
                $zip->addFile($file, basename($file));
            }
        }

        if (!$zip->close()) {
            throw new RuntimeException('Cannot write '.$outputFile);
        }
    }
}
