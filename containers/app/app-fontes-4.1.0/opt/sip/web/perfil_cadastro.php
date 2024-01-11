<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/12/2006 - criado por mga
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


  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema'));

  $objPerfilDTO = new PerfilDTO(true);

  $strDesabilitar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'perfil_cadastrar':
      $strTitulo = 'Novo Perfil';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarPerfil" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_listar') . '\';" class="infraButton" />';

      $objPerfilDTO->setNumIdPerfil(null);

      //ORGAO
      $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
      if ($numIdOrgao !== '') {
        $objPerfilDTO->setNumIdOrgaoSistema($numIdOrgao);
      } else {
        $objPerfilDTO->setNumIdOrgaoSistema(null);
      }

      //SISTEMA
      $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
      if ($numIdSistema !== '') {
        $objPerfilDTO->setNumIdSistema($numIdSistema);
      } else {
        $objPerfilDTO->setNumIdSistema(null);
      }

      //$objPerfilDTO->setNumIdSistema($_POST['selSistema']);
      $objPerfilDTO->setStrNome($_POST['txtNome']);
      $objPerfilDTO->setStrDescricao($_POST['txaDescricao']);
      $objPerfilDTO->setStrSinCoordenado(PaginaSip::getInstance()->getCheckbox($_POST['chkCoordenado']));
      $objPerfilDTO->setStrSin2Fatores(PaginaSip::getInstance()->getCheckbox($_POST['chk2Fatores']));
      $objPerfilDTO->setStrSinAtivo("S");

      $arrObjRelGrupoPerfilPerfilDTO = array();
      $arrGrupoPerfil = PaginaSip::getInstance()->getArrValuesSelect($_POST['hdnGruposPerfil']);
      for ($i = 0; $i < count($arrGrupoPerfil); $i++) {
        $arrStrIdComposto = explode('-', $arrGrupoPerfil[$i]);
        $objRelGrupoPerfilPerfilDTO = new RelGrupoPerfilPerfilDTO();
        $objRelGrupoPerfilPerfilDTO->setNumIdPerfil(null);
        $objRelGrupoPerfilPerfilDTO->setNumIdGrupoPerfil($arrStrIdComposto[0]);
        $arrObjRelGrupoPerfilPerfilDTO[] = $objRelGrupoPerfilPerfilDTO;
      }
      $objPerfilDTO->setArrObjRelGrupoPerfilPerfilDTO($arrObjRelGrupoPerfilPerfilDTO);

      if (isset($_POST['sbmCadastrarPerfil'])) {
        try {
          $objPerfilRN = new PerfilRN();
          $objPerfilDTO = $objPerfilRN->cadastrar($objPerfilDTO);
          PaginaSip::getInstance()->setStrMensagem('Perfil "' . $objPerfilDTO->getStrNome() . '" cadastrado com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_listar&acao_origem=' . $_GET['acao'] . PaginaSip::montarAncora($objPerfilDTO->getNumIdPerfil() . '-' . $objPerfilDTO->getNumIdSistema())));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'perfil_alterar':
      $strTitulo = 'Alterar Perfil';
      $arrComandos[] = '<input type="submit" name="sbmAlterarPerfil" value="Salvar" class="infraButton" />';

      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_perfil']) && isset($_GET['id_sistema'])) {
        $objPerfilDTO->setNumIdPerfil($_GET['id_perfil']);
        $objPerfilDTO->setNumIdSistema($_GET['id_sistema']);
        $objPerfilDTO->retTodos();
        $objPerfilRN = new PerfilRN();
        $arrObjPerfilDTO = $objPerfilRN->listarAdministrados($objPerfilDTO);
        if (count($arrObjPerfilDTO) !== 1) {
          throw new InfraException("Registro não encontrado.");
        }
        $objPerfilDTO = $arrObjPerfilDTO[0];
      } else {
        $objPerfilDTO->setNumIdPerfil($_POST['hdnIdPerfil']);
        $objPerfilDTO->setNumIdOrgaoSistema($_POST['hdnIdOrgaoSistema']);
        $objPerfilDTO->setNumIdSistema($_POST['hdnIdSistema']);
        $objPerfilDTO->setStrNome($_POST['txtNome']);
        $objPerfilDTO->setStrDescricao($_POST['txaDescricao']);
        $objPerfilDTO->setStrSinCoordenado(PaginaSip::getInstance()->getCheckbox($_POST['chkCoordenado']));
        $objPerfilDTO->setStrSin2Fatores(PaginaSip::getInstance()->getCheckbox($_POST['chk2Fatores']));
        $objPerfilDTO->setStrSinAtivo("S");
      }

      $arrObjRelGrupoPerfilPerfilDTO = array();
      $arrGrupoPerfil = PaginaSip::getInstance()->getArrValuesSelect($_POST['hdnGruposPerfil']);
      for ($i = 0; $i < count($arrGrupoPerfil); $i++) {
        $arrStrIdComposto = explode('-', $arrGrupoPerfil[$i]);
        $objRelGrupoPerfilPerfilDTO = new RelGrupoPerfilPerfilDTO();
        $objRelGrupoPerfilPerfilDTO->setNumIdPerfil(null);
        $objRelGrupoPerfilPerfilDTO->setNumIdGrupoPerfil($arrStrIdComposto[0]);
        $arrObjRelGrupoPerfilPerfilDTO[] = $objRelGrupoPerfilPerfilDTO;
      }
      $objPerfilDTO->setArrObjRelGrupoPerfilPerfilDTO($arrObjRelGrupoPerfilPerfilDTO);

      $strAncora = PaginaSip::getInstance()->montarAncora($objPerfilDTO->getNumIdPerfil() . '-' . $objPerfilDTO->getNumIdSistema());

      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_listar' . $strAncora) . '\';" class="infraButton" />';

      if (isset($_POST['sbmAlterarPerfil'])) {
        try {
          $objPerfilRN = new PerfilRN();
          $objPerfilRN->alterar($objPerfilDTO);
          PaginaSip::getInstance()->setStrMensagem('Perfil "' . $objPerfilDTO->getStrNome() . '" alterado com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=perfil_listar&acao_origem=' . $_GET['acao'] . $strAncora));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }

      break;

    case 'perfil_consultar':
      $strTitulo = "Consultar Perfil";
      $arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . PaginaSip::getInstance()->montarAncora($_GET['id_perfil'] . '-' . $_GET['id_sistema'])) . '\';" class="infraButton" />';
      $objPerfilDTO->setBolExclusaoLogica(false);
      $objPerfilDTO->setNumIdPerfil($_GET['id_perfil']);
      $objPerfilDTO->setNumIdSistema($_GET['id_sistema']);
      $objPerfilDTO->retTodos();
      $objPerfilRN = new PerfilRN();
      $arrObjPerfilDTO = $objPerfilRN->listarAdministrados($objPerfilDTO);
      if (count($arrObjPerfilDTO) != 1) {
        throw new InfraException("Registro não encontrado.");
      }
      $objPerfilDTO = $arrObjPerfilDTO[0];
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null', '&nbsp;', $objPerfilDTO->getNumIdOrgaoSistema());
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null', '&nbsp;', $objPerfilDTO->getNumIdSistema(), $objPerfilDTO->getNumIdOrgaoSistema());

  $strLinkGruposPerfilSelecao = SessaoSip::getInstance()->assinarLink('controlador.php?acao=grupo_perfil_selecionar&tipo_selecao=2&id_sistema=' . $objPerfilDTO->getNumIdSistema() . '&id_object=objLupaGruposPerfil');
  $strItensSelGrupoPerfil = RelGrupoPerfilPerfilINT::montarSelectGrupoPerfil($objPerfilDTO->getNumIdSistema(), $objPerfilDTO->getNumIdPerfil());
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Perfil');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
  #selOrgaoSistema {position:absolute;left:0%;top:40%;width:20%;}

  #lblSistema {position:absolute;left:0%;top:0%;width:20%;}
  #selSistema {position:absolute;left:0%;top:40%;width:20%;}

  #lblNome {position:absolute;left:0%;top:0%;width:80%;}
  #txtNome {position:absolute;left:0%;top:40%;width:80%;}

  #lblDescricao {position:absolute;left:0%;top:0%;width:80%;}
  #txaDescricao {position:absolute;left:0%;top:15%;width:80%;}

  #divSinCoordenado {position:absolute;left:0%;top:0%;}
  #divSin2Fatores {position:absolute;left:0%;top:40%;}

  #lblGruposPerfil {position:absolute;left:0%;top:0%;}
  #selGruposPerfil {position:absolute;left:0%;top:17%;width:50%;}
  #divOpcoesGruposPerfil {position:absolute;left:51%;top:17%;}


