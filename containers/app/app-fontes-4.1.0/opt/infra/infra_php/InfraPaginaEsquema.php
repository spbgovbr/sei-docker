<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/05/2006 - criado por MGA
 *
 * @package infra_php
 */


abstract class InfraPaginaEsquema extends InfraPagina
{

    public static $ESQUEMA_AZUL_CELESTE = 'azul_celeste';
    public static $ESQUEMA_CEREJA = 'cereja';
    public static $ESQUEMA_VERDE_LIMAO = 'verde_limao';
    public static $ESQUEMA_VERMELHO = 'vermelho';

    private $bolAutoRedimensionar = true;
    private $strEsquemaCores = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function getStrLogoSistema()
    {
        return null;
    }

    public function getStrTextoBarraTribunal()
    {
        return null;
    }

    public function getNumIdOrgaoBarraTribunal()
    {
        if ($this->getObjInfraSessao() != null) {
            return $this->getObjInfraSessao()->getNumIdOrgaoSistema();
        }
        return 1;
    }

    public function getStrEsquemaPadrao()
    {
        return self::$ESQUEMA_AZUL_CELESTE;
    }

    public function getDiretorioEsquemasGlobal()
    {
        return $this->getDiretorioCssGlobal() . '/esquemas';
    }

    public function getDiretorioEsquemasLocal()
    {
        return $this->getDiretorioCssLocal() . '/esquemas';
    }

    public function listarEsquemas()
    {
        return array(
            self::$ESQUEMA_AZUL_CELESTE => 'Azul Celeste',
            self::$ESQUEMA_CEREJA => 'Cereja',
            self::$ESQUEMA_VERDE_LIMAO => 'Verde Limão',
            self::$ESQUEMA_VERMELHO => 'Vermelho'
        );
    }

    public function getStrEsquemaCores()
    {
        if ($this->strEsquemaCores == null) {
            $arrEsquemas = $this->listarEsquemas();

            if ($this->getObjInfraSessao() != null && $this->getObjInfraSessao()->isBolHabilitada(
                ) && $this->getObjInfraSessao()->getObjInfraIBanco() != null) {
                if ($this->getObjInfraSessao()->getAtributo('infra_esquema_cores') != null) {
                    $this->strEsquemaCores = $this->getObjInfraSessao()->getAtributo('infra_esquema_cores');
                } else {
                    try {
                        $objInfraDadoUsuario = new InfraDadoUsuario($this->getObjInfraSessao());
                        $this->strEsquemaCores = $objInfraDadoUsuario->getValor('INFRA_ESQUEMA_CORES');
                    } catch (Exception $e) {
                    }
                }

                if ($this->strEsquemaCores != null && isset($arrEsquemas[$this->strEsquemaCores])) {
                    $this->getObjInfraSessao()->setAtributo('infra_esquema_cores', $this->strEsquemaCores);
                }
            }

            if ($this->strEsquemaCores == null && isset($_COOKIE[$this->getStrPrefixoCookie() . '_esquema_cores'])) {
                $this->strEsquemaCores = $_COOKIE[$this->getStrPrefixoCookie() . '_esquema_cores'];
            }

            if ($this->strEsquemaCores == null || !isset($arrEsquemas[$this->strEsquemaCores])) {
                $this->strEsquemaCores = $this->getStrEsquemaPadrao();
            }

            if ($this->strEsquemaCores == null || !isset($arrEsquemas[$this->strEsquemaCores])) {
                $this->strEsquemaCores = self::$ESQUEMA_AZUL_CELESTE;
            }
        }

        return $this->strEsquemaCores;
    }

    public function setBolAutoRedimensionar($bolAutoRedimensionar)
    {
        $this->bolAutoRedimensionar = $bolAutoRedimensionar;
    }

    public function getBolAutoRedimensionar()
    {
        return $this->bolAutoRedimensionar;
    }

    public abstract function getArrStrAcoesSistema();

    public function getArrStrAcoesBarraSistema()
    {
    }

    public function getArquivoCssGlobal()
    {
        return 'infra-global-esquema.css';
    }

    public function getArquivoCssMenuGlobal()
    {
        return 'menu-global-esquema.css';
    }

    public function getArquivoCssGlobalN()
    {
        return 'infra-global-esquema-1.css';
    }

    public function getArquivoCssEsquema()
    {
        return 'infra-esquema.css';
    }

    public function getArquivoCssEsquemaN()
    {
        return 'infra-esquema-1.css';
    }

