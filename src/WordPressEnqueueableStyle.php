<?php

declare(strict_types=1);

namespace Goldeimer\WordPress\WpUtil;

final class WordPressEnqueableStyle
{
    use WordPressEnqueable;

    final public function __construct(
        string $baseUri,
        string $relpath
    ) {
        self::$type = AssetType::CSS();

        $this->init($baseUri, $relpath);
    }

    final public function enqueue(): void
    {
        \wp_enqueue_style($this->handle);
    }

    final public function register(): void
    {
        \wp_register_style(
            $this->handle,
            $this->uri
        );
    }
}
