<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 23/07/2019 - criado por mga
*
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  $arrComandos = array();

  $objBlocoComentarDTO = new BlocoComentarDTO();

  $bolOk = false;

  switch($_GET['acao']){

    case 'bloco_comentar':

      $strTitulo = 'Comentar Bloco';

      if ($_GET['acao_origem']!='bloco_comentar'){

        $arrNumIdBloco = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $strTextoComentario = null;

        if (count($arrNumIdBloco) == 1) {
          $objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
          $objRelBlocoUnidadeDTO->retStrTextoComentario();
          $objRelBlocoUnidadeDTO->setNumIdBloco($arrNumIdBloco[0]);
          $objRelBlocoUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

          $objRelBlocoUnidadeRN = new RelBlocoUnidadeRN();
          $objRelBlocoUnidadeDTO = $objRelBlocoUnidadeRN->consultarRN1303($objRelBlocoUnidadeDTO);

          if ($objRelBlocoUnidadeDTO!=null){
            $strTextoComentario = $objRelBlocoUnidadeDTO->getStrTextoComentario();
          }
        }

        $objBlocoComentarDTO->setStrTextoComentario($strTextoComentario);

      }else{
        $arrNumIdBloco = explode(',',$_POST['hdnIdBloco']);
        $objBlocoComentarDTO->setStrTextoComentario($_POST['txaDescricao']);
      }

      if (count($arrNumIdBloco)==1){
        $strTitulo .= ' '.$arrNumIdBloco[0];
      }

      if (isset($_POST['sbmSalvar'])){
        try{

          $objBlocoComentarDTO->setArrObjBlocoDTO(InfraArray::gerarArrInfraDTO('BlocoDTO','IdBloco',$arrNumIdBloco));

          $objBlocoRN = new BlocoRN();
          $objBlocoRN->comentar($objBlocoComentarDTO);

          $strLinkRetorno = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&'.PaginaSEI::getParametroRandom().PaginaSEI::montarAncora($arrNumIdBloco));
          $bolOk = true;
          break;

          //header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::montarAncora($arrNumIdBloco)));
          //die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" id="sbmSalvar" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      //$arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&acao_destino='.$_GET['acao'].PaginaSEI::montarAncora($arrNumIdBloco)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
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
#lblDescricao {position:absolute;left:0%;top:0%;width:50%;}
#txaDescricao {position:absolute;left:0%;top:8%;width:90%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){

  <? if ($bolOk){?>
    window.parent.location = '<?=$strLinkRetorno?>';
    self.setTimeout('infraFecharJanelaModal()',200);
  <?}else{?>
    document.getElementById('txaDescricao').focus();
  <?}?>
}

function OnSubmitForm() {
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmBlocoComentario" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('25em');
?>
  
 	<label id="lblDescricao" for="txaDescricao" class="infraLabelOpcional">Descrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="12" onkeypress="return infraLimitarTexto(this,event,2000);" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objBlocoComentarDTO->getStrTextoComentario());?></textarea>

  <input type="hidden" id="hdnIdBloco" name="hdnIdBloco" value="<?=implode(',',$arrNumIdBloco);?>" />
  
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>