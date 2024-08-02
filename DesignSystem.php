<?php

declare( strict_types = 1 );

namespace Northrook;

use Northrook\Core\Process\Status;
use Northrook\DesignSystem\ColorCollection;

final class DesignSystem
{
    public readonly ColorCollection $colorPalette;

    public function __construct(
        public readonly string $name,
    ) {
        $this->colorPalette = new ColorCollection();
    }
}