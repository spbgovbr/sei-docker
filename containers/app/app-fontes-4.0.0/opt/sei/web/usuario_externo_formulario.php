<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/04/2012 - criado por bcu
* 06/06/2018 - cjy - adicao dos campos numero_passaporte e id_pais_passaporte
 * 13/06/2018 - cjy - adicao dos campos pais e estado e cidade estrangeiros
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  $strDominio = "usuario_externo";
  SeiINT::definirIdioma($strDominio,$arrIdiomas,$locale);

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEIExterna::getInstance()->validarLink();

  $numTamSenhaUsuarioExterno = ConfiguracaoSEI::getInstance()->getValor('SEI', 'TamSenhaUsuarioExterno', false, TAM_SENHA_USUARIO_EXTERNO);

  PaginaSEIExterna::getInstance()->setTipoPagina(PaginaSEIExterna::$TIPO_PAGINA_SEM_MENU);
  PaginaSEIExterna::getInstance()->salvarCamposPost(array('selUf','selCidade'));

  $strDisplayMensagem = '';
  $strDisplayCadastro = '';
  $strTextoFormulario = '';

    switch($_GET['acao']){

    case 'usuario_externo_avisar_cadastro':

      $strTitulo = 'Cadastro de Usuário Externo';

      $strDisplayMensagem = '';
      $strDisplayCadastro = 'display:none;';

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $strTextoFormulario = trim($objInfraParametro->getValor('SEI_MSG_AVISO_CADASTRO_USUARIO_EXTERNO'));

      if ($strTextoFormulario==''){
        header('Location: '.SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_enviar_cadastro&acao_origem='.$_GET['acao']));
        die;
      }

      $strTextoFormulario .= '<br /><br /><a id="lnkCadastro" href="'.SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_enviar_cadastro&acao_origem='.$_GET['acao']).'">Clique aqui para continuar</a>';

      break;

    case 'usuario_externo_enviar_cadastro':

      $strTitulo = _('Cadastro de Usuário Externo');

      $strCaptchaPesquisa = PaginaSEIExterna::getInstance()->recuperarCampo('captcha');
      $strCodigoParaGeracaoCaptcha = InfraCaptcha::obterCodigo();
      PaginaSEIExterna::getInstance()->salvarCampo('captcha', InfraCaptcha::gerar($strCodigoParaGeracaoCaptcha));
      $strImagemCaptcha  = 'data:image/png;base64,'.base64_encode(InfraCaptcha::gerarImagem($strCodigoParaGeracaoCaptcha));

      $strDisplayMensagem = 'display:none;';
      $strDisplayCadastro = '';
      //SessaoSEIExterna::getInstance()->validarPermissao($_GET['acao']);
      if (isset($_POST['sbmEnviar'])) {
        if (trim($_POST['txtCaptcha']) != $strCaptchaPesquisa){
          PaginaSEIExterna::getInstance()->setStrMensagem(_('Código de confirmação inválido.'));
        }else{
          try {
            $objUsuarioDTO = new UsuarioDTO();
            $objUsuarioDTO->setStrSigla($_POST['txtEmail']);
            $objUsuarioDTO->retNumIdUsuario();
            $objUsuarioDTO->retStrStaTipo();
            $objUsuarioDTO->setStrStaTipo(array(UsuarioRN::$TU_EXTERNO_PENDENTE,UsuarioRN::$TU_EXTERNO),InfraDTO::$OPER_IN);
            $objUsuarioRN=new UsuarioRN();

            $arrObjUsuarioDTO=$objUsuarioRN->listarRN0490($objUsuarioDTO);

            $numCadastros = count($arrObjUsuarioDTO);

            $objInfraException = new InfraException();
            if ($numCadastros) {

              if ($numCadastros > 1){
                $objInfraException->lancarValidacao(_('Já existem ').$numCadastros._(' cadastros relacionados com este email.'));
              }

              if ($arrObjUsuarioDTO[0]->getStrStaTipo()==UsuarioRN::$TU_EXTERNO_PENDENTE){
                $objInfraException->lancarValidacao(_('Já existe cadastro pendente relacionado com este email.'));
              }

              if ($arrObjUsuarioDTO[0]->getStrStaTipo()==UsuarioRN::$TU_EXTERNO) {
                $objInfraException->lancarValidacao(_('Já existe usuário cadastrado com este email.'));
              }
            } else {
              $objUsuarioDTO = new UsuarioDTO();
              $objUsuarioDTO->setStrSigla($_POST['txtEmail']);
              $objUsuarioDTO->setNumIdUsuario(null);
              $objUsuarioDTO->setNumIdOrgao($_GET['id_orgao_acesso_externo']);
              $objUsuarioDTO->setStrIdOrigem(null);
              $objUsuarioDTO->setStrNome($_POST['txtNome']);
              $objUsuarioDTO->setStrNomeSocial($_POST['txtNomeSocial']);
              $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_EXTERNO_PENDENTE);
              $objUsuarioDTO->setStrSenha($_POST['pwdSenha']);
              $objUsuarioDTO->setStrEnderecoContato($_POST['txtEndereco']);
              $objUsuarioDTO->setStrComplementoContato($_POST['txtComplemento']);
              $objUsuarioDTO->setStrSinEstrangeiro(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEstrangeiro']));

              if($objUsuarioDTO->getStrSinEstrangeiro() == "S"){
                $objUsuarioDTO->setDblCpfContato(null);
                $objUsuarioDTO->setDblRgContato(null);
                $objUsuarioDTO->setStrOrgaoExpedidorContato(null);
                $objUsuarioDTO->setStrNumeroPassaporte($_POST['txtNumeroPassaporte']);
                $objUsuarioDTO->setNumIdPaisPassaporte($_POST['selPaisPassaporte']);
              }else{
                $objUsuarioDTO->setDblCpfContato(InfraUtil::retirarFormatacao($_POST['txtCpf']));
                $objUsuarioDTO->setDblRgContato($_POST['txtRg']);
                $objUsuarioDTO->setStrOrgaoExpedidorContato($_POST['txtExpedidor']);
                $objUsuarioDTO->setStrNumeroPassaporte(null);
                $objUsuarioDTO->setNumIdPaisPassaporte(null);
              }
              $objUsuarioDTO->setStrCepContato($_POST['txtCep']);
              $objUsuarioDTO->setStrBairroContato($_POST['txtBairro']);
              $objUsuarioDTO->setStrNomeCidadeContato($_POST['txtCidade']);
              $objUsuarioDTO->setStrSiglaUfContato($_POST['txtUf']);
              if (isset($_POST['selPais'])) {
                $objUsuarioDTO->setNumIdPaisContato($_POST['selPais']);
              }else{
                $objUsuarioDTO->setNumIdPaisContato(ID_BRASIL);
              }
              $objUsuarioDTO->setStrSiglaUfContato($_POST['txtUf']);
              $objUsuarioDTO->setStrNomeCidadeContato($_POST['txtCidade']);
              $objUsuarioDTO->setStrTelefoneComercialContato($_POST['txtTelefoneComercial']);
              $objUsuarioDTO->setStrTelefoneCelularContato($_POST['txtTelefoneCelular']);
              $objUsuarioDTO->setStrTelefoneResidencialContato($_POST['txtTelefoneResidencial']);
              $objUsuarioDTO->setNumIdCidadeContato($_POST['selCidade']);
              $objUsuarioDTO->setNumIdUfContato($_POST['selUf']);
              $objUsuarioDTO->setNumIdPaisContato($_POST['selPais']);

              $objUsuarioDTO->setStrSinAcessibilidade('N');
              $objUsuarioDTO->setStrSinAtivo('S');

              $objUsuarioRN->cadastrarExterno($objUsuarioDTO);
              PaginaSEIExterna::getInstance()->adicionarMensagem(_('IMPORTANTE: As instruções para ativar o seu cadastro foram encaminhadas para o seu e-mail.'));
              header('Location: '.SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_logar'));
              die;
            }
          } catch (Exception $e) {
            PaginaSEIExterna::getInstance()->processarExcecao($e, true);
          }
        }
      }

      $strItensSelUf = UfINT::montarSelectSiglaRI0416('null','&nbsp;',$_POST['selUf']);
      $strLinkAjaxCidade = SessaoSEIExterna::getInstance()->assinarLink('controlador_ajax_externo.php?acao_ajax=cidade_montar_select_id_cidade_nome');
      $strItensSelCidade = CidadeINT::montarSelectIdCidadeNome('null','&nbsp;',$_POST['selCidade'],$_POST['selUf']);
      $strItensSelPaisPassaporte = PaisINT::montarSelectNome('null','&nbsp',$_POST['selPaisPassaporte']);
      $strItensSelPais = PaisINT::montarSelectNome('null','&nbsp',(isset($_POST['selPais']) ? $_POST['selPais'] : ID_BRASIL));
      break;

    default:
      throw new InfraException(_("Ação '").$_GET['acao']._("' não reconhecida."));
  }
}catch(Exception $e){

  PaginaSEIExterna::getInstance()->processarExcecao($e);
}

$strDivIdioma = '';

if($_GET['acao'] != 'usuario_externo_avisar_cadastro') {

  $strDivIdioma='<div id="divIdioma">'."\n";
  $strLinkConferencia='controlador_externo.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_orgao_acesso_externo='.$_GET['id_orgao_acesso_externo'];

  foreach ($arrIdiomas as $key => $value) {
    $strDivIdioma .= '<a href="' . $strLinkConferencia . '&lang=' . $key . '" title="' . $value[0] . '" style="text-decoration:none;padding:1px;' . ($locale == $key ? 'border:1px solid black;font-weight:bold;' : '') . '">' . $value[1] . '</a>&nbsp;' . "\n";
  }
  $strDivIdioma .= "</div>\n";
}

PaginaSEIExterna::getInstance()->montarDocType();
PaginaSEIExterna::getInstance()->abrirHtml();
PaginaSEIExterna::getInstance()->abrirHead();
PaginaSEIExterna::getInstance()->montarMeta();
PaginaSEIExterna::getInstance()->montarTitle(PaginaSEIExterna::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEIExterna::getInstance()->montarStyle();
PaginaSEIExterna::getInstance()->abrirStyle();
?>

div.infraBarraSistemaE {width:80%;}
div.infraBarraSistemaD {width:15%;}

#divDadosCadastrais {<?=$strDisplayCadastro?>}

#lblTituloDadosCadastrais, #lblTituloAutenticacao {position:relative;display:block;margin:1em 0;}

#lblNome {position:absolute;left:0%;top:0%;width:50%;}
#txtNome {position:absolute;left:0%;top:40%;width:41%;}

#divEstrangeiro {position:absolute;left:42.7%;top:40%;}

#divNacional{display:block;}
#lblNomeSocial {position:absolute;left:0%;top:0%;width:50%;}
#txtNomeSocial {position:absolute;left:0%;top:20%;width:41%;}
#lblCpf {position:absolute;left:0%;top:50%;width:19%;}
#txtCpf {position:absolute;left:0%;top:70%;width:19%;}
#lblRg {position:absolute;left:21%;top:50%;width:20%;}
#txtRg {position:absolute;left:21%;top:70%;width:20%;}
#lblExpedidor {position:absolute;left:43%;top:50%;width:15%;}
#txtExpedidor {position:absolute;left:43%;top:70%;width:15%;}

#divPassaporte{display:none;}
#lblNumeroPassaporte {position:absolute;left:0%;top:0%;width:19%;}
#txtNumeroPassaporte {position:absolute;left:0%;top:40%;width:19%;}

#lblPaisPassaporte {position:absolute;left:21%;top:0%;width:20%;}
#selPaisPassaporte {position:absolute;left:21%;top:40%;width:20%;}

#lblTelefoneComercial {position:absolute;left:0%;top:0%;width:19%;}
#txtTelefoneComercial {position:absolute;left:0%;top:40%;width:19%;}
#lblTelefoneCelular {position:absolute;left:21%;top:0%;width:20%;}
#txtTelefoneCelular {position:absolute;left:21%;top:40%;width:20%;}
#lblTelefoneResidencial {position:absolute;left:43%;top:0%;width:15%;}
#txtTelefoneResidencial {position:absolute;left:43%;top:40%;width:15%;}

#lblEndereco {position:absolute;left:0%;top:0%;width:58%;}
#txtEndereco {position:absolute;left:0%;top:40%;width:58%;}

#lblComplemento {position:absolute;left:0%;top:0%;width:41%;}
#txtComplemento {position:absolute;left:0%;top:40%;width:41%;}
#lblBairro {position:absolute;left:43%;top:0%;width:15%;}
#txtBairro {position:absolute;left:43%;top:40%;width:15%;}

#lblPais {position:absolute;left:0%;top:0%;width:10%;}
#selPais {position:absolute;left:0%;top:40%;width:10%;}
#lblIdUf {position:absolute;left:12%;top:0%;width:7%;}
#selUf {position:absolute;left:12%;top:40%;width:7%;}
#txtUf {position:absolute;left:12%;top:40%;width:7%;}
#lblIdCidade {position:absolute;left:21%;top:0%;width:20.3%;}
#selCidade {position:absolute;left:21%;top:40%;width:20.3%;}
#txtCidade {position:absolute;left:21%;top:40%;width:20%;}
#lblCep {position:absolute;left:43%;top:0%;width:15%;}
#txtCep {position:absolute;left:43%;top:40%;width:15%;}

#lblEmail {position:absolute;left:0%;top:0%;width:33%;}
#txtEmail {position:absolute;left:0%;top:7%;width:33%;}
#lblSenha {position:absolute;left:0%;top:19%;}
#pwdSenha {position:absolute;left:0%;top:26%;width:19%;}
#lblSenhaConfirma {position:absolute;left:0%;top:37%;}
#pwdSenhaConfirma {position:absolute;left:0%;top:44%;width:19%;}

#lblCaptcha {position:absolute;left:0%;top:57%;}
#lblCodigo  {position:absolute;left:35%;top:57%;width:22%;}
#txtCaptcha {position:absolute;left:21%;top:57%;width:12%;height:15%;text-align:center;font-size:3em !important;}

#sbmEnviar {position:absolute;left:0%;top:79%;width:8%;}
#btnVoltar {position:absolute;left:9%;top:79%;width:8%;}

.infraLabelTitulo{
  position:absolute;
  left:0%;
  width:58% !important;
}

#divIdioma {float:right; margin-right:5px; margin-top:5px }
#divIdioma img {padding:2px; width:20px; height:14px;}
.idiomaEscolhido { border: 1px solid gray; border-width:1px !important; }

<?
PaginaSEIExterna::getInstance()->fecharStyle();
PaginaSEIExterna::getInstance()->montarJavaScript();
PaginaSEIExterna::getInstance()->abrirJavaScript();
?>

<?if(0){?><script><?}?>
function inicializar(){

  <?if ($_GET['acao']=='usuario_externo_enviar_cadastro'){?>
    document.getElementById('txtNome').focus();
  <?}?>

    //Ajax para carregar as cidades na escolha do estado
  objAjaxCidade = new infraAjaxMontarSelectDependente('selUf','selCidade','<?=$strLinkAjaxCidade?>');
  objAjaxCidade.prepararExecucao = function(){
    return infraAjaxMontarPostPadraoSelect('null','','null') + '&idUf='+document.getElementById('selUf').value;
  };
  objAjaxCidade.processarResultado = function(){

  };

  infraEfeitoTabelas();

  <?
  if($locale == 'en_US' && empty($_POST)){
  ?>
    $("#chkSinEstrangeiro").prop('checked', true);
  <?
  }
  ?>

  trocarEstrangeiro();

  trocarPais(true);
}

function OnSubmitForm() {
  return validarForm();
}

function validarForm() {

  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('<?=_('Informe o Nome do Representante.')?>');
    document.getElementById('txtNome').focus();
    return false;
  }
  if(!document.getElementById("chkSinEstrangeiro").checked) {
    if (infraTrim(document.getElementById('txtCpf').value) == '') {
      alert('<?=_('Informe o CPF.')?>');
      document.getElementById('txtCpf').focus();
      return false;
    }

    if (!infraValidarCpf(infraTrim(document.getElementById('txtCpf').value))) {
      alert('<?=_('CPF Inválido.')?>');
      document.getElementById('txtCpf').focus();
      return false;
    }

    if (infraTrim(document.getElementById('txtRg').value) == '') {
      alert('<?=_('Informe o RG.')?>');
      document.getElementById('txtRg').focus();
      return false;
    }

    if (infraTrim(document.getElementById('txtExpedidor').value) == '') {
      alert('<?=_('Informe o Órgão Expedidor.')?>');
      document.getElementById('txtExpedidor').focus();
      return false;
    }
  }else{
    if (infraTrim(document.getElementById('txtNumeroPassaporte').value) == '') {
      alert('<?=_('Informe o Número do Passaporte.')?>');
      document.getElementById('txtNumeroPassaporte').focus();
      return false;
    }
    if (!infraSelectSelecionado('selPaisPassaporte')) {
      alert('<?=_('Selecione um País de Emissão.')?>');
      document.getElementById('selPaisPassaporte').focus();
      return false;
    }
  }
	if (infraTrim(document.getElementById('txtTelefoneComercial').value)=='' && infraTrim(document.getElementById('txtTelefoneCelular').value)=='') {
    alert('<?=_('É necessário informar pelo menos um número de telefone.')?>');
    document.getElementById('txtTelefoneComercial').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtEndereco').value)=='') {
    alert('<?=_('Informe o Endereço Residencial.')?>');
    document.getElementById('txtEndereco').focus();
    return false;
  }

  if(!infraSelectSelecionado("selPais")) {
    alert('<?=_('Selecione um País.')?>');
    $("#selPais").focus();
    return false;
  }

  if($("#selPais").val() == '<?=ID_BRASIL?>') {
    if (!infraSelectSelecionado('selUf')) {
      alert('<?=_('Selecione um Estado.')?>');
      document.getElementById('selUf').focus();
      return false;
    }

    if (!infraSelectSelecionado('selCidade')) {
      alert('<?=_('Selecione uma Cidade.')?>');
      document.getElementById('selCidade').focus();
      return false;
    }
  }else{
    if (infraTrim($('#txtCidade').val()) == "") {
      alert('<?=_('Informe a Cidade.')?>');
      $('#txtCidade').focus();
      return false;
    }
  }

  if (infraTrim(document.getElementById('txtCep').value)=='') {
    alert('<?=_('Informe o CEP.')?>');
    document.getElementById('txtCep').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtEmail').value)=='') {
    alert('<?=_('Informe o E-mail pessoal.')?>');
    document.getElementById('txtEmail').focus();
    return false;
  }

  if (!infraValidarEmail(infraTrim(document.getElementById('txtEmail').value))){
		alert('<?=_('E-mail pessoal Inválido.')?>');
		document.getElementById('txtEmail').focus();
		return false;
	}

  if (infraTrim(document.getElementById('pwdSenha').value)=='') {
    alert('<?=_('Informe a Senha.')?>');
    document.getElementById('pwdSenha').focus();
    return false;
  }

  if (infraTrim(document.getElementById('pwdSenha').value).length < <?=$numTamSenhaUsuarioExterno?>) {
    alert('<?=_('A Senha deve ter pelo menos ')?><?=$numTamSenhaUsuarioExterno?><?=_(' caracteres.')?>');
    document.getElementById('pwdSenha').focus();
    return false;
  }

  if (infraTrim(document.getElementById('pwdSenhaConfirma').value)=='') {
    alert('<?=_('Repita a Senha.')?>');
    document.getElementById('pwdSenhaConfirma').focus();
    return false;
  }

  if (infraTrim(document.getElementById('pwdSenha').value)!=infraTrim(document.getElementById('pwdSenhaConfirma').value)) {
    alert('<?=_('Confirmação de Senha não confere.')?>');
    document.getElementById('pwdSenhaConfirma').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtCaptcha').value)=='') {
    alert('<?=_('Informe o código de confirmação.')?>');
    document.getElementById('txtCaptcha').focus();
    return false;
  }

  return true;
}

/*function infraMascaraTelefoneInternacional(object,event){
  numeroTelefone = object.value;
  if(numeroTelefone!= null && numeroTelefone != ""){
    numeroTelefone = numeroTelefone.replace(/[^0-9-\s+\(\)]/i,"");
    object.value = numeroTelefone;
  }
}*/

function infraMascaraTelefoneComercialNacional(event){
  infraMascaraTelefone($("#txtTelefoneComercial").get(0),event)
}
function infraMascaraTelefoneComercialInternacional(event){
  infraMascaraTelefoneInternacional($("#txtTelefoneComercial").get(0),event)
}
function infraMascaraTelefoneCelularNacional(event){
  infraMascaraTelefone($("#txtTelefoneCelular").get(0),event)
}
function infraMascaraTelefoneCelularInternacional(event){
  infraMascaraTelefoneInternacional($("#txtTelefoneCelular").get(0),event)
}
function infraMascaraTelefoneResidencialNacional(event){
  infraMascaraTelefone($("#txtTelefoneResidencial").get(0),event)
}
function infraMascaraTelefoneResidencialInternacional(event){
  infraMascaraTelefoneInternacional($("#txtTelefoneResidencial").get(0),event)
}

function trocarEstrangeiro() {
  if ($("#chkSinEstrangeiro").is(':checked')) {
    $("#divNacional").hide();
    $("#divPassaporte").show();

    $("#txtTelefoneComercial").on("keyup",infraMascaraTelefoneComercialInternacional);
    $("#txtTelefoneComercial").off("keyup",infraMascaraTelefoneComercialNacional);
    $("#txtTelefoneCelular").on("keyup",infraMascaraTelefoneCelularInternacional);
    $("#txtTelefoneCelular").off("keyup",infraMascaraTelefoneCelularNacional);
    $("#txtTelefoneResidencial").on("keyup",infraMascaraTelefoneResidencialInternacional);
    $("#txtTelefoneResidencial").off("keyup",infraMascaraTelefoneResidencialNacional);
  } else {
    $("#divNacional").show();
    $("#divPassaporte").hide();

    $("#txtTelefoneComercial").off("keyup",infraMascaraTelefoneComercialInternacional);
    $("#txtTelefoneComercial").on("keyup",infraMascaraTelefoneComercialNacional);
    $("#txtTelefoneCelular").off("keyup",infraMascaraTelefoneCelularInternacional);
    $("#txtTelefoneCelular").on("keyup",infraMascaraTelefoneCelularNacional);
    $("#txtTelefoneResidencial").off("keyup",infraMascaraTelefoneResidencialInternacional);
    $("#txtTelefoneResidencial").on("keyup",infraMascaraTelefoneResidencialNacional);
  }
  $("#txtTelefoneComercial").keyup();
  $("#txtTelefoneCelular").keyup();
  $("#txtTelefoneResidencial").keyup();

}


function trocarPais(bolInicializacao){
  if ($("#selPais").val() == "<?=ID_BRASIL?>") {
    $("#txtUf").hide();
    $("#txtCidade").hide();
    $("#txtUf").val('');
    $("#txtCidade").val('');
    $("#selUf").show();
    $("#selCidade").show();

    document.getElementById('txtCep').onkeypress = mascaraCepBrasil;
    $("#lblIdUf").removeClass("infraLabelOpcional");
    $("#lblIdUf").addClass("infraLabelObrigatorio");

    if (!bolInicializacao){
      $("#txtCep").val('');
    }
  } else {
    $("#txtUf").show();
    $("#txtCidade").show();
    $("#selUf").hide();
    $("#selCidade").hide();
    $("#selUf").val('');
    $("#selCidade").val('');

    document.getElementById('txtCep').onkeypress = mascaraCepGeral;
    $("#lblIdUf").addClass("infraLabelOpcional");
    $("#lblIdUf").removeClass("infraLabelObrigatorio");

    if (!bolInicializacao) {
      $("#txtUf").val('');
      $("#txtCidade").val('');
      $("#txtCep").val('');
    }
  }
}

function mascaraCepBrasil(event){
  return infraMascaraCEP(document.getElementById('txtCep'), event);
}

function mascaraCepGeral(event){
  return infraMascaraTexto(document.getElementById('txtCep'),event,15)
}

<?if(0){?></script><?}?>
<?
PaginaSEIExterna::getInstance()->fecharJavaScript();
PaginaSEIExterna::getInstance()->fecharHead();
PaginaSEIExterna::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');

echo $strDivIdioma;
?>
<form id="frmUsuarioExterno" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao='.$_GET['acao'].'&lang='.$locale)?>">

  <? if ($strTextoFormulario!=''){ ?>
  <div class="formularioTexto"><?=$strTextoFormulario?></div>
  <? } ?>

  <div id="divDadosCadastrais">

    <label id="lblTituloDadosCadastrais" class="infraLabelTitulo">&nbsp;&nbsp;<?=_("Dados Cadastrais")?></label>

    <div id="divNome" class="infraAreaDados" style="height:5em;">
      <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio"><?=_("Nome do Representante:")?></label>
      <input type="text" id="txtNome" name="txtNome" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtNome'])?>" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <div id="divEstrangeiro" >
        <input type="checkbox" id="chkSinEstrangeiro" name="chkSinEstrangeiro" onchange="trocarEstrangeiro()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEstrangeiro']))?>  tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />
        <label id="lblSinEstrangeiro" for="chkSinEstrangeiro" class="infraLabelCheckbox"><?=_("Estrangeiro")?></label>
      </div>
    </div>

    <div id="divNacional" class="infraAreaDados" style="height:9em;">

      <label id="lblNomeSocial" for="txtNomeSocial" accesskey="" class="infraLabelOpcional"><?=_("Nome Social do Representante:")?></label>
      <input type="text" id="txtNomeSocial" name="txtNomeSocial" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtNomeSocial'])?>" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <label id="lblCpf" for="txtCpf" accesskey="" class="infraLabelObrigatorio"><?=_("CPF:")?></label>
      <input type="text" id="txtCpf" name="txtCpf" onkeypress="return infraMascaraCpf(this,event);" maxlength="15" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtCpf'])?>" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <label id="lblRg" for="txtRg" accesskey="" class="infraLabelObrigatorio"><?=_("RG:")?></label>
      <input type="text" id="txtRg" name="txtRg" onkeypress="return infraMascaraNumero(this,event,15);" maxlength="15" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtRg'])?>" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <label id="lblExpedidor" for="txtExpedidor" accesskey="" class="infraLabelObrigatorio"><?=_("Órgão Expedidor:")?></label>
      <input type="text" id="txtExpedidor" name="txtExpedidor" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtExpedidor'])?>" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />
    </div>

    <div id="divPassaporte" class="infraAreaDados" style="height:5em;">
      <label id="lblNumeroPassaporte" for="txtNumeroPassaporte" class="infraLabelObrigatorio"><?=_("Número do Passaporte:")?></label>
      <input type="text" id="txtNumeroPassaporte" name="txtNumeroPassaporte" maxlength="15" class="infraText" onblur="return infraMascaraNumeroPassaporte(this,event);" onkeyup="return infraMascaraNumeroPassaporte(this,event);" value="<?=PaginaSEI::tratarHTML($_POST["txtNumeroPassaporte"]);?>" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <label id="lblPaisPassaporte" for="selPaisPassaporte" class="infraLabelObrigatorio"><?=_("País de Emissão:")?></label>
      <select id="selPaisPassaporte" name="selPaisPassaporte" class="infraSelect" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">
        <?=$strItensSelPaisPassaporte?>
      </select>
    </div>

    <div id="divTelefones" class="infraAreaDados" style="height:5em;">
      <label id="lblTelefoneComercial" for="txtTelefoneComercial" accesskey="" class="infraLabelOpcional"><?=_("Telefone Comercial:")?></label>
      <input type="text" id="txtTelefoneComercial" name="txtTelefoneComercial" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtTelefoneComercial'])?>"  maxlength="25" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <label id="lblTelefoneCelular" for="txtTelefoneCelular" accesskey="" class="infraLabelOpcional"><?=_("Telefone Celular:")?></label>
      <input type="text" id="txtTelefoneCelular" name="txtTelefoneCelular" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtTelefoneCelular'])?>"  maxlength="25" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <label id="lblTelefoneResidencial" for="txtTelefoneResidencial" accesskey="" class="infraLabelOpcional"><?=_("Telefone Residencial:")?></label>
      <input type="text" id="txtTelefoneResidencial" name="txtTelefoneResidencial" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtTelefoneResidencial'])?>"  maxlength="25" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />
    </div>

    <div id="divEndereco1" class="infraAreaDados" style="height:5em;">
      <label id="lblEndereco" for="txtEndereco" accesskey="" class="infraLabelObrigatorio"><?=_("Endereço Residencial:")?></label>
      <input type="text" id="txtEndereco" name="txtEndereco" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtEndereco'])?>" onkeypress="return infraMascaraTexto(this,event,130);" maxlength="130" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />
    </div>

    <div id="divEndereco2" class="infraAreaDados" style="height:5em;">
      <label id="lblComplemento" for="txtComplemento" accesskey="" class="infraLabelOpcional"><?=_("Complemento:")?></label>
      <input type="text" id="txtComplemento" name="txtComplemento" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtComplemento'])?>" onkeypress="return infraMascaraTexto(this,event,130);" maxlength="130" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <label id="lblBairro" for="txtBairro" accesskey="" class="infraLabelOpcional"><?=_("Bairro:")?></label>
      <input type="text" id="txtBairro" name="txtBairro" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtBairro'])?>" onkeypress="return infraMascaraTexto(this,event,130);" maxlength="130" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />
    </div>

    <div id="divEndereco3" class="infraAreaDados" style="height:5em;">
      <label id="lblPais" for="selPais" class="infraLabelObrigatorio"><?=_("País:")?></label>
      <select id="selPais" name="selPais" class="infraSelect" onchange="trocarPais(false)" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">
        <?=$strItensSelPais?>
      </select>

      <label id="lblIdUf" for="selUf" accesskey="" class="infraLabelOpcional"><?=_("Estado:")?></label>
      <select id="selUf" name="selUf" class="infraSelect" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">
        <?=$strItensSelUf?>
      </select>
      <input type="text" id="txtUf" name="txtUf" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtUf'])?>" class="infraText" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <label id="lblIdCidade" for="selCidade" accesskey="" class="infraLabelObrigatorio"><?=_("Cidade:")?></label>
      <select id="selCidade"  name="selCidade" class="infraSelect" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>">
        <?=$strItensSelCidade?>
      </select>
      <input type="text" id="txtCidade" name="txtCidade" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtCidade'])?>"  onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <label id="lblCep" for="txtCep" accesskey="" class="infraLabelObrigatorio"><?=_("CEP:")?></label>
      <input type="text" id="txtCep" name="txtCep"  maxlength="15" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtCep'])?>"  tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />
    </div>

    <label id="lblTituloAutenticacao" accesskey="" class="infraLabelTitulo">&nbsp;&nbsp;<?=_("Dados de Autenticação")?></label>

    <div id="divCadastroAutenticacao" class="infraAreaDados" style="height:27em;">

      <label id="lblEmail" for="txtEmail" accesskey="" class="infraLabelObrigatorio"><?=_("E-mail pessoal:")?></label>
      <input type="email" id="txtEmail" name="txtEmail" class="infraText" value="<?=PaginaSEIExterna::tratarHTML($_POST['txtEmail'])?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEIExterna::getInstance()->getProxTabDados()?>" />

      <label id="lblSenha" for="pwdSenha" accesskey="" class="infraLabelObrigatorio"><?=_("Senha (no mínimo ")?><?=$numTamSenhaUsuarioExterno?> <?=_(" caracteres com letras e números):")?> </label>
      <?=InfraINT::montarInputPassword('pwdSenha', '', 'tabindex="'.PaginaSEIExterna::getInstance()->getProxTabDados().'"')?>

      <label id="lblSenhaConfirma" for="pwdSenhaConfirma" accesskey="" class="infraLabelObrigatorio"><?=_("Confirmar Senha:")?></label>
      <?=InfraINT::montarInputPassword('pwdSenhaConfirma', '', 'tabindex="'.PaginaSEIExterna::getInstance()->getProxTabDados().'"')?>

      <label id="lblCodigo" for="txtCaptcha" accesskey="" class="labelOpcional"><?=_("Digite o código da imagem ao lado considerando maiúsculas e minúsculas")?></label>
      <label id="lblCaptcha" accesskey="" class="infraLabelObrigatorio"><img src="<?=$strImagemCaptcha?>" alt="<?=_("Não foi possível carregar a imagem de confirmação")?>" /></label>
      <input type="text" id="txtCaptcha" name="txtCaptcha" class="infraText" maxlength="4" value="" />

      <button type="submit" accesskey="" id="sbmEnviar" class="infraButton" name="sbmEnviar" value="Enviar" title="Enviar" ><?=_("Enviar")?></button>
      <button type="button" accesskey="" id="btnVoltar" name="btnVoltar" value="Voltar" onclick="location.href='<?=SessaoSEIExterna::getInstance()->assinarLink('controlador_externo.php?acao=usuario_externo_logar&acao_origem='.$_GET['acao'])?>';" class="infraButton"><?=_("Voltar")?></button>
    </div>
  </div>
</form>
<?
PaginaSEIExterna::getInstance()->montarAreaDebug();
PaginaSEIExterna::getInstance()->fecharBody();
PaginaSEIExterna::getInstance()->fecharHtml();
?>