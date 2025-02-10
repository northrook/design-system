<?php

namespace Northrook\DesignSystem;

use Core\Interface\DataInterface;

/**
 * Configuration for {@see \Northrook\DesignSystem}
 */
class Theme implements DataInterface
{
    protected const array LAYOUT = [
        '--size'               => '1em', // The size of something
        '--gap'                => '1rem', // Spacing between items
        '--gap-row'            => '1rem',
        '--gap-col'            => '1rem',
        '--gutter'             => '2ch',    // left|right padding for elements
        '--min-width'          => '20rem',  // 320px
        '--max-width'          => '75rem', // 1200px
        '--scroll-padding-top' => '--offset-top', // maybe +--gap?
        '--offset-top'         => '1rem',
        '--offset-left'        => '1rem',
        '--offset-right'       => '1rem',
        '--offset-bottom'      => '1rem',
    ];

    protected const array BOX = [
        '--radius-inline' => '.2em',
        '--radius-block'  => '.5rem',
    ];

    protected const array SIZES = [
        // agnostic
        '--auto' => 'auto',
        '--none' => '0',
        '--us'   => '.125rem', // 2px
        '--xs'   => '.25rem',  // 4px
        '--sm'   => '.5rem',
        '--ms'   => '.75rem',
        '--md'   => '1rem',    // 16px
        '--ml'   => '1.5rem',  // 24px
        '--lg'   => '2rem', // using em allows variable spacing based on font size
        '--xl'   => '3rem',
    ];

    protected const array TYPOGRAPHY = [
        '--font-body'    => 'Inter',
        '--font-heading' => 'Inter',
        '--font-code'    => 'monospace',
        '--line-height'  => '1.6em',
        '--line-spacing' => '.5em', // spacing between inline elements
        '--line-length'  => '64ch', // limits inline text elements, like p and h#

        '--weight-body'    => '400',
        '--weight-bold'    => '600',
        '--weight-heading' => '500',

        '--text-body'  => '1rem',
        '--text-h1'    => 'min(max(1.8em, 6vw), 3.05rem)',
        '--text-h2'    => 'min(max(1.6em, 6vw), 2rem)',
        '--text-h3'    => 'min(max(1.25em, 6vw), 1.5rem)',
        '--text-h4'    => 'min(max(1.1em, 6vw), 1.2rem)',
        '--text-small' => '.875rem',
    ];

    protected const array BASELINE = [
        '--baseline-100' => '#050506',
        '--baseline-200' => '#0c0c0e',
        '--baseline-300' => '#131416',
        '--baseline-400' => '#1d1e20',
        '--baseline-500' => '#97989b',
        '--baseline-600' => '#8a8b8f',
        '--baseline-700' => '#eaeaeb',
        '--baseline-800' => '#f2f2f3',
        '--baseline-900' => '#fafafa',
    ];

    protected const array PRIMARY = [
        '--primary-100' => '#00030a',
        '--primary-200' => '#000819',
        '--primary-300' => '#000c28',
        '--primary-400' => '#01133c',
        '--primary-500' => '#3a73f8',
        '--primary-600' => '#2663f3',
        '--primary-700' => '#d9e4fc',
        '--primary-800' => '#e8eefd',
        '--primary-900' => '#f6f8fe',
    ];

    protected const array SUCCESS = [
        '--success-darkest'  => '#172620',
        '--success-darker'   => '#20563f',
        '--success-dark'     => '#227c57',
        '--success'          => '#4bce97',
        '--success-light'    => '#a7e7cc',
        '--success-lighter'  => '#c7f0df',
        '--success-lightest' => '#f3fcf8',
    ];

    protected const array INFO = [
        '--info-darkest'  => '#050505',
        '--info-darker'   => '#1b2432',
        '--info-dark'     => '#203a60',
        '--info'          => '#579dff',
        '--info-light'    => '#adcfff',
        '--info-lighter'  => '#e0edff',
        '--info-lightest' => '#f5f9ff',
    ];

    protected const array NOTICE = [
        '--notice-darkest'  => '#050505',
        '--notice-darker'   => '#1a1726',
        '--notice-dark'     => '#292056',
        '--notice'          => '#9f8fef',
        '--notice-light'    => '#c9c1f6',
        '--notice-lighter'  => '#f4f2fd',
        '--notice-lightest' => '#f7f6fe',
    ];

    protected const array WARNING = [
        '--warning-darkest'  => '#14130f',
        '--warning-darker'   => '#433a1e',
        '--warning-dark'     => '#6f5d1f',
        '--warning'          => '#f5cd47',
        '--warning-light'    => '#fae6a3',
        '--warning-lighter'  => '#fcf2cf',
        '--warning-lightest' => '#fefcf5',
    ];

    protected const array DANGER = [
        '--danger-darkest'  => '#050505',
        '--danger-darker'   => '#321c1b',
        '--danger-dark'     => '#602420',
        '--danger'          => '#f87268',
        '--danger-light'    => '#fbb6b1',
        '--danger-lighter'  => '#fee4e2',
        '--danger-lightest' => '#fff6f5',
    ];

    protected const array COLOR = [
        '--opacity'      => '1',
        '--color'        => '--baseline-300',
        '--background'   => '--baseline-900',
        '--shade'        => '--baseline-600',
        '--outline'      => '--baseline-500',
        '--intent-dark'  => '--baseline-400',
        '--intent'       => '--baseline-500',
        '--intent-light' => '--baseline-700',
        '--accent'       => '--primary-500',
        '--accent-light' => '--primary-600',
    ];

    public function variables() : array
    {
        return [
            ...$this::LAYOUT,
            ...$this::TYPOGRAPHY,
            ...$this::SIZES,
            ...$this::BOX,
            ...$this::BASELINE,
            ...$this::PRIMARY,
            ...$this::SUCCESS,
            ...$this::INFO,
            ...$this::NOTICE,
            ...$this::WARNING,
            ...$this::DANGER,
            ...$this::COLOR,
        ];
    }

    public function getLayout() : array
    {
        return $this::LAYOUT;
    }

    public function getTypography() : array
    {
        return $this::TYPOGRAPHY;
    }

    public function getSizes() : array
    {
        return $this::SIZES;
    }

    public function getBox() : array
    {
        return $this::BOX;
    }
}
