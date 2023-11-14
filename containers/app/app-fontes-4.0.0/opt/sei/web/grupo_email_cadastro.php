<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/09/2010 - criado por alexandre_db
*
* Versão do Gerador de Código: 1.30.0
*
* Versão no CVS: $Id$
*/

try {
	require_once dirname(__FILE__).'/SEI.php';

	session_start();

	//////////////////////////////////////////////////////////////////////////////
	//InfraDebug::getInstance()->setBolLigado(false);
	//InfraDebug::getInstance()->setBolDebugInfra(true);
	//InfraDebug::getInstance()->limpar();
	//////////////////////////////////////////////////////////////////////////////

	SessaoSEI::getInstance()->validarLink();

  if(strpos($_GET['acao'],'grupo_email_institucional')===0){
    $strInstitucional = 'Institucional';
    $strRadical= 'grupo_email_institucional';
    $strStaTipo = GrupoEmailRN::$TGE_INSTITUCIONAL;
  } else {
    $strInstitucional = '';
    $strRadical= 'grupo_email';
    $strStaTipo = GrupoEmailRN::$TGE_UNIDADE;
  }

  PaginaSEI::getInstance()->verificarSelecao($strRadical.'_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

	//PaginaSEI::getInstance()->salvarCamposPost(array('selUnidade','selStaGrupo'));
	
	$objGrupoEmailDTO = new GrupoEmailDTO();

	$strDesabilitar = '';

	$arrComandos = array();
	$arrAcoes = array();

	switch($_GET['acao']){
    case $strRadical.'_cadastrar':

      $strTitulo = 'Novo Grupo de E-mail '.$strInstitucional;

			$arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarGrupoEmail" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
			$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

			$strEmailAcoes = 'true, true';

			$objGrupoEmailDTO->setNumIdGrupoEmail(null);
			$objGrupoEmailDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
			$objGrupoEmailDTO->setStrNome($_POST['txtNome']);
			$objGrupoEmailDTO->setStrDescricao($_POST['txaDescricao']);
      $objGrupoEmailDTO->setStrStaTipo($strStaTipo);
			$objGrupoEmailDTO->setStrSinAtivo('S');

      $arr = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnEnderecosEletronicos']);

			$arrEnderecosEletronicos = array();

			foreach($arr as $linha){
				$objEmailGrupoEmailDTO = new EmailGrupoEmailDTO();
				$objEmailGrupoEmailDTO->setStrEmail($linha[1]);
				$objEmailGrupoEmailDTO->setStrDescricao($linha[2]);
				$arrEnderecosEletronicos[] = $objEmailGrupoEmailDTO;
			}

			$objGrupoEmailDTO->setArrObjEmailGrupoEmailDTO($arrEnderecosEletronicos);
			
			$strEnderecosEletronicos = $_POST['hdnEnderecosEletronicos'];
							
			if (isset($_POST['sbmCadastrarGrupoEmail'])) {
				try{
					$objGrupoEmailRN = new GrupoEmailRN();
					$objGrupoEmailDTO = $objGrupoEmailRN->cadastrar($objGrupoEmailDTO);
					PaginaSEI::getInstance()->setStrMensagem('Grupo de E-mail "'.$objGrupoEmailDTO->getStrNome().'" cadastrado com sucesso.');
					header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_grupo='.$objGrupoEmailDTO->getNumIdGrupoEmail().PaginaSEI::getInstance()->montarAncora($objGrupoEmailDTO->getNumIdGrupoEmail())));
					die;
				}catch(Exception $e){
					PaginaSEI::getInstance()->processarExcecao($e);
				}
			}
			break;

    case $strRadical.'_alterar':

      $strTitulo = 'Alterar Grupo de E-mail '.$strInstitucional;

			$arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarGrupoEmail" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
			$strDesabilitar = 'disabled="disabled"';
			
			$strEmailAcoes = 'true, true';

			if (isset($_GET['id_grupo_email'])){
				$objGrupoEmailDTO->setNumIdGrupoEmail($_GET['id_grupo_email']);
				$objGrupoEmailDTO->retTodos();
				$objGrupoEmailRN = new GrupoEmailRN();
				$objGrupoEmailDTO = $objGrupoEmailRN->consultar($objGrupoEmailDTO);
				if ($objGrupoEmailDTO==null){
					throw new InfraException("Registro não encontrado.");
				}

			} else {
				$objGrupoEmailDTO->setNumIdGrupoEmail($_POST['hdnIdGrupoEmail']);
				$objGrupoEmailDTO->setStrNome($_POST['txtNome']);
				$objGrupoEmailDTO->setStrDescricao($_POST['txaDescricao']);

				$arr = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnEnderecosEletronicos']);

				$arrEnderecosEletronicos = array();

				foreach($arr as $linha){

					$objEmailGrupoEmailDTO = new EmailGrupoEmailDTO();
					$objEmailGrupoEmailDTO->setStrEmail($linha[1]);
					$objEmailGrupoEmailDTO->setStrDescricao($linha[2]);
					$arrEnderecosEletronicos[] = $objEmailGrupoEmailDTO;
				}

				$objGrupoEmailDTO->setArrObjEmailGrupoEmailDTO($arrEnderecosEletronicos);

				$strEnderecosEletronicos = $_POST['hdnEnderecosEletronicos'];

			}

			$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objGrupoEmailDTO->getNumIdGrupoEmail())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

			if (isset($_POST['sbmAlterarGrupoEmail'])) {
				try{
					$objGrupoEmailRN = new GrupoEmailRN();
					$objGrupoEmailRN->alterar($objGrupoEmailDTO);
					PaginaSEI::getInstance()->setStrMensagem('Grupo de E-mail "'.$objGrupoEmailDTO->getStrNome().'" alterado com sucesso.');
					header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objGrupoEmailDTO->getNumIdGrupoEmail())));
					die;
				}catch(Exception $e){
					PaginaSEI::getInstance()->processarExcecao($e);
				}
			}
			break;

    case $strRadical.'_consultar':

      $strTitulo = 'Consultar Grupo de E-mail '.$strInstitucional;

      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_grupo_email'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      
      $strEmailAcoes = 'false, false';
      
      $objGrupoEmailDTO->setNumIdGrupoEmail($_GET['id_grupo_email']);
			$objGrupoEmailDTO->setBolExclusaoLogica(false);
      $objGrupoEmailDTO->retTodos();      

      $objGrupoEmailRN = new GrupoEmailRN();
      $objGrupoEmailDTO = $objGrupoEmailRN->consultar($objGrupoEmailDTO);

      if ($objGrupoEmailDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

     
  if ($objGrupoEmailDTO->getNumIdGrupoEmail()!=null && !isset($_POST['hdnEnderecosEletronicos'])){
	  $objEmailGrupoEmailDTO = new EmailGrupoEmailDTO();
		$objEmailGrupoEmailDTO->retTodos();
		$objEmailGrupoEmailDTO->setNumIdGrupoEmail($objGrupoEmailDTO->getNumIdGrupoEmail());
		$objEmailGrupoEmailDTO->setOrdStrDescricao(InfraDTO::$TIPO_ORDENACAO_DESC); 
				
		$objEmailGrupoEmailRN = new EmailGrupoEmailRN();
								
		$objEmailGrupoEmailDTO = $objEmailGrupoEmailRN->listar($objEmailGrupoEmailDTO);
			
		$arrEnderecosEletronicos = array();
				
		foreach($objEmailGrupoEmailDTO as $objEmailGrupoEmailDTOBanco){
		  $arrEnderecosEletronicos[] = array($objEmailGrupoEmailDTOBanco->getNumIdEmailGrupoEmail(),$objEmailGrupoEmailDTOBanco->getStrEmail(),$objEmailGrupoEmailDTOBanco->getStrDescricao());
		}
					
		$strEnderecosEletronicos = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrEnderecosEletronicos);
  }

  $strVisualizarAdicionar = '';
  if ($_GET['acao']==$strRadical.'_consultar'){
    $strVisualizarAdicionar = 'display:none;';
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

#divGeral {}
#lblNome {position:absolute;left:0%;top:0%;width:70.5%;}
#txtNome {position:absolute;left:0%;top:14%;width:70.5%;}

#lblDescricao {position:absolute;left:0%;top:32%;width:70.5%;}
#txaDescricao {position:absolute;left:0%;top:46%;width:70.5%;}

#divAdicionarEmail {<?=$strVisualizarAdicionar?>}

#lblEmail {position:absolute;left:0%;top:0%;width:34%;}
#txtEmail {position:absolute;left:0%;top:40%;width:34%;}

#lblDescricaoEmail {position:absolute;left:36.5%;top:0%;width:34%;}
#txtDescricaoEmail {position:absolute;left:36.5%;top:40%;width:34%;}

#sbmGravarEmail {position:absolute;left:72.5%;top:40%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

function inicializar(){
  <? if ($_GET['acao']==$strRadical.'_cadastrar'){ ?>
    document.getElementById('txtNome').focus();
  <?} else if ($_GET['acao']==$strRadical.'_consultar'){ ?>
    infraDesabilitarCamposAreaDados();
  <?}else{?>
    document.getElementById('btnCancelar').focus();
  <?}?>
 
  objTabelaEnderecosEletronicos = new infraTabelaDinamica('tblEnderecosEletronicos','hdnEnderecosEletronicos', <?=$strEmailAcoes?>);
  objTabelaEnderecosEletronicos.alterar = function(arr){
    document.getElementById('hdnIdEmail').value = arr[0];
    document.getElementById('txtEmail').value = arr[1];
    document.getElementById('txtDescricaoEmail').value = arr[2];
  };
  
  objTabelaEnderecosEletronicos.remover = function(arr){
    return true;
  }
  
  objTabelaEnderecosEletronicos.gerarEfeitoTabela=true;  
  
  <? foreach(array_keys($arrAcoes) as $id) { ?>
  objTabelaEnderecosEletronicos.adicionarAcoes('<?=$id?>','<?=$arrAcoes[$id]?>');  
  <? } ?>
   

  infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarCadastro();
}

function validarCadastro() {

  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }
    
  return true;
}

