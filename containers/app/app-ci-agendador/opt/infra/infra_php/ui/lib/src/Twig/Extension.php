<?php

namespace TRF4\UI\Twig;

use Twig\Extension\AbstractExtension;

class Extension extends AbstractExtension
{

    public function getTokenParsers()
    {
        return [
            new UIFormTokenParser(),
        ];
    }

    public function getName()
    {
        return UIFormTokenParser::getStartTag();
    }

}