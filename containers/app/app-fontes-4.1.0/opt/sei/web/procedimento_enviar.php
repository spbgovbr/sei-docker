<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }

  if (isset($_GET['id_documento_assinado'])){
    $strParametros .= '&id_documento_assinado='.$_GET['id_documento_assinado'];
  }

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  $arrComandos = array();

  $bolEscolhaLivreUnidades = true;

  switch($_GET['acao']){
    
    case 'procedimento_enviar':
    	
    	$strVisualizar = 'style="visibility:hidden;"';
      $strTitulo = 'Enviar Processo';
      
      $objEnviarProcessoDTO = new EnviarProcessoDTO();

  	  $arrProtocolosOrigem = array();
  	  $arrAtividadesOrigem = array();
      
      if ($_GET['acao_origem']=='arvore_visualizar' || $_GET['acao_origem']=='procedimento_controlar'){
            
      	if ($_GET['acao_origem']=='arvore_visualizar'){
          $arrProtocolosOrigem[] = $_GET['id_procedimento'];
      	}else{
          $arrProtocolosOrigem = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'),PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));

          if (count($arrProtocolosOrigem)==0){
            throw new InfraException('Nenhum processo selecionado.');
          }
          
      	}

      	$objAtividadeRN = new AtividadeRN();
      	
        $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
        $objPesquisaPendenciaDTO->setDblIdProtocolo($arrProtocolosOrigem);
        $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);

        $arrObjAtividadeDTO = array();
        foreach($arrObjProcedimentoDTO as $objProcedimentoDTO){
          $arrObjAtividadeDTO = array_merge($arrObjAtividadeDTO,$objProcedimentoDTO->getArrObjAtividadeDTO()); 
        }
        
        $arrAtividadesOrigem = InfraArray::converterArrInfraDTO($arrObjAtividadeDTO,'IdAtividade');
        
     	}else {
     	  
     	  if ($_POST['hdnIdProtocolos']!=''){
     	    $arrProtocolosOrigem = explode(',',$_POST['hdnIdProtocolos']);
     	  }
     	  
     	  if ($_POST['hdnIdAtividades']!=''){
     	  $arrAtividadesOrigem = explode(',',$_POST['hdnIdAtividades']);
     	  }
     	  
     	}

      $objAtividadeRN = new AtividadeRN();

     	//Monta atividades de origem uma atividade para cada recebida
      $arrObjAtividadeDTOOrigem = array();
      foreach($arrAtividadesOrigem as $numIdAtividade){
      	$objAtividadeDTO = new AtividadeDTO();
        $objAtividadeDTO->setNumIdAtividade($numIdAtividade);
        $arrObjAtividadeDTOOrigem[] = $objAtividadeDTO;         
      }
      $objEnviarProcessoDTO->setArrAtividadesOrigem($arrObjAtividadeDTOOrigem);
      
      //Monta atividades que serão lançadas
      //uma atividade por protocolo/unidade ou protocolo/usuario/unidade
      $arrObjAtividadeDTO = array(); 	      

      if (isset($_POST['hdnInfraItensSelecionados'])){
        $arrUnidades = PaginaSEI::getInstance()->getArrStrItensSelecionados();
      }else {
        $arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);
      }

      foreach($arrProtocolosOrigem as $dblIdProtocolo){
      	foreach($arrUnidades as $numIdUnidade){
      		$objAtividadeDTO = new AtividadeDTO();
      		$objAtividadeDTO->setDblIdProtocolo($dblIdProtocolo);
      		$objAtividadeDTO->setNumIdUsuario(null);
      		$objAtividadeDTO->setNumIdUsuarioOrigem(SessaoSEI::getInstance()->getNumIdUsuario());
      		$objAtividadeDTO->setNumIdUnidade($numIdUnidade);
      		$objAtividadeDTO->setNumIdUnidadeOrigem(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      		$arrObjAtividadeDTO[] = $objAtividadeDTO;
      	}
      }
     	
      $objEnviarProcessoDTO->setArrAtividades($arrObjAtividadeDTO);
      $objEnviarProcessoDTO->setStrSinManterAberto(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinManterAberto']));
      $objEnviarProcessoDTO->setStrSinEnviarEmailNotificacao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEnviarEmailNotificacao']));		        
      $objEnviarProcessoDTO->setStrSinRemoverAnotacoes(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinRemoverAnotacoes']));
      $objEnviarProcessoDTO->setDtaPrazoRetornoProgramado($_POST['txtPrazoRetornoProgramado']);
      $objEnviarProcessoDTO->setNumDiasRetornoProgramado($_POST['txtDiasRetornoProgramado']);
      $objEnviarProcessoDTO->setStrSinDiasUteisRetornoProgramado(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinDiasUteisRetornoProgramado']));
      $objEnviarProcessoDTO->setDtaPrazoReaberturaProgramada($_POST['txtPrazoReaberturaProgramada']);
      $objEnviarProcessoDTO->setNumDiasReaberturaProgramada($_POST['txtDiasReaberturaProgramada']);
      $objEnviarProcessoDTO->setStrSinDiasUteisReaberturaProgramada(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinDiasUteisReaberturaProgramada']));

	    if (isset($_POST['sbmEnviar'])){
	      try{	      		        

	        $strAncora = '';
	        if (PaginaSEI::getInstance()->getAcaoRetorno()=='procedimento_controlar'){
            $strAncora = PaginaSEI::montarAncora($arrProtocolosOrigem);
          }

	        $objAtividadeRN->enviarRN0023($objEnviarProcessoDTO);
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&atualizar_arvore=1'.$strParametros.$strAncora));
          die;

	      }catch(Exception $e){
	        PaginaSEI::getInstance()->processarExcecao($e);
	      }
	    }
	    
	    break;
	
	    default:
	      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkGrupo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_unidade_selecionar&tipo_selecao=2&id_object=objLupaGrupo');
  $strLinkTramitacao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_tramitacao_selecionar&tipo_selecao=2&id_object=objLupaGrupo&id_procedimento='.$arrProtocolosOrigem[0]);
  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_envio_processo');     	 
  $strLinkUnidadeSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_envio_processo&tipo_selecao=2&id_object=objLupaUnidades');
  $strItensSelProcedimentos = ProcedimentoINT::conjuntoCompletoFormatadoRI0903($arrProtocolosOrigem);
  
 	$strIdProtocolos = implode(',',$arrProtocolosOrigem);
 	$strIdAtividades = implode(',',$arrAtividadesOrigem);

  global $SEI_MODULOS;

  if (count($SEI_MODULOS)) {

    $objProtocoloDTO = new ProtocoloDTO();
    $objProtocoloDTO->retDblIdProtocolo();
    $objProtocoloDTO->retStrProtocoloFormatado();
    $objProtocoloDTO->retNumIdTipoProcedimentoProcedimento();
    $objProtocoloDTO->retStrNomeTipoProcedimentoProcedimento();
    $objProtocoloDTO->retNumIdUnidadeGeradora();
    $objProtocoloDTO->setDblIdProtocolo($arrProtocolosOrigem, InfraDTO::$OPER_IN);

    $objProtocoloRN = new ProtocoloRN();
    $arrObjProtocoloDTO = $objProtocoloRN->listarRN0668($objProtocoloDTO);

    $arrObjProcedimentoAPI = array();
    foreach ($arrObjProtocoloDTO as $objProtocoloDTO) {
      $objProcedimentoAPI = new ProcedimentoAPI();
      $objProcedimentoAPI->setIdProcedimento($objProtocoloDTO->getDblIdProtocolo());
      $objProcedimentoAPI->setNumeroProtocolo($objProtocoloDTO->getStrProtocoloFormatado());
      $objProcedimentoAPI->setIdTipoProcedimento($objProtocoloDTO->getNumIdTipoProcedimentoProcedimento());
      $objProcedimentoAPI->setNomeTipoProcedimento($objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento());
      $objProcedimentoAPI->setIdUnidadeGeradora($objProtocoloDTO->getNumIdUnidadeGeradora());
      $arrObjProcedimentoAPI[] = $objProcedimentoAPI;
    }

    $objUnidadeRN = new UnidadeRN();
    $arrObjUnidadeDTOEnvio = array();
    foreach ($SEI_MODULOS as $seiModulo) {
      if (($arrObjUnidadeAPI = $seiModulo->executar('listarUnidadesEnvioProcesso', $arrObjProcedimentoAPI))!=null) {

        if (count($arrObjUnidadeAPI)) {
          $arrIdUnidadeEnvioModulo = array();
          foreach ($arrObjUnidadeAPI as $objUnidadeAPI) {
            $arrIdUnidadeEnvioModulo[] = $objUnidadeAPI->getIdUnidade();
          }

          $objUnidadeDTO = new UnidadeDTO();
          $objUnidadeDTO->retNumIdUnidade();
          $objUnidadeDTO->retStrSigla();
          $objUnidadeDTO->retStrDescricao();
          $objUnidadeDTO->retStrSiglaOrgao();
          $objUnidadeDTO->retStrDescricaoOrgao();
          $objUnidadeDTO->setNumIdUnidade($arrIdUnidadeEnvioModulo, InfraDTO::$OPER_IN);
          $arrObjUnidadeDTO = InfraArray::indexarArrInfraDTO($objUnidadeRN->listarRN0127($objUnidadeDTO), 'IdUnidade');

          $arrIdUnidadeErro = array();
          foreach ($arrIdUnidadeEnvioModulo as $numIdUnidadeEnvioModulo) {
            if (!isset($arrObjUnidadeDTO[$numIdUnidadeEnvioModulo])) {
              $arrIdUnidadeErro[] = $numIdUnidadeEnvioModulo;
            }else{
              $arrObjUnidadeDTOEnvio[$numIdUnidadeEnvioModulo] = $arrObjUnidadeDTO[$numIdUnidadeEnvioModulo];
            }
          }

          $numIdUnidadesErro = count($arrIdUnidadeErro);
          if ($numIdUnidadesErro) {
            if ($numIdUnidadesErro==1) {
              throw new InfraException('Unidade "'.$arrIdUnidadeErro[0].'" não localizada na lista de envio do módulo "'.$seiModulo->getNome().'".');
            }else{
              $strUnidadesErro = '';
              for ($i = 0; $i < $numIdUnidadesErro; $i++) {
                if ($i) {
                  $strUnidadesErro .= ($i == ($numIdUnidadesErro - 1)) ? ' e ' : ', ';
                }
                $strUnidadesErro .= $arrIdUnidadeErro[$i];
              }
              throw new InfraException('Unidades não localizadas na lista de envio do módulo "'.$seiModulo->getNome().'": '.$strUnidadesErro.'.');
            }
          }
        }
      }
    }

    $numRegistros = count($arrObjUnidadeDTOEnvio);

    if ($numRegistros){

      $bolEscolhaLivreUnidades = false;

      if (isset($arrObjUnidadeDTOEnvio[SessaoSEI::getInstance()->getNumIdUnidadeAtual()])){
        unset($arrObjUnidadeDTOEnvio[SessaoSEI::getInstance()->getNumIdUnidadeAtual()]);
        $numRegistros--;
      }

      $arrObjUnidadeDTOEnvio = array_values($arrObjUnidadeDTOEnvio);

      InfraArray::ordenarArrInfraDTO($arrObjUnidadeDTOEnvio, 'Sigla', InfraArray::$TIPO_ORDENACAO_ASC);

      $strResultado = '';

      $strSumarioTabela = 'Tabela de unidades disponíveis para envio.';
      $strCaptionTabela = 'unidades disponíveis para envio';

      $strResultado .= '<table width="81%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
      $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
      $strResultado .= '<th class="infraTh">Sigla</th>'."\n";
      $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
      $strResultado .= '<th class="infraTh">Órgão</th>'."\n";
      $strResultado .= '</tr>'."\n";

      $strCssTr='';
      for($i = 0;$i < $numRegistros; $i++){

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjUnidadeDTOEnvio[$i]->getNumIdUnidade(),UnidadeINT::formatarSiglaDescricao($arrObjUnidadeDTOEnvio[$i]->getStrSigla(),$arrObjUnidadeDTOEnvio[$i]->getStrDescricao())).'</td>';
        $strResultado .= '<td width="15%">'.$arrObjUnidadeDTOEnvio[$i]->getStrSigla().'</td>';
        $strResultado .= '<td>'.$arrObjUnidadeDTOEnvio[$i]->getStrDescricao().'</td>';
        $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjUnidadeDTOEnvio[$i]->getStrDescricaoOrgao()).'" title="'.PaginaSEI::tratarHTML($arrObjUnidadeDTOEnvio[$i]->getStrDescricaoOrgao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjUnidadeDTOEnvio[$i]->getStrSiglaOrgao()).'</a></td>';
        $strResultado .= '</tr>'."\n";
      }
      $strResultado .= '</table>';
    }
  }

  if ($bolEscolhaLivreUnidades){
    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->retNumIdOrgao();
    $objOrgaoDTO->retStrSigla();
    $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objOrgaoRN = new OrgaoRN();
    $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

    $strItensSelOrgaos = InfraINT::montarSelectArrInfraDTO('','Todos',$_POST['selOrgao'],$arrObjOrgaoDTO,'IdOrgao','Sigla');

    $strDisplayOrgao = 'display:none;';
    if (count($arrObjOrgaoDTO) > 1){
      $strDisplayOrgao = '';
    }
  }

  if ($bolEscolhaLivreUnidades || $numRegistros) {
    $arrComandos[] = '<button type="submit" accesskey="E" name="sbmEnviar" id="sbmEnviar" value="Enviar" class="infraButton"><span class="infraTeclaAtalho">E</span>nviar</button>';

    if (PaginaSEI::getInstance()->getAcaoRetorno()=='procedimento_controlar') {
      $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" value="Voltar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].$strParametros.PaginaSEI::montarAncora($arrProtocolosOrigem)).'\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';
    }
  }

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

#lblProcedimentos {position:absolute;left:0%;top:0%;}
#selProcedimentos {position:absolute;left:0%;top:22%;width:86%;height:65%;}

<? if ($bolEscolhaLivreUnidades){ ?>
#divOrgao {<?=$strDisplayOrgao?>}
#lblOrgao {position:absolute;left:0%;top:0%;}
#selOrgao {position:absolute;left:0%;top:40%;width:50%;}

#lblUnidades {position:absolute;left:0%;top:0%;}
#txtUnidade {position:absolute;left:0%;top:17%;width:50%;}
#ancUnidadesTramitacao {position:absolute;left:51%;top:19%;}
#selUnidades {position:absolute;left:0%;top:40%;width:86%;height:60%;}
#divOpcoesUnidades {position:absolute;left:87%;top:40%;}
<? } ?>

#divSinManterAberto {position:absolute;left:0%;top:15%;}
#divSinRemoverAnotacoes {position:absolute;left:0%;top:40%;}
#divSinEnviarEmailNotificacao {position:absolute;left:0%;top:65%;}

<?=SeiINT::montarCssEscolhaDataCertaDiasUteis('RetornoProgramado');?>
<?=SeiINT::montarCssEscolhaDataCertaDiasUteis('ReaberturaProgramada');?>

<?
if ($objEnviarProcessoDTO->getStrSinManterAberto()=='S'){
?>
#divReaberturaProgramada{display:none;}
<?
}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

<? if ($bolEscolhaLivreUnidades){ ?>
var objLupaUnidades = null;
var objAutoCompletarUnidade = null;
var objLupaGrupo = null;
var objLupaTramitacao = null;
<? } ?>
var objAjaxVerificacaoAssinatura = null;

function inicializar(){

  parent.parent.parent.infraOcultarAviso(false)

  <? if ($bolEscolhaLivreUnidades){ ?>
  objLupaUnidades = new infraLupaSelect('selUnidades','hdnUnidades','<?=$strLinkUnidadeSelecao?>');
	  
  objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
  //objAutoCompletarUnidade.maiusculas = true;
  //objAutoCompletarUnidade.mostrarAviso = true;
  //objAutoCompletarUnidade.tempoAviso = 1000;
  //objAutoCompletarUnidade.tamanhoMinimo = 3;
  objAutoCompletarUnidade.limparCampo = true;
  //objAutoCompletarUnidade.bolExecucaoAutomatica = false;
  objAutoCompletarUnidade.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUnidade').value+'&id_orgao='+document.getElementById('selOrgao').value+'&unidade_atual=0';
  };

  objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objLupaUnidades.adicionar(id,descricao,document.getElementById('txtUnidade'));
    }
  };

  objLupaGrupo = new infraLupaSelect('selUnidades','hdnUnidades','<?=$strLinkGrupo?>');
  objLupaGrupo.finalizarSelecao = function(){
    var arrUnidades=[];
    $('#selUnidades option').each(function(){
      var unidade=$(this).val();
      if (unidade!="") arrUnidades.push(unidade);
    });
    //$('#hdnDestinatario').val(arrUnidades.join(';'));
  };
  infraRemoverEvento(objLupaGrupo.sel, "keydown", objLupaGrupo.deleteTeclado);

  objLupaTramitacao = new infraLupaSelect('selUnidades','hdnUnidades','<?=$strLinkTramitacao?>');
  infraRemoverEvento(objLupaTramitacao.sel, "keydown", objLupaTramitacao.deleteTeclado);

  document.getElementById('txtUnidade').focus();
  <?}else{?>
  objLupaUnidades = new infraLupaSelect('selUnidades','hdnUnidades', null);
  <?}?>

  configurarRetornoProgramado();
  configurarReaberturaProgramada();

  <? if (isset($_GET['id_documento_assinado'])){ ?>
  objAjaxVerificacaoAssinatura = new infraAjaxComplementar(null,'<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=documento_verificar_assinatura&id_documento='.$_GET['id_documento_assinado'])?>');
  objAjaxVerificacaoAssinatura.async = false;
  objAjaxVerificacaoAssinatura.bolAssinado = false;
  objAjaxVerificacaoAssinatura.processarResultado = function(arr){
    if (arr!=null) {
      this.bolAssinado = false;
      if (arr['SinAssinado']!=undefined && arr['SinAssinado']=='S') {
        this.bolAssinado = true;
      }
    }
  };
  <?}?>

}

