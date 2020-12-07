<?php

declare(strict_types=1);

namespace Goldeimer\WordPress\WpUtil;

use MyCLabs\Enum\Enum;

final class AssetType extends Enum
{
    private const CSS = 'css';
    private const JS = 'js';
}
