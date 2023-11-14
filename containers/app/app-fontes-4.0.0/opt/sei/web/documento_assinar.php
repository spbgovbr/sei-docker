<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/09/2008 - criado por marcio_db
*
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

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'id_procedimento', 'id_documento', 'id_bloco', 'sta_estado', 'nao_assinados'));
  
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
	
  //PaginaSEI::getInstance()->salvarCamposPost(array('selCargoFuncao'));
  
  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  
  $bolAssinaturaOK = false;
  $bolPermiteAssinaturaLogin=false;
  $bolPermiteAssinaturaCertificado=false;
  $bolAutenticacao = false;
  $strCodigoAssinatura = '';
  $strLinkVerificacaoAssinatura = '';
  $arrIdBlocos=array();

  switch($_GET['acao']){
    
    case 'documento_assinar':

      $objInfraParametro=new InfraParametro(BancoSEI::getInstance());
      $tipoAssinatura=$objInfraParametro->getValor('SEI_TIPO_ASSINATURA_INTERNA');

      $strTitulo = 'Assinatura de Documento';            
      if ($_GET['acao_origem']=='bloco_assinatura_listar'){

        $arrIdDocumentos = array();
        $arrIdBlocosOrigem = PaginaSEI::getInstance()->getArrStrItensSelecionados();

        $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();

        foreach($arrIdBlocosOrigem as $numIdBloco){
          $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
          $objRelBlocoProtocoloDTO->setNumIdBloco($numIdBloco);
          $objRelBlocoProtocoloDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

          $arrIdDocumentos = array_merge($arrIdDocumentos, InfraArray::converterArrInfraDTO($objRelBlocoProtocoloRN->listarProtocolosBloco($objRelBlocoProtocoloDTO),'IdProtocolo'));
        }

        $arrIdDocumentos = array_unique($arrIdDocumentos);

        $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&'.PaginaSEI::getParametroRandom().PaginaSEI::montarAncora($arrIdBlocosOrigem));

      }else if ($_GET['acao_origem']=='rel_bloco_protocolo_listar'){
        
        $arrIdDocumentos = array();
        $arrIdBlocos=array();
        $arrIdDocumentoBloco = PaginaSEI::getInstance()->getArrStrItensSelecionados();

        foreach($arrIdDocumentoBloco as $idDocumentoBloco){
          $arrTemp = explode('-',$idDocumentoBloco);
          $arrIdDocumentos[] = $arrTemp[0];
          $arrIdBlocos[]=$arrTemp[1];
        }

        $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&'.PaginaSEI::getParametroRandom().PaginaSEI::montarAncora($arrIdDocumentoBloco));

      }else if ($_GET['acao_origem']=='bloco_navegar'){

        $arrIdDocumentos = array($_GET['id_documento']);
        $arrIdBlocos=array($_GET['id_bloco']);

      }else if ($_GET['acao_origem']=='arvore_visualizar' || $_GET['acao_origem']=='editor_montar'){

        $arrIdDocumentos = array($_GET['id_documento']);

      }else if ($_GET['acao_origem']!='documento_assinar'){

        if (isset($_GET['id_documento'])){
          $arrIdDocumentos = array($_GET['id_documento']);
        }else{
          $arrIdDocumentos = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        }

        $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::montarAncora($arrIdDocumentos));

      }else{

        if (!isset($_POST['hdnIdDocumentos'])){
          throw new InfraException('Nenhum documento informado.');
        }

        if ($_GET['hash_documentos'] != md5($_POST['hdnIdDocumentos'])){
          throw new InfraException('Conjunto de documentos inválido.');
        }

        $arrIdDocumentos = explode(',',$_POST['hdnIdDocumentos']);
        $arrIdBlocos = explode(',',$_POST['hdnIdBlocos']);

        $strLinkRetorno = $_POST['hdnLinkRetorno'];

      }

      $numRegistros = InfraArray::contar($arrIdDocumentos);

      if ($numRegistros==1){
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->retStrStaDocumento();
        $objDocumentoDTO->retNumIdTipoConferencia();
        $objDocumentoDTO->setDblIdDocumento($arrIdDocumentos[0]);

        $objDocumentoRN = new DocumentoRN();
        $objDocumentoDTO = $objDocumentoRN->consultarRN0005($objDocumentoDTO);

        if ($objDocumentoDTO!=null && $objDocumentoDTO->getStrStaDocumento()==DocumentoRN::$TD_EXTERNO){
          $strTitulo = 'Autenticação de Documento';
          $tipoAssinatura=$objInfraParametro->getValor('SEI_TIPO_AUTENTICACAO_INTERNA');
          $bolAutenticacao = true;
        }
      }

      switch ($tipoAssinatura){
        case 1:
          $bolPermiteAssinaturaCertificado=true;
          $bolPermiteAssinaturaLogin=true;
          break;
        case 2:
          $bolPermiteAssinaturaLogin=true;
          break;
        case 3:
          $bolPermiteAssinaturaCertificado=true;
      }

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->setStrStaFormaAutenticacao($_POST['hdnFormaAutenticacao']);
      
      if (!isset($_POST['hdnFlagAssinatura'])){
        $objAssinaturaDTO->setNumIdOrgaoUsuario(SessaoSEI::getInstance()->getNumIdOrgaoUsuario());
      }else{
        $objAssinaturaDTO->setNumIdOrgaoUsuario($_POST['selOrgao']);
      }

      $objAssinaturaDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
      $objAssinaturaDTO->setStrSenhaUsuario($_POST['pwdSenha']);
      
      //$objAssinaturaDTO->setStrCargoFuncao(PaginaSEI::getInstance()->recuperarCampo('selCargoFuncao'));
      
      $objInfraDadoUsuario = new InfraDadoUsuario(SessaoSEI::getInstance());

      $strChaveDadoUsuarioAssinatura = 'ASSINATURA_CARGO_FUNCAO_'.SessaoSEI::getInstance()->getNumIdUnidadeAtual();

      if (!isset($_POST['selCargoFuncao'])){
        $objAssinaturaDTO->setStrCargoFuncao($objInfraDadoUsuario->getValor($strChaveDadoUsuarioAssinatura));
      }else{
        $objAssinaturaDTO->setStrCargoFuncao($_POST['selCargoFuncao']);

        if ($objAssinaturaDTO->getNumIdUsuario()==SessaoSEI::getInstance()->getNumIdUsuario()) {
          $objInfraDadoUsuario->setValor($strChaveDadoUsuarioAssinatura, $_POST['selCargoFuncao']);
        }
      }

      if ($_POST['hdnFormaAutenticacao'] != null){

        if($_POST['hdnFormaAutenticacao']==AssinaturaRN::$TA_CERTIFICADO_DIGITAL && !$bolPermiteAssinaturaCertificado){
          throw new InfraException('Assinatura por Certificado Digital não permitida.');
        } else if($_POST['hdnFormaAutenticacao']==AssinaturaRN::$TA_SENHA && !$bolPermiteAssinaturaLogin){
          throw new InfraException('Assinatura por login não permitida.');
        }

        if (count($arrIdBlocos)>0){
          $i=count($arrIdDocumentos);
          $arrObjDocumentoDTO=array();
          for($j=0;$j<$i;$j++){
            $objDocumentoDTO=new DocumentoDTO();
            $objDocumentoDTO->setDblIdDocumento($arrIdDocumentos[$j]);
            $objDocumentoDTO->setNumIdBloco($arrIdBlocos[$j]);
            $arrObjDocumentoDTO[]=$objDocumentoDTO;
          }
          $objAssinaturaDTO->setArrObjDocumentoDTO($arrObjDocumentoDTO);
        } else {
          $objAssinaturaDTO->setArrObjDocumentoDTO(InfraArray::gerarArrInfraDTO('DocumentoDTO','IdDocumento',$arrIdDocumentos));
        }


        try{

          $objDocumentoRN = new DocumentoRN();
          $arrObjAssinaturaDTO = $objDocumentoRN->assinar($objAssinaturaDTO);

          if($_POST['hdnFormaAutenticacao']==AssinaturaRN::$TA_CERTIFICADO_DIGITAL) {
            $strCodigoAssinatura = base64_encode(ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL').'/controlador_ws.php?servico=assinador|'.$arrObjAssinaturaDTO[0]->getStrAgrupador());
            $strLinkVerificacaoAssinatura = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assinatura_verificar_confirmacao&agrupador=' . $arrObjAssinaturaDTO[0]->getStrAgrupador());
          }

          $bolAssinaturaOK = true;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e, true);
        }
      }
      
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  

  if ($numRegistros) {
    if ($bolPermiteAssinaturaCertificado && $objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_CERTIFICADO_DIGITAL){
      $arrComandos[] = '<button type="button" accesskey="A" onclick="assinarCertificadoDigital();" id="btnAssinar" name="btnAssinar" value="Assinar" class="infraButton" style="visibility:hidden">&nbsp;<span class="infraTeclaAtalho">A</span>ssinar&nbsp;</button>';
    }else if ($bolPermiteAssinaturaLogin ) {
      $arrComandos[] = '<button type="button" accesskey="A" onclick="assinarSenha();" id="btnAssinar" name="btnAssinar" value="Assinar" class="infraButton">&nbsp;<span class="infraTeclaAtalho">A</span>ssinar&nbsp;</button>';
    }
  }

  if (!isset($_POST['hdnIdUsuario'])){
    $strIdUsuario = SessaoSEI::getInstance()->getNumIdUsuario();
    $strNomeUsuario = SessaoSEI::getInstance()->getStrNomeUsuario();
  }else{
    $strIdUsuario = $_POST['hdnIdUsuario'];
    $strNomeUsuario = $_POST['txtUsuario'];
  }

  $strDisplayIdentificacao = '';
  $strDisplayAutenticacao = '';
  if ($bolAssinaturaOK){
    if ($objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_CERTIFICADO_DIGITAL){
      $strDisplayIdentificacao = 'display:none';
    }
    $strDisplayAutenticacao = 'display:none;';
  }

  $strDisplayCodigo = '';
  if ($strCodigoAssinatura==''){
    $strDisplayCodigo = 'display:none';
  }

  $strLinkAjaxUsuarios = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_assinatura_auto_completar');
  $strItensSelOrgaos = OrgaoINT::montarSelectSiglaRI1358('null','&nbsp;',$objAssinaturaDTO->getNumIdOrgaoUsuario());
  $strItensSelCargoFuncao = AssinanteINT::montarSelectCargoFuncaoUnidadeUsuarioRI1344('null','&nbsp;', $objAssinaturaDTO->getStrCargoFuncao(), $strIdUsuario);
  $strLinkAjaxCargoFuncao = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assinante_carregar_cargo_funcao');

  $strIdDocumentos = implode(',',$arrIdDocumentos);
  $strHashDocumentos = md5($strIdDocumentos);
  $strIdBlocos = implode(',',$arrIdBlocos);

  $strDisplayDadosAssinante = '';
  if ($bolAssinaturaOK && $objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_CERTIFICADO_DIGITAL){
    $strDisplayDadosAssinante = 'display:none';
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

#divIdentificacao {<?=$strDisplayIdentificacao?>}

#lblOrgao {position:absolute;left:0%;top:0%;}
#selOrgao {position:absolute;left:0%;top:38%;width:40%;}

#divUsuario {}
#lblUsuario {position:absolute;left:0%;top:0%;}
#txtUsuario {position:absolute;left:0%;top:38%;width:99%;}

#divAutenticacao {<?=$strDisplayAutenticacao?>}
#pwdSenha {width:25%;}

#lblCargoFuncao {position:absolute;left:0%;top:0%;}
#selCargoFuncao {position:absolute;left:0%;top:38%;width:99%;}

#lblOu {<?=((PaginaSEI::getInstance()->isBolIpad() || PaginaSEI::getInstance()->isBolAndroid())?'visibility:hidden;':'')?>}
#lblCertificadoDigital {<?=((PaginaSEI::getInstance()->isBolIpad() || PaginaSEI::getInstance()->isBolAndroid())?'visibility:hidden;':'')?>}

#divCodigo {<?=$strDisplayCodigo?>}
#lblCodigo, #lblCodigo span  {font-size:1rem}

#ancAjuda:hover {
  text-decoration:none;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->adicionarJavaScript('js/clipboard/clipboard.min.js');
PaginaSEI::getInstance()->abrirJavaScript();
?>

//<script>

var objAutoCompletarUsuario = null;
var objAjaxCargoFuncao = null;
var objAjaxVerificacaoCertificado = null;
var intervaloVerificacao = null;
var bolAssinandoSenha = false;
var timer = null;

$(document).ready(function(){
  new MaskedPassword(document.getElementById("pwdSenha"), '\u25CF');
});

function inicializar(){

  <?if ($numRegistros==0){?>
    alert('Nenhum documento informado.');
    return;
  <?}?>

  //se realizou assinatura
  <?if ($bolAssinaturaOK){ ?>

    <?if ($objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_CERTIFICADO_DIGITAL) {?>

      objAjaxVerificacaoCertificado = new infraAjaxComplementar(null,'<?=$strLinkVerificacaoAssinatura?>');
      objAjaxVerificacaoCertificado.prepararExecucao = function(){
        return null;
      };
      objAjaxVerificacaoCertificado.processarResultado = function(arr){
        if (arr!=null){
          if (arr['assinaturaConfirmada'] == 'S' || timer > 300){
            clearInterval(intervaloVerificacao);
            finalizar();
          }
          timer += 3;
        }
      };

      var clipboard = new Clipboard('.clipboard', {
          text: function (trigger) {
            return '<?=$strCodigoAssinatura?>';
          }
        });

        clipboard.on('success', function (e) {

          verificarConfirmacaoAssinatura();

          var btnCopiarCodigo = document.getElementById('btnCopiarCodigo');

          if (btnCopiarCodigo != null) {

            p = infraObterPosicao(btnCopiarCodigo);

            var div = document.getElementById('divMsgClipboard');
            var criou = false;

            if (div==null) {
              var div = document.createElement("div");
              div.id = 'divMsgClipboard';
              criou = true;
            }
            div.className = 'msgGeral msgSucesso';
            div.innerHTML = 'Dados disponibilizados';
            div.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
            div.style.textAlign = 'center';


            div.style.top = (p.y + 45) + 'px';
            div.style.left = p.x + 'px';
            div.style.width = '180px';

            if (criou) {
              document.body.appendChild(div);
            }

            $("#divMsgClipboard").fadeIn(300).delay(1500).fadeOut(400);
          }

          e.clearSelection();
        });

        clipboard.on('error', function (e) {
          alert('Não foi possível copiar os dados de assinatura para a Área de Transferência.');
        });

    <?}else{?>
       finalizar();
    <?}?>

    return;

  <?}else{?>
  
    if (document.getElementById('selCargoFuncao').options.length==2){
      document.getElementById('selCargoFuncao').options[1].selected = true;
    }

    objAjaxCargoFuncao = new infraAjaxMontarSelect('selCargoFuncao','<?=$strLinkAjaxCargoFuncao?>');
    //objAjaxCargoFuncao.mostrarAviso = true;
    //objAjaxCargoFuncao.tempoAviso = 2000;
    objAjaxCargoFuncao.prepararExecucao = function(){

      if (document.getElementById('hdnIdUsuario').value==''){
        return false;
      }

      return 'id_usuario=' + document.getElementById('hdnIdUsuario').value;
    };

    objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuarios?>');
    //objAutoCompletarUsuario.maiusculas = true;
    //objAutoCompletarUsuario.mostrarAviso = true;
    //objAutoCompletarUsuario.tempoAviso = 1000;
    //objAutoCompletarUsuario.tamanhoMinimo = 3;
    objAutoCompletarUsuario.limparCampo = true;
    //objAutoCompletarUsuario.bolExecucaoAutomatica = false;

    objAutoCompletarUsuario.prepararExecucao = function(){

      if (!infraSelectSelecionado(document.getElementById('selOrgao'))){
        alert('Selecione um Órgão.');
        document.getElementById('selOrgao').focus();
        return false;
      }

      return 'id_orgao=' + document.getElementById('selOrgao').value + '&palavras_pesquisa='+document.getElementById('txtUsuario').value + '&inativos=0';
    };

    objAutoCompletarUsuario.processarResultado = function(id,descricao,complemento){
      if (id!=''){
        document.getElementById('hdnIdUsuario').value = id;
        document.getElementById('txtUsuario').value = descricao;
        objAjaxCargoFuncao.executar();
        window.status='Finalizado.';
      }
    };

    //infraSelecionarCampo(document.getElementById('txtUsuario'));

    <? if($bolPermiteAssinaturaLogin) { ?>
      self.setTimeout('document.getElementById(\'pwdSenha\').focus()',100);
    <?}?>

  <?}?>
}

function OnSubmitForm() {

  if (!infraSelectSelecionado(document.getElementById('selOrgao'))){
    alert('Selecione um Órgão.');
    document.getElementById('selOrgao').focus();
    return false;
  }

  if (infraTrim(document.getElementById('hdnIdUsuario').value)==''){
    alert('Informe um Assinante.');
    document.getElementById('txtUsuario').focus();
    return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selCargoFuncao'))){
    alert('Selecione um Cargo/Função.');
    document.getElementById('selCargoFuncao').focus();
    return false;
  }
  
  if ('<?=$numRegistros?>'=='0'){
    alert('Nenhum documento informado para assinatura.');
    return false;
  }

  return true;
}

function trocarOrgaoUsuario(){
  objAutoCompletarUsuario.limpar();
  objAjaxCargoFuncao.executar();
}

<? if($bolPermiteAssinaturaLogin) { ?>
  function assinarSenha(){
    if (infraTrim(document.getElementById('pwdSenha').value)==''){
      alert('Senha não informada.');
      document.getElementById('pwdSenha').focus();
    }else{
      document.getElementById('hdnFormaAutenticacao').value = '<?=AssinaturaRN::$TA_SENHA?>';
      if (OnSubmitForm()){
        infraExibirAviso(false);
        document.getElementById('frmAssinaturas').submit();
        return true;
      }
    }
    return false;
  }

  function tratarSenha(ev){
    if (!bolAssinandoSenha && infraGetCodigoTecla(ev)==13){
      bolAssinandoSenha = true;
      if (!assinarSenha()){
        bolAssinandoSenha = false;
      }
    }
  }
<? } ?>

<? if($bolPermiteAssinaturaCertificado) { ?>
  function assinarCertificadoDigital(){
    document.getElementById('hdnFormaAutenticacao').value = '<?=AssinaturaRN::$TA_CERTIFICADO_DIGITAL?>';
    if (OnSubmitForm()) {
      infraExibirAviso(false);
      document.getElementById('frmAssinaturas').submit();
    }
  }


<? } ?>

function finalizar(){
  //se realizou assinatura
  <?if ($bolAssinaturaOK){ ?>

    <? if ($_GET['arvore'] == '1'){ ?>

      //atualiza árvore para mostrar caneta de assinatura
      parent.document.getElementById('ifrArvore').src = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&montar_visualizacao=1')?>';
      infraFecharJanelaModal();

    <? }else{ ?>


      <?if($_GET['acao_retorno'] == 'bloco_navegar'){?>
        window.parent.processarDocumento(window.parent.posAtual);
        window.parent.objAjaxAssinaturas.executar();
      <?}else if($_GET['acao_retorno'] == 'editor_montar'){?>
        window.parent.atualizarArvore(true);
      <?} else {?>
        window.parent.location = '<?=$strLinkRetorno?>';
      <?}?>

         infraFecharJanelaModal();

    <?}?>
  <?}?>
}

<?if ($bolAssinaturaOK && $objAssinaturaDTO->getStrStaFormaAutenticacao() == AssinaturaRN::$TA_CERTIFICADO_DIGITAL){ ?>

function verificarConfirmacaoAssinatura(){
  if (timer != null){
    timer = 1;
  }else {
    timer = 1;
    intervaloVerificacao = setInterval(function () {objAjaxVerificacaoCertificado.executar();}, 3000);
  }
}


<?}?>


//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>

<form id="frmAssinaturas" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&acao_retorno='.PaginaSEI::getInstance()->getAcaoRetorno().'&hash_documentos='.$strHashDocumentos)?>">
  
	<?
	//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
	PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
	//PaginaSEI::getInstance()->montarAreaValidacao();
	if ($numRegistros > 0){
  ?>

    <div id="divIdentificacao">
      <div id="divOrgao" class="infraAreaDados" style="height:5em;">
        <label id="lblOrgao" for="selOrgao" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão do Assinante:</label>
        <select id="selOrgao" name="selOrgao" onchange="trocarOrgaoUsuario();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensSelOrgaos?>
        </select>
      </div>

      <div id="divUsuario" class="infraAreaDados" style="height:5em;">
        <label id="lblUsuario" for="txtUsuario" accesskey="e" class="infraLabelObrigatorio">Assinant<span class="infraTeclaAtalho">e</span>:</label>
        <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" value="<?=PaginaSEI::tratarHTML($strNomeUsuario)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$strIdUsuario?>" />
      </div>

      <div id="divCargoFuncao" class="infraAreaDados" style="height:5em;">
        <label id="lblCargoFuncao" for="selCargoFuncao" accesskey="F" class="infraLabelObrigatorio">Cargo / <span class="infraTeclaAtalho">F</span>unção:</label>
        <select id="selCargoFuncao" name="selCargoFuncao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensSelCargoFuncao?>
        </select>
      </div>
      <br />
      <div id="divAutenticacao" class="infraAreaDados" style="height:3em;">
        <? if($bolPermiteAssinaturaLogin) { ?>
          <label id="lblSenha" for="pwdSenha" accesskey="S" class="infraLabelRadio infraLabelObrigatorio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><span class="infraTeclaAtalho">S</span>enha</label>&nbsp;&nbsp;
          <?=InfraINT::montarInputPassword('pwdSenha', '', 'onkeypress="return tratarSenha(event);" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"')?>&nbsp;&nbsp;&nbsp;&nbsp;
        <? }
           if($bolPermiteAssinaturaLogin && $bolPermiteAssinaturaCertificado) { ?>
          <label id="lblOu" class="infraLabelOpcional" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">ou</label>&nbsp;&nbsp;&nbsp;
        <? }
           if($bolPermiteAssinaturaCertificado) { ?>
          <label id="lblCertificadoDigital" onclick="assinarCertificadoDigital();" accesskey="" for="optCertificadoDigital" class="infraLabelRadio infraLabelObrigatorio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=((!$bolPermiteAssinaturaLogin)?(!$bolAutenticacao?'Assinar com ':'Autenticar com '):'')?>Certificado Digital</label>&nbsp;
        <? } ?>
      </div>
    </div>

    <div id="divCodigo" class="infraAreaDados">
      <label id="lblCodigo" class="infraLabelOpcional">Para prosseguir disponibilize os dados de assinatura e execute o programa <span style="font-weight:bold">Assinador de Documentos com Certificado Digital do SEI</span>.</label>
      <br>
      <br>
      <button type="button" id="btnCopiarCodigo" name="btnCopiarCodigo" value="Copiar" class="infraButton clipboard">Disponibilizar dados para o assinador</button>
      &nbsp;&nbsp;
      <button type="button" id="btnAjuda" class="infraButton">
      <a id="ancAjuda" href="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assinatura_digital_ajuda&acao_origem='.$_GET['acao'])?>" target="_blank" title="Ajuda">Ajuda</a>
      </button>

    </div>

    <?
	}
	  //PaginaSEI::getInstance()->fecharAreaDados();
	PaginaSEI::getInstance()->montarAreaDebug();
	//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  <input type="hidden" id="hdnFormaAutenticacao" name="hdnFormaAutenticacao" value="" />
  <input type="hidden" id="hdnLinkRetorno" name="hdnLinkRetorno" value="<?=$strLinkRetorno?>" />
  <input type="hidden" id="hdnFlagAssinatura" name="hdnFlagAssinatura" value="1" />
  <input type="hidden" id="hdnIdDocumentos" name="hdnIdDocumentos" value="<?=$strIdDocumentos?>" />
  <input type="hidden" id="hdnIdBlocos" name="hdnIdBlocos" value="<?=$strIdBlocos?>" />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>