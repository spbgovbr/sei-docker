<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/04/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AndamentoInstalacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrIdInstalacaoFederacao(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAndamentoInstalacaoDTO->getStrIdInstalacaoFederacao())){
      $objInfraException->adicionarValidacao('Instalação não informada.');
    }
  }

  private function validarStrStaEstado(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAndamentoInstalacaoDTO->getStrStaEstado())){
      $objInfraException->adicionarValidacao('Estado não informado.');
    }else{
      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      if (!in_array($objAndamentoInstalacaoDTO->getStrStaEstado(),InfraArray::converterArrInfraDTO($objInstalacaoFederacaoRN->listarValoresEstado(),'StaEstado'))){
        $objInfraException->adicionarValidacao('Estado inválido.');
      }
    }
  }
  
  private function validarNumIdUnidade(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAndamentoInstalacaoDTO->getNumIdUnidade())){
      $objAndamentoInstalacaoDTO->setNumIdUnidade(null);
    }
  }

  private function validarNumIdUsuario(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAndamentoInstalacaoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarDthEstado(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAndamentoInstalacaoDTO->getDthEstado())){
      $objInfraException->adicionarValidacao('Data/Hora não informada.');
    }else{
      if (!InfraData::validarDataHora($objAndamentoInstalacaoDTO->getDthEstado())){
        $objInfraException->adicionarValidacao('Data/Hora inválida.');
      }
    }
  }

  protected function lancarControlado(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_instalacao_cadastrar', __METHOD__, $objAndamentoInstalacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $this->validarStrIdInstalacaoFederacao($objAndamentoInstalacaoDTO, $objInfraException);
      $this->validarStrStaEstado($objAndamentoInstalacaoDTO, $objInfraException);
      $objInfraException->lancarValidacoes();

      $objAndamentoInstalacaoDTO->setNumIdAndamentoInstalacao(null);
      $objAndamentoInstalacaoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objAndamentoInstalacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAndamentoInstalacaoDTO->setDthEstado(InfraData::getStrDataHoraAtual());

      $objAndamentoInstalacaoBD = new AndamentoInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoInstalacaoBD->cadastrar($objAndamentoInstalacaoDTO);

      if ($objAndamentoInstalacaoDTO->isSetArrObjAtributoInstalacaoDTO()){
        $objAtributoInstalacaoRN = new AtributoInstalacaoRN();
        foreach($objAndamentoInstalacaoDTO->getArrObjAtributoInstalacaoDTO() as $objAtributoInstalacaoDTO){
          $objAtributoInstalacaoDTO->setNumIdAndamentoInstalacao($ret->getNumIdAndamentoInstalacao());
          $objAtributoInstalacaoRN->cadastrar($objAtributoInstalacaoDTO);
        }
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Andamento da Instalação do SEI Federação.',$e);
    }
  }

  protected function alterarControlado(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_instalacao_alterar', __METHOD__, $objAndamentoInstalacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAndamentoInstalacaoDTO->isSetStrIdInstalacaoFederacao()){
        $this->validarStrIdInstalacaoFederacao($objAndamentoInstalacaoDTO, $objInfraException);
      }
      if ($objAndamentoInstalacaoDTO->isSetStrStaEstado()){
        $this->validarStrStaEstado($objAndamentoInstalacaoDTO, $objInfraException);
      }
      if ($objAndamentoInstalacaoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objAndamentoInstalacaoDTO, $objInfraException);
      }
      if ($objAndamentoInstalacaoDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objAndamentoInstalacaoDTO, $objInfraException);
      }
      if ($objAndamentoInstalacaoDTO->isSetDthEstado()){
        $this->validarDthEstado($objAndamentoInstalacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAndamentoInstalacaoBD = new AndamentoInstalacaoBD($this->getObjInfraIBanco());
      $objAndamentoInstalacaoBD->alterar($objAndamentoInstalacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Andamento da Instalação do SEI Federação.',$e);
    }
  }

  protected function excluirControlado($arrObjAndamentoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_instalacao_excluir', __METHOD__, $arrObjAndamentoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoInstalacaoBD = new AndamentoInstalacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAndamentoInstalacaoDTO);$i++){
        $objAndamentoInstalacaoBD->excluir($arrObjAndamentoInstalacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Andamento da Instalação do SEI Federação.',$e);
    }
  }

  protected function consultarConectado(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_instalacao_consultar', __METHOD__, $objAndamentoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      if ($objAndamentoInstalacaoDTO->isRetStrDescricaoEstado()) {
        $objAndamentoInstalacaoDTO->retStrStaEstado();
      }

      $objAndamentoInstalacaoBD = new AndamentoInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoInstalacaoBD->consultar($objAndamentoInstalacaoDTO);

      if ($ret != null) {
        if ($objAndamentoInstalacaoDTO->isRetStrDescricaoEstado()) {
          $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
          $arrObjEstadoInstalacaoDTO = InfraArray::indexarArrInfraDTO($objInstalacaoFederacaoRN->listarValoresEstado(), 'StaEstado');
          $ret->setStrDescricaoEstado($arrObjEstadoInstalacaoDTO[$ret->getStrStaEstado()]->getStrDescricao());
        }
      }

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Andamento da Instalação do SEI Federação.',$e);
    }
  }

  protected function listarConectado(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_instalacao_listar', __METHOD__, $objAndamentoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      if ($objAndamentoInstalacaoDTO->isRetStrDescricaoEstado()) {
        $objAndamentoInstalacaoDTO->retStrStaEstado();
      }

      $objAndamentoInstalacaoBD = new AndamentoInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoInstalacaoBD->listar($objAndamentoInstalacaoDTO);

      if (count($ret)) {
        if ($objAndamentoInstalacaoDTO->isRetStrDescricaoEstado()) {
          $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
          $arrObjEstadoInstalacaoDTO = InfraArray::indexarArrInfraDTO($objInstalacaoFederacaoRN->listarValoresEstado(), 'StaEstado');
          foreach($ret as $dto) {
            $dto->setStrDescricaoEstado($arrObjEstadoInstalacaoDTO[$dto->getStrStaEstado()]->getStrDescricao());
          }
        }
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Andamentos das Instalações do SEI Federação.',$e);
    }
  }

  protected function contarConectado(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_instalacao_listar', __METHOD__, $objAndamentoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoInstalacaoBD = new AndamentoInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoInstalacaoBD->contar($objAndamentoInstalacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Andamentos das Instalações do SEI Federação.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjAndamentoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_instalacao_desativar', __METHOD__, $arrObjAndamentoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoInstalacaoBD = new AndamentoInstalacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAndamentoInstalacaoDTO);$i++){
        $objAndamentoInstalacaoBD->desativar($arrObjAndamentoInstalacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Andamento da Instalação do SEI Federação.',$e);
    }
  }

  protected function reativarControlado($arrObjAndamentoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_instalacao_reativar', __METHOD__, $arrObjAndamentoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoInstalacaoBD = new AndamentoInstalacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAndamentoInstalacaoDTO);$i++){
        $objAndamentoInstalacaoBD->reativar($arrObjAndamentoInstalacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Andamento da Instalação do SEI Federação.',$e);
    }
  }

  protected function bloquearControlado(AndamentoInstalacaoDTO $objAndamentoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_instalacao_consultar', __METHOD__, $objAndamentoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoInstalacaoBD = new AndamentoInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoInstalacaoBD->bloquear($objAndamentoInstalacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Andamento da Instalação do SEI Federação.',$e);
    }
  }

 */
}
