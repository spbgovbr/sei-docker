<?php


namespace TRF4\UI\Component;

abstract class MultiRange extends Range
{

    /**
     * InputMultiRange constructor.
     * @param string $labelInnerHtml
     * @param string $name
     */
    private $valEnd;

    public function __construct(?string $labelInnerHtml, string $name, float $v1, float $v2)
    {
        parent::__construct($labelInnerHtml, $name, $v1, $v2);
        $this->multi();
    }

    public function values(float $startValue, float $endValue): self
    {
        $this->value($startValue);
        $this->valEnd = $endValue;
        return $this;
    }

    public function getEndValue(): ?float
    {
        return $this->valEnd;
    }


    public static function get2ndRangeId(string $prefix)
    {
        return $prefix . "_2";
    }
}
