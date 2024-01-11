<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/03/2020 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class PesquisaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(PesquisaDTO $objPesquisaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPesquisaDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objPesquisaDTO->setStrNome(trim($objPesquisaDTO->getStrNome()));

      if (strlen($objPesquisaDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }

      $dto = new PesquisaDTO();
      $dto->retNumIdPesquisa();
      $dto->setNumIdPesquisa($objPesquisaDTO->getNumIdPesquisa(), InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $dto->setStrNome($objPesquisaDTO->getStrNome());
      $dto = $this->consultar($dto);
      if ($dto != null) {
        $objInfraException->adicionarValidacao('Existe outra Pesquisa com mesmo nome.');
      }
    }
  }

  private function validarNumIdUsuario(PesquisaDTO $objPesquisaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPesquisaDTO->getNumIdUsuario())){
      $objPesquisaDTO->setNumIdUsuario(null);
    }
  }

  private function validarNumIdUnidade(PesquisaDTO $objPesquisaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objPesquisaDTO->getNumIdUnidade())){
      $objPesquisaDTO->setNumIdUnidade(null);
    }
  }

  protected function cadastrarControlado(PesquisaDTO $objPesquisaDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('pesquisa_cadastrar',__METHOD__,$objPesquisaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objPesquisaDTO, $objInfraException);
      $this->validarNumIdUsuario($objPesquisaDTO, $objInfraException);
      $this->validarNumIdUnidade($objPesquisaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objPesquisaBD = new PesquisaBD($this->getObjInfraIBanco());
      $ret = $objPesquisaBD->cadastrar($objPesquisaDTO);

      if(InfraArray::contar($objPesquisaDTO->getArrObjCampoPesquisaDTO()) > 0){
        $objCampoPesquisaRN = new CampoPesquisaRN();
        foreach ($objPesquisaDTO->getArrObjCampoPesquisaDTO() as  $objCampoPesquisaDTO){
          $objCampoPesquisaDTO->setNumIdPesquisa($objPesquisaDTO->getNumIdPesquisa());
          $objCampoPesquisaRN->cadastrar($objCampoPesquisaDTO);
        }
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Pesquisa.',$e);
    }
  }

  protected function alterarControlado(PesquisaDTO $objPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('pesquisa_alterar',__METHOD__,$objPesquisaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objPesquisaDTO->isSetStrNome()){
        $this->validarStrNome($objPesquisaDTO, $objInfraException);
      }
      if ($objPesquisaDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objPesquisaDTO, $objInfraException);
      }
      if ($objPesquisaDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objPesquisaDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objPesquisaBD = new PesquisaBD($this->getObjInfraIBanco());
      $objPesquisaBD->alterar($objPesquisaDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Pesquisa.',$e);
    }
  }

  protected function excluirControlado($arrObjPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('pesquisa_excluir',__METHOD__,$arrObjPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objCampoPesquisaRN = new CampoPesquisaRN();
      $objPesquisaBD = new PesquisaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPesquisaDTO);$i++){

        $objCampoPesquisaDTO = new CampoPesquisaDTO();
        $objCampoPesquisaDTO->retNumIdCampoPesquisa();
        $objCampoPesquisaDTO->setNumIdPesquisa($arrObjPesquisaDTO[$i]->getNumIdPesquisa());
        $objCampoPesquisaRN->excluir($objCampoPesquisaRN->listar($objCampoPesquisaDTO));

        $objPesquisaBD->excluir($arrObjPesquisaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Pesquisa.',$e);
    }
  }

  protected function consultarConectado(PesquisaDTO $objPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('pesquisa_consultar',__METHOD__,$objPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPesquisaBD = new PesquisaBD($this->getObjInfraIBanco());
      $ret = $objPesquisaBD->consultar($objPesquisaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Pesquisa.',$e);
    }
  }

  protected function listarConectado(PesquisaDTO $objPesquisaDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('pesquisa_listar',__METHOD__,$objPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPesquisaBD = new PesquisaBD($this->getObjInfraIBanco());
      $ret = $objPesquisaBD->listar($objPesquisaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Pesquisas.',$e);
    }
  }

  protected function contarConectado(PesquisaDTO $objPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('pesquisa_listar',__METHOD__,$objPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPesquisaBD = new PesquisaBD($this->getObjInfraIBanco());
      $ret = $objPesquisaBD->contar($objPesquisaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Pesquisas.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('pesquisa_desativar',__METHOD__,$arrObjPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPesquisaBD = new PesquisaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPesquisaDTO);$i++){
        $objPesquisaBD->desativar($arrObjPesquisaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Pesquisa.',$e);
    }
  }

  protected function reativarControlado($arrObjPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('pesquisa_reativar',__METHOD__,$arrObjPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPesquisaBD = new PesquisaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjPesquisaDTO);$i++){
        $objPesquisaBD->reativar($arrObjPesquisaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Pesquisa.',$e);
    }
  }

  protected function bloquearControlado(PesquisaDTO $objPesquisaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('pesquisa_consultar',__METHOD__,$objPesquisaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objPesquisaBD = new PesquisaBD($this->getObjInfraIBanco());
      $ret = $objPesquisaBD->bloquear($objPesquisaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Pesquisa.',$e);
    }
  }

 */
}
