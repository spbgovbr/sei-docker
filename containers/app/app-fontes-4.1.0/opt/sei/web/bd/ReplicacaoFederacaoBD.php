<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ReplicacaoFederacaoBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }

  public function removerExpirados(){

    try{

      $strDataHoraUTC = gmdate("d/m/Y H:i:s");

      $numDiasTentativasReplicacao = ConfiguracaoSEI::getInstance()->getValor('Federacao', 'NumDiasTentativasReplicacao', false);

      if (!is_numeric($numDiasTentativasReplicacao) || $numDiasTentativasReplicacao <= 0) {
        $numDiasTentativasReplicacao = 3;
      }

      $sql = 'delete from replicacao_federacao where dth_cadastro <= '.$this->getObjInfraIBanco()->formatarGravacaoDth(InfraData::calcularData($numDiasTentativasReplicacao, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ATRAS, $strDataHoraUTC));

      return $this->getObjInfraIBanco()->executarSql($sql);

    }catch(Exception $e){
      throw new InfraException('Erro removendo registros de replicação do SEI Federação expirados.',$e);
    }
  }

}
