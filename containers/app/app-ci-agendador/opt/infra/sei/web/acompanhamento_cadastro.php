<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 05/11/2010 - criado por jonatas_db
 * 06/06/2018 - cjy - adição da opção/icone de acompanhamento especial
 *
 * Versão do Gerador de Código: 1.30.0
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

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('alterados'));

  PaginaSEI::getInstance()->verificarSelecao('acompanhamento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples','id_procedimento'));

  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  if (isset($_GET['pagina_simples'])){
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  $numIdGrupoAcompanhamento = '';
  if(isset($_GET['id_grupo_acompanhamento'])){
    $numIdGrupoAcompanhamento = $_GET['id_grupo_acompanhamento'];
  }else{
    $numIdGrupoAcompanhamento = $_POST['selGrupoAcompanhamento'];
  }

  $objAcompanhamentoDTO = new AcompanhamentoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  $arrIdProtocolo = array();

  switch($_GET['acao']){
    case 'acompanhamento_cadastrar':

      $strTitulo = 'Novo Acompanhamento Especial';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarAcompanhamento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      $arrIdProtocolo = array();
      $lugar =0;
      if ($_GET['acao_origem']=='procedimento_controlar') {
        $arrIdProtocolo = array_merge(PaginaSEI::getInstance()->getArrStrItensSelecionados('Gerados'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Recebidos'), PaginaSEI::getInstance()->getArrStrItensSelecionados('Detalhado'));
      }else if ($_GET['acao_origem']=='rel_bloco_protocolo_listar'){

        $arrIdProtocolo = array();
        $arrIdProcessosBloco = PaginaSEI::getInstance()->getArrStrItensSelecionados();

        foreach($arrIdProcessosBloco as $strIdProcessoBloco){
          $arrTemp = explode('-',$strIdProcessoBloco);
          $arrIdProtocolo[] = $arrTemp[0];
        }

      }else if(isset($_GET['id_procedimento']) && $_GET['id_procedimento']!=''){
        $arrIdProtocolo[] = $_GET['id_procedimento'];
      }else if(isset($_POST['hdnIdProtocolo']) && $_POST['hdnIdProtocolo']!=''){
        $arrIdProtocolo=explode(',',$_POST['hdnIdProtocolo']);
      }

      $strAncora = $arrIdProtocolo;

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'].$strParametros) . PaginaSEI::getInstance()->montarAncora($strAncora) . '\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objAcompanhamentoDTO->setNumIdAcompanhamento(null);
      $objAcompanhamentoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objAcompanhamentoDTO->setNumIdGrupoAcompanhamento($numIdGrupoAcompanhamento);
      $objAcompanhamentoDTO->setDblIdProtocolo($_GET['id_procedimento']);
      $objAcompanhamentoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objAcompanhamentoDTO->setDthAlteracao(InfraData::getStrDataHoraAtual());
      $objAcompanhamentoDTO->setStrObservacao($_POST['txaObservacao']);


      if (isset($_POST['sbmCadastrarAcompanhamento'])) {
        try{
          $objAcompanhamentoRN = new AcompanhamentoRN();
          $arrAcompanhamentoDTO_Multiplo = array();
          foreach ($arrIdProtocolo as $strIdProtocolo_Multiplo) {
            $objAcompanhamentoDTO_Multiplo = new AcompanhamentoDTO();
            $objAcompanhamentoDTO_Multiplo->setNumIdAcompanhamento(null);
            $objAcompanhamentoDTO_Multiplo->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objAcompanhamentoDTO_Multiplo->setNumIdGrupoAcompanhamento($numIdGrupoAcompanhamento);
            $objAcompanhamentoDTO_Multiplo->setDblIdProtocolo($strIdProtocolo_Multiplo);
            $objAcompanhamentoDTO_Multiplo->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
            $objAcompanhamentoDTO_Multiplo->setDthAlteracao(InfraData::getStrDataHoraAtual());
            $objAcompanhamentoDTO_Multiplo->setStrObservacao($_POST['txaObservacao']);
            $arrAcompanhamentoDTO_Multiplo[] = $objAcompanhamentoDTO_Multiplo;
          }
          $arrObjAcompanhamentoDTO = $objAcompanhamentoRN->cadastrarMultiplos($arrAcompanhamentoDTO_Multiplo);
          PaginaSEI::getInstance()->setStrMensagem('Acompanhamento "' . $objAcompanhamentoDTO->getNumIdAcompanhamento() . '" cadastrado com sucesso.');

          if (PaginaSEI::getInstance()->getAcaoRetorno()=='acompanhamento_listar'){
            $strAncora = InfraArray::converterArrInfraDTO($arrObjAcompanhamentoDTO,'IdAcompanhamento');
          }

          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] .'&id_grupo_acompanhamento='.$numIdGrupoAcompanhamento.'&resultado=1'. $strParametros . PaginaSEI::montarAncora($strAncora)));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
     }
      break;

    case 'acompanhamento_alterar':

      if (PaginaSEI::getInstance()->isBolArvore()){
        $strTitulo = 'Acompanhamento Especial';
      }else{
        $strTitulo = 'Alterar Acompanhamento Especial';
      }

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarAcompanhamento" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_acompanhamento'])){
        $objAcompanhamentoDTO->setNumIdAcompanhamento($_GET['id_acompanhamento']);
        $objAcompanhamentoDTO->retTodos();
        $objAcompanhamentoRN = new AcompanhamentoRN();
        $objAcompanhamentoDTO = $objAcompanhamentoRN->consultar($objAcompanhamentoDTO);

        if ($objAcompanhamentoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }

        $numIdGrupoAcompanhamento = $objAcompanhamentoDTO->getNumIdGrupoAcompanhamento();

      } else {
        $objAcompanhamentoDTO->setNumIdAcompanhamento($_POST['hdnIdAcompanhamento']);
        //$objAcompanhamentoDTO->setDblIdProtocolo($_GET['id_procedimento']);
        $objAcompanhamentoDTO->setNumIdGrupoAcompanhamento($numIdGrupoAcompanhamento);
        $objAcompanhamentoDTO->setStrObservacao($_POST['txaObservacao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($strAncora)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarAcompanhamento'])) {
        try{
          $objAcompanhamentoRN = new AcompanhamentoRN();
          $objAcompanhamentoRN->alterar($objAcompanhamentoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Acompanhamento "'.$objAcompanhamentoDTO->getNumIdAcompanhamento().'" alterado com sucesso.');

          //die('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objAcompanhamentoDTO->getNumIdAcompanhamento()));

          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objAcompanhamentoDTO->getNumIdAcompanhamento())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelGrupoAcompanhamento = GrupoAcompanhamentoINT::montarSelectIdGrupoAcompanhamentoRI0012('null','&nbsp;', $numIdGrupoAcompanhamento, SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  if (SessaoSEI::getInstance()->verificarPermissao('grupo_acompanhamento_cadastrar')) {
    $strImgNovoGrupoAcompanhamento = '<img id="imgNovoGrupoAcompanhamento" onclick="cadastrarGrupoAcompanhamento();" src="'.PaginaSEI::getInstance()->getIconeMais().'" alt="Novo Grupo de Acompanhamento" title="Novo Grupo de Acompanhamento" class="infraImg" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"/>';
    $strLinkNovoGrupoAcompanhamento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_acompanhamento_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&pagina_simples=1');
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
#lblSelGrupoAcompanhamento {position:absolute;left:0%;top:0%;width:50%;}
#selGrupoAcompanhamento {position:absolute;left:0%;top:10%;width:50%;}
#imgNovoGrupoAcompanhamento {position:absolute;left:50.5%;top:11%;}

#lblObservacao {position:absolute;left:0%;top:25%;width:95%;}
#txaObservacao {position:absolute;left:0%;top:35%;width:95%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  infraEfeitoTabelas();
}

function validarCadastroRI0017() {
/*
if (infraTrim(document.getElementById('txaObservacao').value)=='') {
alert('Informe a Observação.');
document.getElementById('txaObservacao').focus();
return false;
}
*/
return true;
}

function OnSubmitForm() {
return validarCadastroRI0017();
}

function cadastrarGrupoAcompanhamento(){
  parent.infraAbrirJanelaModal('<?=$strLinkNovoGrupoAcompanhamento?>',700,300);
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAcompanhamentoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->montarAreaValidacao();
  PaginaSEI::getInstance()->abrirAreaDados('20em');
  ?>
  <label id="lblSelGrupoAcompanhamento" for="selGrupoAcompanhamento" accesskey="G" class="infraLabelOpcional"><span class="infraTeclaAtalho">G</span>rupo:</label>
  <select id="selGrupoAcompanhamento" name="selGrupoAcompanhamento" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
    <?=$strItensSelGrupoAcompanhamento?>
  </select>
  <?=$strImgNovoGrupoAcompanhamento?>

  <label id="lblObservacao" for="txaObservacao" accesskey="O" class="infraLabelOpcionals"><span class="infraTeclaAtalho">O</span>bservação:</label>
  <textarea rows="4" name="txaObservacao" id="txaObservacao" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,500);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objAcompanhamentoDTO->getStrObservacao());?></textarea>

  <input type="hidden" id="hdnIdAcompanhamento" name="hdnIdAcompanhamento" value="<?=$objAcompanhamentoDTO->getNumIdAcompanhamento();?>" />

  <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=implode(',',$arrIdProtocolo);?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>


