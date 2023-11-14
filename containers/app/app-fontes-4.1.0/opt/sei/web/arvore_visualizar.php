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

    case 'procedimento_ciencia':
    	try{

        $objProcedimentoDTO = new ProcedimentoDTO();
        $objProcedimentoDTO->setDblIdProcedimento($dblIdProcedimento);

        $objProcedimentoRN = new ProcedimentoRN();
      	$objAtividadeDTO = $objProcedimentoRN->darCiencia($objProcedimentoDTO);
        $strLinkProcedimentoCiencias = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&atualizar_arvore=1&id_procedimento='.$dblIdProcedimento.'&procedimento_visualizar_ciencias=1&id_atividade='.$objAtividadeDTO->getNumIdAtividade().PaginaSEI::getInstance()->montarAncora($objAtividadeDTO->getNumIdAtividade()));
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
        $strLinkProcedimentoAnexadoCiencias = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&atualizar_arvore=1&id_procedimento='.$dblIdProcedimento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado.'&procedimento_visualizar_ciencias=1&id_atividade='.$objAtividadeDTO->getNumIdAtividade().PaginaSEI::getInstance()->montarAncora($objAtividadeDTO->getNumIdAtividade()));

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
        $strLinkDocumentoCiencias = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&atualizar_arvore=1&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&documento_visualizar_ciencias=1&id_atividade='.$objAtividadeDTO->getNumIdAtividade().PaginaSEI::getInstance()->montarAncora($objAtividadeDTO->getNumIdAtividade()));
        PaginaSEI::getInstance()->setStrMensagem('Ciência no documento realizada com sucesso.',PaginaSEI::$TIPO_MSG_INFORMACAO);
        $bolFlagProcessou = true;
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      break;

    case 'arvore_navegar':
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkMontarArvoreProcessarHtml = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=arvore_processar_html');

  $strLinkControleProcessos = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_controlar&acao_origem='.$_GET['acao']);
  $strLinkMontarArvoreProcesso = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento);
  $strLinkMontarArvoreProcessoDocumento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado);
  $strLinkMontarArvoreIsolada = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_documento='.$dblIdDocumento.'&montar_visualizacao=0');


  $bolAcaoExcluirProcesso = SessaoSEI::getInstance()->verificarPermissao('procedimento_excluir');
  $bolAcaoReabrirProcesso = SessaoSEI::getInstance()->verificarPermissao('procedimento_reabrir');
  $bolAcaoRemoverSobrestamentoProcesso = SessaoSEI::getInstance()->verificarPermissao('procedimento_remover_sobrestamento');
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
    $strLinkCienciaProcessoAnexado = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_anexado_ciencia&atualizar_arvore=1&acao_origem=arvore_visualizar&id_procedimento='.$dblIdProcedimento.'&id_procedimento_anexado='.$dblIdProcedimentoAnexado);
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


#divArvoreConteudoIfr {
  overflow:hidden;
<? if (!isset($_GET['arvore']) && !isset($_GET['atualizar_arvore'])){ ?>
  background-color:white;
  margin-top:10px;
  padding:10px 10px 0px 10px;
  border-radius: 5px;
  box-shadow: 0 0.125rem 0.5rem rgba(0, 0, 0, .3), 0 0.0625rem 0.125rem rgba(0, 0, 0, .2);
<?}?>
}


#ifrVisualizacao{
  background-color: white;
}

#frmVisualizar {display:none;}

#divInfraAreaGlobal {width:100% !important;}

#divInfraAreaTelaD{
<? if (!isset($_GET['arvore']) && !isset($_GET['atualizar_arvore'])){ ?>
  background-color:#e0e0e0;
<?}?>

  display: flex!important;
  flex-direction: column!important;
  flex-grow: 1!important;
  padding-bottom:10px;
}

#divArvoreInformacao {
  overflow-y:auto;
}

#divArvoreInformacao, #divArvoreInformacao a {
  font-size:.875rem;
}

#divArvoreAcoesMovel {
<? if (isset($_GET['arvore']) || isset($_GET['atualizar_arvore'])){ ?>
  display:none;
<?}?>
}
#divArvoreAcoesMovel a {display:none;height: 32px;}
#divArvoreNavegacao {display:contents;margin-top:10px;}
#divArvoreNavegacao span {font-size:.825rem;font-weight:bold;line-height:30px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:inline-block;margin-top:10px;}
#divArvoreNavegacao button{margin-left:10px;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->adicionarJavaScript('js/arvore_visualizar.js');
PaginaSEI::getInstance()->abrirJavaScript();
if(0){?><script><?}
?>

