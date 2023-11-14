<?php

namespace App\Http\View\DocsHelper;

use Gajus\Dindent\Indenter;
use Tests\Showcaser;

/**
 * Class TestClass
 */
class TestClass
{
    /** @var string */
    public $name;
    /** @var string */
    public $description;
    /** @var string */
    public $code;
    public $html;
    /** @var Showcaser */
    public $showcaser;
    /** @var string */
    public $php;
    /** @var Directory|null */
    protected $parentDirectory;


    public function __construct(Showcaser $showcaser, Directory $parentDirectory = null)
    {
        $this->name = $showcaser->name();
        $this->description = $showcaser->description();
        $this->php = $showcaser->getActualMethodCode();
        $this->showcaser = $showcaser;
        $this->parentDirectory = $parentDirectory;
    }

    public function getHtmlId(): string
    {
        $parentsNames = $this->buildParentDirectoriesPrefix();
        $ret = str_replace(' ', '-', trim($parentsNames . '_' . $this->name));
        $ret = preg_replace('/[^a-zA-Z0-9\-_]/','',$ret);
        return $ret;
    }

    public function setParentDirectory(Directory $parent)
    {
        $this->parentDirectory = $parent;
    }

    protected function buildParentDirectoriesPrefix(): string
    {
        return $this->parentDirectory->getHtmlId();
    }

    private function formatHTML(string $actual)
    {
        return (new Indenter())->indent(trim(html_entity_decode($actual)));
    }

    public function getHtml()
    {
        return $this->showcaser->isPrototype()
            ? $this->formatHTML($this->showcaser->rendererExpectations()[0][1])
            : $this->formatHTML($this->showcaser->actual());
    }

}