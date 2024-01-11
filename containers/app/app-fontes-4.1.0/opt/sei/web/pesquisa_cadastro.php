<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/03/2020 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
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

  PaginaSEI::getInstance()->verificarSelecao('pesquisa_selecionar');

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);


  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selUsuario','selUnidade'));

  $objPesquisaDTO = new PesquisaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  $bolSalvouPesquisa = false;
  switch($_GET['acao']){
    case 'pesquisa_cadastrar':
      $strTitulo = 'Salvar Pesquisa';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarPesquisa" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';


      $objPesquisaDTO->setNumIdPesquisa(null);
      $objPesquisaDTO->setStrNome($_POST['txtNome']);
      $objPesquisaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objPesquisaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      if (isset($_POST['sbmCadastrarPesquisa'])) {
        try{
          $arrObjCampoPesquisaDTO = array();

          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_PESQUISAR_EM, "rdoPesquisarEm",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_DATA_FIM, "txtDataFim",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_DATA_INICIO, "txtDataInicio",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_SIN_DATA, "selData",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_NOME_ARVORE_DOCUMENTO_PESQUISA, "txtNomeArvoreDocumentoPesquisa",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_NUMERO_DOCUMENTO_PESQUISA, "txtNumeroDocumentoPesquisa",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_ID_SERIE_PESQUISA, "selSeriePesquisa",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_ID_TIPO_PROCEDIMENTO_PESQUISA, "selTipoProcedimentoPesquisa",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_PROTOCOLO_PESQUISA, "txtProtocoloPesquisa",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_ID_UNIDADE, "hdnIdUnidade",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_ID_ASSUNTO, "hdnIdAssunto",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_OBSERVACAO_PESQUISA, "txtObservacaoPesquisa",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_DESCRICAO_PESQUISA, "txtDescricaoPesquisa",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_ID_ASSINANTE, "hdnIdAssinante",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_SIN_DESTINATARIO, "chkSinDestinatario",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_SIN_REMETENTE, "chkSinRemetente",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_SIN_INTERESSADO, "chkSinInteressado",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_ID_CONTATO, "hdnIdContato",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_TEXTO_PESQUISA, "q",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_SIN_TRAMITACAO, "chkSinTramitacao",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_DOCUMENTOS_RECEBIDOS, "chkSinDocumentosRecebidos",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_DOCUMENTOS_GERADOS, "chkSinDocumentosGerados",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_ID_USUARIO_GERADOR1, "hdnIdUsuarioGerador1",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_ID_USUARIO_GERADOR2, "hdnIdUsuarioGerador2",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_ID_USUARIO_GERADOR3, "hdnIdUsuarioGerador3",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_TXT_USUARIO_GERADOR1, "txtUsuarioGerador1",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_TXT_USUARIO_GERADOR2, "txtUsuarioGerador2",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_TXT_USUARIO_GERADOR3, "txtUsuarioGerador3",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_TXT_UNIDADE, "txtUnidade",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_TXT_ASSUNTO, "txtAssunto",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_TXT_ASSINANTE, "txtAssinante",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_TXT_CONTATO, "txtContato",$arrObjCampoPesquisaDTO);
          CampoPesquisaINT::montarArrayPesquisa(CampoPesquisaRN::$CP_SIN_RESTRINGIR_ORGAO, "chkSinRestringirOrgao",$arrObjCampoPesquisaDTO);

          $selOrgao = PaginaSEI::getInstance()->recuperarCampo('selOrgaoPesquisa');
          $strSinRestringirOrgao = PaginaSEI::getInstance()->recuperarCampo("chkSinRestringirOrgao");

          if(!InfraString::isBolVazia($selOrgao) && $strSinRestringirOrgao!='S'){
            $objOrgaoDTO = new OrgaoDTO();
            $objOrgaoRN = new OrgaoRN();
            $numOrgaos = $objOrgaoRN->contarRN1354($objOrgaoDTO);
            $arrNumIdOrgao = explode(',', $selOrgao);
            if($numOrgaos != InfraArray::contar($arrNumIdOrgao)){
              foreach ($arrNumIdOrgao as $numIdOrgao){
                $objCampoPesquisaDTO = new CampoPesquisaDTO();
                $objCampoPesquisaDTO->setNumChave(CampoPesquisaRN::$CP_ID_ORGAO);
                $objCampoPesquisaDTO->setStrValor($numIdOrgao);
                $arrObjCampoPesquisaDTO[] = $objCampoPesquisaDTO;
              }
            }
          }

          $objPesquisaDTO->setArrObjCampoPesquisaDTO($arrObjCampoPesquisaDTO);

          $objPesquisaRN = new PesquisaRN();
          $objPesquisaDTO = $objPesquisaRN->cadastrar($objPesquisaDTO);
          //PaginaSEI::getInstance()->adicionarMensagem('Pesquisa "'.$objPesquisaDTO->getStrNome().'" cadastrada com sucesso.');
          //header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_pesquisa='.$objPesquisaDTO->getNumIdPesquisa().PaginaSEI::getInstance()->montarAncora($objPesquisaDTO->getNumIdPesquisa())));
          //die;
          $bolSalvouPesquisa = true;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'pesquisa_alterar':
      $strTitulo = 'Alterar Pesquisa';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarPesquisa" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_pesquisa'])){
        $objPesquisaDTO->setNumIdPesquisa($_GET['id_pesquisa']);
        $objPesquisaDTO->retTodos();
        $objPesquisaRN = new PesquisaRN();
        $objPesquisaDTO = $objPesquisaRN->consultar($objPesquisaDTO);
        if ($objPesquisaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objPesquisaDTO->setNumIdPesquisa($_POST['hdnIdPesquisa']);
        $objPesquisaDTO->setStrNome($_POST['txtNome']);
        $objPesquisaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
        $objPesquisaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objPesquisaDTO->getNumIdPesquisa())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarPesquisa'])) {
        try{
          $objPesquisaRN = new PesquisaRN();
          $objPesquisaRN->alterar($objPesquisaDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Pesquisa "'.$objPesquisaDTO->getStrNome().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objPesquisaDTO->getNumIdPesquisa())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'pesquisa_consultar':
      $strTitulo = 'Consultar Pesquisa';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_pesquisa'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objPesquisaDTO->setNumIdPesquisa($_GET['id_pesquisa']);
      $objPesquisaDTO->setBolExclusaoLogica(false);
      $objPesquisaDTO->retTodos();
      $objPesquisaRN = new PesquisaRN();
      $objPesquisaDTO = $objPesquisaRN->consultar($objPesquisaDTO);
      if ($objPesquisaDTO===null){
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
#lblNome {position:absolute;left:0%;top:0%;width:90%;}
#txtNome {position:absolute;left:0%;top:40%;width:90%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

  <? if($bolSalvouPesquisa){?>
    infraFecharJanelaModal();
  <?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='pesquisa_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='pesquisa_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas(true);
}

function validarCadastro() {
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

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
if(!$bolSalvouPesquisa) {
  PaginaSEI::getInstance()->abrirBody($strTitulo, 'onload="inicializar();"');
  ?>

  <form id="frmPesquisaCadastro" method="post" onsubmit="return OnSubmitForm();"
        action="<?= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . $_GET['acao'] . '&acao_origem=' . $_GET['acao']) ?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('4.5em');
    ?>
    <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText"
           value="<?= PaginaSEI::tratarHTML($objPesquisaDTO->getStrNome()); ?>"
           onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50"
           tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>
    <input type="hidden" id="hdnIdPesquisa" name="hdnIdPesquisa" value="<?= $objPesquisaDTO->getNumIdPesquisa(); ?>"/>
    <?
    //PaginaSEI::getInstance()->montarAreaDebug();
    // PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>

  <?
  PaginaSEI::getInstance()->fecharBody();
}
PaginaSEI::getInstance()->fecharHtml();
