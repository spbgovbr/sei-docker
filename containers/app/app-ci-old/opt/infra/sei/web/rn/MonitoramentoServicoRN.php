<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/10/2015 - criado por mga
*
* Versão do Gerador de Código: 1.35.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class MonitoramentoServicoRN extends InfraRN {

  public static $TM_RESUMIDO = 'R';
  public static $TM_DETALHADO = 'D';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdServico(MonitoramentoServicoDTO $objMonitoramentoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMonitoramentoServicoDTO->getNumIdServico())){
      $objInfraException->adicionarValidacao('Serviço não informado.');
    }
  }

  private function validarStrOperacao(MonitoramentoServicoDTO $objMonitoramentoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMonitoramentoServicoDTO->getStrOperacao())){
      $objInfraException->adicionarValidacao('Operação não informada.');
    }else{
      $objMonitoramentoServicoDTO->setStrOperacao(trim($objMonitoramentoServicoDTO->getStrOperacao()));

      if (strlen($objMonitoramentoServicoDTO->getStrOperacao())>100){
        $objInfraException->adicionarValidacao('Operação possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarDblTempoExecucao(MonitoramentoServicoDTO $objMonitoramentoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMonitoramentoServicoDTO->getDblTempoExecucao())){
      $objInfraException->adicionarValidacao('Tempo de Execução não informado.');
    }
  }

  private function validarStrIpAcesso(MonitoramentoServicoDTO $objMonitoramentoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMonitoramentoServicoDTO->getStrIpAcesso())){
      $objMonitoramentoServicoDTO->setStrIpAcesso(null);
    }else{
      $objMonitoramentoServicoDTO->setStrIpAcesso(trim($objMonitoramentoServicoDTO->getStrIpAcesso()));

      if (strlen($objMonitoramentoServicoDTO->getStrIpAcesso())>39){
        $objInfraException->adicionarValidacao('IP de Acesso possui tamanho superior a 39 caracteres.');
      }
    }
  }

  private function validarDthAcesso(MonitoramentoServicoDTO $objMonitoramentoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMonitoramentoServicoDTO->getDthAcesso())){
      $objInfraException->adicionarValidacao('Data/Hora de Acesso não informada.');
    }else{
      if (!InfraData::validarDataHora($objMonitoramentoServicoDTO->getDthAcesso())){
        $objInfraException->adicionarValidacao('Data/Hora de Acesso inválida.');
      }
    }
  }

  private function validarStrServidor(MonitoramentoServicoDTO $objMonitoramentoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMonitoramentoServicoDTO->getStrServidor())){
      $objMonitoramentoServicoDTO->setStrServidor(null);
    }else{
      $objMonitoramentoServicoDTO->setStrServidor(trim($objMonitoramentoServicoDTO->getStrServidor()));

      if (strlen($objMonitoramentoServicoDTO->getStrServidor())>250){
        $objInfraException->adicionarValidacao('Servidor possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrUserAgent(MonitoramentoServicoDTO $objMonitoramentoServicoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objMonitoramentoServicoDTO->getStrUserAgent())){
      $objMonitoramentoServicoDTO->setStrUserAgent(null);
    }else{
      $objMonitoramentoServicoDTO->setStrUserAgent(trim($objMonitoramentoServicoDTO->getStrUserAgent()));

      if (strlen($objMonitoramentoServicoDTO->getStrUserAgent())>250){
        $objInfraException->adicionarValidacao('User Agent possui tamanho superior a 250 caracteres.');
      }
    }
  }

  public function listarTiposMonitoramento(){
    $arr = array();

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(self::$TM_RESUMIDO);
    $objTipoDTO->setStrDescricao('Resumido');
    $arr[] = $objTipoDTO;

    $objTipoDTO = new TipoDTO();
    $objTipoDTO->setStrStaTipo(self::$TM_DETALHADO);
    $objTipoDTO->setStrDescricao('Detalhado');
    $arr[] = $objTipoDTO;

    return $arr;
  }

  protected function cadastrarControlado(MonitoramentoServicoDTO $objMonitoramentoServicoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('monitoramento_servico_cadastrar',__METHOD__,$objMonitoramentoServicoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdServico($objMonitoramentoServicoDTO, $objInfraException);
      $this->validarStrOperacao($objMonitoramentoServicoDTO, $objInfraException);
      $this->validarDblTempoExecucao($objMonitoramentoServicoDTO, $objInfraException);
      $this->validarStrIpAcesso($objMonitoramentoServicoDTO, $objInfraException);
      $this->validarDthAcesso($objMonitoramentoServicoDTO, $objInfraException);
      $this->validarStrServidor($objMonitoramentoServicoDTO, $objInfraException);
      $this->validarStrUserAgent($objMonitoramentoServicoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objMonitoramentoServicoBD = new MonitoramentoServicoBD($this->getObjInfraIBanco());
      $ret = $objMonitoramentoServicoBD->cadastrar($objMonitoramentoServicoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Monitoramento de Serviço.',$e);
    }
  }

  protected function alterarControlado(MonitoramentoServicoDTO $objMonitoramentoServicoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('monitoramento_servico_alterar',__METHOD__,$objMonitoramentoServicoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objMonitoramentoServicoDTO->isSetNumIdServico()){
        $this->validarNumIdServico($objMonitoramentoServicoDTO, $objInfraException);
      }
      if ($objMonitoramentoServicoDTO->isSetStrOperacao()){
        $this->validarStrOperacao($objMonitoramentoServicoDTO, $objInfraException);
      }
      if ($objMonitoramentoServicoDTO->isSetDblTempoExecucao()){
        $this->validarDblTempoExecucao($objMonitoramentoServicoDTO, $objInfraException);
      }
      if ($objMonitoramentoServicoDTO->isSetStrIpAcesso()){
        $this->validarStrIpAcesso($objMonitoramentoServicoDTO, $objInfraException);
      }
      if ($objMonitoramentoServicoDTO->isSetDthAcesso()){
        $this->validarDthAcesso($objMonitoramentoServicoDTO, $objInfraException);
      }
      if ($objMonitoramentoServicoDTO->isSetStrServidor()){
        $this->validarStrServidor($objMonitoramentoServicoDTO, $objInfraException);
      }
      if ($objMonitoramentoServicoDTO->isSetStrUserAgent()){
        $this->validarStrUserAgent($objMonitoramentoServicoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objMonitoramentoServicoBD = new MonitoramentoServicoBD($this->getObjInfraIBanco());
      $objMonitoramentoServicoBD->alterar($objMonitoramentoServicoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Monitoramento de Serviço.',$e);
    }
  }

  protected function excluirControlado($arrObjMonitoramentoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('monitoramento_servico_excluir',__METHOD__,$arrObjMonitoramentoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMonitoramentoServicoBD = new MonitoramentoServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMonitoramentoServicoDTO);$i++){
        $objMonitoramentoServicoBD->excluir($arrObjMonitoramentoServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Monitoramento de Serviço.',$e);
    }
  }

  protected function consultarConectado(MonitoramentoServicoDTO $objMonitoramentoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('monitoramento_servico_consultar',__METHOD__,$objMonitoramentoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMonitoramentoServicoBD = new MonitoramentoServicoBD($this->getObjInfraIBanco());
      $ret = $objMonitoramentoServicoBD->consultar($objMonitoramentoServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Monitoramento de Serviço.',$e);
    }
  }

  protected function listarConectado(MonitoramentoServicoDTO $objMonitoramentoServicoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('monitoramento_servico_listar',__METHOD__,$objMonitoramentoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMonitoramentoServicoBD = new MonitoramentoServicoBD($this->getObjInfraIBanco());
      $ret = $objMonitoramentoServicoBD->listar($objMonitoramentoServicoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Monitoramento de Serviços.',$e);
    }
  }

  protected function contarConectado(MonitoramentoServicoDTO $objMonitoramentoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('monitoramento_servico_listar',__METHOD__,$objMonitoramentoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMonitoramentoServicoBD = new MonitoramentoServicoBD($this->getObjInfraIBanco());
      $ret = $objMonitoramentoServicoBD->contar($objMonitoramentoServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Monitoramento de Serviços.',$e);
    }
  }

  protected function pesquisarConectado(MonitoramentoServicoDTO $objMonitoramentoServicoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('monitoramento_servico_listar',__METHOD__,$objMonitoramentoServicoDTO);

      $objInfraException = new InfraException();

      if ($objMonitoramentoServicoDTO->isSetDthInicial() || $objMonitoramentoServicoDTO->isSetDthFinal()){

        if (!$objMonitoramentoServicoDTO->isSetDthInicial()){
          $objInfraException->lancarValidacao('Data/Hora inicial do período de busca não informada.');
        }else{
          if (strlen($objMonitoramentoServicoDTO->getDthInicial())=='16'){
            $objMonitoramentoServicoDTO->setDthInicial($objMonitoramentoServicoDTO->getDthInicial().':00');
          }
        }

        if (!InfraData::validarDataHora($objMonitoramentoServicoDTO->getDthInicial())){
          $objInfraException->lancarValidacao('Data/Hora inicial do período de busca inválida.');
        }

        if (!$objMonitoramentoServicoDTO->isSetDthFinal()){
          $objMonitoramentoServicoDTO->setDthFinal($objMonitoramentoServicoDTO->getDthInicial());
        }else{

          if (strlen($objMonitoramentoServicoDTO->getDthFinal())=='16'){
            $objMonitoramentoServicoDTO->setDthFinal($objMonitoramentoServicoDTO->getDthFinal().':59');
          }

          if (!InfraData::validarDataHora($objMonitoramentoServicoDTO->getDthFinal())){
            $objInfraException->lancarValidacao('Data/Hora final do período de busca inválida.');
          }
        }

        if (InfraData::compararDatas($objMonitoramentoServicoDTO->getDthInicial(),$objMonitoramentoServicoDTO->getDthFinal())<0){
          $objInfraException->lancarValidacao('Período de datas/horas inválido.');
        }

        if (strlen($objMonitoramentoServicoDTO->getDthInicial())=='10'){
          $objMonitoramentoServicoDTO->setDthInicial($objMonitoramentoServicoDTO->getDthInicial().' 00:00:00');
        }

        if (strlen($objMonitoramentoServicoDTO->getDthFinal())=='10'){
          $objMonitoramentoServicoDTO->setDthFinal($objMonitoramentoServicoDTO->getDthFinal().' 23:59:59');
        }

        $objMonitoramentoServicoDTO->adicionarCriterio(array('Acesso','Acesso'),
            array(InfraDTO::$OPER_MAIOR_IGUAL,InfraDTO::$OPER_MENOR_IGUAL),
            array($objMonitoramentoServicoDTO->getDthInicial(),$objMonitoramentoServicoDTO->getDthFinal()),
            InfraDTO::$OPER_LOGICO_AND);
      }

      if ($objMonitoramentoServicoDTO->getStrStaTipo()==self::$TM_DETALHADO) {
        $ret = $this->listar($objMonitoramentoServicoDTO);
      }else{
        $objMonitoramentoServicoBD = new MonitoramentoServicoBD($this->inicializarObjInfraIBanco());
        $ret = $objMonitoramentoServicoBD->gerarTotaisMedias($objMonitoramentoServicoDTO);
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando monitoramento de serviços.',$e);
    }
  }

/* 
  protected function desativarControlado($arrObjMonitoramentoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('monitoramento_servico_desativar',__METHOD__,$arrObjMonitoramentoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMonitoramentoServicoBD = new MonitoramentoServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMonitoramentoServicoDTO);$i++){
        $objMonitoramentoServicoBD->desativar($arrObjMonitoramentoServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Monitoramento de Serviço.',$e);
    }
  }

  protected function reativarControlado($arrObjMonitoramentoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('monitoramento_servico_reativar',__METHOD__,$arrObjMonitoramentoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMonitoramentoServicoBD = new MonitoramentoServicoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjMonitoramentoServicoDTO);$i++){
        $objMonitoramentoServicoBD->reativar($arrObjMonitoramentoServicoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Monitoramento de Serviço.',$e);
    }
  }

  protected function bloquearControlado(MonitoramentoServicoDTO $objMonitoramentoServicoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('monitoramento_servico_consultar',__METHOD__,$objMonitoramentoServicoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objMonitoramentoServicoBD = new MonitoramentoServicoBD($this->getObjInfraIBanco());
      $ret = $objMonitoramentoServicoBD->bloquear($objMonitoramentoServicoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Monitoramento de Serviço.',$e);
    }
  }

 */
}
?>