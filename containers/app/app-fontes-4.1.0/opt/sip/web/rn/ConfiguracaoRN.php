<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/07/2015 - criado por bcu
 *
 * Versão do Gerador de Código: 1.35.0
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../Sip.php';

class ConfiguracaoRN extends InfraRN {

  public static $TP_NUMERICO = 1;
  public static $TP_TEXTO = 2;
  public static $TP_HTML = 3;
  public static $TP_COMBO = 4;
  public static $TP_ID = 5;
  public static $TP_EMAIL = 6;

  public static $POS_TIPO = 0;
  public static $POS_PREFIXO = 1;
  public static $POS_ENTIDADE = 2;
  public static $POS_REGRA = 3;
  public static $POS_OBRIGATORIO = 4;
  public static $POS_MULTIPLO = 5;
  public static $POS_ROTULO = 6;

  public function __construct() {
    parent::__construct();
  }

  public static function montarOpcoesHabilitadoDesabilitado(){
    return array(
      '0' => 'Desabilitado',
      '1' => 'Habilitado'
    );
  }

  public static function montarOpcoesFormatarSiglaUsuario(){
    return array(
      '0' => 'Não formatar',
      '1' => 'Minúsculas',
      '2' => 'Maiúsculas'
    );
  }

  public static function montarOpcoesFormatarNomeUsuario(){
    return array(
      '0' => 'Não formatar',
      '1' => 'Primeira letra de cada palavra em maiúscula e demais em minúsculas',
      '2' => 'Maiúsculas'
    );
  }


  public static function montarTipoCaptcha(){
    return array(
      '1' => 'Alfanumérico 4 caracteres',
      '2' => 'hCaptcha',
      '3' => 'ReCAPTCHA V2',
      '4' => 'ReCAPTCHA V3',
      '5' => 'Alfanumérico 6 caracteres'
    );
  }



