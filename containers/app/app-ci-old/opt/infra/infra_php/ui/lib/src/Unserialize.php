<?php


namespace TRF4\UI;

use TRF4\UI\Bootstrap4\DateInterval;
use TRF4\UI\Component\MultiRange;

class Unserialize
{
    protected $data;

    public function __construct(&$data)
    {
        $this->data = &$data;
    }

    public static function get(): self
    {
        return new self($_GET);
    }

    public static function post(): self
    {
        return new self($_POST);
    }

    public function multiRange(string $idPrefix): ?array
    {
        $key_v1 = $idPrefix;
        $key_v2 = MultiRange::get2ndRangeId($idPrefix);

        $val1 = $_REQUEST[$key_v1] ?? null;
        $val2 = $_REQUEST[$key_v2] ?? null;

        $values = [$val1, $val2];
        if ($val1 === null || $val2 === null) {
            throw new \Exception("Tentando recuperar valores inexistentes ($key_v1, $key_v2)");
        }

        sort($values);
        return $values;
    }

    public function dateInterval(string $namePrefix): ?array
    {
        $startKey = DateInterval::buildStartDate($namePrefix) ?? null;
        $endKey = DateInterval::buildEndDate($namePrefix) ?? null;

        $start = $_REQUEST[$startKey] ?? null;
        $end = $_REQUEST[$endKey] ?? null;

        return [$start, $end];
    }

}
