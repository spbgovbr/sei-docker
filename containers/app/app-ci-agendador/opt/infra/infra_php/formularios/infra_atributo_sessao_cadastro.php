<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 07/08/2009 - criado por mga
*
* Versão do Gerador de Código: 1.27.1
*
* Versão no CVS: $Id$
*/

try {
  //require_once 'Infra.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'infra_atributo_sessao_cadastrar':
      $strTitulo = 'Novo Atributo de Sessão';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarInfraAtributoSessao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $nome = $_POST['txtNome'];
      $valor = $_POST['txtValor'];
      
      if (isset($_POST['sbmCadastrarInfraAtributoSessao'])) {
        try{
          $strSiglaOrgaoSistema = SessaoInfra::getInstance()->getStrSiglaOrgaoSistema();
          $strSiglaSistema = SessaoInfra::getInstance()->getStrSiglaSistema();
          
          $arrAtributos = array();
          if (isset($_SESSION['INFRA_ATRIBUTOS'][$strSiglaOrgaoSistema][$strSiglaSistema][$nome])){
            PaginaInfra::getInstance()->setStrMensagem('Já existe um Atributo de Sessão com este nome.');
          }else{
            SessaoInfra::getInstance()->setAtributo($nome,$valor);
            PaginaInfra::getInstance()->setStrMensagem('Atributo de Sessão "'.$nome.'" cadastrado com sucesso.');
            header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&nome='.$nome.PaginaInfra::getInstance()->montarAncora($nome)));
            die;
          }
        }catch(Exception $e){
          PaginaInfra::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'infra_atributo_sessao_alterar':
      $strTitulo = 'Alterar Atributo de Sessão';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarInfraAtributoSessao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['nome'])){
        $nome = $_GET['nome'];
        $valor = SessaoInfra::getInstance()->getAtributo($nome);
      }else{
        $nome = $_POST['hdnNome'];
        $valor = $_POST['txtValor'];
      }
      
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($nome)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarInfraAtributoSessao'])) {
        try{
          SessaoInfra::getInstance()->setAtributo($_POST['hdnNome'],$_POST['txtValor']);
          PaginaInfra::getInstance()->setStrMensagem('Atributo de Sessão "'.$nome.'" alterado com sucesso.');
          header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($nome)));
          die;
        }catch(Exception $e){
          PaginaInfra::getInstance()->processarExcecao($e);
        }
      }
      break;


    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }


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
#lblNome {position:absolute;left:0%;top:0%;width:25%;}
#txtNome {position:absolute;left:0%;top:6%;width:25%;}

#lblValor {position:absolute;left:0%;top:16%;width:25%;}
#txtValor {position:absolute;left:0%;top:22%;width:25%;}

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='infra_atributo_sessao_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='infra_atributo_sessao_alterar'){
    document.getElementById('txtValor').focus();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoImagens();
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtNome').value)==''){
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInfraAtributoSessaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaInfra::getInstance()->montarAreaValidacao();
PaginaInfra::getInstance()->abrirAreaDados('30em');
?>
  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($nome);?>" onkeypress="return infraMascaraTexto(this,event);" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" <?=$strDesabilitar?> />

  <label id="lblValor" for="txtValor" accesskey="V" class="infraLabelOpcional"><span class="infraTeclaAtalho">V</span>alor:</label>
  <input type="text" id="txtValor" name="txtValor" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($valor);?>" onkeypress="return infraMascaraTexto(this,event);" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnNome" name="hdnNome" value="<?=PaginaInfra::getInstance()->tratarHTML($nome);?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  //PaginaInfra::getInstance()->montarAreaDebug();
  //PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>