<?
try{

  require_once dirname(__FILE__).'/../web/SEI.php';

  session_start();

  SessaoSEI::getInstance(false);

  if ($argc != 2){
    die("USO: ".basename(__FILE__) ." [numero de dias sem movimentacao]\n");
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

  $strIdentificacao = 'Concluir Blocos Disponibilizados - ';

  InfraDebug::getInstance()->gravar(InfraString::excluirAcentos($strIdentificacao.'Iniciando...'));

  $objBlocoDTO = new BlocoDTO();
  $objBlocoDTO->setDistinct(true);
  $objBlocoDTO->retNumIdUnidade();
  $objBlocoDTO->retStrSiglaUnidade();
  $objBlocoDTO->setStrStaTipo(BlocoRN::$TB_INTERNO, InfraDTO::$OPER_DIFERENTE);
  $objBlocoDTO->setStrStaEstado(BlocoRN::$TE_DISPONIBILIZADO);
  $objBlocoDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

  $objBlocoRN = new BlocoRN();
  $arrObjBlocoDTOUnidade = $objBlocoRN->listarRN1277($objBlocoDTO);

  $numTotal = 0;
  foreach ($arrObjBlocoDTOUnidade as $objBlocoDTOUnidade) {

    $objBlocoDTO = new BlocoDTO();
    $objBlocoDTO->setNumIdUnidade($objBlocoDTOUnidade->getNumIdUnidade());
    $objBlocoDTO->setNumDiasSemMovimentacao($argv[1]);

    $arrObjBlocoDTOConcluidos = $objBlocoRN->concluirBlocosDisponibilizados($objBlocoDTO);

    $numBlocos = count($arrObjBlocoDTOConcluidos);

    if ($numBlocos) {

      InfraDebug::getInstance()->gravar($strIdentificacao.$objBlocoDTOUnidade->getStrSiglaUnidade().' ('.$numBlocos.' '.($numBlocos == 1 ? 'bloco' : 'blocos').'): '.implode(',',InfraArray::converterArrInfraDTO($arrObjBlocoDTOConcluidos,'IdBloco')));

      $numTotal += $numBlocos;
    }
  }

  $numSeg = InfraUtil::verificarTempoProcessamento($numSeg);

  InfraDebug::getInstance()->gravar(InfraString::excluirAcentos($strIdentificacao.InfraUtil::formatarMilhares($numTotal).' blocos concluнdos em '.InfraData::formatarTimestamp($numSeg)));

  $objInfraException->lancarValidacao('Operaзгo Finalizada.');

}catch(Exception $e){
  if ($e instanceof InfraException && $e->contemValidacoes()){
    die(InfraString::excluirAcentos($e->__toString())."\n");
  }

  echo(InfraException::inspecionar($e));

  try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
}
?>