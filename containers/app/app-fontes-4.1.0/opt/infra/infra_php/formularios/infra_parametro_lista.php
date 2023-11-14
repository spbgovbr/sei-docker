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

    PaginaInfra::getInstance()->prepararSelecao('infra_parametro_selecionar');

    SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

    switch ($_GET['acao']) {
        case 'infra_parametro_excluir':
            try {
                $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
                $arrObjInfraParametroDTO = array();
                for ($i = 0; $i < count($arrStrIds); $i++) {
                    $objInfraParametroDTO = new InfraParametroDTO();
                    $objInfraParametroDTO->setStrNome($arrStrIds[$i]);
                    $arrObjInfraParametroDTO[] = $objInfraParametroDTO;
                }
                $objInfraParametroRN = new InfraParametroRN();
                $objInfraParametroRN->excluir($arrObjInfraParametroDTO);
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
            case 'infra_parametro_desativar':
              try{
                $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
                $arrObjInfraParametroDTO = array();
                for ($i=0;$i<count($arrStrIds);$i++){
                  $objInfraParametroDTO = new InfraParametroDTO();
                  $objInfraParametroDTO->setStrNome($arrStrIds[$i]);
                  $arrObjInfraParametroDTO[] = $objInfraParametroDTO;
                }
                $objInfraParametroRN = new InfraParametroRN();
                $objInfraParametroRN->desativar($arrObjInfraParametroDTO);
                PaginaInfra::getInstance()->setStrMensagem('Operação realizada com sucesso.');
              }catch(Exception $e){
                PaginaInfra::getInstance()->processarExcecao($e);
              }
              header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
              die;

            case 'infra_parametro_reativar':
              $strTitulo = 'Reativar Parâmetros';
              if ($_GET['acao_confirmada']=='sim'){
                try{
                  $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
                  $arrObjInfraParametroDTO = array();
                  for ($i=0;$i<count($arrStrIds);$i++){
                    $objInfraParametroDTO = new InfraParametroDTO();
                    $objInfraParametroDTO->setStrNome($arrStrIds[$i]);
                    $arrObjInfraParametroDTO[] = $objInfraParametroDTO;
                  }
                  $objInfraParametroRN = new InfraParametroRN();
                  $objInfraParametroRN->reativar($arrObjInfraParametroDTO);
                  PaginaInfra::getInstance()->setStrMensagem('Operação realizada com sucesso.');
                }catch(Exception $e){
                  PaginaInfra::getInstance()->processarExcecao($e);
                }
                header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
                die;
              }
              break;

         */
        case 'infra_parametro_selecionar':
            $strTitulo = PaginaInfra::getInstance()->getTituloSelecao('Selecionar Parâmetro', 'Selecionar Parâmetros');

            //Se cadastrou alguem
            if ($_GET['acao_origem'] == 'infra_parametro_cadastrar') {
                if (isset($_GET['nome'])) {
                    PaginaInfra::getInstance()->adicionarSelecionado($_GET['nome']);
                }
            }
            break;

        case 'infra_parametro_listar':
            $strTitulo = 'Parâmetros';
            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }

    $arrComandos = array();
    if ($_GET['acao'] == 'infra_parametro_selecionar') {
        $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
    }

    /* if ($_GET['acao'] == 'infra_parametro_listar' || $_GET['acao'] == 'infra_parametro_selecionar'){ */
    $bolAcaoCadastrar = SessaoInfra::getInstance()->verificarPermissao('infra_parametro_cadastrar');
    if ($bolAcaoCadastrar) {
        $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\'' . SessaoInfra::getInstance(
            )->assinarLink(
                'controlador.php?acao=infra_parametro_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']
            ) . '\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
    /* } */

    $objInfraParametroDTO = new InfraParametroDTO();
    $objInfraParametroDTO->retStrNome();
    $objInfraParametroDTO->retStrValor();
    /*
      if ($_GET['acao'] == 'infra_parametro_reativar'){
        //Lista somente inativos
        $objInfraParametroDTO->setBolExclusaoLogica(false);
        $objInfraParametroDTO->setStrSinAtivo('N');
      }
     */
    PaginaInfra::getInstance()->prepararOrdenacao($objInfraParametroDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
    //PaginaInfra::getInstance()->prepararPaginacao($objInfraParametroDTO);

    $objInfraParametroRN = new InfraParametroRN();
    $arrObjInfraParametroDTO = $objInfraParametroRN->listar($objInfraParametroDTO);

    //PaginaInfra::getInstance()->processarPaginacao($objInfraParametroDTO);
    $numRegistros = count($arrObjInfraParametroDTO);

    if ($numRegistros > 0) {
        $bolCheck = false;

        if ($_GET['acao'] == 'infra_parametro_selecionar') {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = false;
            $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_parametro_alterar');
            $bolAcaoImprimir = false;
            $bolAcaoExcluir = false;
            $bolAcaoDesativar = false;
            $bolCheck = true;
            /*     }elseif ($_GET['acao']=='infra_parametro_reativar'){
                  $bolAcaoReativar = SessaoInfra::getInstance()->verificarPermissao('infra_parametro_reativar');
                  $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_parametro_consultar');
                  $bolAcaoAlterar = false;
                  $bolAcaoImprimir = true;
                  $bolAcaoExcluir = SessaoInfra::getInstance()->verificarPermissao('infra_parametro_excluir');
                  $bolAcaoDesativar = false;
             */
        } else {
            $bolAcaoReativar = false;
            $bolAcaoConsultar = false;
            $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_parametro_alterar');
            $bolAcaoImprimir = true;
            $bolAcaoExcluir = SessaoInfra::getInstance()->verificarPermissao('infra_parametro_excluir');
            $bolAcaoDesativar = SessaoInfra::getInstance()->verificarPermissao('infra_parametro_desativar');
        }

        /*
        if ($bolAcaoDesativar){
          $bolCheck = true;
          $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
          $strLinkDesativar = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_parametro_desativar&acao_origem='.$_GET['acao']);
        }

        if ($bolAcaoReativar){
          $bolCheck = true;
          $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
          $strLinkReativar = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_parametro_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
        }
         */

        if ($bolAcaoExcluir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
            $strLinkExcluir = SessaoInfra::getInstance()->assinarLink(
                'controlador.php?acao=infra_parametro_excluir&acao_origem=' . $_GET['acao']
            );
        }

        if ($bolAcaoImprimir) {
            $bolCheck = true;
            $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
        }

        $strResultado = '';

        /* if ($_GET['acao']!='infra_parametro_reativar'){ */
        $strSumarioTabela = 'Tabela de Parâmetros.';
        $strCaptionTabela = 'Parâmetros';
        /* }else{
          $strSumarioTabela = 'Tabela de Parâmetros Inativos.';
          $strCaptionTabela = 'Parâmetros Inativos';
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
                $objInfraParametroDTO,
                'Nome',
                'Nome',
                $arrObjInfraParametroDTO
            ) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh">' . PaginaInfra::getInstance()->getThOrdenacao(
                $objInfraParametroDTO,
                'Valor',
                'Valor',
                $arrObjInfraParametroDTO
            ) . '</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="10%">Ações</th>' . "\n";
        $strResultado .= '</tr>' . "\n";
        $strCssTr = '';
        for ($i = 0; $i < $numRegistros; $i++) {
            $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
            $strResultado .= $strCssTr;

            if ($bolCheck) {
                $strResultado .= '<td valign="top">' . PaginaInfra::getInstance()->getTrCheck(
                        $i,
                        $arrObjInfraParametroDTO[$i]->getStrNome(),
                        $arrObjInfraParametroDTO[$i]->getStrNome()
                    ) . '</td>';
            }

            $strResultado .= '<td valign="top">' . PaginaInfra::getInstance()->tratarHTML(
                    $arrObjInfraParametroDTO[$i]->getStrNome()
                ) . '</td>';

            if (substr($arrObjInfraParametroDTO[$i]->getStrValor(), 0, strlen('data:image')) == 'data:image') {
                $strResultado .= '<td valign="top"><img alt="' . PaginaInfra::getInstance()->tratarHTML(
                        $arrObjInfraParametroDTO[$i]->getStrNome()
                    ) . '" src="' . $arrObjInfraParametroDTO[$i]->getStrValor() . '" /></td>';
            } else {
                $strResultado .= '<td valign="top">' . nl2br(
                        PaginaInfra::getInstance()->tratarHTML($arrObjInfraParametroDTO[$i]->getStrValor())
                    ) . '</td>';
            }

            $strResultado .= '<td valign="top" align="center">';

            $strResultado .= PaginaInfra::getInstance()->getAcaoTransportarItem(
                $i,
                $arrObjInfraParametroDTO[$i]->getStrNome()
            );

            if ($bolAcaoConsultar) {
                $strResultado .= '<a href="' . SessaoInfra::getInstance()->assinarLink(
                        'controlador.php?acao=infra_parametro_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&nome=' . $arrObjInfraParametroDTO[$i]->getStrNome(
                        )
                    ) . '" tabindex="' . PaginaInfra::getInstance()->getProxTabTabela(
                    ) . '"><img src="' . PaginaInfra::getInstance()->getIconeConsultar(
                    ) . '" title="Consultar Parâmetro" alt="Consultar Parâmetro" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoAlterar) {
                $strResultado .= '<a href="' . SessaoInfra::getInstance()->assinarLink(
                        'controlador.php?acao=infra_parametro_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&nome=' . $arrObjInfraParametroDTO[$i]->getStrNome(
                        )
                    ) . '" tabindex="' . PaginaInfra::getInstance()->getProxTabTabela(
                    ) . '"><img src="' . PaginaInfra::getInstance()->getIconeAlterar(
                    ) . '" title="Alterar Parâmetro" alt="Alterar Parâmetro" class="infraImg" /></a>&nbsp;';
            }

            if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
                $strId = $arrObjInfraParametroDTO[$i]->getStrNome();
                $strDescricao = PaginaInfra::getInstance()->formatarParametrosJavaScript(
                    $arrObjInfraParametroDTO[$i]->getStrNome()
                );
            }
            /*
                  if ($bolAcaoDesativar){
                    $strResultado .= '<a href="'.PaginaInfra::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeDesativar().'" title="Desativar Parâmetro" alt="Desativar Parâmetro" class="infraImg" /></a>&nbsp;';
                  }

                  if ($bolAcaoReativar){
                    $strResultado .= '<a href="'.PaginaInfra::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeReativar().'" title="Reativar Parâmetro" alt="Reativar Parâmetro" class="infraImg" /></a>&nbsp;';
                  }
             */

            if ($bolAcaoExcluir) {
                $strResultado .= '<a href="' . PaginaInfra::getInstance()->montarAncora(
                        $strId
                    ) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaInfra::getInstance(
                    )->getProxTabTabela() . '"><img src="' . PaginaInfra::getInstance()->getIconeExcluir(
                    ) . '" title="Excluir Parâmetro" alt="Excluir Parâmetro" class="infraImg" /></a>&nbsp;';
            }

            $strResultado .= '</td></tr>' . "\n";
        }
        $strResultado .= '</table>';
    }
    if ($_GET['acao'] == 'infra_parametro_selecionar') {
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
    if ('<?= $_GET['acao'] ?>'=='infra_parametro_selecionar'){
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
    if (confirm("Confirma desativação do Parâmetro \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraParametroLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmInfraParametroLista').submit();
    }
    }

    function acaoDesativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Parâmetro selecionado.');
    return;
    }
    if (confirm("Confirma desativação dos Parâmetros selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraParametroLista').action='<?= $strLinkDesativar ?>';
    document.getElementById('frmInfraParametroLista').submit();
    }
    }
<?
} ?>

<?
if ($bolAcaoReativar) { ?>
    function acaoReativar(id,desc){
    if (confirm("Confirma reativação do Parâmetro \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraParametroLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmInfraParametroLista').submit();
    }
    }

    function acaoReativacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Parâmetro selecionado.');
    return;
    }
    if (confirm("Confirma reativação dos Parâmetros selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraParametroLista').action='<?= $strLinkReativar ?>';
    document.getElementById('frmInfraParametroLista').submit();
    }
    }
<?
} ?>

<?
if ($bolAcaoExcluir) { ?>
    function acaoExcluir(id,desc){
    if (confirm("Confirma exclusão do Parâmetro \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraParametroLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmInfraParametroLista').submit();
    }
    }

    function acaoExclusaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Parâmetro selecionado.');
    return;
    }
    if (confirm("Confirma exclusão dos Parâmetros selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraParametroLista').action='<?= $strLinkExcluir ?>';
    document.getElementById('frmInfraParametroLista').submit();
    }
    }
<?
} ?>

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
    <form id="frmInfraParametroLista" method="post" action="<?= SessaoInfra::getInstance()->assinarLink(
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
