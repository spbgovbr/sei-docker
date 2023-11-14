<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ParticipanteRN extends InfraRN {

  public static $TP_INTERESSADO = 'I';
  public static $TP_DESTINATARIO = 'D';
  public static $TP_REMETENTE = 'R';
  public static $TP_ACESSO_EXTERNO = 'A';
  
  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0170Controlado(ParticipanteDTO $objParticipanteDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('participante_cadastrar',__METHOD__,$objParticipanteDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProtocoloRN0235($objParticipanteDTO, $objInfraException);
      $this->validarStaParticipacaoRN0236($objParticipanteDTO, $objInfraException);
      $this->validarNumIdContatoRN0237($objParticipanteDTO, $objInfraException);
      $this->validarNumIdUnidadeRN0238($objParticipanteDTO, $objInfraException);
      $this->validarNumSequenciaRN1178($objParticipanteDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objParticipanteBD = new ParticipanteBD($this->getObjInfraIBanco());
      $ret = $objParticipanteBD->cadastrar($objParticipanteDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Participante do Protocolo.',$e);
    }
  }

  protected function alterarRN0889Controlado(ParticipanteDTO $objParticipanteDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('participante_alterar',__METHOD__,$objParticipanteDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objParticipanteDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocoloRN0235($objParticipanteDTO, $objInfraException);
      }
      if ($objParticipanteDTO->isSetStrStaParticipacao()){
        $this->validarStaParticipacaoRN0236($objParticipanteDTO, $objInfraException);
      }
      if ($objParticipanteDTO->isSetNumIdContato()){
        $this->validarNumIdContatoRN0237($objParticipanteDTO, $objInfraException);
      }
      if ($objParticipanteDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidadeRN0238($objParticipanteDTO, $objInfraException);
      }
      if ($objParticipanteDTO->isSetNumSequencia()){
        $this->validarNumSequenciaRN1178($objParticipanteDTO, $objInfraException);
      }
      
      $objInfraException->lancarValidacoes();

      $objParticipanteBD = new ParticipanteBD($this->getObjInfraIBanco());
      $objParticipanteBD->alterar($objParticipanteDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Participante do Protocolo.',$e);
    }
  }

  protected function excluirRN0223Controlado($arrObjParticipanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('participante_excluir',__METHOD__,$arrObjParticipanteDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $numParticipantes = count($arrObjParticipanteDTO);

      if ($numParticipantes) {

        $objAcessoExternoRN = new AcessoExternoRN();

        for ($i = 0; $i < $numParticipantes; $i++) {

          $objAcessoExternoDTO = new AcessoExternoDTO();
          $objAcessoExternoDTO->setBolExclusaoLogica(false);
          $objAcessoExternoDTO->retNumIdAcessoExterno();
          $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_SISTEMA, InfraDTO::$OPER_DIFERENTE);
          $objAcessoExternoDTO->setNumIdParticipante($arrObjParticipanteDTO[$i]->getNumIdParticipante());
          $objAcessoExternoDTO->setNumMaxRegistrosRetorno(1);

          if ($objAcessoExternoRN->consultar($objAcessoExternoDTO) != null) {

            $dto = new ParticipanteDTO();
            $dto->retStrNomeContato();
            $dto->setNumIdParticipante($arrObjParticipanteDTO[$i]->getNumIdParticipante());

            $dto = $this->consultarRN1008($dto);

            $objInfraException->adicionarValidacao('Interessado ' . $dto->getStrNomeContato() . ' não pode ser excluído porque recebeu acesso externo ao processo.');
          }
        }

        $objAcessoExternoDTO = new AcessoExternoDTO();
        $objAcessoExternoDTO->retNumIdAcessoExterno();
        $objAcessoExternoDTO->setBolExclusaoLogica(false);
        $objAcessoExternoDTO->setStrStaTipo(AcessoExternoRN::$TA_SISTEMA);
        $objAcessoExternoDTO->setNumIdParticipante(InfraArray::converterArrInfraDTO($arrObjParticipanteDTO, 'IdParticipante'), InfraDTO::$OPER_IN);
        $objAcessoExternoRN->excluir($objAcessoExternoRN->listar($objAcessoExternoDTO));

        $objInfraException->lancarValidacoes();

        $objParticipanteBD = new ParticipanteBD($this->getObjInfraIBanco());
        for ($i = 0; $i < $numParticipantes; $i++) {
          $objParticipanteBD->excluir($arrObjParticipanteDTO[$i]);
        }
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Participante do Protocolo.',$e);
    }
  }

  protected function consultarRN1008Conectado(ParticipanteDTO $objParticipanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('participante_consultar',__METHOD__,$objParticipanteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParticipanteBD = new ParticipanteBD($this->getObjInfraIBanco());
      $ret = $objParticipanteBD->consultar($objParticipanteDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Participante do Protocolo.',$e);
    }
  }

  protected function listarRN0189Conectado(ParticipanteDTO $objParticipanteDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('participante_listar',__METHOD__,$objParticipanteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParticipanteBD = new ParticipanteBD($this->getObjInfraIBanco());
      $ret = $objParticipanteBD->listar($objParticipanteDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Participantes do Protocolo.',$e);
    }
  }

  protected function contarRN0461Conectado(ParticipanteDTO $objParticipanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('participante_listar',__METHOD__,$objParticipanteDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParticipanteBD = new ParticipanteBD($this->getObjInfraIBanco());
      $ret = $objParticipanteBD->contar($objParticipanteDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Participantes do Protocolo.',$e);
    }
  }

/* 
  protected function desativarControlado($arrObjParticipanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('participante_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParticipanteBD = new ParticipanteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjParticipanteDTO);$i++){
        $objParticipanteBD->desativar($arrObjParticipanteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Participante do Protocolo.',$e);
    }
  }

  protected function reativarControlado($arrObjParticipanteDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('participante_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objParticipanteBD = new ParticipanteBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjParticipanteDTO);$i++){
        $objParticipanteBD->reativar($arrObjParticipanteDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Participante do Protocolo.',$e);
    }
  }

 */
  private function validarDblIdProtocoloRN0235(ParticipanteDTO $objParticipanteDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objParticipanteDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado para o participante.');
    }
  }

  private function validarStaParticipacaoRN0236(ParticipanteDTO $objParticipanteDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objParticipanteDTO->getStrStaParticipacao())){
      $objInfraException->adicionarValidacao('Tipo de participação não informada para o participante.');
    }else{
      $arr = $this->listarTiposParticipacaoRN0833();
      foreach($arr as $dto) {
        if ($dto->getStrStaTipo() == $objParticipanteDTO->getStrStaParticipacao())
          return;
      }
      $objInfraException->adicionarValidacao('Tipo de participação inválida.');
    }    
  }

  private function validarNumIdContatoRN0237(ParticipanteDTO $objParticipanteDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objParticipanteDTO->getNumIdContato())){
      $objInfraException->adicionarValidacao('Contato não informado para o participante.');
    }
  }

  private function validarNumIdUnidadeRN0238(ParticipanteDTO $objParticipanteDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objParticipanteDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada para o participante.');
    }
  }

  private function validarNumSequenciaRN1178(ParticipanteDTO $objParticipanteDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objParticipanteDTO->getNumSequencia())){
      $objInfraException->adicionarValidacao('Sequência não informada para o participante.');
    }
  }
  
  public function listarTiposParticipacaoRN0833(){
  	$arr = array();

  	$objTipoDTO = new TipoDTO();
  	$objTipoDTO->setStrStaTipo(self::$TP_INTERESSADO);
  	$objTipoDTO->setStrDescricao('Interessado');
  	$arr[] = $objTipoDTO;

  	$objTipoDTO = new TipoDTO();
  	$objTipoDTO->setStrStaTipo(self::$TP_DESTINATARIO);
  	$objTipoDTO->setStrDescricao('Destinatário');
  	$arr[] = $objTipoDTO;

  	$objTipoDTO = new TipoDTO();
  	$objTipoDTO->setStrStaTipo(self::$TP_REMETENTE);
  	$objTipoDTO->setStrDescricao('Remetente');
  	$arr[] = $objTipoDTO;

  	$objTipoDTO = new TipoDTO();
  	$objTipoDTO->setStrStaTipo(self::$TP_ACESSO_EXTERNO);
  	$objTipoDTO->setStrDescricao('Acesso Externo');
  	$arr[] = $objTipoDTO;
  	
  	
  	return $arr;  	
  }
  
}
?>