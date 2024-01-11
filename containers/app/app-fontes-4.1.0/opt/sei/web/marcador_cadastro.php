<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 11/11/2015 - criado por mga
*
* Versão do Gerador de Código: 1.36.0
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

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('pagina_simples'));

  PaginaSEI::getInstance()->verificarSelecao('marcador_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  if (isset($_GET['pagina_simples'])) {
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  //PaginaSEI::getInstance()->salvarCamposPost(array());

  $objMarcadorDTO = new MarcadorDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  $bolCadastroOk = false;

  switch($_GET['acao']){
    case 'marcador_cadastrar':
      $strTitulo = 'Novo Marcador';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarMarcador" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if (PaginaSEI::getInstance()->getTipoPagina()!=InfraPagina::$TIPO_PAGINA_SIMPLES){
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }

      $objMarcadorDTO->setNumIdMarcador(null);
      $objMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objMarcadorDTO->setStrNome($_POST['txtNome']);
      $objMarcadorDTO->setStrDescricao($_POST['txaDescricao']);
      $objMarcadorDTO->setStrStaIcone($_POST['hdnStaIcone']);
      $objMarcadorDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarMarcador'])) {
        try{
          $objMarcadorRN = new MarcadorRN();
          $objMarcadorDTO = $objMarcadorRN->cadastrar($objMarcadorDTO);

          if (PaginaSEI::getInstance()->getAcaoRetorno()!='marcador_listar'){
            $bolCadastroOk = true;
          }else {
            PaginaSEI::getInstance()->adicionarMensagem('Marcador "' . $objMarcadorDTO->getStrNome() . '" cadastrado com sucesso.');
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '&id_marcador=' . $objMarcadorDTO->getNumIdMarcador() . PaginaSEI::getInstance()->montarAncora($objMarcadorDTO->getNumIdMarcador())));
            die;
          }
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'marcador_alterar':
      $strTitulo = 'Alterar Marcador';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarMarcador" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_marcador'])){
        $objMarcadorDTO->setNumIdMarcador($_GET['id_marcador']);
        $objMarcadorDTO->setBolExclusaoLogica(false);
        $objMarcadorDTO->retTodos();
        $objMarcadorRN = new MarcadorRN();
        $objMarcadorDTO = $objMarcadorRN->consultar($objMarcadorDTO);
        if ($objMarcadorDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objMarcadorDTO->setNumIdMarcador($_POST['hdnIdMarcador']);
        $objMarcadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objMarcadorDTO->setStrNome($_POST['txtNome']);
        $objMarcadorDTO->setStrDescricao($_POST['txaDescricao']);
        $objMarcadorDTO->setStrStaIcone($_POST['hdnStaIcone']);
        //$objMarcadorDTO->setStrSinAtivo('S');
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMarcadorDTO->getNumIdMarcador())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarMarcador'])) {
        try{
          $objMarcadorRN = new MarcadorRN();
          $objMarcadorRN->alterar($objMarcadorDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Marcador "'.$objMarcadorDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objMarcadorDTO->getNumIdMarcador())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'marcador_consultar':
      $strTitulo = 'Consultar Marcador';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_marcador'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objMarcadorDTO->setNumIdMarcador($_GET['id_marcador']);
      $objMarcadorDTO->setBolExclusaoLogica(false);
      $objMarcadorDTO->retTodos();
      $objMarcadorRN = new MarcadorRN();
      $objMarcadorDTO = $objMarcadorRN->consultar($objMarcadorDTO);
      if ($objMarcadorDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelStaIcone = MarcadorINT::montarSelectStaIcone('null','&nbsp;',$objMarcadorDTO->getStrStaIcone());

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

#lblStaIcone {position:absolute;left:0%;top:0%;}
#selStaIcone {position:absolute;left:0%;top:2%;}

#lblNome {position:absolute;left:170px;top:0%;width:60%;}
#txtNome {position:absolute;left:170px;top:2.2%;width:60%;}

#lblDescricao {position:absolute;left:0%;top:11%;width:95%;visibility:hidden;}
#txaDescricao {position:absolute;left:0%;top:15%;width:95%;visibility:hidden;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script type="text/javascript">

function inicializar(){

  <?if ($bolCadastroOk){?>

    if ((modalOrigem = infraObterJanelaOrigemModal())!=null){
      modalOrigem.recarregarMarcadores('<?=$objMarcadorDTO->getNumIdMarcador()?>');
    }else{
      parent.recarregarMarcadores('<?=$objMarcadorDTO->getNumIdMarcador()?>');
    }

    self.setTimeout('infraFecharJanelaModal()',200);

  <?}else{?>

    if ('<?=$_GET['acao']?>'=='marcador_cadastrar'){
      document.getElementById('selStaIcone').focus();
    } else if ('<?=$_GET['acao']?>'=='marcador_consultar'){
      infraDesabilitarCamposAreaDados();
    }else{
      document.getElementById('btnCancelar').focus();
    }

    $('#selStaIcone').ddslick({width: 150,
      onSelected: function(data){
        if(data.selectedIndex > 0) {
          document.getElementById('hdnStaIcone').value = data.selectedData.value;

          var scrollingElement = document.scrollingElement || document.documentElement;

          if (scrollingElement!=null) {
            scrollingElement.scrollTop = 0;
          }

          document.getElementById('txtNome').focus();
        }else{
          document.getElementById('hdnStaIcone').value = '';
        }
      }
    });
  <?}?>
}

function validarCadastro() {

  if (infraTrim(document.getElementById('hdnStaIcone').value)=='') {
    alert('Selecione um Ícone.');
    document.getElementById('selStaIcone').focus();
    return false;
  }

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

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmMarcadorCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('100em');
?>
  <label id="lblStaIcone" for="selStaIcone" accesskey="" class="infraLabelObrigatorio">Ícone:</label>
  <select id="selStaIcone" name="selStaIcone" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelStaIcone?>
  </select>
  <input type="hidden" id="hdnStaIcone" name="hdnStaIcone" value="<?=$objMarcadorDTO->getStrStaIcone()?>" />

  <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objMarcadorDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txaDescricao" class="infraLabelOpcional">Descrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'3':'4'?>" onkeypress="return infraLimitarTexto(this,event,250);" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objMarcadorDTO->getStrDescricao());?></textarea>

  <input type="hidden" id="hdnIdMarcador" name="hdnIdMarcador" value="<?=$objMarcadorDTO->getNumIdMarcador();?>" />
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