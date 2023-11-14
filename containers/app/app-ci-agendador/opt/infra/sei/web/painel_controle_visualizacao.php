<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4º REGIÃO
 *
 * 30/08/2017 - criado por mga
 *
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  //PaginaSEI::getInstance()->prepararSelecao('procedimento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //$numSeg = InfraUtil::verificarTempoProcessamento();

  $arrComandos = array();

  switch ($_GET['acao']) {

    case 'painel_controle_visualizar':
      $strTitulo = 'Painel de Controle';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  NovidadeINT::processar($strJsNovidades);

  $arrComandos[] = '<button type="submit" id="sbmAtualizar" name="sbmAtualizar" value="Atualizar" class="infraButton">Atualizar</button>';

  if (SessaoSEI::getInstance()->verificarPermissao('painel_controle_configurar')){
    $arrComandos[] = '<button type="button" id="btnConfigurar" name="btnConfigurar" value="Configurar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=painel_controle_configurar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\';" class="infraButton">Configurar</button>';
  }

  $strTabResumo = '';
  $strTabControlePrazo = '';
  $strTabRetornoProgramado = '';
  $strTabMarcadores = '';
  $strTabAtribuicoes = '';
  $strTabAcompanhamentos = '';
  $strTabBlocos = '';
  $strTabGruposBlocos = '';


  $objPainelControleRN = new PainelControleRN();
  $objPainelControleDTO = $objPainelControleRN->carregarConfiguracoes();

  $bolSalvarConfiguracoes = false;

  if (isset($_POST['hdnSinVerTiposProcessos']) && $objPainelControleDTO->getStrSinVerSelecaoTiposProcessos()!=$_POST['hdnSinVerTiposProcessos']){
    $objPainelControleDTO->setStrSinVerSelecaoTiposProcessos($_POST['hdnSinVerTiposProcessos']);
    $bolSalvarConfiguracoes = true;
  }

  if (isset($_POST['hdnSinVerGruposBlocos']) && $objPainelControleDTO->getStrSinVerSelecaoGruposBlocos()!=$_POST['hdnSinVerGruposBlocos']){
    $objPainelControleDTO->setStrSinVerSelecaoGruposBlocos($_POST['hdnSinVerGruposBlocos']);
    $bolSalvarConfiguracoes = true;
  }

  if (isset($_POST['hdnSinVerMarcadores']) && $objPainelControleDTO->getStrSinVerSelecaoMarcadores()!=$_POST['hdnSinVerMarcadores']){
    $objPainelControleDTO->setStrSinVerSelecaoMarcadores($_POST['hdnSinVerMarcadores']);
    $bolSalvarConfiguracoes = true;
  }

  if (isset($_POST['hdnSinVerAtribuicoes']) && $objPainelControleDTO->getStrSinVerSelecaoAtribuicoes()!=$_POST['hdnSinVerAtribuicoes']){
    $objPainelControleDTO->setStrSinVerSelecaoAtribuicoes($_POST['hdnSinVerAtribuicoes']);
    $bolSalvarConfiguracoes = true;
  }

  if (isset($_POST['hdnSinVerAcompanhamentos']) && $objPainelControleDTO->getStrSinVerSelecaoAcompanhamentos()!=$_POST['hdnSinVerAcompanhamentos']){
    $objPainelControleDTO->setStrSinVerSelecaoAcompanhamentos($_POST['hdnSinVerAcompanhamentos']);
    $bolSalvarConfiguracoes = true;
  }

  if ($bolSalvarConfiguracoes) {
    $objPainelControleRN->salvarConfiguracoes($objPainelControleDTO);
  }

  $objPainelControleDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
  $objPainelControleDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  $objGrupoBlocoDTO = new GrupoBlocoDTO();
  PaginaSEI::getInstance()->prepararOrdenacao($objGrupoBlocoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, false, 'GruposBlocos');

  $objTipoProcedimentoDTO = new TipoProcedimentoDTO();
  PaginaSEI::getInstance()->prepararOrdenacao($objTipoProcedimentoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, false, 'TiposProcessos');

  $objMarcadorDTO = new MarcadorDTO();
  PaginaSEI::getInstance()->prepararOrdenacao($objMarcadorDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, false, 'Marcadores');

  $objUsuarioDTO = new UsuarioDTO();
  PaginaSEI::getInstance()->prepararOrdenacao($objUsuarioDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, false, 'Atribuicoes');

  $objGrupoAcompanhamentoDTO = new GrupoAcompanhamentoDTO();
  PaginaSEI::getInstance()->prepararOrdenacao($objGrupoAcompanhamentoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, false, 'Acompanhamentos');

  $objAtividadeRN = new AtividadeRN();
  $objAtividadeRN->processarPainel($objPainelControleDTO);

  $numRegistrosProcessos = 0;

  if ($objPainelControleDTO->getStrSinPainelProcessos()=='S') {

    $numRegistrosProcessos = $objPainelControleDTO->getNumProcessosGerados() + $objPainelControleDTO->getNumProcessosRecebidos();

    $strTabResumo .= '<table id="tblResumo" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" summary="Tabela de Resumo do Controles de Processos.">'."\n";
    $strTabResumo .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Processos abertos', null, '', 'Resumo').'</caption>';
    $strTabResumo .= '<tr>';
    $strTabResumo .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'Resumo', '', false).'</th>'."\n";
    $strTabResumo .= '<th class="infraTh" width="18%">Total</th>'."\n";
    $strTabResumo .= '<th class="infraTh" width="18%">Recebidos</th>'."\n";
    $strTabResumo .= '<th class="infraTh" width="16%">Gerados</th>'."\n";
    $strTabResumo .= '<th class="infraTh" width="16%">Sem Acompanhamento</th>'."\n";
    $strTabResumo .= '<th class="infraTh" width="16%">Não Visualizados</th>'."\n";
    $strTabResumo .= '<th class="infraTh" width="16%"><img '.PaginaSEI::montarTitleTooltip('Processos onde um documento foi incluído ou assinado após a última visualização na unidade').' src="'.Icone::EXCLAMACAO.'" class="imagemStatus" /></th>'."\n";
    $strTabResumo .= '</tr>';

    $strTabResumo .= '<tr class="infraTrClara">'."\n";
    $strTabResumo .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck(1, 'R1', 'Resumo', 'N', 'Resumo').'</td>'."\n";
    $strTabResumo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, 0, 0, 0, 0, false, $numRegistrosProcessos).'</td>'."\n";
    $strTabResumo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 0, 0, 0, 0, 0, 0, 0, 0, 0, false, $objPainelControleDTO->getNumProcessosRecebidos()).'</td>'."\n";
    $strTabResumo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 1, 0, 0, 0, 0, 0, 0, 0, 0, false, $objPainelControleDTO->getNumProcessosGerados()).'</td>'."\n";
    $strTabResumo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 1, 0, 0, 0, 0, 0, 0, false, $objPainelControleDTO->getNumProcessosSemAcompanhamento()).'</td>'."\n";
    $strTabResumo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 1, 0, 0, 0, 0, 0, 0, 0, true, $objPainelControleDTO->getNumProcessosNaoVisualizados()).'</td>'."\n";
    $strTabResumo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 1, 0, 0, 0, 0, 0, true, $objPainelControleDTO->getNumProcessosAlterados()).'</td>'."\n";
    $strTabResumo .= '</tr>'."\n";
    $strTabResumo .= '</table>'."\n\n";
  }

  $numRegistrosControlePrazo = 0;

  if ($objPainelControleDTO->getStrSinPainelControlesPrazos()=='S') {

    $strTabControlePrazo .= '<table id="tblControlesPrazos" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" summary="Tabela de Processos associados com Controles de Prazo.">'."\n";

    $strTabControlePrazo .= '<caption class="infraCaption">Controles de Prazos:</caption>'."\n";

    $strTabControlePrazo .= '<tr>'."\n";
    $strTabControlePrazo .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'ControlePrazo', '', false).'</th>'."\n";
    $strTabControlePrazo .= '<th class="infraTh" width="68%">Tipo</th>'."\n";
    $strTabControlePrazo .= '<th class="infraTh" width="16%">Processos</th>'."\n";
    $strTabControlePrazo .= '<th class="infraTh" width="16%"><img '.PaginaSEI::montarTitleTooltip('Processos com controle de prazo onde um documento foi incluído ou assinado').' src="'.Icone::EXCLAMACAO.'" class="imagemStatus" /></th>'."\n";
    $strTabControlePrazo .= '</tr>'."\n";

    if ($objPainelControleDTO->getNumControlePrazoNormal()) {
      $numRegistrosControlePrazo++;
      $strTabControlePrazo .= '<tr class="infraTrClara">'."\n";
      $strTabControlePrazo .= '<td align="left" valign="center" style="padding-left:2em;"><span style="vertical-align:top;"><img src="'.Icone::CONTROLE_PRAZO1.'" class="InfraImg" style="padding-right:1em" />Em andamento</span></td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, AtividadeRN::$TCP_NORMAL, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoNormal()).'</td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, AtividadeRN::$TCP_NORMAL, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoNormalAlterados()).'</td>'."\n";
      $strTabControlePrazo .= '</tr>'."\n";
    }

    if ($objPainelControleDTO->getNumControlePrazoAtrasado()) {
      $numRegistrosControlePrazo++;
      $strTabControlePrazo .= '<tr class="infraTrClara">'."\n";
      $strTabControlePrazo .= '<td align="left" valign="center" style="padding-left:2em;"><span style="vertical-align:top;"><img src="'.Icone::CONTROLE_PRAZO3.'" class="InfraImg" style="padding-right:1em" />Atrasados</span></td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, AtividadeRN::$TCP_ATRASADO, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoAtrasado()).'</td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, AtividadeRN::$TCP_ATRASADO, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoAtrasadoAlterados()).'</td>'."\n";
      $strTabControlePrazo .= '</tr>'."\n";
    }

    if ($objPainelControleDTO->getNumControlePrazoConcluido()) {
      $numRegistrosControlePrazo++;
      $strTabControlePrazo .= '<tr class="infraTrClara">'."\n";
      $strTabControlePrazo .= '<td align="left" valign="center" style="padding-left:2em;"><span style="vertical-align:top;"><img src="'.Icone::CONTROLE_PRAZO2.'" class="InfraImg" style="padding-right:1em" />Concluídos</span></td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, AtividadeRN::$TCP_CONCLUIDO, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoConcluido()).'</td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, AtividadeRN::$TCP_CONCLUIDO, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoConcluidoAlterados()).'</td>'."\n";
      $strTabControlePrazo .= '</tr>'."\n";
    }

    $strTabControlePrazo .= '</table>'."\n\n";
  }

  $numRegistrosRetornoProgramado = 0;

  if ($objPainelControleDTO->getStrSinPainelRetornosProgramados()=='S') {

    $strTabRetornoProgramado .= '<table id="tblRetornosProgramados" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" summary="Tabela de Processos associados com Retorno Programado.">'."\n";

    $strTabRetornoProgramado .= '<caption class="infraCaption">Retornos Programados:</caption>'."\n";

    $strTabRetornoProgramado .= '<tr>'."\n";
    $strTabRetornoProgramado .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'RetornoProgramado', '', false).'</th>'."\n";
    $strTabRetornoProgramado .= '<th class="infraTh" width="68%">Tipo</th>'."\n";
    $strTabRetornoProgramado .= '<th class="infraTh" width="16%">Processos</th>'."\n";
    $strTabRetornoProgramado .= '<th class="infraTh" width="16%"><img '.PaginaSEI::montarTitleTooltip('Processos com retorno programado onde um documento foi incluído ou assinado').' src="'.Icone::EXCLAMACAO.'" class="imagemStatus" /></th>'."\n";
    $strTabRetornoProgramado .= '</tr>'."\n";

    if ($objPainelControleDTO->getNumRetornoProgramadoAguardandoNormal() || $objPainelControleDTO->getNumRetornoProgramadoAguardandoAtrasados()) {
      $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
      $strTabRetornoProgramado .= '<td colspan="3" align="left" style="padding: .5em 0 .5em 2em;font-style:italic;">Aguardando retorno de outras unidades</td>'."\n";
      $strTabRetornoProgramado .= '</tr>'."\n";

      if ($objPainelControleDTO->getNumRetornoProgramadoAguardandoNormal()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span style="vertical-align:top;"><img src="'.Icone::RETORNO_AGUARDANDO1.'" class="InfraImg" style="padding-right:1em" />Em andamento</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_AGUARDANDO_NORMAL, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoNormal()).'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_AGUARDANDO_NORMAL, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoNormalAlterados()).'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

      if ($objPainelControleDTO->getNumRetornoProgramadoAguardandoAtrasados()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span style="vertical-align:top;"><img src="'.Icone::RETORNO_AGUARDANDO3.'" class="InfraImg" style="padding-right:1em" />Atrasado</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_AGUARDANDO_ATRASADO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoAtrasados()).'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_AGUARDANDO_ATRASADO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoAtrasadosAlterados()).'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

      if ($objPainelControleDTO->getNumRetornoProgramadoAguardandoConcluidos()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span style="vertical-align:top;"><img src="'.Icone::RETORNO_AGUARDANDO2.'" class="InfraImg" style="padding-right:1em" />Retornado</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_AGUARDANDO_CONCLUIDO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoConcluidos()).'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_AGUARDANDO_CONCLUIDO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoConcluidosAlterados()).'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

    }

    if ($objPainelControleDTO->getNumRetornoProgramadoDevolverNormal() || $objPainelControleDTO->getNumRetornoProgramadoDevolverAtrasados()) {
      $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
      $strTabRetornoProgramado .= '<td colspan="3" align="left" style="padding: .5em 0 .5em 2em;font-style:italic;">Processos para devolver</td>'."\n";
      $strTabRetornoProgramado .= '</tr>'."\n";

      if ($objPainelControleDTO->getNumRetornoProgramadoDevolverNormal()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span style="vertical-align:top;"><img src="'.Icone::RETORNO_PROGRAMADO1.'" class="InfraImg" style="padding-right:1em" />Em andamento</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_DEVOLVER_NORMAL, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverNormal()).'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_DEVOLVER_NORMAL, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverNormalAlterados()).'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

      if ($objPainelControleDTO->getNumRetornoProgramadoDevolverAtrasados()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span style="vertical-align:top;"><img src="'.Icone::RETORNO_PROGRAMADO3.'" class="InfraImg" style="padding-right:1em" />Atrasado</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_DEVOLVER_ATRASADO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverAtrasados()).'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_DEVOLVER_ATRASADO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverAtrasadosAlterados()).'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

      if ($objPainelControleDTO->getNumRetornoProgramadoDevolverConcluidos()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span style="vertical-align:top;"><img src="'.Icone::RETORNO_PROGRAMADO2.'" class="InfraImg" style="padding-right:1em" />Devolvido</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_DEVOLVER_CONCLUIDO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverConcluidos()).'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_DEVOLVER_CONCLUIDO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverConcluidosAlterados()).'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

    }

    $strTabRetornoProgramado .= '</table>'."\n\n";
  }

  $numRegistrosBlocos = 0;

  if ($objPainelControleDTO->getStrSinPainelBlocos()=='S') {

    $strTabBlocos .= '<table id="tblBlocos" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" summary="Tabela de Blocos.">'."\n";
    $strTabBlocos .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Blocos de Assinatura abertos', null, '', 'Blocos').'</caption>'."\n";
    $strTabBlocos .= '<tr>'."\n";
    $strTabBlocos .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'Blocos', '', false).'</th>'."\n";
    $strTabBlocos .= '<th class="infraTh" width="52%">Situação</th>'."\n";
    $strTabBlocos .= '<th class="infraTh" width="16%">Blocos</th>'."\n";
    $strTabBlocos .= '<th class="infraTh" width="16%">Documentos</th>'."\n";
    $strTabBlocos .= '<th class="infraTh" width="16%">Sem Assinatura</th>'."\n";
    $strTabBlocos .= '</tr>'."\n";

    if ($objPainelControleDTO->getNumBlocosParaRetornar()) {
      $numRegistrosBlocos++;
      $strTabBlocos .= '<tr class="infraTrClara">'."\n";
      $strTabBlocos .= '<td align="left" style="padding-left:2em;">Recebidos</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(true, BlocoRN::$TE_RECEBIDO, 0, $objPainelControleDTO->getNumBlocosParaRetornar()).'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_RECEBIDO, 0, $objPainelControleDTO->getNumBlocosParaRetornarDocumentos()).'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_RECEBIDO, 1, ($objPainelControleDTO->getNumBlocosParaRetornarDocumentos() - $objPainelControleDTO->getNumBlocosParaRetornarAssinados())).'</td>'."\n";
      $strTabBlocos .= '</tr>'."\n";
    }

    if ($objPainelControleDTO->getNumBlocosGerados()) {
      $numRegistrosBlocos++;
      $strTabBlocos .= '<tr class="infraTrClara">'."\n";
      $strTabBlocos .= '<td align="left" style="padding-left:2em;">Gerados</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(true, BlocoRN::$TE_ABERTO, 0, $objPainelControleDTO->getNumBlocosGerados()).'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_ABERTO, 0, $objPainelControleDTO->getNumBlocosGeradosDocumentos()).'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_ABERTO, 1, ($objPainelControleDTO->getNumBlocosGeradosDocumentos() - $objPainelControleDTO->getNumBlocosGeradosAssinados())).'</td>'."\n";
      $strTabBlocos .= '</tr>'."\n";
    }

    if ($objPainelControleDTO->getNumBlocosDisponibilizados()) {
      $numRegistrosBlocos++;
      $strTabBlocos .= '<tr class="infraTrClara">'."\n";
      $strTabBlocos .= '<td align="left" style="padding-left:2em;">Disponibilizados</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(true, BlocoRN::$TE_DISPONIBILIZADO, 0, $objPainelControleDTO->getNumBlocosDisponibilizados()).'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_DISPONIBILIZADO, 0, $objPainelControleDTO->getNumBlocosDisponibilizadosDocumentos()).'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_DISPONIBILIZADO, 1, ($objPainelControleDTO->getNumBlocosDisponibilizadosDocumentos() - $objPainelControleDTO->getNumBlocosDisponibilizadosAssinados())).'</td>'."\n";
      $strTabBlocos .= '</tr>'."\n";
    }

    if ($objPainelControleDTO->getNumBlocosRetornados()) {
      $numRegistrosBlocos++;
      $strTabBlocos .= '<tr class="infraTrClara">'."\n";
      $strTabBlocos .= '<td align="left" style="padding-left:2em;">Retornados</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(true, BlocoRN::$TE_RETORNADO, 0, $objPainelControleDTO->getNumBlocosRetornados()).'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_RETORNADO, 0, $objPainelControleDTO->getNumBlocosRetornadosDocumentos()).'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_RETORNADO, 1, ($objPainelControleDTO->getNumBlocosRetornadosDocumentos() - $objPainelControleDTO->getNumBlocosRetornadosAssinados())).'</td>'."\n";
      $strTabBlocos .= '</tr>'."\n";
    }

    $strTabBlocos .= '</table>';
  }
  
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  $numRegistrosGruposBlocos = 0;
  $strOpcaoGruposBlocos = '';

  if ($objPainelControleDTO->getStrSinPainelGruposBlocos()=='S') {
    $arrObjGruposBlocoDTO = $objPainelControleDTO->getArrObjGrupoBlocoDTO();

    $numRegistrosGruposBlocos = InfraArray::contar($arrObjGruposBlocoDTO);

    //if ($objPainelControleDTO->getStrSinPossuiSelecaoGruposBlocos() == 'S') {
      $strOpcaoGruposBlocos = '<button id="btnVerGruposBlocos" onclick="verSelecao(\'hdnSinVerGruposBlocos\',\''.($objPainelControleDTO->getStrSinVerSelecaoGruposBlocos() == 'S' ? 'N' : 'S').'\',\'ancGruposBlocos\');" class="infraButton" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.($objPainelControleDTO->getStrSinVerSelecaoGruposBlocos() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção').'</button>'."\n";
    //}

    if ($numRegistrosGruposBlocos) {

      $strTabGruposBlocos .= '<a name="ancGruposBlocos"></a>';

      $strTabGruposBlocos .= '<table id="tblGruposBlocos" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" summary="Tabela de Quantidade de Processos em Acompanhamento Especial por Grupo.">'."\n";

      $strTabGruposBlocos .= '<caption class="infraCaption">'."\n";
      $strTabGruposBlocos .= '<div class="divCaptionLeft">Grupos de blocos de assinatura abertos:</div>'."\n";
      $strTabGruposBlocos .= '<div class="divCaptionRight">'."\n";
      $strTabGruposBlocos .= $strOpcaoGruposBlocos;
      $strTabGruposBlocos .= '</div>'."\n";
      $strTabGruposBlocos .= '</caption>'."\n";

      $strTabGruposBlocos .= '<tr>'."\n";
      $strTabGruposBlocos .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'GruposBlocos', '', false).'</th>'."\n";
      $strTabGruposBlocos .= '<th class="infraTh" width="52%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoBlocoDTO, 'Grupo', 'Nome', $arrObjGruposBlocoDTO, true, 'GruposBlocos', 'ordenarGruposBlocos').'</th>'."\n";
      $strTabGruposBlocos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoBlocoDTO, 'Blocos', 'Blocos', $arrObjGruposBlocoDTO, true, 'GruposBlocos', 'ordenarGruposBlocos').'</th>'."\n";
      $strTabGruposBlocos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoBlocoDTO, 'Documentos', 'Documentos', $arrObjGruposBlocoDTO, true, 'GruposBlocos', 'ordenarGruposBlocos').'</th>'."\n";
      $strTabGruposBlocos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoBlocoDTO, 'Sem Assinatura', 'SemAssinatura', $arrObjGruposBlocoDTO, true, 'GruposBlocos', 'ordenarGruposBlocos').'</th>'."\n";
      $strTabGruposBlocos .= '</tr>'."\n";

      $strCssTr = '';
      for ($i = 0; $i < $numRegistrosGruposBlocos; $i++) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabGruposBlocos .= $strCssTr."\n";

        $strTabGruposBlocos .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck($arrObjGruposBlocoDTO[$i]->getNumIdGrupoBloco(), 'GB'.$arrObjGruposBlocoDTO[$i]->getNumIdGrupoBloco(), 'Grupos de Blocos', 'N', 'GruposBlocos').'</td>'."\n";
        $strTabGruposBlocos .= '<td align="left" style="padding-left:2em;">'.PaginaSEI::tratarHTML($arrObjGruposBlocoDTO[$i]->getStrNome()).'</a></td>'."\n";
        $strTabGruposBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkGruposBlocosPainel(true, $arrObjGruposBlocoDTO[$i]->getNumIdGrupoBloco(), false, $arrObjGruposBlocoDTO[$i]->getNumBlocos()).'</td>'."\n";
        $strTabGruposBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkGruposBlocosPainel(false, $arrObjGruposBlocoDTO[$i]->getNumIdGrupoBloco(), false,  $arrObjGruposBlocoDTO[$i]->getNumDocumentos()).'</td>'."\n";
        $strTabGruposBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkGruposBlocosPainel(false, $arrObjGruposBlocoDTO[$i]->getNumIdGrupoBloco(), true, $arrObjGruposBlocoDTO[$i]->getNumSemAssinatura()).'</td>'."\n";
        $strTabGruposBlocos .= '</tr>'."\n";
      }
      $strTabGruposBlocos .= '</table>'."\n\n";
    }
  }

  $numRegistrosTiposProcessos = 0;
  $strOpcaoTiposProcessos = '';

  if ($objPainelControleDTO->getStrSinPainelTiposProcessos()=='S') {

    $arrObjTipoProcedimentoDTO = $objPainelControleDTO->getArrObjTipoProcedimentoDTO();

    $numRegistrosTiposProcessos = InfraArray::contar($arrObjTipoProcedimentoDTO);

    //if ($objPainelControleDTO->getStrSinPossuiSelecaoTiposProcessos() == 'S') {
    $strOpcaoTiposProcessos = '<button id="btnVerTiposProcessos" onclick="verSelecao(\'hdnSinVerTiposProcessos\',\''.($objPainelControleDTO->getStrSinVerSelecaoTiposProcessos() == 'S' ? 'N' : 'S').'\',\'ancTiposProcessos\');" class="infraButton" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.($objPainelControleDTO->getStrSinVerSelecaoTiposProcessos() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção').'</button>'."\n";
    //}

    if ($numRegistrosTiposProcessos) {

      $strTabTiposProcessos .= '<a name="ancTiposProcessos"></a>';

      $strTabTiposProcessos .= '<table id="tblTiposProcessos" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" summary="Tabela de Quantidade de Processos Abertos por Tipo.">'."\n";

      $strTabTiposProcessos .= '<caption class="infraCaption">'."\n";
      $strTabTiposProcessos .= '<div class="divCaptionLeft">Processos abertos por tipo:</div>'."\n";
      $strTabTiposProcessos .= '<div class="divCaptionRight">'."\n";
      $strTabTiposProcessos .= $strOpcaoTiposProcessos;
      $strTabTiposProcessos .= '</div>'."\n";
      $strTabTiposProcessos .= '</caption>'."\n";

      $strTabTiposProcessos .= '<tr>'."\n";
      $strTabTiposProcessos .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'TiposProcessos', '', false).'</th>'."\n";
      $strTabTiposProcessos .= '<th class="infraTh" width="68%">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO, 'Tipo', 'Nome', $arrObjTipoProcedimentoDTO, true, 'TiposProcessos', 'ordenarTiposProcessos').'</th>'."\n";
      $strTabTiposProcessos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO, 'Processos', 'Processos', $arrObjTipoProcedimentoDTO, true, 'TiposProcessos', 'ordenarTiposProcessos').'</th>'."\n";
      $strTabTiposProcessos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO, '<img '.PaginaSEI::montarTitleTooltip('Processos onde um documento foi incluído ou assinado').' src="'.Icone::EXCLAMACAO.'" class="infraImg" />', 'Alterados', $arrObjTipoProcedimentoDTO, true, 'TiposProcessos', 'ordenarTiposProcessos').'</th>'."\n";
      $strTabTiposProcessos .= '</tr>'."\n";

      $strCssTr = '';
      for ($i = 0; $i < $numRegistrosTiposProcessos; $i++) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabTiposProcessos .= $strCssTr."\n";

        $strTabTiposProcessos .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck($arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(), 'TP'.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(), 'Tipo do Processo', 'N', 'TiposProcessos').'</td>'."\n";
        $strTabTiposProcessos .= '<td align="left" style="padding-left:2em;">'.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabTiposProcessos .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 0, 0, 0, $arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(), 0, 0, false, $arrObjTipoProcedimentoDTO[$i]->getNumProcessos()).'</td>'."\n";
        $strTabTiposProcessos .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 1, 0, 0, $arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(), 0, 0, true, $arrObjTipoProcedimentoDTO[$i]->getNumAlterados()).'</td>'."\n";
        $strTabTiposProcessos .= '</tr>'."\n";
      }
      $strTabTiposProcessos .= '</table>'."\n\n";
    }
  }

  $numRegistrosMarcadores = 0;
  $strOpcaoMarcadores = '';

  if ($objPainelControleDTO->getStrSinPainelMarcadores()=='S') {

    $arrObjMarcadorDTO = $objPainelControleDTO->getArrObjMarcadorDTO();

    $numRegistrosMarcadores = InfraArray::contar($arrObjMarcadorDTO);

    //if ($objPainelControleDTO->getStrSinPossuiSelecaoMarcadores() == 'S') {
      $strOpcaoMarcadores = '<button id="btnVerMarcadores" onclick="verSelecao(\'hdnSinVerMarcadores\',\''.($objPainelControleDTO->getStrSinVerSelecaoMarcadores() == 'S' ? 'N' : 'S').'\',\'ancMarcadores\');" class="infraButton" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.($objPainelControleDTO->getStrSinVerSelecaoMarcadores() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção').'</button>'."\n";
    //}

    if ($numRegistrosMarcadores) {

      $strTabMarcadores .= '<a name="ancMarcadores"></a>';

      $strTabMarcadores .= '<table id="tblMarcadores" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" summary="Tabela de Quantidade de Processos por Marcador.">'."\n";
      $strTabMarcadores .= '<caption class="infraCaption">'."\n";
      $strTabMarcadores .= '<div class="divCaptionLeft">Marcadores em processos:</div>'."\n";
      $strTabMarcadores .= '<div class="divCaptionRight">'."\n";
      $strTabMarcadores .= $strOpcaoMarcadores;
      $strTabMarcadores .= '</div>'."\n";
      $strTabMarcadores .= '</caption>'."\n";

      $strTabMarcadores .= '<tr>'."\n";
      $strTabMarcadores .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'Marcadores', '', false).'</th>'."\n";
      $strTabMarcadores .= '<th class="infraTh" width="68%">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO, 'Marcador', 'Nome', $arrObjMarcadorDTO, true, 'Marcadores', 'ordenarMarcadores').'</th>'."\n";
      $strTabMarcadores .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO, 'Processos', 'Processos', $arrObjMarcadorDTO, true, 'Marcadores', 'ordenarMarcadores').'</th>'."\n";
      $strTabMarcadores .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO, '<img '.PaginaSEI::montarTitleTooltip('Processos onde um documento foi incluído ou assinado').' src="'.Icone::EXCLAMACAO.'" class="infraImg" />', 'Alterados', $arrObjMarcadorDTO, true, 'Marcadores', 'ordenarMarcadores').'</th>'."\n";

      $strTabMarcadores .= '</tr>'."\n";

      $strCssTr = '';
      for ($i = 0; $i < $numRegistrosMarcadores; $i++) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabMarcadores .= $strCssTr."\n";

        $strTabMarcadores .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck($arrObjMarcadorDTO[$i]->getNumIdMarcador(), 'M'.$arrObjMarcadorDTO[$i]->getNumIdMarcador(), 'Marcador', 'N', 'Marcadores').'</td>'."\n";

        $strTabMarcadores .= '<td align="left" valign="center" style="padding-left:2em;"><span style="vertical-align:top;">';
        if (!($arrObjMarcadorDTO[$i]->getNumIdMarcador()==-1 && $numRegistrosMarcadores==1)){
          $strTabMarcadores .= '<img src="'.$arrObjMarcadorDTO[$i]->getStrArquivoIcone().'" class="InfraImg" style="padding-right:1em" />';
        }
        $strTabMarcadores .= PaginaSEI::tratarHTML($arrObjMarcadorDTO[$i]->getStrNome()).'</span></td>'."\n";

        $strTabMarcadores .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 0, 0, 0, 0, $arrObjMarcadorDTO[$i]->getNumIdMarcador(), 0, false, $arrObjMarcadorDTO[$i]->getNumProcessos()).'</td>'."\n";
        $strTabMarcadores .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 1, 0, 0, 0, $arrObjMarcadorDTO[$i]->getNumIdMarcador(), 0, true, $arrObjMarcadorDTO[$i]->getNumAlterados()).'</td>'."\n";
        $strTabMarcadores .= '</tr>'."\n";
      }
      $strTabMarcadores .= '</table>'."\n\n";;
    }
  }

  $numRegistrosUsuariosAtribuicao = 0;
  $strOpcaoAtribuicoes = '';

  if ($objPainelControleDTO->getStrSinPainelAtribuicoes()=='S') {

    $arrObjUsuarioDTO = $objPainelControleDTO->getArrObjUsuarioDTOAtribuicao();

    $numRegistrosUsuariosAtribuicao = InfraArray::contar($arrObjUsuarioDTO);

    //if ($objPainelControleDTO->getStrSinPossuiSelecaoAtribuicoes() == 'S') {
      $strOpcaoAtribuicoes = '<button id="btnVerAtribuicoes" onclick="verSelecao(\'hdnSinVerAtribuicoes\',\''.($objPainelControleDTO->getStrSinVerSelecaoAtribuicoes() == 'S' ? 'N' : 'S').'\',\'ancAtribuicoes\');" class="infraButton" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.($objPainelControleDTO->getStrSinVerSelecaoAtribuicoes() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção').'</button>'."\n";
    //}

    if ($numRegistrosUsuariosAtribuicao) {

      $strTabAtribuicoes .= '<a name="ancAtribuicoes"></a>';

      $strTabAtribuicoes .= '<table id="tblAtribuicoes" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" summary="Tabela de Quantidade de Processos Atribuídos por Usuário.">'."\n";

      $strTabAtribuicoes .= '<caption class="infraCaption">'."\n";
      $strTabAtribuicoes .= '<div class="divCaptionLeft">Atribuições de processos:</div>'."\n";
      $strTabAtribuicoes .= '<div class="divCaptionRight">'."\n";
      $strTabAtribuicoes .= $strOpcaoAtribuicoes;
      $strTabAtribuicoes .= '</div>'."\n";
      $strTabAtribuicoes .= '</caption>'."\n";

      $strTabAtribuicoes .= '<tr>'."\n";
      $strTabAtribuicoes .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'Atribuicoes', '', false).'</th>'."\n";
      $strTabAtribuicoes .= '<th class="infraTh" width="68%">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'Usuário', 'Nome', $arrObjUsuarioDTO, true, 'Atribuicoes', 'ordenarAtribuicoes').'</th>'."\n";
      $strTabAtribuicoes .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'Processos', 'Processos', $arrObjUsuarioDTO, true, 'Atribuicoes', 'ordenarAtribuicoes').'</th>'."\n";
      $strTabAtribuicoes .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, '<img '.PaginaSEI::montarTitleTooltip('Processos onde um documento foi incluído ou assinado').' src="'.Icone::EXCLAMACAO.'" class="infraImg" />', 'Alterados', $arrObjUsuarioDTO, true, 'Atribuicoes', 'ordenarAtribuicoes').'</th>'."\n";
      $strTabAtribuicoes .= '</tr>'."\n";

      $strCssTr = '';
      for ($i = 0; $i < $numRegistrosUsuariosAtribuicao; $i++) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabAtribuicoes .= $strCssTr."\n";

        $strTabAtribuicoes .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck($arrObjUsuarioDTO[$i]->getNumIdUsuario(), 'U'.$arrObjUsuarioDTO[$i]->getNumIdUsuario(), 'Atribuição', 'N', 'Atribuicoes').'</td>'."\n";
        $strTabAtribuicoes .= '<td align="left" style="padding-left:2em;"><a alt="'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrNome()).'" title="'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrNome()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrSigla()).'</a></td>'."\n";
        $strTabAtribuicoes .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 0, 0, 0, 0, 0, $arrObjUsuarioDTO[$i]->getNumIdUsuario(), false, $arrObjUsuarioDTO[$i]->getNumProcessos()).'</td>'."\n";
        $strTabAtribuicoes .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 1, 0, 0, 0, 0, $arrObjUsuarioDTO[$i]->getNumIdUsuario(), true, $arrObjUsuarioDTO[$i]->getNumAlterados()).'</td>'."\n";
        $strTabAtribuicoes .= '</tr>'."\n";
      }
      $strTabAtribuicoes .= '</table>'."\n\n";
    }
  }

  $numRegistrosGruposAcompanhamento = 0;
  $strOpcaoAcompanhamentos = '';

  if ($objPainelControleDTO->getStrSinPainelAcompanhamentos()=='S') {
    $arrObjGrupoAcompanhamentoDTO = $objPainelControleDTO->getArrObjGrupoAcompanhamentoDTO();

    $numRegistrosGruposAcompanhamento = InfraArray::contar($arrObjGrupoAcompanhamentoDTO);

    //if ($objPainelControleDTO->getStrSinPossuiSelecaoAcompanhamentos() == 'S') {
      $strOpcaoAcompanhamentos = '<button id="btnVerAcompanhamentos" onclick="verSelecao(\'hdnSinVerAcompanhamentos\',\''.($objPainelControleDTO->getStrSinVerSelecaoAcompanhamentos() == 'S' ? 'N' : 'S').'\',\'ancAcompanhamentos\');" class="infraButton" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.($objPainelControleDTO->getStrSinVerSelecaoAcompanhamentos() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção').'</button>'."\n";
    //}

    if ($numRegistrosGruposAcompanhamento) {

      $strTabAcompanhamentos .= '<a name="ancAcompanhamentos"></a>';

      $strTabAcompanhamentos .= '<table id="tblAcompanhamentos" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" summary="Tabela de Quantidade de Processos em Acompanhamento Especial por Grupo.">'."\n";

      $strTabAcompanhamentos .= '<caption class="infraCaption">'."\n";
      $strTabAcompanhamentos .= '<div class="divCaptionLeft">Acompanhamentos Especiais em processos:</div>'."\n";
      $strTabAcompanhamentos .= '<div class="divCaptionRight">'."\n";
      $strTabAcompanhamentos .= $strOpcaoAcompanhamentos;
      $strTabAcompanhamentos .= '</div>'."\n";
      $strTabAcompanhamentos .= '</caption>'."\n";

      $strTabAcompanhamentos .= '<tr>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'Acompanhamentos', '', false).'</th>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" width="36%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO, 'Grupo', 'Nome', $arrObjGrupoAcompanhamentoDTO, true, 'Acompanhamentos', 'ordenarAcompanhamentos').'</th>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO, 'Processos', 'Processos', $arrObjGrupoAcompanhamentoDTO, true, 'Acompanhamentos', 'ordenarAcompanhamentos').'</th>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO, 'Abertos', 'Abertos', $arrObjGrupoAcompanhamentoDTO, true, 'Acompanhamentos', 'ordenarAcompanhamentos').'</th>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO, 'Fechados', 'Fechados', $arrObjGrupoAcompanhamentoDTO, true, 'Acompanhamentos', 'ordenarAcompanhamentos').'</th>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO, '<img '.PaginaSEI::montarTitleTooltip('Processos onde um documento foi incluído ou assinado').' src="'.Icone::EXCLAMACAO.'" class="infraImg" />', 'Alterados', $arrObjGrupoAcompanhamentoDTO, true, 'Acompanhamentos', 'ordenarAcompanhamentos').'</th>'."\n";
      $strTabAcompanhamentos .= '</tr>'."\n";

      $strCssTr = '';
      for ($i = 0; $i < $numRegistrosGruposAcompanhamento; $i++) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabAcompanhamentos .= $strCssTr."\n";

        $strTabAcompanhamentos .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 'A'.$arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 'Acompanhamento Especial', 'N', 'Acompanhamentos').'</td>'."\n";
        $strTabAcompanhamentos .= '<td align="left" style="padding-left:2em;">'.PaginaSEI::tratarHTML($arrObjGrupoAcompanhamentoDTO[$i]->getStrNome()).'</a></td>'."\n";
        $strTabAcompanhamentos .= '<td align="center">'.ProcedimentoINT::montarLinkAcompanhamentosPainel($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 0, 0, 0, false, $arrObjGrupoAcompanhamentoDTO[$i]->getNumProcessos()).'</td>'."\n";
        $strTabAcompanhamentos .= '<td align="center">'.ProcedimentoINT::montarLinkAcompanhamentosPainel($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 1, 0, 0, false, $arrObjGrupoAcompanhamentoDTO[$i]->getNumAbertos()).'</td>'."\n";
        $strTabAcompanhamentos .= '<td align="center">'.ProcedimentoINT::montarLinkAcompanhamentosPainel($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 0, 1, 0, false, $arrObjGrupoAcompanhamentoDTO[$i]->getNumFechados()).'</td>'."\n";
        $strTabAcompanhamentos .= '<td align="center">'.ProcedimentoINT::montarLinkAcompanhamentosPainel($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 0, 0, 1, true, $arrObjGrupoAcompanhamentoDTO[$i]->getNumAlterados()).'</td>'."\n";
        $strTabAcompanhamentos .= '</tr>'."\n";
      }
      $strTabAcompanhamentos .= '</table>'."\n\n";
    }
  }

  //$arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

  //$numSeg = InfraUtil::verificarTempoProcessamento($numSeg);
  //InfraDebug::getInstance()->gravar($numSeg.' s');

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>

