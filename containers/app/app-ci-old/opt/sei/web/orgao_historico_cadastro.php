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

  PaginaSEI::getInstance()->verificarSelecao('orgao_historico_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_orgao'));

  $objOrgaoHistoricoDTO = new OrgaoHistoricoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'orgao_historico_cadastrar':
      $strTitulo = 'Novo Histórico do Órgão';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarOrgaoHistorico" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objOrgaoHistoricoDTO->setNumIdOrgaoHistorico(null);
      $numIdOrgao = $_GET['id_orgao'];
      if ($numIdOrgao!==''){
        $objOrgaoHistoricoDTO->setNumIdOrgao($numIdOrgao);
      }else{
        $objOrgaoHistoricoDTO->setNumIdOrgao(null);
      }

      $objOrgaoHistoricoDTO->setStrSigla($_POST['txtSigla']);
      $objOrgaoHistoricoDTO->setStrDescricao($_POST['txtDescricao']);
      $objOrgaoHistoricoDTO->setDtaInicio($_POST['txtInicio']);
      $objOrgaoHistoricoDTO->setDtaFim($_POST['txtFim']);

      if (isset($_POST['sbmCadastrarOrgaoHistorico'])) {
        try{
          $objOrgaoHistoricoRN = new OrgaoHistoricoRN();
          $objOrgaoHistoricoDTO = $objOrgaoHistoricoRN->cadastrar($objOrgaoHistoricoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Histórico do Órgão "'.$objOrgaoHistoricoDTO->getStrSigla().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_orgao_historico='.$objOrgaoHistoricoDTO->getNumIdOrgaoHistorico().PaginaSEI::getInstance()->montarAncora($objOrgaoHistoricoDTO->getNumIdOrgaoHistorico())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'orgao_historico_alterar':
      $strTitulo = 'Alterar Histórico do Órgão';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarOrgaoHistorico" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_orgao_historico'])){
        $objOrgaoHistoricoDTO->setNumIdOrgaoHistorico($_GET['id_orgao_historico']);
        $objOrgaoHistoricoDTO->retTodos();
        $objOrgaoHistoricoRN = new OrgaoHistoricoRN();
        $objOrgaoHistoricoDTO = $objOrgaoHistoricoRN->consultar($objOrgaoHistoricoDTO);
        if ($objOrgaoHistoricoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objOrgaoHistoricoDTO->setNumIdOrgaoHistorico($_POST['hdnIdOrgaoHistorico']);
        $objOrgaoHistoricoDTO->setStrSigla($_POST['txtSigla']);
        $objOrgaoHistoricoDTO->setStrDescricao($_POST['txtDescricao']);
        $objOrgaoHistoricoDTO->setDtaInicio($_POST['txtInicio']);
        $objOrgaoHistoricoDTO->setDtaFim($_POST['txtFim']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objOrgaoHistoricoDTO->getNumIdOrgaoHistorico())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarOrgaoHistorico'])) {
        try{
          $objOrgaoHistoricoRN = new OrgaoHistoricoRN();

          $numIdOrgao = $_GET['id_orgao'];
          if ($numIdOrgao!==''){
            $objOrgaoHistoricoDTO->setNumIdOrgao($numIdOrgao);
          }else{
            $objOrgaoHistoricoDTO->setNumIdOrgao(null);
          }
          $objOrgaoHistoricoRN->alterar($objOrgaoHistoricoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Histórico do Órgão "'.$objOrgaoHistoricoDTO->getStrSigla().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objOrgaoHistoricoDTO->getNumIdOrgaoHistorico())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'orgao_historico_consultar':
      $strTitulo = 'Consultar Histórico do Órgão';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_orgao_historico'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objOrgaoHistoricoDTO->setNumIdOrgaoHistorico($_GET['id_orgao_historico']);
      $objOrgaoHistoricoDTO->setBolExclusaoLogica(false);
      $objOrgaoHistoricoDTO->retTodos();
      $objOrgaoHistoricoRN = new OrgaoHistoricoRN();
      $objOrgaoHistoricoDTO = $objOrgaoHistoricoRN->consultar($objOrgaoHistoricoDTO);
      if ($objOrgaoHistoricoDTO===null){
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
  if ('<?=$_GET['acao']?>'=='orgao_historico_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}

function validarCadastro() {

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
<form id="frmOrgaoHistoricoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();

PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblSigla" for="txtSigla" accesskey="" class="infraLabelObrigatorio">Sigla:</label>
  <input type="text" id="txtSigla" name="txtSigla" class="infraText" value="<?=PaginaSEI::tratarHTML($objOrgaoHistoricoDTO->getStrSigla());?>" onkeypress="return infraMascaraTexto(this,event,30);" maxlength="30" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objOrgaoHistoricoDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblInicio" for="txtInicio" accesskey="" class="infraLabelObrigatorio">Data Inicial:</label>
  <input type="text" id="txtInicio" name="txtInicio" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objOrgaoHistoricoDTO->getDtaInicio());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalInicio" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtInicio',this);" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->abrirAreaDados('4.5em');
?>
  <label id="lblFim" for="txtFim" accesskey="" class="infraLabelObrigatorio">Data Final:</label>
  <input type="text" id="txtFim" name="txtFim" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objOrgaoHistoricoDTO->getDtaFim());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <img id="imgCalFim" title="Selecionar Data Final" alt="Selecionar Data Final" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtFim',this);" />
<?
PaginaSEI::getInstance()->fecharAreaDados();
?>
  <input type="hidden" id="hdnIdOrgaoHistorico" name="hdnIdOrgaoHistorico" value="<?=$objOrgaoHistoricoDTO->getNumIdOrgaoHistorico();?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
