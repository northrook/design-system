<?php

/**
 * @noinspection CssUnusedSymbol
 * @noinspection CssUnresolvedCustomProperty
 */

declare(strict_types=1);

namespace Northrook;

use Northrook\DesignSystem\{Compiler\AtomicRules, Theme};
use Stringable;
use Support\Escape;
use Support\Minify\StylesheetMinifier;

// @composer "ozdemirburak/iris": "^3.1",

final class DesignSystem
{
    public const string RESET = <<<'CSS'
        *,
        *::before,
        *::after {
          box-sizing : border-box;
        }
                            
        /*
        	-ms-touch-action	: manipulation;
        	touch-action		: manipulation;
        	-webkit-tap-highlight-color	: transparent;
         */
                            
        /* remove default spacing */
        /* force styling of type through styling, rather than elements */
                            
        /* reset default text opacity of input placeholder */
                            
        ::placeholder {
          color : unset;
        }
                            
        /* Remove details summary webkit styles */
        ::-webkit-details-marker {
          display : none;
        }
                            
        * {
          margin                      : 0;
          padding                     : 0;
          min-width                   : 0;
          font                        : inherit;
          border                      : 0;
          letter-spacing              : inherit;
          color                       : inherit;
          background-color            : transparent;
          -webkit-tap-highlight-color : transparent;
        }
                            
        /* Do we want to set a line height? */
                            
        html {
          -webkit-font-smoothing   : antialiased;
          -moz-text-size-adjust    : none;
          -webkit-text-size-adjust : none;
          /* -webkit-text-size-adjust: 100%; */
          font-size                : var(--text-body);
          text-size-adjust         : none;
          text-rendering           : optimizeLegibility;
          /* color-scheme: dark light;  */ /* We probably do not want this */
        }
                            
        /* min body height */
                            
        body {
          min-height : 100svh;
        }
                            
        /* Reapply the pointer cursor for anchor tags */
        a,
        button,
        input {
          cursor           : revert;
          -ms-touch-action : manipulation;
          touch-action     : manipulation;
          text-decoration  : none;
        }
                            
        p {
          overflow-wrap : break-word;
          text-wrap     : pretty;
        }
                            
        i,
        address {
          font-style : normal;
        }
                            
        /* TODO : Validate usefulness */
        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
          overflow-wrap : break-word;
        }
                            
        /* responsive images/videos */
        img,
        picture,
        svg,
        video {
          display   : block;
          max-width : 100%;
        }
                            
        img {
          max-inline-size : 100%;
          max-block-size  : 100%;
        }
                            
        main,
        menu,
        dialog,
          /*modal,*/
          /*dropdown,*/
        aside {
          isolation : isolate;
        }
                            
        abbr [title] {
          border-bottom   : none;
          text-decoration : underline dotted;
        }
        CSS;

    public const string BASELINE = <<<'CSS'
        html {
          --background: var(--baseline-800);
          --color: var(--baseline-200);
          font-family : var(--font-body), system-ui;
          font-size : var(--text-body);
        }
        p, .heading, .h1, .h2, .h3, .h4 {
          overflow-wrap : break-word;
          text-wrap     : pretty;
        }
        h1, .h1 {
          font-size : var(--text-h1);
        }
        h2, .h2 {
          font-size : var(--text-h2);
        }
        h3, .h3 {
          font-size : var(--text-h3);
        }
        h4, .h4 {
          font-size : var(--text-h4);
        }
        small {
          font-size : var(--text-small);
        }
        .content > * + * {
          margin-top : var(--line-height);
        }
        .nowrap {
          white-space : nowrap;
        }
        code, pre {
          tab-size : 4ch;
        }
        .gap {
          gap: var(--gap-row) var(--gap-col);
        }
        .gap-row {
          row-gap: var(--gap-row);
        }
        .gap-col {
          column-gap: var(--gap-col);
        }
        .sr-only {
            position     : absolute;
            width        : 1px;
            height       : 1px;
            padding      : 0;
            margin       : -1px;
            overflow     : hidden;
            clip         : rect(0, 0, 0, 0);
            white-space  : nowrap;
            border-width : 0;
        }
        em {
          font-style: italic;
        }
        CSS;

    public const string ICONS = <<<CSS
        svg.icon {
          height   : var(--size, 1em);
          width    : var(--size, 1em);
          overflow : visible;
        }
        svg.icon.direction\:up {
          rotate : 0deg;
        }
        svg.icon.direction\:right {
          rotate : 90deg;
        }
        svg.icon.direction\:down {
          rotate : 180deg;
        }
        svg.icon.direction\:left {
          rotate : 270deg;
        }
        CSS;

    protected readonly Theme $config;

    private StylesheetMinifier $stylesheet;

    protected array $root = [];

    protected array $rules = [];

    public function __construct(
        ?Theme $config = null,
    ) {
        $this->config     = $config ?? new Theme();
        $this->stylesheet = new StylesheetMinifier();
    }

    /**
     * Generate core styles using the default {@see Theme}.
     *
     * @param string[]|Stringable[] $source
     *
     * @return string
     */
    public function generateStyles( string|Stringable ...$source ) : string
    {
        $this->rules = AtomicRules::generate( $this->config );

        foreach ( $this->rules as $selector => $declarations ) {
            $this->rules[$selector] = $this->rule( $selector, $declarations );
        }

        $this->rules['reset']    = $this::RESET;
        $this->rules['baseline'] = $this::BASELINE;
        $this->rules['icons']    = $this::ICONS;

        \array_unshift( $this->rules, $this->generateTheme() );

        $generated = \implode( "\n", $this->rules );

        $this->stylesheet->setSource( $generated, ...$source );

        return (string) $this->stylesheet->minify();
    }

    /**
     * Generate a theme.
     *
     * @param null|Theme $theme
     *
     * @return string
     */
    public function generateTheme( ?Theme $theme = null ) : string
    {
        $theme ??= $this->config;

        foreach ( $theme->variables() as $variable => $value ) {
            // $value = $this->selectorVariable( $value );
            $this->root[$variable] ??= "{$variable}: {$value};";
        }

        return ":root{\n\t".\implode( "\n\t", \array_filter( $this->root ) )."\n}";
    }

    final public static function selectorVariable( string $variable ) : string
    {
        $variable = \trim( $variable, " \n\r\t\v\0:-" );
        $variable = match ( $variable ) {
            'auto'  => 'auto',
            'none'  => '0',
            default => $variable,
        };
        // dump( $variable );
        return '--'.Escape::string( $variable, ':' );
    }

    final public static function selectorClass( string $selector ) : string
    {
        return '.'.\trim( Escape::string( $selector, ':' ), '.' );
    }

    private function rule( string $selector, array $declarations ) : string
    {
        return \implode( PHP_EOL, ["{$selector} {", ...$this->declarations( $declarations ), '}'] );
    }

    private function declarations( array $declarations ) : array
    {
        foreach ( $declarations as $declaration => $value ) {
            if ( \str_starts_with( $value, '--' ) ) {
                $value = "var({$value})";
            }

            $declarations[$declaration] = "    {$declaration} : {$value};";
        }
        return $declarations;
    }
}
