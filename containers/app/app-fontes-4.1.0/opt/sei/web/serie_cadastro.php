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
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('serie_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selGrupoSerie', 'selStaAplicabilidade', 'selModelo', 'selTipoFormulario'));

  $objSerieDTO = new SerieDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'serie_cadastrar':
      $strTitulo = 'Novo Tipo de Documento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarSerie" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objSerieDTO->setNumIdSerie(null);
      $numIdGrupoSerie = PaginaSEI::getInstance()->recuperarCampo('selGrupoSerie');
      if ($numIdGrupoSerie!==''){
        $objSerieDTO->setNumIdGrupoSerie($numIdGrupoSerie);
      }else{
        $objSerieDTO->setNumIdGrupoSerie(null);
      }

      $strStaAplicabilidade = PaginaSEI::getInstance()->recuperarCampo('selStaAplicabilidade');
      if ($strStaAplicabilidade!==''){
      	$objSerieDTO->setStrStaAplicabilidade($strStaAplicabilidade);
      }else{
      	$objSerieDTO->setStrStaAplicabilidade(null);
      }
      
      $numIdModelo = PaginaSEI::getInstance()->recuperarCampo('selModelo');
      if ($numIdModelo!==''){
        $objSerieDTO->setNumIdModelo($numIdModelo);
      }else{
        $objSerieDTO->setNumIdModelo(null);
      }
      
      $objSerieDTO->setNumIdModeloEdoc(null);

      $numIdTipoFormulario = PaginaSEI::getInstance()->recuperarCampo('selTipoFormulario');
      if ($numIdTipoFormulario!==''){
        $objSerieDTO->setNumIdTipoFormulario($numIdTipoFormulario);
      }else{
        $objSerieDTO->setNumIdTipoFormulario(null);
      }
      
      if (isset($_POST['selStaNumeracao'])){
        $strStaNumeracao = $_POST['selStaNumeracao'];
      }else{
        $strStaNumeracao = SerieRN::$TN_SEM_NUMERACAO;  
      }
      $objSerieDTO->setStrStaNumeracao($strStaNumeracao);
      $objSerieDTO->setStrNome($_POST['txtNome']);
      $objSerieDTO->setStrDescricao($_POST['txaDescricao']);
      
      if (isset($_POST['chkSinAssinaturaPublicacao'])){
        $strSinAssinaturaPublicacao = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAssinaturaPublicacao']);
      }else{
        $strSinAssinaturaPublicacao = 'S';
      }
      
      $objSerieDTO->setStrSinAssinaturaPublicacao($strSinAssinaturaPublicacao);
      
      $objSerieDTO->setStrSinInteressado(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInteressado']));
      $objSerieDTO->setStrSinDestinatario(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinDestinatario']));
      $objSerieDTO->setStrSinValorMonetario(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinValorMonetario']));
      $objSerieDTO->setStrSinInterno(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInterno']));
      $objSerieDTO->setStrSinUsuarioExterno(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinUsuarioExterno']));
      $objSerieDTO->setStrSinAtivo('S');

      
      $arrObjRelSerieAssuntoDTO = array();
      $arrAssuntos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnAssuntos']);
      for($x = 0;$x<count($arrAssuntos);$x++){
        $objRelSerieAssuntoDTO = new RelSerieAssuntoDTO();
        $objRelSerieAssuntoDTO->setNumIdAssunto($arrAssuntos[$x]);
        $objRelSerieAssuntoDTO->setNumSequencia($x);
        $arrObjRelSerieAssuntoDTO[] = $objRelSerieAssuntoDTO;
      }
      $objSerieDTO->setArrObjRelSerieAssuntoDTO($arrObjRelSerieAssuntoDTO);

      $objSerieDTO->setArrObjSerieRestricaoDTO(OrgaoINT::processarRestricaoOrgaoUnidade('SerieRestricaoDTO'));
      
      $arrObjRelSerieVeiculoPublicacaoDTO = array();
      $arrVeiculosPublicacao = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnVeiculosPublicacao']);
      foreach($arrVeiculosPublicacao as $numIdVeiculoPublicacao){
        $objRelSerieVeiculoPublicacaoDTO = new RelSerieVeiculoPublicacaoDTO();
        $objRelSerieVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($numIdVeiculoPublicacao);
        $arrObjRelSerieVeiculoPublicacaoDTO[] = $objRelSerieVeiculoPublicacaoDTO;
      }
      $objSerieDTO->setArrObjRelSerieVeiculoPublicacaoDTO($arrObjRelSerieVeiculoPublicacaoDTO);
       

      if (isset($_POST['sbmCadastrarSerie'])) {
        try{
          $objSerieRN = new SerieRN();
          $objSerieDTO = $objSerieRN->cadastrarRN0642($objSerieDTO);
          PaginaSEI::getInstance()->setStrMensagem('Tipo de Documento "'.$objSerieDTO->getStrNome().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_serie='.$objSerieDTO->getNumIdSerie().'#ID-'.$objSerieDTO->getNumIdSerie()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'serie_alterar':
      $strTitulo = 'Alterar Tipo de Documento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarSerie" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_serie'])){
        $objSerieDTO->setNumIdSerie($_GET['id_serie']);
        $objSerieDTO->retTodos();
        $objSerieRN = new SerieRN();
        $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);
        if ($objSerieDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
        
        
      } else {
        $objSerieDTO->setNumIdSerie($_POST['hdnIdSerie']);
        $objSerieDTO->setNumIdGrupoSerie($_POST['selGrupoSerie']);
        $objSerieDTO->setStrStaAplicabilidade($_POST['selStaAplicabilidade']);
        $objSerieDTO->setNumIdModelo($_POST['selModelo']);
        $objSerieDTO->setNumIdTipoFormulario($_POST['selTipoFormulario']);
        $objSerieDTO->setStrStaNumeracao($_POST['selStaNumeracao']);
        $objSerieDTO->setStrNome($_POST['txtNome']);
        $objSerieDTO->setStrDescricao($_POST['txaDescricao']);
        
        
        $objSerieDTO->setStrSinAssinaturaPublicacao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAssinaturaPublicacao']));
        $objSerieDTO->setStrSinInteressado(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInteressado']));
        $objSerieDTO->setStrSinDestinatario(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinDestinatario']));
        $objSerieDTO->setStrSinValorMonetario(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinValorMonetario']));
        $objSerieDTO->setStrSinInterno(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinInterno']));
        $objSerieDTO->setStrSinUsuarioExterno(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinUsuarioExterno']));
        $objSerieDTO->setStrSinAtivo('S');
        
        
        $arrObjRelSerieAssuntoDTO = array();
        $arrAssuntos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnAssuntos']);
        for($x = 0;$x<count($arrAssuntos);$x++){
          $objRelSerieAssuntoDTO = new RelSerieAssuntoDTO();
          $objRelSerieAssuntoDTO->setNumIdAssunto($arrAssuntos[$x]);
          $objRelSerieAssuntoDTO->setNumSequencia($x);
          $arrObjRelSerieAssuntoDTO[] = $objRelSerieAssuntoDTO;
        }
        $objSerieDTO->setArrObjRelSerieAssuntoDTO($arrObjRelSerieAssuntoDTO);

        $objSerieDTO->setArrObjSerieRestricaoDTO(OrgaoINT::processarRestricaoOrgaoUnidade('SerieRestricaoDTO'));

        $arrObjRelSerieVeiculoPublicacaoDTO = array();
        $arrVeiculosPublicacao = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnVeiculosPublicacao']);
        foreach($arrVeiculosPublicacao as $numIdVeiculoPublicacao){
          $objRelSerieVeiculoPublicacaoDTO = new RelSerieVeiculoPublicacaoDTO();
          $objRelSerieVeiculoPublicacaoDTO->setNumIdVeiculoPublicacao($numIdVeiculoPublicacao);
          $arrObjRelSerieVeiculoPublicacaoDTO[] = $objRelSerieVeiculoPublicacaoDTO;
        }
        $objSerieDTO->setArrObjRelSerieVeiculoPublicacaoDTO($arrObjRelSerieVeiculoPublicacaoDTO);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objSerieDTO->getNumIdSerie().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarSerie'])) {
        try{
          $objSerieRN = new SerieRN();
          $objSerieRN->alterarRN0643($objSerieDTO);
          PaginaSEI::getInstance()->setStrMensagem('Tipo de Documento "'.$objSerieDTO->getStrNome().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'#ID-'.$objSerieDTO->getNumIdSerie()));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'serie_consultar':
      $strTitulo = "Consultar Tipo de Documento";
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$_GET['id_serie'].'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objSerieDTO->setNumIdSerie($_GET['id_serie']);
      $objSerieDTO->retTodos();
      $objSerieRN = new SerieRN();
      $objSerieDTO = $objSerieRN->consultarRN0644($objSerieDTO);
      if ($objSerieDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelGrupoSerie = GrupoSerieINT::montarSelectNomeRI0801('null','&nbsp;',$objSerieDTO->getNumIdGrupoSerie());
  $strItensSelStaAplicabilidade = SerieINT::montarSelectStaAplicabilidade('null','&nbsp;',$objSerieDTO->getStrStaAplicabilidade());
  $strItensSelModelo = ModeloINT::montarSelectNome('null','&nbsp;',$objSerieDTO->getNumIdModelo());
  $strItensSelTipoFormulario = TipoFormularioINT::montarSelectNome('null','&nbsp;',$objSerieDTO->getNumIdTipoFormulario());
  $strLinkVeiculoPublicacaoSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=veiculo_publicacao_selecionar&id_veiculo_publicacao_atual='.$_GET['id_veiculo_publicacao'].'&tipo_selecao=2&id_object=objLupaVeiculoPublicacao');
  $strItensSelAssuntos = RelSerieAssuntoINT::conjuntoPorCodigo(null,null,null,$objSerieDTO->getNumIdSerie());
  $strItensSelVeiculoPublicacao = RelSerieVeiculoPublicacaoINT::montarSelectIdVeiculoPublicacao(null,null,null,$_GET['id_serie']);
  $strItensSelStaNumeracao = SerieINT::montarSelectStaNumeracaoRI0797('null','&nbsp;',$objSerieDTO->getStrStaNumeracao());
  
  $strLinkAssuntosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_selecionar&tipo_selecao=2&id_object=objLupaAssuntos');
  $strLinkAjaxAssuntoRI1223 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assunto_auto_completar_RI1223');

  OrgaoINT::montarRestricaoOrgaoUnidade(null, $objSerieDTO->getNumIdSerie(), $strCssRestricao, $strHtmlRestricao, $strJsGlobalRestricao, $strJsInicializarRestricao);

  $arrObjSinalizacaoDTO = InfraArray::indexarArrInfraDTO(SerieRN::listarValoresSinalizacao(),'StaSinalizacao');

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
#lblGrupoSerie {position:absolute;left:0%;top:0%;}
#selGrupoSerie {position:absolute;left:0%;top:40%;width:20%;}

#lblNome {position:absolute;left:0%;top:0%;}
#txtNome {position:absolute;left:0%;top:40%;width:50%;}

#lblDescricao {position:absolute;left:0%;top:0%;width:80%;}
#txaDescricao {position:absolute;left:0%;top:30%;width:80%;}

#lblStaAplicabilidade {position:absolute;left:0%;top:0%;}
#selStaAplicabilidade {position:absolute;left:0%;top:40%;width:50%;}

#divModelo {display:none}
#lblModelo {position:absolute;left:0%;top:0%;}
#selModelo {position:absolute;left:0%;top:40%;width:50%;}

#divTipoFormulario {display:none}
#lblTipoFormulario {position:absolute;left:0%;top:0%;}
#selTipoFormulario {position:absolute;left:0%;top:40%;width:50%;}

#divNumeracao {display:none}
#lblStaNumeracao {position:absolute;left:0%;top:0%;}
#selStaNumeracao {position:absolute;left:0%;top:40%;width:35%;}

#divAssuntos {display:none}
#lblAssuntos {position:absolute;left:0%;top:5%;}
#txtAssunto {position:absolute;left:0%;top:20%;width:79.5%;}
#selAssuntos {position:absolute;left:0%;top:40%;width:80%;}
#divOpcoesAssuntos {position:absolute;left:81%;top:40%;}

<?=$strCssRestricao?>

#divVeiculoPublicacao {display:none}
#lblVeiculoPublicacao {position:absolute;left:0%;top:5%;width:50%;}
#selVeiculoPublicacao {position:absolute;left:0%;top:20%;width:50%;}
#divOpcoesVeiculoPublicacao {position:absolute;left:51%;top:20%;}

#divSinAssinaturaPublicacao {display:none}
#divSinInteressado {display:none}
#divSinDestinatario {display:none}
#divSinValorMonetario {display:none}
#divSinInterno {display:none}
#divSinUsuarioExterno {display:none}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
var objLupaAssuntos = null;
var objAutoCompletarAssuntoRI1223 = null;
var objLupaVeiculoPublicacao = null;

<?=$strJsGlobalRestricao?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='serie_cadastrar'){
    document.getElementById('selGrupoSerie').focus();
  } else if ('<?=$_GET['acao']?>'=='serie_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }

  objAutoCompletarAssuntoRI1223 = new infraAjaxAutoCompletar('hdnIdAssunto','txtAssunto','<?=$strLinkAjaxAssuntoRI1223?>');
  objAutoCompletarAssuntoRI1223.limparCampo = true;

  objAutoCompletarAssuntoRI1223.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtAssunto').value;
  };
  
  objAutoCompletarAssuntoRI1223.processarResultado = function(id,descricao,complemento){
    if (id!=''){
      objLupaAssuntos.adicionar(id,descricao,document.getElementById('txtAssunto'));
    }
  };

  objLupaAssuntos = new infraLupaSelect('selAssuntos','hdnAssuntos','<?=$strLinkAssuntosSelecao?>');

  <?=$strJsInicializarRestricao?>

  objLupaVeiculoPublicacao = new infraLupaSelect('selVeiculoPublicacao','hdnVeiculosPublicacao','<?=$strLinkVeiculoPublicacaoSelecao?>');

  configurarAplicabilidade();

}

function OnSubmitForm() {
  if (validarCadastroRI0800()){
    return true;
  }
  return false;
}

function validarCadastroRI0800() {

  if (!infraSelectSelecionado('selGrupoSerie')) {
    alert('Selecione um Grupo de Tipo de Documento.');
    document.getElementById('selGrupoSerie').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtNome').value)=='') {
    alert('Informe o Nome.');
    document.getElementById('txtNome').focus();
    return false;
  }

  if (!infraSelectSelecionado('selStaAplicabilidade')) {
    alert('Selecione uma Aplicabilidade para o Tipo de Documento.');
    document.getElementById('selStaAplicabilidade').focus();
    return false;
  }
  
  if (document.getElementById('selStaAplicabilidade').value == '<?=SerieRN::$TA_INTERNO_EXTERNO?>' || document.getElementById('selStaAplicabilidade').value == '<?=SerieRN::$TA_INTERNO?>'){
    if (!infraSelectSelecionado('selModelo')){
      alert('Selecione um Modelo para os documentos internos.');
      document.getElementById('selModelo').focus();
      return false;
    }

    if (!infraSelectSelecionado('selStaNumeracao')) {
      alert('Selecione um Tipo de Numeração.');
      document.getElementById('selStaNumeracao').focus();
      return false;
    }
  }

  if (document.getElementById('selStaAplicabilidade').value == '<?=SerieRN::$TA_FORMULARIO?>' && !infraSelectSelecionado('selTipoFormulario')){
    alert('Selecione um Formulário.');
    document.getElementById('selTipoFormulario').focus();
    return false;
  }


  return true;
}

function configurarAplicabilidade(){
  var aplic = document.getElementById('selStaAplicabilidade').value;

  if (aplic == '<?=SerieRN::$TA_INTERNO_EXTERNO?>' || aplic == '<?=SerieRN::$TA_INTERNO?>'){
    document.getElementById('divModelo').style.display = 'block';
    document.getElementById('divTipoFormulario').style.display = 'none';
    document.getElementById('divNumeracao').style.display = 'block';
    document.getElementById('divAssuntos').style.display = 'block';
    document.getElementById('divVeiculoPublicacao').style.display = 'block';
    document.getElementById('divSinAssinaturaPublicacao').style.display = 'block';
    document.getElementById('divSinInteressado').style.display = 'block';
    document.getElementById('divSinDestinatario').style.display = 'block';
    document.getElementById('divSinValorMonetario').style.display = 'block';
    document.getElementById('divSinInterno').style.display = 'block';
    document.getElementById('divSinUsuarioExterno').style.display = 'block';
  }else if (aplic == '<?=SerieRN::$TA_EXTERNO?>'){
    document.getElementById('divModelo').style.display = 'none';
    document.getElementById('divTipoFormulario').style.display = 'none';
    document.getElementById('divNumeracao').style.display = 'none';
    document.getElementById('divAssuntos').style.display = 'block';
    document.getElementById('divVeiculoPublicacao').style.display = 'none';
    document.getElementById('divSinAssinaturaPublicacao').style.display = 'none';
    document.getElementById('divSinInteressado').style.display = 'block';
    document.getElementById('divSinDestinatario').style.display = 'block';
    document.getElementById('divSinValorMonetario').style.display = 'block';
    document.getElementById('divSinInterno').style.display = 'block';
    document.getElementById('divSinUsuarioExterno').style.display = 'block';
    objLupaVeiculoPublicacao.limpar();
    infraSelectSelecionarItem(document.getElementById('selStaNumeracao'),'<?=SerieRN::$TN_SEM_NUMERACAO?>');
  }else if (aplic == '<?=SerieRN::$TA_FORMULARIO?>'){
    document.getElementById('divModelo').style.display = 'none';
    document.getElementById('divTipoFormulario').style.display = 'block';
    document.getElementById('divNumeracao').style.display = 'none';
    document.getElementById('divAssuntos').style.display = 'none';
    document.getElementById('divVeiculoPublicacao').style.display = 'none';
    document.getElementById('divSinAssinaturaPublicacao').style.display = 'none';
    document.getElementById('divSinInteressado').style.display = 'none';
    document.getElementById('divSinDestinatario').style.display = 'none';
    document.getElementById('divSinValorMonetario').style.display = 'block';
    document.getElementById('divSinInterno').style.display = 'block';
    document.getElementById('divSinUsuarioExterno').style.display = 'block';
    objLupaAssuntos.limpar();
    objLupaVeiculoPublicacao.limpar();
    infraSelectSelecionarItem(document.getElementById('selStaNumeracao'),'<?=SerieRN::$TN_SEM_NUMERACAO?>');
  }
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmSerieCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <div id="divGrupo" class="infraAreaDados" style="height:5em;">
    <label id="lblGrupoSerie" for="selGrupoSerie" accesskey="" class="infraLabelObrigatorio">Grupo:</label>
    <select id="selGrupoSerie" name="selGrupoSerie" class="infraSelect" >
    <?=$strItensSelGrupoSerie?>
    </select>
  </div>

  <div id="divNome" class="infraAreaDados" style="height:5em;">
    <label id="lblNome" for="txtNome" accesskey="" class="infraLabelObrigatorio">Nome:</label>
    <input type="text" id="txtNome" name="txtNome" class="infraText" value="<?=PaginaSEI::tratarHTML($objSerieDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" />
  </div>

  <div id="divDescricao" class="infraAreaDados" style="height:7em;">
    <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label>
    <textarea id="txaDescricao" name="txaDescricao" rows="2" class="infraTextarea" onkeypress="return infraMascaraTexto(this,event,250);" ><?=PaginaSEI::tratarHTML($objSerieDTO->getStrDescricao());?></textarea>
  </div>

  <div id="divAplicabilidade" class="infraAreaDados" style="height:5em;">
    <label id="lblStaAplicabilidade" for="selStaAplicabilidade"  accesskey="" class="infraLabelObrigatorio">Aplicabilidade:</label>
    <select id="selStaAplicabilidade" name="selStaAplicabilidade" onchange="configurarAplicabilidade()" class="infraSelect" >
    <?=$strItensSelStaAplicabilidade?>
    </select>
  </div>
  
  <div id="divModelo" class="infraAreaDados" style="height:5em;">
    <label id="lblModelo" for="selModelo" accesskey="" class="infraLabelObrigatorio">Modelo:</label>
    <select id="selModelo" name="selModelo" class="infraSelect" >
    <?=$strItensSelModelo?>
    </select>
  </div>

  <div id="divTipoFormulario" class="infraAreaDados" style="height:5em;">
    <label id="lblTipoFormulario" for="selTipoFormulario" accesskey="" class="infraLabelObrigatorio">Tipo de Formulário:</label>
    <select id="selTipoFormulario" name="selTipoFormulario" class="infraSelect" >
      <?=$strItensSelTipoFormulario?>
    </select>
  </div>
  
  <div id="divNumeracao" class="infraAreaDados" style="height:5em;">
    <label id="lblStaNumeracao" for="selStaNumeracao" accesskey="" class="infraLabelObrigatorio">Tipo de Numeração:</label>
    <select id="selStaNumeracao" name="selStaNumeracao" class="infraSelect" >
    <?=$strItensSelStaNumeracao?>
    </select>
  </div>
     
  <div id="divAssuntos" class="infraAreaDados" style="height:13em;">
    <label id="lblAssuntos" for="selAssuntos" accesskey="" class="infraLabelOpcional">Sugestão de Assuntos:</label>
    <input type="text" id="txtAssunto" name="txtAssunto" class="infraText"  />
    <select id="selAssuntos" name="selAssuntos" size="4" multiple="multiple" class="infraSelect">
    	<?=$strItensSelAssuntos?>
    </select>
    <div id="divOpcoesAssuntos">
      <img id="imgLupaAssuntos" onclick="objLupaAssuntos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Localizar Assunto" title="Localizar Assunto" class="infraImg" />
      <img id="imgAssuntosAcima" onclick="objLupaAssuntos.moverAcima();" src="<?=PaginaSEI::getInstance()->getIconeMoverAcima()?>" alt="Mover Acima Assunto Selecionado" title="Mover Acima Assunto Selecionado" class="infraImg" />
      <br />
      <img id="imgExcluirAssuntos" onclick="objLupaAssuntos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Assuntos" title="Remover Assuntos" class="infraImg" />
      <img id="imgAssuntosAbaixo" onclick="objLupaAssuntos.moverAbaixo();" src="<?=PaginaSEI::getInstance()->getIconeMoverAbaixo()?>" alt="Mover Abaixo Assunto Selecionado" title="Mover Abaixo Assunto Selecionado" class="infraImg" />
    </div>
    <input type="hidden" id="hdnIdAssunto" name="hdnIdAssunto" value="" />
  </div>  

  <?=$strHtmlRestricao?>

  <div id="divVeiculoPublicacao" class="infraAreaDados" style="height:12em;">  
    <label id="lblVeiculoPublicacao" for="selVeiculoPublicacao" accesskey="" class="infraLabelOpcional">Veículos de Publicação:</label>  
    <select id="selVeiculoPublicacao" name="selVeiculoPublicacao" size="5" multiple="multiple" class="infraSelect" >
    <?=$strItensSelVeiculoPublicacao?>
    </select>
    <div id="divOpcoesVeiculoPublicacao">
      <img id="imgLupaVeiculoPublicacao" onclick="objLupaVeiculoPublicacao.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Selecionar Veículo Publicação" title="Selecionar Veículo Publicação" class="infraImg"  />
      <br />
      <img id="imgExcluirVeiculoPublicacao" onclick="objLupaVeiculoPublicacao.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Veiculo Publicação Selecionado" title="Remover Veiculo Publicação Selecionado" class="infraImg"  />
    </div>
  </div>

  <div id="divSinAssinaturaPublicacao" class="infraDivCheckbox infraAreaDados" style="height:2.5em;">
    <input type="checkbox" id="chkSinAssinaturaPublicacao" name="chkSinAssinaturaPublicacao" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSerieDTO->getStrSinAssinaturaPublicacao())?>   />
    <label id="lblSinAssinaturaPublicacao" for="chkSinAssinaturaPublicacao" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[SerieRN::$TS_PUBLICACAO_ASSINADOS]->getStrDescricao())?></label>
  </div>

  <div id="divSinUsuarioExterno" class="infraDivCheckbox infraAreaDados" style="height:2.5em;">
    <input type="checkbox" id="chkSinUsuarioExterno" name="chkSinUsuarioExterno" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSerieDTO->getStrSinUsuarioExterno())?>  />
    <label id="lblSinUsuarioExterno" for="chkSinUsuarioExterno" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[SerieRN::$TS_USUARIO_EXTERNO]->getStrDescricao())?></label>
  </div>

  <div id="divSinInteressado" class="infraDivCheckbox infraAreaDados" style="height:2.5em;">
    <input type="checkbox" id="chkSinInteressado" name="chkSinInteressado" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSerieDTO->getStrSinInteressado())?>   />
    <label id="lblSinInteressado" for="chkSinInteressado" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[SerieRN::$TS_PERMITE_INTERESSADOS]->getStrDescricao())?></label>
  </div>

  <div id="divSinDestinatario" class="infraDivCheckbox infraAreaDados" style="height:2.5em;">
    <input type="checkbox" id="chkSinDestinatario" name="chkSinDestinatario" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSerieDTO->getStrSinDestinatario())?>   />
    <label id="lblSinDestinatario" for="chkSinDestinatario" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[SerieRN::$TS_PERMITE_DESTINATARIOS]->getStrDescricao())?></label>
  </div>

  <div id="divSinValorMonetario" class="infraDivCheckbox infraAreaDados" style="height:2.5em;">
    <input type="checkbox" id="chkSinValorMonetario" name="chkSinValorMonetario" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSerieDTO->getStrSinValorMonetario())?>   />
    <label id="lblSinValorMonetario" for="chkSinValorMonetario" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[SerieRN::$TS_PERMITE_VALOR_MONETARIO]->getStrDescricao())?></label>
  </div>

  <div id="divSinInterno" class="infraDivCheckbox infraAreaDados" style="height:2.5em;">
    <input type="checkbox" id="chkSinInterno" name="chkSinInterno" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objSerieDTO->getStrSinInterno())?>  />
    <label id="lblSinInterno" for="chkSinInterno" accesskey="" class="infraLabelCheckbox"><?=PaginaSEI::tratarHTML($arrObjSinalizacaoDTO[SerieRN::$TS_INTERNO_SISTEMA]->getStrDescricao())?></label>
  </div>

  <input type="hidden" id="hdnIdSerie" name="hdnIdSerie" value="<?=$objSerieDTO->getNumIdSerie();?>" />
  <input type="hidden" id="hdnVeiculosPublicacao" name="hdnVeiculosPublicacao" value="<?=$_POST['hdnVeiculosPublicacao']?>" />
  <input type="hidden" id="hdnAssuntos" name="hdnAssuntos" value="<?=$_POST['hdnAssuntos']?>" />
  <?
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>