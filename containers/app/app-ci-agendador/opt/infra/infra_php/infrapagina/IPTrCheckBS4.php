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

        if ($this->objInfraPagina->getTipoSelecao() == InfraPagina::$TIPO_SELECAO_SIMPLES) {
            $name = 'chk' . $strNomeSelecao . 'Item';
            $elInput = InfraUI::radio(null, $this->strId, $strTagId)->name($name);
        } else {
            $name = $strTagId;
            $value = $this->strId;
            $elInput = InfraUI::checkbox(null, $name, $value)->id($strTagId);
        }

        $elA = InfraUI::el('a')
            ->id("lnk${strNomeSelecao}ID-$strId")
            ->innerHTML('')
            ->name("ID-$strId");

        $elInput
            ->title($this->objInfraPagina->tratarHTML($strTitulo))
            ->addAttrAsString($strAtributos);

        if ($this->hasTabIndex) {
            $elInput->tabindex($this->objInfraPagina->getProxTabTabela());
        }

        if ($isChecked) {
            $elInput->checked();
        }

        return $elA . $elInput;
    }
}