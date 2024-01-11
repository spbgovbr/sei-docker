<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelGrupoContatoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0462Controlado(RelGrupoContatoDTO $objRelGrupoContatoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_grupo_contato_cadastrar',__METHOD__,$objRelGrupoContatoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdGrupoContatoRN0559($objRelGrupoContatoDTO, $objInfraException);
      $this->validarNumIdContatoRN0560($objRelGrupoContatoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelGrupoContatoBD = new RelGrupoContatoBD($this->getObjInfraIBanco());
      $ret = $objRelGrupoContatoBD->cadastrar($objRelGrupoContatoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Relação Grupo Contato.',$e);
    }
  }

  protected function alterarRN0481Controlado(RelGrupoContatoDTO $objRelGrupoContatoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('rel_grupo_contato_alterar',__METHOD__,$objRelGrupoContatoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelGrupoContatoDTO->isSetNumIdGrupoContato()){
        $this->validarNumIdGrupoContatoRN0559($objRelGrupoContatoDTO, $objInfraException);
      }
      
      if ($objRelGrupoContatoDTO->isSetNumIdContato()){
        $this->validarNumIdContatoRN0560($objRelGrupoContatoDTO, $objInfraException);
      }
      
      $objInfraException->lancarValidacoes();

      $objRelGrupoContatoBD = new RelGrupoContatoBD($this->getObjInfraIBanco());
      $objRelGrupoContatoBD->alterar($objRelGrupoContatoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Relação Grupo Contato.',$e);
    }
  }

  protected function excluirRN0464Controlado($arrObjRelGrupoContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_grupo_contato_excluir',__METHOD__,$arrObjRelGrupoContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelGrupoContatoBD = new RelGrupoContatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelGrupoContatoDTO);$i++){
        $objRelGrupoContatoBD->excluir($arrObjRelGrupoContatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Relação Grupo Contato.',$e);
    }
  }

  protected function consultarRN0482Conectado(RelGrupoContatoDTO $objRelGrupoContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_grupo_contato_consultar',__METHOD__,$objRelGrupoContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelGrupoContatoBD = new RelGrupoContatoBD($this->getObjInfraIBanco());
      $ret = $objRelGrupoContatoBD->consultar($objRelGrupoContatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Relação Grupo Contato.',$e);
    }
  }

  protected function listarRN0463Conectado(RelGrupoContatoDTO $objRelGrupoContatoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_grupo_contato_listar',__METHOD__,$objRelGrupoContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelGrupoContatoBD = new RelGrupoContatoBD($this->getObjInfraIBanco());
      $ret = $objRelGrupoContatoBD->listar($objRelGrupoContatoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Relações Grupo Contato.',$e);
    }
  }

  protected function contarRN0465Conectado(RelGrupoContatoDTO $objRelGrupoContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_grupo_contato_listar',__METHOD__,$objRelGrupoContatoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelGrupoContatoBD = new RelGrupoContatoBD($this->getObjInfraIBanco());
      $ret = $objRelGrupoContatoBD->contar($objRelGrupoContatoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Relações Grupo Contato.',$e);
    }
  }

  private function validarNumIdGrupoContatoRN0559(RelGrupoContatoDTO $objRelGrupoContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelGrupoContatoDTO->getNumIdGrupoContato())){
      $objInfraException->adicionarValidacao('Grupo de Contato não informado.');
    }
  }

  private function validarNumIdContatoRN0560(RelGrupoContatoDTO $objRelGrupoContatoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelGrupoContatoDTO->getNumIdContato())){
      $objInfraException->adicionarValidacao('Contato do Grupo de Contato não informado.');
    }
  }
  
/* 
  protected function desativarControlado($arrObjRelGrupoContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_grupo_contato_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelGrupoContatoBD = new RelGrupoContatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelGrupoContatoDTO);$i++){
        $objRelGrupoContatoBD->desativar($arrObjRelGrupoContatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Relação Grupo Contato.',$e);
    }
  }

  protected function reativarControlado($arrObjRelGrupoContatoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_grupo_contato_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelGrupoContatoBD = new RelGrupoContatoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelGrupoContatoDTO);$i++){
        $objRelGrupoContatoBD->reativar($arrObjRelGrupoContatoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Relação Grupo Contato.',$e);
    }
  }

 */

  protected function pesquisarConectado(RelGrupoContatoDTO $objRelGrupoContatoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_grupo_contato_listar',__METHOD__,$objRelGrupoContatoDTO);

      $objRelGrupoContatoDTO = InfraString::prepararPesquisaDTO($objRelGrupoContatoDTO,"PalavrasPesquisa", "IdxContatoContato");

      return $this->listarRN0463($objRelGrupoContatoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando Grupo de Envio.',$e);
    }
  }

}
?>