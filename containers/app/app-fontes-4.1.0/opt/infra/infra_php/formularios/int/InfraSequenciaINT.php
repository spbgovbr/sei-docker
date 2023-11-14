<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/08/2009 - criado por mga
 *
 * Versão do Gerador de Código: 1.27.1
 *
 * Versão no CVS: $Id$
 */

//require_once 'Infra.php';

class InfraSequenciaINT extends InfraINT
{

    public static function montarSelectNome($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado)
    {
        $objInfraSequenciaDTO = new InfraSequenciaDTO();
        $objInfraSequenciaDTO->retStrNome();
        $objInfraSequenciaDTO->retStrNome();

        $objInfraSequenciaDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objInfraSequenciaRN = new InfraSequenciaRN();
        $arrObjInfraSequenciaDTO = $objInfraSequenciaRN->listar($objInfraSequenciaDTO);

        return parent::montarSelectArrInfraDTO(
            $strPrimeiroItemValor,
            $strPrimeiroItemDescricao,
            $strValorItemSelecionado,
            $arrObjInfraSequenciaDTO,
            'Nome',
            'Nome'
        );
    }
}
