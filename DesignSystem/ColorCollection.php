<?php

namespace Northrook\DesignSystem;

use Northrook\DesignSystem\Color\Theme;
use Northrook\DesignSystem\ColorPalette\Relative;
use Northrook\DesignSystem\ColorPalette\Weighted;
use Northrook\DesignSystem\Compiler\Scale;
use function Northrook\toString;

class ColorCollection
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

    /** @var ColorPalette[] */
    private array $palettes = [];

    public function __construct( private readonly int $lightnessPadding = 2 ) {}

    public function get( string $palette ) : ?ColorPalette {
        return $this->palettes[ $palette ] ?? null;
    }

    final public function addPalette(
        string        $name,
        mixed         $from,
        array | float $method = Weighted::BASELINE,
        true | string $generate = Theme::LIGHT,
    ) : self {

        $palette = new ColorPalette( $name, $from, $generate, $this->lightnessPadding, );

        $this->palettes[ $name ] = $palette;

        if ( \is_array( $method ) ) {
            $palette->generateWeighted( $method );
        }
        else {
            $palette->generateRelative( $method );
        }

        return $this;
    }

    final public function systemPalettes(
        string $theme = 'atlassian',
        float  $relative = Relative::PERFECT_FOURTH,
    ) : self {

        foreach ( $this::SYSTEM[ $theme ] ?? [] as $name => $color ) {
            $this->addPalette( $name, $color, $relative );
        }

        return $this;
    }

    final public function generateStyles() : string {
        $root  = [];
        $class = [];

        foreach ( $this->palettes as $name => $palette ) {
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