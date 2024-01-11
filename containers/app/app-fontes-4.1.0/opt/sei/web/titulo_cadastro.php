<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/08/2018 - criado por cjy
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

  PaginaSEI::getInstance()->verificarSelecao('titulo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if (isset($_GET['cargo'])){
    $strParametros .= '&cargo='.$_GET['cargo'];
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $objTituloDTO = new TituloDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'titulo_cadastrar':
      $strTitulo = 'Novo Título';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTitulo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if (PaginaSEI::getInstance()->getTipoPagina()!=InfraPagina::$TIPO_PAGINA_SIMPLES){
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }


      $objTituloDTO->setNumIdTitulo(null);
      $objTituloDTO->setStrExpressao($_POST['txtExpressao']);
      $objTituloDTO->setStrAbreviatura($_POST['txtAbreviatura']);
      $objTituloDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarTitulo'])) {
        try{
          $objTituloRN = new TituloRN();
          $objTituloDTO = $objTituloRN->cadastrar($objTituloDTO);

          if (isset($_GET['cargo'])){
            $bolOk = true;
          }else {
            PaginaSEI::getInstance()->adicionarMensagem('Título "'.$objTituloDTO->getStrExpressao().'" cadastrado com sucesso.');
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_titulo='.$objTituloDTO->getNumIdTitulo().PaginaSEI::getInstance()->montarAncora($objTituloDTO->getNumIdTitulo())));
            die;
          }


        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'titulo_alterar':
      $strTitulo = 'Alterar Título';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTitulo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_titulo'])){
        $objTituloDTO->setNumIdTitulo($_GET['id_titulo']);
        $objTituloDTO->retTodos();
        $objTituloRN = new TituloRN();
        $objTituloDTO = $objTituloRN->consultar($objTituloDTO);
        if ($objTituloDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objTituloDTO->setNumIdTitulo($_POST['hdnIdTitulo']);
        $objTituloDTO->setStrExpressao($_POST['txtExpressao']);
        $objTituloDTO->setStrAbreviatura($_POST['txtAbreviatura']);
        $objTituloDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTituloDTO->getNumIdTitulo())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTitulo'])) {
        try{
          $objTituloRN = new TituloRN();
          $objTituloRN->alterar($objTituloDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Título "'.$objTituloDTO->getStrExpressao().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objTituloDTO->getNumIdTitulo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'titulo_consultar':
      $strTitulo = 'Consultar Título';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_titulo'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTituloDTO->setNumIdTitulo($_GET['id_titulo']);
      $objTituloDTO->setBolExclusaoLogica(false);
      $objTituloDTO->retTodos();
      $objTituloRN = new TituloRN();
      $objTituloDTO = $objTituloRN->consultar($objTituloDTO);
      if ($objTituloDTO===null){
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
#lblExpressao {position:absolute;left:0%;top:0%;width:60%;}
#txtExpressao {position:absolute;left:0%;top:40%;width:60%;}

#lblAbreviatura {position:absolute;left:0%;top:0%;width:20%;}
#txtAbreviatura {position:absolute;left:0%;top:40%;width:20%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  <?if ($bolOk){?>
    var sel = window.parent.document.getElementById('selTitulo');
    infraSelectAdicionarOption(sel,'<?=PaginaSEI::tratarHTML(TituloINT::formatarExpressaoAbreviatura($objTituloDTO->getStrExpressao(),$objTituloDTO->getStrAbreviatura()))?>','<?=$objTituloDTO->getNumIdTitulo()?>');
    infraSelectSelecionarItem(sel,'<?=$objTituloDTO->getNumIdTitulo()?>');
    self.setTimeout('infraFecharJanelaModal()',200);
  <?}else{?>
    if ('<?=$_GET['acao']?>'=='titulo_cadastrar'){
      document.getElementById('txtExpressao').focus();
    } else if ('<?=$_GET['acao']?>'=='titulo_consultar'){
      infraDesabilitarCamposAreaDados();
    }else{
      document.getElementById('btnCancelar').focus();
    }
    infraEfeitoTabelas(true);
  <?}?>
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtExpressao').value)=='') {
    alert('Informe a Expressão.');
    document.getElementById('txtExpressao').focus();
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
<form id="frmTituloCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblExpressao" for="txtExpressao" accesskey="" class="infraLabelObrigatorio">Expressão:</label>
  <input type="text" id="txtExpressao" name="txtExpressao" class="infraText" value="<?=PaginaSEI::tratarHTML($objTituloDTO->getStrExpressao());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblAbreviatura" for="txtAbreviatura" accesskey="" class="infraLabelOpcional">Abreviatura:</label>
  <input type="text" id="txtAbreviatura" name="txtAbreviatura" class="infraText" value="<?=PaginaSEI::tratarHTML($objTituloDTO->getStrAbreviatura());?>" onkeypress="return infraMascaraTexto(this,event,20);" maxlength="20" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdTitulo" name="hdnIdTitulo" value="<?=$objTituloDTO->getNumIdTitulo();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
