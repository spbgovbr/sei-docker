<?php


class IPAreaPaginacaoInfra extends AbstractIPAreaPaginacao
{

    public function render()
    {
        $strTipo = $this->strTipo;
        $strSelecao = $this->strSelecao;
        $strCustomCallbackJs = $this->strCustomCallbackJs;
        $paginaAtual0Indexed = $this->paginaAtual0Indexed;
        $objInfraPagina = $this->objInfraPagina;


        $ret = '<div id="div' . $strSelecao . 'AreaPaginacao' . $strTipo . '" class="infraAreaPaginacao">' . "\n";

        if ($this->hasMaisDeUmaPagina()) {

            $numPaginas = $this->numPaginas;

            if (!$this->isPrimeiraPagina) {
                if ($numPaginas > 2) {
                    $ret .= $this->criarBotaoPaginacao(
                            '=',
                            '0',
                            'Primeira Página',
                            'PrimeiraPagina',
                            $objInfraPagina->getIconePaginacaoPrimeira()) . "&nbsp;&nbsp;\n";
                } else {
                    $ret .= str_repeat('&nbsp;', 7);
                }
                $ret .= $this->criarBotaoPaginacao(
                        '-',
                        '0',
                        'Página Anterior',
                        'PaginaAnterior',
                        $objInfraPagina->getIconePaginacaoAnterior()) . "&nbsp;&nbsp;\n";
            } else {
                $ret .= str_repeat('&nbsp;', 14);
            }

            if ($numPaginas > 2) {
                $ret .= '<select id="sel' . $strSelecao . 'Paginacao' . $strTipo . '" name="sel' . $strSelecao . 'Paginacao' . $strTipo . '" onchange="infraAcaoPaginar(\'=\',this.value,\'' . $strSelecao . '\', ' . $strCustomCallbackJs . ');" class="infraSelect" ' . ($this->bolMontarTabIndexPaginacao ? 'tabindex="' . ($strTipo == 'Superior' ? 1001 : 32700) . '"' : '') . ' style="display:inline;">' . "\n";
                for ($i = 0; $i < $numPaginas; $i++) {
                    $ret .= '<option value="' . $i . '"';
                    if ($i == $paginaAtual0Indexed) {
                        $ret .= ' selected="selected" ';
                    }
                    $ret .= '>' . ($i + 1) . '</option>' . "\n";
                }
                $ret .= '</select>&nbsp;&nbsp;' . "\n";
            }

            //Se não esta na última página
            if (!$this->isUltimaPagina) {
                $ret .= $this->criarBotaoPaginacao(
                        '+',
                        '0',
                        'Próxima Página',
                        'ProximaPagina',
                        $objInfraPagina->getIconePaginacaoProxima()) . "&nbsp;&nbsp;\n\n";
                $ret .= '&nbsp;';
                if ($numPaginas > 2) {
                    $ret .= $this->criarBotaoPaginacao(
                        '=',
                        $this->numUltimaPagina,
                        'Última Página',
                        'UltimaPagina',
                        $objInfraPagina->getIconePaginacaoUltima());
                }
            }
        }
        $ret .= '</div>' . "\n";

        return $ret;
    }

    protected function criarBotaoPaginacao($jsTipo, $jsPag, $title, $idParcial, $img)
    {
        $onclick = $this->criarJsOnclick($jsTipo, $jsPag);
        $strSelecao = $this->strSelecao;
        $strTipo = $this->strTipo;
        $bolMontarTabIndexPaginacao = $this->bolMontarTabIndexPaginacao;
        $objInfraPagina = $this->objInfraPagina;

        $tabindex = $bolMontarTabIndexPaginacao ? ('tabindex="' . ($strTipo == 'Superior' ? 1001 : 32700) . '"') : '';
        return <<<html
                    <a id="lnk${strSelecao}${idParcial}${strTipo}" 
                        href="javascript:void(0);" 
                        onclick="$onclick" 
                        title="$title" 
                        $tabindex><img src="$img" 
                            title="$title" 
                            alt="$title" 
                            class="infraImg"/></a>
html;
    }
}