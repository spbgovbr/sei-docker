<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class SituacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(SituacaoDTO $objSituacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSituacaoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objSituacaoDTO->setStrNome(trim($objSituacaoDTO->getStrNome()));

      if (strlen($objSituacaoDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }

      $dto=new SituacaoDTO();
      $dto->setNumIdSituacao($objSituacaoDTO->getNumIdSituacao(),InfraDTO::$OPER_DIFERENTE);
      $dto->setStrNome($objSituacaoDTO->getStrNome());
      $dto->setBolExclusaoLogica(false);
      $dto->retStrSinAtivo();
      $dto=$this->consultar($dto);
      if ($dto!=null) {
        if ($dto->getStrSinAtivo()=='S') {
          $objInfraException->adicionarValidacao('Existe outra ocorrência de Ponto de Controle com este Nome.');
        } else {
          $objInfraException->adicionarValidacao('Existe ocorrência inativa de Ponto de Controle com este Nome.');
        }
      }
    }
  }

  private function validarStrDescricao(SituacaoDTO $objSituacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSituacaoDTO->getStrDescricao())){
      $objSituacaoDTO->setStrDescricao(null);
    }else{
      $objSituacaoDTO->setStrDescricao(trim($objSituacaoDTO->getStrDescricao()));

      if (strlen($objSituacaoDTO->getStrDescricao())>250){
        $objInfraException->adicionarValidacao('Descrição possui tamanho superior a 250 caracteres.');
      }
    }
  }

  private function validarStrSinAtivo(SituacaoDTO $objSituacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objSituacaoDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objSituacaoDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(SituacaoDTO $objSituacaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('situacao_cadastrar',__METHOD__,$objSituacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objSituacaoDTO, $objInfraException);
      $this->validarStrDescricao($objSituacaoDTO, $objInfraException);
      $this->validarStrSinAtivo($objSituacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objSituacaoBD = new SituacaoBD($this->getObjInfraIBanco());
      $ret = $objSituacaoBD->cadastrar($objSituacaoDTO);

      $objRelSituacaoUnidadeRN = new RelSituacaoUnidadeRN();
      $arrObjRelSituacaoUnidadeDTO = $objSituacaoDTO->getArrObjRelSituacaoUnidadeDTO();
      foreach($arrObjRelSituacaoUnidadeDTO as $objRelSituacaoUnidadeDTO){
        $objRelSituacaoUnidadeDTO->setNumIdSituacao($ret->getNumIdSituacao());
        $objRelSituacaoUnidadeRN->cadastrar($objRelSituacaoUnidadeDTO);
      }


      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Ponto de Controle.',$e);
    }
  }

  protected function alterarControlado(SituacaoDTO $objSituacaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('situacao_alterar',__METHOD__,$objSituacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $dto=new SituacaoDTO();
      $dto->setBolExclusaoLogica(false);
      $dto->setNumIdSituacao($objSituacaoDTO->getNumIdSituacao());
      $dto->retStrNome();
      $dto->retNumIdSituacao();
      $dto=$this->consultar($dto);

      if (!$objSituacaoDTO->isSetStrNome()){
        $objSituacaoDTO->setStrNome($dto->getStrNome());
      }
      $this->validarStrNome($objSituacaoDTO, $objInfraException);

      if ($objSituacaoDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objSituacaoDTO, $objInfraException);
      }
      if ($objSituacaoDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objSituacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objSituacaoBD = new SituacaoBD($this->getObjInfraIBanco());
      $objSituacaoBD->alterar($objSituacaoDTO);

      $objRelSituacaoUnidadeRN = new RelSituacaoUnidadeRN();

      $objRelSituacaoUnidadeDTO = new RelSituacaoUnidadeDTO();
      $objRelSituacaoUnidadeDTO->retNumIdSituacao();
      $objRelSituacaoUnidadeDTO->retNumIdUnidade();
      $objRelSituacaoUnidadeDTO->setNumIdSituacao($objSituacaoDTO->getNumIdSituacao());
      $objRelSituacaoUnidadeRN->excluir($objRelSituacaoUnidadeRN->listar($objRelSituacaoUnidadeDTO));

      $arrObjRelSituacaoUnidadeDTO = $objSituacaoDTO->getArrObjRelSituacaoUnidadeDTO();
      foreach($arrObjRelSituacaoUnidadeDTO as $objRelSituacaoUnidadeDTO){
        $objRelSituacaoUnidadeDTO->setNumIdSituacao($objSituacaoDTO->getNumIdSituacao());
        $objRelSituacaoUnidadeRN->cadastrar($objRelSituacaoUnidadeDTO);
      }
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Ponto de Controle.',$e);
    }
  }

  protected function excluirControlado($arrObjSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('situacao_excluir',__METHOD__,$arrObjSituacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();


      $objAndamentoSituacaoRN=new AndamentoSituacaoRN();
      $objRelSituacaoUnidadeRN = new RelSituacaoUnidadeRN();


      $objSituacaoBD = new SituacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSituacaoDTO);$i++){

        $objAndamentoSituacaoDTO=new AndamentoSituacaoDTO();
        $objAndamentoSituacaoDTO->retDblIdProcedimento();
        $objAndamentoSituacaoDTO->setNumIdSituacao($arrObjSituacaoDTO[$i]->getNumIdSituacao());
        $objAndamentoSituacaoDTO->setNumMaxRegistrosRetorno(1);

        if ($objAndamentoSituacaoRN->consultar($objAndamentoSituacaoDTO) != null){
          $objInfraException->lancarValidacao('Ponto de Controle foi utilizado em um ou mais processos.');
        }

        $objRelSituacaoUnidadeDTO = new RelSituacaoUnidadeDTO();
        $objRelSituacaoUnidadeDTO->retNumIdSituacao();
        $objRelSituacaoUnidadeDTO->retNumIdUnidade();
        $objRelSituacaoUnidadeDTO->setNumIdSituacao($arrObjSituacaoDTO[$i]->getNumIdSituacao());
        $objRelSituacaoUnidadeRN->excluir($objRelSituacaoUnidadeRN->listar($objRelSituacaoUnidadeDTO));
        $objSituacaoBD->excluir($arrObjSituacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Ponto de Controle.',$e);
    }
  }

  protected function consultarConectado(SituacaoDTO $objSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('situacao_consultar',__METHOD__,$objSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSituacaoBD = new SituacaoBD($this->getObjInfraIBanco());
      $ret = $objSituacaoBD->consultar($objSituacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Ponto de Controle.',$e);
    }
  }

  protected function listarConectado(SituacaoDTO $objSituacaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('situacao_listar',__METHOD__,$objSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSituacaoBD = new SituacaoBD($this->getObjInfraIBanco());
      $ret = $objSituacaoBD->listar($objSituacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Pontos de Controle.',$e);
    }
  }

  protected function contarConectado(SituacaoDTO $objSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('situacao_listar',__METHOD__,$objSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSituacaoBD = new SituacaoBD($this->getObjInfraIBanco());
      $ret = $objSituacaoBD->contar($objSituacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Pontos de Controle.',$e);
    }
  }

  protected function desativarControlado($arrObjSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('situacao_desativar',__METHOD__,$arrObjSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSituacaoBD = new SituacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSituacaoDTO);$i++){
        $objSituacaoBD->desativar($arrObjSituacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Ponto de Controle.',$e);
    }
  }

  protected function reativarControlado($arrObjSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('situacao_reativar',__METHOD__,$arrObjSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSituacaoBD = new SituacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjSituacaoDTO);$i++){
        $objSituacaoBD->reativar($arrObjSituacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Ponto de Controle.',$e);
    }
  }

  protected function bloquearControlado(SituacaoDTO $objSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('situacao_consultar',__METHOD__,$objSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objSituacaoBD = new SituacaoBD($this->getObjInfraIBanco());
      $ret = $objSituacaoBD->bloquear($objSituacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Ponto de Controle.',$e);
    }
  }
}
?>