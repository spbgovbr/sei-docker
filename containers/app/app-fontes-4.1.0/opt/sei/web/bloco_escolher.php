<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/02/2011 - criado por mga
*
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

  //PaginaSEI::getInstance()->salvarCamposPost(array(''));  
  
  $arrComandos = array();
  
  //Filtrar parâmetros
  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  if (isset($_GET['id_procedimento'])){
    $strParametros .= "&id_procedimento=".$_GET['id_procedimento'];
  } 

  
  if (isset($_GET['id_documento'])){
    $strParametros .= "&id_documento=".$_GET['id_documento'];
  } 
  

  switch($_GET['acao']){
    
    case 'bloco_escolher':
    	
   	  $strTitulo = 'Incluir em Bloco de Assinatura';  

      $numIdBloco = null;
      if (isset($_GET['id_bloco'])){
      	$numIdBloco = $_GET['id_bloco'];
      }else if (isset($_POST['selBloco'])){
      	$numIdBloco = $_POST['selBloco'];
      }

   	  $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
   	  
      //Monta tabela de documentos do processo
      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->retNumIdUnidadeGeradoraProtocolo();
      $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
      $objProcedimentoDTO->setStrSinDocTodos('S');
        
      $objProcedimentoRN = new ProcedimentoRN();
      $arr = $objProcedimentoRN->listarCompleto($objProcedimentoDTO);

			if(count($arr) == 0){
				throw new InfraException('Processo não encontrado.');
			}
			
			$objProcedimentoDTO = $arr[0];
      
			$objDocumentoRN = new DocumentoRN();
			$objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
			
			$strThCheckDocumentos = PaginaSEI::getInstance()->getThCheck('','Documentos');
			
			$arrIdProtocolosBlocos = array();
			
			if ($numIdBloco!=null){
				$objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
				$objRelBlocoProtocoloDTO->retDblIdProtocolo();
				$objRelBlocoProtocoloDTO->setNumIdBloco($numIdBloco);
				
				$arrIdProtocolosBlocos = InfraArray::indexarArrInfraDTO($objRelBlocoProtocoloRN->listarRN1291($objRelBlocoProtocoloDTO),'IdProtocolo');
			}

			
			$numDocumentos = 0;
			
			if (InfraArray::contar($objProcedimentoDTO->getArrObjDocumentoDTO())){
				
				$bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar'); 
				//$bolAcaoRelBlocoProtocoloListar = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_listar');
				$bolAcaoBlocoAssinaturaListar = SessaoSEI::getInstance()->verificarPermissao('bloco_assinatura_listar');
				$bolAcaoRelBlocoProtocoloCadastrar = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_cadastrar');
        $bolAcaoBlocoAssinaturaDisponibilizar = SessaoSEI::getInstance()->verificarPermissao('bloco_disponibilizar');
				$bolAcaoBlocoAssinaturaCadastrar = SessaoSEI::getInstance()->verificarPermissao('bloco_assinatura_cadastrar');
				
				$objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
				$objRelBlocoProtocoloDTO->retDblIdProtocolo();
				$objRelBlocoProtocoloDTO->retNumIdBloco();
				$objRelBlocoProtocoloDTO->setNumIdUnidadeBloco(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
				$objRelBlocoProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($objProcedimentoDTO->getArrObjDocumentoDTO(),'IdDocumento'),InfraDTO::$OPER_IN);
				
				$arrBlocosProtocolos = InfraArray::indexarArrInfraDTO($objRelBlocoProtocoloRN->listarRN1291($objRelBlocoProtocoloDTO),'IdProtocolo',true);
			
				foreach($objProcedimentoDTO->getArrObjDocumentoDTO() as $objDocumentoDTO){
					
					//se não esta no bloco e é selecionável
					if($objDocumentoRN->verificarSelecaoBlocoAssinatura($objDocumentoDTO)){
					  
						$strResultadoDocumentos .= '<tr class="infraTrClara">';
						
            $strSinValor = 'N';
            if (($_GET['acao_origem']=='arvore_visualizar' || $_GET['acao_origem']=='bloco_assinatura_cadastrar') && $_GET['id_documento']==$objDocumentoDTO->getDblIdDocumento()){
              $strSinValor = 'S';
            }

						$strResultadoDocumentos .= '<td align="center" valign="top" class="infraTd">';
						
						$strOpcoesCheck = '';
						if (isset($arrIdProtocolosBlocos[$objDocumentoDTO->getDblIdDocumento()])){  
						  $strSinValor = 'N';
						  $strOpcoesCheck = 'disabled="disabled" style="display:none;"';

              if (isset($_POST['hdnDocumentosItensSelecionados'])) {
                $arrSelecionados = array();
                foreach (explode(',', $_POST['hdnDocumentosItensSelecionados']) as $dblIdDocumentoSelecionado) {
                  if ($dblIdDocumentoSelecionado != $objDocumentoDTO->getDblIdDocumento()){
                    $arrSelecionados[] = $dblIdDocumentoSelecionado;
                  }
                }
                $_POST['hdnDocumentosItensSelecionados'] = implode(',', $arrSelecionados);
              }
						}
						
						$strResultadoDocumentos .= PaginaSEI::getInstance()->getTrCheck($numDocumentos++,$objDocumentoDTO->getDblIdDocumento(),$objDocumentoDTO->getStrProtocoloDocumentoFormatado(),$strSinValor,'Documentos',$strOpcoesCheck);
						
						$strResultadoDocumentos .= '</td>';
	
						$strResultadoDocumentos .= '<td  class="infraTd" align="center" valign="top">';
						
		        if ($bolAcaoDocumentoVisualizar){
		          $strResultadoDocumentos .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento='.$objDocumentoDTO->getDblIdDocumento()) .'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="protocoloNormal" style="font-size:1em !important;">'.PaginaSEI::tratarHTML($objDocumentoDTO->getStrProtocoloDocumentoFormatado()).'</a>';
		        }else{
		          $strResultadoDocumentos .= PaginaSEI::tratarHTML($objDocumentoDTO->getStrProtocoloDocumentoFormatado());
		        }
						
						$strResultadoDocumentos .= '</td>';

						$strResultadoDocumentos .= '<td  class="infraTd" valign="top">';
						$strResultadoDocumentos .= PaginaSEI::tratarHTML($objDocumentoDTO->getStrNomeSerie().' '.$objDocumentoDTO->getStrNumero());
						$strResultadoDocumentos .= '</td>';

						$strResultadoDocumentos .= '<td  class="infraTd" align="center" valign="top">';
						$strResultadoDocumentos .= $objDocumentoDTO->getDtaGeracaoProtocolo();
						$strResultadoDocumentos .= '</td>';
						
						$strResultadoDocumentos .= '<td align="center" valign="top" class="infraTd">';
						if (isset($arrBlocosProtocolos[$objDocumentoDTO->getDblIdDocumento()])){
							$strSeparadorBloco = '';
							foreach($arrBlocosProtocolos[$objDocumentoDTO->getDblIdDocumento()] as $objRelBlocoProtocoloDTO){
								$strResultadoDocumentos .= $strSeparadorBloco;
								
								/*
								if ($bolAcaoRelBlocoProtocoloListar){
                  $strResultadoDocumentos .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_listar&id_bloco='.$objRelBlocoProtocoloDTO->getNumIdBloco().PaginaSEI::getInstance()->montarAncora($objDocumentoDTO->getDblIdDocumento().'-'.$objRelBlocoProtocoloDTO->getNumIdBloco()))) .'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="linkFuncionalidade" style="font-size:1em !important;">'.$objRelBlocoProtocoloDTO->getNumIdBloco().'</a>';
								}else{
		              $strResultadoDocumentos .= $objRelBlocoProtocoloDTO->getNumIdBloco();
								}
								*/
								
								if ($bolAcaoBlocoAssinaturaListar){
                  $strResultadoDocumentos .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_assinatura_listar&acao_origem='.$_GET['acao'].'&id_bloco='.$objRelBlocoProtocoloDTO->getNumIdBloco().PaginaSEI::getInstance()->montarAncora($objRelBlocoProtocoloDTO->getNumIdBloco())) .'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="linkFuncionalidade" style="font-size:1em !important;">'.$objRelBlocoProtocoloDTO->getNumIdBloco().'</a>';
								}else{
		              $strResultadoDocumentos .= $objRelBlocoProtocoloDTO->getNumIdBloco();
								}
								
								
								$strSeparadorBloco = '<br />';
							}
						}else{
						  $strResultadoDocumentos .= '&nbsp;';
						}
						$strResultadoDocumentos .= '</td>';
						
	          $strResultadoDocumentos .= '</tr>';
								
					}
				}
				
				if ($numDocumentos){
		      $strResultadoDocumentos = '<table id="tblDocumentos" width="99%" class="infraTable" summary="Lista de documentos disponíveis para inclusão">
		 						  									<caption class="infraCaption" >'.PaginaSEI::getInstance()->gerarCaptionTabela("documentos disponíveis para inclusão",$numDocumentos).'</caption> 
								 										<tr>
								 										  <th class="infraTh" width="1%">'.$strThCheckDocumentos.'</th>
								 										  <th class="infraTh" width="15%">Nº SEI</th>
								  										<th class="infraTh">Documento</th>
								  										<th class="infraTh" width="15%">Data</th>
								  										<th class="infraTh" width="15%">Blocos</th>
								  									</tr>'.
		                                $strResultadoDocumentos.
		                                '</table>';
				}				
			}

			if ($bolAcaoRelBlocoProtocoloCadastrar){
        $arrComandos[] = '<button type="submit" name="sbmIncluir" id="sbmIncluir" accesskey="I" value="Incluir" class="infraButton"><span class="infraTeclaAtalho">I</span>ncluir</button>';
			}

		  if ($bolAcaoBlocoAssinaturaCadastrar && $bolAcaoBlocoAssinaturaDisponibilizar){
        $arrComandos[] = '<button type="submit" name="sbmIncluirDisponibilizar" id="sbmIncluirDisponibilizar" accesskey="D" value="Incluir e Disponibilizar" class="infraButton">Incluir e <span class="infraTeclaAtalho">D</span>isponibilizar</button>';
      }

			if ($bolAcaoBlocoAssinaturaCadastrar){
	      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovoAssinatura" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_assinatura_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo Bloco</button>';
			}
			
	    //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&resultado=0'.$strParametros)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
	    
			////////////////////////////////////////////////////////////////////////////////////////////////                                

			if (isset($_POST['sbmIncluir']) || isset($_POST['sbmIncluirDisponibilizar'])){

			  $arrIdDocumentos = PaginaSEI::getInstance()->getArrStrItensSelecionados('Documentos');
        $arrObjRelBlocoProtocoloDTO = array();
        foreach($arrIdDocumentos as $dblIdDocumento){
        	$objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
        	$objRelBlocoProtocoloDTO->setNumIdBloco($numIdBloco);
        	$objRelBlocoProtocoloDTO->setDblIdProtocolo($dblIdDocumento);
        	$objRelBlocoProtocoloDTO->setStrAnotacao(null);
        	$arrObjRelBlocoProtocoloDTO[] = $objRelBlocoProtocoloDTO;
        }
        
      	try{

          if (isset($_POST['sbmIncluir'])) {
            $objRelBlocoProtocoloRN->cadastrarMultiplo($arrObjRelBlocoProtocoloDTO);
          }else{
            $objRelBlocoProtocoloRN->cadastrarDisponibilizarMultiplo($arrObjRelBlocoProtocoloDTO);
          }

          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_bloco='.$numIdBloco.$strParametros.PaginaSEI::getInstance()->montarAncora($arrIdDocumentos)));
          die;
      		
      	}catch(Exception $e){
      		PaginaSEI::getInstance()->processarExcecao($e);
      	}
			}
      break;
     
    	default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
 
  $bolAcaoBlocoAssinaturaListar = SessaoSEI::getInstance()->verificarPermissao('bloco_assinatura_listar');
  
  if ($bolAcaoBlocoAssinaturaListar){
    $strLinkBlocosAssinatura = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_assinatura_listar&acao_origem='.$_GET['acao']);
  }
  
  $strItensSelBloco = BlocoINT::montarSelectAssinatura('null','&nbsp;',$numIdBloco);
  
  
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

#lblBloco {position:absolute;left:0%;top:0%;}
#selBloco {position:absolute;left:0%;top:25%;width:99%;}

#ancIrBlocosAssinatura {position:absolute;left:0%;top:65%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  infraEfeitoTabelas();
  self.setTimeout('document.getElementById(\'selBloco\').focus()',500);
}

