<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/01/2023 - criado por cas84
 *
 * Versão do Gerador de Código: 1.43.2
 */

try {
    require_once dirname(__FILE__) . '/SEI.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSEI::getInstance()->validarLink();

    PaginaSEI::getInstance()->prepararSelecao('tipo_prioridade_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    switch ($_GET['acao']) {
        case 'tipo_prioridade_excluir':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjTipoPrioridadeDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objTipoPrioridadeDTO = new TipoPrioridadeDTO();
                    $objTipoPrioridadeDTO->setNumIdTipoPrioridade($arrStrIds[$i]);
                    $arrObjTipoPrioridadeDTO[] = $objTipoPrioridadeDTO;
                }
                $objTipoPrioridadeRN = new TipoPrioridadeRN();
                $objTipoPrioridadeRN->excluir($arrObjTipoPrioridadeDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header(
                'Location: ' . SessaoSEI::getInstance()->assinarLink(
                    'controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']
                )
            );
            die;


        case 'tipo_prioridade_desativar':
            try {
                $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                $arrObjTipoPrioridadeDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objTipoPrioridadeDTO = new TipoPrioridadeDTO();
                    $objTipoPrioridadeDTO->setNumIdTipoPrioridade($arrStrIds[$i]);
                    $arrObjTipoPrioridadeDTO[] = $objTipoPrioridadeDTO;
                }
                $objTipoPrioridadeRN = new TipoPrioridadeRN();
                $objTipoPrioridadeRN->desativar($arrObjTipoPrioridadeDTO);
                PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
            } catch (Exception $e) {
                PaginaSEI::getInstance()->processarExcecao($e);
            }
            header(
                'Location: ' . SessaoSEI::getInstance()->assinarLink(
                    'controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']
                )
            );
            die;

        case 'tipo_prioridade_reativar':
            $strTitulo = 'Reativar Tipos de Prioridade';
            if ($_GET['acao_confirmada'] == 'sim') {
                try {
                    $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
                    $arrObjTipoPrioridadeDTO = array();
                    for ($i = 0; $i < count($arrStrIds); $i++) {
                        $objTipoPrioridadeDTO = new TipoPrioridadeDTO();
                        $objTipoPrioridadeDTO->setNumIdTipoPrioridade($arrStrIds[$i]);
                        $arrObjTipoPrioridadeDTO[] = $objTipoPrioridadeDTO;
                    }
                    $objTipoPrioridadeRN = new TipoPrioridadeRN();
                    $objTipoPrioridadeRN->reativar($arrObjTipoPrioridadeDTO);
                    PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
                header(
                    'Location: ' . SessaoSEI::getInstance()->assinarLink(
                        'controlador.php?acao=' . $_GET['acao_origem'] . '&acao_origem=' . $_GET['acao']
                    )
                );
                die;
            }
            break;


        case 'tipo_prioridade_selecionar':
            $strTitulo = PaginaSEI::getInstance()->getTituloSelecao(
                'Selecionar Tipo de Prioridade',
                'Selecionar Tipos de Prioridade'
            );

            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'tipo_prioridade_cadastrar') {
                if (isset($_GET['id_tipo_prioridade'])) {
                    PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_tipo_prioridade']);
                }
            }
            break;

        case 'tipo_prioridade_listar':
            $strTitulo = 'Tipos de Prioridade';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos = array();
    if ($_GET['acao'] == 'tipo_prioridade_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    if ($_GET['acao'] == 'tipo_prioridade_listar' || $_GET['acao'] == 'tipo_prioridade_selecionar') {
        $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('tipo_prioridade_cadastrar');
        if ($bolAcaoCadastrar) {
            $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoSEI::getInstance(
                )->assinarLink(
                    'controlador.php?acao=tipo_prioridade_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']
                ) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
        }
    }

    $objTipoPrioridadeDTO = new TipoPrioridadeDTO();
    $objTipoPrioridadeDTO->retNumIdTipoPrioridade();
    $objTipoPrioridadeDTO->retStrNome();
    $objTipoPrioridadeDTO->retStrDescricao();

    if ($_GET['acao'] == 'tipo_prioridade_reativar') {
        //Lista somente inativos
        $objTipoPrioridadeDTO->setBolExclusaoLogica(false);
        $objTipoPrioridadeDTO->setStrSinAtivo('N');
    }

    PaginaSEI::getInstance()->prepararOrdenacao(
        $objTipoPrioridadeDTO,
        'IdTipoPrioridade',
        InfraDTO::$TIPO_ORDENACAO_ASC
    );
    PaginaSEI::getInstance()->prepararPaginacao($objTipoPrioridadeDTO);

    $objTipoPrioridadeRN = new TipoPrioridadeRN();
    $arrObjTipoPrioridadeDTO = $objTipoPrioridadeRN->listar($objTipoPrioridadeDTO);

    PaginaSEI::getInstance()->processarPaginacao($objTipoPrioridadeDTO);

    /** @var TipoPrioridadeDTO[] $arrObjTipoPrioridadeDTO */

    $numRegistros = count($arrObjTipoPrioridadeDTO);

    if ($numRegistros > 0) {
        $bolCheck = false;

        if ($_GET['acao'] == 'tipo_prioridade_selecionar') {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tipo_prioridade_consultar');
            $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tipo_prioridade_alterar');
            $bolAcaoImprimir = false;
            //$bolAcaoGerarPlanilha = false;
            $bolAcaoExcluir = false;
            $bolAcaoDesativar = false;
            $bolCheck = true;
        } else {
            if ($_GET['acao'] == 'tipo_prioridade_reativar') {
                $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('tipo_prioridade_reativar');
                $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tipo_prioridade_consultar');
                $bolAcaoAlterar = false;
                $bolAcaoImprimir = true;
                //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
                $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('tipo_prioridade_excluir');
                $bolAcaoDesativar = false;
            } else {
                $bolAcaoReativar = false;
                $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tipo_prioridade_consultar');
                $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tipo_prioridade_alterar');
                $bolAcaoImprimir = true;
                //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
                $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('tipo_prioridade_excluir');
                $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('tipo_prioridade_desativar');
            }
        }


        if ($bolAcaoDesativar) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
            $strLinkDesativar = SessaoSEI::getInstance()->assinarLink(
                'controlador.php?acao=tipo_prioridade_desativar&acao_origem=' . $_GET['acao']
            );
        }

        if ($bolAcaoReativar) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
            $strLinkReativar = SessaoSEI::getInstance()->assinarLink(
                'controlador.php?acao=tipo_prioridade_reativar&acao_origem=' . $_GET['acao'] . '&acao_confirmada=sim'
            );
        }


        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
            $strLinkExcluir = SessaoSEI::getInstance()->assinarLink(
                'controlador.php?acao=tipo_prioridade_excluir&acao_origem=' . $_GET['acao']
            );
        }

        /*
        if ($bolAcaoGerarPlanilha){
          $bolCheck = true;
          $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
        }
        */

        $strResultado = '';

        if ($_GET['acao'] != 'tipo_prioridade_reativar') {
            $strSumarioTabela = 'Tabela de Tipos de Prioridade.';
            $strCaptionTabela = 'Tipos de Prioridade';
        } else {
            $strSumarioTabela = 'Tabela de Tipos de Prioridade Inativos.';
            $strCaptionTabela = 'Tipos de Prioridade Inativos';
        }

        $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela(
                $strCaptionTabela,
                $numRegistros
            ) . '</caption>';
        $strResultado .= '<tr>';
        if ($bolCheck) {
            $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck(
                ) . '</th>' . "\n";
        }
        $strResultado .= '<th class="infraTh" width="30%">' . PaginaSEI::getInstance()->getThOrdenacao(
                $objTipoPrioridadeDTO,
                'Nome',
                'Nome',
                $arrObjTipoPrioridadeDTO
            ) . '</th>' . "\n";
        //$strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoPrioridadeDTO,'Descrição','Descricao',$arrObjTipoPrioridadeDTO).'</th>'."\n";
        $strResultado .= '<th class="infraTh">Descrição</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="15%">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {
            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td valign="center">' . PaginaSEI::getInstance()->getTrCheck(
                        $i,
                        $arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade(),
                        $arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade()
                    ) . '</td>';
            }
            $strResultado .= '<td>' . PaginaSEI::tratarHTML($arrObjTipoPrioridadeDTO[$i]->getStrNome()) . '</td>';
            $strResultado .= '<td>' . nl2br(PaginaSEI::tratarHTML($arrObjTipoPrioridadeDTO[$i]->getStrDescricao())) . '</td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem(
                $i,
                $arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade()
            );

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink(
                        'controlador.php?acao=tipo_prioridade_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_prioridade=' . $arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade(
                        )
                    ) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela(
                    ) . '"><img src="' . PaginaSEI::getInstance()->getIconeConsultar(
                    ) . '" title="Consultar Tipo de Prioridade" alt="Consultar Tipo de Prioridade" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink(
                        'controlador.php?acao=tipo_prioridade_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_tipo_prioridade=' . $arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade(
                        )
                    ) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela(
                    ) . '"><img src="' . PaginaSEI::getInstance()->getIconeAlterar(
                    ) . '" title="Alterar Tipo de Prioridade" alt="Alterar Tipo de Prioridade" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId = $arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade();
                $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript(
                    $arrObjTipoPrioridadeDTO[$i]->getStrNome()
                );
            }

            if ($bolAcaoDesativar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora(
                        $strId
                    ) . '" onclick="acaoDesativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance(
                    )->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeDesativar(
                    ) . '" title="Desativar Tipo de Prioridade" alt="Desativar Tipo de Prioridade" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoReativar) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora(
                        $strId
                    ) . '" onclick="acaoReativar(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance(
                    )->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeReativar(
                    ) . '" title="Reativar Tipo de Prioridade" alt="Reativar Tipo de Prioridade" class="infraImg" /></a>&nbsp;';
            }


            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora(
                        $strId
                    ) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance(
                    )->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeExcluir(
                    ) . '" title="Excluir Tipo de Prioridade" alt="Excluir Tipo de Prioridade" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'tipo_prioridade_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
    } else {
        $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance(
            )->assinarLink(
                'controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']
            ) . '\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
    }
} catch (Exception $e) {
    PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
<? if (0){ ?>
    <style><?}?>
        <? if (0){ ?></style><?
} ?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<? if (0){ ?>
    <script type="text/javascript"><?}?>

        function inicializar() {
            if ('<?=$_GET['acao']?>' == 'tipo_prioridade_selecionar') {
                infraReceberSelecao();
                document.getElementById('btnFecharSelecao').focus();
            } else {
                document.getElementById('btnFechar').focus();
            }
            infraEfeitoTabelas(true);
        }

        <? if ($bolAcaoDesativar){ ?>
        function acaoDesativar(id, desc) {
            if (confirm("Confirma desativação do Tipo de Prioridade \"" + desc + "\"?")) {
                document.getElementById('hdnInfraItemId').value = id;
                document.getElementById('frmTipoPrioridadeLista').action = '<?=$strLinkDesativar?>';
                document.getElementById('frmTipoPrioridadeLista').submit();
            }
        }

        function acaoDesativacaoMultipla() {
            if (document.getElementById('hdnInfraItensSelecionados').value == '') {
                alert('Nenhum Tipo de Prioridade selecionado.');
                return;
            }
            if (confirm("Confirma desativação dos Tipos de Prioridade selecionados?")) {
                document.getElementById('hdnInfraItemId').value = '';
                document.getElementById('frmTipoPrioridadeLista').action = '<?=$strLinkDesativar?>';
                document.getElementById('frmTipoPrioridadeLista').submit();
            }
        }
        <? } ?>

        <? if ($bolAcaoReativar){ ?>
        function acaoReativar(id, desc) {
            if (confirm("Confirma reativação do Tipo de Prioridade \"" + desc + "\"?")) {
                document.getElementById('hdnInfraItemId').value = id;
                document.getElementById('frmTipoPrioridadeLista').action = '<?=$strLinkReativar?>';
                document.getElementById('frmTipoPrioridadeLista').submit();
            }
        }

        function acaoReativacaoMultipla() {
            if (document.getElementById('hdnInfraItensSelecionados').value == '') {
                alert('Nenhum Tipo de Prioridade selecionado.');
                return;
            }
            if (confirm("Confirma reativação dos Tipos de Prioridade selecionados?")) {
                document.getElementById('hdnInfraItemId').value = '';
                document.getElementById('frmTipoPrioridadeLista').action = '<?=$strLinkReativar?>';
                document.getElementById('frmTipoPrioridadeLista').submit();
            }
        }
        <? } ?>

        <? if ($bolAcaoExcluir){ ?>
        function acaoExcluir(id, desc) {
            if (confirm("Confirma exclusão do Tipo de Prioridade \"" + desc + "\"?")) {
                document.getElementById('hdnInfraItemId').value = id;
                document.getElementById('frmTipoPrioridadeLista').action = '<?=$strLinkExcluir?>';
                document.getElementById('frmTipoPrioridadeLista').submit();
            }
        }

        function acaoExclusaoMultipla() {
            if (document.getElementById('hdnInfraItensSelecionados').value == '') {
                alert('Nenhum Tipo de Prioridade selecionado.');
                return;
            }
            if (confirm("Confirma exclusão dos Tipos de Prioridade selecionados?")) {
                document.getElementById('hdnInfraItemId').value = '';
                document.getElementById('frmTipoPrioridadeLista').action = '<?=$strLinkExcluir?>';
                document.getElementById('frmTipoPrioridadeLista').submit();
            }
        }
        <? } ?>

        <? if (0){ ?></script><?
} ?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTipoPrioridadeLista" method="post" action="<?= SessaoSEI::getInstance()->assinarLink(
        'controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']
    ) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        //PaginaSEI::getInstance()->abrirAreaDados('5em');
        //PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        //PaginaSEI::getInstance()->montarAreaDebug();
        PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
