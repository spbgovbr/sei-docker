<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 01/12/2014 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class ControleUnidadeDTO extends InfraDTO {

  public function __construct(){
    parent::__construct();
  }

  public function getStrNomeTabela() {
		return 'controle_unidade';
	}

	public function montar() {

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
          'IdControleUnidade',
          'id_controle_unidade');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL,
          'IdProcedimento',
          'id_procedimento');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
          'IdSituacao',
          'id_situacao');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM,
          'IdUsuario',
          'id_usuario');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
          'Execucao',
          'dth_execucao');

      $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH,
          'Snapshot',
          'dth_snapshot');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_DBL,
          'IdProcedimentoProcedimento',
          'id_procedimento',
          'procedimento');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_NUM,
          'IdTipoProcedimentoProcedimento',
          'id_tipo_procedimento',
          'procedimento');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'ProtocoloFormatadoProcedimento',
          'protocolo_formatado',
          'protocolo');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'NomeTipoProcedimento',
          'nome',
          'tipo_procedimento');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'NomeSituacao',
          'nome',
          'situacao');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'SinAtivoSituacao',
          'sin_ativo',
          'situacao');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
        'SiglaUsuario',
        'sigla',
        'usuario');

      $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
          'NomeUsuario',
          'nome',
          'usuario');

      $this->configurarFK('IdProcedimento', 'procedimento', 'id_procedimento');
      $this->configurarFK('IdTipoProcedimentoProcedimento', 'tipo_procedimento', 'id_tipo_procedimento');
      $this->configurarFK('IdProcedimentoProcedimento', 'protocolo', 'id_protocolo');
      $this->configurarFK('IdUsuario', 'usuario', 'id_usuario');
      $this->configurarFK('IdSituacao', 'situacao', 'id_situacao');
	}
}
?>