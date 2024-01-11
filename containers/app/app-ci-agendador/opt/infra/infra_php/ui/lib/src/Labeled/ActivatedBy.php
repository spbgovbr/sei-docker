<?php


namespace TRF4\UI\Labeled;


trait ActivatedBy
{
    /** @var string */
    protected $activatedBy;

    public function activatedBy(string $fieldId): self {
        $id = $this->getAttrId();
        $this->scripts[] = "UI.PHPHelper.addActivatedBy('$id', '$fieldId');";
        return $this;
    }
}