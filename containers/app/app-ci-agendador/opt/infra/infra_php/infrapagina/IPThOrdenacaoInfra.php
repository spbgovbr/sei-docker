<?php


class IPThOrdenacaoInfra extends AbstractIPThOrdenacao
{

    public function render()
    {
        $strTh = '';

        $strRotulo = str_replace('<br />', '', $this->strRotulo);

        $strImagemAcima = '';
        if ($this->isOrdenacaoAscAtiva) {
          $strImagemAcima = $this->objInfraPagina->getIconeOrdenacaoColunaAcimaSelecionada();
        }else{
          $strImagemAcima = $this->objInfraPagina->getIconeOrdenacaoColunaAcima();
        }

        $strImagemAbaixo = '';
        if ($this->isOrdenacaoDescAtiva) {
          $strImagemAbaixo = $this->objInfraPagina->getIconeOrdenacaoColunaAbaixoSelecionada();
        }else{
          $strImagemAbaixo = $this->objInfraPagina->getIconeOrdenacaoColunaAbaixo();
        }

        if ($this->objInfraPagina instanceof InfraPaginaEsquema3){

          $strTh .= "\n".'<div class="infraDivOrdenacao">'."\n";

          $strTh .= '<div class="infraDivRotuloOrdenacao">';
          $strTh .= $strRotulo;
          $strTh .= '</div>'."\n";

          $strTh .= '<div class="infraDivSetaOrdenacao">';
          $strTh .= '<a href="javascript:void(0);" ' . $this->getOnclickAsc() . ' ' . $this->getTabIndex() . '><img src="' . $strImagemAcima . '" title="Ordenar Ascendente" alt="Ordenar Ascendente" class="infraImgOrdenacao" /></a>';
          $strTh .= '</div>'."\n";

          $strTh .= '<div class="infraDivSetaOrdenacao">';
          $strTh .= '<a href="javascript:void(0);" ' . $this->getOnclickDesc() . ' ' . $this->getTabIndex() . '><img src="' . $strImagemAbaixo . '" title="Ordenar Descendente" alt="Ordenar Descendente" class="infraImgOrdenacao" /></a>';
          $strTh .= '</div>'."\n";

          $strTh .= '</div>'."\n\n";

        }else{

          $strTh .= "\n".'<table class="infraTableOrdenacao">'."\n";
          $strTh .= '<tr class="infraTrOrdenacao">'."\n";
          $strTh .= '<td width="1%" class="infraTdSetaOrdenacao"><a href="javascript:void(0);" ' . $this->getOnclickAsc() . ' ' . $this->getTabIndex() . '><img src="' . $strImagemAcima . '" title="Ordenar ' . $strRotulo . ' Ascendente" alt="Ordenar ' . $strRotulo . ' Ascendente" class="infraImgOrdenacao" /></a></td>'."\n";
          $strTh .= '<td rowspan="2" valign="center" class="infraTdRotuloOrdenacao">' . $strRotulo . '</td>'."\n";
          $strTh .= '</tr>'."\n";
          $strTh .= '<tr class="infraTrOrdenacao">'."\n";
          $strTh .= '<td class="infraTdSetaOrdenacao"><a href="javascript:void(0);" ' . $this->getOnclickDesc() . ' ' . $this->getTabIndex() . '><img src="' . $strImagemAbaixo . '" title="Ordenar ' . $strRotulo . ' Descendente" alt="Ordenar ' . $strRotulo . ' Descendente" class="infraImgOrdenacao" /></a></td>';
          $strTh .= '</tr>'."\n";
          $strTh .= '</table>'."\n\n";
        }
        return $strTh;
    }
}