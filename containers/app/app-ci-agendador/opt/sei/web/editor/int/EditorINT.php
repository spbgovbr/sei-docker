<?
/*
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 02/12/2011 - criado por bcu
 *
 */

require_once dirname(__FILE__).'/../../SEI.php';

class EditorINT  {
	 public static function montarCss(){
	   echo '<style type="text/css" >';
	   echo '<!--/*--><![CDATA[/*><!--*/'."\n";
	   echo '.cke_skin_v2 .cke_contents {border-style: none solid dotted !important; }'."\n";
	   echo '.cke_skin_v2 .cke_rcombo .cke_text {width:auto !important; }'."\n";
	   echo '.cke_skin_v2 .cke_styles_panel {width:400px !important; }'."\n";
	   echo '/*]]>*/-->'."\n";
	   echo '</style>'."\n";
	 }
  /**
   * @return array
   * @throws InfraException
   */
  public static function getArrImagensPermitidas()
  {
    $objImagemFormatoDTO = new ImagemFormatoDTO();
    $objImagemFormatoDTO->retStrFormato();

    $objImagemFormatoRN = new ImagemFormatoRN();
    $arrImagemPermitida = InfraArray::converterArrInfraDTO($objImagemFormatoRN->listar($objImagemFormatoDTO), 'Formato');
    if (in_array('jpg', $arrImagemPermitida) && !in_array('jpeg', $arrImagemPermitida)) {
      $arrImagemPermitida[] = 'jpeg';
    }
    return $arrImagemPermitida;
  }
}
?>