<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class AtributoINT extends InfraINT {

  public static function montarSelectAplicabilidade($strValorItemSelecionado){
		$objAtributoRN = new AtributoRN();
																					
    $arrObjTipoAtributo = $objAtributoRN->tiposAtributoRN0591();

    return parent::montarSelectArrInfraDTO('null', '&nbsp;', $strValorItemSelecionado, $arrObjTipoAtributo, 'StaTipo', 'Descricao');
  }

  public static function obterDescricao($strValue){
  	$objAtributoRN = new AtributoRN();
  	$arrObjAtributoDTO = $objAtributoRN->tiposAtributoRN0591();

  	foreach($arrObjAtributoDTO as $dto){
  	  if ($dto->getStrStaTipo()==$strValue){
  	    return $dto->getStrDescricao();
  	  }
  	}
 	  return null;
  }

  private static function getStrCssWidth($numTamanho){
    $numWidth = $numTamanho;
    if ($numWidth > 95){
      $numWidth = 95;
    }else if ($numWidth < 10){
      $numWidth = 10;
    }
    return 'width:'.$numWidth.'%';
  }

  public static function montarItensTabelaValores($numIdAtributo){
    $objDominioDTO = new DominioDTO();
    $objDominioDTO->setBolExclusaoLogica(false);
    $objDominioDTO->retNumIdDominio();
    $objDominioDTO->retStrValor();
    $objDominioDTO->retStrRotulo();
    $objDominioDTO->retNumOrdem();
    $objDominioDTO->retStrSinPadrao();
    $objDominioDTO->retStrSinAtivo();
    $objDominioDTO->setNumIdAtributo($numIdAtributo);
    $objDominioDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objDominioDTO->setOrdStrRotulo(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objDominioRN = new DominioRN();
    $arrObjDominioDTO = $objDominioRN->listarRN0199($objDominioDTO);

    $arrValores = array();
    foreach($arrObjDominioDTO as $objDominioDTO){
      $arrValores[] = array($objDominioDTO->getNumIdDominio(),PaginaSEI::tratarHTML($objDominioDTO->getStrValor()),PaginaSEI::tratarHTML($objDominioDTO->getStrRotulo()),PaginaSEI::tratarHTML($objDominioDTO->getNumOrdem()),self::lerSinalizadorDominio($objDominioDTO->getStrSinPadrao()),self::lerSinalizadorDominio($objDominioDTO->getStrSinAtivo()));
    }

    return PaginaSEI::getInstance()->gerarItensTabelaDinamica(array_reverse($arrValores));
  }

  public static function lerSinalizadorDominio($strSinalizador){
    return ($strSinalizador=='S')?'S':'';
  }

  public static function gravarSinalizadorDominio($strSinalizador){
    return ($strSinalizador=='S')?'S':'N';
  }

  public static function montar($dblIdProtocolo, $numIdTipoFormulario, &$html, &$javascript){
  
    $html = '<div id="divInfraAreaDadosAtributos" class="infraAreaDadosDinamica">'."\n\n";

    $javascript = '';

    if ($dblIdProtocolo!=null) {
      $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
      $objRelProtocoloAtributoDTO->retNumIdAtributo();
      $objRelProtocoloAtributoDTO->retStrValor();
      $objRelProtocoloAtributoDTO->setDblIdProtocolo($dblIdProtocolo);

      $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
      $arrObjRelProtocoloAtributoDTO = InfraArray::indexarArrInfraDTO($objRelProtocoloAtributoRN->listar($objRelProtocoloAtributoDTO), 'IdAtributo');
    }else{
      $arrObjRelProtocoloAtributoDTO = array();
    }

    $objAtributoDTO = new AtributoDTO();
    $objAtributoDTO->setBolExclusaoLogica(false);
    $objAtributoDTO->retNumIdAtributo();
    $objAtributoDTO->retStrNome();
    $objAtributoDTO->retStrRotulo();
    $objAtributoDTO->retNumOrdem();
    $objAtributoDTO->retStrSinObrigatorio();
    $objAtributoDTO->retStrStaTipo();
    $objAtributoDTO->retNumTamanho();
    $objAtributoDTO->retNumDecimais();
    $objAtributoDTO->retStrValorMinimo();
    $objAtributoDTO->retStrValorMaximo();
    $objAtributoDTO->retStrValorPadrao();
    $objAtributoDTO->retNumLinhas();
    $objAtributoDTO->retStrMascara();
    $objAtributoDTO->retStrSinAtivo();
    $objAtributoDTO->setNumIdTipoFormulario($numIdTipoFormulario);

    if (count($arrObjRelProtocoloAtributoDTO)){
      $objAtributoDTO->setBolExclusaoLogica(false);
      $objAtributoDTO->setNumIdAtributo(array_keys($arrObjRelProtocoloAtributoDTO), InfraDTO::$OPER_IN);
    }

    $objAtributoDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objAtributoDTO->setOrdStrRotulo(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objAtributoRN = new AtributoRN();
    $arrObjAtributoDTO = $objAtributoRN->listarRN0165($objAtributoDTO);

    if ($dblIdProtocolo!=null) {
      $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
      $objRelProtocoloAtributoDTO->retNumIdAtributo();
      $objRelProtocoloAtributoDTO->retStrValor();
      $objRelProtocoloAtributoDTO->setDblIdProtocolo($dblIdProtocolo);

      $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
      $arrObjRelProtocoloAtributoDTO = InfraArray::indexarArrInfraDTO($objRelProtocoloAtributoRN->listar($objRelProtocoloAtributoDTO), 'IdAtributo');
    }else{
      $arrObjRelProtocoloAtributoDTO = array();
    }

    foreach($arrObjAtributoDTO as $objAtributoDTO){

      if ($objAtributoDTO->getStrSinAtivo()=='N' && ($dblIdProtocolo==null || !isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]))) {
        continue;
      }

      $strRotuloJs = PaginaSEI::getInstance()->formatarParametrosJavaScript(trim($objAtributoDTO->getStrRotulo()));
      if (substr($strRotuloJs,-1)==':'){
        $strRotuloJs = substr($strRotuloJs,0,strlen($strRotuloJs)-1);
      }

      switch ($objAtributoDTO->getStrStaTipo()) {
        case AtributoRN::$TA_LISTA:

          $strIdCampo = 'selAtributo' . $objAtributoDTO->getNumIdAtributo();

          $html .= '<label id="lblAtributo' . $objAtributoDTO->getNumIdAtributo() . '" for="' . $strIdCampo . '"';
          if ($objAtributoDTO->getStrSinObrigatorio() == 'S') {
            $html .= ' class="infraLabelObrigatorio"';
            $javascript .= 'if (!infraSelectSelecionado(\'' . $strIdCampo . '\')){' . "\n";
            $javascript .= '  alert(\'Selecione ' . $strRotuloJs . '.\');' . "\n";
            $javascript .= '  document.getElementById(\'' . $strIdCampo . '\').focus();' . "\n";
            $javascript .= '  return false;' . "\n";
            $javascript .= '}' . "\n\n";
          } else {
            $html .= ' class="infraLabelOpcional"';
          }
          $html .= '>' . DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $objAtributoDTO->getStrRotulo()) . '</label><br />' . "\n";

          $html .= ' <select id="' . $strIdCampo . '" name="' . $strIdCampo . '" class="infraSelect" tabindex="1000">' . "\n";
          $html .= ' <option value="null"></option>' . "\n";

          $objDominioDTO = new DominioDTO();
          $objDominioDTO->setBolExclusaoLogica(false);
          $objDominioDTO->retNumIdAtributo();
          $objDominioDTO->retNumIdDominio();
          $objDominioDTO->retStrValor();
          $objDominioDTO->retStrRotulo();
          $objDominioDTO->retNumOrdem();
          $objDominioDTO->retStrSinPadrao();
          $objDominioDTO->retStrSinAtivo();
          $objDominioDTO->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());
          $objDominioDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
          $objDominioDTO->setOrdStrRotulo(InfraDTO::$TIPO_ORDENACAO_ASC);

          $objDominioRN = new DominioRN();
          $arrObjDominioDTO = $objDominioRN->listarRN0199($objDominioDTO);
          $numDominios = count($arrObjDominioDTO);

          for ($j = 0; $j < $numDominios; $j++) {

            $selected = '';

            if (isset($_POST[$strIdCampo])) {
              if ($_POST[$strIdCampo] == $arrObjDominioDTO[$j]->getStrValor()) {
                $selected = ' selected="selected" ';
              }
            } else {
              if (isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()])) {
                if ($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]->getStrValor() == $arrObjDominioDTO[$j]->getStrValor()) {
                  $selected = ' selected="selected" ';
                }
              } else {
                if ($arrObjDominioDTO[$j]->getStrSinAtivo() == 'S' && $arrObjDominioDTO[$j]->getStrSinPadrao() == 'S') {
                  $selected = ' selected="selected" ';
                }
              }
            }

            if ($selected == '' && $arrObjDominioDTO[$j]->getStrSinAtivo() == 'N') {
              continue;
            }

            $html .= ' <option value="' . PaginaSEI::tratarHTML($arrObjDominioDTO[$j]->getStrValor()) . '"' . $selected . '>' . PaginaSEI::tratarHTML($arrObjDominioDTO[$j]->getStrRotulo()) . '</option>' . "\n";
          }
          $html .= '</select><br /><br />' . "\n\n";
          break;

        case AtributoRN::$TA_OPCOES:

          $strIdCampo = 'rdoAtributo' . $objAtributoDTO->getNumIdAtributo();

          $objDominioDTO = new DominioDTO();
          $objDominioDTO->setBolExclusaoLogica(false);
          $objDominioDTO->retNumIdAtributo();
          $objDominioDTO->retNumIdDominio();
          $objDominioDTO->retStrValor();
          $objDominioDTO->retStrRotulo();
          $objDominioDTO->retNumOrdem();
          $objDominioDTO->retStrSinPadrao();
          $objDominioDTO->retStrSinAtivo();
          $objDominioDTO->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());
          $objDominioDTO->setOrdNumOrdem(InfraDTO::$TIPO_ORDENACAO_ASC);
          $objDominioDTO->setOrdStrRotulo(InfraDTO::$TIPO_ORDENACAO_ASC);

          $objDominioRN = new DominioRN();
          $arrObjDominioDTO = $objDominioRN->listarRN0199($objDominioDTO);
          $numDominios = count($arrObjDominioDTO);

          $html .= '<label id="lblAtributo' . $objAtributoDTO->getNumIdAtributo() . '" for="' . $strIdCampo . '"';
          if ($objAtributoDTO->getStrSinObrigatorio() == 'S') {
            $html .= ' class="infraLabelObrigatorio"';

            if ($numDominios) {

              $javascript .= 'if (';

              for ($j = 0; $j < $numDominios; $j++) {

                if ($j) {
                  $javascript .= '&& ';
                }

                $javascript .= '!document.getElementById(\'opt' . $arrObjDominioDTO[$j]->getNumIdDominio() . '\').checked ';
              }
              $javascript .= '){' . "\n";
              $javascript .= '  alert(\'Escolha ' . $strRotuloJs . '.\');' . "\n";
              $javascript .= '  return false;' . "\n";
              $javascript .= '}' . "\n\n";
            }

          } else {
            $html .= ' class="infraLabelOpcional"';
          }
          $html .= '>' . DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $objAtributoDTO->getStrRotulo()) . '</label><br />' . "\n";


          for ($j = 0; $j < $numDominios; $j++) {

            $checked = '';

            if (isset($_POST[$strIdCampo])) {
              if ($_POST[$strIdCampo] == $arrObjDominioDTO[$j]->getStrValor()) {
                $checked = ' checked="checked" ';
              }
            } else {
              if (!isset($_POST['sbmFormularioProcessar'])){
                if (isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()])) {
                  if ($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]->getStrValor() == $arrObjDominioDTO[$j]->getStrValor()) {
                    $checked = ' checked="checked" ';
                  }
                }else if ($arrObjDominioDTO[$j]->getStrSinAtivo() == 'S' && $arrObjDominioDTO[$j]->getStrSinPadrao() == 'S') {
                  $checked = ' checked="checked" ';
                }
              }
            }

            if ($checked == '' && $arrObjDominioDTO[$j]->getStrSinAtivo() == 'N') {
              continue;
            }

            $strIdOption = 'opt' . $arrObjDominioDTO[$j]->getNumIdDominio();

            $html .= '<div id="div' . $arrObjDominioDTO[$j]->getNumIdDominio() . '" class="infraDivRadio">' . "\n";
            $html .= ' <input type="radio" name="' . $strIdCampo . '" id="' . $strIdOption . '" value="' . PaginaSEI::tratarHTML($arrObjDominioDTO[$j]->getStrValor()) . '" ' . $checked . ' class="infraRadio" />' . "\n";
            $html .= ' <label for="' . $strIdOption . '" class="infraLabelRadio" tabindex="1000">' . PaginaSEI::tratarHTML($arrObjDominioDTO[$j]->getStrRotulo()) . '</label>' . "\n";
            $html .= '</div><br />' . "\n\n";
          }
          $html .= '<br />' . "\n\n";

          break;

        case AtributoRN::$TA_SINALIZADOR:

          $strIdCampo = 'chkAtributo' . $objAtributoDTO->getNumIdAtributo();

          $checked = '';

          if (isset($_POST[$strIdCampo])) {
            if ($_POST[$strIdCampo] == 'on') {
              $checked = ' checked="checked" ';
            }
          } else {
            if (!isset($_POST['sbmFormularioProcessar'])) {
              if (isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()])) {
                if ($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]->getStrValor() == 'S') {
                  $checked = ' checked="checked" ';
                }
              }else if ($objAtributoDTO->getStrSinAtivo()=='S' && $objAtributoDTO->getStrValorPadrao()=='S'){
                $checked = ' checked="checked" ';
              }
            }
          }

          $html .= '<div id="div'.$objAtributoDTO->getNumIdAtributo().'" class="infraDivCheckbox">'."\n";
          $html .= ' <input type="checkbox" name="'.$strIdCampo.'" id="'.$strIdCampo.'" '.$checked.' class="infraCheckbox" />'."\n";
          $html .= ' <label for="'.$strIdCampo.'" class="infraLabelCheckbox" tabindex="1000">' . PaginaSEI::tratarHTML($objAtributoDTO->getStrRotulo()) . '</label>'."\n";
          $html .= '</div><br />'."\n\n";

          break;

        case AtributoRN::$TA_DATA:

          $strIdCampo = 'txtAtributo' . $objAtributoDTO->getNumIdAtributo();
          $strValue = '';
          if (isset($_POST[$strIdCampo])) {
            $strValue = $_POST[$strIdCampo];
          } else {
            if (isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()])){
              $strValue = $arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]->getStrValor();
            }
          }

          $html .= '<label id="lblAtributo' . $objAtributoDTO->getNumIdAtributo() . '" for="' . $strIdCampo . '"';
          if ($objAtributoDTO->getStrSinObrigatorio() == 'S') {
            $html .= ' class="infraLabelObrigatorio"';
            $javascript .= 'if (infraTrim(document.getElementById(\'' . $strIdCampo . '\').value) == \'\'){' . "\n";
            $javascript .= '  alert(\'Informe ' . $strRotuloJs . '.\');' . "\n";
            $javascript .= '  document.getElementById(\'' . $strIdCampo . '\').focus();' . "\n";
            $javascript .= '  return false;' . "\n";
            $javascript .= '}' . "\n\n";
          } else {
            $html .= ' class="infraLabelOpcional"';
          }

          $javascript .= 'if (!infraValidarData(document.getElementById(\'' . $strIdCampo . '\').value)){' . "\n";
          $javascript .= '  document.getElementById(\'' . $strIdCampo . '\').focus();' . "\n";
          $javascript .= '  return false;' . "\n";
          $javascript .= '}' . "\n\n";

          $html .= '>' . DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $objAtributoDTO->getStrRotulo()) . '</label><br />' . "\n";


          $html .= '<input type="text" id="' . $strIdCampo . '" name="' . $strIdCampo . '" class="infraText" value="' . PaginaSEI::tratarHTML($strValue) . '"';
          $html .= ' onkeypress="return infraMascaraData(this, event);" maxlength="10"';
          $html .= ' style="'.self::getStrCssWidth(12).'" tabindex="1000" /> <br /><br />' . "\n\n";

          break;

        case AtributoRN::$TA_NUMERO_INTEIRO:

          $strIdCampo = 'txtAtributo' . $objAtributoDTO->getNumIdAtributo();

          $strValue = '';
          if (isset($_POST[$strIdCampo])) {
            $strValue = $_POST[$strIdCampo];
          } else {
            if (isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()])){
              $strValue = $arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]->getStrValor();
            }
          }

          $html .= '<label id="lblAtributo' . $objAtributoDTO->getNumIdAtributo() . '" for="' . $strIdCampo . '"';
          if ($objAtributoDTO->getStrSinObrigatorio() == 'S') {
            $html .= ' class="infraLabelObrigatorio"';
            $javascript .= 'if (infraTrim(document.getElementById(\'' . $strIdCampo . '\').value) == \'\'){' . "\n";
            $javascript .= '  alert(\'Informe ' . $strRotuloJs . '.\');' . "\n";
            $javascript .= '  document.getElementById(\'' . $strIdCampo . '\').focus();' . "\n";
            $javascript .= '  return false;' . "\n";
            $javascript .= '}' . "\n\n";
          } else {
            $html .= ' class="infraLabelOpcional"';
          }

          $html .= '>' . DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $objAtributoDTO->getStrRotulo()) . '</label><br />' . "\n";

          $html .= '<input type="text" id="' . $strIdCampo . '" name="' . $strIdCampo . '" class="infraText" value="' . PaginaSEI::tratarHTML($strValue) . '"';
          $html .= ' onkeypress="return infraMascaraNumero(this,event,' . $objAtributoDTO->getNumTamanho() . ');"';
          $html .= ' maxlength="' . $objAtributoDTO->getNumTamanho() . '"';
          $html .= ' style="'.self::getStrCssWidth($objAtributoDTO->getNumTamanho()).'" tabindex="1000" /> <br /><br />' . "\n\n";

          break;

        case AtributoRN::$TA_NUMERO_DECIMAL:

          $strIdCampo = 'txtAtributo' . $objAtributoDTO->getNumIdAtributo();

          $strValue = '';
          if (isset($_POST[$strIdCampo])) {
            $strValue = $_POST[$strIdCampo];
          } else {
            if (isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()])){
              $strValue = $arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]->getStrValor();
            }
          }

          $html .= '<label id="lblAtributo' . $objAtributoDTO->getNumIdAtributo() . '" for="' . $strIdCampo . '"';
          if ($objAtributoDTO->getStrSinObrigatorio() == 'S') {
            $html .= ' class="infraLabelObrigatorio"';
            $javascript .= 'if (infraTrim(document.getElementById(\'' . $strIdCampo . '\').value) == \'\'){' . "\n";
            $javascript .= '  alert(\'Informe ' . $strRotuloJs . '.\');' . "\n";
            $javascript .= '  document.getElementById(\'' . $strIdCampo . '\').focus();' . "\n";
            $javascript .= '  return false;' . "\n";
            $javascript .= '}' . "\n\n";
          } else {
            $html .= ' class="infraLabelOpcional"';
          }

          $html .= '>' . DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $objAtributoDTO->getStrRotulo()) . '</label><br />' . "\n";

          $html .= '<input type="text" id="' . $strIdCampo . '" name="' . $strIdCampo . '" class="infraText" value="' . PaginaSEI::tratarHTML($strValue) . '"';
          $html .= ' onkeypress="return infraMascaraDecimais(this, \'\', \',\', event, '.$objAtributoDTO->getNumDecimais().', '.$objAtributoDTO->getNumTamanho().')"';
          $html .= ' style="'.self::getStrCssWidth($objAtributoDTO->getNumTamanho()+1).'" tabindex="1000" /> <br /><br />' . "\n\n";

          break;

        case AtributoRN::$TA_DINHEIRO:

          $strIdCampo = 'txtAtributo' . $objAtributoDTO->getNumIdAtributo();

          $strValue = '';
          if (isset($_POST[$strIdCampo])) {
            $strValue = $_POST[$strIdCampo];
          } else {
            if (isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()])){
              $strValue = $arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]->getStrValor();
            }
          }

          $html .= '<label id="lblAtributo' . $objAtributoDTO->getNumIdAtributo() . '" for="' . $strIdCampo . '"';
          if ($objAtributoDTO->getStrSinObrigatorio() == 'S') {
            $html .= ' class="infraLabelObrigatorio"';
            $javascript .= 'if (infraTrim(document.getElementById(\'' . $strIdCampo . '\').value) == \'\'){' . "\n";
            $javascript .= '  alert(\'Informe ' . $strRotuloJs . '.\');' . "\n";
            $javascript .= '  document.getElementById(\'' . $strIdCampo . '\').focus();' . "\n";
            $javascript .= '  return false;' . "\n";
            $javascript .= '}' . "\n\n";
          } else {
            $html .= ' class="infraLabelOpcional"';
          }

          $html .= '>' . DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $objAtributoDTO->getStrRotulo()) . '</label><br />' . "\n";

          $html .= '<input type="text" id="' . $strIdCampo . '" name="' . $strIdCampo . '" class="infraText" value="' . PaginaSEI::tratarHTML($strValue) . '"';
          $html .= ' onkeypress="return infraMascaraDinheiro(this,event,2,12);"';
          $html .= ' maxlength="'.$objAtributoDTO->getNumTamanho().'"';
          $html .= ' style="'.self::getStrCssWidth(15).'" tabindex="1000" /> <br /><br />' . "\n\n";

          break;

        case AtributoRN::$TA_TEXTO_SIMPLES:

          $strIdCampo = 'txtAtributo' . $objAtributoDTO->getNumIdAtributo();

          $strValue = '';
          if (isset($_POST[$strIdCampo])) {
            $strValue = $_POST[$strIdCampo];
          } else {
            if (isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()])){
              $strValue = $arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]->getStrValor();
            }
          }

          $html .= '<label id="lblAtributo' . $objAtributoDTO->getNumIdAtributo() . '" for="' . $strIdCampo . '"';
          if ($objAtributoDTO->getStrSinObrigatorio() == 'S') {
            $html .= ' class="infraLabelObrigatorio"';
            $javascript .= 'if (infraTrim(document.getElementById(\'' . $strIdCampo . '\').value) == \'\'){' . "\n";
            $javascript .= '  alert(\'Informe ' . $strRotuloJs . '.\');' . "\n";
            $javascript .= '  document.getElementById(\'' . $strIdCampo . '\').focus();' . "\n";
            $javascript .= '  return false;' . "\n";
            $javascript .= '}' . "\n\n";
          } else {
            $html .= ' class="infraLabelOpcional"';
          }
          $html .= '>' . DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $objAtributoDTO->getStrRotulo()) . '</label><br />' . "\n";
          $html .= '<input type="text" id="' . $strIdCampo . '" name="' . $strIdCampo . '" class="infraText" value="' . PaginaSEI::tratarHTML($strValue) . '"';
          $html .= ' onkeypress="return infraMascaraTexto(this,event,'.$objAtributoDTO->getNumTamanho().');"';
          $html .= ' maxlength="'.$objAtributoDTO->getNumTamanho().'"';
          $html .= ' style="'.self::getStrCssWidth($objAtributoDTO->getNumTamanho()).'" tabindex="1000" /> <br /><br />' . "\n\n";
          break;

        case AtributoRN::$TA_TEXTO_GRANDE:

          $strIdCampo = 'txaAtributo' . $objAtributoDTO->getNumIdAtributo();

          $strValue = '';
          if (isset($_POST[$strIdCampo])) {
            $strValue = $_POST[$strIdCampo];
          } else {
            if (isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()])){
              $strValue = $arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]->getStrValor();
            }
          }

          $html .= '<label id="lblAtributo' . $objAtributoDTO->getNumIdAtributo() . '" for="' . $strIdCampo . '"';
          if ($objAtributoDTO->getStrSinObrigatorio() == 'S') {
            $html .= ' class="infraLabelObrigatorio"';
            $javascript .= 'if (infraTrim(document.getElementById(\'' . $strIdCampo . '\').value) == \'\'){' . "\n";
            $javascript .= '  alert(\'Informe ' . $strRotuloJs . '.\');' . "\n";
            $javascript .= '  document.getElementById(\'' . $strIdCampo . '\').focus();' . "\n";
            $javascript .= '  return false;' . "\n";
            $javascript .= '}' . "\n\n";
          } else {
            $html .= ' class="infraLabelOpcional"';
          }
          $html .= '>' . DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $objAtributoDTO->getStrRotulo()) . '</label><br />' . "\n";
          $html .= '<textarea id="' . $strIdCampo . '" name="' . $strIdCampo . '" class="infraTextarea" rows="'.$objAtributoDTO->getNumLinhas().'"';
          $html .= ' onkeypress="return infraLimitarTexto(this,event,'.$objAtributoDTO->getNumTamanho().');"';
          $html .= ' style="'.self::getStrCssWidth($objAtributoDTO->getNumTamanho()).'" tabindex="1000">'.PaginaSEI::tratarHTML($strValue).'</textarea><br /><br />' . "\n\n";
          break;

        case AtributoRN::$TA_TEXTO_MASCARA:

          $strIdCampo = 'txtAtributo' . $objAtributoDTO->getNumIdAtributo();

          $strValue = '';
          if (isset($_POST[$strIdCampo])) {
            $strValue = $_POST[$strIdCampo];
          } else {
            if (isset($arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()])){
              $strValue = $arrObjRelProtocoloAtributoDTO[$objAtributoDTO->getNumIdAtributo()]->getStrValor();
            }
          }

          $html .= '<label id="lblAtributo' . $objAtributoDTO->getNumIdAtributo() . '" for="' . $strIdCampo . '"';
          if ($objAtributoDTO->getStrSinObrigatorio() == 'S') {
            $html .= ' class="infraLabelObrigatorio"';
            $javascript .= 'if (infraTrim(document.getElementById(\'' . $strIdCampo . '\').value) == \'\'){' . "\n";
            $javascript .= '  alert(\'Informe ' . $strRotuloJs . '.\');' . "\n";
            $javascript .= '  document.getElementById(\'' . $strIdCampo . '\').focus();' . "\n";
            $javascript .= '  return false;' . "\n";
            $javascript .= '}' . "\n\n";
          } else {
            $html .= ' class="infraLabelOpcional"';
          }
          $html .= '>' . DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $objAtributoDTO->getStrRotulo()) . '</label><br />' . "\n";
          $html .= '<input type="text" id="' . $strIdCampo . '" name="' . $strIdCampo . '" class="infraText" value="' . PaginaSEI::tratarHTML($strValue) . '"';
          $html .= ' onkeypress="return infraMascara(this,event,\''.$objAtributoDTO->getStrMascara().'\');"';
          $html .= ' maxlength="'.strlen($objAtributoDTO->getStrMascara()).'"';
          $html .= ' style="'.self::getStrCssWidth(strlen($objAtributoDTO->getStrMascara())).'" tabindex="1000" /> <br /><br />' . "\n\n";
          break;

        case AtributoRN::$TA_INFORMACAO:
          $html .= '<label id="lblAtributo' . $objAtributoDTO->getNumIdAtributo() . '" class="infraLabelOpcional">' . DocumentoINT::formatarRotulo(DocumentoINT::$TV_HTML, $objAtributoDTO->getStrRotulo()) . '</label><br />' . "\n";
          $html .= '<input type="hidden" name="hdnAtributo' . $objAtributoDTO->getNumIdAtributo() . '" value="" />' . "\n";
          break;

        default:
          throw new InfraException('Tipo do campo "'.$objAtributoDTO->getStrRotulo().'" não mapeado para processamento.');
      }
    }
    $html .= '</div>'."\n";
  }
   
  public static function processar($dblIdProtocolo, $numIdTipoFormulario){

    $arrObjRelProtocoloAtributoDTO = array();

		foreach(array_keys($_POST) as $strNomeCampo){

      $numTamPrefixo = 11; //---Atributo

	  	if (substr($strNomeCampo,0,$numTamPrefixo)=='txtAtributo' ||
          substr($strNomeCampo,0,$numTamPrefixo)=='selAtributo' ||
	  			substr($strNomeCampo,0,$numTamPrefixo)=='txaAtributo' ||
          substr($strNomeCampo,0,$numTamPrefixo)=='hdnAtributo'){
	  				
        $dto = new RelProtocoloAtributoDTO();
        $dto->setNumIdAtributo(substr($strNomeCampo,$numTamPrefixo));
        $dto->setStrValor($_POST[$strNomeCampo]);
        $arrObjRelProtocoloAtributoDTO[] = $dto;
	   	}
	 	}

    if ($dblIdProtocolo!=null) {
      $objRelProtocoloAtributoDTO = new RelProtocoloAtributoDTO();
      $objRelProtocoloAtributoDTO->retNumIdAtributo();
      $objRelProtocoloAtributoDTO->setDblIdProtocolo($dblIdProtocolo);
      $objRelProtocoloAtributoDTO->setStrStaTipoAtributo(array(AtributoRN::$TA_OPCOES, AtributoRN::$TA_SINALIZADOR),InfraDTO::$OPER_IN);

      $objRelProtocoloAtributoRN = new RelProtocoloAtributoRN();
      $arrIdAtributosProtocolo = InfraArray::converterArrInfraDTO($objRelProtocoloAtributoRN->listar($objRelProtocoloAtributoDTO), 'IdAtributo');
    }else{
      $arrIdAtributosProtocolo = array();
    }

    $objAtributoDTO = new AtributoDTO();
    $objAtributoDTO->setBolExclusaoLogica(false);
    $objAtributoDTO->retNumIdAtributo();
    $objAtributoDTO->retStrStaTipo();
    $objAtributoDTO->retStrSinObrigatorio();
    $objAtributoDTO->retStrSinAtivo();
    $objAtributoDTO->setNumIdTipoFormulario($numIdTipoFormulario);
    $objAtributoDTO->setStrStaTipo(array(AtributoRN::$TA_OPCOES, AtributoRN::$TA_SINALIZADOR),InfraDTO::$OPER_IN);

    if (count($arrIdAtributosProtocolo)){
      $objAtributoDTO->setNumIdAtributo($arrIdAtributosProtocolo, InfraDTO::$OPER_IN);
    }

    $objAtributoRN = new AtributoRN();
    $arrObjAtributoDTO = $objAtributoRN->listarRN0165($objAtributoDTO);

    foreach($arrObjAtributoDTO as $objAtributoDTO){

      if ($objAtributoDTO->getStrSinAtivo()=='N' && ($dblIdProtocolo == null || !in_array($objAtributoDTO->getNumIdAtributo(), $arrIdAtributosProtocolo))){
        continue;
      }

      $dto = new RelProtocoloAtributoDTO();
      $dto->setNumIdAtributo($objAtributoDTO->getNumIdAtributo());

      if ($objAtributoDTO->getStrStaTipo()==AtributoRN::$TA_OPCOES) {

        if (isset($_POST['rdoAtributo' . $objAtributoDTO->getNumIdAtributo()])) {
          $dto->setStrValor($_POST['rdoAtributo' . $objAtributoDTO->getNumIdAtributo()]);
        }else{
          if ($dblIdProtocolo==null){
            $dto->setStrValor(null);
          }else if (in_array($objAtributoDTO->getNumIdAtributo(), $arrIdAtributosProtocolo)) {
            $dto->setStrValor(null);
          }
        }

      }else{

        if (isset($_POST['chkAtributo' . $objAtributoDTO->getNumIdAtributo()]) && $_POST['chkAtributo' . $objAtributoDTO->getNumIdAtributo()]=='on') {
          $dto->setStrValor('S');
        }else{
          if ($dblIdProtocolo == null) {
            $dto->setStrValor('N');
          }else if (in_array($objAtributoDTO->getNumIdAtributo(), $arrIdAtributosProtocolo)) {
            $dto->setStrValor('N');
          }
        }

      }

      if ($dto->isSetStrValor()) {
        $arrObjRelProtocoloAtributoDTO[] = $dto;
      }
    }

    foreach($arrObjRelProtocoloAtributoDTO as $dto){
      InfraDebug::getInstance()->gravar($dto->__toString());
    }


	 	return $arrObjRelProtocoloAtributoDTO;
  }
}
?>