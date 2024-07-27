<?php

namespace Northrook\DesignSystem\ColorPalette;

use Northrook\Core\Trait\PropertyAccessor;
use Northrook\DesignSystem\Compiler\Scale;
use Northrook\Logger\Log;
use OzdemirBurak\Iris\Color as Iris;
use Northrook\Core\Exception\CompileException;
use OzdemirBurak\Iris\Helpers\DefinedColor;
use function Northrook\numberBetween;
use function Northrook\replaceEach;
use function Northrook\stringExplode;
use function Northrook\toString;

/**
 * @property-read  array $colors
 */
final class Color
{
    use PropertyAccessor;

    public const BASELINE = [
        2, 5, 8,
        12, 60, 55,
        92, 95, 98,
    ];

    /**
     * The minimum distance to the floor (0), ceiling (100), and in between each curve value
     *
     * @var int
     */
    private readonly int      $lightnessPadding;
    private readonly Iris\Hsl $color;
    public array              $colors = [];
    public readonly int       $hue;
    public readonly string    $name;

    public function __construct( mixed $color, string $name, int $padding = 2, ) {
        $this->useColor( $color );
        $this->name             = $name;
        $this->lightnessPadding = $padding;
    }

    public function __get( string $property ) {
        return match ( $property ) {
            'colors' => $this->colors
        };
    }

    public function generateColor( array $curve, ?int $saturation = null, float $saturationMultiplier = -.7, ) : self {

        $saturation ??= $this->color->saturation();

        foreach ( $curve as $key => $lightness ) {
            $saturation += ( $key * $saturationMultiplier );

            $this->colors[ "$this->name-" . $key + 1 . '00' ] = ( clone $this->color )
                ->saturation( numberBetween( $saturation, 2, 98 ) )
                ->lightness( numberBetween( $lightness, 2, 98 ) );
        }
        return $this;
    }

    private function shadeScale( ?float $value, float $ratio, int $shades = 2 ) : array {

        // Assign default
        $value ??= $this->color->lightness( $value );

        // Initialize an empty array
        $result = [];

        // The initial value to add or subtract
        $bump = \intval( ( $value * $ratio ) - $value );

        // Place the central value
        $result[ 0 ] = $value;
        $shade       = numberBetween( $value, 45, 55 );

        for ( $i = 1; $i <= $shades; $i++ ) {

            $bump *= $ratio;


            $result[ $i * -1 ] = $shade - $bump;
            $result[ $i ]      = $shade + $bump;
        }
        $result = \array_map( '\intval', $result );
        ksort( $result );

        return \array_values( $result );
    }

    public function generateShade( float $scale, ?int $lightness = null, ) : self {

        $saturation ??= $this->color->saturation();

        $shades = $this->shadeScale( $lightness, $scale, 3 );

        foreach ( $this->shades( $shades ) as $key => $lightness ) {

            $this->colors[ toString( [ $this->name, $key ], '-' ) ] = ( clone $this->color )
                ->saturation( numberBetween( $saturation, 2, $lightness * 2 ) )
                ->lightness( numberBetween( $lightness, 2, 98 ) );
        }

        return $this;
    }

    /**
     * Will attempt to derive a base Hue from a given color string.
     *
     * Accepts RGBa, HEXa, HSLa, HSVa
     *
     * @param string|int  $color
     */
    private function useColor( mixed $color ) : void {

        // Set a $source for referencing, lowercase the $color
        $source = \is_string( $color ) ? $color = \strtolower( $color ) : $color;

        try {
            if ( $this::isHue( $color ) ) {
                $this->color = HSL::fromHue( $color );
            }
            elseif ( \str_starts_with( $color, 'rgb' ) ) {
                $this->color = HSL::fromRGB( $color );
            }
            elseif ( $this::isHex( $color ) ) {
                $hex         = \substr( \ltrim( $color, "# \n\r\t\v\0" ), 0, 6 );
                $this->color = HSL::fromHex( $color );
                // dump($color);
            }
            elseif ( $hsx = $this->asHSX( $color ) ) {
                $hsx         = \array_slice( $hsx, 0, 3 );
                $this->color = new Iris\Hsl( toString( $hsx, ',' ) );
            }
        }
        catch ( \Throwable $exception ) {
            Log::exception( $exception );
        }

        if ( !isset( $this->color ) ) {
            throw new CompileException( $this::class . "::useColor could not assign a base color." );
        }

        $this->hue = $this->color->hue();
    }

    public static function isHue( mixed $color ) : bool {
        $length = \strlen( (string) $color );
        return \is_int( $color ) && $length >= 1 && $length <= 3;
    }

    public static function isHex( mixed $color ) : bool {
        if ( !\is_string( $color ) ) {
            return false;
        }

        if ( \ctype_xdigit( \ltrim( $color, "# \n\r\t\v\0" ) ) ) {
            return true;
        }

        return false;
    }

    /**
     * @param mixed  $color
     *
     * @return false|array{int}
     */
    private function asHSX( mixed $color ) : false | array {

        if ( !\is_string( $color ) || \str_starts_with( $color, '#' ) ) {
            return false;
        }

        $color = \strtolower( $color );

        $color = \trim( $color, "hslva() \n\r\t\v\0" );

        if ( \str_contains( $color, 'from' ) ) {
            throw new CompileException(
                $this::class . ' cannot use provided HSX string, as it uses the relative `from` syntax.',
            );
        }

        $color = replaceEach(
            [
                ','   => ' ',
                '/'   => ' ',
                '%'   => '',
                'deg' => '',
            ],
            $color,
        );

        $color = stringExplode( ' ', $color );

        if ( \count( $color ) < 3 || \count( $color ) > 4 ) {
            throw new CompileException(
                $this::class . ' encountered a malformed HSX value: ' . print_r( $color, true ),
            );
        }

        return \array_map( '\intval', $color );
    }

    private function shades( array $colors ) : array {
        return \array_combine(
            [
                'darkest',
                'darker',
                'dark',
                null,
                'light',
                'lighter',
                'lightest',
            ],
            $colors,
        );
    }
}