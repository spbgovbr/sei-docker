<?php


namespace TRF4\UI\Renderer;


use TRF4\UI;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\Labeled\AbstractElementWithLabel;

abstract class AbstractRenderer
{
    public abstract function getAlertClass(): string;

    public abstract function getButtonClass(): string;

    public abstract function getCheckboxClass(): string;

    public abstract function getCheckboxGroupClass(): string;

    public abstract function getDateClass(): string;

    public abstract function getDateIntervalClass(): string;

    public abstract function getIconButtonClass(): string;

    public abstract function getInputHiddenClass(): string;

    public abstract function getInputNumberClass(): string;

    public abstract function getInputTextClass(): string;

    public abstract function getInputMaskClass(): string;

    public abstract function getRadioClass(): string;

    public abstract function getRadioGroupClass(): string;

    public abstract function getSelectClass(): string;

    public abstract function getMultiSelectClass(): string;

    public abstract function getTextareaClass(): string;

    public abstract function getFileUploadClass(): string;

    public abstract function getTableClass(): string;

    protected static function createLabelFor(AbstractElementWithLabel $el): GenericElement
    {
        $labelText = $el->getLabelText();
        $label = UI\UI::el('label', $labelText);
        $label->for($el->getAttrId());
        return $label;
    }


}
