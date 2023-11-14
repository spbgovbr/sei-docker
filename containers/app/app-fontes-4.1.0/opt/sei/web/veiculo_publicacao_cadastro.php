<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 24/07/2013 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.1
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

  PaginaSEI::getInstance()->verificarSelecao('veiculo_publicacao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objVeiculoPublicacaoDTO = new VeiculoPublicacaoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'veiculo_publicacao_cadastrar':
      $strTitulo = 'Novo Veículo de Publicação';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarVeiculoPublicacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao(null);
      $objVeiculoPublicacaoDTO->setStrNome($_POST['txtNome']);
      $objVeiculoPublicacaoDTO->setStrDescricao($_POST['txtDescricao']);
      $objVeiculoPublicacaoDTO->setStrStaTipo($_POST['selStaTipo']);
      $objVeiculoPublicacaoDTO->setStrSinFonteFeriados(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinFonteFeriados']));
      $objVeiculoPublicacaoDTO->setStrSinExibirPesquisaInterna(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinExibirPesquisaInterna']));
      $objVeiculoPublicacaoDTO->setStrSinPermiteExtraordinaria(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPermiteExtraordinaria']));
      $objVeiculoPublicacaoDTO->setStrWebService($_POST['txtWebService']);
      $objVeiculoPublicacaoDTO->setStrSinAtivo('S');
      
      if (isset($_POST['sbmCadastrarVeiculoPublicacao'])) {        
        try{          
          $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
          $objVeiculoPublicacaoDTO = $objVeiculoPublicacaoRN->cadastrar($objVeiculoPublicacaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Veículo de Publicação "'.$objVeiculoPublicacaoDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_veiculo_publicacao='.$objVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao().PaginaSEI::getInstance()->montarAncora($objVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'veiculo_publicacao_alterar':
      $strTitulo = 'Alterar Veículo de Publicação';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarVeiculoPublicacao" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
//      $arrComandos[] = '<button type="button" accesskey="S" name="btnAlterarSerie" onclick="infraAbrirBarraProgresso(this.form, \''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao_origem'].'&executar=1').'\', 600, 200);" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_veiculo_publicacao'])){
        $objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($_GET['id_veiculo_publicacao']);
        $objVeiculoPublicacaoDTO->retTodos();
        $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
        $objVeiculoPublicacaoDTO = $objVeiculoPublicacaoRN->consultar($objVeiculoPublicacaoDTO);
        if ($objVeiculoPublicacaoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($_POST['hdnIdVeiculoPublicacao']);
        $objVeiculoPublicacaoDTO->setStrNome($_POST['txtNome']);
        $objVeiculoPublicacaoDTO->setStrDescricao($_POST['txtDescricao']);
        $objVeiculoPublicacaoDTO->setStrStaTipo($_POST['selStaTipo']);        
        $objVeiculoPublicacaoDTO->setStrSinFonteFeriados(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinFonteFeriados']));
        $objVeiculoPublicacaoDTO->setStrSinExibirPesquisaInterna(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinExibirPesquisaInterna']));
        $objVeiculoPublicacaoDTO->setStrSinPermiteExtraordinaria(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPermiteExtraordinaria']));
        $objVeiculoPublicacaoDTO->setStrWebService($_POST['txtWebService']);
        $objVeiculoPublicacaoDTO->setStrSinAtivo('S');
      }
            

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if ($_GET['executar']=='1'){
        PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo,null,false);
        try{
      
          $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
          $objVeiculoPublicacaoRN->indexar($objVeiculoPublicacaoDTO);

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        PaginaSEI::getInstance()->finalizarBarraProgresso2(SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao()),true);
        die;
      }
      

      if (isset($_POST['sbmAlterarVeiculoPublicacao'])) {
        try{
          $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
          $bolAbrirBarraProgresso=$objVeiculoPublicacaoRN->alterar($objVeiculoPublicacaoDTO);
          if ($bolAbrirBarraProgresso) {
            break;
          }

          PaginaSEI::getInstance()->adicionarMensagem('Veículo de Publicação "'.$objVeiculoPublicacaoDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

      break;

    case 'veiculo_publicacao_consultar':
      $strTitulo = 'Consultar Veículo de Publicação';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_veiculo_publicacao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($_GET['id_veiculo_publicacao']);
      $objVeiculoPublicacaoDTO->setBolExclusaoLogica(false);
      $objVeiculoPublicacaoDTO->retTodos();
      $objVeiculoPublicacaoRN = new VeiculoPublicacaoRN();
      $objVeiculoPublicacaoDTO = $objVeiculoPublicacaoRN->consultar($objVeiculoPublicacaoDTO);
      if ($objVeiculoPublicacaoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelStaTipo = VeiculoPublicacaoINT::montarSelectStaTipo('null','&nbsp;',$objVeiculoPublicacaoDTO->getStrStaTipo());

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
#lblNome {position:absolute;left:0%;top:0%;width:40%;}
#txtNome {position:absolute;left:0%;top:14%;width:40%;}

#lblDescricao {position:absolute;left:0%;top:34%;width:95%;}
#txtDescricao {position:absolute;left:0%;top:49%;width:95%;}

#lblStaTipo {position:absolute;left:0%;top:69%;width:15%;}
#selStaTipo {position:absolute;left:0%;top:84%;width:15%;}

#lblWebService {position:relative;float:left;width:90%;}
#txtWebService {position:relative;float:left;width:70%; margin-bottom:2em;}

#divSinExibirPesquisaInterna {position:relative;float:left;width:90%;}
#divSinFonteFeriados {position:relative;float:left;width:90%;}
#divSinPermiteExtraordinaria {position:relative;float:left;width:90%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='veiculo_publicacao_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='veiculo_publicacao_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  
  changeSelStaTipo();
  
  infraEfeitoTabelas();
<?if ($bolAbrirBarraProgresso==true){?>
  infraAbrirBarraProgresso(document.getElementById('frmVeiculoPublicacaoCadastro'), '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao_origem'].'&executar=1')?>', 600, 200);
<?}?>
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  if (!infraSelectSelecionado('selStaTipo')) {
    alert('Selecione um Tipo.');
    document.getElementById('selStaTipo').focus();
    return false;
  }
  
  if (infraTrim(document.getElementById('txtWebService').value)=='' && infraTrim(document.getElementById('selStaTipo').value)=='E') {
    alert('Informe o Web Service.');
    document.getElementById('txtWebService').focus();
    return false;
  }    

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

function changeSelStaTipo(){
  if (document.getElementById('selStaTipo').value == '<?=VeiculoPublicacaoRN::$TV_EXTERNO?>'){
    $('#divCamposAdicionais div').show();
  }else if (document.getElementById('selStaTipo').value == '<?=VeiculoPublicacaoRN::$TV_MODULO?>'){
    $('#divCamposAdicionais div').hide();
    $('#divSinPermiteExtraordinaria input[type="checkbox"]').prop('checked', false);
    $('#divSinExibirPesquisaInterna').show();
    $('#divSinFonteFeriados').hide();
  }else{
    $('#divCamposAdicionais div').hide();
    $('#divCamposVeiculoExterno input[type="text"]').val('');
    $('#divCamposAdicionais input[type="checkbox"]').prop('checked', false);
  }
} 

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmVeiculoPublicacaoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();

?>
  <div id="divCamposFixos" class="infraAreaDados" style="height:14em;">
  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objVeiculoPublicacaoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txtDescricao" accesskey="D" class="infraLabelOpcional"><span class="infraTeclaAtalho">D</span>escrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objVeiculoPublicacaoDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,500);" maxlength="500" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblStaTipo" for="selStaTipo" accesskey="T" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">T</span>ipo:</label>
  <select id="selStaTipo" name="selStaTipo" onchange="changeSelStaTipo();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelStaTipo?>
  </select>    
      
  </div>

  <div id="divCamposAdicionais" class="infraAreaDados" style="margin-top:2em;">
    <div id="divCamposVeiculoExterno" style="display: none;">
      <label id="lblWebService" for="txtWebService" accesskey="" class="infraLabelObrigatorio">Web Service:</label>
      <input type="text" id="txtWebService" name="txtWebService" class="infraText"
             value="<?= PaginaSEI::tratarHTML($objVeiculoPublicacaoDTO->getStrWebService()); ?>"
             onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250"
             tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    </div>
    <div id="divSinExibirPesquisaInterna" class="infraDivCheckbox" style="display: none;">
      <input type="checkbox" id="chkSinExibirPesquisaInterna" name="chkSinExibirPesquisaInterna"
             class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox($objVeiculoPublicacaoDTO->getStrSinExibirPesquisaInterna()) ?>
             tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
      <label id="lblSinExibirPesquisaInterna" for="chkSinExibirPesquisaInterna" accesskey="" class="infraLabelCheckbox">Exibir
        as publicações enviadas para este veículo na pesquisa de publicações interna</label>
    </div>

    <div id="divSinFonteFeriados" class="infraDivCheckbox" style="display: none;">
      <input type="checkbox" id="chkSinFonteFeriados" name="chkSinFonteFeriados"
             class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox($objVeiculoPublicacaoDTO->getStrSinFonteFeriados()) ?>
             tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
      <label id="lblSinFonteFeriados" for="chkSinFonteFeriados" accesskey="" class="infraLabelCheckbox">Utilizar os
        feriados cadastrados neste veículo como padrão para o sistema</label>
    </div>

    <div id="divSinPermiteExtraordinaria" class="infraDivCheckbox" style="display: none;">
      <input type="checkbox" id="chkSinPermiteExtraordinaria" name="chkSinPermiteExtraordinaria"
             class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox($objVeiculoPublicacaoDTO->getStrSinPermiteExtraordinaria()) ?>
             tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
      <label id="lblSinPermiteExtraordinaria" for="chkSinPermiteExtraordinaria" accesskey="" class="infraLabelCheckbox">Permite
        edição extraordinária</label>
    </div>

  </div>
  
  <input type="hidden" id="hdnIdVeiculoPublicacao" name="hdnIdVeiculoPublicacao" value="<?=$objVeiculoPublicacaoDTO->getNumIdVeiculoPublicacao();?>" />
  <input type="hidden" id="hdnPublicacoesSimultaneas" name="hdnPublicacoesSimultaneas" value="<?=$_POST['hdnPublicacoesSimultaneas']?>" />
  <?  
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>