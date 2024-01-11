<?php


namespace TRF4\UI\Util;


/**
 * @property array dataMap
 * @property string innerHTMLAttr
 * @property string valueAttr
 * @property array params
 * @property string url
 */
class AjaxCallback
{
    /** @var string */
    public $method;

    public function __construct(string $method, string $url, array $params, string $valueAttr, string $innerHTMLAttr, array $dataMap = [])
    {
        $this->method = $method;
        $this->url = $url;
        $this->params = $params;
        $this->valueAttr = $valueAttr;
        $this->innerHTMLAttr = $innerHTMLAttr;
        $this->dataMap = $dataMap;
    }

    /**
     * @param $strChave
     * @param $strValor
     */
    public function addParam($strChave, $strValor) {
        $this->params[$strChave] = $strValor;
        return $this;
    }
}
