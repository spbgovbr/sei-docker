<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4Њ REGIУO
 * 
 * 12/11/2007 - criado por MGA
 *
 */

require_once dirname(__FILE__).'/SEI.php';

class FeedSEIPublicacoes {
	
 	private static $instance = null;
 	private $arrObjInfraIFeed = null;
 	private $bolAcumularFeeds = false;
 	
 	public static function getInstance() { 
	    if (self::$instance == null) { 
        self::$instance = new FeedSEIPublicacoes();
	    } 
	    return self::$instance; 
	} 
 	 
	private function __construct(){

	  $this->arrObjInfraIFeed = array();

	  $objFeedSolrPublicacoes = new FeedSolrPublicacoes();
	  if ($objFeedSolrPublicacoes->getStrServidor()!=null) {
      $this->arrObjInfraIFeed[] = $objFeedSolrPublicacoes;
    }
	}
	
	public function adicionarFeed(InfraFeedDTO $objInfraFeedDTO){
		foreach($this->arrObjInfraIFeed as $objInfraIFeed){
			$objInfraIFeed->adicionar($objInfraFeedDTO);
		}
	}

 	public function setBolAcumularFeeds($bolAcumularFeeds){
 		$this->bolAcumularFeeds = $bolAcumularFeeds;
 	}
 	
 	public function isBolAcumularFeeds(){
 		return $this->bolAcumularFeeds;
 	}
 	
 	public function removerFeed(InfraFeedDTO $objInfraFeedDTO){
 	  foreach($this->arrObjInfraIFeed as $objInfraIFeed){
 	    $objInfraIFeed->remover($objInfraFeedDTO);
 	  }
 	}
 	
	public function indexarFeeds(){
	  
		if ($this->isBolAcumularFeeds()){
			return;
		}
		
		foreach($this->arrObjInfraIFeed as $objInfraIFeed){

      if (ConfiguracaoSEI::getInstance()->getValor('Solr', 'LogarFeeds', false) === true){
        $objFeedDTO = new FeedDTO();
        $objFeedDTO->setStrConteudo(get_class($objInfraIFeed).':'.$objInfraIFeed->__toString());

        $objFeedRN = new FeedRN();
        $objFeedRN->cadastrar($objFeedDTO);
      }

		  try{
		    
		    $objInfraIFeed->indexar();
		    
		  }catch(Exception $e){

        $objInfraIFeed->limpar();

		    //apenas logar erros de indexaчуo
		    LogSEI::getInstance()->gravar(InfraException::inspecionar($e));

		  }
		}
	}
}
?>