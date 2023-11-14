<?php


namespace TRF4\UI\Bootstrap4;


class InputMask extends InputText
{
    protected $mask;

    public function __construct(?string $label, string $name, string $mask, string $pattern)
    {
        parent::__construct($label, $name);
        $this->pattern($pattern);
        $this->mask = $mask;
        $this->invalidValueFeedback = 'O padrão ' . $this->mask . ' do campo está inválido.';
    }

    /**
        * @return string
    */
    public function render(): string
    {
        $inputId = $this->_input->getAttrId();
        $inputHTML = parent::render();
        $mask = $this->mask;

        $html = <<<h
            $inputHTML
            <script type="text/javascript">
                IMask(document.getElementById('$inputId'), {
                   mask: '$mask',
                });
            </script>
h;
        return $html;
    }
}