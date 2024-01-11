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

  public static function formatarNaoSelecionavel($strConteudo){

    //-webkit-touch-callout: none; /* iOS Safari */
    //  -webkit-user-select: none; /* Safari */
    //   -khtml-user-select: none; /* Konqueror HTML */
    //     -moz-user-select: none; /* Old versions of Firefox */
    //      -ms-user-select: none; /* Internet Explorer/Edge */
    //          user-select: none; /* Non-prefixed version, currently
    //                                supported by Chrome, Edge, Opera and Firefox */

    return "\n<div unselectable=\"on\" style=\"-webkit-touch-callout:none;-webkit-user-select:none;-khtml-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;\">\n".$strConteudo."\n</div>\n";
  }
}
?>