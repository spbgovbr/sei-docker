<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/07/2016 - criado por mga
 *
 * Versão do Gerador de Código: 1.27.1
 *
 * Versão no CVS: $Id$
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

    PaginaInfra::getInstance()->salvarCamposPost(array('txtAtributo', 'txtLimite'));

    switch ($_GET['acao']) {
        case 'infra_atributo_cache_excluir':
            try {
                $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
                foreach ($arrStrIds as $id) {
                    CacheInfra::getInstance()->removerAtributo($id);
                }
                PaginaInfra::getInstance()->setStrMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaInfra::getInstance()->processarExcecao($e);
            }
            header(
                'Location: ' . SessaoInfra::getInstance()->assinarLink(
                    'controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']
                )
            );
            die;

        case 'infra_atributo_cache_listar':
            $strTitulo = 'Cache em Memória ' . CacheInfra::getInstance()->getStrServidor();
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos = array();

    $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

    $arrAtributos = array();

    $strAtributo = PaginaInfra::getInstance()->recuperarCampo('txtAtributo');
    $numLimite = PaginaInfra::getInstance()->recuperarCampo('txtLimite', 100);

    $arrAtributos = CacheInfra::getInstance()->listarAtributos();

    $numRegistros = 0;

    $numTotal = count($arrAtributos);

    if ($numTotal == 0) {
        $strTitulo .= ' (nenhum atributo)';
    } elseif ($numTotal == 1) {
        $strTitulo .= ' (1 atributo)';
    } else {
        $strTitulo .= ' (' . $numTotal . ' atributos)';
    }

    if ($_GET['acao_origem'] == 'infra_atributo_cache_listar' || $_GET['acao_origem'] == 'infra_atributo_cache_consultar' || $_GET['acao_origem'] == 'infra_atributo_cache_excluir') {
        if ($strAtributo != '') {
            $arrTemp = $arrAtributos;

            $arrAtributos = array();

            $strAtributo = InfraString::transformarCaixaAlta($strAtributo);

            foreach ($arrTemp as $strChave) {
                if (strpos($strChave, $strAtributo) !== false) {
                    $arrAtributos[] = $strChave;
                }
            }
        }

        sort($arrAtributos);

        if (is_numeric($numLimite) && $numLimite > 0) {
            $arrAtributos = array_slice($arrAtributos, 0, $numLimite);
        }

        $numRegistros = count($arrAtributos);
    }


    if ($numRegistros > 0) {
        $bolCheck = false;

        $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_atributo_cache_consultar');
        $bolAcaoExcluir = SessaoInfra::getInstance()->verificarPermissao('infra_atributo_cache_excluir');

        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
            $strLinkExcluir = SessaoInfra::getInstance()->assinarLink(
                'controlador.php?acao=infra_atributo_cache_excluir&acao_origem=' . $_GET['acao']
            );
        }

        $strResultado = '';

        $strSumarioTabela = 'Tabela de Atributos da Cache em Memória.';
        $strCaptionTabela = 'Atributos da Cache em Memória';

        $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaInfra::getInstance()->gerarCaptionTabela(
                $strCaptionTabela,
                $numRegistros
            ) . '</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="1%">' . PaginaInfra::getInstance()->getThCheck(
                ) . '</th>' . "\n";
        }
        $strResultado .= '<th class="infraTh">Nome</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="10%">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        $i = 0;

        foreach ($arrAtributos as $nome) {
            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td>' . PaginaInfra::getInstance()->getTrCheck($i++, $nome, $nome) . '</td>';
            }
            $strResultado .= '<td>' . PaginaInfra::getInstance()->tratarHTML($nome) . '</td>';

            $strResultado .= '<td align="center">';

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . SessaoInfra::getInstance()->assinarLink(
                        'controlador.php?acao=infra_atributo_cache_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&nome=' . $nome
                    ) . '" tabindex="' . PaginaInfra::getInstance()->getProxTabTabela(
                    ) . '"><img src="' . PaginaInfra::getInstance()->getIconeConsultar(
                    ) . '" title="Consultar Atributo de Memória" alt="Consultar Atributo de Memória" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoExcluir) {
                $strId = $nome;
                $strDescricao = PaginaInfra::getInstance()->formatarParametrosJavaScript($nome);
            }

            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaInfra::getInstance()->montarAncora(
                        $strId
                    ) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaInfra::getInstance(
                    )->getProxTabTabela() . '"><img src="' . PaginaInfra::getInstance()->getIconeExcluir(
                    ) . '" title="Excluir Atributo de Memória" alt="Excluir Atributo de Memória" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoInfra::getInstance(
        )->assinarLink(
            'controlador.php?acao=' . PaginaInfra::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']
        ) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
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
    #lblAtributo {position:absolute;left:0%;top:0%;}
    #txtAtributo {position:absolute;left:0%;top:40%;width:50%;}

    #lblLimite {position:absolute;left:55%;top:0%;}
    #txtLimite {position:absolute;left:55%;top:40%;width:10%;}

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>

    function inicializar(){
    document.getElementById('btnFechar').focus();
    infraEfeitoImagens();
    infraEfeitoTabelas();
    }

<?
if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id,desc){
    if (confirm("Confirma exclusão do Atributo da Cache \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraAtributoCacheLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmInfraAtributoCacheLista').submit();
    }
    }

    function acaoExclusaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Atributo da Cache selecionado.');
    return;
    }

    if (confirm("Confirma exclusão dos Atributos da Cache selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraAtributoCacheLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmInfraAtributoCacheLista').submit();
    }
    }
<?
} ?>

    function validarForm(){
    if (document.getElementById('txtLimite').value=='0'){
    alert('Número máximo de registros para retorno inválido.');
    document.getElementById('txtLimite').focus();
    return false;
    }

    infraExibirAviso();

    return true;
    }
<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmInfraAtributoCacheLista" method="post" onsubmit="return validarForm();"
          action="<?= SessaoInfra::getInstance()->assinarLink(
              'controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']
          ) ?>">
        <?
        PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaInfra::getInstance()->abrirAreaDados('5em');
        ?>
        <label id="lblAtributo" for="txtAtributo" class="infraLabelOpcional">Atributo:</label>
        <input type="text" id="txtAtributo" name="txtAtributo" class="infraText"
               value="<?= PaginaInfra::getInstance()->tratarHTML($strAtributo) ?>"
               tabindex="<?= PaginaInfra::getInstance()->getProxTabDados() ?>"/>

        <label id="lblLimite" for="txtLimite" class="infraLabelOpcional">Máx. Retorno:</label>
        <input type="text" id="txtLimite" name="txtLimite" class="infraText"
               onkeypress="return infraMascaraNumero(this,event,5);"
               value="<?= PaginaInfra::getInstance()->tratarHTML($numLimite) ?>"
               tabindex="<?= PaginaInfra::getInstance()->getProxTabDados() ?>"/>

        <?
        PaginaInfra::getInstance()->fecharAreaDados();

        PaginaInfra::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        //PaginaInfra::getInstance()->montarAreaDebug();
        PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
