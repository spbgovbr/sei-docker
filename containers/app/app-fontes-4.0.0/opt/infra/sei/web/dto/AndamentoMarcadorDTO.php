<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class AndamentoMarcadorDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'andamento_marcador';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdAndamentoMarcador',
                                   'id_andamento_marcador');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdMarcador',
                                   'id_marcador');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
                                   'IdProcedimento',
                                   'id_procedimento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUnidade',
                                   'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
                                   'IdUsuario',
                                   'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
                                   'Execucao',
                                   'dth_execucao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                   'Texto',
                                   'texto');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'StaOperacao',
                                  'sta_operacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinUltimo',
                                  'sin_ultimo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR,
                                  'SinAtivo',
                                  'sin_ativo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeMarcador',
                                              'nome',
                                              'marcador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'StaIconeMarcador',
                                              'sta_icone',
                                              'marcador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SinAtivoMarcador',
                                              'sin_ativo',
                                              'marcador');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'SiglaUsuario',
                                              'sigla',
                                              'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
                                              'NomeUsuario',
                                              'nome',
                                              'usuario');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'ArquivoIconeMarcador');

    $this->configurarPK('IdAndamentoMarcador',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdMarcador', 'marcador', 'id_marcador', InfraDTO::$TIPO_FK_OPCIONAL);
    $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');

    $this->configurarExclusaoLogica('SinAtivo', 'N');
  }
}
?>