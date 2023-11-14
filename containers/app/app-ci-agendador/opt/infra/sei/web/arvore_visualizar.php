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

  global $SEI_MODULOS;

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->setBolEcho(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////


  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  //não deixa redimensionar pela infra porque dá problema com a carga do iframe
  PaginaSEI::getInstance()->setBolAutoRedimensionar(false);

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $arrComandos = array();

  $bolFlagProcessou = false;

  $strLinkIniciarEditor = '';

  $dblIdProcedimento = '';
  if (isset($_GET['id_procedimento'])){
    $dblIdProcedimento = $_GET['id_procedimento'];
  }

  $dblIdDocumento = '';
  if (isset($_GET['id_documento'])){
    $dblIdDocumento = $_GET['id_documento'];
  }

  $dblIdProcedimentoAnexado = '';
  if (isset($_GET['id_procedimento_anexado'])){
    $dblIdProcedimentoAnexado = $_GET['id_procedimento_anexado'];
  }

  $numIdBloco = '';
  if (isset($_GET['id_bloco'])){
    $numIdBloco = $_GET['id_bloco'];
  }

  $strLinkProcedimentoCiencias = '';
  $strLinkProcedimentoAnexadoCiencias = '';
  $strLinkDocumentoCiencias = '';

  switch($_GET['acao']){

    case 'arvore_visualizar':
    	//Título
      $strTitulo = 'Visualizar Árvore';

      //vindo do cadastro de documento e tudo OK então gera link para abrir editor
      if ($_GET['acao_origem']=='documento_gerar' && $_GET['atualizar_arvore']=='1'){
        $strLinkIniciarEditor = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=editor_montar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento);
      }

      break;

    case 'procedimento_excluir':
      try{
        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setDblIdProcedimento($dblIdProcedimento);
        $objProcedimentoRN = new ProcedimentoRN();
        $objProcedimentoRN->excluirRN0280($objProcedimentoDTO);
        ProtocoloINT::removerProtocoloVisitado($dblIdProcedimento);
        PaginaSEI::getInstance()->setStrMensagem('Exclusão realizada com sucesso.');
        $bolFlagProcessou = true;

      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      break;


    case 'procedimento_reabrir':
    	try{
        $objReabrirProcessoDTO = new ReabrirProcessoDTO();
        $objReabrirProcessoDTO->setDblIdProcedimento($dblIdProcedimento);
        $objReabrirProcessoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
        $objReabrirProcessoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

      	$objProcedimentoRN = new ProcedimentoRN();
      	$objProcedimentoRN->reabrirRN0966($objReabrirProcessoDTO);
      	PaginaSEI::getInstance()->setStrMensagem('Reabertura realizada com sucesso.');
      	$bolFlagProcessou = true;
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      break;

    case 'procedimento_remover_sobrestamento':
      try{
        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($dblIdProcedimento);

        $objProcedimentoRN = new ProcedimentoRN();
        $objProcedimentoRN->removerSobrestamentoRN1017(array($objRelProtocoloProtocoloDTO));

        PaginaSEI::getInstance()->setStrMensagem('Remoção de sobrestamento realizada com sucesso.');
        $bolFlagProcessou = true;
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      break;

    case 'procedimento_concluir':
    	try{

        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setDblIdProcedimento($dblIdProcedimento);

        $objProcedimentoRN = new ProcedimentoRN();
      	$objProcedimentoRN->concluir(array($objProcedimentoDTO));
      	PaginaSEI::getInstance()->setStrMensagem('Conclusão realizada com sucesso.');
      	$bolFlagProcessou = true;
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      break;

    case 'procedimento_ciencia':
    	try{

        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setDblIdProcedimento($dblIdProcedimento);

        $objProcedimentoRN = new ProcedimentoRN();
      	$objAtividadeDTO = $objProcedimentoRN->darCiencia($objProcedimentoDTO);
        $strLinkProcedimentoCiencias = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&procedimento_visualizar_ciencias=1&id_atividade='.$objAtividadeDTO->getNumIdAtividade().PaginaSEI::getInstance()->montarAncora($objAtividadeDTO->getNumIdAtividade()));
      	PaginaSEI::getInstance()->setStrMensagem('Ciência no processo realizada com sucesso.',PaginaSEI::$TIPO_MSG_INFORMACAO);
      	$bolFlagProcessou = true;
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      break;

    case 'procedimento_anexado_ciencia':
      try{

        $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo1($dblIdProcedimento);
        $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($dblIdProcedimentoAnexado);

        $objProcedimentoRN = new ProcedimentoRN();
        $objAtividadeDTO = $objProcedimentoRN->darCienciaAnexado($objRelProtocoloProtocoloDTO);
        $strLinkProcedimentoAnexadoCiencias = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado.'&procedimento_visualizar_ciencias=1&id_atividade='.$objAtividadeDTO->getNumIdAtividade().PaginaSEI::getInstance()->montarAncora($objAtividadeDTO->getNumIdAtividade()));
        PaginaSEI::getInstance()->setStrMensagem('Ciência no processo anexado realizada com sucesso.',PaginaSEI::$TIPO_MSG_INFORMACAO);
        $bolFlagProcessou = true;
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      break;

    case 'procedimento_credencial_renunciar':
    	try{

        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setDblIdProcedimento($dblIdProcedimento);

        $objAtividadeRN = new AtividadeRN();
        $objAtividadeRN->renunciarCredenciais($objProcedimentoDTO);

      	PaginaSEI::getInstance()->setStrMensagem('Renúncia realizada com sucesso.');
      	$bolFlagProcessou = true;
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      break;

    case 'documento_excluir':
      try{
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setDblIdDocumento($dblIdDocumento);
        $objDocumentoRN = new DocumentoRN();
        $objDocumentoRN->excluirRN0006($objDocumentoDTO);
        ProtocoloINT::removerProtocoloVisitado($dblIdDocumento);
        PaginaSEI::getInstance()->setStrMensagem('Exclusão realizada com sucesso.');
        $bolFlagProcessou = true;
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      break;

    case 'documento_ciencia':
      try{
        $objDocumentoDTO = new DocumentoDTO();
        $objDocumentoDTO->setDblIdDocumento($dblIdDocumento);
        $objDocumentoRN = new DocumentoRN();
        $objAtividadeDTO = $objDocumentoRN->darCiencia($objDocumentoDTO);
        $strLinkDocumentoCiencias = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&documento_visualizar_ciencias=1&id_atividade='.$objAtividadeDTO->getNumIdAtividade().PaginaSEI::getInstance()->montarAncora($objAtividadeDTO->getNumIdAtividade()));
        PaginaSEI::getInstance()->setStrMensagem('Ciência no documento realizada com sucesso.',PaginaSEI::$TIPO_MSG_INFORMACAO);
        $bolFlagProcessou = true;
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      break;


    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkControleProcessos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao']);
  $strLinkMontarArvoreProcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento);
  $strLinkMontarArvoreProcessoDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado);
  $strLinkMontarArvoreIsolada = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&montar_visualizacao=0');


  $bolAcaoExcluirProcesso = SessaoSEI::getInstance()->verificarPermissao('procedimento_excluir');
  $bolAcaoReabrirProcesso = SessaoSEI::getInstance()->verificarPermissao('procedimento_reabrir');
  $bolAcaoRemoverSobrestamentoProcesso = SessaoSEI::getInstance()->verificarPermissao('procedimento_remover_sobrestamento');
  $bolAcaoConcluirProcesso = SessaoSEI::getInstance()->verificarPermissao('procedimento_concluir');
  $bolAcaoExcluirDocumento = SessaoSEI::getInstance()->verificarPermissao('documento_excluir');
  $bolAcaoAssinarDocumento = SessaoSEI::getInstance()->verificarPermissao('documento_assinar');
  $bolAcaoProcedimentoEnviarEmail = SessaoSEI::getInstance()->verificarPermissao('procedimento_enviar_email');
  $bolAcaoDocumentoEnviarEmail = SessaoSEI::getInstance()->verificarPermissao('documento_enviar_email');
  $bolAcaoEncaminharEmail = SessaoSEI::getInstance()->verificarPermissao('email_encaminhar');
  $bolAcaoResponderFormulario = SessaoSEI::getInstance()->verificarPermissao('responder_formulario');
  $bolAcaoEditarConteudo = SessaoSEI::getInstance()->verificarPermissao('editor_montar');
  $bolAcaoRenunciarCredencial = SessaoSEI::getInstance()->verificarPermissao('procedimento_credencial_renunciar');
  $bolAcaoCienciaProcesso = SessaoSEI::getInstance()->verificarPermissao('procedimento_ciencia');
  $bolAcaoCienciaDocumento = SessaoSEI::getInstance()->verificarPermissao('documento_ciencia');
  $bolAcaoCienciaProcessoAnexado = SessaoSEI::getInstance()->verificarPermissao('procedimento_anexado_ciencia');
  $bolAcaoAlterarFormulario = SessaoSEI::getInstance()->verificarPermissao('formulario_alterar');
  $bolAcaoBlocoSelecionarProcesso = SessaoSEI::getInstance()->verificarPermissao('bloco_selecionar_processo');
  $bolAcaoRelBlocoProtocoloCadastrar = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_cadastrar');
  $bolAcaoRelBlocoProtocoloListar = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_listar');


  $strLinkExcluirProcesso = '';
  if ($bolAcaoExcluirProcesso) {
    $strLinkExcluirProcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_excluir&acao_origem='.$_GET['acao'].'&id_procedimento='.$dblIdProcedimento);
  }

  $strLinkReabrirProcesso = '';
  if ($bolAcaoReabrirProcesso) {
    $strLinkReabrirProcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_reabrir&acao_origem='.$_GET['acao'].'&id_procedimento='.$dblIdProcedimento.'&atualizar_arvore=1');
  }

  $strLinkRemoverSobrestamentoProcesso = '';
  if ($bolAcaoRemoverSobrestamentoProcesso) {
    $strLinkRemoverSobrestamentoProcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_remover_sobrestamento&acao_origem='.$_GET['acao'].'&id_procedimento='.$dblIdProcedimento.'&atualizar_arvore=1');
  }

  $strLinkConcluirProcesso = '';
  if ($bolAcaoConcluirProcesso) {
    $strLinkConcluirProcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_concluir&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&atualizar_arvore=1');
  }

  $strLinkExcluirDocumento = '';
  if ($bolAcaoExcluirDocumento) {
    $strLinkExcluirDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_excluir&acao_origem='.$_GET['acao'].'&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&atualizar_arvore=1');
  }

  $strLinkAssinarDocumento = '';
  if ($bolAcaoAssinarDocumento) {
    $strLinkAssinarDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_assinar&acao_origem='.$_GET['acao'].'&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1');
  }

  $strLinkProcedimentoEnviarEmail = '';
  if ($bolAcaoProcedimentoEnviarEmail) {
    $strLinkProcedimentoEnviarEmail = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_enviar_email&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&arvore=1');
  }

  $strLinkDocumentoEnviarEmail = '';
  if ($bolAcaoDocumentoEnviarEmail) {
    $strLinkDocumentoEnviarEmail = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_enviar_email&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1');
  }

  $strLinkEncaminharEmail = '';
  if ($bolAcaoEncaminharEmail) {
    $strLinkEncaminharEmail = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=email_encaminhar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1');
  }

  $strLinkResponderFormulario = '';
  if ($bolAcaoResponderFormulario) {
    $strLinkResponderFormulario = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=responder_formulario&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1');
  }

  $strLinkEditarConteudo = '';
  if ($bolAcaoEditarConteudo) {
    $strLinkEditarConteudo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=editor_montar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento);
  }

  $strLinkRenunciarCredencial = '';
  if ($bolAcaoRenunciarCredencial) {
    $strLinkRenunciarCredencial = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_credencial_renunciar&id_procedimento='.$dblIdProcedimento);
  }

  $strLinkCienciaProcesso = '';
  if ($bolAcaoCienciaProcesso) {
    $strLinkCienciaProcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_ciencia&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&atualizar_arvore=1');
  }

  $strLinkCienciaDocumento = '';
  if ($bolAcaoCienciaDocumento) {
    $strLinkCienciaDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_ciencia&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&atualizar_arvore=1');
  }

  $strLinkCienciaProcessoAnexado = '';
  if ($bolAcaoCienciaProcessoAnexado) {
    $strLinkCienciaProcessoAnexado = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_anexado_ciencia&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado);
  }

  $strLinkAlterarFormulario = '';
  if ($bolAcaoAlterarFormulario) {
    $strLinkAlterarFormulario = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=formulario_alterar&acao_origem=arvore_visualizar&acao_retorno=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&arvore=1');
  }

  $strLinkLupaBloco = '';
  if ($bolAcaoBlocoSelecionarProcesso) {
    $strLinkLupaBloco = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_selecionar_processo&tipo_selecao=1&id_object=objLupaBloco&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento);
  }

  $strLinkIncluirEmBloco = '';
  if ($bolAcaoRelBlocoProtocoloCadastrar) {
    $strLinkIncluirEmBloco = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_cadastrar&acao_origem='.$_GET['acao'].'&id_procedimento='.$dblIdProcedimento.'&arvore=1');
  }

  $strLinkProtocolosBloco = '';
  if ($bolAcaoRelBlocoProtocoloListar) {
    $strLinkProtocolosBloco = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_listar&acao_origem='.$_GET['acao'].'&id_bloco='.$numIdBloco);
  }

  $strLinkTarjasAssinatura = '';
  if (isset($_GET['buscar_tarjas']) && $_GET['buscar_tarjas']=='S'){
    $strLinkTarjasAssinatura = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=tarja_assinatura_montar&id_documento='.$dblIdDocumento);
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

html, body {
overflow:visible;
}

body{
text-align:left;
margin:0;
}

#divInfraBarraLocalizacao {display:none;}

#ifrArvoreHtml {
  background-color:white;
}

#divArvoreAguarde {margin:0;display:block;text-align:center;display:none;}
#imgArvoreAguarde {position:relative;top:50%;}

#divArvoreConteudo {
  display:none;
  overflow:hidden;
  background-color:white;
  margin-top:10px;
  padding:10px;
  border-radius: 5px;
  box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, .3), 0 0.0625rem 0.125rem rgba(0, 0, 0, .2);
<? if (PaginaSEI::getInstance()->isBolNavegadorSafariIpad()){?>
  overflow: scroll !important;
  -webkit-overflow-scrolling:touch;
<? }?>
}

#divArvoreHtml{
  display:contents;
}

#frmVisualizar {display:none;}

#ifrEditor {display:none;width:100%;}

#divInfraAreaGlobal {width:100% !important;}

#divInfraAreaTelaD{background-color:#e0e0e0;}

#divArvoreInformacao {
  overflow-y:auto;
}

