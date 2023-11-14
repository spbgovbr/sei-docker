<?php

namespace TRF4\UI;

use TRF4\UI\Bootstrap4\InputMask;
use TRF4\UI\Component\InputText;

class Preset
{

    /**
     * @param string|null $label
     * @param string $name
     * @param bool $required
     * @return InputMask
     * O campo padrão é um "input text"
     * Cria um campo de CPF.
     */
    public static function cpf(?string $label, ?string $name): InputText
    {
        $maskCpf = '000.000.000-00';
        $patternCpf = '[0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}';

        return UI::inputText($label, $name)
            ->mask($maskCpf)
            ->pattern($patternCpf);
    }

    /**
     * @param string|null $label
     * @param string $name
     * @return InputText
     * Cria um campo de CNPJ.
     * O campo padrão é um "input text"
     */
    public static function cnpj(?string $label, ?string $name): InputText
    {
        $maskCnpj = '00.000.000/0000-00';
        $patternCnpj = '[0-9]{2}.[0-9]{3}.[0-9]{3}/[0-9]{4}-[0-9]{2}';
        return UI::inputText($label, $name)
            ->mask($maskCnpj)
            ->pattern($patternCnpj);
    }

    /**
     * @param string|null $label
     * @param string $name
     * @return InputText
     * Cria um campo de Número de processo.
     * O campo padrão é um "input text"
     */
    public static function numeroProcesso(?string $label, ?string $name): InputText
    {
        $maskProcesso = '0000000-00.0000.0.00.0000';
        $patternProcesso = '[0-9]{7}-[0-9]{2}.[0-9]{4}.[0-9]{1}.[0-9]{2}.[0-9]{4}';

        return UI::inputText($label, $name)
            ->mask($maskProcesso)
            ->pattern($patternProcesso);
    }

}   