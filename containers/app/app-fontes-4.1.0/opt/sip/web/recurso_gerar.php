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

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema', 'txtCaminhoBase'));

  if (isset($_POST['sbmGerarRecurso'])) {
    PaginaSip::getInstance()->salvarCampo('chkAcaoCadastrar', PaginaSip::getInstance()->getCheckbox($_POST['chkAcaoCadastrar']));
    PaginaSip::getInstance()->salvarCampo('chkAcaoAlterar', PaginaSip::getInstance()->getCheckbox($_POST['chkAcaoAlterar']));
    PaginaSip::getInstance()->salvarCampo('chkAcaoConsultar', PaginaSip::getInstance()->getCheckbox($_POST['chkAcaoConsultar']));
    PaginaSip::getInstance()->salvarCampo('chkAcaoListar', PaginaSip::getInstance()->getCheckbox($_POST['chkAcaoListar']));
    PaginaSip::getInstance()->salvarCampo('chkAcaoSelecionar', PaginaSip::getInstance()->getCheckbox($_POST['chkAcaoSelecionar']));
    PaginaSip::getInstance()->salvarCampo('chkAcaoExcluir', PaginaSip::getInstance()->getCheckbox($_POST['chkAcaoExcluir']));
    PaginaSip::getInstance()->salvarCampo('chkAcaoDesativar', PaginaSip::getInstance()->getCheckbox($_POST['chkAcaoDesativar']));
    PaginaSip::getInstance()->salvarCampo('chkAcaoReativar', PaginaSip::getInstance()->getCheckbox($_POST['chkAcaoReativar']));
  }

  $objRecursoPadraoDTO = new RecursoPadraoDTO(true);

  $strDesabilitar = '';

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'recurso_gerar':
      $strTitulo = 'Gerar Recursos Padrão PHP';
      $arrComandos[] = '<input type="submit" name="sbmGerarRecurso" value="Gerar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_listar') . '\';" class="infraButton" />';

      //ORGAO
      $numIdOrgao = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
      if ($numIdOrgao !== '') {
        $objRecursoPadraoDTO->setNumIdOrgaoSistema($numIdOrgao);
      } else {
        $objRecursoPadraoDTO->setNumIdOrgaoSistema(null);
      }

      //SISTEMA
      $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
      if ($numIdSistema !== '') {
        $objRecursoPadraoDTO->setNumIdSistema($numIdSistema);
      } else {
        $objRecursoPadraoDTO->setNumIdSistema(null);
      }

      $objRecursoPadraoDTO->setStrEntidade($_POST['txtEntidade']);

      $objRecursoPadraoDTO->setStrCaminhoBase(PaginaSip::getInstance()->recuperarCampo('txtCaminhoBase', 'controlador.php?acao='));
      $objRecursoPadraoDTO->setStrSinAcaoCadastrar(PaginaSip::getInstance()->recuperarCampo('chkAcaoCadastrar', 'S'));
      $objRecursoPadraoDTO->setStrSinAcaoAlterar(PaginaSip::getInstance()->recuperarCampo('chkAcaoAlterar', 'S'));
      $objRecursoPadraoDTO->setStrSinAcaoConsultar(PaginaSip::getInstance()->recuperarCampo('chkAcaoConsultar', 'S'));
      $objRecursoPadraoDTO->setStrSinAcaoListar(PaginaSip::getInstance()->recuperarCampo('chkAcaoListar', 'S'));
      $objRecursoPadraoDTO->setStrSinAcaoSelecionar(PaginaSip::getInstance()->recuperarCampo('chkAcaoSelecionar', 'S'));
      $objRecursoPadraoDTO->setStrSinAcaoExcluir(PaginaSip::getInstance()->recuperarCampo('chkAcaoExcluir', 'S'));
      $objRecursoPadraoDTO->setStrSinAcaoDesativar(PaginaSip::getInstance()->recuperarCampo('chkAcaoDesativar', 'S'));
      $objRecursoPadraoDTO->setStrSinAcaoReativar(PaginaSip::getInstance()->recuperarCampo('chkAcaoReativar', 'S'));

      if (isset($_POST['sbmGerarRecurso'])) {
        try {
          $objRecursoRN = new RecursoRN();
          $arrObjRecursoDTO = $objRecursoRN->gerar($objRecursoPadraoDTO);


          $arrAncora = array();

          if (count($arrObjRecursoDTO) > 0) {
            foreach ($arrObjRecursoDTO as $objRecursoDTO) {
              $arrAncora[] = $objRecursoPadraoDTO->getNumIdSistema() . '-' . $objRecursoDTO->getNumIdRecurso();
            }

            if (count($arrObjRecursoDTO) == 1) {
              PaginaSip::getInstance()->setStrMensagem('Recurso gerado com sucesso.');
            } else {
              PaginaSip::getInstance()->setStrMensagem('Recursos gerados com sucesso.');
            }
          } else {
            PaginaSip::getInstance()->setStrMensagem('Nenhum novo recurso foi gerado.');
          }
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=recurso_listar' . PaginaSip::getInstance()->montarAncora($arrAncora)));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null', '&nbsp;', $objRecursoPadraoDTO->getNumIdOrgaoSistema());
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null', '&nbsp;', $objRecursoPadraoDTO->getNumIdSistema(), $objRecursoPadraoDTO->getNumIdOrgaoSistema());
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Recurso');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:20%;}
  #selOrgaoSistema {position:absolute;left:0%;top:6%;width:20%;}

  #lblSistema {position:absolute;left:0%;top:16%;width:20%;}
  #selSistema {position:absolute;left:0%;top:22%;width:20%;}

  #lblEntidade {position:absolute;left:0%;top:32%;width:80%;}
  #txtEntidade {position:absolute;left:0%;top:38%;width:80%;}

  #lblCaminhoBase {position:absolute;left:0%;top:48%;width:50%;}
  #txtCaminhoBase {position:absolute;left:0%;top:54%;width:50%;}

  #fldAcoesPadrao {position:absolute;left:0%;top:64%;height:34%;width:45%;}

  #divAcaoCadastrar {position:absolute;left:7%;top:20%;width:23%;}
  #divAcaoAlterar {position:absolute;left:7%;top:40%;width:23%;}
  #divAcaoExcluir {position:absolute;left:7%;top:60%;width:23%;}
  #divAcaoConsultar {position:absolute;left:40%;top:20%;width:23%;}
  #divAcaoListar {position:absolute;left:40%;top:40%;width:23%;}
  #divAcaoSelecionar {position:absolute;left:40%;top:60%;width:23%;}
  #divAcaoDesativar {position:absolute;left:73%;top:20%;width:23%;}
  #divAcaoReativar {position:absolute;left:73%;top:40%;width:23%;}

<?
if (PaginaSip::getInstance()->isBolAjustarTopFieldset()) {
  ?>
  #divAcaoCadastrar {top:10%;}
  #divAcaoAlterar {top:35%}
  #divAcaoExcluir {top:60%;}
  #divAcaoConsultar {top:10%;}
  #divAcaoListar {top:35%;}
  #divAcaoSelecionar {top:60%;}
  #divAcaoDesativar {top:10%;}
  #divAcaoReativar {top:35%;}
  <?
}
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
  function inicializar(){
  document.getElementById('selOrgaoSistema').focus();
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

  if (infraTrim(document.getElementById('txtEntidade').value)=='') {
  alert('Informe Entidade.');
  document.getElementById('txtEntidade').focus();
  return false;
  }

  if (infraTrim(document.getElementById('txtCaminhoBase').value)=='') {
  alert('Informe Caminho Base.');
  document.getElementById('txtCaminhoBase').focus();
  return false;
  }


  if (!document.getElementById('chkAcaoCadastrar').checked &&
  !document.getElementById('chkAcaoAlterar').checked &&
  !document.getElementById('chkAcaoConsultar').checked &&
  !document.getElementById('chkAcaoListar').checked &&
  !document.getElementById('chkAcaoSelecionar').checked &&
  !document.getElementById('chkAcaoExcluir').checked &&
  !document.getElementById('chkAcaoDesativar').checked &&
  !document.getElementById('chkAcaoReativar').checked){
  alert('Nenhuma ação selecionada.');
  return false;
  }

  return true;
  }

  function trocarOrgaoSistema(obj){
  document.getElementById('selSistema').value='null';
  obj.form.submit();
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmRecursoCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('recurso_gerar.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('35em');
    ?>
    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="m" class="infraLabelObrigatorio">Órgão do Siste<span
        class="infraTeclaAtalho">m</span>a:</label>
    <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="trocarOrgaoSistema(this);" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> >
      <?=$strItensSelOrgaoSistema?>
    </select>

    <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <select id="selSistema" name="selSistema" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelSistema?>
    </select>

    <label id="lblEntidade" for="txtEntidade" accesskey="N" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">E</span>ntidade (separar por vírgula ',' para mais de uma entidade):</label>
    <input type="text" id="txtEntidade" name="txtEntidade" class="infraText"
           value="<?=PaginaSip::tratarHTML($objRecursoPadraoDTO->getStrEntidade());?>" maxlength="1000"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <label id="lblCaminhoBase" for="txtCaminhoBase" accesskey="C" class="infraLabelObrigatorio">Caminho <span
        class="infraTeclaAtalho">B</span>ase:</label>
    <input type="text" id="txtCaminhoBase" name="txtCaminhoBase" class="infraText"
           value="<?=PaginaSip::tratarHTML($objRecursoPadraoDTO->getStrCaminhoBase());?>" maxlength="255"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"/>

    <fieldset id="fldAcoesPadrao" class="infraFieldset">
      <legend class="infraLegend">&nbsp;Ações&nbsp;</legend>

      <div id="divAcaoCadastrar" class="infraDivCheckbox">
        <input type="checkbox" id="chkAcaoCadastrar" name="chkAcaoCadastrar" <?=PaginaSip::getInstance()->setCheckbox($objRecursoPadraoDTO->getStrSinAcaoCadastrar())?> class="infraCheckbox"/>
        <label id="lblAcaoCadastrar" for="chkAcaoCadastrar" accesskey="" class="infraLabelCheckbox">cadastrar</label>
      </div>

      <div id="divAcaoAlterar" class="infraDivCheckbox">
        <input type="checkbox" id="chkAcaoAlterar" name="chkAcaoAlterar" <?=PaginaSip::getInstance()->setCheckbox($objRecursoPadraoDTO->getStrSinAcaoAlterar())?> class="infraCheckbox"/>
        <label id="lblAcaoAlterar" for="chkAcaoAlterar" accesskey="" class="infraLabelCheckbox">alterar</label>
      </div>

      <div id="divAcaoExcluir" class="infraDivCheckbox">
        <input type="checkbox" id="chkAcaoExcluir" name="chkAcaoExcluir" <?=PaginaSip::getInstance()->setCheckbox($objRecursoPadraoDTO->getStrSinAcaoExcluir())?> class="infraCheckbox"/>
        <label id="lblAcaoExcluir" for="chkAcaoExcluir" accesskey="" class="infraLabelCheckbox">excluir</label>
      </div>

      <div id="divAcaoConsultar" class="infraDivCheckbox">
        <input type="checkbox" id="chkAcaoConsultar" name="chkAcaoConsultar" <?=PaginaSip::getInstance()->setCheckbox($objRecursoPadraoDTO->getStrSinAcaoConsultar())?> class="infraCheckbox"/>
        <label id="lblAcaoConsultar" for="chkAcaoConsultar" accesskey="" class="infraLabelCheckbox">consultar</label>
      </div>

      <div id="divAcaoListar" class="infraDivCheckbox">
        <input type="checkbox" id="chkAcaoListar" name="chkAcaoListar" <?=PaginaSip::getInstance()->setCheckbox($objRecursoPadraoDTO->getStrSinAcaoListar())?> class="infraCheckbox"/>
        <label id="lblAcaoListar" for="chkAcaoListar" accesskey="" class="infraLabelCheckbox">listar</label>
      </div>

      <div id="divAcaoSelecionar" class="infraDivCheckbox">
        <input type="checkbox" id="chkAcaoSelecionar" name="chkAcaoSelecionar" <?=PaginaSip::getInstance()->setCheckbox($objRecursoPadraoDTO->getStrSinAcaoSelecionar())?> class="infraCheckbox"/>
        <label id="lblAcaoSelecionar" for="chkAcaoSelecionar" accesskey="" class="infraLabelCheckbox">selecionar</label>
      </div>

      <div id="divAcaoDesativar" class="infraDivCheckbox">
        <input type="checkbox" id="chkAcaoDesativar" name="chkAcaoDesativar" <?=PaginaSip::getInstance()->setCheckbox($objRecursoPadraoDTO->getStrSinAcaoDesativar())?> class="infraCheckbox"/>
        <label id="lblAcaoDesativar" for="chkAcaoDesativar" accesskey="" class="infraLabelCheckbox">desativar</label>
      </div>

      <div id="divAcaoReativar" class="infraDivCheckbox">
        <input type="checkbox" id="chkAcaoReativar" name="chkAcaoReativar" <?=PaginaSip::getInstance()->setCheckbox($objRecursoPadraoDTO->getStrSinAcaoReativar())?> class="infraCheckbox"/>
        <label id="lblAcaoReativar" for="chkAcaoReativar" accesskey="" class="infraLabelCheckbox">reativar</label>
      </div>


    </fieldset>

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