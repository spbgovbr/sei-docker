<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__) . '/Sip.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array(
      'selOrgaoSistema', 'selSistema', 'selOrgaoUnidade', 'selUnidade', 'selOrgaoUsuario', 'hdnIdUsuario', 'txtUsuario', 'hdnNomeUsuario', 'hdnSiglaUsuario', 'selPerfil'
    ));

  $objPermissaoDTO = new PermissaoDTO(true);

  $strDesabilitar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'permissao_cadastrar':
      $strTitulo = 'Nova Permissão';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarPermissao" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton" />';

      //ORGAO SISTEMA
      $numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
      if ($numIdOrgaoSistema !== '') {
        $objPermissaoDTO->setNumIdOrgaoSistema($numIdOrgaoSistema);
      } else {
        $objPermissaoDTO->setNumIdOrgaoSistema(null);
      }

      //SISTEMA
      $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
      if ($numIdSistema !== '') {
        $objPermissaoDTO->setNumIdSistema($numIdSistema);
      } else {
        $objPermissaoDTO->setNumIdSistema(null);
      }

      //ORGAO UNIDADE
      $numIdOrgaoUnidade = PaginaSip::getInstance()->recuperarCampo('selOrgaoUnidade', SessaoSip::getInstance()->getNumIdOrgaoSistema());
      if ($numIdOrgaoUnidade !== '') {
        $objPermissaoDTO->setNumIdOrgaoUnidade($numIdOrgaoUnidade);
      } else {
        $objPermissaoDTO->setNumIdOrgaoUnidade(null);
      }

      //UNIDADE
      $numIdUnidade = PaginaSip::getInstance()->recuperarCampo('selUnidade');
      if ($numIdUnidade !== '') {
        $objPermissaoDTO->setNumIdUnidade($numIdUnidade);
      } else {
        $objPermissaoDTO->setNumIdUnidade(null);
      }

      //ORGAO USUARIO
      $numIdOrgaoUsuario = PaginaSip::getInstance()->recuperarCampo('selOrgaoUsuario', SessaoSip::getInstance()->getNumIdOrgaoSistema());
      if ($numIdOrgaoUsuario !== '') {
        $objPermissaoDTO->setNumIdOrgaoUsuario($numIdOrgaoUsuario);
      } else {
        $objPermissaoDTO->setNumIdOrgaoUsuario(null);
      }

      //USUARIO
      $objPermissaoDTO->setNumIdUsuario(PaginaSip::getInstance()->recuperarCampo('hdnIdUsuario'));
      $objPermissaoDTO->setStrSiglaUsuario(PaginaSip::getInstance()->recuperarCampo('hdnSiglaUsuario'));
      $objPermissaoDTO->setStrNomeUsuario(PaginaSip::getInstance()->recuperarCampo('hdnNomeUsuario'));

      $numIdPerfil = PaginaSip::getInstance()->recuperarCampo('selPerfil');
      if ($numIdPerfil !== '') {
        $objPermissaoDTO->setNumIdPerfil($numIdPerfil);
      } else {
        $objPermissaoDTO->setNumIdPerfil(null);
      }

      if (!isset($_POST['selTipoPermissao'])) {
        $objPermissaoDTO->setNumIdTipoPermissao(1);
      } else {
        $objPermissaoDTO->setNumIdTipoPermissao($_POST['selTipoPermissao']);
      }

      if (!isset($_POST['chkReplicar'])) {
        $objPermissaoDTO->setStrSinSubunidades('N');
      } else {
        $objPermissaoDTO->setStrSinSubunidades(PaginaSip::getInstance()->getCheckbox($_POST['chkReplicar']));
      }

      if (!isset($_POST['txtDataInicio'])) {
        $objPermissaoDTO->setDtaDataInicio(InfraData::getStrDataAtual());
      } else {
        $objPermissaoDTO->setDtaDataInicio($_POST['txtDataInicio']);
      }
      $objPermissaoDTO->setDtaDataFim($_POST['txtDataFim']);

      if (isset($_POST['sbmCadastrarPermissao'])) {
        try {
          $objPermissaoRN = new PermissaoRN();
          $objPermissaoDTO = $objPermissaoRN->cadastrar($objPermissaoDTO);
          PaginaSip::getInstance()->setStrMensagem('Permissão cadastrada com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($objPermissaoDTO->getNumIdPerfil() . '-' . $objPermissaoDTO->getNumIdSistema() . '-' . $objPermissaoDTO->getNumIdUsuario() . '-' . $objPermissaoDTO->getNumIdUnidade())));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'permissao_alterar':
      $strTitulo = 'Alterar Permissão';
      $arrComandos[] = '<input type="submit" name="sbmAlterarPermissao" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($_GET['id_perfil'] . '-' . $_GET['id_sistema'] . '-' . $_GET['id_usuario'] . '-' . $_GET['id_unidade'])) . '\';" class="infraButton" />';

      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_perfil']) && isset($_GET['id_sistema']) && isset($_GET['id_usuario']) && isset($_GET['id_unidade'])) {
        $objPermissaoDTO->setNumIdPerfil($_GET['id_perfil']);
        $objPermissaoDTO->setNumIdSistema($_GET['id_sistema']);
        $objPermissaoDTO->setNumIdUsuario($_GET['id_usuario']);
        $objPermissaoDTO->setNumIdUnidade($_GET['id_unidade']);
        $objPermissaoDTO->retTodos(true);
        $objPermissaoRN = new PermissaoRN();
        $objPermissaoDTO = $objPermissaoRN->consultar($objPermissaoDTO);
        if ($objPermissaoDTO === null) {
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objPermissaoDTO->setNumIdOrgaoSistema($_POST['hdnIdOrgaoSistema']);
        $objPermissaoDTO->setNumIdSistema($_POST['hdnIdSistema']);
        $objPermissaoDTO->setNumIdOrgaoUsuario($_POST['hdnIdOrgaoUsuario']);
        $objPermissaoDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
        $objPermissaoDTO->setStrSiglaUsuario($_POST['hdnSiglaUsuario']);
        $objPermissaoDTO->setStrNomeUsuario($_POST['hdnNomeUsuario']);
        $objPermissaoDTO->setNumIdOrgaoUnidade($_POST['hdnIdOrgaoUnidade']);
        $objPermissaoDTO->setNumIdUnidade($_POST['hdnIdUnidade']);
        $objPermissaoDTO->setNumIdPerfil($_POST['hdnIdPerfil']);
        $objPermissaoDTO->setNumIdTipoPermissao($_POST['selTipoPermissao']);
        $objPermissaoDTO->setStrSinSubunidades(PaginaSip::getInstance()->getCheckbox($_POST['chkReplicar']));
        $objPermissaoDTO->setDtaDataInicio($_POST['txtDataInicio']);
        $objPermissaoDTO->setDtaDataFim($_POST['txtDataFim']);
      }

      if (isset($_POST['sbmAlterarPermissao'])) {
        try {
          $objPermissaoRN = new PermissaoRN();
          $objPermissaoRN->alterar($objPermissaoDTO);
          PaginaSip::getInstance()->setStrMensagem('Permissão alterada com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($objPermissaoDTO->getNumIdPerfil() . '-' . $objPermissaoDTO->getNumIdSistema() . '-' . $objPermissaoDTO->getNumIdUsuario() . '-' . $objPermissaoDTO->getNumIdUnidade())));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'permissao_consultar':
      $strTitulo = "Consultar Permissão";
      $arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($_GET['id_perfil'] . '-' . $_GET['id_sistema'] . '-' . $_GET['id_usuario'] . '-' . $_GET['id_unidade'])) . '\';" class="infraButton" />';

      $objPermissaoDTO->setNumIdPerfil($_GET['id_perfil']);
      $objPermissaoDTO->setNumIdSistema($_GET['id_sistema']);
      $objPermissaoDTO->setNumIdUsuario($_GET['id_usuario']);
      $objPermissaoDTO->setNumIdUnidade($_GET['id_unidade']);
      $objPermissaoDTO->retTodos();
      $objPermissaoRN = new PermissaoRN();
      $objPermissaoDTO = $objPermissaoRN->consultar($objPermissaoDTO);
      if ($objPermissaoDTO === null) {
        throw new InfraException("Registro não encontrado.");
      }

      //Carrega combos permitindo sistema e orgao da permissao
      $strItensSelOrgaoSistema = InfraINT::montarItemSelect($objPermissaoDTO->getNumIdOrgaoSistema(), $objPermissaoDTO->getStrSiglaOrgaoSistema(), true);
      $strItensSelSistema = InfraINT::montarItemSelect($objPermissaoDTO->getNumIdSistema(), $objPermissaoDTO->getStrSiglaSistema(), true);
      $strItensSelOrgaoUnidade = InfraINT::montarItemSelect($objPermissaoDTO->getNumIdOrgaoUnidade(), $objPermissaoDTO->getStrSiglaOrgaoUnidade(), true);
      $strItensSelUnidade = InfraINT::montarItemSelect($objPermissaoDTO->getNumIdUnidade(), $objPermissaoDTO->getStrSiglaUnidade(), true);
      $strItensSelOrgaoUsuario = InfraINT::montarItemSelect($objPermissaoDTO->getNumIdOrgaoUsuario(), $objPermissaoDTO->getStrSiglaOrgaoUsuario(), true);
      $strItensSelTipoPermissao = InfraINT::montarItemSelect($objPermissaoDTO->getNumIdTipoPermissao(), $objPermissaoDTO->getStrDescricaoTipoPermissao(), true);
      $strItensSelPerfil = InfraINT::montarItemSelect($objPermissaoDTO->getNumIdPerfil(), $objPermissaoDTO->getStrNomePerfil(), true);

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  //Na consulta posiciona diretamente não precisa buscar no banco, além disso
  //alguns itens não poderiam ser mostrados se a consulta viesse da tela
  //de permissões pessoais
  if ($_GET['acao'] != 'permissao_consultar') {
    $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAutorizados('null', '&nbsp;', $objPermissaoDTO->getNumIdOrgaoSistema());
    $strItensSelSistema = SistemaINT::montarSelectSiglaAutorizados('null', '&nbsp;', $objPermissaoDTO->getNumIdSistema(), $objPermissaoDTO->getNumIdOrgaoSistema());
    $strItensSelOrgaoUnidade = OrgaoINT::montarSelectSiglaTodos('null', '&nbsp;', $objPermissaoDTO->getNumIdOrgaoUnidade());

    //$strItensSelUnidade = UnidadeINT::montarSelectSigla('null','&nbsp;',$objPermissaoDTO->getNumIdUnidade(), $objPermissaoDTO->getNumIdOrgaoUnidade(), $objPermissaoDTO->getNumIdSistema() );
    $strItensSelUnidade = UnidadeINT::montarSelectSiglaAutorizadas('null', '&nbsp;', $objPermissaoDTO->getNumIdUnidade(), $objPermissaoDTO->getNumIdOrgaoUnidade(), $objPermissaoDTO->getNumIdSistema());

    $strItensSelOrgaoUsuario = OrgaoINT::montarSelectSiglaTodos('null', '&nbsp;', $objPermissaoDTO->getNumIdOrgaoUsuario());
    //$strItensSelUsuario = UsuarioINT::montarSelectSigla('null','&nbsp;',$objPermissaoDTO->getNumIdUsuario(), $objPermissaoDTO->getNumIdOrgaoUsuario());
    $strItensSelTipoPermissao = TipoPermissaoINT::montarSelectDescricao('null', '&nbsp;', $objPermissaoDTO->getNumIdTipoPermissao());
    $strItensSelPerfil = PerfilINT::montarSelectSiglaAutorizados('null', '&nbsp;', $objPermissaoDTO->getNumIdPerfil(), $objPermissaoDTO->getNumIdSistema(), $objPermissaoDTO->getNumIdUnidade());
  }

  //AJAX
  $strLinkAjaxSistemas = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=sistema_montar_select_sigla_autorizados');

  //$strLinkAjaxUnidades = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_montar_select_sigla');
  $strLinkAjaxUnidades = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_montar_select_sigla_autorizadas');

  $strLinkAjaxPerfis = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=perfil_montar_select_sigla_autorizados');
  $strLinkAjaxUsuario = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_sigla_nome');
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Permissão');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:22%;}
  #selOrgaoSistema {position:absolute;left:0%;top:6%;width:22%;}

  #lblSistema {position:absolute;left:0%;top:16%;width:22%;}
  #selSistema {position:absolute;left:0%;top:22%;width:22%;}

  #lblOrgaoUnidade {position:absolute;left:25%;top:0%;width:22%;}
  #selOrgaoUnidade {position:absolute;left:25%;top:6%;width:22%;}

  #lblUnidade {position:absolute;left:25%;top:16%;width:22%;}
  #selUnidade {position:absolute;left:25%;top:22%;width:22%;}

  #lblOrgaoUsuario {position:absolute;left:50%;top:0%;width:22%;}
  #selOrgaoUsuario {position:absolute;left:50%;top:6%;width:22%;}

  #lblUsuario {position:absolute;left:50%;top:16%;width:21.5%;}
  #txtUsuario {position:absolute;left:50%;top:22%;width:21.5%;}
  #lblNomeUsuario {position:absolute;left:75%;top:22%;width:21.5%;}

  #lblPerfil {position:absolute;left:75%;top:0%;width:22%;}
  #selPerfil {position:absolute;left:75%;top:6%;width:22%;}

  #lblTipoPermissao {position:absolute;left:0%;top:32%;width:22%;visibility:hidden;}
  #selTipoPermissao {position:absolute;left:0%;top:38%;width:22%;visibility:hidden;}

  #lblDataInicio {position:absolute;left:0%;top:48%;width:18%;}
  #txtDataInicio {position:absolute;left:0%;top:54%;width:18%;}
  #imgCalDataInicio {position:absolute;left:19%;top:54%;}

  #lblDataFim {position:absolute;left:25%;top:48%;width:18%;}
  #txtDataFim {position:absolute;left:25%;top:54%;width:18%;}
  #imgCalDataFim {position:absolute;left:44%;top:54%;}

  #divReplicar {position:absolute;left:0%;top:70%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  var objAjaxSistemas = null;
  var objAjaxUnidades = null;
  var objAjaxPerfis = null;

  function inicializar(){
  //COMBO DE SISTEMAS
  objAjaxSistemas = new infraAjaxMontarSelectDependente('selOrgaoSistema','selSistema','<?=$strLinkAjaxSistemas?>');
  objAjaxSistemas.prepararExecucao = function(){
  return infraAjaxMontarPostPadraoSelect('null','','') + '&idOrgaoSistema='+document.getElementById('selOrgaoSistema').value;
  }
  objAjaxSistemas.processarResultado = function(){
  //alert('Carregou sistemas.');
  infraSelectLimpar('selUnidade');
  infraSelectLimpar('selPerfil');
  }

  if ('<?=$_GET['acao']?>'=='permissao_cadastrar' && '<?=$numIdSistema?>'==''){
  objAjaxSistemas.executar();
  }

  //COMBO DE UNIDADES
  objAjaxUnidades = new infraAjaxMontarSelectDependente('selOrgaoUnidade','selUnidade','<?=$strLinkAjaxUnidades?>');
  objAjaxUnidades.prepararExecucao = function(){
  return infraAjaxMontarPostPadraoSelect('null','','') + '&idOrgaoUnidade='+document.getElementById('selOrgaoUnidade').value + '&idSistema='+document.getElementById('selSistema').value;
  }
  objAjaxUnidades.processarResultado = function(){
  //alert('Carregou unidades.');
  }


  //COMBO DE PERFIS
  objAjaxPerfis = new infraAjaxMontarSelect('selPerfil','<?=$strLinkAjaxPerfis?>');
  objAjaxPerfis.prepararExecucao = function(){
  return infraAjaxMontarPostPadraoSelect('null','','') + '&idSistema='+document.getElementById('selSistema').value + '&idUnidade=' + document.getElementById('selUnidade').value;
  }
  objAjaxPerfis.processarResultado = function(){
  //alert('Carregou perfis.');
  }

  //AUTO COMPLETAR USUARIO
  objAjaxUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuario?>');
  objAjaxUsuario.prepararExecucao = function(){
  if (!infraSelectSelecionado('selOrgaoUsuario')){
  alert('Selecione Órgão do Usuário.');
  document.getElementById('selOrgaoUsuario').focus();
  return false;
  }
  return 'sigla='+document.getElementById('txtUsuario').value + '&idOrgao='+document.getElementById('selOrgaoUsuario').value;
  };

  objAjaxUsuario.processarResultado = function(id,descricao,complemento){
  if (id != ''){
  document.getElementById('lblNomeUsuario').innerHTML = complemento;
  document.getElementById('hdnSiglaUsuario').value = descricao;
  document.getElementById('hdnNomeUsuario').value = complemento;
  }else{
  document.getElementById('hdnSiglaUsuario').value = '';
  document.getElementById('lblNomeUsuario').innerHTML = '';
  document.getElementById('hdnNomeUsuario').value = '';
  }
  };

  objAjaxUsuario.selecionar('<?=$objPermissaoDTO->getNumIdUsuario();?>','<?=PaginaSip::getInstance()->formatarParametrosJavascript($objPermissaoDTO->getStrSiglaUsuario(),
  false);?>','<?=PaginaSip::getInstance()->formatarParametrosJavascript($objPermissaoDTO->getStrNomeUsuario(), false)?>');


  if ('<?=$_GET['acao']?>'=='permissao_cadastrar'){
  document.getElementById('selOrgaoSistema').focus();
  } else if ('<?=$_GET['acao']?>'=='permissao_consultar'){
  infraDesabilitarCamposAreaDados();
  }
  }

  function trocarSistema(){
  //alert('trocar Sistema');
  objAjaxUnidades.executar();
  objAjaxPerfis.executar();
  }

  function trocarUnidade(){
  //alert('trocar Unidade');
  //objAjaxUnidades.executar();
  objAjaxPerfis.executar();
  }

  function OnSubmitForm() {


  if (!validarForm()){
  return false;
  }

  return true;
  }

  function validarForm(){

  if (!infraSelectSelecionado(document.getElementById('selOrgaoSistema'))) {
  alert('Selecione Órgão do Sistema.');
  document.getElementById('selOrgaoSistema').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selSistema'))) {
  alert('Selecione um Sistema.');
  document.getElementById('selSistema').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selOrgaoUnidade'))) {
  alert('Selecione Órgão da Unidade.');
  document.getElementById('selOrgaoUnidade').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selUnidade'))) {
  alert('Selecione uma Unidade.');
  document.getElementById('selUnidade').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selOrgaoUsuario'))) {
  alert('Selecione Órgão do Usuário.');
  document.getElementById('selOrgaoUsuario').focus();
  return false;
  }

  if (infraTrim(document.getElementById('hdnIdUsuario').value)=='') {
  alert('Informe um Usuário.');
  document.getElementById('txtUsuario').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selTipoPermissao'))) {
  alert('Selecione um Tipo de Permissão.');
  document.getElementById('selTipoPermissao').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selPerfil'))) {
  alert('Selecione um Perfil.');
  document.getElementById('selPerfil').focus();
  return false;
  }

  if (infraTrim(document.getElementById('txtDataInicio').value)=='') {
  alert('Informe Data Inicial.');
  document.getElementById('txtDataInicio').focus();
  return false;
  }

  if (!infraValidaData(document.getElementById('txtDataInicio'))){
  return false;
  }

  if (!infraValidaData(document.getElementById('txtDataFim'))){
  return false;
  }

  /*
  if ('<?=$_GET['acao']?>'=='permissao_cadastrar'){
  if (infraCompararDatas(infraDataAtual(),document.getElementById('txtDataInicio').value)<0){
  alert('Data Inicial não pode estar no passado.');
  document.getElementById('txtDataInicio').focus();
  return false;
  }
  }
  */

  if (infraCompararDatas(infraDataAtual(),document.getElementById('txtDataFim').value)<0){
  alert('Data Final não pode estar no passado.');
  document.getElementById('txtDataFim').focus();
  return false;
  }

  if(infraCompararDatas(document.getElementById('txtDataInicio').value,document.getElementById('txtDataFim').value)<0){
  alert('Data Final deve ser igual ou superior a Data Inicial.');
  document.getElementById('txtDataFim').focus();
  return false;
  }

  return true;
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmPermissaoCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('permissao_cadastro.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('30em');
    ?>
    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="" class="infraLabelObrigatorio">Órgão do
      Sistema:</label>
    <select id="selOrgaoSistema" name="selOrgaoSistema" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelOrgaoSistema?>
    </select>

    <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <select id="selSistema" name="selSistema" onchange="trocarSistema();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelSistema?>
    </select>

    <label id="lblOrgaoUnidade" for="selOrgaoUnidade" accesskey="" class="infraLabelObrigatorio">Órgão da
      Unidade:</label>
    <select id="selOrgaoUnidade" name="selOrgaoUnidade" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelOrgaoUnidade?>
    </select>

    <label id="lblUnidade" for="selUnidade" accesskey="U" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">U</span>nidade:</label>
    <select id="selUnidade" name="selUnidade" onchange="trocarUnidade();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelUnidade?>
    </select>

    <label id="lblOrgaoUsuario" for="selOrgaoUsuario" accesskey="" class="infraLabelObrigatorio">Órgão do
      Usuário:</label>
    <select id="selOrgaoUsuario" name="selOrgaoUsuario" onchange="objAjaxUsuario.limpar();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelOrgaoUsuario?>
    </select>

    <label id="lblUsuario" for="txtUsuario" accesskey="o" class="infraLabelObrigatorio">Usuári<span
        class="infraTeclaAtalho">o</span>:</label>
    <input type="text" id="txtUsuario" name="txtUsuario" class="infraText"
           value="<?=PaginaSip::tratarHTML($objPermissaoDTO->getStrSiglaUsuario())?>" maxlength="100"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> />
    <label id="lblNomeUsuario" class="infraLabelOpcional"></label>

    <label id="lblPerfil" for="selPerfil" accesskey="P" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">P</span>erfil:</label>
    <select id="selPerfil" name="selPerfil" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelPerfil?>
    </select>

    <label id="lblTipoPermissao" for="selTipoPermissao" accesskey="T" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">T</span>ipo:</label>
    <select id="selTipoPermissao" name="selTipoPermissao" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelTipoPermissao?>
    </select>

    <label id="lblDataInicio" for="txtDataInicio" accesskey="I" class="infraLabelObrigatorio">Data <span
        class="infraTeclaAtalho">I</span>nicial:</label>
    <input type="text" id="txtDataInicio" name="txtDataInicio" onkeypress="return infraMascaraData(this, event)"
           class="infraText" value="<?=PaginaSip::tratarHTML($objPermissaoDTO->getDtaDataInicio());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <img src="<?=PaginaSip::getInstance()->getIconeCalendario()?>" id="imgCalDataInicio"
         title="Selecionar Data Inicial " alt="Selecionar Data Inicial" class="infraImg"
         onclick="infraCalendario('txtDataInicio',this);"
         tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblDataFim" for="txtDataFim" accesskey="F" class="infraLabelOpcional">Data <span
        class="infraTeclaAtalho">F</span>inal:</label>
    <input type="text" id="txtDataFim" name="txtDataFim" onkeypress="return infraMascaraData(this, event)"
           class="infraText" value="<?=PaginaSip::tratarHTML($objPermissaoDTO->getDtaDataFim());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <img src="<?=PaginaSip::getInstance()->getIconeCalendario()?>" id="imgCalDataFim" title="Selecionar Data Final"
         alt="Selecionar Data Final" class="infraImg" onclick="infraCalendario('txtDataFim',this);"
         tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <div id="divReplicar" class="infraDivCheckbox">
      <input type="checkbox" id="chkReplicar" name="chkReplicar" class="infraCheckbox" <?=PaginaSip::getInstance()->setCheckbox($objPermissaoDTO->getStrSinSubunidades())?> />
      <label id="lblReplicar" accesskey="" for="chkReplicar" class="infraLabelCheckbox">Estender permissão às
        subunidades</label>
    </div>

    <input type="hidden" id="hdnIdPerfil" name="hdnIdPerfil" value="<?=$objPermissaoDTO->getNumIdPerfil();?>"/>
    <input type="hidden" id="hdnIdOrgaoSistema" name="hdnIdOrgaoSistema"
           value="<?=$objPermissaoDTO->getNumIdOrgaoSistema();?>"/>
    <input type="hidden" id="hdnIdSistema" name="hdnIdSistema" value="<?=$objPermissaoDTO->getNumIdSistema();?>"/>
    <input type="hidden" id="hdnIdOrgaoUnidade" name="hdnIdOrgaoUnidade"
           value="<?=$objPermissaoDTO->getNumIdOrgaoUnidade();?>"/>
    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" value="<?=$objPermissaoDTO->getNumIdUnidade();?>"/>
    <input type="hidden" id="hdnIdOrgaoUsuario" name="hdnIdOrgaoUsuario"
           value="<?=$objPermissaoDTO->getNumIdOrgaoUsuario();?>"/>
    <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$objPermissaoDTO->getNumIdUsuario();?>"/>
    <input type="hidden" id="hdnSiglaUsuario" name="hdnSiglaUsuario"
           value="<?=PaginaSip::tratarHTML($objPermissaoDTO->getStrSiglaUsuario());?>"/>
    <input type="hidden" id="hdnNomeUsuario" name="hdnNomeUsuario"
           value="<?=PaginaSip::tratarHTML($objPermissaoDTO->getStrNomeUsuario());?>"/>
    <?

    PaginaSip::getInstance()->fecharAreaDados();
    //PaginaSip::getInstance()->montarAreaDebug();
    //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>