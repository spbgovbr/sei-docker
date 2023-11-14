<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/12/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class SinalizacaoFederacaoRN extends InfraRN {

  public static $TSF_NENHUMA = 0;
  public static $TSF_ATENCAO = 1;
  public static $TSF_PUBLICACAO = 2;
  public static $TSF_ENVIO = 4;
  public static $TSF_CANCELAMENTO = 8;

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrIdInstalacaoFederacao(SinalizacaoFederacaoDTO $objSinalizacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSinalizacaoFederacaoDTO->getStrIdInstalacaoFederacao())){
      $objInfraException->adicionarValidacao('Instalação não informada.');
    }else{
      $objSinalizacaoFederacaoDTO->setStrIdInstalacaoFederacao(trim($objSinalizacaoFederacaoDTO->getStrIdInstalacaoFederacao()));

      if (strlen($objSinalizacaoFederacaoDTO->getStrIdInstalacaoFederacao())>26){
        $objInfraException->adicionarValidacao('Instalação possui tamanho superior a 26 caracteres.');
      }
    }
  }

  private function validarStrIdProtocoloFederacao(SinalizacaoFederacaoDTO $objSinalizacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSinalizacaoFederacaoDTO->getStrIdProtocoloFederacao())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }else{
      $objSinalizacaoFederacaoDTO->setStrIdProtocoloFederacao(trim($objSinalizacaoFederacaoDTO->getStrIdProtocoloFederacao()));

      if (strlen($objSinalizacaoFederacaoDTO->getStrIdProtocoloFederacao())>26){
        $objInfraException->adicionarValidacao('Protocolo possui tamanho superior a 26 caracteres.');
      }
    }
  }

  private function validarDthSinalizacao(SinalizacaoFederacaoDTO $objSinalizacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSinalizacaoFederacaoDTO->getDthSinalizacao())){
      $objInfraException->adicionarValidacao('Data/Hora não informada.');
    }else{
      if (!InfraData::validarDataHora($objSinalizacaoFederacaoDTO->getDthSinalizacao())){
        $objInfraException->adicionarValidacao('Data/Hora inválida.');
      }
    }
  }

  private function validarNumStaSinalizacao(SinalizacaoFederacaoDTO $objSinalizacaoFederacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSinalizacaoFederacaoDTO->getNumStaSinalizacao())){
      $objInfraException->adicionarValidacao('Tipo não informado.');
    }
  }

  protected function cadastrarControlado(SinalizacaoFederacaoDTO $objSinalizacaoFederacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('sinalizacao_federacao_cadastrar', __METHOD__, $objSinalizacaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrIdInstalacaoFederacao($objSinalizacaoFederacaoDTO, $objInfraException);
      $this->validarStrIdProtocoloFederacao($objSinalizacaoFederacaoDTO, $objInfraException);
      $this->validarDthSinalizacao($objSinalizacaoFederacaoDTO, $objInfraException);
      $this->validarNumStaSinalizacao($objSinalizacaoFederacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objSinalizacaoFederacaoBD = new SinalizacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objSinalizacaoFederacaoBD->cadastrar($objSinalizacaoFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Sinalização do SEI Federação.',$e);
    }
  }

  protected function alterarControlado(SinalizacaoFederacaoDTO $objSinalizacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('sinalizacao_federacao_alterar', __METHOD__, $objSinalizacaoFederacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objSinalizacaoFederacaoDTO->isSetStrIdInstalacaoFederacao()){
        $this->validarStrIdInstalacaoFederacao($objSinalizacaoFederacaoDTO, $objInfraException);
      }
      if ($objSinalizacaoFederacaoDTO->isSetStrIdProtocoloFederacao()){
        $this->validarStrIdProtocoloFederacao($objSinalizacaoFederacaoDTO, $objInfraException);
      }
      if ($objSinalizacaoFederacaoDTO->isSetDthSinalizacao()){
        $this->validarDthSinalizacao($objSinalizacaoFederacaoDTO, $objInfraException);
      }
      if ($objSinalizacaoFederacaoDTO->isSetNumStaSinalizacao()){
        $this->validarNumStaSinalizacao($objSinalizacaoFederacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objSinalizacaoFederacaoBD = new SinalizacaoFederacaoBD($this->getObjInfraIBanco());
      $objSinalizacaoFederacaoBD->alterar($objSinalizacaoFederacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Sinalização do SEI Federação.',$e);
    }
  }

  protected function excluirControlado($arrObjSinalizacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('sinalizacao_federacao_excluir', __METHOD__, $arrObjSinalizacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSinalizacaoFederacaoBD = new SinalizacaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSinalizacaoFederacaoDTO);$i++){
        $objSinalizacaoFederacaoBD->excluir($arrObjSinalizacaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Sinalização do SEI Federação.',$e);
    }
  }

  protected function consultarConectado(SinalizacaoFederacaoDTO $objSinalizacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('sinalizacao_federacao_consultar', __METHOD__, $objSinalizacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSinalizacaoFederacaoBD = new SinalizacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objSinalizacaoFederacaoBD->consultar($objSinalizacaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Sinalização do SEI Federação.',$e);
    }
  }

  protected function listarConectado(SinalizacaoFederacaoDTO $objSinalizacaoFederacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('sinalizacao_federacao_listar', __METHOD__, $objSinalizacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSinalizacaoFederacaoBD = new SinalizacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objSinalizacaoFederacaoBD->listar($objSinalizacaoFederacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Sinalizações do SEI Federação.',$e);
    }
  }

  protected function contarConectado(SinalizacaoFederacaoDTO $objSinalizacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('sinalizacao_federacao_listar', __METHOD__, $objSinalizacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSinalizacaoFederacaoBD = new SinalizacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objSinalizacaoFederacaoBD->contar($objSinalizacaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Sinalizações do SEI Federação.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjSinalizacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('sinalizacao_federacao_desativar', __METHOD__, $arrObjSinalizacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSinalizacaoFederacaoBD = new SinalizacaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSinalizacaoFederacaoDTO);$i++){
        $objSinalizacaoFederacaoBD->desativar($arrObjSinalizacaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Sinalização do SEI Federação.',$e);
    }
  }

  protected function reativarControlado($arrObjSinalizacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('sinalizacao_federacao_reativar', __METHOD__, $arrObjSinalizacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSinalizacaoFederacaoBD = new SinalizacaoFederacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSinalizacaoFederacaoDTO);$i++){
        $objSinalizacaoFederacaoBD->reativar($arrObjSinalizacaoFederacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Sinalização do SEI Federação.',$e);
    }
  }
*/

  protected function bloquearControlado(SinalizacaoFederacaoDTO $objSinalizacaoFederacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('sinalizacao_federacao_consultar', __METHOD__, $objSinalizacaoFederacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSinalizacaoFederacaoBD = new SinalizacaoFederacaoBD($this->getObjInfraIBanco());
      $ret = $objSinalizacaoFederacaoBD->bloquear($objSinalizacaoFederacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Sinalização do SEI Federação.',$e);
    }
  }

  protected function processarReplicacaoSinalizacoesConectado(ReplicarSinalizacoesFederacaoDTO $objReplicarSinalizacoesFederacaoDTO){
    try{

      $objInstalacaoFederacaoDTORemetente = $objReplicarSinalizacoesFederacaoDTO->getObjInstalacaoFederacaoDTORemetente();
      $objOrgaoFederacaoDTORemetente = $objReplicarSinalizacoesFederacaoDTO->getObjOrgaoFederacaoDTORemetente();
      $objUnidadeFederacaoDTORemetente = $objReplicarSinalizacoesFederacaoDTO->getObjUnidadeFederacaoDTORemetente();
      $arrObjSinalizacaoFederacaoDTOReplicacao = $objReplicarSinalizacoesFederacaoDTO->getArrObjSinalizacaoFederacaoDTO();

      if (count($arrObjSinalizacaoFederacaoDTOReplicacao)) {

        $arrIdProtocoloFederacao = InfraArray::converterArrInfraDTO($arrObjSinalizacaoFederacaoDTOReplicacao, 'IdProtocoloFederacao');

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retDblIdProtocolo();
        $objProtocoloDTO->retStrIdProtocoloFederacao();
        $objProtocoloDTO->setStrIdProtocoloFederacao($arrIdProtocoloFederacao, InfraDTO::$OPER_IN);

        $objProtocoloRN = new ProtocoloRN();
        $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarRN0668($objProtocoloDTO), 'IdProtocoloFederacao');

        foreach ($arrObjSinalizacaoFederacaoDTOReplicacao as $objSinalizacaoFederacaoDTO) {
          //se o protocolo do sei federacao existe localmente
          if (isset($arrObjProtocoloDTO[$objSinalizacaoFederacaoDTO->getStrIdProtocoloFederacao()])) {
            $objSinalizacaoFederacaoDTO->setStrIdInstalacaoFederacao($objInstalacaoFederacaoDTORemetente->getStrIdInstalacaoFederacao());
            $objSinalizacaoFederacaoDTO->setDblIdProtocolo($arrObjProtocoloDTO[$objSinalizacaoFederacaoDTO->getStrIdProtocoloFederacao()]->getDblIdProtocolo());
            $this->atualizarSinalizacaoProtocolo($objSinalizacaoFederacaoDTO);
          }
        }
      }
    }catch(Exception $e){
      throw new InfraException('Erro processando replicação de sinalizações do SEI Federação.',$e);
    }
  }

  protected function atualizarSinalizacaoProtocoloControlado(SinalizacaoFederacaoDTO $parObjSinalizacaoFederacaoDTO){
    try{

      $numTipoVisualizacao = 0;

      if ($parObjSinalizacaoFederacaoDTO->getNumStaSinalizacao() & self::$TSF_ATENCAO){
        $numTipoVisualizacao = AtividadeRN::$TV_ATENCAO;
      }

      if ($parObjSinalizacaoFederacaoDTO->getNumStaSinalizacao() & self::$TSF_PUBLICACAO){
        $numTipoVisualizacao = $numTipoVisualizacao | AtividadeRN::$TV_PUBLICACAO;
      }

      if ($parObjSinalizacaoFederacaoDTO->getNumStaSinalizacao() & self::$TSF_ENVIO){
        $numTipoVisualizacao = $numTipoVisualizacao | AtividadeRN::$TV_ENVIO_FEDERACAO;
      }

      if ($parObjSinalizacaoFederacaoDTO->getNumStaSinalizacao() & self::$TSF_CANCELAMENTO){
        $numTipoVisualizacao = $numTipoVisualizacao | AtividadeRN::$TV_CANCELAMENTO_FEDERACAO;
      }

      if ($numTipoVisualizacao) {

        $objProtocoloFederacaoDTO = new ProtocoloFederacaoDTO();
        $objProtocoloFederacaoDTO->setStrIdProtocoloFederacao($parObjSinalizacaoFederacaoDTO->getStrIdProtocoloFederacao());

        $objProtocoloFederacaoRN = new ProtocoloFederacaoRN();
        $objProtocoloFederacaoRN->bloquear($objProtocoloFederacaoDTO);

        //recupera sinalizacoes registradas para as unidades
        $objSinalizacaoFederacaoDTO = new SinalizacaoFederacaoDTO();
        $objSinalizacaoFederacaoDTO->retStrIdInstalacaoFederacao();
        $objSinalizacaoFederacaoDTO->retStrIdProtocoloFederacao();
        $objSinalizacaoFederacaoDTO->retNumIdUnidade();
        $objSinalizacaoFederacaoDTO->retNumStaSinalizacao();
        $objSinalizacaoFederacaoDTO->retDthSinalizacao();
        $objSinalizacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjSinalizacaoFederacaoDTO->getStrIdInstalacaoFederacao());
        $objSinalizacaoFederacaoDTO->setStrIdProtocoloFederacao($parObjSinalizacaoFederacaoDTO->getStrIdProtocoloFederacao());
        $arrObjSinalizacaoFederacaoDTO = InfraArray::indexarArrInfraDTO($this->listar($objSinalizacaoFederacaoDTO), 'IdUnidade');

        $objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setBolReplicandoFederacao(true);
        $objAtividadeDTO->setDblIdProtocolo($parObjSinalizacaoFederacaoDTO->getDblIdProtocolo());
        $objAtividadeDTO->setNumTipoVisualizacao($numTipoVisualizacao);

        $arrIdUnidadesNaoAtualizar = array();
        if (count($arrObjSinalizacaoFederacaoDTO)){

          $numSegundosSincronizacao = ConfiguracaoSEI::getInstance()->getValor('Federacao', 'NumSegundosSincronizacao', false, 300);

          if (!is_numeric($numSegundosSincronizacao) || $numSegundosSincronizacao < 0) {
            $numSegundosSincronizacao = 300;
          }

          //verifica unidades que já visualizaram o processo na instalacao após o evento
          foreach($arrObjSinalizacaoFederacaoDTO as $objSinalizacaoFederacaoDTO){
            $dthSinalizacaoRemota = InfraData::calcularData($numSegundosSincronizacao,InfraData::$UNIDADE_SEGUNDOS,InfraData::$SENTIDO_ADIANTE,$parObjSinalizacaoFederacaoDTO->getDthSinalizacao());
            if (InfraData::compararDataHora($dthSinalizacaoRemota, $objSinalizacaoFederacaoDTO->getDthSinalizacao()) > 0){
              $arrIdUnidadesNaoAtualizar[] = $objSinalizacaoFederacaoDTO->getNumIdUnidade();
            }
          }

          if (count($arrIdUnidadesNaoAtualizar)){
            $objAtividadeDTO->setNumIdUnidade($arrIdUnidadesNaoAtualizar);
          }
        }


        $objAtividadeRN = new AtividadeRN();
        $arrIdUnidadesAtualizadas = $objAtividadeRN->atualizarVisualizacao($objAtividadeDTO);

        foreach ($arrIdUnidadesAtualizadas as $numIdUnidade) {

          //se a unidade que foi atualizada ainda nao tem sinalizacao entao cadastra
          if (!isset($arrObjSinalizacaoFederacaoDTO[$numIdUnidade])) {

            $objSinalizacaoFederacaoDTO = new SinalizacaoFederacaoDTO();
            $objSinalizacaoFederacaoDTO->setStrIdInstalacaoFederacao($parObjSinalizacaoFederacaoDTO->getStrIdInstalacaoFederacao());
            $objSinalizacaoFederacaoDTO->setStrIdProtocoloFederacao($parObjSinalizacaoFederacaoDTO->getStrIdProtocoloFederacao());
            $objSinalizacaoFederacaoDTO->setNumIdUnidade($numIdUnidade);
            $objSinalizacaoFederacaoDTO->setDthSinalizacao($parObjSinalizacaoFederacaoDTO->getDthSinalizacao());
            $objSinalizacaoFederacaoDTO->setNumStaSinalizacao($parObjSinalizacaoFederacaoDTO->getNumStaSinalizacao());

            try {
              $this->cadastrar($objSinalizacaoFederacaoDTO);
            } catch (Exception $e) {
              LogSEI::getInstance()->gravar('Erro cadastrando sinalização replicada do SEI Federação:'."\n".InfraException::inspecionar($e));
            }

          //se a unidade já tem sinalização altera
          } else {

            $objSinalizacaoFederacaoDTO = $arrObjSinalizacaoFederacaoDTO[$numIdUnidade];
            $objSinalizacaoFederacaoDTO->setDthSinalizacao($parObjSinalizacaoFederacaoDTO->getDthSinalizacao());
            $objSinalizacaoFederacaoDTO->setNumStaSinalizacao($objSinalizacaoFederacaoDTO->getNumStaSinalizacao() | $parObjSinalizacaoFederacaoDTO->getNumStaSinalizacao());

            try {
              $this->alterar($objSinalizacaoFederacaoDTO);
            } catch (Exception $e) {
              LogSEI::getInstance()->gravar('Erro alterando sinalização replicada do SEI Federação:'."\n".InfraException::inspecionar($e));
            }

            unset($arrObjSinalizacaoFederacaoDTO[$numIdUnidade]);
          }
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro processando replicação de sinalizações do SEI Federação em protocolo.',$e);
    }
  }

  protected function configurarVisualizadaControlado(ProcedimentoDTO $objProcedimentoDTO){
    try{

      if ($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo() != null) {
        $objSinalizacaoFederacaoDTO = new SinalizacaoFederacaoDTO();
        $objSinalizacaoFederacaoDTO->retStrIdInstalacaoFederacao();
        $objSinalizacaoFederacaoDTO->retStrIdProtocoloFederacao();
        $objSinalizacaoFederacaoDTO->retNumIdUnidade();
        $objSinalizacaoFederacaoDTO->retNumStaSinalizacao();
        $objSinalizacaoFederacaoDTO->setStrIdProtocoloFederacao($objProcedimentoDTO->getStrIdProtocoloFederacaoProtocolo());
        $objSinalizacaoFederacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objSinalizacaoFederacaoRN = new SinalizacaoFederacaoRN();
        $arrObjSinalizacaoFederacaoDTO = $objSinalizacaoFederacaoRN->listar($objSinalizacaoFederacaoDTO);
        foreach ($arrObjSinalizacaoFederacaoDTO as $objSinalizacaoFederacaoDTO) {

          $numStaSinalizacaoOriginal = $objSinalizacaoFederacaoDTO->getNumStaSinalizacao();
          $numStaSinalizacaoNovo = $numStaSinalizacaoOriginal & ~SinalizacaoFederacaoRN::$TSF_ENVIO;
          $numStaSinalizacaoNovo = $numStaSinalizacaoNovo & ~SinalizacaoFederacaoRN::$TSF_CANCELAMENTO;

          if ($numStaSinalizacaoOriginal != $numStaSinalizacaoNovo) {
            $objSinalizacaoFederacaoDTO->setDthSinalizacao(gmdate("d/m/Y H:i:s"));
            $objSinalizacaoFederacaoDTO->setNumStaSinalizacao($numStaSinalizacaoNovo);
            $objSinalizacaoFederacaoRN->alterar($objSinalizacaoFederacaoDTO);
          }
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro configurando visualização de sinalizações do SEI Federação.', $e);
    }
  }
}
