<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/11/2010 - criado por mga
*
* Versão do Gerador de Código: 1.30.0
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
  if (isset($_GET['id_controle_unidade'])){
  	$strParametros .= '&id_controle_unidade='.$_GET['id_controle_unidade'];
  }

  if (isset($_GET['id_situacao'])){
    $strParametros .= '&id_situacao='.$_GET['id_situacao'];
  }

  if (isset($_GET['id_tipo_procedimento'])){
  	$strParametros .= '&id_tipo_procedimento='.$_GET['id_tipo_procedimento'];
  }

  $objControleUnidadeDTO = new ControleUnidadeDTO();
  
  switch($_GET['acao']){

    case 'controle_unidade_detalhar':

      $strTitulo = 'Controle da Unidade';

    	PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);

    	$objControleUnidadeDTO->setDblIdControleUnidade($_GET['id_controle_unidade']);

      if (isset($_GET['id_situacao'])){
        $objControleUnidadeDTO->setNumIdSituacao($_GET['id_situacao']);
      }

      if (isset($_GET['id_tipo_procedimento'])){
        $objControleUnidadeDTO->setNumIdTipoProcedimentoProcedimento($_GET['id_tipo_procedimento']);
      }

    	break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $objControleUnidadeDTO->retDblIdControleUnidade();
  $objControleUnidadeDTO->retDblIdProcedimento();
  $objControleUnidadeDTO->retStrProtocoloFormatadoProcedimento();
  $objControleUnidadeDTO->retStrNomeTipoProcedimento();
  $objControleUnidadeDTO->retStrNomeSituacao();
  $objControleUnidadeDTO->retStrSiglaUsuario();
  $objControleUnidadeDTO->retStrNomeUsuario();
  $objControleUnidadeDTO->retDthExecucao();
  $objControleUnidadeDTO->retStrSinAtivoSituacao();

  PaginaSEI::getInstance()->prepararOrdenacao($objControleUnidadeDTO, 'IdProcedimento', InfraDTO::$TIPO_ORDENACAO_DESC);

  PaginaSEI::getInstance()->prepararPaginacao($objControleUnidadeDTO);

  $objControleUnidadeRN = new ControleUnidadeRN();
  $arrObjControleUnidadeDTO = $objControleUnidadeRN->listar($objControleUnidadeDTO);
  
  PaginaSEI::getInstance()->processarPaginacao($objControleUnidadeDTO);
  $numRegistros = count($arrObjControleUnidadeDTO);

  if ($numRegistros > 0){

    $bolAcaoProcedimentoTrabalhar = SessaoSEI::getInstance()->verificarPermissao('procedimento_trabalhar');
    $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');

    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Processos.';
    $strCaptionTabela = 'Processos';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";

    //$strResultado .= '<th class="infraTh" width="10%">Órgão</th>'."\n";
    //$strResultado .= '<th class="infraTh" width="10%">Unidade</th>'."\n";
    //$strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objRelProcedSituacaoUnidadeDTO,'Data/Hora','ExecucaoAndamentoSituacao',$arrObjRelProcedSituacaoUnidadeDTO).'</th>'."\n";

    $strResultado .= '<th class="infraTh" width="25%">'.PaginaSEI::getInstance()->getThOrdenacao($objControleUnidadeDTO,'Processo','IdProcedimento',$arrObjControleUnidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objControleUnidadeDTO,'Tipo','NomeTipoProcedimento',$arrObjControleUnidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="18%">Ponto de Controle</th>'."\n";
    $strResultado .= '<th class="infraTh" width="18%">'.PaginaSEI::getInstance()->getThOrdenacao($objControleUnidadeDTO,'Usuário','SiglaUsuario',$arrObjControleUnidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="18%">'.PaginaSEI::getInstance()->getThOrdenacao($objControleUnidadeDTO,'Data/Hora','Execucao',$arrObjControleUnidadeDTO).'</th>'."\n";
    
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjControleUnidadeDTO[$i]->getDblIdControleUnidade(),$arrObjControleUnidadeDTO[$i]->getDblIdControleUnidade()).'</td>';
      $strResultado .= '<td valign="top" align="center">';
      if ($bolAcaoProcedimentoTrabalhar){              
      	$strLinkProcedimento = 'controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjControleUnidadeDTO[$i]->getDblIdProcedimento();
      	$strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink($strLinkProcedimento).'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.$arrObjControleUnidadeDTO[$i]->getStrNomeTipoProcedimento().'">'.$arrObjControleUnidadeDTO[$i]->getStrProtocoloFormatadoProcedimento().'</a>';
      }else{
      	$strResultado .= $arrObjControleUnidadeDTO[$i]->getStrProtocoloFormatadoProcedimento();
      }  
      $strResultado .= '</td>';
      
      $strResultado .= '<td valign="top" align="center">'.PaginaSEI::tratarHTML($arrObjControleUnidadeDTO[$i]->getStrNomeTipoProcedimento()).'</td>';
      $strResultado .= '<td valign="top" align="center">'.PaginaSEI::tratarHTML($arrObjControleUnidadeDTO[$i]->getStrNomeSituacao());
      if ($arrObjControleUnidadeDTO[$i]->getStrSinAtivoSituacao()=='N') {
        $strResultado .=' - DESATIVADO';
      }
      $strResultado .= '</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjControleUnidadeDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjControleUnidadeDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjControleUnidadeDTO[$i]->getStrSiglaUsuario()).'</a></td>';
      $strResultado .= '<td valign="top" align="center">'.$arrObjControleUnidadeDTO[$i]->getDthExecucao().'</td>';
      $strResultado .= '</tr>'."\n";
            
    }
    $strResultado .= '</table>';
  }
  
  
  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton" style="width:8em"><span class="infraTeclaAtalho">F</span>echar</button>';

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
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmControleUnidadeDetalhe" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
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