<?php


namespace TRF4\UI\Labeled;


use TRF4\UI\Config;
use TRF4\UI\Element\Component;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\UI;

abstract class AbstractElementWithLabel extends Component
{
    /**
     * @var string
     */
    protected $invalidValueFeedback;
    /** @var array */
    protected $scripts = [];
    /** @var string|null */
    protected $labelInnerHtml = '';
    /** @var GenericElement | null */
    public $_label = null;
    /** @var GenericElement | null */
    public $_hintWrapper = null;

    private $hint;

    public function __construct(?string $labelInnerHtml = null) {
        if ($labelInnerHtml !== null) {
            $this->labelInnerHtml = $labelInnerHtml;
            $this->_label = UI::el('label', $labelInnerHtml);
        }
    }

    public function required(?string $invalidValueFeedback = null, ?bool $required = true): self
    {
        $this->setValue('required', $required);
        if ($invalidValueFeedback) {
            $this->invalidValueFeedback = $invalidValueFeedback;
        }
        return $this;
    }

    public function hint(string $tooltipContent, bool $isHtml = false): self
    {

        $id = ($this->getAttrId()) ? $this->getAttrId() : $this->defaultChildrenName;

        if (!$this->hasLabel()) {
            $this->_hintWrapper = UI::el('div')->class('input-hint-wrapper');
        }

        $this->hint = UI::el('i')
            ->class('material-icons float-right')
            ->dataContent($tooltipContent)
            ->dataHtml(($isHtml) ? "true" : "false")
            ->dataToggle('popover')
            ->dataTrigger('hover')
            ->id($id . '-hint')
            ->innerHTML('help_outline');

        return $this;
    }

    public function getHint(): ?string
    {
        return $this->hint;
    }

    public function buildHintIfIsSet($wrapper = null, ?string $class = null)
    {
        $id = ($this->getAttrId()) ? $this->getAttrId() : $this->defaultChildrenName;

        if ($this->getHint()) {
            if ($this->hasLabel()) {
                $this->_label->class('w-100')->innerHTML($this->getLabelText() . $this->getHint());
            } else {

                $el = ($wrapper) ? $wrapper : $this->getDefaultElement();

                if ($class) {
                    $this->_hintWrapper->class($class);
                }

                $this->_hintWrapper->innerHTML(
                    $el .
                    $this->getHint()
                );
            }
            $this->scripts[] = "$('#$id-hint').popover();";
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLabelText(): ?string
    {
        return $this->labelInnerHtml;
    }


    public function getInvalidValueFeedbackMessage(): string
    {
        if ($this->invalidValueFeedback) {
            return $this->invalidValueFeedback;
        }
        $label = $this->getLabelText();
        return Config::getFeedbackForInvalidField($label);
    }

    protected function hasLabel(): bool
    {
        return $this->_label !== null;
    }

}
