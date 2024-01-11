<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
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

  PaginaSEI::getInstance()->verificarSelecao('assinante_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objAssinanteDTO = new AssinanteDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'assinante_cadastrar':
      $strTitulo = 'Nova Assinatura de Unidade';
      
      
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarAssinante" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objAssinanteDTO->setNumIdAssinante(null);
      $objAssinanteDTO->setNumIdOrgao($_POST['selOrgao']);
      $objAssinanteDTO->setStrCargoFuncao($_POST['txtCargoFuncao']);
      
      $arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);
      $arrObjRelAssinanteUnidadeDTO = array();
      foreach($arrUnidades as $unidade){
        $objRelAssinanteUnidadeDTO = new RelAssinanteUnidadeDTO();
        $objRelAssinanteUnidadeDTO->setNumIdUnidade($unidade);
        $arrObjRelAssinanteUnidadeDTO[] = $objRelAssinanteUnidadeDTO;
      }
      $objAssinanteDTO->setArrObjRelAssinanteUnidadeDTO($arrObjRelAssinanteUnidadeDTO);
      
      
      if (isset($_POST['sbmCadastrarAssinante'])) {
        try{
          $objAssinanteRN = new AssinanteRN();
          $objAssinanteDTO = $objAssinanteRN->cadastrarRN1335($objAssinanteDTO);
          //PaginaSEI::getInstance()->setStrMensagem('Assinatura da Unidade cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_assinante='.$objAssinanteDTO->getNumIdAssinante().PaginaSEI::getInstance()->montarAncora($objAssinanteDTO->getNumIdAssinante())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

      
    case 'assinante_alterar':
      $strTitulo = 'Alterar Assinatura de Unidade';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarAssinante" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      
      if (isset($_GET['id_assinante'])){
        $objAssinanteDTO->setNumIdAssinante($_GET['id_assinante']);
        $objAssinanteDTO->retTodos(true);
        $objAssinanteRN = new AssinanteRN();
        $objAssinanteDTO = $objAssinanteRN->consultarRN1338($objAssinanteDTO);
        if ($objAssinanteDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objAssinanteDTO->setNumIdAssinante($_POST['hdnIdAssinante']);
        $objAssinanteDTO->setStrCargoFuncao($_POST['txtCargoFuncao']);
        $objAssinanteDTO->setNumIdOrgao($_POST['hdnIdOrgao']);
        
        $arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);
        $arrObjRelAssinanteUnidadeDTO = array();
        foreach($arrUnidades as $unidade){
          $objRelAssinanteUnidadeDTO = new RelAssinanteUnidadeDTO();
          $objRelAssinanteUnidadeDTO->setNumIdUnidade($unidade);
          $arrObjRelAssinanteUnidadeDTO[] = $objRelAssinanteUnidadeDTO;
        }
        $objAssinanteDTO->setArrObjRelAssinanteUnidadeDTO($arrObjRelAssinanteUnidadeDTO);
        
      }

      
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objAssinanteDTO->getNumIdAssinante())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarAssinante'])) {
        try{
          $objAssinanteRN = new AssinanteRN();
          $objAssinanteRN->alterarRN1336($objAssinanteDTO);
          PaginaSEI::getInstance()->setStrMensagem('Assinatura de Unidade alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objAssinanteDTO->getNumIdAssinante())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'assinante_consultar':
      $strTitulo = 'Consultar Assinatura da Unidade';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_assinante'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objAssinanteDTO->setNumIdAssinante($_GET['id_assinante']);
      $objAssinanteDTO->setBolExclusaoLogica(false);
      $objAssinanteDTO->retTodos(true);
      $objAssinanteRN = new AssinanteRN();
      $objAssinanteDTO = $objAssinanteRN->consultarRN1338($objAssinanteDTO);
      if ($objAssinanteDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;
    
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('null','&nbsp;',$objAssinanteDTO->getNumIdOrgao());
  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');
  $strLinkUnidadeSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_orgao&tipo_selecao=2&id_object=objLupaUnidades&id_orgao='.$objAssinanteDTO->getNumIdOrgao());
  $strItensSelUnidade = RelAssinanteUnidadeINT::montarSelectUnidades(null,null,null,$objAssinanteDTO->getNumIdAssinante());

  if ($objAssinanteDTO->getNumIdOrgao()===null){
    $strDisplayUnidades = 'display:none;';
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

#lblCargoFuncao {position:absolute;left:0%;top:0%;}
#txtCargoFuncao {position:absolute;left:0%;top:38%;width:69%;}

#lblOrgao {position:absolute;left:0%;top:0%;width:25%;}
#selOrgao {position:absolute;left:0%;top:38%;width:25%;}

#divUnidades {<?=$strDisplayUnidades?>}
#lblUnidades {position:absolute;left:0%;top:0%;width:70%;}
#txtUnidade {position:absolute;left:0%;top:10%;width:50%;}
#selUnidades {position:absolute;left:0%;top:21%;width:70%;}
#imgLupaUnidades {position:absolute;left:71%;top:21%;}
#imgExcluirUnidades {position:absolute;left:71%;top:31%;}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objLupaUnidades = null;
var objAutoCompletarUnidade = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='assinante_cadastrar'){
    if (infraTrim(document.getElementById('txtCargoFuncao').value)==''){
      document.getElementById('txtCargoFuncao').focus();
    }else if (!infraSelectSelecionado(document.getElementById('selOrgao'))){
      document.getElementById('selOrgao').focus();
    }else{
      document.getElementById('txtUnidade').focus();
    }
  } else if ('<?=$_GET['acao']?>'=='assinante_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  
  
  objLupaUnidades = new infraLupaSelect('selUnidades','hdnUnidades','<?=$strLinkUnidadeSelecao?>');
  
  
  objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
  //objAutoCompletarUnidade.maiusculas = true;
  //objAutoCompletarUnidade.mostrarAviso = true;
  //objAutoCompletarUnidade.tempoAviso = 1000;
  //objAutoCompletarUnidade.tamanhoMinimo = 3;
  objAutoCompletarUnidade.limparCampo = true;
  //objAutoCompletarUnidade.bolExecucaoAutomatica = false;

  objAutoCompletarUnidade.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUnidade').value+'&id_orgao=<?=$objAssinanteDTO->getNumIdOrgao()?>';
  };
  
  objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objLupaUnidades.adicionar(id,descricao,document.getElementById('txtUnidade'));
    }
  };

  infraEfeitoTabelas();
}

function validarCadastroRI1345() {

  if (infraTrim(document.getElementById('txtCargoFuncao').value)=='') {
    alert('Informe Cargo/Função.');
    document.getElementById('txtCargoFuncao').focus();
    return false;
  }

  if (!infraSelectSelecionado(document.getElementById('selOrgao'))) {
    alert('Selecione um Órgão.');
    document.getElementById('selOrgao').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastroRI1345();
}

function trocarOrgao(){
  objLupaUnidades.limpar();
  document.getElementById('frmAssinanteCadastro').submit();
}


<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAssinanteCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>

  <div id="divCargoFuncao" class="infraAreaDados" style="height:5em;">
    <label id="lblCargoFuncao" for="txtCargoFuncao" accesskey="F" class="infraLabelObrigatorio">Cargo / <span class="infraTeclaAtalho">F</span>unção:</label>
    <input type="text" id="txtCargoFuncao" name="txtCargoFuncao" class="infraText" value="<?=PaginaSEI::tratarHTML($objAssinanteDTO->getStrCargoFuncao())?>" onkeypress="return infraMascaraTexto(this,event,200);" maxlength="200" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>

  <div id="divOrgao" class="infraAreaDados" style="height:5em;">
    <label id="lblOrgao" for="selOrgao" accesskey="r" class="infraLabelObrigatorio">Ó<span class="infraTeclaAtalho">r</span>gão:</label>
    <select id="selOrgao" name="selOrgao" onchange="trocarOrgao()" <?=$strDesabilitar?> class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgao?>
    </select>
  </div>

  <div id="divUnidades" class="infraAreaDados" style="height:22em;">
    <label id="lblUnidades" for="selUnidades" class="infraLabelObrigatorio">Unidades:</label>
    <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value="" />
    <select id="selUnidades" name="selUnidades" size="10" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelUnidade?>
    </select>
    <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Unidades" title="Selecionar Unidades" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>

  <input type="hidden" id="hdnIdAssinante" name="hdnIdAssinante" value="<?=$objAssinanteDTO->getNumIdAssinante();?>" />
  <input type="hidden" id="hdnIdOrgao" name="hdnIdOrgao" value="<?=$objAssinanteDTO->getNumIdOrgao();?>" />
  <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?=$_POST['hdnUnidades']?>" />

  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>