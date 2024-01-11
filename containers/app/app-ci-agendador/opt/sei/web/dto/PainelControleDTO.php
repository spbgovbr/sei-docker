<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 18/08/2017 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class PainelControleDTO extends InfraDTO {

  public function getStrNomeTabela() {
    return null;
  }

  public function montar() {

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUsuario');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'IdUnidade');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ProcessosRecebidos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ProcessosGerados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ProcessosNaoVisualizados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ProcessosSemAcompanhamento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ProcessosAlterados');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjTipoProcedimentoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjGrupoBlocoDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjMarcadorDTO');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjUsuarioDTOAtribuicao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_ARR, 'ObjGrupoAcompanhamentoDTO');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosGerados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosGeradosDocumentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosGeradosAssinados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosDisponibilizados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosDisponibilizadosDocumentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosDisponibilizadosAssinados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosParaRetornar');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosParaRetornarDocumentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosParaRetornarAssinados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosRetornados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosRetornadosDocumentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'BlocosRetornadosAssinados');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPossuiSelecaoGruposBlocos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerSelecaoGruposBlocos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerBlocosSemGrupo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerGruposBlocosZerados');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ControlePrazoNormal');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ControlePrazoNormalAlterados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ControlePrazoAtrasado');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ControlePrazoAtrasadoAlterados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ControlePrazoConcluido');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'ControlePrazoConcluidoAlterados');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoAguardandoNormal');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoAguardandoNormalAlterados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoAguardandoAtrasados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoAguardandoAtrasadosAlterados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoAguardandoConcluidos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoAguardandoConcluidosAlterados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoDevolverNormal');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoDevolverNormalAlterados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoDevolverAtrasados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoDevolverAtrasadosAlterados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoDevolverConcluidos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_NUM, 'RetornoProgramadoDevolverConcluidosAlterados');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPainelProcessos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPainelTiposProcessos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPainelControlesPrazos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPainelRetornosProgramados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPainelBlocos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPainelGruposBlocos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPainelMarcadores');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPainelAtribuicoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPainelAcompanhamentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPainelPaginaInicial');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPossuiSelecaoTiposProcessos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerSelecaoTiposProcessos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerTiposProcessosZerados');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPossuiSelecaoMarcadores');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerSelecaoMarcadores');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerProcessosSemMarcador');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerMarcadoresZerados');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPossuiSelecaoAtribuicoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerSelecaoAtribuicoes');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerProcessosSemAtribuicao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerAtribuicoesZeradas');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinPossuiSelecaoAcompanhamentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerSelecaoAcompanhamentos');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerProcessosSemAcompanhamento');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinVerAcompanhamentosZerados');

    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelAtribuicao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelTipoProcesso');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelInteressados');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelEspecificacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelAnotacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelObservacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelControlePrazo');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelRetornoDevolver');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelRetornoAguardando');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelUltimaMovimentacao');
    $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'SinNivelMarcadores');

  }
}
?>