<?php

class IPAreaPaginacaoBS4 extends AbstractIPAreaPaginacao
{
    public function render()
    {
        $html = '';
        if ($this->hasMaisDeUmaPagina()) {
            $html = $this->criarNavbar();
        }
        return $html;
    }

    private function criarNavbar()
    {
        $navClass = $this->strTipo == 'Superior' ? 'mt-0' : 'mb-2';
        $strSelecao = $this->strSelecao;
        $strTipo = $this->strTipo;

        $htmlPrimeiraPagina = $this->criarBotaoPrimeira();
        $htmlPaginaAnterior = $this->criarBotaoAnterior();
        $htmlSelect = $this->tentarCriarSelectPaginas();
        $htmlProximaPagina = $this->criarBotaoProxima();
        $htmlUltimaPagina = $this->criarBotaoUltima();

        return <<<html
            <nav class='infraAreaPaginacao $navClass' id="div{$strSelecao}AreaPaginacao{$strTipo}">
                <ul class="pagination pagination-sm justify-content-center mb-1">
                    $htmlPrimeiraPagina
                    $htmlPaginaAnterior
                    $htmlSelect                
                    $htmlProximaPagina
                    $htmlUltimaPagina
                </ul>
            </nav>
html;
    }

    private function criarBotaoPrimeira()
    {
        return $this->criarBotaoPaginacao(
            '=',
            '0',
            '<i class="material-icons icon-aligned">first_page</i>',
            'Primeira Página',
            'PrimeiraPagina',
            !(!$this->isPrimeiraPagina && $this->numPaginas > 2)
        );
    }

    private function criarBotaoAnterior()
    {
        return $this->criarBotaoPaginacao(
            '-',
            '0',
            '<i class="material-icons icon-aligned">navigate_before</i>',
            'Página Anterior',
            'PaginaAnterior',
            !($this->paginaAtual0Indexed > 0)
        );
    }

    private function tentarCriarSelectPaginas()
    {
        $select = '';

        if ($this->numPaginas >= 2) {
            $strSelecao = $this->strSelecao;
            $strTipo = $this->strTipo;

            $id = "sel{$strSelecao}Paginacao$strTipo";
            $onchange = "infraAcaoPaginar('=',this.value,'$strSelecao', $this->strCustomCallbackJs);";

            $strTabIndex = '';
            if ($this->varTabIndex === true) {
                $strTabIndex = 'tabindex="' . $this->objInfraPagina->getProxTabTabela() . '"';
            } elseif ($this->varTabIndex !== false && is_numeric($this->varTabIndex)) {
                $strTabIndex = 'tabindex="' . $this->varTabIndex . '"';
            }

            $options = '';

            for ($i = 0; $i < $this->numPaginas; $i++) {
                $options .= '<option value="' . $i . '"';
                if ($i == $this->paginaAtual0Indexed) {
                    $options .= ' selected="selected" ';
                }
                $options .= '>' . ($i + 1) . '</option>' . "\n";
            }

            $select = <<<html
                            <li class="page-item">
                                <select data-style='btn-primary' 
                                        class="selectpicker form-control form-control-sm page-link" 
                                        data-custom-title-format="{0} de {1}" 
                                        id="$id" 
                                        onchange="$onchange" $strTabIndex>
                                    $options
                                </select>
                            </li>
                            <script>
                                $(function (){
                                 $("#$id").selectpicker();
                                    infraBS4SelectAtivarPaginacao("#$id");
                                });
                            </script>
html;
        }

        return $select;
    }

    private function criarBotaoProxima()
    {
        return $this->criarBotaoPaginacao(
            '+',
            '0',
            '<i class="material-icons icon-aligned">navigate_next</i>',
            'Próxima Página',
            'ProximaPagina',
            $this->isUltimaPagina
        );
    }

    private function criarBotaoUltima()
    {
        return $this->criarBotaoPaginacao(
            '=',
            $this->numPaginas - 1,
            '<i class="material-icons icon-aligned">last_page</i>',
            'Última Página',
            'UltimaPagina',
            !(!$this->isUltimaPagina && $this->numPaginas > 2)
        );
    }

    /**
     * @param string $strSelecao
     * @param string $strTipo
     * @param string $onclick
     * @param bool $varTabIndex
     * @param InfraPagina $objInfraPagina
     * @param string $innerHtml
     * @param string $title
     * @param string $idParcial
     * @param bool $disabled
     * @return string
     */
    protected function criarBotaoPaginacao($jsTipo, $jsPag, $innerHtml, $title, $idParcial, $disabled)
    {
        $onclick = $this->criarJsOnclick($jsTipo, $jsPag);

        $strTabIndex = '';
        if ($this->varTabIndex === true) {
            $strTabIndex = 'tabindex="' . $this->objInfraPagina->getProxTabTabela() . '"';
        } elseif ($this->varTabIndex !== false && is_numeric($this->varTabIndex)) {
            $strTabIndex = 'tabindex="' . $this->varTabIndex . '"';
        }

        if ($disabled) {
            $onclick = '';
            $strDisabled = 'disabled="disabled"';
            $disabledClass = 'disabled';
        } else {
            $onclick = "onclick=\"$onclick\"";
            $strDisabled = '';
            $disabledClass = '';
        }

        $strSelecao = $this->strSelecao;
        $strTipo = $this->strTipo;
        return <<<html
                    <li class="page-item $disabledClass"
                        $strDisabled 
                        id="lnk{$strSelecao}{$idParcial}{$strTipo}">
                        <a href="javascript:void(0);" 
                           class="page-link pl-1 pr-1"
                           $onclick
                           title="$title" 
                           $strTabIndex>$innerHtml</a>
                    </li>
html;
    }
}