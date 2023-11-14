<?php

namespace App\Http\View\DocsHelper;

use Tests\AbstractDirectoryMetadata;
use Tests\DefaultDirectoryMetadata;

class Directory
{
    /** @var string */
    public $name;
    /** @var Directory[] */
    public $childDirectories;
    /** @var TestClass[] */
    public $testClasses;
    /** @var Directory */
    public $parent;
    /** @var AbstractDirectoryMetadata */
    public $metadata;

    /**
     * DocsTreeNode constructor.
     * @param string $name
     * @param AbstractDirectoryMetadata|null $metadata Se nulo, DefaultDirectoryMetadata será usado
     * @param Directory[] $childDirectories
     * @param TestClass[] $testClasses
     */
    public function __construct(string $name, ?AbstractDirectoryMetadata $metadata, array $childDirectories = [], array $testClasses = [])
    {
        $this->name = $name;
        $this->metadata = $metadata ?: new DefaultDirectoryMetadata();
        foreach ($childDirectories as $childDirectory) {
            $this->addChildDirectory($childDirectory);
        }

        foreach ($testClasses as $testClass) {
            $testClass->setParentDirectory($this);
        }

        $this->childDirectories = $childDirectories;
        $this->testClasses = $testClasses;
    }


    public function addTestClass(TestClass $testClass): void
    {
        $testClass->setParentDirectory($this);
        $this->testClasses[] = $testClass;
    }

    public function getNameHtml(): string
    {
        return str_replace(' ', '-', $this->name);
    }

    public function getHtmlId(): string
    {
        $parentsNames = [];
        $parent = $this;

        while ($parent != null) {
            $parentsNames[] = $parent->getNameHtml();
            $parent = $parent->parent;
        }

        return implode('_', array_reverse($parentsNames));
    }

    public function addChildDirectory(Directory $child)
    {
        $child->setParentDirectory($this);
        $this->childDirectories[] = $child;
    }

    protected function setParentDirectory(Directory $parent)
    {
        $this->parent = $parent;
    }

    public function render()
    {
        return $this->renderView('directory');
    }

    public function renderFragments(): string
    {
        return $this->renderView('body');
    }

    public function getDescription(): string
    {
        return $this->metadata->getDescription() ?: '';
    }

    public function shouldShowChildren(): bool
    {
        return in_array($this->metadata->viewType, [
            AbstractDirectoryMetadata::TYPE_CARD,
            AbstractDirectoryMetadata::TYPE_FORM_CARD,
        ]);
    }

    public function getTitle(): string
    {
        return $this->metadata->buildTitle($this->name);
    }


    private function renderView(string $view)
    {
        return view('components.showcase.' . $this->metadata->viewType . '.' . $view, [
            'directory' => $this
        ]);
    }
}