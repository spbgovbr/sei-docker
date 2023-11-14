<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/11/2010 - criado por jonatas_db
* 15/06/2018 - cjy - ícone de acompanhamento no controle de processos*
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class GrupoAcompanhamentoINT extends InfraINT {

  public static function montarSelectIdGrupoAcompanhamentoRI0012($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdUnidade=''){
    $objGrupoAcompanhamentoDTO = new GrupoAcompanhamentoDTO();
    $objGrupoAcompanhamentoDTO->retNumIdGrupoAcompanhamento();
    $objGrupoAcompanhamentoDTO->retStrNome();

    if ($numIdUnidade!==''){
      $objGrupoAcompanhamentoDTO->setNumIdUnidade($numIdUnidade);
    }

    $objGrupoAcompanhamentoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objGrupoAcompanhamentoRN = new GrupoAcompanhamentoRN();
    $arrObjGrupoAcompanhamentoDTO = $objGrupoAcompanhamentoRN->listar($objGrupoAcompanhamentoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjGrupoAcompanhamentoDTO, 'IdGrupoAcompanhamento', 'Nome');
  }

}
?>