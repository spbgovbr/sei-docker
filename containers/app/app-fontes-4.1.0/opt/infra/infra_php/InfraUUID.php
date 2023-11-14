<?php

class InfraUUID
{
    /**
     * @link https://gist.github.com/dahnielson/508447
     * @return string
     */
    public static function gerar()
    {
        if (version_compare(PHP_VERSION, '7.0.0') >= 0) {
            $uuid = random_bytes(16);
            $uuid[6] = chr(ord($uuid[6]) & 0x0f | 0x40);
            $uuid[8] = chr(ord($uuid[8]) & 0x3f | 0x80);

            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($uuid), 4));
        } else {
            return sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff)
            );
        }
    }

    public static function validar($uuid)
    {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid);
    }
}