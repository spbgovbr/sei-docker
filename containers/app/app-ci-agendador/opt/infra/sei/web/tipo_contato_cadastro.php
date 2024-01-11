<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 17/12/2007 - criado por fbv
 *
 * Versão do Gerador de Código: 1.10.1
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

  PaginaSEI::getInstance()->verificarSelecao('tipo_contato_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objTipoContatoDTO = new TipoContatoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'tipo_contato_cadastrar':
      $strTitulo = 'Novo Tipo de Contato';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarTipoContato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objTipoContatoDTO->setNumIdTipoContato(null);
      $objTipoContatoDTO->setStrNome($_POST['txtNome']);
      $objTipoContatoDTO->setStrDescricao($_POST['txaDescricao']);

      if (PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPesquisaCompleta']) == 'S'){
        $objTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_COMPLETA);
      }else if (PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPesquisaResumida']) == 'S'){
        $objTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_RESUMIDA);
      }else{
        $objTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_NENHUM);
      }

      $objTipoContatoDTO->setStrSinSistema('N');
      $objTipoContatoDTO->setStrSinAtivo('S');

      $arrUnidadesAlteracao = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidadesAlteracao']);

      $arrObjRelUnidadeTipoContatoDTO = array();
      foreach($arrUnidadesAlteracao as $numIdUnidade){
        $objRelUnidadeTipoContatoDTO = new RelUnidadeTipoContatoDTO();
        $objRelUnidadeTipoContatoDTO->setNumIdUnidade($numIdUnidade);
        $objRelUnidadeTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_ALTERACAO);
        $arrObjRelUnidadeTipoContatoDTO[] = $objRelUnidadeTipoContatoDTO;
      }

      $arrUnidadesConsulta = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidadesConsulta']);
      foreach($arrUnidadesConsulta as $numIdUnidade){
        $objRelUnidadeTipoContatoDTO = new RelUnidadeTipoContatoDTO();
        $objRelUnidadeTipoContatoDTO->setNumIdUnidade($numIdUnidade);
        $objRelUnidadeTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_COMPLETA);
        $arrObjRelUnidadeTipoContatoDTO[] = $objRelUnidadeTipoContatoDTO;
      }

      $objTipoContatoDTO->setArrObjRelUnidadeTipoContatoDTO($arrObjRelUnidadeTipoContatoDTO);

      if (isset($_POST['sbmCadastrarTipoContato'])) {
        try{
          $objTipoContatoRN = new TipoContatoRN();
          $objTipoContatoDTO = $objTipoContatoRN->cadastrarRN0334($objTipoContatoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Tipo de Contato "'.$objTipoContatoDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_tipo_contato='.$objTipoContatoDTO->getNumIdTipoContato().'#ID-'.$objTipoContatoDTO->getNumIdTipoContato()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_contato_alterar':
      $strTitulo = 'Alterar Tipo de Contato';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarTipoContato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_tipo_contato'])){
        $objTipoContatoDTO->setNumIdTipoContato($_GET['id_tipo_contato']);
        $objTipoContatoDTO->retTodos();
        $objTipoContatoRN = new TipoContatoRN();
        $objTipoContatoDTO = $objTipoContatoRN->consultarRN0336($objTipoContatoDTO);
        if ($objTipoContatoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objTipoContatoDTO->setNumIdTipoContato($_POST['hdnIdTipoContato']);
        $objTipoContatoDTO->setStrNome($_POST['txtNome']);
        $objTipoContatoDTO->setStrDescricao($_POST['txaDescricao']);

        if (PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPesquisaCompleta']) == 'S'){
          $objTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_COMPLETA);
        }else if (PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPesquisaResumida']) == 'S'){
          $objTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_RESUMIDA);
        }else{
          $objTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_NENHUM);
        }

        $objTipoContatoDTO->setStrSinAtivo('S');

        $arrUnidadesAlteracao = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidadesAlteracao']);

        $arrObjRelUnidadeTipoContatoDTO = array();
        foreach($arrUnidadesAlteracao as $numIdUnidade){
          $objRelUnidadeTipoContatoDTO = new RelUnidadeTipoContatoDTO();
          $objRelUnidadeTipoContatoDTO->setNumIdUnidade($numIdUnidade);
          $objRelUnidadeTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_ALTERACAO);
          $arrObjRelUnidadeTipoContatoDTO[] = $objRelUnidadeTipoContatoDTO;
        }

        $arrUnidadesConsulta = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnUnidadesConsulta']);
        foreach($arrUnidadesConsulta as $numIdUnidade){
          $objRelUnidadeTipoContatoDTO = new RelUnidadeTipoContatoDTO();
          $objRelUnidadeTipoContatoDTO->setNumIdUnidade($numIdUnidade);
          $objRelUnidadeTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_COMPLETA);
          $arrObjRelUnidadeTipoContatoDTO[] = $objRelUnidadeTipoContatoDTO;
        }

        $objTipoContatoDTO->setArrObjRelUnidadeTipoContatoDTO($arrObjRelUnidadeTipoContatoDTO);

      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objTipoContatoDTO->getNumIdTipoContato().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarTipoContato'])) {
        try{
          $objTipoContatoRN = new TipoContatoRN();
          $objTipoContatoRN->alterarRN0335($objTipoContatoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Tipo de Contato "'.$objTipoContatoDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objTipoContatoDTO->getNumIdTipoContato()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'tipo_contato_consultar':
      $strTitulo = "Consultar Tipo de Contato";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_tipo_contato'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objTipoContatoDTO->setNumIdTipoContato($_GET['id_tipo_contato']);
      $objTipoContatoDTO->retTodos();
      $objTipoContatoRN = new TipoContatoRN();
      $objTipoContatoDTO = $objTipoContatoRN->consultarRN0336($objTipoContatoDTO);
      if ($objTipoContatoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkUnidadesAlteracaoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidadesAlteracao');
  $strLinkUnidadesConsultaSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_todas&tipo_selecao=2&id_object=objLupaUnidadesConsulta');
  $strLinkAjaxUnidades = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas');

  RelUnidadeTipoContatoINT::montarSelectSiglaUnidadeRI1202($objTipoContatoDTO->getNumIdTipoContato(),$strSelUnidadesAlteracao,$strSelUnidadesConsulta);


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

#divGeral {height:35em;}
#lblNome {position:absolute;left:0%;top:0%;width:70%;}
#txtNome {position:absolute;left:0%;top:6%;width:70%;}

#lblDescricao {position:absolute;left:0%;top:15%;width:70%;}
#txaDescricao {position:absolute;left:0%;top:21%;width:70%;}

#lblUnidadesAlteracao {position:absolute;left:0%;top:35%;width:40%;}
#txtUnidadeAlteracao {position:absolute;left:0%;top:40.5%;width:40%;}
#selUnidadesAlteracao {position:absolute;left:0%;top:48%;width:50.5%;}
#imgLupaUnidadesAlteracao {position:absolute;left:51%;top:48%;}
#imgExcluirUnidadesAlteracao {position:absolute;left:51%;top:55%;}

#divSinPesquisaCompleta {position:absolute;left:0%;top:82%;}
#divSinPesquisaResumida {position:absolute;left:0%;top:90%;}

#divUnidadesConsulta {height:17em;}
#lblUnidadesConsulta {position:absolute;left:0%;top:0%;width:40%;}
#txtUnidadeConsulta {position:absolute;left:0%;top:12%;width:40%;}
#selUnidadesConsulta {position:absolute;left:0%;top:27%;width:50.5%;}
#imgLupaUnidadesConsulta {position:absolute;left:51%;top:27%;}
#imgExcluirUnidadesConsulta {position:absolute;left:51%;top:40%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

var objLupaUnidadesAlteracao = null;
var objLupaUnidadesConsulta = null;

var objAutoCompletarUnidadeAlteracao = null;
var objAutoCompletarUnidadeConsulta = null;

function inicializar(){
  if ('<?=$_GET['acao']?>'=='tipo_contato_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='tipo_contato_consultar'){
    infraDesabilitarCamposAreaDados();
  }

  objLupaUnidadesAlteracao = new infraLupaSelect('selUnidadesAlteracao','hdnUnidadesAlteracao','<?=$strLinkUnidadesAlteracaoSelecao?>');
  objLupaUnidadesConsulta = new infraLupaSelect('selUnidadesConsulta','hdnUnidadesConsulta','<?=$strLinkUnidadesConsultaSelecao?>');


  objAutoCompletarUnidadeAlteracao = new infraAjaxAutoCompletar('hdnIdUnidadeAlteracao','txtUnidadeAlteracao','<?=$strLinkAjaxUnidades?>');
  //objAutoCompletarUnidadeAlteracao.maiusculas = true;
  //objAutoCompletarUnidadeAlteracao.mostrarAviso = true;
  //objAutoCompletarUnidadeAlteracao.tempoAviso = 1000;
  //objAutoCompletarUnidadeAlteracao.tamanhoMinimo = 3;
  objAutoCompletarUnidadeAlteracao.limparCampo = true;
  //objAutoCompletarUnidadeAlteracao.bolExecucaoAutomatica = false;

  objAutoCompletarUnidadeAlteracao.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUnidadeAlteracao').value;
  };

  objAutoCompletarUnidadeAlteracao.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objLupaUnidadesAlteracao.adicionar(id,descricao,document.getElementById('txtUnidadeAlteracao'));
    }
  };

  /////////////////////////////

  objAutoCompletarUnidadeConsulta = new infraAjaxAutoCompletar('hdnIdUnidadeConsulta','txtUnidadeConsulta','<?=$strLinkAjaxUnidades?>');
  //objAutoCompletarUnidadeConsulta.maiusculas = true;
  //objAutoCompletarUnidadeConsulta.mostrarAviso = true;
  //objAutoCompletarUnidadeConsulta.tempoAviso = 1000;
  //objAutoCompletarUnidadeConsulta.tamanhoMinimo = 3;
  objAutoCompletarUnidadeConsulta.limparCampo = true;
  //objAutoCompletarUnidadeConsulta.bolExecucaoAutomatica = false;

  objAutoCompletarUnidadeConsulta.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtUnidadeConsulta').value;
  };

  objAutoCompletarUnidadeConsulta.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objLupaUnidadesConsulta.adicionar(id,descricao,document.getElementById('txtUnidadeConsulta'));
    }
  };

  formatarOpcoesPesquisa();
}

