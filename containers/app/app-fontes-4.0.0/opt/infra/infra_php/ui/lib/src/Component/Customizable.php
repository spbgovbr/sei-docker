<?php

namespace TRF4\UI\Component;

trait Customizable
{
    /** @var callable|null */
    protected $customizeFn = null;

    public function customize(callable $fn): self {
        $this->customizeFn = $fn;
        return $this;
    }

    protected function runCustomizeHook() {
        if ($this->customizeFn) {
            call_user_func($this->customizeFn, $this);
        }
    }

    public function render(): string {
        $this->buildElements();
        $this->runCustomizeHook();
        $html = $this->assembleAndPrintElements();
        $html .= $this->printScripts();
        return $html;
    }

    abstract protected function buildElements(): void;

    abstract protected function assembleAndPrintElements(): string;

    private function printScripts(): string {
        $ret = '';
        if ($this->scripts) {
            $ret = "<script>";
            foreach ($this->scripts as $script) {
                $ret .= $script;
            }
            $ret .= '</script>';
        }
        return $ret;
    }
}