<? if ($bolEscolhaLivreUnidades){ ?>
function selecionarGrupo(){
  objLupaGrupo.selecionar(700,500);
}

function selecionarTramitacao(){
  objLupaTramitacao.selecionar(700,500);
}
<?}?>

function validarCadastroAbrirRI0825(){
  <? if ($bolEscolhaLivreUnidades){ ?>
  if (!infraSelectSelecionado('selOrgao')) {
    alert('Informe o Órgão das unidades.');
    document.getElementById('selOrgao').focus();
    return false;
  }

	if (document.getElementById('hdnUnidades').value=='') {
	  alert('Informe as Unidades de Destino.');
	  document.getElementById('selUnidades').focus();
	  return false;
	}
	<?}else{?>

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Unidade selecionada para envio.');
    return false;
  }

  <?}?>

<? if (isset($_GET['id_documento_assinado'])){ ?>

  objAjaxVerificacaoAssinatura.executar();

  if (!objAjaxVerificacaoAssinatura.bolAssinado){
    if (!confirm('A assinatura no documento foi cancelada.\n\n Confirma envio do processo?')) {
      return false;
    }
  }

<?}?>

  parent.parent.parent.infraExibirAviso();

	return true;
}

function OnSubmitForm() {
	return validarCadastroAbrirRI0825();
}

