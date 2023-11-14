<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/11/2018 - criado por mga
*
*/

require_once dirname(__FILE__).'/../Sip.php';

class LoginINT extends InfraINT {

  public static $ACAO_DADOS_USUARIO = 1;
  public static $ACAO_LOGAR_SENHA = 2;
  public static $ACAO_VALIDAR_CODIGO = 3;
  public static $ACAO_INSTRUCOES_2_FATORES = 4;
  public static $ACAO_LOGAR_CONFIGURAR_2_FATORES = 5;
  public static $ACAO_ATIVAR_2_FATORES = 6;
  public static $ACAO_DESATIVAR_2_FATORES = 7;
  public static $ACAO_GERAR_2_FATORES = 8;
  public static $ACAO_AVISAR_2_FATORES = 9;

  public static function montarSelectStaLogin($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objLoginRN = new LoginRN();
    $arrObjSituacaoLoginDTO = $objLoginRN->listarValoresSituacao();
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjSituacaoLoginDTO, 'StaSituacao', 'Descricao');
  }

  public static function obterSistema($strSiglaSistema, $strSiglaOrgaoSistema){

    $objSistemaDTO = new SistemaDTO();
    $objSistemaDTO->retNumIdSistema();
    $objSistemaDTO->retStrSigla();
    $objSistemaDTO->retStrSiglaOrgao();

    $objSistemaRN = new SistemaRN();
    $arrObjSistemaDTO = $objSistemaRN->listar($objSistemaDTO);

    $objSistemaDTO = null;
    foreach($arrObjSistemaDTO as $dto){
      if ($dto->getStrSigla()==$strSiglaSistema && $dto->getStrSiglaOrgao()==$strSiglaOrgaoSistema){
        $dto2 = new SistemaDTO();
        $dto2->retNumIdSistema();
        $dto2->retStrSigla();
        $dto2->retStrDescricao();
        $dto2->retStrSiglaOrgao();
        $dto2->retStrDescricaoOrgao();
        $dto2->retStrLogo();
        $dto2->retStrPaginaInicial();
        $dto2->retStrSta2Fatores();
        $dto2->retStrEsquemaLogin();
        $dto2->setNumIdSistema($dto->getNumIdSistema());
        $objSistemaDTO = $objSistemaRN->consultar($dto2);
        break;
      }
    }

    if ($objSistemaDTO==null){
      throw new InfraException('Sistema \''.$strSiglaSistema.'/'.$strSiglaOrgaoSistema.'\' inválido.',null, null, false);
    }

    return $objSistemaDTO;
  }

  public static function inicializarSession(){

    if (!isset($_SESSION['SIP_ID_USUARIO'])){
      $_SESSION['SIP_ID_USUARIO'] = 0;
    }

    //if (!isset($_SESSION['SIP_ACAO_ORIGEM'])){
    //  $_SESSION['SIP_ACAO_ORIGEM'] = 0;
    //}

    if (!isset($_SESSION['SIP_2_FATORES'])){
      $_SESSION['SIP_2_FATORES'] = 0;
    }

    if (!isset($_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'])){
      $_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'] = 0;
    }

    if (!isset($_SESSION['SIP_ID_CODIGO_ACESSO_CONFIRMADO'])){
      $_SESSION['SIP_ID_CODIGO_ACESSO_CONFIRMADO'] = 0;
    }

    if (!isset($_SESSION['SIP_NUM_FALHA_LOGIN'])){
      $_SESSION['SIP_NUM_FALHA_LOGIN'] = 0;
    }

    if (!isset($_SESSION['SIP_NUM_FALHA_CODIGO_ACESSO'])){
      $_SESSION['SIP_NUM_FALHA_CODIGO_ACESSO'] = 0;
    }

    if (!isset($_SESSION['SIP_ID_CODIGO_ACESSO_CONFIGURACAO'])){
      $_SESSION['SIP_ID_CODIGO_ACESSO_CONFIGURACAO'] = 0;
    }

    if (!isset($_SESSION['SIP_CAPTCHA'])){
      $_SESSION['SIP_CAPTCHA'] = '';
    }
  }

  public static function limparSession($bolTentativas = true){
    $_SESSION['SIP_ID_USUARIO'] = 0;
    //$_SESSION['SIP_ACAO_ORIGEM'] = 0;
    $_SESSION['SIP_2_FATORES'] = 0;
    $_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'] = 0;
    $_SESSION['SIP_ID_CODIGO_ACESSO_CONFIRMADO'] = 0;
    $_SESSION['SIP_ID_CODIGO_ACESSO_CONFIGURACAO'] = 0;

    unset($_SESSION['DADOS_CERT']);

    if ($bolTentativas) {
      $_SESSION['SIP_NUM_FALHA_LOGIN'] = 0;
      $_SESSION['SIP_NUM_FALHA_CODIGO_ACESSO'] = 0;
      $_SESSION['SIP_CAPTCHA'] = '';
    }
  }

  public static function verificarLogin(){
    return ($_SESSION['SIP_ID_USUARIO'] != 0 && ($_SESSION['SIP_2_FATORES'] == 0 || $_SESSION['SIP_ID_CODIGO_ACESSO_VALIDACAO'] == $_SESSION['SIP_ID_CODIGO_ACESSO_CONFIRMADO']));
  }

  public static function redirecionarSistema($objLoginDTO){

    self::limparSession();

    $objInfraException = new InfraException();

    if ($objLoginDTO->getStrPaginaInicialSistema() == null) {
      $objInfraException->lancarValidacao('Sistema não possui página inicial cadastrada.');
    }

    $strPaginaInicial = $objLoginDTO->getStrPaginaInicialSistema();

    if (strpos($strPaginaInicial, '?') === false) {
      $strPar = '?';
    } else {
      $strPar = '&';
    }

    $strPar .= 'infra_sip=true';
    $strPar .= '&id_sistema='.$objLoginDTO->getNumIdSistema();
    $strPar .= '&id_usuario='.$objLoginDTO->getNumIdUsuario();
    $strPar .= '&id_login='.$objLoginDTO->getStrIdLogin();

    if ($_GET['modulo_sistema'] != '') {
      $strPar .= '&modulo_sistema='.$_GET['modulo_sistema'];
    }

    if ($_GET['menu_sistema'] != '') {
      $strPar .= '&menu_sistema='.$_GET['menu_sistema'];
    }

    if ($_GET['infra_url'] != '') {
      $strPar .= '&infra_url='.$_GET['infra_url'];
    }

    header('Location: '.$strPaginaInicial.$strPar);
    die;
  }

  public static function montarDivCaptcha($strCodigoParaGeracaoCaptcha){
    return '<div id="divCaptcha">
              <div id="divCaptchaConfirmacao">
                <label id="lblCaptchaConfirmacao" for="txtCaptchaConfirmacao" class="infraLabelObrigatorio">Confirmação:</label>
                <input type="text" id="txtCaptchaConfirmacao" name="txtCaptchaConfirmacao" class="infraText" maxlength="4" value="" tabindex="'.PaginaLogin::getInstance()->getProxTabDados().'"/>
              </div>
              <div id="divCaptchaImagem">'.
                InfraCaptcha::montarLabel($strCodigoParaGeracaoCaptcha).
                InfraCaptcha::montarAudio($strCodigoParaGeracaoCaptcha,'audioCaptchaMedia','srcAudioCaptcha','imgCaptchaAudio',PaginaLogin::getInstance()->getDiretorioSvgLocal().'/audio.svg').
              '</div>
            </div>';
  }

  public static function montarDivCaptchaBootstrap($strCodigoParaGeracaoCaptcha){
    return '
                            <div id="divCaptcha" class="pt-3 px-0">
                              <div class="container px-0">
                                <div class="row px-0">
                                  <div class="col-6 d-flex">
      <label id="lblCaptcha" accesskey="" class="infraLabelObrigatorio align-self-end  d-flex m-0">
      <img class="align-self-end" src="data:image/png;base64,'.base64_encode(InfraCaptcha::gerarImagem($strCodigoParaGeracaoCaptcha)).'" alt="Não foi possível carregar a imagem de confirmação" /></label>
                                  </div>
                                  <div class="col-6">
                                    <div class="d-block">
                                      <label id="lblCodigo" for="txtCaptcha" accesskey="" class="infraLabelObrigatorio" style="font-size:1em;">Código de confirmação:</label>
                                    </div>
                                    <div class="d-block">
                                      <input type="text" id="txtCaptchaConfirmacao" name="txtCaptchaConfirmacao" class="form-control" style="font-size: 1.4rem;text-align: center"  maxlength="4" value="" tabindex="'.PaginaLogin::getInstance()->getProxTabDados().'"/>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
 ';
  }

  public static function adicionarMensagemEnvioLink($strEmail){
    $objInfraParametro = new InfraParametro(BancoSip::getInstance());
    $numMinutosLink = $objInfraParametro->getValor('SIP_2_FATORES_TEMPO_MINUTOS_LINK_HABILITACAO');
    PaginaLogin::getInstance()->setStrMensagem('Um e-mail foi enviado para '.$strEmail.' contendo um link com validade de '.$numMinutosLink.' minutos para conclusão da solicitação.\n\nCaso não tenha recebido verifique a caixa de spam.');
  }
}
?>