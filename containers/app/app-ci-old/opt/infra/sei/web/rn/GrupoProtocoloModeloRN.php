<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/08/2012 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoProtocoloModeloRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(GrupoProtocoloModeloDTO $objGrupoProtocoloModeloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoProtocoloModeloDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objGrupoProtocoloModeloDTO->setStrNome(trim($objGrupoProtocoloModeloDTO->getStrNome()));

      if (strlen($objGrupoProtocoloModeloDTO->getStrNome())>$this->getNumMaxTamanhoNome()){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a '.$this->getNumMaxTamanhoNome().' caracteres.');
      }
    }
  }

  public function getNumMaxTamanhoNome(){
    return 50;
  }

  private function validarNumIdUnidade(GrupoProtocoloModeloDTO $objGrupoProtocoloModeloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoProtocoloModeloDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function verificarExistencia(GrupoProtocoloModeloDTO $objGrupoProtocoloModeloDTO, InfraException $objInfraException){
  
    // Verifica se o Grupo existe dentro desta unidade
    $objGrupoProtocoloModeloVerificacaoDTO = new GrupoProtocoloModeloDTO();
    $objGrupoProtocoloModeloVerificacaoDTO->setNumIdUnidade($objGrupoProtocoloModeloDTO->getNumIdUnidade());
    $objGrupoProtocoloModeloVerificacaoDTO->setStrNome($objGrupoProtocoloModeloDTO->getStrNome());
    $objGrupoProtocoloModeloVerificacaoDTO->retNumIdGrupoProtocoloModelo();
  
    if ($this->consultar($objGrupoProtocoloModeloVerificacaoDTO)){
      $objInfraException->adicionarValidacao('Este grupo já existe nesta Unidade.');
    }
  }
  
  protected function cadastrarControlado(GrupoProtocoloModeloDTO $objGrupoProtocoloModeloDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_protocolo_modelo_cadastrar',__METHOD__,$objGrupoProtocoloModeloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objGrupoProtocoloModeloDTO, $objInfraException);
      $this->validarNumIdUnidade($objGrupoProtocoloModeloDTO, $objInfraException);
      $this->verificarExistencia($objGrupoProtocoloModeloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objGrupoProtocoloModeloBD = new GrupoProtocoloModeloBD($this->getObjInfraIBanco());
      $ret = $objGrupoProtocoloModeloBD->cadastrar($objGrupoProtocoloModeloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Grupo de Favorito.',$e);
    }
  }

  protected function alterarControlado(GrupoProtocoloModeloDTO $objGrupoProtocoloModeloDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('grupo_protocolo_modelo_alterar',__METHOD__,$objGrupoProtocoloModeloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objGrupoProtocoloModeloDTO->isSetStrNome()){
        $this->validarStrNome($objGrupoProtocoloModeloDTO, $objInfraException);
      }
      if ($objGrupoProtocoloModeloDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objGrupoProtocoloModeloDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objGrupoProtocoloModeloBD = new GrupoProtocoloModeloBD($this->getObjInfraIBanco());
      $objGrupoProtocoloModeloBD->alterar($objGrupoProtocoloModeloDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Grupo de Favorito.',$e);
    }
  }

  protected function excluirControlado($arrObjGrupoProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_protocolo_modelo_excluir',__METHOD__,$arrObjGrupoProtocoloModeloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objProtocoloModeloRN = new ProtocoloModeloRN();
      foreach($arrObjGrupoProtocoloModeloDTO as $objGrupoProtocoloModeloDTO){
        $objProtocoloModeloDTO = new ProtocoloModeloDTO();
        $objProtocoloModeloDTO->setNumIdGrupoProtocoloModelo($objGrupoProtocoloModeloDTO->getNumIdGrupoProtocoloModelo());
        
        if ($objProtocoloModeloRN->contar($objProtocoloModeloDTO)){
          $dto = new GrupoProtocoloModeloDTO();
          $dto->retStrNome();
          $dto->setNumIdGrupoProtocoloModelo($objGrupoProtocoloModeloDTO->getNumIdGrupoProtocoloModelo());
          
          $dto = $this->consultar($dto);
          
          $objInfraException->adicionarValidacao('Grupo "'.$dto->getStrNome().'" contém documentos associados.');
        }
      }
      $objInfraException->lancarValidacoes();

      $objGrupoProtocoloModeloBD = new GrupoProtocoloModeloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoProtocoloModeloDTO);$i++){
        $objGrupoProtocoloModeloBD->excluir($arrObjGrupoProtocoloModeloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Grupo de Favorito.',$e);
    }
  }

  protected function consultarConectado(GrupoProtocoloModeloDTO $objGrupoProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_protocolo_modelo_consultar',__METHOD__,$objGrupoProtocoloModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoProtocoloModeloBD = new GrupoProtocoloModeloBD($this->getObjInfraIBanco());
      $ret = $objGrupoProtocoloModeloBD->consultar($objGrupoProtocoloModeloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Grupo de Favorito.',$e);
    }
  }

  protected function listarConectado(GrupoProtocoloModeloDTO $objGrupoProtocoloModeloDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_protocolo_modelo_listar',__METHOD__,$objGrupoProtocoloModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoProtocoloModeloBD = new GrupoProtocoloModeloBD($this->getObjInfraIBanco());
      $ret = $objGrupoProtocoloModeloBD->listar($objGrupoProtocoloModeloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Grupo de Favoritos.',$e);
    }
  }

  protected function contarConectado(GrupoProtocoloModeloDTO $objGrupoProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_protocolo_modelo_listar',__METHOD__,$objGrupoProtocoloModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoProtocoloModeloBD = new GrupoProtocoloModeloBD($this->getObjInfraIBanco());
      $ret = $objGrupoProtocoloModeloBD->contar($objGrupoProtocoloModeloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Grupo de Favoritos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjGrupoProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_protocolo_modelo_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoProtocoloModeloBD = new GrupoProtocoloModeloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoProtocoloModeloDTO);$i++){
        $objGrupoProtocoloModeloBD->desativar($arrObjGrupoProtocoloModeloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Grupo de Favorito.',$e);
    }
  }

  protected function reativarControlado($arrObjGrupoProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_protocolo_modelo_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoProtocoloModeloBD = new GrupoProtocoloModeloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoProtocoloModeloDTO);$i++){
        $objGrupoProtocoloModeloBD->reativar($arrObjGrupoProtocoloModeloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Grupo de Favorito.',$e);
    }
  }

  protected function bloquearControlado(GrupoProtocoloModeloDTO $objGrupoProtocoloModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_protocolo_modelo_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoProtocoloModeloBD = new GrupoProtocoloModeloBD($this->getObjInfraIBanco());
      $ret = $objGrupoProtocoloModeloBD->bloquear($objGrupoProtocoloModeloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Grupo de Favorito.',$e);
    }
  }

 */
}
?>