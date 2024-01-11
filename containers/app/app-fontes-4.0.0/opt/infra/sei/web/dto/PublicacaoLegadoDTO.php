<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class PublicacaoLegadoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'publicacao_legado';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdPublicacaoLegado',
                                   'id_publicacao_legado');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdPublicacaoLegadoAgrupador',
                                   'id_publicacao_legado_agrupador');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdVeiculoIO',
                                   'id_veiculo_io');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSecaoIO',
                                   'id_secao_io');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdVeiculoPublicacao',
                                   'id_veiculo_publicacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'IdDocumento',
                                   'id_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Publicacao',
                                   'dta_publicacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Numero',
                                   'numero');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Resumo',
                                   'resumo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'ConteudoDocumento',
                                   'conteudo_documento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'PaginaIO',
                                   'pagina_io');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'PublicacaoIO',
                                   'dta_publicacao_io');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA,
                                   'Geracao',
                                   'dta_geracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'ProtocoloFormatado',
                                   'protocolo_formatado');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSerie',
                                              'nome',
                                              'serie');

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
                                              'SiglaVeiculoImprensaNacional',
                                              'sigla',
                                              'veiculo_imprensa_nacional');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoVeiculoImprensaNacional',
                                              'descricao',
                                              'veiculo_imprensa_nacional');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSecaoImprensaNacional',
                                              'nome',
                                              'secao_imprensa_nacional');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeVeiculoPublicacao',
                                              'nome',
                                              'veiculo_publicacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaTipoVeiculoPublicacao',
                                              'sta_tipo',
                                              'veiculo_publicacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoVeiculoPublicacao',
                                              'descricao',
                                              'veiculo_publicacao');

    $this->configurarPK('IdPublicacaoLegado',InfraDTO::$TIPO_PK_SEQUENCIAL);

    $this->configurarFK('IdSerie', 'serie', 'id_serie');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');    
    $this->configurarFK('IdOrgaoUnidade','orgao','id_orgao');
    $this->configurarFK('IdVeiculoPublicacao', 'veiculo_publicacao', 'id_veiculo_publicacao');
    $this->configurarFK('IdVeiculoIO','veiculo_imprensa_nacional','id_veiculo_imprensa_nacional',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdSecaoIO','secao_imprensa_nacional','id_secao_imprensa_nacional',InfraDTO::$TIPO_FK_OPCIONAL);
  }
}
?>