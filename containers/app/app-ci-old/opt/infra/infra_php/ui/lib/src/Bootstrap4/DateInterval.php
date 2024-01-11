<?php


namespace TRF4\UI\Bootstrap4;

use TRF4\UI\Renderer\Bootstrap4;
use TRF4\UI\UI;

class DateInterval extends Date
{

    public function render(): string
    {
        $inputId = $this->getAttrId();
        $startId = self::buildStartDate($inputId);
        $endId = self::buildEndDate($inputId);

        $rowWrapper = UI::el("div")->class("form-row");

        $startDate = new Date('', $startId);
        $endDate = new Date('', $endId);

        if ($this->withTime) {
            $startDate->withTime();
            $endDate->withTime();
        }

        $inputHTMLInicio = $this->renderInput($startDate);
        $inputHTMLFim = $this->renderInput($endDate);

        Bootstrap4::transformLabel($this);
        $label = $this->_label;

        $this->buildHintIfIsSet($rowWrapper, "no-label");

        $js = $this->getHint() ? "$('#$inputId-hint').popover();" : '';

        $invalidValueFeedbackHtml = Bootstrap4::getFeedbackForInvalidValue($this);
        $withTime = json_encode($this->withTime);

        $rowWrapper->innerHTML($inputHTMLInicio . $inputHTMLFim);

        $this->_wrapper->innerHTML($label . $rowWrapper . $invalidValueFeedbackHtml);

        $_wrapperHTML = $this->_wrapper->render();

        $html = <<<h
        $_wrapperHTML
        <script>
            UI.PHPHelper.dateInterval.init('$startId', '$endId', $withTime);$js
        </script>
h;

        return $html;
    }

    /**
     * @param $inputId
     * @return string
     * @internal
     */
    public static function buildStartDate($inputId): string
    {
        return $inputId . 'Inicio';
    }

    /**
     * @param $inputId
     * @return string
     * @internal
     */
    public static function buildEndDate($inputId): string
    {
        return $inputId . 'Fim';
    }
}