<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/11/2010 - criado por mga
*
* Versão do Gerador de Código: 1.17.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class EstatisticasArquivamentoDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return 'atributo_andamento';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdAtributoAndamento','id_atributo_andamento');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,'IdAtividade','id_atividade');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Nome','nome');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'Valor','valor');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,'IdOrigem','id_origem');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdTarefaAtividade','id_tarefa','atividade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,'IdProtocoloAtividade','id_protocolo','atividade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUnidadeOrigemAtividade','id_unidade_origem','atividade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUnidadeAtividade','id_unidade','atividade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DTH,'AberturaAtividade','dth_abertura','atividade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUsuarioAtividade','id_usuario','atividade');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdUsuarioOrigemAtividade','id_usuario_origem','atividade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,'IdDocumento','id_documento','documento');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdSerieDocumento','id_serie','documento');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NumeroDocumento','numero','documento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeSerie','nome','serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'ProtocoloFormatado','protocolo_formatado','protocolo');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdLocalizadorArquivamento','id_localizador','arquivamento');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdTipoLocalizadorLocalizador','id_tipo_localizador','localizador');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'SeqLocalizador','seq_localizador','localizador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'SiglaTipoLocalizador','sigla','tipo_localizador');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeTipoLocalizador','nome','tipo_localizador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'NomeTipoLocalizadorAndamento','tl.nome','tipo_localizador tl');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'IdTipoLocalizadorAndamento','l.id_tipo_localizador','localizador l');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,'SeqLocalizadorAndamento','seq_localizador','localizador l');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,'StaEstadoLocalizadorAndamento','sta_estado','localizador l');


    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Inicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Fim');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'Arquivados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'Desarquivados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'Recebidos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'EliminadosFisicos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'Localizadores');


    $this->configurarPK('IdAtributoAndamento', InfraDTO::$TIPO_PK_NATIVA );

    $this->configurarFK('IdAtividade', 'atividade', 'id_atividade');
    $this->configurarFK('IdOrigem','documento','id_documento');
    $this->configurarFK('IdOrigem','protocolo','id_protocolo');
    $this->configurarFK('IdOrigem','arquivamento','id_protocolo');
    $this->configurarFK('IdOrigem','localizador l','id_localizador');
    $this->configurarFK('IdSerieDocumento','serie','id_serie');
    $this->configurarFK('IdLocalizadorArquivamento','localizador','id_localizador',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdTipoLocalizadorLocalizador','tipo_localizador','id_tipo_localizador');
    $this->configurarFK('IdTipoLocalizadorAndamento','tipo_localizador tl','tl.id_tipo_localizador');


  }
}
?>