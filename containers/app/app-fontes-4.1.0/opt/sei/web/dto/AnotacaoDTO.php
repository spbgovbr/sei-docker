<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 22/03/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AnotacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'anotacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAnotacao',
                                   'id_anotacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo',
                                   'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Descricao',
                                   'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinPrioridade',
                                   'sin_prioridade');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Anotacao',
                                   'dth_anotacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaAnotacao',
                                   'sta_anotacao');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'SiglaUsuario',
                                             'sigla',
                                             'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                             'NomeUsuario',
                                             'nome',
                                             'usuario');

    $this->configurarPK('IdAnotacao', InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdUsuario','usuario','id_usuario');
  }
}
?>