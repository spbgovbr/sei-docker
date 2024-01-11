<?php

declare(strict_types = 1);

/*

Copyright (c) 2017-2020 Mika Tuupola

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/

/**
 * @see       https://github.com/tuupola/base32
 * @license   https://www.opensource.org/licenses/mit-license.php
 */

namespace Tuupola\Base32;

use InvalidArgumentException;
use Tuupola\Base32;

abstract class BaseEncoder
{
    /**
     * @var array<string, bool|string>
     */
    protected $options = [
        "characters" => Base32::RFC4648,
        "padding" => "=",
        "crockford" => false,
    ];

    /**
     * @param array<string, bool|string> $options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, (array) $options);

        $uniques = count_chars($this->characters(), 3);
        /** @phpstan-ignore-next-line */
        if (32 !== strlen($uniques) || 32 !== strlen($this->characters())) {
            throw new InvalidArgumentException("Character set must 32 unique characters");
        }
    }

    protected function validateInput(string $data): void
    {
        $characters = $this->characters() . $this->padding();
        if (strlen($data) !== strspn($data, $characters)) {
            $valid = str_split($this->characters());
            $invalid = str_replace($valid, "", $data);
            $invalid = count_chars($invalid, 3);
            throw new InvalidArgumentException(
                /** @phpstan-ignore-next-line */
                "Data contains invalid characters \"{$invalid}\""
            );
        }
    }

    /**
     * Return the value of the characters setting
     */
    protected function characters(): string
    {
        return (string) $this->options["characters"];
    }

    /**
     * Return the value of the padding setting
     */
    protected function padding(): string
    {
        return (string) $this->options["padding"];
    }

    /**
     * Return the value of the crockford setting
     */
    protected function isCrockford(): bool
    {
        return true === $this->options["crockford"];
    }

    /**
     * Encode given data to a base32 string
     */
    abstract public function encode(string $data): string;

    /**
     * Decode given a base32 string back to data
     */
    abstract public function decode(string $data): string;

    /**
     * Encode given integer to a base32 string
     */
    abstract public function encodeInteger(int $data): string;

    /**
     * Decode given base32 string back to an integer
     */
    abstract public function decodeInteger(string $data): int;
}
