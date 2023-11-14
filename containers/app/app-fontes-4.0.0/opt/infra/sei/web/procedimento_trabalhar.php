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

  //PaginaSEI::getInstance()->prepararSelecao('procedimento_selecionar');
  
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->setBolAutoRedimensionar(false);

  $strLinkMontarArvore = null;
  
  switch($_GET['acao']){  	     
        
    case 'procedimento_trabalhar':
    	//Título
      $strTitulo = 'Processo';
      
      $dblIdProcedimento = '';
      $dblIdDocumento = '';
      $dblIdProcedimentoAnexado = '';
      
      if (isset($_GET['id_procedimento']) && isset($_GET['id_documento'])){
        
        $dblIdProcedimento = $_GET['id_procedimento'];
        $dblIdDocumento = $_GET['id_documento'];

        ProtocoloINT::adicionarProtocoloVisitado($dblIdProcedimento);

      }else if (isset($_GET['id_procedimento'])){
        
        $dblIdProcedimento = $_GET['id_procedimento'];

        ProtocoloINT::adicionarProtocoloVisitado($dblIdProcedimento);
        
      }else if (isset($_GET['id_documento'])){
        
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdProcedimento();
        $objDocumentoDTO->setDblIdDocumento($_GET['id_documento']);
        
        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

        if ($objDocumentoDTO==null){
          throw new InfraException('Documento não encontrado.',null,null,false);
        }
        
        $dblIdProcedimento = $objDocumentoDTO->getDblIdProcedimento();
        $dblIdDocumento = $_GET['id_documento'];
        
      }else if (isset($_GET['id_protocolo'])){
        
        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retStrStaProtocolo();
        $objProtocoloDTO->setDblIdProtocolo($_GET['id_protocolo']);
        
        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
      
        if ($objProtocoloDTO==null){
          throw new InfraException('Registro não encontrado.', null, null, false);
        }
          
        if ($objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_PROCEDIMENTO){

          $dblIdProcedimento = $objProtocoloDTO->getDblIdProtocolo();

          ProtocoloINT::adicionarProtocoloVisitado($dblIdProcedimento);

        }else{
          $dblIdDocumento = $objProtocoloDTO->getDblIdProtocolo();
          
          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($dblIdDocumento);
          $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
          
          $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
          $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);
          
          $dblIdProcedimento = $objRelProtocoloProtocoloDTO->getDblIdProtocolo1();
        }        
      }
      
      if (isset($_GET['id_procedimento_anexado'])){
        $dblIdProcedimentoAnexado = $_GET['id_procedimento_anexado'];
      }
      
      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retStrStaNivelAcessoGlobal();
      $objProtocoloDTO->setDblIdProtocolo($dblIdProcedimento);
      
      $objProtocoloRN = new ProtocoloRN();
			$objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

			if ($objProtocoloDTO==null){
			  throw new InfraException('Processo não encontrado.',null,null,false);
			}

			if ($objProtocoloDTO->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_SIGILOSO && $_GET['acesso']!='1' && $_GET['acao_origem']!='procedimento_gerar'){

        //verifica permissão de acesso ao processo
        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
        $objPesquisaProtocoloDTO->setDblIdProtocolo($dblIdProcedimento);
        
        $objProtocoloRN = new ProtocoloRN();
        $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);

        if (count($arrObjProtocoloDTO)==0){
     			header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao']));
					die;     			
        }
				
				$bolAcesso = false;
				$strLinkMontarArvore = '';
				$strLinkAcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=usuario_validar_acesso&acao_origem='.$_GET['acao'].'&acao_destino=procedimento_trabalhar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado.'&acao_negado=procedimento_controlar');
      }else{
        $bolAcesso = true;
        $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&acao_retorno='.PaginaSEI::getInstance()->getAcaoRetorno().'&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado);
        $strLinkAcesso = '';	
      }
      
      break;    	
 
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $numPercentualArvore = '23';
  $numPercentualVisualizacao = '73';
  $bolNavegadorSafariIPad = PaginaSEI::getInstance()->isBolNavegadorSafariIpad();

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
#divInfraBarraLocalizacao {
  display:none;
}