    public function montarStyle()
    {
        $numVersao = $this->getNumVersao();
        $strDiretorioCssGlobal = $this->getDiretorioCssGlobal();
        $strDiretorioJavascriptGlobal = $this->getDiretorioJavaScriptGlobal();
        $numTipoBrowser = $this->getNumTipoBrowser();

        echo '<link href="' . $strDiretorioCssGlobal . '/' . $this->getArquivoCssGlobal() . '?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />
<link href="' . $strDiretorioCssGlobal . '/' . $this->getArquivoCssGlobalN(
            ) . '?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";

        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA && $this->getStrMenuSistema() != null) {
            if ($this->obterTipoMenu() == self::$MENU_NORMAL) {
                echo '<link href="' . $strDiretorioCssGlobal . '/' . $this->getArquivoCssMenuGlobal(
                    ) . '?' . $this->getNumVersao() . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
            } elseif ($this->adicionarJQuery()) {
                echo '<link href="' . $strDiretorioCssGlobal . '/smartmenu/sm-core-css.css?' . $this->getNumVersao(
                    ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
                    '<link href="' . $strDiretorioCssGlobal . '/smartmenu/sm-' . $this->obterSmartMenuClass(
                    ) . '/sm-' . $this->obterSmartMenuClass() . '.css?' . $this->getNumVersao(
                    ) . '" rel = "stylesheet" type = "text/css" media="all" />' . "\n";
            }
        }

        echo '<link href="' . $this->getDiretorioEsquemasGlobal() . '/' . $this->getStrEsquemaCores(
            ) . '/' . $this->getArquivoCssEsquema() . '?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />
<link href="' . $this->getDiretorioEsquemasGlobal() . '/' . $this->getStrEsquemaCores(
            ) . '/' . $this->getArquivoCssEsquemaN() . '?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />
<link href="' . $strDiretorioCssGlobal . '/infra-tooltip.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />
<link href="' . $strDiretorioCssGlobal . '/infra-barra-progresso.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />
<link href="' . $this->getDiretorioCssLocal(
            ) . '/infra-esquema-local.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";

        if ($numTipoBrowser == self::$TIPO_BROWSER_IE56 || $numTipoBrowser == self::$TIPO_BROWSER_IE7 || $numTipoBrowser == self::$TIPO_BROWSER_IE8) {
            echo '<link href="' . $strDiretorioCssGlobal . '/infra-impressao-global-ie.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="print" />' . "\n";
        } else {
            echo '<link href="' . $strDiretorioCssGlobal . '/infra-impressao-global.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="print" />' . "\n";
        }

        if ($this->isBolNavegadorSafariIpad()) {
            echo '<link href="' . $strDiretorioCssGlobal . '/infra-safari.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        }

        echo '<link href="' . $strDiretorioCssGlobal . '/infra-ajax.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />
<link href="' . $strDiretorioJavascriptGlobal . '/calendario/v' . $this->getNumVersaoCalendario(
            ) . '/infra-calendario.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />
