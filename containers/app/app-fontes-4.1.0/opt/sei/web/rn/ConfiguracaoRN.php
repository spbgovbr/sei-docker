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

require_once dirname(__FILE__) . '/../SEI.php';

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

  public static function montarAcessoFormularioOuvidoria() {
    return array(
      '0' => 'Normal', '1' => 'Somente unidade de Ouvidoria'
    );
  }

  public static function montarAlteracaoNivelAcessoDocumento() {
    return array(
      '0' => 'Somente unidade geradora',
      '1' => 'Qualquer unidade com tramitação no processo');
  }

  public static function montarExibirArvoreAcessoRestritoSemAcesso(){
    return array(
      '0' => 'Não exibe informando apenas acesso negado',
      '1' => 'Exibe com itens desabilitados possibilitando também a consulta do andamento'
    );
  }

  public static function montarFederacaoNomeTipoProcesso(){
    return array(
      '0' => 'Sempre assume o tipo padrão do SEI Federação',
      '1' => 'Tenta utilizar um tipo de processo da instalação local com o mesmo nome'
    );
  }

  public static function montarFederacaoNumeroProcesso(){
    return array(
      '0' => 'Sempre gera um novo número para o processo recebido',
      '1' => 'Tenta utilizar o mesmo número do processo recebido gerando novo número apenas em caso de conflito'
    );
  }

  public static function montarHabilitarGrauSigilo() {
    return array(
      '0' => 'Desabilitado',
      '1' => 'Opcional',
      '2' => 'Obrigatório'
    );
  }

  public static function montarHabilitarHipoteseLegal() {
    return array(
      '0' => 'Desabilitado',
      '1' => 'Opcional',
      '2' => 'Obrigatório'
    );
  }

  public static function montarHabilitarMoverDocumento() {
    return array(
      '0' => 'Desabilitado',
      '1' => 'Habilitado somente para unidades de protocolo',
      '2' => 'Habilitado para todos os usuários',
      '3' => 'Habilitado somente para documentos externos incluídos por unidades de protocolo',
      '4' => 'Habilitado para unidades de protocolo e nas demais unidades disponível apenas em documentos externos incluídos por unidades de protocolo'
    );
  }

  public static function montarHabilitarAutenticacaoDocumentoExterno() {
    return array(
      '0' => 'Desabilitado',
      '1' => 'Habilitado somente para unidades de protocolo',
      '2' => 'Habilitado para todos os usuários'
    );
  }

  public static function montarTipoAssinaturaInterna() {
    return array(
      '1' => 'Login/Senha e Certificado Digital',
      '2' => 'Somente Login/Senha',
      '3' => 'Somente Certificado Digital'
    );
  }

  public static function montarTipoAutenticacaoInterna() {
    return array(
      '1' => 'Login/Senha e Certificado Digital',
      '2' => 'Somente Login/Senha',
      '3' => 'Somente Certificado Digital'
    );
  }

  public static function montarHabilitarNumeroProcessoInformado() {
    return array(
      '0' => 'Desabilitado',
      '1' => 'Habilitado somente para unidades de protocolo',
      '2' => 'Habilitado para todos os usuários',
      '3' => 'inclusão habilitada para todos os usuários e alteração apenas por unidades de protocolo'
    );
  }

  public static function montarHabilitarVerificacaoRepositorio() {
    return array(
      '0' => 'Desabilitado',
      '1' => 'Verifica a integridade do documento em cada acesso'
    );
  }

  public static function montarSinalizacaoProcesso(){
    return array(
      '0' => 'Quando qualquer pessoa na unidade acessar o processo',
      '1' => 'Somente quando o processo for acessado pela pessoa para a qual está atribuído na unidade'
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

  public static function montarWsPlanoTrabalhoInclusaoDocumento(){
    return array(
      '0' => 'valida apenas se na chamada do serviço for informado um item do plano para associação',
      '1' => 'sempre valida'
    );
  }


  public function getArrParametrosConfiguraveis() {

    $arr = array();

    $arr['Protocolos']['SEI_HABILITAR_GRAU_SIGILO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarHabilitarGrauSigilo',
      self::$POS_ROTULO=>'Grau de Sigilo');

    $arr['Protocolos']['SEI_HABILITAR_HIPOTESE_LEGAL'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarHabilitarHipoteseLegal',
      self::$POS_ROTULO=>'Hipótese Legal');

    $arr['Processos']['SEI_HABILITAR_NUMERO_PROCESSO_INFORMADO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarHabilitarNumeroProcessoInformado',
      self::$POS_ROTULO=>'Permitir informar o número de processo e data de autuação');

    $arr['Processos']['SEI_MASCARA_NUMERO_PROCESSO_INFORMADO'] = array(
      self::$TP_TEXTO, 'txt',
      self::$POS_ROTULO=>'Máscara para número de processo informado');

    $arr['Processos']['SEI_EXIBIR_ARVORE_RESTRITO_SEM_ACESSO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarExibirArvoreAcessoRestritoSemAcesso',
      self::$POS_ROTULO => 'Montar árvore para processo restrito sem acesso');

    $arr['Processos']['SEI_NUM_PAGINACAO_CONTROLE_PROCESSOS'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Número de processos para paginação no Controle de Processos');

    $arr['Processos']['SEI_SINALIZACAO_PROCESSO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarSinalizacaoProcesso',
      self::$POS_ROTULO=>'Remoção de sinalizações em processos');

    $arr['Documentos']['SEI_ALTERACAO_NIVEL_ACESSO_DOCUMENTO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarAlteracaoNivelAcessoDocumento',
      self::$POS_ROTULO => 'Alteração do nível de acesso de documento');

    $arr['Documentos']['SEI_HABILITAR_MOVER_DOCUMENTO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarHabilitarMoverDocumento',
      self::$POS_ROTULO => 'Mover documento');

    $arr['Documentos']['SEI_HABILITAR_VALIDACAO_EXTENSAO_ARQUIVOS'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarOpcoesHabilitadoDesabilitado',
      self::$POS_ROTULO=>'Validar extensões de arquivos');

    $arr['Documentos']['SEI_HABILITAR_VERIFICACAO_REPOSITORIO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarHabilitarVerificacaoRepositorio',
      self::$POS_ROTULO=>'Verificação de documentos do repositório');

    $arr['Documentos']['SEI_TAM_MB_DOC_EXTERNO'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Tamanho máximo (Mb) para documentos externos');

    $arr['Documentos']['SEI_TAM_MB_CORRETOR_DESABILITADO'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Tamanho padrão (Mb) do documento para desabilitar automaticamente o corretor ortográfico');

    $arr['Documentos']['SEI_NUM_MAX_DOCS_PASTA'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Número de documentos por pasta na árvore de processo');

    $arr['Assinatura/Autenticação']['SEI_TIPO_ASSINATURA_INTERNA'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarTipoAssinaturaInterna',
      self::$POS_ROTULO=>'Tipos de assinatura disponíveis');

    $arr['Assinatura/Autenticação']['SEI_HABILITAR_AUTENTICACAO_DOCUMENTO_EXTERNO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarHabilitarAutenticacaoDocumentoExterno',
      self::$POS_ROTULO=>'Autenticação de documentos externos');

    $arr['Assinatura/Autenticação']['SEI_TIPO_AUTENTICACAO_INTERNA'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarTipoAutenticacaoInterna',
      self::$POS_ROTULO=>'Tipos de autenticação disponíveis');

    $arr['Assinatura/Autenticação']['SEI_HABILITAR_VALIDACAO_CPF_CERTIFICADO_DIGITAL'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarOpcoesHabilitadoDesabilitado',
      self::$POS_ROTULO=>'Validação do CPF de certificados digitais');

    $arr['Blocos']['SEI_NUM_MAX_PROTOCOLOS_BLOCO'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Número máximo de protocolos em um bloco');

    $arr['Consulta Processual']['SEI_HABILITAR_CONSULTA_PROCESSUAL'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarOpcoesHabilitadoDesabilitado',
      self::$POS_ROTULO=>'Consulta Processual externa');

    $arr['Bases de Conhecimento']['ID_MODELO_INTERNO_BASE_CONHECIMENTO'] = array(
      self::$TP_ID, 'sel', 'Modelo',
      self::$POS_ROTULO=>'Modelo para Bases de Conhecimento');

    $arr['Usuário Externo']['SEI_MSG_AVISO_CADASTRO_USUARIO_EXTERNO'] = array(
      self::$TP_HTML, 'txa',
      self::$POS_ROTULO=>'Texto exibido antes do cadastro de usuários externos');

    $arr['Usuário Externo']['SEI_HABILITAR_ACESSO_EXTERNO_INCLUSAO_DOCUMENTO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarOpcoesHabilitadoDesabilitado',
      self::$POS_ROTULO=>'Liberação de acessos externos com possibilidade de inclusão de documento');

    $arr['Arquivo']['SEI_DATA_CORTE_SINALIZADOR_PARA_ARQUIVAMENTO'] = array(
      self::$TP_TEXTO, 'txt',
      self::$POS_ROTULO=>'Data de corte para o sinalizador de arquivamento');

    $arr['Arquivo']['ID_TIPO_PROCEDIMENTO_ELIMINACAO'] = array(
      self::$TP_ID, 'sel', 'TipoProcedimento',
      self::$POS_ROTULO=>'Tipo de processo para Eliminação');

    $arr['Arquivo']['SEI_NUM_DIAS_PRAZO_ELIMINACAO'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_ROTULO=>'Número de dias para permitir eliminar documentos de um edital de eliminação após publicação');

    $arr['Arquivo']['SEI_MASCARA_ASSUNTO'] = array(
      self::$TP_TEXTO, 'txt',
      self::$POS_ROTULO=>'Máscara para assuntos do plano de classificação');

    $arr['Email']['ID_SERIE_EMAIL'] = array(
      self::$TP_ID, 'sel', 'Serie',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Tipo de documento para Email');

    $arr['Email']['SEI_EMAIL_ADMINISTRADOR'] = array(
      self::$TP_EMAIL, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Email do Administrador');

    $arr['Email']['SEI_EMAIL_SISTEMA'] = array(
      self::$TP_EMAIL, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Email do Sistema');

    $arr['Email']['SEI_SUFIXO_EMAIL'] = array(
      self::$TP_TEXTO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Sufixo adicionado em emails enviados pelo sistema (variável @sufixo_email@)');

    $arr['Email']['SEI_EMAIL_CONVERTER_ANEXO_HTML_PARA_PDF'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarOpcoesHabilitadoDesabilitado',
      self::$POS_ROTULO=>'Converter anexos de email de HTML para PDF');

    $arr['SEI Federação']['SEI_FEDERACAO_NUMERO_PROCESSO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarFederacaoNumeroProcesso',
      self::$POS_ROTULO => 'Controle do número de processo para processos recebidos do SEI Federação');

    $arr['SEI Federação']['SEI_FEDERACAO_NOME_TIPO_PROCESSO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarFederacaoNomeTipoProcesso',
      self::$POS_ROTULO => 'Controle do tipo de processo para processos recebidos do SEI Federação');

    $arr['SEI Federação']['SEI_ID_TIPO_PROCEDIMENTO_FEDERACAO'] = array(
      self::$TP_ID, 'sel', 'TipoProcedimento',
      self::$POS_ROTULO=>'Tipo de processo padrão do SEI Federação');

    $arr['SEI Federação']['ID_TIPO_CONTATO_FEDERACAO'] = array(
      self::$TP_ID, 'sel', 'TipoContato',
      self::$POS_ROTULO=>'Tipo de contato para cadastro do SEI Federação');

    $arr['Ouvidoria']['SEI_ACESSO_FORMULARIO_OUVIDORIA'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_REGRA => 'montarAcessoFormularioOuvidoria',
      self::$POS_ROTULO => 'Acesso ao formulário de ouvidoria');

    $arr['Ouvidoria']['SEI_MSG_FORMULARIO_OUVIDORIA'] = array(
      self::$TP_HTML, 'txa',
      self::$POS_ROTULO=>'Texto para o formulário de ouvidoria');

    $arr['Ouvidoria']['SEI_MAX_TAM_MENSAGEM_OUVIDORIA'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_ROTULO=>'Tamanho máximo para mensagens da ouvidoria');

    $arr['Ouvidoria']['ID_SERIE_OUVIDORIA'] = array(
      self::$TP_ID, 'sel', 'Serie',
      self::$POS_ROTULO => 'Tipo de documento para o formulário de ouvidoria');

    $arr['Ouvidoria']['ID_TIPO_PROCEDIMENTO_OUVIDORIA_EQUIVOCO'] = array(
      self::$TP_ID, 'sel', 'TipoProcedimento',
      self::$POS_ROTULO=>'Tipo de processo da ouvidoria que representa um contato equivocado');

    $arr['Ouvidoria']['ID_TIPO_CONTATO_OUVIDORIA'] = array(
      self::$TP_ID, 'sel', 'TipoContato',
      self::$POS_ROTULO=>'Tipo de contato para cadastro da ouvidoria');

    $arr['Web Services']['SEI_WS_NUM_MAX_DOCS'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Número máximo de documentos que podem ser gerados simultaneamente em um processo por web services');

    $arr['Web Services']['SEI_WS_PLANO_TRABALHO_INCLUSAO_DOCUMENTO'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarWsPlanoTrabalhoInclusaoDocumento',
      self::$POS_ROTULO=>'Validar restrições do Plano de Trabalho em web services');

    $arr['Geral']['ID_UNIDADE_TESTE'] = array(
      self::$TP_ID, 'sel', 'Unidade',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Unidade de Teste');

    $arr['Geral']['SEI_ID_SISTEMA'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do sistema SEI no SIP');

    $arr['Geral']['ID_USUARIO_SEI'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do usuário SEI');

    $arr['Geral']['ID_PAIS_BRASIL'] = array(
      self::$TP_NUMERICO, 'txt',
      self::$POS_OBRIGATORIO => true,
      self::$POS_ROTULO=>'Identificador do país Brasil');

    $arr['Geral']['ID_TIPO_CONTATO_TEMPORARIO'] = array(
      self::$TP_ID, 'sel', 'TipoContato',
      self::$POS_ROTULO=>'Tipo de contato para cadastro temporário');

    $arr['Geral']['ID_TIPO_CONTATO_ORGAOS'] = array(
      self::$TP_ID, 'sel', 'TipoContato',
      self::$POS_ROTULO=>'Tipo de contato para cadastro de órgãos');

    $arr['Geral']['ID_TIPO_CONTATO_SISTEMAS'] = array(
      self::$TP_ID, 'sel', 'TipoContato',
      self::$POS_ROTULO=>'Tipo de contato para cadastro de sistemas');

    $arr['Geral']['SEI_TIPO_CAPTCHA'] = array(
      self::$TP_COMBO, 'sel',
      self::$POS_OBRIGATORIO => true,
      self::$POS_REGRA => 'montarTipoCaptcha',
      self::$POS_ROTULO=>'Tipo do mecanismo de captcha');

    return $arr;
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
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

      SessaoSEI::getInstance()->validarAuditarPermissao('sistema_configurar');

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

      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      foreach ($arrObjInfraParametroDTO as $objInfraParametroDTO) {
        $objInfraParametro->setValor($objInfraParametroDTO->getStrNome(),$objInfraParametroDTO->getStrValor());
      }

    } catch (Exception $e) {
      throw new InfraException('Erro configurando parâmetros.', $e);
    }
  }
}
