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

class Base32
{
    const CROCKFORD = "0123456789ABCDEFGHJKMNPQRSTVWXYZ";
    const RFC4648 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";
    const ZBASE32 = "ybndrfg8ejkmcpqxot1uwisza345h769";
    const GMP = "0123456789ABCDEFGHIJKLMNOPQRSTUV";
    const HEX = "0123456789ABCDEFGHIJKLMNOPQRSTUV";

    private $encoder;
    private $options = [];

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, (array) $options);
        if (function_exists("gmp_init")) {
            $this->encoder = new Base32\GmpEncoder($this->options);
        }
        $this->encoder = new Base32\PhpEncoder($this->options);
    }

    public function encode($data)
    {
        return $this->encoder->encode($data);
    }

    public function decode($data, $integer = false)
    {
        return $this->encoder->decode($data, $integer);
    }

    /**
     * Encode given integer to a base32 string
     */
    public function encodeInteger($data)
    {
        return $this->encoder->encodeInteger($data);
    }

    /**
     * Decode given base32 string back to an integer
     */
    public function decodeInteger($data)
    {
        return $this->encoder->decodeInteger($data);
    }
}
