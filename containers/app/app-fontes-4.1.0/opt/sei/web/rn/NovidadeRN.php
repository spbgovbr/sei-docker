<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/03/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class NovidadeRN extends InfraRN {

  public static $DATA_NAO_LIBERADO = '31/12/2100 00:00:00';
  
  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUsuario(NovidadeDTO $objNovidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objNovidadeDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarStrTitulo(NovidadeDTO $objNovidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objNovidadeDTO->getStrTitulo())){
      $objInfraException->adicionarValidacao('Título não informado.');
    }else{
      $objNovidadeDTO->setStrTitulo(trim($objNovidadeDTO->getStrTitulo()));

      if (strlen($objNovidadeDTO->getStrTitulo())>50){
        $objInfraException->adicionarValidacao('Título possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrDescricao(NovidadeDTO $objNovidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objNovidadeDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objNovidadeDTO->setStrDescricao(trim($objNovidadeDTO->getStrDescricao()));
    }
  }

  private function validarDthLiberacao(NovidadeDTO $objNovidadeDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objNovidadeDTO->getDthLiberacao())){
      $objNovidadeDTO->setDthLiberacao(null);
    }else{
      if (!InfraData::validarDataHora($objNovidadeDTO->getDthLiberacao())){
        $objInfraException->adicionarValidacao('Data/Hora inválida.');
      }
    }
  }

  protected function cadastrarControlado(NovidadeDTO $objNovidadeDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('novidade_cadastrar',__METHOD__,$objNovidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarStrTitulo($objNovidadeDTO, $objInfraException);
      $this->validarStrDescricao($objNovidadeDTO, $objInfraException);
      
      $objNovidadeDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objNovidadeDTO->setDthLiberacao(self::$DATA_NAO_LIBERADO);

      $objInfraException->lancarValidacoes();

      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
      $ret = $objNovidadeBD->cadastrar($objNovidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Novidade.',$e);
    }
  }

  protected function alterarControlado(NovidadeDTO $objNovidadeDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('novidade_alterar',__METHOD__,$objNovidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objNovidadeDTO->isSetStrTitulo()){
        $this->validarStrTitulo($objNovidadeDTO, $objInfraException);
      }
      if ($objNovidadeDTO->isSetStrDescricao()){
        $this->validarStrDescricao($objNovidadeDTO, $objInfraException);
      }
      
      $objNovidadeDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objNovidadeDTO->setDthLiberacao(self::$DATA_NAO_LIBERADO);

      $objInfraException->lancarValidacoes();

      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
      $objNovidadeBD->alterar($objNovidadeDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Novidade.',$e);
    }
  }

  protected function liberarControlado($arrObjNovidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('novidade_liberar',__METHOD__,$arrObjNovidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      foreach($arrObjNovidadeDTO as $objNovidadeDTO){
        $dto = new NovidadeDTO();
        $dto->retStrTitulo();
        $dto->setNumIdNovidade($objNovidadeDTO->getNumIdNovidade());
        $dto->setDthLiberacao(self::$DATA_NAO_LIBERADO, InfraDTO::$OPER_DIFERENTE);
        
        $dto = $this->consultar($dto);
        
        if ($dto != null){
          $objInfraException->adicionarValidacao('Novidade "'.$dto->getStrTitulo().'" já foi liberada.');
        }
      }
      
      $objInfraException->lancarValidacoes();

      
      $dthLiberacao = InfraData::getStrDataHoraAtual();
      
      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
        
      foreach($arrObjNovidadeDTO as $objNovidadeDTO){
        $dto = new NovidadeDTO();
        $dto->setNumIdNovidade($objNovidadeDTO->getNumIdNovidade());
        $dto->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $dto->setDthLiberacao($dthLiberacao);
        $objNovidadeBD->alterar($dto);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro liberando Novidade.',$e);
    }
  }
  
  protected function cancelarLiberacaoControlado($arrObjNovidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('novidade_cancelar_liberacao',__METHOD__,$arrObjNovidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      foreach($arrObjNovidadeDTO as $objNovidadeDTO){
        $dto = new NovidadeDTO();
        $dto->retStrTitulo();
        $dto->setNumIdNovidade($objNovidadeDTO->getNumIdNovidade());
        $dto->setDthLiberacao(self::$DATA_NAO_LIBERADO);
        
        $dto = $this->consultar($dto);
        
        if ($dto != null){
          $objInfraException->adicionarValidacao('Novidade "'.$dto->getStrTitulo().'" não consta como liberada.');
        }
      }
      
      $objInfraException->lancarValidacoes();

      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
        
      foreach($arrObjNovidadeDTO as $objNovidadeDTO){
        $dto = new NovidadeDTO();
        $dto->setNumIdNovidade($objNovidadeDTO->getNumIdNovidade());
        $dto->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $dto->setDthLiberacao(self::$DATA_NAO_LIBERADO);
        $objNovidadeBD->alterar($dto);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro cancelando liberação de Novidade.',$e);
    }
  }
  
  protected function excluirControlado($arrObjNovidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('novidade_excluir',__METHOD__,$arrObjNovidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      foreach($arrObjNovidadeDTO as $objNovidadeDTO){
        $dto = new NovidadeDTO();
        $dto->retStrTitulo();
        $dto->setNumIdNovidade($objNovidadeDTO->getNumIdNovidade());
        $dto->setDthLiberacao(self::$DATA_NAO_LIBERADO, InfraDTO::$OPER_DIFERENTE);
        
        $dto = $this->consultar($dto);
        
        if ($dto != null){
          $objInfraException->adicionarValidacao('Novidade "'.$dto->getStrTitulo().'" consta como liberada.');
        }
      }
      
      $objInfraException->lancarValidacoes();

      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjNovidadeDTO);$i++){
        $objNovidadeBD->excluir($arrObjNovidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Novidade.',$e);
    }
  }

  protected function consultarConectado(NovidadeDTO $objNovidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('novidade_consultar',__METHOD__,$objNovidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
      $ret = $objNovidadeBD->consultar($objNovidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Novidade.',$e);
    }
  }

  protected function listarConectado(NovidadeDTO $objNovidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('novidade_listar',__METHOD__,$objNovidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
      $ret = $objNovidadeBD->listar($objNovidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Novidades.',$e);
    }
  }

  protected function contarConectado(NovidadeDTO $objNovidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('novidade_listar',__METHOD__,$objNovidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
      $ret = $objNovidadeBD->contar($objNovidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Novidades.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjNovidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('novidade_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjNovidadeDTO);$i++){
        $objNovidadeBD->desativar($arrObjNovidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Novidade.',$e);
    }
  }

  protected function reativarControlado($arrObjNovidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('novidade_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjNovidadeDTO);$i++){
        $objNovidadeBD->reativar($arrObjNovidadeDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Novidade.',$e);
    }
  }

  protected function bloquearControlado(NovidadeDTO $objNovidadeDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('novidade_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objNovidadeBD = new NovidadeBD($this->getObjInfraIBanco());
      $ret = $objNovidadeBD->bloquear($objNovidadeDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Novidade.',$e);
    }
  }

 */
}
?>