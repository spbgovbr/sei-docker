<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/12/2007 - criado por fbv
*
* Versão do Gerador de Código: 1.10.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('vocativo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'vocativo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjVocativoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objVocativoDTO = new VocativoDTO();
          $objVocativoDTO->setNumIdVocativo($arrStrIds[$i]);
          $arrObjVocativoDTO[] = $objVocativoDTO;
        }
        $objVocativoRN = new VocativoRN();
        $objVocativoRN->excluirRN0311($arrObjVocativoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
			header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'vocativo_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjVocativoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objVocativoDTO = new VocativoDTO();
          $objVocativoDTO->setNumIdVocativo($arrStrIds[$i]);
          $arrObjVocativoDTO[] = $objVocativoDTO;
        }
        $objVocativoRN = new VocativoRN();
        $objVocativoRN->desativarRN0347($arrObjVocativoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'vocativo_reativar':
      $strTitulo = 'Reativar Vocativo';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjVocativoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objVocativoDTO = new VocativoDTO();
            $objVocativoDTO->setNumIdVocativo($arrStrIds[$i]);
            $arrObjVocativoDTO[] = $objVocativoDTO;
          }
          $objVocativoRN = new VocativoRN();
          $objVocativoRN->reativarRN0348($arrObjVocativoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

    case 'vocativo_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Vocativo','Selecionar Vocativos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='vocativo_cadastrar'){
        if (isset($_GET['id_vocativo'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_vocativo']);
        }
      }
      break;

    case 'vocativo_listar':
      $strTitulo = 'Vocativos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'vocativo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'vocativo_listar' || $_GET['acao'] == 'vocativo_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('vocativo_cadastrar');
    if ($bolAcaoCadastrar){    	
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=vocativo_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }
   

  $objVocativoDTO = new VocativoDTO();
  $objVocativoDTO->retNumIdVocativo();
  $objVocativoDTO->retStrExpressao();

  if ($_GET['acao'] == 'vocativo_reativar'){
    //Lista somente inativos
    $objVocativoDTO->setBolExclusaoLogica(false);
    $objVocativoDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objVocativoDTO, 'Expressao', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objVocativoDTO);

  $objVocativoRN = new VocativoRN();
  $arrObjVocativoDTO = $objVocativoRN->listarRN0310($objVocativoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objVocativoDTO);
  $numRegistros = count($arrObjVocativoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='vocativo_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('vocativo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('vocativo_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='vocativo_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('vocativo_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('vocativo_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false; //SessaoSEI::getInstance()->verificarPermissao('vocativo_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('vocativo_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('vocativo_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('vocativo_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;      
      $arrComandos[] = '<button type="button" accesskey="T" name="btnDesativar" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=vocativo_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=vocativo_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=vocativo_excluir&acao_origem='.$_GET['acao']);
    }
    
    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    $strResultado = '';

    if ($_GET['acao']!='vocativo_reativar'){
      $strSumarioTabela = 'Tabela de Vocativos.';
      $strCaptionTabela = 'Vocativos';
    }else{
      $strSumarioTabela = 'Tabela de Vocativos Inativos.';
      $strCaptionTabela = 'Vocativos Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objVocativoDTO,'Expressão','Expressao',$arrObjVocativoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjVocativoDTO[$i]->getNumIdVocativo(),$arrObjVocativoDTO[$i]->getStrExpressao()).'</td>';
      }
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjVocativoDTO[$i]->getStrExpressao()).'</td>';
      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjVocativoDTO[$i]->getNumIdVocativo());
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=vocativo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_vocativo='.$arrObjVocativoDTO[$i]->getNumIdVocativo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Vocativo" alt="Consultar Vocativo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=vocativo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_vocativo='.$arrObjVocativoDTO[$i]->getNumIdVocativo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Vocativo" alt="Alterar Vocativo" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoDesativar){
        $strResultado .= '<a href="#ID-'.$arrObjVocativoDTO[$i]->getNumIdVocativo().'"  onclick="acaoDesativar(\''.$arrObjVocativoDTO[$i]->getNumIdVocativo().'\',\''.$arrObjVocativoDTO[$i]->getStrExpressao().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Vocativo" alt="Desativar Vocativo" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="#ID-'.$arrObjVocativoDTO[$i]->getNumIdVocativo().'"  onclick="acaoReativar(\''.$arrObjVocativoDTO[$i]->getNumIdVocativo().'\',\''.$arrObjVocativoDTO[$i]->getStrExpressao().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Vocativo" alt="Reativar Vocativo" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$arrObjVocativoDTO[$i]->getNumIdVocativo().'"  onclick="acaoExcluir(\''.$arrObjVocativoDTO[$i]->getNumIdVocativo().'\',\''.$arrObjVocativoDTO[$i]->getStrExpressao().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Vocativo" alt="Excluir Vocativo" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'vocativo_selecionar'){
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
  if ('<?=$_GET['acao']?>'=='vocativo_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }
  
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Vocativo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVocativoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmVocativoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Vocativo selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Vocativos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVocativoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmVocativoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Vocativo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVocativoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmVocativoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Vocativo selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Vocativos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVocativoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmVocativoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Vocativo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmVocativoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmVocativoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Vocativo selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Vocativos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmVocativoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmVocativoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmVocativoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>