.divLinha{
  background-image: url(imagens/barra_redimensionamento.gif);
  background-repeat: repeat-y;
  background-position: left;
  padding-left:6px;
}



#divInfraAreaTelaD{
  padding: 0px !important;
  overflow-y: auto;
}
.divIosScroll {
  overflow: scroll;
  -webkit-overflow-scrolling: touch;
}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
//PaginaSEI::getInstance()->abrirJavaScript();
?>
<script>

function redimensionar() {
  if( !infraIsBreakpointBootstrap("lg") ) {
    $("#divIframeVisualizacao").removeClass("divLinha");
    document.getElementById('divIframeVisualizacao').style.cssText  = "display:block !important;";
    document.getElementById('divIframeArvore').style.cssText  = "display:none !important;";
  }else{
    adicionarLinha();
    document.getElementById('divIframeVisualizacao').style.cssText  = "display:block !important;";
    document.getElementById('divIframeArvore').style.cssText  = "display:block !important;";
  }
}

function adicionarLinha(){
  $("#divIframeVisualizacao").addClass("divLinha");
  $("#divIframeArvore").resizable({
    handles: "e,  w",

    minWidth: 200,
    maxWidth: $(document).width() - 600,

    start: function () {
      ifr = $('#ifrArvore');
      var d = $('<div></div>');

      $('#divConteudo').append(d[0]);
      d[0].id = 'temp_div';
      d.css({position: 'absolute'});
      d.css({top: ifr.position().top, left: 0});
      d.height(ifr.height());
      d.width('100%');
    },
    stop: function () {
      $('#temp_div').remove();
    }
  });
}

function inicializar(){

  if(infraIsBreakpointBootstrap("lg")) {
    adicionarLinha();
  }


  if ('<?=$bolAcesso?>'!='1'){
    infraAbrirJanelaModal('<?=$strLinkAcesso?>',500,300,true,'finalizar');
    return;
  }
    
  if ('<?=$_GET['acao_origem']?>' == 'procedimento_controlar' ||
      '<?=$_GET['acao_origem']?>' == 'procedimento_gerar' ||
      '<?=$_GET['acao_origem']?>' == 'rel_bloco_protocolo_listar' ||
      '<?=$_GET['acao_origem']?>' == 'procedimento_duplicar'){
    infraOcultarMenuSistemaEsquema(false);
  }

  infraAdicionarEvento(window,'resize',redimensionar);

}
function verificar(ifr){

  //se trocou unidade
  if (window.frames[ifr.id] != null && window.frames[ifr.id].document.getElementById('frmProcedimentoControlar')!=null){
    ifr.style.visibility = 'hidden';
    parent.parent.document.location.href = window.frames[ifr.id].document.location.href;
  }
}

</script>
<?
//PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
if (PaginaSEI::getInstance()->getStrMensagens()==''){
?>

	<div id="divConteudo" class="w-100 d-flex flex-grow-1 flex-lg-row" style="height:  <?=($bolNavegadorSafariIPad ? 'calc(100vh - 70px);' : '100%')?>;" >

    <div id="divIframeArvore" class=" flex-grow-1 flex-lg-grow-0 h-100 <?=($bolNavegadorSafariIPad ? 'divIosScroll' : '')?>" >
	    <iframe id="ifrArvore"  name="ifrArvore" class="ifrArvore w-100" style="height: 100%;display: block;" onload="verificar(this);" src="<?=$strLinkMontarArvore?>" frameborder="0"  ></iframe>
    </div>

    <div id="divIframeVisualizacao" class="flex-grow-1 d-none d-lg-block h-100">
      <iframe id="ifrVisualizacao" name="ifrVisualizacao" style="height:100%;display: block;"  class=" w-100" onload="verificar(this);" src="about:blank" frameborder="0"></iframe>
    </div>
  </div>

<?
}
//PaginaSEI::getInstance()->montarAreaDebug();
//PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>