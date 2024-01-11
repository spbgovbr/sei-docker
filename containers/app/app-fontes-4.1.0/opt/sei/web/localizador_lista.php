<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/05/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////
 
  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('localizador_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selTipoLocalizador','selEstado'));

  switch($_GET['acao']){
    case 'localizador_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjLocalizadorDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objLocalizadorDTO = new LocalizadorDTO();
          $objLocalizadorDTO->setNumIdLocalizador($arrStrIds[$i]);
          $arrObjLocalizadorDTO[] = $objLocalizadorDTO;
        }
        $objLocalizadorRN = new LocalizadorRN();
        $objLocalizadorRN->excluirRN0620($arrObjLocalizadorDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'localizador_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Localizador','Selecionar Localizadores');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='localizador_cadastrar'){
        if (isset($_GET['id_localizador'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_localizador']);
        }
      }
      break;

    //desativar padrão
    case 'localizador_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjLocalizadorDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objLocalizadorDTO = new LocalizadorDTO();
          $objLocalizadorDTO->setNumIdLocalizador($arrStrIds[$i]);
          $arrObjLocalizadorDTO[] = $objLocalizadorDTO;
        }
        $objLocalizadorRN = new LocalizadorRN();
        $objLocalizadorRN->desativar($arrObjLocalizadorDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;
    //reativar padrão
    case 'localizador_reativar':
      $strTitulo = 'Reativar Comissões Permanentes de Avaliação de Documentos';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjLocalizadorDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objLocalizadorDTO = new LocalizadorDTO();
            $objLocalizadorDTO->setNumIdLocalizador($arrStrIds[$i]);
            $arrObjLocalizadorDTO[] = $objLocalizadorDTO;
          }
          $objLocalizadorRN = new LocalizadorRN();
          $objLocalizadorRN->reativar($arrObjLocalizadorDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      }
      break;

    case 'localizador_listar':
      $strTitulo = 'Localizadores';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'localizador_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('localizador_cadastrar');
  if ($bolAcaoCadastrar){
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
  }

  $objLocalizadorDTO = new LocalizadorDTO(true);
  $objLocalizadorDTO->retNumIdLocalizador();
  $objLocalizadorDTO->retStrIdentificacao();
  $objLocalizadorDTO->retStrDescricaoEstado();
  $objLocalizadorDTO->retNumQtdProtocolos();

  if ($_GET['acao'] == 'localizador_reativar'){
    //Lista somente inativos
    $objLocalizadorDTO->setBolExclusaoLogica(false);
    $objLocalizadorDTO->setStrSinAtivo('N');
  }

  $objLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  
  
  $numIdTipoLocalizador = PaginaSEI::getInstance()->recuperarCampo('selTipoLocalizador');
  if ($numIdTipoLocalizador!==''){
    $objLocalizadorDTO->setNumIdTipoLocalizador($numIdTipoLocalizador);
  }
  
  $strStaEstado = PaginaSEI::getInstance()->recuperarCampo('selEstado');
  if ($strStaEstado!==''){
    $objLocalizadorDTO->setStrStaEstado($strStaEstado);
  }
  

  $objLocalizadorRN = new LocalizadorRN();
  
  //sql server precisa de um campo para ordenacao no banco
  //$objLocalizadorDTO->setOrdNumIdLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objLocalizadorDTO->setOrdStrSiglaTipoLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objLocalizadorDTO->setOrdNumSeqLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
  
  
  PaginaSEI::getInstance()->prepararPaginacao($objLocalizadorDTO);
  
  $arrObjLocalizadorDTO = $objLocalizadorRN->listarRN0622($objLocalizadorDTO);
  
  PaginaSEI::getInstance()->processarPaginacao($objLocalizadorDTO);

  $numRegistros = count($arrObjLocalizadorDTO);

  if ($numRegistros > 0){

    //InfraArray::ordenarArrInfraDTO($arrObjLocalizadorDTO,'Identificacao',InfraArray::$TIPO_ORDENACAO_ASC);
    
    $bolCheck = false;

    if ($_GET['acao']=='localizador_selecionar'){
      $bolAcaoConsultar = false;
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('localizador_alterar');
      $bolAcaoListarProtocolosLocalizador = false;
      $bolAcaoMigrar = false;
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoEtiquetas = false;
      $bolAcaoDesativar = false;
      $bolAcaoReativar = false;
      $bolCheck = true;
     }else if ($_GET['acao']=='localizador_reativar'){
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('localizador_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoListarProtocolosLocalizador = false;
      $bolAcaoMigrar = false;
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('localizador_excluir');
      $bolAcaoEtiquetas = false;
      $bolAcaoDesativar = false;
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('localizador_reativar');
    }  else{
      $bolAcaoListarProtocolosLocalizador = SessaoSEI::getInstance()->verificarPermissao('localizador_protocolos_listar');
      $bolAcaoMigrar = SessaoSEI::getInstance()->verificarPermissao('arquivamento_migrar_localizador');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('localizador_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('localizador_alterar');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('localizador_excluir');
      $bolAcaoEtiquetas = SessaoSEI::getInstance()->verificarPermissao('localizador_imprimir_etiqueta');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('localizador_desativar');
      $bolAcaoReativar = false;
    }

    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
 
    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_excluir&acao_origem='.$_GET['acao']);
    }
    
    if ($bolAcaoEtiquetas){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="Q" id="btnEtiquetas" value="Etiquetas" onclick="acaoEtiquetasMultipla();" class="infraButton">Eti<span class="infraTeclaAtalho">q</span>uetas</button>';
      $strLinkEtiquetas = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_imprimir_etiqueta&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    if ($_GET['acao']!='localzador_reativar') {
      $strSumarioTabela = 'Tabela de Localizadores.';
      $strCaptionTabela = 'Localizadores';
    }else{
      $strSumarioTabela = 'Tabela de Localizadores Inativos.';
      $strCaptionTabela = 'Localizadores Inativos';
    }

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //80
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="25%">Identificação</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">Estado</th>'."\n";
    $strResultado .= '<th class="infraTh" width="25%">Documentos</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjLocalizadorDTO[$i]->getNumIdLocalizador(),$arrObjLocalizadorDTO[$i]->getNumSeqLocalizador()).'</td>';
      }

      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjLocalizadorDTO[$i]->getStrIdentificacao()).'</td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjLocalizadorDTO[$i]->getStrDescricaoEstado()).'</td>';
      $strResultado .= '<td align="center">'.($arrObjLocalizadorDTO[$i]->getNumQtdProtocolos()>0?$arrObjLocalizadorDTO[$i]->getNumQtdProtocolos():'&nbsp;').'</td>';
      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjLocalizadorDTO[$i]->getNumIdLocalizador());

      if ($arrObjLocalizadorDTO[$i]->getNumQtdProtocolos()>0 && $bolAcaoListarProtocolosLocalizador){
				$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_protocolos_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_localizador='.$arrObjLocalizadorDTO[$i]->getNumIdLocalizador()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::ARQUIVO_PESQUISAR.'" title="Listar Protocolos Arquivados" alt="Listar Protocolos Arquivados" class="infraImg" /></a>&nbsp;';
      }
      
      if ($arrObjLocalizadorDTO[$i]->getNumQtdProtocolos()>0 && $bolAcaoMigrar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arquivamento_migrar_localizador&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_localizador='.$arrObjLocalizadorDTO[$i]->getNumIdLocalizador()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::ARQUIVO_MIGRAR_LOCALIZADOR.'" title="Migração de Protocolos Arquivados" alt="Migração de Protocolos Arquivados" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_localizador='.$arrObjLocalizadorDTO[$i]->getNumIdLocalizador()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Localizador" alt="Consultar Localizador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_localizador='.$arrObjLocalizadorDTO[$i]->getNumIdLocalizador()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Localizador" alt="Alterar Localizador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strId = $arrObjLocalizadorDTO[$i]->getNumIdLocalizador();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjLocalizadorDTO[$i]->getStrSiglaTipoLocalizador().'-'.$arrObjLocalizadorDTO[$i]->getNumSeqLocalizador());
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjLocalizadorDTO[$i]->getNumIdLocalizador();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjLocalizadorDTO[$i]->getStrIdentificacao());
      }

      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Localizador" alt="Desativar Localizador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Localizador" alt="Reativar Localizador" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir && $arrObjLocalizadorDTO[$i]->getNumQtdProtocolos()==0){
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Localizador" alt="Excluir Localizador" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'localizador_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelTipoLocalizador = TipoLocalizadorINT::montarSelectNomeRI0676('','Todos',$numIdTipoLocalizador);
  $strItensSelEstado = LocalizadorINT::montarSelectStaEstadoRI0681('','Todos',$strStaEstado);
  
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