function transportarEmail(){

  //VALIDAÇÕES
		
	if (infraTrim(document.getElementById('txtEmail').value)=='') {
		alert('E-mail não informado.');
		document.getElementById('txtEmail').focus();
		return false;
	}
	
	if (infraTrim(document.getElementById('txtDescricaoEmail').value)=='') {
		alert('Descrição de E-mail não informada.');
		document.getElementById('txtDescricaoEmail').focus();
		return false;
	}
		
	if (!infraValidarEmail(infraTrim(document.getElementById('txtEmail').value))){
		alert('E-mail inválido.');
		document.getElementById('txtEmail').focus();
		return false;
	}
	
  var id = ((document.getElementById('hdnIdEmail').value!='') ? document.getElementById('hdnIdEmail').value : 'NOVO' + (new Date()).getTime());
	var email = document.getElementById('txtEmail').value;
	var descricaoEmail = document.getElementById('txtDescricaoEmail').value;
	
  objTabelaEnderecosEletronicos.adicionar([id, email, descricaoEmail]);
  
  //depois de incluir limpa os input
	document.getElementById('txtEmail').value = '';
	document.getElementById('txtDescricaoEmail').value = '';
	document.getElementById('hdnIdEmail').value = '';
	document.getElementById('txtEmail').focus();
}

