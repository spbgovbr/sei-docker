<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 23/05/2006 - criado por MGA
 *
 * @package infra_php
 */


abstract class InfraPagina
{
    protected $bolExibirMensagens;
    private $numTipoBrowser = null;
    private $strMensagens;
    private $bolExibindoExcecao;
    private $numVersao;
    private $bolBarraProgresso;
    private $bolBarraProgresso2;
    private $tipoMenu;
    private $bolRequerHttps;
    private $bolPermitirIndexacaoRobos;
    /** @var AbstractInfraPaginaRendererFactory */
    private $infraPaginaRendererFactory;

    /**
     * @var array
     * @see imprimirVariaveisGlobaisJS
     * O formato é k => v, ou seja:
     * NOME_VAR = VALOR_VAR
     */
    protected $arrVariaveisGlobaisJS = array();

    private $numTabBarraTribunal;
    private $numTabBarraSistema;
    private $numTabBarraComandosSuperior;
    private $numTabMenu;
    private $numTabDados;
    private $numTabTabela;
    private $numTabBarraComandosInferior;

    private $numDivAreaDados = '';

    public static $MENU_NORMAL = 0;
    public static $MENU_SMART = 1;
    public static $MENU_BOOTSTRAP = 2;

    public static $INFRA_LUPA_POPUP = 1;
    public static $INFRA_LUPA_MODAL = 2;

    public static $INFRA_JANELA_POPUP = 1;
    public static $INFRA_JANELA_MODAL = 2;

    public static $TIPO_BROWSER_MOZILLA = 0;
    public static $TIPO_BROWSER_IE = 100;
    public static $TIPO_BROWSER_IE56 = 101;
    public static $TIPO_BROWSER_IE7 = 102;
    public static $TIPO_BROWSER_IE8 = 103;
    public static $TIPO_BROWSER_IE9 = 104;
    public static $TIPO_BROWSER_FF = 200;
    public static $TIPO_BROWSER_CHROME = 300;

    protected static $TAB_INI_BARRA_TRIBUNAL = 1;
    protected static $TAB_FIM_BARRA_TRIBUNAL = 50;
    protected static $TAB_INI_BARRA_SISTEMA = 51;
    protected static $TAB_FIM_BARRA_SISTEMA = 100;
    protected static $TAB_INI_MENU = 101;
    protected static $TAB_FIM_MENU = 449;
    protected static $TAB_BARRA_LOCALIZACAO = 450;
    protected static $TAB_INI_BARRA_COMANDOS_SUPERIOR = 451;
    protected static $TAB_FIM_BARRA_COMANDOS_SUPERIOR = 500;
    protected static $TAB_INI_DADOS = 501;
    protected static $TAB_FIM_DADOS = 1000;
    protected static $TAB_INI_TABELA = 1001;
    protected static $TAB_FIM_TABELA = 32700;
    protected static $TAB_INI_BARRA_COMANDOS_INFERIOR = 32701;
    protected static $TAB_FIM_BARRA_COMANDOS_INFERIOR = 32767;

    public static $TIPO_PAGINA_COMPLETA = 1;
    public static $TIPO_PAGINA_SIMPLES = 2;
    public static $TIPO_PAGINA_SEM_MENU = 3;

    public static $TIPO_SELECAO_NENHUM = 0;
    public static $TIPO_SELECAO_SIMPLES = 1;
    public static $TIPO_SELECAO_MULTIPLA = 2;

    protected static $POS_SES_SEL_TIPO = 0;
    protected static $POS_SES_SEL_DADOS = 1;
    protected static $POS_SES_SEL_ID_OBJECT = 2;
    protected static $POS_SES_SEL_ID_SELECT = 3;
    protected static $POS_SES_SEL_ID_HIDDEN = 4;
    protected static $POS_SES_SEL_ID_TEXT = 5;
    protected static $POS_SES_ORDENACAO_CAMPO = 6;
    protected static $POS_SES_ORDENACAO_TIPO = 7;
    protected static $POS_SES_ACAO_RETORNO = 8;
    protected static $POS_SES_CAMPOS_INTERFACE = 9;
    protected static $POS_SES_PAGINA_ATUAL = 10;
    protected static $POS_SES_MENU = 11;
    protected static $POS_SES_HASH_CRITERIOS = 12;
    protected static $POS_SES_MSG = 13;
    protected static $POS_SES_GRUPOS_SELECOES = 14;
    protected static $POS_SES_SEL_ID_TEXTAREA = 15;

    protected static $POS_SES_MSG_TIPO = 0;
    protected static $POS_SES_MSG_CONTEUDO = 1;

    private static $POS_SES_GRUPOS_SELECOES_ACAO = 0;
    private static $POS_SES_GRUPOS_SELECOES_ITEM_ID = 1;
    private static $POS_SES_GRUPOS_SELECOES_ITENS = 2;

    public static $TIPO_MSG_INFORMACAO = 1;
    public static $TIPO_MSG_AVISO = 2;
    public static $TIPO_MSG_ERRO = 4;

    public static $JQUERY_1_12_4 = '1.12.4';
    public static $JQUERY_3_4_1 = '3.4.1';
    public static $JQUERY_3_6_0 = '3.6.0';

    private $numTipoPagina = 0;
    private $numTipoSelecao = 0;

    protected $bolMontouTabela = false;
    protected $numMaxRegistrosTab = 0;

    private $arrSelecoes = null;

    private static $POS_SEL_ITEM_ID = 0;
    private static $POS_SEL_NRO_ITENS = 1;
    private static $POS_SEL_ITENS = 2;
    private static $POS_SEL_ITENS_SELECIONADOS = 3;
    private static $POS_SEL_SESSAO = 4;
    private static $POS_SEL_PAG_PAGINA_ATUAL = 5;
    private static $POS_SEL_PAG_HASH_CRITERIOS = 6;
    private static $POS_SEL_PAG_REGISTROS_POR_PAGINA = 7;
    private static $POS_SEL_PAG_PREPARAR = 8;
    private static $POS_SEL_PAG_TOTAL_REGISTROS = 9;
    private static $POS_SEL_PAG_REGISTROS_PAGINA_ATUAL = 10;
    private static $POS_SEL_PAG_PROCESSAR = 11;
    private static $POS_SEL_ORD_PREPARAR = 12;
    private static $POS_SEL_ORD_CAMPO = 13;
    private static $POS_SEL_ORD_TIPO = 14;
    private static $POS_SEL_NUM_AREA_TABELA = 15;
    private static $POS_SEL_TAB_INDEX = 16;

    public function setInfraPaginaRendererFactory(AbstractInfraPaginaRendererFactory $renderer)
    {
        $this->infraPaginaRendererFactory = $renderer;
    }

    /** @return AbstractInfraPaginaRendererFactory */
    public function getInfraPaginaRendererFactory()
    {
        if ($this->infraPaginaRendererFactory === null) {
            $this->setInfraPaginaRendererFactory(new InfraPaginaRendererInfraFactory());
        }

        return $this->infraPaginaRendererFactory;
    }


    abstract public function getStrNomeSistema();

    abstract public function getStrMenuSistema();

    abstract public function getArrStrAcoesBarraSistema();

    /**
     * @return InfraSessao
     */
    abstract public function getObjInfraSessao();

    abstract public function isBolProducao();

    public function isBolPermitirIndexacaoRobos()
    {
        return $this->bolPermitirIndexacaoRobos;
    }

    public function setBolPermitirIndexacaoRobos($bolPermitirIndexacaoRobos)
    {
        $this->bolPermitirIndexacaoRobos = $bolPermitirIndexacaoRobos;
    }

    public function isBolRequerHttps()
    {
        return $this->bolRequerHttps;
    }

    public function setBolRequerHttps($bolRequerHttps)
    {
        $this->bolRequerHttps = $bolRequerHttps;
    }

    public function isBolTratarHtmlBarraTribunal()
    {
        return true;
    }

    public function getObjInfraLog()
    {
        return null;
    }

    public function obterTipoMenu()
    {
        return self::$MENU_NORMAL;
    }

    public function obterTipoJanelaLupas()
    {
        return self::$INFRA_JANELA_POPUP;
    }

    public function obterTipoJanelaBarraProgresso()
    {
        return self::$INFRA_JANELA_POPUP;
    }

    public function obterSmartMenuClass()
    {
        return 'infra';
    }

    public function permitirXHTML()
    {
        return false;
    }

    public function validarHashTabelas()
    {
        return false;
    }

    public function esconderMenuAutomaticamente()
    {
        return false;
    }

    public function adicionarJQuery()
    {
        return true;
    }

    public function getVersaoJQuery()
    {
        return '3.6.1';
    }

    public function getVersaoJQueryUI()
    {
        return '1.13.2';
    }

    public function adicionarD3()
    {
        return false;
    }

    public function isBolMontarBarraAcesso()
    {
        return true;
    }

    public function obterTiposMensagemExibicao()
    {
        return self::$TIPO_MSG_INFORMACAO | self::$TIPO_MSG_AVISO | self::$TIPO_MSG_ERRO;
    }

    public function __construct()
    {
        $this->arrVariaveisGlobaisJS = array(
            'INFRA_PATH_CSS' => $this->getDiretorioCssGlobal(),
            'INFRA_PATH_IMAGENS' => $this->getDiretorioImagensGlobal(),
            'INFRA_PATH_JS' => $this->getDiretorioJavaScriptGlobal(),
            'INFRA_PATH_SVG' => $this->getDiretorioSvgGlobal(),
            'INFRA_LUPA_TIPO_JANELA' => $this->obterTipoJanelaLupas(),
            'INFRA_BARRA_TIPO_JANELA' => $this->obterTipoJanelaBarraProgresso()
        );

        $this->strMensagens = '';
        $this->bolExibindoExcecao = false;
        $this->bolBarraProgresso = false;
        $this->bolRequerHttps = true;
        $this->bolPermitirIndexacaoRobos = false;

        $this->setBolExibirMensagens(true);

        $this->arrSelecoes = array();

        $this->setNumTipoBrowser($this->verificarTipoBrowser());

        $this->tipoMenu = $this->obterTipoMenu();

        $this->numVersao = $this->gerarNumVersao();

        $this->numTabBarraTribunal = self::$TAB_INI_BARRA_TRIBUNAL;
        $this->numTabBarraSistema = self::$TAB_INI_BARRA_SISTEMA;
        $this->numTabBarraComandosSuperior = self::$TAB_INI_BARRA_COMANDOS_SUPERIOR;
        $this->numTabMenu = self::$TAB_INI_MENU;
        $this->numTabDados = self::$TAB_INI_DADOS;
        $this->numTabTabela = self::$TAB_INI_TABELA;
        $this->numTabBarraComandosInferior = self::$TAB_INI_BARRA_COMANDOS_INFERIOR;

        $this->setTipoPagina(self::$TIPO_PAGINA_COMPLETA);
        $this->setTipoSelecao(self::$TIPO_SELECAO_NENHUM);
        $this->salvarAcaoRetorno();
        $this->limparPost();
    }

    public function isBolNavegadorIE()
    {
        $numTB = $this->getNumTipoBrowser();
        return ($numTB == self::$TIPO_BROWSER_IE56 || $numTB == self::$TIPO_BROWSER_IE7 || $numTB == self::$TIPO_BROWSER_IE8 || $numTB == self::$TIPO_BROWSER_IE9 || $numTB == self::$TIPO_BROWSER_IE);
    }

    public function isBolNavegadorFirefox()
    {
        return ($this->getNumTipoBrowser() == self::$TIPO_BROWSER_FF);
    }

    public function isBolNavegadorChrome()
    {
        return ($this->getNumTipoBrowser() == self::$TIPO_BROWSER_CHROME);
    }

    public function isBolNavegadorSafari()
    {
        return (self::getNumVersaoSafari() !== null);
    }

    public function isBolNavegadorSafariIpad()
    {
        return (self::getNumVersaoSafariIpad() !== null);
    }

    public function isBolAjustarTopFieldset()
    {
        return $this->isBolNavegadorFirefox() || $this->isBolNavegadorSafari() || intval(
                self::getNumVersaoChrome()
            ) >= 88;
    }

    public function getStrPrefixoCookie()
    {
        $strPrefixoCookie = null;
        if ($this->getObjInfraSessao() != null) {
            $strPrefixoCookie = $this->getObjInfraSessao()->getStrPrefixoCookie();
        } else {
            $strPrefixoCookie = str_replace(' ', '_', $this->getStrNomeSistema());
        }
        return $strPrefixoCookie;
    }

    protected function getStrCookieMenuMostrar()
    {
        $ret = '';
        if (isset($_COOKIE[$this->getStrPrefixoCookie() . '_menu_mostrar'])) {
            $ret = $_COOKIE[$this->getStrPrefixoCookie() . '_menu_mostrar'];
        }
        return $ret;
    }

    public function getStrNomeCookiePrivado($strNome)
    {
        return $this->getStrPrefixoCookie() . '_' . $strNome;
    }

    public function getStrCookiePrivado($strNome)
    {
        if (!isset($_COOKIE[$this->getStrNomeCookiePrivado($strNome)])) {
            return null;
        }

        return $_COOKIE[$this->getStrNomeCookiePrivado($strNome)];
    }

    protected function getNumVersaoCalendario()
    {
        return 1;
    }

    private function limparPost()
    {
        if (count($_POST) > 0) {
            foreach (array_keys($_POST) as $key) {
                $strPrefixo = substr($key, 0, 3);
                if (($strPrefixo == 'txt' || $strPrefixo == 'txa' || $strPrefixo == 'hdn') && !is_array($_POST[$key])) {
                    if (strpos($_POST[$key], "\'") !== false) {
                        $_POST[$key] = str_replace("\'", '\'', $_POST[$key]);
                    }

                    if (strpos($_POST[$key], '\"') !== false) {
                        $_POST[$key] = str_replace('\"', '"', $_POST[$key]);
                    }

                    if (strpos($_POST[$key], "\\\\") !== false) {
                        $_POST[$key] = str_replace("\\\\", "\\", $_POST[$key]);
                    }
                }
            }
        }
    }

    private function gerarNumVersao()
    {
        if ($this->isBolProducao()) {
            if ($this->getNumVersaoCache() == null) {
                return VERSAO_INFRA;
            } else {
                return VERSAO_INFRA . '-' . $this->getNumVersaoCache();
            }
        }

        return VERSAO_INFRA . '-' . mt_rand();
    }

    protected function getNumVersao()
    {
        return $this->numVersao;
    }

    protected function getNumVersaoCache()
    {
        return null;
    }

    public function setTipoPagina($numTipoPagina)
    {
        if (!is_numeric($numTipoPagina) ||
            ($numTipoPagina != self::$TIPO_PAGINA_COMPLETA && $numTipoPagina != self::$TIPO_PAGINA_SIMPLES && $numTipoPagina != self::$TIPO_PAGINA_SEM_MENU)) {
            throw new InfraException('Tipo de página [' . $numTipoPagina . '] inválido.');
        }
        $this->numTipoPagina = $numTipoPagina;
    }

    public function getTipoPagina()
    {
        return $this->numTipoPagina;
    }

    public function setTipoSelecao($numTipoSelecao)
    {
        if (!is_numeric($numTipoSelecao) ||
            (($numTipoSelecao != self::$TIPO_SELECAO_NENHUM) &&
                ($numTipoSelecao != self::$TIPO_SELECAO_SIMPLES) &&
                ($numTipoSelecao != self::$TIPO_SELECAO_MULTIPLA))) {
            throw new InfraException('Tipo de seleção [' . $numTipoSelecao . '] inválida.');
        }
        $this->numTipoSelecao = $numTipoSelecao;
    }

    public function getTipoSelecao()
    {
        return $this->numTipoSelecao;
    }

    public function verificarTipoBrowser()
    {
        $numVersao = self::getNumVersaoInternetExplorer();
        if ($numVersao !== null) {
            if ($numVersao <= 6) {
                return self::$TIPO_BROWSER_IE56;
            } elseif ($numVersao == 7) {
                return self::$TIPO_BROWSER_IE7;
            } elseif ($numVersao == 8) {
                return self::$TIPO_BROWSER_IE8;
            } elseif ($numVersao == 9) {
                return self::$TIPO_BROWSER_IE9;
            } else {
                return self::$TIPO_BROWSER_IE;
            }
        }

        if (self::getNumVersaoFirefox() !== null) {
            return self::$TIPO_BROWSER_FF;
        }

        if (self::getNumVersaoChrome() !== null) {
            return self::$TIPO_BROWSER_CHROME;
        }

        $pos = strpos($_SERVER['HTTP_USER_AGENT'], 'Gecko');
        if ($pos !== false) {
            return self::$TIPO_BROWSER_MOZILLA;
        }

        return null;
    }

