<?php


namespace TRF4\UI\Util;


use TRF4\UI\Element\AbstractSimpleElement;

class Option extends AbstractSimpleElement
{

    private $value;
    private $content;

    function render(): string
    {
        $attrs = [
            'value' => $this->value
        ];
        //return \EprocINT::option($this->content, $attrs);
    }

    public function content(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function value($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getTagName(): string
    {
        return 'option';
    }
}
