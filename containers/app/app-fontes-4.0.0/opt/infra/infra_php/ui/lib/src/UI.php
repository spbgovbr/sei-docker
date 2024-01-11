<?php


namespace TRF4\UI;

use TRF4\UI\Component\Alert;
use TRF4\UI\Component\Button;
use TRF4\UI\Component\CheckboxGroup;
use TRF4\UI\Component\Date;
use TRF4\UI\Component\FileUpload;
use TRF4\UI\Component\InputHidden;
use TRF4\UI\Component\InputNumber;
use TRF4\UI\Component\InputText;
use TRF4\UI\Component\MultiRange;
use TRF4\UI\Component\RadioGroup;
use TRF4\UI\Component\Range;
use TRF4\UI\Component\Table;
use TRF4\UI\Component\Textarea;
use TRF4\UI\Element\GenericElement;
use TRF4\UI\Labeled\Checkbox;
use TRF4\UI\Labeled\Radio;
use TRF4\UI\Labeled\Select;
use TRF4\UI\Renderer\AbstractRenderer;
use TRF4\UI\Util\Dependency;

class UI
{
    /** @var string */
    public static $defaultFeedbackForInvalidField;

    /**
     * @var AbstractRenderer
     */
    protected static $defaultRenderer;

    /**
     * @param AbstractRenderer $defaultRenderer é o renderer usado
     * @param string $defaultFeedbackForInvalidField Mensagem de feedback padrão exibida no caso de um campo estar inválido.
     * É uma string que pode receber como parâmetro (para o sprintf) o label do campo em questão.
     *
     * Ex.: `O campo %s é obrigatório` se transforma em  `O campo UF é obrigatório` para um input com label "UF"
     */
    public static function config(AbstractRenderer $defaultRenderer)
    {
        Config::setRenderer($defaultRenderer);
    }

    public static function getRenderer(): ?AbstractRenderer
    {
        return Config::getRenderer();
    }

    /**
     * Elemento modificado por padrão: alerta (div)
     * @param string $innerHTML
     * @return Alert
     */
    public static function alert(string $innerHTML): Alert
    {
        $class = self::getRenderer()->getAlertClass();
        return new $class($innerHTML);
    }

    /**
     * Elemento modificado por padrão: button
     * @param string $innerHTML
     * @return Button
     */
    public static function button(string $innerHTML): Button
    {
        $class = self::getRenderer()->getButtonClass();
        return new $class($innerHTML);
    }

    /**
     *
     * Caso utilize o checkboxGroup,
     *      se este checkbox não possuir um name,
     *      o name atribuído será o do checkboxGroup
     *
     * @param string|null $labelInnerHtml
     * @param string|null $name
     * @param string|null $value
     * @return Checkbox
     * @see self::checkboxGroup
     */
    public static function checkbox(?string $labelInnerHtml = null, ?string $name = null, ?string $value = null): Checkbox
    {
        $class = self::getRenderer()->getCheckboxClass();
        return new $class($labelInnerHtml, $name, $value);
    }

    /**
     * Recebe, na forma curta, um array de arrays de opções
     * ou arrays de objetos do tipo Checkbox
     *
     * Caso $name seja definido, para simplificar o código,
     * os checkboxes de $setOptions herdarão este $name, APENAS SE eles próprios não possuírem um name.
     *
     * @param string $label
     * @param array $options
     * @param string|null $defaultChildrenName
     * @return CheckboxGroup
     */
    public static function checkboxGroup(string $label, array $options, ?string $defaultChildrenName = null): CheckboxGroup
    {
        $class = self::getRenderer()->getCheckboxGroupClass();
        return new $class($label, $options, $defaultChildrenName);
    }

    public static function dependency(string $name, string $inputId, string $placeholderIfNull): Dependency
    {
        return new Dependency($name, $inputId, $placeholderIfNull);
    }

    /**
     * Elemento modificado por padrão: input
     * @param string $label
     * @param string $nameAndId
     * @return Date
     */
    public static function datetime(string $label, string $nameAndId): Date
    {
        return self::date($label, $nameAndId)->withTime();
    }

    /**
     * Elemento modificado por padrão: input
     * @param string $label
     * @param string $nameAndId
     * @return Date
     */
    public static function date(string $label, string $nameAndId): Date
    {
        $class = self::getRenderer()->getDateClass();
        return new $class($label, $nameAndId);
    }

