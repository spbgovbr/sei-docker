<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/09/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../Sip.php';

class DispositivoAcessoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  private function validarStrIdCodigoAcesso(DispositivoAcessoDTO $objDispositivoAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDispositivoAcessoDTO->getStrIdCodigoAcesso())){
      $objInfraException->adicionarValidacao('Habilitação de Autenticação em 2 Fatores não informada.');
    }
  }

  private function validarDthLiberacao(DispositivoAcessoDTO $objDispositivoAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDispositivoAcessoDTO->getDthLiberacao())){
      $objDispositivoAcessoDTO->setDthLiberacao(null);
    }else{
      if (!InfraData::validarDataHora($objDispositivoAcessoDTO->getDthLiberacao())){
        $objInfraException->adicionarValidacao('Data de Liberação inválida.');
      }
    }
  }

  private function validarDthAcesso(DispositivoAcessoDTO $objDispositivoAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDispositivoAcessoDTO->getDthAcesso())){
      $objInfraException->adicionarValidacao('Data de Acesso não informada.');
    }else{
      if (!InfraData::validarDataHora($objDispositivoAcessoDTO->getDthAcesso())){
        $objInfraException->adicionarValidacao('Data de Acesso inválida.');
      }
    }
  }

  private function validarStrIpAcesso(DispositivoAcessoDTO $objDispositivoAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDispositivoAcessoDTO->getStrIpAcesso())){
      $objInfraException->adicionarValidacao('IP de Acesso não informado.');
    }else{
      $objDispositivoAcessoDTO->setStrIpAcesso(trim($objDispositivoAcessoDTO->getStrIpAcesso()));

      if (strlen($objDispositivoAcessoDTO->getStrIpAcesso())>39){
        $objInfraException->adicionarValidacao('IP de Acesso possui tamanho superior a 60 caracteres.');
      }
    }
  }

  private function validarStrChaveAcesso(DispositivoAcessoDTO $objDispositivoAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDispositivoAcessoDTO->getStrChaveAcesso())){
      $objDispositivoAcessoDTO->setStrChaveAcesso(null);
    }else{
      $objDispositivoAcessoDTO->setStrChaveAcesso(trim($objDispositivoAcessoDTO->getStrChaveAcesso()));

      if (strlen($objDispositivoAcessoDTO->getStrChaveAcesso())>60){
        $objInfraException->adicionarValidacao('Chave de Acesso possui tamanho superior a 60 caracteres.');
      }
    }
  }

  private function validarStrChaveDispositivo(DispositivoAcessoDTO $objDispositivoAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDispositivoAcessoDTO->getStrChaveDispositivo())){
      $objInfraException->adicionarValidacao('Chave de Dispositivo não informada.');
    }else{
      $objDispositivoAcessoDTO->setStrChaveDispositivo(trim($objDispositivoAcessoDTO->getStrChaveDispositivo()));

      if (strlen($objDispositivoAcessoDTO->getStrChaveDispositivo())>60){
        $objInfraException->adicionarValidacao('Chave de Dispositivo possui tamanho superior a 60 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(DispositivoAcessoDTO $objDispositivoAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objDispositivoAcessoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objDispositivoAcessoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(DispositivoAcessoDTO $objDispositivoAcessoDTO) {
    try{

      //SessaoSip::getInstance()->validarAuditarPermissao('dispositivo_acesso_cadastrar', __METHOD__, $objDispositivoAcessoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrIdCodigoAcesso($objDispositivoAcessoDTO, $objInfraException);
      $this->validarDthLiberacao($objDispositivoAcessoDTO, $objInfraException);
      $this->validarDthAcesso($objDispositivoAcessoDTO, $objInfraException);
      $this->validarStrIpAcesso($objDispositivoAcessoDTO, $objInfraException);
      $this->validarStrChaveAcesso($objDispositivoAcessoDTO, $objInfraException);
      $this->validarStrChaveDispositivo($objDispositivoAcessoDTO, $objInfraException);
      $this->validarStrSinAtivo($objDispositivoAcessoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objDispositivoAcessoBD = new DispositivoAcessoBD($this->getObjInfraIBanco());
      $ret = $objDispositivoAcessoBD->cadastrar($objDispositivoAcessoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Dispositivo de Acesso.',$e);
    }
  }

  protected function alterarControlado(DispositivoAcessoDTO $objDispositivoAcessoDTO){
    try {

      //SessaoSip::getInstance()->validarAuditarPermissao('dispositivo_acesso_alterar', __METHOD__, $objDispositivoAcessoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objDispositivoAcessoDTO->isSetStrIdCodigoAcesso()){
        $this->validarStrIdCodigoAcesso($objDispositivoAcessoDTO, $objInfraException);
      }
      if ($objDispositivoAcessoDTO->isSetDthLiberacao()){
        $this->validarDthLiberacao($objDispositivoAcessoDTO, $objInfraException);
      }
      if ($objDispositivoAcessoDTO->isSetStrChaveAcesso()){
        $this->validarStrChaveAcesso($objDispositivoAcessoDTO, $objInfraException);
      }
      if ($objDispositivoAcessoDTO->isSetStrChaveDispositivo()){
        $this->validarStrChaveDispositivo($objDispositivoAcessoDTO, $objInfraException);
      }
      if ($objDispositivoAcessoDTO->isSetDthAcesso()){
        $this->validarDthAcesso($objDispositivoAcessoDTO, $objInfraException);
      }
      if ($objDispositivoAcessoDTO->isSetStrIpAcesso()) {
        $this->validarStrIpAcesso($objDispositivoAcessoDTO, $objInfraException);
      }
      if ($objDispositivoAcessoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objDispositivoAcessoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objDispositivoAcessoBD = new DispositivoAcessoBD($this->getObjInfraIBanco());
      $objDispositivoAcessoBD->alterar($objDispositivoAcessoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Dispositivo de Acesso.',$e);
    }
  }

  protected function excluirControlado($arrObjDispositivoAcessoDTO){
    try {

      //SessaoSip::getInstance()->validarAuditarPermissao('dispositivo_acesso_excluir', __METHOD__, $arrObjDispositivoAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDispositivoAcessoBD = new DispositivoAcessoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjDispositivoAcessoDTO);$i++){
        $objDispositivoAcessoBD->excluir($arrObjDispositivoAcessoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Dispositivo de Acesso.',$e);
    }
  }

  protected function consultarConectado(DispositivoAcessoDTO $objDispositivoAcessoDTO){
    try {

      //SessaoSip::getInstance()->validarAuditarPermissao('dispositivo_acesso_consultar', __METHOD__, $objDispositivoAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDispositivoAcessoBD = new DispositivoAcessoBD($this->getObjInfraIBanco());
      $ret = $objDispositivoAcessoBD->consultar($objDispositivoAcessoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Dispositivo de Acesso.',$e);
    }
  }

  protected function listarConectado(DispositivoAcessoDTO $objDispositivoAcessoDTO) {
    try {

      //SessaoSip::getInstance()->validarAuditarPermissao('dispositivo_acesso_listar', __METHOD__, $objDispositivoAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDispositivoAcessoBD = new DispositivoAcessoBD($this->getObjInfraIBanco());
      $ret = $objDispositivoAcessoBD->listar($objDispositivoAcessoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Dispositivos de Acesso.',$e);
    }
  }

  protected function contarConectado(DispositivoAcessoDTO $objDispositivoAcessoDTO){
    try {

      //SessaoSip::getInstance()->validarAuditarPermissao('dispositivo_acesso_listar', __METHOD__, $objDispositivoAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDispositivoAcessoBD = new DispositivoAcessoBD($this->getObjInfraIBanco());
      $ret = $objDispositivoAcessoBD->contar($objDispositivoAcessoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Dispositivos de Acesso.',$e);
    }
  }

  protected function desativarControlado($arrObjDispositivoAcessoDTO){
    try {

      //SessaoSip::getInstance()->validarAuditarPermissao('dispositivo_acesso_desativar', __METHOD__, $arrObjDispositivoAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $strDataHora = InfraData::getStrDataHoraAtual();
      $objDispositivoAcessoBD = new DispositivoAcessoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjDispositivoAcessoDTO);$i++){
        $objDispositivoAcessoBD->desativar($arrObjDispositivoAcessoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Dispositivo de Acesso.',$e);
    }
  }

  protected function reativarControlado($arrObjDispositivoAcessoDTO){
    try {

      //SessaoSip::getInstance()->validarAuditarPermissao('dispositivo_acesso_reativar', __METHOD__, $arrObjDispositivoAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDispositivoAcessoBD = new DispositivoAcessoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjDispositivoAcessoDTO);$i++){
        $objDispositivoAcessoBD->reativar($arrObjDispositivoAcessoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Dispositivo de Acesso.',$e);
    }
  }

  protected function bloquearControlado(DispositivoAcessoDTO $objDispositivoAcessoDTO){
    try {

      //SessaoSip::getInstance()->validarAuditarPermissao('dispositivo_acesso_consultar', __METHOD__, $objDispositivoAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objDispositivoAcessoBD = new DispositivoAcessoBD($this->getObjInfraIBanco());
      $ret = $objDispositivoAcessoBD->bloquear($objDispositivoAcessoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Dispositivo de Acesso.',$e);
    }
  }
}
