<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/08/2010 - criado por jonatas_db
*
* Versão do Gerador de Código: 1.30.0
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

  PaginaSEI::getInstance()->prepararSelecao('retorno_programado_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  
  if (isset($_GET['data_inicial']) && isset($_GET['data_final'])){
  	PaginaSEI::getInstance()->salvarCampo('data_inicial',$_GET['data_inicial']);
  	PaginaSEI::getInstance()->salvarCampo('data_final',$_GET['data_final']);
  	PaginaSEI::getInstance()->salvarCampo('data_atual','');
  }else if (isset($_GET['data_atual'])){
  	PaginaSEI::getInstance()->salvarCampo('data_inicial','');
  	PaginaSEI::getInstance()->salvarCampo('data_final','');
  	PaginaSEI::getInstance()->salvarCampo('data_atual',$_GET['data_atual']);
  }
  
  switch($_GET['acao']){
    case 'retorno_programado_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados('Aguardando');

        $arrObjRetornoProgramadoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objRetornoProgramadoDTO = new RetornoProgramadoDTO();
          $objRetornoProgramadoDTO->setNumIdRetornoProgramado($arrStrIds[$i]);
          $arrObjRetornoProgramadoDTO[] = $objRetornoProgramadoDTO;
        }
        $objRetornoProgramadoRN = new RetornoProgramadoRN();
        $objRetornoProgramadoRN->excluir($arrObjRetornoProgramadoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'retorno_programado_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar retorno','Selecionar retornos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='retorno_programado_cadastrar'){
        if (isset($_GET['id_retorno_programado'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_retorno_programado']);
        }
      }
      break;

    case 'retorno_programado_listar':
    	
      $strTitulo = 'Retorno Programado';
      
      $objRetornoProgramadoDTO	= new RetornoProgramadoDTO();
		  $objRetornoProgramadoRN 	= new RetornoProgramadoRN();
		  
		  $dtaInicial = PaginaSEI::getInstance()->recuperarCampo('data_inicial');
		  $dtaFinal = PaginaSEI::getInstance()->recuperarCampo('data_final');
		  $dtaAtual = PaginaSEI::getInstance()->recuperarCampo('data_atual');

      $bolFlagTodoMes = false;
		  if ($dtaInicial!='' && $dtaFinal!=''){
        $bolFlagTodoMes = true;
        $dtaAtual = $dtaInicial;
      }else if ($dtaAtual == ''){
        $dtaAtual = InfraData::getStrDataAtual();
      }

		  $numDiaInicioMes =  '01'.substr($dtaAtual,2);
		  $numDiaFinalMes = InfraData::obterUltimoDiaMes(substr($dtaAtual,3,2),substr($dtaAtual,6,4)).substr($dtaAtual,2);
		  
		  // Faz a busca no banco dos registros para todo o mês
			$objRetornoProgramadoDTO->setDtaInicial($numDiaInicioMes);
			$objRetornoProgramadoDTO->setDtaFinal($numDiaFinalMes);
      //$objRetornoProgramadoDTO->setNumIdAtividadeRetorno(null);

		  $arrObjRetornoProgramadoDTOCalendario = $objRetornoProgramadoRN->listarDevolucoesEntregas($objRetornoProgramadoDTO);

      if ($bolFlagTodoMes){
        $arrObjRetornoProgramadoDTO = $arrObjRetornoProgramadoDTOCalendario;
      }else {
        $arrObjRetornoProgramadoDTO = InfraArray::filtrarArrInfraDTO($arrObjRetornoProgramadoDTOCalendario, 'Programada', $dtaAtual);
      }

      $numRegistros = count($arrObjRetornoProgramadoDTO);

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $strResultado = '';

	if ($numRegistros > 0) {

    //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('retorno_programado_alterar');

    $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('retorno_programado_excluir');
    if ($bolAcaoExcluir) {
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=retorno_programado_excluir&acao_origem='.$_GET['acao']);
    }

    $strCheckAguardando = PaginaSEI::getInstance()->getThCheck('', 'Aguardando');
    $strCheckDevolver = PaginaSEI::getInstance()->getThCheck('', 'Devolver');

    $strAguardando = '';
    $strDevolver = '';

    $contadorAguardando = 0;
    $contadorDevolver = 0;
    for ($i = 0; $i < $numRegistros; $i++) {

      if ($arrObjRetornoProgramadoDTO[$i]->getNumDiasPrazo() < 0) {
        $strCssTr = '<tr class="processoAtrasado">';
      } else {
        $strCssTr = '<tr class="infraTrClara">';
      }

      $objProtocoloDTO = $arrObjRetornoProgramadoDTO[$i]->getObjProtocoloDTO();
      $strCorProcesso = ' class="'.($objProtocoloDTO->getStrSinAberto() == 'S' ? 'protocoloAberto' : 'protocoloFechado').'"';

      if ($arrObjRetornoProgramadoDTO[$i]->getNumIdUnidadeEnvio() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()) {

        $strAguardando .= $strCssTr;

        $strAguardando .= '<td style="display:none;">'.PaginaSEI::getInstance()->getTrCheck($contadorAguardando++, $arrObjRetornoProgramadoDTO[$i]->getNumIdRetornoProgramado(), $arrObjRetornoProgramadoDTO[$i]->getDtaProgramada(), 'N', 'Aguardando').'</td>';
        $strAguardando .= '<td align="center"><a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$objProtocoloDTO->getDblIdProtocolo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento()).'" title="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento()).'" '.$strCorProcesso.'>'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrProtocoloFormatado()).'</a></td>'."\n";
        $strAguardando .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjRetornoProgramadoDTO[$i]->getStrDescricaoUnidadeRetorno()).'" title="'.PaginaSEI::tratarHTML($arrObjRetornoProgramadoDTO[$i]->getStrDescricaoUnidadeRetorno()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjRetornoProgramadoDTO[$i]->getStrSiglaUnidadeRetorno()).'</a></td>';
        $strAguardando .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjRetornoProgramadoDTO[$i]->getDtaProgramada()).'</td>';
        $dthAberturaAtividadeRetorno = '&nbsp;';
        if (!InfraString::isBolVazia($arrObjRetornoProgramadoDTO[$i]->getDthAberturaAtividadeRetorno())) {
          $dthAberturaAtividadeRetorno = PaginaSEI::tratarHTML($arrObjRetornoProgramadoDTO[$i]->getDthAberturaAtividadeRetorno());
        }
        $strAguardando .= '<td align="center">'.$dthAberturaAtividadeRetorno.'</td>';
        $strAguardando .= '<td align="center">'.$arrObjRetornoProgramadoDTO[$i]->getNumDiasPrazo().'</td>';

        $strAguardando .= '<td align="center">';

        if ($bolAcaoAlterar && $dthAberturaAtividadeRetorno == '&nbsp;') {
          $strAguardando .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=retorno_programado_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_retorno_programado='.$arrObjRetornoProgramadoDTO[$i]->getNumIdRetornoProgramado()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar retorno" alt="Alterar retorno" class="infraImg" /></a>&nbsp;';
        }
        if ($bolAcaoExcluir) {
          $strId = $arrObjRetornoProgramadoDTO[$i]->getNumIdRetornoProgramado();
          $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjRetornoProgramadoDTO[$i]->getStrSiglaUnidadeRetorno().' em '.$arrObjRetornoProgramadoDTO[$i]->getDtaProgramada());
        }
        if ($bolAcaoExcluir && $arrObjRetornoProgramadoDTO[$i]->getNumIdAtividadeRetorno() == null) {
          $strAguardando .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir retorno" alt="Excluir retorno" class="infraImg" /></a>&nbsp;';
        }
        $strAguardando .= '</td>';

        $strAguardando .= '</tr>'."\n";

      } else {


        $strDevolver .= $strCssTr;
        $strDevolver .= '<td style="display:none;">'.PaginaSEI::getInstance()->getTrCheck($contadorDevolver++, $arrObjRetornoProgramadoDTO[$i]->getNumIdRetornoProgramado(), $arrObjRetornoProgramadoDTO[$i]->getDtaProgramada(), 'N', 'Devolver').'</td>';
        $strDevolver .= '<td align="center"><a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$objProtocoloDTO->getDblIdProtocolo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.$objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento().'" title="'.$objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento().'" '.$strCorProcesso.'>'.$objProtocoloDTO->getStrProtocoloFormatado().'</a></td>'."\n";
        $strDevolver .= '<td align="center"><a alt="'.$arrObjRetornoProgramadoDTO[$i]->getStrDescricaoUnidadeEnvio().'" title="'.$arrObjRetornoProgramadoDTO[$i]->getStrDescricaoUnidadeEnvio().'" class="ancoraSigla">'.$arrObjRetornoProgramadoDTO[$i]->getStrSiglaUnidadeEnvio().'</a></td>';
        $strDevolver .= '<td align="center">'.$arrObjRetornoProgramadoDTO[$i]->getDtaProgramada().'</td>';
        $dthAberturaAtividadeRetorno = '&nbsp;';
        if (!InfraString::isBolVazia($arrObjRetornoProgramadoDTO[$i]->getDthAberturaAtividadeRetorno())) {
          $dthAberturaAtividadeRetorno = $arrObjRetornoProgramadoDTO[$i]->getDthAberturaAtividadeRetorno();
        }
        $strDevolver .= '<td align="center">'.$dthAberturaAtividadeRetorno.'</td>';
        $strDevolver .= '<td align="center">'.$arrObjRetornoProgramadoDTO[$i]->getNumDiasPrazo().'</td>';
        $strDevolver .= '</tr>'."\n";
      }
    }


    if ($contadorAguardando) {
      $strResultado .= '<label class="textoSubtitulo">Processos aguardando retorno de outras unidades</label>'."\n".
          '<hr class="linhaSubtitulo"/>'."\n".
          '<table width="99%" class="infraTable" summary="Tabela de processos aguardando retorno de outras unidades">'."\n".
          '<caption class="infraCaption">'.$contadorAguardando.' registro'.($contadorAguardando > 1 ? '(s)' : '').':'.'</caption>'.
          '<tr>'.
          '<th class="infraTh" width="1%" style="display:none;">'.$strCheckAguardando.'</th>'."\n".
          '<th class="infraTh" width="28%">Processo</th>'."\n".
          '<th class="infraTh">Unidade</th>'."\n".
          '<th class="infraTh">Data Programada</th>'."\n".
          '<th class="infraTh">Data de Retorno Efetiva</th>'."\n".
          '<th class="infraTh">Prazo Restante</th>'."\n".
          '<th class="infraTh">Ações</th>'."\n".
          '</tr>'."\n".
          $strAguardando.
          '</table><br/><br/>'.
          '<br />'.
          '<br />';
    }


    if ($contadorDevolver) {
      $strResultado .= '<label class="textoSubtitulo">Processos para devolver</label>'.
          '<hr class="linhaSubtitulo" />'.
          '<table width="99%" class="infraTable" summary="Tabela de processos para devolver">'."\n".
          '<caption class="infraCaption">'.$contadorDevolver.' registro'.($contadorDevolver > 1 ? '(s)' : '').':'.'</caption>'.
          '<tr>'.
          '<th class="infraTh" width="1%" style="display:none;">'.$strCheckDevolver.'</th>'."\n".
          '<th class="infraTh" width="28%">Processo</th>'."\n".
          '<th class="infraTh">Unidade</th>'."\n".
          '<th class="infraTh">Data Programada</th>'."\n".
          '<th class="infraTh">Data de Retorno Efetiva</th>'."\n".
          '<th class="infraTh">Prazo Restante</th>'."\n".
          '</tr>'."\n".
          $strDevolver.
          '</table><br/><br/>';
    }
  }

  // Bloco do Calendario
	$strResultadoCalendario = RetornoProgramadoINT::gerarCalendario($dtaAtual,$bolFlagTodoMes,$numDiaInicioMes, $numDiaFinalMes, $arrObjRetornoProgramadoDTOCalendario);    
  
  
  //if ($_GET['acao'] == 'retorno_programado_selecionar'){
  //  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  //}else{
  //  $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  //}
  
  
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
div.infraAreaTabela{width:70%;float:left;padding:.5em 3em 0 0;}

