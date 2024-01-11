<?php
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 17/06/2010 - criado por fazenda_db
*
* Versão do Gerador de Código: 1.29.1
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

  PaginaSEI::getInstance()->verificarSelecao('base_conhecimento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selUnidade'));

  $objBaseConhecimentoDTO = new BaseConhecimentoDTO();

  $strDesabilitar = '';

	//ANEXOS
  $bolAcaoUpload = SessaoSEI::getInstance()->verificarPermissao('documento_upload_anexo');
  $bolAcaoDownload = SessaoSEI::getInstance()->verificarPermissao('documento_download_anexo');
  $bolAcaoRemoverAnexo = SessaoSEI::getInstance()->verificarPermissao('documento_remover_anexo');
  
  $arrComandos = array();
  switch($_GET['acao']){
  	
    case 'base_conhecimento_upload_anexo':
      if (isset($_FILES['filArquivo'])){
        PaginaSEI::getInstance()->processarUpload('filArquivo', DIR_SEI_TEMP, false);
      }
      die;  	
  	
    case 'base_conhecimento_cadastrar':
    	
      $strTitulo = 'Novo Procedimento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarBaseConhecimento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objBaseConhecimentoDTO->setNumIdBaseConhecimento(null);
      $objBaseConhecimentoDTO->setNumIdBaseConhecimentoOrigem(null);
      $objBaseConhecimentoDTO->setNumIdBaseConhecimentoAgrupador(null);
      $objBaseConhecimentoDTO->setStrStaEstado(BaseConhecimentoRN::$TE_RASCUNHO);
      $objBaseConhecimentoDTO->setNumIdUsuarioGerador(SessaoSEI::getInstance()->getNumIdUsuario());
      $objBaseConhecimentoDTO->setDthGeracao(InfraData::getStrDataHoraAtual());
      $objBaseConhecimentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objBaseConhecimentoDTO->setStrDescricao($_POST['txtDescricao']);
      $objBaseConhecimentoDTO->setStrConteudo(null);
	 		$objBaseConhecimentoDTO->setArrObjAnexoDTO(AnexoINT::processarRI0872($_POST['hdnAnexos']));
      
	 		$arrTiposProcedimento = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTiposProcedimento']);
	 		$arrObjRelBaseConhecTipoProcedDTO = array();
	 		foreach($arrTiposProcedimento as $numIdTipoProcedimento){
	 			$objRelBaseConhecTipoProcedDTO = new RelBaseConhecTipoProcedDTO();
	 			$objRelBaseConhecTipoProcedDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
	 			$arrObjRelBaseConhecTipoProcedDTO[] =$objRelBaseConhecTipoProcedDTO; 
	 		}
	 		$objBaseConhecimentoDTO->setArrObjRelBaseConhecTipoProcedDTO($arrObjRelBaseConhecTipoProcedDTO);
	 		
      if (isset($_POST['sbmCadastrarBaseConhecimento'])) {
        try{
          $objBaseConhecimentoRN = new BaseConhecimentoRN();
          $objBaseConhecimentoDTO = $objBaseConhecimentoRN->cadastrar($objBaseConhecimentoDTO);
          
          PaginaSEI::getInstance()->setStrMensagem('Procedimento cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_listar&acao_origem='.$_GET['acao'].'&id_base_conhecimento='.$objBaseConhecimentoDTO->getNumIdBaseConhecimento().'&resultado=1'.PaginaSEI::getInstance()->montarAncora($objBaseConhecimentoDTO->getNumIdBaseConhecimento())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      
      break;

    case 'base_conhecimento_nova_versao':
    	
      $strTitulo = 'Nova Versão do Procedimento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmNovaVersao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objBaseConhecimentoRN = new BaseConhecimentoRN();
      
      if (isset($_GET['id_base_conhecimento_origem'])){
        
      	$objBaseConhecimentoDTO->setNumIdBaseConhecimentoOrigem($_GET['id_base_conhecimento_origem']);
        $objBaseConhecimentoDTO = $objBaseConhecimentoRN->prepararClone($objBaseConhecimentoDTO);
        
        $strItensSelTipoProcedimento = InfraINT::montarSelectArrInfraDTO(null, null, null, $objBaseConhecimentoDTO->getArrObjRelBaseConhecTipoProcedDTO(), 'IdTipoProcedimento', 'NomeTipoProcedimento');
        
        $arrObjAnexoDTO = $objBaseConhecimentoDTO->getArrObjAnexoDTO();
        
        $arrAcoesDownload = array();
        $arrAcoesRemover = array();
        
        if (InfraArray::contar($arrObjAnexoDTO)){

        	$arrItensTabelaAnexo = array();
        	
        	foreach($arrObjAnexoDTO as $objAnexoDTO){
        	  
        		$arrItensTabelaAnexo[] = array($objAnexoDTO->getNumIdAnexo(),
        		                               $objAnexoDTO->getStrNome(),
        		                               $objAnexoDTO->getDthInclusao(),
        		                               $objAnexoDTO->getNumTamanho(),
        		                               InfraUtil::formatarTamanhoBytes($objAnexoDTO->getNumTamanho()),
        		                               $objAnexoDTO->getStrSiglaUsuario(),
        		                               $objAnexoDTO->getStrSiglaUnidade());
        		                               
		        if ($bolAcaoDownload){
		        	//aponta para o anexo da versão anterior para visualização
		          $arrAcoesDownload[$objAnexoDTO->getNumIdAnexo()] = '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_download_anexo&id_anexo='.$objAnexoDTO->getNumIdAnexoOrigem()).'" target="_blank"><img src="'.PaginaSEI::getInstance()->getIconeDownload().'" title="Baixar Anexo" alt="Baixar Anexo" class="infraImg" /></a> ';
		        }
		        
		        if ($bolAcaoRemoverAnexo){
		          $arrAcoesRemover[$objAnexoDTO->getNumIdAnexo()] = true;
		        }
        		                               
        	}
        	$_POST['hdnAnexos'] = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrItensTabelaAnexo);
        }
      }else{
	      $objBaseConhecimentoDTO->setNumIdBaseConhecimento(null);
	      $objBaseConhecimentoDTO->setNumIdBaseConhecimentoOrigem($_POST['hdnIdBaseConhecimentoOrigem']);
	      $objBaseConhecimentoDTO->setStrDescricao($_POST['txtDescricao']);
	      $objBaseConhecimentoDTO->setArrObjAnexoDTO(AnexoINT::processarRI0872($_POST['hdnAnexos']));
	      
		 		$arrTiposProcedimento = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTiposProcedimento']);
		 		$arrObjRelBaseConhecTipoProcedDTO = array();
		 		foreach($arrTiposProcedimento as $numIdTipoProcedimento){
		 			$objRelBaseConhecTipoProcedDTO = new RelBaseConhecTipoProcedDTO();
		 			$objRelBaseConhecTipoProcedDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
		 			$arrObjRelBaseConhecTipoProcedDTO[] =$objRelBaseConhecTipoProcedDTO; 
		 		}
		 		$objBaseConhecimentoDTO->setArrObjRelBaseConhecTipoProcedDTO($arrObjRelBaseConhecTipoProcedDTO);
	      
      }
            
      if (isset($_POST['sbmNovaVersao'])) {
        try{
        	
          $objBaseConhecimentoDTO = $objBaseConhecimentoRN->gerarNovaVersao($objBaseConhecimentoDTO);
          
          PaginaSEI::getInstance()->setStrMensagem('Nova Versão de Procedimento cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_listar&acao_origem='.$_GET['acao'].'&id_base_conhecimento='.$objBaseConhecimentoDTO->getNumIdBaseConhecimento().'&resultado=1'.PaginaSEI::getInstance()->montarAncora($objBaseConhecimentoDTO->getNumIdBaseConhecimento())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;
      
    case 'base_conhecimento_alterar':
      $strTitulo = 'Alterar Procedimento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarBaseConhecimento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      
      $strDesabilitar = 'disabled="disabled"';

      
      
      if (isset($_GET['id_base_conhecimento'])){
        $objBaseConhecimentoDTO->setNumIdBaseConhecimento($_GET['id_base_conhecimento']);
        $objBaseConhecimentoDTO->retTodos();
        $objBaseConhecimentoRN = new BaseConhecimentoRN();
        $objBaseConhecimentoDTO = $objBaseConhecimentoRN->consultar($objBaseConhecimentoDTO);
        if ($objBaseConhecimentoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
      	 
      	//print_r($_REQUEST);die;
      	
        $objBaseConhecimentoDTO->setNumIdBaseConhecimento($_POST['hdnIdBaseConhecimento']);
        $objBaseConhecimentoDTO->setNumIdBaseConhecimentoOrigem($_POST['hdnIdBaseConhecimentoOrigem']);
        $objBaseConhecimentoDTO->setStrDescricao($_POST['txtDescricao']);
        $objBaseConhecimentoDTO->setArrObjAnexoDTO(AnexoINT::processarRI0872($_POST['hdnAnexos']));
        
		 		$arrTiposProcedimento = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnTiposProcedimento']);
		 		$arrObjRelBaseConhecTipoProcedDTO = array();
		 		foreach($arrTiposProcedimento as $numIdTipoProcedimento){
		 			$objRelBaseConhecTipoProcedDTO = new RelBaseConhecTipoProcedDTO();
		 			$objRelBaseConhecTipoProcedDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
		 			$arrObjRelBaseConhecTipoProcedDTO[] =$objRelBaseConhecTipoProcedDTO; 
		 		}
		 		$objBaseConhecimentoDTO->setArrObjRelBaseConhecTipoProcedDTO($arrObjRelBaseConhecTipoProcedDTO);
        
      }
				
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objBaseConhecimentoDTO->getNumIdBaseConhecimento())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarBaseConhecimento'])) {
        try{
        	
          $objBaseConhecimentoRN = new BaseConhecimentoRN();
          $objBaseConhecimentoRN->alterar($objBaseConhecimentoDTO);
          
          PaginaSEI::getInstance()->setStrMensagem('Procedimento "'.$objBaseConhecimentoDTO->getNumIdBaseConhecimento().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_listar&acao_origem='.$_GET['acao'].'&id_base_conhecimento='.$objBaseConhecimentoDTO->getNumIdBaseConhecimento().'&resultado=1'.PaginaSEI::getInstance()->montarAncora($objBaseConhecimentoDTO->getNumIdBaseConhecimento())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'base_conhecimento_consultar':
      $strTitulo = 'Consultar Procedimento';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_base_conhecimento='.$_GET['id_base_conhecimento'].'&id_base_conhecimento_agrupador='.$_GET['id_base_conhecimento_agrupador'].PaginaSEI::getInstance()->montarAncora($_GET['id_base_conhecimento'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objBaseConhecimentoDTO->setNumIdBaseConhecimento($_GET['id_base_conhecimento']);
      $objBaseConhecimentoDTO->setBolExclusaoLogica(false);
      $objBaseConhecimentoDTO->retTodos();
      $objBaseConhecimentoRN = new BaseConhecimentoRN();
      $objBaseConhecimentoDTO = $objBaseConhecimentoRN->consultar($objBaseConhecimentoDTO);
      if ($objBaseConhecimentoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      
      $bolAcaoRemoverAnexo = false;
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkAnexos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=base_conhecimento_upload_anexo');

  $strLinkAjaxTipoProcedimento = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=tipo_procedimento_auto_completar');     	 
  $strLinkTipoProcedimentoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tipo_procedimento_selecionar&tipo_selecao=2&id_object=objLupaTipoProcedimento');

  //na primeira vez que entrar na tela de geração de nova versão não deve processar os anexos (a tabela deve ser montada com os anexos do clone)  
  if (!($_GET['acao']=='base_conhecimento_nova_versao' && isset($_GET['id_base_conhecimento_origem']))){

    $arrIdAnexos = null;
    if ($objBaseConhecimentoDTO->getNumIdBaseConhecimento()!=null) {
      //Itens da tabela de anexos
      $objAnexoDTO = new AnexoDTO();
      $objAnexoDTO->retNumIdAnexo();
      $objAnexoDTO->setNumIdBaseConhecimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());

      $objAnexoRN = new AnexoRN();
      $arrIdAnexos = InfraArray::converterArrInfraDTO($objAnexoRN->listarRN0218($objAnexoDTO),'IdAnexo');
    }

    $_POST['hdnAnexos'] = AnexoINT::montarAnexos($arrIdAnexos,
        $bolAcaoDownload,
        'base_conhecimento_download_anexo',
        $arrAcoesDownload,
        $bolAcaoRemoverAnexo,
        $arrAcoesRemover);

    $strItensSelTipoProcedimento = RelBaseConhecTipoProcedINT::montarSelectNomeTipoProcedimento($objBaseConhecimentoDTO->getNumIdBaseConhecimento());    
  }
  
  if ($_GET['acao']=='base_conhecimento_cadastrar'){
    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numIdModeloInterno = $objInfraParametro->getValor('ID_MODELO_INTERNO_BASE_CONHECIMENTO');
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

#lblDescricao {position:absolute;left:0%;top:0%;width:49%;}
#txtDescricao {position:absolute;left:0%;top:11%;width:49%;}

#lblTipoProcedimento {position:absolute;left:0%;top:32%;width:49%;}
#txtTipoProcedimento {position:absolute;left:0%;top:43%;width:49%;}
#selTipoProcedimento {position:absolute;left:0%;top:58%;width:65%;}
#imgLupaTipoProcedimento {position:absolute;left:65.5%;top:59%;}
#imgExcluirTipoProcedimento {position:absolute;left:65.5%;top:71.5%;}

#divArquivo {height:3em;}
#lblArquivo {position:absolute;left:0%;top:0%;width:50%;}
#filArquivo {position:absolute;left:0%;top:50%;width:50%;}

<?      
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
var objAutoCompletarTipoProcedimento = null;
var objLupaTipoProcedimento = null;
var objUpload = null;
var objTabelaAnexos = null;

function inicializar(){

  if ('<?=$_GET['acao']?>'=='base_conhecimento_cadastrar'){
    document.getElementById('txtDescricao').focus();
  } else if ('<?=$_GET['acao']?>'=='base_conhecimento_consultar'){
    infraDesabilitarCamposAreaDados();
  }  
  
  objLupaTipoProcedimento = new infraLupaSelect('selTipoProcedimento','hdnTiposProcedimento','<?=$strLinkTipoProcedimentoSelecao?>');

  objAutoCompletarTipoProcedimento = new infraAjaxAutoCompletar('hdnIdTipoProcedimento','txtTipoProcedimento','<?=$strLinkAjaxTipoProcedimento?>');
  objAutoCompletarTipoProcedimento.limparCampo = true;

  objAutoCompletarTipoProcedimento.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtTipoProcedimento').value;
  };
  
  objAutoCompletarTipoProcedimento.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objLupaTipoProcedimento.adicionar(id,descricao,document.getElementById('txtTipoProcedimento'));
    }
  };
  
  funcaoConclusao = function(arr){
    objTabelaAnexos.adicionar([arr['nome_upload'],arr['nome'],arr['data_hora'],arr['tamanho'],infraFormatarTamanhoBytes(arr['tamanho']),'<?=PaginaSEI::getInstance()->formatarParametrosJavaScript(SessaoSEI::getInstance()->getStrSiglaUsuario())?>' ,'<?=PaginaSEI::getInstance()->formatarParametrosJavaScript(SessaoSEI::getInstance()->getStrSiglaUnidadeAtual())?>']);
    objTabelaAnexos.adicionarAcoes(arr['nome_upload'],'',false,true);  
    //document.getElementById('divArquivo').style.display = 'none';
  }

  <?=DocumentoINT::montarUpload('frmAnexos',$strLinkAnexos,'filArquivo','objUpload', 'funcaoConclusao','objTabelaAnexos','tblAnexos','hdnAnexos')?>

  

  //Monta ações de download 
  <? 
  	if (InfraArray::contar($arrAcoesDownload)>0){
       foreach(array_keys($arrAcoesDownload) as $id) { ?>
  			objTabelaAnexos.adicionarAcoes('<?=$id?>','<?=$arrAcoesDownload[$id]?>');  
  <?   }
  	} 
  ?>  
  //Monta ações para remover anexos
  <? if (InfraArray::contar($arrAcoesRemover)>0){
       foreach(array_keys($arrAcoesRemover) as $id) { 
  ?>
  			objTabelaAnexos.adicionarAcoes('<?=$id?>','',false,true);  
  <?   
       }
     } 
  ?>  
  
  infraEfeitoTabelas();
  
}

function validarCadastro() {

  if (infraTrim(document.getElementById('txtDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txtDescricao').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmBaseConhecimentoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divDados" class="infraAreaDados" style="height:18em;">
  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objBaseConhecimentoDTO->getStrDescricao())?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  />

 	<label id="lblTipoProcedimento" for="selTipoProcedimento" class="infraLabelOpcional">Tipos de Processo Associados:</label>
  <input type="text" id="txtTipoProcedimento" name="txtTipoProcedimento" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <input type="hidden" id="hdnIdTipoProcedimento" name="hdnIdTipoProcedimento" class="infraText" value="<?=$_POST['hdnIdTipoProcedimento']?>" />
  <select id="selTipoProcedimento" name="selTipoProcedimento" size="4" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelTipoProcedimento?>
  </select>
  <img id="imgLupaTipoProcedimento" onclick="objLupaTipoProcedimento.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Tipo de Processo" title="Selecionar Tipo de Processo" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgExcluirTipoProcedimento" onclick="objLupaTipoProcedimento.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Tipos de Processo Selecionados" title="Remover Tipos de Processo Selecionados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdBaseConhecimento" name="hdnIdBaseConhecimento" value="<?=$objBaseConhecimentoDTO->getNumIdBaseConhecimento();?>" />
  <input type="hidden" id="hdnIdBaseConhecimentoOrigem" name="hdnIdBaseConhecimentoOrigem" value="<?=$objBaseConhecimentoDTO->getNumIdBaseConhecimentoOrigem();?>" />
	<input type="hidden" id="hdnAnexos" name="hdnAnexos" value="<?=$_POST['hdnAnexos']?>"/>  
	<input type="hidden" id="hdnTiposProcedimento" name="hdnTiposProcedimento" value="<?=$_POST['hdnTiposProcedimento']?>"/>
	</div>
   <?
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<form id="frmAnexos" style="margin:0;border:0;padding:0;">
  <div id="divArquivo" class="infraAreaDados">
    <label id="lblArquivo" for="filArquivo" accesskey="" class="infraLabelInputFile">Anexar Arquivo...</label>
    <input type="file" id="filArquivo" name="filArquivo" class="infraInputFile" size="50" onchange="objUpload.executar();" tabindex="1000"/><br />
  </div>
  
  <div id="divAnexos" style="height:10em;">    
     <table id="tblAnexos" name="tblAnexos" class="infraTable" style="width:90%">
        <caption class="infraCaption"><?=PaginaSEI::getInstance()->gerarCaptionTabela("Anexos",0)?></caption>
       
    		<tr>
    			<th style="display:none;">ID</th>
    			<th width="30%" class="infraTh">Nome</th>
    			<th class="infraTh" align="center">Data</th>
    			<th style="display:none;">Bytes</th>
    			<th width="10%" class="infraTh" align="center">Tamanho</th>
    			<th width="10%" class="infraTh" align="center">Usuário</th>
    			<th width="10%" class="infraTh" align="center">Unidade</th>
    			<th width="10%" class="infraTh">Ações</th>
    		</tr>
      </table>
      <!-- campo hidden correspondente (hdnAnexos) deve ficar no outro form -->
    </div>
</form>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>