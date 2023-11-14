<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 14/06/2006 - criado por MGA
 *
 * @package infra_php
 */

 		
abstract class InfraLog extends InfraRN {

	public static $ERRO = 'E';
	public static $AVISO = 'A';
	public static $INFORMACAO = 'I';
	public static $DEBUG = 'D';

	public function __construct(InfraIBanco $objInfraIBanco) {
		BancoInfra::setObjInfraIBanco($objInfraIBanco);
	}
	
	public function getNumTipoPK(){
		return InfraDTO::$TIPO_PK_SEQUENCIAL;
	}

	public function isBolTratarTipos(){
		return false;
	}

	protected function inicializarObjInfraIBanco(){
    return BancoInfra::getInstance();
  }
	 
  protected function gravarControlado($str, $strStatipo = 'E') {
		try {  	
		  
		  if (InfraString::isBolVazia($str)){
		    throw new InfraException('Texto do Log não informado.');
		  }

			if (!in_array($strStatipo,array_keys(self::getArrTipos()))){
				throw new InfraException('Tipo do log inválido.');
			}

		  if ($this->getNumTipoPK()==InfraDTO::$TIPO_PK_SEQUENCIAL){
	      $objInfraSequencia = new InfraSequencia($this->getObjInfraIBanco());
	  		$numProxSeq = $objInfraSequencia->obterProximaSequencia('infra_log');
		  }else if ($this->getNumTipoPK()==InfraDTO::$TIPO_PK_NATIVA){
  		  $numProxSeq = $this->getObjInfraIBanco()->getValorSequencia('seq_infra_log');
		  }else{
		  	throw new InfraException('Tipo PK inválida para infra_log.');
		  }

		  $objInfraLogDTO = new InfraLogDTO();
      $objInfraLogDTO->setNumIdInfraLog($numProxSeq);
      $objInfraLogDTO->setDthLog(InfraData::getStrDataHoraAtual());
      $objInfraLogDTO->setStrTextoLog($str);
      $objInfraLogDTO->setStrIp(InfraUtil::getStrIpUsuario());

      if ($this->isBolTratarTipos()){
        $objInfraLogDTO->setStrStaTipo($strStatipo);
      }

      $objInfraLogRN = new InfraLogRN();
      $objInfraLogRN->cadastrar($objInfraLogDTO);

  	  return $numProxSeq;
  	  
		} catch(Exception $e){
			throw new InfraException('Erro gravando log.',$e);
		}	
  }

	public static function getArrTipos(){
		return array(self::$ERRO => 'Erro',
				         self::$AVISO => 'Aviso',
				         self::$INFORMACAO => 'Informação',
				         self::$DEBUG => 'Debug');
	}

  public function gerarTelaListagem($objInfraPagina,$objInfraSessao, $objInfraIBanco){
    PaginaInfra::setObjInfraPagina($objInfraPagina);
    SessaoInfra::setObjInfraSessao($objInfraSessao);
    BancoInfra::setObjInfraIBanco($objInfraIBanco);
    require_once dirname(__FILE__).'/formularios/infra_log_lista.php';
  }
}
?>