#divInfraBarraComandosSuperior {width:98%}

#tblControlesPrazos img {cursor:default;}
#tblRetornosProgramados img {cursor:default;}
#tblMarcadores img {cursor:default;}

table.tabelaPainel {
  border:1px solid c0c0c0;
  width:99%;
}


table.tabelaPainel td{
  border:0;
}

table.tabelaPainel td span{
  font-size:.875rem;
}

table.tabelaPainel span img{
  vertical-align:middle;
}

table.tabelaPainel caption{
  text-align:left;
  font-size:1.1rem;
}

div.divCaptionLeft{
  display:inline;
  float:left;
  margin-top:14px;
}

div.divCaptionRight{
  display:inline;
  float:right;
}

div.divCaptionRight button{
  font-size:.8rem;
}

div.divMensagem {
  display:inline-table;
  width:99%;
  margin-left:0;
  border-left:2px solid #bfbfbf;
  border-bottom:1px solid #bfbfbf;
  padding:4px 0;
  margin:.6em 0;
}

div.divMensagem label{
  float:left;
  padding-left:1em;
}

div.divMensagem button{
  float:right;
}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

  function inicializar(){
    <?=$strJsNovidades?>
    infraOcultarMenuSistemaEsquema();
    infraEfeitoTabelas();
  }

  function verSelecao(campo,valor,ancora){
    document.getElementById(campo).value = valor;
    processar(ancora);
  }

  function ordenarGruposBlocos(){
    processar('ancGruposBlocos');
  }

  function ordenarTiposProcessos(){
    processar('ancTiposProcessos');
  }

  function ordenarMarcadores(){
    processar('ancMarcadores');
  }

  function ordenarAtribuicoes(){
    processar('ancAtribuicoes');
  }

  function ordenarAcompanhamentos(){
    processar('ancAcompanhamentos');
  }

  function processar(ancora){
    document.getElementById('frmPainelControle').action += '#' + ancora;
    document.getElementById('frmPainelControle').submit();
  }


