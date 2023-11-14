<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class CpadAvaliacaoDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'cpad_avaliacao';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdCpadAvaliacao', 'id_cpad_avaliacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAvaliacaoDocumental', 'id_avaliacao_documental');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdCpadComposicao', 'id_cpad_composicao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Avaliacao', 'dth_avaliacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'StaCpadAvaliacao', 'sta_cpad_avaliacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Motivo', 'motivo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Justificativa', 'justificativa');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'SinAtivo', 'sin_ativo');

    $this->configurarPK('IdCpadAvaliacao',InfraDTO::$TIPO_PK_NATIVA);

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdCpadComposicao', 'id_cpad_composicao', 'cpad_composicao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdCpadVersao', 'id_cpad_versao', 'cpad_composicao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdCpad', 'id_cpad', 'cpad_versao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario', 'cpad_composicao');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'SiglaUsuario',  'sigla',   'usuario');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,  'NomeUsuario',   'nome',     'usuario');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL, 'IdProcedimentoAvaliacaoDocumental', 'id_procedimento', 'avaliacao_documental');
    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR, 'StaAvaliacaoAvaliacaoDocumental', 'sta_avaliacao', 'avaliacao_documental');

    $this->configurarFK('IdCpadComposicao', 'cpad_composicao', 'id_cpad_composicao');
    $this->configurarFK('IdCpadVersao', 'cpad_versao', 'id_cpad_versao');
    $this->configurarFK('IdCpad', 'cpad', 'id_cpad');
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
    $this->configurarFK('IdAvaliacaoDocumental', 'avaliacao_documental', 'id_avaliacao_documental');

    $this->configurarExclusaoLogica('SinAtivo', 'N');

  }
}
