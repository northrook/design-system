<?php

declare( strict_types = 1 );

namespace Northrook;

use Northrook\DesignSystem\ColorCollection;

final readonly class DesignSystem
{
    public ColorCollection $colorPalette;

    public function __construct() {
        $this->colorPalette = new ColorCollection();
    }
}