#divArvoreInformacao, #divArvoreInformacao a {
  font-size:.875rem;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
if(0){?><script><?}
?>

var bolRedimensionando = false;
var objLupaBloco = null;
var objAjaxVerificacaoAssinatura = null;


function redimensionar(){

  if (!bolRedimensionando && parent.document.getElementById('ifrVisualizacao')!=null){

    bolRedimensionando = true;

    exibirLinkVoltarArvoreVisualizar();

    var hVisualizacao = parent.document.getElementById('ifrVisualizacao').offsetHeight;
    var hAcoesMovel = document.getElementById('divArvoreAcoesMovel').offsetHeight;
    var hAcoes = $("#collapseControle").hasClass('show') ? document.getElementById('divArvoreAcoes').offsetHeight : 0;

  	var hRedimensionamento = hVisualizacao - hAcoesMovel - hAcoes - 30;

  	if (hRedimensionamento > 0 && hRedimensionamento < 1920){ //FullHD
    	document.getElementById('divArvoreAguarde').style.height = hRedimensionamento + 'px';
      document.getElementById('divArvoreConteudo').style.height = hRedimensionamento + 'px';
      document.getElementById('ifrEditor').style.height = hRedimensionamento + 'px';
    }

    if (document.getElementById('ifrArvoreHtml')==null && document.getElementById('ifrTarjasAssinatura')==null){

      document.getElementById('divArvoreInformacao').style.height = (hRedimensionamento - 10) + 'px';

    }else {

      var hInformacao = 0;
      if (document.getElementById('divArvoreInformacao')!=null){
        hInformacao = document.getElementById('divArvoreInformacao').offsetHeight;
      }

      if (document.getElementById('ifrArvoreHtml')!=null) {
        document.getElementById('ifrArvoreHtml').style.height = (hRedimensionamento - hInformacao - 10) + 'px';
      }

      if (document.getElementById('ifrTarjasAssinatura')!=null) {
        document.getElementById('ifrTarjasAssinatura').style.height = (hRedimensionamento - hInformacao - 10) + 'px';
      }
    }

    bolRedimensionando = false;
  }
}

