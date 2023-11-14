<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 08/06/2011 - criado por mga
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
  InfraDebug::getInstance()->setBolDebugInfra(true);
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
  
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  $arrComandos = array();
  
  switch($_GET['acao']){
  	
  	case 'procedimento_credencial_conceder':
  		
  		$strTitulo = 'Concessão de Credencial';
  		$numIdAtividade = null;
  		
  		try{
		    $objConcederCredencialDTO = new ConcederCredencialDTO();
		    
		    $arrAtividadesOrigem = explode(',',$_POST['hdnIdAtividades']);
		 
	     	$objAtividadeRN = new AtividadeRN();
	
	      $objConcederCredencialDTO->setDblIdProcedimento($_GET['id_procedimento']);
	      $objConcederCredencialDTO->setNumIdUsuario($_POST['hdnIdUsuario']);
	      $objConcederCredencialDTO->setNumIdUnidade($_POST['selUnidade']);
	      $objConcederCredencialDTO->setArrAtividadesOrigem(InfraArray::gerarArrInfraDTO('AtividadeDTO','IdAtividade',explode(',',$_POST['hdnIdAtividades'])));
	      
	      $ret = $objAtividadeRN->concederCredencial($objConcederCredencialDTO);

	      $numIdAtividade = $ret->getNumIdAtividade();

	      PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');

  		}catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
  		}

      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_credencial_gerenciar&acao_origem='.$_GET['acao'].'&resultado=1'.$strParametros.PaginaSEI::getInstance()->montarAncora($numIdAtividade)));
      die;

    case 'procedimento_credencial_cassar':
    	
    	$strTitulo = 'Cassação de Credencial';
    	
      try{

      	$arrObjAtividadeDTO = InfraArray::gerarArrInfraDTO('AtividadeDTO','IdAtividade',PaginaSEI::getInstance()->getArrStrItensSelecionados());
      	
        $objAtividadeRN = new AtividadeRN();
        $objAtividadeRN->cassarCredenciais($arrObjAtividadeDTO);
        
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
        
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&resultado=1'.$strParametros.PaginaSEI::getInstance()->montarAncora(implode(',',PaginaSEI::getInstance()->getArrStrItensSelecionados()))));
      die;

    case 'procedimento_credencial_renovar':

      $strTitulo = 'Renovação de Credencial';

      try{

        $arrObjAtividadeDTO = InfraArray::gerarArrInfraDTO('AtividadeDTO','IdAtividade',PaginaSEI::getInstance()->getArrStrItensSelecionados());

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeRN->renovarCredenciais($arrObjAtividadeDTO);

        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');

      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&resultado=1'.$strParametros.PaginaSEI::getInstance()->montarAncora(implode(',',PaginaSEI::getInstance()->getArrStrItensSelecionados()))));
      die;

    case 'procedimento_credencial_gerenciar':
      $strTitulo = 'Gerenciar Credenciais';
	    break;
	
	    default:
	      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  
  $arrComandos = array();

  
  $objProcedimentoDTO = new ProcedimentoDTO();
  $objProcedimentoDTO->setDblIdProcedimento($_GET['id_procedimento']);
	  
	$objAtividadeRN = new AtividadeRN();
	$arrObjAtividadeDTO = $objAtividadeRN->listarCredenciais($objProcedimentoDTO);
	
  $numRegistros = count($arrObjAtividadeDTO);

  $bolAcaoConceder = SessaoSEI::getInstance()->verificarPermissao('procedimento_credencial_conceder');
  $bolAcaoCassar = SessaoSEI::getInstance()->verificarPermissao('procedimento_credencial_cassar');
  $bolAcaoRenovar = SessaoSEI::getInstance()->verificarPermissao('procedimento_credencial_renovar');
  	
  if ($bolAcaoConceder){
  	$strLinkConceder = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_credencial_conceder&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros);
  }

  if ($numRegistros > 0){

    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->setBolExclusaoLogica(false);
    $objOrgaoDTO->retNumIdOrgao();
    $objOrgaoDTO->retStrSigla();
    $objOrgaoDTO->setNumIdOrgao(array_unique(array_merge(InfraArray::converterArrInfraDTO($arrObjAtividadeDTO,'IdOrgaoUsuario'),InfraArray::converterArrInfraDTO($arrObjAtividadeDTO,'IdOrgaoUsuarioOrigem'))),InfraDTO::$OPER_IN);

    $objOrgaoRN = new OrgaoRN();
    $arrObjOrgaoDTO = InfraArray::indexarArrInfraDTO($objOrgaoRN->listarRN1353($objOrgaoDTO),'IdOrgao');

  	
    if ($bolAcaoCassar){
      $strLinkCassar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_credencial_cassar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros);
    }

    if ($bolAcaoRenovar){
      $strLinkRenovar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_credencial_renovar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].$strParametros);
    }

    //$arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Credenciais Concedidas / Cassadas.';
    $strCaptionTabela = 'Credenciais Concedidas / Cassadas';

    $strResultado .= '<table id="tblCredenciais" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n"; //90
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%" style="display:none;" rowspan="2">'.PaginaSEI::getInstance()->getThCheck('','Infra','style="display:none;"').'</th>'."\n";
    $strResultado .= '<th class="infraTh" colspan="2">De</th>'."\n";
    $strResultado .= '<th class="infraTh" colspan="2">Para</th>'."\n";
    $strResultado .= '<th class="infraTh" width="14%" rowspan="2">Concessão</th>'."\n";
    $strResultado .= '<th class="infraTh" width="14%" rowspan="2">Renovação</th>'."\n";
    $strResultado .= '<th class="infraTh" width="14%" rowspan="2">Cassação</th>'."\n";
    $strResultado .= '<th class="infraTh" rowspan="2">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="12%">Usuário</th>'."\n";
    $strResultado .= '<th class="infraTh" width="12%">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh" width="12%">Usuário</th>'."\n";
    $strResultado .= '<th class="infraTh" width="12%">Unidade</th>'."\n";
    $strResultado .= '</tr>'."\n";

    $strCssTr='';
    
    $n = 0;
    foreach($arrObjAtividadeDTO as $objAtividadeDTO){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= "\n".'<td style="display:none;">';
      //if ($objAtividadeDTO->getNumIdTarefa()==TarefaRN::$TI_PROCESSO_CONCESSAO_CREDENCIAL || $objAtividadeDTO->getNumIdTarefa()==TarefaRN::$TI_PROCESSO_TRANSFERENCIA_CREDENCIAL){
        $strResultado .= PaginaSEI::getInstance()->getTrCheck($n++,$objAtividadeDTO->getNumIdAtividade(),$objAtividadeDTO->getStrSiglaUsuario().'/'.$objAtividadeDTO->getStrSiglaUnidade(),'N','Infra','style="visibility:hidden;"');
      //}else{
      //	$strResultado .= '&nbsp;';
      //}
      $strResultado .= '</td>';

      $strResultado .= "\n".'<td align="center">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioOrigem().' / '.$arrObjOrgaoDTO[$objAtividadeDTO->getNumIdOrgaoUsuarioOrigem()]->getStrSigla()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuarioOrigem().' / '.$arrObjOrgaoDTO[$objAtividadeDTO->getNumIdOrgaoUsuarioOrigem()]->getStrSigla()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUsuarioOrigem()).'</a>';
      $strResultado .= '</td>';

      $strResultado .= "\n".'<td align="center">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidadeOrigem()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidadeOrigem()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUnidadeOrigem()).'</a>';
      $strResultado .= '</td>';

      $strResultado .= "\n".'<td align="center">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuario().' / '.$arrObjOrgaoDTO[$objAtividadeDTO->getNumIdOrgaoUsuario()]->getStrSigla()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrNomeUsuario().' / '.$arrObjOrgaoDTO[$objAtividadeDTO->getNumIdOrgaoUsuario()]->getStrSigla()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUsuario()).'</a>';
      $strResultado .= '</td>';

      $strResultado .= "\n".'<td align="center">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objAtividadeDTO->getStrSiglaUnidade()).'</a>';
      $strResultado .= '</td>';

      $strResultado .= '<td align="center">'.substr($objAtividadeDTO->getDthAbertura(),0,16).'</td>';

      $strResultado .= '<td align="center">';
      $bolRenovacao = false;
      foreach ($objAtividadeDTO->getArrObjAtributoAndamentoDTO() as $objAtributoAndamentoDTO) {
        if ($objAtributoAndamentoDTO->getStrNome() == 'RENOVACAO') {

          if ($bolRenovacao){
            $strResultado .= '<br>';
          }else{
            $bolRenovacao = true;
          }

          $strResultado .= substr($objAtributoAndamentoDTO->getStrValor(), 0, 16);
          break;
        }
      }

      if (!$bolRenovacao){
        $strResultado .= '&nbsp;';
      }

      $strResultado .= '</td>';

      if (in_array($objAtividadeDTO->getNumIdTarefa(), TarefaRN::getArrTarefasCassacaoCredencial(false))) {
        $strResultado .= '<td align="center">';
        foreach ($objAtividadeDTO->getArrObjAtributoAndamentoDTO() as $objAtributoAndamentoDTO) {
          if ($objAtributoAndamentoDTO->getStrNome() == 'DATA_HORA') {
            $strResultado .= substr($objAtributoAndamentoDTO->getStrValor(), 0, 16);
            break;
          }
        }
        $strResultado .= '</td>';
      }else{
        $strResultado .= '<td>&nbsp;</td>';
      }

			$strResultado .= "\n".'<td align="center">&nbsp;';

      if (in_array($objAtividadeDTO->getNumIdTarefa(),TarefaRN::getArrTarefasConcessaoCredencial(false))) {

        //na mesma unidade mas de outros usuarios
        if ($bolAcaoCassar && $objAtividadeDTO->getNumIdUnidadeOrigem()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objAtividadeDTO->getNumIdUsuario()!=SessaoSEI::getInstance()->getNumIdUsuario()) {
          $strResultado .= '<a href="#ID-'.$objAtividadeDTO->getNumIdAtividade().'"  onclick="acaoCassar(\''.$objAtividadeDTO->getNumIdAtividade().'\',\''.$objAtividadeDTO->getStrSiglaUsuario().'/'.$objAtividadeDTO->getStrSiglaUnidade().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::CREDENCIAL_CASSAR.'" title="Cassar Credencial de Acesso" alt="Cassar Credencial de Acesso" class="infraImg" /></a>&nbsp;';
        }

        //na mesma unidade e mesmo usuario
        if ($bolAcaoRenovar && $objAtividadeDTO->getNumIdUnidadeOrigem()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objAtividadeDTO->getNumIdUsuarioOrigem()==SessaoSEI::getInstance()->getNumIdUsuario()) {
          $strResultado .= '<a href="#ID-'.$objAtividadeDTO->getNumIdAtividade().'"  onclick="acaoRenovar(\''.$objAtividadeDTO->getNumIdAtividade().'\',\''.$objAtividadeDTO->getStrSiglaUsuario().'/'.$objAtividadeDTO->getStrSiglaUnidade().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::CREDENCIAL_RENOVAR.'" title="Renovar Credencial de Acesso" alt="Renovar Credencial de Acesso" class="infraImg" /></a>&nbsp;';
        }

      }

			$strResultado .= '</td>';
      
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  
  //$arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

  $strLinkAjaxUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar');
  $strLinkAjaxUnidadesUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_unidades_permissao');
  $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].'&id_procedimento='.$_GET['id_procedimento'].'&montar_visualizacao=0');

  //busca andamentos abertos do processo para validar na hora de salvar (verifica se ocorreu alteracao) 
  if ($_GET['acao_origem']=='arvore_visualizar' || 
      $_GET['acao_origem']=='procedimento_credencial_conceder' ||
      $_GET['acao_origem']=='procedimento_credencial_cassar' ||
      $_GET['acao_origem']=='procedimento_credencial_renovar'){

  	$objAtividadeRN = new AtividadeRN();
  	$objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
  	$objPesquisaPendenciaDTO->setDblIdProtocolo($_GET['id_procedimento']);
  	$objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
  	$objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
  	$arrObjProcedimentoDTO = $objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO);

  	if (count($arrObjProcedimentoDTO)==0){
  		throw new InfraException('Processo não encontrado.');
  	}
  	$arrAtividadesOrigem = InfraArray::converterArrInfraDTO($arrObjProcedimentoDTO[0]->getArrObjAtividadeDTO(),'IdAtividade');
  }else {
  	if ($_POST['hdnIdAtividades']!=''){
  		$arrAtividadesOrigem = explode(',',$_POST['hdnIdAtividades']);
  	}
  }
  $arrNumIdAtividades = implode(',',$arrAtividadesOrigem);

  
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
#lblUsuario {position:absolute;left:0%;top:10%;}
#txtUsuario {position:absolute;left:0%;top:45%;width:40%;}
#lblUnidade {position:absolute;left:41%;top:10%;visibility:hidden;}
#selUnidade {position:absolute;left:41%;top:45%;width:40%;visibility:hidden;}
#btnConceder {position:absolute;left:84%;top:45%;visibility:hidden;}

