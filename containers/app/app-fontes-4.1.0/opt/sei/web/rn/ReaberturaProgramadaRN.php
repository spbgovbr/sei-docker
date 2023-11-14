<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/11/2021 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ReaberturaProgramadaRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  private function validarDblIdProtocolo(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getDblIdProtocolo())){
      $objInfraException->adicionarValidacao('Processo não informado.');
    }
  }

  private function validarNumIdUnidade(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getNumIdUnidade())){
      $objInfraException->adicionarValidacao('Unidade não informada.');
    }
  }

  private function validarNumIdUsuario(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getNumIdUsuario())){
      $objInfraException->adicionarValidacao('Usuário não informado.');
    }
  }

  private function validarNumIdAtividade(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getNumIdAtividade())){
      $objReaberturaProgramadaDTO->setNumIdAtividade(null);
    }
  }

  private function validarDtaProgramada(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getDtaProgramada())){
      $objInfraException->adicionarValidacao('Data Programada não informada.');
    }else{
      if (!InfraData::validarData($objReaberturaProgramadaDTO->getDtaProgramada())){
        $objInfraException->adicionarValidacao('Data Programada inválida.');
      }

      if (InfraData::compararDatas($objReaberturaProgramadaDTO->getDtaProgramada(),InfraData::getStrDataAtual())>=0){
        $objInfraException->adicionarValidacao('Data Programada deve estar no futuro.');
      }

      $objReaberturaProgramadaDTOBanco = new ReaberturaProgramadaDTO();
      $objReaberturaProgramadaDTOBanco->setNumMaxRegistrosRetorno(1);
      $objReaberturaProgramadaDTOBanco->retStrProtocoloFormatadoProtocolo();
      $objReaberturaProgramadaDTOBanco->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objReaberturaProgramadaDTOBanco->setDblIdProtocolo($objReaberturaProgramadaDTO->getDblIdProtocolo());
      $objReaberturaProgramadaDTOBanco->setDtaProgramada($objReaberturaProgramadaDTO->getDtaProgramada());
      $objReaberturaProgramadaDTOBanco->setNumIdReaberturaProgramada($objReaberturaProgramadaDTO->getNumIdReaberturaProgramada(),InfraDTO::$OPER_DIFERENTE);
      $objReaberturaProgramadaDTOBanco = $this->consultar($objReaberturaProgramadaDTOBanco);

      if ($objReaberturaProgramadaDTOBanco != null) {
        $objInfraException->adicionarValidacao('Já existe reabertura programada na unidade '.SessaoSEI::getInstance()->getStrSiglaUnidadeAtual().' para o processo '.$objReaberturaProgramadaDTOBanco->getStrProtocoloFormatadoProtocolo().' em '.$objReaberturaProgramadaDTO->getDtaProgramada().'.');
      }
    }
  }

  private function validarDthAlteracao(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getDthAlteracao())){
      $objInfraException->adicionarValidacao('Data de Alteração não informada.');
    }else{
      if (!InfraData::validarDataHora($objReaberturaProgramadaDTO->getDthAlteracao())){
        $objInfraException->adicionarValidacao('Data de Alteração inválida.');
      }
    }
  }

  private function validarDthProcessamento(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getDthProcessamento())){
      $objReaberturaProgramadaDTO->setDthProcessamento(null);
    }else{
      if (!InfraData::validarDataHora($objReaberturaProgramadaDTO->getDthProcessamento())){
        $objInfraException->adicionarValidacao('Data de Processamento inválida.');
      }
    }
  }

  private function validarDthVisualizacao(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getDthVisualizacao())){
      $objInfraException->adicionarValidacao('Data de Visualização não informada.');
    }else{
      if (!InfraData::validarDataHora($objReaberturaProgramadaDTO->getDthVisualizacao())){
        $objInfraException->adicionarValidacao('Data de Visualização inválida.');
      }
    }
  }

  private function validarStrErro(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getStrErro())){
      $objReaberturaProgramadaDTO->setStrErro(null);
    }else{

      $objReaberturaProgramadaDTO->setStrErro(trim($objReaberturaProgramadaDTO->getStrErro()));

      if (strlen($objReaberturaProgramadaDTO->getStrErro())>250){
        $objInfraException->adicionarValidacao('Erro possui tamanho superior a 250 caracteres.');
      }
    }
  }

  protected function validarStrSinDiasUteis(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getStrSinDiasUteis())){
      $objInfraException->adicionarValidacao('Sinalizador de dias úteis da reabertura programada não informado.');
    }else{
      if (!InfraUtil::isBolSinalizadorValido($objReaberturaProgramadaDTO->getStrSinDiasUteis())){
        $objInfraException->lancarValidacao('Sinalizador de dias úteis da reabertura programada inválido.');
      }
    }
  }

  private function validarDtaPrazo(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getDtaPrazo())){
      $objReaberturaProgramadaDTO->setDtaPrazo(null);
    }else{
      if (!InfraData::validarData($objReaberturaProgramadaDTO->getDtaPrazo())){
        $objInfraException->adicionarValidacao('Data da reabertura programada inválida.');
      }

      if (InfraData::compararDatas(InfraData::getStrDataAtual(),$objReaberturaProgramadaDTO->getDtaPrazo())<0){
        $objInfraException->adicionarValidacao('Data da reabertura programada não pode estar no passado.');
      }
    }
  }

  private function validarNumDias(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO, InfraException $objInfraException){
    if (InfraString::isBolVazia($objReaberturaProgramadaDTO->getNumDias())){
      $objReaberturaProgramadaDTO->setNumDias(null);
    }else{

      $objReaberturaProgramadaDTO->setNumDias(trim($objReaberturaProgramadaDTO->getNumDias()));

      if (!is_numeric($objReaberturaProgramadaDTO->getNumDias()) ||	$objReaberturaProgramadaDTO->getNumDias() < 1){
        $objInfraException->adicionarValidacao('Número de dias para reabertura programada inválido.');
      }
    }
  }

  protected function registrarControlado(ReaberturaProgramadaDTO $parObjReaberturaProgramadaDTO) {
    try{

      $ret = null;

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('reabertura_programada_registrar',__METHOD__,$parObjReaberturaProgramadaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDtaPrazo($parObjReaberturaProgramadaDTO, $objInfraException);
      $this->validarNumDias($parObjReaberturaProgramadaDTO, $objInfraException);
      $this->validarStrSinDiasUteis($parObjReaberturaProgramadaDTO, $objInfraException);

      if (!InfraString::isBolVazia($parObjReaberturaProgramadaDTO->getDtaPrazo()) && !InfraString::isBolVazia($parObjReaberturaProgramadaDTO->getNumDias())){
        $objInfraException->adicionarValidacao('Não é possível informar simultaneamente uma data específica e um número de dias para a Reabertura Programada.');
      }

      $objInfraException->lancarValidacoes();

      $dtaProgramada = null;

      if (!InfraString::isBolVazia($parObjReaberturaProgramadaDTO->getDtaPrazo())){

        $dtaProgramada = $parObjReaberturaProgramadaDTO->getDtaPrazo();

      }else if (!InfraString::isBolVazia($parObjReaberturaProgramadaDTO->getNumDias())){

        if ($parObjReaberturaProgramadaDTO->getStrSinDiasUteis() == 'N'){

          $dtaProgramada = InfraData::calcularData($parObjReaberturaProgramadaDTO->getNumDias(),InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE);

        }else{

          $strDataInicial = InfraData::getStrDataAtual();

          //busca feriados ate 1 ano a frente do periodo corrido solicitado
          $strDataFinal = InfraData::calcularData(($parObjReaberturaProgramadaDTO->getNumDias() + 365), InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $strDataInicial);

          $objPublicacaoRN = new PublicacaoRN();

          $objFeriadoDTO = new FeriadoDTO();
          $objFeriadoDTO->setNumIdOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
          $objFeriadoDTO->setDtaInicial($strDataInicial);
          $objFeriadoDTO->setDtaFinal($strDataFinal);

          $arrFeriados = InfraArray::simplificarArr($objPublicacaoRN->listarFeriados($objFeriadoDTO), 'Data');

          $numDias = $parObjReaberturaProgramadaDTO->getNumDias();
          $dtaProgramada = $strDataInicial;

          while($numDias){

            do{
              $dtaProgramada = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $dtaProgramada);
            }while (InfraData::obterDescricaoDiaSemana($dtaProgramada) == 'sábado' ||	InfraData::obterDescricaoDiaSemana($dtaProgramada) == 'domingo' ||	in_array($dtaProgramada, $arrFeriados));

            $numDias--;
          }
        }
      }

      if ($dtaProgramada!=null) {

        if ($parObjReaberturaProgramadaDTO->getNumIdReaberturaProgramada()==null) {

          $arrIdProtocolo = $parObjReaberturaProgramadaDTO->getDblIdProtocolo();

          foreach ($arrIdProtocolo as $dblIdProtocolo) {
            $objReaberturaProgramadaDTO = new ReaberturaProgramadaDTO();
            $objReaberturaProgramadaDTO->setNumIdReaberturaProgramada(null);
            $objReaberturaProgramadaDTO->setDblIdProtocolo($dblIdProtocolo);
            $objReaberturaProgramadaDTO->setDtaProgramada($dtaProgramada);
            $ret = $this->cadastrar($objReaberturaProgramadaDTO);
          }

        }else{

          $objReaberturaProgramadaDTO = new ReaberturaProgramadaDTO();
          $objReaberturaProgramadaDTO->setNumIdReaberturaProgramada($parObjReaberturaProgramadaDTO->getNumIdReaberturaProgramada());
          $objReaberturaProgramadaDTO->setDtaProgramada($dtaProgramada);
          $this->alterar($objReaberturaProgramadaDTO);
        }
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro registrando Reabertura Programada.',$e);
    }
  }

  protected function listarReaberturasUnidadeConectado(ReaberturaProgramadaDTO $parObjReaberturaProgramadaDTO){
    try{

      $objInfraException = new InfraException();

      if (!$parObjReaberturaProgramadaDTO->isSetDblIdProtocolo()){
        $parObjReaberturaProgramadaDTO->setDblIdProtocolo(null);
      }

      if (!$parObjReaberturaProgramadaDTO->isSetDtaInicio()){
        $parObjReaberturaProgramadaDTO->setDtaInicio(null);
      }

      if (!$parObjReaberturaProgramadaDTO->isSetDtaFim()){
        $parObjReaberturaProgramadaDTO->setDtaFim(null);
      }

      if (!$parObjReaberturaProgramadaDTO->isSetStrProtocoloFormatadoProtocolo()){
        $parObjReaberturaProgramadaDTO->setStrProtocoloFormatadoProtocolo(null);
      }

      if (!InfraString::isBolVazia($parObjReaberturaProgramadaDTO->getDtaInicio()) || !InfraString::isBolVazia($parObjReaberturaProgramadaDTO->getDtaFim())){
        InfraData::validarPeriodo($parObjReaberturaProgramadaDTO->getDtaInicio(),$parObjReaberturaProgramadaDTO->getDtaFim(),$objInfraException,true,true);
      }

      $objInfraException->lancarValidacoes();

      $objReaberturaProgramadaDTO = new ReaberturaProgramadaDTO();
      $objReaberturaProgramadaDTO->retNumIdReaberturaProgramada();
      $objReaberturaProgramadaDTO->retStrSiglaUsuario();
      $objReaberturaProgramadaDTO->retStrNomeUsuario();
      $objReaberturaProgramadaDTO->retDtaProgramada();
      $objReaberturaProgramadaDTO->retDblIdProtocolo();
      $objReaberturaProgramadaDTO->retNumIdAtividade();
      $objReaberturaProgramadaDTO->retDthProcessamento();
      $objReaberturaProgramadaDTO->retStrErro();

      $objReaberturaProgramadaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      if ($parObjReaberturaProgramadaDTO->getDblIdProtocolo()!=null){
        $objReaberturaProgramadaDTO->setDblIdProtocolo($parObjReaberturaProgramadaDTO->getDblIdProtocolo());
      }

      if ($parObjReaberturaProgramadaDTO->getDtaInicio()!=null && $parObjReaberturaProgramadaDTO->getDtaFim()!=null) {

        $objReaberturaProgramadaDTO->adicionarCriterio(array('Programada', 'Programada'),
          array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
          array($parObjReaberturaProgramadaDTO->getDtaInicio(), $parObjReaberturaProgramadaDTO->getDtaFim()),
          array(InfraDTO::$OPER_LOGICO_AND));
      }

      if ($parObjReaberturaProgramadaDTO->getStrProtocoloFormatadoProtocolo()!=null){
        $objReaberturaProgramadaDTO->setStrProtocoloFormatadoPesquisaProtocolo(InfraUtil::retirarFormatacao($parObjReaberturaProgramadaDTO->getStrProtocoloFormatadoProtocolo(),false));
      }

      if ($parObjReaberturaProgramadaDTO->getStrSinAgendadas()=='S'){
        $objReaberturaProgramadaDTO->setDthProcessamento(null);
      }else{
        $objReaberturaProgramadaDTO->setDthProcessamento(null, InfraDTO::$OPER_DIFERENTE);
      }

      if ($parObjReaberturaProgramadaDTO->isOrdDblIdProtocolo()){
        $objReaberturaProgramadaDTO->setOrdDblIdProtocolo($parObjReaberturaProgramadaDTO->getOrdDblIdProtocolo());
      }

      if ($parObjReaberturaProgramadaDTO->isOrdDtaProgramada()){
        $objReaberturaProgramadaDTO->setOrdDtaProgramada($parObjReaberturaProgramadaDTO->getOrdDtaProgramada());
      }

      if ($parObjReaberturaProgramadaDTO->isOrdStrSiglaUsuario()){
        $objReaberturaProgramadaDTO->setOrdStrSiglaUsuario($parObjReaberturaProgramadaDTO->getOrdStrSiglaUsuario());
      }

      if ($parObjReaberturaProgramadaDTO->isOrdDthProcessamento()){
        $objReaberturaProgramadaDTO->setOrdDthProcessamento($parObjReaberturaProgramadaDTO->getOrdDthProcessamento());
      }

      //paginação
      $objReaberturaProgramadaDTO->setNumMaxRegistrosRetorno($parObjReaberturaProgramadaDTO->getNumMaxRegistrosRetorno());
      $objReaberturaProgramadaDTO->setNumPaginaAtual($parObjReaberturaProgramadaDTO->getNumPaginaAtual());

      $arrObjReaberturaProgramadaDTO = $this->listar($objReaberturaProgramadaDTO);

      //paginação
      $parObjReaberturaProgramadaDTO->setNumTotalRegistros($objReaberturaProgramadaDTO->getNumTotalRegistros());
      $parObjReaberturaProgramadaDTO->setNumRegistrosPaginaAtual($objReaberturaProgramadaDTO->getNumRegistrosPaginaAtual());


      if (count($arrObjReaberturaProgramadaDTO)>0){
        $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
        $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_PROCEDIMENTOS);
        $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
        $objPesquisaProtocoloDTO->setDblIdProtocolo(InfraArray::converterArrInfraDTO($arrObjReaberturaProgramadaDTO,'IdProtocolo'));

        $objProtocoloRN = new ProtocoloRN();
        $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO),'IdProtocolo');
      }

      $arrRet = array();
      foreach($arrObjReaberturaProgramadaDTO as $objReaberturaProgramadaDTO){
        if (isset($arrObjProtocoloDTO[$objReaberturaProgramadaDTO->getDblIdProtocolo()])){
          $objReaberturaProgramadaDTO->setObjProtocoloDTO($arrObjProtocoloDTO[$objReaberturaProgramadaDTO->getDblIdProtocolo()]);
          $arrRet[] = $objReaberturaProgramadaDTO;
        }
      }

      return $arrRet;

    }catch(Exception $e){
      throw new InfraException('Erro listando reaberturas programadas.',$e);
    }
  }

  protected function cadastrarControlado(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO) {
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('reabertura_programada_cadastrar', __METHOD__, $objReaberturaProgramadaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $this->validarDblIdProtocolo($objReaberturaProgramadaDTO, $objInfraException);
      $this->validarDtaProgramada($objReaberturaProgramadaDTO, $objInfraException);

      $objInfraException->lancarValidacoes();

      $objReaberturaProgramadaDTO->setNumIdReaberturaProgramada(null);
      $objReaberturaProgramadaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objReaberturaProgramadaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objReaberturaProgramadaDTO->setDthAlteracao(InfraData::getStrDataHoraAtual());
      $objReaberturaProgramadaDTO->setNumIdAtividade(null);
      $objReaberturaProgramadaDTO->setDthProcessamento(null);
      $objReaberturaProgramadaDTO->setDthVisualizacao(null);
      $objReaberturaProgramadaDTO->setStrErro(null);

      $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());
      $ret = $objReaberturaProgramadaBD->cadastrar($objReaberturaProgramadaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro cadastrando Reabertura Programada.',$e);
    }
  }

  protected function alterarControlado(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('reabertura_programada_alterar', __METHOD__, $objReaberturaProgramadaDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      $objReaberturaProgramadaDTOBanco = new ReaberturaProgramadaDTO();
      $objReaberturaProgramadaDTOBanco->retDblIdProtocolo();
      $objReaberturaProgramadaDTOBanco->retNumIdUnidade();
      $objReaberturaProgramadaDTOBanco->retNumIdAtividade();
      $objReaberturaProgramadaDTOBanco->retDthProcessamento();
      $objReaberturaProgramadaDTOBanco->retDthVisualizacao();
      $objReaberturaProgramadaDTOBanco->retStrErro();
      $objReaberturaProgramadaDTOBanco->setNumIdReaberturaProgramada($objReaberturaProgramadaDTO->getNumIdReaberturaProgramada());

      $objReaberturaProgramadaDTOBanco = $this->consultar($objReaberturaProgramadaDTOBanco);

      if ($objReaberturaProgramadaDTOBanco==null){
        throw new InfraException('Registro de reabertura programada não encontrado.');
      }

      if ($objReaberturaProgramadaDTO->isSetDblIdProtocolo() && $objReaberturaProgramadaDTO->getDblIdProtocolo()!=$objReaberturaProgramadaDTOBanco->getDblIdProtocolo()){
        $objInfraException->adicionarValidacao('Não é possível alterar o processo da reabertura programada.');
      }else{
        $objReaberturaProgramadaDTO->setDblIdProtocolo($objReaberturaProgramadaDTOBanco->getDblIdProtocolo());
      }

      if ($objReaberturaProgramadaDTO->isSetNumIdUnidade() && $objReaberturaProgramadaDTO->getNumIdUnidade()!=$objReaberturaProgramadaDTOBanco->getNumIdUnidade()){
        $objInfraException->adicionarValidacao('Não é possível alterar a unidade da reabertura programada.');
      }else{
        $objReaberturaProgramadaDTO->setNumIdUnidade($objReaberturaProgramadaDTOBanco->getNumIdUnidade());
      }

      if ($objReaberturaProgramadaDTO->isSetNumIdAtividade() && $objReaberturaProgramadaDTO->getNumIdAtividade()!=$objReaberturaProgramadaDTOBanco->getNumIdAtividade()){
        $objInfraException->adicionarValidacao('Não é possível alterar a atividade da reabertura programada.');
      }

      if ($objReaberturaProgramadaDTO->isSetDthProcessamento() && $objReaberturaProgramadaDTO->getDthProcessamento()!=$objReaberturaProgramadaDTOBanco->getDthProcessamento()){
        $objInfraException->adicionarValidacao('Não é possível alterar a data de processamento da reabertura programada.');
      }

      if ($objReaberturaProgramadaDTO->isSetDthVisualizacao() && $objReaberturaProgramadaDTO->getDthVisualizacao()!=$objReaberturaProgramadaDTOBanco->getDthVisualizacao()){
        $objInfraException->adicionarValidacao('Não é possível alterar a data de visualização da reabertura programada.');
      }

      if ($objReaberturaProgramadaDTO->isSetStrErro() && $objReaberturaProgramadaDTO->getStrErro()!=$objReaberturaProgramadaDTOBanco->getStrErro()){
        $objInfraException->adicionarValidacao('Não é possível alterar o erro da reabertura programada.');
      }

      if ($objReaberturaProgramadaDTO->isSetDtaProgramada()){
        $this->validarDtaProgramada($objReaberturaProgramadaDTO, $objInfraException);
      }

      $objReaberturaProgramadaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objReaberturaProgramadaDTO->setDthAlteracao(InfraData::getStrDataHoraAtual());

      $objInfraException->lancarValidacoes();

      $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());
      $objReaberturaProgramadaBD->alterar($objReaberturaProgramadaDTO);

    }catch(Exception $e){
      throw new InfraException('Erro alterando Reabertura Programada.',$e);
    }
  }

  protected function excluirControlado($arrObjReaberturaProgramadaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('reabertura_programada_excluir', __METHOD__, $arrObjReaberturaProgramadaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjReaberturaProgramadaDTO);$i++){
        $objReaberturaProgramadaBD->excluir($arrObjReaberturaProgramadaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro excluindo Reabertura Programada.',$e);
    }
  }

  protected function consultarConectado(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('reabertura_programada_consultar', __METHOD__, $objReaberturaProgramadaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());
      $ret = $objReaberturaProgramadaBD->consultar($objReaberturaProgramadaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro consultando Reabertura Programada.',$e);
    }
  }

  protected function listarConectado(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO) {
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('reabertura_programada_listar', __METHOD__, $objReaberturaProgramadaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());
      $ret = $objReaberturaProgramadaBD->listar($objReaberturaProgramadaDTO);

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Reaberturas Programadas.',$e);
    }
  }

  protected function contarConectado(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('reabertura_programada_listar', __METHOD__, $objReaberturaProgramadaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());
      $ret = $objReaberturaProgramadaBD->contar($objReaberturaProgramadaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro contando Reaberturas Programadas.',$e);
    }
  }
