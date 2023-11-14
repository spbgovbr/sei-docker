<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 03/07/2019 - criado por cle@trf4.jus.br
 * Versão do Gerador de Código: 1.42.0
 */

try {
    require_once dirname(__FILE__) . '/../Infra.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoInfra::getInstance()->validarLink();

    PaginaInfra::getInstance()->prepararSelecao('infra_sessao_rest_selecionar');

    SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

    switch ($_GET['acao']) {
        case 'infra_sessao_rest_selecionar':
            $strTitulo = PaginaInfra::getInstance()->getTituloSelecao(
                'Selecionar Infra Sessão REST',
                'Selecionar Infra Sessão REST'
            );

            if ($_GET['acao_origem'] == 'infra_sessao_rest_cadastrar') {
                if (isset($_GET['id_infra_sessao_rest'])) {
                    PaginaInfra::getInstance()->adicionarSelecionado($_GET['id_infra_sessao_rest']);
                }
            }
            break;

        case 'infra_sessao_rest_listar':
            $strTitulo = 'Infra Sessão REST';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos = array();
    if ($_GET['acao'] == 'infra_sessao_rest_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    $objInfraSessaoRestDTO = new InfraSessaoRestDTO();
    $objInfraSessaoRestDTO->retStrIdInfraSessaoRest();
    //$objInfraSessaoRestDTO->retNumIdUsuario();
    $objInfraSessaoRestDTO->retStrSiglaUsuario();
    //$objInfraSessaoRestDTO->retNumIdOrgao();
    $objInfraSessaoRestDTO->retStrSiglaOrgao();
    $objInfraSessaoRestDTO->retDthLogin();
    $objInfraSessaoRestDTO->retDthAcesso();
    $objInfraSessaoRestDTO->retDthLogout();
    $objInfraSessaoRestDTO->retStrUserAgent();
    //$objInfraSessaoRestDTO->retStrHttpClientIp();
    //$objInfraSessaoRestDTO->retStrHttpXForwardedFor();
    //$objInfraSessaoRestDTO->retStrRemoteAddr();

    if ($_POST['txtSiglaUsuario'] != '') {
        $objInfraSessaoRestDTO->setStrSiglaUsuario($_POST['txtSiglaUsuario']);
    }

    if ($_POST['txtSiglaOrgao'] != '') {
        $objInfraSessaoRestDTO->setStrSiglaOrgao($_POST['txtSiglaOrgao']);
    }

    if (($_POST['txtDataInicialLogin'] != '') && ($_POST['txtDataFinalLogin'] != '')) {
        $objInfraSessaoRestDTO->adicionarCriterio(array('Login', 'Login'),
            array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
            array($_POST['txtDataInicialLogin'] . ' 00:00:00', $_POST['txtDataFinalLogin'] . ' 23:59:59'),
            InfraDTO::$OPER_LOGICO_AND);
    }

    PaginaInfra::getInstance()->prepararOrdenacao($objInfraSessaoRestDTO, 'Acesso', InfraDTO::$TIPO_ORDENACAO_DESC);
    PaginaInfra::getInstance()->prepararPaginacao($objInfraSessaoRestDTO);

    $objInfraSessaoRestRN = new InfraSessaoRestRN();
    $arrObjInfraSessaoRestDTO = $objInfraSessaoRestRN->listar($objInfraSessaoRestDTO);

    PaginaInfra::getInstance()->processarPaginacao($objInfraSessaoRestDTO);
    $numRegistros = count($arrObjInfraSessaoRestDTO);

    if ($numRegistros > 0) {
        $bolCheck = false;

        if ($_GET['acao'] == 'infra_sessao_rest_selecionar') {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_sessao_rest_consultar');
            $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_sessao_rest_alterar');
            $bolAcaoImprimir = false;
            //$bolAcaoGerarPlanilha = false;
            $bolAcaoExcluir = false;
            $bolAcaoDesativar = false;
            $bolCheck = true;
        } else {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_sessao_rest_consultar');
            $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_sessao_rest_alterar');
            $bolAcaoImprimir = true;
            //$bolAcaoGerarPlanilha = SessaoInfra::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
            $bolAcaoExcluir = SessaoInfra::getInstance()->verificarPermissao('infra_sessao_rest_excluir');
            $bolAcaoDesativar = SessaoInfra::getInstance()->verificarPermissao('infra_sessao_rest_desativar');
        }

        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
            $strLinkExcluir = SessaoInfra::getInstance()->assinarLink(
                'controlador.php?acao=infra_sessao_rest_excluir&acao_origem=' . $_GET['acao']
            );
        }

        /*if ($bolAcaoGerarPlanilha){
          $bolCheck = true;
          $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
        }*/

        $strResultado = '';

        $strSumarioTabela = 'Tabela de Infra Sessão REST.';
        $strCaptionTabela = 'Infra Sessão REST';

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
        //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraSessaoRestDTO,'Id do Usuário no SIP','IdUsuario',$arrObjInfraSessaoRestDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraSessaoRestDTO,
                'Sigla do Usuário',
                'SiglaUsuario',
                $arrObjInfraSessaoRestDTO
            ) . '</th>' . "\n";
        //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraSessaoRestDTO,'Id do Órgão','IdOrgao',$arrObjInfraSessaoRestDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraSessaoRestDTO,
                'Sigla do Órgão',
                'SiglaOrgao',
                $arrObjInfraSessaoRestDTO
            ) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraSessaoRestDTO,
                'Data do Login',
                'Login',
                $arrObjInfraSessaoRestDTO
            ) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraSessaoRestDTO,
                'Data do Último Acesso',
                'Acesso',
                $arrObjInfraSessaoRestDTO
            ) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraSessaoRestDTO,
                'Data do Logout',
                'Logout',
                $arrObjInfraSessaoRestDTO
            ) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraSessaoRestDTO,
                'User Agent',
                'UserAgent',
                $arrObjInfraSessaoRestDTO
            ) . '</th>' . "\n";
        //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraSessaoRestDTO,'IP do Cliente','HttpClientIp',$arrObjInfraSessaoRestDTO).'</th>'."\n";
        //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraSessaoRestDTO,'X-Forwarded-For','HttpXForwardedFor',$arrObjInfraSessaoRestDTO).'</th>'."\n";
        //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraSessaoRestDTO,'Remote Address','RemoteAddr',$arrObjInfraSessaoRestDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';

        for ($i = 0; $i < $numRegistros; $i++) {
            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td valign="top">' . PaginaInfra::getInstance()->getTrCheck(
                        $i,
                        $arrObjInfraSessaoRestDTO[$i]->getStrIdInfraSessaoRest(),
                        $arrObjInfraSessaoRestDTO[$i]->getNumIdUsuario()
                    ) . '</td>';
            }
            //$strResultado .= '<td>'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraSessaoRestDTO[$i]->getNumIdUsuario()).'</td>';
            $strResultado .= '<td align="center">' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraSessaoRestDTO[$i]->getStrSiglaUsuario()
                ) . '</td>';
            //$strResultado .= '<td>'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraSessaoRestDTO[$i]->getNumIdOrgao()).'</td>';
            $strResultado .= '<td align="center">' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraSessaoRestDTO[$i]->getStrSiglaOrgao()
                ) . '</td>';
            $strResultado .= '<td align="center">' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraSessaoRestDTO[$i]->getDthLogin()
                ) . '</td>';
            $strResultado .= '<td align="center">' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraSessaoRestDTO[$i]->getDthAcesso()
                ) . '</td>';
            $strResultado .= '<td align="center">' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraSessaoRestDTO[$i]->getDthLogout()
                ) . '</td>';
            $strResultado .= '<td>' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraSessaoRestDTO[$i]->getStrUserAgent()
                ) . '</td>';
            //$strResultado .= '<td>'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraSessaoRestDTO[$i]->getStrHttpClientIp()).'</td>';
            //$strResultado .= '<td>'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraSessaoRestDTO[$i]->getStrHttpXForwardedFor()).'</td>';
            //$strResultado .= '<td>'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraSessaoRestDTO[$i]->getStrRemoteAddr()).'</td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaInfra::getInstance()->getAcaoTransportarItem(
                $i,
                $arrObjInfraSessaoRestDTO[$i]->getStrIdInfraSessaoRest()
            );

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . SessaoInfra::getInstance()->assinarLink(
                        'controlador.php?acao=infra_sessao_rest_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_infra_sessao_rest=' . $arrObjInfraSessaoRestDTO[$i]->getStrIdInfraSessaoRest(
                        )
                    ) . '" tabindex="' . PaginaInfra::getInstance()->getProxTabTabela(
                    ) . '"><img src="' . PaginaInfra::getInstance()->getIconeConsultar(
                    ) . '" title="Consultar Infra Sessão Rest" alt="Consultar Infra Sessão Rest" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'infra_sessao_rest_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
    } else {
        $arrComandos[] = '<button type="button" accesskey="i" id="btnFiltrar" value="Filtrar" onclick="document.getElementById(\'frmInfraSessaoRestLista\').submit();" class="infraButton">F<span class="infraTeclaAtalho">i</span>ltrar</button>';
        $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoInfra::getInstance(
            )->assinarLink(
                'controlador.php?acao=' . PaginaInfra::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']
            ) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
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
<? if (0){ ?>
    <style><?}?>
        <? if (0){ ?></style><?
} ?>
<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>
<? if (0){ ?>
    <script type="text/javascript"><?}?>
        function inicializar() {
            if ('<?=$_GET['acao']?>' == 'infra_sessao_rest_selecionar') {
                infraReceberSelecao();
                document.getElementById('btnFecharSelecao').focus();
            } else {
                document.getElementById('btnFechar').focus();
            }
            infraEfeitoTabelas(true);
        }
        <? if (0){ ?></script><?
} ?>
<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmInfraSessaoRestLista" method="post" action="<?= SessaoInfra::getInstance()->assinarLink(
        'controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']
    ) ?>">
        <?
        PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaInfra::getInstance()->abrirAreaDados('6em');
        ?>
        <fieldset class="infraFieldset">
            <legend class="infraLegendOpcional">Filtros</legend>
            <label class="infraLabelOpcional">Sigla do Usuário: </label><input type="text" size="10"
                                                                               name="txtSiglaUsuario"
                                                                               value="<?= $_POST['txtSiglaUsuario']; ?>"
                                                                               tabindex="<?= PaginaInfra::getInstance(
                                                                               )->getProxTabDados() ?>"/>
            &nbsp;&nbsp;&nbsp;
            <label class="infraLabelOpcional">Sigla do Órgão: </label><input type="text" size="10" name="txtSiglaOrgao"
                                                                             value="<?= $_POST['txtSiglaOrgao']; ?>"
                                                                             tabindex="<?= PaginaInfra::getInstance(
                                                                             )->getProxTabDados() ?>"/>
            &nbsp;&nbsp;&nbsp;
            <label class="infraLabelOpcional">Data do Login entre </label><input type="text" size="10"
                                                                                 id="txtDataInicialLogin"
                                                                                 name="txtDataInicialLogin"
                                                                                 value="<?= $_POST['txtDataInicialLogin']; ?>"
                                                                                 tabindex="<?= PaginaInfra::getInstance(
                                                                                 )->getProxTabDados() ?>"/>
            <img src="src="<?= PaginaInfra::getInstance()->getIconeCalendario() ?>" id="imgCalDataInicio"
            title="Selecionar Data Inicial" alt="Selecionar Data Inicial" class="infraImg"
            onclick="infraCalendario('txtDataInicialLogin',this);" tabindex="<?= PaginaInfra::getInstance(
            )->getProxTabDados() ?>" />
            <label class="infraLabelOpcional"> e </label><input type="text" size="10" id="txtDataFinalLogin"
                                                                name="txtDataFinalLogin"
                                                                value="<?= $_POST['txtDataFinalLogin']; ?>"
                                                                tabindex="<?= PaginaInfra::getInstance(
                                                                )->getProxTabDados() ?>"/>
            <img src="<?= PaginaInfra::getInstance()->getIconeCalendario() ?>" id="imgCalDataFim"
                 title="Selecionar Data Final" alt="Selecionar Data Final" class="infraImg"
                 onclick="infraCalendario('txtDataFinalLogin',this);"
                 tabindex="<?= PaginaInfra::getInstance()->getProxTabDados() ?>"/>
        </fieldset>
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
