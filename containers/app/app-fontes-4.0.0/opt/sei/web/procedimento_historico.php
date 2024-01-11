<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
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

  PaginaSEI::getInstance()->salvarCamposPost(array('hdnTipoHistorico'));

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  if(isset($_GET['id_localizador'])){
    $strParametros .= '&id_localizador='.$_GET['id_localizador'];
  }

  if(isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }
  
  
 	$arrComandos = array();

  switch($_GET['acao']){  	      
    case 'procedimento_consultar_historico':
    	//Título
      $strTitulo = 'Histórico do Processo';
      break;    	

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $bolAcaoDefinirAtividade = SessaoSEI::getInstance()->verificarPermissao('procedimento_atualizar_andamento');
  
  if ($bolAcaoDefinirAtividade){
    $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
    $objPesquisaPendenciaDTO->setDblIdProtocolo($_GET['id_procedimento']);
    $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
    $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    
    $objAtividadeRN = new AtividadeRN();
    $arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);
    
    if (count($arrObjProcedimentoDTO)>0){
      $arrComandos[] = '<button type="button" accesskey="A" id="btnAtualizarAndamento" name="btnAtualizarAndamento" value="Atualizar Andamento" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_atualizar_andamento&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$_GET['id_procedimento'].'&arvore=1').'\';" class="infraButton"><span class="infraTeclaAtalho">A</span>tualizar Andamento</button>';
    }
  }
  
  
  $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
  $objProcedimentoHistoricoDTO->setDblIdProcedimento($_GET['id_procedimento']);
  $objProcedimentoHistoricoDTO->setStrStaHistorico(PaginaSEI::getInstance()->recuperarCampo('hdnTipoHistorico',ProcedimentoRN::$TH_RESUMIDO));
  
  PaginaSEI::getInstance()->prepararPaginacao($objProcedimentoHistoricoDTO,100);
  $objProcedimentoRN = new ProcedimentoRN();
  $objProcedimentoDTORet = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);
  PaginaSEI::getInstance()->processarPaginacao($objProcedimentoHistoricoDTO);
  
  $arrObjAtividadeDTO = $objProcedimentoDTORet->getArrObjAtividadeDTO();

  $strTitulo .= ' '.$objProcedimentoDTORet->getStrProtocoloProcedimentoFormatado().'&nbsp;&nbsp;';
  
  
  $numRegistrosAtividades = InfraArray::contar($arrObjAtividadeDTO);
  
  if ($numRegistrosAtividades > 0){
    
    $bolCheck = false;

    $strResultado = '';

    $strResultado .= '<table id="tblHistorico" width="99%" class="infraTable" summary="Histórico de Andamentos">'."\n";    
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Andamentos',$numRegistrosAtividades).'</caption>';
		$strResultado .= '<tr>';
		$strResultado .= '<th class="infraTh" width="15%">Data/Hora</th>';
		$strResultado .= '<th class="infraTh" width="15%">Unidade</th>';
		//$strResultado .= '<th class="infraTh" width="15%">Órgão</th>';
		$strResultado .= '<th class="infraTh" width="10%">Usuário</th>';
		$strResultado .= '<th class="infraTh">Descrição</th>';
		$strResultado .= '</tr>'."\n";					

		$strQuebraLinha = '<span style="line-height:.5em"><br /></span>';
		
		foreach($arrObjAtividadeDTO as $objAtividadeDTO){
        
        //InfraDebug::getInstance()->gravar($objAtividadeDTO->getNumIdAtividade());
      
        $strResultado .= "\n\n".'<!-- '.$objAtividadeDTO->getNumIdAtividade().' -->'."\n";
        
				if ($objAtividadeDTO->getStrSinUltimaUnidadeHistorico() == 'S'){		
					$strAbertas = 'class="andamentoAberto"';
				}else{
					$strAbertas = 'class="andamentoConcluido"';
				}	
				
				$strResultado .= '<tr '.$strAbertas.'>';		
				$strResultado .= "\n".'<td align="center" valign="top">';
			  $strResultado .= substr($objAtividadeDTO->getDthAbertura(),0,16);
			  
				$strResultado .= '</td>';
				$strResultado .= "\n".'<td align="center"  valign="top">';
			  $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUnidade()).'</a>';
				$strResultado .= '</td>';

				//$strResultado .= '</td>';
				//$strResultado .= "\n".'<td align="center"  valign="top">';
			  //$strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoOrgao()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoOrgao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaOrgao()).'</a>';
				//$strResultado .= '</td>';

				$strResultado .= "\n".'<td align="center"  valign="top">';
			  $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioOrigem()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioOrigem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUsuarioOrigem()).'</a>';
				$strResultado .= '</td>';
			  $strResultado .= "\n".'<td valign="top">';
			  
				if (!InfraString::isBolVazia($objAtividadeDTO->getStrNomeTarefa())){
					$strResultado .= nl2br($objAtividadeDTO->getStrNomeTarefa()).$strQuebraLinha;
				}else{
					$strResultado .= '&nbsp;';
				}
			
				$strResultado .= '</td>';
					
				$strResultado .= '</tr>';				
  	}
    $strResultado .= '</table>';
  }
  
  //$arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_procedimento']))).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  
  if ($objProcedimentoHistoricoDTO->getStrStaHistorico()==ProcedimentoRN::$TH_PARCIAL){
    $strLinkTipoHistorico = '<a id="ancTipoHistorico" onclick="verHistorico(\''.ProcedimentoRN::$TH_RESUMIDO.'\');" class="ancoraPadraoPreta">Ver histórico resumido</a>';
  }else {
    $strLinkTipoHistorico = '<a id="ancTipoHistorico" onclick="verHistorico(\''.ProcedimentoRN::$TH_PARCIAL.'\');" class="ancoraPadraoPreta">Ver histórico completo</a>';
  }
  
  if (SessaoSEI::getInstance()->verificarPermissao('procedimento_historico_total')){
    $strLinkTipoHistoricoTotal = '<a id="ancTipoHistoricoTotal" onclick="verHistorico(\''.ProcedimentoRN::$TH_TOTAL.'\');" class="ancoraPadraoPreta">Ver histórico total</a>';
  }

  $strUnidadesAcessoAutomatico = '';
  if ($objProcedimentoDTORet->getStrStaNivelAcessoGlobalProtocolo()==ProtocoloRN::$NA_RESTRITO) {
    $objAcessoDTO = new AcessoDTO();
    $objAcessoDTO->setDistinct(true);
    $objAcessoDTO->retStrSiglaUnidade();
    $objAcessoDTO->retStrDescricaoUnidade();
    $objAcessoDTO->setDblIdProtocolo($objProcedimentoDTORet->getDblIdProcedimento());
    $objAcessoDTO->setStrStaTipo(AcessoRN::$TA_CONTROLE_INTERNO);
    $objAcessoDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objAcessoRN = new AcessoRN();
    $arrObjAcessoDTO = $objAcessoRN->listar($objAcessoDTO);

    $numUnidadesAcessoAutomatico = count($arrObjAcessoDTO);

    for($i=0;$i<$numUnidadesAcessoAutomatico; $i++){

      if ($i){
        if ($i == ($numUnidadesAcessoAutomatico - 1)) {
          $strUnidadesAcessoAutomatico .= ' e ';
        }else{
          $strUnidadesAcessoAutomatico .= ', ';
        }
      }

      $strUnidadesAcessoAutomatico .= '<a alt="'.PaginaSEI::tratarHTML($arrObjAcessoDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjAcessoDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjAcessoDTO[$i]->getStrSiglaUnidade()).'</a>';
    }

    if ($strUnidadesAcessoAutomatico!=''){
      $strUnidadesAcessoAutomatico = '<br /><label>Unidades com acesso automático para consulta ao processo: '.$strUnidadesAcessoAutomatico.'.</label>';
    }
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->setBolAutoRedimensionar(false);

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>


.andamentoAberto {
  background-color:#ffff66;
}

.andamentoConcluido {
  background-color:white;
}

#tblHistorico td{
padding:.2em;
}
  
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  infraEfeitoTabelas();

  }

function verHistorico(valor){
  document.getElementById('hdnTipoHistorico').value = valor;
  document.getElementById('frmProcedimentoHistorico').submit();
}


<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<form id="frmProcedimentoHistorico" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">


  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);

  if (count($arrComandos)>0){
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  }
  
  PaginaSEI::getInstance()->abrirAreaDados('2.5em');
  echo $strLinkTipoHistorico.'&nbsp;&nbsp;&nbsp;&nbsp;'.$strLinkTipoHistoricoTotal;
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado.$strUnidadesAcessoAutomatico,$numRegistrosAtividades);
  //echo $strUnidadesAcessoAutomatico;
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  
  <input type="hidden" id="hdnTipoHistorico" name="hdnTipoHistorico" value="<?=$objProcedimentoHistoricoDTO->getStrStaHistorico();?>" />

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>