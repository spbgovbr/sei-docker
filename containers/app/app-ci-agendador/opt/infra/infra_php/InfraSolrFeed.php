<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/10/2012 - criado por mkr@trf4.jus.br
 *
 * @package infra_php
 */

abstract class InfraSolrFeed implements InfraIFeed{

  private $arrFeedsArquivos = null;
  private $domFeedXml = null;
  private $arrArquivosExcluir = null;

  public function __construct(){
    $this->limpar();
  }

  public abstract function getStrServidor();
  public abstract function getStrCore();
  public abstract function getObjInfraLog();
  public abstract function getDiretorioTemporario();

  public function getCommitWithin(){
    return 60000;
  }

  public function limpar(){
     
    if ($this->domFeedXml!=null){
      unset($this->domFeedXml);
      $this->domFeedXml = null;
    }

    if (is_array($this->arrFeedsArquivos)){
      unset($this->arrFeedsArquivos);
    }
    $this->arrFeedsArquivos = array();

    if (is_array($this->arrArquivosExcluir)){
      foreach($this->arrArquivosExcluir  as $strArquivoExcluir){
        if (file_exists($strArquivoExcluir) !== false){
          unlink($strArquivoExcluir);
        }
      }
      unset($this->arrArquivosExcluir);
    }
    $this->arrArquivosExcluir = array();
  }

  private function montarMetaTags(InfraFeedDTO $objInfraFeedDTO){
    $arrPostFields = array();
    
    $arrPostFields['literal.id'] = $objInfraFeedDTO->getStrUrl();

    if ($objInfraFeedDTO->getArrMetaTags()){
      foreach ($objInfraFeedDTO->getArrMetaTags() as $strKey => $strValue) {
          if (is_array($strValue)) {
              if (substr($strKey, 0, 4) == 'dta_') {
                  for($i=0;$i<count($strValue);$i++) {
                      $strValue[$i] = $this->formatarDta($strValue[$i]);
                  }
              }
              $arrPostFields['multiValued.literal.' . $strKey] = $strValue;
          }
          else {
              $strValue = trim($strValue);
              if ($strValue != '') {
                  if (substr($strKey, 0, 4) == 'dta_') {
                      $strValue = $this->formatarDta($strValue);
                  }
                  $arrPostFields['literal.' . $strKey] = $strValue;
              }
          }
    }
    }
    return $arrPostFields;
  }

  public function adicionar(InfraFeedDTO $objInfraFeedDTO){

    if ($objInfraFeedDTO->getStrCaminhoArquivo()!=null){

      $arrPostFields = $this->montarMetaTags($objInfraFeedDTO);

      if (class_exists('CURLFile', false)){
        $arrPostFields['myfile'] = new CURLFile($objInfraFeedDTO->getStrCaminhoArquivo(), $objInfraFeedDTO->getStrMimeType());
      }else{
        $arrPostFields['myfile'] = '@'.$objInfraFeedDTO->getStrCaminhoArquivo();
        $arrPostFields['stream.contentType'] = $objInfraFeedDTO->getStrMimeType();
      }

      $this->arrFeedsArquivos[] = $arrPostFields;

    }else if ($objInfraFeedDTO->getBinConteudo()!=null){

      $arrPostFields = $this->montarMetaTags($objInfraFeedDTO);
      
      $strFileName = $this->getDiretorioTemporario().'/'.uniqid(md5($objInfraFeedDTO->getStrUrl()).'-').'.solr';
      file_put_contents($strFileName, $objInfraFeedDTO->getBinConteudo());
      $this->arrArquivosExcluir[] = $strFileName;

      if (class_exists('CURLFile', false)) {
        $arrPostFields['myfile'] = new CURLFile($strFileName, $objInfraFeedDTO->getStrMimeType());
      }else{
        $arrPostFields['myfile'] = '@' . $strFileName;
        $arrPostFields['stream.contentType'] = $objInfraFeedDTO->getStrMimeType();
      }

      $this->arrFeedsArquivos[] = $arrPostFields;

    }else{

      $update = null;

      if ($this->domFeedXml == null){
        $this->domFeedXml = new DOMDocument();
        $update = $this->domFeedXml->createElement('update');
        $this->domFeedXml->appendChild($update);
        $add = $this->domFeedXml->createElement('add');
      }else{

        $add = $this->domFeedXml->getElementsByTagName('add')->item(0);

        if ($add == null){
          $update = $this->domFeedXml->getElementsByTagName('update')->item(0);
          $add = $this->domFeedXml->createElement('add');
        }
      }
      $doc = $this->domFeedXml->createElement('doc');
      // ID
      $field = $this->domFeedXml->createElement('field');
      $field->setAttribute('name','id');
      $field->appendChild($this->domFeedXml->createTextNode($objInfraFeedDTO->getStrUrl()));
      $doc->appendChild($field);

      if ($objInfraFeedDTO->getArrMetaTags()){
        foreach ($objInfraFeedDTO->getArrMetaTags() as $strKey => $strValue){

          $strValue = trim($strValue);

          if ($strValue != ''){
            $field = $this->domFeedXml->createElement('field');
            $field->setAttribute('name',$strKey);
            $field->setAttribute('update','set');

            if (substr($strKey,0,4) == 'dta_'){
              $strValue = $this->formatarDta($strValue);
            }

            $field->appendChild($this->domFeedXml->createTextNode(utf8_encode(InfraUtil::filtrarISO88591($strValue))));

            $doc->appendChild($field);
          }
        }
      }

      $add->appendChild($doc);

      if ($update != null){
        $update->appendChild($add);
      }
    }
  }

