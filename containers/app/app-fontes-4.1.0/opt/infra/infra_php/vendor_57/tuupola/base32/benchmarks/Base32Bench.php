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

use Tuupola\Base32;
use Tuupola\Base32\PhpEncoder;
use Tuupola\Base32\GmpEncoder;

/**
 * @BeforeMethods({"init"})
 * @Iterations(5)
 * @Warmup(2)
 * @OutputTimeUnit("seconds")
 * @OutputMode("throughput")
 */

class Base32Bench
{
    private $data;

    public function init()
    {
        $this->data = random_bytes(128);
        $this->gmp = new GmpEncoder;
        $this->gmp2 = new GmpEncoder(["characters" => Base32::CROCKFORD]);
        $this->php = new PhpEncoder;
    }

    /**
     * @Revs(10)
     */
    public function benchGmpEncoder()
    {
        $encoded = $this->gmp->encode($this->data);
        $decoded = $this->gmp->decode($encoded);
    }

    /**
     * @Revs(10)
     */
    public function benchGmpEncoderCustom()
    {
        $encoded = $this->gmp2->encode($this->data);
        $decoded = $this->gmp2->decode($encoded);
    }

    /**
     * @Revs(10)
     */
    public function benchPhpEncoder()
    {
        $encoded = $this->php->encode($this->data);
        $decoded = $this->php->decode($encoded);
    }
}