<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 31/01/2008 - criado por marcio_db
 * 15/06/2018 - cjy - ícone de acompanhamento no controle de processos
 *
 * Versão do Gerador de Código: 1.13.1
 *
 * Versão no CVS: $Id$
 */

require_once dirname(__FILE__) . '/../SEI.php';

class PesquisaAvaliacaoDocumentalDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'protocolo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
      'IdProtocolo',
      'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
      'IdProtocoloFederacao',
      'id_protocolo_federacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
      'ProtocoloFormatado',
      'protocolo_formatado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
      'ProtocoloFormatadoPesquisa',
      'protocolo_formatado_pesquisa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
      'StaNivelAcessoGlobal',
      'sta_nivel_acesso_global');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
      'StaEstado',
      'sta_estado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
      'Geracao',
      'dta_geracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
      'IdUsuarioGeradorProtocolo',
      'id_usuario_gerador');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
      'IdUnidadeGeradora',
      'id_unidade_geradora');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdOrgaoUnidadeGeradoraProtocolo',
      'uni_ger.id_orgao',
      'unidade uni_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdUnidadeGeradoraProtocolo',
      'uni_ger.id_orgao',
      'unidade uni_ger');


    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaUsuarioGerador',
      'usu_ger.sigla',
      'usuario usu_ger');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdTipoProcedimento',
      'p.id_tipo_procedimento',
      'procedimento p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
      'ConclusaoProcedimento',
      'p.dta_conclusao',
      'procedimento p');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
      'IdProcedimento',
      'p.id_procedimento',
      'procedimento p');


    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeTipoProcedimento',
      'tpp.nome',
      'tipo_procedimento tpp');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaAvaliacaoDocumental',
      'ad.sta_avaliacao',
      'avaliacao_documental ad');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTA,
      'AvaliacaoDocumental',
      'ad.dta_avaliacao',
      'avaliacao_documental ad');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAvaliacaoDocumental',
      'ad.id_avaliacao_documental',
      'avaliacao_documental ad');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssuntoOriginalAvaliacaoDocumental',
      'ad.id_assunto',
      'avaliacao_documental ad');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssuntoOriginalAssunto',
      'ass_ori.id_assunto',
      'assunto ass_ori');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'PrazoCorrenteAssuntoOriginal',
      'ass_ori.prazo_corrente',
      'assunto ass_ori');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'PrazoIntermediarioAssuntoOriginal',
      'ass_ori.prazo_intermediario',
      'assunto ass_ori');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaDestinacaoAssuntoOriginal',
      'ass_ori.sta_destinacao',
      'assunto ass_ori');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdUsuarioAvaliacaoDocumental',
      'ad.id_usuario',
      'avaliacao_documental ad');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssuntoProxyAvaliacaoDocumental',
      'ad.id_assunto_proxy',
      'avaliacao_documental ad');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssuntoAvaliacaoDocumental',
      'ass_pro_ava.id_assunto',
      'assunto_proxy ass_pro_ava');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaDestinacaoAssuntoAvaliacaoDocumental',
      'ass_ava.sta_destinacao',
      'assunto ass_ava');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'PrazoCorrenteAssuntoAvaliacaoDocumental',
      'ass_ava.prazo_corrente',
      'assunto ass_ava');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'PrazoIntermediarioAssuntoAvaliacaoDocumental',
      'ass_ava.prazo_intermediario',
      'assunto ass_ava');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdUnidadeAvaliacaoDocumental',
      'ad.id_unidade',
      'avaliacao_documental ad');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdOrgaoAvaliacaoDocumental',
      'uni_ava.id_orgao',
      'unidade ');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaUsuarioAvaliacaoDocumental',
      'usu_ava.sigla',
      'usuario usu_ava');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeUsuarioAvaliacaoDocumental',
      'usu_ava.nome',
      'usuario usu_ava');


    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
      'IdProcedimentoRelProtocoloAssunto',
      'rel_pro_ass.id_procedimento',
      'rel_protocolo_assunto rel_pro_ass');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssuntoProxyRelProtocoloAssunto',
      'rel_pro_ass.id_assunto_proxy',
      'rel_protocolo_assunto rel_pro_ass');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssuntoAssuntoProxy',
      'ass_pro.id_assunto',
      'assunto_proxy ass_pro');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaDestinacaoAssunto',
      'ass.sta_destinacao',
      'assunto ass');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'DescricaoAssunto',
      'ass.descricao',
      'assunto ass');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssuntoAssunto',
      'ass.id_assunto',
      'assunto ass');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssuntoAssunto2',
      'ass2.id_assunto',
      'assunto ass2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaDestinacaoAssunto2',
      'ass2.sta_destinacao',
      'assunto ass2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdCpadAvaliacao',
      'ca.id_cpad_avaliacao',
      'cpad_avaliacao ca');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SinAtivoCpadAvaliacao',
      'ca.sin_ativo',
      'cpad_avaliacao ca');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaCpadAvaliacao',
      'ca.sta_cpad_avaliacao',
      'cpad_avaliacao ca');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdCpadComposicao',
      'ca.id_cpad_composicao',
      'cpad_avaliacao ca');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdUsuarioCpadComposicao',
      'cc.id_usuario',
      'cpad_composicao cc');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdCpadVersao',
      'cc.id_cpad_versao',
      'cpad_composicao cc');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdCpadCpadVersao',
      'cv.id_cpad',
      'cpad_versao cv');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdCpad',
      'cp.id_cpad',
      'cpad cp');


    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
      'IdProtocolo2RelProtocolo',
      'rel_pro_pro.id_protocolo_2',
      'rel_protocolo_protocolo rel_pro_pro');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssuntoProxyRelProtocoloAssunto2',
      'rel_pro_ass2.id_assunto_proxy',
      'rel_protocolo_assunto rel_pro_ass2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdAssuntoAssuntoProxy2',
      'ass_pro2.id_assunto',
      'assunto_proxy ass_pro2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'StaAssociacaoRelProtocoloProtocolo',
      'rel_pro_pro.sta_associacao',
      'rel_protocolo_protocolo rel_pro_pro');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
      'IdEliminacaoDocumentalConteudo',
      'eli_con.id_avaliacao_documental',
      'edital_eliminacao_conteudo eli_con');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_DBL,'IdProcedimento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjRelProtocoloAssuntoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinUnidadeGeradoraProtocolo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'GeracaoInicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'GeracaoFim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'ConclusaoInicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'ConclusaoFim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'PeriodoInicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'PeriodoFim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinAvaliacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDiscordancia');

    $this->configurarPK('IdProtocolo',InfraDTO::$TIPO_PK_INFORMADO);

    //join com procedimento, pois a avaliacao é sempre em procedimento
    $this->configurarFK('IdProtocolo', 'procedimento p', 'p.id_procedimento');
    //join com tipo de procedimento, referente ao tipo de processo
    $this->configurarFK('IdTipoProcedimento', 'tipo_procedimento tpp', 'tpp.id_tipo_procedimento');
    //join com a unidade, referente a unidade do processo
    $this->configurarFK('IdUnidadeGeradora', 'unidade uni_ger', 'uni_ger.id_unidade');
    //join com o orgao, referente ao orgao do processo
    $this->configurarFK('IdOrgaoUnidadeGeradoraProtocolo', 'orgao', 'id_orgao');
    //join com usuario, referente ao usuario do processo
    $this->configurarFK('IdUsuarioGeradorProtocolo', 'usuario usu_ger', 'usu_ger.id_usuario');
    //join com a avaliacao documental do processo, que nem sempre existe
    //aqui é o join é no 'where' e opcional, pois deve ser feito um left join da tabela de processo com a avaliacao_documental, retornando processos mesmo que nao tenham avaliacao documental
    //assim, na tela de listagem há duas situacoes:
    //  1. lista os processos que nao tem avaliacao documental: testa com a coluna/atributo avaliacao_documental.id_avaliacao_documental (poderia ser outra coluna) igual a null (será colocado no where), assim retornara os processos no left join que nao tem valor nessa coluna, ou seja, nao tem avaliacao documental
    //  2. lista os processos que tem avaliacao documental: testa com a coluna/atributo avaliacao_documental.id_avaliacao_documental (poderia ser outra coluna) diferente de null (será colocado no where), assim retornara os processos no left join que tem valor nessa coluna, ou seja, tem avaliacao documental
    //obs.: no caso "2.", ainda podem ser listados apenas os processos que tem avaliacao documental e tambem alguma avaliacao cpad com divergencia. Esse caso é tratado a partir do join com a avaliacao_cpad, em seguida aqui no dto
    $this->configurarFK('IdProcedimento', 'avaliacao_documental ad', 'ad.id_procedimento', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com assunto proxy da avaliacao documental
    $this->configurarFK('IdAssuntoProxyAvaliacaoDocumental', 'assunto_proxy ass_pro_ava', 'ass_pro_ava.id_assunto_proxy', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com assunto da avaliacao documental
    $this->configurarFK('IdAssuntoAvaliacaoDocumental', 'assunto ass_ava', 'ass_ava.id_assunto', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com a unidade da avaliacao documental do processo, que é sempre a unidade de quem realizou a avaliacao
    $this->configurarFK('IdUnidadeAvaliacaoDocumental', 'unidade uni_ava', 'uni_ava.id_unidade', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com o assunto original da avaliacao documental
    // o assunto original serve caso mude a tabela de assuntos e o assunto (via assunto proxy) mude, assim mantem o original, usado para a eliminacao
    $this->configurarFK('IdAssuntoOriginalAvaliacaoDocumental', 'assunto ass_ori', 'ass_ori.id_assunto', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com o usuario da avaliacao documental do processo, que é quemrealizou a avaliacao
    $this->configurarFK('IdUsuarioAvaliacaoDocumental', 'usuario usu_ava', 'usu_ava.id_usuario', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com rel_protocolo_assunto, que contem os assuntos proxy do processo
    //o join é com a coluna id_protocolo_procedimento, pois a tabela rel_protocolo_assunto contem assuntos de processos e de documentos, assim são buscados os asssuntos tanto do processo, quanto de seus documentos
    //se for um processo, as colunas id_protocolo_procedimento e id_protocolo sao iguais; se for documento, a coluna id_protocolo_procedimento contem o processo e a id_protocolo o documentio
    $this->configurarFK('IdProcedimento', 'rel_protocolo_assunto rel_pro_ass', 'rel_pro_ass.id_protocolo_procedimento', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com assunto_proxy, que contem os assuntos proxy do processo
    $this->configurarFK('IdAssuntoProxyRelProtocoloAssunto', 'assunto_proxy ass_pro', 'ass_pro.id_assunto_proxy', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com assunto, que contem as informacoes de um assunto do processo
    $this->configurarFK('IdAssuntoAssuntoProxy', 'assunto ass', 'ass.id_assunto', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com cpad_avaliacao, que contem as avaliacoes cpad de uma avaliacao documental do processo
    //aqui o join é no 'on', pois sempre que é feita uma avaliacao cpad, essa nova fica com ativo igual a 'S'.
    //entao, na listagem de processos que o usuario pode fazer uma avaliacao cpad, é filtrado por processos que tem avaliacao cpad com ativo igual a 'S'
    //como é um left join (parametro 'opciional'), retornará processos que tem ou nao tem avaliacao documental e avaliacao cpad
    //contudo os joins seguintes com a composicao cpad e versao cpad são obrigatorios, pois sempre que há avaliacao cpad, há registros nessas tabelas, e são realizados filtros/restricoes de usuario e de versao nelas, entao nao poderia ser left join
    //há duas telas e situacoes que usa isso:
    //  1. na listagem de avaliacao documental, para listar os processos que já tem avaliacao cpad (ativas) e que pelo menos é negada (usa distinct, pois pode haver mais de uma avaliacao cpad negada)
    //      como o join com a avaliacao cpad é um left e os filtros sao feitos no 'on', deve ser filtrado por cpad.id_cpad nao nulo, já que os filtros com a cpad sao no where
    //  2. na listagem de avaliacoes cpad que podem ser realizadas pelo usuario.
    //      nesse caso, os joins com a cpad_componente e cpad_versao devem ser inner, pois sao filtradas avaliacoes cpad feitas pelo usuario e da ultima versao (ativa), assim há tres possibilidades de retorno:
    //      2.1 tem avaliacao documental, mas nao avaliacao cpad: retorna registro, mas cpad.id_cpad é nulo
    //      2.2 tem avaliacao documental e tem avaliacao cpad, mas por outros usuarios: nao retorna registros, devido aos inner join
    //      2.3 tem avaliacao documental e tem avaliacao cpad com esse usuario: retorna registro e cpad.id_cpad nao é nulo
    // obs.: caso uma avaliacao cpad seja 'negado', após ser realizada a justificativa na avaliacao documental, essa avaliacao cpad fica com ativo igual a 'N', assim o processo novamente é retornado na listagem de avaliacoes cpad a serem realizadas pelo usuario
    $this->configurarFK('IdAvaliacaoDocumental', 'cpad_avaliacao ca', 'ca.id_avaliacao_documental', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_ON);
    //join com cpad_composicao, que contem a composicao cpad de uma avaliacao cpad do processo
    $this->configurarFK('IdCpadComposicao', 'cpad_composicao cc', 'cc.id_cpad_composicao');
    //join com cpad_versap, que contem a versao cpad de uma avaliacao cpad do processo
    $this->configurarFK('IdCpadVersao', 'cpad_versao cv', 'cv.id_cpad_versao');
    //join com cpad, via where, com a cpad que fez a avaliacao
    $this->configurarFK('IdCpadCpadVersao', 'cpad cp', 'cp.id_cpad', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);

    $this->configurarFK('IdAvaliacaoDocumental', 'edital_eliminacao_conteudo eli_con', 'eli_con.id_avaliacao_documental', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);

    //join com rel_protocolo_protocolo, que contem processos relacionados ao processo
    //usado para buscar os processos anexados ao processo, para buscar os assuntos desse processo anexado e os assuntos dos documentos do proceso anexado
    $this->configurarFK('IdProcedimento', 'rel_protocolo_protocolo rel_pro_pro', 'rel_pro_pro.id_protocolo_1', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_ON);
    //join com rel_protocolo_assunto novamente, mas que contem os assuntos proxy do processo anexado e dos seus documentos
    $this->configurarFK('IdProtocolo2RelProtocolo', 'rel_protocolo_assunto rel_pro_ass2', 'rel_pro_ass2.id_protocolo_procedimento', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com assunto_proxy novamente, mas que contem informacoes dos assuntos proxy do processo anexado e dos seus documentos
    $this->configurarFK('IdAssuntoProxyRelProtocoloAssunto2', 'assunto_proxy ass_pro2', 'ass_pro2.id_assunto_proxy', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);
    //join com assunto novamente, mas que contem as informacoes de um assunto do processo anexado e dos seus documentos
    $this->configurarFK('IdAssuntoAssuntoProxy2', 'assunto ass2', 'ass2.id_assunto', InfraDTO::$TIPO_FK_OPCIONAL, InfraDTO::$FILTRO_FK_WHERE);

    //obs.: existem joins que sao no 'where', pois existem atributos usados como filtros dos processos na pesquisa, e como sao opcionais, nao poderiam ficar no 'on' do left join pois retornariam igual processos nao correspondentes aos filtros
  }
}
?>