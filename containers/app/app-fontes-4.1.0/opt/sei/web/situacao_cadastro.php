<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/09/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
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

  PaginaSEI::getInstance()->verificarSelecao('situacao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objSituacaoDTO = new SituacaoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'situacao_cadastrar':
      $strTitulo = 'Novo Ponto de Controle';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarSituacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objSituacaoDTO->setNumIdSituacao(null);
      $objSituacaoDTO->setStrNome($_POST['txtNome']);
      $objSituacaoDTO->setStrDescricao($_POST['txaDescricao']);
      $objSituacaoDTO->setStrSinAtivo('S');

      $arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);
      $arrObjRelSituacaoUnidadeDTO = array();
      foreach($arrUnidades as $unidade){
        $objRelSituacaoUnidadeDTO = new RelSituacaoUnidadeDTO();
        $objRelSituacaoUnidadeDTO->setNumIdUnidade($unidade);
        $arrObjRelSituacaoUnidadeDTO[] = $objRelSituacaoUnidadeDTO;
      }
      $objSituacaoDTO->setArrObjRelSituacaoUnidadeDTO($arrObjRelSituacaoUnidadeDTO);


      if (isset($_POST['sbmCadastrarSituacao'])) {
        try{
          $objSituacaoRN = new SituacaoRN();
          $objSituacaoDTO = $objSituacaoRN->cadastrar($objSituacaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Ponto de Controle "'.$objSituacaoDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_situacao='.$objSituacaoDTO->getNumIdSituacao().PaginaSEI::getInstance()->montarAncora($objSituacaoDTO->getNumIdSituacao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'situacao_alterar':
      $strTitulo = 'Alterar Ponto de Controle';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarSituacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_situacao'])){
        $objSituacaoDTO->setNumIdSituacao($_GET['id_situacao']);
        $objSituacaoDTO->setBolExclusaoLogica(false);
        $objSituacaoDTO->retTodos();
        $objSituacaoRN = new SituacaoRN();
        $objSituacaoDTO = $objSituacaoRN->consultar($objSituacaoDTO);
        if ($objSituacaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objSituacaoDTO->setNumIdSituacao($_POST['hdnIdSituacao']);
        $objSituacaoDTO->setStrNome($_POST['txtNome']);
        $objSituacaoDTO->setStrDescricao($_POST['txaDescricao']);
        //$objSituacaoDTO->setStrSinAtivo('S');

        $arrUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);
        $arrObjRelSituacaoUnidadeDTO = array();
        foreach($arrUnidades as $unidade){
          $objRelSituacaoUnidadeDTO = new RelSituacaoUnidadeDTO();
          $objRelSituacaoUnidadeDTO->setNumIdUnidade($unidade);
          $arrObjRelSituacaoUnidadeDTO[] = $objRelSituacaoUnidadeDTO;
        }
        $objSituacaoDTO->setArrObjRelSituacaoUnidadeDTO($arrObjRelSituacaoUnidadeDTO);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objSituacaoDTO->getNumIdSituacao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarSituacao'])) {
        try{
          $objSituacaoRN = new SituacaoRN();
          $objSituacaoRN->alterar($objSituacaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Ponto de Controle "'.$objSituacaoDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objSituacaoDTO->getNumIdSituacao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'situacao_consultar':
      $strTitulo = 'Consultar Ponto de Controle';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_situacao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objSituacaoDTO->setNumIdSituacao($_GET['id_situacao']);
      $objSituacaoDTO->setBolExclusaoLogica(false);
      $objSituacaoDTO->retTodos();
      $objSituacaoRN = new SituacaoRN();
      $objSituacaoDTO = $objSituacaoRN->consultar($objSituacaoDTO);
      if ($objSituacaoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');
  $strLinkUnidadeSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidades');
  $strItensSelUnidade = RelSituacaoUnidadeINT::montarSelectUnidades(null,null,null,$objSituacaoDTO->getNumIdSituacao());

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

#lblNome {position:absolute;left:0%;top:0%;width:50%;}
#txtNome {position:absolute;left:0%;top:4%;width:50%;}

#lblDescricao {position:absolute;left:0%;top:10%;width:70%;}
#txaDescricao {position:absolute;left:0%;top:14%;width:70%;}

#lblUnidades {position:absolute;left:0%;top:27%;width:70%;}
#txtUnidade {position:absolute;left:0%;top:31%;width:50%;}
#selUnidades {position:absolute;left:0%;top:36.5%;width:70%;}
#imgLupaUnidades {position:absolute;left:71%;top:36.5%;}
#imgExcluirUnidades {position:absolute;left:71%;top:41%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objLupaUnidades = null;
var objAutoCompletarUnidade = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='situacao_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='situacao_consultar'){
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
  return 'palavras_pesquisa='+document.getElementById('txtUnidade').value;
  };

  objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
    if (id!=''){
       objLupaUnidades.adicionar(id,descricao,document.getElementById('txtUnidade'));
    }
  };
}

function validarCadastro() {

  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
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
<form id="frmSituacaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('50em');
?>

  <label id="lblNome" for="txtNome" accesskey="n" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objSituacaoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txaDescricao" accesskey="d" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="3"  class="infraTextarea"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objSituacaoDTO->getStrDescricao());?></textarea>

  <label id="lblUnidades" for="selUnidades" class="infraLabelObrigatorio">Unidades:</label>
  <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value="" />
  <select id="selUnidades" name="selUnidades" size="20" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelUnidade?>
  </select>
  <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Unidades" title="Selecionar Unidades" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />


  <input type="hidden" id="hdnIdSituacao" name="hdnIdSituacao" value="<?=$objSituacaoDTO->getNumIdSituacao();?>" />
  <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?=$_POST['hdnUnidades']?>" />
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