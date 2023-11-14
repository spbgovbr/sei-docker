<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AndamentoSituacaoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarDthExecucao(AndamentoSituacaoDTO $objAndamentoSituacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAndamentoSituacaoDTO->getDthExecucao())){
      $objInfraException->adicionarValidacao(' não informad.');
    }else{
      if (!InfraData::validarDataHora($objAndamentoSituacaoDTO->getDthExecucao())){
        $objInfraException->adicionarValidacao(' inválid.');
      }
    }
  }

  private function validarDblIdProcedimento(AndamentoSituacaoDTO $objAndamentoSituacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAndamentoSituacaoDTO->getDblIdProcedimento())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdUnidade(AndamentoSituacaoDTO $objAndamentoSituacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAndamentoSituacaoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  private function validarNumIdUsuario(AndamentoSituacaoDTO $objAndamentoSituacaoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objAndamentoSituacaoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao(' não informad.');
    }
  }

  protected function gerenciarControlado(AndamentoSituacaoDTO $parObjAndamentoSituacaoDTO) {
    try{

      $ret = array();

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_situacao_gerenciar',__METHOD__,$parObjAndamentoSituacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($parObjAndamentoSituacaoDTO->getNumIdSituacao())) {
        $parObjAndamentoSituacaoDTO->setNumIdSituacao(null);
      }

      if (is_array($parObjAndamentoSituacaoDTO->getDblIdProcedimento())){
        $arrIdProcedimento = $parObjAndamentoSituacaoDTO->getDblIdProcedimento();
      }else if (!InfraString::isBolVazia($parObjAndamentoSituacaoDTO->getDblIdProcedimento())){
        $arrIdProcedimento = array($parObjAndamentoSituacaoDTO->getDblIdProcedimento());
      }else{
        $arrIdProcedimento = array();
      }

      if (count($arrIdProcedimento)==0){
        $objInfraException->adicionarValidacao('Nenhum processo informado.');
      }

      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retDblIdProtocolo();
      $objProtocoloDTO->retStrStaNivelAcessoGlobal();
      $objProtocoloDTO->setStrStaProtocolo(ProtocoloRN::$TP_PROCEDIMENTO);
      $objProtocoloDTO->setDblIdProtocolo($arrIdProcedimento,InfraDTO::$OPER_IN);

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarRN0668($objProtocoloDTO),'IdProtocolo');

      foreach($arrIdProcedimento as $dblIdProcedimento){
        if (!isset($arrObjProtocoloDTO[$dblIdProcedimento])){
          throw new InfraException('Processo não encontrado.');
        }

        if ($arrObjProtocoloDTO[$dblIdProcedimento]->getStrStaNivelAcessoGlobal()==ProtocoloRN::$NA_SIGILOSO){
          $objInfraException->lancarValidacao('Não é possível definir Ponto de Controle em processo sigiloso.');
        }
      }

      if ($parObjAndamentoSituacaoDTO->getNumIdSituacao()!=null) {
        $objRelSituacaoUnidadeDTO = new RelSituacaoUnidadeDTO();
        $objRelSituacaoUnidadeDTO->setNumIdSituacao($parObjAndamentoSituacaoDTO->getNumIdSituacao());
        $objRelSituacaoUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objRelSituacaoUnidadeRN = new RelSituacaoUnidadeRN();
        if ($objRelSituacaoUnidadeRN->contar($objRelSituacaoUnidadeDTO)==0) {
          $objInfraException->lancarValidacao('Situacao não está associada com a unidade atual.');
        }
      }

      $objInfraException->lancarValidacoes();

      $objAndamentoSituacaoBD = new AndamentoSituacaoBD($this->getObjInfraIBanco());

      $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();
      $objAndamentoSituacaoDTO->retNumIdAndamentoSituacao();
      $objAndamentoSituacaoDTO->retNumIdSituacao();
      $objAndamentoSituacaoDTO->retDblIdProcedimento();
      $objAndamentoSituacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAndamentoSituacaoDTO->setDblIdProcedimento($arrIdProcedimento,InfraDTO::$OPER_IN);
      $objAndamentoSituacaoDTO->setStrSinUltimo('S');

      $arrObjAndamentoSituacaoDTO = $this->listar($objAndamentoSituacaoDTO);

      $arrIdProcedimentoNaoModificado = array();
      foreach($arrObjAndamentoSituacaoDTO as $objAndamentoSituacaoDTO){

        if ($objAndamentoSituacaoDTO->getNumIdSituacao()!=$parObjAndamentoSituacaoDTO->getNumIdSituacao()) {

          $dto = new AndamentoSituacaoDTO();
          $dto->setStrSinUltimo('N');
          $dto->setNumIdAndamentoSituacao($objAndamentoSituacaoDTO->getNumIdAndamentoSituacao());
          $objAndamentoSituacaoBD->alterar($dto);

        }else{

          $arrIdProcedimentoNaoModificado[$objAndamentoSituacaoDTO->getDblIdProcedimento()] = 0;
        }
      }

      if (count($arrIdProcedimento)!=count($arrIdProcedimentoNaoModificado)) {
        $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();
        $objAndamentoSituacaoDTO->setNumIdAndamentoSituacao(null);
        $objAndamentoSituacaoDTO->setNumIdSituacao($parObjAndamentoSituacaoDTO->getNumIdSituacao());
        $objAndamentoSituacaoDTO->setDblIdProcedimento(null);
        $objAndamentoSituacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objAndamentoSituacaoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objAndamentoSituacaoDTO->setDthExecucao(InfraData::getStrDataHoraAtual());

        if ($parObjAndamentoSituacaoDTO->getNumIdSituacao()!=null) {
          $objAndamentoSituacaoDTO->setStrSinUltimo('S');
        }else{
          $objAndamentoSituacaoDTO->setStrSinUltimo('N');
        }

        $arrObjAndamentoSituacaoDTO = array();
        foreach ($arrIdProcedimento as $dblIdProcedimento) {

          if (!isset($arrIdProcedimentoNaoModificado[$dblIdProcedimento])) {
            $dto = clone($objAndamentoSituacaoDTO);
            $dto->setDblIdProcedimento($dblIdProcedimento);
            $arrObjAndamentoSituacaoDTO[] = $dto;
          }
        }

        $ret = $objAndamentoSituacaoBD->cadastrar($arrObjAndamentoSituacaoDTO);
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro gerenciando Situação.',$e);
    }
  }

  /*protected function alterarControlado(AndamentoSituacaoDTO $objAndamentoSituacaoDTO){
    try {

      //Valida Permissao
  	   SessaoSEI::getInstance()->validarAuditarPermissao('andamento_situacao_alterar',__METHOD__,$objAndamentoSituacaoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if ($objAndamentoSituacaoDTO->isSetDthExecucao()){
        $this->validarDthExecucao($objAndamentoSituacaoDTO, $objInfraException);
      }
      if ($objAndamentoSituacaoDTO->isSetDblIdProcedimento()){
        $this->validarDblIdProcedimento($objAndamentoSituacaoDTO, $objInfraException);
      }
      if ($objAndamentoSituacaoDTO->isSetNumIdUnidade()){
        $this->validarNumIdUnidade($objAndamentoSituacaoDTO, $objInfraException);
      }
      if ($objAndamentoSituacaoDTO->isSetNumIdUsuario()){
        $this->validarNumIdUsuario($objAndamentoSituacaoDTO, $objInfraException);
      }

      $objInfraException->lancarValidacoes();

      $objAndamentoSituacaoBD = new AndamentoSituacaoBD($this->getObjInfraIBanco());
      $objAndamentoSituacaoBD->alterar($objAndamentoSituacaoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando .',$e);
    }
  }*/

  protected function excluirControlado($arrObjAndamentoSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_situacao_excluir',__METHOD__,$arrObjAndamentoSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoSituacaoBD = new AndamentoSituacaoBD($this->getObjInfraIBanco());
      for ($i = 0; $i < count($arrObjAndamentoSituacaoDTO); $i++) {
        $objAndamentoSituacaoBD->excluir($arrObjAndamentoSituacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo .',$e);
    }
  }

  protected function consultarConectado(AndamentoSituacaoDTO $objAndamentoSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_situacao_consultar',__METHOD__,$objAndamentoSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoSituacaoBD = new AndamentoSituacaoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoSituacaoBD->consultar($objAndamentoSituacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando .',$e);
    }
  }

  protected function listarConectado(AndamentoSituacaoDTO $objAndamentoSituacaoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_situacao_listar',__METHOD__,$objAndamentoSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoSituacaoBD = new AndamentoSituacaoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoSituacaoBD->listar($objAndamentoSituacaoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando .',$e);
    }
  }

  protected function contarConectado(AndamentoSituacaoDTO $objAndamentoSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_situacao_listar',__METHOD__,$objAndamentoSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoSituacaoBD = new AndamentoSituacaoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoSituacaoBD->contar($objAndamentoSituacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando .',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjAndamentoSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_situacao_desativar',__METHOD__,$arrObjAndamentoSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoSituacaoBD = new AndamentoSituacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAndamentoSituacaoDTO);$i++){
        $objAndamentoSituacaoBD->desativar($arrObjAndamentoSituacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando .',$e);
    }
  }

  protected function reativarControlado($arrObjAndamentoSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_situacao_reativar',__METHOD__,$arrObjAndamentoSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoSituacaoBD = new AndamentoSituacaoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjAndamentoSituacaoDTO);$i++){
        $objAndamentoSituacaoBD->reativar($arrObjAndamentoSituacaoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando .',$e);
    }
  }

  protected function bloquearControlado(AndamentoSituacaoDTO $objAndamentoSituacaoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('andamento_situacao_consultar',__METHOD__,$objAndamentoSituacaoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoSituacaoBD = new AndamentoSituacaoBD($this->getObjInfraIBanco());
      $ret = $objAndamentoSituacaoBD->bloquear($objAndamentoSituacaoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando .',$e);
    }
  }

 */
}
?>