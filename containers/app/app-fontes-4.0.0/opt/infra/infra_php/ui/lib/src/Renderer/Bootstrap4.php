<?php

namespace TRF4\UI\Renderer;

use TRF4\UI\Bootstrap4\Alert;
use TRF4\UI\Bootstrap4\Button;
use TRF4\UI\Bootstrap4\Checkbox;
use TRF4\UI\Bootstrap4\CheckboxGroup;
use TRF4\UI\Bootstrap4\Date;
use TRF4\UI\Bootstrap4\DateInterval;
use TRF4\UI\Bootstrap4\FileUpload;
use TRF4\UI\Bootstrap4\IconButton;
use TRF4\UI\Bootstrap4\InputMask;
use TRF4\UI\Bootstrap4\InputNumber;
use TRF4\UI\Bootstrap4\InputText;
use TRF4\UI\Bootstrap4\InputHidden;
use TRF4\UI\Bootstrap4\MultiRange;
use TRF4\UI\Bootstrap4\MultiSelect;
use TRF4\UI\Bootstrap4\Radio;
use TRF4\UI\Bootstrap4\RadioGroup;
use TRF4\UI\Bootstrap4\Range;
use TRF4\UI\Bootstrap4\Select;
use TRF4\UI\Bootstrap4\Table;
use TRF4\UI\Bootstrap4\Textarea;
use TRF4\UI\Element\AbstractSimpleElement;
use TRF4\UI\Element\FluidElementInterface;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\Labeled\AbstractElementWithLabel;
use TRF4\UI\UI;

class Bootstrap4 extends AbstractRenderer
{

    public static function transformLabel(AbstractElementWithLabel $el)
    {
        if ($el->_label) {
            $label = $el->_label->for($el->getAttrId());

            if (self::isRequired($el)) {
                $label->prepend('<span class="text-danger">*</span>');
            }
        }
    }

    public function getAlertClass(): string
    {
        return Alert::class;
    }

        public function getButtonClass(): string
    {
        return Button::class;
    }

    public function getCheckboxClass(): string
    {
        return Checkbox::class;
    }

    public function getDateClass(): string
    {
        return Date::class;
    }

    public function getDateIntervalClass(): string
    {
        return DateInterval::class;
    }

    public function getIconButtonClass(): string
    {
        return IconButton::class;
    }

    public function getInputTextClass(): string
    {
        return InputText::class;
    }

    public function getInputNumberClass(): string
    {
        return InputNumber::class;
    }

    public function getInputHiddenClass(): string
    {
        return InputHidden::class;
    }

    public function getInputMaskClass(): string
    {
        return InputMask::class;
    }

    public function getRadioClass(): string
    {
        return Radio::class;
    }

    public function getRadioGroupClass(): string
    {
        return RadioGroup::class;
    }

    public function getCheckboxGroupClass(): string
    {
        return CheckboxGroup::class;
    }

    public function getSelectClass(): string
    {
        return Select::class;
    }

    public function getMultiSelectClass(): string
    {
        return MultiSelect::class;
    }

    public function getFileUploadClass(): string
    {
        return FileUpload::class;
    }

    public function getRangeClass(): string
    {
        return Range::class;
    }

    public function getMultiRangeClass(): string
    {
        return MultiRange::class;
    }

    public function getTableClass(): string
    {
        return Table::class;
    }

    public function getTextareaClass(): string
    {
        return Textarea::class;
    }


    public static function formGroup(?string $innerHTML = null): AbstractSimpleElement
    {
        $formGroup = UI
            ::el('div')
            ->class('form-group');

        if ($innerHTML) {
            $formGroup->innerHTML($innerHTML);
        }

        return $formGroup;
    }


    public static function createLabelFor(AbstractElementWithLabel $el): GenericElement
    {
        $label = parent::createLabelFor($el);

        if (self::isRequired($el)) {
            $label->prepend('<span class="text-danger">*</span>');
        }

        return $label;
    }

    protected static function isRequired(FluidElementInterface $el): bool
    {
        return $el->get('required') === true;
    }

    protected static function hasPattern(\TRF4\UI\Element\FluidElementInterface $el): bool
    {
        return $el->get('pattern') !== null;
    }

    /**
     * TODO refatorar. Esse método foi feito para tratar feedback de valor vazio, e não valores inválidos (de acordo com pattern)
     * @param AbstractElementWithLabel $el
     * @param callable|null $filter
     * @return string
     */
    public static function getFeedbackForInvalidValue(AbstractElementWithLabel $el, ?callable $filter = null): string
    {
        $html = '';
        if (self::isRequired($el) || self::hasPattern($el)) {
            $message = $el->getInvalidValueFeedbackMessage();
            if ($filter) {
                $message = $filter($message);
            }

            $html = "<div class='invalid-feedback'>$message</div>";
        }
        return $html;
    }


}
