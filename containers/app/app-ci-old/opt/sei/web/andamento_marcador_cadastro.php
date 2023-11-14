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

  $objAndamentoMarcadorRN = new AndamentoMarcadorRN();

  switch ($_GET['acao']) {
    case 'andamento_marcador_cadastrar':
      $strTitulo = 'Adicionar Marcador';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" value="Salvar" class="infraButton">Salvar</button>';

      $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();

      if ($_GET['acao_origem']=='andamento_marcador_gerenciar') {
        $arrIdProtocolo = array($_GET['id_procedimento']);
      } else if ($_GET['acao_origem'] == 'procedimento_controlar') {
        $arrItensControleProcesso = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
        $arrIdProtocolo = $arrItensControleProcesso;
      } else {
        $arrIdProtocolo = explode(',',$_POST['hdnIdProtocolo']);
      }

      if (PaginaSEI::getInstance()->getAcaoRetorno()=='andamento_marcador_gerenciar'){
        $strAncora = $_POST['hdnIdMarcador'];
      }else if (PaginaSEI::getInstance()->getAcaoRetorno()=='acompanhamento_listar'){
        $strAncora = $_GET['id_acompanhamento'];
      }else{
        $strAncora = $arrIdProtocolo;
      }

      $arrComandos[] = '<button type="button" accesskey="V" name="btnVoltar" id="btnVoltar" value="Voltar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';

      $objAndamentoMarcadorDTO->setDblIdProcedimento($arrIdProtocolo);
      $objAndamentoMarcadorDTO->setNumIdMarcador($_POST['hdnIdMarcador']);
      $objAndamentoMarcadorDTO->setStrTexto($_POST['txaTexto']);

      if (isset($_POST['sbmSalvar'])) {

        try{

          $ret = $objAndamentoMarcadorRN->cadastrar($objAndamentoMarcadorDTO);

          //PaginaSEI::getInstance()->adicionarMensagem('Marcador "'.$objRelProcedSituacaoUnidadeDTO->getNumIdSituacao().'" definido com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&resultado=1'.PaginaSEI::getInstance()->montarAncora($strAncora)));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

      break;

    case 'andamento_marcador_alterar':
      $strTitulo = 'Alterar Texto do Marcador';

      PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" value="Salvar" class="infraButton">Salvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();

      if ($_GET['acao_origem']=='andamento_marcador_gerenciar'){

        $objAndamentoMarcadorDTO->retNumIdAndamentoMarcador();
        $objAndamentoMarcadorDTO->retNumIdMarcador();
        $objAndamentoMarcadorDTO->retDblIdProcedimento();
        $objAndamentoMarcadorDTO->retStrTexto();
        $objAndamentoMarcadorDTO->setNumIdMarcador($_GET['id_marcador']);
        $objAndamentoMarcadorDTO->setDblIdProcedimento($_GET['id_procedimento']);
        $objAndamentoMarcadorDTO->setStrSinUltimo('S');

        $objAndamentoMarcadorDTO = $objAndamentoMarcadorRN->consultar($objAndamentoMarcadorDTO);

        if ($objAndamentoMarcadorDTO==null){

          $objAndamentoMarcadorDTO = new AndamentoMarcadorDTO();
          $objAndamentoMarcadorDTO->setNumIdMarcador(null);
          $objAndamentoMarcadorDTO->setStrTexto(null);
          $arrIdProtocolo = array();

          PaginaSEI::getInstance()->setStrMensagem("Marcador foi removido do processo.", InfraPagina::$TIPO_MSG_AVISO);
          $bolNaoEncontrado = true;

        }else{
          $arrIdProtocolo = array($objAndamentoMarcadorDTO->getDblIdProcedimento());
        }

      } else {

        $arrIdProtocolo = explode(',',$_POST['hdnIdProtocolo']);

        $objAndamentoMarcadorDTO->setNumIdMarcador($_POST['hdnIdMarcador']);
        $objAndamentoMarcadorDTO->setDblIdProcedimento($arrIdProtocolo);
        $objAndamentoMarcadorDTO->setStrTexto($_POST['txaTexto']);
      }

      if (isset($_POST['sbmSalvar'])) {

        try{

          $ret = $objAndamentoMarcadorRN->alterar($objAndamentoMarcadorDTO);

          //PaginaSEI::getInstance()->adicionarMensagem('Marcador "'.$objRelProcedSituacaoUnidadeDTO->getNumIdSituacao().'" definido com sucesso.');
          $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&resultado=1'.PaginaSEI::getInstance()->montarAncora($_GET['id_marcador']));
          //die;
          $bolFlagAlteracaoOK = true;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
  }

  $strDisplayMarcador = '';
  $strItensSelMarcador = '';
  $strLinkAjaxMarcadores = '';

  if ($_GET['acao']=='andamento_marcador_cadastrar'){

    if (SessaoSEI::getInstance()->verificarPermissao('marcador_cadastrar')) {
      $strImgNovoMarcador = '<img id="imgNovoMarcador" onclick="cadastrarMarcador();" src="'.PaginaSEI::getInstance()->getIconeMais().'" alt="Novo Marcador" title="Novo Marcador" class="infraImg" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"/>';
      $strLinkNovoMarcador = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=marcador_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&pagina_simples=1');
    }

    $strItensSelMarcador = MarcadorINT::montarSelectMarcador('null', '&nbsp;', $objAndamentoMarcadorDTO->getNumIdMarcador());
    $strLinkAjaxMarcadores = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=marcador_montar_opcoes');

  }else{
    $strDisplayMarcador = 'display:none;';
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

#divDadosMarcador {height:5em;overflow:visible !important;<?=$strDisplayMarcador?>}
#lblMarcador {position:absolute;left:0%;top:0%;}
#selMarcador {position:absolute;left:0%;top:40%;}
#imgNovoMarcador {position:absolute;left:410px;top:45%;}

#divDadosTexto {height:10em;}
#lblTexto {position:absolute;left:0%;top:0%;width:95%;}
#txaTexto {position:absolute;left:0%;top:20%;width:95%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
  //<script type="javascript">

  var objAjaxMarcadores = null;

  function inicializar(){

    if ('<?=$bolNaoEncontrado?>' == '1'){
      window.opener.infraFecharJanelaModal();
      self.setTimeout('window.opener.location.reload()',500);
      self.setTimeout('window.close();',500);
    }

    if ('<?=$bolFlagAlteracaoOK?>'=='1'){
      window.opener.infraFecharJanelaModal();
      window.opener.location = '<?=$strLinkRetorno?>';
      self.setTimeout('window.close();',500);
    }


<?if($_GET['acao']=='andamento_marcador_cadastrar'){?>

    document.getElementById('selMarcador').focus();

    $('#selMarcador').ddslick({width: 400,
      onSelected: function(data){
        if(data.selectedIndex > 0) {
          document.getElementById('hdnIdMarcador').value = data.selectedData.value;
        }else{
          document.getElementById('hdnIdMarcador').value = '';
          document.getElementById('txaTexto').innerHTML = '';
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
            document.getElementById('txaTexto').innerHTML = '';
          }
        }
      });
    };

<?}else{?>
    document.getElementById('txaTexto').focus();
<?}?>

  }

  function validarCadastro() {
    return true;
  }

  function OnSubmitForm() {
    return validarCadastro();
  }

  function cadastrarMarcador(){
    parent.infraAbrirJanelaModal('<?=$strLinkNovoMarcador?>',700,450);
  }

  function recarregarMarcadores(idMarcador){
    document.getElementById('hdnIdMarcador').value = idMarcador;
    objAjaxMarcadores.executar();
  }

  //</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmAndamentoMarcadorCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>
    <div id="divDadosMarcador" class="infraAreaDados">

      <label id="lblMarcador" for="selMarcador" accesskey="" class="infraLabelOpcional">Marcador:</label>
      <select id="selMarcador" name="selMarcador" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
        <?=$strItensSelMarcador?>
      </select>
      <?=$strImgNovoMarcador?>
      <input type="hidden" id="hdnIdMarcador" name="hdnIdMarcador" value="<?=$objAndamentoMarcadorDTO->getNumIdMarcador()?>" />
    </div>

    <div id="divDadosTexto" class="infraAreaDados">
      <label id="lblTexto" for="txaTexto" class="infraLabelOpcional">Texto:</label>
      <textarea id="txaTexto" name="txaTexto" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'3':'4'?>" onkeypress="return infraLimitarTexto(this,event,250);" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objAndamentoMarcadorDTO->getStrTexto());?></textarea>
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