<?php


class InfraPaginaRendererFactoryBS4 extends AbstractInfraPaginaRendererFactory
{
    public function getCaminhosRelativosCSS()
    {
        return ['infra_pagina_renderer/bs4/main.css'];
    }

    public function getCaminhosRelativosJS()
    {
        return ['infra_pagina_renderer/bs4/main.js'];
    }

    public function getAreaPaginacaoClass()
    {
        return IPAreaPaginacaoBS4::class;
    }

    public function getThOrdenacaoClass()
    {
        return IPThOrdenacaoBS4::class;
    }

    protected function getTrCheckClass()
    {
        return IPTrCheckBS4::class;
    }
}