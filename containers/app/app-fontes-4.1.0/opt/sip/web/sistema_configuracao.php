<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 05/06/2015 - criado por bcu
 *
 * Versão do Gerador de Código: 1.34.0
 *
 * Versão no CVS: $Id$
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


  $strDesabilitar = '';
  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'sistema_configurar':
      $strTitulo = 'Configuração do Sistema SIP';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objConfiguracaoRN = new ConfiguracaoRN();

      $arrParametrosConfiguracao = $objConfiguracaoRN->getArrParametrosConfiguraveis();
      $arrObjInfraParametroDTO = array();
      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      if (isset($_POST['sbmSalvar'])) {
        foreach ($arrParametrosConfiguracao as $strNomeGrupo => $arrParametros) {
          foreach ($arrParametros as $strNome => $arrConfig) {
            $strPrefixo = $arrConfig[ConfiguracaoRN::$POS_PREFIXO];
            $objInfraParametroDTO = new InfraParametroDTO();
            $objInfraParametroDTO->setStrNome($strNome);
            $objInfraParametroDTO->setStrValor($_POST[$strPrefixo . $strNome]);
            $arrObjInfraParametroDTO[$strNome] = $objInfraParametroDTO;
          }
        }
      } else {

        $arrNomesParametros = array();
        foreach ($arrParametrosConfiguracao as $strNomeGrupo => $arrParametros) {
          $arrNomesParametros = array_merge($arrNomesParametros, array_keys($arrParametros));
        }

        $arrParametrosBanco = $objInfraParametro->listarValores($arrNomesParametros,false);
        foreach ($arrParametrosBanco as $strNome => $valor) {
          $objInfraParametroDTO = new InfraParametroDTO();
          $objInfraParametroDTO->setStrNome($strNome);
          $objInfraParametroDTO->setStrValor($valor);
          $arrObjInfraParametroDTO[$strNome] = $objInfraParametroDTO;
        }
      }
      //validar dados

      if (isset($_POST['sbmSalvar'])) {
        try {
          $objConfiguracaoRN = new ConfiguracaoRN();
          $objConfiguracaoRN->gravar(array_values($arrObjInfraParametroDTO));

          PaginaSip::getInstance()->adicionarMensagem('Parametros gravados com sucesso.');
          header('Location: ' . SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . PaginaSip::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']));
          die;
        } catch (Exception $e) {
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }


  $id = 0;
  $arrAreaDados = array();

  foreach ($arrParametrosConfiguracao as $strNomeGrupo => $arrParametros) {

    $arrAreaDados[] = array('<label class="infraLabelTitulo">'.$strNomeGrupo.'</label>', '4.5em');

    foreach ($arrParametros as $strNome => $arrConfig) {

      $strValor = isset($arrObjInfraParametroDTO[$strNome]) ? $arrObjInfraParametroDTO[$strNome]->getStrValor() : '';

      $staTipo = $arrConfig[ConfiguracaoRN::$POS_TIPO];
      $strPrefixo = $arrConfig[ConfiguracaoRN::$POS_PREFIXO];
      $bolObrigatorio = $arrConfig[ConfiguracaoRN::$POS_OBRIGATORIO];
      if (isset($arrConfig[ConfiguracaoRN::$POS_ROTULO])) {
        $strRotulo = $arrConfig[ConfiguracaoRN::$POS_ROTULO];
      } else {
        $strRotulo = $strNome;
      }

      $tamAreaDados = '5em';
      $id++;
      $strHtml = '';
      $strHtml .= '<label id="lblParam' . $id . '" for="' . $strPrefixo . $strNome . '" class="'.($bolObrigatorio ? 'infraLabelObrigatorio' : 'infraLabelOpcional').'">' . $strRotulo . ':</label>' . "\n";

      switch ($staTipo) {
        case ConfiguracaoRN::$TP_NUMERICO:
          $strHtml .= '<input type="text" id="txtParam' . $id . '" name="txt' . $strNome . '" class="infraText" value="' . $strValor . '" onkeypress="return infraMascaraNumero(this,event,9);" maxlength="9" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '" />';
          break;

        case ConfiguracaoRN::$TP_TEXTO:
        case ConfiguracaoRN::$TP_EMAIL:
          $strHtml .= '<input type="text" id="txtParam' . $id . '" name="txt' . $strNome . '" class="infraText" value="' . $strValor . '" onkeypress="return infraMascaraTexto(this,event,9);" maxlength="9" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '" />';
          break;

        case ConfiguracaoRN::$TP_COMBO:
          $strHtml .= '<select id="selParam' . $id . '" name="sel' . $strNome . '" class="infraSelect" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '">';
          $regra = $arrConfig[ConfiguracaoRN::$POS_REGRA];

          $arrValores = call_user_func('ConfiguracaoRN::' . $regra);

          $arrChaves = array_keys($arrValores);
          foreach($arrChaves as $strChave){
            $arrValores[$strChave] =  $strChave .' - '.$arrValores[$strChave];
          }

          $strHtml .= InfraINT::montarSelectArray('null', '&nbsp;', $strValor, $arrValores);
          $strHtml .= '</select>' . "\n";
          break;

        case ConfiguracaoRN::$TP_HTML:
          $tamAreaDados = '18em';
          $strHtml .= '<textarea id="txaParam' . $id . '" name="txa' . $strNome . '" rows="8" class="infraTextarea" tabindex="' . PaginaSip::getInstance()->getProxTabDados() . '">' . $strValor . '</textarea>';
          break;
      }

      $strHtml .= '<label id="lblNomeParametro'.$strNome.'" class="nomeParametro">'.$strNome.'</label>';

      $arrAreaDados[] = array($strHtml, $tamAreaDados);
    }
  }

} catch (Exception $e) {
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema() . ' - ' . $strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
if (0){
  ?>
  <style><?}
?>
    #frmConfiguracao label {position: absolute;left: 0;top: 0;}

    #frmConfiguracao label.infraLabelTitulo {position: absolute;left: 0;top: 30%;}

    #frmConfiguracao label.nomeParametro {position: absolute;left: 51%;top: 45%; color: #c0c0c0;}

    #frmConfiguracao input {position: absolute;left: 0;top: 38%;width: 50%}

    #frmConfiguracao select {position: absolute;left: 0;top: 38%;width: 50%}

    #frmConfiguracao textarea {position: absolute;left: 0;top: 2em;width: 50%}
    <?
    if (0){
    ?></style><?
}
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
if (0){
  ?>
  <script><?}
    ?>
    function inicializar() {
    }

    function validarCadastro() {

      return true;
    }

    function onSubmitForm() {
      return validarCadastro();
    }

    <?
    if (0){
    ?></script><?
}
PaginaSip::getInstance()->fecharJavaScript();
//if (isset($retEditor)) {
//  echo $retEditor->getStrInicializacao();
//}
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
?>
  <form id="frmConfiguracao" method="post" onsubmit="return onSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao'])?>">
    <?
    PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSip::getInstance()->montarAreaValidacao();

    foreach ($arrAreaDados as $areaDados) {
      PaginaSip::getInstance()->abrirAreaDados($areaDados[1]);
      echo $areaDados[0];
      PaginaSip::getInstance()->fecharAreaDados();
    }


    //PaginaSip::getInstance()->montarAreaDebug();
    PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>