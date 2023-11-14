<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/05/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.16.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ObservacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function cadastrarRN0222Controlado(ObservacaoDTO $objObservacaoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('observacao_cadastrar',__METHOD__,$objObservacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProtocoloRN0232($objObservacaoDTO, $objInfraException);
      $this->validarNumIdUnidadeRN0233($objObservacaoDTO, $objInfraException);
      $this->validarStrDescricaoRN0234($objObservacaoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objObservacaoDTO->setStrIdxObservacao(null);
      
      $objObservacaoBD = new ObservacaoBD($this->getObjInfraIBanco());
      $ret = $objObservacaoBD->cadastrar($objObservacaoDTO);

      $this->montarIndexacao($ret);
      
      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Observação.',$e);
    }
  }

  protected function alterarRN0749Controlado(ObservacaoDTO $objObservacaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('observacao_alterar',__METHOD__,$objObservacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objObservacaoDTO->isSetDblIdProtocolo()){
        $this->validarDblIdProtocoloRN0232($objObservacaoDTO, $objInfraException);
      }
      if ($objObservacaoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidadeRN0233($objObservacaoDTO, $objInfraException);
      }
      if ($objObservacaoDTO->isSetStrDescricao()){
        $this->validarStrDescricaoRN0234($objObservacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();


      if ($objObservacaoDTO->isSetStrIdxObservacao()){
        $objObservacaoDTO->unSetStrIdxObservacao();
      }

      $objObservacaoBD = new ObservacaoBD($this->getObjInfraIBanco());
      $objObservacaoBD->alterar($objObservacaoDTO);

      $this->montarIndexacao($objObservacaoDTO);
      
      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Observação.',$e);
    }
  }

  protected function excluirRN0220Controlado($arrObjObservacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('observacao_excluir',__METHOD__,$arrObjObservacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objObservacaoBD = new ObservacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjObservacaoDTO);$i++){
        $objObservacaoBD->excluir($arrObjObservacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Observação.',$e);
    }
  }

  protected function consultarRN0221Conectado(ObservacaoDTO $objObservacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('observacao_consultar',__METHOD__,$objObservacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objObservacaoBD = new ObservacaoBD($this->getObjInfraIBanco());
      $ret = $objObservacaoBD->consultar($objObservacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Observação.',$e);
    }
  }

  protected function listarRN0219Conectado(ObservacaoDTO $objObservacaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('observacao_listar',__METHOD__,$objObservacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objObservacaoBD = new ObservacaoBD($this->getObjInfraIBanco());
      $ret = $objObservacaoBD->listar($objObservacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Observações.',$e);
    }
  }

  protected function contarRN0750Conectado(ObservacaoDTO $objObservacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('observacao_listar',__METHOD__,$objObservacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objObservacaoBD = new ObservacaoBD($this->getObjInfraIBanco());
      $ret = $objObservacaoBD->contar($objObservacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Observações.',$e);
    }
  }

/* 
  protected function desativarControlado($arrObjObservacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('observacao_desativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objObservacaoBD = new ObservacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjObservacaoDTO);$i++){
        $objObservacaoBD->desativar($arrObjObservacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Observação.',$e);
    }
  }

  protected function reativarControlado($arrObjObservacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('observacao_reativar');

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objObservacaoBD = new ObservacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjObservacaoDTO);$i++){
        $objObservacaoBD->reativar($arrObjObservacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Observação.',$e);
    }
  }

 */

  private function validarDblIdProtocoloRN0232(ObservacaoDTO $objObservacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objObservacaoDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Protocolo não informado.');
    }
  }

  private function validarNumIdUnidadeRN0233(ObservacaoDTO $objObservacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objObservacaoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarStrDescricaoRN0234(ObservacaoDTO $objObservacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objObservacaoDTO->getStrDescricao())){
      $objInfraException->adicionarValidacao('Descrição não informada.');
    }else{
      $objObservacaoDTO->setStrDescricao(trim($objObservacaoDTO->getStrDescricao()));

      if (strlen($objObservacaoDTO->getStrDescricao())>1000){
        $objInfraException->adicionarValidacao('Observação possui tamanho superior a 1000 caracteres.');
      }
    }
  }

  private function validarStrIdxObservacao(ObservacaoDTO $objObservacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objObservacaoDTO->getStrIdxObservacao())){
      $objObservacaoDTO->setStrIdxObservacao(null);
    }
  }

  protected function montarIndexacaoControlado(ObservacaoDTO $parObjObservacaoDTO){
    try{

      $objObservacaoDTO = new ObservacaoDTO();
      $objObservacaoDTO->retNumIdObservacao();
      $objObservacaoDTO->retStrDescricao();

      if (is_array($parObjObservacaoDTO->getNumIdObservacao())){
        $objObservacaoDTO->setNumIdObservacao($parObjObservacaoDTO->getNumIdObservacao(),InfraDTO::$OPER_IN);
      }else{
        $objObservacaoDTO->setNumIdObservacao($parObjObservacaoDTO->getNumIdObservacao());
      }

      $objObservacaoDTOIdx = new ObservacaoDTO();
      $objInfraException = new InfraException();
      $objObservacaoBD = new ObservacaoBD($this->getObjInfraIBanco());

      $arrObjObservacaoDTO = $this->listarRN0219($objObservacaoDTO);

      foreach($arrObjObservacaoDTO as $objObservacaoDTO) {

        $objObservacaoDTOIdx->setStrIdxObservacao(InfraString::prepararIndexacao($objObservacaoDTO->getStrDescricao()));
        $objObservacaoDTOIdx->setNumIdObservacao($objObservacaoDTO->getNumIdObservacao());

        $this->validarStrIdxObservacao($objObservacaoDTOIdx, $objInfraException);
        $objInfraException->lancarValidacoes();

        $objObservacaoBD->alterar($objObservacaoDTOIdx);
      }

    }catch(Exception $e){
      throw new InfraException('Erro montando indexação de Observação.',$e);
    }
  }

  protected function complementarConectado($arrObjProcedimentoDTO){
    try {

      $objObservacaoDTO  = new ObservacaoDTO();
      $objObservacaoDTO->retDblIdProtocolo();
      $objObservacaoDTO->retStrDescricao();
      $objObservacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objObservacaoDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento'), InfraDTO::$OPER_IN);
      $arrObjObservacaoDTO = InfraArray::indexarArrInfraDTO($this->listarRN0219($objObservacaoDTO), 'IdProtocolo');

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        if (isset($arrObjObservacaoDTO[$objProcedimentoDTO->getDblIdProcedimento()])) {
          $objProcedimentoDTO->setObjObservacaoDTO($arrObjObservacaoDTO[$objProcedimentoDTO->getDblIdProcedimento()]);
        } else {
          $objProcedimentoDTO->setObjObservacaoDTO(null);
        }
      }

    } catch (Exception $e) {
      throw new InfraException('Erro complementando Observações.', $e);
    }
  }
}
?>