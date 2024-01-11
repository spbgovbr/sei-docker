<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/12/2020 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class AvisoINT extends InfraINT {

  public static function processar(&$strJavascript, &$strCss, &$strHtml){

    $strJavascript = '';
    $strCss = '';
    $strHtml = '';

    try {
      $objAvisoDTO_Pesquisa = new AvisoDTO();
      $objAvisoDTO_Pesquisa->retTodos();
      $objAvisoDTO_Pesquisa->adicionarCriterio(
        array('Inicio', 'Fim'),
        array(InfraDTO::$OPER_MENOR_IGUAL, InfraDTO::$OPER_MAIOR_IGUAL),
        array(InfraData::getStrDataHoraAtual(), InfraData::getStrDataHoraAtual()),
        array(InfraDTO::$OPER_LOGICO_AND));
      $objAvisoDTO_Pesquisa->setNumIdOrgaoRelAvisoOrgao(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
      $objAvisoDTO_Pesquisa->setStrSinLiberado("S");

      $objAvisoRN = new AvisoRN();
      $arrObjAvisoDTO = $objAvisoRN->listar($objAvisoDTO_Pesquisa);

      if (count($arrObjAvisoDTO)) {

        $objAvisoDTO_Banner = null;
        $objAvisoDTO_Alert = null;

        $numAviso = InfraArray::contar($arrObjAvisoDTO);
        if ($numAviso == 1) {
          if ($arrObjAvisoDTO[0]->getStrStaAviso() == AvisoRN::$AVISO_BANNER) {
            $objAvisoDTO_Banner = $arrObjAvisoDTO[0];
          } else {
            $objAvisoDTO_Alert = $arrObjAvisoDTO[0];
          }
        } else if ($numAviso == 2) {
          if ($arrObjAvisoDTO[0]->getStrStaAviso() == AvisoRN::$AVISO_BANNER) {
            $objAvisoDTO_Banner = $arrObjAvisoDTO[0];
            $objAvisoDTO_Alert = $arrObjAvisoDTO[1];
          } else {
            $objAvisoDTO_Alert = $arrObjAvisoDTO[0];
            $objAvisoDTO_Banner = $arrObjAvisoDTO[1];
          }
        }

        if ($objAvisoDTO_Banner != null) {

          $strHashUltimoBanner = null;

          if (isset($_COOKIE[PaginaSEI::getInstance()->getStrPrefixoCookie().'_banner'])) {
            $strHashUltimoBanner = $_COOKIE[PaginaSEI::getInstance()->getStrPrefixoCookie().'_banner'];
          }

          $strHashBannerAtual = md5($objAvisoDTO_Banner->getStrImagem());
          if ($strHashBannerAtual != $strHashUltimoBanner) {
            $strCss = '
a.botaoFecharBanner{
  cursor:pointer;
  vertical-align:top;
  color: #fff;
  border-radius: 10px;
  background: #605F61;
  font-size: 16px;
  font-weight: bold;
  display: inline-block;
  line-height: 1px;
  padding: 9px 5px;
  margin-right:-10px;
}

.botaoFecharBanner:before {
  content: "×";
}
';

            $strLink = (InfraString::isBolVazia($objAvisoDTO_Banner->getStrLink()) ? "" : 'href="'.$objAvisoDTO_Banner->getStrLink().'"');

            $strHtml .= '<div id="divBanner" class="d-md-block d-none" style="text-align:left;">';
            $strHtml .= '<a '.$strLink.' target="_blank"><img src="data:image/png;base64,'.$objAvisoDTO_Banner->getStrImagem().'" title="'.PaginaSEI::tratarHTML($objAvisoDTO_Banner->getStrDescricao()).'" style="max-width:95%;"/></a>'."\n";
            $strHtml .= '<a onclick="$(\'#divBanner\').removeClass(\'d-md-block\');infraCriarCookie(\''.PaginaSEI::getInstance()->getStrPrefixoCookie().'_banner\', \''.$strHashBannerAtual.'\', 3650);" id="ancFecharBanner" class="botaoFecharBanner" title="'.PaginaSEI::tratarHTML('Não exibir novamente').'"></a>';
            $strHtml .= '</div>';
          }
        }

        if (isset($_GET['inicializando']) && $_GET['inicializando'] == '1') {

          if ($objAvisoDTO_Alert != null) {
            $strHashUltimo = null;

            if (isset($_COOKIE[PaginaSEI::getInstance()->getStrPrefixoCookie().'_aviso'])) {
              $strHashUltimo = $_COOKIE[PaginaSEI::getInstance()->getStrPrefixoCookie().'_aviso'];
            }

            $strHashAvisoAtual = md5($objAvisoDTO_Alert->getStrImagem());
            if ($strHashAvisoAtual != $strHashUltimo) {
              $img = getimagesize('data:image/png;base64,'.$objAvisoDTO_Alert->getStrImagem());
              $strJavascript = "\n".'infraAbrirJanelaModal(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=aviso_mostrar&id_aviso='.$objAvisoDTO_Alert->getNumIdAviso()).'\','.($img[0] + 2).','.($img[1] + 36).', false);'."\n";
            }
          }
        }
      }
    }catch(Exception $e){
      throw new InfraException('Erro processando avisos.',$e);
    }
  }

  public static function montarSelectStaAviso($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objAvisoRN = new AvisoRN();

    $arrObjAvisoAvisoDTO = $objAvisoRN->listarValoresAviso();

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjAvisoAvisoDTO, 'StaAviso', 'Descricao');
  }
}
?>