function exibirLinkVoltarArvoreVisualizar(){
  if(parent.infraIsBreakpointBootstrap("lg")){
    $("#ancVoltarArvore").css("display","none");
    $("#ancIcones").css("display","none");
    $("#ancAnteriorArvore").css("display","none");
    $("#ancProximoArvore").css("display","none");
    $("#collapseControle").collapse("show");
  }else {
    $("#ancVoltarArvore").css("display", "inline");
    $("#ancIcones").css("display", "inline");
    $("#ancAnteriorArvore").css("display","inline");
    $("#ancProximoArvore").css("display","inline");
    $("#collapseControle").collapse("hide");
  }
}

function inicializar(){

  exibirLinkVoltarArvoreVisualizar();

  <? if ($strLinkIniciarEditor!=''){?>
    infraAdicionarEvento(document.getElementById('ifrEditor'),'load', loadEditarConteudo);
    infraAbrirJanela('<?=$strLinkIniciarEditor?>','janelaEditor_<?=SessaoSEI::getInstance()->getNumIdUsuario().'_'.$dblIdDocumento?>',infraClientWidth(),infraClientHeight(),'location=0,status=0,resizable=1,scrollbars=1',false);
  <?}?>

  //exclusão/renúncia volta para o controle de processos
  <? if (($_GET['acao']=='procedimento_excluir' && $bolFlagProcessou == '1') ||
         ($_GET['acao']=='procedimento_credencial_renunciar' && $bolFlagProcessou == '1')){ ?>
    parent.parent.document.location.href = '<?=$strLinkControleProcessos?>';
    return;
  <?}else if ($_GET['acao_origem']=='rel_bloco_protocolo_cadastrar'){ ?>
    parent.parent.document.location.href = '<?=$strLinkProtocolosBloco?>#' + infraGetAnchor();
    return;
  <?}else if ($_GET['acao']=='procedimento_ciencia' && $bolFlagProcessou == '1'){ ?>
    atualizarArvore('<?=$strLinkProcedimentoCiencias?>');
    return;
  <?} if ($_GET['acao']=='procedimento_anexado_ciencia' && $bolFlagProcessou == '1'){ ?>
    atualizarArvore('<?=$strLinkProcedimentoAnexadoCiencias?>');
    return;
  <?}else if ($_GET['acao']=='documento_ciencia' && $bolFlagProcessou == '1'){?>
    atualizarArvore('<?=$strLinkDocumentoCiencias?>');
    return;
  <?}else if ($_GET['acao']=='documento_excluir' && $bolFlagProcessou == '1'){ ?>
    atualizarArvore('<?=$strLinkMontarArvoreProcesso?>');
    return;
  <?}else if (isset($_GET['atualizar_arvore']) && $_GET['atualizar_arvore']=='1'){?>
     atualizarArvore('<?=$strLinkMontarArvoreProcessoDocumento?>');
     return;
  <?}?>


  objAjaxVerificacaoAssinatura = new infraAjaxComplementar(null,'<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=documento_verificar_assinatura&id_documento='.$dblIdDocumento)?>');
  objAjaxVerificacaoAssinatura.async = false;
  objAjaxVerificacaoAssinatura.bolAssinado = false;
  objAjaxVerificacaoAssinatura.processarResultado = function(arr){
   if (arr!=null) {
     this.bolAssinado = false;
     if (arr['SinAssinado']!=undefined && arr['SinAssinado']=='S') {
       this.bolAssinado = true;
     }
   }
  };

  //monta visualização de acordo com o nó selecionado na árvore
  var objArvore = parent.document.getElementById('ifrArvore').contentWindow['objArvore'];

  if (objArvore != null){

    var noSelecionado = objArvore.getNoSelecionado();

    if (noSelecionado != null){

      if (noSelecionado.acoes != undefined){
        document.getElementById('divArvoreAcoes').innerHTML = noSelecionado.acoes;
      }

      if(noSelecionado.acoes == undefined ||  noSelecionado.acoes.trim() == ""){
        $("#divArvoreAcoesMovel").hide();
      }

      document.getElementById('divArvoreAguarde').style.display = 'block';

      if (noSelecionado.src!=undefined && noSelecionado.src!=''){

        //se for um link carrega no iframe
        if (noSelecionado.html != undefined && noSelecionado.html != ''){

           var innerHtml = '';

           if (noSelecionado.assinatura == undefined || noSelecionado.assinatura == ''){
             innerHtml += '<div id="divArvoreInformacao">' + noSelecionado.html + '</div>';
           }else{
             innerHtml += '<div id="divArvoreInformacao">' + noSelecionado.html + '<div id="divAssinaturas" style="float:right">' + noSelecionado.assinatura + '</div></div>';
           }

           innerHtml += '<iframe onload="ocultarAguarde();" id="ifrArvoreHtml" src="' + noSelecionado.src + '" frameborder="0" height="100%" width="100%"></iframe>';

          <?if($strLinkTarjasAssinatura!=''){?>
          innerHtml += '<iframe id="ifrTarjasAssinatura" src="<?=$strLinkTarjasAssinatura?>" frameborder="0" height="100%" width="100%" style="display:none"></iframe>';
          <?}?>

          document.getElementById('divArvoreHtml').innerHTML = innerHtml;
        }else{
          document.getElementById('divArvoreHtml').innerHTML = '<iframe id="ifrArvoreHtml" onload="ocultarAguarde();" src="' + noSelecionado.src + '" frameborder="0" height="100%" width="100%"></iframe>';
        }

        if (noSelecionado.src.indexOf('documento_download_anexo')!=-1){
          ocultarAguarde();
        }

      }else if (noSelecionado.html != undefined &&  noSelecionado.html != ''){

         var innerHtml = '';

         if (noSelecionado.assinatura == undefined || noSelecionado.assinatura == ''){
           innerHtml += '<div id="divArvoreInformacao">' + noSelecionado.html + '</div>';
         }else{
           innerHtml += '<div id="divArvoreInformacao">' + noSelecionado.html + '<div id="divAssinaturas" style="float:right">' + noSelecionado.assinatura + '</div></div>';
         }

        <?if($strLinkTarjasAssinatura!=''){?>
        innerHtml += '<iframe id="ifrTarjasAssinatura" src="<?=$strLinkTarjasAssinatura?>" frameborder="0" height="100%" width="100%" style="display:none"></iframe>';
        <?}?>

        //se for um conteudo HTML copia para a div
        document.getElementById('divArvoreHtml').innerHTML = innerHtml;

        ocultarAguarde();
      }


    }else{
      atualizarArvore('<?=$strLinkMontarArvoreProcesso?>');
      return;
    }

    redimensionar();
    infraAdicionarEvento(window,'resize',redimensionar);
  }

	objLupaBloco = new infraLupaText('txtBloco','hdnIdBloco','<?=$strLinkLupaBloco?>');
	objLupaBloco.finalizarSelecao = function(){
    document.getElementById('frmVisualizar').action = '<?=$strLinkIncluirEmBloco?>';
    document.getElementById('frmVisualizar').submit();
	}
}