  public function remover(InfraFeedDTO $objInfraFeedDTO){

    $update = null;

    if ($this->domFeedXml == null){

      $this->domFeedXml = new DOMDocument();
      $update = $this->domFeedXml->createElement('update');
      $this->domFeedXml->appendChild($update);
      $delete = $this->domFeedXml->createElement('delete');

    }else{
      $delete = $this->domFeedXml->getElementsByTagName('delete')->item(0);

      if ($delete==null){
        $update = $this->domFeedXml->getElementsByTagName('update')->item(0);
        $delete = $this->domFeedXml->createElement('delete');
      }
    }

    // ID
    $field = $this->domFeedXml->createElement('id');
    $field->appendChild($this->domFeedXml->createTextNode($objInfraFeedDTO->getStrUrl()));

    $delete->appendChild($field);

    if ($update!=null){
      $update->appendChild($delete);
    }
  }

  public function indexar(){

    if ($this->domFeedXml != null){

      $strUrlSolr = $this->getStrServidor().'/'.$this->getStrCore().'/update';

      if ($this->getCommitWithin()!=null){
        $strUrlSolr .= '?commitWithin='.$this->getCommitWithin();
      }

      $this->enviar($strUrlSolr, $this->getStrFeed(), array("Content-Type: application/xml"));
    }

    if (count($this->arrFeedsArquivos)){

      foreach($this->arrFeedsArquivos as $postfields){
        try{

            $strUrlSolr = $this->getStrServidor().'/'.$this->getStrCore().'/update/extract';

            if ($this->getCommitWithin()!=null){
                $strUrlSolr .= '?commitWithin='.$this->getCommitWithin();
            }

            //Tratar fields MultiValued
            foreach ($postfields as $strKey => $strValue){
              if(strpos($strKey,'multiValued.')!==false){
                $strMultiValued = '';
                foreach ($strValue as $strItemValue){
                   $strMultiValued .= '&'.str_replace('multiValued.','',$strKey).'='.urlencode(utf8_encode($strItemValue));
                }
                $strUrlSolr .= $strMultiValued;
              }
            }
          $this->enviar($strUrlSolr, $postfields, array("Content-Type: multipart/form-data"));

        }catch (Exception $e){
          if ($this->getObjInfraLog() != null){

            if (class_exists('CURLFile', false)){
              $strArquivoErro = $postfields['myfile']->getFilename();
            }else{
              $strArquivoErro = $postfields['myfile'];
            }

            $this->getObjInfraLog()->gravar('Erro enviando arquivo ' .$strArquivoErro .'. ' .InfraException::inspecionar($e));
          }
        }
      }
    }

    $this->limpar();
  }

  private function enviar($urlPagina, $arrPostFields, $arrHttpHeader){

    $ch = curl_init();

    $arrOptions = array(
      CURLOPT_URL        => $urlPagina,
      CURLOPT_POST       => true,
      CURLOPT_POSTFIELDS => $arrPostFields,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FAILONERROR => true,
      CURLOPT_HTTPHEADER => $arrHttpHeader
    );

    curl_setopt_array($ch, $arrOptions);
     
    if (curl_exec($ch)===false){
        $erro=curl_error($ch);
      throw new InfraException('Erro enviando feed ao Solr: '.curl_error($ch) . "\n" . print_r($arrPostFields, true));
    }

    curl_close($ch);
  }

  function getStrFeed(){
    $ret = null;
    if ($this->domFeedXml!=null){
      $ret = $this->domFeedXml->saveXML();
    }
    return $ret;
  }

  public function formatarDta($dta){
    return gmdate("Y-m-d\T00:00:00\Z", strtotime(substr($dta,6,4).'-'.substr($dta,3,2).'-'.substr($dta,0,2)));
  }

  public function formatarDth($dth){
    return gmdate("Y-m-d\Th:i:s\Z", strtotime(substr($dth,6,4).'-'.substr($dth,3,2).'-'.substr($dth,0,2).' '.substr($dth,11,8)));
  }

  public function __toString(){
    $ret = $this->getStrFeed()."\n";
    foreach($this->arrFeedsArquivos as $postfields){
      $ret .= print_r($postfields, true)."\n";
    }
    return $ret;
  }
}
?>