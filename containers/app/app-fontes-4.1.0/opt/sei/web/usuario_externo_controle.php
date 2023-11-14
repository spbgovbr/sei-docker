<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/04/2012 - criado por mga
*
*
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

  SessaoSEIExterna::getInstance()->validarLink();

  switch($_GET['acao']){

      case 'usuario_externo_controle_acessos':
        $strTitulo = 'Controle de Acessos Externos';
        break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objAcessoExternoDTO = new AcessoExternoDTO();
  $objAcessoExternoDTO->setNumIdUsuarioExterno(SessaoSEIExterna::getInstance()->getNumIdUsuarioExterno());

  $strSinExpirados =  $_POST['hdnSinExpirados'];
  if($strSinExpirados=='S'){
    $objAcessoExternoDTO->setStrSinExpirados("S");
  }else{
    $objAcessoExternoDTO->setStrSinExpirados("N");
  }

  PaginaSEIExterna::getInstance()->prepararPaginacao($objAcessoExternoDTO);

  $objAcessoExternoRN = new AcessoExternoRN();
  $arrObjAcessoExternoDTO = $objAcessoExternoRN->listarDocumentosControleAcesso($objAcessoExternoDTO);

  PaginaSEIExterna::getInstance()->processarPaginacao($objAcessoExternoDTO);

  $strResultado = '';
  $numRegistros = count($arrObjAcessoExternoDTO);

  $arrComandos = array();

  foreach ($SEI_MODULOS as $seiModulo) {
    if (($arrIntegracao = $seiModulo->executar('montarBotaoControleAcessoExterno')) != null) {
      foreach ($arrIntegracao as $strIntegracao) {
        $arrComandos[] = $strIntegracao;
      }
    }
  }

  if ($numRegistros){

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $bolHabilitarInclusaoDocumento = ($objInfraParametro->getValor('SEI_HABILITAR_ACESSO_EXTERNO_INCLUSAO_DOCUMENTO') == '1');

    $arrObjAcessoExternoAPI = array();
    $arrIntegracaoAcoesProcedimentos = array();

    foreach($arrObjAcessoExternoDTO as $objAcessoExternoDTO) {

      $objAcessoExternoAPI = new AcessoExternoAPI();
      $objAcessoExternoAPI->setIdAcessoExterno($objAcessoExternoDTO->getNumIdAcessoExterno());
      $objAcessoExternoAPI->setDataValidade($objAcessoExternoDTO->getDtaValidade());
      $objAcessoExternoAPI->setSinAcessoProcesso($objAcessoExternoDTO->getStrSinProcesso());

      $objProcedimentoDTO = $objAcessoExternoDTO->getObjProcedimentoDTO();

      $objProcedimentoAPI = new ProcedimentoAPI();
      $objProcedimentoAPI->setIdProcedimento($objProcedimentoDTO->getDblIdProcedimento());
      $objProcedimentoAPI->setNumeroProtocolo($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado());
      $objProcedimentoAPI->setIdTipoProcedimento($objProcedimentoDTO->getNumIdTipoProcedimento());
      $objProcedimentoAPI->setNomeTipoProcedimento($objProcedimentoDTO->getStrNomeTipoProcedimento());
      $objProcedimentoAPI->setIdTipoPrioridade($objProcedimentoDTO->getNumIdTipoPrioridade());
      $objProcedimentoAPI->setNivelAcesso($objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo());
      $objAcessoExternoAPI->setProcedimento($objProcedimentoAPI);

      if ($objAcessoExternoDTO->isSetObjDocumentoDTO()){

        $objDocumentoDTO = $objAcessoExternoDTO->getObjDocumentoDTO();

        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objDocumentoAPI->setNumeroProtocolo($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
        $objDocumentoAPI->setIdSerie($objDocumentoDTO->getNumIdSerie());
        $objDocumentoAPI->setIdUnidadeGeradora($objDocumentoDTO->getNumIdUnidadeGeradoraProtocolo());
        $objDocumentoAPI->setTipo($objDocumentoDTO->getStrStaProtocoloProtocolo());
        $objDocumentoAPI->setSinAssinado($objDocumentoDTO->getStrSinAssinado());
        $objDocumentoAPI->setSinPublicado($objDocumentoDTO->getStrSinPublicado());
        $objDocumentoAPI->setNivelAcesso($objDocumentoDTO->getStrStaNivelAcessoGlobalProtocolo());
        $objAcessoExternoAPI->setDocumento($objDocumentoAPI);
      }

      $arrObjAcessoExternoAPI[] = $objAcessoExternoAPI;

      /*
      if ($objAcessoExternoDTO->isSetObjDocumentoDTO()){
        $objDocumentoDTO = $objAcessoExternoDTO->getObjDocumentoDTO();
      }
      */
    }

    foreach ($SEI_MODULOS as $seiModulo) {
      if (InfraArray::contar($arrObjAcessoExternoAPI)){
        if (($arr = $seiModulo->executar('montarAcaoControleAcessoExterno', $arrObjAcessoExternoAPI))!=null){;
          foreach($arr as $key => $arr) {
            if (!isset($arrIntegracaoAcoesProcedimentos[$key])) {
              $arrIntegracaoAcoesProcedimentos[$key] = $arr;
            }else {
              $arrIntegracaoAcoesProcedimentos[$key] = array_merge($arrIntegracaoAcoesProcedimentos[$key], $arr);
            }
          }
        }
      }
    }



    $strResultado = '<table id="tblDocumentos" width="99%" class="infraTable" summary="Lista de Acessos Externos" align="center" >
  					  									<caption class="infraCaption" >'.PaginaSEIExterna::getInstance()->gerarCaptionTabela("Acessos Externos",$numRegistros).'</caption> 
  					 										<tr>
  					 										  <th class="infraTh" width="1%" style="display:none">'.PaginaSEIExterna::getInstance()->getThCheck().'</th>
  					 										  <th class="infraTh" width="20%">Processo</th>
  					  										<th class="infraTh" width="20%">Documento para Assinatura</th>
  					  										<th class="infraTh">Tipo</th>
  					  										<th class="infraTh" width="15%">Liberação</th>
  					  										<th class="infraTh" width="15%">Validade</th>
  					  										<th class="infraTh" width="10%">Ações</th>
  					  									</tr>';


    $n = 0;

    foreach($arrObjAcessoExternoDTO as $objAcessoExternoDTO){

      $bolExpirado = (!InfraString::isBolVazia($objAcessoExternoDTO->getDtaValidade()) && InfraData::compararDatas(InfraData::getStrDataAtual(), $objAcessoExternoDTO->getDtaValidade()) < 0);

      $objProcedimentoDTO = $objAcessoExternoDTO->getObjProcedimentoDTO();

      $objDocumentoDTO = null;
      if ($objAcessoExternoDTO->isSetObjDocumentoDTO()){
        $objDocumentoDTO = $objAcessoExternoDTO->getObjDocumentoDTO();
      }

      SessaoSEIExterna::getInstance()->configurarAcessoExterno($objAcessoExternoDTO->getNumIdAcessoExterno());

      $strLinkProcedimento = SessaoSEIExterna::getInstance()->assinarLink('processo_acesso_externo_consulta.php?id_acesso_externo='.$objAcessoExternoDTO->getNumIdAcessoExterno());

      if ($objDocumentoDTO != null){
        $bolFlagAssinou = false;
      	$arrObjAssinaturaDTO = $objDocumentoDTO->getArrObjAssinaturaDTO();
      	foreach($arrObjAssinaturaDTO as $objAssinaturaDTO){
      	  if ($objAssinaturaDTO->getNumIdUsuario()==SessaoSEIExterna::getInstance()->getNumIdUsuarioExterno()){
      	    $bolFlagAssinou = true;
      	    break;
      	  }
      	}
        $strLinkDocumento = SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_documento_assinar&id_acesso_externo='.$objAcessoExternoDTO->getNumIdAcessoExterno().'&id_documento='.$objDocumentoDTO->getDblIdDocumento());
      }

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

    	$strResultado .= '<td valign="top" style="display:none">'.PaginaSEIExterna::getInstance()->getTrCheck($n++,$objAcessoExternoDTO->getNumIdAcessoExterno(),$objAcessoExternoDTO->getNumIdAcessoExterno()).'</td>';

      if ($bolExpirado){
        $strResultado .= '<td align="center"><a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);alert(\'Este acesso externo expirou em '.$objAcessoExternoDTO->getDtaValidade().'.\');" alt="' . PaginaSEIExterna::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()) . '" title="' . PaginaSEIExterna::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()) . '" class="ancoraPadraoPreta">' . PaginaSEIExterna::tratarHTML($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado()) . '</a></td>';
      }else {
        if ($objAcessoExternoDTO->getStrSinProcesso() == 'S') {
          $strResultado .= '<td align="center"><a href="'.$strLinkProcedimento.'" target="_blank" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" alt="' . PaginaSEIExterna::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()) . '" title="' . PaginaSEIExterna::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()) . '" class="ancoraPadraoAzul">' . PaginaSEIExterna::tratarHTML($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado()) . '</a></td>'."\n";
        } else {
          $strResultado .= '<td align="center"><a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);alert(\'Sem acesso à íntegra do processo.\');" alt="' . PaginaSEIExterna::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()) . '" title="' . PaginaSEIExterna::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()) . '" class="ancoraPadraoPreta">' . PaginaSEIExterna::tratarHTML($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado()) . '</a></td>'."\n";
        }
      }

    	if ($objDocumentoDTO != null){
    	  if ($objDocumentoDTO->getStrStaEstadoProtocolo()!=ProtocoloRN::$TE_DOCUMENTO_CANCELADO){
          $strResultado .= '<td align="center"><a href="'.$strLinkDocumento.'" target="_blank" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" tabindex="'.PaginaSEIExterna::getInstance()->getProxTabTabela().'" class="ancoraPadraoAzul" alt="'.PaginaSEIExterna::tratarHTML($objDocumentoDTO->getStrNomeSerie()).'" title="'.PaginaSEIExterna::tratarHTML($objDocumentoDTO->getStrNomeSerie()).'">'.PaginaSEIExterna::tratarHTML($objDocumentoDTO->getStrProtocoloDocumentoFormatado()).'</a></td>'."\n";
        }else{
    	    $strResultado .= '<td align="center"><a href="javascript:void(0);" onclick="alert(\'Documento foi cancelado.\');" alt="'.PaginaSEIExterna::tratarHTML($objDocumentoDTO->getStrNomeSerie()).'" title="'.PaginaSEIExterna::tratarHTML($objDocumentoDTO->getStrNomeSerie()).'" class="ancoraPadraoPreta">'.PaginaSEIExterna::tratarHTML($objDocumentoDTO->getStrProtocoloDocumentoFormatado()).'</a></td>'."\n";
    	  }
        $strResultado .= '<td align="center">'.PaginaSEIExterna::tratarHTML($objDocumentoDTO->getStrNomeSerie()).'</td>';
    	}else{
    	  $strResultado .= '<td align="center">&nbsp;</td>';
        $strResultado .= '<td align="center">&nbsp;</td>';
    	}


      $strResultado .= '<td align="center">'.PaginaSEIExterna::tratarHTML(substr($objAcessoExternoDTO->getDthAberturaAtividade(),0,10)).'</td>';
      $strResultado .= '<td align="center">'.(!InfraString::isBolVazia($objAcessoExternoDTO->getDtaValidade()) ?  PaginaSEIExterna::tratarHTML(substr($objAcessoExternoDTO->getDtaValidade(),0,10)) : "").'</td>';
      //$strResultado .= '<td align="center"><a alt="'.$objAcessoExternoDTO->getStrDescricaoUnidade().'" title="'.$objAcessoExternoDTO->getStrDescricaoUnidade().'" class="ancoraSigla">'.$objAcessoExternoDTO->getStrSiglaUnidade().'</a></td>';
      $strResultado .= '<td align="center">';

    	if (!$bolExpirado && $objDocumentoDTO != null && !$bolFlagAssinou && $objDocumentoDTO->getStrStaEstadoProtocolo()!=ProtocoloRN::$TE_DOCUMENTO_CANCELADO){
        $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);infraAbrirJanelaModal(\''.SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_assinar&id_acesso_externo='.$objAcessoExternoDTO->getNumIdAcessoExterno().'&id_documento='.$objDocumentoDTO->getDblIdDocumento()).'\',450,330);" tabindex="'.PaginaSEIExterna::getInstance()->getProxTabTabela().'"><img src="'.Icone::DOCUMENTO_ASSINAR.'" title="Assinar Documento" alt="Assinar Documento" class="infraImg" /></a>&nbsp;';
      }

      if ($bolHabilitarInclusaoDocumento && !$bolExpirado && $objAcessoExternoDTO->getStrSinInclusao() == "S"){
        $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);infraAbrirJanelaModal(\''.SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_incluir_documento&id_acesso_externo='.$objAcessoExternoDTO->getNumIdAcessoExterno().'&acao_origem='.$_GET['acao'].'&id_orgao_acesso_externo='.$_GET['id_orgao_acesso_externo']).'\',700,350);" tabindex="'.PaginaSEIExterna::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeUpload().'" title="Inclusão de Documento" alt="Inclusão de Documento" class="infraImg" /></a>';
      }

      if (is_array($arrIntegracaoAcoesProcedimentos) && isset($arrIntegracaoAcoesProcedimentos[$objAcessoExternoDTO->getNumIdAcessoExterno()])){
        foreach($arrIntegracaoAcoesProcedimentos[$objAcessoExternoDTO->getNumIdAcessoExterno()] as $strIconeIntegracao){
          $strResultado .= '&nbsp;'.$strIconeIntegracao;
        }
      }

    	$strResultado .='</td></tr>';

    }
    $strResultado .= '</table>';
  }

  SessaoSEIExterna::getInstance()->configurarAcessoExterno(null);

}catch(Exception $e){
  PaginaSEIExterna::getInstance()->processarExcecao($e);
}