<link href="' . $strDiretorioJavascriptGlobal . '/arvore/infra-arvore.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />
<link href="' . $strDiretorioJavascriptGlobal . '/mapa/infra-mapa.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />
';

        if ($numTipoBrowser == self::$TIPO_BROWSER_IE56) {
            echo '<link href="' . $strDiretorioCssGlobal . '/infra-ie56.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        } elseif ($numTipoBrowser == self::$TIPO_BROWSER_IE7) {
            echo '<link href="' . $strDiretorioCssGlobal . '/infra-ie7.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        }

        if ($this->adicionarJQuery()) {
            echo '<link href="' . $strDiretorioJavascriptGlobal . '/jquery/jquery-ui-' . $this->getVersaoJQueryUI(
                ) . '/jquery-ui.min.css?' . $this->getVersaoJQueryUI(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
                '<link href="' . $strDiretorioJavascriptGlobal . '/jquery/jquery-ui-' . $this->getVersaoJQueryUI(
                ) . '/jquery-ui.structure.min.css?' . $this->getVersaoJQueryUI(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
                '<link href="' . $strDiretorioJavascriptGlobal . '/jquery/jquery-ui-' . $this->getVersaoJQueryUI(
                ) . '/jquery-ui.theme.min.css?' . $this->getVersaoJQueryUI(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
                '<link href="' . $strDiretorioJavascriptGlobal . '/multiple-select/multiple-select.min.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
                '<link href="' . $strDiretorioJavascriptGlobal . '/modal/jquery.modalLink-1.0.0.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
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
        $this->montarBarraTribunal();

        echo '<div id="divInfraBarraSistemaLocalizacao" class="infraBarraSistemaLocalizacao">' . "\n";
        $this->montarBarraSistema();
        $this->montarBarraLocalizacao($strLocalizacao);

        echo '</div>' .
            '<div id="divInfraAreaTela" class="infraAreaTela">' . "\n";

        $strStyle = '';
        if ($this->getTipoPagina() != self::$TIPO_PAGINA_COMPLETA || $this->getStrMenuSistema(
            ) == null || $this->getStrCookieMenuMostrar() == 'N') {
            $strStyle = 'style="width:99%"';
        }
        echo '<div id="divInfraAreaTelaD" class="infraAreaTelaD" ' . $strStyle . '>' . "\n";
        $this->montarBarraAcesso();
    }

    private function montarBarraTribunal()
    {
        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA || $this->getTipoPagina(
            ) == self::$TIPO_PAGINA_SEM_MENU) {
            echo '<div id="divInfraBarraTribunal" class="infraBarraTribunal">' . "\n" .
                '<div id="divInfraBarraTribunalD" class="infraBarraTribunalD">' . "\n";

            $arrStrAcoes = $this->getArrStrAcoesSistema();
            if ($arrStrAcoes != null) {
                foreach ($arrStrAcoes as $acao) {
                    if (trim($acao) != '') {
                        echo '<div class="infraAcaoBarraSistema">&nbsp;' . $acao . '</div>' . "\n";
                    }
                }
            }

            echo '</div>' . "\n" .
                '<div id="divInfraBarraTribunalE" class="infraBarraTribunalE">' . "\n";

            $strTextoBarraTribunal = $this->getStrTextoBarraTribunal();

            if ($strTextoBarraTribunal == null) {
                switch ($this->getNumIdOrgaoBarraTribunal()) {
                    case 1:
                        echo '<a href="http://www.trf4.jus.br" target="_blank" title="Site do Tribunal Regional Federal da 4ª Região" tabindex="' . $this->getProxTabBarraTribunal(
                            ) . '">Tribunal Regional Federal da 4ª Região</a>' . "\n";
                        break;

                    case 2:
                        echo '<a href="http://www.jfrs.jus.br" target="_blank" title="Site da Justiça Federal do Rio Grande do Sul" tabindex="' . $this->getProxTabBarraTribunal(
                            ) . '">Seção Judiciária do Rio Grande do Sul</a>' . "\n";
                        break;

                    case 3:
                        echo '<a href="http://www.jfsc.jus.br" target="_blank" title="Site da Justiça Federal de Santa Catarina" tabindex="' . $this->getProxTabBarraTribunal(
                            ) . '">Seção Judiciária de Santa Catarina</a>' . "\n";
                        break;

                    case 4:
                        echo '<a href="http://www.jfpr.jus.br" target="_blank" title="Site da Justiça Federal do Paraná" tabindex="' . $this->getProxTabBarraTribunal(
                            ) . '">Seção Judiciária do Paraná</a>' . "\n";
                        break;

                    default:
                        if ($this->getObjInfraSessao() != null) {
                            echo '<label>' . self::tratarHTML(
                                    $this->getObjInfraSessao()->getStrDescricaoOrgaoSistema()
                                ) . '</label>' . "\n";
                        }
                }
            } else {
                echo($this->isBolTratarHtmlBarraTribunal() ? self::tratarHTML(
                    $strTextoBarraTribunal
                ) : $strTextoBarraTribunal);
            }

            echo '</div>' . "\n" .
                '</div>' . "\n";
        }
    }

    private function montarBarraSistema()
    {
        if (($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA && $this->getStrMenuSistema(
                ) != null) || $this->getTipoPagina() == self::$TIPO_PAGINA_SEM_MENU) {
            echo '<div id="divInfraBarraSistema" class="infraBarraSistema" style="height:4em;background: url(' . $this->getDiretorioEsquemasGlobal(
                ) . '/' . $this->getStrEsquemaCores() . '/bg_barra_sistema_conteudo.png' . ');">' . "\n" .
                '<div id="divInfraBarraSistemaE" class="infraBarraSistemaE">' . "\n";

            if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA) {
                echo $this->montarLinkMenu();
            }
            echo '</div>' . "\n";

            if ($this->getStrLogoSistema() !== null) {
                echo '<div id="divInfraBarraSistemaD" class="infraBarraSistemaD" style="top:.5em;">' . "\n" .
                    $this->getStrLogoSistema() .
                    '</div>' . "\n";
            } else {
                echo '<div id="divInfraBarraSistemaD" class="infraBarraSistemaD">' . "\n" .
                    '<label>' . self::tratarHTML($this->getStrNomeSistema()) . '</label>' .
                    '</div>' . "\n";
            }

            echo '</div>' . "\n" .
                '<div id="divInfraCurvaBarraSistema" class="infraCurvaBarraSistema">' .
                '<img src="' . $this->getDiretorioEsquemasGlobal() . '/' . $this->getStrEsquemaCores(
                ) . '/bg_barra_sistema_curva.png" />' .
                '</div>';
        }
    }

    public function montarBarraLocalizacao($strLocalizacao)
    {
        echo '<div id="divInfraBarraLocalizacao" class="infraBarraLocalizacao">' . $strLocalizacao . '</div>' . "\n";
    }

    public function montarBarraComandosSuperior($arrComandos)
    {
        echo '<input type="hidden" id="hdnInfraTipoPagina" name="hdnInfraTipoPagina" value="' . $this->getTipoPagina(
            ) . '" />' . "\n" .
            '<div id="divInfraBarraComandosSuperior" class="infraBarraComandos">' . "\n";

        if (is_array($arrComandos)) {
            foreach ($arrComandos as $comando) {
                if (trim($comando) != '') {
                    if (strpos($comando, 'tabindex') === false) {
                        $comando = str_replace(
                            ' type=',
                            ' tabindex="' . $this->getProxTabBarraComandosSuperior() . '" type=',
                            $comando
                        );
                    }
                    echo $comando . '&nbsp;' . "\n";
                }
            }
        }
        echo '</div>' . "\n";
    }

    public function montarBarraComandosInferior($arrComandos, $bolForcarMontagem = false)
    {
        if (!$this->bolMontouTabela || $this->numMaxRegistrosTab > 15 || $bolForcarMontagem) {
            echo '<div id="divInfraBarraComandosInferior" class="infraBarraComandos">' . "\n" . '<br />';
            if (is_array($arrComandos)) {
                foreach ($arrComandos as $comando) {
                    if (trim($comando) != '') {
                        if (strpos($comando, 'tabindex') === false) {
                            $comando = str_replace(
                                ' type=',
                                ' tabindex="' . $this->getProxTabBarraComandosInferior() . '" type=',
                                $comando
                            );
                        }
                        echo $comando . '&nbsp;' . "\n";
                    }
                }
            }
            echo '</div>' . "\n";
        } else {
            echo '<br /><br />';
        }
    }

    public function montarPaginaErro($strErro, $strDetalhes, $strTrace)
    {
        $this->setBolExibirMensagens(false);
        $this->montarDocType();
        $this->abrirHtml();
        $this->abrirHead();
        $this->montarMeta();
        $this->montarTitle($this->getStrNomeSistema());
        $this->montarStyle();
        $this->montarJavaScript();
        $this->abrirJavaScript();
        ?>
        function ocultarAvisos(){janela = window;do{janela.infraOcultarAviso();if (janela == window.top){break;}janela = janela.parent;}while(true);}
        <?php
        $this->fecharJavaScript();
        $this->fecharHead();
        $this->abrirBody('Erro', 'onload="ocultarAvisos();"');

        $arrComandos = array();

        if (!$this->isBolProducao()) {
            $arrComandos[] = $this->montarBotaoDetalhesExcecao();
        }

        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA || $this->getTipoPagina(
            ) == self::$TIPO_PAGINA_SEM_MENU) {
            $arrComandos[] = $this->montarBotaoVoltarExcecao();
        }

        $this->montarBarraComandosSuperior($arrComandos);

        echo '<div id="divInfraExcecao" class="infraExcecao">' .
            '<span class="infraExcecao">' . nl2br($strErro) . '</span>' .
            '</div>' . "\n";

        if (!$this->isBolProducao()) {
            $this->abrirAreaDados();

            //Validar Permissao para ver detalhes
            echo '<div id="divInfraDetalhesExcecao" class="infraDetalhesExcecao">' .
                '<span class="infraDetalhesExcecao">';

            if ($strDetalhes != '') {
                echo '<br /><br /><b>Detalhes:</b><br />' . nl2br(
                        self::tratarHTML(str_replace(',', ', ', $strDetalhes))
                    );
            }

            if ($strTrace != '') {
                $strTrace = str_replace("\\n", "", $strTrace);
                echo '<br /><br /><b>Trilha de Processamento:</b><br />' . nl2br(
                        self::tratarHTML(InfraString::limparParametrosPhp($strTrace))
                    );
            }

            echo '</span>';
            //Deixa área de debug dentro da div para só mostrar ao clicar
            $this->montarAreaDebug();
            echo '</div>';
            $this->fecharAreaDados();
        }

        $this->fecharBody();
        $this->fecharHtml();
        die;
    }

    public function montarBotaoDetalhesExcecao()
    {
        return '<input type="button" id="btnInfraDetalhesExcecao" name="btnInfraDetalhesExcecao" value="Exibir Detalhes" onclick="infraDetalhesExcecao();" class="infraButton" />';
    }

    public function montarBotaoVoltarExcecao()
    {
        return '<input id="btnInfraVoltarExcecao" name="btnInfraVoltarExcecao" type="button" value="Voltar" onclick="history.go(-1);" class="infraButton" />';
    }

    public function montarLinkConfiguracao($strLink = null, $strIcone = null)
    {
        if ($strLink == null) {
            $strLink = 'controlador.php?acao=infra_configurar';
        }

        $objInfraSessao = $this->getObjInfraSessao();
        if ($objInfraSessao != null) {
            $arrParametrosRepasseLink = $objInfraSessao->getArrParametrosRepasseLink();
            $objInfraSessao->setArrParametrosRepasseLink(null);

            $strLink = $this->getObjInfraSessao()->assinarLink($strLink);

            $objInfraSessao->setArrParametrosRepasseLink($arrParametrosRepasseLink);
        }

        if ($strIcone == null) {
            $strIcone = $this->getDiretorioImagensGlobal() . '/configuracao.gif';
        }

        return '<a id="lnkConfiguracaoSistema" href="' . $strLink . '" tabindex="' . $this->getProxTabBarraSistema(
            ) . '"><img src="' . $strIcone . '" title="Configurações do Sistema" alt="Configurações do Sistema" class="infraImg" /></a>';
    }

    public function montarLinkMenu()
    {
        $strLink = '<a id="lnkInfraMenuSistema" onclick="infraMenuSistemaEsquema(false, null, \'' . $this->getDiretorioImagensGlobal(
            ) . '\');" tabindex="' . $this->getProxTabBarraSistema() . '">';
        $strLink .= '<img id="imgInfraMenuSistema"';
        if ($this->getStrCookieMenuMostrar() == 'N') {
            $strLink .= ' src="' . $this->getDiretorioImagensGlobal(
                ) . '/botao_menu_abrir.gif" title="Exibir Menu do Sistema" alt="Exibir Menu do Sistema"';
        } else {
            $strLink .= ' src="' . $this->getDiretorioImagensGlobal(
                ) . '/botao_menu_fechar.gif" title="Ocultar Menu do Sistema" alt="Ocultar Menu do Sistema"';
        }
        $strLink .= ' class="infraImg" /></a>';
        return $strLink;
    }

    public function montarIdentificacaoUsuario($strSigla = null, $strOrgao = null, $strNome = null)
    {
        if ($strSigla === null && $strOrgao === null && $strNome === null) {
            if ($this->getObjInfraSessao() !== null) {
                if ($this->getObjInfraSessao()->getStrSiglaUsuario() != null) {
                    $strSigla = $this->getObjInfraSessao()->getStrSiglaUsuario();
                }

                if ($this->getObjInfraSessao()->getStrSiglaOrgaoUsuario() != null) {
                    $strOrgao = $this->getObjInfraSessao()->getStrSiglaOrgaoUsuario();
                }

                if ($this->getObjInfraSessao()->getStrNomeUsuario() != null) {
                    $strNome = $this->getObjInfraSessao()->getStrNomeUsuario();
                }
            }
        }

        $strDados = '';
        $strSeparador = '';

        if ($strNome !== null) {
            $strDados .= $strNome;
            $strSeparador = ' - ';
        }

        if ($strSigla !== null) {
            $strDados .= $strSeparador . $strSigla;
            $strSeparador = '/';
        }

        if ($strOrgao !== null) {
            $strDados .= $strSeparador . $strOrgao;
        }

        if ($strDados === '') {
            return '';
        }

        return '<span id="spanInfraUsuario" class="infraUsuario">' . $strDados . '</span>';
        //return '<div id="divInfraUsuario" class="infraUsuario">'.$strDados.'</div>';
        //return $strDados;
    }

    public function montarSelectUnidades()
    {
        return '<span id="spanInfraUnidade" class="infraUnidade">' . parent::montarSelectUnidades() . '</span>&nbsp;';
    }
}

