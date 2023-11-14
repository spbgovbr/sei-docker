<?php


namespace TRF4\UI\Element;

/**
 * @method AbstractSimpleElement id(string $value)
 * @method AbstractSimpleElement class(string $value)
 * @method AbstractSimpleElement dataText(string $value)
 * @method AbstractSimpleElement for (string|null $getAttrId)
 * @method AbstractSimpleElement href (string $getAttrId)
 * @method AbstractSimpleElement name(string $value)
 * @method AbstractSimpleElement pattern(string $string)
 * @method AbstractSimpleElement placeholder (string $value)
 * @method AbstractSimpleElement style(string $value)
 * @method AbstractSimpleElement title(string $value)
 * @method AbstractSimpleElement type(string $value)
 * @method AbstractSimpleElement value(string $value)
 * @method AbstractSimpleElement role(string $value)
 *
 * @method AbstractSimpleElement checked(bool $value = true)
 * @method AbstractSimpleElement disabled(bool $value = true)
 * @method AbstractSimpleElement multiple(bool $value = true)
 * @method AbstractSimpleElement required(bool $value = true)
 * @method AbstractSimpleElement readonly(bool $value = true)
 */
abstract class FluidElementInterface
{
    /** @var bool */
    protected $disableIdEqualToName = false;

    abstract public function __call($method, $arguments);

    abstract public function render(): string;

    abstract protected function setValue($method, $arguments);

    public function __toString(): string
    {
        return $this->render();
    }

    public function randomId(): self
    {
        $this->id('ui-id-' . str_replace('\\', '-', get_class($this)) . rand());
        return $this;
    }

    public function __isset($name)
    {
        return true;
    }

    abstract protected function hasId(): bool;

    protected function setIdIfNotSet()
    {
        if ($this->disableIdEqualToName) {
            return;
        }

        if (!$this->hasId()) {
            $id = $this->getAttrId();

            if ($id) {
                $this->id($id);
            }
        }
    }

    protected function isRequired(): bool
    {
        return $this->get('required') === true;
    }
}