var objLupaBloco = null;
var objAjaxVerificacaoAssinatura = null;
var noSelecionado = null;
var nomeJanelaProcesso = '<?=SessaoSEI::getInstance()->getNumIdUsuario().'_'.$dblIdProcedimento?>';
var nomeJanelaDocumento = '<?=SessaoSEI::getInstance()->getNumIdUsuario().'_'.$dblIdDocumento?>';
var linkMontarArvoreProcesso = '<?=$strLinkMontarArvoreProcesso?>';
var linkMontarArvoreProcessoDocumento = '<?=$strLinkMontarArvoreProcessoDocumento?>';
var linkMontarArvoreProcessarHtml = '<?=$strLinkMontarArvoreProcessarHtml?>';

<?
if ($_GET['acao_origem']=='rel_bloco_protocolo_cadastrar'){?>
var linkProtocolosBloco = '<?=$strLinkProtocolosBloco?>';
<?}
if ($bolAcaoExcluirProcesso){?>
var linkExcluirProcesso = '<?=$strLinkExcluirProcesso?>';
<?}
if ($bolAcaoRemoverSobrestamentoProcesso){?>
var linkRemoverSobrestamentoProcesso = '<?=$strLinkRemoverSobrestamentoProcesso?>';
<?}
if ($bolAcaoReabrirProcesso){?>
var linkReabrirProcesso = '<?=$strLinkReabrirProcesso?>';
<?}
if ($bolAcaoExcluirDocumento){?>
var linkExcluirDocumento = '<?=$strLinkExcluirDocumento?>';
<?}
if ($bolAcaoCienciaProcesso){?>
var linkCienciaProcesso = '<?=$strLinkCienciaProcesso?>';
<?}
if ($bolAcaoCienciaDocumento){?>
var linkCienciaDocumento = '<?=$strLinkCienciaDocumento?>';
<?}
if ($bolAcaoCienciaProcessoAnexado){?>
var linkCienciaProcessoAnexado = '<?=$strLinkCienciaProcessoAnexado?>';
<?}
if ($bolAcaoAssinarDocumento){?>
var linkAssinarDocumento = '<?=$strLinkAssinarDocumento?>';
<?}
if ($bolAcaoProcedimentoEnviarEmail){?>
var linkProcedimentoEnviarEmail = '<?=$strLinkProcedimentoEnviarEmail?>';
<?}
if ($bolAcaoDocumentoEnviarEmail){?>
var linkDocumentoEnviarEmail = '<?=$strLinkDocumentoEnviarEmail?>';
<?}
if ($bolAcaoEncaminharEmail){?>
var linkEncaminharEmail = '<?=$strLinkEncaminharEmail?>';
<?}
if ($bolAcaoResponderFormulario){?>
var linkResponderFormulario = '<?=$strLinkResponderFormulario?>';
<?}
if ($bolAcaoRenunciarCredencial){?>
var linkRenunciarCredencial = '<?=$strLinkRenunciarCredencial?>';
<?}
if ($bolAcaoEditarConteudo){?>
var linkEditarConteudo = '<?=$strLinkEditarConteudo?>';
<?}
if ($bolAcaoAlterarFormulario){?>
var linkAlterarFormulario = '<?=$strLinkAlterarFormulario?>';
<?}?>


