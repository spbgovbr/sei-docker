<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/05/2014 - criado por mga
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class VelocidadeTransferenciaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'velocidade_transferencia';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'Velocidade',
                                   'velocidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUsuario',
                                              'sigla',
                                              'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeUsuario',
                                              'nome',
                                              'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdOrgaoUsuario',
                                              'id_orgao',
                                              'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoUsuario',
                                              'o1.sigla',
                                              'orgao o1');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoOrgaoUsuario',
                                              'o1.descricao',
                                              'orgao o1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdContatoUnidade',
                                              'id_contato',
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
                                              'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaOrgaoUnidade',
                                              'o2.sigla',
                                              'orgao o2');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'DescricaoOrgaoUnidade',
                                              'o2.descricao',
                                              'orgao o2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdCidadeContato',
                                              'id_cidade',
                                              'contato');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeCidadeContato',
                                              'nome',
                                              'cidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
                                              'IdUfCidadeContato',
                                              'id_uf',
                                              'cidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUfCidadeContato',
                                              'sigla',
                                              'uf');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeUfCidadeContato',
                                              'nome',
                                              'uf');
    
    $this->configurarPK('IdUsuario',InfraDTO::$TIPO_PK_INFORMADO);
    
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    $this->configurarFK('IdOrgaoUsuario', 'orgao o1', 'o1.id_orgao');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdOrgaoUnidade', 'orgao o2', 'o2.id_orgao');
    $this->configurarFK('IdContatoUnidade', 'contato', 'id_contato');
    $this->configurarFK('IdCidadeContato', 'cidade', 'id_cidade');
    $this->configurarFK('IdUfCidadeContato', 'uf', 'id_uf');
  }
}
?>