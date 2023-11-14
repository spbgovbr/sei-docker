<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/07/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class VeiculoPublicacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'veiculo_publicacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdVeiculoPublicacao',
                                   'id_veiculo_publicacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaTipo',
                                   'sta_tipo');
            
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinFonteFeriados',
                                   'sin_fonte_feriados');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinPermiteExtraordinaria',
                                   'sin_permite_extraordinaria');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinExibirPesquisaInterna',
                                  'sin_exibir_pesquisa_interna');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'WebService',
                                   'web_service');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR,'ObjPublicacaoDTO');

    $this->configurarPK('IdVeiculoPublicacao',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
?>