var HTML = "";
function inicializar(){
  HTML = "";

  exibirVoltarAcoes(true);

  <?if ($strLinkIniciarEditor!=''){?>
   iniciarEditor('<?=$strLinkIniciarEditor?>');
  <?}?>

  //exclusão/renúncia volta para o controle de processos
  <? if (($_GET['acao']=='procedimento_excluir' && $bolFlagProcessou == '1') ||
         ($_GET['acao']=='procedimento_credencial_renunciar' && $bolFlagProcessou == '1')){ ?>
    parent.parent.document.location.href = '<?=$strLinkControleProcessos?>';
    return;
  <?}else if ($_GET['acao_origem']=='rel_bloco_protocolo_cadastrar'){ ?>
    self.setTimeout('redirecionarBlocos()',500);
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
    atualizarArvore(linkMontarArvoreProcesso);
    return;
  <?}else if (isset($_GET['atualizar_arvore']) && $_GET['atualizar_arvore']=='1'){?>
     atualizarArvore(linkMontarArvoreProcessoDocumento);
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
  <? if (!(isset($_GET['arvore']) && $_GET['arvore']=='1')){ ?>

  //monta visualização de acordo com o nó selecionado na árvore
  var objArvore = parent.parent.document.getElementById('ifrArvore').contentWindow['objArvore'];

  if (objArvore != null){

    noSelecionado = objArvore.getNoSelecionado();

    if (noSelecionado != null){
      if (noSelecionado.bolAgrupador && !parent.infraIsBreakpointBootstrap("lg")){
        if (noSelecionado.bolAberto){
          $("#ancFecharArvore").css("display", "inline");
        }else{
          $("#ancAbrirArvore").css("display", "inline");
        }

        document.getElementById('divArvoreNavegacao').innerHTML = '<span>' + noSelecionado.title + '</span>';
        document.getElementById('divArvoreConteudoIfr').style = 'display: none !important;';

      }else {
        document.getElementById('divArvoreNavegacao').innerHTML = '<span>' + noSelecionado.label + '</span>';

        if (noSelecionado.acoes != undefined) {
          document.getElementById('divArvoreAcoes').innerHTML = noSelecionado.acoes;
          $("#hdnHashAcoes").val(infraMd5(noSelecionado.acoes));
          $(document).ready(function () {

            alterarTargetAcoes();

          });
        }
      }

      if (noSelecionado.src!=undefined && noSelecionado.src!=''){

        //se for um link carrega no iframe
        if (noSelecionado.html != undefined && noSelecionado.html != ''){

          var innerHtml = '<div class="h-100 w-100 d-flex flex-column">';

          if (noSelecionado.assinatura == undefined || noSelecionado.assinatura == ''){
             innerHtml += '<div id="divArvoreInformacao"  >' + noSelecionado.html + '</div>';
           }else{
             innerHtml += '<div id="divArvoreInformacao"   >' + noSelecionado.html + '<div id="divAssinaturas" style="float:right">' + noSelecionado.assinatura + '</div></div>';
           }

           innerHtml += '<iframe onload="parent.ocultarAguarde();parent.detectarExcecao();" id="ifrArvoreHtml" src="' + noSelecionado.src + '" class="w-100 flex-grow-1" style="display: flex;" frameborder="0"  ></iframe>';

          <?if($strLinkTarjasAssinatura!=''){?>
          innerHtml += '<iframe id="ifrTarjasAssinatura" src="<?=$strLinkTarjasAssinatura?>" frameborder="0" class="w-100 flex-grow-1" style="display:none;"></iframe>';
          <?}?>
          innerHtml += "</div>";

          HTML = innerHtml;
          document.getElementById('ifrVisualizacao').src = linkMontarArvoreProcessarHtml;
          exibirAguarde("ifrVisualizacao");

        }else{
          $("#ifrVisualizacao").addClass("ifrVisualizacaoContraste")
          document.getElementById('ifrVisualizacao').src =  noSelecionado.src
          exibirAguarde("ifrVisualizacao");
        }

        if (noSelecionado.src.indexOf('documento_download_anexo')!=-1){
          ocultarAguarde();
        }

      }else if (noSelecionado.html != undefined &&  noSelecionado.html != ''){
         var innerHtml = '<div class="h-100 w-100 d-flex flex-column">';

           if (noSelecionado.assinatura == undefined || noSelecionado.assinatura == ''){
             innerHtml += '<div id="divArvoreInformacao"  >' + noSelecionado.html + '</div>';
           }else{
             innerHtml += '<div id="divArvoreInformacao"  >' + noSelecionado.html + '<div id="divAssinaturas" style="float:right">' + noSelecionado.assinatura + '</div></div>';
           }

        <?if($strLinkTarjasAssinatura!=''){?>
        innerHtml += '<iframe id="ifrTarjasAssinatura" onload="parent.ocultarAguarde();" src="<?=$strLinkTarjasAssinatura?>" frameborder="0" class="w-100 flex-grow-1" style="display:none"></iframe>';
        <?}?>
        innerHtml += "</div>";

        HTML = innerHtml;

        document.getElementById('ifrVisualizacao').src = linkMontarArvoreProcessarHtml;

        exibirAguarde("ifrVisualizacao");

      }
    }else{
      atualizarArvore(linkMontarArvoreProcesso);
      return;
    }
  }

  <?} ?>

  objLupaBloco = new infraLupaText('txtBloco','hdnIdBloco','<?=$strLinkLupaBloco?>');
	objLupaBloco.finalizarSelecao = function(){
    document.getElementById('frmVisualizar').action = '<?=$strLinkIncluirEmBloco?>';
    document.getElementById('frmVisualizar').submit();
	}

  exibirVoltarAcoes(false);
  infraAdicionarEvento(window,'resize',redimensionar);
}

<?
if(0){?></script><?}
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody(null,'onload="inicializar();"');
?>
<? if (!isset($_GET['arvore']) && !isset($_GET['atualizar_arvore'])){ ?>

  <div id="divArvoreAcoesMovel" class="barraBotoesSEIMovel">
    <a id="ancVoltarArvore" href="javascript:seiVoltarArvoreProcesso()" class="btn" style="display:none;" title="Voltar para a Árvore do Processo" tabindex="<?=PaginaSEI::getInstance()->getProxTabBarraComandosSuperior()?>">
      <img src="<?=PaginaSEI::getInstance()->getIconeVoltar()?>" width="32" height="32"/>
    </a>

    <a id="ancIcones" class="btn" data-toggle="collapse" style="display: none;" href="#collapseControle" role="button" aria-expanded="false" aria-controls="collapseControle" title="Exibir/Ocultar Ícones" tabindex="<?=PaginaSEI::getInstance()->getProxTabBarraComandosSuperior()?>">
      <img src="<?=PaginaSEI::getInstance()->getIconeMenuPontos()?>" width="32" height="32"/>
    </a>

    <a id="ancAnteriorArvore" href="javascript:parent.parent.document.getElementById('ifrArvore').contentWindow.navegarArvore('A')" class="btn" style="display:none;" title="Visualizar Anterior" tabindex="<?=PaginaSEI::getInstance()->getProxTabBarraComandosSuperior()?>">
      <img src="<?=PaginaSEI::getInstance()->getIconeAnterior()?>" width="32" height="32"/>
    </a>

    <a id="ancProximoArvore" href="javascript:parent.parent.document.getElementById('ifrArvore').contentWindow.navegarArvore('P')" class="btn" style="display:none;" title="Visualizar Próximo" tabindex="<?=PaginaSEI::getInstance()->getProxTabBarraComandosSuperior()?>">
      <img src="<?=PaginaSEI::getInstance()->getIconeProximo()?>" width="32" height="32"/>
    </a>

    <a id="ancAbrirArvore" href="javascript:parent.parent.document.getElementById('ifrArvore').contentWindow.navegarAgrupador(noSelecionado.id)" class="btn" title="Abrir Item" tabindex="<?=PaginaSEI::getInstance()->getProxTabBarraComandosSuperior()?>">
      <img src="<?=Icone::ARVORE_ABRIR?>" width="32" height="32"/>
    </a>

    <a id="ancFecharArvore" href="javascript:parent.parent.document.getElementById('ifrArvore').contentWindow.navegarAgrupador(noSelecionado.id)" class="btn" title="Fechar Item" tabindex="<?=PaginaSEI::getInstance()->getProxTabBarraComandosSuperior()?>">
      <img src="<?=Icone::ARVORE_FECHAR?>" width="32" height="32"/>
    </a>

    <div id="divArvoreNavegacao" style="display:none;height: 32px;"></div>

  </div>

  <div class="collapse" id="collapseControle" >
    <div id="divArvoreAcoes" class="barraBotoesSEI"></div>
  </div>
<? } ?>

  <div id="divArvoreConteudoIfr" tabindex="1000" class="d-flex flex-grow-1 ">  <iframe id="ifrVisualizacao" onload="testarMudancaHrefIframeInterno();ocultarAguarde();testarDocumento('<?=(isset($_GET['arvore']) && $_GET['arvore']=='1')?>');"  name="ifrVisualizacao"  class="ifrVisualizacao h-100 w-100"  src="about:blank" frameborder="0"></iframe></div>
  <div id="divArvoreAguarde" style="position: absolute;height: 100%;width: 95%;" ><img id="imgArvoreAguarde" class="mx-auto" src="<?=PaginaSEI::getInstance()->getIconeAguardar()?>" width="48" height="48" /></div>
  <!-- Inclusão em Bloco -->
  <form id="frmVisualizar" method="post" action="">
    <input type="text" id="txtBloco" name="txtBloco" value="" />
    <input type="hidden" id="hdnIdBloco" name="hdnIdBloco" value="" />
    <input type="hidden" id="hdnHashAcoes" name="hdnHashAcoes" value="" />
  </form>
<?
PaginaSEI::getInstance()->montarAreaDebug();
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>