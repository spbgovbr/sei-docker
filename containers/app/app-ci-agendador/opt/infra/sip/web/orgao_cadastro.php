<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 27/11/2006 - criado por mga
*
*
*/

try {
  require_once dirname(__FILE__).'/Sip.php';

  session_start();
	
  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

	
  //SessaoSip::getInstance()->validarSessao();
  SessaoSip::getInstance()->validarLink();

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  $objOrgaoDTO = new OrgaoDTO();

  $arrComandos = array();

  $strDesabilitarCodigo = '';
  
  switch($_GET['acao']){
    case 'orgao_cadastrar':
      $strTitulo = 'Novo Órgão';
      $arrComandos[] = '<input type="submit" name="sbmCadastrarOrgao" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_listar').'\';" class="infraButton" />';
			
			$objOrgaoDTO->setNumIdOrgao(null);
			$objOrgaoDTO->setStrSigla($_POST['txtSigla']);
			$objOrgaoDTO->setStrDescricao($_POST['txtDescricao']);

      if (!isset($_POST['txtOrdem'])){
        $objOrgaoDTO->setNumOrdem(0);
      }else{
        $objOrgaoDTO->setNumOrdem($_POST['txtOrdem']);
      }

      $objOrgaoDTO->setStrSinAutenticar('S');
			$objOrgaoDTO->setStrSinAtivo('S');
			
			$arrObjRelOrgaoAutenticacaoDTO = array();
			$arrServidoresAutenticacao = PaginaSip::getInstance()->getArrValuesSelect($_POST['hdnServidoresAutenticacao']);
			for($i=0; $i< count($arrServidoresAutenticacao) ;$i++){
			  $objRelOrgaoAutenticacao  = new RelOrgaoAutenticacaoDTO();
			  $objRelOrgaoAutenticacao->setNumIdOrgao(null);
			  $objRelOrgaoAutenticacao->setNumIdServidorAutenticacao($arrServidoresAutenticacao[$i]);
			  $objRelOrgaoAutenticacao->setNumSequencia($i);
			  $arrObjRelOrgaoAutenticacaoDTO[] = $objRelOrgaoAutenticacao;
			}
			$objOrgaoDTO->setArrObjRelOrgaoAutenticacaoDTO($arrObjRelOrgaoAutenticacaoDTO);
				
			
      if (isset($_POST['sbmCadastrarOrgao'])) {
				try{
					$objOrgaoRN = new OrgaoRN();
					$objOrgaoDTO = $objOrgaoRN->cadastrar($objOrgaoDTO);
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_listar'.PaginaSip::getInstance()->montarAncora($objOrgaoDTO->getNumIdOrgao())));
					die;
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
      }
      break;

    case 'orgao_alterar':
      $strTitulo = 'Alterar Órgão';
      $arrComandos[] = '<input type="submit" name="sbmAlterarOrgao" value="Salvar" class="infraButton" />';
      $arrComandos[] = '<input type="button" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_listar'.PaginaSip::getInstance()->montarAncora($_GET['id_orgao'])).'\';" class="infraButton" />';
			
      $strDesabilitarCodigo = 'disabled="disabled"';
      
			if (isset($_GET['id_orgao'])){
        $objOrgaoDTO->setNumIdOrgao($_GET['id_orgao']);
        $objOrgaoDTO->retTodos();
        $objOrgaoRN = new OrgaoRN();
        $objOrgaoDTO = $objOrgaoRN->consultar($objOrgaoDTO);
        if ($objOrgaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
			} else {
				$objOrgaoDTO->setNumIdOrgao($_POST['hdnIdOrgao']);
				$objOrgaoDTO->setStrSigla($_POST['txtSigla']);
				$objOrgaoDTO->setStrDescricao($_POST['txtDescricao']);
        $objOrgaoDTO->setNumOrdem($_POST['txtOrdem']);
				$objOrgaoDTO->setStrSinAtivo("S");
			}
			
			$arrObjRelOrgaoAutenticacaoDTO = array();
			$arrServidoresAutenticacao = PaginaSip::getInstance()->getArrValuesSelect($_POST['hdnServidoresAutenticacao']);
			for($i=0; $i< count($arrServidoresAutenticacao) ;$i++){
			  $objRelOrgaoAutenticacao = new RelOrgaoAutenticacaoDTO();
			  $objRelOrgaoAutenticacao->setNumIdOrgao(null);
			  $objRelOrgaoAutenticacao->setNumIdServidorAutenticacao($arrServidoresAutenticacao[$i]);
			  $objRelOrgaoAutenticacao->setNumSequencia($i);
			  $arrObjRelOrgaoAutenticacaoDTO[] = $objRelOrgaoAutenticacao;
			}
			$objOrgaoDTO->setArrObjRelOrgaoAutenticacaoDTO($arrObjRelOrgaoAutenticacaoDTO);
				
			
      if (isset($_POST['sbmAlterarOrgao'])) {
				try{
					$objOrgaoRN = new OrgaoRN();
					$objOrgaoRN->alterar($objOrgaoDTO);
					header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_listar'.PaginaSip::getInstance()->montarAncora($objOrgaoDTO->getNumIdOrgao())));
					die;
				}catch(Exception $e){
					PaginaSip::getInstance()->processarExcecao($e);
				}
				$objOrgaoDTO->setStrSinAutenticar($_POST['hdnSinAutenticar']);
      }
      break;

    case 'orgao_consultar':
      $strTitulo = "Consultar Órgão";
      $arrComandos[] = '<input type="button" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao=orgao_listar'.PaginaSip::getInstance()->montarAncora($_GET['id_orgao'])).'\';" class="infraButton" />';
      $objOrgaoDTO->setNumIdOrgao($_GET['id_orgao']);
      $objOrgaoDTO->retTodos();
      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultar($objOrgaoDTO);
      if ($objOrgaoDTO==null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  
  $strLinkServidoresAutenticacaoSelecao = SessaoSip::getInstance()->assinarLink('controlador.php?acao=servidor_autenticacao_selecionar&tipo_selecao=2&id_object=objLupaServidoresAutenticacao');
  $strItensSelServidoresAutenticacao = RelOrgaoAutenticacaoINT::montarSelectServidoresAutenticacao($objOrgaoDTO->getNumIdOrgao());

  $strAvisoAutenticacao = '';
  $strDisplayAvisoAutenticacao = 'display:none;';
  if ($objOrgaoDTO->getStrSinAutenticar()=='N'){
    $strAvisoAutenticacao = 'ATENÇÃO: Órgão não possui autenticação habilitada (sigla=senha).';
    $strDisplayAvisoAutenticacao = '';
  }

  
}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - Órgão');
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>

#lblSigla {position:absolute;left:0%;top:0%;width:15%;}
#txtSigla {position:absolute;left:0%;top:13%;width:15%;}

#lblDescricao {position:absolute;left:0%;top:32%;width:80%;}
#txtDescricao {position:absolute;left:0%;top:45%;width:80%;}

#lblOrdem {position:absolute;left:0%;top:65%;width:10%;}
#txtOrdem {position:absolute;left:0%;top:77%;width:10%;}
#ancAjudaOrdem {position:absolute;left:11.5%;top:77.2%;}

#divAvisoAutenticacao {<?=$strDisplayAvisoAutenticacao?>}
#lblAvisoAutenticacao {position:absolute;left:0%;top:10%;color:white;background-color:red;padding:.2em 1em;}

#lblServidoresAutenticacao {position:absolute;left:0%;top:0%;}
#selServidoresAutenticacao {position:absolute;left:0%;top:13%;width:50%;}
#divOpcoesServidoresAutenticacao {position:absolute;left:51%;top:13%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>

var objLupaServidoresAutenticacao = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='orgao_cadastrar'){
    document.getElementById('txtSigla').focus();
  } else if ('<?=$_GET['acao']?>'=='orgao_consultar'){
    infraDesabilitarCamposAreaDados();
  }
  
  objLupaServidoresAutenticacao = new infraLupaSelect('selServidoresAutenticacao','hdnServidoresAutenticacao','<?=$strLinkServidoresAutenticacaoSelecao?>');
  
}

