<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/09/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
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

  PaginaSEI::getInstance()->verificarSelecao('servico_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array(''));

  $strParametros = '&id_usuario='.$_GET['id_usuario'];
  
  $objServicoDTO = new ServicoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'servico_cadastrar':
      $strTitulo = 'Novo Serviço';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarServico" id="sbmCadastrarServico" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $strSinChaveAcesso = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinChaveAcesso']);
      $strSinServidor = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinServidor']);

      $objServicoDTO->setNumIdServico(null);
      $objServicoDTO->setNumIdUsuario($_GET['id_usuario']);
      $objServicoDTO->setStrIdentificacao($_POST['txtIdentificacao']);
      $objServicoDTO->setStrDescricao($_POST['txtDescricao']);
      $objServicoDTO->setStrServidor(implode(',', PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnServidores'])));
      $objServicoDTO->setStrSinLinkExterno(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinLinkExterno']));
      $objServicoDTO->setStrSinServidor(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinServidor']));
      $objServicoDTO->setStrSinChaveAcesso(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinChaveAcesso']));
      $objServicoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarServico'])) {
        try{
          $objServicoRN = new ServicoRN();
          $objServicoDTO = $objServicoRN->cadastrar($objServicoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Serviço "'.$objServicoDTO->getStrIdentificacao().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_servico='.$objServicoDTO->getNumIdServico().$strParametros.PaginaSEI::getInstance()->montarAncora($objServicoDTO->getNumIdServico())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'servico_alterar':
      $strTitulo = 'Alterar Serviço';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarServico" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_servico'])){
        $objServicoDTO->setNumIdServico($_GET['id_servico']);
        $objServicoDTO->retTodos(true);
        $objServicoRN = new ServicoRN();
        $objServicoDTO = $objServicoRN->consultar($objServicoDTO);
        if ($objServicoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objServicoDTO->setNumIdServico($_POST['hdnIdServico']);
        $objServicoDTO->setNumIdUsuario($_GET['id_usuario']);
        $objServicoDTO->setStrIdentificacao($_POST['txtIdentificacao']);
        $objServicoDTO->setStrDescricao($_POST['txtDescricao']);
        $objServicoDTO->setStrServidor(implode(',',PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnServidores'])));
        $objServicoDTO->setStrSinLinkExterno(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinLinkExterno']));
        $objServicoDTO->setStrSinServidor(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinServidor']));
        $objServicoDTO->setStrSinChaveAcesso(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinChaveAcesso']));
        $objServicoDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objServicoDTO->getNumIdServico())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarServico'])) {
        try{
            $objServicoRN = new ServicoRN();
            $objServicoRN->alterar($objServicoDTO);
            PaginaSEI::getInstance()->setStrMensagem('Serviço "'.$objServicoDTO->getStrIdentificacao().'" alterado com sucesso.');
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objServicoDTO->getNumIdServico())));
            die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'servico_consultar':
      $strTitulo = 'Consultar Serviço';
      $objServicoDTO->setNumIdServico($_GET['id_servico']);
      $objServicoDTO->setBolExclusaoLogica(false);
      $objServicoDTO->retTodos(true);
      $objServicoRN = new ServicoRN();
      $objServicoDTO = $objServicoRN->consultar($objServicoDTO);
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_servico'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

      if ($objServicoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  
  $arrServidores = array();
  if (!InfraString::isBolVazia($objServicoDTO->getStrServidor())){
    foreach(explode(',',$objServicoDTO->getStrServidor()) as $strServidor){
      $arrServidores[$strServidor] = $strServidor;
    }
    ksort($arrServidores);
  }
  $strItensSelServidores = InfraINT::montarSelectArray(null, null, null, $arrServidores);

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

#lblIdentificacao {position:absolute;left:0%;top:0%;width:30.5%;}
#txtIdentificacao {position:absolute;left:0%;top:15%;width:30.5%;}

#lblDescricao {position:absolute;left:0%;top:35%;width:70%;}
#txtDescricao {position:absolute;left:0%;top:50%;width:70%;}

#divSinLinkExterno {position:absolute;left:0%;top:73%;}

#fldAutenticacao {height:80%;left:0;top:0;width:30%;}
#divChkSinChaveAcesso {position:absolute;left:5%;top:30%;}
#divChkSinServidor {position:absolute;left:5%;top:55%;}

#divServidores {display:none;}
#lblServidores {position:absolute;left:0%;top:0%;width:44%;}
#txtServidor {position:absolute;left:0%;top:14%;width:44%;}
#selServidores {position:absolute;left:0%;top:32%;width:44.5%;}
#imgExcluirServidores {position:absolute;left:45.5%;top:32%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
var objLupaServidores = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='servico_cadastrar'){
    self.setTimeout('document.getElementById(\'txtIdentificacao\').focus()',500);
  } else if ('<?=$_GET['acao']?>'=='servico_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }

  objLupaServidores = new infraLupaSelect('selServidores','hdnServidores',null);

  trocarTipo();
}

function trocarTipo(){
  if (document.getElementById('chkSinServidor').checked){
    $("#divServidores").show();
  }else{
    $("#divServidores").hide();
  }
}

function validarCadastro() {

  if (infraTrim(document.getElementById('txtIdentificacao').value)=='') {
    alert('Informe a Identificação.');
    document.getElementById('txtIdentificacao').focus();
    return false;
  }

  if (document.getElementById('chkSinServidor').checked && document.getElementById('selServidores').options.length==0) {
    alert('Informe pelo menos um Servidor.');
    document.getElementById('txtServidor').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

function adicionarServidor(obj, ev){
  if (infraGetCodigoTecla(ev)==13){
    
    obj.value = infraTrim(obj.value); 
     
    if (obj.value==''){
      alert('Servidor não informado.');
      return false;
    }

    objLupaServidores.adicionar(obj.value,obj.value);

    document.getElementById('txtServidor').value = '';
    document.getElementById('txtServidor').focus();
    
    return false;
  }
  
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmServicoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divGeral" class="infraAreaDados" style="height:13em;">
    <label id="lblIdentificacao" for="txtIdentificacao" accesskey="" class="infraLabelObrigatorio">Identificação:</label>
    <input type="text" id="txtIdentificacao" name="txtIdentificacao" class="infraText" value="<?=PaginaSEI::tratarHTML($objServicoDTO->getStrIdentificacao());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
    <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objServicoDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <div id="divSinLinkExterno" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinLinkExterno" name="chkSinLinkExterno" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objServicoDTO->getStrSinLinkExterno())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
      <label id="lblSinLinkExterno" for="chkSinLinkExterno" accesskey="" class="infraLabelCheckbox">Gerar links de acesso externos</label>
    </div>
  </div>

  <div id="divAutenticacao" class="infraAreaDados" style="height:10em;">
    <fieldset id="fldAutenticacao" class="infraFieldset">
      <legend class="infraLegend">Autenticação</legend>

      <div id="divChkSinChaveAcesso" class="infraDivCheckbox">
        <input type="checkbox" name="chkSinChaveAcesso" id="chkSinChaveAcesso" onchange="trocarTipo()" <?=PaginaSEI::getInstance()->setCheckbox($objServicoDTO->getStrSinChaveAcesso())?> class="infraCheckbox"/>
        <label for="chkSinChaveAcesso" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Chave de Acesso</label>
      </div>

      <div id="divChkSinServidor" class="infraDivCheckbox">
        <input type="checkbox" name="chkSinServidor" id="chkSinServidor" onchange="trocarTipo()" <?=PaginaSEI::getInstance()->setCheckbox($objServicoDTO->getStrSinServidor())?> class="infraCheckbox"/>
        <label for="chkSinServidor" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Endereço</label>
      </div>
    </fieldset>
  </div>

  <div id="divServidores" class="infraAreaDados" style="height:15em;">
    <label id="lblServidores" for="selServidores" class="infraLabelObrigatorio">Servidores:</label>
    <input type="text" id="txtServidor" name="txtServidor" class="infraText" value="" onkeypress="return adicionarServidor(this,event);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <input type="hidden" id="hdnServidores" name="hdnServidores" class="infraText" value="" />
    <select id="selServidores" name="selServidores" size="6" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelServidores?>
    </select>
    <img id="imgExcluirServidores" onclick="objLupaServidores.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Servidores Selecionados" title="Remover Servidores Selecionados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>

  <input type="hidden" id="hdnIdServico" name="hdnIdServico" value="<?=$objServicoDTO->getNumIdServico();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>