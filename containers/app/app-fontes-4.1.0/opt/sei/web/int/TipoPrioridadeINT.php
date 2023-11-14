<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/01/2023 - criado por cas84
 *
 * Versão do Gerador de Código: 1.43.2
 */

require_once dirname(__FILE__) . '/../SEI.php';

class TipoPrioridadeINT extends InfraINT
{

    public static function montarSelectIdTipoPrioridade(
        $strPrimeiroItemValor,
        $strPrimeiroItemDescricao,
        $strValorItemSelecionado
    ) {
        $objTipoPrioridadeDTO = new TipoPrioridadeDTO();
        $objTipoPrioridadeDTO->retNumIdTipoPrioridade();
        $objTipoPrioridadeDTO->retStrNome();

        if ($strValorItemSelecionado != null) {
            $objTipoPrioridadeDTO->setBolExclusaoLogica(false);
            $objTipoPrioridadeDTO->adicionarCriterio(array('SinAtivo', 'IdTipoPrioridade'),
                array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
                array('S', $strValorItemSelecionado),
                InfraDTO::$OPER_LOGICO_OR);
        }

        $objTipoPrioridadeDTO->setOrdNumIdTipoPrioridade(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objTipoPrioridadeRN = new TipoPrioridadeRN();
        $arrObjTipoPrioridadeDTO = $objTipoPrioridadeRN->listar($objTipoPrioridadeDTO);

        return parent::montarSelectArrInfraDTO(
            $strPrimeiroItemValor,
            $strPrimeiroItemDescricao,
            $strValorItemSelecionado,
            $arrObjTipoPrioridadeDTO,
            'IdTipoPrioridade',
            'Nome'
        );
    }
}
