<?php


abstract class AbstractIPComponente
{

    /**
     * @return string
     */
    public abstract function render();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }
}