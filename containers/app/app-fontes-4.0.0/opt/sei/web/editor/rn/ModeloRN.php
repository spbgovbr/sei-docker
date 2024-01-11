<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/11/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: ModeloRN.php 10198 2015-08-04 17:56:36Z mga $
*/

require_once dirname(__FILE__).'/../../SEI.php';

class ModeloRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrNome(ModeloDTO $objModeloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objModeloDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objModeloDTO->setStrNome(trim($objModeloDTO->getStrNome()));

      if (strlen($objModeloDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
    }

    $dto = new ModeloDTO();
    $dto->retStrSinAtivo();
    $dto->setNumIdModelo($objModeloDTO->getNumIdModelo(),InfraDTO::$OPER_DIFERENTE);
    $dto->setStrNome($objModeloDTO->getStrNome(),InfraDTO::$OPER_IGUAL);
    $dto->setBolExclusaoLogica(false);

    $dto = $this->consultar($dto);
    if ($dto != NULL){
      if ($dto->getStrSinAtivo() == 'S') {
        $objInfraException->adicionarValidacao('Existe outro Modelo que utiliza o mesmo Nome.');
      }else {
        $objInfraException->adicionarValidacao('Existe um Modelo inativo que utiliza o mesmo Nome.');
      }
    }
  }

  private function validarStrSinAtivo(ModeloDTO $objModeloDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objModeloDTO->getStrSinAtivo())){
      $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objModeloDTO->getStrSinAtivo())){
        $objInfraException->adicionarValidacao('Sinalizador de Exclusão Lógica inválido.');
      }
    }
  }

  protected function cadastrarControlado(ModeloDTO $objModeloDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('modelo_cadastrar',__METHOD__,$objModeloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrNome($objModeloDTO, $objInfraException);
      $this->validarStrSinAtivo($objModeloDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objModeloBD = new ModeloBD($this->getObjInfraIBanco());
      $ret = $objModeloBD->cadastrar($objModeloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Modelo.',$e);
    }
  }

  protected function clonarControlado(ClonarModeloDTO $objClonarModeloDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('modelo_clonar',__METHOD__, $objClonarModeloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objClonarModeloDTO->getNumIdModeloOrigem())){
        $objInfraException->adicionarValidacao('Modelo de Origem não informado.');
      }

      if (InfraString::isBolVazia($objClonarModeloDTO->getStrNomeDestino())){
        $objInfraException->adicionarValidacao('Nome de Destino não informado.');
      }

      $objInfraException->lancarValidacoes();
      
      $objModeloDTO = new ModeloDTO();
      $objModeloDTO->retTodos();
      $objModeloDTO->setNumIdModelo($objClonarModeloDTO->getNumIdModeloOrigem());
      $objModeloDTO = $this->consultar($objModeloDTO);
      
      $objModeloDTO->setNumIdModelo(null);
      $objModeloDTO->setStrNome($objClonarModeloDTO->getStrNomeDestino());
      
      $objModeloDTO = $this->cadastrar($objModeloDTO);
      $idModeloNovo=$objModeloDTO->getNumIdModelo();
      
      //modelo clonado, clonar seções com seus estilos    
      $objSecaoModeloDTO = new SecaoModeloDTO();
      $objSecaoModeloDTO->retTodos();
      $objSecaoModeloDTO->setNumIdModelo($objClonarModeloDTO->getNumIdModeloOrigem());
      $objSecaoModeloDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
      
      $objSecaoModeloRN = new SecaoModeloRN();
      $arrObjSecaoModeloDTO = $objSecaoModeloRN->listar($objSecaoModeloDTO);
      
      foreach($arrObjSecaoModeloDTO as $objSecaoModeloDTO){
      	$objSecaoModeloDTO->setNumIdModelo($idModeloNovo);
      	$objRelSecaoModeloEstiloDTO = new RelSecaoModeloEstiloDTO();
      	$objRelSecaoModeloEstiloDTO->retTodos();
      	$objRelSecaoModeloEstiloDTO->setNumIdSecaoModelo($objSecaoModeloDTO->getNumIdSecaoModelo());
      	$objRelSecaoModeloEstiloRN = new RelSecaoModeloEstiloRN();
        $arrObjRelSecaoModeloEstiloDTO = $objRelSecaoModeloEstiloRN->listar($objRelSecaoModeloEstiloDTO);
      	$objSecaoModeloDTO->setArrObjRelSecaoModeloEstiloDTO($arrObjRelSecaoModeloEstiloDTO);
      	
      	$objSecaoModeloRN->cadastrar($objSecaoModeloDTO);
       }

      //Auditoria

      return $objModeloDTO;

    }catch(Exception $e){
      throw new InfraException('Erro clonando Modelo.',$e);
    }
  }

  protected function alterarControlado(ModeloDTO $objModeloDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('modelo_alterar',__METHOD__,$objModeloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objModeloDTO->isSetStrNome()){
        $this->validarStrNome($objModeloDTO, $objInfraException);
      }
      if ($objModeloDTO->isSetStrSinAtivo()){
        $this->validarStrSinAtivo($objModeloDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objModeloBD = new ModeloBD($this->getObjInfraIBanco());
      $objModeloBD->alterar($objModeloDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Modelo.',$e);
    }
  }

  protected function excluirControlado($arrObjModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('modelo_excluir',__METHOD__,$arrObjModeloDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      
      $objSerieRN = new SerieRN();
      
      foreach ($arrObjModeloDTO as $objModeloDTO) {

        $objSerieDTO = new SerieDTO();
        $objSerieDTO->setBolExclusaoLogica(false);
        $objSerieDTO->retStrNome();
      	$objSerieDTO->setNumIdModelo($objModeloDTO->getNumIdModelo());
      	
      	if($objSerieRN->contarRN0647($objSerieDTO)){
      	  
      	  $dto = new ModeloDTO();
          $dto->setBolExclusaoLogica(false);
      	  $dto->retStrNome();
      	  $dto->setNumIdModelo($objModeloDTO->getNumIdModelo());
      	  $dto = $this->consultar($dto);

          if ($dto==null){
            throw new InfraException('Modelo não encontrado para exclusão.');
          }
      	  
      		$objInfraException->adicionarValidacao('Existem tipos de documento utilizando o modelo "'.$dto->getStrNome().'".');
      	}
      }
      
      $objInfraException->lancarValidacoes();


      $objModeloBD = new ModeloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjModeloDTO);$i++){

        $objSecaoModeloDTO = new SecaoModeloDTO();
        $objSecaoModeloDTO->setBolExclusaoLogica(false);
        $objSecaoModeloDTO->retNumIdSecaoModelo();
        $objSecaoModeloDTO->setNumIdModelo($arrObjModeloDTO[$i]->getNumIdModelo());

        $objSecaoModeloRN = new SecaoModeloRN();
        $objSecaoModeloRN->excluir($objSecaoModeloRN->listar($objSecaoModeloDTO));

        $objModeloBD->excluir($arrObjModeloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Modelo.',$e);
    }
  }

  protected function consultarConectado(ModeloDTO $objModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('modelo_consultar',__METHOD__,$objModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objModeloBD = new ModeloBD($this->getObjInfraIBanco());
      $ret = $objModeloBD->consultar($objModeloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Modelo.',$e);
    }
  }

  protected function listarConectado(ModeloDTO $objModeloDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('modelo_listar',__METHOD__,$objModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objModeloBD = new ModeloBD($this->getObjInfraIBanco());
      $ret = $objModeloBD->listar($objModeloDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Modelos.',$e);
    }
  }

  protected function contarConectado(ModeloDTO $objModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('modelo_listar',__METHOD__,$objModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objModeloBD = new ModeloBD($this->getObjInfraIBanco());
      $ret = $objModeloBD->contar($objModeloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Modelos.',$e);
    }
  }

  protected function desativarControlado($arrObjModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('modelo_desativar',__METHOD__,$arrObjModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objModeloBD = new ModeloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjModeloDTO);$i++){
        $objModeloBD->desativar($arrObjModeloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Modelo.',$e);
    }
  }

  protected function reativarControlado($arrObjModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('modelo_reativar',__METHOD__,$arrObjModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objModeloBD = new ModeloBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjModeloDTO);$i++){
        $objModeloBD->reativar($arrObjModeloDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Modelo.',$e);
    }
  }

  protected function bloquearControlado(ModeloDTO $objModeloDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('modelo_consultar',__METHOD__,$objModeloDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objModeloBD = new ModeloBD($this->getObjInfraIBanco());
      $ret = $objModeloBD->bloquear($objModeloDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Modelo.',$e);
    }
  }


}
?>