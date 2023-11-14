<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/08/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoBlocoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUnidade(GrupoBlocoDTO $objGrupoBlocoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoBlocoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarStrNome(GrupoBlocoDTO $objGrupoBlocoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoBlocoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{

      $objGrupoBlocoDTO->setStrNome(trim($objGrupoBlocoDTO->getStrNome()));

      $objGrupoBlocoDTO->setStrNome(InfraUtil::filtrarISO88591($objGrupoBlocoDTO->getStrNome()));

      if (strlen($objGrupoBlocoDTO->getStrNome())>$this->getNumMaxTamanhoNome()){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a '.$this->getNumMaxTamanhoNome().' caracteres.');
      }

      $dto = new GrupoBlocoDTO();
      $dto->retNumIdGrupoBloco();
      $dto->setNumIdGrupoBloco($objGrupoBlocoDTO->getNumIdGrupoBloco(), InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdUnidade($objGrupoBlocoDTO->getNumIdUnidade());
      $dto->setStrNome($objGrupoBlocoDTO->getStrNome());
      $dto = $this->consultar($dto);
      if ($dto != null) {
        $objInfraException->adicionarValidacao('Existe outro Grupo de Bloco com mesmo nome nesta unidade.');
      }
    }
  }

  public function getNumMaxTamanhoNome(){
    return 100;
  }

  protected function cadastrarControlado(GrupoBlocoDTO $objGrupoBlocoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_bloco_cadastrar', __METHOD__, $objGrupoBlocoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUnidade($objGrupoBlocoDTO, $objInfraException);
      $this->validarStrNome($objGrupoBlocoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objGrupoBlocoBD = new GrupoBlocoBD($this->getObjInfraIBanco());
      $ret = $objGrupoBlocoBD->cadastrar($objGrupoBlocoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Grupo de Bloco.',$e);
    }
  }

  protected function alterarControlado(GrupoBlocoDTO $objGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_bloco_alterar', __METHOD__, $objGrupoBlocoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objGrupoBlocoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objGrupoBlocoDTO, $objInfraException);
      }
      if ($objGrupoBlocoDTO->isSetStrNome()){
        $this->validarStrNome($objGrupoBlocoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objGrupoBlocoBD = new GrupoBlocoBD($this->getObjInfraIBanco());
      $objGrupoBlocoBD->alterar($objGrupoBlocoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Grupo de Bloco.',$e);
    }
  }

  protected function excluirControlado($arrObjGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_bloco_excluir', __METHOD__, $arrObjGrupoBlocoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objRelBlocoUnidadeRN = new RelBlocoUnidadeRN();
      foreach($arrObjGrupoBlocoDTO as $objGrupoBlocoDTO){

        $objGrupoBlocoDTOBanco = new GrupoBlocoDTO();
        $objGrupoBlocoDTOBanco->retStrNome();
        $objGrupoBlocoDTOBanco->setNumIdGrupoBloco($objGrupoBlocoDTO->getNumIdGrupoBloco());
        $objGrupoBlocoDTOBanco = $this->consultar($objGrupoBlocoDTOBanco);

        if ($objGrupoBlocoDTOBanco == null){
          throw new InfraException('Grupo de bloco não encontrado.');
        }

        $objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
        $objRelBlocoUnidadeDTO->retNumIdBloco();
        $objRelBlocoUnidadeDTO->setNumIdGrupoBloco($objGrupoBlocoDTO->getNumIdGrupoBloco());
        $objRelBlocoUnidadeDTO->setNumMaxRegistrosRetorno(1);

        if ($objRelBlocoUnidadeRN->consultarRN1303($objRelBlocoUnidadeDTO) != null){
          $objInfraException->adicionarValidacao('Existem blocos associados com o grupo "'.$objGrupoBlocoDTOBanco->getStrNome().'".');
        }
      }
      $objInfraException->lancarValidacoes();

      $objGrupoBlocoBD = new GrupoBlocoBD($this->getObjInfraIBanco());
      foreach($arrObjGrupoBlocoDTO as $objGrupoBlocoDTO){

        $objRelUsuarioGrupoBlocoDTO = new RelUsuarioGrupoBlocoDTO();
        $objRelUsuarioGrupoBlocoDTO->retNumIdGrupoBloco();
        $objRelUsuarioGrupoBlocoDTO->retNumIdUsuario();
        $objRelUsuarioGrupoBlocoDTO->setNumIdGrupoBloco($objGrupoBlocoDTO->getNumIdGrupoBloco());

        $objRelUsuarioGrupoBlocoRN = new RelUsuarioGrupoBlocoRN();
        $objRelUsuarioGrupoBlocoRN->excluir($objRelUsuarioGrupoBlocoRN->listar($objRelUsuarioGrupoBlocoDTO));

        $objGrupoBlocoBD->excluir($objGrupoBlocoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Grupo de Bloco.',$e);
    }
  }

  protected function consultarConectado(GrupoBlocoDTO $objGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_bloco_consultar', __METHOD__, $objGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoBlocoBD = new GrupoBlocoBD($this->getObjInfraIBanco());
      $ret = $objGrupoBlocoBD->consultar($objGrupoBlocoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Grupo de Bloco.',$e);
    }
  }

  protected function listarConectado(GrupoBlocoDTO $objGrupoBlocoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_bloco_listar', __METHOD__, $objGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoBlocoBD = new GrupoBlocoBD($this->getObjInfraIBanco());
      $ret = $objGrupoBlocoBD->listar($objGrupoBlocoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Grupos de Blocos.',$e);
    }
  }

  protected function contarConectado(GrupoBlocoDTO $objGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_bloco_listar', __METHOD__, $objGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoBlocoBD = new GrupoBlocoBD($this->getObjInfraIBanco());
      $ret = $objGrupoBlocoBD->contar($objGrupoBlocoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Grupos de Blocos.',$e);
    }
  }

  protected function desativarControlado($arrObjGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_bloco_desativar', __METHOD__, $arrObjGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoBlocoBD = new GrupoBlocoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoBlocoDTO);$i++){
        $objGrupoBlocoBD->desativar($arrObjGrupoBlocoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Grupo de Bloco.',$e);
    }
  }

  protected function reativarControlado($arrObjGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_bloco_reativar', __METHOD__, $arrObjGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoBlocoBD = new GrupoBlocoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoBlocoDTO);$i++){
        $objGrupoBlocoBD->reativar($arrObjGrupoBlocoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Grupo de Bloco.',$e);
    }
  }

  protected function bloquearControlado(GrupoBlocoDTO $objGrupoBlocoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_bloco_consultar', __METHOD__, $objGrupoBlocoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoBlocoBD = new GrupoBlocoBD($this->getObjInfraIBanco());
      $ret = $objGrupoBlocoBD->bloquear($objGrupoBlocoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Grupo de Bloco.',$e);
    }
  }
}