    public function isBolIpad()
    {
        return (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad;') !== false);
    }

    public function isBolAndroid()
    {
        return (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false);
    }

    public function isBolIphone()
    {
        return (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false);
    }

    public static function getNumVersaoFirefox($strUserAgent = null)
    {
        $strVersao = null;

        if ($strUserAgent == null) {
            $strUserAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        $posNavegador = strpos($strUserAgent, 'Firefox/');
        if ($posNavegador !== false) {
            $posFimVersao = strpos($strUserAgent, ' ', $posNavegador);
            if ($posFimVersao !== false) {
                $strVersao = substr(
                    $strUserAgent,
                    $posNavegador + strlen('Firefox/'),
                    $posFimVersao - ($posNavegador + strlen('Firefox/'))
                );
            } else {
                $strVersao = substr($strUserAgent, $posNavegador + strlen('Firefox/'));
            }
        }
        return self::formatarVersaoNavegador($strVersao);
    }

    public static function getNumVersaoChrome($strUserAgent = null)
    {
        $strVersao = null;

        if ($strUserAgent == null) {
            $strUserAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        $posNavegador = strpos($strUserAgent, 'Chrome/');

        if ($posNavegador !== false) {
            $posFimVersao = strpos($strUserAgent, ' ', $posNavegador);
            if ($posFimVersao !== false) {
                $strVersao = substr(
                    $strUserAgent,
                    $posNavegador + strlen('Chrome/'),
                    $posFimVersao - ($posNavegador + strlen('Chrome/'))
                );
            }
        }
        return self::formatarVersaoNavegador($strVersao);
    }

    public static function getNumVersaoEdge($strUserAgent = null)
    {
        $strVersao = null;

        if ($strUserAgent == null) {
            $strUserAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        $re = "/Edge\\/(\\d+(.\\d+)*)/";

        if (preg_match($re, $strUserAgent, $matches) != 0) {
            $strVersao = $matches[1][0];
        }
        return self::formatarVersaoNavegador($strVersao);
    }

    public static function getNumVersaoInternetExplorer($strUserAgent = null)
    {
        $strVersao = null;

        if ($strUserAgent == null) {
            $strUserAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        $posNavegador = strpos($strUserAgent, 'MSIE ');
        if ($posNavegador !== false) {
            $posFimVersao = strpos($strUserAgent, ';', $posNavegador);
            if ($posFimVersao !== false) {
                $strVersao = substr(
                    $strUserAgent,
                    $posNavegador + strlen('MSIE '),
                    $posFimVersao - ($posNavegador + strlen('MSIE '))
                );
            }
        } elseif (strpos($strUserAgent, 'Trident/') !== false) {
            $posNavegador = strpos($strUserAgent, 'rv:');

            $posFimVersao1 = strpos($strUserAgent, ';', $posNavegador);
            $posFimVersao2 = strpos($strUserAgent, ')', $posNavegador);
            $posFimVersao3 = strpos($strUserAgent, ' ', $posNavegador);

            $numTamUA = strlen($strUserAgent);
            if ($posFimVersao1 === false) {
                $posFimVersao1 = $numTamUA;
            }
            if ($posFimVersao2 === false) {
                $posFimVersao2 = $numTamUA;
            }
            if ($posFimVersao3 === false) {
                $posFimVersao3 = $numTamUA;
            }

            $posFimVersao = $numTamUA;
            if ($posFimVersao1 < $posFimVersao2 && $posFimVersao1 < $posFimVersao3) {
                $posFimVersao = $posFimVersao1;
            } elseif ($posFimVersao2 < $posFimVersao1 && $posFimVersao2 < $posFimVersao3) {
                $posFimVersao = $posFimVersao2;
            } elseif ($posFimVersao3 < $posFimVersao1 && $posFimVersao3 < $posFimVersao2) {
                $posFimVersao = $posFimVersao3;
            }

            $strVersao = substr($strUserAgent, $posNavegador + 3, $posFimVersao - ($posNavegador + 3));
        }
        return self::formatarVersaoNavegador($strVersao);
    }

    public static function getNumVersaoSafariIpad($strUserAgent = null)
    {
        $strVersao = null;

        if ($strUserAgent == null) {
            $strUserAgent = $_SERVER['HTTP_USER_AGENT'];
        }

        //Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10
        //Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X; pt-br) AppleWebKit/534.46.0 (KHTML, like Gecko) CriOS/21.0.1180.82 Mobile/10A403 Safari/7534.48.3

        if (strpos($strUserAgent, 'iPad;') !== false) {
            $posNavegador = strpos($strUserAgent, 'Version/');
            if ($posNavegador !== false) {
                $posFimVersao = strpos($strUserAgent, ' ', $posNavegador);
                if ($posFimVersao !== false) {
                    $strVersao = substr(
                        $strUserAgent,
                        $posNavegador + strlen('Version/'),
                        $posFimVersao - ($posNavegador + strlen('Version/'))
                    );
                }
            }
        }
        return self::formatarVersaoNavegador($strVersao);
    }

    public static function getNumVersaoSafari($strUserAgent = null)
    {
        $strVersao = null;

        if ($strUserAgent == null) {
            $strUserAgent = $_SERVER['HTTP_USER_AGENT'];
        }
        //Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2
        //Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_2 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B146 Safari/8536.25

        if (strpos($strUserAgent, 'iPad;') === false && strpos($strUserAgent, 'Chrome/') === false && strpos(
                $strUserAgent,
                'Safari/'
            ) !== false) {
            $posNavegador = strpos($strUserAgent, 'Version/');
            if ($posNavegador !== false) {
                $strVersao = substr($strUserAgent, $posNavegador + strlen('Version/'));

                $posFimVersao = strpos($strVersao, ' ');
                if ($posFimVersao !== false) {
                    $strVersao = substr($strVersao, 0, $posFimVersao);
                }
            }
        }
        return self::formatarVersaoNavegador($strVersao);
    }

    private static function formatarVersaoNavegador($strVersao)
    {
        if ($strVersao != null) {
            $arrVersao = explode('.', $strVersao);
            if (isset($arrVersao[0], $arrVersao[1])) {
                $strVersao = $arrVersao[0] . '.' . $arrVersao[1];
            }
        }
        return $strVersao;
    }

    public function isBolAjustarCSS()
    {
        $numVersao = self::getNumVersaoInternetExplorer();
        if ($numVersao !== null && $numVersao < 9) {
            return true;
        }

        $numVersao = self::getNumVersaoFirefox();
        if ($numVersao !== null && $numVersao < 10) {
            return true;
        }

        return false;
    }

    public function isBolBrowserXHTML()
    {
        $ret = false;

        $matches = null;
        if (preg_match('/application\/xhtml\+xml(;q=(\d+\.\d+))?/i', $_SERVER['HTTP_ACCEPT'], $matches)) {
            $xhtmlQ = isset($matches[2]) ? $matches[2] : 1;
            if (preg_match('/text\/html(;q=(\d+\.\d+))?/i', $_SERVER['HTTP_ACCEPT'], $matches)) {
                $htmlQ = isset($matches[2]) ? $matches[2] : 1;
                $ret = ($xhtmlQ >= $htmlQ);
            } else {
                $ret = true;
            }
        }
        return $ret;
    }

    public function setBolXHTML($bolXHTML)
    {
    }

    public function isBolXHTML()
    {
        return false;
    }

    public function formatarXHTML($strLink)
    {
        return $strLink;
    }

    private function setNumTipoBrowser($numTipoBrowser)
    {
        $this->numTipoBrowser = $numTipoBrowser;
    }

    public function getNumTipoBrowser()
    {
        return $this->numTipoBrowser;
    }

    private function montarMenuSistema()
    {
        if ($this->obterTipoMenu() != self::$MENU_BOOTSTRAP) {
            if ($this->obterTipoMenu() == self::$MENU_NORMAL) {
                echo '<div id="divInfraMenu" class="infraMenu">' . "\n" .
                    $this->getStrMenuSistema() .
                    '</div>' . "\n";
            } else {
                echo $this->getStrMenuSistema();
            }
        } else {
            echo $this->getStrMenuSistema();
        }
    }

    public function getArquivoCssGlobal()
    {
        return 'infra-global.css';
    }

    public function getArquivoCssMenuGlobal()
    {
        return 'menu-global.css';
    }

    public function montarDocType()
    {
        $this->montarHeader('Content-Type: text/html; charset=iso-8859-1');
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">' . "\n";
    }

    public function abrirHtml($strAtributos = '')
    {
        echo '<html lang="pt-br" ' . $strAtributos . '>' . "\n";
    }

    public function abrirHead($strAtributos = '')
    {
        echo '<head ' . $strAtributos . '>' . "\n";
    }

    public function montarTitle($strTitulo)
    {
        echo '<title>' . $strTitulo . '</title>' . "\n";
    }

    public function montarMeta()
    {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />' . "\n";

        if (!$this->isBolPermitirIndexacaoRobos()) {
            echo '<meta name="robots" content="noindex" />' . "\n";
        }

        if ($this->isBolIphone() || $this->isBolIpad()) {
            echo '<meta name="format-detection" content="telephone=no" />' . "\n";
        }
    }

    public function montarStyle()
    {
        $numVersao = $this->getNumVersao();
        $strDiretorioCssGlobal = $this->getDiretorioCssGlobal();
        $strDiretorioJavascriptGlobal = $this->getDiretorioJavaScriptGlobal();

        echo '<link href="' . $strDiretorioCssGlobal . '/' . $this->getArquivoCssGlobal(
            ) . '?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
            '<link href="' . $strDiretorioCssGlobal . '/infra-tooltip.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
            '<link href="' . $strDiretorioCssGlobal . '/infra-barra-progresso.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
            '<link href="' . $this->getDiretorioCssLocal(
            ) . '/infra-local.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";

        if ($this->isBolNavegadorIE()) {
            echo '<link href="' . $strDiretorioCssGlobal . '/infra-impressao-global-ie.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="print" />' . "\n";
        } else {
            echo '<link href="' . $strDiretorioCssGlobal . '/infra-impressao-global.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="print" />' . "\n";
        }

        echo '<link href="' . $strDiretorioCssGlobal . '/infra-ajax.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
            '<link href="' . $strDiretorioJavascriptGlobal . '/calendario/v' . $this->getNumVersaoCalendario(
            ) . '/infra-calendario.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
            '<link href="' . $strDiretorioJavascriptGlobal . '/arvore/infra-arvore.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
            '<link href="' . $strDiretorioJavascriptGlobal . '/mapa/infra-mapa.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";

        if ($this->obterTipoMenu() != self::$MENU_BOOTSTRAP) {
            if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA && $this->getStrMenuSistema() != null) {
                if ($this->obterTipoMenu() == self::$MENU_NORMAL) {
                    echo '<link href="' . $strDiretorioCssGlobal . '/' . $this->getArquivoCssMenuGlobal(
                        ) . '?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
                        '<link href="' . $this->getDiretorioCssLocal(
                        ) . '/menu-local.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
                } elseif ($this->adicionarJQuery()) {
                    echo '<link href="' . $strDiretorioCssGlobal . '/smartmenu/sm-core-css.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n" .
                        '<link href="' . $strDiretorioCssGlobal . '/smartmenu/sm-' . $this->obterSmartMenuClass(
                        ) . '/sm-' . $this->obterSmartMenuClass(
                        ) . '.css" rel = "stylesheet" type = "text/css" media="all" />' . "\n";
                }
            }
        }
        if ($this->getNumTipoBrowser() == self::$TIPO_BROWSER_IE56) {
            echo '<link href="' . $strDiretorioCssGlobal . '/infra-ie56.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        } elseif ($this->getNumTipoBrowser() == self::$TIPO_BROWSER_IE7) {
            echo '<link href="' . $strDiretorioCssGlobal . '/infra-ie7.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        } elseif ($this->isBolNavegadorSafariIpad()) {
            echo '<link href="' . $strDiretorioCssGlobal . '/infra-safari.css?' . $numVersao . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
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

        foreach ($this->getInfraPaginaRendererFactory()->getCaminhosRelativosCSS() as $style) {
            echo "<link href=\"$strDiretorioCssGlobal/$style?$numVersao\" rel=\"stylesheet\" type=\"text/css\" media=\"all\" />\n";
        }
    }

    public function abrirStyle($strAtributos = '')
    {
        echo '<style type="text/css" ' . $strAtributos . '>' . "\n" .
            '<!--/*--><![CDATA[/*><!--*/' . "\n";
    }

    public function fecharStyle()
    {
        echo '/*]]>*/-->' . "\n" .
            '</style>' . "\n";
    }

    public function abrirStyleIE($strCondicao = 'if IE', $strAtributos = '')
    {
        echo '<!--[' . $strCondicao . ']>' . "\n" .
            '<style type="text/css" ' . $strAtributos . '>' . "\n";
    }

    public function fecharStyleIE()
    {
        echo '</style>' . "\n" .
            '<![endif]-->' . "\n";
    }

    public function abrirStyleCH($strAtributos = '')
    {
        echo '<style type="text/css" ' . $strAtributos . '>' . "\n" .
            '<!--/*--><![CDATA[/*><!--*/' . "\n" .
            '@media screen and (-webkit-min-device-pixel-ratio:0){';
    }

    public function fecharStyleCH()
    {
        echo '}' .
            '/*]]>*/-->' . "\n" .
            '</style>' . "\n";
    }

    public function adicionarStyle($strHref, $strMedia = 'all')
    {
        echo '<link href="' . $strHref . '?' . $this->getNumVersao(
            ) . '" rel="stylesheet" type="text/css" media="' . $strMedia . '" />' . "\n";
    }

    public function adicionarJavaScript($strSrc)
    {
        echo '<script type="text/javascript" charset="iso-8859-1" src="' . $strSrc . '?' . $this->getNumVersao(
            ) . '"></script>' . "\n";
    }

    public function montarJavaScript()
    {
        $strDiretorioJavascriptGlobal = $this->getDiretorioJavaScriptGlobal();
        $numVersao = $this->getNumVersao();

        $this->imprimirVariaveisGlobaisJS();

        if ($this->adicionarJQuery()) {
            echo '<script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/jquery/jquery-' . $this->getVersaoJQuery(
                ) . '.min.js?' . $this->getVersaoJQuery() . '"></script>
<script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/jquery/jquery-ui-' . $this->getVersaoJQueryUI(
                ) . '/jquery-ui.min.js?' . $this->getVersaoJQueryUI() . '"></script>
<script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/multiple-select/multiple-select.min.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/ddslick/jquery.ddslick.min.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/modal/jquery.modalLink-1.0.0.js?' . $numVersao . '"></script>
';
        }

        if ($this->obterTipoMenu() == self::$MENU_SMART) {
            echo '<script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/smartmenu/jquery.smartmenus.js"></script>
<script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/smartmenu/addons/keyboard/jquery.smartmenus.keyboard.js"></script>
';
        }

        echo '<script type="text/javascript" charset="iso-8859-1" src="' . $this->getDiretorioJavaScriptGlobal(
            ) . '/' . $this->getArquivoJavaScriptPagina() . '?' . $this->getNumVersao() . '"></script>
';

        if ($this->adicionarD3()) {
            echo '<script type="text/javascript" charset="utf-8" src="' . $strDiretorioJavascriptGlobal . '/D3/d3.v3.min.js"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/mapa/InfraMapa.js?' . $numVersao . '"></script>
';
        }

        if ($this->obterTipoMenu() != self::$MENU_BOOTSTRAP) {
            echo '<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraMenu.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraAcaoMenu.js?' . $numVersao . '"></script>
';
        }

        echo '<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraBotaoMenu.js?' . $numVersao . '"></script>    
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraUtil.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraCookie.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraUpload.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraTabelaDinamica.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraLupas.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraSelectEditavel.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraAjax.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraTooltip.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/calendario/v' . $this->getNumVersaoCalendario(
            ) . '/InfraCalendario.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/arvore/InfraArvore.js?' . $numVersao . '"></script>
<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/maskedpwd/MaskedPassword.min.js?' . $numVersao . '"></script>
';

        if ($this->getNumTipoBrowser() == self::$TIPO_BROWSER_IE56) {
            echo '<script type="text/javascript" charset="iso-8859-1" src="' . $strDiretorioJavascriptGlobal . '/InfraIE56.js?' . $numVersao . '"></script>';
        }

        if ($this->adicionarJQuery() && $this->getVersaoJQuery() == self::$JQUERY_1_12_4) {
            echo '<script type="text/javascript">
jQuery.ajaxPrefilter( function( s ) {
  if ( s.crossDomain ) {
    s.contents.script = false;
  }
} );
</script>
';
            foreach ($this->getInfraPaginaRendererFactory()->getCaminhosRelativosJS() as $script) {
                echo "<script type=\"text/javascript\" charset=\"iso-8859-1\" src=\"$strDiretorioJavascriptGlobal/$script?$numVersao\"></script>";
            }
        }

        if ($this->tipoMenu == self::$MENU_SMART && $this->adicionarJQuery()) {
            echo '<script type="text/javascript">
$(function() {
	$(\'#main-menu\').smartmenus({
    subMenusSubOffsetX: 1,
    subMenusSubOffsetY: -8
  });

  //MENU CTRL+ALT+F9 
  $(\'#main-menu\').smartmenus(\'keyboardSetHotkey\', 120, [\'ctrlKey\', \'altKey\']);
});
</script>
';
        }
    }

    /**
     * @return void
     */
    protected function imprimirVariaveisGlobaisJS()
    {
        $arrVars = array();
        foreach ($this->arrVariaveisGlobaisJS as $k => $v) {
            $arrVars[] = $k . ' = ' . $this->formatarVariavel($v);
        }
        $strVars = "\nvar " . implode(",\n    ", $arrVars) . ";\n";

        echo "<script type=\"text/javascript\" charset=\"iso-8859-1\">$strVars</script>\n";
    }

    /**
     * @param string|bool|int $v
     * @return string
     */
    private function formatarVariavel($v)
    {
        if (is_string($v)) {
            return '"' . str_replace('"', '\"', $v) . '"';
        } elseif (is_numeric($v)) {
            return $v;
        } else { //boolean
            return $v === true ? 'true' : 'false';
        }
    }

    public function abrirJavaScript($strAtributos = '')
    {
        echo '
<script type="text/javascript" charset="iso-8859-1" ' . $strAtributos . '>
<!--//--><![CDATA[//><!--

';
    }

    public function fecharJavaScript()
    {
        echo '
//--><!]]>
</script>
';
    }

    public function fecharHead()
    {
        echo '</head>
';
    }

    public function setBolExibirMensagens($bolExibirMensagens)
    {
        $this->bolExibirMensagens = $bolExibirMensagens;
    }

    public function getBolExibirMensagens()
    {
        return $this->bolExibirMensagens;
    }

    public function abrirBody($strAtributos = '')
    {
        //if ($this->getStrMenuSistema() != null && $this->numTipoPagina!==self::$TIPO_PAGINA_SIMPLES){
        if (!$this->esconderMenuAutomaticamente()) {
            $strAtributos = $this->complementarAtributo($strAtributos, 'onload', 'infraProcessarMouseDown();');
        }

        //Esconder combos mostrar menu somente IE
        if ($this->getNumTipoBrowser() == self::$TIPO_BROWSER_IE56) {
            $strAtributos = $this->complementarAtributo($strAtributos, 'onload', 'infraProcessarMouseOver();');
        }
        //}

        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA && $this->getStrMenuSistema() != null) {
            $strAtributos = $this->complementarAtributo($strAtributos, 'onload', 'infraMenuSistema(true);');
        }

        //$strAtributos = $this->complementarAtributo($strAtributos,'onload','infraEfeitoImagens();');
        //$strAtributos = $this->complementarAtributo($strAtributos,'onload','infraEfeitoTabelas();');


        echo '<body ' . $strAtributos . '>' . "\n";

        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA || $this->getTipoPagina(
            ) == self::$TIPO_PAGINA_SEM_MENU) {
            echo '<div id="divInfraAreaGlobal" class="infraAreaGlobal">' . "\n";
        } else {
            echo '<div id="divInfraAreaGlobal" class="infraAreaGlobal" style="border:0;">' . "\n";
        }

        $this->montarBarraSeguranca();
        $this->montarBarraTribunal();
        $this->montarBarraSistema();


        echo '<div id="divInfraAreaTela" class="infraAreaTela">' . "\n";

        $strStyle = '';
        if ($this->getTipoPagina() != self::$TIPO_PAGINA_COMPLETA || $this->getStrMenuSistema(
            ) == null || $this->getStrCookieMenuMostrar() == 'N') {
            $strStyle = 'style="width:99%"';
        }
        echo '<div id="divInfraAreaTelaD" class="infraAreaTelaD" ' . $strStyle . '>' . "\n";
        $this->montarBarraAcesso();
    }

    protected function montarBarraSeguranca()
    {
        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA || $this->getTipoPagina(
            ) == self::$TIPO_PAGINA_SEM_MENU) {
            if ($this->isBolRequerHttps() && !(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')) {
                echo '<div id="divInfraBarraSeguranca" class="infraBarraSeguranca"><span>ATENÇÃO: Esta conexão não é segura!</span></div>';
            }
        }
    }

    protected function montarBarraAcesso()
    {
        if ($this->isBolMontarBarraAcesso() && $this->getObjInfraSessao() != null && $this->getObjInfraSessao(
            )->getDthUltimoAcesso() != null && $this->getObjInfraSessao()->getAtributo('infra_ultimo_acesso') == null) {
            $strTexto = InfraData::formatarExtenso4($this->getObjInfraSessao()->getDthUltimoAcesso());
            $strTexto = 'Último acesso ' . (strpos(
                    $strTexto,
                    'feira'
                ) != null ? 'na' : 'no') . ' ' . InfraString::transformarCaixaBaixa($strTexto);

            if ($this->getObjInfraSessao()->verificarPermissao('infra_acesso_usuario_listar')) {
                $strTexto = '<a title="Ver últimos acessos" href="' . $this->getObjInfraSessao()->assinarLink(
                        'controlador.php?acao=infra_acesso_usuario_listar'
                    ) . '">' . $strTexto . '</a>';
            }

            echo '<div id="divInfraBarraAcesso" class="infraBarraAcesso"><span>' . $strTexto . '</span></div>' . "\n";

            $this->getObjInfraSessao()->setAtributo(
                'infra_ultimo_acesso',
                $this->getObjInfraSessao()->getDthUltimoAcesso()
            );
        }
    }

    private function montarBarraTribunal()
    {
        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA || $this->getTipoPagina(
            ) == self::$TIPO_PAGINA_SEM_MENU) {
            $linkTRF = '<a href="http://www.trf4.gov.br" target="_blank" title="Site do Tribunal Regional Federal da 4ª Região" tabindex="' . $this->getProxTabBarraTribunal(
                ) . '">Tribunal Regional Federal da 4ª Região</a>' . "\n";
            $linkPR = '<a href="http://www.jfpr.gov.br" target="_blank" title="Site da Justiça Federal do Paraná" tabindex="' . $this->getProxTabBarraTribunal(
                ) . '">PR</a>' . "\n";
            $linkRS = '<a href="http://www.jfrs.gov.br" target="_blank" title="Site da Justiça Federal do Rio Grande do Sul" tabindex="' . $this->getProxTabBarraTribunal(
                ) . '">RS</a>' . "\n";
            $linkSC = '<a href="http://www.jfsc.gov.br" target="_blank" title="Site da Justiça Federal de Santa Catarina" tabindex="' . $this->getProxTabBarraTribunal(
                ) . '">SC</a>' . "\n";

            echo '<div id="divInfraBarraTribunal" class="infraBarraTribunal">' . "\n" .
                '<div id="divInfraBarraTribunalD" class="infraBarraTribunalD">' . "\n" .
                $linkPR .
                '<label>&nbsp;|&nbsp;</label>' .
                $linkRS .
                '<label>&nbsp;|&nbsp;</label>' .
                $linkSC .
                '</div>' . "\n" .
                '<div id="divInfraBarraTribunalE" class="infraBarraTribunalE">' . "\n" .
                $linkTRF .
                '</div>' . "\n" .
                '</div>' . "\n";
        }
    }