function transportarEmailEnter(event){
    if (event.keyCode==13){
      transportarEmail();
      return false;
    }
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();

?>
  <div id="divGeral" class="infraAreaDados" style="height:15em;">
    <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objGrupoEmailDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblDescricao" for="txaDescricao" class="infraLabelOpcional">Descrição do Grupo:</label>
    <textarea id="txaDescricao" name="txaDescricao" rows="3" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,300);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objGrupoEmailDTO->getStrDescricao());?></textarea>
  </div>

  <div id="divAdicionarEmail" class="infraAreaDados" style="height:5em;">
    <label id="lblEmail" for="txtEmail" accesskey="E" class="infraLabelOpcional"><span class="infraTeclaAtalho">E</span>-mail:</label>
    <input type="text" id="txtEmail" name="txtEmail" class="infraText"  onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>

    <label id="lblDescricaoEmail" for="txtDescricaoEmail" accesskey="" class="infraLabelOpcional">Descrição do E-mail:</label>
    <input type="text" id="txtDescricaoEmail" name="txtDescricaoEmail" class="infraText"  onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>

    <input type="button" id="sbmGravarEmail" name="sbmGravarEmail"  class="infraButton" value="Adicionar E-mail" onclick="transportarEmail();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>

    <input type="hidden" id="hdnIdGrupoEmail" name="hdnIdGrupoEmail" value="<?=$objGrupoEmailDTO->getNumIdGrupoEmail();?>" />
  </div>

	<div id="divTabelaEnderecosEletronicos" class="infraAreaTabela" >
	
		<table  id="tblEnderecosEletronicos" width="85%" class="infraTable">
			<tr>
			  <th style="display:none;">ID</th>
				<th class="infraTh" width="42%">E-mail</th>
				<th class="infraTh" width="42%" align="left">Descrição</th>
				<th class="infraTh">Ações</th>
			</tr>
	  </table>
	  
	   <input type="hidden" id="hdnIdEmail" name="hdnIdEmail" value=""/>
	  <input type="hidden" id="hdnEnderecosEletronicos" name="hdnEnderecosEletronicos" value="<?=$strEnderecosEletronicos;?>"/>
	  
	</div>   
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>