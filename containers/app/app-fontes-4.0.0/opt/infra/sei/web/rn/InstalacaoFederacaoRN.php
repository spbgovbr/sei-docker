<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/04/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class InstalacaoFederacaoRN extends InfraRN {

  private static $objInstalacaoDTOLocal = null;

  public static $TI_LOCAL = 'L';
  public static $TI_ENVIADA = 'E';
  public static $TI_RECEBIDA = 'R';
  public static $TI_REPLICADA = 'P';

  public static $EI_ANALISE = 'A';
  public static $EI_LIBERADA = 'L';
  public static $EI_BLOQUEADA = 'B';

  public static $AI_NENHUM = 'N';
  public static $AI_EMAIL_ENVIADO = 'E';
  public static $AI_IGNORADO = 'I';

  public static $TC_OK = 'OK';
  public static $TC_INDISPONIVEL = 'Indisponível';
  public static $TC_ERRO = 'Erro';

  public function __construct(){

    parent::__construct();

    if (self::$objInstalacaoDTOLocal==null){
      self::$objInstalacaoDTOLocal = $this->obterInstalacaoLocal();
    }
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function autenticarWS($Identificacao){
    try{

      $objInfraException = new InfraException();

      $this->verificarFederacao($Identificacao);

      $Remetente = $Identificacao->Remetente;
      $Destinatario = $Identificacao->Destinatario;
      $Instalacao = $Remetente->Instalacao;
      $strIdentificacaoRemota = $Instalacao->Sigla.' ('.InfraUtil::formatarCnpj($Instalacao->Cnpj).')';

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retStrSigla();
      $objInstalacaoFederacaoDTO->retStrDescricao();
      $objInstalacaoFederacaoDTO->retStrStaEstado();
      $objInstalacaoFederacaoDTO->retStrDescricaoEstado();
      $objInstalacaoFederacaoDTO->retStrEndereco();
      $objInstalacaoFederacaoDTO->retStrSinAtivo();
      $objInstalacaoFederacaoDTO->retDblCnpj();
      $objInstalacaoFederacaoDTO->retStrChavePrivada();
      $objInstalacaoFederacaoDTO->retStrChavePublicaRemota();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($Instalacao->IdInstalacaoFederacao);

      $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

      if ($objInstalacaoFederacaoDTO==null){
        $objInfraException->lancarValidacao('Instalação '.$strIdentificacaoRemota.' não encontrada na instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

      if ($objInstalacaoFederacaoDTO->getDblCnpj()!=$Instalacao->Cnpj){
        $objInfraException->lancarValidacao('CNPJ da instalação '.$Instalacao->Sigla.' diferente na instalação remota ('.InfraUtil::formatarCnpj($objInstalacaoFederacaoDTO->getDblCnpj()).').');
      }

      if ($objInstalacaoFederacaoDTO->getStrEndereco()!=$Instalacao->Endereco){
        $objInfraException->lancarValidacao('Endereço da instalação '.$strIdentificacaoRemota.' diferente na instalação remota ('.$objInstalacaoFederacaoDTO->getStrEndereco().').');
      }

      if ($objInstalacaoFederacaoDTO->getStrSinAtivo()=='N'){
        $objInfraException->lancarValidacao('Instalação '.$Instalacao->Sigla.' desativada na instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

      if ($objInstalacaoFederacaoDTO->getStrStaEstado() == self::$EI_ANALISE) {
        $objInfraException->lancarValidacao('Instalação '.$Instalacao->Sigla.' em análise na instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

      if ($objInstalacaoFederacaoDTO->getStrStaEstado() == self::$EI_BLOQUEADA) {
        $objInfraException->lancarValidacao('Instalação '.$Instalacao->Sigla.' bloqueada na instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

      $Destino = $Destinatario->Instalacao;

      if ($Destino->IdInstalacaoFederacao != $this->obterIdInstalacaoFederacaoLocal()){
        $objInfraException->lancarValidacao('Identificador do SEI Federação da instalação '.$this->obterSiglaInstalacaoLocal().' diferente na instalação origem.');
      }

      if ($Destino->Cnpj != $this->obterCnpjInstalacaoLocal()){
        $objInfraException->lancarValidacao('CNPJ da instalação '.$this->obterSiglaInstalacaoLocal().' diferente na instalação origem ('.InfraUtil::formatarCnpj($this->obterCnpjInstalacaoLocal()).').');
      }

      $this->descriptografarHash($objInstalacaoFederacaoDTO->getStrChavePrivada(), $objInstalacaoFederacaoDTO->getStrChavePublicaRemota(), $Instalacao->Hash );

      if ($Instalacao->Sigla!=$objInstalacaoFederacaoDTO->getStrSigla() || $Instalacao->Descricao!=$objInstalacaoFederacaoDTO->getStrDescricao()){
        $objInstalacaoFederacaoDTOSincronizacao = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTOSincronizacao->setStrSigla($Instalacao->Sigla);
        $objInstalacaoFederacaoDTOSincronizacao->setStrDescricao($Instalacao->Descricao);
        $objInstalacaoFederacaoDTOSincronizacao->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $this->sincronizar($objInstalacaoFederacaoDTOSincronizacao);
      }

      $Orgao = $Remetente->Orgao;

      $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
      $objOrgaoFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
      $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($Orgao->IdOrgaoFederacao);
      $objOrgaoFederacaoDTO->setStrSigla($Orgao->Sigla);
      $objOrgaoFederacaoDTO->setStrDescricao($Orgao->Descricao);

      $objOrgaoFederacaoRN = new OrgaoFederacaoRN();
      $objOrgaoFederacaoRN->sincronizar($objOrgaoFederacaoDTO);

      $Unidade = $Remetente->Unidade;
      
      $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
      $objUnidadeFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
      $objUnidadeFederacaoDTO->setStrIdUnidadeFederacao($Unidade->IdUnidadeFederacao);
      $objUnidadeFederacaoDTO->setStrSigla($Unidade->Sigla);
      $objUnidadeFederacaoDTO->setStrDescricao($Unidade->Descricao);

      $objUnidadeFederacaoRN = new UnidadeFederacaoRN();
      $objUnidadeFederacaoRN->sincronizar($objUnidadeFederacaoDTO);


      $Usuario = $Remetente->Usuario;

      $objUsuarioFederacaoDTO = new UsuarioFederacaoDTO();
      $objUsuarioFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
      $objUsuarioFederacaoDTO->setStrIdUsuarioFederacao($Usuario->IdUsuarioFederacao);
      $objUsuarioFederacaoDTO->setStrSigla($Usuario->Sigla);
      $objUsuarioFederacaoDTO->setStrNome($Usuario->Nome);

      $objUsuarioFederacaoRN = new UsuarioFederacaoRN();
      $objUsuarioFederacaoRN->sincronizar($objUsuarioFederacaoDTO);

      SessaoSEIFederacao::getInstance()->logar($objInstalacaoFederacaoDTO, $objOrgaoFederacaoDTO, $objUnidadeFederacaoDTO, $objUsuarioFederacaoDTO);

      return $objInstalacaoFederacaoDTO;

    }catch (Exception $e){
      throw new InfraException('Erro autenticando instalação.', $e);
    }
  }

  public function verificarFederacao($Identificacao){
    try{
      $objInfraException = new InfraException();

      if (!ConfiguracaoSEI::getInstance()->getValor('Federacao','Habilitado',false,false)) {
        $objInfraException->lancarValidacao('SEI Federação desabilitado na instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

      if (explode('.', SEI_FEDERACAO_VERSAO)[0] != explode('.', $Identificacao->VersaoSeiFederacao)[0]){
        $objInfraException->lancarValidacao('Versão do SEI Federação da instalação '.$Identificacao->Remetente->Instalacao->Sigla.' '.$Identificacao->VersaoSeiFederacao.' incompatível com a versão da instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

    }catch (Exception $e){
      throw new InfraException('Erro verificando SEI Federação.', $e);
    }
  }

  public function listarValoresTipo(){
    try {

      $arrObjTipoInstalacaoFederacaoDTO = array();

      $objTipoInstalacaoFederacaoDTO = new TipoInstalacaoFederacaoDTO();
      $objTipoInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_LOCAL);
      $objTipoInstalacaoFederacaoDTO->setStrDescricao('Local');
      $arrObjTipoInstalacaoFederacaoDTO[] = $objTipoInstalacaoFederacaoDTO;

      $objTipoInstalacaoFederacaoDTO = new TipoInstalacaoFederacaoDTO();
      $objTipoInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_ENVIADA);
      $objTipoInstalacaoFederacaoDTO->setStrDescricao('Enviada');
      $arrObjTipoInstalacaoFederacaoDTO[] = $objTipoInstalacaoFederacaoDTO;

      $objTipoInstalacaoFederacaoDTO = new TipoInstalacaoFederacaoDTO();
      $objTipoInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_RECEBIDA);
      $objTipoInstalacaoFederacaoDTO->setStrDescricao('Recebida');
      $arrObjTipoInstalacaoFederacaoDTO[] = $objTipoInstalacaoFederacaoDTO;

      $objTipoInstalacaoFederacaoDTO = new TipoInstalacaoFederacaoDTO();
      $objTipoInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_REPLICADA);
      $objTipoInstalacaoFederacaoDTO->setStrDescricao('Replicada');
      $arrObjTipoInstalacaoFederacaoDTO[] = $objTipoInstalacaoFederacaoDTO;


      return $arrObjTipoInstalacaoFederacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Tipo.',$e);
    }
  }

  public function listarValoresEstado(){
    try {

      $arrObjEstadoInstalacaoFederacaoDTO = array();

      $objEstadoInstalacaoFederacaoDTO = new EstadoInstalacaoFederacaoDTO();
      $objEstadoInstalacaoFederacaoDTO->setStrStaEstado(self::$EI_ANALISE);
      $objEstadoInstalacaoFederacaoDTO->setStrDescricao('Em Análise');
      $arrObjEstadoInstalacaoFederacaoDTO[] = $objEstadoInstalacaoFederacaoDTO;

      $objEstadoInstalacaoFederacaoDTO = new EstadoInstalacaoFederacaoDTO();
      $objEstadoInstalacaoFederacaoDTO->setStrStaEstado(self::$EI_LIBERADA);
      $objEstadoInstalacaoFederacaoDTO->setStrDescricao('Liberada');
      $arrObjEstadoInstalacaoFederacaoDTO[] = $objEstadoInstalacaoFederacaoDTO;

      $objEstadoInstalacaoFederacaoDTO = new EstadoInstalacaoFederacaoDTO();
      $objEstadoInstalacaoFederacaoDTO->setStrStaEstado(self::$EI_BLOQUEADA);
      $objEstadoInstalacaoFederacaoDTO->setStrDescricao('Bloqueada');
      $arrObjEstadoInstalacaoFederacaoDTO[] = $objEstadoInstalacaoFederacaoDTO;

      return $arrObjEstadoInstalacaoFederacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Estado.',$e);
    }
  }

  public function listarValoresAgendamento(){
    try {

      $arrObjAgendamentoInstalacaoFederacaoDTO = array();

      $objAgendamentoInstalacaoFederacaoDTO = new AgendamentoInstalacaoFederacaoDTO();
      $objAgendamentoInstalacaoFederacaoDTO->setStrStaAgendamento(self::$AI_NENHUM);
      $objAgendamentoInstalacaoFederacaoDTO->setStrDescricao('Nenhum');
      $arrObjAgendamentoInstalacaoFederacaoDTO[] = $objAgendamentoInstalacaoFederacaoDTO;

      $objAgendamentoInstalacaoFederacaoDTO = new AgendamentoInstalacaoFederacaoDTO();
      $objAgendamentoInstalacaoFederacaoDTO->setStrStaAgendamento(self::$AI_EMAIL_ENVIADO);
      $objAgendamentoInstalacaoFederacaoDTO->setStrDescricao('E-mail enviado');
      $arrObjAgendamentoInstalacaoFederacaoDTO[] = $objAgendamentoInstalacaoFederacaoDTO;

      $objAgendamentoInstalacaoFederacaoDTO = new AgendamentoInstalacaoFederacaoDTO();
      $objAgendamentoInstalacaoFederacaoDTO->setStrStaAgendamento(self::$AI_IGNORADO);
      $objAgendamentoInstalacaoFederacaoDTO->setStrDescricao('Ignorado');
      $arrObjAgendamentoInstalacaoFederacaoDTO[] = $objAgendamentoInstalacaoFederacaoDTO;


      return $arrObjAgendamentoInstalacaoFederacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Agendamento.',$e);
    }
  }

  private function validarStrIdInstalacaoFederacao(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao())){
      $objInfraException->adicionarValidacao('Identificador do SEI Federação não informado.');
    }else {

      if (!InfraULID::validar($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao())){
        $objInfraException->lancarValidacao('Identificador do SEI Federação '.$objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao().' inválido.');
      }

      $dto = new InstalacaoFederacaoDTO();
      $dto->retStrIdInstalacaoFederacao();
      $dto->setNumMaxRegistrosRetorno(1);
      $dto->setBolExclusaoLogica(false);
      $dto->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
      if ($this->consultar($dto) != null) {
        $objInfraException->adicionarValidacao('Já existe uma Instalação cadastrada com o identificador '.$objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao().' do SEI Federação.');
      }
    }
  }

  private function validarDblCnpj(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInstalacaoFederacaoDTO->getDblCnpj())){
      $objInfraException->adicionarValidacao('CNPJ não informado.');
    }else {
      if (!InfraUtil::validarCnpj($objInstalacaoFederacaoDTO->getDblCnpj())) {
        $objInfraException->adicionarValidacao('CNPJ inválido.');
      }
    }

    $dto = new InstalacaoFederacaoDTO();
    $dto->setBolExclusaoLogica(false);
    $dto->retStrSinAtivo();
    $dto->retStrSigla();
    $dto->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao(),InfraDTO::$OPER_DIFERENTE);
    $dto->setDblCnpj($objInstalacaoFederacaoDTO->getDblCnpj());

    $dto = $this->consultar($dto);

    if ($dto != null){
      $objInfraException->adicionarValidacao('CNPJ '.InfraUtil::formatarCnpj($objInstalacaoFederacaoDTO->getDblCnpj()).' já está associado com a instalação '.($dto->getStrSinAtivo()=='N' ? 'inativa' : '').' '.$dto->getStrSigla().'.');
    }

  }

  private function validarStrSigla(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInstalacaoFederacaoDTO->getStrSigla())){
      $objInfraException->adicionarValidacao('Sigla não informada.');
    }else{
      $objInstalacaoFederacaoDTO->setStrSigla(trim($objInstalacaoFederacaoDTO->getStrSigla()));

      if (strlen($objInstalacaoFederacaoDTO->getStrSigla())>30){
        $objInfraException->adicionarValidacao('Sigla possui tamanho superior a 30 caracteres.');
      }
    }
  }

  private function validarStrDescricao(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInstalacaoFederacaoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objInstalacaoFederacaoDTO->setStrDescricao(trim($objInstalacaoFederacaoDTO->getStrDescricao()));

      if (strlen($objInstalacaoFederacaoDTO->getStrDescricao())>100){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrEndereco(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInstalacaoFederacaoDTO->getStrEndereco())){
      $objInfraException->adicionarValidacao('Endereço não informado.');
    }else{
      $objInstalacaoFederacaoDTO->setStrEndereco(trim($objInstalacaoFederacaoDTO->getStrEndereco()));

      if (strlen($objInstalacaoFederacaoDTO->getStrEndereco())>250){
        $objInfraException->adicionarValidacao('Endereço possui tamanho superior a 250 caracteres.');
      }

      $dto = new InstalacaoFederacaoDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSinAtivo();
      $dto->retStrSigla();
      $dto->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrEndereco($objInstalacaoFederacaoDTO->getStrEndereco());

      $dto = $this->consultar($dto);

      if ($dto != null){
        $objInfraException->adicionarValidacao('Endereço "'.$objInstalacaoFederacaoDTO->getStrEndereco().'"" já está associado com a instalação '.($dto->getStrSinAtivo()=='N' ? 'inativa' : '').' '.$dto->getStrSigla().'.');
      }
    }
  }

  private function validarStrStaTipo(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInstalacaoFederacaoDTO->getStrStaTipo())){
      $objInfraException->adicionarValidacao('Tipo não informado.');
    }else{
      if (!in_array($objInstalacaoFederacaoDTO->getStrStaTipo(),InfraArray::converterArrInfraDTO($this->listarValoresTipo(),'StaTipo'))){
        $objInfraException->adicionarValidacao('Tipo inválido.');
      }
    }
  }

  private function validarStrStaEstado(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInstalacaoFederacaoDTO->getStrStaEstado())){
      $objInfraException->adicionarValidacao('Estado não informado.');
    }else{
      if (!in_array($objInstalacaoFederacaoDTO->getStrStaEstado(),InfraArray::converterArrInfraDTO($this->listarValoresEstado(),'StaEstado'))){
        $objInfraException->adicionarValidacao('Estado inválido.');
      }
    }
  }

  private function validarStrStaAgendamento(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInstalacaoFederacaoDTO->getStrStaAgendamento())){
      $objInfraException->adicionarValidacao('Situação de Agendamento não informada.');
    }else{
      if (!in_array($objInstalacaoFederacaoDTO->getStrStaAgendamento(),InfraArray::converterArrInfraDTO($this->listarValoresAgendamento(),'StaAgendamento'))){
        $objInfraException->adicionarValidacao('Situação de Agendamento inválida.');
      }
    }
  }

  private function validarStrSinAtivo(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objInstalacaoFederacaoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objInstalacaoFederacaoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_cadastrar', __METHOD__, $objInstalacaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO, $objInfraException);
      $this->validarDblCnpj($objInstalacaoFederacaoDTO, $objInfraException);
      $this->validarStrSigla($objInstalacaoFederacaoDTO, $objInfraException);
      $this->validarStrDescricao($objInstalacaoFederacaoDTO, $objInfraException);
      $this->validarStrEndereco($objInstalacaoFederacaoDTO, $objInfraException);
      $this->validarStrStaTipo($objInstalacaoFederacaoDTO, $objInfraException);
      $this->validarStrStaEstado($objInstalacaoFederacaoDTO, $objInfraException);
      $this->validarStrStaAgendamento($objInstalacaoFederacaoDTO, $objInfraException);
      $this->validarStrSinAtivo($objInstalacaoFederacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objInstalacaoFederacaoBD = new InstalacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objInstalacaoFederacaoBD->cadastrar($objInstalacaoFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Instalação do SEI Federação.',$e);
    }
  }

  protected function alterarControlado(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_alterar', __METHOD__, $objInstalacaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objInstalacaoFederacaoDTO->isSetDblCnpj()){
        $this->validarDblCnpj($objInstalacaoFederacaoDTO, $objInfraException);
      }
      if ($objInstalacaoFederacaoDTO->isSetStrSigla()){
        $this->validarStrSigla($objInstalacaoFederacaoDTO, $objInfraException);
      }
      if ($objInstalacaoFederacaoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objInstalacaoFederacaoDTO, $objInfraException);
      }
      if ($objInstalacaoFederacaoDTO->isSetStrEndereco()){
        $this->validarStrEndereco($objInstalacaoFederacaoDTO, $objInfraException);
      }
      if ($objInstalacaoFederacaoDTO->isSetStrStaTipo()){
        $this->validarStrStaTipo($objInstalacaoFederacaoDTO, $objInfraException);
      }
      if ($objInstalacaoFederacaoDTO->isSetStrStaEstado()){
        $this->validarStrStaEstado($objInstalacaoFederacaoDTO, $objInfraException);
      }
      if ($objInstalacaoFederacaoDTO->isSetStrStaAgendamento()){
        $this->validarStrStaAgendamento($objInstalacaoFederacaoDTO, $objInfraException);
      }
      if ($objInstalacaoFederacaoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objInstalacaoFederacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objInstalacaoFederacaoBD = new InstalacaoFederacaoBD($this->getObjInfraIBanco());
      $objInstalacaoFederacaoBD->alterar($objInstalacaoFederacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Instalação do SEI Federação.',$e);
    }
  }

  protected function excluirControlado($arrObjInstalacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_excluir', __METHOD__, $arrObjInstalacaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objAcessoFederacaoRN = new AcessoFederacaoRN();
      for($i=0;$i<count($arrObjInstalacaoFederacaoDTO);$i++){
        $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
        $objAcessoFederacaoDTO->setBolExclusaoLogica(false);
        $objAcessoFederacaoDTO->setNumMaxRegistrosRetorno(1);
        $objAcessoFederacaoDTO->retStrIdInstalacaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrSiglaInstalacaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrIdInstalacaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrSiglaInstalacaoFederacaoDest();
        $objAcessoFederacaoDTO->adicionarCriterio(array('IdInstalacaoFederacaoRem','IdInstalacaoFederacaoDest'),
                                                  array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),
                                                  array($arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao(),$arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao()),
                                                  InfraDTO::$OPER_LOGICO_OR);

        if (($objAcessoFederacaoDTO=$objAcessoFederacaoRN->consultar($objAcessoFederacaoDTO))!=null){

          if ($objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoRem() == $arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao()){
            $strSiglaInstalacaoAcesso = $objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoRem();
          }else{
            $strSiglaInstalacaoAcesso = $objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoDest();
          }
          $objInfraException->adicionarValidacao('Não é possível excluir a instalação '.$strSiglaInstalacaoAcesso.' porque existem acessos relacionados.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
      $objAtributoInstalacaoRN = new AtributoInstalacaoRN();
      $objOrgaoFederacaoRN = new OrgaoFederacaoRN();
      $objUnidadeFederacaoRN = new UnidadeFederacaoRN();
      $objUsuarioFederacaoRN = new UsuarioFederacaoRN();

      $objInstalacaoFederacaoBD = new InstalacaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInstalacaoFederacaoDTO);$i++){

        $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
        $objAtributoInstalacaoDTO->retNumIdAtributoInstalacao();
        $objAtributoInstalacaoDTO->setStrIdInstalacaoFederacao($arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao());
        $objAtributoInstalacaoRN->excluir($objAtributoInstalacaoRN->listar($objAtributoInstalacaoDTO));

        $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
        $objAndamentoInstalacaoDTO->retNumIdAndamentoInstalacao();
        $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao());
        $objAndamentoInstalacaoRN->excluir($objAndamentoInstalacaoRN->listar($objAndamentoInstalacaoDTO));

        $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
        $objOrgaoFederacaoDTO->retStrIdOrgaoFederacao();
        $objOrgaoFederacaoDTO->setStrIdInstalacaoFederacao($arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao());
        $objOrgaoFederacaoRN->excluir($objOrgaoFederacaoRN->listar($objOrgaoFederacaoDTO));

        $objUnidadeFederacaoDTO = new UnidadeFederacaoDTO();
        $objUnidadeFederacaoDTO->retStrIdUnidadeFederacao();
        $objUnidadeFederacaoDTO->setStrIdInstalacaoFederacao($arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao());
        $objUnidadeFederacaoRN->excluir($objUnidadeFederacaoRN->listar($objUnidadeFederacaoDTO));

        $objUsuarioFederacaoDTO = new UsuarioFederacaoDTO();
        $objUsuarioFederacaoDTO->retStrIdUsuarioFederacao();
        $objUsuarioFederacaoDTO->setStrIdInstalacaoFederacao($arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao());
        $objUsuarioFederacaoRN->excluir($objUsuarioFederacaoRN->listar($objUsuarioFederacaoDTO));

        $objInstalacaoFederacaoBD->excluir($arrObjInstalacaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Instalação do SEI Federação.',$e);
    }
  }

  protected function consultarConectado(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_consultar', __METHOD__, $objInstalacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      if ($objInstalacaoFederacaoDTO->isRetStrDescricaoTipo()){
        $objInstalacaoFederacaoDTO->retStrStaTipo();
      }

      if ($objInstalacaoFederacaoDTO->isRetStrDescricaoEstado()){
        $objInstalacaoFederacaoDTO->retStrStaEstado();
      }

      $objInstalacaoFederacaoBD = new InstalacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objInstalacaoFederacaoBD->consultar($objInstalacaoFederacaoDTO);

      if ($ret != null) {
        if ($objInstalacaoFederacaoDTO->isRetStrDescricaoTipo()) {
          $arrObjTipoInstalacaoFederacaoDTO = InfraArray::indexarArrInfraDTO($this->listarValoresTipo(), 'StaTipo');
          $ret->setStrDescricaoTipo($arrObjTipoInstalacaoFederacaoDTO[$ret->getStrStaTipo()]->getStrDescricao());
        }

        if ($objInstalacaoFederacaoDTO->isRetStrDescricaoEstado()) {
          $arrObjEstadoInstalacaoFederacaoDTO = InfraArray::indexarArrInfraDTO($this->listarValoresEstado(), 'StaEstado');
          $ret->setStrDescricaoEstado($arrObjEstadoInstalacaoFederacaoDTO[$ret->getStrStaEstado()]->getStrDescricao());
        }
      }

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Instalação do SEI Federação.',$e);
    }
  }

  protected function listarConectado(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_listar', __METHOD__, $objInstalacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      if ($objInstalacaoFederacaoDTO->isRetStrDescricaoTipo()){
        $objInstalacaoFederacaoDTO->retStrStaTipo();
      }

      if ($objInstalacaoFederacaoDTO->isRetStrDescricaoEstado()){
        $objInstalacaoFederacaoDTO->retStrStaEstado();
      }

      $objInstalacaoFederacaoBD = new InstalacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objInstalacaoFederacaoBD->listar($objInstalacaoFederacaoDTO);

      if (count($ret)) {

        if ($objInstalacaoFederacaoDTO->isRetStrDescricaoTipo()) {
          $arrObjTipoInstalacaoFederacaoDTO = InfraArray::indexarArrInfraDTO($this->listarValoresTipo(), 'StaTipo');
          foreach($ret as $dto) {
            $dto->setStrDescricaoTipo($arrObjTipoInstalacaoFederacaoDTO[$dto->getStrStaTipo()]->getStrDescricao());
          }
        }

        if ($objInstalacaoFederacaoDTO->isRetStrDescricaoEstado()) {
          $arrObjEstadoInstalacaoFederacaoDTO = InfraArray::indexarArrInfraDTO($this->listarValoresEstado(), 'StaEstado');
          foreach($ret as $dto) {
            $dto->setStrDescricaoEstado($arrObjEstadoInstalacaoFederacaoDTO[$dto->getStrStaEstado()]->getStrDescricao());
          }
        }
      }
      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro listando Instalações do SEI Federação.',$e);
    }
  }

  protected function contarConectado(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_listar', __METHOD__, $objInstalacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInstalacaoFederacaoBD = new InstalacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objInstalacaoFederacaoBD->contar($objInstalacaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Instalações do SEI Federação.',$e);
    }
  }

  protected function desativarControlado($arrObjInstalacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_desativar', __METHOD__, $arrObjInstalacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInstalacaoFederacaoBD = new InstalacaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInstalacaoFederacaoDTO);$i++){
        $objInstalacaoFederacaoBD->desativar($arrObjInstalacaoFederacaoDTO[$i]);

        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
        $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
        $objInstalacaoFederacaoDTO->retStrStaEstado();
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao());
        $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

        $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
        $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $objAndamentoInstalacaoDTO->setStrStaEstado($objInstalacaoFederacaoDTO->getStrStaEstado());
        $objAndamentoInstalacaoDTO->setNumIdTarefaInstalacao(TarefaInstalacaoRN::$TI_DESATIVACAO);

        $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
        $objAndamentoInstalacaoRN->lancar($objAndamentoInstalacaoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Instalação do SEI Federação.',$e);
    }
  }

  protected function reativarControlado($arrObjInstalacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_reativar', __METHOD__, $arrObjInstalacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objInstalacaoFederacaoBD = new InstalacaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjInstalacaoFederacaoDTO);$i++){
        $objInstalacaoFederacaoBD->reativar($arrObjInstalacaoFederacaoDTO[$i]);

        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
        $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
        $objInstalacaoFederacaoDTO->retStrStaEstado();
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($arrObjInstalacaoFederacaoDTO[$i]->getStrIdInstalacaoFederacao());
        $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

        $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
        $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $objAndamentoInstalacaoDTO->setStrStaEstado($objInstalacaoFederacaoDTO->getStrStaEstado());
        $objAndamentoInstalacaoDTO->setNumIdTarefaInstalacao(TarefaInstalacaoRN::$TI_REATIVACAO);

        $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
        $objAndamentoInstalacaoRN->lancar($objAndamentoInstalacaoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Instalação do SEI Federação.',$e);
    }
  }

  private function obterServico(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTO){
    try {

      $strWSDL = '';

      if (substr($objInstalacaoFederacaoDTO->getStrEndereco(),0,8) !== 'https://'){
        $strWSDL = 'https://';
      }

      //se possui endereco completo
      if (strpos($objInstalacaoFederacaoDTO->getStrEndereco(),'/controlador_ws.php?servico=federacao') !== false){
        $strWSDL .= $objInstalacaoFederacaoDTO->getStrEndereco();
      }else {
        //senão assume endereço padrao "/sei"
        $strWSDL .= $objInstalacaoFederacaoDTO->getStrEndereco().'/sei/controlador_ws.php?servico=federacao';
      }

      $objWS = new SoapClient($strWSDL, array ('encoding'=>'ISO-8859-1'));

      return $objWS;

    } catch (Exception $e) {

      $objInfraException = new InfraException();

      if (strpos(strtoupper($e->__toString()),'COULDN\'T LOAD FROM')!==false){
        $objInfraException->lancarValidacao('Não foi possível acessar o serviço no endereço da instalação.');
      }

      throw new InfraException('Falha na conexão com a Instalação.', $e);
    }
  }

  private function obterInstalacaoLocal(){
    try{

      $objInfraException = new InfraException();

      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->retDblCnpjContato();
      $objOrgaoDTO->setStrSigla(ConfiguracaoSEI::getInstance()->getValor('SessaoSEI','SiglaOrgaoSistema'));

      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

      if ($objOrgaoDTO==null){
        throw new InfraException('Órgão da instalação local do SEI Federação não encontrado.');
      }

      if (InfraString::isBolVazia($objOrgaoDTO->getDblCnpjContato())) {
        if (SessaoSEI::getInstance()->isBolHabilitada()) {
          $objInfraException->lancarValidacao('CNPJ não informado no cadastro do órgão '.ConfiguracaoSEI::getInstance()->getValor('SessaoSEI', 'SiglaOrgaoSistema').' para o SEI Federação.'."\n\n".'Informar por meio do menu Administração/Órgãos, ação "Alterar Órgão", ícone "Alterar Dados do Contato Associado".');
        }else{
          $objInfraException->lancarValidacao('CNPJ não foi cadastrado na instalação remota.');
        }
      }

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retStrSigla();
      $objInstalacaoFederacaoDTO->retStrDescricao();
      $objInstalacaoFederacaoDTO->retDblCnpj();
      $objInstalacaoFederacaoDTO->retStrEndereco();
      $objInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_LOCAL);

      $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

      $strEnderecoLocal = $this->montarEnderecoInstalacaoLocal();

      if ($objInstalacaoFederacaoDTO==null){

        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao(InfraULID::gerar());
        $objInstalacaoFederacaoDTO->setStrSigla($objOrgaoDTO->getStrSigla());
        $objInstalacaoFederacaoDTO->setStrDescricao($objOrgaoDTO->getStrDescricao());
        $objInstalacaoFederacaoDTO->setStrEndereco($strEnderecoLocal);
        $objInstalacaoFederacaoDTO->setDblCnpj($objOrgaoDTO->getDblCnpjContato());
        $objInstalacaoFederacaoDTO->setStrChavePrivada(null);
        $objInstalacaoFederacaoDTO->setStrChavePublicaLocal(null);
        $objInstalacaoFederacaoDTO->setStrChavePublicaRemota(null);
        $objInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_LOCAL);
        $objInstalacaoFederacaoDTO->setStrStaEstado(self::$EI_LIBERADA);
        $objInstalacaoFederacaoDTO->setStrStaAgendamento(self::$AI_NENHUM);
        $objInstalacaoFederacaoDTO->setStrSinAtivo('S');

        $this->cadastrar($objInstalacaoFederacaoDTO);

      }else{

        if ($objInstalacaoFederacaoDTO->getStrSigla()!=$objOrgaoDTO->getStrSigla() ||
            $objInstalacaoFederacaoDTO->getStrDescricao()!=$objOrgaoDTO->getStrDescricao() ||
            $objInstalacaoFederacaoDTO->getDblCnpj()!=$objOrgaoDTO->getDblCnpjContato() ||
            $objInstalacaoFederacaoDTO->getStrEndereco()!=$strEnderecoLocal){

          $objInstalacaoFederacaoDTO->setStrSigla($objOrgaoDTO->getStrSigla());
          $objInstalacaoFederacaoDTO->setStrDescricao($objOrgaoDTO->getStrDescricao());
          $objInstalacaoFederacaoDTO->setDblCnpj($objOrgaoDTO->getDblCnpjContato());
          $objInstalacaoFederacaoDTO->setStrEndereco($strEnderecoLocal);

          $this->alterar($objInstalacaoFederacaoDTO);
        }

      }

      return $objInstalacaoFederacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro obtendo órgão da instalação local', $e);
    }
  }

  private function montarEnderecoInstalacaoLocal(){
    try {
      $strEndereco = strtolower(trim(ConfiguracaoSEI::getInstance()->getValor('SEI', 'URL')));

      //remove prefixo
      $strEndereco = str_replace(array('http://', 'https://'), '', $strEndereco);

      //URL no padrão https://endereco/sei
      if (substr($strEndereco, -4) == '/sei') {
        //retornar apenas o "endereco"
        $strEndereco = substr($strEndereco, 0, strlen($strEndereco) - 4);
      } else {
        //passar apontamento completo
        $strEndereco .= '/controlador_ws.php?servico=federacao';
      }

      return $strEndereco;

    }catch(Exception $e){
      throw new InfraException('Erro montando endereço da instalação local.', $e);
    }
  }

  public function obterIdInstalacaoFederacaoLocal(){
    return self::$objInstalacaoDTOLocal->getStrIdInstalacaoFederacao();
  }

  public function obterEnderecoInstalacaoLocal(){
    return self::$objInstalacaoDTOLocal->getStrEndereco();
  }

  public function obterCnpjInstalacaoLocal(){
    return self::$objInstalacaoDTOLocal->getDblCnpj();
  }

  public function obterSiglaInstalacaoLocal(){
    return self::$objInstalacaoDTOLocal->getStrSigla();
  }

  public function obterDescricaoInstalacaoLocal(){
    return self::$objInstalacaoDTOLocal->getStrDescricao();
  }

  protected function solicitarRegistroControlado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_cadastrar', __METHOD__, $parObjInstalacaoFederacaoDTO);

      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($this->obterCnpjInstalacaoLocal())){
        $objInfraException->lancarValidacao('CNPJ da instalação local não informado.');
      }

      $objWS = $this->obterServico($parObjInstalacaoFederacaoDTO);


      try {

        $objInstalacao = new stdClass();
        $objInstalacao->IdInstalacaoFederacao = $this->obterIdInstalacaoFederacaoLocal();
        $objInstalacao->Cnpj = $this->obterCnpjInstalacaoLocal();
        $objInstalacao->Sigla = $this->obterSiglaInstalacaoLocal();
        $objInstalacao->Descricao = $this->obterDescricaoInstalacaoLocal();
        $objInstalacao->Endereco = $this->obterEnderecoInstalacaoLocal();

        $objRemetente = new stdClass();
        $objRemetente->Instalacao = $objInstalacao;

        $objIdentificacao = new stdClass();
        $objIdentificacao->VersaoSei = SEI_VERSAO;
        $objIdentificacao->VersaoSeiFederacao = SEI_FEDERACAO_VERSAO;
        $objIdentificacao->Remetente = $objRemetente;

        $ret = $objWS->solicitarRegistro($objIdentificacao);

      } catch (Exception $e) {
        throw new InfraException('Erro solicitanto registro na Instalação.', $e);
      }

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retDblCnpj();
      $objInstalacaoFederacaoDTO->retStrEndereco();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($ret->IdInstalacaoFederacao);

      $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

      //se ainda nao existe o registro local
      if ($objInstalacaoFederacaoDTO==null){
        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($ret->IdInstalacaoFederacao);
        $objInstalacaoFederacaoDTO->setDblCnpj($ret->Cnpj);
        $objInstalacaoFederacaoDTO->setStrSigla($ret->Sigla);
        $objInstalacaoFederacaoDTO->setStrDescricao($ret->Descricao);
        $objInstalacaoFederacaoDTO->setStrEndereco($ret->Endereco);
        $objInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_ENVIADA);
        $objInstalacaoFederacaoDTO->setStrStaEstado($ret->StaEstado);
        $objInstalacaoFederacaoDTO->setStrStaAgendamento(self::$AI_NENHUM);
        $objInstalacaoFederacaoDTO->setStrSinAtivo('S');
        $objInstalacaoFederacaoDTO = $this->cadastrar($objInstalacaoFederacaoDTO);
      }else{

        if ($objInstalacaoFederacaoDTO->getStrEndereco()!=$ret->Endereco){
          $objInfraException->lancarValidacao('Endereço da instalação '.InfraUtil::formatarCnpj($ret->Cnpj).' ('.$ret->Endereco.') não confere com o já registrado na instalação local para este CNPJ ('.$objInstalacaoFederacaoDTO->getStrEndereco().').');
        }

        //senao apenas atualiza
        $objInstalacaoFederacaoDTO->setDblCnpj($ret->Cnpj);
        $objInstalacaoFederacaoDTO->setStrSigla($ret->Sigla);
        $objInstalacaoFederacaoDTO->setStrDescricao($ret->Descricao);
        $objInstalacaoFederacaoDTO->setStrStaEstado($ret->StaEstado);
        $objInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_ENVIADA);
        $objInstalacaoFederacaoDTO->setStrStaAgendamento(self::$AI_NENHUM);
        $this->alterar($objInstalacaoFederacaoDTO);
      }

      $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
      $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
      $objAndamentoInstalacaoDTO->setStrStaEstado($objInstalacaoFederacaoDTO->getStrStaEstado());
      $objAndamentoInstalacaoDTO->setNumIdTarefaInstalacao(TarefaInstalacaoRN::$TI_ENVIO_SOLICITACAO);

      $arrObjAtributoInstalacaoDTO = array();

      $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
      $objAtributoInstalacaoDTO->setStrNome('INSTITUICAO');
      $objAtributoInstalacaoDTO->setStrValor($objInstalacaoFederacaoDTO->getStrSigla()."¥".$objInstalacaoFederacaoDTO->getStrDescricao());
      $objAtributoInstalacaoDTO->setStrIdOrigem($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
      $arrObjAtributoInstalacaoDTO[] = $objAtributoInstalacaoDTO;

      $objAndamentoInstalacaoDTO->setArrObjAtributoInstalacaoDTO($arrObjAtributoInstalacaoDTO);

      $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
      $objAndamentoInstalacaoRN->lancar($objAndamentoInstalacaoDTO);

      return $objInstalacaoFederacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro solicitando registro de Instalação do SEI Federação.',$e);
    }
  }

  protected function alterarRegistroControlado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_alterar', __METHOD__, $parObjInstalacaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $this->validarStrEndereco($parObjInstalacaoFederacaoDTO, $objInfraException);
      $objInfraException->lancarValidacoes();

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retStrEndereco();
      $objInstalacaoFederacaoDTO->retDblCnpj();
      $objInstalacaoFederacaoDTO->retStrSigla();
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

      $objInstalacaoFederacaoDTOBanco = $this->consultar($objInstalacaoFederacaoDTO);

      //se realmente houve mudança no endereço
      if ($objInstalacaoFederacaoDTOBanco->getStrEndereco()!=$parObjInstalacaoFederacaoDTO->getStrEndereco()) {

        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
        $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
        $objInstalacaoFederacaoDTO->setStrEndereco($parObjInstalacaoFederacaoDTO->getStrEndereco());
        $objInstalacaoFederacaoDTO->setNumMaxRegistrosRetorno(1);

        $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

        if ($objInstalacaoFederacaoDTO != null) {
          $objInfraException->lancarValidacao('Já existe uma Instalação cadastrada com o endereço '.$parObjInstalacaoFederacaoDTO->getStrEndereco().'.');
        }

        $objWS = $this->obterServico($parObjInstalacaoFederacaoDTO);

        try {

          $objInstalacao = new stdClass();
          $objInstalacao->IdInstalacaoFederacao = $this->obterIdInstalacaoFederacaoLocal();
          $objInstalacao->Cnpj = $this->obterCnpjInstalacaoLocal();
          $objInstalacao->Sigla = $this->obterSiglaInstalacaoLocal();
          $objInstalacao->Descricao = $this->obterDescricaoInstalacaoLocal();
          $objInstalacao->Endereco = $this->obterEnderecoInstalacaoLocal();

          $objRemetente = new stdClass();
          $objRemetente->Instalacao = $objInstalacao;

          $objIdentificacao = new stdClass();
          $objIdentificacao->VersaoSei = SEI_VERSAO;
          $objIdentificacao->VersaoSeiFederacao = SEI_FEDERACAO_VERSAO;
          $objIdentificacao->Remetente = $objRemetente;

          //envia solicitação de registro padrão
          $ret = $objWS->solicitarRegistro($objIdentificacao);

        } catch (Exception $e) {
          throw new InfraException('Erro solicitanto registro na Instalação.', $e);
        }

        if ($ret->IdInstalacaoFederacao != $objInstalacaoFederacaoDTOBanco->getStrIdInstalacaoFederacao()){
          $objInfraException->lancarValidacao('Identificador do SEI Federação da instalação remota não corresponde ao da instalação '.$objInstalacaoFederacaoDTOBanco->getStrSigla().'.');
        }

        //se retornou um CNPJ diferente
        if ($ret->Cnpj != $objInstalacaoFederacaoDTOBanco->getDblCnpj()){
          $objInfraException->lancarValidacao('CNPJ da instalação remota não corresponde ao CNPJ da Instalação '.$objInstalacaoFederacaoDTOBanco->getStrSigla().'.');
        }

        //atualiza dados
        $objInstalacaoFederacaoDTOBanco->setStrEndereco($ret->Endereco);
        $objInstalacaoFederacaoDTOBanco->setDblCnpj($ret->Cnpj);
        $objInstalacaoFederacaoDTOBanco->setStrSigla($ret->Sigla);
        $objInstalacaoFederacaoDTOBanco->setStrDescricao($ret->Descricao);
        $objInstalacaoFederacaoDTOBanco->setStrStaEstado($ret->StaEstado);
        $this->alterar($objInstalacaoFederacaoDTOBanco);


        $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
        $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTOBanco->getStrIdInstalacaoFederacao());
        $objAndamentoInstalacaoDTO->setStrStaEstado($objInstalacaoFederacaoDTOBanco->getStrStaEstado());
        $objAndamentoInstalacaoDTO->setNumIdTarefaInstalacao(TarefaInstalacaoRN::$TI_ALTERACAO_ENDERECO);

        $arrObjAtributoInstalacaoDTO = array();

        $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
        $objAtributoInstalacaoDTO->setStrNome('ENDERECO');
        $objAtributoInstalacaoDTO->setStrValor($ret->Endereco);
        $objAtributoInstalacaoDTO->setStrIdOrigem(null);
        $arrObjAtributoInstalacaoDTO[] = $objAtributoInstalacaoDTO;

        $objAndamentoInstalacaoDTO->setArrObjAtributoInstalacaoDTO($arrObjAtributoInstalacaoDTO);

        $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
        $objAndamentoInstalacaoRN->lancar($objAndamentoInstalacaoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro solicitando alteração do registro de Instalação do SEI Federação.',$e);
    }
  }

  protected function processarSolicitacaoRegistroControlado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO) {
    try{

      $objInfraException = new InfraException();

      $strIdentificacaoRemota = $parObjInstalacaoFederacaoDTO->getStrSigla().' ('.InfraUtil::formatarCnpj($parObjInstalacaoFederacaoDTO->getDblCnpj()).')';

      if ($this->obterIdInstalacaoFederacaoLocal()==$parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao()){
        $objInfraException->lancarValidacao('Identificador do SEI Federação da instalação '.$strIdentificacaoRemota.' é igual ao da instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

      if ($this->obterCnpjInstalacaoLocal()==$parObjInstalacaoFederacaoDTO->getDblCnpj()){
        $objInfraException->lancarValidacao('CNPJ da instalação '.$strIdentificacaoRemota.' é igual ao da instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

      $objInstalacaoFederacaoDTOBanco = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTOBanco->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTOBanco->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTOBanco->retStrStaTipo();
      $objInstalacaoFederacaoDTOBanco->retStrStaEstado();
      $objInstalacaoFederacaoDTOBanco->retDblCnpj();
      $objInstalacaoFederacaoDTOBanco->retStrEndereco();
      $objInstalacaoFederacaoDTOBanco->retStrSinAtivo();
      $objInstalacaoFederacaoDTOBanco->setStrIdInstalacaoFederacao($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

      $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTOBanco);

      //se ainda não existe cadastra normalmente
      if ($objInstalacaoFederacaoDTO==null){

        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $objInstalacaoFederacaoDTO->setDblCnpj($parObjInstalacaoFederacaoDTO->getDblCnpj());
        $objInstalacaoFederacaoDTO->setStrSigla($parObjInstalacaoFederacaoDTO->getStrSigla());
        $objInstalacaoFederacaoDTO->setStrDescricao($parObjInstalacaoFederacaoDTO->getStrDescricao());
        $objInstalacaoFederacaoDTO->setStrEndereco($parObjInstalacaoFederacaoDTO->getStrEndereco());
        $objInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_RECEBIDA);
        $objInstalacaoFederacaoDTO->setStrStaEstado(self::$EI_ANALISE);
        $objInstalacaoFederacaoDTO->setStrStaAgendamento(self::$AI_NENHUM);
        $objInstalacaoFederacaoDTO->setStrSinAtivo('S');
        $objInstalacaoFederacaoDTO = $this->cadastrar($objInstalacaoFederacaoDTO);

        $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
        $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $objAndamentoInstalacaoDTO->setStrStaEstado($objInstalacaoFederacaoDTO->getStrStaEstado());
        $objAndamentoInstalacaoDTO->setNumIdTarefaInstalacao(TarefaInstalacaoRN::$TI_RECEBIMENTO_SOLICITACAO);

        $arrObjAtributoInstalacaoDTO = array();

        $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
        $objAtributoInstalacaoDTO->setStrNome('INSTITUICAO');
        $objAtributoInstalacaoDTO->setStrValor($objInstalacaoFederacaoDTO->getStrSigla()."¥".$objInstalacaoFederacaoDTO->getStrDescricao());
        $objAtributoInstalacaoDTO->setStrIdOrigem($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $arrObjAtributoInstalacaoDTO[] = $objAtributoInstalacaoDTO;

        $objAndamentoInstalacaoDTO->setArrObjAtributoInstalacaoDTO($arrObjAtributoInstalacaoDTO);

        $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
        $objAndamentoInstalacaoRN->lancar($objAndamentoInstalacaoDTO);


      }else{


        if ($objInstalacaoFederacaoDTO->getStrEndereco()!=$parObjInstalacaoFederacaoDTO->getStrEndereco()){
          $objInfraException->lancarValidacao('Endereço da instalação diferente para a instalação '.$strIdentificacaoRemota.' na instalação '.$this->obterSiglaInstalacaoLocal().'.');
        }

        //evita registro duplo entre as instalacoes (A->B, B->A)
        //if ($objInstalacaoFederacaoDTO->getStrStaTipo()==self::$TI_ENVIADA && $objInstalacaoFederacaoDTO->getStrStaEstado() == self::$EI_ANALISE){
        //  $objInfraException->lancarValidacao('Já existe solicitação enviada pela Instalação '.$this->obterSiglaInstalacaoLocal().' para a instalação '.$strIdentificacaoRemota.'.');
        //}

        //evita tentativas sucessivas de registro (Instalação bloqueada passa a ser ignorada)
        if ($objInstalacaoFederacaoDTO->getStrStaEstado()==self::$EI_BLOQUEADA){
          $objInfraException->lancarValidacao('Instalação '.$strIdentificacaoRemota.' bloqueada na instalação '.$this->obterSiglaInstalacaoLocal().'.');
        }

        if ($objInstalacaoFederacaoDTO->getStrSinAtivo()=='N'){
          $objInfraException->lancarValidacao('Instalação '.$strIdentificacaoRemota.' consta como desativada na instalação '.$this->obterSiglaInstalacaoLocal().'.');
        }

        //se estava como replicada ou enviada ou liberada ou trocou CNPJ volta para em analise (evitar roubo de identidade)
        if ($objInstalacaoFederacaoDTO->getStrStaTipo()==self::$TI_REPLICADA ||
            $objInstalacaoFederacaoDTO->getStrStaTipo()==self::$TI_ENVIADA ||
            $objInstalacaoFederacaoDTO->getStrStaEstado()==self::$EI_LIBERADA ||
            $objInstalacaoFederacaoDTO->getDblCnpj()!=$parObjInstalacaoFederacaoDTO->getDblCnpj()) {

          //atualiza dados
          $objInstalacaoFederacaoDTO->setStrSigla($parObjInstalacaoFederacaoDTO->getStrSigla());
          $objInstalacaoFederacaoDTO->setStrDescricao($parObjInstalacaoFederacaoDTO->getStrDescricao());
          $objInstalacaoFederacaoDTO->setDblCnpj($parObjInstalacaoFederacaoDTO->getDblCnpj());
          $objInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_RECEBIDA);
          $objInstalacaoFederacaoDTO->setStrStaAgendamento(self::$AI_NENHUM);
          $objInstalacaoFederacaoDTO->setStrStaEstado(self::$EI_ANALISE);
          $this->alterar($objInstalacaoFederacaoDTO);

          $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
          $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
          $objAndamentoInstalacaoDTO->setStrStaEstado($objInstalacaoFederacaoDTO->getStrStaEstado());
          $objAndamentoInstalacaoDTO->setNumIdTarefaInstalacao(TarefaInstalacaoRN::$TI_RECEBIMENTO_SOLICITACAO);

          $arrObjAtributoInstalacaoDTO = array();

          $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
          $objAtributoInstalacaoDTO->setStrNome('INSTITUICAO');
          $objAtributoInstalacaoDTO->setStrValor($objInstalacaoFederacaoDTO->getStrSigla()."¥".$objInstalacaoFederacaoDTO->getStrDescricao());
          $objAtributoInstalacaoDTO->setStrIdOrigem($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
          $arrObjAtributoInstalacaoDTO[] = $objAtributoInstalacaoDTO;

          $objAndamentoInstalacaoDTO->setArrObjAtributoInstalacaoDTO($arrObjAtributoInstalacaoDTO);

          $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
          $objAndamentoInstalacaoRN->lancar($objAndamentoInstalacaoDTO);

        }else if ($objInstalacaoFederacaoDTO->getStrStaEstado()==self::$EI_ANALISE) {
          //apenas  responde normalmente (pode ter ocorrido erro na Instalação de origem na primeira tentativa)
        }
      }

      $objInstalacaoFederacaoDTORet = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTORet->setStrIdInstalacaoFederacao($this->obterIdInstalacaoFederacaoLocal());
      $objInstalacaoFederacaoDTORet->setDblCnpj($this->obterCnpjInstalacaoLocal());
      $objInstalacaoFederacaoDTORet->setStrSigla($this->obterSiglaInstalacaoLocal());
      $objInstalacaoFederacaoDTORet->setStrDescricao($this->obterDescricaoInstalacaoLocal());
      $objInstalacaoFederacaoDTORet->setStrEndereco($this->obterEnderecoInstalacaoLocal());
      $objInstalacaoFederacaoDTORet->setStrStaEstado($objInstalacaoFederacaoDTO->getStrStaEstado());

      return $objInstalacaoFederacaoDTORet;

    }catch(Exception $e){
      throw new InfraException('Erro processando solicitação de registro de Instalação do SEI Federação.',$e);
    }
  }

  protected function liberarRegistroConectado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO) {
    try{

      $objInstalacaoFederacaoDTOBanco = $this->prepararLiberacaoInterno($parObjInstalacaoFederacaoDTO);

      $objWS = $this->obterServico($objInstalacaoFederacaoDTOBanco);

      try {

        $objInstalacao = new stdClass();
        $objInstalacao->IdInstalacaoFederacao = $this->obterIdInstalacaoFederacaoLocal();
        $objInstalacao->Cnpj = $this->obterCnpjInstalacaoLocal();
        $objInstalacao->Sigla = $this->obterSiglaInstalacaoLocal();
        $objInstalacao->Descricao = $this->obterDescricaoInstalacaoLocal();
        $objInstalacao->Endereco = $this->obterEnderecoInstalacaoLocal();
        $objInstalacao->ChavePublica = $objInstalacaoFederacaoDTOBanco->getStrChavePublicaLocal();

        $objRemetente = new stdClass();
        $objRemetente->Instalacao = $objInstalacao;

        $objIdentificacao = new stdClass();
        $objIdentificacao->VersaoSei = SEI_VERSAO;
        $objIdentificacao->VersaoSeiFederacao = SEI_FEDERACAO_VERSAO;
        $objIdentificacao->Remetente = $objRemetente;

        $objWS->liberarRegistro($objIdentificacao);

      } catch (Exception $e) {
        throw new InfraException('Erro liberando registro da Instalação.', $e);
      }

      $this->finalizarLiberacaoInterno($objInstalacaoFederacaoDTOBanco);

    }catch(Exception $e){
      throw new InfraException('Erro liberando registro da Instalação no SEI Federação.',$e);
    }
  }

  protected function prepararLiberacaoInternoControlado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO){

    SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_liberar', __METHOD__, $parObjInstalacaoFederacaoDTO);

    //Regras de Negocio
    $objInfraException = new InfraException();

    $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
    $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
    $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
    $objInstalacaoFederacaoDTO->retStrEndereco();
    $objInstalacaoFederacaoDTO->retStrStaTipo();
    $objInstalacaoFederacaoDTO->retStrStaEstado();
    $objInstalacaoFederacaoDTO->retStrSinAtivo();
    $objInstalacaoFederacaoDTO->retStrSigla();
    $objInstalacaoFederacaoDTO->retStrDescricao();
    $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

    $objInstalacaoFederacaoDTOBanco = $this->consultar($objInstalacaoFederacaoDTO);

    if ($objInstalacaoFederacaoDTOBanco==null){
      $objInfraException->lancarValidacao('Registro da Instalação não encontrado.');
    }

    if ($objInstalacaoFederacaoDTOBanco->getStrSinAtivo()=='N'){
      $objInfraException->lancarValidacao('Registro da Instalação consta como desativado.');
    }

    if ($objInstalacaoFederacaoDTOBanco->getStrStaTipo()==InstalacaoFederacaoRN::$TI_REPLICADA){
      $objInfraException->lancarValidacao('Não é possível liberar uma solicitação de registro que consta como replicada.');
    }

    if ($objInstalacaoFederacaoDTOBanco->getStrStaTipo()==InstalacaoFederacaoRN::$TI_ENVIADA){
      $objInfraException->lancarValidacao('Não é possível liberar uma solicitação de registro enviada para outra Instalação.');
    }

    if ($objInstalacaoFederacaoDTOBanco->getStrStaEstado()==InstalacaoFederacaoRN::$EI_LIBERADA){
      $objInfraException->lancarValidacao('Instalação já consta como liberada.');
    }

    $parChavesLocal = sodium_crypto_box_keypair();
    $chavePrivadaLocal = base64_encode(sodium_crypto_box_secretkey($parChavesLocal));
    $chavePublicaLocal = base64_encode(sodium_crypto_box_publickey($parChavesLocal));

    $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
    $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTOBanco->getStrIdInstalacaoFederacao());
    $objInstalacaoFederacaoDTO->setStrChavePublicaLocal($chavePublicaLocal);
    $objInstalacaoFederacaoDTO->setStrChavePrivada($chavePrivadaLocal);
    $this->alterar($objInstalacaoFederacaoDTO);

    $objInstalacaoFederacaoDTOBanco->setStrChavePublicaLocal($chavePublicaLocal);
    $objInstalacaoFederacaoDTOBanco->setStrChavePrivada($chavePrivadaLocal);

    return $objInstalacaoFederacaoDTOBanco;
  }

  protected function finalizarLiberacaoInternoControlado(InstalacaoFederacaoDTO $objInstalacaoFederacaoDTOBanco){

    $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
    $objInstalacaoFederacaoDTO->setStrStaEstado(InstalacaoFederacaoRN::$EI_LIBERADA);
    $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTOBanco->getStrIdInstalacaoFederacao());
    $objInstalacaoFederacaoDTO->setStrChavePublicaLocal($objInstalacaoFederacaoDTOBanco->getStrChavePublicaLocal());
    $objInstalacaoFederacaoDTO->setStrChavePrivada($objInstalacaoFederacaoDTOBanco->getStrChavePrivada());
    $this->alterar($objInstalacaoFederacaoDTO);

    $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
    $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
    $objAndamentoInstalacaoDTO->setStrStaEstado($objInstalacaoFederacaoDTO->getStrStaEstado());
    $objAndamentoInstalacaoDTO->setNumIdTarefaInstalacao(TarefaInstalacaoRN::$TI_ENVIO_LIBERACAO);

    $arrObjAtributoInstalacaoDTO = array();

    $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
    $objAtributoInstalacaoDTO->setStrNome('INSTITUICAO');
    $objAtributoInstalacaoDTO->setStrValor($objInstalacaoFederacaoDTOBanco->getStrSigla()."¥".$objInstalacaoFederacaoDTOBanco->getStrDescricao());
    $objAtributoInstalacaoDTO->setStrIdOrigem($objInstalacaoFederacaoDTOBanco->getStrIdInstalacaoFederacao());
    $arrObjAtributoInstalacaoDTO[] = $objAtributoInstalacaoDTO;

    $objAndamentoInstalacaoDTO->setArrObjAtributoInstalacaoDTO($arrObjAtributoInstalacaoDTO);

    $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
    $objAndamentoInstalacaoRN->lancar($objAndamentoInstalacaoDTO);
  }

  protected function processarLiberacaoRegistroControlado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO) {
    try{

      $objInfraException = new InfraException();

      $strIdentificacaoRemota = $parObjInstalacaoFederacaoDTO->getStrSigla().' ('.InfraUtil::formatarCnpj($parObjInstalacaoFederacaoDTO->getDblCnpj()).')';

      //Regras de Negocio
      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retDblCnpj();
      $objInstalacaoFederacaoDTO->retStrStaTipo();
      $objInstalacaoFederacaoDTO->retStrEndereco();
      $objInstalacaoFederacaoDTO->retStrStaEstado();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

      $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

      if ($objInstalacaoFederacaoDTO==null){
        $objInfraException->lancarValidacao('Instalação '.$strIdentificacaoRemota.' não possui registro na instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

      //nao pode liberar um registro que foi recebido em outra Instalacao
      if ($objInstalacaoFederacaoDTO->getStrStaTipo()==InstalacaoFederacaoRN::$TI_RECEBIDA){
        $objInfraException->lancarValidacao('Instalação '.$strIdentificacaoRemota.' possui solicitação de registro recebida na instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

      //pode liberar se foi enviada ou replicada
      if ($objInstalacaoFederacaoDTO->getStrStaTipo()==self::$TI_ENVIADA) {

        $objInstalacaoFederacaoDTO_Banco = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO_Banco->setBolExclusaoLogica(false);
        $objInstalacaoFederacaoDTO_Banco->retStrEndereco();
        $objInstalacaoFederacaoDTO_Banco->retDblCnpj();
        $objInstalacaoFederacaoDTO_Banco->retStrSigla();
        $objInstalacaoFederacaoDTO_Banco->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

        $objInstalacaoFederacaoDTO_Banco = $this->consultar($objInstalacaoFederacaoDTO_Banco);
        $objWS = $this->obterServico($objInstalacaoFederacaoDTO_Banco);

        $parChavesLocal = sodium_crypto_box_keypair();
        $chavePrivadaLocal = base64_encode(sodium_crypto_box_secretkey($parChavesLocal));
        $chavePublicaLocal = base64_encode(sodium_crypto_box_publickey($parChavesLocal));
        $strHash = $this->criptografarTimeStamp($chavePrivadaLocal, $parObjInstalacaoFederacaoDTO->getStrChavePublicaRemota());

        try {

          $objInstalacao = new stdClass();
          $objInstalacao->IdInstalacaoFederacao = $this->obterIdInstalacaoFederacaoLocal();
          $objInstalacao->Sigla = $this->obterSiglaInstalacaoLocal();
          $objInstalacao->Cnpj = $this->obterCnpjInstalacaoLocal();
          $objInstalacao->Hash = $strHash;
          $objInstalacao->ChavePublica = $chavePublicaLocal;

          $objRemetente = new stdClass();
          $objRemetente->Instalacao = $objInstalacao;

          $objIdentificacao = new stdClass();
          $objIdentificacao->VersaoSei = SEI_VERSAO;
          $objIdentificacao->VersaoSeiFederacao = SEI_FEDERACAO_VERSAO;
          $objIdentificacao->Remetente = $objRemetente;

          $objWS->confirmarLiberacao($objIdentificacao);

        } catch (Exception $e) {
          throw new InfraException('Erro confirmando liberação do registro na Instalação .', $e);
        }

        //atualiza dados
        $objInstalacaoFederacaoDTO->setStrChavePublicaRemota($parObjInstalacaoFederacaoDTO->getStrChavePublicaRemota());
        $objInstalacaoFederacaoDTO->setStrChavePublicaLocal($chavePublicaLocal);
        $objInstalacaoFederacaoDTO->setStrChavePrivada($chavePrivadaLocal);
        $objInstalacaoFederacaoDTO->setStrSigla($parObjInstalacaoFederacaoDTO->getStrSigla());
        $objInstalacaoFederacaoDTO->setStrDescricao($parObjInstalacaoFederacaoDTO->getStrDescricao());
        $objInstalacaoFederacaoDTO->setStrStaAgendamento(self::$AI_NENHUM);
        $objInstalacaoFederacaoDTO->setStrStaEstado(self::$EI_LIBERADA);

        $this->alterar($objInstalacaoFederacaoDTO);

        $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
        $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $objAndamentoInstalacaoDTO->setStrStaEstado($objInstalacaoFederacaoDTO->getStrStaEstado());
        $objAndamentoInstalacaoDTO->setNumIdTarefaInstalacao(TarefaInstalacaoRN::$TI_RECEBIMENTO_LIBERACAO);

        $arrObjAtributoInstalacaoDTO = array();

        $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
        $objAtributoInstalacaoDTO->setStrNome('INSTITUICAO');
        $objAtributoInstalacaoDTO->setStrValor($parObjInstalacaoFederacaoDTO->getStrSigla()."¥".$parObjInstalacaoFederacaoDTO->getStrDescricao());
        $objAtributoInstalacaoDTO->setStrIdOrigem($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $arrObjAtributoInstalacaoDTO[] = $objAtributoInstalacaoDTO;

        $objAndamentoInstalacaoDTO->setArrObjAtributoInstalacaoDTO($arrObjAtributoInstalacaoDTO);

        $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
        $objAndamentoInstalacaoRN->lancar($objAndamentoInstalacaoDTO);
      }
    }catch(Exception $e){
      throw new InfraException('Erro processando liberação da Instalação no SEI Federação.',$e);
    }
  }

  private function criptografarTimeStamp($chavePrivadaLocal, $strChavePublicaRemota){
    try {
      $strTimeStamp = gmdate("d/m/Y H:i:s");

      $parChavesLocalRemota = sodium_crypto_box_keypair_from_secretkey_and_publickey(
          base64_decode($chavePrivadaLocal),
          base64_decode($strChavePublicaRemota)
      );
      $strNonce = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);
      $strHash = sodium_crypto_box(
          $strTimeStamp,
          $strNonce,
          $parChavesLocalRemota
      );
      return base64_encode($strNonce.$strHash);

    }catch(Exception $e) {
      throw new InfraException('Erro criptografando dados.', $e);
    }
  }

  private function descriptografarHash($chavePrivadaLocal, $strChavePublicaRemota, $strHash){
    try {

      $parChavesLocalRemota = sodium_crypto_box_keypair_from_secretkey_and_publickey(
          base64_decode($chavePrivadaLocal),
          base64_decode($strChavePublicaRemota)
      );

      $strNonce = substr(base64_decode($strHash), 0, 24);
      $strHash = substr(base64_decode($strHash), 24);
      $numTimeStampRecebido = sodium_crypto_box_open(
          $strHash,
          $strNonce,
          $parChavesLocalRemota
      );

      if ($numTimeStampRecebido === false) {
        throw new InfraException('Dados de criptografia inválidos.');
      } else {

        $numSegundosSincronizacao = ConfiguracaoSEI::getInstance()->getValor('Federacao', 'NumSegundosSincronizacao', false, 300);

        if (!is_numeric($numSegundosSincronizacao) || $numSegundosSincronizacao < 0) {
          $numSegundosSincronizacao = 300;
        }

        $dthTimeStamp_Atual = gmdate("d/m/Y H:i:s");
        $dthTimeStamp_Recebido = InfraData::calcularData($numSegundosSincronizacao, InfraData::$UNIDADE_SEGUNDOS, InfraData::$SENTIDO_ADIANTE, $numTimeStampRecebido);

        if (InfraData::compararDataHorasSimples($dthTimeStamp_Recebido, $dthTimeStamp_Atual) == 1) {
          throw new InfraException('Dados de criptografia expirados.');
        }

      }

    }catch(Exception $e){
      throw new InfraException('Erro descriptografando dados.', $e);
    }
  }

  protected function processarConfirmacaoLiberacaoControlado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO){
    try{

      $strIdentificacaoRemota = $parObjInstalacaoFederacaoDTO->getStrSigla().' ('.InfraUtil::formatarCnpj($parObjInstalacaoFederacaoDTO->getDblCnpj()).')';

      $objInfraException = new InfraException();

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->retStrEndereco();
      $objInstalacaoFederacaoDTO->retDblCnpj();
      $objInstalacaoFederacaoDTO->retStrSigla();
      $objInstalacaoFederacaoDTO->retStrChavePublicaLocal();
      $objInstalacaoFederacaoDTO->retStrChavePrivada();
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

      $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

      if ($objInstalacaoFederacaoDTO==null){
        $objInfraException->lancarValidacao('Instalação '.$strIdentificacaoRemota.' não possui registro na instalação local.');
      }

      if(InfraString::isBolVazia($objInstalacaoFederacaoDTO->getStrChavePublicaLocal())){
        $objInfraException->lancarValidacao('Instalação '.$strIdentificacaoRemota.' não possui chave pública cadastrada na instalação local.');
      }

      $this->descriptografarHash($objInstalacaoFederacaoDTO->getStrChavePrivada(), $parObjInstalacaoFederacaoDTO->getStrChavePublicaRemota(),$parObjInstalacaoFederacaoDTO->getStrHash());

      //atualiza dados
      $objInstalacaoFederacaoDTO_Atualizar = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO_Atualizar->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
      $objInstalacaoFederacaoDTO_Atualizar->setStrChavePublicaRemota($parObjInstalacaoFederacaoDTO->getStrChavePublicaRemota());
      $this->alterar($objInstalacaoFederacaoDTO_Atualizar);

    }catch(Exception $e){
     // LogSEI::getInstance()->gravar($e);
      throw new InfraException('Erro processando liberação da Instalação no SEI Federação.',$e);
    }
  }

  protected function bloquearRegistroConectado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO)
  {

    //Transação separada pois para bloquear localmente não pode depender da resposta da outra Instalação
    $objInstalacaoFederacaoDTOBanco = $this->bloquearRegistroInterno($parObjInstalacaoFederacaoDTO);

    try {
      //tenta sinalizar na outra Instalação a mudança na situação
      $objWS = $this->obterServico($objInstalacaoFederacaoDTOBanco);

      $objInstalacao = new stdClass();
      $objInstalacao->IdInstalacaoFederacao = $this->obterIdInstalacaoFederacaoLocal();
      $objInstalacao->Sigla = $this->obterSiglaInstalacaoLocal();
      $objInstalacao->Descricao = $this->obterSiglaInstalacaoLocal();
      $objInstalacao->Cnpj = $this->obterCnpjInstalacaoLocal();

      $objRemetente = new stdClass();
      $objRemetente->Instalacao = $objInstalacao;

      $objIdentificacao = new stdClass();
      $objIdentificacao->VersaoSei = SEI_VERSAO;
      $objIdentificacao->VersaoSeiFederacao = SEI_FEDERACAO_VERSAO;
      $objIdentificacao->Remetente = $objRemetente;

      $objWS->bloquearRegistro($objIdentificacao);

    } catch (Exception $e) {
      //apenas loga o erro (evita tentativa de evitar o bloqueio retornando um erro)
      LogSEI::getInstance()->gravar('Erro sinalizando bloqueio para a instalação remota:'.InfraException::inspecionar($e));
    }
  }

  protected function bloquearRegistroInternoControlado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_bloquear', __METHOD__, $parObjInstalacaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retStrEndereco();
      $objInstalacaoFederacaoDTO->retStrStaTipo();
      $objInstalacaoFederacaoDTO->retStrStaEstado();
      $objInstalacaoFederacaoDTO->retStrSinAtivo();
      $objInstalacaoFederacaoDTO->retStrSigla();
      $objInstalacaoFederacaoDTO->retStrDescricao();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

      $objInstalacaoFederacaoDTOBanco = $this->consultar($objInstalacaoFederacaoDTO);

      if ($objInstalacaoFederacaoDTOBanco==null){
        $objInfraException->lancarValidacao('Registro da Instalação não encontrado.');
      }

      if ($objInstalacaoFederacaoDTOBanco->getStrSinAtivo()=='N'){
        $objInfraException->lancarValidacao('Registro da Instalação consta como desativado.');
      }

      if ($objInstalacaoFederacaoDTOBanco->getStrStaTipo()==InstalacaoFederacaoRN::$TI_ENVIADA){
        $objInfraException->lancarValidacao('Não é possível bloquear uma solicitação de registro enviada para outra Instalação.');
      }

      if ($objInstalacaoFederacaoDTOBanco->getStrStaEstado()!=InstalacaoFederacaoRN::$EI_BLOQUEADA) {

        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->setStrStaEstado(InstalacaoFederacaoRN::$EI_BLOQUEADA);
        $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTOBanco->getStrIdInstalacaoFederacao());

        $this->alterar($objInstalacaoFederacaoDTO);


        $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
        $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $objAndamentoInstalacaoDTO->setStrStaEstado($objInstalacaoFederacaoDTO->getStrStaEstado());
        $objAndamentoInstalacaoDTO->setNumIdTarefaInstalacao(TarefaInstalacaoRN::$TI_ENVIO_BLOQUEIO);

        $arrObjAtributoInstalacaoDTO = array();

        $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
        $objAtributoInstalacaoDTO->setStrNome('INSTITUICAO');
        $objAtributoInstalacaoDTO->setStrValor($objInstalacaoFederacaoDTOBanco->getStrSigla()."¥".$objInstalacaoFederacaoDTOBanco->getStrDescricao());
        $objAtributoInstalacaoDTO->setStrIdOrigem($objInstalacaoFederacaoDTOBanco->getStrIdInstalacaoFederacao());
        $arrObjAtributoInstalacaoDTO[] = $objAtributoInstalacaoDTO;

        $objAndamentoInstalacaoDTO->setArrObjAtributoInstalacaoDTO($arrObjAtributoInstalacaoDTO);

        $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
        $objAndamentoInstalacaoRN->lancar($objAndamentoInstalacaoDTO);
      }

      return $objInstalacaoFederacaoDTOBanco;

    }catch(Exception $e){
      throw new InfraException('Erro bloqueando registro da Instalação no SEI Federação.',$e);
    }
  }

  protected function processarBloqueioRegistroControlado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO) {
    try{

      //Regras de Negocio
      $objInfraException = new InfraException();

      $strIdentificacaoRemota = $parObjInstalacaoFederacaoDTO->getStrSigla().' ('.InfraUtil::formatarCnpj($parObjInstalacaoFederacaoDTO->getDblCnpj()).')';

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retStrStaEstado();
      $objInstalacaoFederacaoDTO->retStrSigla();
      $objInstalacaoFederacaoDTO->retStrDescricao();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

      $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

      if ($objInstalacaoFederacaoDTO==null){
        $objInfraException->lancarValidacao('Instalação '.$strIdentificacaoRemota.' não possui registro na instalação remota.');
      }

      //somente bloqueia se estiver em analise ou liberada
      if ($objInstalacaoFederacaoDTO->getStrStaEstado()==self::$EI_ANALISE || $objInstalacaoFederacaoDTO->getStrStaEstado()==self::$EI_LIBERADA) {

        $objInstalacaoFederacaoDTO->setStrStaEstado(self::$EI_BLOQUEADA);

        $this->alterar($objInstalacaoFederacaoDTO);

        $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
        $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $objAndamentoInstalacaoDTO->setStrStaEstado($objInstalacaoFederacaoDTO->getStrStaEstado());
        $objAndamentoInstalacaoDTO->setNumIdTarefaInstalacao(TarefaInstalacaoRN::$TI_RECEBIMENTO_BLOQUEIO);

        $arrObjAtributoInstalacaoDTO = array();

        $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
        $objAtributoInstalacaoDTO->setStrNome('INSTITUICAO');
        $objAtributoInstalacaoDTO->setStrValor($parObjInstalacaoFederacaoDTO->getStrSigla()."¥".$parObjInstalacaoFederacaoDTO->getStrDescricao());
        $objAtributoInstalacaoDTO->setStrIdOrigem($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $arrObjAtributoInstalacaoDTO[] = $objAtributoInstalacaoDTO;

        $objAndamentoInstalacaoDTO->setArrObjAtributoInstalacaoDTO($arrObjAtributoInstalacaoDTO);

        $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
        $objAndamentoInstalacaoRN->lancar($objAndamentoInstalacaoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro processando bloqueio da Instalação do SEI Federação.',$e);
    }
  }

  protected function verificarConexaoConectado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO) {
    try{

      $ret = '';

      SessaoSEI::getInstance()->validarAuditarPermissao('instalacao_federacao_verificar_conexao', __METHOD__, $parObjInstalacaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retStrStaEstado();
      $objInstalacaoFederacaoDTO->retStrSinAtivo();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

      $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

      if ($objInstalacaoFederacaoDTO==null){
        $objInfraException->lancarValidacao('Instalação não encontrada.');
      }

      if ($objInstalacaoFederacaoDTO->getStrSinAtivo()=='N'){
        return self::$TC_INDISPONIVEL;
      }

      if ($objInstalacaoFederacaoDTO->getStrStaEstado() == self::$EI_ANALISE) {
        return self::$TC_INDISPONIVEL;
      }

      if ($objInstalacaoFederacaoDTO->getStrStaEstado() == self::$EI_BLOQUEADA) {
        return self::$TC_INDISPONIVEL;
      }

      try {

        $ret = $this->executar('verificarConexao', $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

      } catch (Exception $e) {
        $ret = self::$TC_ERRO.': '.($e->getMessage()!=null ? $e->getMessage() : $e->__toString());
        LogSEI::getInstance()->gravar(InfraException::inspecionar($e));
      }

      return $ret;


    }catch(Exception $e){
      throw new InfraException('Erro obtendo estado da Instalação do SEI Federação.',$e);
    }
  }

  protected function sincronizarControlado(InstalacaoFederacaoDTO $parObjInstalacaoFederacaoDTO){
    try{

      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retStrSigla();
      $objInstalacaoFederacaoDTO->retStrDescricao();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());

      $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

      if ($objInstalacaoFederacaoDTO == null){

        throw new InfraException('Instalação '.$parObjInstalacaoFederacaoDTO->getStrSigla().' não encontrada.');

      }else{

        if ($objInstalacaoFederacaoDTO->getStrSigla()!=$parObjInstalacaoFederacaoDTO->getStrSigla() || $objInstalacaoFederacaoDTO->getStrDescricao()!=$parObjInstalacaoFederacaoDTO->getStrDescricao()){
          $objInstalacaoFederacaoDTO->setStrSigla($parObjInstalacaoFederacaoDTO->getStrSigla());
          $objInstalacaoFederacaoDTO->setStrDescricao($parObjInstalacaoFederacaoDTO->getStrDescricao());
          $this->alterar($objInstalacaoFederacaoDTO);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro sincronizando instalação do SEI Federação.',$e);
    }
  }

  public function executar($func, $strIdInstalacaoFederacao, ...$params) {
    try {

      $objInfraException = new InfraException();

      //Instalacao
      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
      $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
      $objInstalacaoFederacaoDTO->retDblCnpj();
      $objInstalacaoFederacaoDTO->retStrSigla();
      $objInstalacaoFederacaoDTO->retStrDescricao();
      $objInstalacaoFederacaoDTO->retStrEndereco();
      $objInstalacaoFederacaoDTO->retStrChavePrivada();
      $objInstalacaoFederacaoDTO->retStrChavePublicaRemota();
      $objInstalacaoFederacaoDTO->retStrStaEstado();
      $objInstalacaoFederacaoDTO->retStrSinAtivo();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($strIdInstalacaoFederacao);

      $objInstalacaoFederacaoDTO = $this->consultar($objInstalacaoFederacaoDTO);

      if ($objInstalacaoFederacaoDTO==null){
        throw new InfraException('Instalação '.$strIdInstalacaoFederacao.' não encontrada.');
      }

      if ($objInstalacaoFederacaoDTO->getStrSinAtivo()=='N'){
        $objInfraException->lancarValidacao('Instalação '.$objInstalacaoFederacaoDTO->getStrSigla().' desativada.');
      }

      if ($objInstalacaoFederacaoDTO->getStrStaEstado()!=self::$EI_LIBERADA){
        $objInfraException->lancarValidacao('Instalação '.$objInstalacaoFederacaoDTO->getStrSigla().' não está liberada na instalação '.$this->obterSiglaInstalacaoLocal().'.');
      }

      $objInstalacao = new stdClass();
      $objInstalacao->IdInstalacaoFederacao = $this->obterIdInstalacaoFederacaoLocal();
      $objInstalacao->Cnpj = $this->obterCnpjInstalacaoLocal();
      $objInstalacao->Endereco = $this->obterEnderecoInstalacaoLocal();
      $objInstalacao->Sigla = $this->obterSiglaInstalacaoLocal();
      $objInstalacao->Descricao = $this->obterDescricaoInstalacaoLocal();
      $objInstalacao->Hash = $this->criptografarTimeStamp($objInstalacaoFederacaoDTO->getStrChavePrivada(), $objInstalacaoFederacaoDTO->getStrChavePublicaRemota());

      //Orgao
      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrIdOrgaoFederacao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->retStrDescricao();
      $objOrgaoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());

      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

      if ($objOrgaoDTO->getStrIdOrgaoFederacao()==null){
        $objOrgaoRN->gerarIdentificadorFederacao($objOrgaoDTO);
      }

      $objOrgao = new stdClass();
      $objOrgao->IdOrgaoFederacao = $objOrgaoDTO->getStrIdOrgaoFederacao();
      $objOrgao->Sigla = $objOrgaoDTO->getStrSigla();
      $objOrgao->Descricao = $objOrgaoDTO->getStrDescricao();

      //Unidade
      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->retNumIdUnidade();
      $objUnidadeDTO->retStrIdUnidadeFederacao();
      $objUnidadeDTO->retStrSigla();
      $objUnidadeDTO->retStrDescricao();
      $objUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

      if ($objUnidadeDTO->getStrIdUnidadeFederacao()==null){
        $objUnidadeRN->gerarIdentificadorFederacao($objUnidadeDTO);
      }

      $objUnidade = new stdClass();
      $objUnidade->IdUnidadeFederacao = $objUnidadeDTO->getStrIdUnidadeFederacao();
      $objUnidade->Sigla = $objUnidadeDTO->getStrSigla();
      $objUnidade->Descricao = $objUnidadeDTO->getStrDescricao();

      //Usuario
      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->retNumIdUsuario();
      $objUsuarioDTO->retStrIdUsuarioFederacao();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      $objUsuarioRN = new UsuarioRN();
      $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

      if ($objUsuarioDTO->getStrIdUsuarioFederacao()==null){
        $objUsuarioRN->gerarIdentificadorFederacao($objUsuarioDTO);
      }
      
      $objUsuario = new stdClass();
      $objUsuario->IdUsuarioFederacao = $objUsuarioDTO->getStrIdUsuarioFederacao();
      $objUsuario->Sigla = $objUsuarioDTO->getStrSigla();
      $objUsuario->Nome = $objUsuarioDTO->getStrNome();

      $objRemetente = new stdClass();
      $objRemetente->Instalacao = $objInstalacao;
      $objRemetente->Orgao = $objOrgao;
      $objRemetente->Unidade = $objUnidade;
      $objRemetente->Usuario = $objUsuario;

      $objDestinatario = new stdClass();
      $objInstalacaoDestino = new stdClass();
      $objInstalacaoDestino->IdInstalacaoFederacao = $objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao();
      $objInstalacaoDestino->Cnpj = $objInstalacaoFederacaoDTO->getDblCnpj();
      $objDestinatario->Instalacao = $objInstalacaoDestino;

      $objIdentificacao = new stdClass();
      $objIdentificacao->VersaoSei = SEI_VERSAO;
      $objIdentificacao->VersaoSeiFederacao = SEI_FEDERACAO_VERSAO;
      $objIdentificacao->Remetente = $objRemetente;
      $objIdentificacao->Destinatario = $objDestinatario;

      //LogSEI::getInstance()->gravar(print_r($objRemetente,true));

      $objWS = $this->obterServico($objInstalacaoFederacaoDTO);

      array_unshift($params, $objIdentificacao);

      $ret = call_user_func_array(array($objWS, $func), $params);

    }catch(Throwable $e){

      if (strpos(strtoupper($e->__toString()),'ERROR FETCHING HTTP HEADERS')!==false){
        throw new InfraException('Não houve resposta para o serviço solicitado na instalação '.$objInstalacaoFederacaoDTO->getStrSigla().'.', $e);
      }

      if ($func == 'verificarConexao') {
        throw $e;
      }

      throw new InfraException('Erro processando serviço "'.$func.'" do SEI Federação na instalação "'.$objInstalacaoFederacaoDTO->getStrSigla().'".', $e);
    }

    return $ret;
  }

  protected function listarAcessosConectado(ProcedimentoDTO $objProcedimentoDTO){
    try {

      $arrObjInstalacaoFederacaoDTO = array();

      if ($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo()!=null) {

        $objSinalizacaoFederacaoDTO = new SinalizacaoFederacaoDTO();
        $objSinalizacaoFederacaoDTO->retTodos();
        $objSinalizacaoFederacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objSinalizacaoFederacaoDTO->setStrIdProtocoloFederacao($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo());

        $objSinalizacaoFederacaoRN = new SinalizacaoFederacaoRN();
        $arrObjSinalizacaoFederacaoDTO = InfraArray::indexarArrInfraDTO($objSinalizacaoFederacaoRN->listar($objSinalizacaoFederacaoDTO), 'IdInstalacaoFederacao');

        $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
        $objAcessoFederacaoDTO->retStrIdAcessoFederacao();
        $objAcessoFederacaoDTO->retStrIdInstalacaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrSiglaInstalacaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrDescricaoInstalacaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrIdOrgaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrSiglaOrgaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrDescricaoOrgaoFederacaoRem();
        $objAcessoFederacaoDTO->retStrIdInstalacaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrSiglaInstalacaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrDescricaoInstalacaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrIdOrgaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrSiglaOrgaoFederacaoDest();
        $objAcessoFederacaoDTO->retStrDescricaoOrgaoFederacaoDest();
        $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo());
        $objAcessoFederacaoDTO->setOrdStrIdAcessoFederacao(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objAcessoFederacaoRN = new AcessoFederacaoRN();
        $arrObjAcessoFederacaoDTO = $objAcessoFederacaoRN->listar($objAcessoFederacaoDTO);

        $arrObjOrgaoFederacaoDTO = array();

        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();

        $bolAcessoLocal = false;

        foreach ($arrObjAcessoFederacaoDTO as $objAcessoFederacaoDTO) {

          $strIdInstalacaoFederacaoRem = $objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoRem();
          if (!isset($arrObjInstalacaoFederacaoDTO[$strIdInstalacaoFederacaoRem])) {
            $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
            $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($strIdInstalacaoFederacaoRem);
            $objInstalacaoFederacaoDTO->setStrSigla($objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoRem());
            $objInstalacaoFederacaoDTO->setStrDescricao($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoRem());
            $arrObjInstalacaoFederacaoDTO[$strIdInstalacaoFederacaoRem] = $objInstalacaoFederacaoDTO;
            $arrObjOrgaoFederacaoDTO[$strIdInstalacaoFederacaoRem] = array();
          }

          $strIdInstalacaoFederacaoDest = $objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoDest();
          if (!isset($arrObjInstalacaoFederacaoDTO[$strIdInstalacaoFederacaoDest])) {
            $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
            $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($strIdInstalacaoFederacaoDest);
            $objInstalacaoFederacaoDTO->setStrSigla($objAcessoFederacaoDTO->getStrSiglaInstalacaoFederacaoDest());
            $objInstalacaoFederacaoDTO->setStrDescricao($objAcessoFederacaoDTO->getStrDescricaoInstalacaoFederacaoDest());
            $arrObjInstalacaoFederacaoDTO[$strIdInstalacaoFederacaoDest] = $objInstalacaoFederacaoDTO;
            $arrObjOrgaoFederacaoDTO[$strIdInstalacaoFederacaoDest] = array();
          }

          $strIdOrgaoFederacaoRem = $objAcessoFederacaoDTO->getStrIdOrgaoFederacaoRem();
          if (!isset($arrObjOrgaoFederacaoDTO[$strIdInstalacaoFederacaoRem][$strIdOrgaoFederacaoRem])) {
            $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
            $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($strIdOrgaoFederacaoRem);
            $objOrgaoFederacaoDTO->setStrSigla($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoRem());
            $objOrgaoFederacaoDTO->setStrDescricao($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoRem());
            $objOrgaoFederacaoDTO->setStrSinOrigem('N');
            $arrObjOrgaoFederacaoDTO[$strIdInstalacaoFederacaoRem][$strIdOrgaoFederacaoRem] = $objOrgaoFederacaoDTO;
          }

          $strIdOrgaoFederacaoDest = $objAcessoFederacaoDTO->getStrIdOrgaoFederacaoDest();
          if (!isset($arrObjOrgaoFederacaoDTO[$strIdInstalacaoFederacaoDest][$strIdOrgaoFederacaoDest])) {
            $objOrgaoFederacaoDTO = new OrgaoFederacaoDTO();
            $objOrgaoFederacaoDTO->setStrIdOrgaoFederacao($strIdOrgaoFederacaoDest);
            $objOrgaoFederacaoDTO->setStrSigla($objAcessoFederacaoDTO->getStrSiglaOrgaoFederacaoDest());
            $objOrgaoFederacaoDTO->setStrDescricao($objAcessoFederacaoDTO->getStrDescricaoOrgaoFederacaoDest());
            $objOrgaoFederacaoDTO->setStrSinOrigem('N');
            $arrObjOrgaoFederacaoDTO[$strIdInstalacaoFederacaoDest][$strIdOrgaoFederacaoDest] = $objOrgaoFederacaoDTO;
          }

          if ($objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoDest() == $objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal()){
            $bolAcessoLocal = true;
          }
        }

        if (count($arrObjAcessoFederacaoDTO)) {
          if (!$bolAcessoLocal) {
            $objProtocoloFederacaoDTO = new ProtocoloFederacaoDTO();
            $objProtocoloFederacaoDTO->retStrIdInstalacaoFederacao();
            $objProtocoloFederacaoDTO->setStrIdProtocoloFederacao($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo());

            $objProtocoloFederacaoRN = new ProtocoloFederacaoRN();
            $objProtocoloFederacaoDTO = $objProtocoloFederacaoRN->consultar($objProtocoloFederacaoDTO);

            if ($objProtocoloFederacaoDTO->getStrIdInstalacaoFederacao() == $objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal()) {
              $bolAcessoLocal = true;
            }
          }

          if ($bolAcessoLocal) {

            //echo print_r(array_keys($arrObjInstalacaoFederacaoDTO),true).'<br>';
            //echo print_r(array_keys($arrObjOrgaoFederacaoDTO),true).'<br>';

            //remove orgao origem da lista para deixar no topo
            $objOrgaoFederacaoDTOOrigem = $arrObjOrgaoFederacaoDTO[$arrObjAcessoFederacaoDTO[0]->getStrIdInstalacaoFederacaoRem()][$arrObjAcessoFederacaoDTO[0]->getStrIdOrgaoFederacaoRem()];
            unset($arrObjOrgaoFederacaoDTO[$arrObjAcessoFederacaoDTO[0]->getStrIdInstalacaoFederacaoRem()][$arrObjAcessoFederacaoDTO[0]->getStrIdOrgaoFederacaoRem()]);
            $objOrgaoFederacaoDTOOrigem->setStrSinOrigem('S');

            foreach($arrObjOrgaoFederacaoDTO as $strIdInstalacaoFederacao => $arr){
              $arr = array_values($arr);
              InfraArray::ordenarArrInfraDTO($arr, 'Sigla', InfraArray::$TIPO_ORDENACAO_ASC);
              $arrObjInstalacaoFederacaoDTO[$strIdInstalacaoFederacao]->setArrObjOrgaoFederacaoDTO($arr);
            }

            //coloca instalacao origem em primeiro
            $objInstalacaoFederacaoDTOOrigem = $arrObjInstalacaoFederacaoDTO[$arrObjAcessoFederacaoDTO[0]->getStrIdInstalacaoFederacaoRem()];
            unset($arrObjInstalacaoFederacaoDTO[$arrObjAcessoFederacaoDTO[0]->getStrIdInstalacaoFederacaoRem()]);
            $arrObjInstalacaoFederacaoDTO = array_values($arrObjInstalacaoFederacaoDTO);
            InfraArray::ordenarArrInfraDTO($arrObjInstalacaoFederacaoDTO, 'Sigla', InfraArray::$TIPO_ORDENACAO_ASC);
            $arrObjInstalacaoFederacaoDTO = array_merge(array($objInstalacaoFederacaoDTOOrigem), $arrObjInstalacaoFederacaoDTO);

            //coloca o orgao origem em primeiro na lista da instalacao origem
            $arrObjOrgaoFederacaoDTOOrigem = $arrObjInstalacaoFederacaoDTO[0]->getArrObjOrgaoFederacaoDTO();
            $arrObjOrgaoFederacaoDTOOrigem = array_merge(array($objOrgaoFederacaoDTOOrigem), $arrObjOrgaoFederacaoDTOOrigem);
            $arrObjInstalacaoFederacaoDTO[0]->setArrObjOrgaoFederacaoDTO($arrObjOrgaoFederacaoDTOOrigem);

            foreach($arrObjInstalacaoFederacaoDTO as $objInstalacaoFederacaoDTO){
              if (!isset($arrObjSinalizacaoFederacaoDTO[$objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao()])){
                $objInstalacaoFederacaoDTO->setObjSinalizacaoFederacaoDTO(null);
              }else{
                $objInstalacaoFederacaoDTO->setObjSinalizacaoFederacaoDTO($arrObjSinalizacaoFederacaoDTO[$objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao()]);
              }
            }
          }
        }

        //remove sinalizacoes de instalacoes que nao estao na lista
        $arrIdInstalacaoFederacao = InfraArray::converterArrInfraDTO($arrObjInstalacaoFederacaoDTO, 'IdInstalacaoFederacao');

        foreach($arrIdInstalacaoFederacao as $strIdInstalacaoFederacao){
          if (isset($arrObjSinalizacaoFederacaoDTO[$strIdInstalacaoFederacao])){
            unset($arrObjSinalizacaoFederacaoDTO[$strIdInstalacaoFederacao]);
          }
        }

        if (count($arrObjSinalizacaoFederacaoDTO)){
          $objSinalizacaoFederacaoRN->excluir(array_values($arrObjSinalizacaoFederacaoDTO));
        }
      }

      if (!$bolAcessoLocal){
        $arrObjInstalacaoFederacaoDTO = array();
      }

      return $arrObjInstalacaoFederacaoDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro listando instalações com acesso no SEI Federação.', $e);
    }
  }

  protected function processarAgendamentoConectado(){
    try {

      //processa replicacoes pendentes
      $objReplicacaoFederacaoRN = new ReplicacaoFederacaoRN();
      $objReplicacaoFederacaoRN->replicar();

      //envia email de aviso sobre solicitacoes
      $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
      $strEmailSistema = $objInfraParametro->getValor('SEI_EMAIL_SISTEMA');
      $strEmailAdministrador = $objInfraParametro->getValor('SEI_EMAIL_ADMINISTRADOR');

      if (!InfraString::isBolVazia($strEmailSistema) && !InfraString::isBolVazia($strEmailAdministrador)) {

        MailSEI::getInstance()->limpar();

        $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
        $objInstalacaoFederacaoDTO->retStrIdInstalacaoFederacao();
        $objInstalacaoFederacaoDTO->retDblCnpj();
        $objInstalacaoFederacaoDTO->retStrSigla();
        $objInstalacaoFederacaoDTO->retStrDescricao();
        $objInstalacaoFederacaoDTO->retStrEndereco();
        $objInstalacaoFederacaoDTO->retStrStaEstado();
        $objInstalacaoFederacaoDTO->retStrStaTipo();
        $objInstalacaoFederacaoDTO->setStrStaTipo(self::$TI_LOCAL, InfraDTO::$OPER_DIFERENTE);
        $objInstalacaoFederacaoDTO->setStrStaAgendamento(self::$AI_NENHUM);
        $arrObjInstalacaoFederacaoDTO = $this->listar($objInstalacaoFederacaoDTO);

        foreach ($arrObjInstalacaoFederacaoDTO as $objInstalacaoFederacaoDTO) {

          $bolEmail = false;

          if ($objInstalacaoFederacaoDTO->getStrStaTipo() == self::$TI_RECEBIDA) {
            if ($objInstalacaoFederacaoDTO->getStrStaEstado() == self::$EI_ANALISE) {
              $objEmailDTO = new EmailDTO();
              $objEmailDTO->setStrDe($strEmailSistema);
              $objEmailDTO->setStrPara($strEmailAdministrador);
              $objEmailDTO->setStrAssunto('SEI Federação - Solicitação recebida de '.$objInstalacaoFederacaoDTO->getStrSigla());

              $strConteudo = '';
              $strConteudo .= "\n".'Solicitação de registro recebida no SEI Federação de:'."\n\n";
              $strConteudo .= 'CNPJ - '.InfraUtil::formatarCnpj($objInstalacaoFederacaoDTO->getDblCnpj())."\n";
              $strConteudo .= 'Sigla - '.$objInstalacaoFederacaoDTO->getStrSigla()."\n";
              $strConteudo .= 'Descrição - '.$objInstalacaoFederacaoDTO->getStrDescricao()."\n";
              $strConteudo .= 'Endereço - '.$objInstalacaoFederacaoDTO->getStrEndereco()."\n";

              $objEmailDTO->setStrMensagem($strConteudo);

              MailSEI::getInstance()->adicionar($objEmailDTO);

              $bolEmail = true;
            }

          }else if ($objInstalacaoFederacaoDTO->getStrStaTipo() == self::$TI_ENVIADA) {
            if ($objInstalacaoFederacaoDTO->getStrStaEstado() == self::$EI_LIBERADA) {

              $objEmailDTO = new EmailDTO();
              $objEmailDTO->setStrDe($strEmailSistema);
              $objEmailDTO->setStrPara($strEmailAdministrador);
              $objEmailDTO->setStrAssunto('SEI Federação - Instalação liberada por '.$objInstalacaoFederacaoDTO->getStrSigla());

              $strConteudo = '';
              $strConteudo .= "\n".'Instalação '.$this->obterSiglaInstalacaoLocal().' foi liberada por:'."\n\n";
              $strConteudo .= 'CNPJ - '.InfraUtil::formatarCnpj($objInstalacaoFederacaoDTO->getDblCnpj())."\n";
              $strConteudo .= 'Sigla - '.$objInstalacaoFederacaoDTO->getStrSigla()."\n";
              $strConteudo .= 'Descrição - '.$objInstalacaoFederacaoDTO->getStrDescricao()."\n";
              $strConteudo .= 'Endereço - '.$objInstalacaoFederacaoDTO->getStrEndereco()."\n";

              $objEmailDTO->setStrMensagem($strConteudo);

              MailSEI::getInstance()->adicionar($objEmailDTO);

              $bolEmail = true;
            }

          }else if ($objInstalacaoFederacaoDTO->getStrStaTipo() == self::$TI_REPLICADA) {
            if ($objInstalacaoFederacaoDTO->getStrStaEstado() == self::$EI_ANALISE) {
              $objEmailDTO = new EmailDTO();
              $objEmailDTO->setStrDe($strEmailSistema);
              $objEmailDTO->setStrPara($strEmailAdministrador);
              $objEmailDTO->setStrAssunto('SEI Federação - Replicação recebida para '.$objInstalacaoFederacaoDTO->getStrSigla());

              $strConteudo = '';
              $strConteudo .= "\n".'Replicação de registro recebida no SEI Federação para:'."\n\n";
              $strConteudo .= 'CNPJ - '.InfraUtil::formatarCnpj($objInstalacaoFederacaoDTO->getDblCnpj())."\n";
              $strConteudo .= 'Sigla - '.$objInstalacaoFederacaoDTO->getStrSigla()."\n";
              $strConteudo .= 'Descrição - '.$objInstalacaoFederacaoDTO->getStrDescricao()."\n";
              $strConteudo .= 'Endereço - '.$objInstalacaoFederacaoDTO->getStrEndereco()."\n";

              $objEmailDTO->setStrMensagem($strConteudo);

              MailSEI::getInstance()->adicionar($objEmailDTO);

              $bolEmail = true;
            }

          }


          $objInstalacaoFederacaoDTO2 = new InstalacaoFederacaoDTO();
          $objInstalacaoFederacaoDTO2->setStrStaAgendamento($bolEmail ? self::$AI_EMAIL_ENVIADO : self::$AI_IGNORADO);
          $objInstalacaoFederacaoDTO2->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTO->getStrIdInstalacaoFederacao());
          $this->alterar($objInstalacaoFederacaoDTO2);
        }

        MailSEI::getInstance()->enviar();
      }

    }catch(Exception $e){
      throw new InfraException('Erro processando agendamento do SEI Federação.', $e);
    }
  }
}