#divCalendario {float:left;border:2px solid #BBBBBB;width:16em;border-radius:.4rem;}
#divCalendario table{width:100%;}
#divCalendario table td {border-radius:.2rem;}
#divCalendario a {text-decoration:none;font-size:12px;}
#divCalendario .diaFimDeSemana{border:1px solid white; background-color:white;}
#divCalendario .diaConteudo{border:1px solid white; background-color:#FFFFAE !important;}
#divCalendario .diaHoje { border:1px dotted black !important;}
td.diaHoje a { font-weight:bold !important;}
#divCalendario .diaAtual { border:1px solid black !important;}
#divCalendario .diaUtil{border:1px solid white; background-color:#CCCCCC;}
#divCalendario .diaAtrasado{border:1px solid white; background-color:#F59F9F;}
label.textoSubtitulo{font-size:1.6em;}
label.textoNenhum{font-size:1.2em;}
hr.linhaSubtitulo{width:100%;color:black;background-color:black;height:.2em;}
tr.processoAtrasado {background-color: #F59F9F}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  infraEfeitoTabelas();
}

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do retorno programado da unidade "+desc+"?")){
    document.getElementById('hdnAguardandoItemId').value=id;
    document.getElementById('frmRetornoProgramadoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmRetornoProgramadoLista').submit();
  }
}

<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmRetornoProgramadoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

  PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros, true, '', null, 'Aguardando');

	echo $strResultadoCalendario;

  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>