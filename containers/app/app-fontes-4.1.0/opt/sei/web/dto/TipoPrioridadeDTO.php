<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/01/2023 - criado por cas84
 *
 * Versão do Gerador de Código: 1.43.2
 */

require_once dirname(__FILE__) . '/../SEI.php';

class TipoPrioridadeDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'tipo_prioridade';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdTipoPrioridade', 'id_tipo_prioridade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Nome', 'nome');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

        $this->configurarPK('IdTipoPrioridade', InfraDTO::$TIPO_PK_NATIVA);

        $this->configurarExclusaoLogica('SinAtivo', 'N');

        $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'Processos');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Alterados');
    }
}
