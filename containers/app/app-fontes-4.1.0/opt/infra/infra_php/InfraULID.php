<?php
/**
 * @package infra_php
 *
 */

class InfraULID
{
    const PAYLOAD_SIZE = 10;
    const PAYLOAD_ENCODED_SIZE = 16;
    const TIMESTAMP_SIZE = 6;
    const TIMESTAMP_ENCODED_SIZE = 10;
    private $payload;
    private $timestamp;

    public function __construct($timestamp = null, $payload = null)
    {
        $this->payload = $payload;
        $this->timestamp = $timestamp;
        if (empty($payload)) {
            $this->payload = random_bytes(self::PAYLOAD_SIZE);
        }
        if (empty($timestamp)) {
            $this->timestamp = time();
        }
    }

    public static function gerar()
    {
        return (new self)->string();
    }

    public static function validar($ulid)
    {
        return preg_match('/^[0123456789ABCDEFGHJKMNPQRSTVWXYZ]{26}$/i', $ulid);
    }

    public function string()
    {
        return $this->encodeTimeStamp() . $this->encodePayload();
    }

    private function encodePayload()
    {
        $base32 = new Tuupola\Base32([
            "characters" => Tuupola\Base32::CROCKFORD,
            "padding" => false
        ]);
        $encoded = $base32->encode($this->payload);
        return str_pad($encoded, self::PAYLOAD_ENCODED_SIZE, "0", STR_PAD_LEFT);
    }

    private function encodeTimeStamp()
    {
        $base32 = new Tuupola\Base32([
            "characters" => Tuupola\Base32::CROCKFORD,
            "padding" => false
        ]);
        $encoded = $base32->encode($this->timestamp);
        return str_pad($encoded, self::TIMESTAMP_ENCODED_SIZE, "0", STR_PAD_LEFT);
    }

    public function payload()
    {
        return $this->payload;
    }

    public function timestamp()
    {
        return $this->timestamp;
    }

    public function unixtime()
    {
        return $this->timestamp;
    }

    public function __toString()
    {
        return $this->string();
    }
}