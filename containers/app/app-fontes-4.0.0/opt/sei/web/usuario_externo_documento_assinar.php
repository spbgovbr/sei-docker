<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/11/2015 - criado por bcu
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  SessaoSEIExterna::getInstance()->validarLink();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEIExterna::getInstance()->validarLink();

  PaginaSEIExterna::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  switch($_GET['acao']){

    case 'usuario_externo_documento_assinar':

      $strTitulo = 'Documento para Assinatura';

      $objAcessoExternoDTO = new AcessoExternoDTO();
      $objAcessoExternoDTO->setNumIdUsuarioExterno(SessaoSEIExterna::getInstance()->getNumIdUsuarioExterno());
      $objAcessoExternoDTO->setDblIdDocumento($_GET['id_documento']);

      $objAcessoExternoRN = new AcessoExternoRN();
      $arrObjAcessoExternoDTO = $objAcessoExternoRN->listarDocumentosControleAcesso($objAcessoExternoDTO);

      if (count($arrObjAcessoExternoDTO)!=1) {
        throw new InfraException('Documento não está disponível.', null, null, false);
      }

      $objDocumentoDTO=$arrObjAcessoExternoDTO[0]->getObjDocumentoDTO();
      $strLinkDocumento = SessaoSEIExterna::getInstance()->assinarLink('documento_consulta_externa.php?id_acesso_externo='.$_GET['id_acesso_externo'].'&id_documento='.$objDocumentoDTO->getDblIdDocumento());
      $strLinkAssinatura = SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_assinar&id_acesso_externo='.$_GET['id_acesso_externo'].'&id_documento='.$objDocumentoDTO->getDblIdDocumento());
      $strDocumento= $objDocumentoDTO->getStrProtocoloDocumentoFormatado();

      $objProcedimentoDTO=$arrObjAcessoExternoDTO[0]->getObjProcedimentoDTO();
      $strProcesso = $objProcedimentoDTO->getStrProtocoloProcedimentoFormatado();

      $bolFlagAssinou = false;
      $arrObjAssinaturaDTO = $objDocumentoDTO->getArrObjAssinaturaDTO();
      foreach($arrObjAssinaturaDTO as $objAssinaturaDTO){
        if ($objAssinaturaDTO->getNumIdUsuario()==SessaoSEIExterna::getInstance()->getNumIdUsuarioExterno()){
          $bolFlagAssinou = true;
          break;
        }
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


}catch(Exception $e){
  PaginaSEIExterna::getInstance()->processarExcecao($e);
}

PaginaSEIExterna::getInstance()->montarDocType();
PaginaSEIExterna::getInstance()->abrirHtml();
PaginaSEIExterna::getInstance()->abrirHead();
PaginaSEIExterna::getInstance()->montarMeta();
echo '<meta name="viewport" content="width=980">';
PaginaSEIExterna::getInstance()->montarTitle(PaginaSEIExterna::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEIExterna::getInstance()->montarStyle();
PaginaSEIExterna::getInstance()->abrirStyle();
?>
#divNavegacaoBloco {position:fixed;width:100%;z-index:9000;}
body {margin:0;overflow:hidden}

#divDocumento {box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box;}
#ifrDocumento {width:100%;border:0;top:40px;position:absolute;overflow:auto;}

#lblNumProcesso {color:white;font-size:20px; position:relative;left:3px; }
#lblNumDocumento {color:white;font-size:20px; position:relative;left:3px;}
#lblProcesso {color:white;position:relative;left:3px;}
#lblDocumento {color:white;position:relative;left:3px;}

#divAcoes {float:right;}
#imgAssinatura {float:left;}

#btnAssinar {border: 1px solid #a6a6a6;
  border-radius: 3px;
  background: #e4e4e4;
  background-image: -webkit-linear-gradient(top,#fff,#e4e4e4);
  background-image: -moz-linear-gradient(top,#fff,#e4e4e4);
  box-sizing: content-box;
  margin: 6px;
  height: 30px;
  }

#divAcoes img{
border:0;

}

<?
PaginaSEIExterna::getInstance()->fecharStyle();
PaginaSEIExterna::getInstance()->montarJavaScript();
PaginaSEIExterna::getInstance()->abrirJavaScript();
?>
//<script type="text/javascript">
  function inicializar() {
    infraAdicionarEvento(window,'resize',redimensionar);
    infraEfeitoTabelas();
    redimensionar();
  }
  function redimensionar() {
    setTimeout(function(){

      var tamDivNavegacao=document.getElementById('divNavegacaoBloco').offsetHeight;
      var ifrDocumento=document.getElementById('ifrDocumento');
      if (tamDivNavegacao>ifrDocumento.offsetHeight) tamDivNavegacao-=ifrDocumento.offsetHeight;
      var tamEditor=infraClientHeight()- tamDivNavegacao;
      ifrDocumento.style.height = (tamEditor>0?tamEditor:1) +'px';
    },0);
  }

  function assinar(){
      infraAbrirJanelaModal('<?=$strLinkAssinatura;?>',450,330);
  }

  //</script>
<?
PaginaSEIExterna::getInstance()->fecharJavaScript();
PaginaSEIExterna::getInstance()->fecharHead();
//PaginaSEIExterna::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<body onload="inicializar()">
  <div id="divNavegacaoBloco" class="infraCorBarraSistema">
    <div style="display:block;float:left;left:5px;position:relative">
      <label id="lblProcesso">Processo:</label>
      <br />
      <label id="lblNumProcesso"><?=$strProcesso;?></label>
    </div>
    <div style="display:block;float:left;left:30px;position:relative">
      <label id="lblDocumento">Documento:</label>
      <br />
      <label id="lblNumDocumento"><?=$strDocumento;?></label>
    </div>
<? if ($objDocumentoDTO != null && !$bolFlagAssinou && $objDocumentoDTO->getStrStaEstadoProtocolo()!=ProtocoloRN::$TE_DOCUMENTO_CANCELADO){ ?>
    <div id="divAcoes">
      <button id="btnAssinar" onclick="assinar()" style="">
        <img id="imgAssinatura" src="<?=Icone::DOCUMENTO_ASSINAR?>" alt="Assinar Documento" title="Assinar Documento" style="float: left;">
        <span style="height: 30px;padding:10px; vertical-align: sub; ">Assinar</span>
      </button>
    </div>
<? } ?>
</div>
  <?
//  PaginaSEIExterna::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEIExterna::getInstance()->montarAreaDebug();
//  PaginaSEIExterna::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  <div id="divDocumento">
    <iframe id="ifrDocumento" src="<?=$strLinkDocumento; ?>">

    </iframe>
  </div>
<?
PaginaSEIExterna::getInstance()->fecharBody();
PaginaSEIExterna::getInstance()->fecharHtml();
?>