<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 05/07/2018 - criado por mga
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

  SessaoSip::getInstance()->validarLink();

  PaginaSip::getInstance()->verificarSelecao('email_sistema_selecionar');

  SessaoSip::getInstance()->validarPermissao($_GET['acao']);

  $objEmailSistemaDTO = new EmailSistemaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'email_sistema_cadastrar':
      $strTitulo = 'Novo E-mail do Sistema';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarEmailSistema" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objEmailSistemaDTO->setNumIdEmailSistema(null);
      $objEmailSistemaDTO->setStrDescricao($_POST['txtDescricao']);
      $objEmailSistemaDTO->setStrDe($_POST['txtDe']);
      $objEmailSistemaDTO->setStrPara($_POST['txtPara']);
      $objEmailSistemaDTO->setStrAssunto($_POST['txtAssunto']);
      $objEmailSistemaDTO->setStrConteudo($_POST['txaConteudo']);

      if (isset($_POST['sbmCadastrarEmailSistema'])) {
        try{
          $objEmailSistemaRN = new EmailSistemaRN();
          $objEmailSistemaDTO = $objEmailSistemaRN->cadastrar($objEmailSistemaDTO);
          PaginaSip::getInstance()->adicionarMensagem('E-mail do Sistema "'.$objEmailSistemaDTO->getNumIdEmailSistema().'" cadastrado com sucesso.');
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_email_sistema='.$objEmailSistemaDTO->getNumIdEmailSistema().PaginaSip::getInstance()->montarAncora($objEmailSistemaDTO->getNumIdEmailSistema())));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'email_sistema_alterar':
      $strTitulo = 'Alterar E-mail do Sistema';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarEmailSistema" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_email_sistema'])){
        $objEmailSistemaDTO->setNumIdEmailSistema($_GET['id_email_sistema']);
        $objEmailSistemaDTO->retTodos();
        $objEmailSistemaRN = new EmailSistemaRN();
        $objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);
        if ($objEmailSistemaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objEmailSistemaDTO->setNumIdEmailSistema($_POST['hdnIdEmailSistema']);
        $objEmailSistemaDTO->setStrDescricao($_POST['txtDescricao']);
        $objEmailSistemaDTO->setStrDe($_POST['txtDe']);
        $objEmailSistemaDTO->setStrPara($_POST['txtPara']);
        $objEmailSistemaDTO->setStrAssunto($_POST['txtAssunto']);
        $objEmailSistemaDTO->setStrConteudo($_POST['txaConteudo']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($objEmailSistemaDTO->getNumIdEmailSistema())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarEmailSistema'])) {
        try{
          $objEmailSistemaRN = new EmailSistemaRN();
          $objEmailSistemaRN->alterar($objEmailSistemaDTO);
          PaginaSip::getInstance()->adicionarMensagem('E-mail do Sistema "'.$objEmailSistemaDTO->getNumIdEmailSistema().'" alterado com sucesso.');
          header('Location: '.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($objEmailSistemaDTO->getNumIdEmailSistema())));
          die;
        }catch(Exception $e){
          PaginaSip::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'email_sistema_consultar':
      $strTitulo = 'Consultar E-mail do Sistema';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSip::getInstance()->assinarLink('controlador.php?acao='.PaginaSip::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSip::getInstance()->montarAncora($_GET['id_email_sistema'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objEmailSistemaDTO->setNumIdEmailSistema($_GET['id_email_sistema']);
      $objEmailSistemaDTO->setBolExclusaoLogica(false);
      $objEmailSistemaDTO->retTodos();
      $objEmailSistemaRN = new EmailSistemaRN();
      $objEmailSistemaDTO = $objEmailSistemaRN->consultar($objEmailSistemaDTO);
      if ($objEmailSistemaDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


  $strLinkAjudaRemetente = SessaoSip::getInstance()->assinarLink('controlador.php?acao=ajuda_variaveis_email_sistema&tipo='.$objEmailSistemaDTO->getNumIdEmailSistema().'&campo=R');
  $strLinkAjudaDestinatario = SessaoSip::getInstance()->assinarLink('controlador.php?acao=ajuda_variaveis_email_sistema&tipo='.$objEmailSistemaDTO->getNumIdEmailSistema().'&campo=D');
  $strLinkAjudaAssunto = SessaoSip::getInstance()->assinarLink('controlador.php?acao=ajuda_variaveis_email_sistema&tipo='.$objEmailSistemaDTO->getNumIdEmailSistema().'&campo=A');
  $strLinkAjudaConteudo = SessaoSip::getInstance()->assinarLink('controlador.php?acao=ajuda_variaveis_email_sistema&tipo='.$objEmailSistemaDTO->getNumIdEmailSistema().'&campo=C');

}catch(Exception $e){
  PaginaSip::getInstance()->processarExcecao($e);
}

PaginaSip::getInstance()->montarDocType();
PaginaSip::getInstance()->abrirHtml();
PaginaSip::getInstance()->abrirHead();
PaginaSip::getInstance()->montarMeta();
PaginaSip::getInstance()->montarTitle(PaginaSip::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSip::getInstance()->montarStyle();
PaginaSip::getInstance()->abrirStyle();
?>
#lblDescricao {position:absolute;left:0%;top:0%;}
#txtDescricao {position:absolute;left:0%;top:4%;width:70%;}

#lblDe {position:absolute;left:0%;top:11%;}
#txtDe {position:absolute;left:0%;top:15%;width:70%;}
#ancAjudaDe {position:absolute;left:72%;top:15%;}

#lblPara {position:absolute;left:0%;top:22%;}
#txtPara {position:absolute;left:0%;top:26%;width:70%;}
#ancAjudaPara {position:absolute;left:72%;top:26%;}

#lblAssunto {position:absolute;left:0%;top:33%;}
#txtAssunto {position:absolute;left:0%;top:37%;width:70%;}
#ancAjudaAssunto {position:absolute;left:72%;top:37%;}
 
#lblConteudo {position:absolute;left:0%;top:44%;}
#txaConteudo {position:absolute;left:0%;top:48%;width:90%;}
#ancAjudaConteudo {position:absolute;left:92%;top:48%;}

<?
PaginaSip::getInstance()->fecharStyle();
PaginaSip::getInstance()->montarJavaScript();
PaginaSip::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='email_sistema_cadastrar'){
    document.getElementById('txtDescricao').focus();
  } else if ('<?=$_GET['acao']?>'=='email_sistema_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txtDescricao').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtDe').value)=='') {
    alert('Informe o Remetente.');
    document.getElementById('txtDe').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtPara').value)=='') {
    alert('Informe o Destinatário.');
    document.getElementById('txtPara').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtAssunto').value)=='') {
    alert('Informe o Assunto.');
    document.getElementById('txtAssunto').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txaConteudo').value)=='') {
    alert('Informe o Conteúdo.');
    document.getElementById('txaConteudo').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

function exibirAjuda(link){
  infraAbrirJanela(link,'janelaAjudaVariaveisEmailSistema',800,600,'location=0,status=1,resizable=1,scrollbars=1',false);
}


<?
PaginaSip::getInstance()->fecharJavaScript();
PaginaSip::getInstance()->fecharHead();
PaginaSip::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEmailSistemaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSip::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSip::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSip::getInstance()->montarAreaValidacao();
PaginaSip::getInstance()->abrirAreaDados('45em');
?>
  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSip::tratarHTML($objEmailSistemaDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />

  <label id="lblDe" for="txtDe" accesskey="" class="infraLabelObrigatorio">Remetente:</label>
  <input type="text" id="txtDe" name="txtDe" class="infraText" value="<?=PaginaSip::tratarHTML($objEmailSistemaDTO->getStrDe());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  <a id="ancAjudaDe" href="javascript:void(0);" onclick="exibirAjuda('<?=$strLinkAjudaRemetente?>');" title="Ajuda Remetente" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSip::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>

  <label id="lblPara" for="txtPara" accesskey="" class="infraLabelObrigatorio">Destinatário:</label>
  <input type="text" id="txtPara" name="txtPara" class="infraText" value="<?=PaginaSip::tratarHTML($objEmailSistemaDTO->getStrPara());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  <a id="ancAjudaPara" href="javascript:void(0);" onclick="exibirAjuda('<?=$strLinkAjudaDestinatario?>');" title="Ajuda Destinatário" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSip::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>

  <label id="lblAssunto" for="txtAssunto" accesskey="" class="infraLabelObrigatorio">Assunto:</label>
  <input type="text" id="txtAssunto" name="txtAssunto" class="infraText" value="<?=PaginaSip::tratarHTML($objEmailSistemaDTO->getStrAssunto());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>" />
  <a id="ancAjudaAssunto" href="javascript:void(0);" onclick="exibirAjuda('<?=$strLinkAjudaAssunto?>');" title="Ajuda Assunto" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSip::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>

 	<label id="lblConteudo" for="txaConteudo" class="infraLabelObrigatorio">Conteúdo:</label>
  <textarea id="txaConteudo" name="txaConteudo" rows="<?=PaginaSip::getInstance()->isBolNavegadorFirefox()?'14':'15'?>" class="infraTextarea" maxlength="4000" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"><?=PaginaSip::tratarHTML($objEmailSistemaDTO->getStrConteudo());?></textarea>
  <a id="ancAjudaConteudo" href="javascript:void(0);" onclick="exibirAjuda('<?=$strLinkAjudaConteudo?>');" title="Ajuda Conteúdo" tabindex="<?=PaginaSip::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSip::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>

  <input type="hidden" id="hdnIdEmailSistema" name="hdnIdEmailSistema" value="<?=$objEmailSistemaDTO->getNumIdEmailSistema();?>" />
  <?
  PaginaSip::getInstance()->fecharAreaDados();
  //PaginaSip::getInstance()->montarAreaDebug();
  //PaginaSip::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSip::getInstance()->fecharBody();
PaginaSip::getInstance()->fecharHtml();
?>