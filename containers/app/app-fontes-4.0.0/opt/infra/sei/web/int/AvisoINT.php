<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/12/2020 - criado por mga
*
*/

require_once dirname(__FILE__).'/../SEI.php';

class AvisoINT extends InfraINT {

  public static function processar(&$strJavascript, &$strHtml){

    $strJavascript = '';
    $strHtml = '';

    if (isset($_GET['inicializando']) && $_GET['inicializando']=='1') {

      $arrAviso = ConfiguracaoSEI::getInstance()->getValor('PaginaSEI', 'AvisoControleProcessos', false);
      if ($arrAviso != null) {

        if (($arrAviso['IdOrgaos'] == null || in_array(SessaoSEI::getInstance()->getNumIdOrgaoUsuario(), $arrAviso['IdOrgaos'])) &&
            InfraData::compararDataHora($arrAviso['DthInicio'], InfraData::getStrDataHoraAtual()) > 0 && InfraData::compararDataHora(InfraData::getStrDataHoraAtual(), $arrAviso['DthFim']) > 0) {

          $strHashUltimo = null;

          if (isset($_COOKIE[PaginaSEI::getInstance()->getStrPrefixoCookie().'_aviso'])) {
            $strHashUltimo = $_COOKIE[PaginaSEI::getInstance()->getStrPrefixoCookie().'_aviso'];
          }

          if (md5($arrAviso['Imagem']) != $strHashUltimo) {
            $strJavascript = "\n".'infraAbrirJanelaModal(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=aviso_mostrar').'\','.$arrAviso['Largura'].','.($arrAviso['Altura']+30).');'."\n";
          }
        }
      }
    }

    $arrBannerControleProcessos = ConfiguracaoSEI::getInstance()->getValor('PaginaSEI','BannerControleProcessos',false);
    if ($arrBannerControleProcessos!=null) {

      if (($arrBannerControleProcessos['IdOrgaos'] == null || in_array(SessaoSEI::getInstance()->getNumIdOrgaoUsuario(), $arrBannerControleProcessos['IdOrgaos'])) &&
          InfraData::compararDataHora($arrBannerControleProcessos['DthInicio'], InfraData::getStrDataHoraAtual()) > 0 && InfraData::compararDataHora(InfraData::getStrDataHoraAtual(), $arrBannerControleProcessos['DthFim']) > 0){

        $strHashUltimoBanner = null;

        if (isset($_COOKIE[PaginaSEI::getInstance()->getStrPrefixoCookie() . '_banner'])){
          $strHashUltimoBanner = $_COOKIE[PaginaSEI::getInstance()->getStrPrefixoCookie() . '_banner'];
        }

        if (md5($arrBannerControleProcessos['Imagem'])!=$strHashUltimoBanner) {
          $strHtml .= '<div id="divBanner" class="d-md-block d-none" style="text-align:left;">';
          $strHtml .= '<a href="'.$arrBannerControleProcessos['Link'].'" target="_blank"><img src="'.$arrBannerControleProcessos['Imagem'].'" title="'.PaginaSEI::tratarHTML($arrBannerControleProcessos['Descricao']).'" style="max-width:95%;"/></a>'."\n";
          $strHtml .= '<a onclick="fecharBanner(\''.md5($arrBannerControleProcessos['Imagem']).'\')" id="ancFecharBanner" class="botaoFecharBanner" title="'.PaginaSEI::tratarHTML('Não exibir novamente').'"></a>';
          $strHtml .= '</div>';
        }
      }
    }

  }
}
?>