<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/11/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioTipoProcedRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUsuario(RelUsuarioTipoProcedDTO $objRelUsuarioTipoProcedDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioTipoProcedDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarNumIdTipoProcedimento(RelUsuarioTipoProcedDTO $objRelUsuarioTipoProcedDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioTipoProcedDTO->getNumIdTipoProcedimento())){
      $objInfraException->adicionarValidacao('Tipo de Processo não informado.');
    }
  }

  private function validarNumIdUnidade(RelUsuarioTipoProcedDTO $objRelUsuarioTipoProcedDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioTipoProcedDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  protected function configurarControlado($arrObjRelUsuarioTipoProcedDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_configurar',__METHOD__,$arrObjRelUsuarioTipoProcedDTO);

      //Regras de Negocio

      $objRelUsuarioTipoProcedDTO = new RelUsuarioTipoProcedDTO();
      $objRelUsuarioTipoProcedDTO->retNumIdTipoProcedimento();
      $objRelUsuarioTipoProcedDTO->retNumIdUsuario();
      $objRelUsuarioTipoProcedDTO->retNumIdUnidade();
      $objRelUsuarioTipoProcedDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objRelUsuarioTipoProcedDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      $this->excluir($this->listar($objRelUsuarioTipoProcedDTO));

      foreach($arrObjRelUsuarioTipoProcedDTO as $objRelUsuarioTipoProcedDTO){
        $objRelUsuarioTipoProcedDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objRelUsuarioTipoProcedDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $this->cadastrar($objRelUsuarioTipoProcedDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro configurando visualização de tipos de processos.',$e);
    }
  }

  protected function cadastrarControlado(RelUsuarioTipoProcedDTO $objRelUsuarioTipoProcedDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_cadastrar', __METHOD__, $objRelUsuarioTipoProcedDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUsuario($objRelUsuarioTipoProcedDTO, $objInfraException);
      $this->validarNumIdTipoProcedimento($objRelUsuarioTipoProcedDTO, $objInfraException);
      $this->validarNumIdUnidade($objRelUsuarioTipoProcedDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelUsuarioTipoProcedBD = new RelUsuarioTipoProcedBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioTipoProcedBD->cadastrar($objRelUsuarioTipoProcedDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Tipo de Processo Selecionado.',$e);
    }
  }

  protected function alterarControlado(RelUsuarioTipoProcedDTO $objRelUsuarioTipoProcedDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_alterar', __METHOD__, $objRelUsuarioTipoProcedDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelUsuarioTipoProcedDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objRelUsuarioTipoProcedDTO, $objInfraException);
      }
      if ($objRelUsuarioTipoProcedDTO->isSetNumIdTipoProcedimento()){
        $this->validarNumIdTipoProcedimento($objRelUsuarioTipoProcedDTO, $objInfraException);
      }
      if ($objRelUsuarioTipoProcedDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objRelUsuarioTipoProcedDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelUsuarioTipoProcedBD = new RelUsuarioTipoProcedBD($this->getObjInfraIBanco());
      $objRelUsuarioTipoProcedBD->alterar($objRelUsuarioTipoProcedDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Tipo de Processo Selecionado.',$e);
    }
  }

  protected function excluirControlado($arrObjRelUsuarioTipoProcedDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_excluir', __METHOD__, $arrObjRelUsuarioTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoProcedBD = new RelUsuarioTipoProcedBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioTipoProcedDTO);$i++){
        $objRelUsuarioTipoProcedBD->excluir($arrObjRelUsuarioTipoProcedDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Tipo de Processo Selecionado.',$e);
    }
  }

  protected function consultarConectado(RelUsuarioTipoProcedDTO $objRelUsuarioTipoProcedDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_consultar', __METHOD__, $objRelUsuarioTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoProcedBD = new RelUsuarioTipoProcedBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioTipoProcedBD->consultar($objRelUsuarioTipoProcedDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Tipo de Processo Selecionado.',$e);
    }
  }

  protected function listarConectado(RelUsuarioTipoProcedDTO $objRelUsuarioTipoProcedDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_listar', __METHOD__, $objRelUsuarioTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoProcedBD = new RelUsuarioTipoProcedBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioTipoProcedBD->listar($objRelUsuarioTipoProcedDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Tipos de Processos Selecionados.',$e);
    }
  }

  protected function contarConectado(RelUsuarioTipoProcedDTO $objRelUsuarioTipoProcedDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_listar', __METHOD__, $objRelUsuarioTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoProcedBD = new RelUsuarioTipoProcedBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioTipoProcedBD->contar($objRelUsuarioTipoProcedDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Tipos de Processos Selecionados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelUsuarioTipoProcedDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_desativar', __METHOD__, $arrObjRelUsuarioTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoProcedBD = new RelUsuarioTipoProcedBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioTipoProcedDTO);$i++){
        $objRelUsuarioTipoProcedBD->desativar($arrObjRelUsuarioTipoProcedDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Tipo de Processo Selecionado.',$e);
    }
  }

  protected function reativarControlado($arrObjRelUsuarioTipoProcedDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_reativar', __METHOD__, $arrObjRelUsuarioTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoProcedBD = new RelUsuarioTipoProcedBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioTipoProcedDTO);$i++){
        $objRelUsuarioTipoProcedBD->reativar($arrObjRelUsuarioTipoProcedDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Tipo de Processo Selecionado.',$e);
    }
  }

  protected function bloquearControlado(RelUsuarioTipoProcedDTO $objRelUsuarioTipoProcedDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_tipo_proced_consultar', __METHOD__, $objRelUsuarioTipoProcedDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioTipoProcedBD = new RelUsuarioTipoProcedBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioTipoProcedBD->bloquear($objRelUsuarioTipoProcedDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Tipo de Processo Selecionado.',$e);
    }
  }

 */
}
