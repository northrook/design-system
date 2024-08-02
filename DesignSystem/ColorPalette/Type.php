<?php

namespace Northrook\DesignSystem\ColorPalette;

use Northrook\DesignSystem\ColorCollection;

/** @internal */
final class Type
{
    private string $type;

    public function __construct( private readonly ColorCollection $colorPalette ) {}

    public function weighted() : ColorCollection {}

    public function relative() : ColorCollection {}
}