<?php

namespace TRF4\UI\Component;

use TRF4\UI\Element\AbstractElement;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\Labeled\AbstractElementWithLabel;
use TRF4\UI\UI;

abstract class FileUpload extends AbstractElementWithLabel
{
    use Customizable;

    /** @var GenericElement */
    public $_input;

    protected $description = null; 

    protected $urlAjax = '';
    
    protected $maxFiles = 1;

    protected $allowedFileExtensions;

    protected $initFiles;

    protected $overrideFile = false;
    
    protected $async = false;

    protected $showRemove = false;

    protected $cancel = false;

    protected $deleteUrl = '';

    /**
     * InputText constructor.
     * @param string|null $labelInnerHtml
     * @param string|null $name
     */
    public function __construct(string $labelInnerHtml, ?string $name = null)
    {
        parent::__construct($labelInnerHtml);

        $this->_input = UI::el('input')->type('file');
        
        if ($name) {
            $this->name($name);
        }
    }

    public function getDefaultElement(): AbstractElement {
        return $this->_input;
    }

    public function description(string $description): self {
        $this->description = $description;
        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }   

    public function urlAjax(string $urlAjax, bool $async = false): self {
        $this->urlAjax = $urlAjax;
        $this->async   = $async;
        return $this;
    }

    public function getUrlAjax(): string {
        return $this->urlAjax;
    }

    public function maxFiles(int $maxFiles): self {
        $this->maxFiles = $maxFiles;
        return $this;
    }

    public function getMaxFiles(): int {
        return $this->maxFiles;
    } 

    public function allowedFileExtensions(array $allowedFileExtensions): self {
        $this->allowedFileExtensions = $allowedFileExtensions;
        return $this;
    }

    public function getAllowedFileExtensions(): array {
        return $this->allowedFileExtensions;
    } 

    public function initFiles(array $initFiles): self {
        $this->initFiles = $initFiles;
        return $this;
    }

    public function getInitFiles(): array {
        return $this->initFiles;
    } 

    public function overrideFile(): self {
        $this->overrideFile = true;
        return $this;
    }

    public function isOverrideFile(): bool {
        return $this->overrideFile;
    }

    public function deleteUrl(string $deleteUrl): self {
        $this->deleteUrl = $deleteUrl;
        return $this;
    }

    public function showRemove(): self {
        $this->showRemove = true;
        return $this;
    }

    public function isShowRemove(): bool {
        return $this->showRemove;
    }

    public function showCancel(): self {
        $this->cancel = true;
        return $this;
    }

    public function isShowCancel(): bool {
        return $this->cancel;
    }

    public function getDeleteUrl(): string {
        return $this->deleteUrl;
    }
    
    public function isAsync(): bool {
        return $this->async;
    }

}
