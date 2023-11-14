<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ReplicacaoFederacaoRN extends InfraRN {

  public static $TRF_ACESSOS = 1;
  public static $TRF_SINALIZACAO_ATENCAO = 2;
  public static $TRF_SINALIZACAO_PUBLICACAO = 3;
  public static $TRF_SINALIZACAO_ENVIO = 4;
  public static $TRF_SINALIZACAO_CANCELAMENTO = 5;

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function listarValoresTipo(){
    try {

      $arrObjTipoReplicacaoFederacaoDTO = array();

      $objTipoReplicacaoFederacaoDTO = new TipoReplicacaoFederacaoDTO();
      $objTipoReplicacaoFederacaoDTO->setNumStaTipo(self::$TRF_ACESSOS);
      $objTipoReplicacaoFederacaoDTO->setStrDescricao('Acessos');
      $arrObjTipoReplicacaoFederacaoDTO[] = $objTipoReplicacaoFederacaoDTO;

      $objTipoReplicacaoFederacaoDTO = new TipoReplicacaoFederacaoDTO();
      $objTipoReplicacaoFederacaoDTO->setNumStaTipo(self::$TRF_SINALIZACAO_ATENCAO);
      $objTipoReplicacaoFederacaoDTO->setStrDescricao('Atenção');
      $arrObjTipoReplicacaoFederacaoDTO[] = $objTipoReplicacaoFederacaoDTO;

      $objTipoReplicacaoFederacaoDTO = new TipoReplicacaoFederacaoDTO();
      $objTipoReplicacaoFederacaoDTO->setNumStaTipo(self::$TRF_SINALIZACAO_PUBLICACAO);
      $objTipoReplicacaoFederacaoDTO->setStrDescricao('Publicação');
      $arrObjTipoReplicacaoFederacaoDTO[] = $objTipoReplicacaoFederacaoDTO;

      $objTipoReplicacaoFederacaoDTO = new TipoReplicacaoFederacaoDTO();
      $objTipoReplicacaoFederacaoDTO->setNumStaTipo(self::$TRF_SINALIZACAO_ENVIO);
      $objTipoReplicacaoFederacaoDTO->setStrDescricao('Novo Envio');
      $arrObjTipoReplicacaoFederacaoDTO[] = $objTipoReplicacaoFederacaoDTO;

      $objTipoReplicacaoFederacaoDTO = new TipoReplicacaoFederacaoDTO();
      $objTipoReplicacaoFederacaoDTO->setNumStaTipo(self::$TRF_SINALIZACAO_CANCELAMENTO);
      $objTipoReplicacaoFederacaoDTO->setStrDescricao('Cancelamento de Envio');
      $arrObjTipoReplicacaoFederacaoDTO[] = $objTipoReplicacaoFederacaoDTO;

      return $arrObjTipoReplicacaoFederacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Tipo.',$e);
    }
  }

  private function validarStrIdInstalacaoFederacao(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReplicacaoFederacaoDTO->getStrIdInstalacaoFederacao())){
      $objInfraException->adicionarValidacao('Instalação não informada.');
    }else{
      $objReplicacaoFederacaoDTO->setStrIdInstalacaoFederacao(trim($objReplicacaoFederacaoDTO->getStrIdInstalacaoFederacao()));

      if (strlen($objReplicacaoFederacaoDTO->getStrIdInstalacaoFederacao())>26){
        $objInfraException->adicionarValidacao('Instalação possui tamanho superior a 26 caracteres.');
      }
    }
  }

  private function validarStrIdProtocoloFederacao(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReplicacaoFederacaoDTO->getStrIdProtocoloFederacao())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }else{
      $objReplicacaoFederacaoDTO->setStrIdProtocoloFederacao(trim($objReplicacaoFederacaoDTO->getStrIdProtocoloFederacao()));

      if (strlen($objReplicacaoFederacaoDTO->getStrIdProtocoloFederacao())>26){
        $objInfraException->adicionarValidacao('Protocolo possui tamanho superior a 26 caracteres.');
      }
    }
  }

  private function validarNumStaTipo(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReplicacaoFederacaoDTO->getNumStaTipo())){
      $objInfraException->adicionarValidacao('Tipo não informado.');
    }else{
      if (!in_array($objReplicacaoFederacaoDTO->getNumStaTipo(),InfraArray::converterArrInfraDTO($this->listarValoresTipo(),'StaTipo'))){
        $objInfraException->adicionarValidacao('Tipo inválido.');
      }
    }
  }

  private function validarDthCadastro(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReplicacaoFederacaoDTO->getDthCadastro())){
      $objInfraException->adicionarValidacao('Data/Hora de Cadastro não informada.');
    }else{
      if (!InfraData::validarDataHora($objReplicacaoFederacaoDTO->getDthCadastro())){
        $objInfraException->adicionarValidacao('Data/Hora de Cadastro inválida.');
      }
    }
  }

  private function validarDthReplicacao(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReplicacaoFederacaoDTO->getDthReplicacao())){
      $objReplicacaoFederacaoDTO->setDthReplicacao(null);
    }else{
      if (!InfraData::validarDataHora($objReplicacaoFederacaoDTO->getDthReplicacao())){
        $objInfraException->adicionarValidacao('Data/Hora de Replicação inválida.');
      }
    }
  }

  private function validarNumTentativa(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReplicacaoFederacaoDTO->getNumTentativa())){
      $objInfraException->adicionarValidacao('Tentativas não informada.');
    }
  }

  private function validarStrErro(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReplicacaoFederacaoDTO->getStrErro())){
      $objReplicacaoFederacaoDTO->setStrErro(null);
    }else{
      $objReplicacaoFederacaoDTO->setStrErro(trim($objReplicacaoFederacaoDTO->getStrErro()));

      if (strlen($objReplicacaoFederacaoDTO->getStrErro())>4000){
        $objInfraException->adicionarValidacao('Erro possui tamanho superior a 4000 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReplicacaoFederacaoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objReplicacaoFederacaoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_cadastrar', __METHOD__, $objReplicacaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrIdInstalacaoFederacao($objReplicacaoFederacaoDTO, $objInfraException);
      $this->validarStrIdProtocoloFederacao($objReplicacaoFederacaoDTO, $objInfraException);
      $this->validarNumStaTipo($objReplicacaoFederacaoDTO, $objInfraException);
      $this->validarDthCadastro($objReplicacaoFederacaoDTO, $objInfraException);
      $this->validarDthReplicacao($objReplicacaoFederacaoDTO, $objInfraException);
      $this->validarNumTentativa($objReplicacaoFederacaoDTO, $objInfraException);
      $this->validarStrErro($objReplicacaoFederacaoDTO, $objInfraException);
      $this->validarStrSinAtivo($objReplicacaoFederacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objReplicacaoFederacaoBD = new ReplicacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objReplicacaoFederacaoBD->cadastrar($objReplicacaoFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Replicação do SEI Federação.',$e);
    }
  }

  protected function alterarControlado(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_alterar', __METHOD__, $objReplicacaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objReplicacaoFederacaoDTO->isSetStrIdInstalacaoFederacao()){
        $this->validarStrIdInstalacaoFederacao($objReplicacaoFederacaoDTO, $objInfraException);
      }
      if ($objReplicacaoFederacaoDTO->isSetStrIdProtocoloFederacao()){
        $this->validarStrIdProtocoloFederacao($objReplicacaoFederacaoDTO, $objInfraException);
      }
      if ($objReplicacaoFederacaoDTO->isSetNumStaTipo()){
        $this->validarNumStaTipo($objReplicacaoFederacaoDTO, $objInfraException);
      }
      if ($objReplicacaoFederacaoDTO->isSetDthCadastro()){
        $this->validarDthCadastro($objReplicacaoFederacaoDTO, $objInfraException);
      }
      if ($objReplicacaoFederacaoDTO->isSetDthReplicacao()){
        $this->validarDthReplicacao($objReplicacaoFederacaoDTO, $objInfraException);
      }
      if ($objReplicacaoFederacaoDTO->isSetNumTentativa()){
        $this->validarNumTentativa($objReplicacaoFederacaoDTO, $objInfraException);
      }
      if ($objReplicacaoFederacaoDTO->isSetStrErro()){
        $this->validarStrErro($objReplicacaoFederacaoDTO, $objInfraException);
      }
      if($objReplicacaoFederacaoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objReplicacaoFederacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objReplicacaoFederacaoBD = new ReplicacaoFederacaoBD($this->getObjInfraIBanco());
      $objReplicacaoFederacaoBD->alterar($objReplicacaoFederacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Replicação do SEI Federação.',$e);
    }
  }

  protected function excluirControlado($arrObjReplicacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_excluir', __METHOD__, $arrObjReplicacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReplicacaoFederacaoBD = new ReplicacaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjReplicacaoFederacaoDTO);$i++){
        $objReplicacaoFederacaoBD->excluir($arrObjReplicacaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Replicação do SEI Federação.',$e);
    }
  }

  protected function consultarConectado(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_consultar', __METHOD__, $objReplicacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReplicacaoFederacaoBD = new ReplicacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objReplicacaoFederacaoBD->consultar($objReplicacaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Replicação do SEI Federação.',$e);
    }
  }

  protected function listarConectado(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_listar', __METHOD__, $objReplicacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReplicacaoFederacaoBD = new ReplicacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objReplicacaoFederacaoBD->listar($objReplicacaoFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Replicações do SEI Federação.',$e);
    }
  }

  protected function contarConectado(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_listar', __METHOD__, $objReplicacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReplicacaoFederacaoBD = new ReplicacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objReplicacaoFederacaoBD->contar($objReplicacaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Replicações do SEI Federação.',$e);
    }
  }

  protected function desativarControlado($arrObjReplicacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_desativar', __METHOD__, $arrObjReplicacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReplicacaoFederacaoBD = new ReplicacaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjReplicacaoFederacaoDTO);$i++){
        $objReplicacaoFederacaoBD->desativar($arrObjReplicacaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Replicação do SEI Federação.',$e);
    }
  }

  protected function reativarControlado($arrObjReplicacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_reativar', __METHOD__, $arrObjReplicacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReplicacaoFederacaoBD = new ReplicacaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjReplicacaoFederacaoDTO);$i++){
        $objReplicacaoFederacaoBD->reativar($arrObjReplicacaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Replicação do SEI Federação.',$e);
    }
  }

  /*
  protected function bloquearControlado(ReplicacaoFederacaoDTO $objReplicacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_consultar', __METHOD__, $objReplicacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReplicacaoFederacaoBD = new ReplicacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objReplicacaoFederacaoBD->bloquear($objReplicacaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Replicação do SEI Federação.',$e);
    }
  }

 */

  protected function agendarControlado(ReplicacaoFederacaoDTO $parObjReplicacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_agendar', __METHOD__, $parObjReplicacaoFederacaoDTO);

      $strDataHoraUTC = gmdate("d/m/Y H:i:s");

      if ($parObjReplicacaoFederacaoDTO->getNumStaTipo() == self::$TRF_ACESSOS){

        $objReplicacaoFederacaoDTO = new ReplicacaoFederacaoDTO();
        $objReplicacaoFederacaoDTO->setStrIdReplicacaoFederacao(InfraULID::gerar());
        $objReplicacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjReplicacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $objReplicacaoFederacaoDTO->setStrIdProtocoloFederacao($parObjReplicacaoFederacaoDTO->getStrIdProtocoloFederacao());
        $objReplicacaoFederacaoDTO->setNumStaTipo($parObjReplicacaoFederacaoDTO->getNumStaTipo());
        $objReplicacaoFederacaoDTO->setDthCadastro($strDataHoraUTC);
        $objReplicacaoFederacaoDTO->setDthReplicacao(null);
        $objReplicacaoFederacaoDTO->setNumTentativa(0);
        $objReplicacaoFederacaoDTO->setStrErro(null);
        $objReplicacaoFederacaoDTO->setStrSinAtivo('S');
        $this->cadastrar($objReplicacaoFederacaoDTO);

      }else if ($parObjReplicacaoFederacaoDTO->getNumStaTipo() == self::$TRF_SINALIZACAO_ATENCAO ||
                $parObjReplicacaoFederacaoDTO->getNumStaTipo() == self::$TRF_SINALIZACAO_PUBLICACAO ||
                $parObjReplicacaoFederacaoDTO->getNumStaTipo() == self::$TRF_SINALIZACAO_ENVIO ||
                $parObjReplicacaoFederacaoDTO->getNumStaTipo() == self::$TRF_SINALIZACAO_CANCELAMENTO) {

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retStrIdProtocoloFederacao();
        $objProtocoloDTO->setDblIdProtocolo($parObjReplicacaoFederacaoDTO->getDblIdProtocolo());

        $objProtocoloRN = new ProtocoloRN();
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

        if ($objProtocoloDTO->getStrIdProtocoloFederacao() != null) {

          $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
          $objAcessoFederacaoDTO->retStrIdInstalacaoFederacaoRem();
          $objAcessoFederacaoDTO->retStrIdInstalacaoFederacaoDest();
          $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($objProtocoloDTO->getStrIdProtocoloFederacao());

          $objAcessoFederacaoRN = new AcessoFederacaoRN();
          $arrObjAcessoFederacaoDTO = $objAcessoFederacaoRN->listar($objAcessoFederacaoDTO);

          if (count($arrObjAcessoFederacaoDTO)) {

            $arrIdInstalacoes = array();
            foreach ($arrObjAcessoFederacaoDTO as $objAcessoFederacaoDTO) {
              $arrIdInstalacoes[$objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoRem()] = true;
              $arrIdInstalacoes[$objAcessoFederacaoDTO->getStrIdInstalacaoFederacaoDest()] = true;
            }

            $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
            unset($arrIdInstalacoes[$objInstalacaoFederacaoRN->obterIdInstalacaoFederacaoLocal()]);

            foreach (array_keys($arrIdInstalacoes) as $strIdInstalacaoFederacao) {
              $objReplicacaoFederacaoDTO = new ReplicacaoFederacaoDTO();
              $objReplicacaoFederacaoDTO->setStrIdReplicacaoFederacao(InfraULID::gerar());
              $objReplicacaoFederacaoDTO->setStrIdInstalacaoFederacao($strIdInstalacaoFederacao);
              $objReplicacaoFederacaoDTO->setStrIdProtocoloFederacao($objProtocoloDTO->getStrIdProtocoloFederacao());
              $objReplicacaoFederacaoDTO->setNumStaTipo($parObjReplicacaoFederacaoDTO->getNumStaTipo());
              $objReplicacaoFederacaoDTO->setDthCadastro($strDataHoraUTC);
              $objReplicacaoFederacaoDTO->setDthReplicacao(null);
              $objReplicacaoFederacaoDTO->setNumTentativa(0);
              $objReplicacaoFederacaoDTO->setStrErro(null);
              $objReplicacaoFederacaoDTO->setStrSinAtivo('S');
              $this->cadastrar($objReplicacaoFederacaoDTO);
            }
          }
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro agendando Replicação do SEI Federação.',$e);
    }
  }

  protected function replicarConectado(){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('replicacao_federacao_replicar', __METHOD__);

      $objReplicacaoFederacaoBD = new ReplicacaoFederacaoBD($this->getObjInfraIBanco());
      $objReplicacaoFederacaoBD->removerExpirados();

      while(true) {

        $objReplicacaoFederacaoDTO = new ReplicacaoFederacaoDTO();
        $objReplicacaoFederacaoDTO->setNumMaxRegistrosRetorno(1000);
        $objReplicacaoFederacaoDTO->retStrIdReplicacaoFederacao();
        $objReplicacaoFederacaoDTO->retStrIdInstalacaoFederacao();
        $objReplicacaoFederacaoDTO->retStrIdProtocoloFederacao();
        $objReplicacaoFederacaoDTO->retNumStaTipo();
        $objReplicacaoFederacaoDTO->retDthCadastro();
        $objReplicacaoFederacaoDTO->retDthReplicacao();
        $objReplicacaoFederacaoDTO->retNumTentativa();
        $objReplicacaoFederacaoDTO->setOrdDthCadastro(InfraDTO::$TIPO_ORDENACAO_DESC);

        $arrObjReplicacaoFederacaoDTOPagina = $this->listar($objReplicacaoFederacaoDTO);
        $arrObjReplicacaoFederacaoDTOPorInstalacao = InfraArray::indexarArrInfraDTO($arrObjReplicacaoFederacaoDTOPagina, 'IdInstalacaoFederacao', true);

        $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();

        foreach ($arrObjReplicacaoFederacaoDTOPorInstalacao as $strIdInstalacaoFederacao => $arrObjReplicacaoFederacaoDTO) {

          $arrParaReplicar = array();
          $arrParaExcluir = array();
          foreach ($arrObjReplicacaoFederacaoDTO as $objReplicacaoFederacaoDTO) {
            $strChave = $objReplicacaoFederacaoDTO->getStrIdProtocoloFederacao().'-'.$objReplicacaoFederacaoDTO->getNumStaTipo();
            if (!isset($arrParaReplicar[$strChave])) {
              $arrParaReplicar[$strChave] = $objReplicacaoFederacaoDTO;
            } else {
              $arrParaExcluir[] = $objReplicacaoFederacaoDTO;
            }
          }

          //exclui duplicados deixando apenas os mais recentes
          if (count($arrParaExcluir)) {
            $this->excluir($arrParaExcluir);
          }

          if (count($arrParaReplicar)) {


            $objAcessoFederacaoRN = new AcessoFederacaoRN();

            foreach ($arrParaReplicar as $objReplicacaoFederacaoDTO) {
              if ($objReplicacaoFederacaoDTO->getNumStaTipo() == self::$TRF_ACESSOS) {
                try {

                  $objAcessoFederacaoDTO = new AcessoFederacaoDTO();
                  $objAcessoFederacaoDTO->setStrIdInstalacaoFederacaoDest($strIdInstalacaoFederacao);
                  $objAcessoFederacaoDTO->setStrIdProcedimentoFederacao($objReplicacaoFederacaoDTO->getStrIdProtocoloFederacao());
                  $objAcessoFederacaoRN->replicarAcessos($objAcessoFederacaoDTO);

                  //////////////////////////////////////////////////////////////////////////////////////
                  $this->excluir(array($objReplicacaoFederacaoDTO));
                  /*
                  $objReplicacaoFederacaoDTO->setDthReplicacao(InfraData::getStrDataHoraAtual());
                  $objReplicacaoFederacaoDTO->setNumTentativa($objReplicacaoFederacaoDTO->getNumTentativa() + 1);
                  $objReplicacaoFederacaoDTO->setStrErro('EXCLUIR');
                  $this->alterar($objReplicacaoFederacaoDTO);
                  */
                  //////////////////////////////////////////////////////////////////////////////////////

                } catch (Exception $e) {
                  $objReplicacaoFederacaoDTO->setDthReplicacao(InfraData::getStrDataHoraAtual());
                  $objReplicacaoFederacaoDTO->setStrErro(substr($e->__toString(), 0, 4000));
                  $objReplicacaoFederacaoDTO->setNumTentativa($objReplicacaoFederacaoDTO->getNumTentativa() + 1);
                  $this->alterar($objReplicacaoFederacaoDTO);
                }
              }
            }


            $arrObjSinalizacoes = array();
            $arrParaProcessar = array();

            foreach ($arrParaReplicar as $objReplicacaoFederacaoDTO) {
              if ($objReplicacaoFederacaoDTO->getNumStaTipo() == self::$TRF_SINALIZACAO_ATENCAO ||
                  $objReplicacaoFederacaoDTO->getNumStaTipo() == self::$TRF_SINALIZACAO_PUBLICACAO ||
                  $objReplicacaoFederacaoDTO->getNumStaTipo() == self::$TRF_SINALIZACAO_ENVIO ||
                  $objReplicacaoFederacaoDTO->getNumStaTipo() == self::$TRF_SINALIZACAO_CANCELAMENTO) {

                if (!isset($arrObjSinalizacoes[$objReplicacaoFederacaoDTO->getStrIdProtocoloFederacao()])) {

                  $objSinalizacaoFederacao = new stdClass();
                  $objSinalizacaoFederacao->IdProtocoloFederacao = $objReplicacaoFederacaoDTO->getStrIdProtocoloFederacao();
                  $objSinalizacaoFederacao->DthSinalizacao = $objReplicacaoFederacaoDTO->getDthCadastro();

                  switch ($objReplicacaoFederacaoDTO->getNumStaTipo()) {
                    case self::$TRF_SINALIZACAO_ATENCAO:
                      $objSinalizacaoFederacao->StaSinalizacao = SinalizacaoFederacaoRN::$TSF_ATENCAO;
                      break;

                    case self::$TRF_SINALIZACAO_PUBLICACAO:
                      $objSinalizacaoFederacao->StaSinalizacao = SinalizacaoFederacaoRN::$TSF_PUBLICACAO;
                      break;

                    case self::$TRF_SINALIZACAO_ENVIO:
                      $objSinalizacaoFederacao->StaSinalizacao = SinalizacaoFederacaoRN::$TSF_ENVIO;
                      break;

                    case self::$TRF_SINALIZACAO_CANCELAMENTO:
                      $objSinalizacaoFederacao->StaSinalizacao = SinalizacaoFederacaoRN::$TSF_CANCELAMENTO;
                      break;

                  }

                  $arrObjSinalizacoes[$objReplicacaoFederacaoDTO->getStrIdProtocoloFederacao()] = $objSinalizacaoFederacao;

                } else {

                  $objSinalizacaoFederacao = $arrObjSinalizacoes[$objReplicacaoFederacaoDTO->getStrIdProtocoloFederacao()];

                  switch ($objReplicacaoFederacaoDTO->getNumStaTipo()) {
                    case self::$TRF_SINALIZACAO_ATENCAO:
                      $objSinalizacaoFederacao->StaSinalizacao = $objSinalizacaoFederacao->StaSinalizacao | SinalizacaoFederacaoRN::$TSF_ATENCAO;
                      break;

                    case self::$TRF_SINALIZACAO_PUBLICACAO:
                      $objSinalizacaoFederacao->StaSinalizacao = $objSinalizacaoFederacao->StaSinalizacao | SinalizacaoFederacaoRN::$TSF_PUBLICACAO;
                      break;

                    case self::$TRF_SINALIZACAO_ENVIO:
                      $objSinalizacaoFederacao->StaSinalizacao = $objSinalizacaoFederacao->StaSinalizacao | SinalizacaoFederacaoRN::$TSF_ENVIO;
                      break;

                    case self::$TRF_SINALIZACAO_CANCELAMENTO:
                      $objSinalizacaoFederacao->StaSinalizacao = $objSinalizacaoFederacao->StaSinalizacao | SinalizacaoFederacaoRN::$TSF_CANCELAMENTO;
                      break;
                  }

                  $arrObjSinalizacoes[$objReplicacaoFederacaoDTO->getStrIdProtocoloFederacao()] = $objSinalizacaoFederacao;
                }

                $arrParaProcessar[] = $objReplicacaoFederacaoDTO;
              }
            }

            if ($arrObjSinalizacoes) {

              try {

                $objInstalacaoFederacaoRN->executar('replicarSinalizacoes', $strIdInstalacaoFederacao, array_values($arrObjSinalizacoes));

                //////////////////////////////////////////////////////////////////////////////////////
                $this->excluir($arrParaProcessar);
                /*
                foreach($arrParaProcessar as $objReplicacaoFederacaoDTO){
                  $objReplicacaoFederacaoDTO->setDthReplicacao(InfraData::getStrDataHoraAtual());
                  $objReplicacaoFederacaoDTO->setNumTentativa($objReplicacaoFederacaoDTO->getNumTentativa() + 1);
                  $objReplicacaoFederacaoDTO->setStrErro('EXCLUIR');
                  $this->alterar($objReplicacaoFederacaoDTO);
                }
                */
                //////////////////////////////////////////////////////////////////////////////////////

              } catch (Exception $e) {
                foreach ($arrParaProcessar as $objReplicacaoFederacaoDTO) {
                  $objReplicacaoFederacaoDTO->setDthReplicacao(InfraData::getStrDataHoraAtual());
                  $objReplicacaoFederacaoDTO->setStrErro(substr($e->__toString(), 0, 4000));
                  $objReplicacaoFederacaoDTO->setNumTentativa($objReplicacaoFederacaoDTO->getNumTentativa() + 1);
                  $this->alterar($objReplicacaoFederacaoDTO);
                }
              }
            }
          }
        }

        if (count($arrObjReplicacaoFederacaoDTOPagina) < 1000){
          break;
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro replicando sinalizações para o SEI Federação.',$e);
    }
  }
}
