<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/03/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class NovidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'novidade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdNovidade',
                                   'id_novidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Titulo',
                                   'titulo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Liberacao',
                                   'dth_liberacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaUsuario',
                                             'sigla',
                                             'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomeUsuario',
                                             'nome',
                                             'usuario');
                                             
    $this->configurarPK('IdNovidade', InfraDTO::$TIPO_PK_NATIVA );
    
    $this->configurarFK('IdUsuario','usuario','id_usuario');

  }
}
?>