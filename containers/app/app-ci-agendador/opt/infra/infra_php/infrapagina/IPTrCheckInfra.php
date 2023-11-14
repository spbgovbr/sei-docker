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
        $inputTitle = $this->objInfraPagina->tratarHTML($strTitulo);

        $strTagId = 'chk' . $strNomeSelecao . 'Item' . $this->numItem;
        $inputTabindex = $this->hasTabIndex ? $this->objInfraPagina->getProxTabTabela() : '';
        $inputChecked = $isChecked ? "checked=\"checked\"" : '';

        if ($this->objInfraPagina->getTipoSelecao() == InfraPagina::$TIPO_SELECAO_SIMPLES) {
            $name = 'chk' . $strNomeSelecao . 'Item';
            $elInput = "<input $inputChecked class=\"infraRadio\" id=\"$strTagId\"  name=\"$name\" $inputTabindex title=\"$inputTitle\" type=\"radio\" value=\"$strId\" $strAtributos/>";
        } else {
            $name = $strTagId;
            $value = $this->strId;
            $elInput = "<input $inputChecked class=\"infraCheckbox\" id=\"$strTagId\" name=\"$name\" $inputTabindex title=\"$inputTitle\" type=\"checkbox\" value=\"$value\" $strAtributos/>";
        }

        $aId = "ID-$strId";
        $elA = "<a id=\"lnk${strNomeSelecao}$aId\" name=\"$aId\"></a>";


        return $elA . $elInput;
    }
}