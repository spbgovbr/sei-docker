<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/10/2009 - criado por fbv@trf4.gov.br
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class RelBlocoUnidadeDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'rel_bloco_unidade';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdBloco', 'id_bloco');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdGrupoBloco', 'id_grupo_bloco');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioRevisao', 'id_usuario_revisao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioPrioridade', 'id_usuario_prioridade');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioAtribuicao', 'id_usuario_atribuicao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuarioComentario', 'id_usuario_comentario');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinRetornado', 'sin_retornado');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinRevisao', 'sin_revisao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinPrioridade','sin_prioridade');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinComentario','sin_comentario');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'TextoComentario','texto_comentario');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Revisao', 'dth_revisao');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Prioridade', 'dth_prioridade');
    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Comentario', 'dth_comentario');

		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidade', 'u1.sigla', 'unidade u1');
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidade', 'u1.descricao', 'unidade u1');
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaEstadoBloco', 'b.sta_estado', 'bloco b');
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaTipoBloco', 'b.sta_tipo', 'bloco b');
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUnidadeBloco', 'b.id_unidade', 'bloco b');
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUnidadeBloco', 'u2.sigla', 'unidade u2');
		$this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'DescricaoUnidadeBloco', 'u2.descricao','unidade u2');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioPrioridade', 'up.sigla', 'usuario up');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioRevisao', 'ur.sigla', 'usuario ur');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioAtribuicao', 'ua.sigla', 'usuario ua');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioAtribuicao', 'ua.nome', 'usuario ua');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuarioComentario', 'uc.sigla', 'usuario uc');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeUsuarioComentario', 'uc.nome', 'usuario uc');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'NomeGrupoBloco', 'nome', 'grupo_bloco');

					                                   
    $this->configurarPK('IdUnidade',InfraDTO::$TIPO_PK_INFORMADO);
    $this->configurarPK('IdBloco',InfraDTO::$TIPO_PK_INFORMADO);

    $this->configurarFK('IdBloco', 'bloco b', 'b.id_bloco');
    $this->configurarFK('IdUnidade','unidade u1','u1.id_unidade');
    $this->configurarFK('IdUnidadeBloco','unidade u2','u2.id_unidade');
    $this->configurarFK('IdUsuarioAtribuicao','usuario ua','ua.id_usuario',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUsuarioPrioridade','usuario up','up.id_usuario',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUsuarioRevisao','usuario ur','ur.id_usuario',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUsuarioComentario','usuario uc','uc.id_usuario',InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdGrupoBloco','grupo_bloco','id_grupo_bloco',InfraDTO::$TIPO_FK_OPCIONAL);
    
  }
}
?>