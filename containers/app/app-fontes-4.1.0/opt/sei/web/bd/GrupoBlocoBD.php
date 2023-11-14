<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/08/2019 - criado por mga
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoBlocoBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }

  public function listarUnidade(GrupoBlocoDTO $objGrupoBlocoDTO){
    try{
      $objGrupoBlocoDTO->retNumIdGrupoBloco();
      $objGrupoBlocoDTO->setBolExclusaoLogica(false);
      $objGrupoBlocoDTO->retNumIdGrupoBloco();
      $objGrupoBlocoDTO->retStrNome();
      $objGrupoBlocoDTO->retStrSinAtivo();
      $objGrupoBlocoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $ret = $this->listar($objGrupoBlocoDTO);

      if (count($ret)){

        $sql = 'select count(*) as total, rel_bloco_unidade.id_grupo_bloco, bloco.sta_tipo '.
               'from rel_bloco_unidade, bloco '.
               'where rel_bloco_unidade.id_bloco=bloco.id_bloco '.
               'and rel_bloco_unidade.id_unidade='.$this->getObjInfraIBanco()->formatarGravacaoNum(SessaoSEI::getInstance()->getNumIdUnidadeAtual()).' '.
               'and '.$this->formatarIn('rel_bloco_unidade.id_grupo_bloco', InfraArray::converterArrInfraDTO($ret,'IdGrupoBloco'), InfraDTO::$PREFIXO_NUM).' '.
               'and (bloco.id_unidade='.$this->getObjInfraIBanco()->formatarGravacaoNum(SessaoSEI::getInstance()->getNumIdUnidadeAtual()).' or bloco.sta_estado='.$this->getObjInfraIBanco()->formatarGravacaoStr(BlocoRN::$TE_DISPONIBILIZADO).') '.
               'group by rel_bloco_unidade.id_grupo_bloco, bloco.sta_tipo';

        $rs = $this->getObjInfraIBanco()->consultarSql($sql);

        foreach($ret as $objGrupoBlocoDTO){

          $objGrupoBlocoDTO->setNumBlocosAssinatura(0);
          $objGrupoBlocoDTO->setNumBlocosInternos(0);
          $objGrupoBlocoDTO->setNumBlocosReuniao(0);

          foreach($rs as $item){
            if ($objGrupoBlocoDTO->getNumIdGrupoBloco()==$this->getObjInfraIBanco()->formatarLeituraNum($item['id_grupo_bloco'])){
              if ($item['sta_tipo'] == BlocoRN::$TB_ASSINATURA) {
                $objGrupoBlocoDTO->setNumBlocosAssinatura($this->getObjInfraIBanco()->formatarLeituraNum($item['total']));
              } else if ($item['sta_tipo'] == BlocoRN::$TB_INTERNO) {
                $objGrupoBlocoDTO->setNumBlocosInternos($this->getObjInfraIBanco()->formatarLeituraNum($item['total']));
              } else if ($item['sta_tipo'] == BlocoRN::$TB_REUNIAO) {
                $objGrupoBlocoDTO->setNumBlocosReuniao($this->getObjInfraIBanco()->formatarLeituraNum($item['total']));
              }
            }
          }
        }
      }

      return $ret;

    }catch(Exception $e){
      throw new InfraException('Erro listando Grupos de Blocos da Unidade.',$e);
    }
  }

}
