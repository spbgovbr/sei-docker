<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AssinanteRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarStrCargoFuncao(AssinanteDTO $objAssinanteDTO, InfraException $objInfraException){
  	if (InfraString::isBolVazia($objAssinanteDTO->getStrCargoFuncao())){
      $objInfraException->adicionarValidacao('Cargo/Função não informado.');
    }else{
    	$objAssinanteDTO->setStrCargoFuncao(trim($objAssinanteDTO->getStrCargoFuncao()));
      
      if (strlen($objAssinanteDTO->getStrCargoFuncao())>200){
        $objInfraException->adicionarValidacao('Cargo/Função possui tamanho superior a 200 caracteres.');
      }
      
      $dto = new AssinanteDTO();
      $dto->setNumMaxRegistrosRetorno(1);
      $dto->retStrCargoFuncao();
      $dto->retStrSiglaOrgao();
      $dto->setNumIdAssinante($objAssinanteDTO->getNumIdAssinante(),InfraDTO::$OPER_DIFERENTE);
      $dto->setNumIdOrgao($objAssinanteDTO->getNumIdOrgao());
      $dto->setStrCargoFuncao($objAssinanteDTO->getStrCargoFuncao());
          
      if (($dto = $this->consultarRN1338($dto))!=null){
        $objInfraException->adicionarValidacao('Já existe uma assinatura cadastrada com cargo/função "'.$dto->getStrCargoFuncao().'" no órgão '.$dto->getStrSiglaOrgao().'.');
      }
    }
  }

  private function validarNumIdOrgao(AssinanteDTO $objAssinanteDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAssinanteDTO->getNumIdOrgao())){
      $objInfraException->adicionarValidacao('Órgão não informado.');
    }
  }

  private function validarArrObjRelAssinanteUnidadeDTO(AssinanteDTO $objAssinanteDTO, InfraException $objInfraException){
    if (InfraArray::contar($objAssinanteDTO->getArrObjRelAssinanteUnidadeDTO())){
      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->retStrSigla();
      $objUnidadeDTO->setNumIdUnidade(InfraArray::converterArrInfraDTO($objAssinanteDTO->getArrObjRelAssinanteUnidadeDTO(),'IdUnidade'),InfraDTO::$OPER_IN);
      $objUnidadeDTO->setNumIdOrgao($objAssinanteDTO->getNumIdOrgao(), InfraDTO::$OPER_DIFERENTE);

      $objUnidadeRN = new UnidadeRN();
      $arrObjUnidadeDTO = $objUnidadeRN->listarRN0127($objUnidadeDTO);
      $numUnidades = count($arrObjUnidadeDTO);
      if ($numUnidades == 1){
        $objInfraException->adicionarValidacao('A unidade '.$arrObjUnidadeDTO[0]->getStrSigla().' não pertence ao órgão da assinatura.');
      }else if ($numUnidades){
        $objInfraException->adicionarValidacao("Unidades não pertencem ao órgão da assinatura: \n".implode("\n", InfraArray::converterArrInfraDTO($arrObjUnidadeDTO,'Sigla')));
      }
    }
  }

  protected function cadastrarRN1335Controlado(AssinanteDTO $objAssinanteDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinante_cadastrar',__METHOD__,$objAssinanteDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdOrgao($objAssinanteDTO, $objInfraException);
      $this->validarStrCargoFuncao($objAssinanteDTO, $objInfraException);
      $this->validarArrObjRelAssinanteUnidadeDTO($objAssinanteDTO, $objInfraException);
      
      $objInfraException->lancarValidacoes();

      $objAssinanteBD = new AssinanteBD($this->getObjInfraIBanco());
      $ret = $objAssinanteBD->cadastrar($objAssinanteDTO);

      $objRelAssinanteUnidadeRN = new RelAssinanteUnidadeRN();
      $arrObjRelAssinanteUnidadeDTO = $objAssinanteDTO->getArrObjRelAssinanteUnidadeDTO();
      foreach($arrObjRelAssinanteUnidadeDTO as $objRelAssinanteUnidadeDTO){
        $objRelAssinanteUnidadeDTO->setNumIdAssinante($ret->getNumIdAssinante());
        $objRelAssinanteUnidadeRN->cadastrarRN1376($objRelAssinanteUnidadeDTO);
      }
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Assinante da Unidade.',$e);
    }
  }

  protected function alterarRN1336Controlado(AssinanteDTO $objAssinanteDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('assinante_alterar',__METHOD__,$objAssinanteDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objAssinanteDTOBanco = new AssinanteDTO();
      $objAssinanteDTOBanco->retNumIdOrgao();
      $objAssinanteDTOBanco->setNumIdAssinante($objAssinanteDTO->getNumIdAssinante());
      $objAssinanteDTOBanco = $this->consultarRN1338($objAssinanteDTOBanco);

      if ($objAssinanteDTO->isSetNumIdOrgao()){
        if ($objAssinanteDTO->getNumIdOrgao()!=$objAssinanteDTOBanco->getNumIdOrgao()){
          $objInfraException->lancarValidacao('Não é possível alterar o órgão de uma assinatura.');
        }
      }else{
        $objAssinanteDTO->setNumIdOrgao($objAssinanteDTOBanco->getNumIdOrgao());
      }

      if ($objAssinanteDTO->isSetStrCargoFuncao()){
        $this->validarStrCargoFuncao($objAssinanteDTO, $objInfraException);
      }

      if ($objAssinanteDTO->isSetArrObjRelAssinanteUnidadeDTO()) {
        $this->validarArrObjRelAssinanteUnidadeDTO($objAssinanteDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAssinanteBD = new AssinanteBD($this->getObjInfraIBanco());
      $objAssinanteBD->alterar($objAssinanteDTO);

      if ($objAssinanteDTO->isSetArrObjRelAssinanteUnidadeDTO()) {

        $objRelAssinanteUnidadeDTO = new RelAssinanteUnidadeDTO();
        $objRelAssinanteUnidadeDTO->retNumIdAssinante();
        $objRelAssinanteUnidadeDTO->retNumIdUnidade();
        $objRelAssinanteUnidadeDTO->setNumIdAssinante($objAssinanteDTO->getNumIdAssinante());

        $objRelAssinanteUnidadeRN = new RelAssinanteUnidadeRN();
        $objRelAssinanteUnidadeRN->excluirRN1378($objRelAssinanteUnidadeRN->listarRN1380($objRelAssinanteUnidadeDTO));

        $arrObjRelAssinanteUnidadeDTO = $objAssinanteDTO->getArrObjRelAssinanteUnidadeDTO();
        foreach ($arrObjRelAssinanteUnidadeDTO as $objRelAssinanteUnidadeDTO) {
          $objRelAssinanteUnidadeDTO->setNumIdAssinante($objAssinanteDTO->getNumIdAssinante());
          $objRelAssinanteUnidadeRN->cadastrarRN1376($objRelAssinanteUnidadeDTO);
        }
      }
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Assinante da Unidade.',$e);
    }
  }

  protected function excluirRN1337Controlado($arrObjAssinanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinante_excluir',__METHOD__,$arrObjAssinanteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      
      $objRelAssinanteUnidadeDTO = new RelAssinanteUnidadeDTO();
      $objRelAssinanteUnidadeDTO->retNumIdAssinante();
      $objRelAssinanteUnidadeDTO->retNumIdUnidade();
      
      $objRelAssinanteUnidadeRN = new RelAssinanteUnidadeRN();
      
      $objAssinanteBD = new AssinanteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAssinanteDTO);$i++){
        
        $objRelAssinanteUnidadeDTO->setNumIdAssinante($arrObjAssinanteDTO[$i]->getNumIdAssinante());
        $objRelAssinanteUnidadeRN->excluirRN1378($objRelAssinanteUnidadeRN->listarRN1380($objRelAssinanteUnidadeDTO));
        
        $objAssinanteBD->excluir($arrObjAssinanteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Assinante da Unidade.',$e);
    }
  }

  protected function consultarRN1338Conectado(AssinanteDTO $objAssinanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinante_consultar',__METHOD__,$objAssinanteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssinanteBD = new AssinanteBD($this->getObjInfraIBanco());
      $ret = $objAssinanteBD->consultar($objAssinanteDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Assinante da Unidade.',$e);
    }
  }

  protected function listarRN1339Conectado(AssinanteDTO $objAssinanteDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinante_listar',__METHOD__,$objAssinanteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssinanteBD = new AssinanteBD($this->getObjInfraIBanco());
      $ret = $objAssinanteBD->listar($objAssinanteDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Assinantes da Unidade.',$e);
    }
  }

  protected function contarRN1340Conectado(AssinanteDTO $objAssinanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinante_listar',__METHOD__,$objAssinanteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssinanteBD = new AssinanteBD($this->getObjInfraIBanco());
      $ret = $objAssinanteBD->contar($objAssinanteDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Assinantes da Unidade.',$e);
    }
  }


  protected function pesquisarConectado(AssinanteDTO $objAssinanteDTO){
    try {

      //Valida Permissao
      /////////////////////////////////////////////////////////////////
      SessaoSEI::getInstance()->validarAuditarPermissao('assinante_listar',__METHOD__,$objAssinanteDTO);
      /////////////////////////////////////////////////////////////////

      $objAssinanteDTO = InfraString::tratarPalavrasPesquisaDTO($objAssinanteDTO,"CargoFuncao");

      if ($objAssinanteDTO->isSetNumIdUnidade() && !InfraString::isBolVazia($objAssinanteDTO->getNumIdUnidade())){

        $objRelAssinanteUnidadeDTO = new RelAssinanteUnidadeDTO();
        $objRelAssinanteUnidadeDTO->retNumIdAssinante();
        $objRelAssinanteUnidadeDTO->setNumIdUnidade($objAssinanteDTO->getNumIdUnidade());

        $objRelAssinanteUnidadeRN = new RelAssinanteUnidadeRN();
        $arrObjRelAssinanteUnidadeDTO = $objRelAssinanteUnidadeRN->listarRN1380($objRelAssinanteUnidadeDTO);

        if (count($arrObjRelAssinanteUnidadeDTO)){
          $objAssinanteDTO->setNumIdAssinante(InfraArray::converterArrInfraDTO($arrObjRelAssinanteUnidadeDTO,'IdAssinante'), InfraDTO::$OPER_IN);
        }else{
          return array();
        }
      }

      return $this->listarRN1339($objAssinanteDTO);

    }catch(Exception $e){
      throw new InfraException('Erro pesquisando Usuários.',$e);
    }
  }

/* 
  protected function desativarRN1341Controlado($arrObjAssinanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinante_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssinanteBD = new AssinanteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAssinanteDTO);$i++){
        $objAssinanteBD->desativar($arrObjAssinanteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Assinante da Unidade.',$e);
    }
  }

  protected function reativarRN1342Controlado($arrObjAssinanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinante_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssinanteBD = new AssinanteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAssinanteDTO);$i++){
        $objAssinanteBD->reativar($arrObjAssinanteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Assinante da Unidade.',$e);
    }
  }

  protected function bloquearRN1343Controlado(AssinanteDTO $objAssinanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('assinante_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAssinanteBD = new AssinanteBD($this->getObjInfraIBanco());
      $ret = $objAssinanteBD->bloquear($objAssinanteDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Assinante da Unidade.',$e);
    }
  }

 */
}
?>