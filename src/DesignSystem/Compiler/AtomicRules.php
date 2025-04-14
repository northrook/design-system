<?php

declare(strict_types=1);

namespace Northrook\DesignSystem\Compiler;

use Northrook\DesignSystem;
use Northrook\DesignSystem\Theme;

final class AtomicRules
{
    private const array POSITION = [
        'fixed'    => ['position' => 'fixed', 'z-index' => '125'],
        'absolute' => ['position' => 'absolute'],

        // None
        'fixed.top'    => ['top' => '0'],
        'fixed.right'  => ['right' => '0'],
        'fixed.bottom' => ['bottom' => '0'],
        'fixed.left'   => ['left' => '0'],

        'absolute.top'    => ['top' => '0'],
        'absolute.right'  => ['right' => '0'],
        'absolute.bottom' => ['bottom' => '0'],
        'absolute.left'   => ['left' => '0'],

        'fixed.inset:0'    => ['inset' => '0'],
        'absolute.inset:0' => ['inset' => '0'],

        // Full
        'fixed.top:full'    => ['left' => '0', 'right' => '0'],
        'fixed.bottom:full' => ['left' => '0', 'right' => '0'],
        'fixed.right:full'  => ['top' => '0', 'bottom' => '0'],
        'fixed.left:full'   => ['top' => '0', 'bottom' => '0'],

        'absolute.top:full'    => ['left' => '0', 'right' => '0'],
        'absolute.bottom:full' => ['left' => '0', 'right' => '0'],
        'absolute.right:full'  => ['top' => '0', 'bottom' => '0'],
        'absolute.left:full'   => ['top' => '0', 'bottom' => '0'],

        // Offset
        'fixed.top:offset'    => ['top' => '--offset-top'],
        'fixed.right:offset'  => ['right' => '--offset-right'],
        'fixed.bottom:offset' => ['bottom' => '--offset-bottom'],
        'fixed.left:offset'   => ['left' => '--offset-left'],

        'absolute.top:offset'    => ['top' => '--offset-top'],
        'absolute.right:offset'  => ['right' => '--offset-right'],
        'absolute.bottom:offset' => ['bottom' => '--offset-bottom'],
        'absolute.left:offset'   => ['left' => '--offset-left'],
    ];

    private const array DISPLAY = [
        'none'         => ['display' => 'none'],
        'block'        => ['display' => 'block'],
        'inline'       => ['display' => 'inline'],
        'inline-block' => ['display' => 'inline-block'],
        'inline-flex'  => ['display' => 'inline-flex'],
        'inline-grid'  => ['display' => 'inline-grid'],
        'flex'         => ['display' => 'flex'],
        'flow'         => [
            'display'     => 'flex',
            'line-height' => '--line-height, 1.5',
        ],
        'flex.reverse'          => ['flex-direction' => 'row-reverse'],
        'flex.center'           => ['align-items' => 'center', 'justify-content' => 'center'],
        'flex.align:top'        => ['align-items' => 'flex-start'],
        'flex.align:right'      => ['align-items' => 'flex-end'],
        'flex.align:bottom'     => ['align-items' => 'flex-start'],
        'flex.align:left'       => ['align-items' => 'flex-start'],
        'flex.align:center'     => ['align-items' => 'center'],
        'flex.col'              => ['flex-direction' => 'column'],
        'flex.col.reverse'      => ['flex-direction' => 'column-reverse'],
        'flex.col.align:top'    => ['justify-content' => 'flex-start'],
        'flex.col.align:right'  => ['justify-content' => 'flex-end'],
        'flex.col.align:bottom' => ['justify-content' => 'flex-start'],
        'flex.col.align:left'   => ['justify-content' => 'flex-start'],
        'flex.col.align:center' => ['justify-content' => 'flex-start'],
    ];

    private const array COLOR = [
        'baseline:100' => ['color' => '--baseline-900', 'background-color' => '--baseline-100'],
        'baseline:200' => ['color' => '--baseline-800', 'background-color' => '--baseline-200'],
        'baseline:300' => ['color' => '--baseline-700', 'background-color' => '--baseline-300'],
        'baseline:400' => ['color' => '--baseline-600', 'background-color' => '--baseline-400'],
        'baseline:500' => ['color' => '--baseline-500', 'background-color' => '--baseline-500'],
        'baseline:600' => ['color' => '--baseline-400', 'background-color' => '--baseline-600'],
        'baseline:700' => ['color' => '--baseline-300', 'background-color' => '--baseline-700'],
        'baseline:800' => ['color' => '--baseline-200', 'background-color' => '--baseline-800'],
        'baseline:900' => ['color' => '--baseline-100', 'background-color' => '--baseline-900'],
        'color:100'    => ['color' => '--baseline-100'],
        'color:200'    => ['color' => '--baseline-200'],
        'color:300'    => ['color' => '--baseline-300'],
        'color:400'    => ['color' => '--baseline-400'],
        'color:500'    => ['color' => '--baseline-500'],
        'color:600'    => ['color' => '--baseline-600'],
        'color:700'    => ['color' => '--baseline-700'],
        'color:800'    => ['color' => '--baseline-800'],
        'color:900'    => ['color' => '--baseline-900'],
        'bg:100'       => ['background-color' => '--baseline-100'],
        'bg:200'       => ['background-color' => '--baseline-200'],
        'bg:300'       => ['background-color' => '--baseline-300'],
        'bg:400'       => ['background-color' => '--baseline-400'],
        'bg:500'       => ['background-color' => '--baseline-500'],
        'bg:600'       => ['background-color' => '--baseline-600'],
        'bg:700'       => ['background-color' => '--baseline-700'],
        'bg:800'       => ['background-color' => '--baseline-800'],
        'bg:900'       => ['background-color' => '--baseline-900'],
    ];

