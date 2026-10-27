<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

use Codeception\Module;
use Symfony\Component\BrowserKit\Response;
use ZipArchive;

/**
 * Response assertions the Laravel module (InnerBrowser) lacks: headers and zip downloads.
 */
class Responses extends Module
{
    public function seeResponseHeaderContains(string $name, string $expected): void
    {
        $this->assertStringContainsString($expected, (string) $this->grabResponseHeader($name));
    }

    public function grabResponseHeader(string $name): ?string
    {
        return $this->getResponse()->getHeader($name);
    }

    /**
     * @return array<string, string> file name => file contents
     */
    public function grabZipEntriesFromResponse(): array
    {
        $path = tempnam(sys_get_temp_dir(), 'uwetranslate_test_');
        file_put_contents($path, $this->getResponse()->getContent());

        try {
            return self::readZip($path);
        } finally {
            unlink($path);
        }
    }

    /**
     * @return array<string, string> file name => file contents, sorted by name
     */
    public static function readZip(string $path): array
    {
        $zip = new ZipArchive();
        $result = $zip->open($path);
        if ($result !== true) {
            throw new \RuntimeException("Can't open $path as a zip archive (error $result)");
        }

        $entries = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entries[$zip->getNameIndex($i)] = $zip->getFromIndex($i);
        }
        $zip->close();
        ksort($entries);

        return $entries;
    }

    private function getResponse(): Response
    {
        return $this->getModule('Laravel')->client->getInternalResponse();
    }
}