#tblCredenciais td {padding:.4em;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAutoCompletarUsuario = null;
var objAjaxUnidadesUsuario = null;
var objTabelaUsuariosUnidades = null;
var bolRemontandoTela = false;

function inicializar(){

  <?if (($_GET['acao_origem']=='procedimento_credencial_conceder' || $_GET['acao_origem']=='procedimento_credencial_cassar' || $_GET['acao_origem']=='procedimento_credencial_renovar') && $_GET['resultado']=='1') { ?>
    parent.parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
  <?}?>

  objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?=$strLinkAjaxUsuario?>');
  //objAutoCompletarUsuario.maiusculas = true;
  //objAutoCompletarUsuario.mostrarAviso = true;
  //objAutoCompletarUsuario.tempoAviso = 1000;
  //objAutoCompletarUsuario.tamanhoMinimo = 3;
  objAutoCompletarUsuario.limparCampo = true;
  //objAutoCompletarUsuario.bolExecucaoAutomatica = false;

  objAutoCompletarUsuario.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUsuario').value;
  };
  
  objAutoCompletarUsuario.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objAjaxUnidadesUsuario.executar();
    }else{
	    document.getElementById('lblUnidade').style.visibility = 'hidden';
      document.getElementById('selUnidade').style.visibility = 'hidden';
      document.getElementById('selUnidade').options.length = 0;
      document.getElementById('btnConceder').style.visibility = 'hidden';
    }
  };
  
  objAjaxUnidadesUsuario = new infraAjaxMontarSelect('selUnidade','<?=$strLinkAjaxUnidadesUsuario?>');
	  objAjaxUnidadesUsuario.prepararExecucao = function(){
	    return 'id_usuario='+document.getElementById('hdnIdUsuario').value;
	  };
	  objAjaxUnidadesUsuario.processarResultado = function(nroItens){
	    
	    document.getElementById('lblUnidade').style.visibility = 'hidden';
      document.getElementById('selUnidade').style.visibility = 'hidden';
      document.getElementById('btnConceder').style.visibility = 'hidden';
	    
      if (document.getElementById('selUnidade').options.length == 1){
        if (document.getElementById('selUnidade').options[0].value=='null'){
          alert('Usuário não tem acesso a nenhuma unidade.');
        }else{
          document.getElementById('selUnidade').options[0].selected = true;
          document.getElementById('btnConceder').style.left = '41%'; 
          document.getElementById('btnConceder').style.visibility = 'visible';
        }
	    }else if (document.getElementById('selUnidade').options.length > 1){
	      document.getElementById('lblUnidade').style.visibility = 'visible';
	      document.getElementById('selUnidade').style.visibility = 'visible';
        document.getElementById('btnConceder').style.left = '82%'; 
	      document.getElementById('selUnidade').focus();
	      
	      if (bolRemontandoTela){
	        infraSelectSelecionarItem('selUnidade','<?=$_POST['selUnidade']?>');
	        escolheuUnidade();
	      }
	    }
	  }
  
