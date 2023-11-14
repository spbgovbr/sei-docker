<?php

class InfraBarraProgresso2 {
  const RUNNING = 0;
  const STATUS_CLOSED_CLIENT = -2;
  const STATUS_SERVER_CLOSED = -101;
  const STATUS_ABORTED_CLIENT = -102;
  const PREFIXO = 'infraBP';

  private static $strPath = null;
  private static $arrInstance = null;
  private static $strExcecao = '';
  private static $strValidacao = '';
  private static $strId=null;
  private static $strUrlRedirecionamento=null;
  private static $bolFecharJanela=null;

  private $numMaximo = 100;
  private $numMinimo = 0;
  private $strRotulo = '';
  private $numPosicao = 0;
  private $numStatus = self::RUNNING;
  private $strCorFundo=null;
  private $strCorBorda=null;
  private $strNome;
  private $strArquivo;

  public static function getStrUrlRedirecionamento()
  {
    return self::$strUrlRedirecionamento;
  }
  public static function setStrUrlRedirecionamento($strUrlRedirecionamento)
  {
    self::$strUrlRedirecionamento = $strUrlRedirecionamento;
  }
  public static function getBolFecharJanela()
  {
    return self::$bolFecharJanela;
  }
  public static function setBolFecharJanela($bolFecharJanela)
  {
    self::$bolFecharJanela = $bolFecharJanela;
  }
  public function setStrCorBorda($strCorBorda)
  {
    $this->strCorBorda = $strCorBorda;
  }
  public function setStrCorFundo($strCorFundo)
  {
    $this->strCorFundo = $strCorFundo;
  }
  public function setNumMin($numMinimo)
  {
    $this->numMinimo = $numMinimo;
  }
  public function setNumMax($numMaximo)
  {
    $this->numMaximo = $numMaximo;
  }
  public function getNumMax()
  {
    return $this->numMaximo;
  }
  public function getNumMin()
  {
    return $this->numMinimo;
  }
  public function getNumPosicao()
  {
    return $this->numPosicao;
  }
  public function getStrCorFundo()
  {
    return $this->strCorFundo;
  }
  public function getStrCorBorda()
  {
    return $this->strCorBorda;
  }
  public function getStrRotulo()
  {
    return $this->strRotulo;
  }
  public function setStrRotulo($strRotulo)
  {
    $this->strRotulo = utf8_encode($strRotulo);
    return $this->sendProgress();
  }

  public static function preparar($id=null){
    if (self::$strPath) return;
    self::_buildPath($id);
    self::$arrInstance = array();

    $prefixo=self::PREFIXO.self::$strId.'_';
    $tam_prefixo=strlen($prefixo);
    $diretorio=dir(self::$strPath);
    while(false!==($arquivo=$diretorio->read())){
      if(strpos($arquivo,$prefixo)===0){
        $nome=substr($arquivo,$tam_prefixo,-5);
        $json=json_decode(self::read(self::$strPath.$arquivo),true);
        self::$arrInstance[$nome] = new self($nome, $json);
        if(isset($json['redirect'])) {
          self::$strUrlRedirecionamento=$json['redirect'];
          self::$bolFecharJanela=$json['fechar'];
        }
        self::$arrInstance[$nome]->numStatus=$json['status'];
        self::$strExcecao=$json['excecao'];
        self::$strValidacao=$json['validacao'];
      }
    }
    $diretorio->close();
//    self::_writeProgress();
    return null;
  }
  public static function newInstance($strNome,$arrParametros)
  {
    $strNome=hash("crc32b", $strNome);
    if (self::$arrInstance === null) {
      throw new InfraException('Classe BarraProgresso2 não inicializada.');
    }
    $obj= new self($strNome,$arrParametros);
    self::$arrInstance[$strNome] = $obj;
    $obj->_writeProgress(); // Create the progressbar file
    return $obj;
  }

  public static function getInstance($strNome)
  {
    $strNome=hash("crc32b", $strNome);
    if (isset(self::$arrInstance[$strNome])) {
      return self::$arrInstance[$strNome];
    }
    return null;
  }
  public static function getInstances(){
    return self::$arrInstance;
  }
  private static function _buildPath($id) {
    if ($id==null){
      $id = md5(uniqid(mt_rand()));
    }
    $path = sys_get_temp_dir();
    if (substr($path,strlen($path)-1) != DIRECTORY_SEPARATOR) $path .= DIRECTORY_SEPARATOR;
    self::$strPath = $path;
    self::$strId=$id;
  }
  protected function __construct( $strNome = '', $arrParametros = null) {
    if(is_array($arrParametros)){
      $this->numMaximo = isset($arrParametros['maximo']) ? $arrParametros['maximo'] : 100;
      $this->numMinimo = isset($arrParametros['minimo']) ? $arrParametros['minimo'] : 0;
      $this->numPosicao = isset($arrParametros['posicao']) ? $arrParametros['posicao'] : 0;
      $this->strRotulo = isset($arrParametros['rotulo']) ? $arrParametros['rotulo'] : '';
      $this->strCorBorda= isset($arrParametros['cor_borda']) ? $arrParametros['cor_borda'] : null;
      $this->strCorFundo= isset($arrParametros['cor_fundo']) ? $arrParametros['cor_fundo'] : null;
    } else {
      $this->numMaximo = 100;
      $this->numMinimo = 0;
      $this->strRotulo = '';
      $this->numPosicao= 0;
      $this->strCorBorda= null;
      $this->strCorFundo= null;
    }
    $this->numStatus = self::RUNNING;
    $this->strNome=$strNome;
    $this->strArquivo=self::$strPath.self::PREFIXO.self::$strId.'_'.$this->strNome.'.json';
  }
  public static function read($arquivo) {
    if (!$arquivo) return '';
    if (!is_readable($arquivo)) {
      usleep(50000);
      if (!is_readable($arquivo)) {
        return '';
      }
    }
    try {
      return file_get_contents($arquivo);
    } catch(Exception $e) { return $e->getMessage(); }
  }

