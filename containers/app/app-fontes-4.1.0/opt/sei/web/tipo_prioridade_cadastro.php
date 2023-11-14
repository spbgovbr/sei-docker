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

    PaginaSEI::getInstance()->verificarSelecao('tipo_prioridade_selecionar');

    SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

    $objTipoPrioridadeDTO = new TipoPrioridadeDTO();

    $strDesabilitar = '';

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'tipo_prioridade_cadastrar':
            $strTitulo = 'Novo Tipo de Prioridade';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoPrioridade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance(
                )->assinarLink(
                    'controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno(
                    ) . '&acao_origem=' . $_GET['acao']
                ) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            $objTipoPrioridadeDTO->setNumIdTipoPrioridade(null);
            $objTipoPrioridadeDTO->setStrNome($_POST['txtNome']);
            $objTipoPrioridadeDTO->setStrDescricao($_POST['txaDescricao']);
            $objTipoPrioridadeDTO->setStrSinAtivo('S');

            if (isset($_POST['sbmCadastrarTipoPrioridade'])) {
                try {
                    $objTipoPrioridadeRN = new TipoPrioridadeRN();
                    $objTipoPrioridadeDTO = $objTipoPrioridadeRN->cadastrar($objTipoPrioridadeDTO);
                    PaginaSEI::getInstance()->adicionarMensagem(
                        'Tipo de Prioridade "' . $objTipoPrioridadeDTO->getNumIdTipoPrioridade(
                        ) . '" cadastrado com sucesso.'
                    );
                    header(
                        'Location: ' . SessaoSEI::getInstance()->assinarLink(
                            'controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno(
                            ) . '&acao_origem=' . $_GET['acao'] . '&id_tipo_prioridade=' . $objTipoPrioridadeDTO->getNumIdTipoPrioridade(
                            ) . PaginaSEI::getInstance()->montarAncora($objTipoPrioridadeDTO->getNumIdTipoPrioridade())
                        )
                    );
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'tipo_prioridade_alterar':
            $strTitulo = 'Alterar Tipo de Prioridade';
            $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoPrioridade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
            $strDesabilitar = 'disabled="disabled"';

            if (isset($_GET['id_tipo_prioridade'])) {
                $objTipoPrioridadeDTO->setNumIdTipoPrioridade($_GET['id_tipo_prioridade']);
                $objTipoPrioridadeDTO->retTodos();
                $objTipoPrioridadeRN = new TipoPrioridadeRN();
                $objTipoPrioridadeDTO = $objTipoPrioridadeRN->consultar($objTipoPrioridadeDTO);
                if ($objTipoPrioridadeDTO == null) {
                    throw new InfraException("Registro não encontrado.");
                }
            } else {
                $objTipoPrioridadeDTO->setNumIdTipoPrioridade($_POST['hdnIdTipoPrioridade']);
                $objTipoPrioridadeDTO->setStrNome($_POST['txtNome']);
                $objTipoPrioridadeDTO->setStrDescricao($_POST['txaDescricao']);
                $objTipoPrioridadeDTO->setStrSinAtivo('S');
            }

            $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance(
                )->assinarLink(
                    'controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno(
                    ) . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora(
                        $objTipoPrioridadeDTO->getNumIdTipoPrioridade()
                    )
                ) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

            if (isset($_POST['sbmAlterarTipoPrioridade'])) {
                try {
                    $objTipoPrioridadeRN = new TipoPrioridadeRN();
                    $objTipoPrioridadeRN->alterar($objTipoPrioridadeDTO);
                    PaginaSEI::getInstance()->adicionarMensagem(
                        'Tipo de Prioridade "' . $objTipoPrioridadeDTO->getNumIdTipoPrioridade(
                        ) . '" alterado com sucesso.'
                    );
                    header(
                        'Location: ' . SessaoSEI::getInstance()->assinarLink(
                            'controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno(
                            ) . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora(
                                $objTipoPrioridadeDTO->getNumIdTipoPrioridade()
                            )
                        )
                    );
                    die;
                } catch (Exception $e) {
                    PaginaSEI::getInstance()->processarExcecao($e);
                }
            }
            break;

        case 'tipo_prioridade_consultar':
            $strTitulo = 'Consultar Tipo de Prioridade';
            $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance(
                )->assinarLink(
                    'controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno(
                    ) . '&acao_origem=' . $_GET['acao'] . PaginaSEI::getInstance()->montarAncora(
                        $_GET['id_tipo_prioridade']
                    )
                ) . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
            $objTipoPrioridadeDTO->setNumIdTipoPrioridade($_GET['id_tipo_prioridade']);
            $objTipoPrioridadeDTO->setBolExclusaoLogica(false);
            $objTipoPrioridadeDTO->retTodos();
            $objTipoPrioridadeRN = new TipoPrioridadeRN();
            $objTipoPrioridadeDTO = $objTipoPrioridadeRN->consultar($objTipoPrioridadeDTO);
            if ($objTipoPrioridadeDTO === null) {
                throw new InfraException("Registro não encontrado.");
            }
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
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
        #lblNome {
            position: absolute;
            left: 0%;
            top: 0%;
            width: 70%;
        }

        #txtNome {
            position: absolute;
            left: 0%;
            top: 40%;
            width: 70%;
        }

        #lblDescricao {
            position: absolute;
            left: 0%;
            top: 0%;
            width: 95%;
        }

        #txaDescricao {
            position: absolute;
            left: 0%;
            top: 40%;
            width: 95%;
        }

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
            if ('<?=$_GET['acao']?>' == 'tipo_prioridade_cadastrar') {
                document.getElementById('txtNome').focus();
            } else if ('<?=$_GET['acao']?>' == 'tipo_prioridade_consultar') {
                infraDesabilitarCamposAreaDados();
            } else {
                document.getElementById('btnCancelar').focus();
            }
            infraEfeitoTabelas(true);
        }

        function validarCadastro() {
            if (infraTrim(document.getElementById('txtNome').value) == '') {
                alert('Informe o Nome.');
                document.getElementById('txtNome').focus();
                return false;
            }

            return true;
        }

        function OnSubmitForm() {
            return validarCadastro();
        }

        <? if (0){ ?></script><?
} ?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmTipoPrioridadeCadastro" method="post" onsubmit="return OnSubmitForm();"
          action="<?= SessaoSEI::getInstance()->assinarLink(
              'controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']
          ) ?>">
        <?
        PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
        //PaginaSEI::getInstance()->montarAreaValidacao();
        PaginaSEI::getInstance()->abrirAreaDados('4.5em');
        ?>
        <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span
                class="infraTeclaAtalho">N</span>ome:</label>
        <input type="text" id="txtNome" name="txtNome" class="infraText"
               value="<?= PaginaSEI::tratarHTML($objTipoPrioridadeDTO->getStrNome()); ?>"
               onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100"
               tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        PaginaSEI::getInstance()->abrirAreaDados('4.5em');
        ?>
        <label id="lblDescricao" for="txaDescricao" accesskey="D" class="infraLabelOpcional"><span
                class="infraTeclaAtalho">D</span>escrição:</label>
        <textarea id="txaDescricao" name="txaDescricao" rows="3"
                  onkeypress="return infraLimitarTexto(this,event,500);" maxlength="500"
                  class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objTipoPrioridadeDTO->getStrDescricao());?></textarea>
        <?
        PaginaSEI::getInstance()->fecharAreaDados();
        ?>
        <input type="hidden" id="hdnIdTipoPrioridade" name="hdnIdTipoPrioridade"
               value="<?= $objTipoPrioridadeDTO->getNumIdTipoPrioridade(); ?>"/>
        <?
        //PaginaSEI::getInstance()->montarAreaDebug();
        //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
