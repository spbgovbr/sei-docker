<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/10/2010 - criado por mga
*
* Versão do Gerador de Código: 1.12.1
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
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
    
	$objArquivamentoDTO = new ArquivamentoDTO();
  
  $arrComandos = array();

  switch($_GET['acao']){
    
  	case 'arquivamento_solicitar_desarquivamento':
    	
      $strTitulo = 'Solicitar Desarquivamento';
      
      $arrComandos[] = '<button type="submit" name="sbmSolicitar" accesskey="S" value="Solicitar" class="infraButton"><span class="infraTeclaAtalho">S</span>olicitar</button>';


      $objArquivamentoDTO = new ArquivamentoDTO();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retStrNomeSerieDocumento();
      $objArquivamentoDTO->retStrNumeroDocumento();
      $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_ARQUIVADO);
      $objArquivamentoDTO->setDblIdProcedimentoDocumento($_GET['id_procedimento']);

      $objArquivamentoRN = new ArquivamentoRN();
      $arrObjArquivamentoDTO = $objArquivamentoRN->listar($objArquivamentoDTO);

			$numDocumentos = count($arrObjArquivamentoDTO);
			
      $strResultado = '<table id="tblDocumentos" width="90.5%" class="infraTable" summary="Lista de Documentos">
 						  			 	 <caption class="infraCaption" >'.PaginaSEI::getInstance()->gerarCaptionTabela("Documentos", $numDocumentos).'</caption> 
						 					 <tr>
						  				   <th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>
						  					 <th class="infraTh">Documento</th>
		  								 </tr>';
			
			for($i=0; $i < $numDocumentos; $i++){
					
			  //marca documento origem da solicitação através da árvore
				$strSinValor = 'N';
				if (!isset($_POST['hdnFlagSolicitarDesarquivamento']) && 
				     isset($_GET['id_documento']) && $_GET['id_documento']==$arrObjArquivamentoDTO[$i]->getDblIdProtocolo()){
				  $strSinValor = 'S';
				}
					
				$strResultado .= '<tr class="infraTrClara"> 
				                  <td align="center" class="infraTd">'.PaginaSEI::getInstance()->getTrCheck($i, $arrObjArquivamentoDTO[$i]->getDblIdProtocolo(), $arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento(),$strSinValor).'</td>
				                  <td  class="infraTd">'.PaginaSEI::tratarHTML($arrObjArquivamentoDTO[$i]->getStrNomeSerieDocumento().' '.$arrObjArquivamentoDTO[$i]->getStrNumeroDocumento()).' ('.$arrObjArquivamentoDTO[$i]->getStrProtocoloFormatadoDocumento().')</td>
                          </tr>';
			}
			
			$strResultado .= '</table>';
       
            	        
      if (isset($_POST['sbmSolicitar'])) {
        try{

          $objArquivamentoDTO->setDblIdProtocolo(PaginaSEI::getInstance()->getArrStrItensSelecionados());
       		
          $objArquivamentoRN = new ArquivamentoRN();
          $objArquivamentoRN->solicitarDesarquivamento($objArquivamentoDTO);
          
          PaginaSEI::getInstance()->setStrMensagem('Favor entrar em contato com o arquivo para retirada '.(InfraArray::contar($objArquivamentoDTO->getDblIdProtocolo())==1?'do documento':'dos documentos').'.',PaginaSEI::$TIPO_MSG_AVISO);
          
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&atualizar_arvore=1'.$strParametros));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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

var objLupaDocumentos = null;

function inicializar(){
  
  infraEfeitoTabelas();
  
}

function OnSubmitForm(){

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return false;
  }  
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmSolicitarDesarquivamento" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numDocumentos);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
<input type="hidden" id="hdnFlagSolicitarDesarquivamento" name="hdnFlagSolicitarDesarquivamento" value="1" />  
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>