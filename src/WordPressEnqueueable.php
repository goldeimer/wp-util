<?php

declare(strict_types=1);

namespace Goldeimer\WordPress\WpUtil;

trait WordPressEnqueable
{
    private string $uri;
    private string $relpath;
    private string $handle;

    private static AssetType $type;

    final public function handle(): string
    {
        return $this->handle;
    }

    final public function relpath(): string
    {
        return $this->relpath;
    }

    final public function uri(): string
    {
        return $this->uri;
    }

    final private function init(
        string $baseUri,
        string $relpath
    ): void {
        $this->uri = self::makeUri($baseUri, $relpath);
        $this->handle = self::makeHandle($relpath);
    }

    final private static function makeHandle(string $path): string
    {
        $dirnames = explode('/', $path);

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

    final private static function makeUri(
        $baseUri,
        $relpath
    ): string {
        return \trailingslashit($baseUri) . 'static/' . $relpath;
    }

    abstract public function enqueue();
    abstract public function register();
}
