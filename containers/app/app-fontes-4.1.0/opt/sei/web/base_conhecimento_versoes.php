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
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  
  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }  
  
  if(isset($_GET['id_tipo_procedimento'])){
  	$strParametros .= '&id_tipo_procedimento='.$_GET['id_tipo_procedimento'];
  }
  
  switch($_GET['acao']){
    case 'base_conhecimento_versoes':
      $strTitulo = 'Versões do Procedimento';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
  $objBaseConhecimentoDTO->retNumIdBaseConhecimento();
  $objBaseConhecimentoDTO->retNumIdBaseConhecimentoOrigem();
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

  $objBaseConhecimentoDTO->setNumIdBaseConhecimentoAgrupador($_GET['id_base_conhecimento_agrupador']);
  $objBaseConhecimentoDTO->setOrdDthGeracao(InfraDTO::$TIPO_ORDENACAO_DESC);
    
  //PaginaSEI::getInstance()->prepararPaginacao($objBaseConhecimentoDTO);

  $objBaseConhecimentoRN = new BaseConhecimentoRN();
  

  $arrObjBaseConhecimentoDTO = $objBaseConhecimentoRN->listar($objBaseConhecimentoDTO);
  
  //PaginaSEI::getInstance()->processarPaginacao($objBaseConhecimentoDTO);
  $numRegistros = count($arrObjBaseConhecimentoDTO);

  if ($numRegistros > 0){

  	$bolAcaoVisualizar = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_visualizar');
  	$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_consultar');
  	
    $strResultado = '';

    $strSumarioTabela = 'Tabela de Versões do Procedimento.';
    $strCaptionTabela = 'Versões do Procedimento';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '<th class="infraTh">Usuário Gerador</th>'."\n";
    $strResultado .= '<th class="infraTh">Data Geração</th>'."\n";
    $strResultado .= '<th class="infraTh">Usuário Liberação</th>'."\n";
    $strResultado .= '<th class="infraTh">Data Liberação</th>'."\n";
    
    $strResultado .= '<th class="infraTh" width="5%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){
    
      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento(),$arrObjBaseConhecimentoDTO[$i]->getStrDescricao()).'</td>';
      
      
      if ($bolAcaoVisualizar){
      	$strResultado .= '<td align="left" valign="top" ><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_visualizar&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento()).'" target="_blank" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrDescricao()).'</a></td>';
      }else{
        $strResultado .= '<td align="left">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrDescricao()).'</td>';
      }
      
      $strResultado .= '<td align="center" valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioGerador()).'" title="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioGerador()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrSiglaUsuarioGerador()).'</a>';
      $strResultado .= '</td>';
			
			
      $strResultado .= '<td align="center">'.$arrObjBaseConhecimentoDTO[$i]->getDthGeracao().'</td>';
      
      $strResultado .= '<td align="center" valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioLiberacao()).'" title="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioLiberacao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrSiglaUsuarioLiberacao()).'</a>';
      $strResultado .= '</td>';
      			        
      $strResultado .= '<td align="center">'.$arrObjBaseConhecimentoDTO[$i]->getDthLiberacao().'</td>';
      $strResultado .= '<td align="center">';

      if ($bolAcaoVisualizar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_visualizar&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::DOCUMENTO_BASE_CONHECIMENTO.'" class="infraImg" alt="Visualizar Conteúdo da Versão" title="Visualizar Conteúdo da Versão" /></a>&nbsp;';
      }

      if ($bolAcaoConsultar && PaginaSEI::getInstance()->getAcaoRetorno()=='base_conhecimento_listar'){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_consultar&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento().'&id_base_conhecimento_agrupador='.$_GET['id_base_conhecimento_agrupador'].'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Cadastro do Procedimento" alt="Consultar Cadastro do Procedimento" class="infraImg" /></a>&nbsp;';
      }      
      
      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
	$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_base_conhecimento'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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
  infraEfeitoTabelas();
  
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar(); "');
?>

<form id="frmBaseConhecimentoVersoes" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
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