    private const array TYPOGRAPHY = [
        'font:code'  => ['font-family' => '--font-code'],
        'text:body'  => ['font-size' => '--text-body'],
        'text:h1'    => ['font-size' => '--text-h1'],
        'text:h2'    => ['font-size' => '--text-h2'],
        'text:h3'    => ['font-size' => '--text-h3'],
        'text:h4'    => ['font-size' => '--text-h4'],
        'text:small' => [
            'font-size'     => '--text-small',
            '--line-height' => '1rem',
        ],
    ];

    private const array ICONS = [

    ];

    /** @var array<string, string> */
    protected array $rules = [];

    private function __construct( private readonly Theme $config ) {}

    protected function generateSizes() : self
    {
        foreach ( $this->config->getSizes() as $size => $unused ) {
            $value = DesignSystem::selectorVariable( $size );
            $size  = ':'.\trim( $size, '-' );

            $this->rules[DesignSystem::selectorClass( "space-h{$size} > * + *" )] = [
                '--mt'       => $value,
                'margin-top' => 'var(--mt)',
            ];

            $this->rules[DesignSystem::selectorClass( "space-v{$size} > * + *" )] = [
                '--mt'        => $value,
                'margin-left' => 'var(--mt)',
            ];

            $this->rules[DesignSystem::selectorClass( "m{$size}" )] = [
                '--m'    => $value,
                'margin' => 'var(--m)',
            ];
            $this->rules[DesignSystem::selectorClass( "mt{$size}" )] = [
                '--mt'       => $value,
                'margin-top' => 'var(--mt)',
            ];
            $this->rules[DesignSystem::selectorClass( "mr{$size}" )] = [
                '--mr'         => $value,
                'margin-right' => 'var(--mr)',
            ];
            $this->rules[DesignSystem::selectorClass( "mb{$size}" )] = [
                '--mb'          => $value,
                'margin-bottom' => 'var(--mb)',
            ];
            $this->rules[DesignSystem::selectorClass( "ml{$size}" )] = [
                '--ml'        => $value,
                'margin-left' => 'var(--ml)',
            ];
            $this->rules[DesignSystem::selectorClass( "mh{$size}" )] = [
                '--mh'         => $value,
                'margin-left'  => 'var(--mh)',
                'margin-right' => 'var(--mh)',
            ];
            $this->rules[DesignSystem::selectorClass( "mv{$size}" )] = [
                '--mv'          => $value,
                'margin-top'    => 'var(--mv)',
                'margin-bottom' => 'var(--mv)',
            ];
            $this->rules[DesignSystem::selectorClass( "p{$size}" )] = [
                '--p'     => $value,
                'padding' => 'var(--p)',
            ];
            $this->rules[DesignSystem::selectorClass( "pt{$size}" )] = [
                '--pt'        => $value,
                'padding-top' => 'var(--pt)',
            ];
            $this->rules[DesignSystem::selectorClass( "pr{$size}" )] = [
                '--pr'          => $value,
                'padding-right' => 'var(--pr)',
            ];
            $this->rules[DesignSystem::selectorClass( "pb{$size}" )] = [
                '--pb'           => $value,
                'padding-bottom' => 'var(--pb)',
            ];
            $this->rules[DesignSystem::selectorClass( "pl{$size}" )] = [
                '--pl'         => $value,
                'padding-left' => 'var(--pl)',
            ];
            $this->rules[DesignSystem::selectorClass( "ph{$size}" )] = [
                '--ph'          => $value,
                'padding-left'  => 'var(--ph)',
                'padding-right' => 'var(--ph)',
            ];
            $this->rules[DesignSystem::selectorClass( "pv{$size}" )] = [
                '--pv'           => $value,
                'padding-top'    => 'var(--pv)',
                'padding-bottom' => 'var(--pv)',
            ];
        }

        return $this;
    }

    protected function generateRules() : self
    {
        foreach ( [
            ...$this::POSITION,
            ...$this::DISPLAY,
            ...$this::COLOR,
            ...$this::TYPOGRAPHY,
        ] as $selector => $declarations ) {
            \assert( \is_string( $selector ) );
            $selector = DesignSystem::selectorClass( $selector );

            if ( isset( $this->rules[$selector] ) ) {
                $this->rules[$selector] = \array_merge( $this->rules[$selector], $declarations );
            }
            else {
                $this->rules[$selector] = $declarations;
            }
        }

        return $this;
    }

    public static function generate( Theme $theme ) : array
    {
        $atomic = new self( $theme );

        $atomic
            ->generateSizes()
            ->generateRules();

        return $atomic->getRules();
    }

    public function getRules() : array
    {
        // TODO : Generate dynamic sizes:
        // ?      w:16   -> style="--width:  16px"
        // ?      h:1rem -> style="--height: 1rem"
        // ?      h:5vh  -> style="--height: 5vh"
        return $this->rules;
    }
}
