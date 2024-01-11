<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 10/06/2010 - criado por fazenda_db
 *
 * Versão do Gerador de Código: 1.29.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../SEI.php';

class AcessoExternoDTO extends InfraDTO
{

    public function getStrNomeTabela(){
     return 'acesso_externo';
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdAcessoExterno',
            'id_acesso_externo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdAtividade',
            'id_atividade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdParticipante',
            'id_participante');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
            'IdDocumento',
            'id_documento');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
            'Validade',
            'dta_validade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'EmailUnidade',
            'email_unidade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'StaTipo',
            'sta_tipo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'EmailDestinatario',
            'email_destinatario');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'HashInterno',
            'hash_interno');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
            'IdProtocoloAtividade',
            'id_protocolo',
            'atividade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdUnidadeAtividade',
            'id_unidade',
            'atividade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
            'AberturaAtividade',
            'dth_abertura',
            'atividade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdTarefaAtividade',
            'id_tarefa',
            'atividade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdContatoParticipante',
            'id_contato',
            'participante');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
        'IdUnidade',
        'id_unidade',
        'unidade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'SiglaUnidade',
            'sigla',
            'unidade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'DescricaoUnidade',
            'descricao',
            'unidade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdOrgaoUnidade',
            'id_orgao',
            'unidade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'SiglaOrgaoUnidade',
            'sigla',
            'orgao');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'DescricaoOrgaoUnidade',
            'descricao',
            'orgao');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'SiglaContato',
            'sigla',
            'contato');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'NomeContato',
            'nome',
            'contato');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
            'IdDocumentoDocumento',
            'id_documento',
            'documento');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'ProtocoloDocumentoFormatado',
            'protocolo_formatado',
            'protocolo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SinProcesso',
            'sin_processo');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SinInclusao',
            'sin_inclusao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SinAtivo',
            'sin_ativo');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
        'Visualizacao',
        'dth_visualizacao');

        $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'Dias');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Senha');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Motivo');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUsuarioExterno');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH, 'Cancelamento');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_DTH, 'Utilizacao');

        $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProcedimentoDTO');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'DocumentoDTO');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelAcessoExtProtocoloDTO');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjRelAcessoExtSerieDTO');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProtocoloConsulta');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProcedimentoAnexadoConsulta');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinParcial');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinExpirados');

        $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdProcedimento');

        $this->configurarPK('IdAcessoExterno', InfraDTO::$TIPO_PK_NATIVA);

        $this->configurarFK('IdAtividade', 'atividade', 'id_atividade');
        $this->configurarFK('IdParticipante', 'participante', 'id_participante');
        $this->configurarFK('IdUnidadeAtividade', 'unidade', 'id_unidade');
        $this->configurarFK('IdOrgaoUnidade', 'orgao', 'id_orgao');
        $this->configurarFK('IdContatoParticipante', 'contato', 'id_contato');
        $this->configurarFK('IdDocumento', 'documento', 'id_documento', InfraDTO::$TIPO_FK_OPCIONAL);
        $this->configurarFK('IdDocumentoDocumento', 'protocolo', 'id_protocolo');

        $this->configurarExclusaoLogica('SinAtivo', 'N');
    }
}

?>