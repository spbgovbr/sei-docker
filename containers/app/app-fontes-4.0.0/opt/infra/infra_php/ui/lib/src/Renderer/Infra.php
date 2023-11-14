<?php


namespace TRF4\UI\Renderer;


use TRF4\UI\Infra\Alert;
use TRF4\UI\Infra\Button;
use TRF4\UI\Infra\Checkbox;
use TRF4\UI\Infra\CheckboxGroup;
use TRF4\UI\Infra\Date;
use TRF4\UI\Infra\InputText;
use TRF4\UI\Infra\InputHidden;
use TRF4\UI\Infra\MultiSelect;
use TRF4\UI\Infra\Radio;
use TRF4\UI\Infra\RadioGroup;
use TRF4\UI\Infra\Select;
use TRF4\UI\Infra\Table;
use TRF4\UI\Infra\Textarea;
use TRF4\UI\Labeled\AbstractElementWithLabel;

class Infra extends AbstractRenderer
{

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
        return \TRF4\UI\Infra\DateInterval::class;
    }

    public function getIconButtonClass(): string
    {
        // TODO: Implement getIconButtonClass() method.
    }

    public function getInputTextClass(): string
    {
        return InputText::class;
    }

    public function getInputHiddenClass(): string
    {
        return InputHidden::class;
    }

    public function getInputNumberClass(): string
    {
        return \TRF4\UI\Infra\InputNumber::class;
    }

    public function getInputMaskClass(): string
    {
        // TODO: Implement getInputMaskClass() method.
    }

    public function getRadioClass(): string
    {
        return Radio::class;
    }

    public function getRadioGroupClass(): string
    {
        return RadioGroup::class;
    }

    public function getSelectClass(): string
    {
        return Select::class;
    }

    public function getMultiSelectClass(): string
    {
        return MultiSelect::class;
    }

    public function getTableClass(): string
    {
        return Table::class;
    }

    public function getTextareaClass(): string
    {
        return Textarea::class;
    }

    public function getFileUploadClass(): string
    {
        return \TRF4\UI\Infra\FileUpload::class;
    }

    public function getRangeClass(): string
    {
        return \TRF4\UI\Infra\Range::class;
    }

    public function getMultiRangeClass(): string
    {
        return \TRF4\UI\Infra\MultiRange::class;
    }

    public static function createLabelFor(AbstractElementWithLabel $el): \TRF4\UI\Element\GenericElement
    {
        $label = parent::createLabelFor($el);
        $label->class('infraLabelOpcional');
        return $label;
    }


    public function getCheckboxGroupClass(): string
    {
        return CheckboxGroup::class;
    }

    public function checkbox(\TRF4\UI\Labeled\Checkbox $checkbox): string
    {
        return Checkbox::class;
    }


}
