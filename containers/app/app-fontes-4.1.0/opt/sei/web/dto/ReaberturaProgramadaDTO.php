<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/11/2021 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class ReaberturaProgramadaDTO extends InfraDTO {

  public function getStrNomeTabela() {
  	 return 'reabertura_programada';
  }

  public function montar() {

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdReaberturaProgramada', 'id_reabertura_programada');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'IdProtocolo', 'id_protocolo');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUnidade', 'id_unidade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdUsuario', 'id_usuario');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'IdAtividade', 'id_atividade');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTA, 'Programada', 'dta_programada');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Alteracao', 'dth_alteracao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Processamento', 'dth_processamento');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DTH, 'Visualizacao', 'dth_visualizacao');

    $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Erro', 'erro');


    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'SiglaUsuario',
      'sigla',
      'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'NomeUsuario',
      'nome',
      'usuario');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'ProtocoloFormatadoProtocolo',
      'protocolo_formatado',
      'protocolo');

    $this->adicionarAtributoTabelaRelacionada(InfraDTO::$PREFIXO_STR,
      'ProtocoloFormatadoPesquisaProtocolo',
      'protocolo_formatado_pesquisa',
      'protocolo');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA,'Prazo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM,'Dias');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR,'SinDiasUteis');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA, 'Inicio');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_DTA, 'Fim');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_OBJ, 'ProtocoloDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinAgendadas');

    $this->configurarPK('IdReaberturaProgramada',InfraDTO::$TIPO_PK_NATIVA);

    $this->configurarFK('IdUsuario','usuario','id_usuario');
    $this->configurarFK('IdProtocolo','protocolo','id_protocolo');
  }
}
