<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 24/10/2011 - criado por mga
 *
 * Versão do Gerador de Código: 1.32.1
 *
 * Versão no CVS: $Id$
 */

//require_once dirname(__FILE__).'/../Infra.php';

class InfraAuditoriaDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'infra_auditoria';
    }

    public function __construct()
    {
        parent::__construct();
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_DBL,
            'IdInfraAuditoria',
            'id_infra_auditoria'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'Recurso',
            'recurso'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_DTH,
            'Acesso',
            'dth_acesso'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'Ip',
            'ip'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_NUM,
            'IdUsuario',
            'id_usuario'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'SiglaUsuario',
            'sigla_usuario'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'NomeUsuario',
            'nome_usuario'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_NUM,
            'IdOrgaoUsuario',
            'id_orgao_usuario'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'SiglaOrgaoUsuario',
            'sigla_orgao_usuario'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_NUM,
            'IdUsuarioEmulador',
            'id_usuario_emulador'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'SiglaUsuarioEmulador',
            'sigla_usuario_emulador'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'NomeUsuarioEmulador',
            'nome_usuario_emulador'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_NUM,
            'IdOrgaoUsuarioEmulador',
            'id_orgao_usuario_emulador'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'SiglaOrgaoUsuarioEmulador',
            'sigla_orgao_usuario_emulador'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_NUM,
            'IdUnidade',
            'id_unidade'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'SiglaUnidade',
            'sigla_unidade'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'DescricaoUnidade',
            'descricao_unidade'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_NUM,
            'IdOrgaoUnidade',
            'id_orgao_unidade'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'SiglaOrgaoUnidade',
            'sigla_orgao_unidade'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'Servidor',
            'servidor'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'UserAgent',
            'user_agent'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'Requisicao',
            'requisicao'
        );

        $this->adicionarAtributoTabela(
            InfraDTO::$PREFIXO_STR,
            'Operacao',
            'operacao'
        );


        $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH, 'Inicial');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH, 'Final');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Base');

        $this->configurarPK('IdInfraAuditoria', InfraDTO::$TIPO_PK_NATIVA);
    }
}
