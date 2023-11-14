<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 21/07/2016 - criado por mga
 *
 */

try {
    //require_once 'Infra.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoInfra::getInstance()->validarLink();

    SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

    $arrComandos = array();

    $strAtributo = '';

    switch ($_GET['acao']) {
        case 'infra_atributo_cache_consultar':
            $strTitulo = 'Consultar Atributo ' . $_GET['nome'];
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoInfra::getInstance(
                )->assinarLink(
                    'controlador.php?acao=' . PaginaInfra::getInstance()->getAcaoRetorno(
                    ) . '&acao_origem=' . $_GET['acao'] . PaginaInfra::getInstance()->montarAncora($_GET['nome'])
                ) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

            $valor = CacheInfra::getInstance()->getAtributo($_GET['nome']);

            if ($valor === null) {
                $strAtributo = 'Atributo não encontrado.';
            } else {
                if (!is_array($valor) && !is_object($valor)) {
                    $strAtributo = PaginaInfra::getInstance()->tratarHTML($valor);
                } else {
                    if (is_object($valor) && method_exists($valor, '__toString()')) {
                        $strAtributo = nl2br(PaginaInfra::getInstance()->tratarHTML($valor->__toString()));
                    } else {
                        $strAtributo = nl2br(PaginaInfra::getInstance()->tratarHTML(print_r($valor, true)));
                    }
                }
            }

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }
} catch (Exception $e) {
    PaginaInfra::getInstance()->processarExcecao($e);
}

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>
<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>

    function inicializar(){
    document.getElementById('btnFechar').focus();
    }

    function OnSubmitForm() {
    return validarCadastro();
    }

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmInfraAtributoCacheCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoInfra::getInstance()->assinarLink(
              'controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']
          ) ?>">
        <?
        PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
        //PaginaInfra::getInstance()->montarAreaValidacao();
        //PaginaInfra::getInstance()->abrirAreaDados('45em');
        ?>
        <label id="lblValor" class="infraLabelOpcional"><?= $strAtributo ?></label>
        <?
        //PaginaInfra::getInstance()->fecharAreaDados();
        //PaginaInfra::getInstance()->montarAreaDebug();
        //PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