<?if ($bolAcaoExcluirProcesso){?>
function excluirProcesso(){
  if (confirm('Confirma exclusão do processo?')){
   location.href = '<?=$strLinkExcluirProcesso?>';
  }
}
<?}?>

<?if ($bolAcaoRemoverSobrestamentoProcesso){?>
function removerSobrestamentoProcesso(){
  if (confirm('Confirma remoção de sobrestamento do processo?')){
   location.href = '<?=$strLinkRemoverSobrestamentoProcesso?>';
  }
}
<?}?>

<?if ($bolAcaoConcluirProcesso){?>
function concluirProcesso(){
  //if (confirm('Confirma conclusão do processo?')){
   location.href = '<?=$strLinkConcluirProcesso?>';
  //}
}
<?}?>

<?if ($bolAcaoReabrirProcesso){?>
function reabrirProcesso(){
  //if (confirm('Confirma reabetura do processo?')){
   location.href = '<?=$strLinkReabrirProcesso?>';
  //}
}
<?}?>

<?if ($bolAcaoExcluirDocumento){?>
function excluirDocumento(){
  if (confirm('Confirma exclusão do documento?')){
   location.href = '<?=$strLinkExcluirDocumento?>';
  }
}
<?}?>

<?if ($bolAcaoCienciaProcesso){?>
function cienciaProcesso(){
  //if (confirm('Confirma ciência no processo?')){
   location.href = '<?=$strLinkCienciaProcesso?>';
  //}
}
<?}?>

