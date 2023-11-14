<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/03/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class NovidadeINT extends InfraINT {

  public static function montarSelectTitulo($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUsuario=''){
    $objNovidadeDTO = new NovidadeDTO();
    $objNovidadeDTO->retNumIdNovidade();
    $objNovidadeDTO->retStrTitulo();

    if ($numIdUsuario!==''){
      $objNovidadeDTO->setNumIdUsuario($numIdUsuario);
    }

    $objNovidadeDTO->setOrdStrTitulo(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objNovidadeRN = new NovidadeRN();
    $arrObjNovidadeDTO = $objNovidadeRN->listar($objNovidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjNovidadeDTO, 'IdNovidade', 'Titulo');
  }

  public static function processar(&$strJsInicializar){

    $strJsInicializar = '';

    //sessão carrega na entrada do sistema
    if (isset($_GET['inicializando']) && $_GET['inicializando']=='1') {

      $objNovidadeDTO = new NovidadeDTO();
      $objNovidadeDTO->retNumIdNovidade();

      $objInfraDadoUsuario = new InfraDadoUsuario(SessaoSEI::getInstance());
      $dthUltimaNovidadeExibida = $objInfraDadoUsuario->getValor('NOVIDADE_ULTIMA');

      if ($dthUltimaNovidadeExibida == null) {

        $dthUltimaNovidadeExibida = $_COOKIE[PaginaSEI::getInstance()->getStrPrefixoCookie() . '_ultima_novidade'];

        if ($dthUltimaNovidadeExibida != null) {
          $objInfraDadoUsuario->setValor('NOVIDADE_ULTIMA', $dthUltimaNovidadeExibida);
        }
      }

      //se não existe data para utilizar no cookie
      if (!InfraString::isBolVazia($dthUltimaNovidadeExibida) && InfraData::validarDataHora($dthUltimaNovidadeExibida)) {

        $objNovidadeDTO->adicionarCriterio(array('Liberacao', 'Liberacao'),
            array(InfraDTO::$OPER_MAIOR, InfraDTO::$OPER_DIFERENTE),
            array($dthUltimaNovidadeExibida, NovidadeRN::$DATA_NAO_LIBERADO),
            InfraDTO::$OPER_LOGICO_AND);
      } else {
        $objNovidadeDTO->setDthLiberacao(NovidadeRN::$DATA_NAO_LIBERADO, InfraDTO::$OPER_DIFERENTE);
      }
      $objNovidadeDTO->setNumMaxRegistrosRetorno(1);

      $objNovidadeRN = new NovidadeRN();

      if ($objNovidadeRN->consultar($objNovidadeDTO) != null) {
        $strLinkNovidades = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=novidade_mostrar');
        $strJsInicializar = '  
        infraAbrirJanelaModal(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=novidade_mostrar').'\',950,500,false);
        ';

      }
    }

  }
}
?>