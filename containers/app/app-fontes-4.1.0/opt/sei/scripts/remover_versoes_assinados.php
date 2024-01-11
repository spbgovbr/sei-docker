<?
try{

  require_once dirname(__FILE__).'/../web/SEI.php';

  session_start();

  SessaoSEI::getInstance(false);

  if ($argc != 2){
    die("USO: ".basename(__FILE__) ." [numero de dias da assinatura]\n");
  }

  if (!is_numeric($argv[1]) || $argv[1] <= 0){
    die("Numero de dias invalido.\n");
  }

  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->setBolEcho(true);
  InfraDebug::getInstance()->limpar();

  $objInfraException = new InfraException();

  LimiteSEI::getInstance()->configurarNivel3();

  $numSeg = InfraUtil::verificarTempoProcessamento();

  $strIdentificacao = 'Remover Versoes Assinados - ';

  InfraDebug::getInstance()->gravar(InfraString::excluirAcentos($strIdentificacao.'Iniciando...'));

  $objAtividadeDTO = new AtividadeDTO();
  $objAtividadeDTO->retDthAbertura();
  $objAtividadeDTO->setOrdDthAbertura(InfraDTO::$TIPO_ORDENACAO_ASC);
  $objAtividadeDTO->setNumMaxRegistrosRetorno(1);

  $objAtividadeRN = new AtividadeRN();
  $objAtividadeDTO = $objAtividadeRN->consultarRN0033($objAtividadeDTO);

  $numTotal = 0;
  if ($objAtividadeDTO) {

    $dtaInicial = InfraData::calcularData($argv[1], InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS);
    $dtaFinal = substr($objAtividadeDTO->getDthAbertura(), 0, 10);

    $objAssinaturaRN = new AssinaturaRN();
    $objDocumentoRN = new DocumentoRN();

    while (InfraData::compararDatas($dtaFinal, $dtaInicial) >= 0) {

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->setDistinct(true);
      $objAssinaturaDTO->retDblIdDocumento();
      $objAssinaturaDTO->setStrSinVersoesDocumento('S');
      $objAssinaturaDTO->setNumIdTarefaAtividade(TarefaRN::$TI_ASSINATURA_DOCUMENTO);
      $objAssinaturaDTO->adicionarCriterio(array('AberturaAtividade', 'AberturaAtividade'),
        array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
        array($dtaInicial.' 00:00:00', $dtaInicial.' 23:59:59'),
        InfraDTO::$OPER_LOGICO_AND);

      $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);

      $numDocs = 0;
      foreach ($arrObjAssinaturaDTO as $objAssinaturaDTO) {
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setDblIdDocumento($objAssinaturaDTO->getDblIdDocumento());
        $objDocumentoRN->removerVersoes($objDocumentoDTO);
        $numDocs++;
      }

      if ($numDocs) {
        InfraDebug::getInstance()->gravar($dtaInicial.' ('.$numDocs.' documentos)');
        $numTotal += $numDocs;
      }

      $dtaInicial = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS, $dtaInicial);
    }
  }

  $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

  InfraDebug::getInstance()->gravar(InfraString::excluirAcentos($strIdentificacao.InfraUtil::formatarMilhares($numTotal).' documentos em '.InfraData::formatarTimestamp($numSeg)));

  $objInfraException->lancarValidacao('Operaчуo Finalizada.');

}catch(Exception $e){
  if ($e instanceof InfraException && $e->contemValidacoes()){
    die(InfraString::excluirAcentos($e->__toString())."\n");
  }

  echo(InfraException::inspecionar($e));

  try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
}
?>