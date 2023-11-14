<?php


class IPTrCheckBS4 extends AbstractTrCheck
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
        $label = '<label class="custom-control-label" for="' . $strTagId . '"></label>';

        if ($this->objInfraPagina->getTipoSelecao() == InfraPagina::$TIPO_SELECAO_SIMPLES) {
            $name = 'chk' . $strNomeSelecao . 'Item';
            $elInput = <<<html
    <div class="custom-control custom-radio">
        <input $inputChecked class="custom-control-input" id="$strTagId" name="$name" $strTabIndex title="$inputTitle" type="radio" value="$strId" $strAtributos>
        <label class="custom-control-label" for="$strTagId"></label>
    </div>
html;
        } else {
            $name = $strTagId;
            $value = $this->strId;
            $elInput = <<<html
    <div class="custom-control custom-checkbox form-group">
        <input $inputChecked class="custom-control-input" id="$strTagId" name="$name" $strTabIndex title="$inputTitle" type="checkbox" value="$value" $strAtributos>
        $label
    </div>
html;
        }

        $elA = $this->getLink();

        return $elA . $elInput;
    }
}