function OnSubmitForm() {
  return ValidarCadastroRI0366();
}

function ValidarCadastroRI0366() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }
  return true;
}

function formatarOpcoesPesquisa(){
  if (document.getElementById('chkSinPesquisaCompleta').checked){
    document.getElementById('chkSinPesquisaResumida').checked = false;
    document.getElementById('chkSinPesquisaResumida').disabled = true;
    document.getElementById('divUnidadesConsulta').style.visibility = 'hidden';
  }else{
    document.getElementById('chkSinPesquisaResumida').disabled = false;
    document.getElementById('divUnidadesConsulta').style.visibility = 'visible';
  }
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmTipoContatoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>

    <div id="divGeral" class="infraAreaDados">
      <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
      <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objTipoContatoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <label id="lblDescricao" for="txaDescricao" accesskey="e" class="infraLabelOpcional">D<span class="infraTeclaAtalho">e</span>scrição:</label>
      <textarea id="txaDescricao" name="txaDescricao" onkeypress="return infraLimitarTexto(this,event,250);" rows='2' class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objTipoContatoDTO->getStrDescricao());?></textarea>

      <label id="lblUnidadesAlteracao" for="selUnidadesAlteracao" class="infraLabelOpcional">Unidades administradoras deste tipo:</label>
      <input type="text" id="txtUnidadeAlteracao" name="txtUnidadeAlteracao" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdUnidadeAlteracao" name="hdnIdUnidadeAlteracao" class="infraText" value="" />
      <select id="selUnidadesAlteracao" name="selUnidadesAlteracao" onclick="" size="5" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strDesabilitarReceber?>>
        <?=$strSelUnidadesAlteracao?>
      </select>
      <img id="imgLupaUnidadesAlteracao" onclick="objLupaUnidadesAlteracao.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Unidades" title="Selecionar Unidades" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <img id="imgExcluirUnidadesAlteracao" onclick="objLupaUnidadesAlteracao.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />


      <div id="divSinPesquisaCompleta" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinPesquisaCompleta" name="chkSinPesquisaCompleta" onchange="formatarOpcoesPesquisa()" class="infraCheckbox" <?=($objTipoContatoDTO->getStrStaAcesso()==TipoContatoRN::$TA_CONSULTA_COMPLETA ? 'checked="checked"' : '')?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinPesquisaCompleta" for="chkSinPesquisaCompleta" accesskey="" class="infraLabelCheckbox">Consulta completa em todas as unidades (endereço, telefone, CPF, RG, data de nascimento, ...) </label>
      </div>

      <div id="divSinPesquisaResumida" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinPesquisaResumida" name="chkSinPesquisaResumida" onchange="formatarOpcoesPesquisa()" class="infraCheckbox" <?=($objTipoContatoDTO->getStrStaAcesso()==TipoContatoRN::$TA_CONSULTA_RESUMIDA ? 'checked="checked"' : '')?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinPesquisaResumida" for="chkSinPesquisaResumida" accesskey="" class="infraLabelCheckbox">Consulta resumida em todas as unidades (tratamento, cargo, título, vocativo, e-mail, ...) </label>
      </div>
    </div>

    <div id="divUnidadesConsulta" class="infraAreaDados">
      <label id="lblUnidadesConsulta" for="selUnidadesConsulta" class="infraLabelOpcional">Consulta completa apenas nas unidades:</label>
      <input type="text" id="txtUnidadeConsulta" name="txtUnidadeConsulta" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdUnidadeConsulta" name="hdnIdUnidadeConsulta" class="infraText" value="" />
      <select id="selUnidadesConsulta" name="selUnidadesConsulta" onclick="" size="5" multiple="multiple" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strDesabilitarReceber?>>
      <?=$strSelUnidadesConsulta?>
      </select>
      <img id="imgLupaUnidadesConsulta" onclick="objLupaUnidadesConsulta.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Unidades" title="Selecionar Unidades" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <img id="imgExcluirUnidadesConsulta" onclick="objLupaUnidadesConsulta.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>
    <?
    //PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>

    <input type="hidden" id="hdnUnidadesAlteracao" name="hdnUnidadesAlteracao" value="<?=$_POST['hdnUnidadesAlteracao']?>" />
    <input type="hidden" id="hdnUnidadesConsulta" name="hdnUnidadesConsulta" value="<?=$_POST['hdnUnidadesConsulta']?>" />
    <input type="hidden" id="hdnIdTipoContato" name="hdnIdTipoContato" value="<?=$objTipoContatoDTO->getNumIdTipoContato();?>" />

  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>