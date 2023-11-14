<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/10/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ComentarioDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'comentario';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdComentario', 'id_comentario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProcedimento', 'id_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdRelProtocoloProtocolo', 'id_rel_protocolo_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Descricao', 'descricao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Comentario', 'dth_comentario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaUnidade',
      'sigla',
      'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeUnidade',
      'descricao',
      'unidade');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaUsuario',
      'sigla',
      'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeUsuario',
      'nome',
      'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
        'IdProtocolo2',
        'id_protocolo_2',
        'rel_protocolo_protocolo');


    $this->configurarPK('IdComentario',InfraDTO::$TIPO_PK_NATIVA);
    $this->configurarFK('IdUsuario','usuario','id_usuario');
    $this->configurarFK('IdUnidade','unidade','id_unidade');
    $this->configurarFK('IdRelProtocoloProtocolo','rel_protocolo_protocolo','id_rel_protocolo_protocolo',InfraDTO::$TIPO_FK_OPCIONAL,InfraDTO::$FILTRO_FK_WHERE);
  }
}
