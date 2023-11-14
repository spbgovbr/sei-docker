<?php


namespace TRF4\UI\Element;

use Exception;
use TRF4\UI\Renderer;


abstract class AbstractElement extends FluidElementInterface
{
    /** @var Renderer\AbstractRenderer */
    protected $renderer;

    /** @var array */
    protected $attrs = [];

    /** @var string */
    protected $rawAttrValueString = '';

    /** @var string[] */
    protected static $booleanAttributes = [
        'required',
        'readonly',
        'disabled',
        'multiple',
        'checked',
        'selected',
    ];

    protected function camel2dashed($val)
    {
        return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $val));
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

        if (!method_exists($this, $method)) {
            $el = $this;
            $el->setValue($method, $arguments);
        }

        return $this;
    }


    /**
     * Retorna null caso não possua o valor, string ou boolean caso contrário
     * @param string $attr
     * @return bool|string|null
     */
    public function get(string $attr)
    {
        return $this->attrs[$attr] ?? null;
    }

    /**
     * Método getter/setter: define ou complementa o valor de um atributo. No caso de complementar, adiciona um espaço antes do próximo valor.
     * @param string $attr
     * @param $value
     */
    public function attr(string $attr, $value)
    {
        if (!isset($this->attrs[$attr])) {
            $this->attrs[$attr] = $value;
        } else {
            $this->attrs[$attr] .= ' ' . $value;
        }
    }

    public function overrideName($value)
    {
        $this->attrs['name'] = $value;
    }

    /**
     * Método adicionado por motivo de retrocompatibilidade.
     * Prefira os métodos setters mágicos ou attr.
     * Como último recurso, se precisar adicionar um atributo no formato `attr="value"`, use este método.
     * Note que não é feita validação alguma de pré-existência dos atributos setados por meio deste método.
     *
     * @param string $rawAttr
     * @return $this
     */
    public function addAttrAsString(string $rawAttr): self
    {
        $this->rawAttrValueString = $rawAttr;
        return $this;
    }

    public function getAttrId(): ?string
    {
        $el = $this;

        if (isset($el->attrs['id'])) {
            return $el->get('id');
        }

        if (isset($el->attrs['name'])) {
            return $el->get('name');
        }

        return null;
    }

    protected function hasId(): bool
    {
        return $this->get('id') ? true : false;
    }

    protected function setValue($method, $arguments)
    {
        $this->checkForExceptions($arguments);

        if (!$this->callHasNullEffect($method, $arguments)) {
            $el = $this;
            $value = $el->getValueForAttr($method, $arguments);
            $attr = $this->camel2dashed($method);
            $el->attr($attr, $value);
        }
    }

    private function checkForExceptions($arguments)
    {
        if (isset($arguments[0]) && is_object($arguments[0])) {
            throw new Exception('Métodos do builder não podem receber objetos por parâmetro.');
        }
    }

    private function callHasNullEffect($method, $args): bool
    {
        $hasNullEffect = false;

        if ($method === 'value' and ($args[0] === null || $args[0] === '')) {
            $hasNullEffect = true;
        }

        return $hasNullEffect;
    }

    private function getValueForAttr($method, $arguments)
    {
        $isBooleanAttr = $this->isBooleanAttr($method);

        if (!$isBooleanAttr) {
            $value = $this->getValueForRegularAttr($method, $arguments);
        } else {
            $value = $this->getValueForBooleanAttr($method, $arguments);
        }

        return $value;
    }

    private function isBooleanAttr($name): bool
    {
        return in_array($name, self::$booleanAttributes);
    }

    private function getValueForBooleanAttr($method, $arguments): bool
    {
        if (!isset($arguments[0])) {
            $value = true;
        } else {
            $value = $arguments[0];
        }

        if (!is_bool($value)) {
            throw new Exception ("O método $method só pode receber os valores true ou false. Valor recebido: $value.");
        }

        return $value;
    }

    private function getValueForRegularAttr($method, $arguments)
    {
        try {
            $value = $arguments[0];
            if ($value === null) {
                throw new Exception("Para o método $method não foi passado o parâmetro de valor");
            }
        } catch (Exception $e) {
            throw new Exception("Para o método $method não foi passado o parâmetro de valor", 0, $e);
        }
        return $value;
    }


}
