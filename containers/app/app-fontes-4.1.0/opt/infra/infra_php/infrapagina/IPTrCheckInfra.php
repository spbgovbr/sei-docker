<?php


class IPTrCheckInfra extends AbstractTrCheck
{

    public function render()
    {
        $strId = $this->strId;
        $strNomeSelecao = $this->strNomeSelecao;
        $strAtributos = $this->strAtributos;
        $strTitulo = $this->strTitulo;
        $isChecked = $this->isChecked;
        $strTagId = 'chk' . $strNomeSelecao . 'Item' . $this->numItem;
        $inputTitle = $this->objInfraPagina->tratarHTML($strTitulo);

        $strTabIndex = '';
        if ($this->varTabIndex === true) {
            $strTabIndex = 'tabindex="' . $this->objInfraPagina->getProxTabTabela() . '"';
        } elseif ($this->varTabIndex !== false && is_numeric($this->varTabIndex)) {
            $strTabIndex = 'tabindex="' . $this->varTabIndex . '"';
        }

        $inputChecked = $isChecked ? "checked=\"checked\"" : '';

        if ($this->objInfraPagina->getTipoSelecao() == InfraPagina::$TIPO_SELECAO_SIMPLES) {
            $name = 'chk' . $strNomeSelecao . 'Item';
            $elInput = "<input $inputChecked class=\"infraRadio\" id=\"$strTagId\"  name=\"$name\" $strTabIndex title=\"$inputTitle\" type=\"radio\" value=\"$strId\" $strAtributos/>";
        } else {
            $name = $strTagId;
            $value = $this->strId;
            $elInput = "<input $inputChecked class=\"infraCheckbox\" id=\"$strTagId\" name=\"$name\" $strTabIndex title=\"$inputTitle\" type=\"checkbox\" value=\"$value\" $strAtributos/>";
        }

        $elA = $this->getLink();

        return $elA . $elInput;
    }
}