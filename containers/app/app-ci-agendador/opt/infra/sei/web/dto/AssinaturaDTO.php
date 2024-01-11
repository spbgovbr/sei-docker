<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 09/10/2009 - criado por mga
 *
 * Versão do Gerador de Código: 1.29.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../SEI.php';

class AssinaturaDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'assinatura';
    }

    public function montar()
    {

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdAssinatura',
            'id_assinatura');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
            'IdDocumento',
            'id_documento');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdAtividade',
            'id_atividade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdUsuario',
            'id_usuario');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdUnidade',
            'id_unidade');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
            'IdTarjaAssinatura',
            'id_tarja_assinatura');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'Agrupador',
            'agrupador');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'StaFormaAutenticacao',
            'sta_forma_autenticacao');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'Nome',
            'nome');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'Tratamento',
            'tratamento');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
            'Cpf',
            'cpf');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'P7sBase64',
            'p7s_base64');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'NumeroSerieCertificado',
            'numero_serie_certificado');

        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
            'SinAtivo',
            'sin_ativo');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'IdOrigemUsuario',
          'id_origem',
          'usuario');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'SiglaUsuario',
            'sigla',
            'usuario');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'NomeUsuario',
            'nome',
            'usuario');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdContatoUsuario',
            'id_contato',
            'usuario');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'StaTipoUsuario',
            'sta_tipo',
            'usuario');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
          'IdOrgaoUsuario',
          'id_orgao',
          'usuario');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'SiglaOrgaoUsuario',
          'sigla',
          'orgao');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
            'IdProcedimentoDocumento',
            'id_procedimento',
            'documento');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
          'IdSerieDocumento',
          'id_serie',
          'documento');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'NomeSerie',
          'nome',
          'serie');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'StaNivelAcessoGlobalProtocoloProtocolo',
            'pp.sta_nivel_acesso_global',
            'protocolo pp');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,
            'AberturaAtividade',
            'dth_abertura',
            'atividade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
            'IdDocumentoDocumento',
            'id_documento',
            'documento');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'StaDocumentoDocumento',
            'sta_documento',
            'documento');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'ProtocoloDocumentoFormatado',
            'pd.protocolo_formatado',
            'protocolo pd');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
            'IdUnidadeGeradoraProtocolo',
            'pd.id_unidade_geradora',
            'protocolo pd');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'StaProtocoloProtocolo',
            'pd.sta_protocolo',
            'protocolo pd');
      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'StaEstadoProtocolo',
          'pd.sta_estado',
          'protocolo pd');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'SiglaUnidade',
            'sigla',
            'unidade');

        $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
            'DescricaoUnidade',
            'descricao',
            'unidade');

        //usado na assinatura
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'CargoFuncao');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdOrgaoUsuario');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SenhaUsuario');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjDocumentoDTO');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL, 'IdDocumentoEdoc');

        //usado pelo assinador
        $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'IdsDocumentosAssinados');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Base64PacoteAssinaturas');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'HashPacoteAssinaturas');
        $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'TamanhoAssinaturas');

        $this->configurarPK('IdAssinatura', InfraDTO::$TIPO_PK_NATIVA);

        $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
        $this->configurarFK('IdOrgaoUsuario', 'orgao', 'id_orgao');
        $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
        $this->configurarFK('IdDocumento', 'documento', 'id_documento');
        $this->configurarFK('IdSerieDocumento','serie','id_serie');
        $this->configurarFK('IdDocumentoDocumento', 'protocolo pd', 'pd.id_protocolo');
        $this->configurarFK('IdProcedimentoDocumento', 'protocolo pp', 'pp.id_protocolo');
        $this->configurarFK('IdAtividade', 'atividade', 'id_atividade');

        $this->configurarExclusaoLogica('SinAtivo', 'N');
    }
}

?>