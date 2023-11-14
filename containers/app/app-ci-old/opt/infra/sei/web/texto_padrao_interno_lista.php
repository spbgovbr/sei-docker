<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/11/2008 - criado por mga
*
* Versão do Gerador de Código: 1.25.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('texto_padrao_interno_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array('selUnidade'));

  switch($_GET['acao']){
    case 'texto_padrao_interno_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjTextoPadraoInternoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objTextoPadraoInternoDTO = new TextoPadraoInternoDTO();
          $objTextoPadraoInternoDTO->setNumIdTextoPadraoInterno($arrStrIds[$i]);
          $arrObjTextoPadraoInternoDTO[] = $objTextoPadraoInternoDTO;
        }
        $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
        $objTextoPadraoInternoRN->excluir($arrObjTextoPadraoInternoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'texto_padrao_interno_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Texto Padrão da Unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual(),'Selecionar Textos Padrão da Unidade' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual());

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='texto_padrao_interno_cadastrar'){
        if (isset($_GET['id_texto_padrao_interno'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_texto_padrao_interno']);
        }
      }
      break;

    case 'texto_padrao_interno_listar':
      $strTitulo = 'Textos Padrão da Unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual();
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'texto_padrao_interno_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'texto_padrao_interno_listar' || $_GET['acao'] == 'texto_padrao_interno_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=texto_padrao_interno_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objTextoPadraoInternoDTO = new TextoPadraoInternoDTO(true);
  $objTextoPadraoInternoDTO->retNumIdTextoPadraoInterno();
  $objTextoPadraoInternoDTO->retStrNome();
  $objTextoPadraoInternoDTO->retStrDescricao();
  $objTextoPadraoInternoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  
  /*if ($numIdUnidade!==''){
    $objTextoPadraoInternoDTO->setNumIdUnidade($numIdUnidade);
  }*/


  PaginaSEI::getInstance()->prepararOrdenacao($objTextoPadraoInternoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objTextoPadraoInternoDTO);

  $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
  $arrObjTextoPadraoInternoDTO = $objTextoPadraoInternoRN->listar($objTextoPadraoInternoDTO);

  PaginaSEI::getInstance()->processarPaginacao($objTextoPadraoInternoDTO);
  $numRegistros = count($arrObjTextoPadraoInternoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='texto_padrao_interno_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_alterar');
      $bolAcaoTextoPadraoInternoDesbloquear = false;
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='texto_padrao_interno_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_alterar');
      $bolAcaoTextoPadraoInternoDesbloquear = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_desbloquear');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('texto_padrao_interno_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=texto_padrao_interno_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=texto_padrao_interno_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=texto_padrao_interno_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    /* if ($_GET['acao']!='texto_padrao_interno_reativar'){ */
      $strSumarioTabela = 'Tabela de Textos Padrão Interno da Unidade.';
      $strCaptionTabela = 'Textos Padrão Interno da Unidade';
    /* }else{
      $strSumarioTabela = 'Tabela de Textos Padrão da Unidade Inativos.';
      $strCaptionTabela = 'Textos Padrão da Unidade Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="10%">ID</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTextoPadraoInternoDTO,'Nome','Nome',$arrObjTextoPadraoInternoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    
    $EDocRN = new EDocRN();
    $objDocumentoDTO = new DocumentoDTO();
    
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjTextoPadraoInternoDTO[$i]->getNumIdTextoPadraoInterno(),$arrObjTextoPadraoInternoDTO[$i]->getNumIdTextoPadraoInterno()).'</td>';
      }
      $strResultado .= '<td valign="top" align="center">'.$arrObjTextoPadraoInternoDTO[$i]->getNumIdTextoPadraoInterno().'</td>';
      $strResultado .= '<td valign="top">'.PaginaSEI::tratarHTML($arrObjTextoPadraoInternoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td valign="top">'.PaginaSEI::tratarHTML($arrObjTextoPadraoInternoDTO[$i]->getStrDescricao()).'</td>';
      $strResultado .= '<td valign="top" align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjTextoPadraoInternoDTO[$i]->getNumIdTextoPadraoInterno());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=texto_padrao_interno_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_texto_padrao_interno='.$arrObjTextoPadraoInternoDTO[$i]->getNumIdTextoPadraoInterno()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Texto Padrão da Unidade" alt="Consultar Texto Padrão da Unidade" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=texto_padrao_interno_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_texto_padrao_interno='.$arrObjTextoPadraoInternoDTO[$i]->getNumIdTextoPadraoInterno()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Texto Padrão da Unidade" alt="Alterar Texto Padrão da Unidade" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjTextoPadraoInternoDTO[$i]->getNumIdTextoPadraoInterno();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjTextoPadraoInternoDTO[$i]->getStrNome());
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$strId.'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Texto Padrão da Unidade" alt="Excluir Texto Padrão da Unidade" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'texto_padrao_interno_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='texto_padrao_interno_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Texto Padrão da Unidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTextoPadraoInternoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmTextoPadraoInternoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Texto Padrão da Unidade selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Textos Padrão da Unidade selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTextoPadraoInternoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmTextoPadraoInternoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Texto Padrão da Unidade \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTextoPadraoInternoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmTextoPadraoInternoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Texto Padrão da Unidade selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Textos Padrão da Unidade selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTextoPadraoInternoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmTextoPadraoInternoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Texto Padrão \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTextoPadraoInternoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTextoPadraoInternoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Texto Padrão da Unidade selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Textos Padrão da Unidade selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTextoPadraoInternoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTextoPadraoInternoLista').submit();
  }
}
<? } ?>

function desbloquear(link){
  if (confirm("ATENÇÃO: ao realizar o desbloqueio, a versão visualizada através da ação \"Visualizar Conteúdo\" será considerada como sendo a última editada.\n Confirma desbloqueio do documento?")){
     infraAbrirJanela(link,'janelaDesbloqueio',500,350,'location=0,status=1,resizable=1,scrollbars=1');
  }
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<!-- Edição de Conteúdo -->
<iframe id="ifrEditor" style="display:none;"></iframe>

<form id="frmTextoPadraoInternoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>