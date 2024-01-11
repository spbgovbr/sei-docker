<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoUnidadeRN extends InfraRN {

  public static $TGU_INSTITUCIONAL = 'I';
  public static $TGU_UNIDADE = 'U';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function listarValoresTipo(){
    try {

      $arrObjTipoDTO = array();

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TGU_INSTITUCIONAL);
      $objTipoDTO->setStrDescricao('Institucional');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TGU_UNIDADE);
      $objTipoDTO->setStrDescricao('Unidade');
      $arrObjTipoDTO[] = $objTipoDTO;

      return $arrObjTipoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Tipo.',$e);
    }
  }

  private function validarStrNome(GrupoUnidadeDTO $objGrupoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoUnidadeDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objGrupoUnidadeDTO->setStrNome(trim($objGrupoUnidadeDTO->getStrNome()));

      if (strlen($objGrupoUnidadeDTO->getStrNome()) > $this->getNumMaxTamanhoNome()){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a '.$this->getNumMaxTamanhoNome().' caracteres.');
      }

      $dto = new GrupoUnidadeDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSinAtivo();

      $dto->setNumIdGrupoUnidade($objGrupoUnidadeDTO->getNumIdGrupoUnidade(),InfraDTO::$OPER_DIFERENTE);

      if ($objGrupoUnidadeDTO->getStrStaTipo()==self::$TGU_UNIDADE) {
        $dto->setNumIdUnidade($objGrupoUnidadeDTO->getNumIdUnidade());
      }

      $dto->setStrNome($objGrupoUnidadeDTO->getStrNome());
      $dto->setStrStaTipo($objGrupoUnidadeDTO->getStrStaTipo());

      $dto = $this->consultar($dto);

      if ($dto!=null) {
        if ($dto->getStrSinAtivo()=='S') {
          if ($objGrupoUnidadeDTO->getStrStaTipo()==self::$TGU_INSTITUCIONAL) {
            $objInfraException->adicionarValidacao('Existe outro Grupo de Envio Institucional com este Nome.');
          } else {
            $objInfraException->adicionarValidacao('Existe outro Grupo de Envio com este Nome para esta Unidade.');
          }

        } else {
          if ($objGrupoUnidadeDTO->getStrStaTipo()==self::$TGU_INSTITUCIONAL) {
            $objInfraException->adicionarValidacao('Existe ocorrência inativa de Grupo de Envio Institucional com este Nome.');
          } else {
            $objInfraException->adicionarValidacao('Existe ocorrência inativa de Grupo de Envio com este Nome para esta Unidade.');
          }

        }
      }
    }
  }

  public function getNumMaxTamanhoNome(){
    return 50;
  }

  private function validarStrDescricao(GrupoUnidadeDTO $objGrupoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoUnidadeDTO->getStrDescricao())){
      $objGrupoUnidadeDTO->setStrDescricao(null);
    }else{
      $objGrupoUnidadeDTO->setStrDescricao(trim($objGrupoUnidadeDTO->getStrDescricao()));

      if (strlen($objGrupoUnidadeDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrStaTipo(GrupoUnidadeDTO $objGrupoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoUnidadeDTO->getStrStaTipo())){
      $objInfraException->adicionarValidacao('Tipo não informado.');
    }else{
      if (!in_array($objGrupoUnidadeDTO->getStrStaTipo(),InfraArray::converterArrInfraDTO($this->listarValoresTipo(),'StaTipo'))){
        $objInfraException->adicionarValidacao('Tipo inválido.');
      }
    }
  }

  private function validarStrSinAtivo(GrupoUnidadeDTO $objGrupoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoUnidadeDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objGrupoUnidadeDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  private function validarNumIdUnidade(GrupoUnidadeDTO $objGrupoUnidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objGrupoUnidadeDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  protected function cadastrarControlado(GrupoUnidadeDTO $objGrupoUnidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_unidade_cadastrar',__METHOD__,$objGrupoUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objGrupoUnidadeDTO, $objInfraException);
      $this->validarStrDescricao($objGrupoUnidadeDTO, $objInfraException);
      $this->validarStrStaTipo($objGrupoUnidadeDTO, $objInfraException);
      $this->validarStrSinAtivo($objGrupoUnidadeDTO, $objInfraException);
      $this->validarNumIdUnidade($objGrupoUnidadeDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objGrupoUnidadeBD = new GrupoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objGrupoUnidadeBD->cadastrar($objGrupoUnidadeDTO);

      $arrObjRelGrupoUnidadeUnidadeDTO =  $objGrupoUnidadeDTO->getArrObjRelGrupoUnidadeUnidadeDTO();

      $objRelGrupoUnidadeUnidadeRN = new RelGrupoUnidadeUnidadeRN();
      foreach($arrObjRelGrupoUnidadeUnidadeDTO as $objRelGrupoUnidadeUnidadeDTO){
        $objRelGrupoUnidadeUnidadeDTO->setNumIdGrupoUnidade($ret->getNumIdGrupoUnidade());
        $objRelGrupoUnidadeUnidadeRN->cadastrar($objRelGrupoUnidadeUnidadeDTO);
      }
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Grupo de Envio.',$e);
    }
  }

  protected function alterarControlado(GrupoUnidadeDTO $objGrupoUnidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('grupo_unidade_alterar',__METHOD__,$objGrupoUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objGrupoUnidadeDTOBanco = new GrupoUnidadeDTO();
      $objGrupoUnidadeDTOBanco->retNumIdUnidade();
      $objGrupoUnidadeDTOBanco->retStrStaTipo();
      $objGrupoUnidadeDTOBanco->setNumIdGrupoUnidade($objGrupoUnidadeDTO->getNumIdGrupoUnidade());
      $objGrupoUnidadeDTOBanco = $this->consultar($objGrupoUnidadeDTOBanco);

      if ($objGrupoUnidadeDTO->isSetNumIdUnidade() && $objGrupoUnidadeDTO->getNumIdUnidade()!=$objGrupoUnidadeDTOBanco->getNumIdUnidade()){
        $objInfraException->lancarValidacao('Unidade do Grupo de Envio não pode ser alterada.');
      }else{
        $objGrupoUnidadeDTO->setNumIdUnidade($objGrupoUnidadeDTOBanco->getNumIdUnidade());
      }

      if ($objGrupoUnidadeDTO->isSetStrStaTipo() && $objGrupoUnidadeDTO->getStrStaTipo()!=$objGrupoUnidadeDTOBanco->getStrStaTipo()){
        $objInfraException->lancarValidacao('Tipo do Grupo de Envio não pode ser alterado.');
      }else{
        $objGrupoUnidadeDTO->setStrStaTipo($objGrupoUnidadeDTOBanco->getStrStaTipo());
      }

      if ($objGrupoUnidadeDTO->isSetStrNome()){
        $this->validarStrNome($objGrupoUnidadeDTO, $objInfraException);
      }

      if ($objGrupoUnidadeDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objGrupoUnidadeDTO, $objInfraException);
      }

      if ($objGrupoUnidadeDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objGrupoUnidadeDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      if ($objGrupoUnidadeDTO->isSetArrObjRelGrupoUnidadeUnidadeDTO()){

        $objRelGrupoUnidadeUnidadeDTO = new RelGrupoUnidadeUnidadeDTO();
        $objRelGrupoUnidadeUnidadeDTO->retTodos();
        $objRelGrupoUnidadeUnidadeDTO->setNumIdGrupoUnidade($objGrupoUnidadeDTO->getNumIdGrupoUnidade());

        $objRelGrupoUnidadeUnidadeRN = new RelGrupoUnidadeUnidadeRN();
        $objRelGrupoUnidadeUnidadeRN->excluir($objRelGrupoUnidadeUnidadeRN->listar($objRelGrupoUnidadeUnidadeDTO));

        $arrObjRelGrupoUnidadeUnidadeDTO = $objGrupoUnidadeDTO->getArrObjRelGrupoUnidadeUnidadeDTO();
        foreach($arrObjRelGrupoUnidadeUnidadeDTO as $objRelGrupoUnidadeUnidadeDTO){
          $objRelGrupoUnidadeUnidadeDTO->setNumIdGrupoUnidade($objGrupoUnidadeDTO->getNumIdGrupoUnidade());
          $objRelGrupoUnidadeUnidadeRN->cadastrar($objRelGrupoUnidadeUnidadeDTO);
        }
      }

      $objGrupoUnidadeBD = new GrupoUnidadeBD($this->getObjInfraIBanco());
      $objGrupoUnidadeBD->alterar($objGrupoUnidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Grupo de Envio.',$e);
    }
  }

  protected function excluirControlado($arrObjGrupoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_unidade_excluir',__METHOD__,$arrObjGrupoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();
      $objRelGrupoUnidadeUnidadeRN = new RelGrupoUnidadeUnidadeRN();
      $objRelGrupoUnidadeUnidadeDTO = new RelGrupoUnidadeUnidadeDTO();
      $objRelGrupoUnidadeUnidadeDTO->retNumIdUnidade();
      $objRelGrupoUnidadeUnidadeDTO->retNumIdGrupoUnidade();

      $objGrupoUnidadeBD = new GrupoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoUnidadeDTO);$i++){
        $objRelGrupoUnidadeUnidadeDTO->setNumIdGrupoUnidade($arrObjGrupoUnidadeDTO[$i]->getNumIdGrupoUnidade());
        $arr=$objRelGrupoUnidadeUnidadeRN->listar($objRelGrupoUnidadeUnidadeDTO);
        if (count($arr)>0) $objRelGrupoUnidadeUnidadeRN->excluir($arr);
        $objGrupoUnidadeBD->excluir($arrObjGrupoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Grupo de Envio.',$e);
    }
  }

  protected function consultarConectado(GrupoUnidadeDTO $objGrupoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_unidade_consultar',__METHOD__,$objGrupoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoUnidadeBD = new GrupoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objGrupoUnidadeBD->consultar($objGrupoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Grupo de Envio.',$e);
    }
  }

  protected function listarConectado(GrupoUnidadeDTO $objGrupoUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_unidade_listar',__METHOD__,$objGrupoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoUnidadeBD = new GrupoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objGrupoUnidadeBD->listar($objGrupoUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Grupos de Envio.',$e);
    }
  }

  protected function contarConectado(GrupoUnidadeDTO $objGrupoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_unidade_listar',__METHOD__,$objGrupoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoUnidadeBD = new GrupoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objGrupoUnidadeBD->contar($objGrupoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Grupos de Envio.',$e);
    }
  }

  protected function desativarControlado($arrObjGrupoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_unidade_institucional_desativar',__METHOD__,$arrObjGrupoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoUnidadeBD = new GrupoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoUnidadeDTO);$i++){
        $objGrupoUnidadeBD->desativar($arrObjGrupoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Grupo de Envio.',$e);
    }
  }

  protected function reativarControlado($arrObjGrupoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_unidade_institucional_reativar',__METHOD__,$arrObjGrupoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoUnidadeBD = new GrupoUnidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjGrupoUnidadeDTO);$i++){
        $objGrupoUnidadeBD->reativar($arrObjGrupoUnidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Grupo de Envio.',$e);
    }
  }

  protected function bloquearControlado(GrupoUnidadeDTO $objGrupoUnidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('grupo_unidade_consultar',__METHOD__,$objGrupoUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objGrupoUnidadeBD = new GrupoUnidadeBD($this->getObjInfraIBanco());
      $ret = $objGrupoUnidadeBD->bloquear($objGrupoUnidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Grupo de Envio.',$e);
    }
  }

}
?>