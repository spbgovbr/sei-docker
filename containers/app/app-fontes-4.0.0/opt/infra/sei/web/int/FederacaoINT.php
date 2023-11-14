<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/01/2020 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class FederacaoINT extends InfraINT {

  public static function montarTabelaAutuacao(ProcedimentoDTO $objProcedimentoDTO){
    $strRet = '';
    $strRet .= '<table id="tblCabecalhoFederacao" width="99.3%" class="infraTable" summary="Cabeçalho do Processo">'."\n";
    $strRet .= '<tr><td width="20%"><b>Órgão:</b></td><td>'.PaginaSEI::tratarHTML($objProcedimentoDTO->getStrDescricaoOrgaoUnidadeGeradoraProtocolo()).'</td></tr>'."\n";
    $strRet .= '<tr><td width="20%"><b>Processo:</b></td><td>'.PaginaSEI::tratarHTML($objProcedimentoDTO->getStrProtocoloProcedimentoFormatado()).'</td></tr>'."\n";
    $strRet .= '<tr><td width="20%"><b>Tipo:</b></td><td>'.PaginaSEI::tratarHTML($objProcedimentoDTO->getStrNomeTipoProcedimento()).'</td></tr>'."\n";
    $strRet .= '<tr><td width="20%"><b>Data de Geração:</b></td><td>'.PaginaSEI::tratarHTML($objProcedimentoDTO->getDtaGeracaoProtocolo()).'</td></tr>'."\n";

    $objProtocoloRN = new ProtocoloRN();
    $arrObjNivelAcessoDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->listarNiveisAcessoRN0878(), 'StaNivel');
    $strNivelAcesso = 'Não Identificado';
    if (isset($arrObjNivelAcessoDTO[$objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo()])){
      $strNivelAcesso = $arrObjNivelAcessoDTO[$objProcedimentoDTO->getStrStaNivelAcessoGlobalProtocolo()]->getStrDescricao();
    }
    $strRet .= '<tr><td width="20%"><b>Nível de Acesso:</b></td><td>'.PaginaSEI::tratarHTML($strNivelAcesso).'</td></tr>'."\n";

    if (count($objProcedimentoDTO->getArrObjParticipanteDTO())==0){
      $strInteressados = '&nbsp;';
    }else{
      $strInteressados = '';
      foreach($objProcedimentoDTO->getArrObjParticipanteDTO() as $objParticipanteDTO){
        $strInteressados .= PaginaSEI::tratarHTML($objParticipanteDTO->getStrNomeContato())."<br /> ";
      }
    }

    $strRet .= '<tr><td width="20%" valign="top"><b>Interessados:</b></td><td> '.$strInteressados.'</td></tr>'."\n";

    $strRet .= '</table>'."\n";
    return $strRet;
  }
}
?>