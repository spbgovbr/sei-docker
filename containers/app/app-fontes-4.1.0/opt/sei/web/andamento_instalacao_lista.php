<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 10/04/2019 - criado por mga
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  switch($_GET['acao']){

    case 'andamento_instalacao_listar':
      $strTitulo = 'Histórico da Instalação';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
  $objInstalacaoFederacaoDTO->setBolExclusaoLogica(false);
  $objInstalacaoFederacaoDTO->retStrSigla();
  $objInstalacaoFederacaoDTO->retStrDescricao();
  $objInstalacaoFederacaoDTO->retDblCnpj();
  $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($_GET['id_instalacao_federacao']);

  $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
  $objInstalacaoFederacaoDTO = $objInstalacaoFederacaoRN->consultar($objInstalacaoFederacaoDTO);

  if ($objInstalacaoFederacaoDTO==null){
    throw new InfraException('Instalação não encontrada.');
  }

  $arrComandos = array();

  $objAndamentoInstalacaoDTO = new AndamentoInstalacaoDTO();
  $objAndamentoInstalacaoDTO->retNumIdAndamentoInstalacao();
  $objAndamentoInstalacaoDTO->retDthEstado();
  $objAndamentoInstalacaoDTO->retNumIdUnidade();
  $objAndamentoInstalacaoDTO->retStrSiglaUnidade();
  $objAndamentoInstalacaoDTO->retStrDescricaoUnidade();
  $objAndamentoInstalacaoDTO->retNumIdUsuario();
  $objAndamentoInstalacaoDTO->retStrSiglaUsuario();
  $objAndamentoInstalacaoDTO->retStrNomeUsuario();
  $objAndamentoInstalacaoDTO->retStrStaEstado();
  $objAndamentoInstalacaoDTO->retStrDescricaoEstado();
  $objAndamentoInstalacaoDTO->retStrNomeTarefaInstalacao();
  $objAndamentoInstalacaoDTO->retNumIdTarefaInstalacao();
  $objAndamentoInstalacaoDTO->setStrIdInstalacaoFederacao($_GET['id_instalacao_federacao']);
  $objAndamentoInstalacaoDTO->setOrdNumIdAndamentoInstalacao(InfraDTO::$TIPO_ORDENACAO_DESC);

  //PaginaSEI::getInstance()->prepararPaginacao($objAndamentoInstalacaoDTO);

  $objAndamentoInstalacaoRN = new AndamentoInstalacaoRN();
  $arrObjAndamentoInstalacaoDTO = $objAndamentoInstalacaoRN->listar($objAndamentoInstalacaoDTO);

  //PaginaSEI::getInstance()->processarPaginacao($objAndamentoInstalacaoDTO);
  $numRegistros = count($arrObjAndamentoInstalacaoDTO);

  if ($numRegistros > 0){

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Situações.';
    $strCaptionTabela = 'Situações';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="15%">Data/Hora</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Unidade</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Usuário</th>'."\n";
    $strResultado .= '<th class="infraTh">Situação</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $objAtributoInstalacaoRN = new AtributoInstalacaoRN();

    for($i = 0;$i < $numRegistros; $i++){

      $objAtributoInstalacaoDTO = new AtributoInstalacaoDTO();
      $objAtributoInstalacaoDTO->retStrNome();
      $objAtributoInstalacaoDTO->retStrValor();
      $objAtributoInstalacaoDTO->setNumIdAndamentoInstalacao($arrObjAndamentoInstalacaoDTO[$i]->getNumIdAndamentoInstalacao());
      $arrObjAtributoInstalacao = $objAtributoInstalacaoRN->listar($objAtributoInstalacaoDTO);

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAndamentoInstalacaoDTO[$i]->getDthEstado()).'</td>';

      $strResultado .= '<td align="center">';
      if ($arrObjAndamentoInstalacaoDTO[$i]->getStrSiglaUsuario() == SessaoSEI::$USUARIO_INTERNET){
        $strResultado .= '&nbsp;';
      }else{
        $strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjAndamentoInstalacaoDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjAndamentoInstalacaoDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjAndamentoInstalacaoDTO[$i]->getStrSiglaUnidade()).'</a>';
      }
      $strResultado .= '</td>';

      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjAndamentoInstalacaoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjAndamentoInstalacaoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjAndamentoInstalacaoDTO[$i]->getStrSiglaUsuario()).'</a></td>';
      $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($arrObjAndamentoInstalacaoDTO[$i]->getStrDescricaoEstado()).'</td>';
      $strResultado .= '<td>'.AndamentoInstalacaoINT::montarDescricao($arrObjAndamentoInstalacaoDTO[$i]->getStrNomeTarefaInstalacao(),$arrObjAtributoInstalacao).'</td>';
      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  $arrComandos[] = '<button type="button" accesskey="V" id="btnVoltar" value="Voltar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_instalacao_federacao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">V</span>oltar</button>';

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

#lblCnpj {position:absolute;left:0%;top:0%;width:20%;}
#txtCnpj {position:absolute;left:0%;top:35%;width:20%;}

#lblSigla {position:absolute;left:22%;top:0%;width:20%;}
#txtSigla {position:absolute;left:22%;top:35%;width:20%;}

#lblDescricao {position:absolute;left:44%;top:0%;width:55%;}
#txtDescricao {position:absolute;left:44%;top:35%;width:55%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  document.getElementById('btnVoltar').focus();
  infraEfeitoTabelas(true);
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAndamentoInstalacaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>

  <label id="lblSigla" for="txtSigla" accesskey="" class="infraLabelOpcional">Sigla:</label>
  <input type="text" id="txtSigla" name="txtSigla" value="<?=PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrSigla())?>" disabled="disabled" class="infraText infraReadOnly" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblCnpj" for="txtCnpj" accesskey="" class="infraLabelOpcional">CNPJ:</label>
  <input type="text" id="txtCnpj" name="txtCnpj" value="<?=PaginaSEI::tratarHTML(InfraUtil::formatarCnpj($objInstalacaoFederacaoDTO->getDblCnpj()))?>" disabled="disabled" class="infraText infraReadOnly" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
  <input type="text" id="txtDescricao" name="txtDescricao" value="<?=PaginaSEI::tratarHTML($objInstalacaoFederacaoDTO->getStrDescricao())?>" disabled="disabled" class="infraText infraReadOnly" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
