<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/05/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AcessoRN extends InfraRN {

	public static $TA_RESTRITO_UNIDADE = 'R';
	public static $TA_CREDENCIAL_PROCESSO = 'S';
	public static $TA_CREDENCIAL_ASSINATURA_PROCESSO = 'A';
	public static $TA_CREDENCIAL_ASSINATURA_DOCUMENTO = 'D';
  public static $TA_CONTROLE_INTERNO = 'C';

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarNumIdUsuario(AcessoDTO $objAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcessoDTO->getNumIdUsuario())){
      $objAcessoDTO->setNumIdUsuario(null);
    }
  }

  private function validarNumIdControleInterno(AcessoDTO $objAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcessoDTO->getNumIdControleInterno())){
      if ($objAcessoDTO->getStrStaTipo()==AcessoRN::$TA_CONTROLE_INTERNO){
        $objInfraException->lancarValidacao('Identificador do critério de controle interno não informado para o acesso.');
      }
      $objAcessoDTO->setNumIdControleInterno(null);
    }
  }

  private function validarNumIdUnidade(AcessoDTO $objAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcessoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarDblIdProtocolo(AcessoDTO $objAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcessoDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }
  }
  
  private function validarStrStaTipo(AcessoDTO $objAcessoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAcessoDTO->getStrStaTipo())){
      $objInfraException->adicionarValidacao('Tipo do Acesso não informado.');
    }else{
      if (!in_array($objAcessoDTO->getStrStaTipo(),InfraArray::converterArrInfraDTO($this->listarValoresTipoAcesso(),'StaTipo'))){
        $objInfraException->adicionarValidacao('Tipo do Acesso inválido.');
      }
    }
  }
  
  public function listarValoresTipoAcesso(){
    try {

      $arrObjTipoDTO = array();

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TA_RESTRITO_UNIDADE);
      $objTipoDTO->setStrDescricao('Protocolos Restritos da Unidade');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TA_CREDENCIAL_PROCESSO);
      $objTipoDTO->setStrDescricao('Protocolos com Credencial de Acesso');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TA_CREDENCIAL_ASSINATURA_PROCESSO);
      $objTipoDTO->setStrDescricao('Protocolos do processo acessados pela Credencial de Assinatura');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TA_CREDENCIAL_ASSINATURA_DOCUMENTO);
      $objTipoDTO->setStrDescricao('Protocolos do processo para assinatura por Credencial de Assinatura');
      $arrObjTipoDTO[] = $objTipoDTO;

      $objTipoDTO = new TipoDTO();
      $objTipoDTO->setStrStaTipo(self::$TA_CONTROLE_INTERNO);
      $objTipoDTO->setStrDescricao('Protocolos acessados por meio de Critérios de Controle Internos');
      $arrObjTipoDTO[] = $objTipoDTO;

      return $arrObjTipoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando valores de Tipo de Acesso.',$e);
    }
  }

  protected function cadastrarMultiploControlado($arrObjAcessoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_cadastrar',__METHOD__,$arrObjAcessoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      foreach($arrObjAcessoDTO as $objAcessoDTO) {
        $this->validarNumIdUsuario($objAcessoDTO, $objInfraException);
        $this->validarNumIdControleInterno($objAcessoDTO, $objInfraException);
        $this->validarNumIdUnidade($objAcessoDTO, $objInfraException);
        $this->validarDblIdProtocolo($objAcessoDTO, $objInfraException);
        $this->validarStrStaTipo($objAcessoDTO, $objInfraException);
        $objInfraException->lancarValidacoes();
      }

      foreach($arrObjAcessoDTO as $objAcessoDTO) {
        if ($objAcessoDTO->getNumIdAcesso()==null){
          $objAcessoDTO->setNumIdAcesso($this->getObjInfraIBanco()->getValorSequencia('seq_acesso'));
        }
      }

      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      $ret = $objAcessoBD->cadastrar($arrObjAcessoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando múltiplos registros de Acesso.',$e);
    }
  }

  protected function cadastrarControlado(AcessoDTO $objAcessoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_cadastrar',__METHOD__,$objAcessoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarNumIdUsuario($objAcessoDTO, $objInfraException);
      $this->validarNumIdControleInterno($objAcessoDTO, $objInfraException);
      $this->validarNumIdUnidade($objAcessoDTO, $objInfraException);
      $this->validarDblIdProtocolo($objAcessoDTO, $objInfraException);
      $this->validarStrStaTipo($objAcessoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      if ($objAcessoDTO->getNumIdAcesso()==null){
        $objAcessoDTO->setNumIdAcesso($this->getObjInfraIBanco()->getValorSequencia('seq_acesso'));
      }

      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      $ret = $objAcessoBD->cadastrar($objAcessoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Acesso.',$e);
    }
  }

  protected function alterarControlado(AcessoDTO $objAcessoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('acesso_alterar',__METHOD__,$objAcessoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAcessoDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objAcessoDTO, $objInfraException);
      }
      if ($objAcessoDTO->isSetNumIdControleInterno()){
        $this->validarNumIdControleInterno($objAcessoDTO, $objInfraException);
      }
      if ($objAcessoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objAcessoDTO, $objInfraException);
      }
      if ($objAcessoDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocolo($objAcessoDTO, $objInfraException);
      }
      if ($objAcessoDTO->isSetStrStaTipo()){
        $this->validarStrStaTipo($objAcessoDTO, $objInfraException);
      }
      
      $objInfraException->lancarValidacoes();

      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      $objAcessoBD->alterar($objAcessoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Acesso.',$e);
    }
  }

  protected function excluirControlado($arrObjAcessoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_excluir',__METHOD__,$arrObjAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAcessoDTO);$i++){
        $objAcessoBD->excluir($arrObjAcessoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Acesso.',$e);
    }
  }

  protected function excluirControleInternoControlado(AcessoDTO $objAcessoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_excluir',__METHOD__,$objAcessoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($objAcessoDTO->getNumIdControleInterno())){
        $objInfraException->adicionarValidacao('Critério de controle interno não informado para exclusão de acessos.');
      }

      $objInfraException->lancarValidacoes();


      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      $objAcessoBD->excluirControleInterno($objAcessoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo acessos de controle interno.',$e);
    }
  }


  protected function consultarConectado(AcessoDTO $objAcessoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_consultar',__METHOD__,$objAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      $ret = $objAcessoBD->consultar($objAcessoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Acesso.',$e);
    }
  }

  protected function listarConectado(AcessoDTO $objAcessoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_listar',__METHOD__,$objAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      $ret = $objAcessoBD->listar($objAcessoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Acessos.',$e);
    }
  }

  protected function contarConectado(AcessoDTO $objAcessoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_listar',__METHOD__,$objAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      $ret = $objAcessoBD->contar($objAcessoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Acessos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjAcessoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_desativar',__METHOD__,$arrObjAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAcessoDTO);$i++){
        $objAcessoBD->desativar($arrObjAcessoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Acesso.',$e);
    }
  }

  protected function reativarControlado($arrObjAcessoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_reativar',__METHOD__,$arrObjAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAcessoDTO);$i++){
        $objAcessoBD->reativar($arrObjAcessoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Acesso.',$e);
    }
  }

  protected function bloquearControlado(AcessoDTO $objAcessoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('acesso_consultar',__METHOD__,$objAcessoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAcessoBD = new AcessoBD($this->getObjInfraIBanco());
      $ret = $objAcessoBD->bloquear($objAcessoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Acesso.',$e);
    }
  }

 */
}
?>