PaginaSEIExterna::getInstance()->montarDocType();
PaginaSEIExterna::getInstance()->abrirHtml();
PaginaSEIExterna::getInstance()->abrirHead();
PaginaSEIExterna::getInstance()->montarMeta();
PaginaSEIExterna::getInstance()->montarTitle(PaginaSEIExterna::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEIExterna::getInstance()->montarStyle();
PaginaSEIExterna::getInstance()->abrirStyle();
?>


<?
PaginaSEIExterna::getInstance()->fecharStyle();
PaginaSEIExterna::getInstance()->montarJavaScript();
PaginaSEIExterna::getInstance()->abrirJavaScript();
?>

function inicializar(){
  infraEfeitoTabelas();
}
function verValidosExpirados(sinExpirados){
  document.getElementById('hdnSinExpirados').value = sinExpirados;
  document.getElementById('frmUsuarioExternoControle').action='<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_controle_acessos'.'&acao_origem='.$_GET['acao'])?>';
  document.getElementById('frmUsuarioExternoControle').submit();
}
<?
PaginaSEIExterna::getInstance()->fecharJavaScript();
PaginaSEIExterna::getInstance()->fecharHead();
PaginaSEIExterna::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<div id="divFiltro" class="infraAreaDados">
  <br>
  <a  href="#" class="ancoraPadraoPreta" onclick="verValidosExpirados('<?=$strSinExpirados=='S'?'N':'S'?>')" ><?=$strSinExpirados == 'S' ? "Ver válidos" : "Ver expirados" ?></a>
</div>
<form id="frmUsuarioExternoControle" method="post" action="<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <input type="hidden" id="hdnSinExpirados" name="hdnSinExpirados" value="<?=$strSinExpirados?>" />
  <?
  if(InfraArray::contar($arrComandos)) {
    PaginaSEIExterna::getInstance()->montarBarraComandosSuperior($arrComandos);
  }
PaginaSEIExterna::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
?>
</form>
<?
PaginaSEIExterna::getInstance()->montarAreaDebug();
PaginaSEIExterna::getInstance()->fecharBody();
PaginaSEIExterna::getInstance()->fecharHtml();
?>