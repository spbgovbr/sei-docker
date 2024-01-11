<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
*
* 07/07/2015 - criado por bcu
*
* Versгo do Gerador de Cуdigo: 1.35.0
*
* Versгo no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ConfiguracaoRN extends InfraRN {

  public static $TP_NUMERICO=1;
  public static $TP_TEXTO=2;
  public static $TP_HTML=3;
  public static $TP_COMBO=4;
  public static $TP_ID=5;
  public static $TP_EMAIL=6;

  public static $POS_TIPO=0;
  public static $POS_PREFIXO=1;
  public static $POS_ENTIDADE=2;
  public static $POS_REGRA=3;
  public static $POS_OBRIGATORIO=4;
  public static $POS_MULTIPLO=5;
  public static $POS_ROTULO=6;

  public function __construct(){
    parent::__construct();
  }
  
  public static function montarArrayObrigatoriedade()
  {
    return array(0 => 'Desabilitado', 1 => 'Opcional', 2 => 'Obrigatуrio');
  }
  public static function montarArrayRegraAcesso()
  {
    return array(0 => 'Normal', 1 => 'Somente Unidade Geradora');
  }
  public static function montarArrayBoolean(){
    return array(0 => 'Desabilitado', 1 => 'Habilitado');
  }
  public static function montarArrayMoverDocumento(){
    return array(0 => 'Desabilitado',
        1 => 'Habilitado somente para Unidades de Protocolo',
        2 => 'Habilitado para Todos os Usuбrios',
        3 => 'Habilitado somente para documento externos incluнdos por Unidades de Protocolo',
        4 => 'Habilitado para Unidades de Protocolo e documento externos incluнdos por Unidades de Protocolo');
  }
  public static function montarArrayPermissaoProtocolo(){
    return array(0 => 'Desabilitado',
        1 => 'Habilitado somente para Unidades de Protocolo',
        2 => 'Habilitado para Todos os Usuбrios');
  }
  public static function montarArrayTipoAssinatura(){
    return array(1 => 'Login/Senha e Certificado Digital',
        2 => 'Somente Login/Senha',
        3 => 'Somente Certificado Digital');
  }

  public function getArrParametrosConfiguraveis()
  {
    $arr = array();
    $arr['ID_MODELO_INTERNO_BASE_CONHECIMENTO'] = array(self::$TP_ID,'sel','modelo');
    $arr['ID_SERIE_EMAIL'] =  array(self::$TP_ID,'sel','serie');
    $arr['ID_SERIE_OUVIDORIA'] =  array(self::$TP_ID,'sel','serie');
    $arr['ID_UNIDADE_TESTE'] =  array(self::$TP_ID,'sel','unidade');
    $arr['SEI_ACESSO_FORMULARIO_OUVIDORIA'] =  array(self::$TP_COMBO,'sel',self::$POS_REGRA=>'RegraAcesso');
    $arr['SEI_EMAIL_ADMINISTRADOR'] = array(self::$TP_EMAIL,'txt');
    $arr['SEI_EMAIL_SISTEMA'] = array(self::$TP_EMAIL,'txt');
    $arr['SEI_HABILITAR_AUTENTICACAO_DOCUMENTO_EXTERNO'] = array(self::$TP_COMBO,'sel',self::$POS_REGRA=>'PermissaoProtocolo');
    $arr['SEI_HABILITAR_GRAU_SIGILO'] = array(self::$TP_COMBO,'sel',self::$POS_REGRA=>'Obrigatoriedade');
    $arr['SEI_HABILITAR_HIPOTESE_LEGAL'] = array(self::$TP_COMBO,'sel',self::$POS_REGRA=>'Obrigatoriedade');
    $arr['SEI_HABILITAR_MOVER_DOCUMENTO'] = array(self::$TP_COMBO,'sel',self::$POS_REGRA=>'MoverDocumento');
    $arr['SEI_HABILITAR_NUMERO_PROCESSO_INFORMADO'] = array(self::$TP_COMBO,'sel',self::$POS_REGRA=>'PermissaoProtocolo');
    $arr['SEI_HABILITAR_VALIDACAO_CPF_CERTIFICADO_DIGITAL'] = array(self::$TP_COMBO,'sel',self::$POS_REGRA=>'Boolean');
    $arr['SEI_HABILITAR_VALIDACAO_EXTENSAO_ARQUIVOS'] = array(self::$TP_COMBO,'sel',self::$POS_REGRA=>'Boolean');
    $arr['SEI_ID_SISTEMA'] = array(self::$TP_NUMERICO,'txt');
    $arr['SEI_MASCARA_ASSUNTO'] = array(self::$TP_TEXTO,'txt');
    $arr['SEI_MASCARA_NUMERO_PROCESSO_INFORMADO'] = array(self::$TP_TEXTO,'txt');
    $arr['SEI_MAX_TAM_MENSAGEM_OUVIDORIA'] = array(self::$TP_NUMERICO,'txt');
    $arr['SEI_NUM_FATOR_DOWNLOAD_AUTOMATICO'] = array(self::$TP_NUMERICO,'txt');
    $arr['SEI_NUM_MAX_DOCS_PASTA'] = array(self::$TP_NUMERICO,'txt');
    $arr['SEI_NUM_PAGINACAO_CONTROLE_PROCESSOS'] = array(self::$TP_NUMERICO,'txt');
    $arr['SEI_SUFIXO_EMAIL'] = array(self::$TP_TEXTO,'txt');
    $arr['SEI_TAM_MB_DOC_EXTERNO'] = array(self::$TP_NUMERICO,'txt');
    $arr['SEI_TIPO_ASSINATURA_INTERNA'] = array(self::$TP_COMBO,'sel',self::$POS_REGRA=>'TipoAssinatura');
    $arr['SEI_TIPO_AUTENTICACAO_INTERNA'] = array(self::$TP_COMBO,'sel',self::$POS_REGRA=>'TipoAssinatura');
    $arr['SEI_WS_NUM_MAX_DOCS'] = array(self::$TP_NUMERICO,'txt');
    $arr['SEI_MSG_AVISO_CADASTRO_USUARIO_EXTERNO'] = array(self::$TP_HTML,'txa');
    $arr['SEI_MSG_FORMULARIO_OUVIDORIA'] = array(self::$TP_HTML,'txa');

    return $arr;
  }
  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }


  private function validarTexto(InfraParametroDTO $objInfraParametroDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())){
      $objInfraException->adicionarValidacao('Valor do parвmetro ['.$objInfraParametroDTO->getStrNome().'] nгo informado.');
    }
  }
  private function validarNumero(InfraParametroDTO $objInfraParametroDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())){
      $objInfraException->adicionarValidacao('Valor do parвmetro ['.$objInfraParametroDTO->getStrNome().'] nгo informado.');
    } else {
      if (!is_numeric($objInfraParametroDTO->getStrValor())) {
        $objInfraException->adicionarValidacao('Valor do parвmetro ['.$objInfraParametroDTO->getStrNome().'] nгo й vбlido.');
      }
    }
  }
  private function validarIdUnidade(InfraParametroDTO $objInfraParametroDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())){
      $objInfraException->adicionarValidacao('Valor do parвmetro ['.$objInfraParametroDTO->getStrNome().'] nгo informado.');
    } else {
      $objUnidadeDTO=new UnidadeDTO();
      $objUnidadeRN=new UnidadeRN();
      $objUnidadeDTO->setNumIdUnidade($objInfraParametroDTO->getStrValor());
      if ($objUnidadeRN->contarRN0128($objUnidadeDTO)!=1) {
        $objInfraException->adicionarValidacao('Valor do parвmetro ['.$objInfraParametroDTO->getStrNome().'] nгo й uma unidade vбlida.');
      }
    }
  }
  private function validarIdSerie(InfraParametroDTO $objInfraParametroDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())){
      $objInfraException->adicionarValidacao('Valor do parвmetro ['.$objInfraParametroDTO->getStrNome().'] nгo informado.');
    }else {
      $objSerieDTO=new SerieDTO();
      $objSerieRN=new SerieRN();
      $objSerieDTO->setNumIdSerie($objInfraParametroDTO->getStrValor());
      if ($objSerieRN->contarRN0647($objSerieDTO)!=1) {
        $objInfraException->adicionarValidacao('Valor do parвmetro ['.$objInfraParametroDTO->getStrNome().'] nгo й um tipo de documento vбlido.');
      }
    }
  }
  private function validarIdModelo(InfraParametroDTO $objInfraParametroDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInfraParametroDTO->getStrValor())){
      $objInfraException->adicionarValidacao('Valor do parвmetro ['.$objInfraParametroDTO->getStrNome().'] nгo informado.');
    }else {
      $objModeloDTO=new ModeloDTO();
      $objModeloRN=new ModeloRN();
      $objModeloDTO->setNumIdModelo($objInfraParametroDTO->getStrValor());
      if ($objModeloRN->contar($objModeloDTO)!=1) {
        $objInfraException->adicionarValidacao('Valor do parвmetro ['.$objInfraParametroDTO->getStrNome().'] nгo й um modelo de documento vбlido.');
      }
    }
  }


  protected function gravarControlado($arrObjInfraParametroDTO) {
    try{

      //Valida Permissao
//      SessaoSEI::getInstance()->validarPermissao('julgamento_configurar');

      $arrParametrosConfiguracao=$this->getArrParametrosConfiguraveis();

      //Regras de Negocio
      $objInfraException = new InfraException();
      if(InfraArray::contar($arrObjInfraParametroDTO)!=InfraArray::contar($arrParametrosConfiguracao)){
        $objInfraException->lancarValidacao('Nгo foram informados todos os parвmetros do Sistema.');
      }

      foreach ($arrObjInfraParametroDTO as $objInfraParametroDTO) {
        if (!isset($arrParametrosConfiguracao[$objInfraParametroDTO->getStrNome()])){
          $objInfraException->lancarValidacao('Parвmetro informado nгo esperado.');
        }
//        $this->validarStrValor($objInfraParametroDTO, $objInfraException);
        $tipo=$arrParametrosConfiguracao[$objInfraParametroDTO->getStrNome()][self::$POS_TIPO];

        switch($tipo) {
          case self::$TP_HTML:
          case self::$TP_EMAIL:
          case self::$TP_TEXTO:
            $this->validarTexto($objInfraParametroDTO, $objInfraException);
            break;
          case self::$TP_ID:
            $entidade=$arrParametrosConfiguracao[$objInfraParametroDTO->getStrNome()][self::$POS_ENTIDADE];
            $entidade=ucfirst($entidade);
            call_user_func(array($this,'validarId'.$entidade),$objInfraParametroDTO, $objInfraException);
            break;
          case self::$TP_NUMERICO:
            $this->validarNumero($objInfraParametroDTO, $objInfraException);
            break;
          case self::$TP_COMBO:
            $regra=$arrParametrosConfiguracao[$objInfraParametroDTO->getStrNome()][self::$POS_REGRA];
            $arr=call_user_func(array($this,'montarArray'.$regra),$objInfraParametroDTO, $objInfraException);
            if (!array_key_exists($objInfraParametroDTO->getStrValor(),$arr)) {
              $objInfraException->adicionarValidacao('Valor do parвmetro ['.$objInfraParametroDTO->getStrNome().'] nгo й vбlido.');
            }
            break;
          default:
            $objInfraException->lancarValidacao('Configuraзгo do parвmetro [' . $objInfraParametroDTO->getStrNome() . '] nгo permitida.');
        }
      }
      
      $objInfraException->lancarValidacoes();

      $objInfraParametro=new InfraParametro(BancoSEI::getInstance());
      foreach ($arrObjInfraParametroDTO as $objInfraParametroDTO) {
//        $objInfraParametro->gravar($objInfraParametroDTO->getStrNome(),$objInfraParametroDTO->getStrValor());
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro configurando parвmetros.',$e);
    }
  }


}
?>