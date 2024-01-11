<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 06/07/2018 - criado por cjy
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

  PaginaSEI::getInstance()->verificarSelecao('unidade_historico_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_unidade'));

  PaginaSEI::getInstance()->salvarCamposPost(array('selOrgao'));


  $objUnidadeHistoricoDTO = new UnidadeHistoricoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'unidade_historico_cadastrar':
      $strTitulo = 'Novo Histórico da Unidade';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarUnidadeHistorico" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objUnidadeHistoricoDTO->setNumIdUnidadeHistorico(null);

      $numIdOrgao = PaginaSEI::getInstance()->recuperarCampo('selOrgao');
      if ($numIdOrgao!==''){
        $objUnidadeHistoricoDTO->setNumIdOrgao($numIdOrgao);
      }else{
        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->retNumIdOrgao();
        $objUnidadeDTO->setNumIdUnidade($_GET['id_unidade']);
        $objUnidadeRN = new UnidadeRN();
        $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

        $objUnidadeHistoricoDTO->setNumIdOrgao($objUnidadeDTO->getNumIdOrgao());
      }

      $numIdUnidade = $_GET['id_unidade'];
      if ($numIdUnidade!==''){
        $objUnidadeHistoricoDTO->setNumIdUnidade($numIdUnidade);
      }else{
        $objUnidadeHistoricoDTO->setNumIdUnidade(null);
      }
      $objUnidadeHistoricoDTO->setStrSigla($_POST['txtSigla']);
      $objUnidadeHistoricoDTO->setStrDescricao($_POST['txtDescricao']);
      $objUnidadeHistoricoDTO->setDtaInicio($_POST['txtInicio']);
      $objUnidadeHistoricoDTO->setDtaFim($_POST['txtFim']);

      if (isset($_POST['sbmCadastrarUnidadeHistorico'])) {
        try{
          $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
          $objUnidadeHistoricoDTO = $objUnidadeHistoricoRN->cadastrar($objUnidadeHistoricoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Histórico da Unidade "'.$objUnidadeHistoricoDTO->getStrSigla().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_unidade_historico='.$objUnidadeHistoricoDTO->getNumIdUnidadeHistorico().PaginaSEI::getInstance()->montarAncora($objUnidadeHistoricoDTO->getNumIdUnidadeHistorico())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'unidade_historico_alterar':
      $strTitulo = 'Alterar Histórico da Unidade';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarUnidadeHistorico" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_unidade_historico'])){
        $objUnidadeHistoricoDTO->setNumIdUnidadeHistorico($_GET['id_unidade_historico']);
        $objUnidadeHistoricoDTO->retTodos();
        $objUnidadeHistoricoDTO->retStrSiglaOrgao();
        $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
        $objUnidadeHistoricoDTO = $objUnidadeHistoricoRN->consultar($objUnidadeHistoricoDTO);
        if ($objUnidadeHistoricoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objUnidadeHistoricoDTO->setNumIdUnidadeHistorico($_POST['hdnIdUnidadeHistorico']);
        $objUnidadeHistoricoDTO->setNumIdOrgao($_POST['selOrgao']);
        $objUnidadeHistoricoDTO->setStrSigla($_POST['txtSigla']);
        $objUnidadeHistoricoDTO->setStrDescricao($_POST['txtDescricao']);
        $objUnidadeHistoricoDTO->setDtaInicio($_POST['txtInicio']);
        $objUnidadeHistoricoDTO->setDtaFim($_POST['txtFim']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objUnidadeHistoricoDTO->getNumIdUnidadeHistorico())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarUnidadeHistorico'])) {
        try{
          $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
          $numIdUnidade = $_GET['id_unidade'];
          if ($numIdUnidade!==''){
            $objUnidadeHistoricoDTO->setNumIdUnidade($numIdUnidade);
          }else{
            $objUnidadeHistoricoDTO->setNumIdUnidade(null);
          }
          $objUnidadeHistoricoRN->alterar($objUnidadeHistoricoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Histórico da Unidade "'.$objUnidadeHistoricoDTO->getStrSigla().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objUnidadeHistoricoDTO->getNumIdUnidadeHistorico())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'unidade_historico_consultar':
      $strTitulo = 'Consultar Histórico da Unidade';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_unidade_historico'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objUnidadeHistoricoDTO->setNumIdUnidadeHistorico($_GET['id_unidade_historico']);
      $objUnidadeHistoricoDTO->setBolExclusaoLogica(false);
      $objUnidadeHistoricoDTO->retStrSiglaOrgao();
      $objUnidadeHistoricoDTO->retTodos();
      $objUnidadeHistoricoRN = new UnidadeHistoricoRN();
      $objUnidadeHistoricoDTO = $objUnidadeHistoricoRN->consultar($objUnidadeHistoricoDTO);
      if ($objUnidadeHistoricoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('null','&nbsp;',$objUnidadeHistoricoDTO->getNumIdOrgao());


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

#lblOrgao {position:absolute;left:0%;top:0%;width:25%;}
#selOrgao {position:absolute;left:0%;top:40%;width:25%;}

#lblSigla {position:absolute;left:0%;top:0%;width:30%;}
#txtSigla {position:absolute;left:0%;top:40%;width:30%;}

#lblDescricao {position:absolute;left:0%;top:0%;width:95%;}
#txtDescricao {position:absolute;left:0%;top:40%;width:95%;}

#lblInicio {position:absolute;left:0%;top:0%;width:25%;}
#txtInicio {position:absolute;left:0%;top:40%;width:9%;}
#imgCalInicio {position:absolute;left:10%;top:40%;}

#lblFim {position:absolute;left:0%;top:0%;width:25%;}
#txtFim {position:absolute;left:0%;top:40%;width:9%;}
#imgCalFim {position:absolute;left:10%;top:40%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='unidade_historico_cadastrar'){
    document.getElementById('selOrgao').focus();
  } else if ('<?=$_GET['acao']?>'=='unidade_historico_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}

function validarCadastro() {
  if (!infraSelectSelecionado('selOrgao')) {
    alert('Selecione um Órgão.');
    document.getElementById('selOrgao').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtSigla').value)=='') {
    alert('Informe a Sigla.');
    document.getElementById('txtSigla').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txtDescricao').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtInicio').value)=='') {
    alert('Informe a Data Inicial.');
    document.getElementById('txtInicio').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtFinal').value)=='') {
    alert('Informe a Data Final.');
    document.getElementById('txtFinal').focus();
    return false;
  }

  if (!infraValidarData(document.getElementById('txtInicio'))){
    return false;
  }

  if (!infraValidarData(document.getElementById('txtFim'))){
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
<form id="frmUnidadeHistoricoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();

PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblOrgao" for="selOrgao" accesskey="" class="infraLabelObrigatorio">Órgão:</label>
  <select id="selOrgao" name="selOrgao" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strItensSelOrgao?>
  </select>
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblSigla" for="txtSigla" accesskey="" class="infraLabelObrigatorio">Sigla:</label>
  <input type="text" id="txtSigla" name="txtSigla" class="infraText" value="<?=PaginaSEI::tratarHTML($objUnidadeHistoricoDTO->getStrSigla());?>" onkeypress="return infraMascaraTexto(this,event,30);" maxlength="30" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objUnidadeHistoricoDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblInicio" for="txtInicio" accesskey="" class="infraLabelObrigatorio">Data Inicial:</label>
  <input type="text" id="txtInicio" name="txtInicio" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objUnidadeHistoricoDTO->getDtaInicio());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalInicio" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtInicio',this);" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblFim" for="txtFim" accesskey="" class="infraLabelObrigatorio">Data Final:</label>
  <input type="text" id="txtFim" name="txtFim" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objUnidadeHistoricoDTO->getDtaFim());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalFim" title="Selecionar Data Final" alt="Selecionar Data Final" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtFim',this);" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdUnidadeHistorico" name="hdnIdUnidadeHistorico" value="<?=$objUnidadeHistoricoDTO->getNumIdUnidadeHistorico();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
