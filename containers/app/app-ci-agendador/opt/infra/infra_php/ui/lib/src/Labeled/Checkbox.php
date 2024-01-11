<?php


namespace TRF4\UI\Labeled;


use Exception;
use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\UI;

abstract class Checkbox extends AbstractElementWithLabel
{
    /** @var GenericElement */
    public $_input;
    /**
     * @internal
     * @var bool
     */
    public $isInGroup = false;


    public function getDefaultElement(): AbstractElement {
        return $this->_input;
    }

    /**
     * @param string $labelInnerHtml
     * @param string $name
     * @param string|null $value
     */
    public function __construct(?string $labelInnerHtml = null, ?string $name = null, ?string $value = null) {
        parent::__construct($labelInnerHtml);

        $this->_input = UI::el('input')->type('checkbox');

        if ($name) {
            $this->name($name);
        }

        if ($value) {
            $this->value($value);
        }
    }

    /**
     * Formatos possíveis:
     *
     * [label, value]
     *
     * [label, name, value]
     *
     * @param array $a
     * @return Checkbox
     * @throws Exception
     */
    public static function fromArray(array $a): Checkbox {
        if (count($a) == 2) { //label, value
            return UI::checkbox($a[0], null, $a[1]);
        }

        if (count($a) === 3) { // label, name, value
            return UI::checkbox(...$a);
        }

        throw new Exception("Formato do array " . var_export($a, true) . ' não suportado por Checkbox::fromArray');

    }


    public function getAttrId(): ?string {
        $el = $this->getDefaultElement();

        $id = $el->get('id');

        if ($id) {
            return $id;
        }

        $name = $el->get('name');
        $value = $el->get('value');

        if ($name && $value) {
            return $name . '_' . $value;
        }

        if ($name) {
            return $name;
        }

        return null;
    }

    public function checked(?bool $checked = true): self {
    
        $el = $this->getDefaultElement();
        $el->checked($checked);        

        return $this;
    }

}
