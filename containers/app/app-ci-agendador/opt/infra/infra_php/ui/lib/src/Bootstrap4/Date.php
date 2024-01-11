<?php

namespace TRF4\UI\Bootstrap4;


use TRF4\UI\Component\Date as BaseDate;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\UI;

class Date extends BaseDate
{
    /** @var GenericElement */
    public $_wrapper;
    /**
     * @internal
     * @var string|null
     */
    public $value = null;
    protected $pattern = "(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d";
    protected $patternWithTime = "(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d ([0-9]|0[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9])$";

    public function __construct(string $label, string $name)
    {
        parent::__construct($label, $name);

        $this->_wrapper = UI::el('div')->class('form-group');
    }

    public function value(?string $value)
    {
        $this->value = $value;
        return $this;
    }

    public function render(): string
    {
        $date = $this;

        $this->buildHintIfIsSet();

        if ($this->_hintWrapper) {
            $date->_input = $this->_hintWrapper;
        }

        $html = $this->renderInput($date);

        $withTime = json_encode($this->withTime);
        $inputId = json_encode($date->getAttrId());
        $value = json_encode($date->value);

        Bootstrap4::transformLabel($this);

        $scripts = implode($this->scripts, "");

        $label = $this->_label->render();

        $html .= <<<JS
<script>
    UI.PHPHelper.date.init($inputId, $withTime, $value);$scripts
</script>
JS;

        Bootstrap4::transformLabel($this);
        $this->_wrapper->innerHTML(
            $label .
            $html
        );
        return $this->_wrapper->render();
    }

    protected function renderInput(Date $date)
    {
        $inputId = $date->getAttrId();

        $date->class('form-control datetimepicker-input')
            ->dataToggle('datetimepicker')
            ->dataTarget("#$inputId");

        if (!$this->get('placeholder')) {
            $date->placeholder("__/__/____");

            if ($this->withTime) {
                $date->placeholder("__:__");
            }
        }

        $feedback = Bootstrap4::getFeedbackForInvalidValue($date);

        $pattern = $this->withTime ? $this->patternWithTime : $this->pattern;

        if ($this->isRequired()) {
            $date->attr("pattern", $pattern);
        }

        $inputHTML = $date->_input->render();
        return <<<HTML
            <div class='input-group input-group-sm datepicker'>
                $inputHTML
                <div class="input-group-append" data-target="#$inputId" data-toggle="datetimepicker">
                    <span class="input-group-text" id="inputGroup-sizing-sm">
                        <i class="material-icons m-0">date_range</i>
                    </span>
                </div>
                $feedback
            </div>
HTML;
    }
}
