<?php

namespace Northrook\DesignSystem\Compiler;

class TypographyRules {


    private const array RULES = [
            '.font:code'  => ['font-family' => '--font-code'],
            '.text:body'  => ['font-size' => '--text-body'],
            '.text:h1'    => ['font-size' => '--text-h1'],
            '.text:h2'    => ['font-size' => '--text-h2'],
            '.text:h3'    => ['font-size' => '--text-h3'],
            '.text:h4'    => ['font-size' => '--text-h4'],
            '.text:small' => [
                    'font-size'     => '--text-small',
                    '--line-height' => '1rem',
            ],
    ];
}