/* 
  protected function desativarControlado($arrObjReaberturaProgramadaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('reabertura_programada_desativar', __METHOD__, $arrObjReaberturaProgramadaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjReaberturaProgramadaDTO);$i++){
        $objReaberturaProgramadaBD->desativar($arrObjReaberturaProgramadaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro desativando Reabertura Programada.',$e);
    }
  }

  protected function reativarControlado($arrObjReaberturaProgramadaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('reabertura_programada_reativar', __METHOD__, $arrObjReaberturaProgramadaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());
      for($i=0;$i<count($arrObjReaberturaProgramadaDTO);$i++){
        $objReaberturaProgramadaBD->reativar($arrObjReaberturaProgramadaDTO[$i]);
      }

    }catch(Exception $e){
      throw new InfraException('Erro reativando Reabertura Programada.',$e);
    }
  }

  protected function bloquearControlado(ReaberturaProgramadaDTO $objReaberturaProgramadaDTO){
    try {

      SessaoSEI::getInstance()->validarAuditarPermissao('reabertura_programada_consultar', __METHOD__, $objReaberturaProgramadaDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());
      $ret = $objReaberturaProgramadaBD->bloquear($objReaberturaProgramadaDTO);

      return $ret;
    }catch(Exception $e){
      throw new InfraException('Erro bloqueando Reabertura Programada.',$e);
    }
  }

 */

  protected function processarAgendamentoConectado(){
    try {

      $objReaberturaProgramadaDTO = new ReaberturaProgramadaDTO();
      $objReaberturaProgramadaDTO->retNumIdReaberturaProgramada();
      $objReaberturaProgramadaDTO->retDblIdProtocolo();
      $objReaberturaProgramadaDTO->retNumIdUnidade();
      $objReaberturaProgramadaDTO->setDtaProgramada(InfraData::getStrDataAtual(), InfraDTO::$OPER_MENOR_IGUAL);
      $objReaberturaProgramadaDTO->setDthProcessamento(null);
      $objReaberturaProgramadaDTO->setOrdNumIdUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

      $arrObjReaberturaProgramadaDTO = $this->listar($objReaberturaProgramadaDTO);

      $objProtocoloRN = new ProtocoloRN();
      $objProcedimentoRN = new ProcedimentoRN();
      $objAtividadeRN = new AtividadeRN();
      $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());

      $numIdUnidadeAtual = null;

      foreach($arrObjReaberturaProgramadaDTO as $objReaberturaProgramadaDTO){

        $objProtocoloDTO = new ProtocoloDTO();
        $objProtocoloDTO->retStrProtocoloFormatado();
        $objProtocoloDTO->retStrStaNivelAcessoGlobal();
        $objProtocoloDTO->setDblIdProtocolo($objReaberturaProgramadaDTO->getDblIdProtocolo());
        $objProtocoloDTO = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

        if ($objProtocoloDTO != null) {

          if ($numIdUnidadeAtual!=$objReaberturaProgramadaDTO->getNumIdUnidade()) {
            $numIdUnidadeAtual = $objReaberturaProgramadaDTO->getNumIdUnidade();
            SessaoSEI::getInstance()->simularLogin(SessaoSEI::$USUARIO_SEI, null, null, $numIdUnidadeAtual);
          }

          $numIdAtividade = null;
          $strErro = null;

          if ($objProtocoloDTO->getStrStaNivelAcessoGlobal() == ProtocoloRN::$NA_SIGILOSO) {
            $strErro = 'Processo sigiloso';
          }else {

            $objAtividadeDTO = new AtividadeDTO();
            $objAtividadeDTO->setNumMaxRegistrosRetorno(1);
            $objAtividadeDTO->retNumIdAtividade();
            $objAtividadeDTO->setDblIdProtocolo($objReaberturaProgramadaDTO->getDblIdProtocolo());
            $objAtividadeDTO->setNumIdUnidade($objReaberturaProgramadaDTO->getNumIdUnidade());
            $objAtividadeDTO->setDthConclusao(null);

            if ($objAtividadeRN->consultarRN0033($objAtividadeDTO)!=null) {
              $strErro = 'Processo já estava aberto na unidade';
            }else{
              try {
                $objReabrirProcessoDTO = new ReabrirProcessoDTO();
                $objReabrirProcessoDTO->setDblIdProcedimento($objReaberturaProgramadaDTO->getDblIdProtocolo());
                $objReabrirProcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
                $objReabrirProcessoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
                $objAtividadeDTOConclusao = $objProcedimentoRN->reabrirRN0966($objReabrirProcessoDTO);

                $numIdAtividade = $objAtividadeDTOConclusao->getNumIdAtividade();

              }catch(Throwable $excReabertura){
                $strErro = substr($excReabertura->__toString(),0,250);
              }
            }

            $objAtividadeDTOVisualizacao = new AtividadeDTO();
            $objAtividadeDTOVisualizacao->setDblIdProtocolo($objReaberturaProgramadaDTO->getDblIdProtocolo());
            $objAtividadeDTOVisualizacao->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAtividadeDTOVisualizacao->setNumTipoVisualizacao(AtividadeRN::$TV_NAO_VISUALIZADO | AtividadeRN::$TV_REABERTURA_PROGRAMADA);
            $objAtividadeRN->atualizarVisualizacaoUnidade($objAtividadeDTOVisualizacao);
          }

          $dto = new ReaberturaProgramadaDTO();
          $dto->setDthProcessamento(InfraData::getStrDataHoraAtual());
          $dto->setStrErro($strErro);
          $dto->setNumIdAtividade($numIdAtividade);
          $dto->setNumIdReaberturaProgramada($objReaberturaProgramadaDTO->getNumIdReaberturaProgramada());
          $objReaberturaProgramadaBD->alterar($dto);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro processando agendamento para Reabertura de Processos.',$e);
    }
  }

  protected function configurarVisualizadaControlado(ProcedimentoDTO $objProcedimentoDTO){
    try{

      if ($objProcedimentoDTO->isSetArrObjReaberturaProgramadaDTO() && $objProcedimentoDTO->getArrObjReaberturaProgramadaDTO()!=null){

        $arrObjReaberturaProgramadaDTO = $objProcedimentoDTO->getArrObjReaberturaProgramadaDTO();
        $objReaberturaProgramadaBD = new ReaberturaProgramadaBD($this->getObjInfraIBanco());

        $dthAtual = InfraData::getStrDataHoraAtual();
        foreach ($arrObjReaberturaProgramadaDTO as $objReaberturaProgramadaDTO) {
          $objReaberturaProgramadaDTO2 = new ReaberturaProgramadaDTO();
          $objReaberturaProgramadaDTO2->setDthVisualizacao($dthAtual);
          $objReaberturaProgramadaDTO2->setNumIdReaberturaProgramada($objReaberturaProgramadaDTO->getNumIdReaberturaProgramada());
          $objReaberturaProgramadaBD->alterar($objReaberturaProgramadaDTO2);
        }
      }

    }catch(Exception $e){
      throw new InfraException('Erro configurando visualização de Reaberturas Programadas.', $e);
    }
  }

}