function OnSubmitForm() {
 
  if (!infraSelectSelecionado('selBloco')) {
    alert('Selecione um Bloco de Assinatura.');
    document.getElementById('selBloco').focus();
    return false;
  }
 
 if (document.getElementById('hdnDocumentosItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return false;
  }

  return true;  
}

<? if ($bolAcaoBlocoAssinaturaListar){ ?>
  function irBlocosAssinatura(){
    parent.parent.document.location.href = '<?=$strLinkBlocosAssinatura?>';
  }
<?}?>  

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmBlocoEscolher" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>" >
<?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->montarAreaValidacao();
  PaginaSEI::getInstance()->abrirAreaDados('8em');
?>
  <label id="lblBloco" for="selBloco" accesskey="B" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">B</span>loco:</label>
  <select id="selBloco" name="selBloco" class="infraSelect" onchange="this.form.submit();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelBloco?>
  </select>

  <?if ($bolAcaoBlocoAssinaturaListar){?>
  <a id="ancIrBlocosAssinatura" href="javascript:void(0);" onclick="irBlocosAssinatura();" class="ancoraPadraoPreta" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Ir para Blocos de Assinatura</a>
  <?}?>
<?
  PaginaSEI::getInstance()->fecharAreaDados();
  if ($numDocumentos){
    PaginaSEI::getInstance()->montarAreaTabela($strResultadoDocumentos,$numDocumentos);
  }else{
  	if ($numIdBloco!=null){
  	  echo '<label>Nenhum documento disponível para inclusão neste bloco de assinatura.</label>';
  	}else{
  		echo '<label>Nenhum documento disponível para inclusão em bloco de assinatura.</label>';
  	}
  }
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
?>
</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>