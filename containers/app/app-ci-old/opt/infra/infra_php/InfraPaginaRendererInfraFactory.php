<?php


class InfraPaginaRendererInfraFactory extends AbstractInfraPaginaRendererFactory
{
    public function getCaminhosRelativosCSS()
    {
        return array();
    }

    public function getCaminhosRelativosJS()
    {
        return array();
    }

    public function getAreaPaginacaoClass()
    {
        return 'IPAreaPaginacaoInfra';
    }

    public function getThOrdenacaoClass()
    {
        return 'IPThOrdenacaoInfra';
    }

    protected function getTrCheckClass()
    {
        return 'IPTrCheckInfra';
    }
}