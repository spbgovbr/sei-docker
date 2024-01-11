<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/06/2012 - criado por bcu
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
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('tarja_assinatura_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  

  $objTarjaAssinaturaDTO = new TarjaAssinaturaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'tarja_assinatura_upload':
      //Trata do campo file que é postado para a mesma ação
      if (isset($_FILES['filArquivo'])){
        PaginaSEI::getInstance()->processarUpload('filArquivo', DIR_SEI_TEMP, false);
      }
      die;

    /*  
    case 'tarja_assinatura_cadastrar':
      $strTitulo = 'Nova Tarja';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTarjaAssinatura" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objTarjaAssinaturaDTO->setNumIdTarjaAssinatura(null);

      $strStaTarjaAssinatura = PaginaSEI::getInstance()->recuperarCampo('selStaTarjaAssinatura');
      if ($strStaTarjaAssinatura!==''){
        $objTarjaAssinaturaDTO->setStrStaTarjaAssinatura($strStaTarjaAssinatura);
      }else{
        $objTarjaAssinaturaDTO->setStrStaTarjaAssinatura(null);
      }

      $objTarjaAssinaturaDTO->setStrTexto($_POST['txaTexto']);
      $objTarjaAssinaturaDTO->setStrLogo($_POST['hdnTimbre']);
      $objTarjaAssinaturaDTO->setStrNomeArquivo($_POST['hdnNomeArquivo']);

      if (isset($_POST['sbmCadastrarTarjaAssinatura'])) {
        try{
          $objTarjaAssinaturaRN = new TarjaAssinaturaRN();
          $objTarjaAssinaturaDTO = $objTarjaAssinaturaRN->cadastrar($objTarjaAssinaturaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tarja "'.$objTarjaAssinaturaDTO->getStrStaTarjaAssinatura().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tarja_assinatura='.$objTarjaAssinaturaDTO->getNumIdTarjaAssinatura().PaginaSEI::getInstance()->montarAncora($objTarjaAssinaturaDTO->getNumIdTarjaAssinatura())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;
    */
      
    case 'tarja_assinatura_alterar':
      $strTitulo = 'Alterar Tarja';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTarjaAssinatura" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_tarja_assinatura'])){
        $objTarjaAssinaturaDTO->setNumIdTarjaAssinatura($_GET['id_tarja_assinatura']);
        $objTarjaAssinaturaDTO->retTodos();
        $objTarjaAssinaturaRN = new TarjaAssinaturaRN();
        $objTarjaAssinaturaDTO = $objTarjaAssinaturaRN->consultar($objTarjaAssinaturaDTO);
        if ($objTarjaAssinaturaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objTarjaAssinaturaDTO->setNumIdTarjaAssinatura($_POST['hdnIdTarjaAssinatura']);
        $objTarjaAssinaturaDTO->setStrStaTarjaAssinatura($_POST['hdnStaTarjaAssinatura']);
        $objTarjaAssinaturaDTO->setStrTexto($_POST['txaTexto']);
        $objTarjaAssinaturaDTO->setStrLogo($_POST['hdnTimbre']);
        $objTarjaAssinaturaDTO->setStrNomeArquivo($_POST['hdnNomeArquivo']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTarjaAssinaturaDTO->getNumIdTarjaAssinatura())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTarjaAssinatura'])) {
        try{
          $objTarjaAssinaturaRN = new TarjaAssinaturaRN();
          $objTarjaAssinaturaRN->alterar($objTarjaAssinaturaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Tarja alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTarjaAssinaturaDTO->getNumIdTarjaAssinatura())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tarja_assinatura_consultar':
      $strTitulo = 'Consultar Tarja';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_tarja_assinatura'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTarjaAssinaturaDTO->setNumIdTarjaAssinatura($_GET['id_tarja_assinatura']);
      $objTarjaAssinaturaDTO->setBolExclusaoLogica(false);
      $objTarjaAssinaturaDTO->retTodos();
      $objTarjaAssinaturaRN = new TarjaAssinaturaRN();
      $objTarjaAssinaturaDTO = $objTarjaAssinaturaRN->consultar($objTarjaAssinaturaDTO);
      if ($objTarjaAssinaturaDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelStaTarjaAssinatura = TarjaAssinaturaINT::montarSelectStaTarjaAssinatura('null','&nbsp;',$objTarjaAssinaturaDTO->getStrStaTarjaAssinatura());
  $strLinkUpload = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarja_assinatura_upload&acao_origem='.$_GET['acao']);
  
  if ($objTarjaAssinaturaDTO->getStrStaTarjaAssinatura()==TarjaAssinaturaRN::$TT_INSTRUCOES_VALIDACAO){
    $strDisplayLogotipo = 'display:none;';
    $strLinkAjuda = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=ajuda_variaveis_tarjas&tipo=V');
  }else{
    $strDisplayLogotipo = '';
    $strLinkAjuda = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=ajuda_variaveis_tarjas&tipo=A');
  }

  $strDisplayControleLogotipo = '';
  if (InfraString::isBolVazia($objTarjaAssinaturaDTO->getStrLogo())) {
    $strDisplayControleLogotipo = 'display:none;';
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
#lblStaTarjaAssinatura {position:absolute;left:0%;top:0%;}
#selStaTarjaAssinatura {position:absolute;left:0%;top:6%;width:25%;}

#lblTexto {position:absolute;left:0%;top:15%;}
#txaTexto {position:absolute;left:0%;top:21%;width:90%;}
#ancAjuda {position:absolute;left:91%;top:21%;}

#imgRemover {width:1.6em; height:1.6em}

#divUpload {<?=$strDisplayLogotipo?>}
#lblArquivo {position:absolute;left:0%;top:0%;}
#filArquivo {position:absolute;left:0%;top:40%;}
#imgRemover {width:1.6em; height:1.6em}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){

  if ('<?=$_GET['acao']?>'=='tarja_assinatura_consultar'){
    infraDesabilitarCamposAreaDados();
    document.getElementById('imgRemover').style.display="none";
  }else{
    document.getElementById('btnCancelar').focus();
  }
   
  if ('<?=$_GET['acao']?>'!='tarja_assinatura_consultar'){
    objUpload = new infraUpload('frmUpload','<?=$strLinkUpload?>');
    objUpload.validar = function() {
      nomeArquivo=document.getElementById('filArquivo').value;
      if (nomeArquivo.substr(nomeArquivo.length-4,4)!='.png') {
        alert ("Imagem do logotipo deve ser no formato PNG.");
        return false;
      } else return true;
    }
    objUpload.finalizou = function(arr){
      removerTimbre();
      if (arr!=null){
        document.getElementById('hdnNomeArquivo').value = arr['nome_upload'];
      }
    }
  }
 

 infraEfeitoTabelas();
}

function removerTimbre(){
  document.getElementById('hdnNomeArquivo').value="*REMOVER*";
  document.getElementById('imgTimbre').style.display='none';
  document.getElementById('imgRemover').style.display='none';
}


function validarCadastro() {

  /*
  if (!infraSelectSelecionado('selStaTarjaAssinatura')) {
    alert('Selecione uma Forma de Autenticação.');
    document.getElementById('selStaTarjaAssinatura').focus();
    return false;
  }
  */

  if (infraTrim(document.getElementById('txaTexto').value)=='') {
    alert('Informe o Conteúdo HTML.');
    document.getElementById('txaTexto').focus();
    return false;
  }

  /*
  if (infraTrim(document.getElementById('hdnNomeArquivo').value)=='') {
    alert('Informe o Logotipo.');
    document.getElementById('filArquivo').focus();
    return false;
  }
  */

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

function exibirAjuda(){
  infraAbrirJanelaModal('<?=$strLinkAjuda?>',800,600);
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmTarjaAssinaturaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('32em');
?>

  <label id="lblStaTarjaAssinatura" for="selStaTarjaAssinatura" accesskey="" class="infraLabelOpcional">Tipo:</label>
  <select id="selStaTarjaAssinatura" name="selStaTarjaAssinatura" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" disabled="disabled">
  <?=$strItensSelStaTarjaAssinatura?>
  </select>
  <label id="lblTexto" for="txaTexto" accesskey="" class="infraLabelObrigatorio">Conteúdo HTML:</label>  
  <textarea id="txaTexto" name="txaTexto" rows="13"  class="infraTextarea" style="font-family: Courier, 'Courier New', monospace" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=InfraString::formatarXML($objTarjaAssinaturaDTO->getStrTexto());?></textarea>
  <a href="javascript:void(0);" id="ancAjuda" onclick="exibirAjuda()" title="Ajuda" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>
  
  <input type="hidden" id="hdnIdTarjaAssinatura" name="hdnIdTarjaAssinatura" value="<?=$objTarjaAssinaturaDTO->getNumIdTarjaAssinatura();?>" />
  <input type="hidden" id="hdnStaTarjaAssinatura" name="hdnStaTarjaAssinatura" value="<?=$objTarjaAssinaturaDTO->getStrStaTarjaAssinatura();?>" />
  <input type="hidden" id="hdnNomeArquivo" name="hdnNomeArquivo" value="" />
  <input type="hidden" id="hdnTimbre" name="hdnTimbre" value="<?=$objTarjaAssinaturaDTO->getStrLogo();?>" />
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<form id="frmUpload">
  <div id="divUpload" class="infraAreaDados" style="height:5em;">
    <!-- 
    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
    -->
<? if ($_GET['acao']=='tarja_assinatura_alterar') { ?>
  <label id="lblArquivo" for="filArquivo" accesskey="" class="infraLabelInputFile">Escolher Logotipo...</label>
  <input type="file" id="filArquivo" accept="image/gif,image/png" name="filArquivo" class="infraInputFile" size="50" onchange="objUpload.executar();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" /><br />
<? }else{?>
  <label id="lblArquivo" for="filArquivo" accesskey="" >Logotipo:</label>
<?
}
?>
  </div>
  <img id="imgTimbre" style="border:1px dotted #c0c0c0;float:left;<?=$strDisplayControleLogotipo?>" src="<?="data:image/png;base64,".$objTarjaAssinaturaDTO->getStrLogo(); ?>" />
  <img id="imgRemover" style="<?=$strDisplayControleLogotipo?>" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Timbre" title="Remover Timbre" class="infraImg" onclick="removerTimbre();" />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>