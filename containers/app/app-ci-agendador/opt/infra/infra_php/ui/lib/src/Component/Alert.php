<?php

namespace TRF4\UI\Component;

use TRF4\UI\Element\Component;

abstract class Alert extends Component
{
    const TYPE_SECONDARY = 0;
    const TYPE_SUCCESS = 1;
    const TYPE_DANGER = 2;
    const TYPE_INFO = 3;
    const TYPE_WARNING = 4;
    /** @var string */
    public $innerHTML;
    /** @var int */
    public $type;

    public function __construct(string $innerHTML)
    {
        $this->innerHTML = $innerHTML;
    }

    public function danger(): self
    {
        $this->type = self::TYPE_DANGER;
        return $this;
    }

    public function info(): self
    {
        $this->type = self::TYPE_INFO;
        return $this;
    }

    public function secondary(): self
    {
        $this->type = self::TYPE_SECONDARY;
        return $this;
    }

    public function success(): self
    {
        $this->type = self::TYPE_SUCCESS;
        return $this;
    }

    public function warning(): self
    {
        $this->type = self::TYPE_WARNING;
        return $this;
    }

}