<?=SeiINT::montarJavascriptEscolhaDataCertaDiasUteis('RetornoProgramado')?>
<?=SeiINT::montarJavascriptEscolhaDataCertaDiasUteis('ReaberturaProgramada')?>

function manterAberto(){
  if (document.getElementById('chkSinManterAberto').checked){
    document.getElementById('divReaberturaProgramada').style.display='none';
  }else{
    document.getElementById('divReaberturaProgramada').style.display='block';
  }
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAtividadeListar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divProcedimentos" class="infraAreaDados" style="height:7.5em;">
	 	<label id="lblProcedimentos" for="selProcedimentos" class="infraLabelObrigatorio">Processos:</label>
	  <select id="selProcedimentos" name="selProcedimentos" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	  <?=$strItensSelProcedimentos?>
	  </select>
  </div>

  <? if ($bolEscolhaLivreUnidades){ ?>

  <div id="divOrgao" class="infraAreaDados" style="height:5em;">
    <label id="lblOrgao" for="selOrgao" class="infraLabelObrigatorio">Órgão das Unidades:</label>
    <select id="selOrgao" name="selOrgao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgaos?>
    </select>
  </div>

  <div id="divUnidades" class="infraAreaDados" style="height:11em;">
	 	<label id="lblUnidades" for="selUnidades" class="infraLabelObrigatorio">Unidades:</label>
	  <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <?
    if(count($arrProtocolosOrigem)==1){
      echo '<a id="ancUnidadesTramitacao" href="javascript:void(0);" onclick="selecionarTramitacao();" class="ancoraPadraoPreta" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'">Mostrar unidades por onde tramitou</a>';
    }
    ?>

	  <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value="" />
	  <select id="selUnidades" name="selUnidades" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	  </select>
    <div id="divOpcoesUnidades">
      <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Unidades" title="Selecionar Unidades" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <img id="imgSelecionarGrupo" onclick="selecionarGrupo();" src="<?=PaginaSEI::getInstance()->getIconeGrupo()?>" title="Selecionar Grupos de Envio" alt="Selecionar Grupos de Envio" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <br />
      <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>
  </div>
  <?}else{?>
  <select id="selUnidades" name="selUnidades" style="display:none;" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  </select>
  <?
    if ($numRegistros) {
      PaginaSEI::getInstance()->montarAreaTabela($strResultado, $numRegistros);
    }else{ ?>
      <label class="infraLabelObrigatorio">Nenhuma unidade disponível para envio.</label>
  <?}
  }?>

  <div id="divGeral" class="infraAreaDados" style="height:9em;">
  
    <div id="divSinManterAberto" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinManterAberto" name="chkSinManterAberto" class="infraCheckbox" onchange="manterAberto()" <?=PaginaSEI::getInstance()->setCheckbox($objEnviarProcessoDTO->getStrSinManterAberto())?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  	  <label id="lblSinManterAberto" for="chkSinManterAberto" accesskey="" class="infraLabelCheckbox" >Manter processo aberto na unidade atual</label>
  	</div>      
	
	  <div id="divSinRemoverAnotacoes" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinRemoverAnotacoes" name="chkSinRemoverAnotacoes" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objEnviarProcessoDTO->getStrSinRemoverAnotacoes())?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />	  
	    <label id="lblSinRemoverAnotacoes" for="chkSinRemoverAnotacoes" accesskey="" class="infraLabelCheckbox" >Remover anotação</label>
	  </div>      
	
	  <div id="divSinEnviarEmailNotificacao" class="infraDivCheckbox">
	    <input type="checkbox" id="chkSinEnviarEmailNotificacao" name="chkSinEnviarEmailNotificacao" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objEnviarProcessoDTO->getStrSinEnviarEmailNotificacao())?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
	    <label id="lblSinEnviarEmailNotificacao" for="chkSinEnviarEmailNotificacao" accesskey="" class="infraLabelCheckbox" >Enviar e-mail de notificação</label>
	  </div>

  </div>

  <?=SeiINT::montarHtmlEscolhaDataCertaDiasUteis('RetornoProgramado','Retorno Programado', $objEnviarProcessoDTO->getStrSinDiasUteisRetornoProgramado())?>
  <?=SeiINT::montarHtmlEscolhaDataCertaDiasUteis('ReaberturaProgramada','Reabertura Programada', $objEnviarProcessoDTO->getStrSinDiasUteisReaberturaProgramada())?>

  <input type="hidden" id="hdnIdProtocolos" name="hdnIdProtocolos" value="<?=$strIdProtocolos;?>" />
  <input type="hidden" id="hdnIdAtividades" name="hdnIdAtividades" value="<?=$strIdAtividades;?>" />
  <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?=$_POST['hdnUnidades']?>" />
  
  <?
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>