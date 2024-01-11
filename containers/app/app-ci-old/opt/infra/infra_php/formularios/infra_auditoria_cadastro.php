<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/10/2011 - criado por mga
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

try {
  //require_once dirname(__FILE__).'/Infra.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  PaginaInfra::getInstance()->verificarSelecao('infra_auditoria_selecionar');

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  PaginaInfra::getInstance()->salvarCamposPost(array('selInfraAuditoriaUsuario','selInfraAuditoriaUsuarioEmulador','selInfraAuditoriaUnidade'));

  $objInfraAuditoriaDTO = new InfraAuditoriaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'infra_auditoria_cadastrar':
      $strTitulo = 'Novo Dado de Auditoria';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarInfraAuditoria" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objInfraAuditoriaDTO->setNumIdInfraAuditoria(null);
      $objInfraAuditoriaDTO->setStrRecurso($_POST['txtRecurso']);
      $objInfraAuditoriaDTO->setStrDados($_POST['txtDados']);
      $objInfraAuditoriaDTO->setDthAcesso($_POST['txtAcesso']);
      $objInfraAuditoriaDTO->setStrIp($_POST['txtIp']);
      $numIdUsuario = PaginaInfra::getInstance()->recuperarCampo('selInfraAuditoriaUsuario');
      if ($numIdUsuario!==''){
        $objInfraAuditoriaDTO->setNumIdUsuario($numIdUsuario);
      }else{
        $objInfraAuditoriaDTO->setNumIdUsuario(null);
      }

      $numIdUsuarioEmulador = PaginaInfra::getInstance()->recuperarCampo('selInfraAuditoriaUsuarioEmulador');
      if ($numIdUsuarioEmulador!==''){
        $objInfraAuditoriaDTO->setNumIdUsuarioEmulador($numIdUsuarioEmulador);
      }else{
        $objInfraAuditoriaDTO->setNumIdUsuarioEmulador(null);
      }

      $numIdUnidade = PaginaInfra::getInstance()->recuperarCampo('selInfraAuditoriaUnidade');
      if ($numIdUnidade!==''){
        $objInfraAuditoriaDTO->setNumIdUnidade($numIdUnidade);
      }else{
        $objInfraAuditoriaDTO->setNumIdUnidade(null);
      }


      if (isset($_POST['sbmCadastrarInfraAuditoria'])) {
        try{
          $objInfraAuditoriaRN = new InfraAuditoriaRN();
          $objInfraAuditoriaDTO = $objInfraAuditoriaRN->cadastrar($objInfraAuditoriaDTO);
          PaginaInfra::getInstance()->adicionarMensagem('Dado de Auditoria "'.$objInfraAuditoriaDTO->getNumIdInfraAuditoria().'" cadastrado com sucesso.');
          header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_infra_auditoria='.$objInfraAuditoriaDTO->getNumIdInfraAuditoria().PaginaInfra::getInstance()->montarAncora($objInfraAuditoriaDTO->getNumIdInfraAuditoria())));
          die;
        }catch(Exception $e){
          PaginaInfra::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'infra_auditoria_alterar':
      $strTitulo = 'Alterar Dado de Auditoria';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarInfraAuditoria" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_infra_auditoria'])){
        $objInfraAuditoriaDTO->setNumIdInfraAuditoria($_GET['id_infra_auditoria']);
        $objInfraAuditoriaDTO->retTodos();
        $objInfraAuditoriaRN = new InfraAuditoriaRN();
        $objInfraAuditoriaDTO = $objInfraAuditoriaRN->consultar($objInfraAuditoriaDTO);
        if ($objInfraAuditoriaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objInfraAuditoriaDTO->setNumIdInfraAuditoria($_POST['hdnIdInfraAuditoria']);
        $objInfraAuditoriaDTO->setStrRecurso($_POST['txtRecurso']);
        $objInfraAuditoriaDTO->setStrDados($_POST['txtDados']);
        $objInfraAuditoriaDTO->setDthAcesso($_POST['txtAcesso']);
        $objInfraAuditoriaDTO->setStrIp($_POST['txtIp']);
        $objInfraAuditoriaDTO->setNumIdUsuario($_POST['selInfraAuditoriaUsuario']);
        $objInfraAuditoriaDTO->setNumIdUsuarioEmulador($_POST['selInfraAuditoriaUsuarioEmulador']);
        $objInfraAuditoriaDTO->setNumIdUnidade($_POST['selInfraAuditoriaUnidade']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($objInfraAuditoriaDTO->getNumIdInfraAuditoria())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarInfraAuditoria'])) {
        try{
          $objInfraAuditoriaRN = new InfraAuditoriaRN();
          $objInfraAuditoriaRN->alterar($objInfraAuditoriaDTO);
          PaginaInfra::getInstance()->adicionarMensagem('Dado de Auditoria "'.$objInfraAuditoriaDTO->getNumIdInfraAuditoria().'" alterado com sucesso.');
          header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($objInfraAuditoriaDTO->getNumIdInfraAuditoria())));
          die;
        }catch(Exception $e){
          PaginaInfra::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'infra_auditoria_consultar':
      $strTitulo = 'Consultar Dado de Auditoria';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($_GET['id_infra_auditoria'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objInfraAuditoriaDTO->setNumIdInfraAuditoria($_GET['id_infra_auditoria']);
      $objInfraAuditoriaDTO->setBolExclusaoLogica(false);
      $objInfraAuditoriaDTO->retTodos();
      $objInfraAuditoriaRN = new InfraAuditoriaRN();
      $objInfraAuditoriaDTO = $objInfraAuditoriaRN->consultar($objInfraAuditoriaDTO);
      if ($objInfraAuditoriaDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelInfraAuditoriaUsuario = InfraAuditoriaUsuarioINT::montarSelectSigla('null','&nbsp;',$objInfraAuditoriaDTO->getNumIdUsuario());
  $strItensSelInfraAuditoriaUsuarioEmulador = InfraAuditoriaUsuarioINT::montarSelectSigla('null','&nbsp;',$objInfraAuditoriaDTO->getNumIdUsuarioEmulador());
  $strItensSelInfraAuditoriaUnidade = InfraAuditoriaUnidadeINT::montarSelectSigla('null','&nbsp;',$objInfraAuditoriaDTO->getNumIdUnidade());

}catch(Exception $e){
  PaginaInfra::getInstance()->processarExcecao($e);
}

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>

#lblRecurso {position:absolute;left:0%;top:0%;width:50%;}
#txtRecurso {position:absolute;left:0%;top:6%;width:50%;}

#lblDados {position:absolute;left:0%;top:16%;width:25%;}
#txtDados {position:absolute;left:0%;top:22%;width:25%;}

#lblAcesso {position:absolute;left:0%;top:32%;width:25%;}
#txtAcesso {position:absolute;left:0%;top:38%;width:25%;}
#imgCalAcesso {position:absolute;left:26%;top:38%;}

#lblIp {position:absolute;left:0%;top:48%;width:15%;}
#txtIp {position:absolute;left:0%;top:54%;width:15%;}

#lblInfraAuditoriaUsuario {position:absolute;left:0%;top:64%;width:25%;}
#selInfraAuditoriaUsuario {position:absolute;left:0%;top:70%;width:25%;}

#lblInfraAuditoriaUsuarioEmulador {position:absolute;left:0%;top:80%;width:25%;}
#selInfraAuditoriaUsuarioEmulador {position:absolute;left:0%;top:86%;width:25%;}

#lblInfraAuditoriaUnidade {position:absolute;left:0%;top:96%;width:25%;}
#selInfraAuditoriaUnidade {position:absolute;left:0%;top:102%;width:25%;}

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='infra_auditoria_cadastrar'){
    document.getElementById('txtRecurso').focus();
  } else if ('<?=$_GET['acao']?>'=='infra_auditoria_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {

  if (infraTrim(document.getElementById('txtRecurso').value)=='') {
    alert('Informe o Recurso.');
    document.getElementById('txtRecurso').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtAcesso').value)=='') {
    alert('Informe a Data/Hora.');
    document.getElementById('txtAcesso').focus();
    return false;
  }

  if (!infraValidarDataHora(document.getElementById('txtAcesso'))){
    return false;
  }

  if (infraTrim(document.getElementById('txtIp').value)=='') {
    alert('Informe o IP de Acesso.');
    document.getElementById('txtIp').focus();
    return false;
  }

  if (!infraSelectSelecionado('selInfraAuditoriaUsuario')) {
    alert('Selecione um Usuário.');
    document.getElementById('selInfraAuditoriaUsuario').focus();
    return false;
  }

  if (!infraSelectSelecionado('selInfraAuditoriaUnidade')) {
    alert('Selecione uma Unidade.');
    document.getElementById('selInfraAuditoriaUnidade').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInfraAuditoriaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaInfra::getInstance()->montarAreaValidacao();
PaginaInfra::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblRecurso" for="txtRecurso" accesskey="" class="infraLabelObrigatorio">Recurso:</label>
  <input type="text" id="txtRecurso" name="txtRecurso" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraAuditoriaDTO->getStrRecurso());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

  <label id="lblDados" for="txtDados" accesskey="" class="infraLabelOpcional">Dados:</label>
  <input type="text" id="txtDados" name="txtDados" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraAuditoriaDTO->getStrDados());?>" onkeypress="return infraMascaraTexto(this,event);" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

  <label id="lblAcesso" for="txtAcesso" accesskey="" class="infraLabelObrigatorio">Data/Hora:</label>
  <input type="text" id="txtAcesso" name="txtAcesso" onkeypress="return infraMascaraDataHora(this, event)" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraAuditoriaDTO->getDthAcesso());?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <img id="imgCalAcesso" title="Selecionar Data/Hora" alt="Selecionar Data/Hora" src="<?=PaginaInfra::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtAcesso',this);" />

  <label id="lblIp" for="txtIp" accesskey="" class="infraLabelObrigatorio">IP de Acesso:</label>
  <input type="text" id="txtIp" name="txtIp" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraAuditoriaDTO->getStrIp());?>" onkeypress="return infraMascaraTexto(this,event,15);" maxlength="15" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

  <label id="lblInfraAuditoriaUsuario" for="selInfraAuditoriaUsuario" accesskey="" class="infraLabelObrigatorio">Usuário:</label>
  <select id="selInfraAuditoriaUsuario" name="selInfraAuditoriaUsuario" class="infraSelect" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>">
  <?=$strItensSelInfraAuditoriaUsuario?>
  </select>
  <label id="lblInfraAuditoriaUsuarioEmulador" for="selInfraAuditoriaUsuarioEmulador" accesskey="" class="infraLabelOpcional">Usuário Emulador:</label>
  <select id="selInfraAuditoriaUsuarioEmulador" name="selInfraAuditoriaUsuarioEmulador" class="infraSelect" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>">
  <?=$strItensSelInfraAuditoriaUsuarioEmulador?>
  </select>
  <label id="lblInfraAuditoriaUnidade" for="selInfraAuditoriaUnidade" accesskey="" class="infraLabelObrigatorio">Unidade:</label>
  <select id="selInfraAuditoriaUnidade" name="selInfraAuditoriaUnidade" class="infraSelect" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>">
  <?=$strItensSelInfraAuditoriaUnidade?>
  </select>
  <input type="hidden" id="hdnIdInfraAuditoria" name="hdnIdInfraAuditoria" value="<?=$objInfraAuditoriaDTO->getNumIdInfraAuditoria();?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  //PaginaInfra::getInstance()->montarAreaDebug();
  PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>