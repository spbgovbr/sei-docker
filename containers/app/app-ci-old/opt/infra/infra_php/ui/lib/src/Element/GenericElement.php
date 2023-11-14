<?php


namespace TRF4\UI\Element;

class GenericElement extends AbstractSimpleElement
{
    protected $tagName;

    public function getTagName(): string
    {
        return $this->tagName;
    }

    public function __construct(string $tagName, ?string $innerHTML = null)
    {
        $this->tagName = $tagName;

        if ($innerHTML) {
            $this->innerHTML($innerHTML);
        }

        parent::__construct();
    }

    public function attrs(array $attrs)
    {
        $this->attrs = $attrs;
        return $this;
    }
}
