<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 12/06/2014 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../Sip.php';

class ServidorAutenticacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSip::getInstance();
  }

  public function listarValoresTipo(){
    try {

      $objArrTipoServidorAutenticacaoDTO = array();

      $objTipoServidorAutenticacaoDTO = new TipoServidorAutenticacaoDTO();
      $objTipoServidorAutenticacaoDTO->setStrStaTipo(InfraLDAP::$TIPO_LDAP);
      $objTipoServidorAutenticacaoDTO->setStrDescricao('OpenLDAP');
      $objArrTipoServidorAutenticacaoDTO[] = $objTipoServidorAutenticacaoDTO;

      $objTipoServidorAutenticacaoDTO = new TipoServidorAutenticacaoDTO();
      $objTipoServidorAutenticacaoDTO->setStrStaTipo(InfraLDAP::$TIPO_AD);
      $objTipoServidorAutenticacaoDTO->setStrDescricao('Active Directory');
      $objArrTipoServidorAutenticacaoDTO[] = $objTipoServidorAutenticacaoDTO;

      return $objArrTipoServidorAutenticacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Tipo.',$e);
    }
  }

  private function validarStrNome(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrNome())){
      $objInfraException->adicionarValidacao('Nome não informado.');
    }else{
      $objServidorAutenticacaoDTO->setStrNome(trim($objServidorAutenticacaoDTO->getStrNome()));
  
      if (strlen($objServidorAutenticacaoDTO->getStrNome())>50){
        $objInfraException->adicionarValidacao('Nome possui tamanho superior a 50 caracteres.');
      }
      
      $dto = new ServidorAutenticacaoDTO();
      $dto->setStrNome($objServidorAutenticacaoDTO->getStrNome());
      $dto->setNumIdServidorAutenticacao($objServidorAutenticacaoDTO->getNumIdServidorAutenticacao(),InfraDTO::$OPER_DIFERENTE);
      
      if ($this->contar($dto)){
        $objInfraException->adicionarValidacao('Existe outro Servidor de Autenticação cadastrado com o mesmo nome.');
      }
    }
  }
  
  private function validarStrStaTipo(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrStaTipo())){
      $objInfraException->adicionarValidacao('Tipo não informado.');
    }else{
      if (!in_array($objServidorAutenticacaoDTO->getStrStaTipo(),InfraArray::converterArrInfraDTO($this->listarValoresTipo(),'StaTipo'))){
        $objInfraException->adicionarValidacao('Tipo inválido.');
      }
    }
  }

  private function validarStrEndereco(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrEndereco())){
      $objInfraException->adicionarValidacao('Endereço não informado.');
    }else{
      $objServidorAutenticacaoDTO->setStrEndereco(trim($objServidorAutenticacaoDTO->getStrEndereco()));

      if (strlen($objServidorAutenticacaoDTO->getStrEndereco())>100){
        $objInfraException->adicionarValidacao('Endereço possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarNumPorta(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getNumPorta())){
      $objInfraException->adicionarValidacao('Porta não informada.');
    }
  }

  private function validarStrSufixo(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrSufixo())){
      $objServidorAutenticacaoDTO->setStrSufixo(null);
    }else{
      $objServidorAutenticacaoDTO->setStrSufixo(trim($objServidorAutenticacaoDTO->getStrSufixo()));

      if (strlen($objServidorAutenticacaoDTO->getStrSufixo())>50){
        $objInfraException->adicionarValidacao('Sufixo possui tamanho superior a 50 caracteres.');
      }
    }
  }

  private function validarStrUsuarioPesquisa(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrUsuarioPesquisa())){
      $objServidorAutenticacaoDTO->setStrUsuarioPesquisa(null);
    }else{
      $objServidorAutenticacaoDTO->setStrUsuarioPesquisa(trim($objServidorAutenticacaoDTO->getStrUsuarioPesquisa()));

      if (strlen($objServidorAutenticacaoDTO->getStrUsuarioPesquisa())>100){
        $objInfraException->adicionarValidacao('Usuário de Pesquisa possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrSenhaPesquisa(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrSenhaPesquisa())){
      $objServidorAutenticacaoDTO->setStrSenhaPesquisa(null);
    }else{
      $objServidorAutenticacaoDTO->setStrSenhaPesquisa(trim($objServidorAutenticacaoDTO->getStrSenhaPesquisa()));

      if (strlen($objServidorAutenticacaoDTO->getStrSenhaPesquisa())>100){
        $objInfraException->adicionarValidacao('Senha de Pesquisa possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrContextoPesquisa(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrContextoPesquisa())){
      $objServidorAutenticacaoDTO->setStrContextoPesquisa(null);
    }else{
      $objServidorAutenticacaoDTO->setStrContextoPesquisa(trim($objServidorAutenticacaoDTO->getStrContextoPesquisa()));

      if (strlen($objServidorAutenticacaoDTO->getStrContextoPesquisa())>100){
        $objInfraException->adicionarValidacao('Contexto de Pesquisa possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrAtributoFiltroPesquisa(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrAtributoFiltroPesquisa())){
      $objServidorAutenticacaoDTO->setStrAtributoFiltroPesquisa(null);
    }else{
      $objServidorAutenticacaoDTO->setStrAtributoFiltroPesquisa(trim($objServidorAutenticacaoDTO->getStrAtributoFiltroPesquisa()));

      if (strlen($objServidorAutenticacaoDTO->getStrAtributoFiltroPesquisa())>100){
        $objInfraException->adicionarValidacao('Atributo Filtro possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarStrAtributoRetornoPesquisa(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getStrAtributoRetornoPesquisa())){
      $objServidorAutenticacaoDTO->setStrAtributoRetornoPesquisa(null);
    }else{
      $objServidorAutenticacaoDTO->setStrAtributoRetornoPesquisa(trim($objServidorAutenticacaoDTO->getStrAtributoRetornoPesquisa()));

      if (strlen($objServidorAutenticacaoDTO->getStrAtributoRetornoPesquisa())>100){
        $objInfraException->adicionarValidacao('Atributo Retorno possui tamanho superior a 100 caracteres.');
      }
    }
  }

  private function validarNumVersao(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objServidorAutenticacaoDTO->getNumVersao())){
      $objInfraException->adicionarValidacao('Versão não informada.');
    }
  }

  protected function cadastrarControlado(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO) {
    try{

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('servidor_autenticacao_cadastrar', __METHOD__, $objServidorAutenticacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $this->validarStrNome($objServidorAutenticacaoDTO, $objInfraException);
      $this->validarStrStaTipo($objServidorAutenticacaoDTO, $objInfraException);
      $this->validarStrEndereco($objServidorAutenticacaoDTO, $objInfraException);
      $this->validarNumPorta($objServidorAutenticacaoDTO, $objInfraException);
      $this->validarStrSufixo($objServidorAutenticacaoDTO, $objInfraException);
      $this->validarStrUsuarioPesquisa($objServidorAutenticacaoDTO, $objInfraException);
      $this->validarStrSenhaPesquisa($objServidorAutenticacaoDTO, $objInfraException);
      $this->validarStrContextoPesquisa($objServidorAutenticacaoDTO, $objInfraException);
      $this->validarStrAtributoFiltroPesquisa($objServidorAutenticacaoDTO, $objInfraException);
      $this->validarStrAtributoRetornoPesquisa($objServidorAutenticacaoDTO, $objInfraException);
      $this->validarNumVersao($objServidorAutenticacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objServidorAutenticacaoBD = new ServidorAutenticacaoBD($this->getObjInfraIBanco());
      $ret = $objServidorAutenticacaoBD->cadastrar($objServidorAutenticacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Servidor de Autenticação.',$e);
    }
  }

  protected function alterarControlado(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO){
    try {

      //Valida Permissao
  	   SessaoSip::getInstance()->validarAuditarPermissao('servidor_autenticacao_alterar', __METHOD__, $objServidorAutenticacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();
      
      if ($objServidorAutenticacaoDTO->isSetStrNome()){
        $this->validarStrNome($objServidorAutenticacaoDTO, $objInfraException);
      }
      if ($objServidorAutenticacaoDTO->isSetStrStaTipo()){
        $this->validarStrStaTipo($objServidorAutenticacaoDTO, $objInfraException);
      }
      if ($objServidorAutenticacaoDTO->isSetStrEndereco()){
        $this->validarStrEndereco($objServidorAutenticacaoDTO, $objInfraException);
      }
      if ($objServidorAutenticacaoDTO->isSetNumPorta()){
        $this->validarNumPorta($objServidorAutenticacaoDTO, $objInfraException);
      }
      if ($objServidorAutenticacaoDTO->isSetStrSufixo()){
        $this->validarStrSufixo($objServidorAutenticacaoDTO, $objInfraException);
      }
      if ($objServidorAutenticacaoDTO->isSetStrUsuarioPesquisa()){
        $this->validarStrUsuarioPesquisa($objServidorAutenticacaoDTO, $objInfraException);
      }
      if ($objServidorAutenticacaoDTO->isSetStrSenhaPesquisa()){
        $this->validarStrSenhaPesquisa($objServidorAutenticacaoDTO, $objInfraException);
      }
      if ($objServidorAutenticacaoDTO->isSetStrContextoPesquisa()){
        $this->validarStrContextoPesquisa($objServidorAutenticacaoDTO, $objInfraException);
      }
      if ($objServidorAutenticacaoDTO->isSetStrAtributoFiltroPesquisa()){
        $this->validarStrAtributoFiltroPesquisa($objServidorAutenticacaoDTO, $objInfraException);
      }
      if ($objServidorAutenticacaoDTO->isSetStrAtributoRetornoPesquisa()){
        $this->validarStrAtributoRetornoPesquisa($objServidorAutenticacaoDTO, $objInfraException);
      }
      if ($objServidorAutenticacaoDTO->isSetNumVersao()){
        $this->validarNumVersao($objServidorAutenticacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objServidorAutenticacaoBD = new ServidorAutenticacaoBD($this->getObjInfraIBanco());
      $objServidorAutenticacaoBD->alterar($objServidorAutenticacaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Servidor de Autenticação.',$e);
    }
  }

  protected function excluirControlado($arrObjServidorAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('servidor_autenticacao_excluir', __METHOD__, $arrObjServidorAutenticacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objRelOrgaoAutenticacaoRN = new RelOrgaoAutenticacaoRN();
      
      for($i=0;$i<count($arrObjServidorAutenticacaoDTO);$i++){
        $objRelOrgaoAutenticacaoDTO = new RelOrgaoAutenticacaoDTO();
        $objRelOrgaoAutenticacaoDTO->setNumIdServidorAutenticacao($arrObjServidorAutenticacaoDTO[$i]->getNumIdServidorAutenticacao());
        if ($objRelOrgaoAutenticacaoRN->contar($objRelOrgaoAutenticacaoDTO)){
          $objInfraException->adicionarValidacao('Existem órgãos associados com este servidor de autenticação.');
        }
      }
            
      $objInfraException->lancarValidacoes();
      

      $objServidorAutenticacaoBD = new ServidorAutenticacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjServidorAutenticacaoDTO);$i++){
        $objServidorAutenticacaoBD->excluir($arrObjServidorAutenticacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Servidor de Autenticação.',$e);
    }
  }

  protected function consultarConectado(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO){
    try {

      /////////////////////////////////////////////////////////////////////////////////////
      //Valida Permissao
      //SessaoSip::getInstance()->validarAuditarPermissao('servidor_autenticacao_consultar', __METHOD__, $objServidorAutenticacaoDTO);
      /////////////////////////////////////////////////////////////////////////////////////

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objServidorAutenticacaoBD = new ServidorAutenticacaoBD($this->getObjInfraIBanco());
      $ret = $objServidorAutenticacaoBD->consultar($objServidorAutenticacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Servidor de Autenticação.',$e);
    }
  }

  protected function listarConectado(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO) {
    try {

      //Valida Permissao
      //SessaoSip::getInstance()->validarAuditarPermissao('servidor_autenticacao_listar', __METHOD__, $objServidorAutenticacaoDTO);
      
      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objServidorAutenticacaoBD = new ServidorAutenticacaoBD($this->getObjInfraIBanco());
      $ret = $objServidorAutenticacaoBD->listar($objServidorAutenticacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Servidores de Autenticação.',$e);
    }
  }

  protected function contarConectado(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('servidor_autenticacao_listar', __METHOD__, $objServidorAutenticacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objServidorAutenticacaoBD = new ServidorAutenticacaoBD($this->getObjInfraIBanco());
      $ret = $objServidorAutenticacaoBD->contar($objServidorAutenticacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Servidores de Autenticação.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjServidorAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('servidor_autenticacao_desativar', __METHOD__, $arrObjServidorAutenticacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objServidorAutenticacaoBD = new ServidorAutenticacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjServidorAutenticacaoDTO);$i++){
        $objServidorAutenticacaoBD->desativar($arrObjServidorAutenticacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Servidor de Autenticação.',$e);
    }
  }

  protected function reativarControlado($arrObjServidorAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('servidor_autenticacao_reativar', __METHOD__, $arrObjServidorAutenticacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objServidorAutenticacaoBD = new ServidorAutenticacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjServidorAutenticacaoDTO);$i++){
        $objServidorAutenticacaoBD->reativar($arrObjServidorAutenticacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Servidor de Autenticação.',$e);
    }
  }

  protected function bloquearControlado(ServidorAutenticacaoDTO $objServidorAutenticacaoDTO){
    try {

      //Valida Permissao
      SessaoSip::getInstance()->validarAuditarPermissao('servidor_autenticacao_consultar', __METHOD__, $objServidorAutenticacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objServidorAutenticacaoBD = new ServidorAutenticacaoBD($this->getObjInfraIBanco());
      $ret = $objServidorAutenticacaoBD->bloquear($objServidorAutenticacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Servidor de Autenticação.',$e);
    }
  }

 */
}
?>