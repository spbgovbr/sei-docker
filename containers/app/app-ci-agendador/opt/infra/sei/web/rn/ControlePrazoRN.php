<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/08/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ControlePrazoRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarDblIdProtocolo(ControlePrazoDTO $objControlePrazoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objControlePrazoDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Processo não informado.');
    }
  }

  private function validarNumIdUnidade(ControlePrazoDTO $objControlePrazoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objControlePrazoDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarNumIdUsuario(ControlePrazoDTO $objControlePrazoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objControlePrazoDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarDtaPrazo(ControlePrazoDTO $objControlePrazoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objControlePrazoDTO->getDtaPrazo())){
      $objControlePrazoDTO->setDtaPrazo(null);
    }else{
      if (!InfraData::validarData($objControlePrazoDTO->getDtaPrazo())){
        $objInfraException->adicionarValidacao('Data de prazo inválida.');
      }

      if (InfraData::compararDatas(InfraData::getStrDataAtual(),$objControlePrazoDTO->getDtaPrazo())<0){
        $objInfraException->adicionarValidacao('Data de prazo não pode estar no passado.');
      }
    }
  }

  private function validarNumDias(ControlePrazoDTO $objControlePrazoDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objControlePrazoDTO->getNumDias())){
      $objControlePrazoDTO->setNumDias(null);
    }else{
      $objControlePrazoDTO->setNumDias(trim($objControlePrazoDTO->getNumDias()));

      if (!is_numeric($objControlePrazoDTO->getNumDias()) ||	$objControlePrazoDTO->getNumDias() < 1){
        $objInfraException->adicionarValidacao('Número de dias para prazo inválido.');
      }
    }
  }

  protected function validarStrSinDiasUteis(ControlePrazoDTO $objControlePrazoDTO, InfraException $objInfraException){
    if ($objControlePrazoDTO->getNumDias()!=null && !InfraUtil::isBolSinalizadorValido($objControlePrazoDTO->getStrSinDiasUteis())){
      $objInfraException->lancarValidacao('Sinalizador de dias úteis inválido no Controle de Prazo.');
    }
  }

  private function validarDtaPrazoNumDias(ControlePrazoDTO $objControlePrazoDTO, InfraException $objInfraException){
    if (!InfraString::isBolVazia($objControlePrazoDTO->getDtaPrazo()) && !InfraString::isBolVazia($objControlePrazoDTO->getNumDias())){
      $objInfraException->adicionarValidacao('Não é possível informar simultaneamente uma data específica e um número de dias para o Controle de Prazo.');
    }else if (InfraString::isBolVazia($objControlePrazoDTO->getDtaPrazo()) && InfraString::isBolVazia($objControlePrazoDTO->getNumDias())){
      $objInfraException->adicionarValidacao('Uma data específica ou um número de dias para o Controle de Prazo deve ser informado.');
    }
  }

  private function validarDuplicado(ControlePrazoDTO $objControlePrazoDTO, InfraException $objInfraException){

    $dto = new ControlePrazoDTO();
    $dto->setNumMaxRegistrosRetorno(1);
    $dto->retNumIdControlePrazo();
    $dto->setDblIdProtocolo($objControlePrazoDTO->getDblIdProtocolo());
    $dto->setNumIdUnidade($objControlePrazoDTO->getNumIdUnidade());

    if ($this->consultar($dto) != null){
      $objProtocoloDTO = new ProtocoloDTO();
      $objProtocoloDTO->retStrProtocoloFormatado();
      $objProtocoloDTO->setDblIdProtocolo($objControlePrazoDTO->getDblIdProtocolo());

      $objProtocoloRN = new ProtocoloRN();
      $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);
      $objInfraException->lancarValidacao('Já existe um Controle de Prazo nesta Unidade para o processo '.$objProtocoloDTO->getStrProtocoloFormatado().'.');
    }
  }

  protected function definirControlado($arrObjControlePrazoDTO){
    try{
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_definir', __METHOD__, $arrObjControlePrazoDTO);

      foreach($arrObjControlePrazoDTO as $parObjControlePrazoDTO){

        $objControlePrazoDTO = new ControlePrazoDTO();
        $objControlePrazoDTO->retNumIdControlePrazo();
        $objControlePrazoDTO->setDblIdProtocolo($parObjControlePrazoDTO->getDblIdProtocolo());
        $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objControlePrazoDTO = $this->consultar($objControlePrazoDTO);

        if ($objControlePrazoDTO == null) {

          $parObjControlePrazoDTO->setNumIdControlePrazo(null);
          $this->cadastrar($parObjControlePrazoDTO);

        } else {

          $parObjControlePrazoDTO->setNumIdControlePrazo($objControlePrazoDTO->getNumIdControlePrazo());
          $this->alterar($parObjControlePrazoDTO);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro definindo Controle de Prazos.',$e);
    }
  }

  protected function concluirControlado($arrObjControlePrazoDTO){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_concluir', __METHOD__, $arrObjControlePrazoDTO);

      $objInfraException = new InfraException();

      foreach($arrObjControlePrazoDTO as $parObjControlePrazoDTO){

        $objControlePrazoDTO = new ControlePrazoDTO();
        $objControlePrazoDTO->retNumIdControlePrazo();
        $objControlePrazoDTO->retDtaConclusao();
        $objControlePrazoDTO->retStrProtocoloFormatado();
        $objControlePrazoDTO->setDblIdProtocolo($parObjControlePrazoDTO->getDblIdProtocolo());
        $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

        $objControlePrazoDTO = $this->consultar($objControlePrazoDTO);

        if ($objControlePrazoDTO == null) {

          $objControlePrazoDTO = new ControlePrazoDTO();
          $objControlePrazoDTO->setNumIdControlePrazo(null);
          $objControlePrazoDTO->setDtaPrazo(InfraData::getStrDataAtual());
          $objControlePrazoDTO->setNumDias(null);
          $objControlePrazoDTO->setStrSinDiasUteis('N');
          $objControlePrazoDTO->setDblIdProtocolo($parObjControlePrazoDTO->getDblIdProtocolo());
          $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          $objControlePrazoDTO = $this->cadastrar($objControlePrazoDTO);

        }else {

          if ($objControlePrazoDTO->getDtaConclusao() != null) {
            $objInfraException->lancarValidacao('Controle de Prazo do processo '.$objControlePrazoDTO->getStrProtocoloFormatado().' já está concluído na unidade.');
          }

        }

        $objControlePrazoDTO->setDtaConclusao(InfraData::getStrDataAtual());

        $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
        $objControlePrazoBD->alterar($objControlePrazoDTO);
      }

    }catch(Exception $e){
      throw new InfraException('Erro concluindo Controle de Prazos.',$e);
    }
  }


  protected function cadastrarControlado(ControlePrazoDTO $objControlePrazoDTO) {
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_cadastrar',__METHOD__,$objControlePrazoDTO);

      $objControlePrazoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objControlePrazoDTO->setDtaConclusao(null);

      //Regras de Negocio
      $objInfraException = new InfraException();
      $this->validarDuplicado($objControlePrazoDTO, $objInfraException);
      $this->validarDblIdProtocolo($objControlePrazoDTO, $objInfraException);
      //$this->validarNumIdUnidade($objControlePrazoDTO, $objInfraException);
      //$this->validarNumIdUsuario($objControlePrazoDTO, $objInfraException);
      $this->validarDtaPrazo($objControlePrazoDTO, $objInfraException);
      $this->validarNumDias($objControlePrazoDTO, $objInfraException);
      $this->validarStrSinDiasUteis($objControlePrazoDTO, $objInfraException);
      $this->validarDtaPrazoNumDias($objControlePrazoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      if (InfraString::isBolVazia($objControlePrazoDTO->getDtaPrazo())) {
        $this->calcularPrazo($objControlePrazoDTO);
      }

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      $ret = $objControlePrazoBD->cadastrar($objControlePrazoDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Controle de Prazos.',$e);
    }
  }

  protected function alterarControlado(ControlePrazoDTO $objControlePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_alterar',__METHOD__,$objControlePrazoDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objControlePrazoDTO_Banco = new ControlePrazoDTO();
      $objControlePrazoDTO_Banco->retDtaPrazo();
      $objControlePrazoDTO_Banco->retDblIdProtocolo();
      $objControlePrazoDTO_Banco->retNumIdUnidade();
      $objControlePrazoDTO_Banco->retDtaConclusao();
      $objControlePrazoDTO_Banco->setNumIdControlePrazo($objControlePrazoDTO->getNumIdControlePrazo());

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      $objControlePrazoDTO_Banco = $objControlePrazoBD->consultar($objControlePrazoDTO_Banco);

      if ($objControlePrazoDTO_Banco == null){
        throw new InfraException('Registro não encontrado.');
      }

      if(!InfraString::isBolVazia($objControlePrazoDTO_Banco->getDtaConclusao())){
        //$objInfraException->lancarValidacao("O Controle de Prazo para este processo na unidade já foi concluído e não pode ser alterado.");
        $objControlePrazoDTO->setDtaConclusao(null);
      }

      if ($objControlePrazoDTO_Banco->getNumIdUnidade()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
        $objInfraException->lancarValidacao('Não é possível alterar um Controle de Prazo de outra unidade.');
      }

      if ($objControlePrazoDTO->isSetNumIdUnidade() && $objControlePrazoDTO->getNumIdUnidade()!=$objControlePrazoDTO_Banco->getNumIdUnidade()){
        $objInfraException->lancarValidacao('Não é possível alterar a unidade de um Controle de Prazo.');
      }else{
        $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      }

      if ($objControlePrazoDTO->isSetDblIdProtocolo() && $objControlePrazoDTO->getDblIdProtocolo()!=$objControlePrazoDTO_Banco->getDblIdProtocolo()){
        $objInfraException->lancarValidacao('Não é possível alterar o processo de um Controle de Prazo.');
      }

      if ($objControlePrazoDTO->isSetNumIdUsuario() && $objControlePrazoDTO->getNumIdUsuario()!=SessaoSEI::getInstance()->getNumIdUsuario()) {
        $objInfraException->lancarValidacao('Não é possível informar o usuário para o Controle de Prazo.');
      }else{
        $objControlePrazoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      }

      if ($objControlePrazoDTO->isSetDtaPrazo()){
        $this->validarDtaPrazo($objControlePrazoDTO, $objInfraException);
      }else{
        $objControlePrazoDTO->setDtaPrazo(null);
      }

      if ($objControlePrazoDTO->isSetNumDias()){
        $this->validarNumDias($objControlePrazoDTO, $objInfraException);
      }else{
        $objControlePrazoDTO->setNumDias(null);
      }

      if ($objControlePrazoDTO->isSetStrSinDiasUteis() ){
        $this->validarStrSinDiasUteis($objControlePrazoDTO, $objInfraException);
      }else{
        if ($objControlePrazoDTO->getNumDias()!=null){
          $objInfraException->adicionarValidacao('Sinalizador de dias úteis não informado.');
        }
      }

      $this->validarDtaPrazoNumDias($objControlePrazoDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      if ($objControlePrazoDTO->getDtaPrazo()==null && $objControlePrazoDTO->getNumDias()!=null){
        $this->calcularPrazo($objControlePrazoDTO);
      }

      $objControlePrazoBD->alterar($objControlePrazoDTO);

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro alterando Controle de Prazos.',$e);
    }
  }

  protected function excluirControlado($arrObjControlePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_excluir',__METHOD__,$arrObjControlePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjControlePrazoDTO);$i++){
        $objControlePrazoBD->excluir($arrObjControlePrazoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Controle de Prazos.',$e);
    }
  }

  protected function consultarConectado(ControlePrazoDTO $objControlePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_consultar',__METHOD__,$objControlePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      $ret = $objControlePrazoBD->consultar($objControlePrazoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Controle de Prazos.',$e);
    }
  }

  protected function listarConectado(ControlePrazoDTO $objControlePrazoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_listar',__METHOD__,$objControlePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      $arrObjControlePrazoDTO = $objControlePrazoBD->listar($objControlePrazoDTO);

      return $arrObjControlePrazoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro listando Controles de Prazos.',$e);
    }
  }

  protected function listarCompletoConectado(ControlePrazoDTO $parObjControlePrazoDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_listar',__METHOD__,$parObjControlePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControlePrazoDTO = new ControlePrazoDTO();
      $objControlePrazoDTO->retNumIdControlePrazo();
      $objControlePrazoDTO->retDblIdProtocolo();
      $objControlePrazoDTO->retDtaPrazo();
      $objControlePrazoDTO->retDtaConclusao();
      $objControlePrazoDTO->retStrSiglaUsuario();
      $objControlePrazoDTO->retStrNomeUsuario();
      $objControlePrazoDTO->retStrProtocoloFormatado();
      $objControlePrazoDTO->retStrNomeTipoProcedimento();

      if($parObjControlePrazoDTO->isSetNumAno() && !InfraString::isBolVazia($parObjControlePrazoDTO->getNumAno())) {
        $objControlePrazoDTO->adicionarCriterio(array('Prazo', 'Prazo'),
            array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
            array("01/01/".$parObjControlePrazoDTO->getNumAno(), "31/12/".$parObjControlePrazoDTO->getNumAno()),
            array(InfraDTO::$OPER_LOGICO_AND));
      }

      $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      if ($parObjControlePrazoDTO->isOrdDblIdProtocolo()){
        $objControlePrazoDTO->setOrdDblIdProtocolo($parObjControlePrazoDTO->getOrdDblIdProtocolo());
      }

      if ($parObjControlePrazoDTO->isOrdStrSiglaUsuario()){
        $objControlePrazoDTO->setOrdStrSiglaUsuario($parObjControlePrazoDTO->getOrdStrSiglaUsuario());
      }

      if ($parObjControlePrazoDTO->isOrdDtaPrazo()){
        $objControlePrazoDTO->setOrdDtaPrazo($parObjControlePrazoDTO->getOrdDtaPrazo());
      }

      if ($parObjControlePrazoDTO->isOrdDtaConclusao()){
        $objControlePrazoDTO->setOrdDtaConclusao($parObjControlePrazoDTO->getOrdDtaConclusao());
      }


      //paginação
      $objControlePrazoDTO->setNumMaxRegistrosRetorno($parObjControlePrazoDTO->getNumMaxRegistrosRetorno());
      $objControlePrazoDTO->setNumPaginaAtual($parObjControlePrazoDTO->getNumPaginaAtual());

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      $arrObjControlePrazoDTO = $objControlePrazoBD->listar($objControlePrazoDTO);

      //paginação
      $parObjControlePrazoDTO->setNumTotalRegistros($objControlePrazoDTO->getNumTotalRegistros());
      $parObjControlePrazoDTO->setNumRegistrosPaginaAtual($objControlePrazoDTO->getNumRegistrosPaginaAtual());

      if ( count($arrObjControlePrazoDTO)>0){
        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
        $objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjControlePrazoDTO,'IdProtocolo'));

        $objProtocoloRN = new ProtocoloRN();
        $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO),'IdProtocolo');
      }

      $arrRet = array();
      foreach($arrObjControlePrazoDTO as $objControlePrazoDTO){
        if (isset($arrObjProtocoloDTO[$objControlePrazoDTO->getDblIdProtocolo()])){
          $objControlePrazoDTO->setStrSinAberto($arrObjProtocoloDTO[$objControlePrazoDTO->getDblIdProtocolo()]->getStrSinAberto());
          $arrRet[] = $objControlePrazoDTO;
        }
      }


      return $arrRet;

    }catch(Exception $e){
      throw new InfraException('Erro listando Controles de Prazos.',$e);
    }
  }

  protected function contarConectado(ControlePrazoDTO $objControlePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_listar',__METHOD__,$objControlePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      $ret = $objControlePrazoBD->contar($objControlePrazoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Controles de Prazos.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjControlePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_desativar',__METHOD__,$arrObjControlePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjControlePrazoDTO);$i++){
        $objControlePrazoBD->desativar($arrObjControlePrazoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro desativando Controle de Prazos.',$e);
    }
  }

  protected function reativarControlado($arrObjControlePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_reativar',__METHOD__,$arrObjControlePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjControlePrazoDTO);$i++){
        $objControlePrazoBD->reativar($arrObjControlePrazoDTO[$i]);
      }

      //Auditoria

    }catch(Exception $e){
      throw new InfraException('Erro reativando Controle de Prazos.',$e);
    }
  }

  protected function bloquearControlado(ControlePrazoDTO $objControlePrazoDTO){
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_prazo_consultar',__METHOD__,$objControlePrazoDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      $ret = $objControlePrazoBD->bloquear($objControlePrazoDTO);

      //Auditoria

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Controle de Prazos.',$e);
    }
  }

 */

  protected function complementarConectado($arrObjProcedimentoDTO){
    try {

      $objControlePrazoDTO = new ControlePrazoDTO();
      $objControlePrazoDTO->setDistinct(true);
      $objControlePrazoDTO->retNumIdControlePrazo();
      $objControlePrazoDTO->retStrSiglaUsuario();
      $objControlePrazoDTO->retDblIdProtocolo();
      $objControlePrazoDTO->retDtaPrazo();
      $objControlePrazoDTO->retDtaConclusao();
      $objControlePrazoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      //$objControlePrazoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objControlePrazoDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO, 'IdProcedimento'), InfraDTO::$OPER_IN);

      $objControlePrazoBD = new ControlePrazoBD($this->getObjInfraIBanco());
      $arrObjControlePrazoDTO = $objControlePrazoBD->listar($objControlePrazoDTO);

      $arrObjControlePrazoDTO = InfraArray::indexarArrInfraDTO($arrObjControlePrazoDTO, 'IdProtocolo');

      foreach ($arrObjProcedimentoDTO as $objProcedimentoDTO) {
        if (isset($arrObjControlePrazoDTO[$objProcedimentoDTO->getDblIdProcedimento()])) {
          $objProcedimentoDTO->setObjControlePrazoDTO($arrObjControlePrazoDTO[$objProcedimentoDTO->getDblIdProcedimento()]);
        }else{
          $objProcedimentoDTO->setObjControlePrazoDTO(null);
        }
      }

    } catch (Exception $e) {
      throw new InfraException('Erro complementando Controle de Prazo.', $e);
    }
  }

  public function calcularPrazo(ControlePrazoDTO $objControlePrazoDTO){

    if ($objControlePrazoDTO->getStrSinDiasUteis() == 'N') {

      $strPrazo = InfraData::calcularData($objControlePrazoDTO->getNumDias(), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE);
      $objControlePrazoDTO->setDtaPrazo($strPrazo);

    } else {

      $strDataInicial = InfraData::getStrDataAtual();

      //busca feriados ate 1 ano a frente do periodo corrido solicitado
      $strDataFinal = InfraData::calcularData(($objControlePrazoDTO->getNumDias() + 365), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strDataInicial);

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->retNumIdOrgao();
      $objUnidadeDTO->setNumIdUnidade($objControlePrazoDTO->getNumIdUnidade());
      $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

      //pega todos os feriados cadastrados por órgão
      $objFeriadoDTO = new FeriadoDTO();
      $objFeriadoDTO->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());
      $objFeriadoDTO->setDtaInicial($strDataInicial);
      $objFeriadoDTO->setDtaFinal($strDataFinal);

      $objPublicacaoRN = new PublicacaoRN();
      $arrFeriados = InfraArray::simplificarArr($objPublicacaoRN->listarFeriados($objFeriadoDTO), 'Data');

      $numDias = $objControlePrazoDTO->getNumDias();
      $strPrazo = $strDataInicial;

      while ($numDias) {

        do {
          $strPrazo = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strPrazo);
        } while (InfraData::obterDescricaoDiaSemana($strPrazo) == 'sábado' || InfraData::obterDescricaoDiaSemana($strPrazo) == 'domingo' || in_array($strPrazo, $arrFeriados));

        $numDias--;
      }

      $objControlePrazoDTO->setDtaPrazo($strPrazo);
    }
  }
}
