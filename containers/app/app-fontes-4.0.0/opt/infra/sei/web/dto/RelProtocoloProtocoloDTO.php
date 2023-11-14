<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/07/2008 - criado por mga
*
* Versão do Gerador de Código: 1.21.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelProtocoloProtocoloDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_protocolo_protocolo';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                  'IdRelProtocoloProtocolo',
                                  'id_rel_protocolo_protocolo');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo1',
                                   'id_protocolo_1');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProtocolo2',
                                   'id_protocolo_2');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                    'SinCiencia',
                                    'sin_ciencia');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                  'Sequencia',
                                  'sequencia');
    
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'StaAssociacao',
                                   'sta_associacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Associacao',
                                   'dth_associacao');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'ProtocoloFormatadoProtocolo1',
                                    'p1.protocolo_formatado',
                                    'protocolo p1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'StaProtocoloProtocolo1',
                                    'p1.sta_protocolo',
                                    'protocolo p1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'StaEstadoProtocolo1',
                                    'p1.sta_estado',
                                    'protocolo p1');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'ProtocoloFormatadoProtocolo2',
                                    'p2.protocolo_formatado',
                                    'protocolo p2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'StaProtocoloProtocolo2',
                                    'p2.sta_protocolo',
                                    'protocolo p2');
    
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'StaEstadoProtocolo2',
                                    'p2.sta_estado',
                                    'protocolo p2');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'SiglaUsuario',
                                    'sigla',
                                    'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'NomeUsuario',
                                    'nome',
                                    'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'SiglaUnidade',
                                    'sigla',
                                    'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                    'DescricaoUnidade',
                                    'descricao',
                                    'unidade');
    
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Tipo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Motivo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'IdentificacaoProtocolo1');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'IdentificacaoProtocolo2');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinExclusao');
    
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProtocoloDTO1');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProtocoloDTO2');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAcessoBasico');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinComentarios');
    
    $this->configurarPK('IdRelProtocoloProtocolo',InfraDTO::$TIPO_PK_NATIVA);                                   
    
    $this->configurarFK('IdProtocolo1','protocolo p1','p1.id_protocolo');
    $this->configurarFK('IdProtocolo2','protocolo p2','p2.id_protocolo');
    $this->configurarFK('IdUsuario','usuario','id_usuario');
    $this->configurarFK('IdUnidade', 'unidade', 'id_unidade');

    
  }
}
?>