<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
  function inicializar(){
  if ('<?=$_GET['acao']?>'=='perfil_cadastrar'){
  if (!infraSelectSelecionado('selSistema')){
  document.getElementById('selOrgaoSistema').focus();
  }else{
  document.getElementById('txtNome').focus();
  }
  } else if ('<?=$_GET['acao']?>'=='perfil_consultar'){
  infraDesabilitarCamposAreaDados();
  }
  objLupaGruposPerfil = new infraLupaSelect('selGruposPerfil','hdnGruposPerfil','<?=$strLinkGruposPerfilSelecao?>');
  objLupaGruposPerfil.validarSelecionar = function(){
  if (document.getElementById('selSistema').selectedIndex < 1){
  alert('Selecione um Sistema.');
  return false;
  }
  return true;
  }

  }

  function OnSubmitForm() {
  if (!infraSelectSelecionado(document.getElementById('selOrgaoSistema'))) {
  alert('Selecione um Órgão do Sistema.');
  document.getElementById('selOrgaoSistema').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selSistema'))) {
  alert('Selecione um Sistema.');
  document.getElementById('selSistema').focus();
  return false;
  }

  if (infraTrim(document.getElementById('txtNome').value)=='') {
  alert('Informe Nome.');
  document.getElementById('txtNome').focus();
  return false;
  }

  return true;
  }

  function trocarOrgaoSistema(obj){
  document.getElementById('selSistema').value='null';
  obj.form.submit();
  }

  function trocarSistema(obj){
  document.getElementById('hdnGruposPerfil').value='';
  obj.form.submit();
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmPerfilCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('perfil_cadastro.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span
        class="infraTeclaAtalho">r</span>gão do Sistema:</label>
    <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelOrgaoSistema?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <select id="selSistema" name="selSistema" class="infraSelect" onchange="trocarSistema(this);"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelSistema?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">N</span>ome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText"
           value="<?=PaginaSip::tratarHTML($objPerfilDTO->getStrNome());?>" maxlength="100"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('13em');
    ?>
    <label id="lblDescricao" for="txaDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
    <textarea id="txaDescricao" name="txaDescricao" class="infraTextarea" rows="5"
              tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"><?=PaginaSip::tratarHTML($objPerfilDTO->getStrDescricao());?></textarea>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('6em');
    ?>
    <div id="divSinCoordenado" class="infraDivCheckbox">
      <input type="checkbox" id="chkCoordenado" name="chkCoordenado" <?=PaginaSip::getInstance()->setCheckbox($objPerfilDTO->getStrSinCoordenado())?> class="infraCheckbox" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
      <label id="lblCoordenado" accesskey="" for="chkCoordenado" class="infraLabelCheckbox">Disponível aos Coordenadores
        de Unidade</label>
    </div>

    <div id="divSin2Fatores" class="infraDivCheckbox">
      <input type="checkbox" id="chk2Fatores" name="chk2Fatores" <?=PaginaSip::getInstance()->setCheckbox($objPerfilDTO->getStrSin2Fatores())?> class="infraCheckbox" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
      <label id="lbl2Fatores" accesskey="" for="chk2Fatores" class="infraLabelCheckbox">Requer Autenticação em 2
        Fatores</label>
    </div>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('10em');
    ?>
    <label id="lblGruposPerfil" for="selGruposPerfil" class="infraLabelOpcional">Grupos de Perfis Associados:</label>
    <select id="selGruposPerfil" name="selGruposPerfil" size="5" class="infraSelect" multiple="multiple"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelGrupoPerfil?>
    </select>
    <div id="divOpcoesGruposPerfil">
      <img id="imgPesquisarGruposPerfil" onclick="objLupaGruposPerfil.selecionar(700,500);"
           src="<?=PaginaSip::getInstance()->getIconePesquisar()?>" alt="Pesquisa de Grupos de Perfis"
           title="Pesquisa de Grupos de Perfis" class="infraImg"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
      <br>
      <img id="imgRemoverGruposPerfil" onclick="objLupaGruposPerfil.remover();"
           src="<?=PaginaSip::getInstance()->getIconeRemover()?>" alt="Remover Grupos de Perfis Selecionados"
           title="Remover Grupos de Perfis Selecionados" class="infraImg"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
    </div>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    ?>
    <input type="hidden" name="hdnIdPerfil" value="<?=$objPerfilDTO->getNumIdPerfil();?>"/>
    <input type="hidden" name="hdnIdOrgaoSistema" value="<?=$objPerfilDTO->getNumIdOrgaoSistema();?>"/>
    <input type="hidden" name="hdnIdSistema" value="<?=$objPerfilDTO->getNumIdSistema();?>"/>
    <input type="hidden" name="hdnIdSistema" value="<?=$objPerfilDTO->getNumIdSistema();?>"/>
    <input type="hidden" id="hdnGruposPerfil" name="hdnGruposPerfil"
           value="<?=PaginaSip::tratarHTML($_POST['hdnGruposPerfil'])?>"/>
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