<?if ($bolAcaoCienciaDocumento){?>
function cienciaDocumento(){
  //if (confirm('Confirma ciência no documento?')){
   location.href = '<?=$strLinkCienciaDocumento?>';
  //}
}
<?}?>

<?if ($bolAcaoCienciaProcessoAnexado){?>
function cienciaProcessoAnexado(){
  //if (confirm('Confirma ciência no processo anexado?')){
  location.href = '<?=$strLinkCienciaProcessoAnexado?>';
  //}
}
<?}?>

<?if ($bolAcaoAssinarDocumento){?>
  function assinarDocumento(){
    parent.infraAbrirJanelaModal('<?=$strLinkAssinarDocumento?>',600,450);
  }
<?}?>

<?if ($bolAcaoProcedimentoEnviarEmail){?>
function enviarEmailProcedimento(){
  abrirJanela('janelaEmail_<?=SessaoSEI::getInstance()->getNumIdUsuario().'_'.$dblIdProcedimento?>','<?=$strLinkProcedimentoEnviarEmail?>');
}
<?}?>

<?if ($bolAcaoDocumentoEnviarEmail){?>
function enviarEmailDocumento(){
  abrirJanela('janelaEmailDocumento_<?=SessaoSEI::getInstance()->getNumIdUsuario().'_'.$dblIdDocumento?>','<?=$strLinkDocumentoEnviarEmail?>');
}
<?}?>

