<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/06/2008 - criado por mga
*
* Versão do Gerador de Código: 1.18.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AnexoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'anexo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAnexo',
                                   'id_anexo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo',
                                   'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdBaseConhecimento',
                                   'id_base_conhecimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdProjeto',
                                   'id_projeto');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Nome',
                                   'nome');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'Tamanho',
                                   'tamanho');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Inclusao',
                                   'dth_inclusao');
                                   
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'SinAtivo',
                                   'sin_ativo');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Hash',
                                   'hash');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUnidade',
                                              'sigla',
                                              'unidade');
                                   
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUsuario',
                                              'sigla',
                                              'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'ProtocoloFormatadoProtocolo',
                                              'protocolo_formatado',
                                              'protocolo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdAnexoOrigem');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDuplicando');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinExclusaoAutomatica');
    
    $this->configurarPK('IdAnexo', InfraDTO::$TIPO_PK_NATIVA);
    
    
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    $this->configurarFK('IdProtocolo', 'protocolo', 'id_protocolo');

    $this->configurarExclusaoLogica('SinAtivo', 'N');    
  }
}
?>