<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/12/2006 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RelHierarquiaUnidadeBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco) {
    parent::__construct($objInfraIBanco);
  }

  public function listarUnidadesNovas(RelHierarquiaUnidadeDTO $objRelHierarquiaUnidadeDTO) {
    try {
      $objInfraBanco = $this->getObjInfraIBanco();

      $sql = ' SELECT a.id_unidade as id_unidade, a.sigla as sigla_unidade ' . ' FROM unidade a ' . ' WHERE NOT EXISTS (SELECT b.id_unidade ' . ' FROM rel_hierarquia_unidade b ' . ' WHERE a.id_unidade=b.id_unidade and b.id_hierarquia=' . $objInfraBanco->formatarGravacaoNum($objRelHierarquiaUnidadeDTO->getNumIdHierarquia()) . ') ' . ' AND sin_global=' . $objInfraBanco->formatarGravacaoStr('N') . ' AND sin_ativo=' . $objInfraBanco->formatarGravacaoStr('S') . ' AND a.id_orgao=' . $objInfraBanco->formatarGravacaoNum($objRelHierarquiaUnidadeDTO->getNumIdOrgaoUnidade()) . ' ORDER BY a.sigla ASC';

      $rs = $objInfraBanco->consultarSql($sql);

      $ret = array();
      foreach ($rs as $item) {
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setNumIdUnidade($objInfraBanco->formatarLeituraNum($item['id_unidade']));
        $objUnidadeDTO->setStrSigla($objInfraBanco->formatarLeituraStr($item['sigla_unidade']));
        $ret[] = $objUnidadeDTO;
      }

      return $ret;
    } catch (Exception $e) {
      throw new InfraException('Erro listando unidades novas para hierarquia.', $e);
    }
  }
}

?>