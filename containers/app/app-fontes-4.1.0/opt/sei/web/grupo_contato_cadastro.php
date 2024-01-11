<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(false);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  if(strpos($_GET['acao'],'grupo_contato_institucional')===0){
    $strInstitucional = 'Institucional';
    $strRadical= 'grupo_contato_institucional';
    $strStaTipo = GrupoContatoRN::$TGC_INSTITUCIONAL;
  } else {
    $strInstitucional = '';
    $strRadical= 'grupo_contato';
    $strStaTipo = GrupoContatoRN::$TGC_UNIDADE;
  }

  PaginaSEI::getInstance()->verificarSelecao($strRadical.'_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $objGrupoContatoDTO = new GrupoContatoDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case $strRadical.'_cadastrar':

      $strTitulo = 'Novo Grupo de Contatos '.$strInstitucional;

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarGrupoContato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
			$arrOpcoes = array();
			$arrObjDTOA = array();
			
	    $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnContatos']);

			for($x = 0;$x<count($arrOpcoes);$x++){
			  $objRelGrupoContatoDTO = new RelGrupoContatoDTO();
			  $objRelGrupoContatoDTO->setNumIdContato($arrOpcoes[$x]);
			  $arrObjDTOA[$x] = $objRelGrupoContatoDTO;
			}
			$objGrupoContatoDTO->setArrObjRelGrupoContatoDTO($arrObjDTOA);
                          
      $objGrupoContatoDTO->setNumIdGrupoContato(null);
      $objGrupoContatoDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objGrupoContatoDTO->setStrNome($_POST['txtNome']);
      $objGrupoContatoDTO->setStrDescricao($_POST['txaDescricao']);
      $objGrupoContatoDTO->setStrStaTipo($strStaTipo);
      $objGrupoContatoDTO->setStrSinAtivo('S');

      if (isset($_POST['sbmCadastrarGrupoContato'])) {
        try{
          $objGrupoContatoRN = new GrupoContatoRN();
          $objGrupoContatoDTO = $objGrupoContatoRN->cadastrarRN0472($objGrupoContatoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Grupo de Contatos "'.$objGrupoContatoDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_grupo_contato='.$objGrupoContatoDTO->getNumIdGrupoContato().'#ID-'.$objGrupoContatoDTO->getNumIdGrupoContato()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case $strRadical.'_alterar':

      $strTitulo = 'Alterar Grupo de Contatos '.$strInstitucional;

      $arrComandos[] = '<button type="submit" accesskey="S" id="sbmAlterarGrupoContato" name="sbmAlterarGrupoContato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      $numIdGrupoContato = null;
      
      if (isset($_GET['id_grupo_contato'])){
        $numIdGrupoContato = $_GET['id_grupo_contato'];
      }else if (isset($_POST['selGrupoContato'])){
        $numIdGrupoContato = $_POST['selGrupoContato'];
      }
      
      if ($numIdGrupoContato!==null){
        $objGrupoContatoDTO->setNumIdGrupoContato($numIdGrupoContato);
        $objGrupoContatoDTO->retTodos();
        $objGrupoContatoRN = new GrupoContatoRN();
        $objGrupoContatoDTO = $objGrupoContatoRN->consultarRN0474($objGrupoContatoDTO);
        if ($objGrupoContatoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objGrupoContatoDTO->setNumIdGrupoContato($_POST['hdnIdGrupoContato']);
        $objGrupoContatoDTO->setStrNome($_POST['txtNome']);
        $objGrupoContatoDTO->setStrDescricao($_POST['txaDescricao']);
      }
      
	    $arrOpcoes = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnContatos']);

			for($x = 0;$x<count($arrOpcoes);$x++){
			  $objRelGrupoContatoDTO = new RelGrupoContatoDTO();
			  $objRelGrupoContatoDTO->setNumIdContato($arrOpcoes[$x]);
			  $arrObjDTOA[$x] = $objRelGrupoContatoDTO;
			}
			$objGrupoContatoDTO->setArrObjRelGrupoContatoDTO($arrObjDTOA);
      

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objGrupoContatoDTO->getNumIdGrupoContato().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarGrupoContato'])) {
        try{
          $objGrupoContatoRN = new GrupoContatoRN();
          $objGrupoContatoRN->alterarRN0473($objGrupoContatoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Grupo de Contatos "'.$objGrupoContatoDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objGrupoContatoDTO->getNumIdGrupoContato()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case $strRadical.'_consultar':

      $strTitulo = 'Consultar Grupo de Contatos '.$strInstitucional;

      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_grupo_contato'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objGrupoContatoDTO->setNumIdGrupoContato($_GET['id_grupo_contato']);
      $objGrupoContatoDTO->setBolExclusaoLogica(false);
      $objGrupoContatoDTO->retTodos();
      $objGrupoContatoRN = new GrupoContatoRN();
      $objGrupoContatoDTO = $objGrupoContatoRN->consultarRN0474($objGrupoContatoDTO);
      
      if ($objGrupoContatoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;
      
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strLinkAjaxContatos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_contexto_RI1225');
  $strLinkContatosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_selecionar&tipo_selecao=2&id_object=objLupaContatos');
  $strLinkAjaxCadastroAutomatico = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_cadastro_contexto_temporario');
  $strItensSelGrupoContato = ContatoINT::montarSelectContatosGrupoRI0495(null,null,null,$objGrupoContatoDTO->getNumIdGrupoContato());

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

#lblNome {position:absolute;left:0%;top:0%;width:20%;}
#txtNome {position:absolute;left:0%;top:5%;width:50%;}

#lblDescricao {position:absolute;left:0%;top:12%;width:50%;}
#txaDescricao {position:absolute;left:0%;top:17%;width:80%;}

#lblContatos {position:absolute;left:0%;top:29%;width:50%;}
#txtContato {position:absolute;left:0%;top:34%;width:79.3%;}
#selContatos {position:absolute;left:0%;top:40.5%;width:80%;}

#imgLupaContatos {position:absolute;left:81%;top:40.5%;}
#imgExcluirContatos {position:absolute;left:81%;top:46%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>

var objAutoCompletarContato = null;
var objLupaContatos = null;
var objContatoCadastroAutomatico = null;

function inicializar(){

  <? if ($_GET['acao'] == $strRadical.'_cadastrar'){ ?>
    document.getElementById('txtNome').focus();
  <? } else if ($_GET['acao'] == $strRadical.'_consultar'){ ?>
    infraDesabilitarCamposAreaDados();
  <? } ?>

  objLupaContatos = new infraLupaSelect('selContatos','hdnContatos','<?=$strLinkContatosSelecao?>');

  objAutoCompletarContato = new infraAjaxAutoCompletar('hdnIdContato','txtContato','<?=$strLinkAjaxContatos?>');
  //objAutoCompletarContato.maiusculas = true;
  //objAutoCompletarContato.mostrarAviso = true;
  //objAutoCompletarContato.tempoAviso = 1000;
  //objAutoCompletarContato.tamanhoMinimo = 3;
  objAutoCompletarContato.limparCampo = false;
  //objAutoCompletarContato.bolExecucaoAutomatica = false;

  objAutoCompletarContato.prepararExecucao = function(){
    return 'palavras_pesquisa='+encodeURIComponent(document.getElementById('txtContato').value);
  };

  objAutoCompletarContato.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objLupaContatos.adicionar(id,descricao,document.getElementById('txtContato'));
    }
  };

  infraAdicionarEvento(document.getElementById('txtContato'),'keyup',tratarEnterContato);

  objContatoCadastroAutomatico = new infraAjaxComplementar(null,'<?=$strLinkAjaxCadastroAutomatico?>');
  //objContatoCadastroAutomatico.mostrarAviso = false;
  //objContatoCadastroAutomatico.tempoAviso = 3000;
  //objContatoCadastroAutomatico.limparCampo = false;

  objContatoCadastroAutomatico.prepararExecucao = function(){
      return 'nome='+encodeURIComponent(document.getElementById('txtContato').value);
  };

  objContatoCadastroAutomatico.processarResultado = function(arr){
    if (arr!=null){
      objAutoCompletarContato.processarResultado(arr['IdContato'], document.getElementById('txtContato').value, null);
    }
  };
}


function tratarEnterContato(ev){
  var key = infraGetCodigoTecla(ev);

  if (key == 13 && document.getElementById('hdnIdContato').value=='' && infraTrim(document.getElementById('txtContato').value)!=''){
    if (confirm('Nome inexistente. Deseja incluir?')){
      objContatoCadastroAutomatico.executar();
    }
  }
}

function OnSubmitForm() {
  return validarCadastroRI0494();
}

function validarCadastroRI0494() {
  
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
<form id="frmGrupoContatoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('40em');
?>
  <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
  <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objGrupoContatoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="2" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objGrupoContatoDTO->getStrDescricao());?></textarea>

  <label id="lblContatos" for="selContatos" accesskey="" class="infraLabelOpcional">Contatos:</label>
  <input type="text" id="txtContato" name="txtContato" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
  <input type="hidden" id="hdnIdContato" name="hdnIdContato" class="infraText" value="" />
  <select id="selContatos" name="selContatos" size="12" multiple="multiple" class="infraSelect">
  	<?=$strItensSelGrupoContato?>
  </select>
  <img id="imgLupaContatos" onclick="objLupaContatos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Localizar Contato" title="Localizar Contato" class="infraImg" />
  <img id="imgExcluirContatos" onclick="objLupaContatos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Contatos" title="Remover Contato" class="infraImg" />

  <input type="hidden" id="hdnIdGrupoContato" name="hdnIdGrupoContato" value="<?=$objGrupoContatoDTO->getNumIdGrupoContato();?>" />
  <input type="hidden" id="hdnContatos" name="hdnContatos" value="<?=$_POST['hdnContatos']?>" />
  
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