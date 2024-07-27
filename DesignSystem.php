<?php

declare( strict_types = 1 );

namespace Northrook;

use Northrook\Core\Process\Status;
use Northrook\DesignSystem\ColorPalette;

final class DesignSystem
{
    public readonly ColorPalette $colorPalette;

    public function __construct(
        public readonly string $name,
    ) {
        $this->colorPalette = new ColorPalette();
    }
}