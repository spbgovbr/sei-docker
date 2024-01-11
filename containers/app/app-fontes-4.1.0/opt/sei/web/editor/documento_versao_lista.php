<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
* 
* 09/12/2011 - criado por bcu
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id: documento_versao_lista.php 10035 2015-06-09 15:10:40Z mga $
*/

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('versao_secao_documento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  if (isset($_GET['id_procedimento'])){
      $strParametros .= "&id_procedimento=".$_GET['id_procedimento'];
  }

  if (isset($_GET['id_documento'])){
      $strParametros .= "&id_documento=".$_GET['id_documento'];
  }
  
  
  //PaginaSEI::getInstance()->salvarCamposPost(array('selDocumento'));
  $bolRecuperacaoOK = false;
  switch($_GET['acao']){
 
    case 'documento_versao_recuperar':
      try{
        $objEditorDTO=new EditorDTO();
        $objEditorDTO->setDblIdDocumento($_GET['id_documento']);
        $objEditorDTO->setNumVersao($_GET['versao']);
        $objEditorRN=new EditorRN();
        $objEditorRN->recuperarVersao($objEditorDTO);
        $bolRecuperacaoOK = true;
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      //header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&id_documento='.$_GET['id_documento'].$strParametros));
      //die;
      break;

    case 'documento_versao_listar':
      
      break;
    
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  
  $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
  $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_DOCUMENTOS_GERADOS);
  $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
  $objPesquisaProtocoloDTO->setDblIdProtocolo($_GET['id_documento']);
  
  $objProtocoloRN = new ProtocoloRN();
  $arrObjProtocoloDTO = $objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO);
  
  if (count($arrObjProtocoloDTO)===0){
    throw new InfraException('Documento não encontrado.');
  }
  
  $strTitulo = 'Versões do Documento '.$arrObjProtocoloDTO[0]->getStrProtocoloFormatado();


  $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
  $objVersaoSecaoDocumentoDTO->setDistinct(true);
  $objVersaoSecaoDocumentoDTO->retNumVersao();
  $objVersaoSecaoDocumentoDTO->retDthAtualizacao();
  //$objVersaoSecaoDocumentoDTO->retNumIdSecaoDocumento();
  $objVersaoSecaoDocumentoDTO->retDblIdDocumentoSecaoDocumento();
  $objVersaoSecaoDocumentoDTO->retStrNomeUsuario();
  $objVersaoSecaoDocumentoDTO->retNumIdUnidade();
  $objVersaoSecaoDocumentoDTO->retNumIdUsuario();
  $objVersaoSecaoDocumentoDTO->retStrSiglaUsuario();
  $objVersaoSecaoDocumentoDTO->retStrSiglaUnidade();
  $objVersaoSecaoDocumentoDTO->retStrDescricaoUnidade();
  $objVersaoSecaoDocumentoDTO->setNumVersao(1,InfraDTO::$OPER_MAIOR_IGUAL);
  $objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_DESC);  
  $dblIdDocumento = $_GET['id_documento'];
  if ($dblIdDocumento!==''){
    $objVersaoSecaoDocumentoDTO->setDblIdDocumentoSecaoDocumento($dblIdDocumento);
  }

  //PaginaSEI::getInstance()->prepararOrdenacao($objVersaoSecaoDocumentoDTO, 'IdSecaoDocumento', InfraDTO::$TIPO_ORDENACAO_ASC);
  //PaginaSEI::getInstance()->prepararPaginacao($objVersaoSecaoDocumentoDTO);

  $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
  $arrObjVersaoSecaoDocumentoDTO = $objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objVersaoSecaoDocumentoDTO);
  $numRegistros = count($arrObjVersaoSecaoDocumentoDTO);

  
  
  if ($numRegistros > 0){

    $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
    $objPesquisaPendenciaDTO->setDblIdProtocolo($_GET['id_procedimento']);
    $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
    $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    
    $objAtividadeRN = new AtividadeRN();
    $bolFlagAberto = false;
    if ($objAtividadeRN->listarPendenciasRN0754($objPesquisaPendenciaDTO)){
      $bolFlagAberto = true;
    }
    if ($numRegistros > 1){
      $bolCheck =SessaoSEI::getInstance()->verificarPermissao('documento_versao_comparar');
    } else {
      $bolCheck = false;
    }


   
    $bolAcaoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');
    $bolAcaoRecuperar = SessaoSEI::getInstance()->verificarPermissao('documento_versao_recuperar');
  

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Versões.';
    $strCaptionTabela = 'Versões';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros-$primeiroRegistro).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">Versão</th>'."\n";
    $strResultado .= '<th class="infraTh">Usuário</th>'."\n";
    $strResultado .= '<th class="infraTh">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh">Última Modificação</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $numIdUnidadeAtual=SessaoSEI::getInstance()->getNumIdUnidadeAtual();
    $numIdUnidadeGeradora=$arrObjProtocoloDTO[0]->getNumIdUnidadeGeradora();

    for($i=0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjVersaoSecaoDocumentoDTO[$i]->getNumVersao(),$arrObjVersaoSecaoDocumentoDTO[$i]->getNumVersao()).'</td>';
      }
      $strResultado .= '<td align="center">'.$arrObjVersaoSecaoDocumentoDTO[$i]->getNumVersao().'</td>';      

      $strResultado .= "\n".'<td align="center">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjVersaoSecaoDocumentoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjVersaoSecaoDocumentoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjVersaoSecaoDocumentoDTO[$i]->getStrSiglaUsuario()).'</a>';
      $strResultado .= '</td>';
      
      $strResultado .= "\n".'<td align="center">';
      $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjVersaoSecaoDocumentoDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjVersaoSecaoDocumentoDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjVersaoSecaoDocumentoDTO[$i]->getStrSiglaUnidade()).'</a>';
      $strResultado .= '</td>';
      
      $strResultado .= '<td align="center">'.$arrObjVersaoSecaoDocumentoDTO[$i]->getDthAtualizacao().'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjVersaoSecaoDocumentoDTO[$i]->getNumVersao().'-'.$arrObjVersaoSecaoDocumentoDTO[$i]->getDblIdDocumentoSecaoDocumento());

      $numIdUnidadeVersao=$arrObjVersaoSecaoDocumentoDTO[$i]->getNumIdUnidade();

      if ($bolAcaoVisualizar && ($i===0 || (($numIdUnidadeVersao==$numIdUnidadeAtual || $numIdUnidadeGeradora==$numIdUnidadeAtual)&& $arrObjProtocoloDTO[0]->getStrSinPublicado()==='N'))){
        if ($arrObjProtocoloDTO[0]->getStrStaNivelAcessoGlobal()!=ProtocoloRN::$NA_SIGILOSO || $arrObjVersaoSecaoDocumentoDTO[$i]->getNumIdUsuario()==SessaoSEI::getInstance()->getNumIdUsuario()) {
          $strResultado .= '<a target="_blank" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&versao='.$arrObjVersaoSecaoDocumentoDTO[$i]->getNumVersao().'&id_documento='.$arrObjVersaoSecaoDocumentoDTO[$i]->getDblIdDocumentoSecaoDocumento().$strParametros).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::DOCUMENTO_INTERNO.'" title="Visualizar Versão" alt="Visualizar Versão" class="infraImg" /></a>&nbsp;';
        }
      }
      
      if ($i>0 && $bolAcaoRecuperar && $bolFlagAberto && ($numIdUnidadeVersao==$numIdUnidadeAtual || $numIdUnidadeGeradora==$numIdUnidadeAtual) && $arrObjProtocoloDTO[0]->getStrSinPublicado()==='N'){
        if ($arrObjProtocoloDTO[0]->getStrStaNivelAcessoGlobal()!=ProtocoloRN::$NA_SIGILOSO || $arrObjVersaoSecaoDocumentoDTO[$i]->getNumIdUsuario()==SessaoSEI::getInstance()->getNumIdUsuario()) {
           $strResultado .= '<a href="#ID-'.$arrObjVersaoSecaoDocumentoDTO[$i]->getNumVersao().'" onclick="acaoRecuperar(\''.$arrObjVersaoSecaoDocumentoDTO[$i]->getNumVersao().'\',\''.$arrObjProtocoloDTO[0]->getStrSinAssinado().'\',\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_versao_recuperar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&versao='.$arrObjVersaoSecaoDocumentoDTO[$i]->getNumVersao().'&id_documento='.$arrObjVersaoSecaoDocumentoDTO[$i]->getDblIdDocumentoSecaoDocumento().$strParametros).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::DOCUMENTO_RECUPERAR_VERSAO.'" title="Recuperar Versão" alt="Recuperar Versão" class="infraImg" /></a>&nbsp;';
        }
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  $strLinkComparacao=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_versao_comparar&acao_origem='.$_GET['acao'].'&id_documento='.$_GET['id_documento']);
  if ($bolCheck){
    $arrComandos[] = '<button type="button" accesskey="V" id="btnComparar" value="Comparar" onclick="comparar(\''.$strLinkComparacao.'\');" class="infraButton">Comparar <span class="infraTeclaAtalho">V</span>ersões</button>';
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
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script type="text/javascript">
function inicializar(){

  <?if ($bolRecuperacaoOK){ ?>
     window.parent.parent.document.location.href = '<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$_GET['id_documento'].'&montar_visualizacao=1')?>';
     return;
  <?}?>

  //document.getElementById('btnFechar').focus();
  infraEfeitoTabelas();
}

<? if ($bolAcaoRecuperar){ ?>
function acaoRecuperar(id,assinado,link){
  if (assinado == 'S'){
    if (!confirm("Este documento já foi assinado. Se a versão for recuperada deverá ser assinado novamente.\nDeseja recuperar a versão "+id+"?")){
      return;
    }
  }
  
  document.getElementById('frmVersaoSecaoDocumentoLista').action = link;
  document.getElementById('frmVersaoSecaoDocumentoLista').submit();
}

function comparar(link){

  var selecao=$('#hdnInfraItensSelecionados').val();
  if (selecao=="" || selecao.split(',').length!=2){
    alert('Selecione duas versões para comparar.');
    return false;
  }

  infraAbrirJanelaModal('',800,600,false);
  document.getElementById('frmVersaoSecaoDocumentoLista').target='modal-frame';
  document.getElementById('frmVersaoSecaoDocumentoLista').action=link;
  document.getElementById('frmVersaoSecaoDocumentoLista').submit();
}

<? } ?>
//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmVersaoSecaoDocumentoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>