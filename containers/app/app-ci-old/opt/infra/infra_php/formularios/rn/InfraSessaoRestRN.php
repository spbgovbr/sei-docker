<?
  /**
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 03/07/2019 - criado por cle@trf4.jus.br
  * Versão do Gerador de Código: 1.42.0
  */

  require_once dirname(__FILE__).'/../../Infra.php';

  class InfraSessaoRestRN extends InfraRN {

    public function __construct(){
      parent::__construct();
    }

    protected function inicializarObjInfraIBanco(){
      return BancoInfra::getInstance();
    }

    private function validarNumIdUsuario(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getNumIdUsuario())){
        $objInfraException->adicionarValidacao('Id do Usuário no SIP não informado.');
      }
    }

    private function validarStrSiglaUsuario(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getStrSiglaUsuario())){
        $objInfraException->adicionarValidacao('Sigla do Usuário não informada.');
      }else{
        $objInfraSessaoRestDTO->setStrSiglaUsuario(trim($objInfraSessaoRestDTO->getStrSiglaUsuario()));

        if (strlen($objInfraSessaoRestDTO->getStrSiglaUsuario())>100){
          $objInfraException->adicionarValidacao('Sigla do Usuário possui tamanho superior a 100 caracteres.');
        }
      }
    }

    private function validarNumIdOrgao(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getNumIdOrgao())){
        $objInfraException->adicionarValidacao('Id do Órgão não informado.');
      }
    }

    private function validarStrSiglaOrgao(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getStrSiglaOrgao())){
        $objInfraException->adicionarValidacao('Sigla do Órgão não informada.');
      }else{
        $objInfraSessaoRestDTO->setStrSiglaOrgao(trim($objInfraSessaoRestDTO->getStrSiglaOrgao()));

        if (strlen($objInfraSessaoRestDTO->getStrSiglaOrgao())>30){
          $objInfraException->adicionarValidacao('Sigla do Órgão possui tamanho superior a 30 caracteres.');
        }
      }
    }

    private function validarDthLogin(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getDthLogin())){
        $objInfraException->adicionarValidacao('Data do Login não informada.');
      }else{
        if (!InfraData::validarDataHora($objInfraSessaoRestDTO->getDthLogin())){
          $objInfraException->adicionarValidacao('Data do Login inválida.');
        }
      }
    }

    private function validarDthAcesso(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getDthAcesso())){
        $objInfraException->adicionarValidacao('Data do Último Acesso não informada.');
      }else{
        if (!InfraData::validarDataHora($objInfraSessaoRestDTO->getDthAcesso())){
          $objInfraException->adicionarValidacao('Data do Último Acesso inválida.');
        }
      }
    }

    private function validarDthLogout(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getDthLogout())){
        $objInfraSessaoRestDTO->setDthLogout(null);
      }else{
        if (!InfraData::validarDataHora($objInfraSessaoRestDTO->getDthLogout())){
          $objInfraException->adicionarValidacao('Data do Logout inválida.');
        }
      }
    }

    private function validarStrUserAgent(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getStrUserAgent())){
        $objInfraException->adicionarValidacao('User Agent não informado.');
      }else{
        $objInfraSessaoRestDTO->setStrUserAgent(trim($objInfraSessaoRestDTO->getStrUserAgent()));

        if (strlen($objInfraSessaoRestDTO->getStrUserAgent())>500){
          $objInfraException->adicionarValidacao('User Agent possui tamanho superior a 500 caracteres.');
        }
      }
    }

    private function validarStrHttpClientIp(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getStrHttpClientIp())){
        $objInfraSessaoRestDTO->setStrHttpClientIp(null);
      }else{
        $objInfraSessaoRestDTO->setStrHttpClientIp(trim($objInfraSessaoRestDTO->getStrHttpClientIp()));

        if (strlen($objInfraSessaoRestDTO->getStrHttpClientIp())>39){
          $objInfraException->adicionarValidacao('IP do Cliente possui tamanho superior a 39 caracteres.');
        }
      }
    }

    private function validarStrHttpXForwardedFor(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException){
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getStrHttpXForwardedFor())){
        $objInfraSessaoRestDTO->setStrHttpXForwardedFor(null);
      }else{
        $objInfraSessaoRestDTO->setStrHttpXForwardedFor(trim($objInfraSessaoRestDTO->getStrHttpXForwardedFor()));

        if (strlen($objInfraSessaoRestDTO->getStrHttpXForwardedFor())>39){
          $objInfraException->adicionarValidacao('X-Forwarded-For possui tamanho superior a 39 caracteres.');
        }
      }
    }

    private function validarStrRemoteAddr(InfraSessaoRestDTO $objInfraSessaoRestDTO, InfraException $objInfraException) {
      if (InfraString::isBolVazia($objInfraSessaoRestDTO->getStrRemoteAddr())){
        $objInfraSessaoRestDTO->setStrRemoteAddr(null);
      }else{
        $objInfraSessaoRestDTO->setStrRemoteAddr(trim($objInfraSessaoRestDTO->getStrRemoteAddr()));

        if (strlen($objInfraSessaoRestDTO->getStrRemoteAddr())>39){
          $objInfraException->adicionarValidacao('Remote Address possui tamanho superior a 39 caracteres.');
        }
      }
    }

    protected function cadastrarControlado(InfraSessaoRestDTO $objInfraSessaoRestDTO) {
      try {
        //SessaoInfra::getInstance()->validarPermissao('infra_sessao_rest_cadastrar');

        //Regras de Negocio
        $objInfraException = new InfraException();

        $this->validarNumIdUsuario($objInfraSessaoRestDTO, $objInfraException);
        $this->validarStrSiglaUsuario($objInfraSessaoRestDTO, $objInfraException);
        $this->validarNumIdOrgao($objInfraSessaoRestDTO, $objInfraException);
        $this->validarStrSiglaOrgao($objInfraSessaoRestDTO, $objInfraException);
        $this->validarDthLogin($objInfraSessaoRestDTO, $objInfraException);
        $this->validarDthAcesso($objInfraSessaoRestDTO, $objInfraException);
        $this->validarDthLogout($objInfraSessaoRestDTO, $objInfraException);
        $this->validarStrUserAgent($objInfraSessaoRestDTO, $objInfraException);
        $this->validarStrHttpClientIp($objInfraSessaoRestDTO, $objInfraException);
        $this->validarStrHttpXForwardedFor($objInfraSessaoRestDTO, $objInfraException);
        $this->validarStrRemoteAddr($objInfraSessaoRestDTO, $objInfraException);

        $objInfraException->lancarValidacoes();

        $objInfraSessaoRestBD = new InfraSessaoRestBD($this->getObjInfraIBanco());
        $ret = $objInfraSessaoRestBD->cadastrar($objInfraSessaoRestDTO);

        return $ret;

      }catch(Exception $e){
        throw new InfraException('Erro cadastrando Infra Sessão Rest.',$e);
      }
    }

    protected function alterarControlado(InfraSessaoRestDTO $objInfraSessaoRestDTO){
      try {
        //SessaoInfra::getInstance()->validarPermissao('infra_sessao_rest_alterar');

        //Regras de Negocio
        $objInfraException = new InfraException();

        if ($objInfraSessaoRestDTO->isSetNumIdUsuario()){
          $this->validarNumIdUsuario($objInfraSessaoRestDTO, $objInfraException);
        }
        if ($objInfraSessaoRestDTO->isSetStrSiglaUsuario()){
          $this->validarStrSiglaUsuario($objInfraSessaoRestDTO, $objInfraException);
        }
        if ($objInfraSessaoRestDTO->isSetNumIdOrgao()){
          $this->validarNumIdOrgao($objInfraSessaoRestDTO, $objInfraException);
        }
        if ($objInfraSessaoRestDTO->isSetStrSiglaOrgao()){
          $this->validarStrSiglaOrgao($objInfraSessaoRestDTO, $objInfraException);
        }
        if ($objInfraSessaoRestDTO->isSetDthLogin()){
          $this->validarDthLogin($objInfraSessaoRestDTO, $objInfraException);
        }
        if ($objInfraSessaoRestDTO->isSetDthAcesso()){
          $this->validarDthAcesso($objInfraSessaoRestDTO, $objInfraException);
        }
        if ($objInfraSessaoRestDTO->isSetDthLogout()){
          $this->validarDthLogout($objInfraSessaoRestDTO, $objInfraException);
        }
        if ($objInfraSessaoRestDTO->isSetStrUserAgent()){
          $this->validarStrUserAgent($objInfraSessaoRestDTO, $objInfraException);
        }
        if ($objInfraSessaoRestDTO->isSetStrHttpClientIp()){
          $this->validarStrHttpClientIp($objInfraSessaoRestDTO, $objInfraException);
        }
        if ($objInfraSessaoRestDTO->isSetStrHttpXForwardedFor()){
          $this->validarStrHttpXForwardedFor($objInfraSessaoRestDTO, $objInfraException);
        }
        if ($objInfraSessaoRestDTO->isSetStrRemoteAddr()){
          $this->validarStrRemoteAddr($objInfraSessaoRestDTO, $objInfraException);
        }

        $objInfraException->lancarValidacoes();

        $objInfraSessaoRestBD = new InfraSessaoRestBD($this->getObjInfraIBanco());
        $objInfraSessaoRestBD->alterar($objInfraSessaoRestDTO);

      }catch(Exception $e){
        throw new InfraException('Erro alterando Infra Sessão Rest.',$e);
      }
    }

    protected function excluirControlado($arrObjInfraSessaoRestDTO){
      try {
        //SessaoInfra::getInstance()->validarPermissao('infra_sessao_rest_excluir');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objInfraSessaoRestBD = new InfraSessaoRestBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjInfraSessaoRestDTO);$i++){
          $objInfraSessaoRestBD->excluir($arrObjInfraSessaoRestDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro excluindo Infra Sessão Rest.',$e);
      }
    }

    protected function consultarConectado(InfraSessaoRestDTO $objInfraSessaoRestDTO){
      try {
        //SessaoInfra::getInstance()->validarPermissao('infra_sessao_rest_consultar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objInfraSessaoRestBD = new InfraSessaoRestBD($this->getObjInfraIBanco());
        $ret = $objInfraSessaoRestBD->consultar($objInfraSessaoRestDTO);

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro consultando Infra Sessão Rest.',$e);
      }
    }

    protected function listarConectado(InfraSessaoRestDTO $objInfraSessaoRestDTO) {
      try {
        //SessaoInfra::getInstance()->validarPermissao('infra_sessao_rest_listar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objInfraSessaoRestBD = new InfraSessaoRestBD($this->getObjInfraIBanco());
        $ret = $objInfraSessaoRestBD->listar($objInfraSessaoRestDTO);

        return $ret;

      }catch(Exception $e){
        throw new InfraException('Erro listando Infra Sessão Rest.',$e);
      }
    }

    protected function contarConectado(InfraSessaoRestDTO $objInfraSessaoRestDTO){
      try {
        //SessaoInfra::getInstance()->validarPermissao('infra_sessao_rest_listar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objInfraSessaoRestBD = new InfraSessaoRestBD($this->getObjInfraIBanco());
        $ret = $objInfraSessaoRestBD->contar($objInfraSessaoRestDTO);

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro contando Infra Sessão Rest.',$e);
      }
    }
  /*
    protected function desativarControlado($arrObjInfraSessaoRestDTO){
      try {

        SessaoInfra::getInstance()->validarPermissao('infra_sessao_rest_desativar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objInfraSessaoRestBD = new InfraSessaoRestBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjInfraSessaoRestDTO);$i++){
          $objInfraSessaoRestBD->desativar($arrObjInfraSessaoRestDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro desativando Infra Sessão Rest.',$e);
      }
    }

    protected function reativarControlado($arrObjInfraSessaoRestDTO){
      try {

        SessaoInfra::getInstance()->validarPermissao('infra_sessao_rest_reativar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objInfraSessaoRestBD = new InfraSessaoRestBD($this->getObjInfraIBanco());
        for($i=0;$i<count($arrObjInfraSessaoRestDTO);$i++){
          $objInfraSessaoRestBD->reativar($arrObjInfraSessaoRestDTO[$i]);
        }

      }catch(Exception $e){
        throw new InfraException('Erro reativando Infra Sessão Rest.',$e);
      }
    }

    protected function bloquearControlado(InfraSessaoRestDTO $objInfraSessaoRestDTO){
      try {

        SessaoInfra::getInstance()->validarPermissao('infra_sessao_rest_consultar');

        //Regras de Negocio
        //$objInfraException = new InfraException();

        //$objInfraException->lancarValidacoes();

        $objInfraSessaoRestBD = new InfraSessaoRestBD($this->getObjInfraIBanco());
        $ret = $objInfraSessaoRestBD->bloquear($objInfraSessaoRestDTO);

        return $ret;
      }catch(Exception $e){
        throw new InfraException('Erro bloqueando Infra Sessão Rest.',$e);
      }
    }

   */
  }
