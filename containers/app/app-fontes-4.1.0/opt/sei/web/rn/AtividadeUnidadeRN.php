<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/09/2022 - criado por mgb29
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class AtividadeUnidadeRN extends InfraRN {

  public static $T_TOTAIS = '1';
  public static $T_DETALHADO = '2';

  public function __construct() {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco() {
    return BancoSEI::getInstance();
  }


  public function listarValoresTipo() {
    $arr = array();

    $objInfraValorStaDTO = new InfraValorStaDTO();
    $objInfraValorStaDTO->setStrStaValor(self::$T_TOTAIS);
    $objInfraValorStaDTO->setStrDescricao('Totais');
    $arr[] = $objInfraValorStaDTO;

    $objInfraValorStaDTO = new InfraValorStaDTO();
    $objInfraValorStaDTO->setStrStaValor(self::$T_DETALHADO);
    $objInfraValorStaDTO->setStrDescricao('Detalhado');
    $arr[] = $objInfraValorStaDTO;

    return $arr;
  }

  protected function pesquisarConectado(AtividadeUnidadeDTO $parObjAtividadeUnidadeDTO) {
    try {

      LimiteSEI::getInstance()->configurarNivel3();

      $ret = array();

      SessaoSEI::getInstance()->validarAuditarPermissao('atividade_unidade_pesquisar', __METHOD__, $parObjAtividadeUnidadeDTO);

      //Regras de Negocio
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($parObjAtividadeUnidadeDTO->getNumIdUnidade())) {
        $objInfraException->lancarValidacao('Unidade não informada.');
      }

      if ($parObjAtividadeUnidadeDTO->getNumIdUnidade() != SessaoSEI::getInstance()->getNumIdUnidadeAtual() && !SessaoSEI::getInstance()->verificarPermissao('atividade_unidade_orgao')) {
        $objInfraException->lancarValidacao('Usuário sem permissão para pesquisar nesta unidade.');
      }

      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->setBolExclusaoLogica(false);
      $objUnidadeDTO->retNumIdOrgao();
      $objUnidadeDTO->setNumIdUnidade($parObjAtividadeUnidadeDTO->getNumIdUnidade());

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

      if ($objUnidadeDTO==null){
        throw new InfraException('Unidade não encontrada.');
      }

      if ($objUnidadeDTO->getNumIdOrgao()!=SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual()){
        $objInfraException->lancarValidacao('Usuário sem permissão para pesquisar neste órgão.');
      }

      if (InfraString::isBolVazia($parObjAtividadeUnidadeDTO->getNumIdUsuario())) {
        $objInfraException->lancarValidacao('Usuário não informado.');
      }

      InfraData::validarPeriodo($parObjAtividadeUnidadeDTO->getDtaInicio(), $parObjAtividadeUnidadeDTO->getDtaFim(), $objInfraException);

      $dtaFimMaximo = InfraData::calcularData(1, InfraData::$UNIDADE_ANOS, InfraData::$SENTIDO_ADIANTE, $parObjAtividadeUnidadeDTO->getDtaInicio());
      if ((InfraData::compararDatas($parObjAtividadeUnidadeDTO->getDtaFim(), $dtaFimMaximo) - 1) < 0) {
        $objInfraException->adicionarValidacao('Período não pode ser superior a 1 ano.');
      }

      if (!in_array($parObjAtividadeUnidadeDTO->getStrStaTipo(),InfraArray::converterArrInfraDTO($this->listarValoresTipo(),'StaValor'))){
        $objInfraException->lancarValidacao('Tipo do relatório inválido.');
      }

      $objInfraException->lancarValidacoes();


      $objAtividadeDTO = new AtividadeDTO();
      $objAtividadeDTO->retNumIdAtividade();
      $objAtividadeDTO->retDblIdProtocolo();

      if ($parObjAtividadeUnidadeDTO->getStrStaTipo() == self::$T_TOTAIS) {
        $objAtividadeDTO->retNumIdTarefa();
      }

      $objAtividadeDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO, InfraDTO::$OPER_DIFERENTE);
      $objAtividadeDTO->setNumIdUnidade($parObjAtividadeUnidadeDTO->getNumIdUnidade());
      $objAtividadeDTO->setNumIdUsuarioOrigem($parObjAtividadeUnidadeDTO->getNumIdUsuario());
      $objAtividadeDTO->setStrSinHistoricoCompletoTarefa('S');

      if ($parObjAtividadeUnidadeDTO->isSetNumIdTarefa()){
        $objAtividadeDTO->setNumIdTarefa($parObjAtividadeUnidadeDTO->getNumIdTarefa());
      }

      $objAtividadeDTO->adicionarCriterio(
        array('Abertura', 'Abertura'),
        array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
        array($parObjAtividadeUnidadeDTO->getDtaInicio() . ' 00:00:00', $parObjAtividadeUnidadeDTO->getDtaFim() . ' 23:59:59'),
        array(InfraDTO::$OPER_LOGICO_AND),
        'cAbertura');

      $objAtividadeDTO->adicionarCriterio(
        array('Conclusao', 'Conclusao'),
        array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
        array($parObjAtividadeUnidadeDTO->getDtaInicio() . ' 00:00:00', $parObjAtividadeUnidadeDTO->getDtaFim() . ' 23:59:59'),
        array(InfraDTO::$OPER_LOGICO_AND),
        'cConclusao');

      $objAtividadeDTO->agruparCriterios(array('cAbertura', 'cConclusao'), InfraDTO::$OPER_LOGICO_OR);

      $objAtividadeDTO->setOrdNumIdAtividade(InfraDTO::$TIPO_ORDENACAO_DESC);

      if ($parObjAtividadeUnidadeDTO->getStrStaTipo()==self::$T_DETALHADO) {

        //paginação
        $objAtividadeDTO->setNumMaxRegistrosRetorno($parObjAtividadeUnidadeDTO->getNumMaxRegistrosRetorno());
        $objAtividadeDTO->setNumPaginaAtual($parObjAtividadeUnidadeDTO->getNumPaginaAtual());

        $objAtividadeRN = new AtividadeRN();
        $arrObjAtividadeDTO = $objAtividadeRN->listarRN0036($objAtividadeDTO);

        //paginação
        $parObjAtividadeUnidadeDTO->setNumTotalRegistros($objAtividadeDTO->getNumTotalRegistros());
        $parObjAtividadeUnidadeDTO->setNumRegistrosPaginaAtual($objAtividadeDTO->getNumRegistrosPaginaAtual());

        $arrHistorico = array();

        if (count($arrObjAtividadeDTO)) {

          $objProcedimentoRN = new ProcedimentoRN();

          $arrObjAtividadeDTO = InfraArray::indexarArrInfraDTO($arrObjAtividadeDTO, 'IdProtocolo', true);

          foreach ($arrObjAtividadeDTO as $dblIdProtocolo => $arr) {

            $objProcedimentoHistoricoDTO = new ProcedimentoHistoricoDTO();
            $objProcedimentoHistoricoDTO->setDblIdProcedimento($dblIdProtocolo);
            $objProcedimentoHistoricoDTO->setStrStaHistorico(ProcedimentoRN::$TH_PERSONALIZADO);
            $objProcedimentoHistoricoDTO->setStrSinGerarLinksHistorico('S');
            $objProcedimentoHistoricoDTO->setNumIdAtividade(InfraArray::converterArrInfraDTO($arr,'IdAtividade'));
            $objProcedimentoDTOHistorico = $objProcedimentoRN->consultarHistoricoRN1025($objProcedimentoHistoricoDTO);

            $arrObjAtividadeDTOHistorico = $objProcedimentoDTOHistorico->getArrObjAtividadeDTO();

            /** @var AtividadeDTO $objAtividadeDTO */
            foreach ($arrObjAtividadeDTOHistorico as $objAtividadeDTO) {
              $objAtividadeDTO->setStrProtocoloFormatadoProtocolo($objProcedimentoDTOHistorico->getStrProtocoloProcedimentoFormatado());
              $objAtividadeDTO->setStrNomeTipoProcedimentoProtocolo($objProcedimentoDTOHistorico->getStrNomeTipoProcedimento());
            }

            $arrHistorico = array_merge($arrHistorico, $objProcedimentoDTOHistorico->getArrObjAtividadeDTO());
          }
        }

        InfraArray::ordenarArrInfraDTO($arrHistorico, 'IdAtividade', InfraDTO::$TIPO_ORDENACAO_DESC);

        $ret = array();
        foreach($arrHistorico as $objAtividadeDTO){
          $objAtividadeUnidadeDTO = new AtividadeUnidadeDTO();
          $objAtividadeUnidadeDTO->setNumIdAtividade($objAtividadeDTO->getNumIdAtividade());
          $objAtividadeUnidadeDTO->setDblIdProcedimento($objAtividadeDTO->getDblIdProtocolo());
          $objAtividadeUnidadeDTO->setStrProtocoloFormatadoProcedimento($objAtividadeDTO->getStrProtocoloFormatadoProtocolo());
          $objAtividadeUnidadeDTO->setStrNomeTipoProcedimento($objAtividadeDTO->getStrNomeTipoProcedimentoProtocolo());
          $objAtividadeUnidadeDTO->setNumIdUnidade($objAtividadeDTO->getNumIdUnidade());
          $objAtividadeUnidadeDTO->setStrSiglaUnidade($objAtividadeDTO->getStrSiglaUnidade());
          $objAtividadeUnidadeDTO->setStrDescricaoUnidade($objAtividadeDTO->getStrDescricaoUnidade());
          $objAtividadeUnidadeDTO->setNumIdUsuario($objAtividadeDTO->getNumIdUsuarioOrigem());
          $objAtividadeUnidadeDTO->setStrSiglaUsuario($objAtividadeDTO->getStrSiglaUsuarioOrigem());
          $objAtividadeUnidadeDTO->setStrNomeUsuario($objAtividadeDTO->getStrNomeUsuarioOrigem());
          $objAtividadeUnidadeDTO->setDthAbertura($objAtividadeDTO->getDthAbertura());
          $objAtividadeUnidadeDTO->setStrNomeTarefa($objAtividadeDTO->getStrNomeTarefa());
          $ret[] = $objAtividadeUnidadeDTO;
        }

      }else{

        $objAtividadeRN = new AtividadeRN();
        $arrObjAtividadeDTO = InfraArray::indexarArrInfraDTO($objAtividadeRN->listarRN0036($objAtividadeDTO), 'IdTarefa', true);

        $objTarefaDTO = new TarefaDTO();
        $objTarefaDTO->retNumIdTarefa();
        $objTarefaDTO->retStrNome();

        $objTarefaRN = new TarefaRN();
        $arrObjTarefaDTO = InfraArray::indexarArrInfraDTO($objTarefaRN->listar($objTarefaDTO),'IdTarefa');

        foreach ($arrObjAtividadeDTO as $numIdTarefa => $arrObjAtividadeDTOTarefa) {

          $strNomeTarefa = '';
          if ($numIdTarefa == TarefaRN::$TI_ATUALIZACAO_ANDAMENTO){
            $strNomeTarefa = 'Atualização de andamento';
          }else if ($numIdTarefa == TarefaRN::$TI_PUBLICACAO){
            $strNomeTarefa = str_replace('@MOTIVO@', 'Publicação', $arrObjTarefaDTO[$numIdTarefa]->getStrNome());
          }else{
            $strNomeTarefa = $arrObjTarefaDTO[$numIdTarefa]->getStrNome();
          }

          $strNomeTarefa = preg_replace('/(?>([[(])?@[A-Za-z_0-9-]+@(?(1)[])])[\s\.]*)+/m', "... ", $strNomeTarefa);
          $strNomeTarefa = preg_replace('/(\.\.\. ){2,}/m', "$1", $strNomeTarefa);
          $strNomeTarefa = str_replace("\r",'',$strNomeTarefa);
          $strNomeTarefa = str_replace("\n",'',$strNomeTarefa);
          $strNomeTarefa = str_replace('"... "','...',$strNomeTarefa);

          $objAtividadeUnidadeDTO = new AtividadeUnidadeDTO();
          $objAtividadeUnidadeDTO->setNumIdTarefa($numIdTarefa);
          $objAtividadeUnidadeDTO->setStrNomeTarefa($strNomeTarefa);
          $objAtividadeUnidadeDTO->setNumTotalTarefas(count($arrObjAtividadeDTOTarefa));
          $ret[] = $objAtividadeUnidadeDTO;
        }
      }

      return $ret;

    } catch (Exception $e) {
      throw new InfraException('Erro gerando relatório de Atividade na Unidade.', $e);
    }
  }
}
