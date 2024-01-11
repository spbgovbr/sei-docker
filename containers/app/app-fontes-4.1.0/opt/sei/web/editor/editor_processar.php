<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/04/2012 - criado por mga
 *
 * Versão do Gerador de Código: 1.32.1
 *
 * Versão no CVS: $Id: editor_processar.php 10312 2015-09-16 15:01:30Z bcu $
 */

try {
  require_once __DIR__ . '/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  if ($_GET['acao'] == 'editor_salvar' && (isset($_POST['hdnSiglaUnidade']) && $_POST['hdnSiglaUnidade']!=SessaoSEI::getInstance()->getStrSiglaUnidadeAtual())){
    die("INFRA_VALIDACAO\nDetectada troca para a unidade ".SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().".\\nPara salvar este documento é necessário retornar para a unidade ".$_POST['hdnSiglaUnidade'].".");
  }

  PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarAuditarPermissao($_GET['acao']);

  $strParametros = '';

  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }

  if (isset($_GET['id_documento'])){
    $strParametros .= '&id_documento='.$_GET['id_documento'];
  }

  if (SessaoSEI::getInstance()->verificarPermissao('documento_assinar')){
    $strLinkAssinatura=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_assinar&acao_origem=editor_montar&acao_retorno=editor_montar&id_documento='.$_GET['id_documento']);
  }

  if (isset($_GET['id_base_conhecimento'])){
    $strParametros .= '&id_base_conhecimento='.$_GET['id_base_conhecimento'];
  }
  $strLinkAjaxProtocoloLinkEditor = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=protocolo_link_editor');
  $strLinkAjaxConfirmarAlteracao= SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=corfirmacao_atualizacao_documento&id_documento='.$_GET['id_documento']);
  switch($_GET['acao']){
    case 'editor_imagem_upload':
      if (isset($_FILES['filArquivo'])){
        PaginaSEI::getInstance()->processarUpload('filArquivo', DIR_SEI_TEMP, false);
      }
      die;
    case 'editor_montar':

      if (isset($_GET['id_documento'])){

        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retDblIdDocumento();
        $objDocumentoDTO->retStrProtocoloDocumentoFormatado();
        $objDocumentoDTO->retStrNomeSerie();
        $objDocumentoDTO->retNumIdConjuntoEstilos();
        $objDocumentoDTO->retStrStaProtocoloProtocolo();
        $objDocumentoDTO->setDblIdDocumento($_GET['id_documento']);

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

        if ($objDocumentoDTO==null){
          throw new InfraException('Documento não encontrado.', null, null, false);
        }
        if($objDocumentoDTO->getStrStaProtocoloProtocolo()!=ProtocoloRN::$TP_DOCUMENTO_GERADO){
          throw new InfraException('Tipo de Documento inválido.');
        }

        $strTitulo = DocumentoINT::montarTitulo($objDocumentoDTO);

        $objEditorDTO = new EditorDTO();
        $objEditorDTO->setDblIdDocumento($objDocumentoDTO->getDblIdDocumento());
        $objEditorDTO->setNumIdConjuntoEstilos($objDocumentoDTO->getNumIdConjuntoEstilos());
        $objEditorDTO->setNumIdBaseConhecimento(null);
        $objEditorDTO->setStrSinMontandoEditor('S');
      } elseif (isset($_GET['id_base_conhecimento'])) {
        $objBaseConhecimentoDTO = new BaseConhecimentoDTO();
        $objBaseConhecimentoDTO->retNumIdBaseConhecimento();
        $objBaseConhecimentoDTO->retStrDescricao();
        $objBaseConhecimentoDTO->retStrSiglaUnidade();
        $objBaseConhecimentoDTO->retNumIdConjuntoEstilos();
        $objBaseConhecimentoDTO->setNumIdBaseConhecimento($_GET['id_base_conhecimento']);

        $objBaseConhecimentoRN = new BaseConhecimentoRN();
        $objBaseConhecimentoDTO = $objBaseConhecimentoRN->consultar($objBaseConhecimentoDTO);

        $strTitulo = BaseConhecimentoINT::montarTitulo($objBaseConhecimentoDTO);

        $objEditorDTO = new EditorDTO();
        $objEditorDTO->setDblIdDocumento(null);
        $objEditorDTO->setNumIdConjuntoEstilos($objBaseConhecimentoDTO->getNumIdConjuntoEstilos());
        $objEditorDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());
        $objEditorDTO->setStrSinProcessandoEditor('S');
      } else {
        throw new InfraException('Montagem do editor não recebeu documento ou base de conhecimento.');
      }

      $objEditorRN = new EditorRN();
      $objEditorDTORetorno = $objEditorRN->montar($objEditorDTO);

      break;

    case 'editor_simular':

      $numIdModelo=$_GET['id_modelo'];

      $objModeloRN=new ModeloRN();
      $objModeloDTO=new ModeloDTO();
      $objModeloDTO->setNumIdModelo($numIdModelo);
      $objModeloDTO->retTodos();
      $objModeloDTO=$objModeloRN->consultar($objModeloDTO);

      if($objModeloDTO==null){
        throw new InfraException('Modelo não encontrado para simular.');
      }


      $strTitulo='Simulação do modelo ['.$objModeloDTO->getStrNome().']';
      $objEditorDTO = new EditorDTO();
      $objEditorDTO->setNumIdModelo($numIdModelo);

      $objEditorRN = new EditorRN();
      $objEditorDTORetorno = $objEditorRN->montarTesteModelo($objEditorDTO);
      break;

    case 'editor_salvar':


      if (count($_POST) == 0) {
        die("INFRA_VALIDACAO\nNão foi possível salvar o documento.");
      }

      $objEditorDTO = new EditorDTO();

      if (!InfraString::isBolVazia($_GET['id_documento'])) {
        $objEditorDTO->setDblIdDocumento($_GET['id_documento']);
        $objEditorDTO->setNumIdBaseConhecimento(null);
      } elseif (!InfraString::isBolVazia($_GET['id_base_conhecimento'])) {
        $objEditorDTO->setDblIdDocumento(null);
        $objEditorDTO->setNumIdBaseConhecimento($_GET['id_base_conhecimento']);
      }
      $objEditorDTO->setNumVersao($_POST['hdnVersao']);
      $objEditorDTO->setStrSinIgnorarNovaVersao($_POST['hdnIgnorarNovaVersao']);

      $arrObjSecaoDocumentoDTO = array();
      $numTamPrefixo = strlen('txaEditor_');
      foreach ($_POST as $chave => $valor) {
        if (strpos($chave, 'txaEditor_') === 0) {
          $objSecaoDocumentoDTO = new SecaoDocumentoDTO();
          $objSecaoDocumentoDTO->setNumIdSecaoModelo(substr($chave, $numTamPrefixo));
          $objSecaoDocumentoDTO->setStrConteudo($valor);
          $arrObjSecaoDocumentoDTO[] = $objSecaoDocumentoDTO;
        }
      }

      $objEditorDTO->setArrObjSecaoDocumentoDTO($arrObjSecaoDocumentoDTO);
      $objEditorDTO->setStrSinProcessandoEditor('S');

      try {
        $objEditorRN = new EditorRN();
        $numVersao = $objEditorRN->adicionarVersao($objEditorDTO);

        die('OK ' . $numVersao);
      } catch (Exception $e) {
        if ($e instanceof InfraException && $e->contemValidacoes()) {
          die("INFRA_VALIDACAO\n" . $e->__toString()); //retorna para o iframe exibir o alert
        }

        if (strpos($e->__toString(), 'COMPARACAO')===0){
          die($e->__toString());
        }

        PaginaSEI::getInstance()->processarExcecao($e); //vai para a página de erro padrão
      }

      break;


    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }
  $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
  $objTextoPadraoInternoDTO = new TextoPadraoInternoDTO();
  $objTextoPadraoInternoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  $objTextoPadraoInternoDTO->retNumIdTextoPadraoInterno();
  $objTextoPadraoInternoDTO->retStrNome();
  $objTextoPadraoInternoDTO->retStrConteudo();
  $objTextoPadraoInternoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

  $arrObjTextoPadraoInternoDTO = $objTextoPadraoInternoRN->listar($objTextoPadraoInternoDTO);
  $strItens = '[  ';
  $strTextos = '[  ';
  $strConcat = '';
  if (count($arrObjTextoPadraoInternoDTO) > 0) {
    foreach ($arrObjTextoPadraoInternoDTO as $objTextoPadraoInternoDTO) {
      $strItens .= $strConcat . '"' . base64_encode($objTextoPadraoInternoDTO->getStrNome()) . '"';
      $strTexto = base64_encode($objTextoPadraoInternoDTO->getStrConteudo());
      $strTextos .= $strConcat . "\"$strTexto\"";
      $strConcat = ', ';
    }
  }
  $strItens .= ']';
  $strTextos .= ']';

  $objImagemFormatoDTO = new ImagemFormatoDTO();
  $objImagemFormatoDTO->retStrFormato();
  $objImagemFormatoDTO->setBolExclusaoLogica(false);

  $objImagemFormatoRN = new ImagemFormatoRN();

  $arrImagemPermitida = InfraArray::converterArrInfraDTO($objImagemFormatoRN->listar($objImagemFormatoDTO), 'Formato');
  if (in_array('jpg', $arrImagemPermitida) && !in_array('jpeg', $arrImagemPermitida)) {
    $arrImagemPermitida[] = 'jpeg';
  }
  $strArrImgPermitida = "'" . implode('\',\'', $arrImagemPermitida) . "'";
  $strArrImgPermitida = 'var arrImgPermitida = Array(' . InfraString::transformarCaixaBaixa($strArrImgPermitida) . ');' . "\n";
} catch (Exception $e) {
  PaginaSEI::getInstance()->processarExcecao($e);
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
  <meta http-equiv="X-UA-Compatible" content="IE=8,9,10,11"/>
  <meta http-equiv="Pragma" content="no-cache"/>
  <meta name="robots" content="noindex"/>
  <meta http-equiv="Content-Type" content="text/html;"/>
  <meta name="format-detection" content="telephone=no"/>
  <title><?=$strTitulo?></title>
  <?
  PaginaSEI::getInstance()->montarStyle();
  PaginaSEI::getInstance()->montarJavaScript();
  ?>
  <style>
    <!-- /*--><![CDATA[/*><!--*/
    .cke_combo__styles .cke_combo_text {width: 230px !important;}
    .cke_button__save_label {display: inline !important;}
    .cke_button__autotexto_label {display: inline !important;}
    .cke_button__assinatura_label {display: inline !important;}
    .cke_combopanel__styles {width: 500px !important;}
    div.infraAreaDebug {
      overflow: auto;
      display: table;
      white-space: pre-wrap;
      font-size: 1em;
      width: 100%;
    }
    div.infraAviso {
      position: absolute;
      padding: .4em;
      border: .1em solid black;
      background-color: #dfdfdf;
      z-index: 999;
    }

    div.infraAviso span {
      /* font-family:Verdana, Arial, Helvetica, sans-serif;  */
      font-weight: bold;
      font-size: 1.2em;
    }

    div.infraFundoTransparente {
      z-index: 997;
      visibility: hidden;
      position: absolute;
      overflow: hidden;
      width: 1px;
      height: 1px;
      left: 0;
      top: 0;
      background: transparent url("/infra_css/imagens/fndtransp.gif") repeat;
    }
    div.editorSomenteLeitura {background: #e5e5e5;border-color: #696969;border-style: dotted solid;border-width: 1px;}
    #myDiv p {white-space: pre-wrap}

    <?=$objEditorDTORetorno->getStrCss();?>
    /*]]>*/-->
  </style>
  <?=$objEditorDTORetorno->getStrInicializacao()?>
  <?
  //if (PaginaSEI::getInstance()->getNumVersaoInternetExplorer()>0) {
  //  echo '<script type="text/javascript" src="'.ConfiguracaoSEI::getInstance()->getValor('SEI','URL').'/editor/ck/jquery-1.10.2.js"></script>';
  //}
  ?>
  <script type="text/javascript">//<![CDATA[

    if (document.documentMode == 7) {
      alert("Não é possível inicializar corretamente o editor.\nCerifique-se que o modo de compatibilidade esteja desativado.");
      window.CKEDITOR = undefined;
    }
    if(!document.getElementsByClassName){
      alert("Não é possível inicializar corretamente o editor.\nUtilize um navegador mais atualizado.");
      window.CKEDITOR = undefined;
    }
    <?
    //se houver validações, só exibe as validações e descarta o resto do javascript
    if ($objEditorDTORetorno->getStrValidacao() == true){
    ?>
    function  inicializar() {
      document.getElementById('frmEditor').hidden = false;
      document.getElementById('divCarregando').style.display = "none";
      if (INFRA_IOS) {
        document.getElementById('divEditores').style.overflow = 'scroll';
      }
      alert('<?=$objEditorDTORetorno->getStrMensagens();?>');
    }
    <?
    } else {
    echo $strArrImgPermitida;?>

    var _procedimento = '';
    var _idProtocolo = '';
    var _protocoloFormatado = '';
    var toolbar = <?=$objEditorDTORetorno->getStrToolbar()?>;
    var timeoutExibirBotao = null;
    var timer=0;
    var realceTimer=null;
    var timerAlertaSalvar=null;
    var arrAutotextoItens=<?echo $strTextos??'[]';?>;
    var selAutotextoItens=<?echo $strItens??'[]';?>;
    var strLinkAssinatura='<?=$strLinkAssinatura?>';
    var readOnlyColor='#e5e5e5';
    var modificado=false;
    var botoes=[];
    var timerVerificaAlteracao=null;

    objAjax = new infraAjaxComplementar(null, '<?=$strLinkAjaxProtocoloLinkEditor?>');
    objAjax.limparCampo = false;
    objAjax.mostrarAviso = false;
    objAjax.tempoAviso = 1000;
    objAjax.async = false;

    objAjax.prepararExecucao = function () {
      window._idProtocolo = '';
      window._protocoloFormatado = '';
      return 'idProtocoloDigitado=' + window._procedimento + "&idProcedimento=<?=$_GET["id_procedimento"];?>&idDocumento=<?=$_GET["id_documento"];?>";
    };

    objAjax.processarResultado = function (arr) {
      if (arr != null) {
        window._idProtocolo = arr['IdProtocolo'];
        window._protocoloFormatado = arr['ProtocoloFormatado'];
      }
    };

    CKEDITOR.config.contentsCss = "<?=str_replace('"', '\"', $objEditorDTORetorno->getStrCss())?>";
    verificarSalvamento=function(){};

<?if($_GET['acao']!=='editor_simular'){?>
    function plugin_save(evt) {
      CKEDITOR.plugins.registered['save'] = {
        init: function (editor) {
          var command = editor.addCommand('save', {
            modes: {wysiwyg: 1, source: 1},
            readOnly: 1,
            exec: function (editor) {
              if (editor.fire('save')) {
                var $form = editor.element.$.form;
                if (validarTags() && confirmarSalvamento()){
                  exibirAvisoEditor();
                  timeoutExibirBotao = self.setTimeout('exibirBotaoCancelarAviso()', 30000);

                  if ($form) {
                    try {
                      $form.submit();
                    } catch (e) {
                      if ($form.submit.click) {
                        $form.submit.click();
                      }
                    }
                  }
                }
              }
            }
          });
          editor.ui.addButton('Save', {label: 'Salvar', title: "Salvar", command: 'save'});
          editor.setKeystroke(CKEDITOR.CTRL + CKEDITOR.ALT + 83 /*S*/, 'save');
          editor.setKeystroke(CKEDITOR.CTRL + CKEDITOR.ALT + 65 /*A*/, 'assinatura');
          editor.setKeystroke(CKEDITOR.CTRL + CKEDITOR.SHIFT + 88 /*X*/, 'autotexto');
          editor.setKeystroke(CKEDITOR.CTRL + CKEDITOR.SHIFT + 76 /*L*/, 'linkseiDialog');
        }
      }
    }
    if (CKEDITOR.status != 'loaded') {
      console.log('carregamento lento do CK');
      CKEDITOR.on('loaded', plugin_save);
    } else {
      plugin_save(null);
    }

    function exibirBotaoCancelarAviso() {

      var div = document.getElementById('divInfraAvisoFundo');

      if (div != null && div.style.visibility == 'visible') {

        var botaoCancelar = document.getElementById('btnInfraAvisoCancelar');

        if (botaoCancelar != null) {
          botaoCancelar.style.display = 'block';
        }
      }
    }
    function exibirAvisoEditor() {

      infraExibirAviso(false, 'Salvando...');
      var divFundo = document.getElementById('divInfraAvisoFundo');

      if (INFRA_IE == 0 || INFRA_IE >= 7) {
        divFundo.style.position = 'fixed';
      }

      var divAviso = document.getElementById('divInfraAviso');

      divAviso.style.top = Math.floor(infraClientHeight() / 3) + 'px';
      divAviso.style.left = Math.floor((infraClientWidth() - 200) / 2) + 'px';
      divAviso.style.width = '200px';
      divAviso.style.border = '1px solid black';

      divFundo.style.width = screen.width * 2 + 'px';
      divFundo.style.height = screen.height * 2 + 'px';
      divFundo.style.visibility = 'visible';

    }
    var habilitaSalvar = function () {
      if (!modificado && timer == 0) {
        timer = setTimeout(function () {
          timer = 0;
          for (var inst in CKEDITOR.instances) {
            if (CKEDITOR.instances[inst].checkDirty()) {
              modificado = true;
              break;
            }
          }
          if (modificado) {
            botoes = document.getElementsByClassName('cke_button__save');
            if (timerAlertaSalvar == null) {
              timerAlertaSalvar = setTimeout(realcarBotao, 600000);//tempo para iniciar o realce do botão default 10min=600.000
            }
            for (var inst in CKEDITOR.instances) {
              CKEDITOR.instances[inst].getCommand('save').setState(CKEDITOR.TRISTATE_ENABLED);
            }

          }
        }, 100);
      }
    }

  var realcarBotao = function () {
    if (modificado)
      if (!realceTimer) {
        realceTimer = setInterval(function () {
          flashIt();
        }, 1000);//intervalo que alterna a cor - default = 1s
      }
  }
  var normalizarBotao = function () {
    if (realceTimer) {
      clearInterval(realceTimer);
        realceTimer = null;
      }
      for (var k = botoes.length - 1; k >= 0; k--) {
        var estilo = botoes[k].style;
        estilo.backgroundColor = "#efefde";
      }
    }

    var flashIt = function () {
      var corPadrao = "#efefde";
      var corRealce = "#ff2400";
      if (INFRA_IE == 0 || INFRA_IE > 8) {
        corPadrao = 'rgb(239, 239, 222)';
      }
      for (var k = botoes.length - 1; k >= 0; k--) {
        var estilo = botoes[k].style;
        estilo.backgroundColor = (estilo.backgroundColor == corPadrao) ? corRealce : corPadrao;
      }
    }

    var desabilitaSalvar = function () {
      modificado = false;
      if (timerAlertaSalvar != null) {
        clearTimeout(timerAlertaSalvar);
        timerAlertaSalvar = null;
      }
      normalizarBotao();
      for (var inst in CKEDITOR.instances) {
        CKEDITOR.instances[inst].getCommand('save').setState(CKEDITOR.TRISTATE_DISABLED);
        CKEDITOR.instances[inst].resetDirty();
      }

    };
    var confirmarSalvamento=function() {
      //ajax buscar dados dos módulos se precisa confirmar antes de salvar
      //retornando mensagem de texto
      var objAjaxAlteracao = new infraAjaxComplementar(null,'<?=$strLinkAjaxConfirmarAlteracao?>');
      objAjaxAlteracao.limparCampo = false;
      objAjaxAlteracao.mostrarAviso = false;
      objAjaxAlteracao.tempoAviso = 1000;
      objAjaxAlteracao.async=false;

      var retorno=true;
      objAjaxAlteracao.processarResultado = function (arr){
        if (arr!=null && arr['Confirmar']==='S'){
          retorno=confirm(arr['Mensagem']+"Deseja salvar o documento?");
    }
      };

      if (!objAjaxAlteracao.executar()){
        retorno = false;
      }

      return retorno;
    }
    var validarTags = function () {
      var inst,ImgSrc;
      for (inst in CKEDITOR.instances) {
        var editor = CKEDITOR.instances[inst];
        if (!editor.readOnly) {
          var tags = ['img', 'button', 'input', 'select', 'iframe', 'frame', 'embed', 'object', 'param', 'video', 'audio', 'form'];
          for (var i = 0; i < tags.length; i++) {
            var elements = editor.document.getElementsByTag(tags[i]);
            if (elements.count() > 0) {
              switch (tags[i]) {
                case 'img':
                  var erro = false;
                  if (arrImgPermitida.length == 0) {
                    alert('Não são permitidas imagens no conteúdo.');
                    erro = true;
                    break;
                  } else {
                    var posIni = null;
                    var posFim = null;
                    var n = elements.count();
                    for (var j = 0; j < n; j++) {
                      ImgSrc = elements.getItem(j).getAttribute('src');
                      if(ImgSrc!=null) {
                      posIni = ImgSrc.indexOf('/');
                      if (posIni != -1) {
                        posFim = ImgSrc.indexOf(';', posIni);
                        if (posFim != -1) {
                          posIni = posIni + 1;
                            if (arrImgPermitida.indexOf(ImgSrc.substring(posIni, posFim))== -1) {
                              alert('Imagem formato "' + ImgSrc.substring(posIni, posFim) + '" não permitida.');
                            erro = true;
                            break;
                          }
                        } else {
                          alert('Não são permitidas imagens referenciadas.');
                          erro = true;
                          break;
                          }
                        }
                      }
                    }
                  }
                  if (erro) break;
                  continue;
                case 'button':
                case 'input':
                case 'select':
                  alert('Não são permitidos componentes de formulário HTML no conteúdo.');
                  break;

                case 'iframe':
                  alert('Não são permitidos formulários ocultos no conteúdo.');
                  break;

                case 'frame':
                case 'form':
                  alert('Não são permitidos formulários no conteúdo.');
                  break;

                case 'embed':
                case 'object':
                case 'param':
                  alert('Não são permitidos objetos no conteúdo.');
                  break;

                case 'video':
                  alert('Não são permitidos vídeos no conteúdo.');
                  break;

                case 'audio':
                  alert('Não é permitido áudio no conteúdo.');
                  break;
              }

              editor.getSelection().selectElement(elements.getItem(0));
              document.getElementById('divEditores').scrollTop = editor.getSelection().getSelectedElement().$.offsetTop;
              var div = '<div id="divRealce" style="border:1px dashed red"><div>' + editor.getSelection().getSelectedElement().$.outerHTML + '</div></div>';
              editor.insertHtml(div);
              editor.focus();
              return false;
            }
          }
        }
      }
      return true;
    };
    verificarSalvamento=function(){
      var ie = infraVersaoIE();
      var docIframe;
      try {
        if (!ie) {
          docIframe = document.getElementById('ifrEditorSalvar').contentWindow.document;
        } else {
          docIframe = window.frames['ifrEditorSalvar'].document;
        }
      } catch (e) {
        infraOcultarAviso();
        alert('Não foi possível salvar o documento.');
        return;
      }

      var ret = $(docIframe.body).text();

      document.getElementById('hdnIgnorarNovaVersao').value = 'N';

      clearTimeout(timeoutExibirBotao);

      if (ret != '') {
        if (ret.substring(0, 2) != 'OK') {

          var prefixoComparacao = 'COMPARACAO';

          if (ret.substring(0,prefixoComparacao.length) == prefixoComparacao){

            if (confirm('Não foi possível salvar o documento porque ele apresenta conteúdo não permitido.\n\nClique OK para comparar o conteúdo atual com o permitido.')){
              infraAbrirJanela(ret.substring(prefixoComparacao.length+1).infraReplaceAll('&amp;','&'),'janelaComparacao',900,700,'location=0,status=1,resizable=1,scrollbars=1',false);
            }

          }else {

          var prefixoValidacao = 'INFRA_VALIDACAO';
            if (ret.substring(0, prefixoValidacao.length)==prefixoValidacao) {
            var msg = ret.substring(prefixoValidacao.length + 1);
            msg = msg.infraReplaceAll("\\n", "\n");
            msg = decodeURIComponent(msg);

            var prefixoNovaVersao = 'Existe uma nova versão';

            if (msg.substring(0, prefixoNovaVersao.length) == prefixoNovaVersao) {
              if (confirm(msg + "\n\n" + 'Ignorar as alterações e salvar o conteúdo atual como última versão?')) {
                document.getElementById('hdnIgnorarNovaVersao').value = 'S';
                for (inst in CKEDITOR.instances) {
                  CKEDITOR.instances[inst].execCommand('save');
                  return;
                }
              }
            } else {
              alert(msg);
            }
          } else {

            try {
              if (docIframe.getElementById('divInfraExcecao') == null) {
                alert('Erro desconhecido salvando documento: \n' + ret);
              } else {
                document.getElementById("ifrEditorSalvar").style.display = 'block';
                document.getElementById('frmEditor').style.display = 'none';
                resizeIframe();
                docIframe.getElementById('btnInfraFecharExcecao').value = 'Voltar';
                if (!ie) {
                  docIframe.getElementById('btnInfraFecharExcecao').innerHTML = 'Voltar';
                }
                docIframe.getElementById('btnInfraFecharExcecao').onclick = function () {
                  document.getElementById("ifrEditorSalvar").style.display = 'none';
                  document.getElementById('frmEditor').style.display = 'block';
                }
              }
            } catch (e) {
            }
          }
          }
        } else {

          document.getElementById('hdnVersao').value = ret.substring(3);

          var spn = null;
          for (var inst in CKEDITOR.instances) {
            if (CKEDITOR.instances[inst].config.dinamico) {
              spn = CKEDITOR.instances[inst].document.getById("spnVersao");
              if (spn != null) {
                if (spn.getHtml() == ret.substring(3)) {
                  alert('Nenhuma alteração foi encontrada no conteúdo do documento.');
                } else {
                  spn.setHtml(ret.substring(3));
                }
              }
              CKEDITOR.instances[inst].resetDirty();
            }
          }
          desabilitaSalvar();
          atualizarArvore();
        }

        infraOcultarAviso();

        if (window.bolAssinar) {
          infraAbrirJanelaModal(window.strLinkAssinatura,700,450,false);
          window.bolAssinar = false;
        }
        if (INFRA_IE) {
          window.status = 'Salvamento finalizado.';
        }
      }
    };
    function atualizarArvore(bolFechar) {
      if (window.opener) {
        var ifr = window.opener.parent.document.getElementById('ifrArvore')
        if (ifr) {
          var objArvore = ifr.contentWindow['objArvore'];
          if (objArvore && objArvore.getNo(<?=$_GET['id_procedimento']?>) != null) {
            var objNo = objArvore.getNoSelecionado();
            if (objNo && objNo.id == '<?=$_GET['id_documento']?>') {
              if (bolFechar) {
                ifr.contentWindow.location.reload();
              } else {
                var ifrHtml = window.opener.document.getElementById('ifrVisualizacao');
                if (ifrHtml) {
                  ifrHtml.contentWindow.location.reload();
                }
              }
            }
          }
        }
      }
      if(bolFechar){
        window.close();
    }
    }
    window.onbeforeunload = function (evt) {
      if (modificado) {
        return 'Existem alterações que não foram salvas.';
      }
    };
<? } ?>
    CKEDITOR.config.zoom = infraLerCookie('<?=PaginaSEI::getInstance()->getStrPrefixoCookie()?>_zoom_editor');
    if (CKEDITOR.config.zoom==null) CKEDITOR.config.zoom=100;
    CKEDITOR.on('instanceReady', function( evt ) {
      redimensionar();

      evt.editor.on("paste", function(ev){

        if(ev.data.dataValue.indexOf('#')===0 && pasteLinkEditor(ev.editor,ev.data.dataValue)){
          ev.cancel();
        }

        var remove=function(){return false;};
        var substitui=function(element){element.replaceWithChildren();};
        var allowedContent = {
          // Use the ability to specify elements as an object.
          elements: {
            'button':remove,
            'input':remove,
            'select':remove,
            'iframe':remove,
            'frame':remove,
            'embed':remove,
            'param':remove,
            'form':substitui,
            'script':remove,
            'object':remove,
            'video':remove,
            'audio':remove,
            'link':remove,
            'font':substitui,
            'o:p':substitui,
            'div':function(element){
              if(element.attributes.hasOwnProperty('unselectable') && element.attributes.unselectable=='on'){
                return false;
              }
            },
            'b':function(element){
              for (var a in element.attributes){
                delete element.attributes.a;
              }
            }
          },
          attributeNames: [
            [ ( /^(?:cke-data-pa)?on\w*/ ), '' ],
            [ ( /^lang/ ), '' ]
          ],
          attributes: {
            'style':function(attribute){
              var estilos=attribute.split(';');
              for (var chave in estilos) {
                if(/counter-\w*\s*:/.test(estilos[chave])){
                  estilos.splice(chave,1);
                }
              }
              return estilos.join(';');
            }
          },
          styles: true,
          classes: true
        };

        var fragment = CKEDITOR.htmlParser.fragment.fromHtml( ev.data.dataValue );
        var filter = new CKEDITOR.htmlParser.filter(allowedContent);
        var writer = new CKEDITOR.htmlParser.basicWriter();
        filter.applyTo(fragment);
        fragment.writeHtml(writer);
        ev.data.dataValue=writer.getHtml();

        if(CKEDITOR.env.chrome){
          ev.data.dataValue = ev.data.dataValue.replace(/(<span)(><a[^>]*href="[^>]*controlador\.php\?acao=protocolo_visualizar(?:&|&amp;)id_protocolo=)(\d+)([^>]*>)(\d{7})(<\/a><\/span>)/gi, '$1 contenteditable="false" style="text-indent:0;"><a class="ancora_sei" id="lnkSei$3" style="text-indent:0;">$5$6');
        }
        ev.data.dataValue = ev.data.dataValue.replace(/(<table[^<>]*border=)("0"|0)([^<>]*>)/gi, '$1"1"$3');
        ev.data.dataValue = ev.data.dataValue.replace(/(<a[^<>]*)(target="[^<>]*")([^<>]*>)/gi, "$1$3");
        ev.data.dataValue = ev.data.dataValue.replace(/(<a[^<>]*)[^<>]*(>)/gi, '$1 target="_blank"$2');
        if (ev.data.type!='text'){
          ev.data.dataValue = ev.data.dataValue.replace(/&nbsp;/gi,' ');
        }
      });

      <?if($_GET['acao']!=='editor_simular'){?>
      evt.editor.getCommand('save').setState(CKEDITOR.TRISTATE_DISABLED);
      <?}?>
      if (evt.editor.readOnly==true) {
        evt.editor.document.$.body.style.backgroundColor=readOnlyColor;
      }

    });

    // CKEDITOR.config.disableNativeSpellChecker = true;



    var pasteLinkEditor=function(editor,data) {
      var regex = /^#([^{}<>\\]*){(\d+)\|([^{}<>()&;\s\\]+)}([^#<>\\]*)#$/;//linksei
      //link_federacao
      var regexfed = /^#([^{}<>\\]*)([pd]){([0-9A-HJKMNP-TV-Z]{26})\|([0-9A-HJKMNP-TV-Z]{26})\|([0-9A-HJKMNP-TV-Z]{26}?)\|([^{}<>()&;\s\\]+)}([^#<>\\]*)#$/;
      var span = editor.document.createElement('span'),
          link = editor.document.createElement('a');
      span.setAttributes({contentEditable: "false", 'data-cke-linksei': 1, 'style': "text-indent:0px;"});

      var m,ft,fi,fp,fd;
      if ((m = regex.exec(data))!==null) {
        // The result can be accessed through the `m`-variable.

        link.setAttributes({
          'id': 'lnkSei' + m[2],
          'class': "ancora_sei",
          'style': "text-indent:0px;"
        });
        link.setHtml(m[3]);
        span.append(link);
        editor.insertText(m[1]);
        editor.insertElement(span);
        var range = editor.getSelection().getRanges()[0];
        range.collapse();
        range.moveToPosition( span, CKEDITOR.POSITION_AFTER_END );
        // editor.getSelection().selectRanges( [ range ] );
        editor.insertText(m[4]);

        return true;
      } else
        if ((m = regexfed.exec(data))!==null) {
          ft=m[2];
          fi=m[3];
          fp=m[4];
          fd=m[5];
          link.setAttributes({
            'data-ft': ft,
            'data-fi': fi,
            'data-fp': fp,
            'class': "ancora_sei",
            'style': "text-indent:0px;"
          });
          if(ft=='d'){
            link.setAttribute('data-fd',fd);
          } else if (ft=='p'){
            if(fd!=''){
              link.setAttributes({'data-ft':'a','data-fa':fd})
            }
          } else return false;

          link.setHtml(m[6]);
          span.append(link);
          editor.insertText(m[1]);
          editor.insertElement(span);
          editor.insertText(m[7]);
          return true;
        }
      return false;
    }


    function  inicializar(){
      if (INFRA_IOS) {
        document.getElementById('divEditores').style.overflow='scroll';
      }

      <?if ($_GET['acao_origem']== 'arvore_visualizar' && $objEditorDTORetorno->getStrSinAlterouVersao()=='S'){?>
        if (window.opener.parent.document.getElementById('ifrArvore')!=null){
          window.opener.parent.document.getElementById('ifrArvore').src = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].$strParametros.'&montar_visualizacao=1')?>';
        }
      <?}?>

      infraAdicionarEvento(window,'resize',redimensionar);
      document.getElementById('frmEditor').hidden=false;
      document.getElementById('divCarregando').style.display="none";
      redimensionar();
      for (var inst in CKEDITOR.instances) {
        if (CKEDITOR.instances[inst].status=='ready') {
          CKEDITOR.instances[inst].execCommand('autogrow');
        }
      }
      redimensionar();
      timerVerificaAlteracao=setInterval(habilitaSalvar,1000);
    }

    function redimensionar() {
      setTimeout(function(){

        var tamComandos=document.getElementById('divComandos').offsetHeight;
        var divEd=document.getElementById('divEditores');
        if (tamComandos>divEd.offsetHeight) tamComandos-=divEd.offsetHeight;
        var tamEditor=infraClientHeight()- tamComandos - 20;
        divEd.style.height = (tamEditor>0?tamEditor:1) +'px';
      },0);
    }


    var resizeIframe = function(){
      document.getElementById("ifrEditorSalvar").style.height = (infraClientHeight()-30) + 'px';
    }

    window.onresize = resizeIframe;
    <?}?>
    //]]></script>
</head>
<body onload="inicializar();" style="margin: 5px;overflow: hidden">
<div id='divCarregando'><h2>Carregando...</h2></div>
<form id="frmEditor" hidden style="margin: 0;" method="post" target="ifrEditorSalvar" action="<?=SessaoSEI::getInstance()->assinarLink('editor/editor_processar.php?acao=editor_salvar&acao_origem='.$_GET['acao'].$strParametros)?>">
  <div id="divComandos" style="margin:0;"></div>
  <?
  if (PaginaSEI::getInstance()->getNumTipoBrowser()==InfraPagina::$TIPO_BROWSER_IE7 ) echo '<br style="margin:0;font-size:1px;"/>';
  ?>
  <div id="divEditores" style="overflow: auto;border-top:2px solid;border-bottom:0;">

    <?=$objEditorDTORetorno->getStrTextareas();?>
    <script type="text/javascript">
      <?=$objEditorDTORetorno->getStrEditores()?>
    </script>
  </div>
  <input type="hidden" id="hdnVersao" name="hdnVersao" value="<?=$objEditorDTORetorno->getNumVersao()?>"/>
  <input type="hidden" id="hdnIgnorarNovaVersao" name="hdnIgnorarNovaVersao" value="N"/>
  <input type="hidden" id="hdnSiglaUnidade" name="hdnSiglaUnidade" value="<?=SessaoSEI::getInstance()->getStrSiglaUnidadeAtual()?>"/>
  <?
  PaginaSEI::getInstance()->montarAreaDebug();
  ?>
  <input type="hidden" id="hdnInfraPrefixoCookie" name="hdnInfraPrefixoCookie" value="<?=PaginaSEI::getInstance()->getStrPrefixoCookie()?>"/>
</form>
<iframe id="ifrEditorSalvar" name="ifrEditorSalvar" onload="verificarSalvamento();" border="0" width="100%" height="100%" style="display:none;"></iframe>
</body>
</html>
