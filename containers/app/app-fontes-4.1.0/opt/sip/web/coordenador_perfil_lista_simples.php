<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/08/2013 - criado por mga
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

  $strParametros = '';

  if (isset($_GET['id_sistema'])) {
    $strParametros .= '&id_sistema=' . $_GET['id_sistema'];
  }

  if (isset($_GET['id_perfil'])) {
    $strParametros .= '&id_perfil=' . $_GET['id_perfil'];
  }


  $arrComandos = array();

  $objPerfilRN = new PerfilRN();

  switch ($_GET['acao']) {
    case 'coordenador_perfil_listar_simples':
      $strTitulo = 'Coordenadores do Perfil';

      $objSistemaDTO = new SistemaDTO();
      $objSistemaDTO->setBolExclusaoLogica(false);
      $objSistemaDTO->retNumIdSistema();
      $objSistemaDTO->retStrSigla();
      $objSistemaDTO->retNumIdOrgao();
      $objSistemaDTO->retStrSiglaOrgao();
      $objSistemaDTO->setNumIdSistema($_GET['id_sistema']);

      $objSistemaRN = new SistemaRN();
      $objSistemaDTO = $objSistemaRN->consultar($objSistemaDTO);

      $objPerfilDTO = new PerfilDTO();
      $objPerfilDTO->setBolExclusaoLogica(false);
      $objPerfilDTO->retNumIdPerfil();
      $objPerfilDTO->retNumIdSistema();
      $objPerfilDTO->retStrNome();
      $objPerfilDTO->setNumIdPerfil($_GET['id_perfil']);

      $objPerfilRN = new PerfilRN();
      $objPerfilDTO = $objPerfilRN->consultar($objPerfilDTO);


      $objCoordenadorPerfilDTO = new CoordenadorPerfilDTO(true);
      $objCoordenadorPerfilDTO->retTodos();
      $objCoordenadorPerfilDTO->setNumIdOrgaoSistema($objSistemaDTO->getNumIdOrgao());
      $objCoordenadorPerfilDTO->setNumIdSistema($objSistemaDTO->getNumIdSistema());
      $objCoordenadorPerfilDTO->setNumIdPerfil($objPerfilDTO->getNumIdPerfil());

      PaginaSip::getInstance()->prepararOrdenacao($objCoordenadorPerfilDTO, 'SiglaUsuario', InfraDTO::$TIPO_ORDENACAO_ASC);

      $objCoordenadorPerfilRN = new CoordenadorPerfilRN();
      $arrObjCoordenadorPerfilDTO = $objCoordenadorPerfilRN->listar($objCoordenadorPerfilDTO);

      $numRegistros = count($arrObjCoordenadorPerfilDTO);

      if ($numRegistros > 0) {
        $arrComandos[] = '<input type="button" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton" />';

        $strResultado = '';
        $strResultado .= '<table width="70%" class="infraTable" summary="Tabela de Coordenadores de Perfis cadastrados">' . "\n";
        $strResultado .= '<caption class="infraCaption">' . PaginaSip::getInstance()->gerarCaptionTabela('Coordenadores do Perfil', $numRegistros) . '</caption>';
        $strResultado .= '<tr>';
        $strResultado .= '<th class="infraTh" width="1%">' . PaginaSip::getInstance()->getThCheck() . '</th>';
        $strResultado .= '<th class="infraTh" width="15%">' . PaginaSip::getInstance()->getThOrdenacao($objCoordenadorPerfilDTO, 'Sigla', 'SiglaUsuario', $arrObjCoordenadorPerfilDTO) . '</th>';
        $strResultado .= '<th class="infraTh">' . PaginaSip::getInstance()->getThOrdenacao($objCoordenadorPerfilDTO, 'Nome', 'NomeUsuario', $arrObjCoordenadorPerfilDTO) . '</th>';
        $strResultado .= '<th class="infraTh" width="15%">' . PaginaSip::getInstance()->getThOrdenacao($objCoordenadorPerfilDTO, 'Órgão', 'SiglaOrgaoUsuario', $arrObjCoordenadorPerfilDTO) . '</th>';
        $strResultado .= '</tr>' . "\n";
        for ($i = 0; $i < $numRegistros; $i++) {
          if (($i + 2) % 2) {
            $strResultado .= '<tr class="infraTrEscura">';
          } else {
            $strResultado .= '<tr class="infraTrClara">';
          }
          $strResultado .= '<td valign="center">' . PaginaSip::getInstance()->getTrCheck($i,
              $arrObjCoordenadorPerfilDTO[$i]->getNumIdPerfil() . '#' . $arrObjCoordenadorPerfilDTO[$i]->getNumIdUsuario() . '#' . $arrObjCoordenadorPerfilDTO[$i]->getNumIdSistema(),
              $arrObjCoordenadorPerfilDTO[$i]->getNumIdPerfil()) . '</td>';

          $strResultado .= '<td align="center">' . PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrSiglaUsuario()) . '</td>';
          $strResultado .= '<td align="left">' . PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrNomeUsuario()) . '</td>';

          $strResultado .= '<td align="center">';
          $strResultado .= '<a alt="' . PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrDescricaoOrgaoUsuario()) . '" title="' . PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrDescricaoOrgaoUsuario()) . '" class="ancoraSigla">' . PaginaSip::tratarHTML($arrObjCoordenadorPerfilDTO[$i]->getStrSiglaOrgaoUsuario()) . '</a>';
          $strResultado .= '</td>';

          $strResultado .= '</tr>' . "\n";
        }
        $strResultado .= '</table>';
      }

      $arrComandos[] = '<input type="button" name="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . $strParametros . PaginaSip::getInstance()->montarAncora($_GET['id_perfil'] . '-' . $_GET['id_sistema'])) . '\';" class="infraButton" />';

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }
} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - Montar Perfil');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
  #lblOrgaoSistema {position:absolute;left:0%;top:0%;width:25%;}
  #txtOrgaoSistema {position:absolute;left:0%;top:12%;width:25%;}

  #lblSistema {position:absolute;left:0%;top:30%;width:25%;}
  #txtSistema {position:absolute;left:0%;top:42%;width:25%;}

  #lblPerfil {position:absolute;left:0%;top:60%;width:60%;}
  #txtPerfil {position:absolute;left:0%;top:72%;width:60%;}
