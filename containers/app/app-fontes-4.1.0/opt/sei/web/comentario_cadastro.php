<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/10/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
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

  PaginaSEI::getInstance()->verificarSelecao('comentario_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selProtocolo'));

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples', 'id_rel_protocolo_protocolo','id_procedimento'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  if (isset($_GET['pagina_simples'])){
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $objComentarioDTO = new ComentarioDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'comentario_cadastrar':
      $strTitulo = 'Novo Comentário';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarComentario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if (!PaginaSEI::getInstance()->isBolArvore()) {
        $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      }else{
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }


      $objComentarioDTO->setNumIdComentario(null);
      $objComentarioDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objComentarioDTO->setDblIdProcedimento($_GET['id_procedimento']);
      $objComentarioDTO->setDblIdRelProtocoloProtocolo($_GET['id_rel_protocolo_protocolo']);
      $objComentarioDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objComentarioDTO->setDthComentario(InfraData::getStrDataHoraAtual());
      $objComentarioDTO->setStrDescricao($_POST['txaDescricao']);

      if (isset($_POST['sbmCadastrarComentario'])) {
        try{
          $objComentarioRN = new ComentarioRN();
          $objComentarioDTO = $objComentarioRN->cadastrar($objComentarioDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Comentário "'.$objComentarioDTO->getNumIdComentario().'" cadastrado com sucesso.');

          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=comentario_listar&acao_origem='.$_GET['acao'].'&id_comentario='.$objComentarioDTO->getNumIdComentario().'&id_rel_protocolo_protocolo='.$_GET['id_rel_protocolo_protocolo'].'&resultado=1'.PaginaSEI::getInstance()->montarAncora($objComentarioDTO->getNumIdComentario())));
          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'comentario_alterar':
      $strTitulo = 'Alterar Comentário';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarComentario" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_comentario'])){
        $objComentarioDTO->setNumIdComentario($_GET['id_comentario']);
        $objComentarioDTO->retTodos();
        $objComentarioRN = new ComentarioRN();
        $objComentarioDTO = $objComentarioRN->consultar($objComentarioDTO);
        if ($objComentarioDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objComentarioDTO->setNumIdComentario($_POST['hdnIdComentario']);
        $objComentarioDTO->setDthComentario(InfraData::getStrDataHoraAtual());
        $objComentarioDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objComentarioDTO->setStrDescricao($_POST['txaDescricao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objComentarioDTO->getNumIdComentario())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarComentario'])) {
        try{
          $objComentarioRN = new ComentarioRN();
          $objComentarioRN->alterar($objComentarioDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Comentário "'.$objComentarioDTO->getNumIdComentario().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=comentario_listar&acao_origem='.$_GET['acao'].'&id_comentario='.$objComentarioDTO->getNumIdComentario().'&id_rel_protocolo_protocolo='.$_GET['id_rel_protocolo_protocolo'].PaginaSEI::getInstance()->montarAncora($objComentarioDTO->getNumIdComentario())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'comentario_consultar':
      $strTitulo = 'Consultar Comentário';
      //$arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_comentario'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objComentarioDTO->setNumIdComentario($_GET['id_comentario']);
      $objComentarioDTO->setBolExclusaoLogica(false);
      $objComentarioDTO->retTodos();
      $objComentarioRN = new ComentarioRN();
      $objComentarioDTO = $objComentarioRN->consultar($objComentarioDTO);
      if ($objComentarioDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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
<?if(0){?><style><?}?>

  #lblDescricao {position:absolute;left:0%;top:0%;width:95%;}
  #txaDescricao {position:absolute;left:0%;top:10%;width:95%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){

  if ('<?=$_GET['acao']?>'=='comentario_consultar'){
    infraDesabilitarCamposAreaDados();
  } else {
    document.getElementById('txaDescricao').focus();
  }
  infraEfeitoTabelas(true);
}

function validarCadastro() {

  if (infraTrim(document.getElementById('txaDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txaDescricao').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmComentarioCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('20em');
?>
  <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
  <textarea rows="10" name="txaDescricao" id="txaDescricao" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,4000);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objComentarioDTO->getStrDescricao());?></textarea>

<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdComentario" name="hdnIdComentario" value="<?=$objComentarioDTO->getNumIdComentario();?>" />

</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