<?if ($bolAcaoEncaminharEmail){?>
function encaminharEmail(){
  abrirJanela('janelaEncaminharEmail_<?=SessaoSEI::getInstance()->getNumIdUsuario().'_'.$dblIdDocumento?>','<?=$strLinkEncaminharEmail?>');
}
<?}?>

<?if ($bolAcaoResponderFormulario){?>
function responderFormulario(){
  abrirJanela('janelaResponderFormulario_<?=SessaoSEI::getInstance()->getNumIdUsuario().'_'.$dblIdDocumento?>','<?=$strLinkResponderFormulario?>');
}
<?}?>

<?if ($bolAcaoRenunciarCredencial){?>
function renunciarCredencial(){
  if (confirm("ATENÇÃO: Confirma renúncia de credenciais do processo nesta unidade?")){
    location.href = '<?=$strLinkRenunciarCredencial?>';
  }
}
<?}?>

<?if ($bolAcaoEditarConteudo){?>
function editarConteudo(assinado){

  if (INFRA_FF > 0 && INFRA_FF < 4){
    alert('Para realizar a edição de documentos no Firefox é recomendado atualizar o navegador para a versão 4 ou posterior.\n\nPara iniciar a atualização automática acesse o menu "Ajuda / Verificar atualizações..." ou "Ajuda / Sobre o Firefox" do navegador.');
    //return;
  }

  if (assinado == 'S') {
    objAjaxVerificacaoAssinatura.bolAssinado = true;
  }else{
    objAjaxVerificacaoAssinatura.executar();
  }

  if (objAjaxVerificacaoAssinatura.bolAssinado){

    if (!confirm('Este documento já foi assinado. Se for editado perderá a assinatura e deverá ser assinado novamente.\n\n Deseja editar o documento?')){

      if (assinado == 'N') {
        atualizarArvore('<?=$strLinkMontarArvoreProcessoDocumento?>');
      }

      return;
    }
  }



  infraAdicionarEvento(document.getElementById('ifrEditor'), 'load', loadEditarConteudo);

  var janelaEditor = infraAbrirJanela('', 'janelaEditor_<?=SessaoSEI::getInstance()->getNumIdUsuario().'_'.$dblIdDocumento?>', infraClientWidth(), infraClientHeight(), 'location=0,status=0,resizable=1,scrollbars=1', false);
  if (janelaEditor.location=='about:blank') {
    janelaEditor.location.href = '<?=$strLinkEditarConteudo?>';
  }
  janelaEditor.focus();
}
<?}?>

