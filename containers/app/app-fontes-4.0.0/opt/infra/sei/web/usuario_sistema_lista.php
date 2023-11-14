<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/04/2008 - criado por mga
*
* Versão do Gerador de Código: 1.14.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('usuario_sistema_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  PaginaSEI::getInstance()->salvarCamposPost(array('selOrgao','txtSiglaUsuario','txtNomeUsuario'));

  switch($_GET['acao']){
    case 'usuario_sistema_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjUsuarioDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->setNumIdUsuario($arrStrIds[$i]);
          $arrObjUsuarioDTO[] = $objUsuarioDTO;
        }
        $objUsuarioRN = new UsuarioRN();
        $objUsuarioRN->excluirRN0491($arrObjUsuarioDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;


    case 'usuario_sistema_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjUsuarioDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->setNumIdUsuario($arrStrIds[$i]);
          $arrObjUsuarioDTO[] = $objUsuarioDTO;
        }
        $objUsuarioRN = new UsuarioRN();
        $objUsuarioRN->desativarRN0695($arrObjUsuarioDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'usuario_sistema_reativar':
      $strTitulo = 'Reativar Sistemas';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjUsuarioDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objUsuarioDTO = new UsuarioDTO();
            $objUsuarioDTO->setNumIdUsuario($arrStrIds[$i]);
            $arrObjUsuarioDTO[] = $objUsuarioDTO;
          }
          $objUsuarioRN = new UsuarioRN();
          $objUsuarioRN->reativarRN0696($arrObjUsuarioDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

    case 'usuario_sistema_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Sistema','Selecionar Sistemas');
      break;

    case 'usuario_sistema_listar':
      $strTitulo = 'Sistemas';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  
  if ($_GET['acao'] == 'usuario_sistema_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }
  
  if ($_GET['acao'] == 'usuario_sistema_listar' || $_GET['acao'] == 'usuario_sistema_selecionar'){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('usuario_sistema_cadastrar');
    if ($bolAcaoCadastrar){    	
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_sistema_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }
  

  $objUsuarioDTO = new UsuarioDTO();
  $objUsuarioDTO->retNumIdUsuario();
  $objUsuarioDTO->retNumIdOrgao();
  $objUsuarioDTO->retStrSiglaOrgao();
  $objUsuarioDTO->retStrDescricaoOrgao();
  $objUsuarioDTO->retStrSigla();
  $objUsuarioDTO->retStrNome();
  //$objUsuarioDTO->retStrEndereco();
  //$objUsuarioDTO->retDtaFixaInicioConsulta();
  //$objUsuarioDTO->retStrStaGenero();
  
  if ($_GET['acao'] == 'usuario_sistema_reativar'){
    //Lista somente inativos
    $objUsuarioDTO->setBolExclusaoLogica(false);
    $objUsuarioDTO->setStrSinAtivo('N');
  }
  
  $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SISTEMA);
  
  PaginaSEI::getInstance()->prepararOrdenacao($objUsuarioDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);
  
  PaginaSEI::getInstance()->prepararPaginacao($objUsuarioDTO);

  $objUsuarioRN = new UsuarioRN();
  $arrObjUsuarioDTO = $objUsuarioRN->pesquisar($objUsuarioDTO);

  PaginaSEI::getInstance()->processarPaginacao($objUsuarioDTO);
  
  $objServicoDTO = new ServicoDTO();
  $objServicoDTO->setDistinct(true);
  $objServicoDTO->retNumIdUsuario();
  
  $objServicoRN = new ServicoRN();
  $arrUsuariosServicos = InfraArray::indexarArrInfraDTO($objServicoRN->listar($objServicoDTO),'IdUsuario');
  
  $numRegistros = count($arrObjUsuarioDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='usuario_sistema_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('usuario_sistema_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('usuario_sistema_alterar');
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
      $bolAcaoServicoListar = false;
    }else if ($_GET['acao']=='usuario_sistema_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('usuario_sistema_reativar');
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('usuario_sistema_excluir');
      $bolAcaoDesativar = false;
      $bolAcaoServicoListar = false;
    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('usuario_sistema_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('usuario_sistema_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('usuario_sistema_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('usuario_sistema_desativar');
      $bolAcaoServicoListar = SessaoSEI::getInstance()->verificarPermissao('servico_listar');
    }

    if ($bolAcaoDesativar){
      $bolCheck = true;
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_sistema_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_sistema_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
    

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_sistema_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    if ($_GET['acao']!='usuario_sistema_reativar'){
      $strSumarioTabela = 'Tabela de Sistemas.';
      $strCaptionTabela = 'Sistemas';
    }else{
      $strSumarioTabela = 'Tabela de Sistemas Inativos.';
      $strCaptionTabela = 'Sistemas Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO,'ID','IdUsuario',$arrObjUsuarioDTO,true).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO,'Sigla','Sigla',$arrObjUsuarioDTO,true).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO,'Nome','Nome',$arrObjUsuarioDTO,true).'</th>'."\n";    
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO,'Órgao','SiglaOrgao',$arrObjUsuarioDTO,true).'</th>'."\n";    
    
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjUsuarioDTO[$i]->getNumIdUsuario(),$arrObjUsuarioDTO[$i]->getStrSigla()).'</td>';
      }
      $strResultado .= '<td align="center" width="10%">'.$arrObjUsuarioDTO[$i]->getNumIdUsuario().'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrSigla()).'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td align="center" width="10%"><a alt="'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrDescricaoOrgao()).'" title="'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrDescricaoOrgao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrSiglaOrgao()).'</a></td>';
      $strResultado .= '<td align="center" width="15%">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjUsuarioDTO[$i]->getNumIdUsuario());
      
      
      
      if ($bolAcaoServicoListar){
      	if (isset($arrUsuariosServicos[$arrObjUsuarioDTO[$i]->getNumIdUsuario()])){ 
      	  $strIconeServicos = Icone::SISTEMA_COM_SERVICO;
      	}else{
      		$strIconeServicos = Icone::SISTEMA_SEM_SERVICO;
      	}
      	
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=servico_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_usuario='.$arrObjUsuarioDTO[$i]->getNumIdUsuario()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.$strIconeServicos.'" title="Serviços" alt="Serviços" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_sistema_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_usuario='.$arrObjUsuarioDTO[$i]->getNumIdUsuario()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Sistema" alt="Consultar Sistema" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_sistema_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_usuario='.$arrObjUsuarioDTO[$i]->getNumIdUsuario()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Sistema" alt="Alterar Sistema" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoDesativar){
        $strResultado .= '<a href="#ID-'.$arrObjUsuarioDTO[$i]->getNumIdUsuario().'"  onclick="acaoDesativar(\''.$arrObjUsuarioDTO[$i]->getNumIdUsuario().'\',\''.$arrObjUsuarioDTO[$i]->getStrSigla().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Sistema" alt="Desativar Sistema" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="#ID-'.$arrObjUsuarioDTO[$i]->getNumIdUsuario().'"  onclick="acaoReativar(\''.$arrObjUsuarioDTO[$i]->getNumIdUsuario().'\',\''.$arrObjUsuarioDTO[$i]->getStrSigla().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Sistema" alt="Reativar Sistema" class="infraImg" /></a>&nbsp;';
      }


      if ($bolAcaoExcluir){
        $strResultado .= '<a href="#ID-'.$arrObjUsuarioDTO[$i]->getNumIdUsuario().'"  onclick="acaoExcluir(\''.$arrObjUsuarioDTO[$i]->getNumIdUsuario().'\',\''.$arrObjUsuarioDTO[$i]->getStrSigla().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Sistema" alt="Excluir Sistema" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'usuario_sistema_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }
  
  $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('','Todos',$numIdOrgao);

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
  if ('<?=$_GET['acao']?>'=='usuario_sistema_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do sistema \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUsuarioLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum sistema selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos sistemas selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUsuarioLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do sistema \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUsuarioLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum sistema selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos sistemas selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUsuarioLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do sistema \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmUsuarioLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum sistema selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos sistemas selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmUsuarioLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmUsuarioLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmUsuarioLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
  <?
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>