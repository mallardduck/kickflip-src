<?php

namespace Kickflip\Enums;

use Spatie\Enum\Enum;

/**
 * @method static self quiet()
 * @method static self normal()
 * @method static self verbose()
 * @method static self veryVerbose()
 * @method static self debug()
 */
class VerbosityFlag extends Enum
{
    protected static function values(): array
    {
        return [
            'quiet' => 'quiet',
            'normal' => 'normal',
            'verbose' => 'v',
            'veryVerbose' => 'vv',
            'debug' => 'vvv',
        ];
    }
}