    /**
     * Cria dois campos que permitem selecionar um intervalo de datas.
     * @param string $label
     * @param string $name
     * @return Date
     */
    public static function dateInterval(string $label, string $name): Date
    {
        $class = self::getRenderer()->getDateIntervalClass();
        return new $class($label, $name);
    }

    /**
     * Cria dois campos para definir um intervalo de datas/horas.
     * @param string $label
     * @param string $name
     * @return DateInterval
     */
    public static function dateTimeInterval(string $label, string $name): Date
    {
        return self::dateInterval($label, $name)->withTime();
    }

    public static function el(string $tagName, string $innerHTML = ''): GenericElement
    {
        return new GenericElement($tagName, $innerHTML);
    }

    /**
     * O campo padrão é um "input text"
     * Cria um campo de texto.
     */
    public static function inputText(?string $label = null, string $nameAndDefaultId): InputText
    {
        $class = self::getRenderer()->getInputTextClass();
        return new $class($label, $nameAndDefaultId);
    }

    /**
     * O campo padrão é um "input hidden"
     * Cria um campo oculto.
     */
    public static function hidden(string $nameAndDefaultId, ?string $value = null): InputHidden
    {
        $class = self::getRenderer()->getInputHiddenClass();
        return new $class($nameAndDefaultId, $value);
    }

    /**
     * @param string $label
     * @param string $nameAndDefaultId
     * @return InputNumber
     */
    public static function inputNumber(string $label, string $nameAndDefaultId): InputNumber
    {
        $class = self::getRenderer()->getInputNumberClass();
        return new $class($label, $nameAndDefaultId);
    }

    /**
     * @param string $label
     * @param string $nameAndDefaultId
     * @return FileUpload
     */
    public static function fileUpload(string $label, string $nameAndDefaultId): FileUpload
    {
        $class = self::getRenderer()->getFileUploadClass();
        return new $class($label, $nameAndDefaultId);
    }

    /**
     * @param string|null $label
     * @param string $nameAndDefaultId
     * @return Range
     */
    public static function range(string $label, string $nameAndDefaultId, float $min, float $max): Range
    {
        $class = self::getRenderer()->getRangeClass();
        return new $class($label, $nameAndDefaultId, $min, $max);
    }

    /**
     * @param string|null $label
     * @param string $name
     * @return MultiRange
     */
    public static function multiRange(string $label, string $name, float $min, float $max): MultiRange
    {
        $class = self::getRenderer()->getMultiRangeClass();
        return new $class($label, $name, $min, $max);
    }


    /**
     * Elemento modificado por padrão: input
     * @param string $label
     * @param string $value
     * @param string $id
     * @return Radio
     */
    public static function radio(string $label, string $value, string $id): Radio
    {
        $class = self::getRenderer()->getRadioClass();
        return new $class($label, $value, $id);
    }

    /**
     * @param string $label
     * @param string $name
     * @param array $options
     * @return RadioGroup
     */
    public static function radioGroup(string $label, string $name, array $options): RadioGroup
    {
        $class = self::getRenderer()->getRadioGroupClass();
        return new $class($label, $name, $options);
    }

    /**
     * Elemento modificado por padrão: select
     * @param string|null $label
     * @param string|null $nameAndDefaultId
     * @param array $options
     * @return Select
     */
    public static function select(string $label, ?string $nameAndDefaultId = null, array $options = []): Select
    {
        $class = self::getRenderer()->getSelectClass();
        return new $class($label, $nameAndDefaultId, $options);
    }

    /**
     * Elemento modificado por padrão: multiselect
     * @param string|null $label
     * @param string|null $nameAndDefaultId
     * @param array $options
     * @return Select
     */
    public static function multiSelect(string $label = null, ?string $nameAndDefaultId = null, array $options = []): Select
    {
        $class = self::getRenderer()->getMultiSelectClass();
        return new $class($label, $nameAndDefaultId, $options);
    }

    public static function table(?string $title, array $rows): Table
    {
        $class = self::getRenderer()->getTableClass();
        return new $class($title, $rows);
    }

    /**
     * Elemento modificado por padrão: textarea
     * @param string|null $label
     * @param string $nameAndDefaultId
     * @return Textarea
     */
    public static function textarea(string $label, string $nameAndDefaultId): Textarea
    {
        $class = self::getRenderer()->getTextareaClass();
        return new $class($label, $nameAndDefaultId);
    }

}
