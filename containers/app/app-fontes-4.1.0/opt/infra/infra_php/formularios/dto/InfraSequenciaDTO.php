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

class InfraSequenciaDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'infra_sequencia';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'Nome',
            'nome_tabela'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_DBL,
            'QtdIncremento',
            'qtd_incremento'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_DBL,
            'NumAtual',
            'num_atual'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_DBL,
            'NumMaximo',
            'num_maximo'
        );

        $this->configurarPK('Nome', InfraDTO::$TIPO_PK_INFORMADO);
    }
}