function OnSubmitForm() {

  if (infraTrim(document.getElementById('txtSigla').value)=='') {
    alert('Informe a Sigla.');
    document.getElementById('txtSigla').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txtDescricao').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtOrdem').value)==''){
    alert('Informe a Ordem.');
    document.getElementById('txtOrdem').focus();
    return false;
  }

  return true;
}

<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmOrgaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('orgao_cadastro.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSip::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
?>
  <div id="divGeral" class="infraAreaDados" style="height:15em;">
    <label id="lblSigla" for="txtSigla" accessKey="S" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">S</span>igla:</label>
    <input type="text" id="txtSigla" name="txtSigla" class="infraText" value="<?=PaginaSip::tratarHTML($objOrgaoDTO->getStrSigla());?>" maxlength="30" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  
    <label id="lblDescricao" for="txtDescricao" accessKey="D" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">D</span>escrição:</label>
    <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSip::tratarHTML($objOrgaoDTO->getStrDescricao());?>" maxlength="250" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

    <label id="lblOrdem" for="txtOrdem" class="infraLabelObrigatorio">Ordem:</label>
    <input type="text" id="txtOrdem" name="txtOrdem" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="<?=PaginaSip::tratarHTML($objOrgaoDTO->getNumOrdem());?>" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
    <a href="javascript:void(0);" id="ancAjudaOrdem" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" onmouseover="return infraTooltipMostrar('<?=PaginaSip::tratarHTML(PaginaSip::formatarParametrosJavaScript('Permite alterar a posição do órgão na lista da tela de login. Órgãos compartilhando o mesmo valor para este campo serão ordenados alfabeticamente pela sigla.'))?>','',200);" onmouseout="return infraTooltipOcultar();"><img src="<?=PaginaSip::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>

  </div>

  <div id="divAvisoAutenticacao" class="infraAreaDados" style="height:3.5em;">
    <label id="lblAvisoAutenticacao" class="infraLabelObrigatorio"><?=$strAvisoAutenticacao?></label>
  </div>
  
  <div id="divServidoresAutenticacao" class="infraAreaDados" style="height:15em;">	
  	<label id="lblServidoresAutenticacao" for="selServidoresAutenticacao" class="infraLabelOpcional">Servidores de Autenticação Associados:</label>
    <select id="selServidoresAutenticacao" name="selServidoresAutenticacao" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"  >
    <?=$strItensSelServidoresAutenticacao?>
    </select>
    <div id="divOpcoesServidoresAutenticacao">
      <img id="imgPesquisarServidoresAutenticacao" onclick="objLupaServidoresAutenticacao.selecionar(700,500);" src="<?=PaginaSip::getInstance()->getIconePesquisar()?>" alt="Pesquisa de Servidores de Autenticação" title="Pesquisa de Servidores de Autenticação" class="infraImg" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
      <img id="imgRemoverServidoresAutenticacao" onclick="objLupaServidoresAutenticacao.remover();" src="<?=PaginaSip::getInstance()->getIconeRemover()?>" alt="Remover Servidores de Autenticação Selecionados" title="Remover Servidores de Autenticação Selecionados" class="infraImg" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
      <br />
      <img id="imgServidoresAutenticacaoAcima" onclick="objLupaServidoresAutenticacao.moverAcima();" src="<?=PaginaSip::getInstance()->getIconeMoverAcima()?>" alt="Mover Acima Servidor de Autenticação Selecionado" title="Mover Acima Servidor de Autenticação Selecionado" class="infraImg" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
      <img id="imgServidoresAutenticacaoAbaixo" onclick="objLupaServidoresAutenticacao.moverAbaixo();" src="<?=PaginaSip::getInstance()->getIconeMoverAbaixo()?>" alt="Mover Abaixo Servidor de Autenticação Selecionado" title="Mover Abaixo Servidor de Autenticação Selecionado" class="infraImg" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
    </div>
  </div>
  
  <input type="hidden" name="hdnIdOrgao" value="<?=$objOrgaoDTO->getNumIdOrgao();?>" />
  <input type="hidden" id="hdnServidoresAutenticacao" name="hdnServidoresAutenticacao" value="<?=PaginaSip::tratarHTML($_POST['hdnServidoresAutenticacao'])?>" />
  <input type="hidden" id="hdnSinAutenticar" name="hdnSinAutenticar" value="<?=PaginaSip::tratarHTML($objOrgaoDTO->getStrSinAutenticar())?>" />
  <?
  //PaginaSip::getInstance()->montarAreaDebug();
  //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>