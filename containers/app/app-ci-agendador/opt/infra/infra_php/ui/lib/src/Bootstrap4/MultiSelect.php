<?php

namespace TRF4\UI\Bootstrap4;

class MultiSelect extends \TRF4\UI\Labeled\MultiSelect
{
    use SelectActions;

    public function __construct(?string $labelInnerHtml = null, ?string $name = null, array $options = [])
    {

        parent::__construct($labelInnerHtml, $name, $options);

        $this->attr('multiple', true);
    }
}