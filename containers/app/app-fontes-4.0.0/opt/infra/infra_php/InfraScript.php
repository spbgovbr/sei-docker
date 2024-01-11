<?

abstract class InfraScript extends InfraRN {

  protected $numSeg = 0;
  private $strNome = null;

  public function __construct(){
    parent::__construct();
  }

  public function getStrNome()
  {
    return $this->strNome;
  }

  public function setStrNome($strNome)
  {
    $this->strNome = $strNome;
  }

  protected function inicializar($strTitulo){

    ini_set('max_execution_time','0');
    ini_set('memory_limit','-1');

    try {
      @ini_set('zlib.output_compression','0');
      @ini_set('implicit_flush', '1');
    }catch(Exception $e){}

    ob_implicit_flush();

    InfraDebug::getInstance()->setBolLigado(true);
    InfraDebug::getInstance()->setBolDebugInfra(true);
    InfraDebug::getInstance()->setBolEcho(true);
    InfraDebug::getInstance()->limpar();

    $this->numSeg = InfraUtil::verificarTempoProcessamento();

    $this->logar($strTitulo);
  }

  protected function logar($strMsg){

    if ($this->getStrNome()!=null){
      $strMsg = $this->getStrNome().' - '.$strMsg;
    }

    InfraDebug::getInstance()->gravar($strMsg);

    flush();
  }

  protected function processarErro($strMsg, $e = null){

    $this->logar('ERRO: '.$strMsg);

    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(false);
    InfraDebug::getInstance()->setBolEcho(false);

    throw new InfraException($strMsg, $e);
  }

  protected function finalizar($strMsg){

    $this->numSeg = InfraUtil::verificarTempoProcessamento($this->numSeg);
    $this->logar('TEMPO TOTAL DE EXECUCAO: ' . $this->numSeg . ' s');

    $this->logar($strMsg);

    InfraDebug::getInstance()->setBolLigado(false);
    InfraDebug::getInstance()->setBolDebugInfra(false);
    InfraDebug::getInstance()->setBolEcho(false);
  }

  protected function verificarParcial($numRegistros, $numTotal, $numParcial){
    return (($numRegistros >= $numParcial && $numRegistros % $numParcial == 0) || $numRegistros == $numTotal);
  }
}
?>