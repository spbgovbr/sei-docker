<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/05/2019 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtributoInstalacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdAndamentoInstalacao(AtributoInstalacaoDTO $objAtributoInstalacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoInstalacaoDTO->getNumIdAndamentoInstalacao())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarStrNome(AtributoInstalacaoDTO $objAtributoInstalacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoInstalacaoDTO->getStrNome())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objAtributoInstalacaoDTO->setStrNome(trim($objAtributoInstalacaoDTO->getStrNome()));

      if (strlen($objAtributoInstalacaoDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrValor(AtributoInstalacaoDTO $objAtributoInstalacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoInstalacaoDTO->getStrValor())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      $objAtributoInstalacaoDTO->setStrValor(trim($objAtributoInstalacaoDTO->getStrValor()));

      if (strlen($objAtributoInstalacaoDTO->getStrValor())>4000){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 4000 caracteres.');
      }
    }
  }

  private function validarStrIdOrigem(AtributoInstalacaoDTO $objAtributoInstalacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAtributoInstalacaoDTO->getStrIdOrigem())){
      $objAtributoInstalacaoDTO->setStrIdOrigem(null);
    }else{
      $objAtributoInstalacaoDTO->setStrIdOrigem(trim($objAtributoInstalacaoDTO->getStrIdOrigem()));

      if (strlen($objAtributoInstalacaoDTO->getStrIdOrigem())>50){
        $objInfraException->adicionarValidacao(' possui tamanho superior a 50 caracteres.');
      }
    }
  }

  protected function cadastrarControlado(AtributoInstalacaoDTO $objAtributoInstalacaoDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_instalacao_cadastrar',__METHOD__,$objAtributoInstalacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdAndamentoInstalacao($objAtributoInstalacaoDTO, $objInfraException);
      $this->validarStrNome($objAtributoInstalacaoDTO, $objInfraException);
      $this->validarStrValor($objAtributoInstalacaoDTO, $objInfraException);
      $this->validarStrIdOrigem($objAtributoInstalacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objAtributoInstalacaoBD = new AtributoInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objAtributoInstalacaoBD->cadastrar($objAtributoInstalacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando .',$e);
    }
  }

  protected function alterarControlado(AtributoInstalacaoDTO $objAtributoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_instalacao_alterar',__METHOD__,$objAtributoInstalacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAtributoInstalacaoDTO->isSetNumIdAndamentoInstalacao()){
        $this->validarNumIdAndamentoInstalacao($objAtributoInstalacaoDTO, $objInfraException);
      }
      if ($objAtributoInstalacaoDTO->isSetStrNome()){
        $this->validarStrNome($objAtributoInstalacaoDTO, $objInfraException);
      }
      if ($objAtributoInstalacaoDTO->isSetStrValor()){
        $this->validarStrValor($objAtributoInstalacaoDTO, $objInfraException);
      }
      if ($objAtributoInstalacaoDTO->isSetStrIdOrigem()){
        $this->validarStrIdOrigem($objAtributoInstalacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAtributoInstalacaoBD = new AtributoInstalacaoBD($this->getObjInfraIBanco());
      $objAtributoInstalacaoBD->alterar($objAtributoInstalacaoDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }

  protected function excluirControlado($arrObjAtributoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_instalacao_excluir',__METHOD__,$arrObjAtributoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoInstalacaoBD = new AtributoInstalacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAtributoInstalacaoDTO);$i++){
        $objAtributoInstalacaoBD->excluir($arrObjAtributoInstalacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(AtributoInstalacaoDTO $objAtributoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_instalacao_consultar',__METHOD__,$objAtributoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoInstalacaoBD = new AtributoInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objAtributoInstalacaoBD->consultar($objAtributoInstalacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(AtributoInstalacaoDTO $objAtributoInstalacaoDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_instalacao_listar',__METHOD__,$objAtributoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoInstalacaoBD = new AtributoInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objAtributoInstalacaoBD->listar($objAtributoInstalacaoDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(AtributoInstalacaoDTO $objAtributoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_instalacao_listar',__METHOD__,$objAtributoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoInstalacaoBD = new AtributoInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objAtributoInstalacaoBD->contar($objAtributoInstalacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjAtributoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_instalacao_desativar',__METHOD__,$arrObjAtributoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoInstalacaoBD = new AtributoInstalacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAtributoInstalacaoDTO);$i++){
        $objAtributoInstalacaoBD->desativar($arrObjAtributoInstalacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjAtributoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_instalacao_reativar',__METHOD__,$arrObjAtributoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoInstalacaoBD = new AtributoInstalacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAtributoInstalacaoDTO);$i++){
        $objAtributoInstalacaoBD->reativar($arrObjAtributoInstalacaoDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(AtributoInstalacaoDTO $objAtributoInstalacaoDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('atributo_instalacao_consultar',__METHOD__,$objAtributoInstalacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAtributoInstalacaoBD = new AtributoInstalacaoBD($this->getObjInfraIBanco());
      $ret = $objAtributoInstalacaoBD->bloquear($objAtributoInstalacaoDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
