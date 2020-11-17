<?php

declare(strict_types=1);

namespace Goldeimer\WordPress\WpUtil;

class StaticWebpackAssetLoader
{
    final protected static function readManifest(string $pkgAbspath)
    {
        return json_decode(
            file_get_contents(
                $pkgAbspath . '/static/artifacts/manifest.json'
            ),
            true
        );
    }

    final protected static function handle(string $relpath): string
    {
        $dirnames = explode('/', $relpath);

        return preg_replace(
            '/\.|~/u',
            '-',
            preg_replace(
                '/\.\w{2,3}$/u',
                '',
                array_pop($dirnames)
            )
        );
    }

    final protected static function assetUri(string $relpath): string
    {
        return get_stylesheet_directory_uri() . '/static/' . $relpath;
    }

    final protected static function registerScript(
        string $handle,
        string $relpath
    ): bool {
        return \wp_register_script(
            $handle,
            self::assetUri($relpath),
            array(),
            false,
            true
        );
    }

    final protected static function registerStyle(
        string $handle,
        string $relpath
    ): bool {
        return \wp_register_style(
            $handle,
            self::assetUri($relpath)
        );
    }

    final protected static function registerAssets(
        string $pkgAbspath,
        bool $shouldEnqueue = true
    ): void {
        $manifest = self::readManifest($pkgAbspath);

        foreach ($manifest['entrypoints'] as $entrypoint) {
            foreach ($entrypoint as $sourceType => $assets) {
                foreach ($assets as $relpath) {
                    $handle = self::handle($relpath);

                    switch ($sourceType) {
                        case 'css':
                            $isRegistered = self::registerStyle($handle, $relpath);

                            if ($isRegistered && $shouldEnqueue) {
                                \wp_enqueue_style($handle);
                            }
                            break;

                        case 'js':
                            $isRegistered = self::registerScript($handle, $relpath);

                            if ($isRegistered && $shouldEnqueue) {
                                \wp_enqueue_script($handle);
                            }
                            break;
                    }
                }
            }
        }
    }
}