  public function getArrParametrosConfiguraveis() {

    $arr = array();

    $arr['Acessos']['SIP_NUM_HISTORICO_ULTIMOS_ACESSOS'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Quantidade de registros retornada na consulta de últimos acessos');

    $arr['Acessos']['SIP_TEMPO_DIAS_HISTORICO_ACESSOS'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Por quantos dias serão mantidos os registros de acessos');

    $arr['Autenticação em 2 Fatores (2FA)']['SIP_2_FATORES_SUFIXOS_EMAIL_NAO_PERMTIDOS'] = array(
      self::$TP_TEXTO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Sufixos de email institucionais para bloqueio');

    $arr['Autenticação em 2 Fatores (2FA)']['SIP_2_FATORES_TEMPO_MINUTOS_LINK_HABILITACAO'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Informa por quantos minutos serão válidos os links de ativação/desativação do 2FA');

    $arr['Autenticação em 2 Fatores (2FA)']['SIP_2_FATORES_TEMPO_DIAS_LINK_BLOQUEIO'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Tempo em dias que serão válidos os links de bloqueio de usuário');

    $arr['Autenticação em 2 Fatores (2FA)']['SIP_2_FATORES_TEMPO_DIAS_PAUSA_USUARIO'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Tempo máximo em dias que uma pausa do 2FA pode ser cadastrada');

    $arr['Autenticação em 2 Fatores (2FA)']['SIP_2_FATORES_TEMPO_DIAS_VALIDADE_DISPOSITIVO'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Validade em dias das chaves de acesso geradas para dispositivos liberados pelo usuário');

    $arr['Autenticação em 2 Fatores (2FA)']['SIP_MSG_USUARIO_BLOQUEADO'] = array(
      self::$TP_TEXTO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Mensagem exibida quando um usuário bloqueado tenta efetuar login');

    $arr['Email']['SIP_EMAIL_ADMINISTRADOR'] = array(
      self::$TP_EMAIL, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Email do Administrador');

    $arr['Email']['SIP_EMAIL_SISTEMA'] = array(
      self::$TP_EMAIL, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Email do Sistema');

    $arr['Replicação']['SIP_FORMATAR_SIGLA_USUARIO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarOpcoesFormatarSiglaUsuario',
      self::$POS_ROTULO=>'Formatação da sigla de usuário');

    $arr['Replicação']['SIP_FORMATAR_NOME_USUARIO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarOpcoesFormatarNomeUsuario',
      self::$POS_ROTULO=>'Formatação do nome de usuário');

    $arr['Geral']['ID_USUARIO_SIP'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do usuário SIP');

    $arr['Geral']['ID_SISTEMA_SIP'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do sistema SIP');

    $arr['Geral']['ID_SISTEMA_SEI'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do sistema SEI');

    $arr['Geral']['ID_PERFIL_SIP_ADMINISTRADOR_SIP'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do perfil de administrador do SIP');

    $arr['Geral']['ID_PERFIL_SIP_ADMINISTRADOR_SISTEMA'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do perfil de administrador de sistema do SIP');

    $arr['Geral']['ID_PERFIL_SIP_BASICO'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do perfil básico do SIP');

    $arr['Geral']['ID_PERFIL_SIP_COORDENADOR_PERFIL'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do perfil de coordenação de perfil do SIP');

    $arr['Geral']['ID_PERFIL_SIP_COORDENADOR_UNIDADE'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do perfil de coordenação de unidade do SIP');

    $arr['Geral']['SIP_TIPO_CAPTCHA'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarTipoCaptcha',
      self::$POS_ROTULO=>'Tipo do mecanismo de captcha');

    return $arr;
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSip::getInstance();
  }


  private function validarTexto(InfraParametroDTO $objInfraParametroDTO, $strIdentificacao, InfraException $objInfraException) {
  }

  private function validarNumero(InfraParametroDTO $objInfraParametroDTO, $strIdentificacao, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())) {
      $objInfraParametroDTO->setStrValor(null);
    } else {
      if (!is_numeric($objInfraParametroDTO->getStrValor())) {
        $objInfraException->adicionarValidacao('Valor do parâmetro ' . $strIdentificacao . ' não é válido.');
      }
    }
  }

  private function validarIdUnidade(InfraParametroDTO $objInfraParametroDTO, $strIdentificacao, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())) {
      $objInfraParametroDTO->setStrValor(null);
    } else {

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->setBolExclusaoLogica(false);
      $objUnidadeDTO->retNumIdUnidade();
      $objUnidadeDTO->setNumIdUnidade($objInfraParametroDTO->getStrValor());

      $objUnidadeRN = new UnidadeRN();
      if ($objUnidadeRN->consultarRN0125($objUnidadeDTO) == null) {
        $objInfraException->adicionarValidacao('Valor do parâmetro ' . $strIdentificacao . ' não é uma unidade válida.');
      }
    }
  }

  private function validarIdSerie(InfraParametroDTO $objInfraParametroDTO, $strIdentificacao, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())) {
      $objInfraParametroDTO->setStrValor(null);
    } else {
      $objSerieDTO = new SerieDTO();
      $objSerieDTO->setBolExclusaoLogica(false);
      $objSerieDTO->retNumIdSerie();
      $objSerieDTO->setNumIdSerie($objInfraParametroDTO->getStrValor());

      $objSerieRN = new SerieRN();
      if ($objSerieRN->consultarRN0644($objSerieDTO) == null) {
        $objInfraException->adicionarValidacao('Valor do parâmetro ' . $strIdentificacao . ' não é um tipo de documento válido.');
      }
    }
  }

  private function validarIdModelo(InfraParametroDTO $objInfraParametroDTO, $strIdentificacao, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())) {
      $objInfraParametroDTO->setStrValor(null);
    } else {
      $objModeloDTO = new ModeloDTO();
      $objModeloDTO->setBolExclusaoLogica(false);
      $objModeloDTO->retNumIdModelo();
      $objModeloDTO->setNumIdModelo($objInfraParametroDTO->getStrValor());

      $objModeloRN = new ModeloRN();
      if ($objModeloRN->consultar($objModeloDTO) == null) {
        $objInfraException->adicionarValidacao('Valor do parâmetro ' . $strIdentificacao . ' não é um modelo de documento válido.');
      }
    }
  }

  private function validarIdTipoProcedimento(InfraParametroDTO $objInfraParametroDTO, $strIdentificacao, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())) {
      $objInfraParametroDTO->setStrValor(null);
    } else {
      $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
      $objTipoProcedimentoDTO->setBolExclusaoLogica(false);
      $objTipoProcedimentoDTO->retNumIdTipoProcedimento();
      $objTipoProcedimentoDTO->setNumIdTipoProcedimento($objInfraParametroDTO->getStrValor());

      $objTipoProcedimentoRN = new TipoProcedimentoRN();
      if ($objTipoProcedimentoRN->consultarRN0267($objTipoProcedimentoDTO) == null) {
        $objInfraException->adicionarValidacao('Valor do parâmetro ' . $strIdentificacao . ' não é um tipo de processo válido.');
      }
    }
  }

  private function validarIdTipoContato(InfraParametroDTO $objInfraParametroDTO, $strIdentificacao, InfraException $objInfraException) {
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())) {
      $objInfraParametroDTO->setStrValor(null);
    } else {
      $objTipoContatoDTO = new TipoContatoDTO();
      $objTipoContatoDTO->setBolExclusaoLogica(false);
      $objTipoContatoDTO->retNumIdTipoContato();
      $objTipoContatoDTO->setNumIdTipoContato($objInfraParametroDTO->getStrValor());

      $objTipoContatoRN = new TipoContatoRN();
      if ($objTipoContatoRN->consultarRN0336($objTipoContatoDTO) == null) {
        $objInfraException->adicionarValidacao('Valor do parâmetro ' . $strIdentificacao . ' não é um tipo de processo válido.');
      }
    }
  }

  protected function gravarControlado($arrObjInfraParametroDTO) {
    try {

      SessaoSip::getInstance()->validarAuditarPermissao('sistema_configurar');

      $arrParametrosConfiguracao = $this->getArrParametrosConfiguraveis();

      $arrParametros = array();
      foreach ($arrParametrosConfiguracao as $arr) {
        $arrParametros = array_merge($arrParametros, $arr);
      }

      //Regras de Negocio
      $objInfraException = new InfraException();
      if (InfraArray::contar($arrObjInfraParametroDTO) != InfraArray::contar($arrParametros)) {
        $objInfraException->lancarValidacao('Não foram informados todos os parâmetros do Sistema.');
      }

      foreach ($arrObjInfraParametroDTO as $objInfraParametroDTO) {

        if (!isset($arrParametros[$objInfraParametroDTO->getStrNome()])) {
          $objInfraException->lancarValidacao('Parâmetro informado "'.$objInfraParametroDTO->getStrNome().'" não esperado.');
        }

        $strIdentificacao = '"'.$arrParametros[$objInfraParametroDTO->getStrNome()][self::$POS_ROTULO].'" ('.$objInfraParametroDTO->getStrNome().')';
        $bolObrigatorio = $arrParametros[$objInfraParametroDTO->getStrNome()][ConfiguracaoRN::$POS_OBRIGATORIO];
        $strTipo = $arrParametros[$objInfraParametroDTO->getStrNome()][self::$POS_TIPO];

        if ($bolObrigatorio && InfraString::isBolVazia($objInfraParametroDTO->getStrValor())){

          $objInfraException->adicionarValidacao('Parâmetro ' . $strIdentificacao . ' não informado.');

        }else {

          switch ($strTipo) {
            case self::$TP_HTML:
            case self::$TP_EMAIL:
            case self::$TP_TEXTO:
              $this->validarTexto($objInfraParametroDTO, $strIdentificacao, $objInfraException);
              break;

            case self::$TP_ID:
              call_user_func(array($this, 'validarId' . $arrParametros[$objInfraParametroDTO->getStrNome()][self::$POS_ENTIDADE]), $objInfraParametroDTO, $strIdentificacao, $objInfraException);
              break;

            case self::$TP_NUMERICO:
              $this->validarNumero($objInfraParametroDTO, $strIdentificacao, $objInfraException);
              break;

            case self::$TP_COMBO:
              $regra = $arrParametros[$objInfraParametroDTO->getStrNome()][self::$POS_REGRA];
              $arr = call_user_func(array($this, $regra), $objInfraParametroDTO, $objInfraException);
              if (!array_key_exists($objInfraParametroDTO->getStrValor(), $arr)) {
                $objInfraException->adicionarValidacao('Valor do parâmetro ' . $strIdentificacao . ' não é válido.');
              }
              break;

            default:
              $objInfraException->lancarValidacao('Configuração do parâmetro ' . $strIdentificacao . ' não permitida.');
          }
        }
      }

      $objInfraException->lancarValidacoes();

      $objInfraParametro = new InfraParametro(BancoSip::getInstance());
      foreach ($arrObjInfraParametroDTO as $objInfraParametroDTO) {
        $objInfraParametro->setValor($objInfraParametroDTO->getStrNome(),$objInfraParametroDTO->getStrValor());
      }

    } catch (Exception $e) {
      throw new InfraException('Erro configurando parâmetros.', $e);
    }
  }
}
