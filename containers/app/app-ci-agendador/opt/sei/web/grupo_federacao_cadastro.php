<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/12/2019 - criado por mga
*
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  if(strpos($_GET['acao'],'grupo_federacao_institucional')===0){
    $strInstitucional = 'Institucional';
    $strRadical= 'grupo_federacao_institucional';
    $strStaTipo = GrupoFederacaoRN::$TGF_INSTITUCIONAL;
  } else {
    $strInstitucional = '';
    $strRadical= 'grupo_federacao';
    $strStaTipo = GrupoFederacaoRN::$TGF_UNIDADE;
  }

  PaginaSEI::getInstance()->verificarSelecao($strRadical.'_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objGrupoFederacaoDTO = new GrupoFederacaoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case $strRadical.'_cadastrar':

      $strTitulo = 'Novo Grupo do SEI Federação '.$strInstitucional;

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarGrupoFederacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
			$arrOpcoes = array();
			$arrObjDTOA = array();
			
	    $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnOrgaosFederacao']);

			for($x = 0;$x<count($arrOpcoes);$x++){
			  $objRelGrupoFedOrgaoFedDTO = new RelGrupoFedOrgaoFedDTO();
			  $objRelGrupoFedOrgaoFedDTO->setStrIdOrgaoFederacao($arrOpcoes[$x]);
			  $arrObjDTOA[$x] = $objRelGrupoFedOrgaoFedDTO;
			}
			$objGrupoFederacaoDTO->setArrObjRelGrupoFedOrgaoFedDTO($arrObjDTOA);
                          
      $objGrupoFederacaoDTO->setNumIdGrupoFederacao(null);
      $objGrupoFederacaoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objGrupoFederacaoDTO->setStrNome($_POST['txtNome']);
      $objGrupoFederacaoDTO->setStrDescricao($_POST['txaDescricao']);
      $objGrupoFederacaoDTO->setStrStaTipo($strStaTipo);
      $objGrupoFederacaoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarGrupoFederacao'])) {
        try{
          $objGrupoFederacaoRN = new GrupoFederacaoRN();
          $objGrupoFederacaoDTO = $objGrupoFederacaoRN->cadastrar($objGrupoFederacaoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Grupo do SEI Federação "'.$objGrupoFederacaoDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_grupo_federacao='.$objGrupoFederacaoDTO->getNumIdGrupoFederacao().'#ID-'.$objGrupoFederacaoDTO->getNumIdGrupoFederacao()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case $strRadical.'_alterar':

      $strTitulo = 'Alterar Grupo do SEI Federação '.$strInstitucional;

      $arrComandos[] = '<button type="submit" accesskey="S" id="sbmAlterarGrupoFederacao" name="sbmAlterarGrupoFederacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      $numIdGrupoFederacao = null;
      
      if (isset($_GET['id_grupo_federacao'])){
        $numIdGrupoFederacao = $_GET['id_grupo_federacao'];
      }else if (isset($_POST['selGrupoFederacao'])){
        $numIdGrupoFederacao = $_POST['selGrupoFederacao'];
      }
      
      if ($numIdGrupoFederacao!==null){
        $objGrupoFederacaoDTO->setNumIdGrupoFederacao($numIdGrupoFederacao);
        $objGrupoFederacaoDTO->retTodos();
        $objGrupoFederacaoRN = new GrupoFederacaoRN();
        $objGrupoFederacaoDTO = $objGrupoFederacaoRN->consultar($objGrupoFederacaoDTO);
        if ($objGrupoFederacaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objGrupoFederacaoDTO->setNumIdGrupoFederacao($_POST['hdnIdGrupoFederacao']);
        $objGrupoFederacaoDTO->setStrNome($_POST['txtNome']);
        $objGrupoFederacaoDTO->setStrDescricao($_POST['txaDescricao']);
      }
      
	    $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnOrgaosFederacao']);

			for($x = 0;$x<count($arrOpcoes);$x++){
			  $objRelGrupoFedOrgaoFedDTO = new RelGrupoFedOrgaoFedDTO();
			  $objRelGrupoFedOrgaoFedDTO->setStrIdOrgaoFederacao($arrOpcoes[$x]);
			  $arrObjDTOA[$x] = $objRelGrupoFedOrgaoFedDTO;
			}
			$objGrupoFederacaoDTO->setArrObjRelGrupoFedOrgaoFedDTO($arrObjDTOA);
      

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objGrupoFederacaoDTO->getNumIdGrupoFederacao().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarGrupoFederacao'])) {
        try{
          $objGrupoFederacaoRN = new GrupoFederacaoRN();
          $objGrupoFederacaoRN->alterar($objGrupoFederacaoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Grupo do SEI Federação "'.$objGrupoFederacaoDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objGrupoFederacaoDTO->getNumIdGrupoFederacao()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case $strRadical.'_consultar':

      $strTitulo = 'Consultar Grupo do SEI Federação '.$strInstitucional;

      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_grupo_federacao'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objGrupoFederacaoDTO->setNumIdGrupoFederacao($_GET['id_grupo_federacao']);
      $objGrupoFederacaoDTO->setBolExclusaoLogica(false);
      $objGrupoFederacaoDTO->retTodos();
      $objGrupoFederacaoRN = new GrupoFederacaoRN();
      $objGrupoFederacaoDTO = $objGrupoFederacaoRN->consultar($objGrupoFederacaoDTO);
      
      if ($objGrupoFederacaoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkOrgaosFederacao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_federacao_selecionar&tipo_selecao=2&id_object=objLupaOrgaosFederacao');
  $strItensSelGrupoFederacao = RelGrupoFedOrgaoFedINT::montarSelectGrupo($objGrupoFederacaoDTO->getNumIdGrupoFederacao());

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

#lblNome {position:absolute;left:0%;top:0%;width:20%;}
#txtNome {position:absolute;left:0%;top:5%;width:50%;}

#lblDescricao {position:absolute;left:0%;top:12%;width:50%;}
#txaDescricao {position:absolute;left:0%;top:17%;width:80%;}

#lblOrgaosFederacao {position:absolute;left:0%;top:29%;width:50%;}
#selOrgaosFederacao {position:absolute;left:0%;top:34%;width:80%;}

#imgLupaOrgaosFederacao {position:absolute;left:81%;top:34%;}
#imgExcluirOrgaosFederacao {position:absolute;left:81%;top:39%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

var objLupaOrgaosFederacao = null;

function inicializar(){

  <? if ($_GET['acao'] == $strRadical.'_cadastrar'){ ?>
    document.getElementById('txtNome').focus();
  <? } else if ($_GET['acao'] == $strRadical.'_consultar'){ ?>
    infraDesabilitarCamposAreaDados();
  <? } ?>

  objLupaOrgaosFederacao = new infraLupaSelect('selOrgaosFederacao','hdnOrgaosFederacao','<?=$strLinkOrgaosFederacao?>');

}

function OnSubmitForm() {
  return validarCadastroRI0494();
}

function validarCadastroRI0494() {
  
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
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
<form id="frmGrupoFederacaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('40em');
?>
  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objGrupoFederacaoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="2" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objGrupoFederacaoDTO->getStrDescricao());?></textarea>

  <label id="lblOrgaosFederacao" for="selOrgaosFederacao" accesskey="" class="infraLabelOpcional">Órgãos do SEI Federação:</label>
  <select id="selOrgaosFederacao" name="selOrgaosFederacao" size="12" multiple="multiple" class="infraSelect">
  	<?=$strItensSelGrupoFederacao?>
  </select>
  <img id="imgLupaOrgaosFederacao" onclick="objLupaOrgaosFederacao.selecionar(800,600);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Localizar Órgãos do SEI Federação" title="Localizar Órgãos do SEI Federação" class="infraImg" />
  <img id="imgExcluirOrgaosFederacao" onclick="objLupaOrgaosFederacao.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Órgãos do SEI Federação" title="Remover Órgãos do SEI Federação" class="infraImg" />

  <input type="hidden" id="hdnIdGrupoFederacao" name="hdnIdGrupoFederacao" value="<?=$objGrupoFederacaoDTO->getNumIdGrupoFederacao();?>" />
  <input type="hidden" id="hdnOrgaosFederacao" name="hdnOrgaosFederacao" value="<?=$_POST['hdnOrgaosFederacao']?>" />
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>