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

    case 'base_conhecimento_listar_associadas':
      $strTitulo = 'Bases de Conhecimento Associadas';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();


  $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
  $objTipoProcedimentoDTO->setNumIdTipoProcedimento($_GET['id_tipo_procedimento']);
   
  //PaginaSEI::getInstance()->prepararPaginacao($objBaseConhecimentoDTO);

  $objBaseConhecimentoRN = new BaseConhecimentoRN();
  $arrObjBaseConhecimentoDTO = $objBaseConhecimentoRN->listarAssociadas($objTipoProcedimentoDTO);
  
  //PaginaSEI::getInstance()->processarPaginacao($objBaseConhecimentoDTO);
  
  $numRegistros = count($arrObjBaseConhecimentoDTO);

  if ($numRegistros > 0){

    $bolAcaoVisualizar = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_visualizar');
    $bolVisualizarVersoes = SessaoSEI::getInstance()->verificarPermissao('base_conhecimento_versoes');
    
    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" style="width:10em;"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Bases de Conhecimento Associadas.';
    $strCaptionTabela = 'Bases de Conhecimento Associadas';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    
    /*
    $strResultado .= '<th class="infraTh" width="10%">Usuário Gerador</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Data Geração</th>'."\n";
    */
    
    $strResultado .= '<th class="infraTh" width="20%">Usuário Liberação</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Data Liberação</th>'."\n";
    
    
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    
    for($i = 0;$i < $numRegistros; $i++){
    

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento(),$arrObjBaseConhecimentoDTO[$i]->getStrDescricao()).'</td>';

      $strResultado .= '<td align="center" valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrSiglaUnidade()).'</a>';
      $strResultado .= '</td>';
      
      if ($bolAcaoVisualizar && ($arrObjBaseConhecimentoDTO[$i]->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO || ($arrObjBaseConhecimentoDTO[$i]->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC && $arrObjBaseConhecimentoDTO[$i]->getDblIdDocumentoEdoc()!=null))){
      	$strResultado .= '<td align="left" valign="top" ><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_visualizar&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento()).'" target="_blank" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrDescricao()).'</a></td>';
      }else{
        $strResultado .= '<td align="left" valign="top">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrDescricao()).'</td>';
      }
      
      /*
      $strResultado .= '<td align="center" valign="top">';
      $strResultado .= '<a alt="'.$arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioGerador().'" title="'.$arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioGerador().'" class="ancoraSigla">'.$arrObjBaseConhecimentoDTO[$i]->getStrSiglaUsuarioGerador().'</a>';
      $strResultado .= '</td>';
      
      $strResultado .= '<td align="center" valign="top">'.$arrObjBaseConhecimentoDTO[$i]->getDthGeracao().'</td>';
      */
      
      $strResultado .= '<td align="center" valign="top">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioLiberacao()).'" title="'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrNomeUsuarioLiberacao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjBaseConhecimentoDTO[$i]->getStrSiglaUsuarioLiberacao()).'</a>';
      $strResultado .= '</td>';
      
      $strResultado .= '<td align="center" valign="top">'.$arrObjBaseConhecimentoDTO[$i]->getDthLiberacao().'</td>';
      $strResultado .= '<td align="center" valign="top">';

      if ($bolAcaoVisualizar && ($arrObjBaseConhecimentoDTO[$i]->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_INTERNO || ($arrObjBaseConhecimentoDTO[$i]->getStrStaDocumento()==DocumentoRN::$TD_EDITOR_EDOC && $arrObjBaseConhecimentoDTO[$i]->getDblIdDocumentoEdoc()!=null))){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_visualizar&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::DOCUMENTO_BASE_CONHECIMENTO.'" class="infraImg" alt="Visualizar Conteúdo do Procedimento" title="Visualizar Conteúdo do Procedimento" /></a>&nbsp;';
      }
      
      if ($bolVisualizarVersoes && $arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimentoOrigem() != null){
      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_versoes&id_base_conhecimento_agrupador='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimentoAgrupador().'&id_base_conhecimento='.$arrObjBaseConhecimentoDTO[$i]->getNumIdBaseConhecimento().'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::BASE_CONHECIMENTO_VERSOES.'" title="Versões do Procedimento" alt="Versões do Procedimento" class="infraImg" /></a>&nbsp;';
      }
            
      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  
  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\'" class="infraButton" style="width:10em;"><span class="infraTeclaAtalho">F</span>echar</button>';
  
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
  
  //document.getElementById('btnFechar').focus();
  
  infraEfeitoTabelas();

}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar(); "');
?>

<form id="frmBaseConhecimentoAssociacoes" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
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