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

  AvisoINT::processar($strJsAviso, $strCssBanner, $strHtmlBanner);
  NovidadeINT::processar($strJsNovidades);

  $arrComandos[] = '<button type="submit" id="sbmAtualizar" name="sbmAtualizar" accesskey="A" value="Atualizar" class="infraButton"><span class="infraTeclaAtalho">A</span>tualizar</button>';

  if (SessaoSEI::getInstance()->verificarPermissao('painel_controle_configurar')){
    $arrComandos[] = '<button type="button" id="btnConfigurar" name="btnConfigurar" accesskey="C" value="Configurar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=painel_controle_configurar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>onfigurar</button>';
  }

  $numTabIndexGeral = PaginaSEI::getInstance()->getProxTabTabela();
  $strTabControlePrazo = '';
  $numTabIndexControlePrazo = null;
  $strTabRetornoProgramado = '';
  $numTabIndexRetornoProgramado = null;
  $strTabMarcadores = '';
  $numTabIndexMarcadores = null;
  $strTabAtribuicoes = '';
  $numTabIndexAtribuicoes = null;
  $strTabAcompanhamentos = '';
  $numTabIndexAcompanhamentos = null;
  $strTabBlocos = '';
  $numTabIndexBlocos = null;
  $strTabGruposBlocos = '';
  $numTabIndexGruposBlocos = null;
  $strTabTiposProcessos = '';
  $numTabIndexTiposProcessos = null;
  $strTabTiposPrioritarios = '';
  $numTabIndexTiposPrioritarios = null;


  $objPainelControleRN = new PainelControleRN();
  $objPainelControleDTO = $objPainelControleRN->carregarConfiguracoes();

  $bolSalvarConfiguracoes = false;

  if (isset($_POST['hdnSinVerTiposProcessos']) && $objPainelControleDTO->getStrSinVerSelecaoTiposProcessos()!=$_POST['hdnSinVerTiposProcessos']){
    $objPainelControleDTO->setStrSinVerSelecaoTiposProcessos($_POST['hdnSinVerTiposProcessos']);
    $bolSalvarConfiguracoes = true;
  }

  if (isset($_POST['hdnSinVerTiposPrioritarios']) && $objPainelControleDTO->getStrSinVerSelecaoTiposPrioritarios()!=$_POST['hdnSinVerTiposPrioritarios']){
    $objPainelControleDTO->setStrSinVerSelecaoTiposPrioritarios($_POST['hdnSinVerTiposPrioritarios']);
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

  $objTipoPrioridadeDTO = new TipoPrioridadeDTO();
  PaginaSEI::getInstance()->prepararOrdenacao($objTipoPrioridadeDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC, false, 'TiposPrioritarios');

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
  }

  $numRegistrosControlePrazo = 0;

  if ($objPainelControleDTO->getStrSinPainelControlesPrazos()=='S') {

    $numTabIndexControlePrazo = PaginaSEI::getInstance()->getProxTabTabela();

    $strTabControlePrazo .= '<table id="tblControlesPrazos" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" tabindex="'.$numTabIndexControlePrazo.'">'."\n";

    $strTabControlePrazo .= '<caption class="infraCaption">Controles de Prazos:</caption>'."\n";

    $strTabControlePrazo .= '<tr>'."\n";
    $strTabControlePrazo .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'ControlePrazo', '', PaginaSEI::getInstance()->getProxTabTabela()).'</th>'."\n";
    $strTabControlePrazo .= '<th class="infraTh" width="68%">Tipo</th>'."\n";
    $strTabControlePrazo .= '<th class="infraTh" width="16%">Processos</th>'."\n";
    $strTabControlePrazo .= '<th class="infraTh" width="16%"><img '.PaginaSEI::montarTitleTooltip('Processos com controle de prazo onde um documento foi incluído ou assinado','','Alterados',false,true).' src="'.Icone::EXCLAMACAO.'" class="imagemStatus" /></th>'."\n";
    $strTabControlePrazo .= '</tr>'."\n";


    if ($objPainelControleDTO->getNumControlePrazoNormal()) {
      $numRegistrosControlePrazo++;
      $strTabControlePrazo .= '<tr class="infraTrClara">'."\n";
      $strTabControlePrazo .= '<td align="left" valign="center" style="padding-left:2em;"><span class="celulaComImagem"><img src="'.Icone::CONTROLE_PRAZO1.'" class="InfraImg" />Em andamento</span></td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, AtividadeRN::$TCP_NORMAL, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoNormal(), 'Em andamento').'</td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, AtividadeRN::$TCP_NORMAL, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoNormalAlterados(), 'Em andamento e alterados').'</td>'."\n";
      $strTabControlePrazo .= '</tr>'."\n";
    }

    if ($objPainelControleDTO->getNumControlePrazoAtrasado()) {
      $numRegistrosControlePrazo++;
      $strTabControlePrazo .= '<tr class="infraTrClara">'."\n";
      $strTabControlePrazo .= '<td align="left" valign="center" style="padding-left:2em;"><span class="celulaComImagem"><img src="'.Icone::CONTROLE_PRAZO3.'" class="InfraImg" />Atrasados</span></td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, AtividadeRN::$TCP_ATRASADO, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoAtrasado(),'Atrasados').'</td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, AtividadeRN::$TCP_ATRASADO, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoAtrasadoAlterados(),'Atrasados e alterados').'</td>'."\n";
      $strTabControlePrazo .= '</tr>'."\n";
    }

    if ($objPainelControleDTO->getNumControlePrazoConcluido()) {
      $numRegistrosControlePrazo++;
      $strTabControlePrazo .= '<tr class="infraTrClara">'."\n";
      $strTabControlePrazo .= '<td align="left" valign="center" style="padding-left:2em;"><span class="celulaComImagem"><img src="'.Icone::CONTROLE_PRAZO2.'" class="InfraImg" />Concluídos</span></td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, AtividadeRN::$TCP_CONCLUIDO, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoConcluido(),'Concluídos').'</td>'."\n";
      $strTabControlePrazo .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, AtividadeRN::$TCP_CONCLUIDO, 0, 0, 0, 0, false,  $objPainelControleDTO->getNumControlePrazoConcluidoAlterados(),'Concluídos e alterados').'</td>'."\n";
      $strTabControlePrazo .= '</tr>'."\n";
    }

    $strTabControlePrazo .= '</table>'."\n\n";
  }

  $numRegistrosRetornoProgramado = 0;

  if ($objPainelControleDTO->getStrSinPainelRetornosProgramados()=='S') {

    $numTabIndexRetornoProgramado = PaginaSEI::getInstance()->getProxTabTabela();

    $strTabRetornoProgramado .= '<table id="tblRetornosProgramados" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" tabindex="'.$numTabIndexRetornoProgramado.'">'."\n";

    $strTabRetornoProgramado .= '<caption class="infraCaption">Retornos Programados:</caption>'."\n";

    $strTabRetornoProgramado .= '<tr>'."\n";
    $strTabRetornoProgramado .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'RetornoProgramado', '', PaginaSEI::getInstance()->getProxTabTabela()).'</th>'."\n";
    $strTabRetornoProgramado .= '<th class="infraTh" width="68%">Tipo</th>'."\n";
    $strTabRetornoProgramado .= '<th class="infraTh" width="16%">Processos</th>'."\n";
    $strTabRetornoProgramado .= '<th class="infraTh" width="16%"><img '.PaginaSEI::montarTitleTooltip('Processos com retorno programado onde um documento foi incluído ou assinado','','Alterados',false,true).' src="'.Icone::EXCLAMACAO.'" class="imagemStatus" /></th>'."\n";
    $strTabRetornoProgramado .= '</tr>'."\n";

    if ($objPainelControleDTO->getNumRetornoProgramadoAguardandoNormal() || $objPainelControleDTO->getNumRetornoProgramadoAguardandoAtrasados()) {
      $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
      $strTabRetornoProgramado .= '<td colspan="3" align="left" style="padding: .5em 0 .5em 2em;font-style:italic;">Aguardando retorno de outras unidades</td>'."\n";
      $strTabRetornoProgramado .= '</tr>'."\n";

      if ($objPainelControleDTO->getNumRetornoProgramadoAguardandoNormal()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span class="celulaComImagem"><img src="'.Icone::RETORNO_AGUARDANDO1.'" class="InfraImg" />Em andamento</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_AGUARDANDO_NORMAL, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoNormal(),'Aguardando retorno').'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_AGUARDANDO_NORMAL, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoNormalAlterados(),'Aguardando retorno e alterados').'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

      if ($objPainelControleDTO->getNumRetornoProgramadoAguardandoAtrasados()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span class="celulaComImagem"><img src="'.Icone::RETORNO_AGUARDANDO3.'" class="InfraImg" />Atrasado</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_AGUARDANDO_ATRASADO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoAtrasados(),'Aguardando retorno atrasados').'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_AGUARDANDO_ATRASADO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoAtrasadosAlterados(),'Aguardando retorno atrasados e alterados').'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

      if ($objPainelControleDTO->getNumRetornoProgramadoAguardandoConcluidos()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span class="celulaComImagem"><img src="'.Icone::RETORNO_AGUARDANDO2.'" class="InfraImg" />Retornado</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_AGUARDANDO_CONCLUIDO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoConcluidos(),'Retornados').'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_AGUARDANDO_CONCLUIDO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoAguardandoConcluidosAlterados(),'Retornados e alterados').'</td>'."\n";
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
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span class="celulaComImagem"><img src="'.Icone::RETORNO_PROGRAMADO1.'" class="InfraImg" />Em andamento</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_DEVOLVER_NORMAL, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverNormal(),'Para devolver').'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_DEVOLVER_NORMAL, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverNormalAlterados(),'Para devolver e alterados').'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

      if ($objPainelControleDTO->getNumRetornoProgramadoDevolverAtrasados()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span class="celulaComImagem"><img src="'.Icone::RETORNO_PROGRAMADO3.'" class="InfraImg" />Atrasado</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_DEVOLVER_ATRASADO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverAtrasados(),'Para devolver atrasados').'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_DEVOLVER_ATRASADO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverAtrasadosAlterados(),'Para devolver atrasados e alterados').'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

      if ($objPainelControleDTO->getNumRetornoProgramadoDevolverConcluidos()) {
        $numRegistrosRetornoProgramado++;
        $strTabRetornoProgramado .= '<tr class="infraTrClara">'."\n";
        $strTabRetornoProgramado .= '<td align="left" valign="center" style="padding-left:2em;"><span class="celulaComImagem"><img src="'.Icone::RETORNO_PROGRAMADO2.'" class="InfraImg" />Devolvido</span></td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 0, 0, AtividadeRN::$TRP_DEVOLVER_CONCLUIDO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverConcluidos(),'Devolvidos').'</td>'."\n";
        $strTabRetornoProgramado .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(1, 1, 0, 0, 1, 0, AtividadeRN::$TRP_DEVOLVER_CONCLUIDO, 0, 0, 0, false,  $objPainelControleDTO->getNumRetornoProgramadoDevolverConcluidosAlterados(),'Devolvidos e alterados').'</td>'."\n";
        $strTabRetornoProgramado .= '</tr>'."\n";
      }

    }

    $strTabRetornoProgramado .= '</table>'."\n\n";
  }

  $numRegistrosBlocos = 0;

  if ($objPainelControleDTO->getStrSinPainelBlocos()=='S') {

    $numTabIndexBlocos = PaginaSEI::getInstance()->getProxTabTabela();

    $strTabBlocos .= '<table id="tblBlocos" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" tabindex="'.$numTabIndexBlocos.'">'."\n";
    $strTabBlocos .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela('Blocos de Assinatura abertos', null, '', 'Blocos').'</caption>'."\n";
    $strTabBlocos .= '<tr>'."\n";
    $strTabBlocos .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'Blocos', '', PaginaSEI::getInstance()->getProxTabTabela()).'</th>'."\n";
    $strTabBlocos .= '<th class="infraTh" width="52%">Situação</th>'."\n";
    $strTabBlocos .= '<th class="infraTh" width="16%">Blocos</th>'."\n";
    $strTabBlocos .= '<th class="infraTh" width="16%">Documentos</th>'."\n";
    $strTabBlocos .= '<th class="infraTh" width="16%">Sem Assinatura</th>'."\n";
    $strTabBlocos .= '</tr>'."\n";

    if ($objPainelControleDTO->getNumBlocosParaRetornar()) {
      $numRegistrosBlocos++;
      $strTabBlocos .= '<tr class="infraTrClara">'."\n";
      $strTabBlocos .= '<td align="left" style="padding-left:2em;">Recebidos</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(true, BlocoRN::$TE_RECEBIDO, 0, $objPainelControleDTO->getNumBlocosParaRetornar(), 'blocos recebidos').'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_RECEBIDO, 0, $objPainelControleDTO->getNumBlocosParaRetornarDocumentos(),'documentos em blocos recebidos').'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_RECEBIDO, 1, ($objPainelControleDTO->getNumBlocosParaRetornarDocumentos() - $objPainelControleDTO->getNumBlocosParaRetornarAssinados()),'documentos em blocos recebidos sem assinatura').'</td>'."\n";
      $strTabBlocos .= '</tr>'."\n";
    }

    if ($objPainelControleDTO->getNumBlocosGerados()) {
      $numRegistrosBlocos++;
      $strTabBlocos .= '<tr class="infraTrClara">'."\n";
      $strTabBlocos .= '<td align="left" style="padding-left:2em;">Gerados</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(true, BlocoRN::$TE_ABERTO, 0, $objPainelControleDTO->getNumBlocosGerados(),'blocos gerados').'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_ABERTO, 0, $objPainelControleDTO->getNumBlocosGeradosDocumentos(),'documentos em blocos gerados').'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_ABERTO, 1, ($objPainelControleDTO->getNumBlocosGeradosDocumentos() - $objPainelControleDTO->getNumBlocosGeradosAssinados()),'documentos em blocos gerados sem assinatura').'</td>'."\n";
      $strTabBlocos .= '</tr>'."\n";
    }

    if ($objPainelControleDTO->getNumBlocosDisponibilizados()) {
      $numRegistrosBlocos++;
      $strTabBlocos .= '<tr class="infraTrClara">'."\n";
      $strTabBlocos .= '<td align="left" style="padding-left:2em;">Disponibilizados</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(true, BlocoRN::$TE_DISPONIBILIZADO, 0, $objPainelControleDTO->getNumBlocosDisponibilizados(),'blocos disponibilizados').'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_DISPONIBILIZADO, 0, $objPainelControleDTO->getNumBlocosDisponibilizadosDocumentos(),'documentos em blocos disponibilizados').'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_DISPONIBILIZADO, 1, ($objPainelControleDTO->getNumBlocosDisponibilizadosDocumentos() - $objPainelControleDTO->getNumBlocosDisponibilizadosAssinados()),'documentos em blocos disponibilizados sem assinatura').'</td>'."\n";
      $strTabBlocos .= '</tr>'."\n";
    }

    if ($objPainelControleDTO->getNumBlocosRetornados()) {
      $numRegistrosBlocos++;
      $strTabBlocos .= '<tr class="infraTrClara">'."\n";
      $strTabBlocos .= '<td align="left" style="padding-left:2em;">Retornados</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(true, BlocoRN::$TE_RETORNADO, 0, $objPainelControleDTO->getNumBlocosRetornados(),'blocos retornados').'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_RETORNADO, 0, $objPainelControleDTO->getNumBlocosRetornadosDocumentos(),'documentos em blocos retornados').'</td>'."\n";
      $strTabBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkBlocosPainel(false, BlocoRN::$TE_RETORNADO, 1, ($objPainelControleDTO->getNumBlocosRetornadosDocumentos() - $objPainelControleDTO->getNumBlocosRetornadosAssinados()),'documentos em blocos retornados sem assinatura').'</td>'."\n";
      $strTabBlocos .= '</tr>'."\n";
    }

    $strTabBlocos .= '</table>';
  }
  
  $numRegistrosGruposBlocos = 0;
  $strOpcaoGruposBlocos = '';

  if ($objPainelControleDTO->getStrSinPainelGruposBlocos()=='S') {

    $numTabIndexGruposBlocos = PaginaSEI::getInstance()->getProxTabTabela();

    $arrObjGruposBlocoDTO = $objPainelControleDTO->getArrObjGrupoBlocoDTO();

    $numRegistrosGruposBlocos = InfraArray::contar($arrObjGruposBlocoDTO);

    //if ($objPainelControleDTO->getStrSinPossuiSelecaoGruposBlocos() == 'S') {
      $strCaptionGruposBlocos = 'Grupos de blocos de assinatura abertos';
      $strBotaoGruposBlocos = ($objPainelControleDTO->getStrSinVerSelecaoGruposBlocos() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção');
      $strOpcaoGruposBlocos = '<button id="btnVerGruposBlocos" onclick="verSelecao(\'hdnSinVerGruposBlocos\',\''.$objPainelControleDTO->getStrSinVerSelecaoGruposBlocos().'\',\'GruposBlocos\');" class="infraButton" aria-label="'.$strBotaoGruposBlocos. ' '.$strCaptionGruposBlocos.'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.$strBotaoGruposBlocos.'</button>'."\n";
    //}

    if ($numRegistrosGruposBlocos) {

      $strTabGruposBlocos .= '<table id="tblGruposBlocos" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" tabindex="'.$numTabIndexGruposBlocos.'">'."\n";

      $strTabGruposBlocos .= '<caption class="infraCaption">'."\n";
      $strTabGruposBlocos .= '<div class="divCaptionLeft">'.$strCaptionGruposBlocos.':</div>'."\n";
      $strTabGruposBlocos .= '<div class="divCaptionRight">'."\n";
      $strTabGruposBlocos .= $strOpcaoGruposBlocos;
      $strTabGruposBlocos .= '</div>'."\n";
      $strTabGruposBlocos .= '</caption>'."\n";

      $strTabGruposBlocos .= '<tr>'."\n";
      $strTabGruposBlocos .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'GruposBlocos', '', PaginaSEI::getInstance()->getProxTabTabela()).'</th>'."\n";
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
        $strTabGruposBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkGruposBlocosPainel(true, $arrObjGruposBlocoDTO[$i]->getNumIdGrupoBloco(), false, $arrObjGruposBlocoDTO[$i]->getNumBlocos(),'blocos no grupo '.$arrObjGruposBlocoDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabGruposBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkGruposBlocosPainel(false, $arrObjGruposBlocoDTO[$i]->getNumIdGrupoBloco(), false,  $arrObjGruposBlocoDTO[$i]->getNumDocumentos(),'documentos no grupo '.$arrObjGruposBlocoDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabGruposBlocos .= '<td align="center">'.ProcedimentoINT::montarLinkGruposBlocosPainel(false, $arrObjGruposBlocoDTO[$i]->getNumIdGrupoBloco(), true, $arrObjGruposBlocoDTO[$i]->getNumSemAssinatura(),'documentos sem assinatura no grupo '.$arrObjGruposBlocoDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabGruposBlocos .= '</tr>'."\n";
      }
      $strTabGruposBlocos .= '</table>'."\n\n";
    }
  }

  $numRegistrosTiposProcessos = 0;
  $strOpcaoTiposProcessos = '';

  if ($objPainelControleDTO->getStrSinPainelTiposProcessos()=='S') {

    $numTabIndexTiposProcessos = PaginaSEI::getInstance()->getProxTabTabela();

    $arrObjTipoProcedimentoDTO = $objPainelControleDTO->getArrObjTipoProcedimentoDTO();

    $numRegistrosTiposProcessos = InfraArray::contar($arrObjTipoProcedimentoDTO);

    //if ($objPainelControleDTO->getStrSinPossuiSelecaoTiposProcessos() == 'S') {
    $strCaptionTiposProcessos = 'Processos abertos por tipo';
    $strBotaoTiposProcessos = ($objPainelControleDTO->getStrSinVerSelecaoTiposProcessos() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção');
    $strOpcaoTiposProcessos = '<button id="btnVerTiposProcessos" onclick="verSelecao(\'hdnSinVerTiposProcessos\',\''.$objPainelControleDTO->getStrSinVerSelecaoTiposProcessos().'\',\'TiposProcessos\');" class="infraButton" aria-label="'.$strBotaoTiposProcessos.' '.$strCaptionTiposProcessos.'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.$strBotaoTiposProcessos.'</button>'."\n";
    //}

    if ($numRegistrosTiposProcessos) {

      $strTabTiposProcessos .= '<table id="tblTiposProcessos" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" tabindex="'.$numTabIndexTiposProcessos.'">'."\n";

      $strTabTiposProcessos .= '<caption class="infraCaption">'."\n";
      $strTabTiposProcessos .= '<div class="divCaptionLeft">'.$strCaptionTiposProcessos.':</div>'."\n";
      $strTabTiposProcessos .= '<div class="divCaptionRight">'."\n";
      $strTabTiposProcessos .= $strOpcaoTiposProcessos;
      $strTabTiposProcessos .= '</div>'."\n";
      $strTabTiposProcessos .= '</caption>'."\n";

      $strTabTiposProcessos .= '<tr>'."\n";
      $strTabTiposProcessos .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'TiposProcessos', '', PaginaSEI::getInstance()->getProxTabTabela()).'</th>'."\n";
      $strTabTiposProcessos .= '<th class="infraTh" width="68%">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO, 'Tipo', 'Nome', $arrObjTipoProcedimentoDTO, true, 'TiposProcessos', 'ordenarTiposProcessos').'</th>'."\n";
      $strTabTiposProcessos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO, 'Processos', 'Processos', $arrObjTipoProcedimentoDTO, true, 'TiposProcessos', 'ordenarTiposProcessos').'</th>'."\n";
      $strTabTiposProcessos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoProcedimentoDTO, '<img '.PaginaSEI::montarTitleTooltip('Processos onde um documento foi incluído ou assinado','','Alterados',false,true).' src="'.Icone::EXCLAMACAO.'" class="infraImg" />', 'Alterados', $arrObjTipoProcedimentoDTO, true, 'TiposProcessos', 'ordenarTiposProcessos').'</th>'."\n";
      $strTabTiposProcessos .= '</tr>'."\n";

      $strCssTr = '';
      for ($i = 0; $i < $numRegistrosTiposProcessos; $i++) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabTiposProcessos .= $strCssTr."\n";

        $strTabTiposProcessos .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck($arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(), 'TP'.$arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(), 'Tipo do Processo', 'N', 'TiposProcessos').'</td>'."\n";
        $strTabTiposProcessos .= '<td align="left" style="padding-left:2em;">'.PaginaSEI::tratarHTML($arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabTiposProcessos .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 0, 0, 0, $arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(), 0, 0, false, $arrObjTipoProcedimentoDTO[$i]->getNumProcessos(),$arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabTiposProcessos .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 1, 0, 0, $arrObjTipoProcedimentoDTO[$i]->getNumIdTipoProcedimento(), 0, 0, true, $arrObjTipoProcedimentoDTO[$i]->getNumAlterados(),'alterados '.$arrObjTipoProcedimentoDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabTiposProcessos .= '</tr>'."\n";
      }
      $strTabTiposProcessos .= '</table>'."\n\n";
    }
  }

  $numRegistrosTiposPrioritarios = 0;
  $strOpcaoTiposPrioritarios = '';

  //$objPainelControleDTO->setStrSinPainelTiposPrioritarios("S");
  if ($objPainelControleDTO->getStrSinPainelTiposPrioritarios()=='S') {

    $numTabIndexTiposPrioritarios = PaginaSEI::getInstance()->getProxTabTabela();

    $arrObjTipoPrioridadeDTO = $objPainelControleDTO->getArrObjTipoPrioridadeDTO();

    $numRegistrosTiposPrioritarios = InfraArray::contar($arrObjTipoPrioridadeDTO);

    $strCaptionTiposPrioritarios = 'Processos abertos por prioridade';
    $strBotaoTiposPrioritarios = ($objPainelControleDTO->getStrSinVerSelecaoTiposPrioritarios() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção');
    $strOpcaoTiposPrioritarios = '<button id="btnVerTiposPrioritarios" onclick="verSelecao(\'hdnSinVerTiposPrioritarios\',\''.$objPainelControleDTO->getStrSinVerSelecaoTiposPrioritarios().'\',\'TiposPrioritarios\');" class="infraButton" aria-label="'.$strBotaoTiposPrioritarios.' '.$strCaptionTiposPrioritarios.'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.$strBotaoTiposPrioritarios.'</button>'."\n";
    //}

    if ($numRegistrosTiposPrioritarios) {

      $strTabTiposPrioritarios .= '<table id="tblTiposPrioritarios" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" tabindex="'.$numTabIndexTiposPrioritarios.'">'."\n";

      $strTabTiposPrioritarios .= '<caption class="infraCaption">'."\n";
      $strTabTiposPrioritarios .= '<div class="divCaptionLeft">'.$strCaptionTiposPrioritarios.':</div>'."\n";
      $strTabTiposPrioritarios .= '<div class="divCaptionRight">'."\n";
      $strTabTiposPrioritarios .= $strOpcaoTiposPrioritarios;
      $strTabTiposPrioritarios .= '</div>'."\n";
      $strTabTiposPrioritarios .= '</caption>'."\n";

      $strTabTiposPrioritarios .= '<tr>'."\n";
      $strTabTiposPrioritarios .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'TiposPrioritarios', '', PaginaSEI::getInstance()->getProxTabTabela()).'</th>'."\n";
      $strTabTiposPrioritarios .= '<th class="infraTh" width="68%">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoPrioridadeDTO, 'Tipo', 'Nome', $arrObjTipoPrioridadeDTO, true, 'TiposPrioritarios', 'ordenarTiposPrioritarios').'</th>'."\n";
      $strTabTiposPrioritarios .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoPrioridadeDTO, 'Processos', 'Processos', $arrObjTipoPrioridadeDTO, true, 'TiposPrioritarios', 'ordenarTiposPrioritarios').'</th>'."\n";
      $strTabTiposPrioritarios .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objTipoPrioridadeDTO, '<img '.PaginaSEI::montarTitleTooltip('Processos onde um documento foi incluído ou assinado','','Alterados',false,true).' src="'.Icone::EXCLAMACAO.'" class="infraImg" />', 'Alterados', $arrObjTipoPrioridadeDTO, true, 'TiposPrioritarios', 'ordenarTiposPrioritarios').'</th>'."\n";
      $strTabTiposPrioritarios .= '</tr>'."\n";

      $strCssTr = '';
      for ($i = 0; $i < $numRegistrosTiposPrioritarios; $i++) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabTiposPrioritarios .= $strCssTr."\n";

        $strTabTiposPrioritarios .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck($arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade(), 'TP'.$arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade(), 'Tipo de Prioridade', 'N', 'TiposPrioritarios').'</td>'."\n";
        $strTabTiposPrioritarios .= '<td align="left" style="padding-left:2em;">'.PaginaSEI::tratarHTML($arrObjTipoPrioridadeDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabTiposPrioritarios .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, false, $arrObjTipoPrioridadeDTO[$i]->getNumProcessos(),$arrObjTipoPrioridadeDTO[$i]->getStrNome(),$arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade()).'</td>'."\n";
        $strTabTiposPrioritarios .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 1, 0, 0, 0, 0, 0, true, $arrObjTipoPrioridadeDTO[$i]->getNumAlterados(),'alterados '.$arrObjTipoPrioridadeDTO[$i]->getStrNome(),$arrObjTipoPrioridadeDTO[$i]->getNumIdTipoPrioridade()).'</td>'."\n";

        $strTabTiposPrioritarios .= '</tr>'."\n";
      }
      $strTabTiposPrioritarios .= '</table>'."\n\n";
    }
  }

  $numRegistrosMarcadores = 0;
  $strOpcaoMarcadores = '';

  if ($objPainelControleDTO->getStrSinPainelMarcadores()=='S') {

    $numTabIndexMarcadores = PaginaSEI::getInstance()->getProxTabTabela();

    $arrObjMarcadorDTO = $objPainelControleDTO->getArrObjMarcadorDTO();

    $numRegistrosMarcadores = InfraArray::contar($arrObjMarcadorDTO);

    //if ($objPainelControleDTO->getStrSinPossuiSelecaoMarcadores() == 'S') {
      $strCaptionMarcadores = 'Marcadores em processos';
      $strBotaoMarcadores = ($objPainelControleDTO->getStrSinVerSelecaoMarcadores() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção');
      $strOpcaoMarcadores = '<button id="btnVerMarcadores" onclick="verSelecao(\'hdnSinVerMarcadores\',\''.$objPainelControleDTO->getStrSinVerSelecaoMarcadores().'\',\'Marcadores\');" class="infraButton" aria-label="'.$strBotaoMarcadores.' '.$strCaptionMarcadores.'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.$strBotaoMarcadores.'</button>'."\n";
    //}

    if ($numRegistrosMarcadores) {

      $strTabMarcadores .= '<table id="tblMarcadores" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" tabindex="'.$numTabIndexMarcadores.'">'."\n";
      $strTabMarcadores .= '<caption class="infraCaption">'."\n";
      $strTabMarcadores .= '<div class="divCaptionLeft">'.$strCaptionMarcadores.':</div>'."\n";
      $strTabMarcadores .= '<div class="divCaptionRight">'."\n";
      $strTabMarcadores .= $strOpcaoMarcadores;
      $strTabMarcadores .= '</div>'."\n";
      $strTabMarcadores .= '</caption>'."\n";

      $strTabMarcadores .= '<tr>'."\n";
      $strTabMarcadores .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'Marcadores', '', PaginaSEI::getInstance()->getProxTabTabela()).'</th>'."\n";
      $strTabMarcadores .= '<th class="infraTh" width="68%">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO, 'Marcador', 'Nome', $arrObjMarcadorDTO, true, 'Marcadores', 'ordenarMarcadores').'</th>'."\n";
      $strTabMarcadores .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO, 'Processos', 'Processos', $arrObjMarcadorDTO, true, 'Marcadores', 'ordenarMarcadores').'</th>'."\n";
      $strTabMarcadores .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objMarcadorDTO, '<img '.PaginaSEI::montarTitleTooltip('Processos onde um documento foi incluído ou assinado','','Alterados',false,true).' src="'.Icone::EXCLAMACAO.'" class="infraImg" />', 'Alterados', $arrObjMarcadorDTO, true, 'Marcadores', 'ordenarMarcadores').'</th>'."\n";

      $strTabMarcadores .= '</tr>'."\n";

      $strCssTr = '';
      for ($i = 0; $i < $numRegistrosMarcadores; $i++) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabMarcadores .= $strCssTr."\n";

        $strTabMarcadores .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck($arrObjMarcadorDTO[$i]->getNumIdMarcador(), 'M'.$arrObjMarcadorDTO[$i]->getNumIdMarcador(), 'Marcador', 'N', 'Marcadores').'</td>'."\n";

        $strTabMarcadores .= '<td align="left" valign="center" style="padding-left:2em;"><span class="celulaComImagem">';
        if (!($arrObjMarcadorDTO[$i]->getNumIdMarcador()==-1 && $numRegistrosMarcadores==1)){
          $strTabMarcadores .= '<img src="'.$arrObjMarcadorDTO[$i]->getStrArquivoIcone().'" class="InfraImg" />';
        }
        $strTabMarcadores .= PaginaSEI::tratarHTML($arrObjMarcadorDTO[$i]->getStrNome()).'</span></td>'."\n";

        $strTabMarcadores .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 0, 0, 0, 0, $arrObjMarcadorDTO[$i]->getNumIdMarcador(), 0, false, $arrObjMarcadorDTO[$i]->getNumProcessos(),$arrObjMarcadorDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabMarcadores .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 1, 0, 0, 0, $arrObjMarcadorDTO[$i]->getNumIdMarcador(), 0, true, $arrObjMarcadorDTO[$i]->getNumAlterados(),'alterados '.$arrObjMarcadorDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabMarcadores .= '</tr>'."\n";
      }
      $strTabMarcadores .= '</table>'."\n\n";;
    }
  }

  $numRegistrosUsuariosAtribuicao = 0;
  $strOpcaoAtribuicoes = '';

  if ($objPainelControleDTO->getStrSinPainelAtribuicoes()=='S') {

    $numTabIndexAtribuicoes = PaginaSEI::getInstance()->getProxTabTabela();

    $arrObjUsuarioDTO = $objPainelControleDTO->getArrObjUsuarioDTOAtribuicao();

    $numRegistrosUsuariosAtribuicao = InfraArray::contar($arrObjUsuarioDTO);

    //if ($objPainelControleDTO->getStrSinPossuiSelecaoAtribuicoes() == 'S') {
      $strCaptionAtribuicoes = 'Atribuições de processos';
      $strBotaoAtribuicoes = ($objPainelControleDTO->getStrSinVerSelecaoAtribuicoes() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção');
      $strOpcaoAtribuicoes = '<button id="btnVerAtribuicoes" onclick="verSelecao(\'hdnSinVerAtribuicoes\',\''.$objPainelControleDTO->getStrSinVerSelecaoAtribuicoes().'\',\'Atribuicoes\');" class="infraButton" aria-label="'.$strBotaoAtribuicoes.' '.$strCaptionAtribuicoes.'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.$strBotaoAtribuicoes.'</button>'."\n";
    //}

    if ($numRegistrosUsuariosAtribuicao) {

      $strTabAtribuicoes .= '<table id="tblAtribuicoes" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" tabindex="'.$numTabIndexAtribuicoes.'">'."\n";

      $strTabAtribuicoes .= '<caption class="infraCaption">'."\n";
      $strTabAtribuicoes .= '<div class="divCaptionLeft">'.$strCaptionAtribuicoes.':</div>'."\n";
      $strTabAtribuicoes .= '<div class="divCaptionRight">'."\n";
      $strTabAtribuicoes .= $strOpcaoAtribuicoes;
      $strTabAtribuicoes .= '</div>'."\n";
      $strTabAtribuicoes .= '</caption>'."\n";

      $strTabAtribuicoes .= '<tr>'."\n";
      $strTabAtribuicoes .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'Atribuicoes', '', PaginaSEI::getInstance()->getProxTabTabela()).'</th>'."\n";
      $strTabAtribuicoes .= '<th class="infraTh" width="68%">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'Usuário', 'Nome', $arrObjUsuarioDTO, true, 'Atribuicoes', 'ordenarAtribuicoes').'</th>'."\n";
      $strTabAtribuicoes .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, 'Processos', 'Processos', $arrObjUsuarioDTO, true, 'Atribuicoes', 'ordenarAtribuicoes').'</th>'."\n";
      $strTabAtribuicoes .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objUsuarioDTO, '<img '.PaginaSEI::montarTitleTooltip('Processos onde um documento foi incluído ou assinado','','Alterados',false,true).' src="'.Icone::EXCLAMACAO.'" class="infraImg" />', 'Alterados', $arrObjUsuarioDTO, true, 'Atribuicoes', 'ordenarAtribuicoes').'</th>'."\n";
      $strTabAtribuicoes .= '</tr>'."\n";

      $strCssTr = '';
      for ($i = 0; $i < $numRegistrosUsuariosAtribuicao; $i++) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabAtribuicoes .= $strCssTr."\n";

        $strTabAtribuicoes .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck($arrObjUsuarioDTO[$i]->getNumIdUsuario(), 'U'.$arrObjUsuarioDTO[$i]->getNumIdUsuario(), 'Atribuição', 'N', 'Atribuicoes').'</td>'."\n";
        $strTabAtribuicoes .= '<td align="left" style="padding-left:2em;"><a alt="'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrNome()).'" title="'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrNome()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjUsuarioDTO[$i]->getStrSigla()).'</a></td>'."\n";
        $strTabAtribuicoes .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 0, 0, 0, 0, 0, $arrObjUsuarioDTO[$i]->getNumIdUsuario(), false, $arrObjUsuarioDTO[$i]->getNumProcessos(),$arrObjUsuarioDTO[$i]->getStrSigla()).'</td>'."\n";
        $strTabAtribuicoes .= '<td align="center">'.ProcedimentoINT::montarLinkProcessosPainel(0, 0, 0, 0, 1, 0, 0, 0, 0, $arrObjUsuarioDTO[$i]->getNumIdUsuario(), true, $arrObjUsuarioDTO[$i]->getNumAlterados(),'alterados '.$arrObjUsuarioDTO[$i]->getStrSigla()).'</td>'."\n";
        $strTabAtribuicoes .= '</tr>'."\n";
      }
      $strTabAtribuicoes .= '</table>'."\n\n";
    }
  }

  $numRegistrosGruposAcompanhamento = 0;
  $strOpcaoAcompanhamentos = '';

  if ($objPainelControleDTO->getStrSinPainelAcompanhamentos()=='S') {

    $numTabIndexAcompanhamentos = PaginaSEI::getInstance()->getProxTabTabela();

    $arrObjGrupoAcompanhamentoDTO = $objPainelControleDTO->getArrObjGrupoAcompanhamentoDTO();

    $numRegistrosGruposAcompanhamento = InfraArray::contar($arrObjGrupoAcompanhamentoDTO);

    //if ($objPainelControleDTO->getStrSinPossuiSelecaoAcompanhamentos() == 'S') {
      $strCaptionAcompanhamentos = 'Acompanhamentos Especiais em processos';
      $strBotaoAcompanhamentos = ($objPainelControleDTO->getStrSinVerSelecaoAcompanhamentos() == 'S' ? 'Ver Tudo' : 'Ver Minha Seleção');
      $strOpcaoAcompanhamentos = '<button id="btnVerAcompanhamentos" onclick="verSelecao(\'hdnSinVerAcompanhamentos\',\''.$objPainelControleDTO->getStrSinVerSelecaoAcompanhamentos().'\',\'Acompanhamentos\');" class="infraButton" aria-label="'.$strBotaoAcompanhamentos.' '.$strCaptionAcompanhamentos.'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'">'.$strBotaoAcompanhamentos.'</button>'."\n";
    //}

    if ($numRegistrosGruposAcompanhamento) {

      $strTabAcompanhamentos .= '<table id="tblAcompanhamentos" border="0" cellspacing="0" cellpadding="1" class="infraTable tabelaPainel" tabindex="'.$numTabIndexAcompanhamentos.'">'."\n";

      $strTabAcompanhamentos .= '<caption class="infraCaption">'."\n";
      $strTabAcompanhamentos .= '<div class="divCaptionLeft">'.$strCaptionAcompanhamentos.':</div>'."\n";
      $strTabAcompanhamentos .= '<div class="divCaptionRight">'."\n";
      $strTabAcompanhamentos .= $strOpcaoAcompanhamentos;
      $strTabAcompanhamentos .= '</div>'."\n";
      $strTabAcompanhamentos .= '</caption>'."\n";

      $strTabAcompanhamentos .= '<tr>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" style="display:none">'.PaginaSEI::getInstance()->getThCheck('', 'Acompanhamentos', '', PaginaSEI::getInstance()->getProxTabTabela()).'</th>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" width="36%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO, 'Grupo', 'Nome', $arrObjGrupoAcompanhamentoDTO, true, 'Acompanhamentos', 'ordenarAcompanhamentos').'</th>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO, 'Processos', 'Processos', $arrObjGrupoAcompanhamentoDTO, true, 'Acompanhamentos', 'ordenarAcompanhamentos').'</th>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO, 'Abertos', 'Abertos', $arrObjGrupoAcompanhamentoDTO, true, 'Acompanhamentos', 'ordenarAcompanhamentos').'</th>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO, 'Fechados', 'Fechados', $arrObjGrupoAcompanhamentoDTO, true, 'Acompanhamentos', 'ordenarAcompanhamentos').'</th>'."\n";
      $strTabAcompanhamentos .= '<th class="infraTh" width="16%">'.PaginaSEI::getInstance()->getThOrdenacao($objGrupoAcompanhamentoDTO, '<img '.PaginaSEI::montarTitleTooltip('Processos onde um documento foi incluído ou assinado','','Alterados',false,true).' src="'.Icone::EXCLAMACAO.'" class="infraImg" />', 'Alterados', $arrObjGrupoAcompanhamentoDTO, true, 'Acompanhamentos', 'ordenarAcompanhamentos').'</th>'."\n";
      $strTabAcompanhamentos .= '</tr>'."\n";

      $strCssTr = '';
      for ($i = 0; $i < $numRegistrosGruposAcompanhamento; $i++) {

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strTabAcompanhamentos .= $strCssTr."\n";

        $strTabAcompanhamentos .= '<td style="display:none">'.PaginaSEI::getInstance()->getTrCheck($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 'A'.$arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 'Acompanhamento Especial', 'N', 'Acompanhamentos').'</td>'."\n";
        $strTabAcompanhamentos .= '<td align="left" style="padding-left:2em;">'.PaginaSEI::tratarHTML($arrObjGrupoAcompanhamentoDTO[$i]->getStrNome()).'</a></td>'."\n";
        $strTabAcompanhamentos .= '<td align="center">'.ProcedimentoINT::montarLinkAcompanhamentosPainel($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 0, 0, 0, false, $arrObjGrupoAcompanhamentoDTO[$i]->getNumProcessos(),$arrObjGrupoAcompanhamentoDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabAcompanhamentos .= '<td align="center">'.ProcedimentoINT::montarLinkAcompanhamentosPainel($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 1, 0, 0, false, $arrObjGrupoAcompanhamentoDTO[$i]->getNumAbertos(),'abertos '.$arrObjGrupoAcompanhamentoDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabAcompanhamentos .= '<td align="center">'.ProcedimentoINT::montarLinkAcompanhamentosPainel($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 0, 1, 0, false, $arrObjGrupoAcompanhamentoDTO[$i]->getNumFechados(),'fechados '.$arrObjGrupoAcompanhamentoDTO[$i]->getStrNome()).'</td>'."\n";
        $strTabAcompanhamentos .= '<td align="center">'.ProcedimentoINT::montarLinkAcompanhamentosPainel($arrObjGrupoAcompanhamentoDTO[$i]->getNumIdGrupoAcompanhamento(), 0, 0, 1, true, $arrObjGrupoAcompanhamentoDTO[$i]->getNumAlterados(),'alterados '.$arrObjGrupoAcompanhamentoDTO[$i]->getStrNome()).'</td>'."\n";
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
#tblMarcadores td img {cursor:default;}

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
  vertical-align:sub;
}

div.divMensagem {
  display:inline-table;
  width:99%;
  margin-left:0;
  border-left:2px solid #bfbfbf;
  border-bottom:1px solid #bfbfbf;
  padding:4px 0;
  margin:.6em 0;
  min-height:40px;
}

div.divMensagem div{
  float:left;
  margin-left:1em;
  font-size: .875rem;
  padding: 0 .5em;
}

div.divMensagem button{
  float:right;
}

.cardPainel a:hover,.cardPainel a:focus {
  box-shadow: 0 .5rem 1rem rgba(0,0,0,.18);
}

.cardPainel a:focus img{
  outline: none !important;
}

.cardTituloPainel{
  font-size:.75rem;
}

span.celulaComImagem{
  vertical-align: text-bottom;
}

span.celulaComImagem img{
  padding-right: .75rem;
  vertical-align: bottom;
}


<?=$strCssBanner?>

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

  function inicializar(){
    <?=$strJsAviso?>
    <?=$strJsNovidades?>

    if( !infraIsBreakpointBootstrap("lg") ) {
      infraOcultarMenuSistemaEsquema();
    }

    infraEfeitoTabelas();

    var ancora = infraGetAnchor();
    if (ancora!=null){
      if (document.getElementById('tbl'+ancora)!=null){
        document.getElementById('tbl'+ancora).focus();
      }else if (document.getElementById('divMsg'+ancora)!=null){
        document.getElementById('divMsg'+ancora).focus();
      }else{
        document.getElementById('sbmAtualizar').focus();
      }
    }else {
      document.getElementById('sbmAtualizar').focus();
    }
  }

  function verSelecao(campo,valor,ancora){
    document.getElementById(campo).value = (valor == 'S' ? 'N' : 'S');
    processar(ancora);
  }

  function ordenarGruposBlocos(){
    processar('GruposBlocos');
  }

  function ordenarTiposProcessos(){
    processar('TiposProcessos');
  }

  function ordenarTiposPrioritarios(){
    processar('TiposPrioritarios');
  }

  function ordenarMarcadores(){
    processar('Marcadores');
  }

  function ordenarAtribuicoes(){
    processar('Atribuicoes');
  }

  function ordenarAcompanhamentos(){
    processar('Acompanhamentos');
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
  echo $strHtmlBanner;

  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

  if ($objPainelControleDTO->getStrSinPainelProcessos()=='S') {
    echo "<br />\n";
    if ($numRegistrosProcessos==0){
      echo '<div class="divMensagem"><div id="divMsgGeral" tabindex="'.$numTabIndexGeral.'">Nenhum processo aberto na unidade.</div></div>';
    }else {
      ?>
      <div class="container-fluid">

        <div class="row">


          <?=SeiINT::montarCard(
            'Total',
            InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosRecebidos()+$objPainelControleDTO->getNumProcessosGerados()),
            ProcedimentoINT::montarLinkControleProcessos($numRegistrosProcessos, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0,0),
            'Total de processos abertos',
            'painelControleControle',
            Icone::PROCESSO_ABERTO,
            0,
            null,
            'color:var(--infra-esquema-cor-escura);',
            null
          )?>

          <?=SeiINT::montarCard(
            'Recebidos',
            InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosRecebidos()),
            ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosRecebidos(), 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0,0),
            'Total de recebidos',
            'painelControleControle',
            null,
            round($objPainelControleDTO->getNumProcessosRecebidos()/($objPainelControleDTO->getNumProcessosRecebidos()+$objPainelControleDTO->getNumProcessosGerados())*100),
            null,
            'color:#ffc107;',
            null

          )?>

          <?=SeiINT::montarCard(
            'Gerados',
            InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosGerados()),
            ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosGerados(), 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0,0),
            'Total de gerados',
            'painelControleControle',
            null,
            round($objPainelControleDTO->getNumProcessosGerados()/($objPainelControleDTO->getNumProcessosRecebidos()+$objPainelControleDTO->getNumProcessosGerados())*100),
            null,
            'color:#ffc107;',
            null

          )?>

          <?=SeiINT::montarCard(
            'Não Visualizados',
            InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosNaoVisualizados()),
            ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosNaoVisualizados(), 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0,0),
            'Processos não visualizados',
            'painelControleControle',
            null,
            null,
            'border-left-width: 5px; border-left-color: #ed1c24;',
            'color:#ed1c24;',
            'color:#ed1c24;',
          )?>

          <?=SeiINT::montarCard(
            'Atribuídos a mim',
            InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosAtribuidosMim()),
            ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosAtribuidosMim(), 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, SessaoSEI::getInstance()->getNumIdUsuario(), 0,0),
            'Processos atribuídos a mim',
            'painelControleControle',
            Icone::PROCESSO_ATRIBUIR,
            null,
            null,
            'color:var(--infra-esquema-cor-escura);',
            null
          )?>

          <?=SeiINT::montarCard(
            'Sem Acompanhamento',
            InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosSemAcompanhamento()),
            ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosSemAcompanhamento(), 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,0,0),
            'Processos sem acompanhamento',
            'painelControleControle',
            Icone::ACOMPANHAMENTO_ESPECIAL_INEXISTENTE,
            null,
            null,
            'color:var(--infra-esquema-cor-escura);',
            null
          )?>



          <?=SeiINT::montarCard(
            'Alterados',
            InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosAlterados()),
            ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosAlterados(), 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0,0,0),
            'Processos alterados',
            'painelControleControle',
            Icone::EXCLAMACAO,
            null,
            null,
            'color:var(--infra-esquema-cor-escura);',
            null,
          )?>

          <? if ($objPainelControleDTO->getNumProcessosReaberturaProgramada()){ ?>

            <?=SeiINT::montarCard(
              'Reaberturas Programadas',
              InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosReaberturaProgramada()),
              ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosReaberturaProgramada(), 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0,0,0),
              'Reaberturas programadas',
              'painelControleControle',
              Icone::PROCESSO_REABERTURA_PROGRAMADA,
              null,
              null,
              'color:var(--infra-esquema-cor-escura);',
              null,
            )?>

          <? } ?>

          <? if ($objPainelControleDTO->getNumProcessosFederacao()){ ?>

            <?=SeiINT::montarCard(
              'SEI Federação',
              InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosFederacao()),
              ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosFederacao(), 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0,0,0),
              'Processos do SEI Federação',
              'painelControleControle',
              Icone::FEDERACAO,
              null,
              null,
              'color:var(--infra-esquema-cor-escura);',
              null,
            )?>

          <? } ?>

          <? if ($objPainelControleDTO->getNumProcessosPrioritarios()){ ?>

            <?=SeiINT::montarCard(
              'Prioritários',
              InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosPrioritarios()),
              ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosPrioritarios(), 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1,0),
              'Processos Prioritários',
              'painelControleControle',
              Icone::PROCESSO_PRIORITARIO,
              null,
              null,
              'color:var(--infra-esquema-cor-escura);',
              null,
            )?>

          <? } ?>

          <? if ($objPainelControleDTO->getNumProcessosSigilosos()){ ?>

          <?=SeiINT::montarCard(
            'Sigilosos',
            InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosSigilosos()),
            ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosSigilosos(), 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0,0,0),
            'Processos sigilosos',
            'painelControleControle',
            Icone::CREDENCIAL_GERENCIAR,
            null,
            null,
            'color:var(--infra-esquema-cor-escura);',
            null,
          )?>
        <? } ?>

        <? if ($objPainelControleDTO->getNumProcessosCredencialAssinatura()){ ?>

          <?=SeiINT::montarCard(
            'Credenciais para Assinatura',
            InfraUtil::formatarMilhares($objPainelControleDTO->getNumProcessosCredencialAssinatura()),
            ProcedimentoINT::montarLinkControleProcessos($objPainelControleDTO->getNumProcessosCredencialAssinatura(), 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0,0,0),
            'Processos com credencial para assinatura',
            'painelControleControle',
            Icone::CREDENCIAL_ASSINATURA,
            null,
            null,
            'color:var(--infra-esquema-cor-escura);',
            null,
          )?>

          <? } ?>

        </div>
      </div>

      <?
    }
  }

  if ($objPainelControleDTO->getStrSinPainelControlesPrazos()=='S') {
    echo "<br />\n";
    if ($numRegistrosControlePrazo==0){
      echo '<div class="divMensagem"><div id="divMsgControlePrazo" tabindex="'.$numTabIndexControlePrazo.'">Nenhum controle de prazo na unidade.</div></div>';
    }else {
      PaginaSEI::getInstance()->montarAreaTabela($strTabControlePrazo, $numRegistrosControlePrazo, false, '', null, 'ControlePrazo');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelRetornosProgramados()=='S') {
    echo "<br />\n";
    if ($numRegistrosRetornoProgramado==0){
      echo '<div class="divMensagem"><div id="divMsgRetornoProgramado" tabindex="'.$numTabIndexRetornoProgramado.'">Nenhum retorno programado na unidade.</div></div>';
    }else {
      PaginaSEI::getInstance()->montarAreaTabela($strTabRetornoProgramado, $numRegistrosRetornoProgramado, false, '', null, 'RetornoProgramado');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelBlocos()=='S') {
    echo "<br />\n";
    if ($numRegistrosBlocos==0){
      echo '<div class="divMensagem"><div id="divMsgBlocos" tabindex="'.$numTabIndexBlocos.'">Nenhum bloco de assinatura aberto na unidade.</div></div>';
    }else {
      PaginaSEI::getInstance()->montarAreaTabela($strTabBlocos, $numRegistrosBlocos, false, '', null, 'Blocos');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelGruposBlocos()=='S') {
    echo "<br />\n";
    if ($numRegistrosGruposBlocos==0) {
      echo '<div class="divMensagem"><div id="divMsgGruposBlocos" tabindex="'.$numTabIndexGruposBlocos.'">Nenhum grupo de bloco de assinatura aberto '.($objPainelControleDTO->getStrSinVerSelecaoGruposBlocos()=='S'?($objPainelControleDTO->getStrSinPossuiSelecaoGruposBlocos()=='S'?'com blocos ':'').'na minha seleção':'com blocos na unidade').'.</div>'.$strOpcaoGruposBlocos.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabGruposBlocos, $numRegistrosGruposBlocos, false, '', null, 'GruposBlocos');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelTiposProcessos()=='S') {
    echo "<br />\n";
    if ($numRegistrosTiposProcessos==0) {
      echo '<div class="divMensagem"><div id="divMsgTiposProcessos" tabindex="'.$numTabIndexTiposProcessos.'">Nenhum tipo de processo '.($objPainelControleDTO->getStrSinVerSelecaoTiposProcessos()=='S'?($objPainelControleDTO->getStrSinPossuiSelecaoTiposProcessos()=='S'?'com processos ':'').'na minha seleção':'com processos na unidade').'.</div>'.$strOpcaoTiposProcessos.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabTiposProcessos, $numRegistrosTiposProcessos, false, '', null, 'TiposProcessos');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelTiposPrioritarios()=='S') {
    echo "<br />\n";
    if ($numRegistrosTiposPrioritarios==0) {
      echo '<div class="divMensagem"><div id="divMsgTiposPrioritarios" tabindex="'.$numTabIndexTiposPrioritarios.'">Nenhum processo com prioridade definida '.($objPainelControleDTO->getStrSinVerSelecaoTiposPrioritarios()=='S'?'na minha seleção':'na unidade').'.</div>'.$strOpcaoTiposPrioritarios.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabTiposPrioritarios, $numRegistrosTiposPrioritarios, false, '', null, 'TiposPrioritarios');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelMarcadores()=='S') {
    echo "<br />\n";
    if ($numRegistrosMarcadores==0) {
      echo '<div class="divMensagem"><div id="divMsgMarcadores" tabindex="'.$numTabIndexMarcadores.'">Nenhum marcador '.($objPainelControleDTO->getStrSinVerSelecaoMarcadores()=='S'?($objPainelControleDTO->getStrSinPossuiSelecaoMarcadores()=='S'?'com processos ':'').'na minha seleção':'com processos na unidade').'.</div>'.$strOpcaoMarcadores.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabMarcadores, $numRegistrosMarcadores, false, '', null, 'Marcadores');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelAtribuicoes()=='S') {
    echo "<br />\n";
    if ($numRegistrosUsuariosAtribuicao==0) {
      echo '<div class="divMensagem"><div id="divMsgAtribuicoes" tabindex="'.$numTabIndexAtribuicoes.'">Nenhum usuário '.($objPainelControleDTO->getStrSinVerSelecaoAtribuicoes()=='S'?($objPainelControleDTO->getStrSinPossuiSelecaoAtribuicoes()=='S'?'com processos atribuídos ':'').'na minha seleção':'com processos atribuídos na unidade').'.</div>'.$strOpcaoAtribuicoes.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabAtribuicoes, $numRegistrosUsuariosAtribuicao, false, '', null, 'Atribuicoes');
    }
  }

  if ($objPainelControleDTO->getStrSinPainelAcompanhamentos()=='S') {
    echo "<br />\n";
    if ($numRegistrosGruposAcompanhamento==0) {
      echo '<div class="divMensagem"><div id="divMsgAcompanhamentos" tabindex="'.$numTabIndexAcompanhamentos.'">Nenhum grupo de acompanhamento especial '.($objPainelControleDTO->getStrSinVerSelecaoAcompanhamentos()=='S'?($objPainelControleDTO->getStrSinPossuiSelecaoAcompanhamentos()=='S'?'com processos ':'').'na minha seleção':'com processos na unidade').'.</div>'.$strOpcaoAcompanhamentos.'</div>';
    }else{
      PaginaSEI::getInstance()->montarAreaTabela($strTabAcompanhamentos, $numRegistrosGruposAcompanhamento, false, '', null, 'Acompanhamentos');
    }
  }

  echo "<br />\n";
  echo "<br />\n";
  echo "<br />\n";
  echo "<br />\n";
  echo "<br />\n";


  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>

  <input type="hidden" id="hdnSinVerTiposProcessos" name="hdnSinVerTiposProcessos" value="<?=$objPainelControleDTO->getStrSinVerSelecaoTiposProcessos()?>" />
  <input type="hidden" id="hdnSinVerTiposPrioritarios" name="hdnSinVerTiposPrioritarios" value="<?=$objPainelControleDTO->getStrSinVerSelecaoTiposProcessos()?>" />
  <input type="hidden" id="hdnSinVerGruposBlocos" name="hdnSinVerGruposBlocos" value="<?=$objPainelControleDTO->getStrSinVerSelecaoGruposBlocos()?>" />
  <input type="hidden" id="hdnSinVerMarcadores" name="hdnSinVerMarcadores" value="<?=$objPainelControleDTO->getStrSinVerSelecaoMarcadores()?>" />
  <input type="hidden" id="hdnSinVerAtribuicoes" name="hdnSinVerAtribuicoes" value="<?=$objPainelControleDTO->getStrSinVerSelecaoAtribuicoes()?>" />
  <input type="hidden" id="hdnSinVerAcompanhamentos" name="hdnSinVerAcompanhamentos" value="<?=$objPainelControleDTO->getStrSinVerSelecaoAcompanhamentos()?>" />

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>