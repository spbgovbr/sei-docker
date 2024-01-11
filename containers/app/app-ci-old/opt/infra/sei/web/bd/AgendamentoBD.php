<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/04/2013 - criado por mga
*
* Versão do Gerador de Código: 1.17.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AgendamentoBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }
  
  public function removerDadosEstatisticas(){

    try{
      
      $sql = 'delete from estatisticas where dth_snapshot <= '.$this->getObjInfraIBanco()->formatarGravacaoDth(InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS, InfraData::getStrDataHoraAtual()));

      return $this->getObjInfraIBanco()->executarSql($sql);
      
    }catch(Exception $e){
      throw new InfraException('Erro removendo dados de estatísticas.',$e);
    }
  }

  public function removerDadosControleUnidade(){

    try{

      $sql = 'delete from controle_unidade where dth_snapshot <= '.$this->getObjInfraIBanco()->formatarGravacaoDth(InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS, InfraData::getStrDataHoraAtual()));

      return $this->getObjInfraIBanco()->executarSql($sql);

    }catch(Exception $e){
      throw new InfraException('Erro removendo dados de controle de unidade.',$e);
    }
  }

  public function removerDadosTemporariosAuditoria(){

    try{
      
      $sql = 'delete from auditoria_protocolo where dta_auditoria <= '.$this->getObjInfraIBanco()->formatarGravacaoDta(InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS, InfraData::getStrDataAtual()));
      
      return $this->getObjInfraIBanco()->executarSql($sql);
      
    }catch(Exception $e){
      throw new InfraException('Erro removendo dados temporários de auditoria.',$e);
    }
  }
  
}
?>