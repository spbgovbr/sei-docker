<?

/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/05/2008 - criado por mga
*
* Versão do Gerador de Código: 1.16.0
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

  PaginaSEI::getInstance()->verificarSelecao('atributo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if(isset($_GET['id_tipo_formulario'])){
    $strParametros .= '&id_tipo_formulario='.$_GET['id_tipo_formulario'];
  }

  $objAtributoDTO = new AtributoDTO();

  $strValoresAcoes = 'false, false';

  $strDesabilitar = '';
  $arrComandos = array();

  switch($_GET['acao']){
    case 'atributo_cadastrar':
    	
      $strTitulo = 'Novo Campo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarAtributo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objAtributoDTO->setNumIdAtributo(null);
      $objAtributoDTO->setNumIdTipoFormulario($_GET['id_tipo_formulario']);
      $objAtributoDTO->setStrNome($_POST['txtNome']);
      $objAtributoDTO->setNumOrdem($_POST['txtOrdem']);
      $objAtributoDTO->setStrRotulo($_POST['txaRotulo']);

      $strStaTipo = $_POST['selStaTipo'];
      if ($strStaTipo!==''){
        $objAtributoDTO->setStrStaTipo($_POST['selStaTipo']);
      }else{
        $objAtributoDTO->setStrStaTipo(null);
      }

      if ($strStaTipo==AtributoRN::$TA_DATA){
        $objAtributoDTO->setStrValorMinimo($_POST['txtDataInicial']);
        $objAtributoDTO->setStrValorMaximo($_POST['txtDataFinal']);
      }else{
        $objAtributoDTO->setStrValorMinimo($_POST['txtValorMinimo']);
        $objAtributoDTO->setStrValorMaximo($_POST['txtValorMaximo']);
      }

      if ($strStaTipo==AtributoRN::$TA_SINALIZADOR) {
        $objAtributoDTO->setStrValorPadrao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinValorPadrao']));
      }else{
        $objAtributoDTO->setStrValorPadrao(null);
      }

      $objAtributoDTO->setNumTamanho($_POST['txtTamanho']);
      $objAtributoDTO->setNumLinhas($_POST['txtLinhas']);
      $objAtributoDTO->setNumDecimais($_POST['txtDecimais']);
      $objAtributoDTO->setStrMascara($_POST['txtMascara']);
      
      $objAtributoDTO->setStrSinObrigatorio(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinObrigatorio']));
      $objAtributoDTO->setStrSinAtivo('S');

      if ($strStaTipo==AtributoRN::$TA_LISTA || $strStaTipo == AtributoRN::$TA_OPCOES) {
        $arrValores = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnValores']);
        $arrObjDominioDTO = array();
        foreach ($arrValores as $arrValor) {
          $objDominioDTO = new DominioDTO();
          $objDominioDTO->setNumIdDominio(null);
          $objDominioDTO->setStrValor($arrValor[1]);
          $objDominioDTO->setStrRotulo($arrValor[2]);
          $objDominioDTO->setNumOrdem($arrValor[3]);
          $objDominioDTO->setStrSinPadrao(AtributoINT::gravarSinalizadorDominio($arrValor[4]));
          $objDominioDTO->setStrSinAtivo('S');
          $arrObjDominioDTO[] = $objDominioDTO;
        }
        $objAtributoDTO->setArrObjDominioDTO($arrObjDominioDTO);
      }else{
        $objAtributoDTO->setArrObjDominioDTO(array());
      }

      $strValores = $_POST['hdnValores'];

      if (isset($_POST['sbmCadastrarAtributo'])) {
        try{
          $objAtributoRN = new AtributoRN();
          $objAtributoDTO = $objAtributoRN->cadastrarRN0113($objAtributoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Atributo "'.$objAtributoDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_atributo='.$objAtributoDTO->getNumIdAtributo().$strParametros).PaginaSEI::montarAncora($objAtributoDTO->getNumIdAtributo()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'atributo_alterar':
      $strTitulo = 'Alterar Campo';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarAtributo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_atributo'])){
        $objAtributoDTO->setNumIdAtributo($_GET['id_atributo']);
        $objAtributoDTO->retTodos();
        $objAtributoRN = new AtributoRN();
        $objAtributoDTO = $objAtributoRN->consultarRN0115($objAtributoDTO);

        if ($objAtributoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }

        $strValores = AtributoINT::montarItensTabelaValores($objAtributoDTO->getNumIdAtributo());

      } else {
        $objAtributoDTO->setNumIdAtributo($_POST['hdnIdAtributo']);
        $objAtributoDTO->setNumIdTipoFormulario($_GET['id_tipo_formulario']);
        $objAtributoDTO->setStrNome($_POST['txtNome']);
        $objAtributoDTO->setNumOrdem($_POST['txtOrdem']);
        $objAtributoDTO->setStrRotulo($_POST['txaRotulo']);
        $objAtributoDTO->setStrStaTipo($_POST['selStaTipo']);

        if ($_POST['selStaTipo']==AtributoRN::$TA_DATA){
          $objAtributoDTO->setStrValorMinimo($_POST['txtDataInicial']);
          $objAtributoDTO->setStrValorMaximo($_POST['txtDataFinal']);
        }else {
          $objAtributoDTO->setStrValorMinimo($_POST['txtValorMinimo']);
          $objAtributoDTO->setStrValorMaximo($_POST['txtValorMaximo']);
        }

        if ($_POST['selStaTipo']==AtributoRN::$TA_SINALIZADOR) {
          $objAtributoDTO->setStrValorPadrao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinValorPadrao']));
        }else{
          $objAtributoDTO->setStrValorPadrao(null);
        }

        $objAtributoDTO->setNumTamanho($_POST['txtTamanho']);
        $objAtributoDTO->setNumLinhas($_POST['txtLinhas']);
        $objAtributoDTO->setNumDecimais($_POST['txtDecimais']);
        $objAtributoDTO->setStrMascara($_POST['txtMascara']);
        $objAtributoDTO->setStrSinObrigatorio(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinObrigatorio']));
        //$objAtributoDTO->setStrSinAtivo('S');

        $arrValores = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnValores']);
        $arrObjDominioDTO = array();
        foreach ($arrValores as $arrValor) {
          $objDominioDTO = new DominioDTO();
          $objDominioDTO->setNumIdDominio((is_numeric($arrValor[0]) ? $arrValor[0] : null));
          $objDominioDTO->setStrValor($arrValor[1]);
          $objDominioDTO->setStrRotulo($arrValor[2]);
          $objDominioDTO->setNumOrdem($arrValor[3]);
          $objDominioDTO->setStrSinPadrao(AtributoINT::gravarSinalizadorDominio($arrValor[4]));
          $objDominioDTO->setStrSinAtivo(AtributoINT::gravarSinalizadorDominio($arrValor[5]));
          $arrObjDominioDTO[] = $objDominioDTO;
        }
        $objAtributoDTO->setArrObjDominioDTO($arrObjDominioDTO);

        $strValores = $_POST['hdnValores'];
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).PaginaSEI::montarAncora($objAtributoDTO->getNumIdAtributo()).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarAtributo'])) {
        try{
        	
          $objAtributoRN = new AtributoRN();
          $objAtributoRN->alterarRN0114($objAtributoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Atributo "'.$objAtributoDTO->getStrNome().'" alterado com sucesso.');
          
          if (isset($_GET['id_atributo'])){
		        $objAtributoDTO->setNumIdAtributo($_GET['id_atributo']);
		        $objAtributoDTO->retTodos();
		        $objAtributoRN = new AtributoRN();
		        $objAtributoDTO = $objAtributoRN->consultarRN0115($objAtributoDTO);

		        if ($objAtributoDTO==null){
		          throw new InfraException("Registro não encontrado.");
		        }
		      }
          
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).PaginaSEI::montarAncora($objAtributoDTO->getNumIdAtributo()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'atributo_consultar':
      $strTitulo = 'Consultar Campo';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).PaginaSEI::montarAncora($_GET['id_atributo']).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

      $objAtributoDTO->retTodos();
      $objAtributoDTO->setBolExclusaoLogica(false);
      $objAtributoDTO->setNumIdAtributo($_GET['id_atributo']);

      $objAtributoRN = new AtributoRN();
      $objAtributoDTO = $objAtributoRN->consultarRN0115($objAtributoDTO);

      if ($objAtributoDTO==null){
        throw new InfraException("Registro não encontrado.");
      }

      $strValores = AtributoINT::montarItensTabelaValores($objAtributoDTO->getNumIdAtributo());

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelStaTipo = AtributoINT::montarSelectAplicabilidade($objAtributoDTO->getStrStaTipo());

  $strCheckNenhuma = '';
  $strCheckAtualFuturo = '';
  $strCheckAtualPassado = '';
  $strCheckFuturo = '';
  $strCheckPassado = '';
  $strCheckIntervalo = '';

  if ($objAtributoDTO->getStrStaTipo()==AtributoRN::$TA_DATA){
    if ($objAtributoDTO->getStrValorMinimo()==null && $objAtributoDTO->getStrValorMaximo()==null) {
      $strCheckNenhuma = 'checked="checked"';
    }else if ($objAtributoDTO->getStrValorMinimo()=='@HOJE@' && $objAtributoDTO->getStrValorMaximo()=='@FUTURO@') {
      $strCheckAtualFuturo = 'checked="checked"';
    }else if ($objAtributoDTO->getStrValorMinimo()=='@PASSADO@' && $objAtributoDTO->getStrValorMaximo()=='@HOJE@') {
      $strCheckAtualPassado = 'checked="checked"';
    }else if ($objAtributoDTO->getStrValorMinimo()=='@AMANHA@' && $objAtributoDTO->getStrValorMaximo()=='@FUTURO@') {
      $strCheckFuturo = 'checked="checked"';
    }else if ($objAtributoDTO->getStrValorMinimo()=='@PASSADO@' && $objAtributoDTO->getStrValorMaximo()=='@ONTEM@') {
      $strCheckPassado = 'checked="checked"';
    }else {
      $strCheckIntervalo = 'checked="checked"';
    }
  }

  if ($_GET['acao']!='atributo_consultar'){
    $strValoresAcoes = 'true, true';
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
#divGeral {}
#lblNome {position:absolute;left:0%;top:0%;width:44%;}
#txtNome {position:absolute;left:0%;top:9%;width:44%;}

#lblOrdem {position:absolute;left:45%;top:0%;width:5%;}
#txtOrdem {position:absolute;left:45%;top:9%;width:5%;}

#lblRotulo {position:absolute;left:0%;top:25%;width:70%;}
#txaRotulo {position:absolute;left:0%;top:34%;width:70%;}

#lblStaTipo {position:absolute;left:0%;top:75%;width:50%;}
#selStaTipo {position:absolute;left:0%;top:84%;width:25%;}

#divSinObrigatorio {position:absolute;left:30%;top:84%;}
#divSinValorPadrao {position:absolute;left:30%;top:84%;visibility:hidden;};

#divTamanho {display:none;}
#lblTamanho {position:absolute;left:0%;top:0%;width:15%;}
#txtTamanho {position:absolute;left:0%;top:40%;width:15%;}

#divLinhas {display:none;}
#lblLinhas {position:absolute;left:0%;top:0%;width:15%;}
#txtLinhas {position:absolute;left:0%;top:40%;width:15%;}

#divDecimais {display:none;}
#lblDecimais {position:absolute;left:0%;top:0%;width:15%;}
#txtDecimais {position:absolute;left:0%;top:40%;width:15%;}

#divMascara {display:none;}
#lblMascara {position:absolute;left:0%;top:0%;width:50%;}
#txtMascara {position:absolute;left:0%;top:40%;width:50%;}
#ancAjudaMascara {position:absolute;left:52%;top:40%}

#divMinMax {display:none;}
#lblValorMinimo {position:absolute;left:0%;top:0%;width:18%;}
#txtValorMinimo {position:absolute;left:0%;top:40%;width:18%;}

#lblValorMaximo {position:absolute;left:20%;top:0%;width:18%;}
#txtValorMaximo {position:absolute;left:20%;top:40%;width:18%;}

#divData {display:none;}
#fldData {width:50%;height:85%}
#divOptDataNenhuma  {position:absolute;left:5%;top:20%;}
#divOptDataAtualFuturo  {position:absolute;left:5%;top:30%;}
#divOptDataAtualPassado  {position:absolute;left:5%;top:40%;}
#divOptDataFuturo  {position:absolute;left:5%;top:50%;}
#divOptDataPassado  {position:absolute;left:5%;top:60%;}
#divOptDataIntervalo  {position:absolute;left:5%;top:70%;}

#lblDataInicial {position:absolute;left:20%;top:60%;width:15%;visibility:hidden;}
#txtDataInicial {position:absolute;left:20%;top:70%;width:10%;visibility:hidden;}

#lblDataFinal {position:absolute;left:35%;top:60%;width:15%;visibility:hidden;}
#txtDataFinal {position:absolute;left:35%;top:70%;width:10%;visibility:hidden;}

#divValorCadastro {display:none;}
#lblValor {position:absolute;left:0%;top:0%;width:19%;}
#txtValor {position:absolute;left:0%;top:40%;width:19%;}

#lblRotuloValor {position:absolute;left:20%;top:0%;width:30%;}
#txtRotuloValor {position:absolute;left:20%;top:40%;width:30%;}

#lblOrdemValor {position:absolute;left:51%;top:0%;width:11%;}
#txtOrdemValor {position:absolute;left:51%;top:40%;width:11%;}

#divSinPadraoValor {position:absolute;left:65%;top:40%;width:12%;}

  #divSinAtivoValor {position:absolute;left:77%;top:40%;width:12%;}

#btnAtualizarValor {position:absolute;left:87%;top:40%;width:10%;}

#divValorTabela {display:none;}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

//<script>

var objTabelaValores = null;

function inicializar(){

  if ('<?=$_GET['acao']?>'=='atributo_cadastrar'){
    document.getElementById('txtNome').focus();
  } else if ('<?=$_GET['acao']?>'=='atributo_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }

  objTabelaValores = new infraTabelaDinamica('tblValores','hdnValores', <?=$strValoresAcoes?>);
  objTabelaValores.alterar = function(arr){
    document.getElementById('hdnIdValor').value = arr[0];
    document.getElementById('txtValor').value = arr[1];
    document.getElementById('txtRotuloValor').value = arr[2];
    document.getElementById('txtOrdemValor').value = arr[3];

    if (arr[4]=='S'){
      document.getElementById('chkSinPadraoValor').checked = true;
    }else{
      document.getElementById('chkSinPadraoValor').checked = false;
    }

    if (arr[5]=='S'){
      document.getElementById('chkSinAtivoValor').checked = true;
    }else{
      document.getElementById('chkSinAtivoValor').checked = false;
    }

  };
  objTabelaValores.gerarEfeitoTabela = true;
  objTabelaValores.inserirNoInicio = false;

  selecionarTipo(true);
}

function atualizarValor(){

  if (infraTrim(document.getElementById('txtValor').value)=='') {
    alert('Informe o Valor.');
    document.getElementById('txtValor').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtRotuloValor').value)=='') {
    alert('Informe o Rótulo do Valor.');
    document.getElementById('txtRotuloValor').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtOrdemValor').value)=='') {
    alert('Informe a Ordem do Valor.');
    document.getElementById('txtOrdemValor').focus();
    return false;
  }

  id = ((document.getElementById('hdnIdValor').value!='') ? document.getElementById('hdnIdValor').value : 'NOVO' + (new Date()).getTime());

  var txtValor = document.getElementById('txtValor').value;
  var txtRotuloValor = document.getElementById('txtRotuloValor').value;
  var txtOrdemValor = document.getElementById('txtOrdemValor').value;
  var sinPadraoValor = (document.getElementById('chkSinPadraoValor').checked?'S':'');
  var sinAtivoValor = (document.getElementById('chkSinAtivoValor').checked?'S':'');

  objTabelaValores.adicionar([id, txtValor, txtRotuloValor, txtOrdemValor, sinPadraoValor, sinAtivoValor]);

  document.getElementById('hdnIdValor').value = '';
  document.getElementById('txtValor').value = '';
  document.getElementById('txtRotuloValor').value = '';
  document.getElementById('txtOrdemValor').value = '';
  document.getElementById('chkSinPadraoValor').checked = false;
  document.getElementById('chkSinAtivoValor').checked = false;
  document.getElementById('txtValor').focus();
}

function OnSubmitForm() {
  return validarCadastroRI0593();
}

function validarCadastroRI0593() {
  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txaRotulo').value)=='') {
    alert('Informe o Rótulo.');
    document.getElementById('txaRotulo').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtOrdem').value)=='') {
    alert('Informe a Ordem.');
    document.getElementById('txtOrdem').focus();
    return false;
  }

  if (!infraSelectSelecionado('selStaTipo')) {
    alert('Selecione o Tipo.');
    document.getElementById('selStaTipo').focus();
    return false;
  }

  if (document.getElementById('selStaTipo').value=='<?=AtributoRN::$TA_DATA?>' && document.getElementById('optDataIntervalo').checked){

    if (infraTrim(document.getElementById('txtDataInicial').value)==''){
      alert('Informe a Data Inicial.');
      document.getElementById('txtDataInicial').focus();
      return false;
    }

    if (!infraValidarData(document.getElementById('txtDataInicial'))){
      document.getElementById('txtDataInicial').focus();
      return false;
    }

    if (infraTrim(document.getElementById('txtDataFinal').value)==''){
      alert('Informe a Data Final.');
      document.getElementById('txtDataFinal').focus();
      return false;
    }

    if (!infraValidarData(document.getElementById('txtDataFinal'))){
      document.getElementById('txtDataFinal').focus();
      return false;
    }

    if (infraCompararDatas(document.getElementById('txtDataInicial').value,document.getElementById('txtDataFinal').value) < 0){
      alert('Intervalo de datas inválido.');
      document.getElementById('txtDataInicial').focus();
      return false;
    }
  }

  return true;
}

function selecionarTipo(inicializando){
  var tipo = document.getElementById('selStaTipo').value;
  var divTamanho = document.getElementById('divTamanho');
  var divLinhas = document.getElementById('divLinhas');
  var divDecimais = document.getElementById('divDecimais');
  var divMascara = document.getElementById('divMascara');
  var divMinMax = document.getElementById('divMinMax');
  var divData = document.getElementById('divData');
  var divValorCadastro = document.getElementById('divValorCadastro');
  var divValorTabela = document.getElementById('divValorTabela');
  var divSinObrigatorio = document.getElementById('divSinObrigatorio');
  var divSinValorPadrao = document.getElementById('divSinValorPadrao');

  var txtTamanho = document.getElementById('txtTamanho');
  var txtLinhas = document.getElementById('txtLinhas');
  var txtDecimais = document.getElementById('txtDecimais');
  var txtMascara = document.getElementById('txtMascara');
  var txtValorMinimo = document.getElementById('txtValorMinimo');
  var txtValorMaximo = document.getElementById('txtValorMaximo');
  var chkSinObrigatorio = document.getElementById('chkSinObrigatorio');

  if (!inicializando){
    txtTamanho.value = '';
    txtLinhas.value = '';
    txtDecimais.value = '';
    txtMascara.value = '';
    txtValorMinimo.value = '';
    txtValorMaximo.value = '';
    chkSinObrigatorio.checked = false;
    divSinObrigatorio.style.visibility = 'visible';
    divSinValorPadrao.style.visibility = 'hidden';
  }

  divTamanho.style.display = 'none';
  divLinhas.style.display = 'none';
  divDecimais.style.display = 'none';
  divMascara.style.display = 'none';
  divMinMax.style.display = 'none';
  divData.style.display = 'none';
  divValorCadastro.style.display = 'none';
  divValorTabela.style.display = 'none';

  if (tipo!='null') {

    switch (tipo) {

      case '<?=AtributoRN::$TA_DATA?>':
        divData.style.display = 'block';
        tratarValidacaoData(inicializando);
        break;

      case '<?=AtributoRN::$TA_LISTA?>':
      case '<?=AtributoRN::$TA_OPCOES?>':
        divValorCadastro.style.display = ('<?=$_GET['acao']?>'!='atributo_consultar') ? 'block' : 'none';
        divValorTabela.style.display = 'block';
        break;

      case '<?=AtributoRN::$TA_NUMERO_INTEIRO?>':
        divTamanho.style.display = 'block';
        txtTamanho.onkeypress = mascaraTamanhoNumero;
        divMinMax.style.display = 'block';
        txtValorMinimo.onkeypress = mascaraMinimoNumero;
        txtValorMaximo.onkeypress = mascaraMaximoNumero;
        break;

      case '<?=AtributoRN::$TA_NUMERO_DECIMAL?>':
        divTamanho.style.display = 'block';
        txtTamanho.onkeypress = mascaraTamanhoNumero;
        divDecimais.style.display = 'block';
        divMinMax.style.display = 'block';
        txtValorMinimo.onkeypress = mascaraMinimoDecimal;
        txtValorMaximo.onkeypress = mascaraMaximoDecimal;
        break;

      case '<?=AtributoRN::$TA_DINHEIRO?>':
        divMinMax.style.display = 'block';
        txtValorMinimo.onkeypress = mascaraMinimoDinheiro;
        txtValorMaximo.onkeypress = mascaraMaximoDinheiro;
        break;

      case '<?=AtributoRN::$TA_TEXTO_SIMPLES?>':
        divTamanho.style.display = 'block';
        txtTamanho.onkeypress = mascaraTamanhoTexto;
        break;

      case '<?=AtributoRN::$TA_TEXTO_GRANDE?>':
        divTamanho.style.display = 'block';
        txtTamanho.onkeypress = mascaraTamanhoTexto;
        divLinhas.style.display = 'block';
        break;

      case '<?=AtributoRN::$TA_TEXTO_MASCARA?>':
        divMascara.style.display = 'block';
        break;

      case '<?=AtributoRN::$TA_SINALIZADOR?>':
        divSinObrigatorio.style.visibility = 'hidden';
        divSinValorPadrao.style.visibility = 'visible';
        break;

      case '<?=AtributoRN::$TA_INFORMACAO?>':
        divSinObrigatorio.style.visibility = 'hidden';
        break;

      default:
        alert('Tipo do campo não mapeado para visualização.');
    }
  }
}

function mascaraMinimoDinheiro(event){
  return infraMascaraDinheiro(document.getElementById('txtValorMinimo'), event, 2, 12);
}

function mascaraMaximoDinheiro(event){
  return infraMascaraDinheiro(document.getElementById('txtValorMaximo'), event, 2, 12);
}

function mascaraMinimoNumero(event){
  return infraMascaraNumero(document.getElementById('txtValorMinimo'), event, 19);
}

function mascaraMaximoNumero(event){
  return infraMascaraNumero(document.getElementById('txtValorMaximo'), event, 19);
}

function mascaraTamanhoTexto(event){
  return infraMascaraNumero(this,event,4);
}

function mascaraTamanhoNumero(event){
  return infraMascaraNumero(this,event,2);
}

///
function mascaraMinimoDecimal(event){
  return mascaraDecimal(document.getElementById('txtValorMinimo'), event);
}

function mascaraMaximoDecimal(event){
  return mascaraDecimal(document.getElementById('txtValorMaximo'), event);
}

function mascaraDecimal(obj, event) {

  var total = infraTrim(document.getElementById('txtTamanho').value);
  var decimais = infraTrim(document.getElementById('txtDecimais').value);

  if (total=='' || Number(total)==0) {
    alert('Informe o tamanho.');
    document.getElementById('txtTamanho').focus();
    return false;
  }

  if (decimais=='') {
    alert('Informe os decimais.');
    document.getElementById('txtDecimais').focus();
    return false;
  }

  if (Number(decimais)>Number(total)) {
    alert('O número de decimais é maior que o tamanho.');
    document.getElementById('txtTamanho').focus();
    return false;
  }

  if (Number(total)>19) {
    alert('O tamanho não pode ser superior a 19.');
    document.getElementById('txtTamanho').focus();
    return false;
  }

  if (Number(decimais)>18) {
    alert('Decimais não pode ser superior a 18.');
    document.getElementById('txtDecimais').focus();
    return false;
  }

  return infraMascaraDecimais(obj, '', ',', event, Number(decimais), Number(total));
}

function tratarValidacaoData(inicializando){

  if (!inicializando) {
    document.getElementById('txtDataInicial').value = '';
    document.getElementById('txtDataFinal').value = '';
  }

  if (document.getElementById('optDataIntervalo').checked){
    document.getElementById('lblDataInicial').style.visibility = 'visible';
    document.getElementById('txtDataInicial').style.visibility = 'visible';
    document.getElementById('lblDataFinal').style.visibility = 'visible';
    document.getElementById('txtDataFinal').style.visibility = 'visible';
  }else {
    document.getElementById('lblDataInicial').style.visibility = 'hidden';
    document.getElementById('txtDataInicial').style.visibility = 'hidden';
    document.getElementById('lblDataFinal').style.visibility = 'hidden';
    document.getElementById('txtDataFinal').style.visibility = 'hidden';

    if (document.getElementById('optDataAtualFuturo').checked){
      document.getElementById('txtDataInicial').value = '@HOJE@';
      document.getElementById('txtDataFinal').value = '@FUTURO@';
    }else if (document.getElementById('optDataAtualPassado').checked){
      document.getElementById('txtDataInicial').value = '@PASSADO@';
      document.getElementById('txtDataFinal').value = '@HOJE@';
    }else if (document.getElementById('optDataFuturo').checked){
      document.getElementById('txtDataInicial').value = '@AMANHA@';
      document.getElementById('txtDataFinal').value = '@FUTURO@';
    }else if (document.getElementById('optDataPassado').checked){
      document.getElementById('txtDataInicial').value = '@PASSADO@';
      document.getElementById('txtDataFinal').value = '@ONTEM@';
    }
  }
}

function exibirAjudaMascara(){
  alert('<?=PaginaSEI::getInstance()->formatarParametrosJavaScript('Caracteres disponíveis para montagem da máscara:'."\n\n".'# - número'."\n".'A - letra maiúscula'."\n".'a - letra minúscula'."\n".'L - letras maiúsculas ou minúsculas')?>');
}

//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAtributoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divGeral" class="infraAreaDados" style="height:20em;">
    <label id="lblNome" for="txtNome" accesskey="N" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">N</span>ome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objAtributoDTO->getStrNome())?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblOrdem" for="txtOrdem" accesskey="o" class="infraLabelObrigatorio"><span class="infraTeclaAtalho">O</span>rdem:</label>
    <input type="text" id="txtOrdem" name="txtOrdem" onkeypress="return infraMascaraNumero(this, event, 5)" class="infraText" value="<?=PaginaSEI::tratarHTML($objAtributoDTO->getNumOrdem())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblRotulo" for="txaRotulo" class="infraLabelObrigatorio">Rótulo:</label>
    <textarea id="txaRotulo" name="txaRotulo" rows="3" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,4000);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objAtributoDTO->getStrRotulo())?></textarea>

    <label id="lblStaTipo" for="selStaTipo" class="infraLabelObrigatorio">Tipo:</label>
    <select id="selStaTipo" name="selStaTipo" class="infraSelect" onchange="selecionarTipo(false)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"  >
    <?=$strItensSelStaTipo?>
    </select>

    <div id="divSinObrigatorio" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinObrigatorio" name="chkSinObrigatorio" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objAtributoDTO->getStrSinObrigatorio())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinObrigatorio" for="chkSinObrigatorio" class="infraLabelCheckbox">Obrigatório</label>
    </div>

    <div id="divSinValorPadrao" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinValorPadrao" name="chkSinValorPadrao" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objAtributoDTO->getStrValorPadrao())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinValorPadrao" for="chkSinValorPadrao" class="infraLabelCheckbox">Exibir marcado como padrão</label>
    </div>
    
  </div>

  <div id="divTamanho" class="infraAreaDados" style="height:5em;">
    <label id="lblTamanho" for="txtTamanho" class="infraLabelObrigatorio">Tamanho:</label>
    <input type="text" id="txtTamanho" name="txtTamanho" class="infraText" value="<?=PaginaSEI::tratarHTML($objAtributoDTO->getNumTamanho())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>

  <div id="divLinhas" class="infraAreaDados" style="height:5em;">
    <label id="lblLinhas" for="txtLinhas" class="infraLabelObrigatorio">Linhas:</label>
    <input type="text" id="txtLinhas" name="txtLinhas" class="infraText" value="<?=PaginaSEI::tratarHTML($objAtributoDTO->getNumLinhas())?>" onkeypress="return infraMascaraNumero(this,event);" maxlength="3" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>

  <div id="divDecimais" class="infraAreaDados" style="height:5em;">
    <label id="lblDecimais" for="txtDecimais" class="infraLabelObrigatorio">Decimais:</label>
    <input type="text" id="txtDecimais" name="txtDecimais" class="infraText" value="<?=PaginaSEI::tratarHTML($objAtributoDTO->getNumDecimais())?>" onkeypress="return infraMascaraNumero(this,event);" maxlength="2" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>

  <div id="divMascara" class="infraAreaDados" style="height:5em;">
    <label id="lblMascara" for="txtMascara" class="infraLabelObrigatorio">Máscara:</label>
    <input type="text" id="txtMascara" name="txtMascara" class="infraText" value="<?=PaginaSEI::tratarHTML($objAtributoDTO->getStrMascara())?>" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <a id="ancAjudaMascara" onclick="exibirAjudaMascara();" title="Ajuda" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><img src="<?=PaginaSEI::getInstance()->getIconeAjuda()?>" class="infraImg"/></a>
  </div>

  <div id="divMinMax" class="infraAreaDados" style="height:5em;">
    <label id="lblValorMinimo" for="txtValorMinimo" class="infraLabelOpcional">Valor Mínimo:</label>
    <input type="text" id="txtValorMinimo" name="txtValorMinimo" class="infraText" value="<?=PaginaSEI::tratarHTML($objAtributoDTO->getStrValorMinimo())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblValorMaximo" for="txtValorMaximo" class="infraLabelOpcional">Valor Máximo:</label>
    <input type="text" id="txtValorMaximo" name="txtValorMaximo" class="infraText" value="<?=PaginaSEI::tratarHTML($objAtributoDTO->getStrValorMaximo())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  </div>

  <div id="divData" class="infraAreaDados" style="height:20em;">

    <fieldset id="fldData" class="infraFieldset">
      <legend class="infraLegend">Validação</legend>

      <div id="divOptDataNenhuma" class="infraDivRadio">
        <input type="radio" name="rdoValidacaoData" id="optDataNenhuma" value="N" <?=$strCheckNenhuma?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
        <label for="optDataNenhuma" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Nenhuma</label>
      </div>

      <div id="divOptDataAtualFuturo" class="infraDivRadio">
        <input type="radio" name="rdoValidacaoData" id="optDataAtualFuturo" value="F" <?=$strCheckAtualFuturo?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
        <label for="optDataAtualFuturo" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Data atual ou futuro</label>
      </div>

      <div id="divOptDataAtualPassado" class="infraDivRadio">
        <input type="radio" name="rdoValidacaoData" id="optDataAtualPassado" value="P" <?=$strCheckAtualPassado?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
        <label for="optDataAtualPassado" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Data atual ou passado</label>
      </div>

      <div id="divOptDataFuturo" class="infraDivRadio">
        <input type="radio" name="rdoValidacaoData" id="optDataFuturo" value="F" <?=$strCheckFuturo?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
        <label for="optDataFuturo" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Futuro</label>
      </div>

      <div id="divOptDataPassado" class="infraDivRadio">
        <input type="radio" name="rdoValidacaoData" id="optDataPassado" value="P" <?=$strCheckPassado?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
        <label for="optDataPassado" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Passado</label>
      </div>

      <div id="divOptDataIntervalo" class="infraDivRadio">
        <input type="radio" name="rdoValidacaoData" id="optDataIntervalo" value="I" <?=$strCheckIntervalo?> onclick="tratarValidacaoData(false)" class="infraRadio"/>
        <label for="optDataIntervalo" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Intervalo</label>
      </div>

      <label id="lblDataInicial" for="txtDataInicial" class="infraLabelObrigatorio">Data Inicial:</label>
      <input type="text" id="txtDataInicial" name="txtDataInicial" class="infraText" value="<?=PaginaSEI::tratarHTML($objAtributoDTO->getStrValorMinimo())?>" width="10" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <label id="lblDataFinal" for="txtDataFinal" class="infraLabelObrigatorio">Data Final:</label>
      <input type="text" id="txtDataFinal" name="txtDataFinal" class="infraText" value="<?=PaginaSEI::tratarHTML($objAtributoDTO->getStrValorMaximo())?>" width="10" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    </fieldset>
  </div>

  <div id="divValorCadastro" class="infraAreaDados" style="height:5em;">

    <label id="lblValor" for="txtValor" class="infraLabelObrigatorio">Valor:</label>
    <input type="text" id="txtValor" name="txtValor" class="infraText" value="" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblRotuloValor" for="txaRotuloValor" class="infraLabelObrigatorio">Rótulo do Valor:</label>
    <input type="text" id="txtRotuloValor" name="txtRotuloValor" class="infraText" value="" onkeypress="return infraLimitarTexto(this,event,100);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblOrdemValor" for="txtOrdemValor" class="infraLabelObrigatorio">Ordem do Valor:</label>
    <input type="text" id="txtOrdemValor" name="txtOrdemValor" onkeypress="return infraMascaraNumero(this, event)" class="infraText" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <div id="divSinPadraoValor" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinPadraoValor" name="chkSinPadraoValor" class="infraCheckbox"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPadraoValor" for="chkSinPadraoValor" class="infraLabelCheckbox">Padrão</label>
    </div>
    
    <div id="divSinAtivoValor" class="infraDivCheckbox">
      <input type="checkbox" id="chkSinAtivoValor" name="chkSinAtivoValor" class="infraCheckbox"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinAtivoValor" for="chkSinAtivoValor" class="infraLabelCheckbox">Ativo</label>
    </div>

    <input type="button" id="btnAtualizarValor" name="btnAtualizarValor"  class="infraButton" value="Atualizar" onclick="atualizarValor();" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
  </div>

  <div id="divValorTabela" class="infraAreaTabela">

    <table  id="tblValores" name="tblValores" width="99%" class="infraTable">
      <tr>
        <th style="display:none;">ID</th>
        <th class="infraTh" width="20%" align="center">Valor</th>
        <th class="infraTh" align="left">Rótulo</th>
        <th class="infraTh" width="12%" align="center">Ordem</th>
        <th class="infraTh" width="12%" align="center">Padrão</th>
        <th class="infraTh" width="12%" align="center">Ativo</th>
        <th class="infraTh" width="12%">Ações</th>
      </tr>
    </table>

    <input type="hidden" id="hdnIdValor" name="hdnIdValor" value=""/>
    <input type="hidden" id="hdnValores" name="hdnValores" value="<?=$strValores;?>"/>

  </div>

  <input type="hidden" id="hdnIdAtributo" name="hdnIdAtributo" value="<?=$objAtributoDTO->getNumIdAtributo();?>" />

  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>