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

  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  PaginaSip::getInstance()->salvarCamposPost(array('selOrgaoSistema', 'selSistema', 'selOrgaoUsuario', 'hdnIdUsuario', 'txtUsuario', 'hdnNomeUsuario'));

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'coordenador_perfil_cadastrar':
      $strTitulo = 'Novo Coordenador de Perfil';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarCoordenadorPerfil" id="sbmCadastrarCoordenadorPerfil" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=coordenador_perfil_listar') . '\';" class="infraButton" />';

      //ORGAO SISTEMA
      $numIdOrgaoSistema = PaginaSip::getInstance()->recuperarCampo('selOrgaoSistema', SessaoSip::getInstance()->getNumIdOrgaoSistema());
      $numIdSistema = PaginaSip::getInstance()->recuperarCampo('selSistema');
      $numIdOrgaoUsuario = PaginaSip::getInstance()->recuperarCampo('selOrgaoUsuario');
      $numIdUsuario = PaginaSip::getInstance()->recuperarCampo('hdnIdUsuario');
      $strSiglaUsuario = PaginaSip::getInstance()->recuperarCampo('txtUsuario');
      $strNomeUsuario = PaginaSip::getInstance()->recuperarCampo('hdnNomeUsuario');


      if (isset($_POST['sbmCadastrarCoordenadorPerfil'])) {
        try {
          $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
          $objCoordenadorPerfilDTO->setNumIdSistema($numIdSistema);
          $objCoordenadorPerfilDTO->setNumIdUsuario($numIdUsuario);
          $objCoordenadorPerfilDTO->setArrObjPerfilDTO(InfraArray::gerarArrInfraDTO('PerfilDTO', 'IdPerfil', PaginaSip::getInstance()->getArrStrItensSelecionados()));

          $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
          $objCoordenadorPerfilDTO = $objCoordenadorPerfilRN->cadastrarMultiplo($objCoordenadorPerfilDTO);
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=coordenador_perfil_listar&acao_origem=' . $_GET['acao']));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  if ($numIdSistema != '' && $numIdUsuario != '') {
    $objPerfilDTO = new PerfilDTO();
    $objPerfilDTO->retNumIdPerfil();
    $objPerfilDTO->retStrNome();
    $objPerfilDTO->retStrDescricao();
    $objPerfilDTO->setNumIdSistema($numIdSistema);

    $objPerfilDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objPerfilRN = new PerfilRN();
    $arrObjPerfilDTO = $objPerfilRN->listarAdministrados($objPerfilDTO);

    $objInfraParametro = new InfraParametro(BancoSip::getInstance());

    if ($numIdSistema == $objInfraParametro->getValor('ID_SISTEMA_SIP')) {
      $arrPerfisReservados = $objInfraParametro->listarValores(array(
        'ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA', 'ID_PERFIL_SIP_COORDENADOR_PERFIL', 'ID_PERFIL_SIP_COORDENADOR_UNIDADE', 'ID_PERFIL_SIP_ADMINISTRADOR_SIP'
      ));

      $arrObjPerfilDTOTemp = $arrObjPerfilDTO;
      $arrObjPerfilDTO = array();
      foreach ($arrObjPerfilDTOTemp as $objPerfilDTO) {
        if (!in_array($objPerfilDTO->getNumIdPerfil(), $arrPerfisReservados)) {
          $arrObjPerfilDTO[] = $objPerfilDTO;
        }
      }
    }

    $numRegistros = count($arrObjPerfilDTO);


    if ($numRegistros > 0) {
      if (!isset($_POST['sbmCadastrarCoordenadorPerfil'])) {
        $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO();
        $objCoordenadorPerfilDTO->retNumIdPerfil();
        $objCoordenadorPerfilDTO->setNumIdUsuario($numIdUsuario);
        $objCoordenadorPerfilDTO->setNumIdSistema($numIdSistema);

        $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
        $arrPerfisCoordenados = InfraArray::converterArrInfraDTO($objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO), 'IdPerfil');
      } else {
        $arrPerfisCoordenados = PaginaSip::getInstance()->getArrStrItensSelecionados();
      }


      $strResultado = '';
      $strResultado .= '<table width="90%" class="infraTable" summary="Tabela de Perfis cadastrados">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela('Perfis', $numRegistros) . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>';
      $strResultado .= '<th class="infraTh" width="20%">' . PaginaSip::getInstance()->getThOrdenacao($objPerfilDTO, 'Nome', 'Nome', $arrObjPerfilDTO) . '</th>';
      $strResultado .= '<th class="infraTh">Descrição</th>';
      $strResultado .= '</tr>' . "\n";

      for ($i = 0; $i < $numRegistros; $i++) {
        if (($i + 2) % 2) {
          $strResultado .= '<tr class="infraTrEscura">';
        } else {
          $strResultado .= '<tr class="infraTrClara">';
        }

        if (in_array($arrObjPerfilDTO[$i]->getNumIdPerfil(), $arrPerfisCoordenados)) {
          $strValor = 'S';
        } else {
          $strValor = 'N';
        }
        $strResultado .= '<td valign="top">' . PaginaSip::getInstance()->getTrCheck($i, $arrObjPerfilDTO[$i]->getNumIdPerfil(), $arrObjPerfilDTO[$i]->getStrNome(), $strValor) . '</td>';
        $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrNome()) . '</td>';
        $strResultado .= '<td>' . PaginaSip::tratarHTML($arrObjPerfilDTO[$i]->getStrDescricao()) . '</td>';
        $strResultado .= '</tr>' . "\n";
      }
      $strResultado .= '</table>';
    }
  }

  $strItensSelOrgaoSistema = OrgaoINT::montarSelectSiglaAdministrados('null', '&nbsp;', $numIdOrgaoSistema);
  $strItensSelSistema = SistemaINT::montarSelectSiglaAdministrados('null', '&nbsp;', $numIdSistema, $numIdOrgaoSistema);
  $strItensSelOrgaoUsuario = OrgaoINT::montarSelectSiglaTodos('null', '&nbsp;', $numIdOrgaoUsuario);
  //$strItensSelUsuario = UsuarioINT::montarSelectSigla('null','&nbsp;',$numIdUsuario, $numIdOrgaoUsuario);

  $strLinkAjaxUsuario = SessaoSip::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar_sigla_nome');
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Coordenador de Perfil');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
  #selOrgaoSistema {position:absolute;left:0%;top:20%;width:25%;}

  #lblSistema {position:absolute;left:0%;top:50%;width:25%;}
  #selSistema {position:absolute;left:0%;top:70%;width:25%;}

  #lblOrgaoUsuario {position:absolute;left:30%;top:0%;width:25%;}
  #selOrgaoUsuario {position:absolute;left:30%;top:20%;width:25%;}

  #lblUsuario {position:absolute;left:30%;top:50%;width:25%;}
  #txtUsuario {position:absolute;left:30%;top:70%;width:25%;}
  #lblNomeUsuario {position:absolute;left:60%;top:70%;width:30%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

  var objAjaxUsuario = null;

  function inicializar(){

  //AUTO COMPLETAR USUARIO
  objAjaxUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuario?>');
  objAjaxUsuario.carregando = true;
  objAjaxUsuario.prepararExecucao = function(){
  if (!infraSelectSelecionado('selOrgaoUsuario')){
  alert('Selecione Órgão do Usuário.');
  document.getElementById('selOrgaoUsuario').focus();
  return false;
  }
  return 'sigla='+document.getElementById('txtUsuario').value + '&idOrgao='+document.getElementById('selOrgaoUsuario').value;
  };

  objAjaxUsuario.processarResultado = function(id,descricao,complemento){

  document.getElementById('lblNomeUsuario').innerHTML = '';
  document.getElementById('hdnNomeUsuario').value = '';

  if (id != ''){

  document.getElementById('lblNomeUsuario').innerHTML = complemento;
  document.getElementById('hdnNomeUsuario').value = complemento;

  if (!this.carregando){

  if (document.getElementById('hdnInfraItensSelecionados')!=null){
  document.getElementById('hdnInfraItensSelecionados').value = '';
  }

  document.getElementById('frmCoordenadorPerfilCadastro').submit();
  }
  }
  };

  objAjaxUsuario.selecionar('<?=$numIdUsuario;?>','<?=$strSiglaUsuario;?>','<?=PaginaSip::getInstance()->formatarParametrosJavascript($strNomeUsuario, false)?>');
  objAjaxUsuario.carregando = false;

  document.getElementById('sbmCadastrarCoordenadorPerfil').focus();

  infraEfeitoTabelas();
  }

  function OnSubmitForm() {
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

  if (!infraSelectSelecionado(document.getElementById('selOrgaoUsuario'))) {
  alert('Selecione Órgão do Usuário.');
  document.getElementById('selOrgaoUsuario').focus();
  return false;
  }

  if (infraTrim(document.getElementById('txtUsuario').value)=='') {
  alert('Informe um Usuário.');
  document.getElementById('txtUsuario').focus();
  return false;
  }

  return true;
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmCoordenadorPerfilCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('coordenador_perfil_cadastro.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('10em');
    ?>

    <label id="lblOrgaoSistema" for="selOrgaoSistema" accesskey="o" class="infraLabelObrigatorio">Ó<span
        class="infraTeclaAtalho">r</span>gão do Sistema:</label>
    <select id="selOrgaoSistema" name="selOrgaoSistema" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelOrgaoSistema?>
    </select>

    <label id="lblSistema" for="selSistema" accesskey="S" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <select id="selSistema" name="selSistema" onchange="this.form.submit();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelSistema?>
    </select>

    <label id="lblOrgaoUsuario" for="selOrgaoUsuario" accesskey="o" class="infraLabelObrigatorio">Órgã<span
        class="infraTeclaAtalho">o</span> do Usuário:</label>
    <select id="selOrgaoUsuario" name="selOrgaoUsuario" onchange="objAjaxUsuario.limpar();" class="infraSelect"
            tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?>>
      <?=$strItensSelOrgaoUsuario?>
    </select>

    <label id="lblUsuario" for="txtUsuario" accesskey="u" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">U</span>suário:</label>
    <input type="text" id="txtUsuario" name="txtUsuario" class="infraText"
           value="<?=PaginaSip::tratarHTML($strSiglaUsuario)?>" maxlength="100"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> />
    <label id="lblNomeUsuario" class="infraLabelOpcional"></label>

    <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="<?=$numIdUsuario?>"/>
    <input type="hidden" id="hdnNomeUsuario" name="hdnNomeUsuario"
           value="<?=PaginaSip::tratarHTML($strNomeUsuario)?>"/>
    <input type="hidden" id="hdnIdSistema" name="hdnIdSistema" value="<?=$numIdSistema;?>"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    if ($strResultado != '') {
      PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros, true);
    }
    //PaginaSip::getInstance()->montarAreaDebug();
    //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>