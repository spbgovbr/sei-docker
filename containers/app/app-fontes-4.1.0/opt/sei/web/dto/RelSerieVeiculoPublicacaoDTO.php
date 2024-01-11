<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/07/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelSerieVeiculoPublicacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_serie_veiculo_publicacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdSerie',
                                   'id_serie');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdVeiculoPublicacao',
                                   'id_veiculo_publicacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeSerie',
                                              'nome',
                                              'serie');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAssinaturaPublicacaoSerie',
                                              'sin_assinatura_publicacao',
                                              'serie');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeVeiculoPublicacao',
                                              'nome',
                                              'veiculo_publicacao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaTipoVeiculoPublicacao',
                                              'sta_tipo',
                                              'veiculo_publicacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAtivoVeiculoPublicacao',
                                              'sin_ativo',
                                              'veiculo_publicacao');

    $this->configurarPK('IdSerie',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdVeiculoPublicacao',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdSerie', 'serie', 'id_serie');
    $this->configurarFK('IdVeiculoPublicacao', 'veiculo_publicacao', 'id_veiculo_publicacao');
  }
}
?>