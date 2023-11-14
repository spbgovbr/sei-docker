<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/05/2012 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
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

  PaginaSEI::getInstance()->verificarSelecao('texto_padrao_interno_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selUnidade'));

  $objTextoPadraoInternoDTO = new TextoPadraoInternoDTO();

  $strDesabilitar = '';
  $readonly=false;
  $arrComandos = array();

  switch($_GET['acao']){
    case 'texto_padrao_interno_cadastrar':
      $strTitulo = 'Novo Texto Padrão Interno';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTextoPadraoInterno" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objTextoPadraoInternoDTO->setNumIdTextoPadraoInterno(null);
      $objTextoPadraoInternoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      $objTextoPadraoInternoDTO->setStrNome($_POST['txtNome']);
      $objTextoPadraoInternoDTO->setStrDescricao($_POST['txtDescricao']);
      $objTextoPadraoInternoDTO->setStrConteudo($_POST['txaConteudo']);

      if (isset($_POST['sbmCadastrarTextoPadraoInterno'])) {
        try{
          $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
          $objTextoPadraoInternoDTO = $objTextoPadraoInternoRN->cadastrar($objTextoPadraoInternoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Texto Padrão Interno "'.$objTextoPadraoInternoDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_texto_padrao_interno='.$objTextoPadraoInternoDTO->getNumIdTextoPadraoInterno().PaginaSEI::getInstance()->montarAncora($objTextoPadraoInternoDTO->getNumIdTextoPadraoInterno())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'texto_padrao_interno_alterar':
      $strTitulo = 'Alterar Texto Padrão Interno';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTextoPadraoInterno" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_texto_padrao_interno'])){
        $objTextoPadraoInternoDTO->setNumIdTextoPadraoInterno($_GET['id_texto_padrao_interno']);
        $objTextoPadraoInternoDTO->retTodos();
        $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
        $objTextoPadraoInternoDTO = $objTextoPadraoInternoRN->consultar($objTextoPadraoInternoDTO);
        if ($objTextoPadraoInternoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objTextoPadraoInternoDTO->setNumIdTextoPadraoInterno($_POST['hdnIdTextoPadraoInterno']);
        $objTextoPadraoInternoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objTextoPadraoInternoDTO->setStrNome($_POST['txtNome']);
        $objTextoPadraoInternoDTO->setStrDescricao($_POST['txtDescricao']);
        $objTextoPadraoInternoDTO->setStrConteudo($_POST['txaConteudo']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTextoPadraoInternoDTO->getNumIdTextoPadraoInterno())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTextoPadraoInterno'])) {
        try{
          $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
          $objTextoPadraoInternoRN->alterar($objTextoPadraoInternoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Texto Padrão Interno "'.$objTextoPadraoInternoDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTextoPadraoInternoDTO->getNumIdTextoPadraoInterno())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'texto_padrao_interno_consultar':
      $strTitulo = 'Consultar Texto Padrão Interno';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_texto_padrao_interno'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTextoPadraoInternoDTO->setNumIdTextoPadraoInterno($_GET['id_texto_padrao_interno']);
      $objTextoPadraoInternoDTO->setBolExclusaoLogica(false);
      $objTextoPadraoInternoDTO->retTodos();
      $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
      $objTextoPadraoInternoDTO = $objTextoPadraoInternoRN->consultar($objTextoPadraoInternoDTO);
      if ($objTextoPadraoInternoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  $objEditorRN=new EditorRN();
  $objEditorDTO=new EditorDTO();
  
  $objEditorDTO->setStrNomeCampo('txaConteudo');
  if ($_GET['acao']=='texto_padrao_interno_consultar') {
    $objEditorDTO->setStrSinSomenteLeitura('S');
  } else {
    $objEditorDTO->setStrSinSomenteLeitura('N');
  }

  $objEditorDTO->setStrSinLinkSei('S');
  $retEditor = $objEditorRN->montarSimples($objEditorDTO);

  $strLinkAjaxProtocoloLinkEditor = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=protocolo_link_editor');

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
EditorINT::montarCss();
PaginaSEI::getInstance()->abrirStyle();
?>
#lblNome {position:absolute;left:0%;top:0%;width:50%;}
#txtNome {position:absolute;left:0%;top:16%;width:50%;}

#lblDescricao {position:absolute;left:0%;top:40%;width:94%;}
#txtDescricao {position:absolute;left:0%;top:56%;width:94%;}

#lblConteudo {position:absolute;left:0%;top:81.9%;width:95%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='texto_padrao_interno_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='texto_padrao_interno_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(); 
}

objAjax = new infraAjaxComplementar(null, '<?=$strLinkAjaxProtocoloLinkEditor?>');
objAjax.limparCampo = false;
objAjax.mostrarAviso = false;
objAjax.tempoAviso = 1000;
objAjax.async = false;

objAjax.prepararExecucao = function () {
  window._idProtocolo = '';
  window._protocoloFormatado = '';
  return 'idProtocoloDigitado=' + window._procedimento + "&idProcedimento=<?=$_GET["id_procedimento"];?>&idDocumento=<?=$_GET["id_documento"];?>";
};

objAjax.processarResultado = function (arr) {
  if (arr!=null) {
    window._idProtocolo = arr['IdProtocolo'];
    window._protocoloFormatado = arr['ProtocoloFormatado'];
  }
};
var _procedimento = '';
var _idProtocolo = '';
var _protocoloFormatado = '';
function validarCadastro() {

  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  if (infraTrim(CKEDITOR.instances['txaConteudo'].getData())=='') {
    alert('Informe o Conteúdo.');
    document.getElementById('txaConteudo').focus();
    return false;
  }

  return true;
}

function exibirAjuda(){
  infraAbrirJanela('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=ajuda_variaveis_secao_modelo')?>','janelaAjudaVariaveisModelo',800,600,'location=0,status=1,resizable=1,scrollbars=1',false);
}

function OnSubmitForm() {
  return validarCadastro();
}
//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
echo $retEditor->getStrInicializacao();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTextoPadraoInternoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('12em','style="border-bottom:0;"');
?>
  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objTextoPadraoInternoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objTextoPadraoInternoDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,300);" maxlength="300" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblConteudo" for="txaConteudo" accesskey="" class="infraLabelObrigatorio">Conteúdo:</label>
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
?>
  
  <div id="divComandos" style="margin-left:0px;"></div>

  <table style="width: 100%">
    <td style="width: 95%">
      <div id="divEditores" style="overflow: auto;">
        <textarea id="txaConteudo" name="txaConteudo" rows="10" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=$objTextoPadraoInternoDTO->getStrConteudo();?></textarea>
        <script type="text/javascript">
          <?=$retEditor->getStrEditores();?>
        </script>
      </div>
    </td>
    <td style="vertical-align: top;"> <a id="ancAjuda" onclick="exibirAjuda();" title="Ajuda" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>
    </td>
  </table>

  <input type="hidden" id="hdnIdTextoPadraoInterno" name="hdnIdTextoPadraoInterno" value="<?=$objTextoPadraoInternoDTO->getNumIdTextoPadraoInterno();?>" />

  <? 
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>
