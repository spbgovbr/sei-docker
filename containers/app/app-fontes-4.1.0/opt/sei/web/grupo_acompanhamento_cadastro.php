<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/11/2010 - criado por jonatas_db
* 06/06/2018 - cjy - adição da opção/icone de acompanhamento especial
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

  PaginaSEI::getInstance()->verificarSelecao('grupo_acompanhamento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('pagina_simples', 'arvore', 'id_procedimento'));

  if (isset($_GET['pagina_simples'])) {
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

	if(isset($_GET['arvore'])){
		PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
   }
   
	$numIdProcedimento = '';   
	if(isset($_GET['id_procedimento'])){
  	$numIdProcedimento = $_GET['id_procedimento'];
  }
  
  $objGrupoAcompanhamentoDTO = new GrupoAcompanhamentoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  $bolOk = false;

  switch($_GET['acao']){
    case 'grupo_acompanhamento_cadastrar':
      $strTitulo = 'Novo Grupo de Acompanhamento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarGrupoAcompanhamento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if (PaginaSEI::getInstance()->getTipoPagina()!=InfraPagina::$TIPO_PAGINA_SIMPLES){
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }

      $objGrupoAcompanhamentoDTO->setNumIdGrupoAcompanhamento(null);
      $objGrupoAcompanhamentoDTO->setStrNome($_POST['txtNome']);
      $numIdUnidade = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
      if ($numIdUnidade!==''){
        $objGrupoAcompanhamentoDTO->setNumIdUnidade($numIdUnidade);
      }else{
        $objGrupoAcompanhamentoDTO->setNumIdUnidade(null);
      }

      if (isset($_POST['sbmCadastrarGrupoAcompanhamento'])) {
        try{
          $objGrupoAcompanhamentoRN = new GrupoAcompanhamentoRN();
          $objGrupoAcompanhamentoDTO = $objGrupoAcompanhamentoRN->cadastrar($objGrupoAcompanhamentoDTO);

          if (PaginaSEI::getInstance()->getAcaoRetorno()!='grupo_acompanhamento_listar'){
            $bolOk = true;
          }else {
            PaginaSEI::getInstance()->setStrMensagem('Grupo de Acompanhamento "'.$objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento().'" cadastrado com sucesso.');
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_grupo_acompanhamento='.$objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento().PaginaSEI::getInstance()->montarAncora($objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento())));
            die;
          }

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'grupo_acompanhamento_alterar':
      $strTitulo = 'Alterar Grupo de Acompanhamento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarGrupoAcompanhamento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_grupo_acompanhamento'])){
        $objGrupoAcompanhamentoDTO->setNumIdGrupoAcompanhamento($_GET['id_grupo_acompanhamento']);
        $objGrupoAcompanhamentoDTO->retTodos();
        $objGrupoAcompanhamentoRN = new GrupoAcompanhamentoRN();
        $objGrupoAcompanhamentoDTO = $objGrupoAcompanhamentoRN->consultar($objGrupoAcompanhamentoDTO);
        if ($objGrupoAcompanhamentoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objGrupoAcompanhamentoDTO->setNumIdGrupoAcompanhamento($_POST['hdnIdGrupoAcompanhamento']);
        $objGrupoAcompanhamentoDTO->setStrNome($_POST['txtNome']);
        $objGrupoAcompanhamentoDTO->setNumIdUnidade($_POST['selUnidade']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarGrupoAcompanhamento'])) {
        try{
          $objGrupoAcompanhamentoRN = new GrupoAcompanhamentoRN();
	        $numIdUnidade = SessaoSEI::getInstance()->getNumIdUnidadeAtual();
	      	if ($numIdUnidade!==''){
	        	$objGrupoAcompanhamentoDTO->setNumIdUnidade($numIdUnidade);
	      	}else{
	        	$objGrupoAcompanhamentoDTO->setNumIdUnidade(null);
	      	}          
          $objGrupoAcompanhamentoRN->alterar($objGrupoAcompanhamentoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Grupo de Acompanhamento "'.$objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento().'" alterado com sucesso.');
					header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_grupo_acompanhamento='.$objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento().PaginaSEI::getInstance()->montarAncora($objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento())));          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  //$strItensSelUnidade = UnidadeINT::montarSelect???????('null','&nbsp;',$objGrupoAcompanhamentoDTO->getNumIdUnidade());

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
#lblNome {position:absolute;left:0%;top:0%;width:75%;}
#txtNome {position:absolute;left:0%;top:40%;width:75%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){

  <?if ($bolOk){?>

    <? if ($_GET['arvore']=='1'){ ?>
    var sel = infraObterJanelaOrigemModal().document.getElementById('selGrupoAcompanhamento');
    <? }else{ ?>
    var sel = window.parent.document.getElementById('selGrupoAcompanhamento');
    <? } ?>

    infraSelectAdicionarOption(sel,'<?=PaginaSEI::tratarHTML($objGrupoAcompanhamentoDTO->getStrNome())?>','<?=$objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento()?>');
    infraSelectSelecionarItem(sel,'<?=$objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento()?>');
    self.setTimeout('infraFecharJanelaModal()',200);

  <?}else{?>

    if ('<?=$_GET['acao']?>'=='grupo_acompanhamento_cadastrar' || '<?=$_GET['acao']?>'=='grupo_acompanhamento_alterar'){
      document.getElementById('txtNome').focus();
    } else if ('<?=$_GET['acao']?>'=='grupo_acompanhamento_consultar'){
      infraDesabilitarCamposAreaDados();
    }else{
      document.getElementById('btnCancelar').focus();
    }
    infraEfeitoTabelas();
  <?}?>
}

function validarCadastroRI0013() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }
  return true;
}

function OnSubmitForm() {
  return validarCadastroRI0013();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmGrupoAcompanhamentoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('5em');
?>
  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objGrupoAcompanhamentoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdGrupoAcompanhamento" name="hdnIdGrupoAcompanhamento" value="<?=$objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento();?>" />
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