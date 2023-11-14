<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/05/2013 - criado por MGA
 *
 * @package infra_php
 */

abstract class InfraPaginaEsquema2 extends InfraPaginaEsquema
{

    public function getStrTextoBarraSuperior()
    {
        return null;
    }

    public function getStrTextoBarraSistema()
    {
        return null;
    }

    public function getArquivoCssGlobalN()
    {
        return 'infra-global-esquema-2.css';
    }

    public function getArquivoCssEsquemaN()
    {
        return 'infra-esquema-2.css';
    }

    private function montarBarraSuperior()
    {
        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA || $this->getTipoPagina(
            ) == self::$TIPO_PAGINA_SEM_MENU) {
            echo '<div id="divInfraBarraSuperior" class="infraBarraSuperior">' . "\n";

            if ($this->getStrTextoBarraSuperior() != null) {
                echo '<label>' . self::tratarHTML(
                        InfraString::transformarCaixaAlta($this->getStrTextoBarraSuperior())
                    ) . '</label>' . "\n";
            } else {
                if ($this->getObjInfraSessao() != null) {
                    echo '<label>' . self::tratarHTML(
                            InfraString::transformarCaixaAlta($this->getObjInfraSessao()->getStrDescricaoOrgaoSistema())
                        ) . '</label>' . "\n";
                }
            }
            echo '</div>' . "\n";
        }
    }

    private function montarBarraSistema()
    {
        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA || $this->getTipoPagina(
            ) == self::$TIPO_PAGINA_SEM_MENU) {
            echo '<div id="divInfraBarraSistema" class="infraBarraSistema">' . "\n" .
                '<div id="divInfraBarraSistemaD" class="infraBarraSistemaD">' . "\n";

            $arrStrAcoes = $this->getArrStrAcoesSistema();
            if ($arrStrAcoes != null) {
                foreach ($arrStrAcoes as $acao) {
                    if (trim($acao) != '') {
                        echo '<div class="infraAcaoBarraSistema">&nbsp;' . $acao . '</div>' . "\n";
                    }
                }
            }

            echo '</div>' . "\n" .
                '<div id="divInfraBarraSistemaE" class="infraBarraSistemaE">' . "\n";

            //if ($this->getTipoPagina()==self::$TIPO_PAGINA_COMPLETA){
            //echo $this->montarLinkMenu();
            //}

            if ($this->getStrTextoBarraSistema() != null) {
                echo '<label>' . self::tratarHTML($this->getStrTextoBarraSistema()) . '</label>';
            } else {
                if ($this->getStrLogoSistema() !== null) {
                    echo $this->getStrLogoSistema() . "\n";
                } else {
                    if ($this->getObjInfraSessao() != null) {
                        echo '<label>' . self::tratarHTML(
                                $this->getObjInfraSessao()->getStrSiglaSistema()
                            ) . '</label>';
                    }
                }
            }
            echo '</div>' . "\n" .
                '</div>' . "\n";
        }
    }

    public function abrirBody($strLocalizacao = '', $strAtributos = '')
    {
        if (!$this->esconderMenuAutomaticamente()) {
            $strAtributos = $this->complementarAtributo($strAtributos, 'onload', 'infraProcessarMouseDown();');
        }

        //Esconder combos mostrar menu somente IE
        if ($this->getNumTipoBrowser() == self::$TIPO_BROWSER_IE56) {
            $strAtributos = $this->complementarAtributo($strAtributos, 'onload', 'infraProcessarMouseOver();');
        }

        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA && $this->getStrMenuSistema() != null) {
            $strAtributos = $this->complementarAtributo(
                $strAtributos,
                'onload',
                'infraMenuSistemaEsquema(true, null, \'' . $this->getDiretorioImagensGlobal() . '\');'
            );
        }

        if ($this->getBolAutoRedimensionar()) {
            $strAtributos = $this->complementarAtributo($strAtributos, 'onload', 'infraProcessarResize();');
        }

        //$strAtributos = $this->complementarAtributo($strAtributos,'onload','infraEfeitoImagens();');
        //$strAtributos = $this->complementarAtributo($strAtributos,'onload','infraEfeitoTabelas();');


        echo '<body ' . $strAtributos . '>' . "\n";

        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA || $this->getTipoPagina(
            ) == self::$TIPO_PAGINA_SEM_MENU) {
            echo '<div id="divInfraAreaGlobal" class="infraAreaGlobal">' . "\n";
        } else {
            echo '<div id="divInfraAreaGlobal" class="infraAreaGlobal" style="border:0px;">' . "\n";
        }
        $this->montarBarraSeguranca();
        $this->montarBarraSuperior();
        $this->montarBarraSistema();


        echo '<div id="divInfraAreaTela" class="infraAreaTela">' . "\n";

        $strStyle = '';
        if ($this->getTipoPagina() != self::$TIPO_PAGINA_COMPLETA || $this->getStrMenuSistema(
            ) == null || $this->getStrCookieMenuMostrar() == 'N') {
            $strStyle = 'style="width:99%"';
        }
        echo '<div id="divInfraAreaTelaD" class="infraAreaTelaD" ' . $strStyle . '>' . "\n";
        $this->montarBarraAcesso();
        $this->montarBarraLocalizacao($strLocalizacao);
    }
}

