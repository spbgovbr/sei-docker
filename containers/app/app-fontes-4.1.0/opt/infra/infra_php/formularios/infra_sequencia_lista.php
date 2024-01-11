<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/08/2009 - criado por mga
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

    PaginaInfra::getInstance()->prepararSelecao('infra_sequencia_selecionar');

    SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

    switch ($_GET['acao']) {
        case 'infra_sequencia_excluir':
            try {
                $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
                $arrObjInfraSequenciaDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objInfraSequenciaDTO = new InfraSequenciaDTO();
                    $objInfraSequenciaDTO->setStrNome($arrStrIds[$i]);
                    $arrObjInfraSequenciaDTO[] = $objInfraSequenciaDTO;
                }
                $objInfraSequenciaRN = new InfraSequenciaRN();
                $objInfraSequenciaRN->excluir($arrObjInfraSequenciaDTO);
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

        /*
            case 'infra_sequencia_desativar':
              try{
                $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
                $arrObjInfraSequenciaDTO = array();
                for ($i=0;$i<count($arrStrIds);$i++){
                  $objInfraSequenciaDTO = new InfraSequenciaDTO();
                  $objInfraSequenciaDTO->setStrNome($arrStrIds[$i]);
                  $arrObjInfraSequenciaDTO[] = $objInfraSequenciaDTO;
                }
                $objInfraSequenciaRN = new InfraSequenciaRN();
                $objInfraSequenciaRN->desativar($arrObjInfraSequenciaDTO);
                PaginaInfra::getInstance()->setStrMensagem('Operação realizada com sucesso.');
              }catch(Exception $e){
                PaginaInfra::getInstance()->processarExcecao($e);
              }
              header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
              die;

            case 'infra_sequencia_reativar':
              $strTitulo = 'Reativar Sequências';
              if ($_GET['acao_confirmada']=='sim'){
                try{
                  $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
                  $arrObjInfraSequenciaDTO = array();
                  for ($i=0;$i<count($arrStrIds);$i++){
                    $objInfraSequenciaDTO = new InfraSequenciaDTO();
                    $objInfraSequenciaDTO->setStrNome($arrStrIds[$i]);
                    $arrObjInfraSequenciaDTO[] = $objInfraSequenciaDTO;
                  }
                  $objInfraSequenciaRN = new InfraSequenciaRN();
                  $objInfraSequenciaRN->reativar($arrObjInfraSequenciaDTO);
                  PaginaInfra::getInstance()->setStrMensagem('Operação realizada com sucesso.');
                }catch(Exception $e){
                  PaginaInfra::getInstance()->processarExcecao($e);
                }
                header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
                die;
              }
              break;

         */
        case 'infra_sequencia_selecionar':
            $strTitulo = PaginaInfra::getInstance()->getTituloSelecao('Selecionar Sequência', 'Selecionar Sequências');

            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'infra_sequencia_cadastrar') {
                if (isset($_GET['nome'])) {
                    PaginaInfra::getInstance()->adicionarSelecionado($_GET['nome']);
                }
            }
            break;

        case 'infra_sequencia_listar':
            $strTitulo = 'Sequências';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos = array();
    if ($_GET['acao'] == 'infra_sequencia_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    /* if ($_GET['acao'] == 'infra_sequencia_listar' || $_GET['acao'] == 'infra_sequencia_selecionar'){ */
    $bolAcaoCadastrar = SessaoInfra::getInstance()->verificarPermissao('infra_sequencia_cadastrar');
    if ($bolAcaoCadastrar) {
        $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\'' . SessaoInfra::getInstance(
            )->assinarLink(
                'controlador.php?acao=infra_sequencia_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']
            ) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    }
    /* } */

    $objInfraSequenciaDTO = new InfraSequenciaDTO();
    $objInfraSequenciaDTO->retStrNome();
    $objInfraSequenciaDTO->retDblQtdIncremento();
    $objInfraSequenciaDTO->retDblNumAtual();
    $objInfraSequenciaDTO->retDblNumMaximo();
    /*
      if ($_GET['acao'] == 'infra_sequencia_reativar'){
        //Lista somente inativos
        $objInfraSequenciaDTO->setBolExclusaoLogica(false);
        $objInfraSequenciaDTO->setStrSinAtivo('N');
      }
     */
    PaginaInfra::getInstance()->prepararOrdenacao($objInfraSequenciaDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
    //PaginaInfra::getInstance()->prepararPaginacao($objInfraSequenciaDTO);

    $objInfraSequenciaRN = new InfraSequenciaRN();
    $arrObjInfraSequenciaDTO = $objInfraSequenciaRN->listar($objInfraSequenciaDTO);

    //PaginaInfra::getInstance()->processarPaginacao($objInfraSequenciaDTO);
    $numRegistros = count($arrObjInfraSequenciaDTO);

    if ($numRegistros > 0) {
        $bolCheck = false;

        if ($_GET['acao'] == 'infra_sequencia_selecionar') {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_sequencia_consultar');
            $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_sequencia_alterar');
            $bolAcaoImprimir = false;
            $bolAcaoExcluir = false;
            $bolAcaoDesativar = false;
            $bolCheck = true;
            /*     }elseif ($_GET['acao']=='infra_sequencia_reativar'){
                  $bolAcaoReativar = SessaoInfra::getInstance()->verificarPermissao('infra_sequencia_reativar');
                  $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_sequencia_consultar');
                  $bolAcaoAlterar = false;
                  $bolAcaoImprimir = true;
                  $bolAcaoExcluir = SessaoInfra::getInstance()->verificarPermissao('infra_sequencia_excluir');
                  $bolAcaoDesativar = false;
             */
        } else {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_sequencia_consultar');
            $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_sequencia_alterar');
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoInfra::getInstance()->verificarPermissao('infra_sequencia_excluir');
            $bolAcaoDesativar = SessaoInfra::getInstance()->verificarPermissao('infra_sequencia_desativar');
        }

        /*
        if ($bolAcaoDesativar){
          $bolCheck = true;
          $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
          $strLinkDesativar = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_sequencia_desativar&acao_origem='.$_GET['acao']);
        }

        if ($bolAcaoReativar){
          $bolCheck = true;
          $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
          $strLinkReativar = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_sequencia_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
        }
         */

        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
            $strLinkExcluir = SessaoInfra::getInstance()->assinarLink(
                'controlador.php?acao=infra_sequencia_excluir&acao_origem=' . $_GET['acao']
            );
        }

        if ($bolAcaoImprimir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        }

        $strResultado = '';

        /* if ($_GET['acao']!='infra_sequencia_reativar'){ */
        $strSumarioTabela = 'Tabela de Sequências.';
        $strCaptionTabela = 'Sequências';
        /* }else{
          $strSumarioTabela = 'Tabela de Sequências Inativas.';
          $strCaptionTabela = 'Sequências Inativas';
        } */

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
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraSequenciaDTO,
                'Nome',
                'Nome',
                $arrObjInfraSequenciaDTO
            ) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraSequenciaDTO,
                'Incremento',
                'QtdIncremento',
                $arrObjInfraSequenciaDTO
            ) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraSequenciaDTO,
                'Valor Atual',
                'NumAtual',
                $arrObjInfraSequenciaDTO
            ) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraSequenciaDTO,
                'Valor Máximo',
                'NumMaximo',
                $arrObjInfraSequenciaDTO
            ) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="15%">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {
            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td valign="top">' . PaginaInfra::getInstance()->getTrCheck(
                        $i,
                        $arrObjInfraSequenciaDTO[$i]->getStrNome(),
                        $arrObjInfraSequenciaDTO[$i]->getStrNome()
                    ) . '</td>';
            }
            $strResultado .= '<td>' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraSequenciaDTO[$i]->getStrNome()
                ) . '</td>';
            $strResultado .= '<td>' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraSequenciaDTO[$i]->getDblQtdIncremento()
                ) . '</td>';
            $strResultado .= '<td>' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraSequenciaDTO[$i]->getDblNumAtual()
                ) . '</td>';
            $strResultado .= '<td>' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraSequenciaDTO[$i]->getDblNumMaximo()
                ) . '</td>';
            $strResultado .= '<td align="center">';

            $strResultado .= PaginaInfra::getInstance()->getAcaoTransportarItem(
                $i,
                $arrObjInfraSequenciaDTO[$i]->getStrNome()
            );

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . SessaoInfra::getInstance()->assinarLink(
                        'controlador.php?acao=infra_sequencia_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&nome=' . $arrObjInfraSequenciaDTO[$i]->getStrNome(
                        )
                    ) . '" tabindex="' . PaginaInfra::getInstance()->getProxTabTabela(
                    ) . '"><img src="' . PaginaInfra::getInstance()->getIconeConsultar(
                    ) . '" title="Consultar Sequência" alt="Consultar Sequência" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . SessaoInfra::getInstance()->assinarLink(
                        'controlador.php?acao=infra_sequencia_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&nome=' . $arrObjInfraSequenciaDTO[$i]->getStrNome(
                        )
                    ) . '" tabindex="' . PaginaInfra::getInstance()->getProxTabTabela(
                    ) . '"><img src="' . PaginaInfra::getInstance()->getIconeAlterar(
                    ) . '" title="Alterar Sequência" alt="Alterar Sequência" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId = $arrObjInfraSequenciaDTO[$i]->getStrNome();
                $strDescricao = PaginaInfra::getInstance()->formatarParametrosJavaScript(
                    $arrObjInfraSequenciaDTO[$i]->getStrNome()
                );
            }
            /*
                  if ($bolAcaoDesativar){
                    $strResultado .= '<a href="'.PaginaInfra::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeDesativar().'" title="Desativar Sequência" alt="Desativar Sequência" class="infraImg" /></a>&nbsp;';
                  }

                  if ($bolAcaoReativar){
                    $strResultado .= '<a href="'.PaginaInfra::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeReativar().'" title="Reativar Sequência" alt="Reativar Sequência" class="infraImg" /></a>&nbsp;';
                  }
             */

            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaInfra::getInstance()->montarAncora(
                        $strId
                    ) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaInfra::getInstance(
                    )->getProxTabTabela() . '"><img src="' . PaginaInfra::getInstance()->getIconeExcluir(
                    ) . '" title="Excluir Sequência" alt="Excluir Sequência" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'infra_sequencia_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
    } else {
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
<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>

    function inicializar(){
    if ('<?= $_GET['acao'] ?>'=='infra_sequencia_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
    }else{
    document.getElementById('btnFechar').focus();
    }
    infraEfeitoImagens();
    infraEfeitoTabelas();
    }

<?
if ($bolAcaoDesativar) { ?>
    function acaoDesativar(id,desc){
    if (confirm("Confirma desativação da Sequência \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraSequenciaLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmInfraSequenciaLista').submit();
    }
    }

    function acaoDesativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Sequência selecionada.');
    return;
    }
    if (confirm("Confirma desativação das Sequências selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraSequenciaLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmInfraSequenciaLista').submit();
    }
    }
<?
} ?>

<?
if ($bolAcaoReativar) { ?>
    function acaoReativar(id,desc){
    if (confirm("Confirma reativação da Sequência \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraSequenciaLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmInfraSequenciaLista').submit();
    }
    }

    function acaoReativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Sequência selecionada.');
    return;
    }
    if (confirm("Confirma reativação das Sequências selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraSequenciaLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmInfraSequenciaLista').submit();
    }
    }
<?
} ?>

<?
if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id,desc){
    if (confirm("Confirma exclusão da Sequência \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraSequenciaLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmInfraSequenciaLista').submit();
    }
    }

    function acaoExclusaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Sequência selecionada.');
    return;
    }
    if (confirm("Confirma exclusão das Sequências selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraSequenciaLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmInfraSequenciaLista').submit();
    }
    }
<?
} ?>

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmInfraSequenciaLista" method="post" action="<?= SessaoInfra::getInstance()->assinarLink(
        'controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']
    ) ?>">
        <?
        PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
        //PaginaInfra::getInstance()->abrirAreaDados('5em');
        //PaginaInfra::getInstance()->fecharAreaDados();
        PaginaInfra::getInstance()->montarAreaTabela($strResultado, $numRegistros);
        //PaginaInfra::getInstance()->montarAreaDebug();
        PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
        ?>
    </form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
