<?php


namespace TRF4\UI\Element;


use Exception;

abstract class Component extends FluidElementInterface
{
    /**
     * Retorna o elemento que recebe, por padrão, todas as ações
     * @return mixed
     */
    abstract public function getDefaultElement(): AbstractElement;

    protected function setValue($method, $arguments)
    {
        $this->getDefaultElement()->$method($arguments);
    }

    /**
     * Define o valor de um atributo arbitrário. Se o nome do método chamado for em camelCase, será convertido para dashed-string (ex.: ->dataId('x') => data-id="x")
     * @param $method
     * @param $arguments
     * @return $this
     * @throws Exception
     */
    public function __call($method, $arguments)
    {
        if (property_exists($this, $method)) {
            $prop = $method;
            $method = $arguments[0];
            $this->$prop->$method($arguments[1]);
            $ret = $this;
        } elseif (!method_exists($this, $method)) {
            $el = $this->getDefaultElement();

            $ret = $el->$method(...$arguments);

            if ($ret instanceof AbstractElement) {
                $ret = $this;
            }
        }

        return $ret;
    }

    protected function hasId(): bool
    {
        return $this->getDefaultElement()->get('id') ? true : false;
    }

}
