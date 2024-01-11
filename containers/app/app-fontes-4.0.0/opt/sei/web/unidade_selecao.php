<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/04/2008 - criado por mga
*
* Versão do Gerador de Código: 1.14.0
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

  PaginaSEI::getInstance()->prepararSelecao('unidade_selecionar_todas');  
  PaginaSEI::getInstance()->prepararSelecao('unidade_selecionar_outras');  
  PaginaSEI::getInstance()->prepararSelecao('unidade_selecionar_envio_processo');
  PaginaSEI::getInstance()->prepararSelecao('unidade_selecionar_orgao');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  PaginaSEI::getInstance()->salvarCamposPost(array('selOrgaoUnidade','txtSiglaUnidade','txtDescricaoUnidade'));

  $objOrgaoDTO = null;

  switch($_GET['acao']){

    case 'unidade_selecionar_todas':
    case 'unidade_selecionar_outras':
    case 'unidade_selecionar_envio_processo':  
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Unidade','Selecionar Unidades');
      break;

    case 'unidade_selecionar_orgao':
      $objOrgaoDTO = new OrgaoDTO();
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->retNumIdOrgao();
      $objOrgaoDTO->retStrSigla();
      $objOrgaoDTO->setNumIdOrgao($_GET['id_orgao']);
      $objOrgaoRN = new OrgaoRN();
      $objOrgaoDTO = $objOrgaoRN->consultarRN1352($objOrgaoDTO);

      if ($objOrgaoDTO==null){
        throw new InfraException('Órgão ['.$_GET['id_orgao'].'] não encontrado.');
      }

      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Unidade ('.$objOrgaoDTO->getStrSigla().')','Selecionar Unidades ('.$objOrgaoDTO->getStrSigla().')');

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  
  $arrComandos[] = '<input type="submit" id="btnPesquisar" value="Pesquisar" class="infraButton" />';  
  
  $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';

  $objUnidadeDTO = new UnidadeDTO();
  $objUnidadeDTO->retNumIdUnidade();
  $objUnidadeDTO->retStrSigla();
  $objUnidadeDTO->retStrDescricao();
  $objUnidadeDTO->retStrSiglaOrgao();
  $objUnidadeDTO->retStrDescricaoOrgao();

  PaginaSEI::getInstance()->prepararOrdenacao($objUnidadeDTO, 'Sigla', InfraDTO::$TIPO_ORDENACAO_ASC);
  PaginaSEI::getInstance()->prepararPaginacao($objUnidadeDTO);

  $numIdOrgao = PaginaSEI::getInstance()->recuperarCampo('selOrgaoUnidade');
  if ($numIdOrgao!==''){
    $objUnidadeDTO->setNumIdOrgao($numIdOrgao);
  }

  $strSiglaPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtSiglaUnidade');
  if ($strSiglaPesquisa!==''){
    $objUnidadeDTO->setStrSigla($strSiglaPesquisa);
  }
  
  $strDescricaoPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtDescricaoUnidade');
  if ($strDescricaoPesquisa!==''){
    $objUnidadeDTO->setStrDescricao($strDescricaoPesquisa);
  }
  
  $objUnidadeRN = new UnidadeRN();
  
  if ($_GET['acao']=='unidade_selecionar_todas'){
    $arrObjUnidadeDTO = $objUnidadeRN->listarTodasComFiltro($objUnidadeDTO);
  }else if ($_GET['acao']=='unidade_selecionar_outras'){
    $arrObjUnidadeDTO = $objUnidadeRN->listarOutrasComFiltro($objUnidadeDTO);
  }else if ($_GET['acao']=='unidade_selecionar_envio_processo'){
    $arrObjUnidadeDTO = $objUnidadeRN->listarEnvioProcesso($objUnidadeDTO);
  }else if ($_GET['acao']=='unidade_selecionar_orgao'){
    $objUnidadeDTO->setNumIdOrgao($objOrgaoDTO->getNumIdOrgao());
    $arrObjUnidadeDTO = $objUnidadeRN->pesquisar($objUnidadeDTO);
  }
  
  PaginaSEI::getInstance()->processarPaginacao($objUnidadeDTO);
  $numRegistros = InfraArray::contar($arrObjUnidadeDTO);

  if ($numRegistros > 0){

    $bolCheck = true;

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Unidades.';
    $strCaptionTabela = 'Unidades';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUnidadeDTO,'Sigla','Sigla',$arrObjUnidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUnidadeDTO,'Descrição','Descricao',$arrObjUnidadeDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objUnidadeDTO,'Órgão','SiglaOrgao',$arrObjUnidadeDTO).'</th>'."\n";
    
    //$strResultado .= '<th align="left" class="infraTh">Sigla</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjUnidadeDTO[$i]->getNumIdUnidade(),UnidadeINT::formatarSiglaDescricao($arrObjUnidadeDTO[$i]->getStrSigla(),$arrObjUnidadeDTO[$i]->getStrDescricao())).'</td>';
      }
      $strResultado .= '<td width="15%">'.$arrObjUnidadeDTO[$i]->getStrSigla().'</td>';
      $strResultado .= '<td>'.$arrObjUnidadeDTO[$i]->getStrDescricao().'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricaoOrgao()).'" title="'.PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrDescricaoOrgao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjUnidadeDTO[$i]->getStrSiglaOrgao()).'</a></td>';
      $strResultado .= '<td align="center">';
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjUnidadeDTO[$i]->getNumIdUnidade());
      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

  if ($_GET['acao'] == 'unidade_selecionar_orgao'){
    $strItensSelOrgao = InfraINT::montarSelectArrInfraDTO(null,null,$objOrgaoDTO->getNumIdOrgao(), array($objOrgaoDTO), 'IdOrgao', 'Sigla');
  }else{
    $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('','Todos',$numIdOrgao);
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

#lblOrgaoUnidade {position:absolute;left:0%;top:0%;width:20%;}
#selOrgaoUnidade {position:absolute;left:0%;top:40%;width:20%;}

#lblSiglaUnidade {position:absolute;left:22%;top:0%;width:20%;}
#txtSiglaUnidade {position:absolute;left:22%;top:40%;width:20%;}

#lblDescricaoUnidade {position:absolute;left:45%;top:0%;width:50%;}
#txtDescricaoUnidade {position:absolute;left:45%;top:40%;width:50%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

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
<form id="frmUnidadeLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_orgao='.$_GET['id_orgao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>

  <label id="lblOrgaoUnidade" for="selOrgaoUnidade" class="infraLabelOpcional">Órgão:</label>
  <select id="selOrgaoUnidade" name="selOrgaoUnidade" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
    <?=$strItensSelOrgao?>
  </select>

  <label id="lblSiglaUnidade" for="txtSiglaUnidade" class="infraLabelOpcional">Sigla:</label>
  <input type="text" id="txtSiglaUnidade" name="txtSiglaUnidade" class="infraText" value="<?=$strSiglaPesquisa?>" maxlength="15" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  
  <label id="lblDescricaoUnidade" for="txtDescricaoUnidade" class="infraLabelOpcional">Descrição:</label>
  <input type="text" id="txtDescricaoUnidade" name="txtDescricaoUnidade" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" value="<?=$strDescricaoPesquisa?>" />
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();  
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>