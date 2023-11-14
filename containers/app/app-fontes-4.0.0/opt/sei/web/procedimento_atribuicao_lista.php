<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/02/2011 - criado por mga
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_usuario_atribuicao'));

  $objAtribuirDTO = new AtribuirDTO();
  
  switch($_GET['acao']){
  	
  	/*
    case 'procedimento_atribuicao_trocar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjVocativoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objVocativoDTO = new VocativoDTO();
          $objVocativoDTO->setNumIdVocativo($arrStrIds[$i]);
          $arrObjVocativoDTO[] = $objVocativoDTO;
        }
        $objVocativoRN = new VocativoRN();
        $objVocativoRN->excluirRN0311($arrObjVocativoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
			header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;
    */

    case 'procedimento_atribuicao_alterar':

      $strTitulo = 'Alterar Atribuição';

      $objAtividadeRN = new AtividadeRN();
      
      $objAtribuirDTO = new AtribuirDTO();
      $objAtribuirDTO->setNumIdUsuarioAtribuicao($_POST['selAtribuicao']);
      $arrStrIdProtocolo = PaginaSEI::getInstance()->getArrStrItensSelecionados();
      
      try{
        	
	      $arrObjProtocoloDTO = array();     
 	      foreach($arrStrIdProtocolo as $dlbIdProtocolo){
 	      	$dto = new ProtocoloDTO();
 	        $dto->setDblIdProtocolo($dlbIdProtocolo);	        
 	        $arrObjProtocoloDTO[] = $dto;
 	      }
          
 	      $objAtribuirDTO->setArrObjProtocoloDTO($arrObjProtocoloDTO);	      
        $objAtividadeRN->atribuirRN0985($objAtribuirDTO);            
       	header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao'].PaginaSEI::montarAncora($arrStrIdProtocolo)));
        die;
                		         
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
  	
    case 'procedimento_atribuicao_listar':
      $strTitulo = 'Atribuições de Processos';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $numRegistros = 0;
  
  $objUsuarioDTO = new UsuarioDTO();
  $objUsuarioDTO->setBolExclusaoLogica(false);
  $objUsuarioDTO->retStrSigla();
  $objUsuarioDTO->setNumIdUsuario($_GET['id_usuario_atribuicao']);

  $objUsuarioRN = new UsuarioRN();
  $objUsuarioDTO = $objUsuarioRN->consultarRN0489($objUsuarioDTO);

  if ($objUsuarioDTO==null){
    throw new InfraException('Usuário não encontrado.');
  }
  
  
 	$strTitulo .= ' - '.$objUsuarioDTO->getStrSigla();
   
	$objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
	$objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
	$objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
 	$objPesquisaPendenciaDTO->setStrStaTipoAtribuicao(AtividadeRN::$TA_ESPECIFICAS);
 	$objPesquisaPendenciaDTO->setNumIdUsuarioAtribuicao($_GET['id_usuario_atribuicao']);
	$objPesquisaPendenciaDTO->setStrStaEstadoProcedimento(array(ProtocoloRN::$TE_NORMAL,ProtocoloRN::$TE_PROCEDIMENTO_BLOQUEADO),InfraDTO::$OPER_IN);
	$objPesquisaPendenciaDTO->setStrSinAnotacoes('S');
  $objPesquisaPendenciaDTO->setStrSinRetornoProgramado('S');
  $objPesquisaPendenciaDTO->setStrSinCredenciais('S');
  $objPesquisaPendenciaDTO->setStrSinSituacoes('S');
  $objPesquisaPendenciaDTO->setStrSinMarcadores('S');
  $objPesquisaPendenciaDTO->setStrSinInteressados('N');

	$objAtividadeRN = new AtividadeRN();
	$arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);
  $numRegistros = count($arrObjProcedimentoDTO);

  
  if ($numRegistros > 0){

    $arrRetIconeIntegracao = ProcedimentoINT::montarIconesIntegracaoControleProcessos($arrObjProcedimentoDTO);

  	$bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('procedimento_atribuicao_alterar');
    $bolAcaoRegistrarAnotacao = SessaoSEI::getInstance()->verificarPermissao('anotacao_registrar');
    $bolAcaoAndamentoSituacaoGerenciar = SessaoSEI::getInstance()->verificarPermissao('andamento_situacao_gerenciar');
    $bolAcaoAndamentoMarcadorGerenciar = SessaoSEI::getInstance()->verificarPermissao('andamento_marcador_gerenciar');
  	
    if ($bolAcaoAlterar){
      $arrComandos[] = '<button type="button" accesskey="A" id="btnSalvar" value="Salvar" onclick="acaoAlteracaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strLinkAlterar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_atribuicao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
    }
  	
    //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Processos.';
    $strCaptionTabela = 'Processos';

    $strResultado .= '<table width="99%" id="tblProcessosDetalhado" class="infraTable tabelaProcessos" summary="'.$strSumarioTabela.'">'."\n"; //80
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="tituloProcessos" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="tituloProcessos" width="10%">&nbsp;</th>'."\n";
    $strResultado .= '<th class="tituloProcessos" width="20%">Processo</th>'."\n";
    $strResultado .= '<th class="tituloProcessos">Tipo</th>'."\n";
    //$strResultado .= '<th class="tituloProcessos">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strResultado .= "\n".'<tr class="infraTrClara">';

      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjProcedimentoDTO[$i]->getDblIdProcedimento(),$arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</td>'."\n";

      //$strResultado .= '<td align="center"> <a onclick="abrirProcesso(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjProcedimentoDTO[$i]->getDblIdProcedimento())).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.$arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento().'" title="'.$arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento().'">'.$arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado().'</a></td>'."\n";
      $strResultado .= '<td align="center">';
      $strResultado .= AnotacaoINT::montarIconeAnotacao($arrObjProcedimentoDTO[$i]->getObjAnotacaoDTO(),$bolAcaoRegistrarAnotacao, $arrObjProcedimentoDTO[$i]->getDblIdProcedimento(),'&id_usuario_atribuicao='.$_GET['id_usuario_atribuicao']);

      $arrObjAtividadeDTO = $arrObjProcedimentoDTO[$i]->getArrObjAtividadeDTO();
      if (InfraArray::contar($arrObjAtividadeDTO)) {
        $strResultado .= ProcedimentoINT::montarIconeVisualizacao($arrObjAtividadeDTO[0]->getNumTipoVisualizacao(), $arrObjProcedimentoDTO[$i], $arrRetIconeIntegracao, $bolAcaoAndamentoSituacaoGerenciar, $bolAcaoAndamentoMarcadorGerenciar);
      }

      $strResultado .= '</td>'."\n";

      $strResultado .= '<td align="center"> <a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjProcedimentoDTO[$i]->getDblIdProcedimento()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" alt="'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).'" title="'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).'" class="protocoloNormal" style="font-size:1em !important;">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</a></td>'."\n";
      $strResultado .= '<td align="left">'.PaginaSEI::tratarHTML($arrObjProcedimentoDTO[$i]->getStrNomeTipoProcedimento()).'</td>'."\n";
      
      /*
      $strResultado .= '<td align="center">';
      if ($bolAcaoAlterar){
        $strResultado .= '<a href="#ID-'.$arrObjProcedimentoDTO[$i]->getDblIdProcedimento().'"  onclick="acaoAlterar(\''.$arrObjProcedimentoDTO[$i]->getDblIdProcedimento().'\',\''.$arrObjProcedimentoDTO[$i]->getStrProtocoloProcedimentoFormatado().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Atribuição" alt="Alterar Atribuição" class="infraImg" /></a>&nbsp;';
      }
      $strResultado .= '</td>'."\n";
      */
      
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>'."\n";
  }
  
  $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_procedimento'])).'\'" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

  $strItensSelAtribuicao = UsuarioINT::montarSelectPorUnidadeOutros('null', '&nbsp;', $_POST['selAtribuicao'], $_GET['id_usuario_atribuicao']);
  
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
#lblAtribuicao {position:absolute;left:0%;top:0%;width:40%;}
#selAtribuicao {position:absolute;left:0%;top:40%;width:40%;}

