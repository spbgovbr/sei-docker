<?php


namespace TRF4\UI\Bootstrap4;


use Mockery\Exception;
use TRF4\UI\Component\IconButton as BaseIconButton;
use TRF4\UI\UI;

class IconButton extends BaseIconButton
{

    /** @var int */
    protected $type = 0;


    public function __construct(?string $title)
    {
        parent::__construct($title);

        $this->_a->href('#');
    }


    public function render(): string
    {
        if (!$this->type) {
            throw new Exception('Tipo de InputButton não definido');
        }

        return $this->_a->innerHTML(
            UI::el('i', $this->getIcon())->class('material-icons')
        );
    }


    private function getIcon()
    {
        switch ($this->type) {
            case self::TYPE_SEARCH;
                return 'search';
            default:
                throw new Exception('Tipo de InputButton não definido');
        }
    }
}