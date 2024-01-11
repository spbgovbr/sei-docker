<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 01/07/2008 - criado por fbv
*
* Versão do Gerador de Código: 1.19.0
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->prepararSelecao('serie_selecionar_acesso_externo');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

	if (isset($_GET['id_grupo_serie'])){
    PaginaSEI::getInstance()->salvarCampo('selGrupoSerie',$_GET['id_grupo_serie']);
    //$_POST['hdnInfraTotalRegistros'] = 0;
	}else{
	  PaginaSEI::getInstance()->salvarCamposPost(array('selGrupoSerie'));
	}

  PaginaSEI::getInstance()->salvarCamposPost(array('selModeloPesquisa', 'txtNomeSeriePesquisa', 'txtAssuntoSerie', 'hdnIdAssuntoSerie'));

  switch($_GET['acao']){


    case 'serie_selecionar_acesso_externo':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Tipo de Documento','Selecionar Tipos de Documento');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='serie_cadastrar'){
        if (isset($_GET['id_serie'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_serie']);
        }
      }
      break;


    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  $arrComandos = array();
  
  $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';

  $objSerieDTO = new SerieDTO(true);
  $objSerieDTO->retNumIdSerie();
  $objSerieDTO->retStrNome();
  $objSerieDTO->retStrNomeGrupoSerie();
  $objSerieDTO->retStrSinUsuarioExterno();
  $objSerieDTO->setStrSinUsuarioExterno("S");

  PaginaSEI::getInstance()->prepararOrdenacao($objSerieDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objSerieDTO);

  $objSerieRN = new SerieRN();
  $arrObjSerieDTO = $objSerieRN->pesquisar($objSerieDTO);
  
  PaginaSEI::getInstance()->processarPaginacao($objSerieDTO);
  $numRegistros = count($arrObjSerieDTO);

  if ($numRegistros > 0){

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Tipos de Documento.';
    $strCaptionTabela = 'Tipos de Documento';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    //$strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objSerieDTO,'ID','IdSerie',$arrObjSerieDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objSerieDTO,'Nome','Nome',$arrObjSerieDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objSerieDTO,'Grupo','NomeGrupoSerie',$arrObjSerieDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;
      $strResultado .= '<td>'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjSerieDTO[$i]->getNumIdSerie(),$arrObjSerieDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td align="center">'.$arrObjSerieDTO[$i]->getNumIdSerie().'</td>';
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjSerieDTO[$i]->getStrNome()).'</td>';
      //$strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjSerieDTO[$i]->getStrNomeGrupoSerie()).'</td>';
      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjSerieDTO[$i]->getNumIdSerie());
      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

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

  var objAutoCompletarAssuntoRI1223 = null;

function inicializar(){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
    infraEfeitoTabelas();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmSerieLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>