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

//require_once 'Infra.php';

class InfraAgendamentoPeriodicidadeDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return null;
    }

    public function montar()
    {
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'StaPeriodicidadeExecucao');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Descricao');
    }
}
