<?php


namespace TRF4\UI\Element;

use Exception;
use TRF4\UI\UI;
use TRF4\UI\UIHtmlBuilder;

abstract class AbstractSimpleElement extends AbstractElement
{

    /** @var ?string */
    public $innerHTML = null;

    public function __construct()
    {
    }

    public abstract function getTagName(): string;

    public function render(): string
    {
        $this->validate();

        $tag = $this->getTagName();
        $innerHTML = $this->innerHTML;
        $this->setIdIfNotSet();
        $attrs = $this->getAttrs();
        ksort($attrs); // o sort é feito

        $unpairedElements = ['img', 'br', 'hr', 'area', 'param', 'wbr', 'base', 'link', 'meta', 'input', 'option', 'a', 'source'];
        $trueValueMapping = ['readonly' => 'readonly', 'disabled' => 'disabled', 'multiple' => 'true', 'checked' => 'checked', 'selected' => 'selected'];

        $tag = trim($tag);
        $html = '<' . $tag;
        foreach ($trueValueMapping as $key => $value) {
            $attrs[$key] = !empty($attrs[$key]) ? $value : null;
        }

        foreach ((array)$attrs as $key => $value) {
            if ($value === true) {
                $html .= ' ' . $key;
                continue;
            }

            if ($value === null || $value === false) {
                continue;
            }

            $html .= ' ' . $key . '="' . $value . '"';
        }

        if (!empty($this->rawAttrValueString)) {
            $html .= " $this->rawAttrValueString";
        }

        if ($innerHTML !== null) {
            $html .= '>' . $innerHTML . '</' . $tag . '>';
        } else {
            $html .= !in_array(strtolower($tag), $unpairedElements) ? '></' . $tag . '>' : ' />';
        }

        return $html . "\n";
    }


    /**
     * Seta o conteúdo (inner html)
     * @param string $innerHTML
     */
    public function innerHTML(?string $innerHTML = null): self
    {
        $this->innerHTML = $innerHTML;
        return $this;
    }

    public function prepend(string $innerHTMLPrefix): self
    {
        $this->innerHTML = $innerHTMLPrefix . $this->innerHTML;
        return $this;
    }

    public function append(string $innerHTMLSuffix): self
    {
        $this->innerHTML .= $innerHTMLSuffix;
        return $this;
    }


    public function getAttrs(): array
    {
        return $this->attrs;
    }


    /**
     * @throws Exception
     */
    protected function validate(): void
    {
        if (!$this->renderer && !UI::getRenderer()) {
            throw new Exception('O objeto não possui um renderer. Inicie o UI:config passando o renderer padrão ou construa o elemento usando o método `renderer`, passando um renderer específico ');
        }
    }


}
