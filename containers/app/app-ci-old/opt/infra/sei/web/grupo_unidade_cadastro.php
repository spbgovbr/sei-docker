<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 27/09/2010 - criado por alexandre_db
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

  if(strpos($_GET['acao'],'grupo_unidade_institucional')===0){
    $strInstitucional = 'Institucional';
    $strRadical= 'grupo_unidade_institucional';
    $strStaTipo = GrupoUnidadeRN::$TGU_INSTITUCIONAL;
  } else {
    $strInstitucional = '';
    $strRadical= 'grupo_unidade';
    $strStaTipo = GrupoUnidadeRN::$TGU_UNIDADE;
  }

  PaginaSEI::getInstance()->verificarSelecao($strRadical.'_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //PaginaSEI::getInstance()->salvarCamposPost(array('selUnidade','selStaGrupo'));

  $objGrupoUnidadeDTO = new GrupoUnidadeDTO();

  $strDesabilitar = '';

  $arrComandos = array();
  $arrAcoes = array();

  switch($_GET['acao']){
    case $strRadical.'_cadastrar':

      $strTitulo = 'Novo Grupo de Envio '.$strInstitucional;

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarGrupoUnidade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objGrupoUnidadeDTO->setNumIdGrupoUnidade(null);
      $objGrupoUnidadeDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objGrupoUnidadeDTO->setStrNome($_POST['txtNome']);
      $objGrupoUnidadeDTO->setStrDescricao($_POST['txaDescricao']);
      $objGrupoUnidadeDTO->setStrStaTipo($strStaTipo);
      $objGrupoUnidadeDTO->setStrSinAtivo('S');

      $arr = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);

      $arrUnidades = array();

      foreach($arr as $numIdUnidade){
        $objRelGrupoUnidadeUnidadeDTO = new RelGrupoUnidadeUnidadeDTO();
        $objRelGrupoUnidadeUnidadeDTO->setNumIdUnidade($numIdUnidade);
        $arrUnidades[] = $objRelGrupoUnidadeUnidadeDTO;
      }

      $objGrupoUnidadeDTO->setArrObjRelGrupoUnidadeUnidadeDTO($arrUnidades);

      if (isset($_POST['sbmCadastrarGrupoUnidade'])) {
        try{
          $objGrupoUnidadeRN = new GrupoUnidadeRN();
          $objGrupoUnidadeDTO = $objGrupoUnidadeRN->cadastrar($objGrupoUnidadeDTO);
          PaginaSEI::getInstance()->setStrMensagem('Grupo de Envio "'.$objGrupoUnidadeDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_grupo='.$objGrupoUnidadeDTO->getNumIdGrupoUnidade().PaginaSEI::getInstance()->montarAncora($objGrupoUnidadeDTO->getNumIdGrupoUnidade())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case $strRadical.'_alterar':

      $strTitulo = 'Alterar Grupo de Envio '.$strInstitucional;

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarGrupoUnidade" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_grupo_unidade'])){
        $objGrupoUnidadeDTO->setNumIdGrupoUnidade($_GET['id_grupo_unidade']);
        $objGrupoUnidadeDTO->retTodos();
        $objGrupoUnidadeRN = new GrupoUnidadeRN();
        $objGrupoUnidadeDTO = $objGrupoUnidadeRN->consultar($objGrupoUnidadeDTO);
        if ($objGrupoUnidadeDTO==null){
          throw new InfraException("Registro não encontrado.");
        }

      } else {
        $objGrupoUnidadeDTO->setNumIdGrupoUnidade($_POST['hdnIdGrupoUnidade']);
        $objGrupoUnidadeDTO->setStrNome($_POST['txtNome']);
        $objGrupoUnidadeDTO->setStrDescricao($_POST['txaDescricao']);

        $arr = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidades']);

        $arrUnidades = array();

        foreach($arr as $numIdUnidade){
          $objRelGrupoUnidadeUnidadeDTO = new RelGrupoUnidadeUnidadeDTO();
          $objRelGrupoUnidadeUnidadeDTO->setNumIdUnidade($numIdUnidade);
          $arrUnidades[] = $objRelGrupoUnidadeUnidadeDTO;
        }

        $objGrupoUnidadeDTO->setArrObjRelGrupoUnidadeUnidadeDTO($arrUnidades);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objGrupoUnidadeDTO->getNumIdGrupoUnidade())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarGrupoUnidade'])) {
        try{
          $objGrupoUnidadeRN = new GrupoUnidadeRN();
          $objGrupoUnidadeRN->alterar($objGrupoUnidadeDTO);
          PaginaSEI::getInstance()->setStrMensagem('Grupo de Envio "'.$objGrupoUnidadeDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objGrupoUnidadeDTO->getNumIdGrupoUnidade())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case $strRadical.'_consultar':

      $strTitulo = 'Consultar Grupo de Envio '.$strInstitucional;

      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_grupo_unidade'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

      $objGrupoUnidadeDTO->setNumIdGrupoUnidade($_GET['id_grupo_unidade']);
      $objGrupoUnidadeDTO->setBolExclusaoLogica(false);
      $objGrupoUnidadeDTO->retTodos();

      $objGrupoUnidadeRN = new GrupoUnidadeRN();
      $objGrupoUnidadeDTO = $objGrupoUnidadeRN->consultar($objGrupoUnidadeDTO);

      if ($objGrupoUnidadeDTO===null){
        throw new InfraException("Registro não encontrado.");
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strSelUnidades = RelGrupoUnidadeUnidadeINT::montarSelectUnidade(null,null,null,$objGrupoUnidadeDTO->getNumIdGrupoUnidade());
  $strLinkAjaxUnidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_envio_processo');
  $strLinkUnidadeSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_envio_processo&tipo_selecao=2&id_object=objLupaUnidades');

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

  #lblNome {position:absolute;left:0%;top:0%;width:70.5%;}
  #txtNome {position:absolute;left:0%;top:5%;width:70.5%;}

  #lblDescricao {position:absolute;left:0%;top:12%;width:70.5%;}
  #txaDescricao {position:absolute;left:0%;top:17%;width:70.5%;}

  #lblUnidade {position:absolute;left:0%;top:29%;width:70.5%;}
  #txtUnidade {position:absolute;left:0%;top:34%;width:70.5%;}
  #selUnidades {position:absolute;left:0%;top:40.5%;width:81%;}
  #imgLupaUnidades {position:absolute;left:82%;top:40.5%;}
  #imgExcluirUnidades {position:absolute;left:82%;top:46%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>
var objLupaUnidades = null;
var objAutoCompletarUnidade = null;

  function inicializar(){
  <? if ($_GET['acao']== $strRadical.'_cadastrar') {?>
  document.getElementById('txtNome').focus();
  <? } else if ($_GET['acao'] == $strRadical.'_consultar'){?>
  infraDesabilitarCamposAreaDados();
  <?}else{?>
  document.getElementById('btnCancelar').focus();
  <?}?>

    objLupaUnidades = new infraLupaSelect('selUnidades','hdnUnidades','<?=$strLinkUnidadeSelecao?>');

    objAutoCompletarUnidade = new infraAjaxAutoCompletar('hdnIdUnidade','txtUnidade','<?=$strLinkAjaxUnidade?>');
    //objAutoCompletarUnidade.tamanhoMinimo = 3;
    objAutoCompletarUnidade.limparCampo = true;

    objAutoCompletarUnidade.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtUnidade').value+'&id_orgao=';
    };

    objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
      if (id!=''){
        objLupaUnidades.adicionar(id,descricao,document.getElementById('txtUnidade'));
      }
    };

    objAutoCompletarUnidade.selecionar('<?=$strIdUnidade?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strDescricaoUnidade,false)?>');

    infraEfeitoTabelas();
}

function OnSubmitForm() {
  return validarCadastro();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }
  return true;
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmGrupoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('40em');
    ?>

    <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objGrupoUnidadeDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblDescricao" for="txaDescricao" class="infraLabelOpcional">Descrição do Grupo:</label>
    <textarea id="txaDescricao" name="txaDescricao" rows="2" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,300);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objGrupoUnidadeDTO->getStrDescricao());?></textarea>

    <label id="lblUnidade" for="txtUnidade" accesskey="U" class="infraLabelOpcional"><span class="infraTeclaAtalho">U</span>nidade:</label>
    <input type="text" id="txtUnidade" name="txtUnidade" class="infraText"  onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
    <select id="selUnidades" name="selUnidades" size="16" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
    <?=$strSelUnidades?>
    </select>
    <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Unidades" title="Selecionar Unidades" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <input type="hidden" id="hdnIdGrupoUnidade" name="hdnIdGrupoUnidade" value="<?=$objGrupoUnidadeDTO->getNumIdGrupoUnidade();?>" />
    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value="" />
    <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="<?=$_POST['hdnUnidades']?>" />
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