<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
  function inicializar(){
  infraEfeitoTabelas();
  }

  function OnSubmitForm() {

  if (!validarForm()){
  return false;
  }

  return true;
  }

  function validarForm(){
  return true;
  }

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmCoordenadorPerfilListaSimples" method="post" onsubmit="return OnSubmitForm();"
        action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'] . $strParametros)?>">
    <?
    //PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();
    PaginaSip::getInstance()->abrirAreaDados('15em');
    ?>
    <label id="lblOrgaoSistema" for="txtOrgaoSistema" accesskey="r" class="infraLabelObrigatorio">Ó<span
        class="infraTeclaAtalho">r</span>gão do Sistema:</label>
    <input type="text" id="txtOrgaoSistema" name="txtOrgaoSistema" class="infraText"
           value="<?=PaginaSip::tratarHTML($objSistemaDTO->getStrSiglaOrgao());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" readonly="readonly"/>

    <label id="lblSistema" for="txtSistema" accesskey="S" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">S</span>istema:</label>
    <input type="text" id="txtSistema" name="txtSistema" class="infraText"
           value="<?=PaginaSip::tratarHTML($objSistemaDTO->getStrSigla());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" readonly="readonly"/>

    <label id="lblPerfil" for="txtPerfil" accesskey="P" class="infraLabelObrigatorio"><span
        class="infraTeclaAtalho">P</span>erfil:</label>
    <input type="text" id="txtPerfil" name="txtPerfil" class="infraText"
           value="<?=PaginaSip::tratarHTML($objPerfilDTO->getStrNome());?>"
           tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" readonly="readonly"/>
    <?
    PaginaSip::getInstance()->fecharAreaDados();
    //echo $strResultado;
    PaginaSip::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>