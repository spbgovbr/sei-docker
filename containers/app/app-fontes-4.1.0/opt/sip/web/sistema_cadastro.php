<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/01/2007 - criado por mga
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

  //PaginaSip::getInstance()->salvarCamposPost(array('selOrgao','selHierarquia'));

  $objSistemaDTO = new SistemaDTO();

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'sistema_upload':
      //Trata do campo file que é postado para a mesma ação
      if (isset($_FILES['filArquivo'])) {
        PaginaSip::getInstance()->processarUpload('filArquivo', DIR_SIP_TEMP, false);
      }
      die;

    case 'sistema_cadastrar':
      $strTitulo = 'Novo Sistema';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarSistema" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno()) . '\';" class="infraButton" />';

      $objSistemaDTO->setNumIdSistema(null);

      //$numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgao');
      $numIdOrgao = $_POST['selOrgao'];
      if ($numIdOrgao !== '') {
        $objSistemaDTO->setNumIdOrgao($numIdOrgao);
      } else {
        $objSistemaDTO->setNumIdOrgao(null);
      }

      //$numIdHierarquia = PaginaSip::getInstance()->recuperarCampo('selHierarquia');
      $numIdHierarquia = $_POST['selHierarquia'];
      if ($numIdHierarquia !== '') {
        $objSistemaDTO->setNumIdHierarquia($numIdHierarquia);
      } else {
        $objSistemaDTO->setNumIdHierarquia(null);
      }

      $objSistemaDTO->setStrSigla($_POST['txtSigla']);
      $objSistemaDTO->setStrDescricao($_POST['txtDescricao']);
      $objSistemaDTO->setStrPaginaInicial($_POST['txtPaginaInicial']);
      $objSistemaDTO->setStrWebService($_POST['txtWebService']);
      $objSistemaDTO->setStrServicosLiberados(implode(',', PaginaSip::getInstance()->getArrValuesSelect($_POST['hdnServicos'])));
      $objSistemaDTO->setStrSta2Fatores($_POST['selSta2Fatores']);
      $objSistemaDTO->setStrEsquemaLogin($_POST['selEsquemaLogin']);
      $objSistemaDTO->setStrLogo(null);
      $objSistemaDTO->setStrChaveAcesso(null);
      $objSistemaDTO->setStrCrc(null);
      $objSistemaDTO->setStrNomeArquivo($_POST['hdnNomeArquivo']);
      $objSistemaDTO->setStrSinAtivo("S");

      if (isset($_POST['sbmCadastrarSistema'])) {
        try {
          $objSistemaRN = new SistemaRN();
          $objSistemaDTO = $objSistemaRN->cadastrar($objSistemaDTO);
          PaginaSip::getInstance()->setStrMensagem('Sistema "' . $objSistemaDTO->getStrSigla() . '" cadastrado com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($objSistemaDTO->getNumIdSistema())));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }

      break;

    case 'sistema_alterar':
      $strTitulo = 'Alterar Sistema';
      $arrComandos[] = '<input type="submit" name="sbmAlterarSistema" value="Salvar" class="infraButton" />';

      if (isset($_GET['id_sistema'])) {
        $objSistemaDTO->setNumIdSistema($_GET['id_sistema']);
        $objSistemaDTO->retTodos();
        $objSistemaRN = new SistemaRN();
        $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);
        if ($objSistemaDTO == null) {
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objSistemaDTO->setNumIdSistema($_POST['hdnIdSistema']);
        $objSistemaDTO->setNumIdOrgao($_POST['selOrgao']);
        $objSistemaDTO->setNumIdHierarquia($_POST['selHierarquia']);
        $objSistemaDTO->setStrSigla($_POST['txtSigla']);
        $objSistemaDTO->setStrDescricao($_POST['txtDescricao']);
        $objSistemaDTO->setStrPaginaInicial($_POST['txtPaginaInicial']);
        $objSistemaDTO->setStrWebService($_POST['txtWebService']);
        $objSistemaDTO->setStrServicosLiberados(implode(',', PaginaSip::getInstance()->getArrValuesSelect($_POST['hdnServicos'])));
        $objSistemaDTO->setStrSta2Fatores($_POST['selSta2Fatores']);
        $objSistemaDTO->setStrEsquemaLogin($_POST['selEsquemaLogin']);
        $objSistemaDTO->setStrLogo($_POST['hdnLogo']);
        $objSistemaDTO->setStrNomeArquivo($_POST['hdnNomeArquivo']);
        $objSistemaDTO->setStrSinAtivo("S");
      }

      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_listar&acao_origem=' . $_GET['acao'] . PaginaSip::getInstance()->montarAncora($objSistemaDTO->getNumIdSistema())) . '\';" class="infraButton" />';

      if (isset($_POST['sbmAlterarSistema'])) {
        try {
          $objSistemaRN = new SistemaRN();
          $objSistemaRN->alterar($objSistemaDTO);
          PaginaSip::getInstance()->setStrMensagem('Sistema "' . $objSistemaDTO->getStrSigla() . '" alterado com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_listar' . PaginaSip::getInstance()->montarAncora($objSistemaDTO->getNumIdSistema())));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'sistema_consultar':
      $strTitulo = "Consultar Sistema";
      $arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . PaginaSip::getInstance()->montarAncora($_GET['id_sistema'])) . '\';" class="infraButton" />';
      $objSistemaDTO->setBolExclusaoLogica(false);
      $objSistemaDTO->setNumIdSistema($_GET['id_sistema']);
      $objSistemaDTO->retTodos();
      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);
      if ($objSistemaDTO == null) {
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaTodos('null', '&nbsp;', $objSistemaDTO->getNumIdOrgao());
  $strItensSelHierarquia = HierarquiaINT::montarSelectNome('null', '&nbsp;', $objSistemaDTO->getNumIdHierarquia());
  $strItensSelServicos = SistemaINT::montarSelectServicos(null, null, null, $objSistemaDTO->getStrServicosLiberados());
  $strLinkServicos = SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_servico_selecionar&tipo_selecao=2&id_object=objLupaServicos');
  $strItensSelSta2Fatores = SistemaINT::montarSelect2Fatores('null', '&nbsp;', $objSistemaDTO->getStrSta2Fatores());
  $strItensSelEsquemaLogin = InfraINT::montarSelectArray('null', '&nbsp', $objSistemaDTO->getStrEsquemaLogin(), PaginaSip::getInstance()->listarEsquemas());
  $strLinkUpload = SessaoSip::getInstance()->assinarLink('controlador.php?acao=sistema_upload&acao_origem=' . $_GET['acao']);

  $strDisplayRemover = '';
  if ($_GET['acao'] == 'sistema_consultar' || InfraString::isBolVazia($objSistemaDTO->getStrLogo())) {
    $strDisplayRemover = 'display:none;';
  }

  $strDisplayLogo = '';
  if (InfraString::isBolVazia($objSistemaDTO->getStrLogo())) {
    $strDisplayLogo = 'display:none;';
  }
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Sistema');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>

  #divInfraAreaTelaD {margin-bottom:2em;}

  #lblOrgao {position:absolute;left:0%;top:0%;width:20%;}
  #selOrgao {position:absolute;left:0%;top:40%;width:20%;}

  #lblHierarquia {position:absolute;left:0%;top:0%;width:30%;}
  #selHierarquia {position:absolute;left:0%;top:40%;width:30%;}

  #lblSigla {position:absolute;left:0%;top:0%;width:20%;}
  #txtSigla {position:absolute;left:0%;top:40%;width:20%;}

  #lblDescricao {position:absolute;left:0%;top:0%;width:80%;}
  #txtDescricao {position:absolute;left:0%;top:40%;width:80%;}

  #lblPaginaInicial {position:absolute;left:0%;top:0%;width:95%;}
  #txtPaginaInicial {position:absolute;left:0%;top:40%;width:95%;font-family: Courier, Courier New, monospace;}

  #lblSta2Fatores {position:absolute;left:0%;top:0%;}
  #selSta2Fatores {position:absolute;left:0%;top:40%;width:20%;}

  #lblWebService {position:absolute;left:0%;top:0%;width:95%;}
  #txtWebService {position:absolute;left:0%;top:40%;width:95%;font-family: Courier, Courier New, monospace;}

  #lblServicos {position:absolute;left:0%;top:0%;width:30%;}
  #selServicos {position:absolute;left:0%;top:13%;width:30.5%;}
  #divOpcoesServicos {position:absolute;left:31.5%;top:13%;}

  #lblEsquemaLogin {position:absolute;left:0%;top:0%;}
  #selEsquemaLogin {position:absolute;left:0%;top:40%;width:40%;}

  #lblArquivo {position:absolute;left:0%;top:0%;}
  #filArquivo {position:absolute;left:0%;top:40%;}
  #imgRemover {width:1.6em; height:1.6em}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  var objUpload = null;

  function inicializar(){
  if ('<?=$_GET['acao']?>'=='sistema_cadastrar'){
  document.getElementById('selOrgao').focus();
  } else if ('<?=$_GET['acao']?>'=='sistema_consultar'){
  infraDesabilitarCamposAreaDados();
  document.getElementById('imgLogo').style.visibility = 'visible';
  }

  objLupaServicos = new infraLupaSelect('selServicos','hdnServicos','<?=$strLinkServicos?>');

  if ('<?=$_GET['acao']?>'!='sistema_consultar'){
  objUpload = new infraUpload('frmUpload','<?=$strLinkUpload?>');
  objUpload.validar = function() {
  nomeArquivo=document.getElementById('filArquivo').value;
  if (nomeArquivo.substr(nomeArquivo.length-4,4)!='.png') {
  alert ("Imagem do logo deve ser no formato PNG.");
  return false;
  } else return true;
  }
  objUpload.finalizou = function(arr){
  removerLogo();
  if (arr!=null){
  document.getElementById('hdnNomeArquivo').value = arr['nome_upload'];
  }
  }
  }

  }

  function removerLogo(){
  document.getElementById('hdnNomeArquivo').value="*REMOVER*";
  document.getElementById('imgLogo').style.display='none';
  document.getElementById('imgRemover').style.display='none';
  }

  function OnSubmitForm() {
  return validarForm();
  }

  function validarForm() {
  if (!infraSelectSelecionado(document.getElementById('selOrgao'))) {
  alert('Selecione um Órgão.');
  document.getElementById('selOrgao').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selHierarquia'))) {
  alert('Selecione uma Hierarquia.');
  document.getElementById('selHierarquia').focus();
  return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selSta2Fatores'))) {
  alert('Selecione um tipo de Autenticação em 2 Fatores.');
  document.getElementById('selSta2Fatores').focus();
  return false;
  }

  if (infraTrim(document.getElementById('txtSigla').value)=='') {
  alert('Informe Sigla.');
  document.getElementById('txtSigla').focus();
  return false;
  }

  return true;
  }
<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmSistemaCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink(basename(__FILE__) . '?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblOrgao" for="selOrgao" accesskey="o" class="infraLabelObrigatorio">Órgã<span
        class="infraTeclaAtalho">o</span>:</label>
    <select id="selOrgao" name="selOrgao" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgao?>
    </select>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblHierarquia" for="selHierarquia" accesskey="H" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">H</span>ierarquia:</label>
    <select id="selHierarquia" name="selHierarquia" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelHierarquia?>
    </select>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblSigla" for="txtSigla" accesskey="S" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">S</span>igla:</label>
    <input type="text" id="txtSigla" name="txtSigla" class="infraText"
           value="<?=PaginaSip::tratarHTML($objSistemaDTO->getStrSigla());?>" maxlength="15"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblDescricao" for="txtDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
    <input type="text" id="txtDescricao" name="txtDescricao" class="infraText"
           value="<?=PaginaSip::tratarHTML($objSistemaDTO->getStrDescricao());?>" maxlength="200"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblPaginaInicial" for="txtPaginaInicial" accesskey="P" class="infraLabelOpcional"><span
        class="infraTeclaAtalho">P</span>ágina Inicial:</label>
    <input type="text" id="txtPaginaInicial" name="txtPaginaInicial" class="infraText"
           value="<?=PaginaSip::tratarHTML($objSistemaDTO->getStrPaginaInicial());?>" maxlength="255"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblSta2Fatores" for="selSta2Fatores" accesskey="" class="infraLabelObrigatorio">Autenticação em 2
      Fatores:</label>
    <select id="selSta2Fatores" name="selSta2Fatores" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelSta2Fatores?>
    </select>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <label id="lblWebService" for="txtWebService" accesskey="W" class="infraLabelOpcional"><span
        class="infraTeclaAtalho">W</span>eb Service:</label>
    <input type="text" id="txtWebService" name="txtWebService" class="infraText"
           value="<?=PaginaSip::tratarHTML($objSistemaDTO->getStrWebService());?>" maxlength="255"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <?
    PaginaSip::getInstance()->fecharAreaDados();
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>

    <input type="hidden" id="hdnIdSistema" name="hdnIdSistema" value="<?=$objSistemaDTO->getNumIdSistema();?>"/>
    <input type="hidden" id="hdnLogo" name="hdnLogo"
           value="<?=PaginaSip::tratarHTML($objSistemaDTO->getStrLogo());?>"/>
    <input type="hidden" id="hdnNomeArquivo" name="hdnNomeArquivo" value=""/>

    <div id="divServicos" class="infraAreaDados" style="height:15em;">
      <label id="lblServicos" for="selServicos" class="infraLabelOpcional">Serviços Liberados para Acesso no
        SIP:</label>
      <input type="hidden" id="hdnServicos" name="hdnServicos" class="infraText" value=""/>
      <select id="selServicos" name="selServicos" size="7" multiple="multiple" class="infraSelect"
              tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
        <?=$strItensSelServicos?>
      </select>
      <div id="divOpcoesServicos">
        <img id="imgPesquisarServicos" onclick="objLupaServicos.selecionar(700,500);"
             src="<?=PaginaSip::getInstance()->getIconePesquisar()?>" alt="Seleção de Serviços"
             title="Seleção de Serviços" class="infraImg"
             tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
        <img id="imgExcluirServicos" onclick="objLupaServicos.remover();"
             src="<?=PaginaSip::getInstance()->getIconeRemover()?>" alt="Remover Serviços Selecionados"
             title="Remover Serviços Selecionados" class="infraImg"
             tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>
      </div>
    </div>
    <?
    PaginaSip::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblEsquemaLogin" for="selEsquemaLogin" accesskey="" class="infraLabelOpcional">Esquema Login:</label>
    <select id="selEsquemaLogin" name="selEsquemaLogin" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>">
      <?=$strItensSelEsquemaLogin?>
    </select>
    <?
    PaginaSip::getInstance()->fecharAreaDados();

    //PaginaSip::getInstance()->montarAreaDebug();
    //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>

  <form id="frmUpload">
    <div id="divUpload" class="infraAreaDados" style="height:5em">
      <label id="lblArquivo" for="filArquivo" accesskey="" class="infraLabelOpcional">Logo:</label>
      <?
      if ($_GET['acao'] == 'sistema_cadastrar' || $_GET['acao'] == 'sistema_alterar') { ?>
        <input type="file" id="filArquivo" accept="image/png" name="filArquivo" size="50"
               onchange="objUpload.executar();" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/><br/>
        <?
      }
      ?>
    </div>
    <img id="imgLogo" style="border:1px dotted #c0c0c0;float:left;<?=$strDisplayLogo?>"
         src="data:image/png;base64,<?=PaginaSip::tratarHTML($objSistemaDTO->getStrLogo());?>"/>
    &nbsp;&nbsp;
    <img id="imgRemover" src="<?=PaginaSip::getInstance()->getIconeRemover()?>" alt="Remover Logo"
         title="Remover Logo" style="<?=$strDisplayRemover?>" class="infraImg" onclick="removerLogo();"
         tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>