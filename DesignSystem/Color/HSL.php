<?php

namespace Northrook\DesignSystem\Color;

use OzdemirBurak\Iris\Color;

final class HSL
{
    public static function fromHue( int $hue ) : Color\Hsl {
        return new Color\HSL( "$hue, 50, 50" );
    }

    public static function fromHex( string $hex ) : Color\Hsl {
        $hex = \substr( \ltrim( $hex, "# \n\r\t\v\0" ), 0, 6 );
        return ( new Color\Hex( $hex ) )->toHsl();
    }

    public static function fromRGB( mixed $rgb ) : Color\Hsl {
        return ( new Color\Rgba( $rgb ) )->toHsl();
    }
}