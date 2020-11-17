<?php

declare(strict_types=1);

namespace Goldeimer\WordPress\WpUtil;

final class WebpackAssetLoader extends StaticWebpackAssetLoader
{
    private $pkgAbspath = '';
    private $shouldEnqueue = '';

    public function __construct(
        $pkgAbspath = null,
        $shouldEnqueue = true
    ) {
        $this->pkgAbspath = $pkgAbspath;
        $this->shouldEnqueue = $shouldEnqueue;
    }

    public function register($pkgAbspath = null)
    {
        if ($pkgAbspath) {
            $this->pkgAbspath = $pkgAbspath;
        }

        \add_action(
            'wp_enqueue_scripts',
            [$this, 'registerAssetsCallback']
        );
    }

    public function registerAssetsCallback()
    {
        self::registerAssets(
            $this->pkgAbspath,
            $this->shouldEnqueue
        );
    }
}
