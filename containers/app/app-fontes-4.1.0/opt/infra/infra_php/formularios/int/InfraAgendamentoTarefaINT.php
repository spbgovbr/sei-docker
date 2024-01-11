<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 15/12/2011 - criado por tamir_db
 *
 * Versão do Gerador de Código: 1.32.1
 *
 * Versão no CVS: $Id$
 */

//require_once dirname(__FILE__).'/../Infra.php';

class InfraAgendamentoTarefaINT extends InfraINT
{

    public static function montarSelectIdInfraAgendamentoTarefa(
        $strPrimeiroItemValor,
        $strPrimeiroItemDescricao,
        $strValorItemSelecionado
    ) {
        $objInfraAgendamentoTarefaDTO = new InfraAgendamentoTarefaDTO();
        $objInfraAgendamentoTarefaDTO->retNumIdInfraAgendamentoTarefa();
        $objInfraAgendamentoTarefaDTO->retNumIdInfraAgendamentoTarefa();

        if ($strValorItemSelecionado != null) {
            $objInfraAgendamentoTarefaDTO->setBolExclusaoLogica(false);
            $objInfraAgendamentoTarefaDTO->adicionarCriterio(array('SinAtivo', 'IdInfraAgendamentoTarefa'),
                array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
                array('S', $strValorItemSelecionado),
                InfraDTO::$OPER_LOGICO_OR);
        }

        $objInfraAgendamentoTarefaDTO->setOrdNumIdInfraAgendamentoTarefa(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objInfraAgendamentoTarefaRN = new InfraAgendamentoTarefaRN();
        $arrObjInfraAgendamentoTarefaDTO = $objInfraAgendamentoTarefaRN->listar($objInfraAgendamentoTarefaDTO);

        return parent::montarSelectArrInfraDTO(
            $strPrimeiroItemValor,
            $strPrimeiroItemDescricao,
            $strValorItemSelecionado,
            $arrObjInfraAgendamentoTarefaDTO,
            'IdInfraAgendamentoTarefa',
            'IdInfraAgendamentoTarefa'
        );
    }

    public static function montarSelectStaPeriodicidadeExecucao(
        $strPrimeiroItemValor,
        $strPrimeiroItemDescricao,
        $strValorItemSelecionado
    ) {
        $objInfraAgendamentoTarefaRN = new InfraAgendamentoTarefaRN();

        $arrObjInfraAgendamentoPeriodicidadeDTO = $objInfraAgendamentoTarefaRN->listarValoresPeriodicidadeExecucao();

        return parent::montarSelectArrInfraDTO(
            $strPrimeiroItemValor,
            $strPrimeiroItemDescricao,
            $strValorItemSelecionado,
            $arrObjInfraAgendamentoPeriodicidadeDTO,
            'StaPeriodicidadeExecucao',
            'Descricao'
        );
    }
}