  public static function read_all(){
    $ret=array();
    foreach (self::$arrInstance as $instance) {
      $ret[$instance->strNome]=$instance->getData();
    }
    return json_encode($ret);

  }
  public static function setStrExcecao($excecao)
  {
    self::$strExcecao = utf8_encode($excecao);
  }
  public static function setStrValidacao($validacao)
  {
    self::$strValidacao = utf8_encode(str_replace("\\n","\n",$validacao));
  }
  public static function getId()
  {
    return self::$strId;
  }

  function setNumPosicao($numPosicao) {
    $this->numPosicao=$numPosicao;
    return $this->sendProgress();
  }
  function moverProximo() {
    $this->numPosicao++;
    return $this->sendProgress();
  }

  function sendProgress() {
    if ($this->numStatus == self::STATUS_SERVER_CLOSED) return false;
    if ($this->numPosicao === null || $this->numPosicao<$this->numMinimo || $this->numPosicao>$this->numMaximo){
      $this->numPosicao=false;
    }
    $progressAborted = $this->isAborted(); // Sets my status if needed
    if($progressAborted){
      self::$strValidacao='Processamento cancelado.';
      foreach (self::$arrInstance as $instance) {
        $instance->numStatus=self::STATUS_SERVER_CLOSED;
        $instance->_writeProgress();
      }
      die;
    }
    $this->_writeProgress();
    return !$progressAborted;
  }

  protected function _writeProgress() {

    file_put_contents($this->strArquivo,json_encode($this->getData()));
  }

  private function getData() {
    $ret=array(
        'rotulo' => $this->strRotulo,
        'cor_fundo' => $this->strCorFundo,
        'cor_borda' => $this->strCorBorda,
        'maximo' => $this->numMaximo,
        'minimo' => $this->numMinimo,
        'posicao' => $this->numPosicao,
        'status' => $this->numStatus,
        'validacao' => self::$strValidacao,
        'excecao' => self::$strExcecao
    );
    if (self::$bolFecharJanela!=null || self::$strUrlRedirecionamento!=null){
      return array_merge($ret,array('fechar'=>self::$bolFecharJanela,'redirect'=>self::$strUrlRedirecionamento));
    }
    return $ret;
  }
  public function close() {
    $this->numStatus = self::STATUS_SERVER_CLOSED;
    $this->_writeProgress();
  }
  public function isAborted() {
    if ($this->numStatus == self::STATUS_ABORTED_CLIENT) return true;
    /* Verify if the client aborted */
    $jsonData = self::read($this->strArquivo);
    if ($jsonData) {
      $progressData = json_decode($jsonData,true);
      if ($progressData['status'] == self::STATUS_ABORTED_CLIENT) {
        $this->numStatus = self::STATUS_ABORTED_CLIENT;
        return true;
      }
    }
    return false;
  }

  public function isRunning() {
    if ($this->numStatus == self::STATUS_SERVER_CLOSED) return false;
    if ($this->isAborted()) return false;
    return true;
  }


  public function client_close()
  {
    try {
      if (is_file($this->strArquivo)) unlink($this->strArquivo);
    } catch (Exception $e) {
    }
    return '';
  }

  public function abort()
  {
    $jsonData = self::read($this->strArquivo);
    if ($jsonData) {
      $progressData = json_decode($jsonData, true);
      if ($progressData['status'] == self::STATUS_ABORTED_CLIENT) {
        $this->numStatus = self::STATUS_ABORTED_CLIENT;
        return true;
      }
      $this->strRotulo = $progressData['rotulo'];
      $this->numMaximo = $progressData['maximo'];
      $this->numMinimo = $progressData['minimo'];
      $this->numPosicao = $progressData['posicao'];
      self::$strExcecao = $progressData['excecao'];
      self::$strValidacao=$progressData['validacao'];
    }
    $this->numStatus = self::STATUS_ABORTED_CLIENT;
    $this->_writeProgress();
    return true;
  }
}