<?if ($bolAcaoAlterarFormulario){?>
function alterarFormulario(assinado){

    if (assinado == 'S') {
      objAjaxVerificacaoAssinatura.bolAssinado = true;
    }else{
      objAjaxVerificacaoAssinatura.executar();
    }

    if (objAjaxVerificacaoAssinatura.bolAssinado){

      if (!confirm('Este formulário já foi assinado. Se for editado perderá a assinatura e deverá ser assinado novamente.\n\n Deseja editar o formulário?')){

        if (assinado == 'N') {
          atualizarArvore('<?=$strLinkMontarArvoreProcessoDocumento?>');
        }

        return;
      }
    }

    location.href = '<?=$strLinkAlterarFormulario?>';
}
<?}?>

function abrirJanela(nome, link){
    var janela = infraAbrirJanela('',nome,800,500,'location=0,status=1,resizable=1,scrollbars=1',false);
    if (janela.location == 'about:blank'){
      janela.location.href = link;
    }
    janela.focus();
}

function ocultarAguarde(){

  if (detectarExcecao('ifrArvoreHtml')){
    if (document.getElementById('divArvoreInformacao')!=null) {
      document.getElementById('divArvoreInformacao').style.display = 'none';
    }
  }

  if (document.getElementById('divArvoreAguarde')!=null) {
    document.getElementById('divArvoreAguarde').style.display = 'none';
  }

  if (document.getElementById('divArvoreConteudo')!=null) {
    document.getElementById('divArvoreConteudo').style.display = 'block';
  }

  //corrige problema do IE onde a barra de status de vez em quando fica como se estivesse carregando (mesmo após o término)
  if (INFRA_IE > 0){
    window.status='Finalizado.';
  }

  redimensionar();
}

function loadEditarConteudo(){

  document.getElementById('divArvoreAcoes').style.display = 'none';
  document.getElementById('divArvoreConteudo').style.display = 'none';

  if (detectarExcecao('ifrEditor')){
    document.getElementById('ifrEditor').style.display = 'block';
    parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvoreIsolada?>';
  }else{
    document.getElementById('ifrEditor').style.display = 'none';
    //monta árvore atualizando iframe para mostrar avisos
    parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvoreProcessoDocumento?>';
  }
}

