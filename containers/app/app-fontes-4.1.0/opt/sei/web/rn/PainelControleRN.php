<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 28/11/2017 - criado por mga
 *
 */

require_once dirname(__FILE__).'/../SEI.php';

class PainelControleRN extends InfraRN
{

  public static $TA_INTERESSADO = 'I';
  public static $TA_USUARIO_EXTERNO = 'E';
  public static $TA_DESTINATARIO_ISOLADO = 'D';
  public static $TA_SISTEMA = 'S';
  public static $TA_ASSINATURA_EXTERNA = 'A';

  public static $TV_INTEGRAL = 'I';
  public static $TV_PARCIAL = 'P';
  public static $TV_NENHUM = 'N';

  public function __construct()
  {
    parent::__construct();
  }

  protected function inicializarObjInfraIBanco()
  {
    return BancoSEI::getInstance();
  }

  protected function carregarConfiguracoesConectado()
  {
    try {

      $objInfraDadoUsuario = new InfraDadoUsuario(SessaoSEI::getInstance());
      $arrConfiguracao = unserialize($objInfraDadoUsuario->getValor('PAINEL_CONTROLE_'.SessaoSEI::getInstance()->getNumIdUnidadeAtual()));

      $objPainelControleDTO = new PainelControleDTO();

      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelProcessos', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelTiposProcessos', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelTiposPrioritarios', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelControlesPrazos', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelRetornosProgramados', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelBlocos', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelGruposBlocos', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelMarcadores', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelAtribuicoes', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelAcompanhamentos', 'S');

      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoTiposProcessos', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerTiposProcessosZerados', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoGruposBlocos', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerBlocosSemGrupo', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerGruposBlocosZerados', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoMarcadores', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerProcessosSemMarcador', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerMarcadoresZerados', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoAtribuicoes', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerProcessosSemAtribuicao', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerAtribuicoesZeradas', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoAcompanhamentos', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerProcessosSemAcompanhamento', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerAcompanhamentosZerados', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPossuiSelecaoTiposPrioritarios', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoTiposPrioritarios', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerTiposProcessosPrioritarios', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerTiposPrioritariosZerados', 'N');


      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelAtribuicao', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelAnotacao', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelTipoProcesso', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelEspecificacao', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelPrioritarios', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelInteressados', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelObservacao', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelControlePrazo', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelRetornoDevolver', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelRetornoAguardando', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelUltimaMovimentacao', 'S');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelMarcadores', 'N');
      $this->lerSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelPaginaInicial', 'N');

      return $objPainelControleDTO;

    } catch (Exception $e) {
      throw new InfraException('Erro carregando configurações do Painel de Controle.', $e);
    }
  }

  protected function salvarConfiguracoesControlado(PainelControleDTO $objPainelControleDTO)
  {
    try {

      $objInfraDadoUsuario = new InfraDadoUsuario(SessaoSEI::getInstance());
      $arrConfiguracao = unserialize($objInfraDadoUsuario->getValor('PAINEL_CONTROLE_'.SessaoSEI::getInstance()->getNumIdUnidadeAtual()));

      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelProcessos');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelTiposProcessos');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelTiposPrioritarios');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelControlesPrazos');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelRetornosProgramados');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelBlocos');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelGruposBlocos');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerBlocosSemGrupo');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerGruposBlocosZerados');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelMarcadores');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelAtribuicoes');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelAcompanhamentos');

      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoTiposProcessos');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerTiposProcessosZerados');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoTiposPrioritarios');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerTiposPrioritariosZerados');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoGruposBlocos');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerBlocosSemGrupo');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerGruposBlocosZerados');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoMarcadores');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerProcessosSemMarcador');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerMarcadoresZerados');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoAtribuicoes');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerProcessosSemAtribuicao');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerAtribuicoesZeradas');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerSelecaoAcompanhamentos');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerProcessosSemAcompanhamento');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinVerAcompanhamentosZerados');

      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelAtribuicao');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelAnotacao');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelTipoProcesso');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelEspecificacao');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelPrioritarios');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelInteressados');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelObservacao');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelControlePrazo');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelRetornoDevolver');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelRetornoAguardando');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelUltimaMovimentacao');
      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinNivelMarcadores');

      $this->gravarSinalizador($arrConfiguracao, $objPainelControleDTO, 'SinPainelPaginaInicial');

      $objInfraDadoUsuario->setValor('PAINEL_CONTROLE_'.SessaoSEI::getInstance()->getNumIdUnidadeAtual(), serialize($arrConfiguracao));

    } catch (Exception $e) {
      throw new InfraException('Erro salvando configurações do Painel de Controle.', $e);
    }
  }

  private function lerSinalizador($arrConfiguracao, PainelControleDTO $objPainelControleDTO, $strNome, $strValorPadrao = 'N') {
    if (!isset($arrConfiguracao[$strNome]) || !InfraUtil::isBolSinalizadorValido($arrConfiguracao[$strNome])) {
      $objPainelControleDTO->set($strNome,$strValorPadrao);
    }else{
      $objPainelControleDTO->set($strNome,$arrConfiguracao[$strNome]);
    }
  }

  private function gravarSinalizador(&$arrConfiguracao, PainelControleDTO $objPainelControleDTO, $strNome) {
    if ($objPainelControleDTO->isSetAtributo($strNome) && InfraUtil::isBolSinalizadorValido($objPainelControleDTO->get($strNome))) {
      $arrConfiguracao[$strNome] = $objPainelControleDTO->get($strNome);
    }
  }

}
?>