    private function montarBarraSistema()
    {
        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA || $this->getTipoPagina(
            ) == self::$TIPO_PAGINA_SEM_MENU) {
            echo '<div id="divInfraBarraSistema" class="infraBarraSistema">' . "\n" .
                '<div id="divInfraBarraSistemaD" class="infraBarraSistemaD">' . "\n";
            $arrStrAcoes = $this->getArrStrAcoesBarraSistema();
            if ($arrStrAcoes != null) {
                foreach ($arrStrAcoes as $acao) {
                    echo $acao . "\n";
                }
            }
            echo '</div>' . "\n" .
                '<div id="divInfraBarraSistemaE" class="infraBarraSistemaE">' . "\n" .
                '<label>' . self::tratarHTML($this->getStrNomeSistema()) . '</label>' . "\n" .
                '</div>' . "\n" .
                '</div>' . "\n";
        }
    }

    public function montarBarraLocalizacao($strLocalizacao)
    {
        echo '<div id="divInfraBarraLocalizacao" class="infraBarraLocalizacao">' . $strLocalizacao . '<hr /></div>' . "\n";
    }

    public function montarBarraComandosSuperior($arrComandos)
    {
        echo '<input type="hidden" id="hdnInfraTipoPagina" name="hdnInfraTipoPagina" value="' . $this->getTipoPagina(
            ) . '" />' . "\n" .
            '<div id="divInfraBarraComandosSuperior" class="infraBarraComandos">' . "\n";
        //echo '<hr />'."\n";
        if (is_array($arrComandos)) {
            foreach ($arrComandos as $comando) {
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
        echo '</div>' . "\n";
    }

    public function montarBarraComandosInferior($arrComandos, $bolForcarMontagem = false)
    {
        if (!$this->bolMontouTabela || $this->numMaxRegistrosTab > 15 || $bolForcarMontagem) {
            echo '<div id="divInfraBarraComandosInferior" class="infraBarraComandos">' . "\n" .
                '<hr />' . "\n";
            if (is_array($arrComandos)) {
                foreach ($arrComandos as $comando) {
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
            echo '</div>' . "\n";
        }
    }

    public function montarAreaValidacao()
    {
        $strMensagens = $this->getStrMensagens();
        if ($strMensagens != '') {
            echo '<div id="divInfraAreaValidacao" class="infraAreaValidacao">' . "\n" .
                str_replace('\\n', '<br />', $strMensagens) . "\n" .
                '</div>' . "\n";
        }
    }

    public function montarAreaDebug()
    {
        $strDebug = InfraDebug::getInstance()->ler();
        if ($strDebug != '') {
            echo '<div id="divInfraAreaDebug" class="infraAreaDebug">' . "\n" .
                '<br /><br /><b>Debug:</b>';
            $strDebug = self::tratarHTML($strDebug) . "\n";

            $numTipoBrowser = $this->getNumTipoBrowser();

            if ($numTipoBrowser != self::$TIPO_BROWSER_FF &&
                $numTipoBrowser != self::$TIPO_BROWSER_CHROME &&
                $numTipoBrowser != self::$TIPO_BROWSER_MOZILLA &&
                $numTipoBrowser != self::$TIPO_BROWSER_IE8) {
                $strDebug = nl2br($strDebug) . "\n";
            }

            if ($numTipoBrowser == self::$TIPO_BROWSER_FF ||
                $numTipoBrowser == self::$TIPO_BROWSER_CHROME ||
                $numTipoBrowser == self::$TIPO_BROWSER_MOZILLA) {
                $strDebug = str_replace(',', ',<wbr />', $strDebug);
            }
            echo $strDebug .
                '</div>' . "\n";
        }
    }

    public function abrirAreaDados($cssHeight = null, $strAtributos = '')
    {
        if ($cssHeight === null) {
            echo '<div id="divInfraAreaDadosDinamica' . $this->numDivAreaDados . '" class="infraAreaDadosDinamica" ' . $strAtributos . '>' . "\n";
        } else {
            //echo '<div id="divInfraAreaDados'.$this->numDivAreaDados.'" class="infraAreaDados" style="height:'.$cssHeight.';" '.$strAtributos.'>'."\n";
            $strAtributos = $this->complementarAtributo($strAtributos, 'style', 'height:' . $cssHeight . ';');
            echo '<div id="divInfraAreaDados' . $this->numDivAreaDados . '" class="infraAreaDados" ' . $strAtributos . '>' . "\n";
        }
        if ($this->numDivAreaDados === '') {
            $this->numDivAreaDados = 1;
        } else {
            $this->numDivAreaDados++;
        }
    }

    public function fecharAreaDados()
    {
        echo '</div>' . "\n";
    }

    public function abrirAreaTabela($strSelecao = 'Infra')
    {
        echo '<div id="div' . $strSelecao . 'AreaTabela' . $this->getProxNumAreaTabela(
                $strSelecao
            ) . '" class="infraAreaTabela">' . "\n";
    }

    public function fecharAreaTabela()
    {
        echo '</div>' . "\n";
    }

    public function montarAreaTabela(
        $strResultado,
        $numRegistros,
        $bolExecutou = false,
        $strAtributos = '',
        $arrSelecoes = null,
        $strSelecaoPrincipal = 'Infra',
        $bolForcarMontagemConteudo = false,
        $strCustomCallbackJs = null
    ) {
        //armazena o maior número de registros
        if ($numRegistros > $this->numMaxRegistrosTab) {
            $this->numMaxRegistrosTab = $numRegistros;
        }

        $arrSel = null;
        if (is_array($arrSelecoes)) {
            foreach ($arrSelecoes as $strSelecao) {
                if (!isset($this->arrSelecoes[$strSelecao])) {
                    throw new InfraException('Seleção [' . $strSelecao . '] não encontrada.');
                }
            }
            $arrSel = $arrSelecoes;
        } else {
            $arrSel = array_keys($this->arrSelecoes);
        }

        if (!isset($this->arrSelecoes[$strSelecaoPrincipal])) {
            $this->inicializarSelecao($strSelecaoPrincipal);
        }

        if ($this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_PAG_PREPARAR] && !$this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_PAG_PROCESSAR]) {
            $this->adicionarMensagem(
                'Falta processar paginação do conjunto ' . $strSelecaoPrincipal . '.',
                self::$TIPO_MSG_ERRO
            );
        } elseif (!$this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_PAG_PREPARAR] && $this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_PAG_PROCESSAR]) {
            $this->adicionarMensagem(
                'Falta preparar paginação do conjunto ' . $strSelecaoPrincipal . '.',
                self::$TIPO_MSG_ERRO
            );
        }

        if ($this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_PAG_PREPARAR] && $this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_PAG_PROCESSAR]) {
            $this->montarAreaPaginacao(
                'Superior',
                $strSelecaoPrincipal,
                $this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_TAB_INDEX],
                $strCustomCallbackJs
            );
        }

        echo '<div id="div' . $strSelecaoPrincipal . 'AreaTabela' . $this->getProxNumAreaTabela(
                $strSelecaoPrincipal
            ) . '" class="infraAreaTabela" ' . $strAtributos . '>' . "\n";

        if ($numRegistros || $bolForcarMontagemConteudo) {
            echo $strResultado;
        } else {
            //Somente após postar a primeira vez ou ter executado mostra mensagem
            if (isset($_POST['hdn' . $strSelecaoPrincipal . 'TipoPagina']) || $bolExecutou) {
                echo '<label>Nenhum registro encontrado.</label>';
            }
            /*
			//Monta seleções vazias porque não executou nenhum getThCheck
			foreach($arrSel as $strNomeSelecao){
				$this->inicializarSelecao($strNomeSelecao);
			}
			*/
        }


        echo "\n" . '</div>' . "\n";

        if (!$this->bolMontouTabela) {
            //Grava dados de todas as seleções na primeira chamada do método

            //Verificar se os itens que estao selecionados foram adicionados via getTrCheck, já que,
            //os selecionados podem vir de dados salvos na sessão, pode acontecer, da página ser gerada
            //sem itens que estão na sessão

            foreach ($arrSel as $strNomeSelecao) {
                //Se carregou da sessão
                if ($this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_SESSAO]) {
                    //se os dados da sessão não eram vazios
                    if ($this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS] != '') {
                        //Se nada foi adicionado via getTrCheck
                        if ($this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS] == '') {
                            //zera selecionados
                            $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS] = '';
                        } else {
                            //Pesquisa se os itens selecionados foram realmente adicionados
                            $arrItens = explode(',', $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS]);
                            $arrSelecionados = explode(
                                ',',
                                $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS]
                            );
                            $strTemp = '';
                            foreach ($arrSelecionados as $strItemSelecionado) {
                                if (InfraUtil::inArray($strItemSelecionado, $arrItens)) {
                                    if ($strTemp != '') {
                                        $strTemp .= ',';
                                    }
                                    $strTemp .= $strItemSelecionado;
                                }
                            }
                            $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS] = $strTemp;
                        }
                    }
                }

                echo "\n" . '<input type="hidden" id="hdn' . $strNomeSelecao . 'NroItens" name="hdn' . $strNomeSelecao . 'NroItens" value="' . $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_NRO_ITENS] . '" />' . "\n" .
                    '<input type="hidden" id="hdn' . $strNomeSelecao . 'ItemId" name="hdn' . $strNomeSelecao . 'ItemId" value="' . $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITEM_ID] . '" />' . "\n" .
                    '<input type="hidden" id="hdn' . $strNomeSelecao . 'Itens" name="hdn' . $strNomeSelecao . 'Itens" value="' . $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS] . '" />' . "\n" .
                    '<input type="hidden" id="hdn' . $strNomeSelecao . 'ItensHash" name="hdn' . $strNomeSelecao . 'ItensHash" value="' . $this->gerarHashConteudo(
                        $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS]
                    ) . '" />' . "\n" .
                    '<input type="hidden" id="hdn' . $strNomeSelecao . 'ItensSelecionados" name="hdn' . $strNomeSelecao . 'ItensSelecionados" value="' . $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS] . '" />' . "\n";
            }

            echo "\n" . '<input type="hidden" id="hdnInfraSelecoes" name="hdnInfraSelecoes" value="' . implode(
                    ',',
                    array_keys(
                        $this->arrSelecoes
                    )
                ) . '" />' . "\n";
        }

        if ($this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_ORD_PREPARAR]) {
            echo "\n" . '<input type="hidden" id="hdn' . $strSelecaoPrincipal . 'CampoOrd" name="hdn' . $strSelecaoPrincipal . 'CampoOrd" value="' . $this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_ORD_CAMPO] . '" />' . "\n" .
                '<input type="hidden" id="hdn' . $strSelecaoPrincipal . 'TipoOrd" name="hdn' . $strSelecaoPrincipal . 'TipoOrd" value="' . $this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_ORD_TIPO] . '" />' . "\n";
        }

        if ($this->isBolPaginaSelecao()) {
            echo "\n";

            $strIdObject = $this->recuperarSessao($_GET['acao'], self::$POS_SES_SEL_ID_OBJECT);
            if ($strIdObject != '') {
                echo '<input type="hidden" id="hdnInfraSelecaoIdObject" name="hdnInfraSelecaoIdObject" value="' . $strIdObject . '" />' . "\n";
            }
            $strIdSelect = $this->recuperarSessao($_GET['acao'], self::$POS_SES_SEL_ID_SELECT);
            if ($strIdSelect != '') {
                echo '<input type="hidden" id="hdnInfraSelecaoIdSelect" name="hdnInfraSelecaoIdSelect" value="' . $strIdSelect . '" />' . "\n";
            }
            $strIdHidden = $this->recuperarSessao($_GET['acao'], self::$POS_SES_SEL_ID_HIDDEN);
            if ($strIdHidden != '') {
                echo '<input type="hidden" id="hdnInfraSelecaoIdHidden" name="hdnInfraSelecaoIdHidden" value="' . $strIdHidden . '" />' . "\n";
            }
            $strIdText = $this->recuperarSessao($_GET['acao'], self::$POS_SES_SEL_ID_TEXT);
            if ($strIdText != '') {
                echo '<input type="hidden" id="hdnInfraSelecaoIdText" name="hdnInfraSelecaoIdText" value="' . $strIdText . '" />' . "\n";
            }
            $strIdTextArea = $this->recuperarSessao($_GET['acao'], self::$POS_SES_SEL_ID_TEXTAREA);
            if ($strIdTextArea != '') {
                echo '<input type="hidden" id="hdnInfraSelecaoIdTextArea" name="hdnInfraSelecaoIdTextArea" value="' . $strIdTextArea . '" />' . "\n";
            }
            echo '<input type="hidden" id="hdnInfraPaginaSelecao" name="hdnInfraPaginaSelecao" value="Sim" />' . "\n";
        }


        if ($this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_PAG_PREPARAR] && $this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_PAG_PROCESSAR]) {
            $this->montarAreaPaginacao(
                'Inferior',
                $strSelecaoPrincipal,
                $this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_TAB_INDEX],
                $strCustomCallbackJs
            );
            echo "\n" . '<input type="hidden" id="hdn' . $strSelecaoPrincipal . 'PaginaAtual" name="hdn' . $strSelecaoPrincipal . 'PaginaAtual" value="' . $this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_PAG_PAGINA_ATUAL] . '" />' . "\n" .
                '<input type="hidden" id="hdn' . $strSelecaoPrincipal . 'HashCriterios" name="hdn' . $strSelecaoPrincipal . 'HashCriterios" value="' . $this->arrSelecoes[$strSelecaoPrincipal][self::$POS_SEL_PAG_HASH_CRITERIOS] . '" />' . "\n";
        }


        $this->bolMontouTabela = true;
    }

    public function gerarHashConteudo($strItens)
    {
        $strItensHash = '';

        if ($strItens != '') {
            if ($this->getObjInfraSessao() != null) {
                $strItensHash = $this->getObjInfraSessao()->gerarHashExterno($strItens);
            } else {
                $strItensHash = hash('SHA256', '@' . $strItens . '#' . strlen($strItens) . '$');
            }
        }

        return $strItensHash;
    }

    public function montarAreaPaginacao(
        $strTipo,
        $strSelecao,
        $varTabIndexPaginacao = true,
        $strCustomCallbackJs = null
    ) {
        $strCustomCallbackJs = $strCustomCallbackJs ? $strCustomCallbackJs : 'null';
        $totalRegistros = $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_TOTAL_REGISTROS];
        $totalRegistrosPaginaAtual = $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_REGISTROS_PAGINA_ATUAL];
        $itensPorPagina = $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_REGISTROS_POR_PAGINA];
        $paginaAtual = (int)$this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL];

        echo $this->getInfraPaginaRendererFactory()->createAreaPaginacao(
            $strTipo,
            $strSelecao,
            $strCustomCallbackJs,
            $varTabIndexPaginacao,
            $totalRegistros,
            $totalRegistrosPaginaAtual,
            $itensPorPagina,
            $paginaAtual,
            $this
        );
    }

    public function fecharBody()
    {
        echo '</div>';//infraAreaTelaD

        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA && $this->getStrMenuSistema() != null) {
            $strStyle = '';
            if ($this->getStrCookieMenuMostrar() == 'N') {
                $strStyle = 'style="display:none;"';
            }

            echo '<div id="divInfraAreaTelaE" class="infraAreaTelaE" ' . $strStyle . '>' . "\n";
            $this->montarMenuSistema();
            echo '</div>' . "\n"; //infraAreaTelaE
        }

        echo '</div>' . "\n" . //infraAreaTela
            '</div>' . "\n" . //infraAreaGlobal
            '<input type="hidden" id="hdnInfraPrefixoCookie" name="hdnInfraPrefixoCookie" value="' . $this->getStrPrefixoCookie(
            ) . '" />' . "\n" .
            '<div id="infraDivImpressao" class="infraImpressao"></div>' . "\n";

        $this->montarMensagens();

        echo '</body>' . "\n";
    }

    public function montarMensagens()
    {
        if ($this->getBolExibirMensagens()) {
            $strAlert = '';
            if (isset($_GET['msg'])) {
                if ($_GET['msg'] != '') {
                    echo '<textarea id="txaInfraMsg" name="txaInfraMsg" style="display:none">' . str_replace(
                            '\n',
                            "\n",
                            self::tratarHTML(
                                $_GET['msg']
                            )
                        ) . '</textarea>' . "\n";
                    $strAlert .= 'self.setTimeout(\'alert(document.getElementById(\\\'txaInfraMsg\\\').value)\',300);';
                }
            }

            $strMensagens = $this->getStrMensagens();
            if ($strMensagens != '') {
                echo '<textarea id="txaInfraValidacao" name="txaInfraValidacao" style="display:none">' . str_replace(
                        '\n',
                        "\n",
                        self::tratarHTML($strMensagens)
                    ) . '</textarea>' . "\n";
                $strAlert .= 'self.setTimeout(\'alert(document.getElementById(\\\'txaInfraValidacao\\\').value)\',300);';
            }

            if ($strAlert != '') {
                $this->abrirJavaScript();
                echo $strAlert;
                $this->fecharJavaScript();
            }
        }

        if ($this->getObjInfraSessao() != null) {
            $this->adicionarSessao('infra_global', self::$POS_SES_MSG, '');
        }
    }

    public function fecharHtml()
    {
        echo '</html>' . "\n";
    }

    protected function complementarAtributo($strAtributos, $strTagAtributo, $strComplementacao)
    {
        if ($strAtributos != '') {
            $posTagAtributo = strpos($strAtributos, $strTagAtributo . '="');
            if ($posTagAtributo !== false) {
                //adiciona na tag do atributo que já existia
                $strAtributos = substr(
                        $strAtributos,
                        0,
                        $posTagAtributo + strlen($strTagAtributo . '="')
                    ) . $strComplementacao . ' ' . substr(
                        $strAtributos,
                        $posTagAtributo + strlen($strTagAtributo . '="')
                    );
            } else {
                //Concatena nos outros atributos
                $strAtributos .= ' ' . $strTagAtributo . '="' . $strComplementacao . '"';
            }
        } else {
            //atributo só vai possuir a tag passada
            $strAtributos = $strTagAtributo . '="' . $strComplementacao . '"';
        }
        return $strAtributos;
    }

    public function getThCheck($strRotulo = '', $strNomeSelecao = 'Infra', $strAtributos = '', $varTabIndex = true)
    {
        if (!isset($this->arrSelecoes[$strNomeSelecao])) {
            $this->inicializarSelecao($strNomeSelecao);
        }

        $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_SESSAO] = false;
        $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_NRO_ITENS] = 0;
        $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_TAB_INDEX] = $varTabIndex;


        //Só verifica se tem dados salvos se especificou ação origem. Ex.:
        //$_GET['acao'] = serie_listar
        //$_GET['acao_origem'] = serie_excluir
        //Vai ter dados quando ocorreu validação na exclusão e retornou para a lista

        if (isset($_GET['acao_origem']) && $_GET['acao_origem'] != '') {
            $arrGruposSelecoes = $this->recuperarSessao($_GET['acao'], self::$POS_SES_GRUPOS_SELECOES);

            if (is_array($arrGruposSelecoes)) {
                //Se esta salvo para esta seleção
                if (isset($arrGruposSelecoes[$strNomeSelecao])) {
                    $arrTemp = $arrGruposSelecoes[$strNomeSelecao];

                    unset($arrGruposSelecoes[$strNomeSelecao]);

                    //Queima dados da seleção, independente da ação pois só devem ser aproveitados uma vez
                    $this->adicionarSessao($_GET['acao'], self::$POS_SES_GRUPOS_SELECOES, $arrGruposSelecoes);

                    //Se os dados foram salvos quando estava executando a ação de origem
                    if ($arrTemp[self::$POS_SES_GRUPOS_SELECOES_ACAO] == $_GET['acao_origem']) {
                        //InfraDebug::getInstance()->gravar('#Recuperou (selecao='.$strNomeSelecao.') (acao='.$_GET['acao'].') (acao_origem='.$_GET['acao_origem'].')');

                        $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_SESSAO] = true;
                        $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITEM_ID] = $arrTemp[self::$POS_SES_GRUPOS_SELECOES_ITEM_ID];
                        $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS] = $arrTemp[self::$POS_SES_GRUPOS_SELECOES_ITENS];
                    }
                }
            }
        } else {
            //Queima todas as seleções desta ação
            $this->adicionarSessao($_GET['acao'], self::$POS_SES_GRUPOS_SELECOES, '');
        }

        //Se não pegou da sessão
        if (!$this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_SESSAO]) {
            $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITEM_ID] = '';
            $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS] = '';
        }

        $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS] = '';

        if ($this->getTipoSelecao() == self::$TIPO_SELECAO_SIMPLES) {
            return '&nbsp;';
        }

        $strAtributos = $this->complementarAtributo(
            $strAtributos,
            'onclick',
            'infraSelecaoMultipla(\'' . $strNomeSelecao . '\');'
        );

        $strAnchor = '';
        $strAnchor .= '<label id="lblInfraCheck" for="lnkInfraCheck" accesskey=";"></label>' . "\n";

        $strTabIndex = '';
        if ($varTabIndex === true) {
            $strTabIndex = ' tabindex="' . $this->getProxTabTabela() . '"';
        } elseif (is_numeric($varTabIndex)) {
            $strTabIndex = ' tabindex="' . $varTabIndex . '"';
        }

        $strAnchor .= '<a href="javascript:void(0);" id="lnkInfraCheck" ' . $strAtributos . $strTabIndex . '><img src="' . $this->getIconeCheck(
            ) . '" id="img' . $strNomeSelecao . 'Check" title="Selecionar Tudo" alt="Selecionar Tudo" class="infraImg" /></a>';

        if (trim($strRotulo) == '') {
            return $strAnchor;
        }

        if ($this instanceof InfraPaginaEsquema3){
            return '<div class="infraDivThCheck"><div class="infraDivThCheckSelecao">' . $strAnchor . '</div><div class="infraDivThCheckRotulo">' . $strRotulo . '</div></div>';
        }else{
            return '<div style="padding:.2em;"><div style="float:left;">' . $strAnchor . '</div><div style="float:left;display:inline;padding:.2em;">&nbsp;' . $strRotulo . '</div></div>';
        }

    }

    public function getTrCheck(
        $numItem,
        $strId,
        $strTitulo,
        $strValor = 'N',
        $strNomeSelecao = 'Infra',
        $strAtributos = ''
    ) {
        $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_NRO_ITENS]++;

        if ($this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS] != '') {
            $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS] .= ',';
        }
        $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS] .= $strId;

        //Marca itens após POST
        $isChecked = false;

        //se pegou da sessão verifica se deve estar marcado
        if ($this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_SESSAO]) {
            //verificar se esta no conjunto salvo
            if (InfraUtil::inArray(
                $strId,
                explode(',', $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS])
            )) {
                $isChecked = true;
            }
        } else {
            //Se foi marcado através do método adicionarSelecionado
            $bolAdicionado = false;
            if (isset($_POST['hdn' . $strNomeSelecao . 'ItensSelecionados']) && $_POST['hdn' . $strNomeSelecao . 'ItensSelecionados'] != '') {
                if (InfraUtil::inArray($strId, explode(',', $_POST['hdn' . $strNomeSelecao . 'ItensSelecionados']))) {
                    $bolAdicionado = true;
                }
            }

            //Se foi adicionado ou foi gerado como marcado
            if ($bolAdicionado || $strValor == 'S') {
                $isChecked = true;
                if ($this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS] != '') {
                    $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS] .= ',';
                }
                $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_ITENS_SELECIONADOS] .= $strId;
            }
        }
        //todo quando migrarem para php7, remover o createTrCheck e usar exatamente o render do  IPTrCheckBS4
        $strAtributos = $this->complementarAtributo(
            $strAtributos,
            'onclick',
            'infraSelecionarItens(this,\'' . $strNomeSelecao . '\');'
        );
        $varTabIndex = $this->arrSelecoes[$strNomeSelecao][self::$POS_SEL_TAB_INDEX];

        return $this->getInfraPaginaRendererFactory()->createTrCheck(
            $strId,
            $strNomeSelecao,
            $strAtributos,
            $strTitulo,
            $isChecked,
            $numItem,
            $varTabIndex,
            $this
        );
    }

    public function getAcaoTransportarItem(
        $numItem,
        $strId,
        $strNomeSelecao = 'Infra',
        $strAtributos = '',
        $strTitulo = 'Transportar este item e Fechar',
        $strIcone = null
    ) {
        if ($this->isBolPaginaSelecao()) {
            $strAtributos = $this->complementarAtributo(
                $strAtributos,
                'onclick',
                'infraTransportarItem(' . $numItem . ',\'' . $strNomeSelecao . '\');'
            );

            if ($strIcone == null) {
                $strIcone = $this->getIconeTransportar();
            }

            return '<a id="lnk' . $strNomeSelecao . 'T-' . $strId . '" href="#" ' . $strAtributos . ' tabindex="' . $this->getProxTabTabela(
                ) . '"><img src="' . $strIcone . '" title="' . $strTitulo . '" alt="' . $strTitulo . '" class="infraImg" /></a>&nbsp;';
        }
    }

    public function getArrStrItensSelecionados($strNomeSelecao = 'Infra', $bolManterOrdemMarcacao = true)
    {
        $ret = null;

        if (!isset($_POST['hdn' . $strNomeSelecao . 'ItemId']) && !isset($_POST['hdn' . $strNomeSelecao . 'ItensSelecionados'])) {
            //throw new InfraException('Seleção '.$strNomeSelecao.' não encontrada na página.');
            return array();
        }

        if (isset($_POST['hdn' . $strNomeSelecao . 'ItemId']) && $_POST['hdn' . $strNomeSelecao . 'ItemId'] != '') {
            $ret = array($_POST['hdn' . $strNomeSelecao . 'ItemId']);
        } elseif (isset($_POST['hdn' . $strNomeSelecao . 'ItensSelecionados']) && $_POST['hdn' . $strNomeSelecao . 'ItensSelecionados'] != '') {
            $ret = explode(',', $_POST['hdn' . $strNomeSelecao . 'ItensSelecionados']);
        } else {
            $ret = array();
        }

        if ($this->validarHashTabelas() && InfraArray::contar($ret)) {
            if (!isset($_POST['hdn' . $strNomeSelecao . 'ItensHash'])) {
                throw new InfraException('Hash da tabela não encontrado.');
            }

            if (!isset($_POST['hdn' . $strNomeSelecao . 'Itens'])) {
                throw new InfraException('Conjunto de itens da tabela não encontrado.');
            }

            if ($_POST['hdn' . $strNomeSelecao . 'ItensHash'] != $this->gerarHashConteudo(
                    $_POST['hdn' . $strNomeSelecao . 'Itens']
                )) {
                throw new InfraException('Hash da tabela inválido.');
            }

            $arrItens = explode(',', $_POST['hdn' . $strNomeSelecao . 'Itens']);

            foreach ($ret as $item) {
                if (!in_array($item, $arrItens)) {
                    throw new InfraException('Item selecionado não consta na tabela.');
                }
            }

            if (!$bolManterOrdemMarcacao) {
                $ret2 = array();
                foreach ($arrItens as $item) {
                    if (in_array($item, $ret)) {
                        $ret2[] = $item;
                    }
                }
                $ret = $ret2;
            }
        }

        return $ret;
    }

    public function getArrStrItens($strNomeSelecao = 'Infra')
    {
        $ret = null;

        if (!isset($_POST['hdn' . $strNomeSelecao . 'Itens'])) {
            //throw new InfraException('Seleção '.$strNomeSelecao.' não encontrada na página.');
            return array();
        }

        if ($this->validarHashTabelas()) {
            if (!isset($_POST['hdn' . $strNomeSelecao . 'ItensHash'])) {
                throw new InfraException('Hash da tabela não encontrado.');
            }

            if (!isset($_POST['hdn' . $strNomeSelecao . 'Itens'])) {
                throw new InfraException('Conjunto de itens da tabela não encontrado.');
            }

            if ($_POST['hdn' . $strNomeSelecao . 'ItensHash'] != $this->gerarHashConteudo(
                    $_POST['hdn' . $strNomeSelecao . 'Itens']
                )) {
                throw new InfraException('Hash da tabela inválido.');
            }
        }

        if (isset($_POST['hdn' . $strNomeSelecao . 'Itens']) && $_POST['hdn' . $strNomeSelecao . 'Itens'] != '') {
            $ret = explode(',', $_POST['hdn' . $strNomeSelecao . 'Itens']);
        } else {
            $ret = array();
        }

        return $ret;
    }

    public function getArrItensTabelaDinamica($strDados, $bolRemoverFormatacaoXML = true)
    {
        $ret = array();
        if ($strDados != '') {
            if ($bolRemoverFormatacaoXML) {
                $strDados = InfraString::removerFormatacaoXML($strDados);
            }

            $arrLinhas = explode('¥', $strDados);
            foreach ($arrLinhas as $linha) {
                $arrColunas = explode('±', $linha);
                $arr = array();
                foreach ($arrColunas as $coluna) {
                    $arr[] = $coluna;
                }
                $ret[] = $arr;
            }
        }
        return $ret;
    }

    public function getArrOptionsSelect($strDados)
    {
        $ret = array();
        if ($strDados != '') {
            $arrLinhas = explode('¥', $strDados);
            foreach ($arrLinhas as $linha) {
                $ret[] = explode('±', $linha);
            }
        }
        return $ret;
    }

    public function getArrValuesSelect($strDados)
    {
        $ret = array();
        $arrOptions = $this->getArrOptionsSelect($strDados);
        if (is_array($arrOptions)) {
            foreach ($arrOptions as $option) {
                $ret[] = $option[0];
            }
        }
        return $ret;
    }

    public function gerarItensTabelaDinamica($arr, $bolTratarHtml = true)
    {
        $ret = '';
        if (is_array($arr)) {
            $numLinhas = count($arr);
            for ($i = 0; $i < $numLinhas; $i++) {
                if ($i > 0) {
                    $ret .= '¥';
                }
                $numColunas = count($arr[$i]);
                for ($j = 0; $j < $numColunas; $j++) {
                    if ($j > 0) {
                        $ret .= '±';
                    }
                    if ($arr[$i][$j] === null) {
                        $ret .= 'null';
                    } else {
                        if ($bolTratarHtml) {
                            $ret .= self::tratarHTML($arr[$i][$j]);
                        } else {
                            $ret .= $arr[$i][$j];
                        }
                    }
                }
            }
        }
        return $ret;
    }

    public function gerarItensLupa($arr)
    {
        $ret = '';
        if (is_array($arr)) {
            $numLinhas = count($arr);
            for ($i = 0; $i < $numLinhas; $i++) {
                if ($i > 0) {
                    $ret .= '¥';
                }
                $ret .= $arr[$i][0] . '±' . $arr[$i][1];
            }
        }
        return $ret;
    }

    public function setStrMensagem($strMsg, $numTipoMsg = 1)
    {
        if ($this->getObjInfraSessao() == null) {
            $this->strMensagens = $strMsg;
        } else {
            $arrMsg = array();
            $arrMsg[self::$POS_SES_MSG_TIPO] = $numTipoMsg;
            $arrMsg[self::$POS_SES_MSG_CONTEUDO] = $strMsg;

            $arrMensagens = array($arrMsg);

            $this->adicionarSessao('infra_global', self::$POS_SES_MSG, $arrMensagens);
        }
    }

    public function adicionarMensagem($strMsg, $numTipoMsg = 1)
    {
        if ($this->getObjInfraSessao() == null) {
            $strMsgSalvas = $this->strMensagens;
            if ($strMsgSalvas != '') {
                $strMsgSalvas .= "\\n";
            }
            $strMsgSalvas .= $strMsg;
            $this->strMensagens = $strMsgSalvas;
        } else {
            $arrMsgSalvas = $this->recuperarSessao('infra_global', self::$POS_SES_MSG);

            if (!is_array($arrMsgSalvas)) {
                $arrMsgSalvas = array();
            }

            $arrMsg = array();
            $arrMsg[self::$POS_SES_MSG_TIPO] = $numTipoMsg;
            $arrMsg[self::$POS_SES_MSG_CONTEUDO] = $strMsg;

            $arrMsgSalvas[] = $arrMsg;

            $this->adicionarSessao('infra_global', self::$POS_SES_MSG, $arrMsgSalvas);
        }
    }

    public function getStrMensagens()
    {
        $msg = '';
        if ($this->getObjInfraSessao() == null) {
            $msg = $this->strMensagens;
        } else {
            $arrMensagens = $this->recuperarSessao('infra_global', self::$POS_SES_MSG);

            if (is_array($arrMensagens)) {
                foreach ($arrMensagens as $arrMensagem) {
                    if ($this->obterTiposMensagemExibicao() & $arrMensagem[self::$POS_SES_MSG_TIPO]) {
                        if (!InfraString::isBolVazia($arrMensagem[self::$POS_SES_MSG_CONTEUDO])) {
                            if ($msg != '') {
                                $msg .= "\\n";
                            }
                            $msg .= $arrMensagem[self::$POS_SES_MSG_CONTEUDO];
                        }
                    }
                }
            }
        }
        return $msg;
    }

    public function salvarSelecao($strAcao, $strAcaoOrigem, $strNomeSelecao = 'Infra')
    {
        $arrGruposSelecoes = array();
        $arrGruposSelecoes[$strNomeSelecao] = array();
        $arrGruposSelecoes[$strNomeSelecao][self::$POS_SES_GRUPOS_SELECOES_ACAO] = $strAcao;
        $arrGruposSelecoes[$strNomeSelecao][self::$POS_SES_GRUPOS_SELECOES_ITEM_ID] = $_POST['hdn' . $strNomeSelecao . 'ItemId'];
        $arrGruposSelecoes[$strNomeSelecao][self::$POS_SES_GRUPOS_SELECOES_ITENS] = $_POST['hdn' . $strNomeSelecao . 'ItensSelecionados'];

        $this->adicionarSessao($strAcaoOrigem, self::$POS_SES_GRUPOS_SELECOES, $arrGruposSelecoes);
    }

    public function processarExcecao($e, $bolLimparParametrosLog = false)
    {
        $strErro = '';
        $strValidacao = '';
        $strDetalhes = '';
        $strTrace = '';
        $bolGravarLog = true;
        $strStaTipoLog = InfraLog::$ERRO;
        $bolValidacao = false;

        $this->setStrMensagem('', self::$TIPO_MSG_ERRO);

        if ($e === null) {
            return;
        } elseif ($e instanceof InfraException) {
            if ($e->contemValidacoes()) {
                $this->setStrMensagem($e->__toString(), self::$TIPO_MSG_ERRO);

                //Salva dados das seleções (colunas de checkbox) na volta para a ação de origem
                //Ex.: validação na exclusão após volta para a tela de lista com os itens selecionados, neste caso:
                //$_GET['acao'] = serie_excluir
                //$_GET['acao_origem'] = serie_listar
                //
                //Ou seja, realizando exclusão vindo da lista

                if (isset($_GET['acao_origem']) && $_GET['acao_origem'] != '') {
                    if ($this->getObjInfraSessao() !== null) {
                        //campo hidden com o nome das seleções da página
                        if (isset($_POST['hdnInfraSelecoes']) && $_POST['hdnInfraSelecoes'] != '') {
                            $arrSelecoes = explode(',', $_POST['hdnInfraSelecoes']);

                            //array com os dados das seleções
                            $arrGruposSelecoes = array();

                            foreach ($arrSelecoes as $strNomeSelecao) {
                                //Para cada grupo de seleção salva:
                                //1 - a ação onde ocorreu a validação (Ex.: só vai aproveitar os dados na serie_lista se retornando de serie_excluir)
                                //2 - o id do item selecionados (clicou na lixeira)
                                //3 - os ids dos itens marcados (clicou em checkboxes da coluna de seleção)

                                //InfraDebug::getInstance()->gravar('#Salvou (selecao='.$strNomeSelecao.') (acao='.$_GET['acao'].') (acao_origem='.$_GET['acao_origem'].')');

                                $arrGruposSelecoes[$strNomeSelecao] = array();
                                $arrGruposSelecoes[$strNomeSelecao][self::$POS_SES_GRUPOS_SELECOES_ACAO] = $_GET['acao'];
                                $arrGruposSelecoes[$strNomeSelecao][self::$POS_SES_GRUPOS_SELECOES_ITEM_ID] = $_POST['hdn' . $strNomeSelecao . 'ItemId'];
                                $arrGruposSelecoes[$strNomeSelecao][self::$POS_SES_GRUPOS_SELECOES_ITENS] = $_POST['hdn' . $strNomeSelecao . 'ItensSelecionados'];
                            }

                            //Salva na sessão para a ação que vai ser executada posteriormente
                            $this->adicionarSessao(
                                $_GET['acao_origem'],
                                self::$POS_SES_GRUPOS_SELECOES,
                                $arrGruposSelecoes
                            );
                        }
                    }
                }
                if ($this->bolBarraProgresso2) {
                    InfraBarraProgresso2::setStrValidacao($e->__toString());
                    $instances = InfraBarraProgresso2::getInstances();
                    foreach ($instances as $instance) {
                        $instance->close();
                    }
                }
                if ($e->getObjException() == null) {
                    return;
                }

                $strValidacao = $e->__toString();
                $strDetalhes = $e->getObjException()->__toString();
                $strTrace = $e->getObjException()->getTraceAsString();
                $bolValidacao = true;
            } else {
                if ($e->isBolPermitirGravacaoLog() === false) {
                    $bolGravarLog = false;
                }

                if ($e->getStrStaTipoLog() !== null) {
                    $strStaTipoLog = $e->getStrStaTipoLog();
                }

                $strErro = $e->__toString();

                //Detalhes passados para o construtor de InfraException
                if ($e->getStrDetalhes() !== null) {
                    $strDetalhes .= $e->getStrDetalhes() . "\n\n";
                }

                if ($e->getObjException() != null) {
                    $strTrace .= $e->getObjException()->__toString() . "\n\n";
                } else {
                    $strTrace .= $e->getStrTrace() . "\n\n";
                }
            }
        } elseif ($e instanceof SoapFault) {
            if (InfraException::getTipoInfraException($e) == 'INFRA_VALIDACAO') {
                $this->setStrMensagem($e->faultstring, self::$TIPO_MSG_ERRO);
                return;
            } else {
                $strErro = $e->faultstring;
                $strDetalhes = $e->__toString();
                $strTrace = $e->getTraceAsString();
            }
        } elseif ($e instanceof Exception) {
            $strErro = $e->__toString();
            $strDetalhes = '';
            $strTrace = $e->getTraceAsString();
        } elseif ($e instanceof Error) {
            $strErro = $e->__toString();
            $strDetalhes = '';
            $strTrace = $e->getTraceAsString();
        } else {
            $strErro = 'Erro não identificado.';
        }

        //Evita que o usuário não consiga mais entrar em uma pagina
        //Em uma pagina de lista (por exemplo) se os criterios provocam um erro
        //o usuario não consegue mais altera-los porque a pesquisa poderá iniciar
        //automaticamente
        if (!$bolValidacao) {
            $this->limparCampos();
        }

        if ($bolGravarLog && $this->getObjInfraLog() instanceof InfraLog) {
            $strTextoLog = '';
            if ($this->getObjInfraSessao() !== null) {
                if ($this->getObjInfraSessao()->getStrSiglaUsuario() !== null) {
                    $strTextoLog .= 'Usuário: ' . $this->getObjInfraSessao()->getStrSiglaUsuario();

                    if ($this->getObjInfraSessao()->getStrSiglaOrgaoUsuario() !== null) {
                        $strTextoLog .= '/' . $this->getObjInfraSessao()->getStrSiglaOrgaoUsuario();
                    }
                }
            }

            if ($bolLimparParametrosLog) {
                $strErro = InfraString::limparParametrosPhp($strErro);
                $strDetalhes = InfraString::limparParametrosPhp($strDetalhes);
                $strTrace = InfraString::limparParametrosPhp($strTrace);
            }

            $strTextoLog .= "\nServidor: " . $_SERVER['SERVER_NAME'] . ' (' . $_SERVER['SERVER_ADDR'] . ')';

            if ($strErro != '') {
                $strTextoLog .= "\nErro: " . $strErro;
            }

            if ($strValidacao != '') {
                $strTextoLog .= "\nValidação: " . $strValidacao;
            }

            $strTextoLog .= "\nDetalhes:\n" . $strDetalhes;
            $strTextoLog .= "\nTrilha de Processamento:\n" . $strTrace;
            $strTextoLog .= "\nNavegador: " . $_SERVER['HTTP_USER_AGENT'];
            if (is_array($_GET)) {
                $strTextoLog .= "\nGET:\n" . print_r($_GET, true);
            }
            if (is_array($_POST)) {
                foreach (array_keys($_POST) as $strChave) {
                    if (substr($strChave, 0, 3) == 'pwd') {
                        $_POST[$strChave] = '********************';
                    }
                }

                $strTextoLog .= "\nPOST:\n" . print_r($_POST, true);
            }

            try {
                $this->getObjInfraLog()->gravar($strTextoLog, $strStaTipoLog);
            } catch (Exception $e) {
                //Ignora, erro mais provavel queda da conexao com o banco
            }
        }
        if ($this->bolBarraProgresso2) {
            InfraBarraProgresso2::setStrValidacao($strErro);
            $instances = InfraBarraProgresso2::getInstances();
            foreach ($instances as $instance) {
                $instance->close();
            }
        } else {
            if (!$bolValidacao) {
                if (!$this->bolBarraProgresso) {
                    $this->montarPaginaErro($strErro, $strDetalhes, $strTrace);
                } else {
                    $this->montarErroBarraProgresso($strErro, $strDetalhes, $strTrace);
                }
            }
        }
    }

    public function prepararBarraProgresso($strTituloJanela, $strTituloPagina = null, $bolExibirCancelar = false)
    {
        $this->bolBarraProgresso = true;

        try {
            @ini_set('zlib.output_compression', 0);
            @ini_set('implicit_flush', 1);
        } catch (Exception $e) {
        }


        $this->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

        header('X-Accel-Buffering: no');

        $this->montarDocType();
        $this->abrirHtml();
        $this->abrirHead();
        $this->montarMeta();
        $this->montarTitle($strTituloJanela);
        //$this->montarStyle();

        if ($this instanceof InfraPaginaEsquema3) {
            echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-global-esquema-3.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        } elseif ($this instanceof InfraPaginaEsquema2) {
            echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-global-esquema.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
            echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-global-esquema-2.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        } elseif ($this instanceof InfraPaginaEsquema) {
            echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-global-esquema.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        } else {
            echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-global.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        }

        echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-barra-progresso.css?' . $this->getNumVersao(
            ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";

        if ($this instanceof InfraPaginaEsquema3) {
            $this->abrirStyle();
            echo 'body {width:90%;margin:0 auto;}' . "\n";
            $this->fecharStyle();
        }

        $this->montarJavaScript();
        $this->fecharHead();
        $this->abrirBody($strTituloPagina);

        $arrComandos = array();
        if ($bolExibirCancelar) {
            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="infraCancelarBarraProgresso();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
        }

        $this->montarBarraComandosSuperior($arrComandos);
        $this->abrirAreaDados();


        flush();
    }

    public function prepararBarraProgresso2(
        $strTituloJanela,
        $strTituloPagina = null,
        $bolExibirCancelar = false,
        $delay = 1000
    ) {
        $this->bolBarraProgresso2 = true;
        $id = null;
        if (isset($_POST['hdnIdInfraBarraProgresso2'])) {
            $id = $_POST['hdnIdInfraBarraProgresso2'];
        }
        InfraBarraProgresso2::preparar($id);
        if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
            if (isset($_POST['status']) && ($status = $_POST['status'])) {
                $instances = InfraBarraProgresso2::getInstances();
                switch ($status) {
                    case InfraBarraProgresso2::STATUS_CLOSED_CLIENT:
                        foreach ($instances as $instance) {
                            $instance->client_close();
                        }
                        break;
                    case InfraBarraProgresso2::STATUS_ABORTED_CLIENT:
                        foreach ($instances as $instance) {
                            $instance->abort();
                        }
                        break;
                    default:
                }
            }
            $progress = InfraBarraProgresso2::read_all();
            header('Content-type: application/json');
            echo $progress; // JSON encoded
            die;
        }

        try {
            @ini_set('zlib.output_compression', 0);
            @ini_set('implicit_flush', 1);
        } catch (Exception $e) {
        }


        $this->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

        header('X-Accel-Buffering: no');
        header('Connection: close');
        session_write_close();
        ob_start();

        $this->montarDocType();
        $this->abrirHtml();
        $this->abrirHead();
        $this->montarMeta();
        $this->montarTitle($strTituloJanela);
        //$this->montarStyle();


        if ($this instanceof InfraPaginaEsquema3) {
            echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-global-esquema-3.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        } elseif ($this instanceof InfraPaginaEsquema2) {
            echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-global-esquema.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
            echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-global-esquema-2.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        } elseif ($this instanceof InfraPaginaEsquema) {
            echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-global-esquema.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        } else {
            echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-global.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        }

        echo '<link href="' . $this->getDiretorioCssGlobal() . '/infra-barra-progresso.css?' . $this->getNumVersao(
            ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        echo '<link href="' . $this->getDiretorioJavaScriptGlobal() . '/jquery/jquery-ui-' . $this->getVersaoJQueryUI(
            ) . '/jquery-ui.min.css?' . $this->getVersaoJQueryUI(
            ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        $this->abrirStyle();

        if ($this instanceof InfraPaginaEsquema3) {
            ?>
            body {width:90%;margin:0 auto;}
            <?php
        }
        ?>

        .ui-progressbar { position: relative; }

        .progress-label { position: absolute; text-align:center; width:100%; top: 5px; font-weight: bold; color:black; text-shadow: 1px 1px 1px white;}

        <?php
        $this->fecharStyle();
        $this->montarJavaScript();
        $this->abrirJavaScript();
    if (0){
        ?>
        <script><?}
            ?>


            ProgressBar = function (divContainerId, url, options) {
                if (!options) options = {};
                this.container = $(divContainerId);
                this.delay = options.delay || 1000;
                this.url = url;
                this.excecao = false;
                this.init();
            };

            ProgressBar.prototype = {
                init: function () {
                    this._timer = null;
                    this._title = '';
                    this._status = ProgressBar.STATUS_POLLING;
                    this.ask();
                },

                stop: function () {
                    if (this._timer) clearTimeout(this._timer);
//          this.container.hide();
                    this._status = ProgressBar.STATUS_CLOSE_CLIENT;
                    this.ask(false);
                    /* Last call to inform the server that the client has closed */
                },

                relaunch: function () {
                    if (this._status == ProgressBar.STATUS_INACTIVE) return;
                    this._timer = setTimeout(this.ask.bind(this), this.delay);
                },

                ask: function (synchronous) {
                    if (this._timer) clearTimeout(this._timer);
                    this._timer = null;
                    var dados = "hdnIdInfraBarraProgresso2=<?=InfraBarraProgresso2::getId()?>&ajax=1";
                    switch (this._status) {
                        case ProgressBar.STATUS_CLOSE_CLIENT:
                            dados += '&status=' + ProgressBar.STATUS_CLOSE_CLIENT;
                            break;
                        case ProgressBar.STATUS_ABORTED:
                            dados += '&status=' + ProgressBar.STATUS_ABORTED;
                            this._status = ProgressBar.STATUS_POLLING; // We keep on polling
                            break;
                    }

                    $.ajax(this.url, {
                        async: (synchronous ? false : true),
                        data: dados,
                        method: 'POST',
                        success: (function (data, text, transport) {
                            if (this._status == ProgressBar.STATUS_CLOSE_CLIENT)
                                this._status = ProgressBar.STATUS_INACTIVE;
                            /* Disable polling */
                            if (progressArray = transport.responseJSON) { // set, not compare
                                var close;
                                for (var name in progressArray) {
                                    if (progressArray[name].excecao != '') {
                                        this.excecao = true;
                                        alert(progressArray[name].excecao);
                                    } else if (progressArray[name].validacao != '') {
                                        if (this.excecao == false) {
                                            alert(progressArray[name].validacao);
                                        }
                                        close = true;
                                        this.excecao = true;
                                    } else {

                                        if (progressArray[name].redirect) {
                                            this.stop();
                                            var janelaPai = (window.opener != null) ? window.opener : parent.infraJanelaModalOrigem;
                                            janelaPai.location = progressArray[name].redirect;
                                        }
                                        if (progressArray[name].fechar == true) {
                                            this.stop();
                                            if (window.opener != null) {
                                                window.close();
                                            } else {
                                                infraFecharJanelaModal();
                                            }
                                        }
                                    }
                                    if (progressArray[name].status != ProgressBar.STATUS_SERVER_CLOSED) {
                                        close = false;
                                    }

                                    this._progress = progressArray[name].posicao;
                                    this._max = progressArray[name].maximo;
                                    this._min = progressArray[name].minimo;
                                    this._title = progressArray[name].rotulo;
                                    this._corFundo = progressArray[name].cor_fundo;
                                    this._corBorda = progressArray[name].cor_borda;
                                    this._BPAtual = this.container.find('#infraBP' + name);
                                    if (this._BPAtual.length == 0) {
                                        this._BPAtual = $('<div />', {id: 'infraBP' + name}).appendTo(this.container).progressbar();
                                        $('<br/>').appendTo(this.container);
                                    }
                                    this._LabelAtual = this._BPAtual.find('.progress-label');
                                    if (this._LabelAtual.length == 0) {
                                        this._LabelAtual = $('<div class="progress-label"/>').appendTo(this._BPAtual);
                                    }
                                    this._BPAtual.progressbar("option", {
                                        value: this._progress !== false ? this._progress - this._min : false,
                                        max: this._max - this._min
                                    });
                                    if (this._corFundo != null) {
                                        this._BPAtual.find(".ui-progressbar-value").css("background", this._corFundo);
                                    }
                                    if (this._corBorda != null) {
                                        this._BPAtual.find(".ui-progressbar-value").css("border-color", this._corBorda);
                                    }

                                    this._LabelAtual.text(this._title);
                                }

                                if (close) {
                                    if (this._status == ProgressBar.STATUS_POLLING)
                                        /* Server closed the progress bar, we have to close client
                   (to acknowledge closing by removing server-side temp file) */
                                        this._status = ProgressBar.STATUS_CLOSE_CLIENT;
                                    /* and we keep the last progress value received */
                                }

                            }
                            this.relaunch.bind(this)();
                        }).bind(this)
                    })
                },

                abort: function () {
                    this._status = ProgressBar.STATUS_ABORTED;
                    this.ask(false); // synchronous call
                }

            };
            ProgressBar.STATUS_INACTIVE = -1;
            ProgressBar.STATUS_POLLING = 0;
            ProgressBar.STATUS_CLOSE_CLIENT = -2;
            ProgressBar.STATUS_SERVER_CLOSED = -101;
            ProgressBar.STATUS_ABORTED = -102;

            <?php
            if (0){
            ?></script><?
    }
        $this->fecharJavaScript();
        $this->fecharHead();
        $this->abrirBody($strTituloPagina);

        echo '<br>';

        $arrComandos = array();
        if ($bolExibirCancelar) {
            $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="prb.abort();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
        }

        $this->montarBarraComandosSuperior($arrComandos);

        $this->abrirAreaDados();

        echo '<div id="divInfraBarraProgresso"></div>';
        $this->fecharAreaDados();
        $this->montarAreaDebug();
        $this->abrirJavaScript();
    if (0){
        ?>
        <script><?}
            ?>
            var prb = new ProgressBar('#divInfraBarraProgresso', '<?=$_SERVER['REQUEST_URI']?>', {
                delay: <?=$delay?>,
                autoHide: false
            });
            <?
            if (0){
            ?></script><?
    }
        $this->fecharJavaScript();
        $this->fecharBody();
        $this->fecharHtml();

        $size = ob_get_length();
        header("Content-Length: $size");
        ob_end_flush();
        flush();
    }

    public function finalizarBarraProgresso2($strDestinoJanelaPai = null, $bolFecharAutomaticamente = true)
    {
        if ($strDestinoJanelaPai != null) {
            InfraBarraProgresso2::setStrUrlRedirecionamento($strDestinoJanelaPai);
        }
        if ($bolFecharAutomaticamente) {
            InfraBarraProgresso2::setBolFecharJanela(true);
        }
        $instances = InfraBarraProgresso2::getInstances();
        foreach ($instances as $instance) {
            $instance->close();
        }

        die;
    }

    public function finalizarBarraProgresso($strDestinoJanelaPai = null, $bolFecharAutomaticamente = true)
    {
        $this->fecharAreaDados();
        $this->montarAreaDebug();
        $this->abrirJavaScript();
        echo 'if (document.getElementById(\'btnCancelar\')!=null){document.getElementById(\'btnCancelar\').style.visibility=\'hidden\';}' . "\n";

        if ($this->getStrMensagens() == '' && $_GET['msg'] == '') {
            echo 'if (document.getElementById(\'divInfraExcecao\')==null){' . "\n";

            if ($strDestinoJanelaPai != null) {
                echo '  if (window.opener!=null) {window.opener.location=\'' . $strDestinoJanelaPai . '\';} else {parent.location=\'' . $strDestinoJanelaPai . '\';} ' . "\n";
            }

            if ($bolFecharAutomaticamente) {
                //para dar tempo de ver a finalização da barra
                sleep(1);
                echo '  if (window.opener!=null) { window.close(); } else { infraFecharJanelaModal(); }' . "\n";
            }

            echo '}' . "\n";
        }

        $this->fecharJavaScript();
        $this->fecharBody();
        $this->fecharHtml();

        $this->bolBarraProgresso = false;

        flush();

        die;
    }

    public function montarPaginaErro($strErro, $strDetalhes, $strTrace)
    {
        $this->montarDocType();
        $this->abrirHtml();
        $this->abrirHead();
        $this->montarMeta();
        $this->montarTitle($this->getStrNomeSistema());
        $this->montarStyle();
        $this->montarJavaScript();
        $this->fecharHead();
        $this->abrirBody('');
        $this->montarBarraLocalizacao('Erro');

        $arrComandos = array();

        if (!$this->isBolProducao()) {
            $arrComandos[] = '<input type="button" id="btnInfraDetalhesExcecao" name="btnInfraDetalhesExcecao" value="Exibir Detalhes" onclick="infraDetalhesExcecao();" class="infraButton" />';
        }

        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA
            || $this->getTipoPagina() == self::$TIPO_PAGINA_SEM_MENU) {
            $arrComandos[] = '<input type="button" id="btnInfraVoltarExcecao" name="btnInfraVoltarExcecao" value="Voltar" onclick="history.go(-1);" class="infraButton" />';
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
                $strTrace = str_replace("\\n", '', $strTrace);
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

    public function montarPaginaManutencao($strTitulo = 'Sistema em Manutenção', $strMensagem = '')
    {
        $this->setTipoPagina(self::$TIPO_PAGINA_SEM_MENU);
        $this->montarDocType();
        $this->abrirHtml();
        $this->abrirHead();
        $this->montarMeta();
        $this->montarTitle($this->getStrNomeSistema());
        $this->montarStyle();
        $this->montarJavaScript();
        $this->fecharHead();
        $this->abrirBody('');
        $this->montarBarraLocalizacao($strTitulo);

        echo '<div id="divInfraDetalhesManutencao" class="infraDetalhesManutencao">' .
            '<span class="infraDetalhesManutencao">' . nl2br($strMensagem) . '</span>' .
            '</div>' . "\n";

        $this->fecharBody();
        $this->fecharHtml();
        die;
    }

    public function montarErroBarraProgresso($strErro, $strDetalhes, $strTrace)
    {
        $strComandos = '';
        $strComandos .= 'document.getElementById(\'divInfraBarraLocalizacao\').innerHTML = \'Erro\';' . "\n";

        $strComandos .= 'document.getElementById(\'divInfraBarraComandosSuperior\').innerHTML = \'';
        if (!$this->isBolProducao()) {
            $strComandos .= $this->montarBotaoDetalhesExcecao() . ' ';
        }
        $strComandos .= '\';' . "\n";

        $strComandos .= 'document.getElementById(\'divInfraAreaDadosDinamica\').innerHTML = \'';

        $strComandos .= '<div id="divInfraExcecao" class="infraExcecao">' .
            '<span class="infraExcecao">' . nl2br(self::tratarHTML($strErro)) . '</span>' .
            '</div>';

        $strConteudoDetalhes = '';

        if (!$this->isBolProducao()) {
            $strComandos .= '<div id="divInfraDetalhesExcecao" class="infraDetalhesExcecao" style="visibility:hidden;">' .
                '<span id="spnDetalhesExcecao" class="infraDetalhesExcecao">';

            if ($strDetalhes != '') {
                $strConteudoDetalhes .= '<br /><br /><b>Detalhes:</b><br />' . nl2br(
                        self::tratarHTML(str_replace(',', ', ', $strDetalhes))
                    );
            }

            if ($strTrace != '') {
                $strTrace = str_replace("\\n", '', $strTrace);
                $strConteudoDetalhes .= '<br /><br /><b>Trilha de Processamento:</b><br />' . nl2br(
                        self::tratarHTML(InfraString::limparParametrosPhp($strTrace))
                    );
            }

            $strComandos .= '</span>';
            $strComandos .= '</div>';
        }

        $strComandos .= '\';';

        $this->abrirJavaScript();
        echo $strComandos;
        echo 'document.getElementById(\'spnDetalhesExcecao\').innerHTML = (new infraBase64()).decodificar(\'' . base64_encode(
                $strConteudoDetalhes
            ) . '\');';
        $this->fecharJavaScript();

        flush();
    }

    public function montarSpanNotificacao($strTexto, $strCor = '', $strAtributos = '')
    {
        $strSpan = '<span class="infraNotificacao';
        switch ($strCor) {
            case 'verde':
            case 'verde2':
            case 'azul':
            case 'amarelo':
            case 'cinza':
            case 'vermelho':
                $strSpan .= ' ' . $strCor;
        }
        $strSpan .= '"';
        if ($strAtributos != '') {
            $strSpan .= ' ' . $strAtributos;
        }
        $strSpan .= '>' . $strTexto . '</span>';
        return $strSpan;
    }

    public function setCheckbox($varValor, $varValorMarcado = 'S', $varValorDesmarcado = 'N')
    {
        if ($varValor === $varValorMarcado) {
            return ' checked="checked" ';
        }
        return '';
    }

    public function getCheckbox($strValorCheckbox, $varValorMarcado = 'S', $varValorDesmarcado = 'N')
    {
        if ($strValorCheckbox == 'on') {
            return $varValorMarcado;
        }
        return $varValorDesmarcado;
    }

    public function getProxTabBarraTribunal()
    {
        return $this->getProxTab($this->numTabBarraTribunal, self::$TAB_FIM_BARRA_TRIBUNAL);
    }

    public function getProxTabBarraSistema()
    {
        return $this->getProxTab($this->numTabBarraSistema, self::$TAB_FIM_BARRA_SISTEMA);
    }

    public function getProxTabBarraComandosSuperior()
    {
        return $this->getProxTab($this->numTabBarraComandosSuperior, self::$TAB_FIM_BARRA_COMANDOS_SUPERIOR);
    }

    public function getProxTabBarraComandosInferior()
    {
        return $this->getProxTab($this->numTabBarraComandosInferior, self::$TAB_FIM_BARRA_COMANDOS_INFERIOR);
    }

    public function getProxTabMenu()
    {
        return $this->getProxTab($this->numTabMenu, self::$TAB_FIM_MENU);
    }

    public function getProxTabDados()
    {
        return $this->getProxTab($this->numTabDados, self::$TAB_FIM_DADOS);
    }

    public function getProxTabTabela()
    {
        return $this->getProxTab($this->numTabTabela, self::$TAB_FIM_TABELA);
    }

    private function getProxTab(&$numTab, $numTabFim)
    {
        if ($numTab == $numTabFim) {
            return $numTab;
        }
        return $numTab++;
    }

    /**
     * Substitui os caracteres especiais do HTML &, <, > e "
     * Implementação de segurança sugerida pela OWASP - https://www.owasp.org/index.php/PHP_Security_Cheat_Sheet
     * @param $str - texto para tratamento
     * @param $imprimirViaJavascript - trata html para ser posteriormente renderizado por código Javascript
     * @param $encoding - encode utilizado no tratamento do texto a ser renderizado
     * @return string
     */

    public static function tratarHTML($str, $imprimirViaJavascript = false, $encoding = 'iso-8859-1')
    {
        //ENT_HTML401 = 0,  constante disponível somente no PHP 5.4

        if ($imprimirViaJavascript) {
            //Quando o texto é renderizado via Javascript, ele precisar sofrer duas conversões
            $str = htmlspecialchars($str, ENT_QUOTES, $encoding);
            $str = addslashes($str);
        }

        return htmlspecialchars($str, ENT_QUOTES, $encoding);
    }


    public function gerarCaptionTabela(
        $strEntidade,
        $numRegistros = null,
        $strPrefixo = 'Lista de ',
        $strSelecao = 'Infra'
    ) {
        $strRet = $strPrefixo . $strEntidade;

        if ($numRegistros !== null && ($strEntidade != '' || $strPrefixo != '')) {
            $strRet .= ' (';
        }

        if (isset($this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_TOTAL_REGISTROS]) && $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_TOTAL_REGISTROS] !== 0 && $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_TOTAL_REGISTROS] > $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_REGISTROS_POR_PAGINA]) {
            $numIni = ($this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL] * $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_REGISTROS_POR_PAGINA]) + 1;
            $numFim = $numIni + $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_REGISTROS_PAGINA_ATUAL] - 1;
            $strRet .= $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_TOTAL_REGISTROS] . ' registros - ' . $numIni . ' a ' . $numFim;
        } else {
            if ($numRegistros !== null) {
                if ($numRegistros == 1) {
                    $strRet .= '1 registro';
                } else {
                    $strRet .= $numRegistros . ' registros';
                }
            }
        }

        if ($numRegistros !== null && ($strEntidade != '' || $strPrefixo != '')) {
            $strRet .= ')';
        }

        $strRet .= ':';

        return $strRet;
    }

    public function salvarAcaoRetorno()
    {
        if (isset($_GET['acao'])) {
            //Vindo do menu
            if (!isset($_GET['acao_origem']) && !isset($_GET['acao_retorno'])) {
                $this->adicionarSessao($_GET['acao'], self::$POS_SES_ACAO_RETORNO, '');
            }

            if (isset($_GET['acao_retorno'])) {
                $this->adicionarSessao($_GET['acao'], self::$POS_SES_ACAO_RETORNO, $_GET['acao_retorno']);
            }
            //Se possui acao e acao_origem ignora
        }
    }

    public function getAcaoRetorno()
    {
        if (!isset($_GET['acao'])) {
            return 'principal';
        }

        $strAcaoRetorno = $this->recuperarSessao($_GET['acao'], self::$POS_SES_ACAO_RETORNO);
        if ($strAcaoRetorno == '') {
            return 'principal';
        }

        return $strAcaoRetorno;
    }

    /** Verifica se usuário escolheu alguma coluna da tabela para ordenar por ela (ou já tinha escolhido anteriormente).
     *  Salva internamente qual a ordenação escohida (bem como na sessão) para que na hora de listar o banco já traga com a ordenação
     * @param InfraDTO $objInfraDTO Objeto InfraDTO que será utilizado pra fazer a consulta no banco
     * @param string $strCampoOrdDefault Qual atributo deverá ser usado por padrão. Ex: NumProcesso
     * @param string $strTipoOrdDefault InfraDTO::$TIPO_ORDENACAO_ASC ou InfraDTO::$TIPO_ORDENACAO_DESC
     * @param bool $bolIgnorarSessao [opcional] A última ordenação é salva na sessão; Caso se queira forçar uma página a trazer uma ordenação específica, setar pra true
     * @param string $strSelecao [opcional] Uma página pode ter mais de uma tabela; Se for o caso, diferenciá-las passando diferentes strings aqui.
     * @throws InfraException
     */
    public function prepararOrdenacao(
        $objInfraDTO,
        $strCampoOrdDefault,
        $strTipoOrdDefault,
        $bolIgnorarSessao = false,
        $strSelecao = 'Infra'
    ) {
        if (!isset($this->arrSelecoes[$strSelecao])) {
            $this->inicializarSelecao($strSelecao);
        }

        $acaoGet = isset($_GET['acao']) ? $_GET['acao'] : '';

        //1 - verifica na postagem do formulario
        if (isset($_POST['hdn' . $strSelecao . 'CampoOrd'], $_POST['hdn' . $strSelecao . 'TipoOrd']) &&
            $_POST['hdn' . $strSelecao . 'CampoOrd'] != '' && $_POST['hdn' . $strSelecao . 'TipoOrd'] != '') {
            //pega para ordenar o array
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_CAMPO] = $_POST['hdn' . $strSelecao . 'CampoOrd'];
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_TIPO] = $_POST['hdn' . $strSelecao . 'TipoOrd'];

            //salva na sessao (caso o usuario vá para outra página ao retornar pode ordenar de novo pelo ultimo critério)
            $this->adicionarSessao(
                $acaoGet . '-' . $strSelecao,
                self::$POS_SES_ORDENACAO_CAMPO,
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_CAMPO]
            );
            $this->adicionarSessao(
                $acaoGet . '-' . $strSelecao,
                self::$POS_SES_ORDENACAO_TIPO,
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_TIPO]
            );
            //2 - Não há nada específico na postagem do formulário. Chegou pelo menu ou por outra pagina - verifica na sessão se havia ordenação previamente salva
        } elseif (!$bolIgnorarSessao && ($this->recuperarSessao(
                    $acaoGet . '-' . $strSelecao,
                    self::$POS_SES_ORDENACAO_TIPO
                ) != '' &&
                $this->recuperarSessao($acaoGet . '-' . $strSelecao, self::$POS_SES_ORDENACAO_CAMPO) != '')) {
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_CAMPO] = $this->recuperarSessao(
                $acaoGet . '-' . $strSelecao,
                self::$POS_SES_ORDENACAO_CAMPO
            );
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_TIPO] = $this->recuperarSessao(
                $acaoGet . '-' . $strSelecao,
                self::$POS_SES_ORDENACAO_TIPO
            );
            $objInfraDTO->setOrd(
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_CAMPO],
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_TIPO]
            );
            //3 - Nenhuma das anteriores, é a primeira vez: pega os parâmetros default e configura pra ordenar por eles
        } else {
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_CAMPO] = $strCampoOrdDefault;
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_TIPO] = $strTipoOrdDefault;
        }

        //O tratamento para o setOrd() está lá em InfraDTO::__call()
        $objInfraDTO->setOrd(
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_CAMPO],
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_TIPO]
        );

        $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_PREPARAR] = true;
    }

    public function prepararPaginacao(
        $objInfraDTO,
        $numRegistrosPorPagina = 50,
        $bolIgnorarSessao = false,
        $numPaginaAtual = null,
        $strSelecao = 'Infra'
    ) {
        //Cria atributos da seleção
        if (!isset($this->arrSelecoes[$strSelecao])) {
            $this->inicializarSelecao($strSelecao);
        }

        if ($this->getObjInfraSessao() == null) {
            $strHashAtual = md5($objInfraDTO->__toString() . $numRegistrosPorPagina);
        } else {
            $strHashAtual = md5(
                $objInfraDTO->__toString() . $numRegistrosPorPagina . $this->getObjInfraSessao()->getNumIdUnidadeAtual()
            );
        }

        //1 - verifica na postagem do formulario
        if (isset($_POST['hdn' . $strSelecao . 'PaginaAtual']) && $_POST['hdn' . $strSelecao . 'PaginaAtual'] != '') {
            if ($strHashAtual != $_POST['hdn' . $strSelecao . 'HashCriterios']) {
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL] = 0;
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_HASH_CRITERIOS] = $strHashAtual;
            } else {
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL] = $_POST['hdn' . $strSelecao . 'PaginaAtual'];
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_HASH_CRITERIOS] = $_POST['hdn' . $strSelecao . 'HashCriterios'];
            }

            //salva na sessao
            $this->adicionarSessao(
                $_GET['acao'] . '-' . $strSelecao,
                self::$POS_SES_PAGINA_ATUAL,
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL]
            );
            $this->adicionarSessao(
                $_GET['acao'] . '-' . $strSelecao,
                self::$POS_SES_HASH_CRITERIOS,
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_HASH_CRITERIOS]
            );
            //2 - Chegou pelo menu ou por outra pagina - verifica na sessão
        } elseif (!$bolIgnorarSessao && $this->recuperarSessao(
                $_GET['acao'] . '-' . $strSelecao,
                self::$POS_SES_PAGINA_ATUAL
            ) != '') {
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL] = $this->recuperarSessao(
                $_GET['acao'] . '-' . $strSelecao,
                self::$POS_SES_PAGINA_ATUAL
            );
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_HASH_CRITERIOS] = $this->recuperarSessao(
                $_GET['acao'] . '-' . $strSelecao,
                self::$POS_SES_HASH_CRITERIOS
            );

            if ($strHashAtual != $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_HASH_CRITERIOS]) {
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL] = 0;
                $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_HASH_CRITERIOS] = $strHashAtual;
                $this->adicionarSessao(
                    $_GET['acao'] . '-' . $strSelecao,
                    self::$POS_SES_PAGINA_ATUAL,
                    $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL]
                );
                $this->adicionarSessao(
                    $_GET['acao'] . '-' . $strSelecao,
                    self::$POS_SES_HASH_CRITERIOS,
                    $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_HASH_CRITERIOS]
                );
            }
            //primeira vez pega os parâmetros
        } else {
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL] = 0;
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_HASH_CRITERIOS] = $strHashAtual;
        }

        if ($numPaginaAtual !== null) {
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL] = $numPaginaAtual;
        }
        $objInfraDTO->setNumMaxRegistrosRetorno($numRegistrosPorPagina);
        $objInfraDTO->setNumPaginaAtual($this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL]);

        //Salva registros por página para verificar posteriormente se mostra ou não o botão próxima
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_REGISTROS_POR_PAGINA] = $numRegistrosPorPagina;

        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PREPARAR] = true;
    }

    /**
     * @param InfraDTO $objInfraDTO
     * @param string $strSelecao
     */
    public function processarPaginacao($objInfraDTO, $strSelecao = 'Infra')
    {
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_TOTAL_REGISTROS] = $objInfraDTO->getNumTotalRegistros();
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_REGISTROS_PAGINA_ATUAL] = $objInfraDTO->getNumRegistrosPaginaAtual(
        );
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PROCESSAR] = true;
    }

    /** Retorna o conteúdo de um table header contendo a descrição da coluna e setas pra cima e pra baixo, que se
     *  clicadas disparam o javascript infraAcaoOrdenar();
     *  Obs.:  Não gera o <th> ou o </th>, apenas o que vai dentro das tags.
     *  Obs.2: Eventualmente essa própria função já ordena o arrDTO, caso o atributo escolhido não seja de banco (pois aí o listar() não ordenou)
     *  Obs.3: O table header em si é gerado em forma de tabela também (pra alinhar as setas).
     * @param InfraDTO $objInfraDTO Objeto derivado de InfraDTO que foi utilizado pra fazer a consulta no banco
     * @param string $strRotulo Titulo da coluna. Ex: "Assunto"
     * @param string $strCampo Nome do atributo do DTO (sem o prefixo) que contém a informação desta coluna. Ex: "DesAssunto"
     * @param InfraDTO[] $arrObjInfraDTO [out]  Array que contém os itens que serão mostrados na tabela. Utilizado para eventualmente ordenar este array (Obs.2)
     * @param bool $bolForcarOrdenacaoMemoria [opcional] Força que essa função ao gerar o th ordene o $arrObjInfraDTO (independente de já ter sido feito antes)
     * @param string $strSelecao [opcional] Uma página pode ter mais de uma tabela; Se for o caso, diferenciá-las passando diferentes strings aqui.
     * @param string|null $strCustomCallbackJs Callback a ser executado ao invés de submeter o formulário, se não nulo.
     * @return string
     * @throws InfraException
     */
    public function getThOrdenacao(
        $objInfraDTO,
        $strRotulo,
        $strCampo,
        &$arrObjInfraDTO,
        $bolForcarOrdenacaoMemoria = false,
        $strSelecao = 'Infra',
        $strCustomCallbackJs = null
    ) {
        $varTabIndex = $this->arrSelecoes[$strSelecao][self::$POS_SEL_TAB_INDEX];
        $isOrdenacaoAscAtiva = ($this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_CAMPO] == $strCampo && $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_TIPO] == InfraDTO::$TIPO_ORDENACAO_ASC);
        $isOrdenacaoDescAtiva = ($this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_CAMPO] == $strCampo && $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_TIPO] == InfraDTO::$TIPO_ORDENACAO_DESC);

        if ($isOrdenacaoAscAtiva) {
            //Verifica se o campo foi ordenado pelo banco (se era atributo de banco, o banco ordena)
            $arrAtributos = $objInfraDTO->getArrAtributos();
            if ($arrAtributos[$strCampo][InfraDTO::$POS_ATRIBUTO_CAMPO_SQL] == null || $bolForcarOrdenacaoMemoria) {
                //ordenar (para casos onde o atributo não é de banco)
                InfraArray::ordenarArrInfraDTO($arrObjInfraDTO, $strCampo, InfraArray::$TIPO_ORDENACAO_ASC);
            }
        }

        if ($isOrdenacaoDescAtiva) {
            //Verifica se o campo foi ordenado pelo banco (se era atributo de banco, o banco ordena)
            $arrAtributos = $objInfraDTO->getArrAtributos();
            if ($arrAtributos[$strCampo][InfraDTO::$POS_ATRIBUTO_CAMPO_SQL] == null || $bolForcarOrdenacaoMemoria) {
                //ordenar (para casos onde o atributo não é de banco)
                InfraArray::ordenarArrInfraDTO($arrObjInfraDTO, $strCampo, InfraArray::$TIPO_ORDENACAO_DESC);
            }
        }

        return $this->getInfraPaginaRendererFactory()->createThOrdenacao(
            $this,
            $varTabIndex,
            $strSelecao,
            $strCampo,
            $isOrdenacaoDescAtiva,
            $isOrdenacaoAscAtiva,
            $strRotulo,
            $strCustomCallbackJs
        );
    }


    public function montarMenuSessao($strNomeMenu)
    {
        if ($this->getObjInfraSessao() !== null) {
            $arrMenu = $this->getObjInfraSessao()->getArrMenu($strNomeMenu);
            return ($this->tipoMenu == self::$MENU_NORMAL || $this->tipoMenu == self::$MENU_BOOTSTRAP ? $this->montarMenuArray(
                $arrMenu
            ) : $this->montarSmartMenuArray($arrMenu));
        }
        return '';
    }

    public function montarMenuArray($arrMenu, $n = '')
    {
        $strMenu = '';
        $objInfraSessao = $this->getObjInfraSessao();

        $arrParametrosRepasseLink = $objInfraSessao->getArrParametrosRepasseLink();
        $objInfraSessao->setArrParametrosRepasseLink(null);

        $numLimite = is_array($arrMenu) ? count($arrMenu) : 0;
        $numIdUl = 0;

        //--^http://krebs.trf4.gov.br/webdas/teste_infra_php/controlador.php?acao=teste_geral^^Infra PHP - Geral^_blank

        if ($numLimite > 0) {
            $strMenu .= '<ul id="infraMenuRaizes' . $n . '">' . "\n";
        }

        $strProximaLinha = '';

        for ($i = 0; $i < $numLimite; $i++) {
            if ($i == 0) {
                $strLinhaAtual = explode('^', $arrMenu[$i]);
            } else {
                $strLinhaAtual = $strProximaLinha;
            }

            //MONTA O LINK DE ACORDO COM O INÍCIO DA URL DO MENU

            //Por causa do MUMPS que dava conflito com os ^ da montagem do menu
            $strLinhaAtual[1] = str_replace('*', '^', $strLinhaAtual[1]);

            //Por causa do MUMPS que tem que abrir os programas numa div especifica
            $strTarget = '';
            if (isset($strLinhaAtual[4])) {
                $strTarget = 'target="' . $strLinhaAtual[4] . '"';
            }

            if ($strTarget == 'target=""') {
                $strTarget = '';
            }


            $strMenu .= '<li>';

            $strMenu .= '<a ';

            if (strlen($strLinhaAtual[0]) == 1) {
                $strMenu .= 'class="infraMenuRaiz"';
            } else {
                $strMenu .= 'class="infraMenuFilho"';
            }


            if (substr($strLinhaAtual[1], 0, 4) == 'java') {
                $strMenu .= ' href="' . $strLinhaAtual[1] . '"';
            } elseif (/*(substr($strLinhaAtual[1],0,4) == 'http') || */
                substr($strLinhaAtual[1], 0, 4) == 'mail') {
                $strMenu .= ' href="' . $strLinhaAtual[1] . '" ' . $strTarget;
            } else {
                if ($strLinhaAtual[1] != '#') {
                    $strMenu .= ' href="' . $objInfraSessao->assinarLink($strLinhaAtual[1]) . '"';
                } else {
                    $strMenu .= ' href="#"';
                }

                $strMenu .= ' ' . $strTarget;
            }

            if (trim($strLinhaAtual[2]) != '') {
                $strMenu .= ' title="' . str_replace('&amp;nbsp;', '&nbsp;', self::tratarHTML($strLinhaAtual[2])) . '"';
            }

            $strMenu .= '>';

            $strMenu .= '<div class="infraItemMenu">';
            $strMenu .= '<div class="infraRotuloMenu">';
            $strMenu .= str_replace('&amp;nbsp;', '&nbsp;', self::tratarHTML($strLinhaAtual[3]));
            $strMenu .= '</div>';

            if (($i + 1) < $numLimite) {
                $strProximaLinha = explode('^', $arrMenu[$i + 1]);
                $dif = strlen($strLinhaAtual[0]) - strlen($strProximaLinha[0]);
            } else {
                $dif = strlen($strLinhaAtual[0]) - 1;
            }

            //Mesmo nivel fecha li
            if ($dif === 0) {
                $strMenu .= '</div></a>';

                $strMenu .= '</li>' . "\n";
                //Nivel mais interno - abre ul
            } elseif ($dif < 0) {
                $strMenu .= '<div class="infraSetaMenu">&raquo;</div></div></a>';
                $strMenu .= "\n" . '<ul id="infraMenuUL' . $numIdUl++ . '">' . "\n";
                //Nivel mais externo - fecha li-ul
            } else {
                $strMenu .= '</div></a>';

                while ($dif > 0) {
                    $strMenu .= '</li>' . "\n";
                    $strMenu .= '</ul>' . "\n";
                    $dif--;
                }
                $strMenu .= '</li>' . "\n";
            }
        }

        if ($numLimite > 0) {
            $strMenu .= '</ul>' . "\n";
        }

        $objInfraSessao->setArrParametrosRepasseLink($arrParametrosRepasseLink);

        return $strMenu;
    }

    public function montarSmartMenuArray($arrMenu, $n = '')
    {
        $strMenu = '';
        $numLimite = is_array($arrMenu) ? count($arrMenu) : 0;
        $objInfraSessao = $this->getObjInfraSessao();

        $arrParametrosRepasseLink = $objInfraSessao->getArrParametrosRepasseLink();
        $objInfraSessao->setArrParametrosRepasseLink(null);

        if ($numLimite > 0) {
            $strMenu .= '<ul id="main-menu" class="sm sm-vertical sm-' . $this->obterSmartMenuClass(
                ) . ' sm-' . $this->obterSmartMenuClass() . '-vertical">' . "\n";
        }

        $strProximaLinha = '';

        for ($i = 0; $i < $numLimite; $i++) {
            if ($i == 0) {
                $strLinhaAtual = explode('^', $arrMenu[$i]);
            } else {
                $strLinhaAtual = $strProximaLinha;
            }

            //MONTA O LINK DE ACORDO COM O INÍCIO DA URL DO MENU

            //Por causa do MUMPS que dava conflito com os ^ da montagem do menu
            $strLinhaAtual[1] = str_replace('*', '^', $strLinhaAtual[1]);

            //Por causa do MUMPS que tem que abrir os programas numa div especifica
            $strTarget = '';
            if (isset($strLinhaAtual[4])) {
                $strTarget = ' target="' . $strLinhaAtual[4] . '"';
            }

            if ($strTarget == ' target=""') {
                $strTarget = '';
            }


            $strMenu .= '<li>';

            $strMenu .= '<a';

            if (substr($strLinhaAtual[1], 0, 4) == 'java') {
                $strMenu .= ' href="' . $strLinhaAtual[1] . '"';
            } elseif (/*(substr($strLinhaAtual[1],0,4) == 'http') || */
                substr($strLinhaAtual[1], 0, 4) == 'mail') {
                $strMenu .= ' href="' . $strLinhaAtual[1] . '"' . $strTarget;
            } else {
                if ($strLinhaAtual[1] != '#') {
                    $strMenu .= ' href="' . $objInfraSessao->assinarLink($strLinhaAtual[1]) . '"';
                } else {
                    $strMenu .= ' href="#"';
                }

                $strMenu .= $strTarget;
            }

            if (trim($strLinhaAtual[2]) != '') {
                $strMenu .= ' title="' . str_replace('&amp;nbsp;', '&nbsp;', self::tratarHTML($strLinhaAtual[2])) . '"';
            }

            $strMenu .= '>';

            $strMenu .= str_replace('&amp;nbsp;', '&nbsp;', self::tratarHTML($strLinhaAtual[3]));

            if (($i + 1) < $numLimite) {
                $strProximaLinha = explode('^', $arrMenu[$i + 1]);
                $dif = strlen($strLinhaAtual[0]) - strlen($strProximaLinha[0]);
            } else {
                $dif = strlen($strLinhaAtual[0]) - 1;
            }

            //Mesmo nivel fecha li
            if ($dif === 0) {
                $strMenu .= '</a></li>' . "\n";
                //Nivel mais interno - abre ul
            } elseif ($dif < 0) {
                $strMenu .= '</a>';
                $strMenu .= "\n" . '<ul>' . "\n";
                //Nivel mais externo - fecha li-ul
            } else {
                $strMenu .= '</a>';

                while ($dif > 0) {
                    $strMenu .= '</li>' . "\n";
                    $strMenu .= '</ul>' . "\n";
                    $dif--;
                }
                $strMenu .= '</li>' . "\n";
            }
        }

        if ($numLimite > 0) {
            $strMenu .= '</ul>' . "\n";
        }

        $objInfraSessao->setArrParametrosRepasseLink($arrParametrosRepasseLink);

        return $strMenu;
    }

    public function montarLinkSair($strLink = null, $strIcone = null)
    {
        $str = '<a id="lnkSairSistema" href="';
        if ($strLink != null) {
            $str .= $strLink;
        } else {
            $str .= $this->getObjInfraSessao()->getStrPaginaLogin();
        }

        if ($strIcone == null) {
            $strIcone = $this->getDiretorioImagensGlobal() . '/sair.gif';
        }

        $str .= '" title="Sair do Sistema"  tabindex="' . $this->getProxTabBarraSistema(
            ) . '"><img src="' . $strIcone . '" title="Sair do Sistema" alt="Sair do Sistema" class="infraImg" /></a>';
        return $str;
    }

    public function montarLinkAjuda($strLink, $strIcone = null)
    {
        $str = '';
        if ($strLink != null) {
            if ($strIcone == null) {
                $strIcone = $this->getIconeAjuda();
            }


            $str = '<a id="lnkAjudaSistema" href="' . $strLink . '" target="_blank" title="Ajuda"  tabindex="' . $this->getProxTabBarraSistema(
                ) . '"><img src="' . $strIcone . '" title="Ajuda" alt="Ajuda" class="infraImg" /></a>';
        }
        return $str;
    }

    public function getDiretorioCssLocal()
    {
        return 'css';
    }

    public function getDiretorioCssGlobal()
    {
        return '/infra_css';
    }

    public function getDiretorioImagensLocal()
    {
        return $this->getDiretorioImagens();
    }

    //compatibilidade
    public function getDiretorioImagens()
    {
        return 'imagens';
    }

    public function getDiretorioImagensGlobal()
    {
        return $this->getDiretorioCssGlobal() . '/imagens';
    }

    public function getDiretorioSvgLocal()
    {
        return 'svg';
    }

    public function getDiretorioSvgGlobal()
    {
        return $this->getDiretorioCssGlobal() . '/svg';
    }

    //compatibilidade
    public function getDiretorioJavaScript()
    {
        return '/infra_js';
    }

    public function getDiretorioJavaScriptGlobal()
    {
        return $this->getDiretorioJavaScript();
    }

    public function getDiretorioJavaScriptLocal()
    {
        return 'js';
    }

    public function getArquivoJavaScriptPagina()
    {
        return 'InfraPagina.js';
    }

    public function verificarSelecao($strAcao)
    {
        if (isset($_GET['acao_origem'])) {
            if ($_GET['acao_origem'] == $strAcao) {
                $this->setTipoPagina(self::$TIPO_PAGINA_SIMPLES);
            } elseif ($_GET['acao_origem'] == $_GET['acao'] && isset($_POST['hdnInfraTipoPagina'])) {
                $this->setTipoPagina($_POST['hdnInfraTipoPagina']);
            }
        }
    }

    public function prepararSelecao($strAcao)
    {
        if ($_GET['acao'] != $strAcao) {
            if (!isset($_GET['acao_origem']) || $_GET['acao_origem'] != $strAcao) {
                return;
            }
        }

        $this->setTipoPagina(self::$TIPO_PAGINA_SIMPLES);

        //Na primeira vez que entra na pagina de seleção recebe o tipo
        if (isset($_GET['tipo_selecao'])) {
            //Configura o tipo de seleção
            $this->setTipoSelecao($_GET['tipo_selecao']);
            $this->adicionarSessao($strAcao, self::$POS_SES_SEL_TIPO, $this->getTipoSelecao());
            $this->adicionarSessao($strAcao, self::$POS_SES_SEL_DADOS, '');

            //Limpa campos de retorno
            $this->adicionarSessao($strAcao, self::$POS_SES_SEL_ID_OBJECT, '');
            $this->adicionarSessao($strAcao, self::$POS_SES_SEL_ID_SELECT, '');
            $this->adicionarSessao($strAcao, self::$POS_SES_SEL_ID_HIDDEN, '');
            $this->adicionarSessao($strAcao, self::$POS_SES_SEL_ID_TEXT, '');
            $this->adicionarSessao($strAcao, self::$POS_SES_SEL_ID_TEXTAREA, '');

            //Armazena campos de entrada/retorno recebidos
            if (isset($_GET['id_object'])) {
                $this->adicionarSessao($strAcao, self::$POS_SES_SEL_ID_OBJECT, $_GET['id_object']);
            }

            if (isset($_GET['id_select'])) {
                $this->adicionarSessao($strAcao, self::$POS_SES_SEL_ID_SELECT, $_GET['id_select']);
            }

            if (isset($_GET['id_hidden'])) {
                $this->adicionarSessao($strAcao, self::$POS_SES_SEL_ID_HIDDEN, $_GET['id_hidden']);
            }

            if (isset($_GET['id_text'])) {
                $this->adicionarSessao($strAcao, self::$POS_SES_SEL_ID_TEXT, $_GET['id_text']);
            }

            if (isset($_GET['id_textarea'])) {
                $this->adicionarSessao($strAcao, self::$POS_SES_SEL_ID_TEXTAREA, $_GET['id_textarea']);
            }
        } else {
            //Voltando para tela de seleção (após novo por exemplo)
            $this->setTipoSelecao($this->recuperarSessao($strAcao, self::$POS_SES_SEL_TIPO));

            //Se esta na pagina de selecao salva dados
            if (isset($_POST['hdnInfraItensSelecionados'])) {
                $this->adicionarSessao($strAcao, self::$POS_SES_SEL_DADOS, $_POST['hdnInfraItensSelecionados']);
            } else {
                $_POST['hdnInfraItensSelecionados'] = $this->recuperarSessao($strAcao, self::$POS_SES_SEL_DADOS);
            }
        }
    }

    public function isBolPaginaSelecao()
    {
        if ($this->getTipoSelecao() !== self::$TIPO_SELECAO_NENHUM) {
            return true;
        }
        return false;
    }

    public function salvarCamposPost($arrCampos, $strIdentificador = null)
    {
        foreach ($arrCampos as $campo) {
            if (isset($_POST[$campo])) {
                $this->salvarCampo($campo, $_POST[$campo], $strIdentificador);
            }
        }
    }

    public function salvarCampo($strCampo, $strValor, $strIdentificador = null)
    {
        $arr = $this->recuperarSessao('infra_global', self::$POS_SES_CAMPOS_INTERFACE);
        if (!is_array($arr)) {
            $arr = array();
        }

        if ($strIdentificador == null) {
            $strIdentificador = $this->montarIdentificadorCampo();
        }

        if ($strIdentificador != null) {
            $arr[$strIdentificador][$strCampo] = $strValor;
        } else {
            $arr[$strCampo] = $strValor;
        }

        $this->adicionarSessao('infra_global', self::$POS_SES_CAMPOS_INTERFACE, $arr);
    }

    public function limparCampos()
    {
        //Pode não ter se logado
        if ($this->getObjInfraSessao() != null) {
            $strOrgao = $this->getObjInfraSessao()->getStrSiglaOrgaoSistema();
            $strSistema = $this->getObjInfraSessao()->getStrSiglaSistema();
            if (isset($_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema]['infra_global'][self::$POS_SES_CAMPOS_INTERFACE])) {
                unset($_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema]['infra_global'][self::$POS_SES_CAMPOS_INTERFACE]);
            }
        }
    }

    public function recuperarCampo($strCampo, $varDefault = '', $strIdentificador = null)
    {
        $ret = '';

        if ($varDefault !== '') {
            $ret = $varDefault;
        }

        $arr = $this->recuperarSessao('infra_global', self::$POS_SES_CAMPOS_INTERFACE);

        if (is_array($arr)) {
            if ($strIdentificador == null) {
                $strIdentificador = $this->montarIdentificadorCampo();
            }

            if ($strIdentificador != null) {
                if (isset($arr[$strIdentificador][$strCampo])) {
                    $ret = $arr[$strIdentificador][$strCampo];
                }
            } else {
                if (isset($arr[$strCampo])) {
                    $ret = $arr[$strCampo];
                }
            }
        }
        return $ret;
    }

    public function montarIdentificadorCampo()
    {
        return null;
    }

    private function getChaveSessaoSistema()
    {
        return $this->getObjInfraSessao()->getStrSiglaOrgaoSistema() . '/' . $this->getObjInfraSessao(
            )->getStrSiglaSistema();
    }

    protected function adicionarSessao($strAcao, $numPosicao, $varValor)
    {
        if ($this->getObjInfraSessao() != null) {
            $strOrgao = $this->getObjInfraSessao()->getStrSiglaOrgaoSistema();
            $strSistema = $this->getObjInfraSessao()->getStrSiglaSistema();

            if ($varValor === '') {
                if (isset($_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema][$strAcao][$numPosicao]) && $_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema][$strAcao][$numPosicao] !== '') {
                    unset($_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema][$strAcao][$numPosicao]);
                }
            } else {
                if (!isset($_SESSION['INFRA_PAGINA'])) {
                    $_SESSION['INFRA_PAGINA'] = array();
                }
                if (!isset($_SESSION['INFRA_PAGINA'][$strOrgao])) {
                    $_SESSION['INFRA_PAGINA'][$strOrgao] = array();
                }

                if (!isset($_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema])) {
                    $_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema] = array();
                }

                if (!isset($_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema][$strAcao])) {
                    $_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema][$strAcao] = array();
                }
                $_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema][$strAcao][$numPosicao] = $varValor;
            }
        }
    }

    protected function recuperarSessao($strAcao, $numPosicao)
    {
        $strOrgao = $this->getObjInfraSessao()->getStrSiglaOrgaoSistema();
        $strSistema = $this->getObjInfraSessao()->getStrSiglaSistema();

        if (!isset($_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema][$strAcao][$numPosicao])) {
            return '';
        }
        return $_SESSION['INFRA_PAGINA'][$strOrgao][$strSistema][$strAcao][$numPosicao];
    }

    public function adicionarSelecionado($strId, $strNomeSelecao = 'Infra')
    {
        if ($_POST['hdn' . $strNomeSelecao . 'ItensSelecionados'] != '') {
            $_POST['hdn' . $strNomeSelecao . 'ItensSelecionados'] = $strId . ',' . $_POST['hdn' . $strNomeSelecao . 'ItensSelecionados'];
        } else {
            $_POST['hdn' . $strNomeSelecao . 'ItensSelecionados'] = $strId;
        }
    }

    public function getTituloSelecao($strTituloSelSimples, $strTituloSelMultipla)
    {
        $numTipoSelecao = $this->getTipoSelecao();

        if ($numTipoSelecao == self::$TIPO_SELECAO_SIMPLES) {
            return $strTituloSelSimples;
        }

        if ($numTipoSelecao == self::$TIPO_SELECAO_MULTIPLA) {
            return $strTituloSelMultipla;
        }

        return '';
    }

    public function setRadio($varValor, $varValorRadio)
    {
        if ($varValor === $varValorRadio) {
            return ' checked="checked" value="' . $varValorRadio . '" ';
        }

        return ' value="' . $varValorRadio . '" ';
    }

    public function montarLinkMenu()
    {
        if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA) {
            return '<a id="lnkMenuSistema" onclick="infraMenuSistema(false);" title="Exibir/Ocultar Menu do Sistema" tabindex="' . $this->getProxTabBarraSistema(
                ) . '"><img src="' . $this->getDiretorioImagensGlobal(
                ) . '/menu.gif" title="Exibir/Ocultar Menu do Sistema" alt="Exibir/Ocultar Menu do Sistema" class="infraImg" /></a>';
        }
    }

    public function montarLinkUsuario($strSigla = null, $strOrgao = null, $strNome = null, $strIcone = null)
    {
        $strLinkAcessos = '';

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

                if ($this->getObjInfraSessao()->verificarPermissao('infra_acesso_usuario_listar')) {
                    $strLinkAcessos = 'href="' . $this->getObjInfraSessao()->assinarLink(
                            'controlador.php?acao=infra_acesso_usuario_listar'
                        ) . '"';
                }
            }
        }

        $strDados = '';
        $strSeparador = '';

        if ($strNome !== null) {
            $strDados .= $strNome;
        }

        if ($strSigla !== null || $strOrgao !== null) {
            $strDados .= ' (';

            if ($strSigla !== null) {
                $strDados .= $strSigla;
                $strSeparador = '/';
            }

            if ($strOrgao !== null) {
                $strDados .= $strSeparador . $strOrgao;
            }

            $strDados .= ')';
        }

        if ($strDados === '') {
            return '';
        }

        if ($strIcone == null) {
            $strIcone = $this->getDiretorioImagensGlobal() . '/usuario.gif';
        }

        return '<a id="lnkUsuarioSistema" ' . $strLinkAcessos . ' title="' . $strDados . '" tabindex="' . $this->getProxTabBarraSistema(
            ) . '"><img src="' . $strIcone . '" title="' . $strDados . '" alt="' . $strDados . '" class="infraImg" /></a>';
    }

    public function montarSelectUnidades()
    {
        $str = '';

        $objInfraSessao = $this->getObjInfraSessao();

        if ($objInfraSessao !== null) {
            $arrParametrosRepasseLink = $objInfraSessao->getArrParametrosRepasseLink();
            $objInfraSessao->setArrParametrosRepasseLink(null);

            //$str .= '<!-- '.$_SERVER['REQUEST_URI'].' -->'."\n";
            $objInfraSessao->setStrUltimaPagina(
                str_replace('infra_sip=true', 'infra_sip=false', $_SERVER['REQUEST_URI'])
            );

            //$str .= '<!-- '.$objInfraSessao->getStrUltimaPagina().' -->'."\n";
            $str .= "\n";
            $str .= '<form id="frmInfraSelecionarUnidade" method="post" action="' . $objInfraSessao->assinarLink(
                    $objInfraSessao->getStrPaginaInicial()
                ) . '" style="display:inline;">' . "\n";
            $str .= '<label id="lblInfraUnidades" for="selInfraUnidades" accesskey="."></label>' . "\n";
            $str .= '<select name="selInfraUnidades" id="selInfraUnidades" onchange="this.form.submit();" style="background-color:white;" tabindex="' . $this->getProxTabBarraSistema(
                ) . '">' . "\n";

            if ($objInfraSessao->getObjInfraSessaoDTO() !== null) {
                $arrIdUnidades = array_keys($objInfraSessao->getArrUnidades());
                foreach ($arrIdUnidades as $numIdUnidade) {
                    $str .= '<option value="' . $numIdUnidade . '"';
                    if ($objInfraSessao->getNumIdUnidadeAtual() == $numIdUnidade) {
                        $str .= ' selected="selected" ';
                    }
                    $str .= '>' . self::tratarHTML(
                            $objInfraSessao->getStrSiglaUnidade($numIdUnidade)
                        ) . '</option>' . "\n";
                }
            }
            $str .= '</select>' . "\n";
            $str .= '</form>' . "\n";

            $objInfraSessao->setArrParametrosRepasseLink($arrParametrosRepasseLink);
        }
        return $str;
    }

    public static function formatarParametrosJavaScript($str, $bolTratarHTML = true)
    {
        $str = str_replace('\\n', "\n", $str);
        $str = str_replace('\\', '\\\\', $str);
        $str = str_replace('"', '\"', $str);
        $str = str_replace("'", '\\\'', $str);
        $str = str_replace("\n", '\\n', $str);
        $str = str_replace("\r", '\\r', $str);

        if ($bolTratarHTML) {
            $str = self::tratarHTML($str);
        }

        return $str;
    }

    public function processarUpload($strCampoArquivo, $strDirUpload, $bolArquivoTemporarioIdentificado = true)
    {
        $ret = '';
        try {
            $_FILES[$strCampoArquivo]['name'] = str_replace(chr(0), '', $_FILES[$strCampoArquivo]['name']);

            $arrStrNome = explode('.', $_FILES[$strCampoArquivo]['name']);

            if (count($arrStrNome) < 2) {
                $ret = 'ERRO#Nome do arquivo não possui extensão.';
            } else {
                if (in_array(
                    str_replace(' ', '', InfraString::transformarCaixaBaixa($arrStrNome[count($arrStrNome) - 1])),
                    array('php', 'php3', 'php4', 'phtml', 'sh', 'cgi')
                )) {
                    $ret = 'ERRO#Extensão de arquivo não permitida.';
                } else {
                    if (!isset($_FILES[$strCampoArquivo])) {
                        $ret = 'ERRO#Campo de arquivo "' . $strCampoArquivo . '" não foi enviado.';
                    } else {
                        if ($_FILES[$strCampoArquivo]['error'] != UPLOAD_ERR_OK) {
                            switch ($_FILES[$strCampoArquivo]['error']) {
                                case UPLOAD_ERR_INI_SIZE:
                                    $ret = 'ERRO#Tamanho do arquivo "' . $_FILES[$strCampoArquivo]['name'] . '" excedeu o limite de ' . ini_get(
                                            'upload_max_filesize'
                                        ) . 'b permitido pelo servidor.';
                                    break;

                                case UPLOAD_ERR_FORM_SIZE:
                                    $ret = 'ERRO#Tamanho do arquivo "' . $_FILES[$strCampoArquivo]['name'] . '" excedeu o limite de ' . $_POST['MAX_FILE_SIZE'] . ' bytes permitido pelo navegador.';
                                    break;

                                case UPLOAD_ERR_PARTIAL:
                                    $ret = 'ERRO#Apenas uma parte do arquivo foi transferida.';
                                    break;

                                case UPLOAD_ERR_NO_FILE:
                                    $ret = 'ERRO#Arquivo não foi transferido.';
                                    break;

                                case UPLOAD_ERR_NO_TMP_DIR:
                                    $ret = 'ERRO#Diretório temporário para transferência não encontrado.';
                                    break;

                                case UPLOAD_ERR_CANT_WRITE:
                                    $ret = 'ERRO#Erro gravando dados no servidor.';
                                    break;

                                case UPLOAD_ERR_EXTENSION:
                                    $ret = 'ERRO#Transferência interrompida.';
                                    break;

                                default:
                                    $ret = 'ERRO# Erro desconhecido transferindo arquivo [' . $_FILES[$strCampoArquivo]['error'] . '].';
                                    break;
                            }
                        } else {
                            $bolConteudoPermitido = true;

                            if (function_exists('finfo_open')) {
                                $bolConteudoPermitido = InfraUtil::verificarConteudoPermitidoArquivo(
                                    $_FILES[$strCampoArquivo]['tmp_name']
                                );
                            }

                            if (!$bolConteudoPermitido) {
                                $ret = 'ERRO#Tipo de arquivo não permitido.';
                            } else {
                                if ($this->getObjInfraSessao() !== null) {
                                    $strUsuario = $this->getObjInfraSessao()->getStrSiglaUsuario();
                                } else {
                                    $strUsuario = 'anonimo';
                                }

                                $numTimestamp = time();

                                if ($bolArquivoTemporarioIdentificado) {
                                    //[usuario][ddmmaaaa-hhmmss]-nomearquivo
                                    $strArquivo = InfraUtil::montarNomeArquivoUpload(
                                        $strUsuario,
                                        $numTimestamp,
                                        $_FILES[$strCampoArquivo]['name']
                                    );
                                } else {
                                    $strArquivo = md5(
                                        $strUsuario . mt_rand() . $numTimestamp . mt_rand(
                                        ) . $_FILES[$strCampoArquivo]['name'] . uniqid(mt_rand(), true)
                                    );
                                }

                                if (file_exists($strDirUpload . '/' . $strArquivo)) {
                                    $ret = 'ERRO#Arquivo "' . $strArquivo . '" já existe no diretório de upload.';
                                } else {
                                    try {
                                        if (!move_uploaded_file(
                                            $_FILES[$strCampoArquivo]['tmp_name'],
                                            $strDirUpload . '/' . $strArquivo
                                        )) {
                                            $ret = 'ERRO#Erro movendo arquivo para o diretório de upload.';
                                        } else {
                                            $ret = '';
                                            $ret .= $strArquivo . '#';
                                            $ret .= $_FILES[$strCampoArquivo]['name'] . '#';
                                            $ret .= $_FILES[$strCampoArquivo]['type'] . '#';
                                            $ret .= $_FILES[$strCampoArquivo]['size'] . '#';
                                            $ret .= date('d/m/Y H:i:s', $numTimestamp);
                                            $ret .= '#';

                                            if (!chmod($strDirUpload . '/' . $strArquivo, 0660)) {
                                                $ret = 'ERRO#Erro alterando permissões do arquivo no diretório de upload.';
                                            }
                                        }
                                    } catch (Exception $e) {
                                        if (stripos($e->__toString(), 'PERMISSION DENIED') !== false) {
                                            $ret = 'ERRO#Permissão negada tentando mover o arquivo para o diretório de upload.';
                                        }
                                        throw $e;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $ret = 'ERRO# ' . $e->__toString();
        }

        if (substr($ret, 0, 6) == 'ERRO# ' && $this->getObjInfraLog() instanceof InfraLog) {
            $strTextoLog = '';
            if ($this->getObjInfraSessao() !== null) {
                if ($this->getObjInfraSessao()->getStrSiglaUsuario() !== null) {
                    $strTextoLog .= 'Usuário: ' . $this->getObjInfraSessao()->getStrSiglaUsuario();

                    if ($this->getObjInfraSessao()->getStrSiglaOrgaoUsuario() !== null) {
                        $strTextoLog .= '/' . $this->getObjInfraSessao()->getStrSiglaOrgaoUsuario();
                    }
                }
            }

            $strTextoLog .= "\nServidor: " . $_SERVER['SERVER_NAME'] . ' (' . $_SERVER['SERVER_ADDR'] . ')';
            $strTextoLog .= "\nErro: " . substr($ret, 5);
            $strTextoLog .= "\nNavegador: " . $_SERVER['HTTP_USER_AGENT'];
            if (is_array($_GET)) {
                $strTextoLog .= "\nGET:\n" . print_r($_GET, true);
            }

            if (is_array($_FILES)) {
                $strTextoLog .= "\nFILES:\n" . print_r($_FILES, true);
            }

            try {
                $this->getObjInfraLog()->gravar($strTextoLog);
            } catch (Exception $e) {
                //Ignora, erro mais provavel queda da conexao com o banco
            }
        }

        echo $ret;
    }

    public static function montarAncora($var)
    {
        $strAncora = '';

        if (is_array($var)) {
            if (count($var) > 0) {
                $strAncora = '#ID-' . implode(',', $var);
            }
        } elseif (!InfraString::isBolVazia($var)) {
            $strAncora = '#ID-' . $var;
        }

        return $strAncora;
    }

    public function montarMenuTextual($arrMenu)
    {
        $strMenu = '';

        $objInfraSessao = $this->getObjInfraSessao();

        $arrParametrosRepasseLink = $objInfraSessao->getArrParametrosRepasseLink();
        $objInfraSessao->setArrParametrosRepasseLink(null);

        foreach ($arrMenu as $arrMenuPrincipal) {
            $numLimite = InfraArray::contar($arrMenuPrincipal);
            for ($i = 0; $i < $numLimite; $i++) {
                $strLinhaAtual = explode('^', $arrMenuPrincipal[$i]);
                for ($j = 0, $jMax = strlen($strLinhaAtual[0]); $j < $jMax; $j++) {
                    $strMenu .= '&nbsp;&nbsp;&nbsp;';
                }
                if ($strLinhaAtual[1] == '#') {
                    $strMenu .= '<span style="font-weight:bold;font-size:12px;">' . $strLinhaAtual[2] . '</span><br/>';
                } else {
                    $strMenu .= '<a href="' . $this->getObjInfraSessao()->assinarLink($strLinhaAtual[1]) . '" title="' .
                        $strLinhaAtual[2] . '">' . $strLinhaAtual[2] . '</a><br/>';
                }
            }
        }

        $objInfraSessao->setArrParametrosRepasseLink($arrParametrosRepasseLink);

        return array($strMenu, $numLimite);
    }

    public static function montarAncoraSigla($strSigla, $strDescricao)
    {
        return '<a alt="' . $strDescricao . '" title="' . $strDescricao . '" class="infraAncoraSigla">' . $strSigla . '</a>';
    }

    public function configurarHttps($bolForcarHttps, $strServerName = null, $strRequestUri = null)
    {
        if ($strServerName == null) {
            $strServerName = $_SERVER['SERVER_NAME'];
        }

        if ($strRequestUri == null) {
            $strRequestUri = $_SERVER['REQUEST_URI'];
        }

        $strServerHttps = '';
        if (isset($_SERVER['HTTPS'])) {
            $strServerHttps = $_SERVER['HTTPS'];
        }

        if ($bolForcarHttps) {
            if ($strServerHttps != 'on') {
                header('Location: https://' . $strServerName . $strRequestUri);
                die;
            }
        } else {
            if ($strServerHttps == 'on') {
                header('Location: http://' . $strServerName . $strRequestUri);
                die;
            }
        }
    }

    private function inicializarSelecao($strSelecao)
    {
        $this->arrSelecoes[$strSelecao] = array();
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_NRO_ITENS] = 0;
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_ITEM_ID] = '';
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_ITENS] = '';
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_ITENS_SELECIONADOS] = '';
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_SESSAO] = false;
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PAGINA_ATUAL] = 0;
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_HASH_CRITERIOS] = '';
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_REGISTROS_POR_PAGINA] = 0;
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PREPARAR] = false;
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_TOTAL_REGISTROS] = 0;
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_REGISTROS_PAGINA_ATUAL] = 0;
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_PAG_PROCESSAR] = false;
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_PREPARAR] = false;
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_CAMPO] = '';
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_ORD_TIPO] = '';
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_NUM_AREA_TABELA] = '';
        $this->arrSelecoes[$strSelecao][self::$POS_SEL_TAB_INDEX] = true;
    }

    private function getProxNumAreaTabela($strSelecao)
    {
        $ret = '';

        if (isset($this->arrSelecoes[$strSelecao][self::$POS_SEL_NUM_AREA_TABELA])) {
            $ret = $this->arrSelecoes[$strSelecao][self::$POS_SEL_NUM_AREA_TABELA];
        }

        if ($ret === '') {
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_NUM_AREA_TABELA] = 1;
        } else {
            $this->arrSelecoes[$strSelecao][self::$POS_SEL_NUM_AREA_TABELA] = $ret + 1;
        }

        return $ret;
    }

    private static function enviarHeaderInicial($strContentType = null)
    {
        /*
			Cache-Control:
			public - may be cached in public shared caches.
			private - may only be cached in private cache.
			no-cache - may not be cached.
			no-store - may be cached but not archived.
		  post-check=0, pre-check=0 - somente IE (nao informar)
		 */
        header('Cache-Control: private, no-cache, no-store, must-revalidate, max-age=0');

        //HTTP/1.0
        header('Pragma: no-cache');
        header('Expires: 0');

        if ($strContentType != null) {
            header($strContentType);
        }
    }

    public static function montarHeaderDownload(
        $strNomeArquivo = null,
        $strContentDisposition = 'inline',
        $strContentType = null,
        $bolXSS = false,
        $bolPermitirIndexacaoRobos = false
    ) {
        self::enviarHeaderInicial($strContentType);

        if (!$bolPermitirIndexacaoRobos) {
            header('X-Robots-Tag: noindex');
        }

        if ($bolXSS) {
            self::montarHeaderAntiXSS();
        }

        if ($strNomeArquivo != null) {
            if ($strContentType == null) {
                header('Content-Type: ' . InfraUtil::getStrMimeType($strNomeArquivo) . ';');
            }

            header(
                'Content-Disposition: ' . $strContentDisposition . '; filename="' . InfraUtil::formatarNomeArquivo(
                    $strNomeArquivo
                ) . '"'
            );
        }
    }

    protected function montarHeader($strContentType = null)
    {
        self::enviarHeaderInicial($strContentType);

        if (!$this->isBolPermitirIndexacaoRobos()) {
            header('X-Robots-Tag: noindex');
        }

        self::montarHeaderAntiXSS();
    }

    private static function montarHeaderAntiXSS()
    {
        //if ($this->isBolNavegadorIE()) {
        //  header('X-UA-Compatible: IE=edge');
        //}

        //Tratamento de segurança para minimizar a vulnerabilidade XSS
        header('X-XSS-Protection: 1; mode=block');

        //Tratamento de segurança para minimizar a vulnerabilidade MIME-sniffing
        header('X-Content-Type-Options: nosniff');

        //Tratamento de segurança para minimizar a vulnerabilidade Clickjacking
        header('X-Frame-Options: SAMEORIGIN');
    }

    public function getIconeMenu()
    {
        return $this->getDiretorioImagensGlobal() . '/menu.gif';
    }

    public function getIconeUsuario()
    {
        return $this->getDiretorioImagensGlobal() . '/usuario.gif';
    }

    public function getIconeCheck()
    {
        return $this->getDiretorioImagensGlobal() . '/check.gif';
    }

    public function getIconeOrdenacaoColunaAcima()
    {
        return $this->getDiretorioImagensGlobal() . '/seta_acima.gif';
    }

    public function getIconeOrdenacaoColunaAcimaSelecionada()
    {
        return $this->getDiretorioImagensGlobal() . '/seta_acima_selecionada.gif';
    }

    public function getIconeOrdenacaoColunaAbaixo()
    {
        return $this->getDiretorioImagensGlobal() . '/seta_abaixo.gif';
    }

    public function getIconeOrdenacaoColunaAbaixoSelecionada()
    {
        return $this->getDiretorioImagensGlobal() . '/seta_abaixo_selecionada.gif';
    }

    public function getIconeConsultar()
    {
        return $this->getDiretorioImagensGlobal() . '/consultar.gif';
    }

    public function getIconeAlterar()
    {
        return $this->getDiretorioImagensGlobal() . '/alterar.gif';
    }

    public function getIconeClonar()
    {
        return $this->getDiretorioImagensGlobal() . '/clonar.gif';
    }

    public function getIconeExcluir()
    {
        return $this->getDiretorioImagensGlobal() . '/excluir.gif';
    }

    public function getIconeDesativar()
    {
        return $this->getDiretorioImagensGlobal() . '/desativar.gif';
    }

    public function getIconeReativar()
    {
        return $this->getDiretorioImagensGlobal() . '/reativar.gif';
    }

    public function getIconePesquisar()
    {
        return $this->getDiretorioImagensGlobal() . '/lupa.gif';
    }

    public function getIconeRemover()
    {
        return $this->getDiretorioImagensGlobal() . '/remover.gif';
    }

    public function getIconeMoverAbaixo()
    {
        return $this->getDiretorioImagensGlobal() . '/seta_abaixo_select.gif';
    }

    public function getIconeMoverAcima()
    {
        return $this->getDiretorioImagensGlobal() . '/seta_acima_select.gif';
    }

    public function getIconeCalendario()
    {
        return $this->getDiretorioImagensGlobal() . '/calendario.gif';
    }

    public function getIconePaginacaoPrimeira()
    {
        return $this->getDiretorioImagensGlobal() . '/primeira_pagina.gif';
    }

    public function getIconePaginacaoAnterior()
    {
        return $this->getDiretorioImagensGlobal() . '/pagina_anterior.gif';
    }

    public function getIconePaginacaoProxima()
    {
        return $this->getDiretorioImagensGlobal() . '/proxima_pagina.gif';
    }

    public function getIconePaginacaoUltima()
    {
        return $this->getDiretorioImagensGlobal() . '/ultima_pagina.gif';
    }

    public function getIconeTransportar()
    {
        return $this->getDiretorioImagensGlobal() . '/transportar.gif';
    }

    public function getIconeAjuda()
    {
        return $this->getDiretorioImagensGlobal() . '/ajuda.gif';
    }

    public function getIconeInformacao()
    {
        return $this->getDiretorioImagensGlobal() . '/info.png';
    }

    public function getIconeMais()
    {
        return $this->getDiretorioImagensGlobal() . '/mais.png';
    }

    public function getIconeMenos()
    {
        return $this->getDiretorioImagensGlobal() . '/menos.png';
    }

    public function getIconeUpload()
    {
        return $this->getDiretorioImagensGlobal() . '/upload.gif';
    }

    public function getIconeDownload()
    {
        return $this->getDiretorioImagensGlobal() . '/download.gif';
    }

    public function getIconeMarcar()
    {
        return $this->getDiretorioImagensGlobal() . '/marcar.gif';
    }

    public function getIconeGrupo()
    {
        return $this->getDiretorioImagensGlobal() . '/grupo.gif';
    }

    public function getIconeAguardar()
    {
        return $this->getDiretorioImagensGlobal() . '/aguarde.gif';
    }

    public static function montarSinalizacao(
        $strValor,
        $strLink,
        $strImagem,
        $strTitle,
        $tabindex = '',
        $strClass = '',
        $strStyle = ''
    ) {
        $strRet = '';

        $strValor = trim($strValor);

        if (strlen($strValor)) {
            $strRet .= '<div style="display:inline-grid;margin: 0 5px;">' . "\n";
            $strRet .= ' <a style="position:relative;" href="' . $strLink . '" ' . ($tabindex != '' ? 'tabindex="' . PaginaSEI::getInstance(
                    )->getProxTabTabela() . '"' : '') . '>' . "\n";
            $strRet .= ' <img src="' . $strImagem . '" title="' . $strTitle . '" alt="' . $strTitle . '" class="infraImg media-object"/>' . "\n";
            $strRet .= ' <span class="badge badge-notification bg-white text-black';
            $strRet .= $strClass;
            $strRet .= '" style="position:absolute;font-size:.625rem;line-height:11px;margin-top:2px;border:1px solid black;top:-7px;left:-6px;';
            if (strlen($strValor) == 1) {
                $strRet .= 'padding:1px 4px;';
            } else {
                $strRet .= 'padding:1px;';
            }
            $strRet .= $strStyle;
            $strRet .= '">' . $strValor . '</span>' . "\n";
            $strRet .= ' </a>' . "\n";
            $strRet .= '</div>' . "\n";
        }
        return $strRet;
    }
}