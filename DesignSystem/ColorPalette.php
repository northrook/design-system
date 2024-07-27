<?php

namespace Northrook\DesignSystem;

use Northrook\Core\Exception\CompileException;
use Northrook\DesignSystem\Color\Theme;
use Northrook\DesignSystem\ColorPalette\Color;
use Northrook\DesignSystem\Compiler\Scale;
use Northrook\Logger\Log;
use OzdemirBurak\Iris\BaseColor;
use OzdemirBurak\Iris\Color\Hex;
use OzdemirBurak\Iris\Color\Hsl;
use OzdemirBurak\Iris\Color\Hsv;
use OzdemirBurak\Iris\Color\Rgb;
use OzdemirBurak\Iris\Color\Rgba;
use function Northrook\numberBetween;
use function Northrook\replaceEach;
use function Northrook\stringExplode;
use function Northrook\toString;
use const Northrook\EMPTY_STRING;

class ColorPalette
{
    private const SYSTEM = [
        'atlassian' => [
            'success' => '4bce97', // green
            'info'    => '579dff', // blue
            'notice'  => '9f8fef', // lavender
            'warning' => 'f5cd47', // gold
            'danger'  => 'f87168', // red
        ],
    ];
    private readonly int   $lightnessPadding;
    private readonly float $systemScale;

    /** @var Color[] */
    private array          $colors = [];
    public readonly string $theme;

    public function __construct(
        Theme $theme = Theme::LIGHT,
        int   $lightnessPadding = 2,
        float $defaultCurve = Scale::PERFECT_FOURTH,
    ) {
        $this->theme            = $theme->value;
        $this->lightnessPadding = $lightnessPadding;
        $this->systemScale      = $defaultCurve;
    }

    public function get( string $color ) : ?Color {
        return $this->colors[ $color ] ?? null;
    }

    final public function addColor( string $name, mixed $color, array $curve ) : self {

        $this->colors[ $name ] = ( new Color( $color, $name, $this->lightnessPadding ) )
            ->generateColor( $curve );

        return $this;
    }

    final public function addColorShade(
        string $name,
        mixed  $color,
        ?float $scale = null,
    ) : self {

        $this->colors[ $name ] = ( new Color( $color, $name ) )
            ->generateShade( $scale ?? $this->systemScale );

        return $this;
    }

    final public function systemShades( string $theme = 'atlassian' ) : self {

        foreach ( $this::SYSTEM[ $theme ] ?? [] as $name => $color ) {
            $this->addColorShade( $name, $color, $this->systemScale );
        }

        return $this;
    }

    final public function generateStyles() : string {
        $root  = [];
        $class = [];

        foreach ( $this->colors as $name => $palette ) {
            foreach ( $palette->colors as $key => $value ) {

                $varValue = $value->toHex();
                $varLast  = array_key_last( $palette->colors );
                $varFirst = array_key_first( $palette->colors );

                // dump($isWeighted);

                $root[]     = "--{$key}: {$varValue};";
                $color      = $value->isLight() ? "color: var(--$varFirst);" : "color: var(--$varLast);";
                $background = "background-color: var(--{$key});";
                $class[]    = ".bg-{$key} { $background $color }";
            }
        }

        return ':root{' . \implode( '', $root ) . '}' . \implode( '', $class );
    }


    final public function getAccentStyles( string $name, array $colors ) : string {
        $root  = [];
        $class = [];

        $shades = \array_combine(
            [
                'darkest',
                'darker',
                'dark',
                null,
                'light',
                'lighter',
                'lightest',
            ], $colors,
        );

        foreach ( $shades as $key => $value ) {
            $var        = toString( [ $name, $key ], '-' );
            $root[]     = "--{$var}:{$value};";
            $color      = $value->isLight() ? "color: var(--{$name}-darkest);" : "color: var(--{$name}-lightest);";
            $background = "background-color: var(--{$var});";
            $class[]    = ".background-{$var} { $background $color }";
        }

        return ':root{' . \implode( '', $root ) . '}' . \implode( '', $class );
    }

    final public function getBaselineStyles( array $colors ) : string {
        $root  = [];
        $class = [];
        foreach ( $colors as $key => $value ) {
            $root[]     = "--baseline-{$key}:{$value};";
            $color      = $value->isLight() ? "color: var(--baseline-200);" : null;
            $background = "background-color: {$value};";
            $class[]    = ".background-{$key} { $background $color }";
        }

        return ':root{' . \implode( '', $root ) . '}' . \implode( '', $class );
    }

}