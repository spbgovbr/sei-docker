<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/04/2023 - criado por cas84
*
* Versão do Gerador de Código: 1.43.2
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioTipoPrioridadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUnidade(RelUsuarioTipoPrioridadeDTO $objRelUsuarioTipoPrioridadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioTipoPrioridadeDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdUsuario(RelUsuarioTipoPrioridadeDTO $objRelUsuarioTipoPrioridadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioTipoPrioridadeDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdTipoPrioridade(RelUsuarioTipoPrioridadeDTO $objRelUsuarioTipoPrioridadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioTipoPrioridadeDTO->getNumIdTipoPrioridade())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  protected function cadastrarControlado(RelUsuarioTipoPrioridadeDTO $objRelUsuarioTipoPrioridadeDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_prioridade_cadastrar',__METHOD__,$objRelUsuarioTipoPrioridadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidade($objRelUsuarioTipoPrioridadeDTO, $objInfraException);
      $this->validarNumIdUsuario($objRelUsuarioTipoPrioridadeDTO, $objInfraException);
      $this->validarNumIdTipoPrioridade($objRelUsuarioTipoPrioridadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelUsuarioTipoPrioridadeBD = new RelUsuarioTipoPrioridadeBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioTipoPrioridadeBD->cadastrar($objRelUsuarioTipoPrioridadeDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(RelUsuarioTipoPrioridadeDTO $objRelUsuarioTipoPrioridadeDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_prioridade_alterar',__METHOD__,$objRelUsuarioTipoPrioridadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelUsuarioTipoPrioridadeDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objRelUsuarioTipoPrioridadeDTO, $objInfraException);
      }
      if ($objRelUsuarioTipoPrioridadeDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objRelUsuarioTipoPrioridadeDTO, $objInfraException);
      }
      if ($objRelUsuarioTipoPrioridadeDTO->isSetNumIdTipoPrioridade()){
        $this->validarNumIdTipoPrioridade($objRelUsuarioTipoPrioridadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelUsuarioTipoPrioridadeBD = new RelUsuarioTipoPrioridadeBD($this->getObjInfraIBanco());
      $objRelUsuarioTipoPrioridadeBD->alterar($objRelUsuarioTipoPrioridadeDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjRelUsuarioTipoPrioridadeDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_prioridade_excluir',__METHOD__,$arrObjRelUsuarioTipoPrioridadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoPrioridadeBD = new RelUsuarioTipoPrioridadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioTipoPrioridadeDTO);$i++){
        $objRelUsuarioTipoPrioridadeBD->excluir($arrObjRelUsuarioTipoPrioridadeDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(RelUsuarioTipoPrioridadeDTO $objRelUsuarioTipoPrioridadeDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_prioridade_consultar',__METHOD__,$objRelUsuarioTipoPrioridadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoPrioridadeBD = new RelUsuarioTipoPrioridadeBD($this->getObjInfraIBanco());

      /** @var RelUsuarioTipoPrioridadeDTO $ret */
      $ret = $objRelUsuarioTipoPrioridadeBD->consultar($objRelUsuarioTipoPrioridadeDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(RelUsuarioTipoPrioridadeDTO $objRelUsuarioTipoPrioridadeDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_prioridade_listar',__METHOD__,$objRelUsuarioTipoPrioridadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoPrioridadeBD = new RelUsuarioTipoPrioridadeBD($this->getObjInfraIBanco());

      /** @var RelUsuarioTipoPrioridadeDTO[] $ret */
      $ret = $objRelUsuarioTipoPrioridadeBD->listar($objRelUsuarioTipoPrioridadeDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(RelUsuarioTipoPrioridadeDTO $objRelUsuarioTipoPrioridadeDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_prioridade_listar',__METHOD__,$objRelUsuarioTipoPrioridadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoPrioridadeBD = new RelUsuarioTipoPrioridadeBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioTipoPrioridadeBD->contar($objRelUsuarioTipoPrioridadeDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }

  protected function configurarControlado($arrObjRelUsuarioTipoPrioridadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_configurar',__METHOD__,$arrObjRelUsuarioTipoPrioridadeDTO);

      //Regras de Negocio

      $objRelUsuarioTipoPrioridadeDTO = new RelUsuarioTipoPrioridadeDTO();
      $objRelUsuarioTipoPrioridadeDTO->setBolExclusaoLogica(false);
      $objRelUsuarioTipoPrioridadeDTO->retNumIdTipoPrioridade();
      $objRelUsuarioTipoPrioridadeDTO->retNumIdUsuario();
      $objRelUsuarioTipoPrioridadeDTO->retNumIdUnidade();
      $objRelUsuarioTipoPrioridadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objRelUsuarioTipoPrioridadeDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      $this->excluir($this->listar($objRelUsuarioTipoPrioridadeDTO));

      foreach($arrObjRelUsuarioTipoPrioridadeDTO as $objRelUsuarioTipoPrioridadeDTO){
        $objRelUsuarioTipoPrioridadeDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objRelUsuarioTipoPrioridadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $this->cadastrar($objRelUsuarioTipoPrioridadeDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro configurando visualização de tipos de prioridades.',$e);
    }
  }

  /* 
    protected function desativarControlado($arrObjRelUsuarioTipoPrioridadeDTO){
      try {
  
        SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_prioridade_desativar',__METHOD__,$arrObjRelUsuarioTipoPrioridadeDTO);
  
        //Regras de Negocio
        //$objInfraException = new InfraException();
  
        //$objInfraException->lancarValidacoes();
  
        $objRelUsuarioTipoPrioridadeBD = new RelUsuarioTipoPrioridadeBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelUsuarioTipoPrioridadeDTO);$i++){
          $objRelUsuarioTipoPrioridadeBD->desativar($arrObjRelUsuarioTipoPrioridadeDTO[$i]);
        }
  
      }catch(Exception $e){
        throw new InfraException('Erro desativando .',$e);
      }
    }
  
    protected function reativarControlado($arrObjRelUsuarioTipoPrioridadeDTO){
      try {
  
        SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_prioridade_reativar',__METHOD__,$arrObjRelUsuarioTipoPrioridadeDTO);
  
        //Regras de Negocio
        //$objInfraException = new InfraException();
  
        //$objInfraException->lancarValidacoes();
  
        $objRelUsuarioTipoPrioridadeBD = new RelUsuarioTipoPrioridadeBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjRelUsuarioTipoPrioridadeDTO);$i++){
          $objRelUsuarioTipoPrioridadeBD->reativar($arrObjRelUsuarioTipoPrioridadeDTO[$i]);
        }
  
      }catch(Exception $e){
        throw new InfraException('Erro reativando .',$e);
      }
    }
  
    protected function bloquearControlado(RelUsuarioTipoPrioridadeDTO $objRelUsuarioTipoPrioridadeDTO){
      try {
  
        SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_prioridade_consultar',__METHOD__,$objRelUsuarioTipoPrioridadeDTO);
  
        //Regras de Negocio
        //$objInfraException = new InfraException();
  
        //$objInfraException->lancarValidacoes();
  
        $objRelUsuarioTipoPrioridadeBD = new RelUsuarioTipoPrioridadeBD($this->getObjInfraIBanco());
        $ret = $objRelUsuarioTipoPrioridadeBD->bloquear($objRelUsuarioTipoPrioridadeDTO);
  
        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando .',$e);
      }
    }
  
   */
}
