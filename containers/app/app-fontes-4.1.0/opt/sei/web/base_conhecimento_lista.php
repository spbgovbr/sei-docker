<?php
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/06/2010 - criado por fazenda_db
*
* Versão do Gerador de Código: 1.29.1
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

  PaginaSEI::getInstance()->prepararSelecao('base_conhecimento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  
  switch($_GET['acao']){
    case 'base_conhecimento_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjBaseConhecimentoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
          $objBaseConhecimentoDTO->setNumIdBaseConhecimento($arrStrIds[$i]);
          $arrObjBaseConhecimentoDTO[] = $objBaseConhecimentoDTO;
        }
        $objBaseConhecimentoRN = new BaseConhecimentoRN();
        $objBaseConhecimentoRN->excluir($arrObjBaseConhecimentoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'base_conhecimento_liberar':      

      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjBaseConhecimentoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
          $objBaseConhecimentoDTO->setNumIdBaseConhecimento($arrStrIds[$i]);
          $arrObjBaseConhecimentoDTO[] = $objBaseConhecimentoDTO;
        }
        $objBaseConhecimentoRN = new BaseConhecimentoRN();
        $objBaseConhecimentoRN->liberar($arrObjBaseConhecimentoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'base_conhecimento_cancelar_liberacao':      

      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjBaseConhecimentoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
          $objBaseConhecimentoDTO->setNumIdBaseConhecimento($arrStrIds[$i]);
          $arrObjBaseConhecimentoDTO[] = $objBaseConhecimentoDTO;
        }
        $objBaseConhecimentoRN = new BaseConhecimentoRN();
        $objBaseConhecimentoRN->cancelarLiberacao($arrObjBaseConhecimentoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;
      
    case 'base_conhecimento_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Procedimento','Selecionar Procedimentos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='base_conhecimento_cadastrar'){
        if (isset($_GET['id_base_conhecimento'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_base_conhecimento']);
        }
      }
      break;

    case 'base_conhecimento_listar':
      $strTitulo = 'Base de Conhecimento '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual();
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'base_conhecimento_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_cadastrar');
  if ($bolAcaoCadastrar){
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    /*
  	if (PaginaSEI::getInstance()->isBolNavegadorIE()){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
  	}else{
  		$arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="alert(\'Para criar uma nova base de conhecimento é necessário utilizar o navegador Internet Explorer.\');" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
  	}
  	*/
  }

  $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
  $objBaseConhecimentoDTO->retNumIdBaseConhecimento();
  $objBaseConhecimentoDTO->retNumIdBaseConhecimentoOrigem();
  $objBaseConhecimentoDTO->retNumIdBaseConhecimentoAgrupador();
  $objBaseConhecimentoDTO->retNumIdUnidade();
  $objBaseConhecimentoDTO->retNumIdUsuarioGerador();
  $objBaseConhecimentoDTO->retNumIdUsuarioLiberacao();
  $objBaseConhecimentoDTO->retStrNomeUsuarioGerador();
  $objBaseConhecimentoDTO->retStrNomeUsuarioLiberacao();
  $objBaseConhecimentoDTO->retStrSiglaUsuarioGerador();
  $objBaseConhecimentoDTO->retStrSiglaUsuarioLiberacao();
  //$objBaseConhecimentoDTO->retStrSiglaUnidade();
  $objBaseConhecimentoDTO->retDthGeracao();
  $objBaseConhecimentoDTO->retDthLiberacao();
  $objBaseConhecimentoDTO->retStrDescricao();
  //$objBaseConhecimentoDTO->retStrDescricaoUnidade();
  $objBaseConhecimentoDTO->retDblIdDocumentoEdoc();
  $objBaseConhecimentoDTO->retStrStaEstado();
  $objBaseConhecimentoDTO->retStrStaDocumento();
  
  $objBaseConhecimentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());	
 	
   
  PaginaSEI::getInstance()->prepararOrdenacao($objBaseConhecimentoDTO, 'Descricao', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objBaseConhecimentoDTO);

  $objBaseConhecimentoRN = new BaseConhecimentoRN();
  $objBaseConhecimentoDTO->setStrStaEstado(BaseConhecimentoRN::$TE_VERSAO_ANTERIOR,InfraDTO::$OPER_DIFERENTE);
  $arrObjBaseConhecimentoDTO = $objBaseConhecimentoRN->listar($objBaseConhecimentoDTO);
  
  //PaginaSEI::getInstance()->processarPaginacao($objBaseConhecimentoDTO);
  $numRegistros = count($arrObjBaseConhecimentoDTO);

  if ($numRegistros > 0){

    $bolAcaoVisualizar = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_visualizar');
    $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_consultar');
    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_alterar');
    $bolNovaVersao = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_nova_versao');
    $bolVisualizarVersoes = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_versoes');
		$bolAcaoLiberar = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_liberar');      
		$bolAcaoCancelarLiberacao = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_cancelar_liberacao');
    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_excluir');


    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    
    $strResultado = '';

    $strSumarioTabela = 'Tabela de Procedimentos.';
    $strCaptionTabela = 'Procedimentos';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objBaseConhecimentoDTO,'Descrição','Descricao',$arrObjBaseConhecimentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objBaseConhecimentoDTO,'Usuário Gerador','NomeUsuarioGerador',$arrObjBaseConhecimentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objBaseConhecimentoDTO,'Data Geração','Geracao',$arrObjBaseConhecimentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objBaseConhecimentoDTO,'Usuário Liberação','NomeUsuarioLiberacao',$arrObjBaseConhecimentoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objBaseConhecimentoDTO,'Data Liberação','Liberacao',$arrObjBaseConhecimentoDTO).'</th>'."\n";
    
    
    $strResultado .= '<th class="infraTh" width="22%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    
    
    $bolFlagExcluir = false;
    $bolFlagLiberar = false;
    $bolFlagCancelarLiberacao = false;
    
    for($i = 0;$i < $numRegistros; $i++){
    

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento(),$arrObjBaseConhecimentoDTO[$i]->getStrDescricao()).'</td>';

      $strTdLinkDescricao = '';
      if ($bolAcaoVisualizar){
        $strTdLinkDescricao = '<td align="left" valign="top" ><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_visualizar&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento()).'" target="_blank" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrDescricao()).'</a></td>';
      }
      
      if ($strTdLinkDescricao==''){
        $strResultado .= '<td align="left" valign="top">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrDescricao()).'</td>';
      }else{
        $strResultado .= $strTdLinkDescricao;
      }
      
      
      $strResultado .= '<td align="center" valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioGerador()).'" title="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioGerador()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrSiglaUsuarioGerador()).'</a>';
      $strResultado .= '</td>';
      
			
      $strResultado .= '<td align="center" valign="top">'.$arrObjBaseConhecimentoDTO[$i]->getDthGeracao().'</td>';
      
      $strResultado .= '<td align="center" valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioLiberacao()).'" title="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioLiberacao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrSiglaUsuarioLiberacao()).'</a>';
      $strResultado .= '</td>';
      
			
      $strResultado .= '<td align="center" valign="top">'.$arrObjBaseConhecimentoDTO[$i]->getDthLiberacao().'</td>';
      $strResultado .= '<td align="center" valign="top">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento());

      if ($bolAcaoVisualizar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_visualizar&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::DOCUMENTO_BASE_CONHECIMENTO.'" class="infraImg" alt="Visualizar Conteúdo do Procedimento" title="Visualizar Conteúdo do Procedimento" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar && $arrObjBaseConhecimentoDTO[$i]->getStrStaEstado()==BaseConhecimentoRN::$TE_LIBERADO){
      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_consultar&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento().'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Cadastro do Procedimento" alt="Consultar Cadastro do Procedimento" class="infraImg" /></a>&nbsp;';
      }
            	
      if ($bolAcaoAlterar && $arrObjBaseConhecimentoDTO[$i]->getStrStaEstado()==BaseConhecimentoRN::$TE_RASCUNHO){

        if ($arrObjBaseConhecimentoDTO[$i]->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){
          $strResultado .= '<a onclick="editarConteudo('.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento().',\''.$arrObjBaseConhecimentoDTO[$i]->getStrStaDocumento().'\',this, \''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=editor_montar&acao_origem=arvore_visualizar&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento()).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::DOCUMENTO_INTERNO.'" title="Alterar Conteúdo do Procedimento" alt="Alterar Conteúdo do Procedimento" class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_alterar&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento().'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Cadastro do Procedimento" alt="Alterar Cadastro do Procedimento" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolNovaVersao && $arrObjBaseConhecimentoDTO[$i]->getStrStaEstado()==BaseConhecimentoRN::$TE_LIBERADO && $arrObjBaseConhecimentoDTO[$i]->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO){
      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_nova_versao&id_base_conhecimento_origem='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento().'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeMais().'" title="Nova Versão do Procedimento" alt="Nova Versão do Procedimento" class="infraImg" /></a>&nbsp;';
      }
      
      
      if ($bolAcaoExcluir || $bolAcaoLiberar || $bolAcaoCancelarLiberacao){
        $strId = $arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjBaseConhecimentoDTO[$i]->getStrDescricao());
      }

      if ($bolAcaoLiberar && $arrObjBaseConhecimentoDTO[$i]->getStrStaEstado()==BaseConhecimentoRN::$TE_RASCUNHO){
      	$bolFlagLiberar = true;
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoLiberar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeMarcar().'" title="Liberar Versão" alt="Liberar Versão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoCancelarLiberacao && $arrObjBaseConhecimentoDTO[$i]->getStrStaEstado()==BaseConhecimentoRN::$TE_LIBERADO){
      	$bolFlagCancelarLiberacao = true;
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoCancelarLiberacao(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeMenos().'" title="Cancelar Liberação de Versão" alt="Cancelar Liberação de Versão" class="infraImg" /></a>&nbsp;';
      }

      if ($bolVisualizarVersoes && $arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimentoOrigem() != null){
      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_versoes&id_base_conhecimento_agrupador='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimentoAgrupador().'&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento().'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::BASE_CONHECIMENTO_VERSOES.'" title="Versões do Procedimento" alt="Versões do Procedimento" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoExcluir && $arrObjBaseConhecimentoDTO[$i]->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $arrObjBaseConhecimentoDTO[$i]->getStrStaEstado()==BaseConhecimentoRN::$TE_RASCUNHO){
      	$bolFlagExcluir = true;
        $strResultado .= '<a href="#ID-'.$strId.'"  onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Procedimento" alt="Excluir Procedimento" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
    
    if ($bolFlagLiberar){
      $arrComandos[] = '<button type="button" accesskey="L" id="btnLiberar" value="Liberar" onclick="acaoLiberacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">L</span>iberar</button>';
      $strLinkLiberar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_liberar&acao_origem='.$_GET['acao']);
    }

    if ($bolFlagCancelarLiberacao){
      $arrComandos[] = '<button type="button" accesskey="b" id="btnCancelarLiberacao" value="Cancelar Liberação" onclick="acaoCancelarLiberacaoMultipla();" class="infraButton">Cancelar Li<span class="infraTeclaAtalho">b</span>eração</button>';
      $strLinkCancelarLiberacao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_cancelar_liberacao&acao_origem='.$_GET['acao']);
    }
    
    if ($bolFlagExcluir){
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_excluir&acao_origem='.$_GET['acao']);
    }
    
  }
  
  if ($_GET['acao'] == 'base_conhecimento_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }
  
  
  //vindo do cadastro de documento e tudo OK então gera link para abrir e-Doc
  if (($_GET['acao_origem']=='base_conhecimento_cadastrar' || $_GET['acao_origem']=='base_conhecimento_nova_versao') && $_GET['resultado']=='1'){
    $strLinkIniciarEditor = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=editor_montar&id_base_conhecimento='.$_GET['id_base_conhecimento']);
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
//<script>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='base_conhecimento_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }

  if ('<?=$strLinkIniciarEditor?>'!=''){
    infraAbrirJanela('<?=$strLinkIniciarEditor?>','janelaEditorBC_<?=SessaoSEI::getInstance()->getNumIdUsuario().'_'.$_GET['id_base_conhecimento']?>',infraClientWidth(),infraClientHeight(),'location=0,status=1,resizable=1,scrollbars=1',false);
  }

  infraEfeitoTabelas();
}

<? if ($bolAcaoLiberar){ ?>
function acaoLiberar(id,desc){
  if (confirm("Confirma liberação do Procedimento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBaseConhecimentoLista').action='<?=$strLinkLiberar?>';
    document.getElementById('frmBaseConhecimentoLista').submit();
  }
}

function acaoLiberacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Procedimento selecionado.');
    return;
  }
  if (confirm("Confirma liberação dos Procedimentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBaseConhecimentoLista').action='<?=$strLinkLiberar?>';
    document.getElementById('frmBaseConhecimentoLista').submit();
  }
}

<? } ?>

<? if ($bolAcaoCancelarLiberacao){ ?>
function acaoCancelarLiberacao(id,desc){
  if (confirm("Confirma cancelamento da liberação do Procedimento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBaseConhecimentoLista').action='<?=$strLinkCancelarLiberacao?>';
    document.getElementById('frmBaseConhecimentoLista').submit();
  }
}

function acaoCancelarLiberacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Procedimento selecionado.');
    return;
  }
  if (confirm("Confirma cancelamento da liberação dos Procedimentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBaseConhecimentoLista').action='<?=$strLinkCancelarLiberacao?>';
    document.getElementById('frmBaseConhecimentoLista').submit();
  }
}

<? } ?>


<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Procedimento \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBaseConhecimentoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmBaseConhecimentoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Procedimento selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Procedimentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBaseConhecimentoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmBaseConhecimentoLista').submit();
  }
}

<? } ?>

function editarConteudo(id, editor, ancora, link){
  document.getElementById('hdnInfraItemId').value=id;
  infraFormatarTrAcessada(ancora.parentNode.parentNode);
  infraAbrirJanela(link,'janelaEditorBC_<?=SessaoSEI::getInstance()->getNumIdUsuario()?>_' + id,infraClientWidth(),infraClientHeight(),'location=0,status=1,resizable=1,scrollbars=1',false);
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar(); "');
?>

<form id="frmBaseConhecimentoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>