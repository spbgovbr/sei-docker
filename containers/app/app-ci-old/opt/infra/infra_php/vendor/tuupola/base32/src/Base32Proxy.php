<?php

/*
 * This file is part of the Base32 package
 *
 * Copyright (c) 2017 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/tuupola/base32
 *
 */

namespace Tuupola;

use Tuupola\Base32;

class Base32Proxy
{
    public static $options = [
        "characters" => Base32::RFC4648,
    ];

    public static function encode($data)
    {
        return (new Base32(self::$options))->encode($data);
    }

    public static function decode($data, $integer = false)
    {
        return (new Base32(self::$options))->decode($data, $integer);
    }

    /**
     * Encode given integer to a base32 string
     */
    public static function encodeInteger($data)
    {
        return (new Base32(self::$options))->encodeInteger($data);
    }

    /**
     * Decode given base32 string back to an integer
     */
    public static function decodeInteger($data)
    {
        return (new Base32(self::$options))->decodeInteger($data);
    }
}