<? if ($_GET['acao']=='procedimento_credencial_conceder'){ ?>
  //erro ao conceder remonta a tela
  bolRemontandoTela = true;
  objAutoCompletarUsuario.selecionar('<?=$_POST['hdnIdUsuario']?>','<?=$_POST['txtUsuario']?>');
<? }else{ ?>
	document.getElementById('txtUsuario').focus();
<? } ?>	
	
  infraEfeitoTabelas();
}

<? if ($bolAcaoConceder){ ?>
function conceder(){
  if (infraTrim(document.getElementById('hdnIdUsuario'))==''){
    alert('Informe um Usuário.');
    document.getElementById('txtUsuario').focus();
    return;
  }

  if (!infraSelectSelecionado('selUnidade')){
    alert('Selecione uma Unidade.');
    document.getElementById('selUnidade').focus();
    return;
  }

  document.getElementById('frmGerenciarCredenciais').action = '<?=$strLinkConceder?>';
  document.getElementById('frmGerenciarCredenciais').submit();
}

function escolheuUnidade(){
  if (!infraSelectSelecionado('selUnidade')){
    document.getElementById('btnConceder').style.visibility = 'hidden'; 
  }else{
    document.getElementById('btnConceder').style.visibility = 'visible';
    document.getElementById('btnConceder').focus();
  }
}
<? } ?>

