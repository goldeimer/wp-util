<?php

declare(strict_types=1);

namespace Goldeimer\WordPress\WpUtil;

final class WordPressEnqueueableScript
{
    use WordPressEnqueueable;

    final public function __construct(
        string $baseUri,
        string $relpath
    ) {
        self::$type = AssetType::JS();

        $this->init($baseUri, $relpath);
    }

    final public function enqueue(): void
    {
        \wp_enqueue_script($this->handle);
    }

    final public function register(): void
    {
        \wp_register_script(
            $this->handle,
            $this->uri,
            [],
            false,
            true
        );
    }
}
