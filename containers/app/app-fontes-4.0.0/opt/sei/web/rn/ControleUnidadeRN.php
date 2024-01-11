<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
*
* 01/12/2014 - criado por mga
*
* Versгo do Gerador de Cуdigo: 1.13.1
*
* Versгo no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ControleUnidadeRN extends InfraRN {

  public function __construct(){
    parent::__construct();
  }
 
  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  protected function listarConectado(ControleUnidadeDTO $objControleUnidadeDTO) {
    try {

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_unidade_listar',__METHOD__,$objControleUnidadeDTO);

      //Regras de Negocio
      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objControleUnidadeBD = new ControleUnidadeBD($this->getObjInfraIBanco());
      $ret = $objControleUnidadeBD->listar($objControleUnidadeDTO);

      //Auditoria

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando registros de Unidade Hoje.',$e);
    }
  }

  protected function gerarConectado(AndamentoSituacaoDTO $parObjAndamentoSituacaoDTO){
    try{

      LimiteSEI::getInstance()->configurarNivel2();

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_unidade_gerar',__METHOD__,$parObjAndamentoSituacaoDTO);

      //$objInfraException = new InfraException();

      //$objInfraException->lancarValidacoes();

      $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();
      $objAndamentoSituacaoDTO->setNumTipoFkSituacao(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objAndamentoSituacaoDTO->retDblIdProcedimento();
      $objAndamentoSituacaoDTO->retNumIdSituacao();
      $objAndamentoSituacaoDTO->retStrProtocoloFormatadoProtocolo();
      $objAndamentoSituacaoDTO->retNumIdTipoProcedimentoProcedimento();
      $objAndamentoSituacaoDTO->retStrNomeTipoProcedimento();
      $objAndamentoSituacaoDTO->retStrNomeSituacao();
      $objAndamentoSituacaoDTO->retDthExecucao();
      $objAndamentoSituacaoDTO->retNumIdUsuario();
      $objAndamentoSituacaoDTO->retStrSiglaUsuario();
      $objAndamentoSituacaoDTO->retStrNomeUsuario();
      $objAndamentoSituacaoDTO->retStrSinAtivoSituacao();
      $objAndamentoSituacaoDTO->setStrStaNivelAcessoGlobalProtocolo(ProtocoloRN::$NA_SIGILOSO,InfraDTO::$OPER_DIFERENTE);
      $objAndamentoSituacaoDTO->setNumIdUnidade($parObjAndamentoSituacaoDTO->getNumIdUnidade());
      $objAndamentoSituacaoDTO->setStrSinUltimo('S');

      if ($parObjAndamentoSituacaoDTO->getStrSinSituacoesDesativadas()=='N'){
        $objAndamentoSituacaoDTO->setStrSinAtivoSituacao('S');
      }

      if (!InfraString::isBolVazia($parObjAndamentoSituacaoDTO->getNumIdSituacao())){
        $objAndamentoSituacaoDTO->setNumIdSituacao($parObjAndamentoSituacaoDTO->getNumIdSituacao());
      }

      if (!InfraString::isBolVazia($parObjAndamentoSituacaoDTO->getNumIdTipoProcedimentoProcedimento())){
        $objAndamentoSituacaoDTO->setNumIdTipoProcedimentoProcedimento($parObjAndamentoSituacaoDTO->getNumIdTipoProcedimentoProcedimento());
      }

      if ($parObjAndamentoSituacaoDTO->isOrdDblIdProcedimento()){
        $objAndamentoSituacaoDTO->setOrdDblIdProcedimento($parObjAndamentoSituacaoDTO->getOrdDblIdProcedimento());
      }

      if ($parObjAndamentoSituacaoDTO->isOrdStrNomeTipoProcedimento()){
        $objAndamentoSituacaoDTO->setOrdStrNomeTipoProcedimento($parObjAndamentoSituacaoDTO->getOrdStrNomeTipoProcedimento());
      }

      if ($parObjAndamentoSituacaoDTO->isOrdStrNomeSituacao()){
        $objAndamentoSituacaoDTO->setOrdStrNomeSituacao($parObjAndamentoSituacaoDTO->getOrdStrNomeSituacao());
      }

      if ($parObjAndamentoSituacaoDTO->isOrdStrSiglaUsuario()){
        $objAndamentoSituacaoDTO->setOrdStrSiglaUsuario($parObjAndamentoSituacaoDTO->getOrdStrSiglaUsuario());
      }

      if ($parObjAndamentoSituacaoDTO->isOrdDthExecucao()){
        $objAndamentoSituacaoDTO->setOrdDthExecucao($parObjAndamentoSituacaoDTO->getOrdDthExecucao());
      }


      //paginaзгo
      $objAndamentoSituacaoDTO->setNumMaxRegistrosRetorno($parObjAndamentoSituacaoDTO->getNumMaxRegistrosRetorno());
      $objAndamentoSituacaoDTO->setNumPaginaAtual($parObjAndamentoSituacaoDTO->getNumPaginaAtual());

      $objUnidadeBD = new UnidadeBD($this->getObjInfraIBanco());
      $arrObjAndamentoSituacaoDTO = $objUnidadeBD->listar($objAndamentoSituacaoDTO);

      //paginaзгo
      $parObjAndamentoSituacaoDTO->setNumTotalRegistros($objAndamentoSituacaoDTO->getNumTotalRegistros());
      $parObjAndamentoSituacaoDTO->setNumRegistrosPaginaAtual($objAndamentoSituacaoDTO->getNumRegistrosPaginaAtual());

      if (count($arrObjAndamentoSituacaoDTO)) {

        if ($parObjAndamentoSituacaoDTO->getStrSinSituacoesDesativadas() == 'S') {
          foreach ($arrObjAndamentoSituacaoDTO as $objAndamentoSituacaoDTO) {
            $objAndamentoSituacaoDTO->setStrNomeSituacao(SituacaoINT::formatarSituacaoDesativada($objAndamentoSituacaoDTO->getStrNomeSituacao(), $objAndamentoSituacaoDTO->getStrSinAtivoSituacao()));
          }
        }
      }

      return $arrObjAndamentoSituacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro gerando pontos de controle da unidade.',$e);
    }
  }

  protected function gerarGraficoConectado(AndamentoSituacaoDTO $parObjAndamentoSituacaoDTO){
    try{

      //Valida Permissao
      SessaoSEI::getInstance()->validarAuditarPermissao('controle_unidade_gerar_grafico',__METHOD__,$parObjAndamentoSituacaoDTO);

      $parObjAndamentoSituacaoDTO->setOrdStrNomeTipoProcedimento(InfraDTO::$TIPO_ORDENACAO_ASC);

      $arrObjAndamentoSituacaoDTO = $this->gerar($parObjAndamentoSituacaoDTO);

      $arrGraficoAcompanhamentoGeral = array();
      $arrGraficoAcompanhamentoPorSituacao = array();

      $dblIdControleUnidade = BancoSEI::getInstance()->getValorSequencia('seq_controle_unidade');
      $dthSnapshot = InfraData::getStrDataHoraAtual();
      $objControleUnidadeBD = new ControleUnidadeBD($this->getObjInfraIBanco());

      foreach($arrObjAndamentoSituacaoDTO as $objAndamentoSituacaoDTO){

        $numIdSituacao = $objAndamentoSituacaoDTO->getNumIdSituacao();
        $strNomeSituacao = $objAndamentoSituacaoDTO->getStrNomeSituacao();
        $numIdTipoProcedimento = $objAndamentoSituacaoDTO->getNumIdTipoProcedimentoProcedimento();
        $strNomeTipoProcedimento = $objAndamentoSituacaoDTO->getStrNomeTipoProcedimento();


        if (!isset($arrGraficoAcompanhamentoGeral[$numIdSituacao])) {
          $arrGraficoAcompanhamentoGeral[$numIdSituacao][0] = 1;
          $arrGraficoAcompanhamentoGeral[$numIdSituacao][1] = $strNomeSituacao;
        }else{
          $arrGraficoAcompanhamentoGeral[$numIdSituacao][0]++;
        }

        if (!isset($arrGraficoAcompanhamentoPorSituacao[$numIdSituacao][$numIdTipoProcedimento])){
          $arrGraficoAcompanhamentoPorSituacao[$numIdSituacao][$numIdTipoProcedimento][0] = 1;
          $arrGraficoAcompanhamentoPorSituacao[$numIdSituacao][$numIdTipoProcedimento][1] = $strNomeSituacao;
          $arrGraficoAcompanhamentoPorSituacao[$numIdSituacao][$numIdTipoProcedimento][2] = $strNomeTipoProcedimento;
        }else{
          $arrGraficoAcompanhamentoPorSituacao[$numIdSituacao][$numIdTipoProcedimento][0]++;
        }

        $dto = new ControleUnidadeDTO();
        $dto->setDblIdControleUnidade($dblIdControleUnidade);
        $dto->setDblIdProcedimento($objAndamentoSituacaoDTO->getDblIdProcedimento());
        $dto->setNumIdSituacao($numIdSituacao);
        $dto->setNumIdUsuario($objAndamentoSituacaoDTO->getNumIdUsuario());
        $dto->setDthExecucao($objAndamentoSituacaoDTO->getDthExecucao());
        $dto->setDthSnapshot($dthSnapshot);

        $objControleUnidadeBD->cadastrar($dto);
      }

      $objAndamentoSituacaoDTO = new AndamentoSituacaoDTO();
      $objAndamentoSituacaoDTO->setDblIdControleUnidade($dblIdControleUnidade);
      $objAndamentoSituacaoDTO->setArrGraficoGeral($arrGraficoAcompanhamentoGeral);
      $objAndamentoSituacaoDTO->setArrGraficoPorSituacao($arrGraficoAcompanhamentoPorSituacao);

      return $objAndamentoSituacaoDTO;

    }catch(Exception $e){
      throw new InfraException('Erro gerando grбficos de pontos de controle.',$e);
    }
  }
}
?>