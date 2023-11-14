<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 20/12/2007 - criado por mga
 *
 * Versão do Gerador de Código: 1.12.0
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__).'/../SEI.php';

class EmailUtilizadoINT extends InfraINT {


  public static function autoCompletarEmail($numIdUnidade,$strPalavrasPesquisa){

    $objEmailUtilizadoDTO=new EmailUtilizadoDTO();
    $objEmailUtilizadoDTO->retStrEmail();
    $objEmailUtilizadoDTO->retNumIdEmailUtilizado();
    $objEmailUtilizadoDTO->setNumIdUnidade($numIdUnidade);
    $objEmailUtilizadoDTO->setStrEmail('%'.$strPalavrasPesquisa.'%',InfraDTO::$OPER_LIKE);
    $objEmailUtilizadoDTO->setOrdStrEmail(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objEmailUtilizadoDTO->setNumMaxRegistrosRetorno(50);

    $objEmailUtilizadoRN=new EmailUtilizadoRN();

    $arrObjEmailUtilizadoDTO = $objEmailUtilizadoRN->listar($objEmailUtilizadoDTO);

    return $arrObjEmailUtilizadoDTO;
  }
}
?>