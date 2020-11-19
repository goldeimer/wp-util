<?php

declare(strict_types=1);

namespace Goldeimer\WordPress\WpUtil;

final class WebpackAssetLoader
{
    private $enqueueables = [];

    public function __construct(
        string $baseUri,
        string $pkgAbspath
    ) {
        $this->enqueueables = self::parseManifest($baseUri, $pkgAbspath);
    }

    public function enqueue()
    {
        $this->eachEnqueueable('enqueue');
    }

    public function register()
    {
        $this->eachEnqueueable('register');
    }

    final private function eachEnqueueable(string $function): void
    {
        foreach ($this->enqueueables as $enqueueable) {
            $enqueueable->$function();
        }
    }

    final private static function parseManifest(
        string $baseUri,
        string $pkgAbspath
    ): array {
        $enqueueables = [];
        $manifest = self::readManifest($pkgAbspath);

        foreach ($manifest['entrypoints'] as $entrypoint) {
            foreach ($entrypoint as $sourceType => $assets) {
                foreach ($assets as $relpath) {
                    switch ($sourceType) {
                        case 'css':
                            $enqueueables[] = new WordPressEnqueueableStyle($baseUri, $relpath);
                            break;

                        case 'js':
                            $enqueueables[] = new WordPressEnqueueableScript($baseUri, $relpath);
                            break;
                    }
                }
            }
        }

        return $enqueueables;
    }

    final private static function readManifest(string $pkgAbspath): array
    {
        return json_decode(
            file_get_contents(
                $pkgAbspath . '/static/artifacts/manifest.json'
            ),
            true
        );
    }
}
