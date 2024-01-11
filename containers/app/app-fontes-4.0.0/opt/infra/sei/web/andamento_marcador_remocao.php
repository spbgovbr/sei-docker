<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 08/08/2017 - criado por mga
 *
 */

try {
  require_once dirname(__FILE__) . '/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples', 'id_acompanhamento', 'id_usuario_atribuicao', 'id_marcador', 'id_procedimento'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  if (isset($_GET['pagina_simples'])){
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $bolFlagAlteracaoOK = false;

  $arrComandos = array();

  switch ($_GET['acao']) {
    case 'andamento_marcador_remover':
      $strTitulo = 'Remoção de Marcador';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmRemover" value="Remover" class="infraButton">Remover</button>';

      if ($_GET['acao_origem'] == 'procedimento_controlar') {
        $arrItensControleProcesso = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
        $arrIdProtocolo = $arrItensControleProcesso;
      } else {
        $arrIdProtocolo = explode(',',$_POST['hdnIdProtocolo']);
      }

      $strAncora = $arrIdProtocolo;

      $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';


      $arrObjAndamentoMarcadorDTO = array();
      foreach($arrIdProtocolo as $dblIdProtocolo){
        $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
        $objAndamentoMarcadorDTO->setDblIdProcedimento($dblIdProtocolo);
        $objAndamentoMarcadorDTO->setNumIdMarcador($_POST['hdnIdMarcador']);
        $arrObjAndamentoMarcadorDTO[] = $objAndamentoMarcadorDTO;
      }

      if (isset($_POST['sbmRemover'])) {

        try{

          $objAndamentoMarcadorRN = new AndamentoMarcadorRN();
          $ret = $objAndamentoMarcadorRN->remover($arrObjAndamentoMarcadorDTO);

          //PaginaSEI::getInstance()->adicionarMensagem('Marcador "'.$objRelProcedSituacaoUnidadeDTO->getNumIdSituacao().'" definido com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&resultado=1'.PaginaSEI::getInstance()->montarAncora($strAncora)));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strItensSelMarcador = MarcadorINT::montarSelectMarcadorRemocao($objAndamentoMarcadorDTO->getNumIdMarcador(), $arrIdProtocolo);
  $strLinkAjaxMarcadores = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=marcador_montar_opcoes');


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

#divDadosMarcador {height:5em;overflow:visible !important;<?=$strDisplayMarcador?>}
#lblMarcador {position:absolute;left:0%;top:0%;}
#selMarcador {position:absolute;left:0%;top:40%;}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
  //<script type="javascript">

  var objAjaxMarcadores = null;

  function inicializar(){

    document.getElementById('selMarcador').focus();

    $('#selMarcador').ddslick({width: 400,
      onSelected: function(data){
        if(data.selectedIndex > 0) {
          document.getElementById('hdnIdMarcador').value = data.selectedData.value;
        }else{
          document.getElementById('hdnIdMarcador').value = '';
        }
      }
    });

    objAjaxMarcadores = new infraAjaxComplementar(null,'<?=$strLinkAjaxMarcadores?>');
    objAjaxMarcadores.limparCampo = false;
    objAjaxMarcadores.mostrarAviso = false;
    objAjaxMarcadores.tempoAviso = 1000;

    objAjaxMarcadores.prepararExecucao = function(){
      return infraAjaxMontarPostPadraoSelect('null','',document.getElementById('hdnIdMarcador').value);
    };

    objAjaxMarcadores.processarResultado = function(arr){

      $('#selMarcador').ddslick('destroy');

      var base64=new infraBase64();
      document.getElementById('selMarcador').innerHTML = base64.decodificar(arr['marcadores']);

      $('#selMarcador').ddslick({width: 400,
        onSelected: function(data){
          if(data.selectedIndex > 0) {
            document.getElementById('hdnIdMarcador').value = data.selectedData.value;
          }else{
            document.getElementById('hdnIdMarcador').value = '';
          }
        }
      });
    };
  }

  function validarRemocao() {

    if (document.getElementById('hdnIdMarcador').value == ''){
      alert('Selecione um marcador para remoção.');
      document.getElementById('selMarcador').focus();
      return false;
    }

    return true;
  }

  function OnSubmitForm() {
    return validarRemocao();
  }

  //</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmAndamentoMarcadorRemocao" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&acao_retorno=procedimento_controlar')?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>
    <div id="divDadosMarcador" class="infraAreaDados">

      <label id="lblMarcador" for="selMarcador" accesskey="" class="infraLabelOpcional">Marcador:</label>
      <select id="selMarcador" name="selMarcador" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensSelMarcador?>
      </select>
      <input type="hidden" id="hdnIdMarcador" name="hdnIdMarcador" value="<?=$objAndamentoMarcadorDTO->getNumIdMarcador()?>" />
    </div>


    <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=implode(',',$arrIdProtocolo)?>" />

    <?
    PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>

<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>