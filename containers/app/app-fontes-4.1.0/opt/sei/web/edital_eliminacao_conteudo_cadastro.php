<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/12/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
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

  PaginaSEI::getInstance()->verificarSelecao('edital_eliminacao_conteudo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selAvaliacaoDocumental','selEditalEliminacao','selUsuario'));

  $objEditalEliminacaoConteudoDTO = new EditalEliminacaoConteudoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'edital_eliminacao_conteudo_cadastrar':
      $strTitulo = 'Novo Conteúdo do Edital de Eliminação';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarEditalEliminacaoConteudo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacaoConteudo(null);
      $numIdAvaliacaoDocumental = PaginaSEI::getInstance()->recuperarCampo('selAvaliacaoDocumental');
      if ($numIdAvaliacaoDocumental!==''){
        $objEditalEliminacaoConteudoDTO->setNumIdAvaliacaoDocumental($numIdAvaliacaoDocumental);
      }else{
        $objEditalEliminacaoConteudoDTO->setNumIdAvaliacaoDocumental(null);
      }

      $numIdEditalEliminacao = PaginaSEI::getInstance()->recuperarCampo('selEditalEliminacao');
      if ($numIdEditalEliminacao!==''){
        $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($numIdEditalEliminacao);
      }else{
        $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao(null);
      }

      $numIdUsuarioInclusao = PaginaSEI::getInstance()->recuperarCampo('selUsuario');
      if ($numIdUsuarioInclusao!==''){
        $objEditalEliminacaoConteudoDTO->setNumIdUsuarioInclusao($numIdUsuarioInclusao);
      }else{
        $objEditalEliminacaoConteudoDTO->setNumIdUsuarioInclusao(null);
      }

      $objEditalEliminacaoConteudoDTO->setDthInclusao($_POST['txtInclusao']);

      if (isset($_POST['sbmCadastrarEditalEliminacaoConteudo'])) {
        try{
          $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
          $objEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->cadastrar($objEditalEliminacaoConteudoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Conteúdo do Edital de Eliminação "'.$objEditalEliminacaoConteudoDTO->getNumIdEditalEliminacaoConteudo().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_edital_eliminacao_conteudo='.$objEditalEliminacaoConteudoDTO->getNumIdEditalEliminacaoConteudo().PaginaSEI::getInstance()->montarAncora($objEditalEliminacaoConteudoDTO->getNumIdEditalEliminacaoConteudo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'edital_eliminacao_conteudo_alterar':
      $strTitulo = 'Alterar Conteúdo do Edital de Eliminação';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarEditalEliminacaoConteudo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_edital_eliminacao_conteudo'])){
        $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacaoConteudo($_GET['id_edital_eliminacao_conteudo']);
        $objEditalEliminacaoConteudoDTO->retTodos();
        $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
        $objEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->consultar($objEditalEliminacaoConteudoDTO);
        if ($objEditalEliminacaoConteudoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacaoConteudo($_POST['hdnIdEditalEliminacaoConteudo']);
        $objEditalEliminacaoConteudoDTO->setNumIdAvaliacaoDocumental($_POST['selAvaliacaoDocumental']);
        $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacao($_POST['selEditalEliminacao']);
        $objEditalEliminacaoConteudoDTO->setNumIdUsuarioInclusao($_POST['selUsuario']);
        $objEditalEliminacaoConteudoDTO->setDthInclusao($_POST['txtInclusao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objEditalEliminacaoConteudoDTO->getNumIdEditalEliminacaoConteudo())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarEditalEliminacaoConteudo'])) {
        try{
          $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
          $objEditalEliminacaoConteudoRN->alterar($objEditalEliminacaoConteudoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Conteúdo do Edital de Eliminação "'.$objEditalEliminacaoConteudoDTO->getNumIdEditalEliminacaoConteudo().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objEditalEliminacaoConteudoDTO->getNumIdEditalEliminacaoConteudo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'edital_eliminacao_conteudo_consultar':
      $strTitulo = 'Consultar Conteúdo do Edital de Eliminação';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_edital_eliminacao_conteudo'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objEditalEliminacaoConteudoDTO->setNumIdEditalEliminacaoConteudo($_GET['id_edital_eliminacao_conteudo']);
      $objEditalEliminacaoConteudoDTO->setBolExclusaoLogica(false);
      $objEditalEliminacaoConteudoDTO->retTodos();
      $objEditalEliminacaoConteudoRN = new EditalEliminacaoConteudoRN();
      $objEditalEliminacaoConteudoDTO = $objEditalEliminacaoConteudoRN->consultar($objEditalEliminacaoConteudoDTO);
      if ($objEditalEliminacaoConteudoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelEditalEliminacao = EditalEliminacaoINT::montarSelectIdEditalEliminacao('null','&nbsp;',$objEditalEliminacaoConteudoDTO->getNumIdEditalEliminacao());

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
#lblAvaliacaoDocumental {position:absolute;left:0%;top:0%;width:25%;}
#selAvaliacaoDocumental {position:absolute;left:0%;top:40%;width:25%;}

#lblEditalEliminacao {position:absolute;left:0%;top:0%;width:25%;}
#selEditalEliminacao {position:absolute;left:0%;top:40%;width:25%;}

#lblUsuario {position:absolute;left:0%;top:0%;width:25%;}
#selUsuario {position:absolute;left:0%;top:40%;width:25%;}

#lblInclusao {position:absolute;left:0%;top:0%;width:25%;}
#txtInclusao {position:absolute;left:0%;top:40%;width:25%;}
#imgCalInclusao {position:absolute;left:26%;top:40%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='edital_eliminacao_conteudo_cadastrar'){
    document.getElementById('selAvaliacaoDocumental').focus();
  } else if ('<?=$_GET['acao']?>'=='edital_eliminacao_conteudo_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}

function validarCadastro() {
  if (!infraSelectSelecionado('selAvaliacaoDocumental')) {
    alert('Selecione uma Avaliação Documental.');
    document.getElementById('selAvaliacaoDocumental').focus();
    return false;
  }

  if (!infraSelectSelecionado('selEditalEliminacao')) {
    alert('Selecione um Edital de Eliminação.');
    document.getElementById('selEditalEliminacao').focus();
    return false;
  }

  if (!infraSelectSelecionado('selUsuario')) {
    alert('Selecione um Usuário de Inclusão.');
    document.getElementById('selUsuario').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtInclusao').value)=='') {
    alert('Informe a Data de Inclusão.');
    document.getElementById('txtInclusao').focus();
    return false;
  }

  if (!infraValidarDataHora(document.getElementById('txtInclusao'))){
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
<form id="frmEditalEliminacaoConteudoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblAvaliacaoDocumental" for="selAvaliacaoDocumental" accesskey="" class="infraLabelObrigatorio">Avaliação Documental:</label>
  <select id="selAvaliacaoDocumental" name="selAvaliacaoDocumental" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelAvaliacaoDocumental?>
  </select>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblEditalEliminacao" for="selEditalEliminacao" accesskey="" class="infraLabelObrigatorio">Edital de Eliminação:</label>
  <select id="selEditalEliminacao" name="selEditalEliminacao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelEditalEliminacao?>
  </select>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblUsuario" for="selUsuario" accesskey="" class="infraLabelObrigatorio">Usuário de Inclusão:</label>
  <select id="selUsuario" name="selUsuario" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelUsuario?>
  </select>
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblInclusao" for="txtInclusao" accesskey="" class="infraLabelObrigatorio">Data de Inclusão:</label>
  <input type="text" id="txtInclusao" name="txtInclusao" onkeypress="return infraMascaraDataHora(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objEditalEliminacaoConteudoDTO->getDthInclusao());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalInclusao" title="Selecionar Data de Inclusão" alt="Selecionar Data de Inclusão" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtInclusao',this);" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdEditalEliminacaoConteudo" name="hdnIdEditalEliminacaoConteudo" value="<?=$objEditalEliminacaoConteudoDTO->getNumIdEditalEliminacaoConteudo();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
