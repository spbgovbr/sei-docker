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

namespace Tuupola\Base32;

use InvalidArgumentException;
use Tuupola\Base32;

class PhpEncoder
{
    private $options = [
        "characters" => Base32::RFC4648,
        "padding" => "=",
    ];

    public function __construct($options = [])
    {
        $this->options = array_merge($this->options, (array) $options);

        $uniques = count_chars($this->options["characters"], 3);
        if (32 !== strlen($uniques) || 32 !== strlen($this->options["characters"])) {
            throw new InvalidArgumentException("Character set must 32 unique characters");
        }
    }

    public function encode($data)
    {
        if (empty($data)) {
            return "";
        }

        /* Create binary string zeropadded to eight bits. */
        if (is_integer($data)) {
            $binary = decbin($data);
            if ($modulus = strlen($binary) % 5) {
                $padding = 5 - $modulus;
                $binary = str_pad($binary, strlen($binary) + $padding, "0", STR_PAD_LEFT);
            }
        } else {
            $data = str_split($data);
            $binary = implode("", array_map(function ($character) {
                return sprintf("%08b", ord($character));
            }, $data));
        }

        /* Split to five bit chunks and make sure last chunk has five bits. */
        $binary = str_split($binary, 5);
        $last = array_pop($binary);
        $binary[] = str_pad($last, 5, "0", STR_PAD_RIGHT);

        /* Convert each five bits to Base32 character. */
        $encoded = implode("", array_map(function ($fivebits) {
            $index = bindec($fivebits);
            return $this->options["characters"][$index];
        }, $binary));

        /* Pad to eight characters when requested. */
        if (!empty($this->options["padding"])) {
            if ($modulus = strlen($encoded) % 8) {
                $padding = 8 - $modulus;
                $encoded .= str_repeat($this->options["padding"], $padding);
            }
        }

        return $encoded;
    }

    public function decode($data, $integer = false)
    {
        if (empty($data)) {
            return "";
        }

        /* If the data contains characters that aren't in the character set. */
        $characters = $this->options["characters"] . (string) $this->options["padding"];
        if (strlen($data) !== strspn($data, $characters)) {
            $valid = str_split($this->options["characters"]);
            $invalid = str_replace($valid, "", $data);
            $invalid = count_chars($invalid, 3);
            throw new InvalidArgumentException(
                "Data contains invalid characters \"{$invalid}\""
            );
        }

        $data = str_split($data);
        $data = array_map(function ($character) {
            if ($character !== $this->options["padding"]) {
                $index = strpos($this->options["characters"], $character);
                return sprintf("%05b", $index);
            }
        }, $data);
        $binary = implode("", $data);

        if ($integer) {
            return bindec($binary);
        }

        /* Split to eight bit chunks. */
        $data = str_split($binary, 8);

        /* Make sure binary is divisible by eight by ignoring the incomplete byte. */
        $last = array_pop($data);
        if (8 === strlen($last)) {
            $data[] = $last;
        }

        return implode("", array_map(function ($byte) {
            return chr(bindec($byte));
        }, $data));
    }

    /**
     * Encode given integer to a base85 string
     */
    public function encodeInteger($data)
    {
        return $this->encode($data, true);
    }

    /**
     * Decode given base85 string back to an integer
     */
    public function decodeInteger($data)
    {
        return $this->decode($data, true);
    }
}
