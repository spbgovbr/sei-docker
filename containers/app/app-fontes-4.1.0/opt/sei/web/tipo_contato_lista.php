<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/12/2007 - criado por fbv
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

  PaginaSEI::getInstance()->prepararSelecao('tipo_contato_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){
    case 'tipo_contato_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjTipoContatoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objTipoContatoDTO = new TipoContatoDTO();
          $objTipoContatoDTO->setNumIdTipoContato($arrStrIds[$i]);          
          $arrObjTipoContatoDTO[] = $objTipoContatoDTO;
        }
        $objTipoContatoRN = new TipoContatoRN();
        $objTipoContatoRN->excluirRN0338($arrObjTipoContatoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'tipo_contato_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjTipoContatoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objTipoContatoDTO = new TipoContatoDTO();
          $objTipoContatoDTO->setNumIdTipoContato($arrStrIds[$i]);
          $arrObjTipoContatoDTO[] = $objTipoContatoDTO;
        }
        $objTipoContatoRN = new TipoContatoRN();
        $objTipoContatoRN->desativarRN0339($arrObjTipoContatoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'tipo_contato_reativar':
      $strTitulo = 'Reativar Tipo de Contato';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjTipoContatoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objTipoContatoDTO = new TipoContatoDTO();
            $objTipoContatoDTO->setNumIdTipoContato($arrStrIds[$i]);
            $arrObjTipoContatoDTO[] = $objTipoContatoDTO;
          }
          $objTipoContatoRN = new TipoContatoRN();
          $objTipoContatoRN->reativarRN0354($arrObjTipoContatoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

    case 'tipo_contato_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Contato','Selecionar Tipos de Contato');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='tipo_contato_cadastrar'){
        if (isset($_GET['id_tipo_contato'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_tipo_contato']);
        }
      }
      break;

    case 'tipo_contato_listar':
      $strTitulo = 'Tipos de Contato';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'tipo_contato_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  if ($_GET['acao'] == 'tipo_contato_listar' || $_GET['acao'] == 'tipo_contato_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('tipo_contato_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_contato_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }
   
  $objTipoContatoDTO = new TipoContatoDTO();
  $objTipoContatoDTO->retNumIdTipoContato();
  $objTipoContatoDTO->retStrNome();
  $objTipoContatoDTO->retStrDescricao();

  if ($_GET['acao'] == 'tipo_contato_reativar'){
    //Lista somente inativos
    $objTipoContatoDTO->setBolExclusaoLogica(false);
    $objTipoContatoDTO->setStrSinAtivo('N');
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objTipoContatoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objTipoContatoDTO);

  $objTipoContatoRN = new TipoContatoRN();
  $arrObjTipoContatoDTO = $objTipoContatoRN->listarRN0337($objTipoContatoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objTipoContatoDTO);
  $numRegistros = count($arrObjTipoContatoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='tipo_contato_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tipo_contato_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tipo_contato_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
    }else if ($_GET['acao']=='tipo_contato_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('tipo_contato_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('tipo_contato_excluir');
      $bolAcaoDesativar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('tipo_contato_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('tipo_contato_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('tipo_contato_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('tipo_contato_desativar');
    }

    
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="T" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_contato_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_contato_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_contato_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    if ($_GET['acao']!='tipo_contato_reativar'){
      $strSumarioTabela = 'Tabela de Tipos de Contato.';
      $strCaptionTabela = 'Tipos de Contato';
    }else{
      $strSumarioTabela = 'Tabela de Tipos de Contato Inativos.';
      $strCaptionTabela = 'Tipos de Contato Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoContatoDTO,'ID','IdTipoContato',$arrObjTipoContatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoContatoDTO,'Nome','Nome',$arrObjTipoContatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjTipoContatoDTO[$i]->getNumIdTipoContato(),$arrObjTipoContatoDTO[$i]->getStrNome()).'</td>';
      }
      $strResultado .= '<td width="10%" align="center">'.$arrObjTipoContatoDTO[$i]->getNumIdTipoContato().'</td>';
      $strResultado .= '<td width="30%">'.PaginaSEI::tratarHTML($arrObjTipoContatoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td>'.nl2br(PaginaSEI::tratarHTML($arrObjTipoContatoDTO[$i]->getStrDescricao())).'</td>';
      $strResultado .= '<td width="15%" align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjTipoContatoDTO[$i]->getNumIdTipoContato());
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_contato_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_contato='.$arrObjTipoContatoDTO[$i]->getNumIdTipoContato()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Tipo de Contato" alt="Consultar Tipo de Contato" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_contato_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_contato='.$arrObjTipoContatoDTO[$i]->getNumIdTipoContato()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Tipo de Contato" alt="Alterar Tipo de Contato" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoDesativar){
        $strResultado .= '<a href="#ID-'.$arrObjTipoContatoDTO[$i]->getNumIdTipoContato().'"  onclick="acaoDesativar(\''.$arrObjTipoContatoDTO[$i]->getNumIdTipoContato().'\',\''.$arrObjTipoContatoDTO[$i]->getStrNome().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Tipo de Contato" alt="Desativar Tipo de Contato" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="#ID-'.$arrObjTipoContatoDTO[$i]->getNumIdTipoContato().'"  onclick="acaoReativar(\''.$arrObjTipoContatoDTO[$i]->getNumIdTipoContato().'\',\''.$arrObjTipoContatoDTO[$i]->getStrNome().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Tipo de Contato" alt="Reativar Tipo de Contato" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$arrObjTipoContatoDTO[$i]->getNumIdTipoContato().'"  onclick="acaoExcluir(\''.$arrObjTipoContatoDTO[$i]->getNumIdTipoContato().'\',\''.$arrObjTipoContatoDTO[$i]->getStrNome().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Tipo de Contato" alt="Excluir Tipo de Contato" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'tipo_contato_selecionar'){
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

  if ('<?=$_GET['acao']?>'=='tipo_contato_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
 }
  
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Tipo de Contato \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTipoContatoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmTipoContatoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Contato selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Tipos de Contato selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTipoContatoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmTipoContatoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Tipo de Contato \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTipoContatoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmTipoContatoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Contato selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Tipos de Contato selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTipoContatoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmTipoContatoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Tipo de Contato \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmTipoContatoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTipoContatoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Tipo de Contato selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Tipos de Contato selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmTipoContatoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmTipoContatoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTipoContatoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
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