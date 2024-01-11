<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/11/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtributoAndamentoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdAtividadeRN1360(AtributoAndamentoDTO $objAtributoAndamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoAndamentoDTO->getNumIdAtividade())){
      $objInfraException->adicionarValidacao('Andamento não informado.');
    }
  }

  private function validarStrNomeRN1361(AtributoAndamentoDTO $objAtributoAndamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoAndamentoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objAtributoAndamentoDTO->setStrNome(trim($objAtributoAndamentoDTO->getStrNome()));

      if (strlen($objAtributoAndamentoDTO->getStrNome())>500){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrValorRN1362(AtributoAndamentoDTO $objAtributoAndamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoAndamentoDTO->getStrValor())){
      $objAtributoAndamentoDTO->setStrValor(null);
    }else{
      $objAtributoAndamentoDTO->setStrValor(trim($objAtributoAndamentoDTO->getStrValor()));

      if (strlen($objAtributoAndamentoDTO->getStrValor())>500){
        $objInfraException->adicionarValidacao('Valor possui tamanho superior a 500 caracteres.');
      }
    }
  }

  private function validarStrIdOrigemRN1360(AtributoAndamentoDTO $objAtributoAndamentoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoAndamentoDTO->getStrIdOrigem())){
      $objAtributoAndamentoDTO->setStrIdOrigem(null);
    }else{
      $objAtributoAndamentoDTO->setStrIdOrigem(trim($objAtributoAndamentoDTO->getStrIdOrigem()));

      if (strlen($objAtributoAndamentoDTO->getStrIdOrigem())>50){
        $objInfraException->adicionarValidacao('ID de origem possui tamanho superior a 50 caracteres.');
      }
    }
  }
  
  protected function cadastrarRN1363Controlado(AtributoAndamentoDTO $objAtributoAndamentoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andamento_cadastrar',__METHOD__,$objAtributoAndamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdAtividadeRN1360($objAtributoAndamentoDTO, $objInfraException);
      $this->validarStrNomeRN1361($objAtributoAndamentoDTO, $objInfraException);
      $this->validarStrValorRN1362($objAtributoAndamentoDTO, $objInfraException);
      $this->validarStrIdOrigemRN1360($objAtributoAndamentoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAtributoAndamentoBD = new AtributoAndamentoBD($this->getObjInfraIBanco());
      $ret = $objAtributoAndamentoBD->cadastrar($objAtributoAndamentoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Atributo de Andamento.',$e);
    }
  }

  protected function alterarRN1364Controlado(AtributoAndamentoDTO $objAtributoAndamentoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andamento_alterar',__METHOD__,$objAtributoAndamentoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAtributoAndamentoDTO->isSetNumIdAtividade()){
        $this->validarNumIdAtividadeRN1360($objAtributoAndamentoDTO, $objInfraException);
      }
      if ($objAtributoAndamentoDTO->isSetStrNome()){
        $this->validarStrNomeRN1361($objAtributoAndamentoDTO, $objInfraException);
      }
      if ($objAtributoAndamentoDTO->isSetStrValor()){
        $this->validarStrValorRN1362($objAtributoAndamentoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAtributoAndamentoBD = new AtributoAndamentoBD($this->getObjInfraIBanco());
      $objAtributoAndamentoBD->alterar($objAtributoAndamentoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Atributo de Andamento.',$e);
    }
  }

  protected function excluirRN1365Controlado($arrObjAtributoAndamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andamento_excluir',__METHOD__,$arrObjAtributoAndamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamentoBD = new AtributoAndamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAtributoAndamentoDTO);$i++){
        $objAtributoAndamentoBD->excluir($arrObjAtributoAndamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Atributo de Andamento.',$e);
    }
  }

  protected function consultarRN1366Conectado(AtributoAndamentoDTO $objAtributoAndamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andamento_consultar',__METHOD__,$objAtributoAndamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamentoBD = new AtributoAndamentoBD($this->getObjInfraIBanco());
      $ret = $objAtributoAndamentoBD->consultar($objAtributoAndamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Atributo de Andamento.',$e);
    }
  }

  protected function listarRN1367Conectado(AtributoAndamentoDTO $objAtributoAndamentoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andamento_listar',__METHOD__,$objAtributoAndamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamentoBD = new AtributoAndamentoBD($this->getObjInfraIBanco());
      $ret = $objAtributoAndamentoBD->listar($objAtributoAndamentoDTO);
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Atributos de Andamento.',$e);
    }
  }

  protected function contarRN1368Conectado(AtributoAndamentoDTO $objAtributoAndamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andamento_listar',__METHOD__,$objAtributoAndamentoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamentoBD = new AtributoAndamentoBD($this->getObjInfraIBanco());
      $ret = $objAtributoAndamentoBD->contar($objAtributoAndamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Atributos de Andamento.',$e);
    }
  }
/* 
  protected function desativarRN1369Controlado($arrObjAtributoAndamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andamento_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamentoBD = new AtributoAndamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAtributoAndamentoDTO);$i++){
        $objAtributoAndamentoBD->desativar($arrObjAtributoAndamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Atributo de Andamento.',$e);
    }
  }

  protected function reativarRN1370Controlado($arrObjAtributoAndamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andamento_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamentoBD = new AtributoAndamentoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAtributoAndamentoDTO);$i++){
        $objAtributoAndamentoBD->reativar($arrObjAtributoAndamentoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Atributo de Andamento.',$e);
    }
  }

  protected function bloquearRN1371Controlado(AtributoAndamentoDTO $objAtributoAndamentoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_andamento_consultar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoAndamentoBD = new AtributoAndamentoBD($this->getObjInfraIBanco());
      $ret = $objAtributoAndamentoBD->bloquear($objAtributoAndamentoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Atributo de Andamento.',$e);
    }
  }

 */
}
?>