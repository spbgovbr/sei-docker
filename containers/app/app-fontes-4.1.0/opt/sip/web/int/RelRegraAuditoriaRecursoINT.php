<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/11/2011 - criado por mga
*
*
*/

require_once dirname(__FILE__) . '/../Sip.php';

class RelRegraAuditoriaRecursoINT extends InfraINT {

  public static function montarSelectRecursos($numIdRegraAuditoria) {
    $objRelRegraAuditoriaRecursoDTO = new RelRegraAuditoriaRecursoDTO();
    $objRelRegraAuditoriaRecursoDTO->retNumIdRecurso();
    $objRelRegraAuditoriaRecursoDTO->retStrNomeRecurso();

    $objRelRegraAuditoriaRecursoDTO->setNumIdRegraAuditoria($numIdRegraAuditoria);

    $objRelRegraAuditoriaRecursoDTO->setOrdStrNomeRecurso(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objRelRegraAuditoriaRecursoRN = new RelRegraAuditoriaRecursoRN();
    $arrObjRelRegraAuditoriaRecursoDTO = $objRelRegraAuditoriaRecursoRN->listar($objRelRegraAuditoriaRecursoDTO);

    return parent::montarSelectArrInfraDTO(null, null, null, $arrObjRelRegraAuditoriaRecursoDTO, 'IdRecurso', 'NomeRecurso');
  }
}

?>