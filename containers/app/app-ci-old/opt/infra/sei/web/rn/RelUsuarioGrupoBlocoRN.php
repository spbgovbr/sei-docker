<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/08/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelUsuarioGrupoBlocoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdGrupoBloco(RelUsuarioGrupoBlocoDTO $objRelUsuarioGrupoBlocoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioGrupoBlocoDTO->getNumIdGrupoBloco())){
      $objInfraException->adicionarValidacao('Grupo de Bloco não informado.');
    }
  }

  private function validarNumIdUsuario(RelUsuarioGrupoBlocoDTO $objRelUsuarioGrupoBlocoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objRelUsuarioGrupoBlocoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  protected function configurarControlado($arrObjRelUsuarioGrupoBlocoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_bloco_configurar',__METHOD__,$arrObjRelUsuarioGrupoBlocoDTO);

      //Regras de Negocio

      $objRelUsuarioGrupoBlocoDTO = new RelUsuarioGrupoBlocoDTO();
      $objRelUsuarioGrupoBlocoDTO->retNumIdGrupoBloco();
      $objRelUsuarioGrupoBlocoDTO->retNumIdUsuario();
      $objRelUsuarioGrupoBlocoDTO->setNumIdUnidadeGrupoBloco(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objRelUsuarioGrupoBlocoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      $this->excluir($this->listar($objRelUsuarioGrupoBlocoDTO));

      foreach($arrObjRelUsuarioGrupoBlocoDTO as $objRelUsuarioGrupoBlocoDTO){
        $objRelUsuarioGrupoBlocoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $this->cadastrar($objRelUsuarioGrupoBlocoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro configurando visualização de grupos de blocos.',$e);
    }
  }
  
  protected function cadastrarControlado(RelUsuarioGrupoBlocoDTO $objRelUsuarioGrupoBlocoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_bloco_cadastrar', __METHOD__, $objRelUsuarioGrupoBlocoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdGrupoBloco($objRelUsuarioGrupoBlocoDTO, $objInfraException);
      $this->validarNumIdUsuario($objRelUsuarioGrupoBlocoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoBlocoBD = new RelUsuarioGrupoBlocoBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioGrupoBlocoBD->cadastrar($objRelUsuarioGrupoBlocoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Grupo de Bloco Selecionado.',$e);
    }
  }

  protected function alterarControlado(RelUsuarioGrupoBlocoDTO $objRelUsuarioGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_bloco_alterar', __METHOD__, $objRelUsuarioGrupoBlocoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objRelUsuarioGrupoBlocoDTO->isSetNumIdGrupoBloco()){
        $this->validarNumIdGrupoBloco($objRelUsuarioGrupoBlocoDTO, $objInfraException);
      }
      if ($objRelUsuarioGrupoBlocoDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objRelUsuarioGrupoBlocoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoBlocoBD = new RelUsuarioGrupoBlocoBD($this->getObjInfraIBanco());
      $objRelUsuarioGrupoBlocoBD->alterar($objRelUsuarioGrupoBlocoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Grupo de Bloco Selecionado.',$e);
    }
  }

  protected function excluirControlado($arrObjRelUsuarioGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_bloco_excluir', __METHOD__, $arrObjRelUsuarioGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoBlocoBD = new RelUsuarioGrupoBlocoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioGrupoBlocoDTO);$i++){
        $objRelUsuarioGrupoBlocoBD->excluir($arrObjRelUsuarioGrupoBlocoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Grupo de Bloco Selecionado.',$e);
    }
  }

  protected function consultarConectado(RelUsuarioGrupoBlocoDTO $objRelUsuarioGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_bloco_consultar', __METHOD__, $objRelUsuarioGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoBlocoBD = new RelUsuarioGrupoBlocoBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioGrupoBlocoBD->consultar($objRelUsuarioGrupoBlocoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Grupo de Bloco Selecionado.',$e);
    }
  }

  protected function listarConectado(RelUsuarioGrupoBlocoDTO $objRelUsuarioGrupoBlocoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_bloco_listar', __METHOD__, $objRelUsuarioGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoBlocoBD = new RelUsuarioGrupoBlocoBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioGrupoBlocoBD->listar($objRelUsuarioGrupoBlocoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Grupos de Blocos Selecionados.',$e);
    }
  }

  protected function contarConectado(RelUsuarioGrupoBlocoDTO $objRelUsuarioGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_bloco_listar', __METHOD__, $objRelUsuarioGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoBlocoBD = new RelUsuarioGrupoBlocoBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioGrupoBlocoBD->contar($objRelUsuarioGrupoBlocoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Grupos de Blocos Selecionados.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjRelUsuarioGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_bloco_desativar', __METHOD__, $arrObjRelUsuarioGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoBlocoBD = new RelUsuarioGrupoBlocoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioGrupoBlocoDTO);$i++){
        $objRelUsuarioGrupoBlocoBD->desativar($arrObjRelUsuarioGrupoBlocoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Grupo de Bloco Selecionado.',$e);
    }
  }

  protected function reativarControlado($arrObjRelUsuarioGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_bloco_reativar', __METHOD__, $arrObjRelUsuarioGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoBlocoBD = new RelUsuarioGrupoBlocoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjRelUsuarioGrupoBlocoDTO);$i++){
        $objRelUsuarioGrupoBlocoBD->reativar($arrObjRelUsuarioGrupoBlocoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Grupo de Bloco Selecionado.',$e);
    }
  }

  protected function bloquearControlado(RelUsuarioGrupoBlocoDTO $objRelUsuarioGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('rel_usuario_grupo_bloco_consultar', __METHOD__, $objRelUsuarioGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objRelUsuarioGrupoBlocoBD = new RelUsuarioGrupoBlocoBD($this->getObjInfraIBanco());
      $ret = $objRelUsuarioGrupoBlocoBD->bloquear($objRelUsuarioGrupoBlocoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Grupo de Bloco Selecionado.',$e);
    }
  }

 */
}
