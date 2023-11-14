<?php


class IPThOrdenacaoBS4 extends AbstractIPThOrdenacao
{

    public function render()
    {
        $classeOrdAsc = $this->getClasseOrdenacaoAsc();
        $classeOrdDesc = $this->getClasseOrdenacaoDesc();

        $onclickAsc = $this->getOnclickAsc();
        $onclickDesc = $this->getOnclickDesc();

        $tabIndexAsc = $this->getTabIndex();
        $tabIndexDesc = $this->getTabIndex();

        $botaoOrdenacaoAsc = $this->criarBotaoOrdenacao(
            'arrow_drop_up',
            $classeOrdAsc,
            $onclickAsc,
            $tabIndexAsc,
            'Ascendente'
        );
        $botaoOrdenacaoDesc = $this->criarBotaoOrdenacao(
            'arrow_drop_down',
            $classeOrdDesc,
            $onclickDesc,
            $tabIndexDesc,
            'Descendente'
        );

        return <<<html
                <div class="d-flex  align-items-center">
                    <div class="mr-auto">$this->strRotulo</div>
                    <div class="flex-column align-self-end">
                        $botaoOrdenacaoAsc
                        $botaoOrdenacaoDesc
                    </div>
                </div>                
html;
    }

    protected function criarBotaoOrdenacao($materialIcon, $iconClass, $onclick, $tabIndex, $strTipoOrdenacao)
    {
        //todo transformar em classe
        return <<<html
            <a class="setas-ordenacao-bs4" $onclick $tabIndex title="Ordenar $this->strRotulo $strTipoOrdenacao" href="#">
                <i class="material-icons$iconClass">$materialIcon</i>
            </a>
html;
    }

    protected function getClasseOrdenacaoAsc()
    {
        return $this->getClasseOrdenacao($this->isOrdenacaoAscAtiva);
    }

    protected function getClasseOrdenacaoDesc()
    {
        return $this->getClasseOrdenacao($this->isOrdenacaoDescAtiva);
    }

    private function getClasseOrdenacao(bool $isActive)
    {
        return $isActive ? ' text-primary' : ' text-muted';
    }
}