//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPainelControle" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

  if ($objPainelControleDTO->getStrSinPainelProcessos()=='S') {
    echo '<br />';
    if ($numRegistrosProcessos==0){
      echo '<div class="divMensagem"><label class="infraLabelOpcional">Nenhum processo aberto na unidade.</label></div>';
    }else {
      PaginaSEI::getInstance()->montarAreaTabela($strTabResumo, $numRegistrosProcessos, false, '', null, 'Resumo');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelControlesPrazos()=='S') {
    echo '<br />';
    if ($numRegistrosControlePrazo==0){
      echo '<div class="divMensagem"><label class="infraLabelOpcional">Nenhum controle de prazo na unidade.</label></div>';
    }else {
      PaginaSEI::getInstance()->montarAreaTabela($strTabControlePrazo, $numRegistrosControlePrazo, false, '', null, 'ControlePrazo');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelRetornosProgramados()=='S') {
    echo '<br />';
    if ($numRegistrosRetornoProgramado==0){
      echo '<div class="divMensagem"><label class="infraLabelOpcional">Nenhum retorno programado na unidade.</label></div>';
    }else {
      PaginaSEI::getInstance()->montarAreaTabela($strTabRetornoProgramado, $numRegistrosRetornoProgramado, false, '', null, 'RetornoProgramado');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelBlocos()=='S') {
    echo '<br />';
    if ($numRegistrosBlocos==0){
      echo '<div class="divMensagem"><label class="infraLabelOpcional">Nenhum bloco de assinatura aberto na unidade.</label></div>';
    }else {
      PaginaSEI::getInstance()->montarAreaTabela($strTabBlocos, $numRegistrosBlocos, false, '', null, 'Blocos');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelGruposBlocos()=='S') {
    echo '<br />';
    if ($numRegistrosGruposBlocos==0) {
      echo '<div class="divMensagem"><label class="infraLabelOpcional">Nenhum grupo de bloco de assinatura aberto '.($objPainelControleDTO->getStrSinVerSelecaoGruposBlocos()=='S'?($objPainelControleDTO->getStrSinPossuiSelecaoGruposBlocos()=='S'?'com blocos ':'').'na minha seleção':'com blocos na unidade').'.</label>'.$strOpcaoGruposBlocos.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabGruposBlocos, $numRegistrosGruposBlocos, false, '', null, 'GruposBlocos');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelTiposProcessos()=='S') {
    echo '<br />';
    if ($numRegistrosTiposProcessos==0) {
      echo '<div class="divMensagem"><label class="infraLabelOpcional">Nenhum tipo de processo '.($objPainelControleDTO->getStrSinVerSelecaoTiposProcessos()=='S'?($objPainelControleDTO->getStrSinPossuiSelecaoTiposProcessos()=='S'?'com processos ':'').'na minha seleção':'com processos na unidade').'.</label>'.$strOpcaoTiposProcessos.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabTiposProcessos, $numRegistrosTiposProcessos, false, '', null, 'TiposProcessos');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelMarcadores()=='S') {
    echo '<br />';
    if ($numRegistrosMarcadores==0) {
      echo '<div class="divMensagem"><label class="infraLabelOpcional">Nenhum marcador '.($objPainelControleDTO->getStrSinVerSelecaoMarcadores()=='S'?($objPainelControleDTO->getStrSinPossuiSelecaoMarcadores()=='S'?'com processos ':'').'na minha seleção':'com processos na unidade').'.</label>'.$strOpcaoMarcadores.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabMarcadores, $numRegistrosMarcadores, false, '', null, 'Marcadores');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelAtribuicoes()=='S') {
    echo '<br />';
    if ($numRegistrosUsuariosAtribuicao==0) {
      echo '<div class="divMensagem"><label class="infraLabelOpcional">Nenhum usuário '.($objPainelControleDTO->getStrSinVerSelecaoAtribuicoes()=='S'?($objPainelControleDTO->getStrSinPossuiSelecaoAtribuicoes()=='S'?'com processos atribuídos ':'').'na minha seleção':'com processos atribuídos na unidade').'.</label>'.$strOpcaoAtribuicoes.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabAtribuicoes, $numRegistrosUsuariosAtribuicao, false, '', null, 'Atribuicoes');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelAcompanhamentos()=='S') {
    echo '<br />';
    if ($numRegistrosGruposAcompanhamento==0) {
      echo '<div class="divMensagem"><label class="infraLabelOpcional">Nenhum grupo de acompanhamento especial '.($objPainelControleDTO->getStrSinVerSelecaoAcompanhamentos()=='S'?($objPainelControleDTO->getStrSinPossuiSelecaoAcompanhamentos()=='S'?'com processos ':'').'na minha seleção':'com processos na unidade').'.</label>'.$strOpcaoAcompanhamentos.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabAcompanhamentos, $numRegistrosGruposAcompanhamento, false, '', null, 'Acompanhamentos');
    }
  }

  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>

  <input type="hidden" id="hdnSinVerTiposProcessos" name="hdnSinVerTiposProcessos" value="<?=$objPainelControleDTO->getStrSinVerSelecaoTiposProcessos()?>" />
  <input type="hidden" id="hdnSinVerGruposBlocos" name="hdnSinVerGruposBlocos" value="<?=$objPainelControleDTO->getStrSinVerSelecaoGruposBlocos()?>" />
  <input type="hidden" id="hdnSinVerMarcadores" name="hdnSinVerMarcadores" value="<?=$objPainelControleDTO->getStrSinVerSelecaoMarcadores()?>" />
  <input type="hidden" id="hdnSinVerAtribuicoes" name="hdnSinVerAtribuicoes" value="<?=$objPainelControleDTO->getStrSinVerSelecaoAtribuicoes()?>" />
  <input type="hidden" id="hdnSinVerAcompanhamentos" name="hdnSinVerAcompanhamentos" value="<?=$objPainelControleDTO->getStrSinVerSelecaoAcompanhamentos()?>" />

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>