table.tabelaProcessos {
background-color:white;
border:0px solid white;
border-spacing:0;
}

table.tabelaProcessos tr{
margin:0;
border:0;
padding:0;
}

th.tituloProcessos{
font-size:1em;
font-weight: bold;
text-align: center;
color: #000;
background-color: #dfdfdf;
border-spacing: 0;
}

#tblProcessosDetalhado td{
border-bottom:1px dotted #666;
padding:.3em;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  //setTimeout("document.getElementById('btnFechar').focus()", 50);
  infraEfeitoTabelas();
}

function abrirProcesso(link){
  document.getElementById('divInfraBarraComandosSuperior').style.visibility = 'hidden';
  document.getElementById('divInfraAreaTabela').style.visibility = 'hidden';
  infraOcultarMenuSistemaEsquema();
  document.getElementById('frmProcedimentoAtribuicaoLista').action = link;
  document.getElementById('frmProcedimentoAtribuicaoLista').submit();
}

<? if ($bolAcaoAlterar){ ?>
function acaoAlterar(id,desc){
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmProcedimentoAtribuicaoLista').action='<?=$strLinkAlterar?>';
  document.getElementById('frmProcedimentoAtribuicaoLista').submit();
}

function acaoAlteracaoMultipla(){
  
  if (!infraSelectSelecionado('selAtribuicao')){
    alert('Selecione um Usuário.');
    return;
  }

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum processo selecionado.');
    return;
  }
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmProcedimentoAtribuicaoLista').action='<?=$strLinkAlterar?>';
  document.getElementById('frmProcedimentoAtribuicaoLista').submit();
}
<? } ?>


<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProcedimentoAtribuicaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
 	<label id="lblAtribuicao" for="selAtribuicao" class="infraLabelOpcional">Atribuir para:</label>
  <select id="selAtribuicao" name="selAtribuicao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelAtribuicao?>
  </select>
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>