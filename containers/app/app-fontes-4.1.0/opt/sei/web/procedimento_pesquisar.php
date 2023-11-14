<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array('selTipoProcedimento'));

  $arrNumIdSerie = array();
  if(isset($_POST['selSerie'])){
    $arrNumIdSerie = $_POST['selSerie'];
    if (!is_array($arrNumIdSerie)){
      $arrNumIdSerie = array($arrNumIdSerie);
    }
  }

  $arrNumIdUnidade = array();
  if(isset($_POST['selUnidade'])){
      $arrNumIdUnidade = $_POST['selUnidade'];
      if (!is_array($arrNumIdUnidade)){
          $arrNumIdUnidade= array($arrNumIdUnidade);
      }
  }


  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  if (isset($_GET['id_procedimento'])){
    $dblIdProcedimento=$_GET['id_procedimento'];
    $strParametros .= '&id_procedimento='.$dblIdProcedimento;
  }

  $arrComandos = array();
  switch($_GET['acao']){
    
    case 'procedimento_pesquisar':
      
      $strTitulo = 'Pesquisar no Processo';

      $strPalavrasPesquisa=null;
      if(isset($_POST['txtPesquisa'])){
          $strPalavrasPesquisa = $_POST['txtPesquisa'];
      }

      $strLinkAjuda = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=pesquisa_solr_ajuda&acao_origem='.$_GET['acao']);

      if (!InfraString::isBolVazia($strPalavrasPesquisa) || InfraArray::contar($arrNumIdSerie)|| InfraArray::contar($arrNumIdUnidade)) {

        try {

          $objPesquisaProtocoloSolrDTO = new PesquisaProtocoloSolrDTO();
          $objPesquisaProtocoloSolrDTO->setStrPalavrasChave($strPalavrasPesquisa);
          $objPesquisaProtocoloSolrDTO->setStrSinTramitacao(null);
          $objPesquisaProtocoloSolrDTO->setStrSinProcessos(null);
          $objPesquisaProtocoloSolrDTO->setStrSinDocumentosGerados('S');
          $objPesquisaProtocoloSolrDTO->setStrSinDocumentosRecebidos('S');
          $objPesquisaProtocoloSolrDTO->setArrNumIdOrgao(null);
          $objPesquisaProtocoloSolrDTO->setNumIdContato(null);
          $objPesquisaProtocoloSolrDTO->setStrSinInteressado(null);
          $objPesquisaProtocoloSolrDTO->setStrSinRemetente(null);
          $objPesquisaProtocoloSolrDTO->setStrSinDestinatario(null);
          $objPesquisaProtocoloSolrDTO->setNumIdAssinante(null);
          $objPesquisaProtocoloSolrDTO->setStrDescricao(null);
          $objPesquisaProtocoloSolrDTO->setStrObservacao(null);
          $objPesquisaProtocoloSolrDTO->setNumIdAssunto(null);
          $objPesquisaProtocoloSolrDTO->setStrProtocoloPesquisa(null);
          $objPesquisaProtocoloSolrDTO->setNumIdTipoProcedimento(null);

          if (InfraArray::contar($arrNumIdSerie)) {
            $objPesquisaProtocoloSolrDTO->setNumIdSerie($arrNumIdSerie);
          }else{
            $objPesquisaProtocoloSolrDTO->setNumIdSerie(null);
          }

          if (InfraArray::contar($arrNumIdUnidade)) {
              $objPesquisaProtocoloSolrDTO->setNumIdUnidadeGeradora($arrNumIdUnidade);
          }else{
              $objPesquisaProtocoloSolrDTO->setNumIdUnidadeGeradora(null);
          }

          $objPesquisaProtocoloSolrDTO->setStrNumero(null);
          $objPesquisaProtocoloSolrDTO->setStrNomeArvore(null);
          $objPesquisaProtocoloSolrDTO->setDinValorInicio(null);
          $objPesquisaProtocoloSolrDTO->setDinValorFim(null);
          $objPesquisaProtocoloSolrDTO->setDtaInicio(null);
          $objPesquisaProtocoloSolrDTO->setDtaFim(null);
          $objPesquisaProtocoloSolrDTO->setNumIdUsuarioGerador1(null);
          $objPesquisaProtocoloSolrDTO->setNumIdUsuarioGerador2(null);
          $objPesquisaProtocoloSolrDTO->setNumIdUsuarioGerador3(null);
          $objPesquisaProtocoloSolrDTO->setNumInicioPaginacao($_POST['hdnInicio']);
          $objPesquisaProtocoloSolrDTO->setDblIdProcedimento($dblIdProcedimento);
          $objPesquisaProtocoloSolrDTO->setBolArvore(true);
          $objPesquisaProtocoloSolrDTO->setStrStaTipoData(null);

          SolrProtocolo::executar($objPesquisaProtocoloSolrDTO);

          $strResultado = $objPesquisaProtocoloSolrDTO->getStrResultadoPesquisa();

        } catch (Exception $e) {
          SeiSolrUtil::tratarErroPesquisa(PaginaSEI::getInstance(), $e);
        }
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strOptionsSeries = SerieINT::montarSelectMultiploProcedimento($_GET['id_procedimento'],$arrNumIdSerie);
  $strOptionsUnidades = UnidadeINT::montarSelectMultiploUnidadesDocumentosProcesso($_GET['id_procedimento'],$arrNumIdUnidade);

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
if(0){?><style><?}
?>
  #divGeral {height:16em;width:99%;overflow:visible;max-width: 1000px;}


  #lblPesquisa {positsion:absolute;left:0%;top:0%;width:50%;display:none;}
  #txtPesquisa {position:absolute;left:0%;top:2%;width:80%;}
  #ancAjuda {position:absolute;left:81%;top:3%;}
  #sbmPesquisar {position:absolute;left:87%;top:0%;}

  #lblSerie {position:absolute;left:0%;top:21%;width:50%;}
  #selSerie, .multipleSelectSerie {position:absolute;left:0%;top:33%;width:50%;}

  #lblUnidade {position:absolute;left:0%;top:50%;width:50%;}
  #selUnidade, .multipleSelectUnidade {position:absolute;left:0%;top:62%;width:50%;}

<?
if(0){?></style><?}
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
if(0){?><script><?}
?>

function inicializar(){



  document.getElementById('txtPesquisa').focus();
  infraEfeitoTabelas();
}

$( document ).ready(function() {
  $("#selSerie").multipleSelect({
    filter: false,
    minimumCountSelected: 1,
    selectAll: false,
  });

  $("#selUnidade").multipleSelect({
    filter: false,
    minimumCountSelected: 1,
    selectAll: false,
  });
});

function OnSubmitForm() {

  if (infraTrim(document.getElementById('txtPesquisa').value)=='' && document.getElementById('selSerie').value=='' && document.getElementById('selUnidade').value==''){
    alert('Nenhum critério de pesquisa informado.');
    return false;
  }

  return true;
}

function navegar(inicio) {
  document.getElementById('hdnInicio').value = inicio;
  if (typeof(window.onSubmitForm)=='function' && !window.onSubmitForm()) {
    return;
  }
  document.getElementById('frmPesquisaProtocolo').submit();
}
<?
if(0){?></script><?}
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPesquisaProtocolo" name="frmPesquisaProtocolo" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <br />
  <br />
  <div id="divGeral" class="infraAreaDados">
  
 	<label id="lblPesquisa" for="txtPesquisa" class="infraLabelObrigatorio">Descrição:</label>
  <input type="text" id="txtPesquisa" name="txtPesquisa" class="infraText"  maxlength="250" onkeypress="return infraLimitarTexto(this,event,250);" value="<?=PaginaSEI::tratarHTML($strPalavrasPesquisa)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <a id="ancAjuda" href="<?=$strLinkAjuda?>" target="janAjuda" title="Ajuda para Pesquisa" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>

  <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelOpcional">Tipos de documentos disponíveis neste processo:</label>
  <select style="display: none" multiple id="selSerie" name="selSerie[]" class="infraSelect multipleSelectSerie" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strOptionsSeries;?>
  </select>

  <label id="lblUnidade" for="selUnidade" accesskey="" class="infraLabelOpcional">Unidade geradora:</label>
  <select style="display: none" multiple id="selUnidade" name="selUnidade[]" class="infraSelect multipleSelectUnidade" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strOptionsUnidades;?>
  </select>

  <input type="submit" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  </div>
  <div id="conteudo" style="width:99%;" class="infraAreaTabela">
  <?=$strResultado;?>
  </div>
  <input type="hidden" id="hdnInicio" name="hdnInicio" value="0" />
</form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>