<? if ($bolAcaoCassar){ ?>
function acaoCassar(id,desc){
  if (confirm("Confirma cassação da credencial \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmGerenciarCredenciais').action='<?=$strLinkCassar?>';
    document.getElementById('frmGerenciarCredenciais').submit();
  }
}
<? } ?>

<? if ($bolAcaoRenovar){ ?>
  function acaoRenovar(id,desc){
    if (confirm("Confirma renovação da credencial \""+desc+"\"?")){
      document.getElementById('hdnInfraItemId').value=id;
      document.getElementById('frmGerenciarCredenciais').action='<?=$strLinkRenovar?>';
      document.getElementById('frmGerenciarCredenciais').submit();
    }
  }
<? } ?>

function OnSubmitForm() {
	return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGerenciarCredenciais" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
	//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
	PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
	//PaginaSEI::getInstance()->montarAreaValidacao();
?>	
  <div id="divUsuarios" class="infraAreaDados" style="height:6em;">
	 	<label id="lblUsuario" for="selUsuario" class="infraLabelOpcional">Conceder Credencial para:</label>
	  <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
	  <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" class="infraText" value="" />
	  
	 	<label id="lblUnidade" for="selUnidade" class="infraLabelOpcional">Unidade:</label>
	  <select id="selUnidade" name="selUnidade" class="infraSelect" onchange="escolheuUnidade();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
	  </select>
	  
	  <button type="button" name="btnConceder" id="btnConceder" onclick="conceder();" accesskey="C" value="Conceder" class="infraButton"><span class="infraTeclaAtalho">C</span>onceder</button>
  </div>
<?	
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
	PaginaSEI::getInstance()->montarAreaDebug();
	PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
?>
  
  <input type="hidden" id="hdnIdAtividades" name="hdnIdAtividades" value="<?=$arrNumIdAtividades;?>" />
  
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>