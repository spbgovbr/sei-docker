<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 29/03/2010 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
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

  PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
  
  switch($_GET['acao']){
    
    case 'novidade_mostrar':
      $strTitulo = '';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  
  $arrComandos = array();

  $objNovidadeRN = new NovidadeRN();

    
  $objNovidadeDTO = new NovidadeDTO();
  $objNovidadeDTO->retNumIdNovidade();
  $objNovidadeDTO->retStrTitulo();
  $objNovidadeDTO->retStrDescricao();
  $objNovidadeDTO->retDthLiberacao();
  $objNovidadeDTO->retStrSiglaUsuario();
  $objNovidadeDTO->retStrNomeUsuario();

  $strOcultar = '';
  
  if(isset($_GET['mostrar_todas']) && $_GET['mostrar_todas'] == '1'){
    
    //mostrar todas sem o checkbox
    $strOcultar = 'style="visibility:hidden;"';
    $objNovidadeDTO->setDthLiberacao(NovidadeRN::$DATA_NAO_LIBERADO,InfraDTO::$OPER_DIFERENTE);
    
  }else {

    $objInfraDadoUsuario = new InfraDadoUsuario(SessaoSEI::getInstance());
    $dthUltimaSalva = $objInfraDadoUsuario->getValor('NOVIDADE_ULTIMA');
    
    if ($dthUltimaSalva==null){
      $dthUltimaSalva = $_COOKIE[PaginaSEI::getInstance()->getStrPrefixoCookie().'_ultima_novidade'];
    }
    
    if(!InfraString::isBolVazia($dthUltimaSalva) && InfraData::validarDataHora($dthUltimaSalva)){
    
      $objNovidadeDTO->adicionarCriterio(array('Liberacao','Liberacao'),
                                         array(InfraDTO::$OPER_MAIOR, InfraDTO::$OPER_DIFERENTE),
                                         array($dthUltimaSalva,NovidadeRN::$DATA_NAO_LIBERADO),
                                         InfraDTO::$OPER_LOGICO_AND);
      
      
    }else{
      
      //sem cookie
      $dto = new NovidadeDTO();
      $dto->retDthLiberacao();
      $dto->setDthLiberacao(InfraData::getStrDataHoraAtual(),InfraDTO::$OPER_MENOR_IGUAL);
      $dto->setOrdDthLiberacao(InfraDTO::$TIPO_ORDENACAO_DESC);
      $dto->setNumMaxRegistrosRetorno(10); //últimas 10 novidades

      $arrDTO = $objNovidadeRN->listar($dto);

      if (count($arrDTO)>0){
        //somente as últimas
        $objNovidadeDTO->setDthLiberacao($arrDTO[0]->getDthLiberacao());
      }else{
        //ocultar não liberadas
        $objNovidadeDTO->setDthLiberacao(NovidadeRN::$DATA_NAO_LIBERADO,InfraDTO::$OPER_DIFERENTE);
      }
    }
  }
            
  $objNovidadeDTO->setOrdDthLiberacao(InfraDTO::$TIPO_ORDENACAO_DESC);
  
  $arrObjNovidadeDTO = $objNovidadeRN->listar($objNovidadeDTO);

  $numRegistros = count($arrObjNovidadeDTO);

  $strResultado = '<br><br><h5>Nenhuma novidade encontrada.</h5>';

  if ($numRegistros > 0){

    $dthUltimaExibida = $arrObjNovidadeDTO[0]->getDthLiberacao();
    
    $strResultado = '';

    for($i = 0;$i < $numRegistros; $i++){
      $strResultado .= '<br />'."\n";
      $strResultado .= '<div class="novidade">'."\n";
      $strResultado .= '<label class="infraLabelTitulo">'."\n";
      $strResultado .=  PaginaSEI::tratarHTML(substr($arrObjNovidadeDTO[$i]->getDthLiberacao(),0,10).' - '.$arrObjNovidadeDTO[$i]->getStrTitulo());
      $strResultado .= '</label>'."\n";
      $strResultado .= '<div class="descricaoNovidade">'."\n";
      $strResultado .= $arrObjNovidadeDTO[$i]->getStrDescricao();
      $strResultado .= '</div>'."\n";
      $strResultado .= '</div>'."\n";
      $strResultado .= '<br />'."\n";
    }
  }
$objEditorRN=new EditorRN();
}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - Novidades');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
echo $objEditorRN->montarCssEditor(0);
?>

div.novidade{
width:95%;
margin:0 auto;
}

label.infraLabelTitulo{
  display:block;
}

div.descricaoNovidade {
font-size:1.2em;
padding:0.2em;
}

#divSinNaoExibir{
  text-align:center;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
var objAjaxNovidade = null;

function inicializar(){
 objAjaxNovidade = new infraAjaxComplementar(null,'<?=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=novidade_salvar')?>');
  objAjaxNovidade.prepararExecucao = function(){
    if (document.getElementById('chkNovidade').checked){
      return 'dth_ultima_exibida=' + document.getElementById('hdnUltimaExibida').value;
    }else{
      return 'dth_ultima_exibida=' + document.getElementById('hdnUltimaSalva').value;
    }
  };
  objAjaxNovidade.processarResultado = function(arr){
  };
}

function gravarUltimaNovidade(chk){
  if (chk.checked){
    infraCriarCookie('<?=PaginaSEI::getInstance()->getStrPrefixoCookie()?>_ultima_novidade', document.getElementById('hdnUltimaExibida').value, 3650);
  }else{
    infraCriarCookie('<?=PaginaSEI::getInstance()->getStrPrefixoCookie()?>_ultima_novidade', document.getElementById('hdnUltimaSalva').value, 3650);
  }
  objAjaxNovidade.executar();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();

?>
<body onload="inicializar();">
<form id="frmNovidadeMostrar" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?=$strResultado?>

  <? if ($numRegistros){ ?>
  <div id="divSinNaoExibir" class="infraDivCheckbox" <?=$strOcultar?>>
    <input type="checkbox" id="chkNovidade" name="chkNovidade" class="infraCheckbox" onclick="gravarUltimaNovidade(this);" />
    <label id="lblNovidade" for="chkNovidade" class="infraLabelCheckbox"><?=$numRegistros==1?'Não exibir novamente esta novidade':'Não exibir novamente estas novidades'?></label>
  </div>
  <? } ?>

  <input type="hidden" id="hdnUltimaExibida" name="hdnUltimaExibida" value="<?=$dthUltimaExibida?>" />
  <input type="hidden" id="hdnUltimaSalva" name="hdnUltimaSalva" value="<?=$dthUltimaSalva?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
</body>
<?
PaginaSEI::getInstance()->fecharHtml();
?>