function atualizarArvore(linkArvore){
  parent.parent.infraOcultarAviso();
  if (detectarExcecao('ifrEditor')){
    document.getElementById('divArvoreAcoes').style.display = 'none';
  }else{
    parent.document.getElementById('ifrArvore').src = linkArvore;
  }
}

function detectarExcecao(idIFrame){
  ret = false;

  try{
    var doc = null;
    if (window.frames[idIFrame]!=null){
      if (window.frames[idIFrame].document){
        doc = window.frames[idIFrame].document;
      }else if (window.frames[idIFrame].contentDocument){
        doc = window.frames[idIFrame].contentDocument;
      }
    }
    ret = (doc!=null && doc.getElementById('divInfraExcecao')!=null);
  }catch(exc){}

  return ret;
}

function restaurarImpressao(){
  	document.getElementById('divArvoreAcoes').style.display='';
}


function incluirEmBloco(tipo){
  document.getElementById('txtBloco').value = '';
  document.getElementById('hdnIdBloco').value = '';
  objLupaBloco.selecionar(950,500);
}

function visualizarHtmlAssinatura(link){
  document.getElementById('divArvoreHtml').innerHTML = '<iframe id="ifrArvoreHtml" src="' + link + '" frameborder="0" height="100%" width="100%"></iframe>';
}

function visualizarAssinaturas(){
  if (document.getElementById('ifrTarjasAssinatura').style.display == 'none'){
    document.getElementById('btnVisualizarAssinaturas').innerHTML = 'Ocultar Autenticações';
    document.getElementById('btnVisualizarAssinaturas').value = 'Ocultar Autenticações';

    if (document.getElementById('ifrArvoreHtml')!=null){
      document.getElementById('ifrArvoreHtml').style.display = 'none';
    }

    document.getElementById('ifrTarjasAssinatura').style.display = 'block';
  }else{
    document.getElementById('btnVisualizarAssinaturas').innerHTML = 'Visualizar Autenticações';
    document.getElementById('btnVisualizarAssinaturas').value = 'Visualizar Autenticações';
    document.getElementById('ifrTarjasAssinatura').style.display = 'none';

    if (document.getElementById('ifrArvoreHtml')!=null){
      document.getElementById('ifrArvoreHtml').style.display = 'block';
    }
  }
}

<?
if(0){?></script><?}
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody(null,'onload="inicializar();"');
?>

  <div id="divArvoreAcoesMovel" class="barraBotoesSEIMovel">
    <a id="ancVoltarArvore" href="javascript:seiVoltarArvoreProcesso()" class="btn" style="display:none;" title="Voltar para a Árvore do Processo">
      <img src="<?=PaginaSEI::getInstance()->getIconeVoltar()?>" width="32" height="32"/>
    </a>

    <a id="ancIcones" class="btn" data-toggle="collapse" style="display: none;" href="#collapseControle" role="button" aria-expanded="true" aria-controls="collapseControle" title="Exibir/Ocultar Ícones">
      <img src="<?=PaginaSEI::getInstance()->getIconeMenuPontos()?>" width="32" height="32"/>
    </a>

    <a id="ancAnteriorArvore" href="javascript:parent.document.getElementById('ifrArvore').contentWindow.navegarArvore('A')" class="btn" style="display:none;" title="Visualizar Anterior">
      <img src="<?=PaginaSEI::getInstance()->getIconeAnterior()?>" width="32" height="32"/>
    </a>

    <a id="ancProximoArvore" href="javascript:parent.document.getElementById('ifrArvore').contentWindow.navegarArvore('P')" class="btn" style="display:none;" title="Visualizar Próximo">
      <img src="<?=PaginaSEI::getInstance()->getIconeProximo()?>" width="32" height="32"/>
    </a>

  </div>

  <div class="collapse show" id="collapseControle">
    <div id="divArvoreAcoes" class="barraBotoesSEI"></div>
  </div>

  <div id="divArvoreAguarde"><img id="imgArvoreAguarde" src="<?=PaginaSEI::getInstance()->getIconeAguardar()?>" width="48" height="48" /></div>
  <div id="divArvoreConteudo"><div id="divArvoreHtml"></div></div>

  <!-- Edição de Conteúdo -->
  <iframe id="ifrEditor" frameborder="0"> </iframe>

  <!-- Inclusão em Bloco -->
  <form id="frmVisualizar" method="post" action="">
    <input type="text" id="txtBloco" name="txtBloco" value="" />
    <input type="hidden" id="hdnIdBloco" name="hdnIdBloco" value="" />
  </form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>