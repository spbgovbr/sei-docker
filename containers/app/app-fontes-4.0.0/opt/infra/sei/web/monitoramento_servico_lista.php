<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/10/2015 - criado por mga
*
* Versão do Gerador de Código: 1.35.0
*
* Versão no CVS: $Id$
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

  PaginaSEI::getInstance()->prepararSelecao('monitoramento_servico_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selSistema', 'selServico', 'selOperacao', 'txtDthInicialMon', 'txtDthFinalMon', 'selStaTipo'));

  switch($_GET['acao']){
    case 'monitoramento_servico_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMonitoramentoServicoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMonitoramentoServicoDTO = new MonitoramentoServicoDTO();
          $objMonitoramentoServicoDTO->setDblIdMonitoramentoServico($arrStrIds[$i]);
          $arrObjMonitoramentoServicoDTO[] = $objMonitoramentoServicoDTO;
        }
        $objMonitoramentoServicoRN = new MonitoramentoServicoRN();
        $objMonitoramentoServicoRN->excluir($arrObjMonitoramentoServicoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'monitoramento_servico_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjMonitoramentoServicoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objMonitoramentoServicoDTO = new MonitoramentoServicoDTO();
          $objMonitoramentoServicoDTO->setDblIdMonitoramentoServico($arrStrIds[$i]);
          $arrObjMonitoramentoServicoDTO[] = $objMonitoramentoServicoDTO;
        }
        $objMonitoramentoServicoRN = new MonitoramentoServicoRN();
        $objMonitoramentoServicoRN->desativar($arrObjMonitoramentoServicoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'monitoramento_servico_reativar':
      $strTitulo = 'Reativar Monitoramento de Serviços';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjMonitoramentoServicoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objMonitoramentoServicoDTO = new MonitoramentoServicoDTO();
            $objMonitoramentoServicoDTO->setDblIdMonitoramentoServico($arrStrIds[$i]);
            $arrObjMonitoramentoServicoDTO[] = $objMonitoramentoServicoDTO;
          }
          $objMonitoramentoServicoRN = new MonitoramentoServicoRN();
          $objMonitoramentoServicoRN->reativar($arrObjMonitoramentoServicoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'monitoramento_servico_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Monitoramento de Serviço','Selecionar Monitoramento de Serviços');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='monitoramento_servico_cadastrar'){
        if (isset($_GET['id_monitoramento_servico'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_monitoramento_servico']);
        }
      }
      break;

    case 'monitoramento_servico_listar':
      $strTitulo = 'Monitoramento de Serviços';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'monitoramento_servico_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'monitoramento_servico_listar' || $_GET['acao'] == 'monitoramento_servico_selecionar'){ */
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('monitoramento_servico_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=monitoramento_servico_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objMonitoramentoServicoDTO = new MonitoramentoServicoDTO();
  $objMonitoramentoServicoDTO->retDblIdMonitoramentoServico();
  $objMonitoramentoServicoDTO->retNumIdServico();
  $objMonitoramentoServicoDTO->retStrIdentificacaoServico();
  $objMonitoramentoServicoDTO->retStrSiglaUsuarioServico();
  $objMonitoramentoServicoDTO->retStrNomeUsuarioServico();
  $objMonitoramentoServicoDTO->retStrOperacao();
  $objMonitoramentoServicoDTO->retDblTempoExecucao();
  $objMonitoramentoServicoDTO->retStrIpAcesso();
  $objMonitoramentoServicoDTO->retDthAcesso();
  $objMonitoramentoServicoDTO->retStrServidor();
  $objMonitoramentoServicoDTO->retStrUserAgent();
/* 
  if ($_GET['acao'] == 'monitoramento_servico_reativar'){
    //Lista somente inativos
    $objMonitoramentoServicoDTO->setBolExclusaoLogica(false);
    $objMonitoramentoServicoDTO->setStrSinAtivo('N');
  }
 */


  $numIdUsuarioServico = PaginaSEI::getInstance()->recuperarCampo('selSistema');
  if ($numIdUsuarioServico!=='' && $numIdUsuarioServico!=='null'){
    $objMonitoramentoServicoDTO->setNumIdUsuarioServico($numIdUsuarioServico);
  }

  $numIdServico = PaginaSEI::getInstance()->recuperarCampo('selServico');
  if ($numIdServico!=='' && $numIdServico!=='null'){
    $objMonitoramentoServicoDTO->setNumIdServico($numIdServico);
  }

  $strOperacao = PaginaSEI::getInstance()->recuperarCampo('selOperacao');
  if ($strOperacao!=='' && $strOperacao!=='null'){
    $objMonitoramentoServicoDTO->setStrOperacao($strOperacao);
  }

  $objMonitoramentoServicoDTO->setStrStaTipo(PaginaSEI::getInstance()->recuperarCampo('selStaTipo',MonitoramentoServicoRN::$TM_RESUMIDO));

  $dthInicial = PaginaSEI::getInstance()->recuperarCampo('txtDthInicialMon');
  if (!InfraString::isBolVazia($dthInicial)){
    $objMonitoramentoServicoDTO->setDthInicial($dthInicial);
  }

  $dthFinal = PaginaSEI::getInstance()->recuperarCampo('txtDthFinalMon');
  if (!InfraString::isBolVazia($dthFinal)){
    $objMonitoramentoServicoDTO->setDthFinal($dthFinal);
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objMonitoramentoServicoDTO, 'Acesso', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSEI::getInstance()->prepararPaginacao($objMonitoramentoServicoDTO);

  if ($_POST['hdnFlagMonitoramento']=='1') {
    $objMonitoramentoServicoRN = new MonitoramentoServicoRN();
    $arrObjMonitoramentoServicoDTO = $objMonitoramentoServicoRN->pesquisar($objMonitoramentoServicoDTO);
  }else{
    $arrObjMonitoramentoServicoDTO = array();
  }

  PaginaSEI::getInstance()->processarPaginacao($objMonitoramentoServicoDTO);
  $numRegistros = InfraArray::contar($arrObjMonitoramentoServicoDTO);

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='monitoramento_servico_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('monitoramento_servico_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('monitoramento_servico_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='monitoramento_servico_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('monitoramento_servico_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('monitoramento_servico_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('monitoramento_servico_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('monitoramento_servico_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('monitoramento_servico_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('monitoramento_servico_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('monitoramento_servico_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=monitoramento_servico_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=monitoramento_servico_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir && $objMonitoramentoServicoDTO->getStrStaTipo() == MonitoramentoServicoRN::$TM_DETALHADO){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=monitoramento_servico_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='monitoramento_servico_reativar'){ */
      $strSumarioTabela = 'Tabela de Serviços Acessados.';
      $strCaptionTabela = 'Serviços Acessados';
    /* }else{
      $strSumarioTabela = 'Tabela de Monitoramento de Serviços Inativos.';
      $strCaptionTabela = 'Monitoramento de Serviços Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';


    $strResultado .= '<tr>';
    if ($objMonitoramentoServicoDTO->getStrStaTipo()==MonitoramentoServicoRN::$TM_DETALHADO) {
      if ($bolCheck) {
        $strResultado .= '<th class="infraTh" width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      }

      $strResultado .= '<th class="infraTh" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objMonitoramentoServicoDTO, 'Data/Hora', 'Acesso', $arrObjMonitoramentoServicoDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objMonitoramentoServicoDTO, 'Tempo', 'TempoExecucao', $arrObjMonitoramentoServicoDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh">Detalhes</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Ações</th>' . "\n";
    }else{
      $strResultado .= '<th class="infraTh" width="15%">Sistema</th>' . "\n";
      $strResultado .= '<th class="infraTh">Serviço</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="20%">Operação</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objMonitoramentoServicoDTO, 'Quantidade', 'Total', $arrObjMonitoramentoServicoDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objMonitoramentoServicoDTO, 'Tempo Médio', 'TempoMedio', $arrObjMonitoramentoServicoDTO) . '</th>' . "\n";
    }
    $strResultado .= '</tr>' . "\n";

    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($objMonitoramentoServicoDTO->getStrStaTipo()==MonitoramentoServicoRN::$TM_DETALHADO) {
        if ($bolCheck) {
          $strResultado .= '<td valign="top">' . PaginaSEI::getInstance()->getTrCheck($i, $arrObjMonitoramentoServicoDTO[$i]->getDblIdMonitoramentoServico(), $arrObjMonitoramentoServicoDTO[$i]->getNumIdServico()) . '</td>';
        }

        $strResultado .= '<td align="center" valign="top">' . $arrObjMonitoramentoServicoDTO[$i]->getDthAcesso() . '</td>';
        $strResultado .= '<td align="center" valign="top">' . round($arrObjMonitoramentoServicoDTO[$i]->getDblTempoExecucao() / 1000, 3) . 's</td>';

        $strResultado .= '<td>' . "\n";
        $strResultado .= '<b>Sistema: </b>' . PaginaSEI::getInstance()->tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrNomeUsuarioServico());
        $strResultado .= '<br /><b>Serviço: </b>' . PaginaSEI::getInstance()->tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrIdentificacaoServico());
        $strResultado .= '<br /><b>Operação: </b>' . PaginaSEI::getInstance()->tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrOperacao());
        $strResultado .= '<br /><b>IP de Acesso: </b>' . PaginaSEI::getInstance()->tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrIpAcesso());
        $strResultado .= '<br /><b>Servidor: </b>' . PaginaSEI::getInstance()->tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrServidor());
        $strResultado .= '<br /><b>User Agent: </b>' . PaginaSEI::getInstance()->tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrUserAgent());
        $strResultado .= '</td>' . "\n";

        $strResultado .= '<td align="center" valign="top">';

        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjMonitoramentoServicoDTO[$i]->getDblIdMonitoramentoServico());

        if ($bolAcaoConsultar) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=monitoramento_servico_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_monitoramento_servico=' . $arrObjMonitoramentoServicoDTO[$i]->getDblIdMonitoramentoServico()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeConsultar() . '" title="Consultar Monitoramento de Serviço" alt="Consultar Monitoramento de Serviço" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoAlterar) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=monitoramento_servico_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_monitoramento_servico=' . $arrObjMonitoramentoServicoDTO[$i]->getDblIdMonitoramentoServico()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeAlterar() . '" title="Alterar Monitoramento de Serviço" alt="Alterar Monitoramento de Serviço" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
          $strId = $arrObjMonitoramentoServicoDTO[$i]->getDblIdMonitoramentoServico();
          $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjMonitoramentoServicoDTO[$i]->getDthAcesso());
        }
        /*
              if ($bolAcaoDesativar){
                $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Monitoramento de Serviço" alt="Desativar Monitoramento de Serviço" class="infraImg" /></a>&nbsp;';
              }

              if ($bolAcaoReativar){
                $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Monitoramento de Serviço" alt="Reativar Monitoramento de Serviço" class="infraImg" /></a>&nbsp;';
              }
         */

        if ($bolAcaoExcluir) {
          $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeExcluir() . '" title="Excluir Monitoramento de Serviço" alt="Excluir Monitoramento de Serviço" class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '</td>'."\n";
      }else{
        $strResultado .= '<td align="center" valign="top"><a alt="'.PaginaSEI::tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrNomeUsuarioServico()).'" title="'.PaginaSEI::tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrNomeUsuarioServico()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrSiglaUsuarioServico()).'</a></td>';
        $strResultado .= '<td align="center" valign="top">' . PaginaSEI::getInstance()->tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrIdentificacaoServico()) . '</td>';
        $strResultado .= '<td align="center" valign="top">' . PaginaSEI::getInstance()->tratarHTML($arrObjMonitoramentoServicoDTO[$i]->getStrOperacao()) . '</td>';
        $strResultado .= '<td align="center" valign="top">' . $arrObjMonitoramentoServicoDTO[$i]->getNumTotal() . '</td>';
        $strResultado .= '<td align="center" valign="top">' . round($arrObjMonitoramentoServicoDTO[$i]->getNumTempoMedio()/1000, 3 ) . 's</td>';
      }

      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'monitoramento_servico_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelSistema = UsuarioINT::montarSelectSiglaSistema('null','&nbsp',$numIdUsuarioServico);
  $strItensSelServico = ServicoINT::montarSelectIdentificacao('null','&nbsp',$numIdServico,$numIdUsuarioServico);
  $strItensSelOperacao = OperacaoServicoINT::montarSelectOperacaoMonitoramento('null','&nbsp',$strOperacao);
  $strItensSelStaTipo = MonitoramentoServicoINT::montarSelectStaTipo('null','&nbsp',$objMonitoramentoServicoDTO->getStrStaTipo());

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

#lblSistema {position:absolute;left:0%;top:0%;width:20%}
#selSistema {position:absolute;left:0%;top:12%;width:20%;}

#lblServico {position:absolute;left:23%;top:0%;width:35%}
#selServico {position:absolute;left:23%;top:12%;width:35%;}

#lblOperacao {position:absolute;left:61%;top:0%;width:35%}
#selOperacao {position:absolute;left:61%;top:12%;width:35%;}

#lblDthInicial {position:absolute;left:0%;top:30%;}
#txtDthInicialMon {position:absolute;left:0%;top:42%;width:17%;}
#imgCalDthInicial {position:absolute;left:18%;top:42%;}

#lblDthFinal {position:absolute;left:21%;top:42%;}
#txtDthFinalMon {position:absolute;left:23%;top:42%;width:17%;}
#imgCalDthFinal {position:absolute;left:41%;top:42%;}

#lblStaTipo {position:absolute;left:0%;top:60%;width:20%}
#selStaTipo {position:absolute;left:0%;top:72%;width:20%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='monitoramento_servico_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Monitoramento de Serviço \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMonitoramentoServicoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMonitoramentoServicoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Monitoramento de Serviço selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Monitoramentos de Serviços selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMonitoramentoServicoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmMonitoramentoServicoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Monitoramento de Serviço \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMonitoramentoServicoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMonitoramentoServicoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Monitoramento de Serviço selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Monitoramentos de Serviços selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMonitoramentoServicoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmMonitoramentoServicoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Monitoramento de Serviço \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmMonitoramentoServicoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMonitoramentoServicoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Monitoramento de Serviço selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Monitoramentos de Serviços selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmMonitoramentoServicoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmMonitoramentoServicoLista').submit();
  }
}
<? } ?>

function onSubmitForm(){

  if (!infraSelectSelecionado('selStaTipo')) {
    alert('Selecione tipo da pesquisa.');
    document.getElementById('selStaTipo').focus();
    return false;
  }

  return true;
}
//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMonitoramentoServicoLista" method="post" onsubmit="return onSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('15em');
  ?>

  <label id="lblSistema" for="selSistema" accesskey="" class="infraLabelOpcional">Sistema:</label>
  <select id="selSistema" name="selSistema" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelSistema?>
  </select>

  <label id="lblServico" for="selServico" accesskey="" class="infraLabelOpcional">Serviço:</label>
  <select id="selServico" name="selServico" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelServico?>
  </select>
  
  <label id="lblOperacao" for="selOperacao" accesskey="" class="infraLabelOpcional">Operação:</label>
  <select id="selOperacao" name="selOperacao" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelOperacao?>
  </select>

  <label id="lblDthInicial" for="txtDthInicialMon" accesskey="" class="infraLabelOpcional" >Período:</label>
  <input type="text" id="txtDthInicialMon" name="txtDthInicialMon" onkeypress="return infraMascara(this, event,'##/##/#### ##:##')" class="infraText" value="<?=PaginaSEI::getInstance()->tratarHTML($dthInicial)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" id="imgCalDthInicial" title="Selecionar Data/Hora Inicial" alt="Selecionar Data/Hora Inicial" class="infraImg" onclick="infraCalendario('txtDthInicialMon',this,true,'<?=InfraData::getStrDataAtual().' 00:00'?>');" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDthFinal" for="txtDthFinalMon" accesskey="" class="infraLabelOpcional" >a</label>
  <input type="text" id="txtDthFinalMon" name="txtDthFinalMon" onkeypress="return infraMascara(this, event,'##/##/#### ##:##')" class="infraText" value="<?=PaginaSEI::getInstance()->tratarHTML($dthFinal)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" id="imgCalDthFinal" title="Selecionar Data/Hora Final" alt="Selecionar Data/Hora Final" class="infraImg" onclick="infraCalendario('txtDthFinalMon',this,true,'<?=InfraData::getStrDataAtual().' 23:59'?>');" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblStaTipo" for="selStaTipo" accesskey="" class="infraLabelObrigatorio">Tipo:</label>
  <select id="selStaTipo" name="selStaTipo" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelStaTipo?>
  </select>

  <input type="hidden" id="hdnFlagMonitoramento" name="hdnFlagMonitoramento" value="1" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>