#lblTipoLocalizador {position:absolute;left:0%;top:13%;;width:50%;}
#selTipoLocalizador {position:absolute;left:0%;top:30%;width:50%;}

#lblEstado {position:absolute;left:0%;top:60%;width:30%;}
#selEstado {position:absolute;left:0%;top:77%;width:30%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='localizador_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  infraEfeitoTabelas();
}


<? if ($bolAcaoDesativar){ ?>
  function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Comissão Permanente de Avaliação de Documentos \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmLocalizadorLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmLocalizadorLista').submit();
  }
  }

  function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhuma Comissão Permanente de Avaliação de Documentos selecionada.');
  return;
  }
  if (confirm("Confirma desativação das Comissões Permanentes de Avaliação de Documentos selecionadas?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmLocalizadorLista').action='<?=$strLinkDesativar?>';
  document.getElementById('frmLocalizadorLista').submit();
  }
  }
<? } ?>

<? if ($bolAcaoReativar){ ?>
  function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Lozalizador \""+desc+"\"?")){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmLocalizadorLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmLocalizadorLista').submit();
  }
  }

  function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Localizador selecionado.');
  return;
  }
  if (confirm("Confirma reativação dos Localizadors selecionadas?")){
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmLocalizadorLista').action='<?=$strLinkReativar?>';
  document.getElementById('frmLocalizadorLista').submit();
  }
  }
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Localizador \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmLocalizadorLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmLocalizadorLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Localizador selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Localizadores selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmLocalizadorLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmLocalizadorLista').submit();
  }
}
<? } ?>


<? if ($bolAcaoEtiquetas){ ?>     
     function acaoEtiquetasMultipla(){
       if (document.getElementById('hdnInfraItensSelecionados').value==''){
         alert('Nenhum item selecionado.');
         return;
       }
       document.getElementById('hdnInfraItemId').value='';
			 document.getElementById('frmLocalizadorLista').action='<?=$strLinkEtiquetas?>';
			 document.getElementById('frmLocalizadorLista').submit();
     }
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmLocalizadorLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('12em');
  ?>
	  <label id="lblTipoLocalizador" for="selTipoLocalizador" accesskey="" class="infraLabelObrigatorio">Tipo do Localizador:</label>
	  <select id="selTipoLocalizador" name="selTipoLocalizador" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	  <?=$strItensSelTipoLocalizador?>	  	
	  </select>
	   
	  <label id="lblEstado" for="selEstado" accesskey="" class="infraLabelObrigatorio">Estado:</label>
	  	<select id="selEstado" name="selEstado" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	  	<?=$strItensSelEstado?>
	  </select>  
	      
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>