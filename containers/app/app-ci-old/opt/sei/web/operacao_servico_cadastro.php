<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/09/2011 - criado por mga
*
* Versão do Gerador de Código: 1.31.0
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

  PaginaSEI::getInstance()->verificarSelecao('operacao_servico_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selStaOperacaoServico','selTipoProcedimento','selSerie','selUnidade'));

  $objOperacaoServicoDTO = new OperacaoServicoDTO();

  $strDesabilitar = '';

  $arrComandos = array();
  
  $strParametros = '&id_usuario='.$_GET['id_usuario'].'&id_servico='.$_GET['id_servico'];
  
  switch($_GET['acao']){
    case 'operacao_servico_cadastrar':
      $strTitulo = 'Nova Operação';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarOperacaoServico" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objOperacaoServicoDTO->setNumIdOperacaoServico(null);
      $objOperacaoServicoDTO->setNumIdServico($_GET['id_servico']);
      $numStaOperacaoServico = PaginaSEI::getInstance()->recuperarCampo('selStaOperacaoServico');
      if ($numStaOperacaoServico!==''){
        $objOperacaoServicoDTO->setNumStaOperacaoServico($numStaOperacaoServico);
      }else{
        $objOperacaoServicoDTO->setNumStaOperacaoServico(null);
      }

      $numIdTipoProcedimento = PaginaSEI::getInstance()->recuperarCampo('selTipoProcedimento');
      if ($numIdTipoProcedimento!==''){
        $objOperacaoServicoDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
      }else{
        $objOperacaoServicoDTO->setNumIdTipoProcedimento(null);
      }

      $numIdSerie = PaginaSEI::getInstance()->recuperarCampo('selSerie');
      if ($numIdSerie!==''){
        $objOperacaoServicoDTO->setNumIdSerie($numIdSerie);
      }else{
        $objOperacaoServicoDTO->setNumIdSerie(null);
      }

      $numIdUnidade = PaginaSEI::getInstance()->recuperarCampo('selUnidade');
      if ($numIdUnidade!==''){
        $objOperacaoServicoDTO->setNumIdUnidade($numIdUnidade);
      }else{
        $objOperacaoServicoDTO->setNumIdUnidade(null);
      }

      
      if (isset($_POST['sbmCadastrarOperacaoServico'])) {
        try{
          $objOperacaoServicoRN = new OperacaoServicoRN();
          $objOperacaoServicoDTO = $objOperacaoServicoRN->cadastrar($objOperacaoServicoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação "'.$objOperacaoServicoDTO->getNumIdOperacaoServico().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_operacao_servico='.$objOperacaoServicoDTO->getNumIdOperacaoServico().$strParametros.PaginaSEI::getInstance()->montarAncora($objOperacaoServicoDTO->getNumIdOperacaoServico())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'operacao_servico_alterar':
      $strTitulo = 'Alterar Operação';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarOperacaoServico" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_operacao_servico'])){
        $objOperacaoServicoDTO->setNumIdOperacaoServico($_GET['id_operacao_servico']);
        $objOperacaoServicoDTO->retTodos();
        $objOperacaoServicoRN = new OperacaoServicoRN();
        $objOperacaoServicoDTO = $objOperacaoServicoRN->consultar($objOperacaoServicoDTO);
        if ($objOperacaoServicoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objOperacaoServicoDTO->setNumIdOperacaoServico($_POST['hdnIdOperacaoServico']);
        $objOperacaoServicoDTO->setNumIdServico($_GET['id_servico']);
        $objOperacaoServicoDTO->setNumStaOperacaoServico($_POST['selStaOperacaoServico']);
        $objOperacaoServicoDTO->setNumIdTipoProcedimento($_POST['selTipoProcedimento']);
        $objOperacaoServicoDTO->setNumIdSerie($_POST['selSerie']);
        $objOperacaoServicoDTO->setNumIdUnidade($_POST['selUnidade']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objOperacaoServicoDTO->getNumIdOperacaoServico())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarOperacaoServico'])) {
        try{
          $objOperacaoServicoRN = new OperacaoServicoRN();
          $objOperacaoServicoRN->alterar($objOperacaoServicoDTO);
          PaginaSEI::getInstance()->setStrMensagem('Operação "'.$objOperacaoServicoDTO->getNumIdOperacaoServico().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objOperacaoServicoDTO->getNumIdOperacaoServico())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'operacao_servico_consultar':
      $strTitulo = 'Consultar Operação';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($_GET['id_operacao_servico'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objOperacaoServicoDTO->setNumIdOperacaoServico($_GET['id_operacao_servico']);
      $objOperacaoServicoDTO->setBolExclusaoLogica(false);
      $objOperacaoServicoDTO->retTodos();
      $objOperacaoServicoRN = new OperacaoServicoRN();
      $objOperacaoServicoDTO = $objOperacaoServicoRN->consultar($objOperacaoServicoDTO);
      if ($objOperacaoServicoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelStaOperacaoServico = OperacaoServicoINT::montarSelectStaOperacaoServico('null','&nbsp;',$objOperacaoServicoDTO->getNumStaOperacaoServico());
  $strItensSelTipoProcedimento = TipoProcedimentoINT::montarSelectNome('','Todos',$objOperacaoServicoDTO->getNumIdTipoProcedimento());
  $strItensSelSerie = SerieINT::montarSelectNomeRI0802('','Todos',$objOperacaoServicoDTO->getNumIdSerie());
  $strItensSelUnidade = UnidadeINT::montarSelectSiglaDescricao('','Todas',$objOperacaoServicoDTO->getNumIdUnidade());

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

#lblStaOperacaoServico {position:absolute;left:0%;top:0%;width:60%;}
#selStaOperacaoServico {position:absolute;left:0%;top:6%;width:60%;}

#lblUnidade {position:absolute;left:0%;top:16%;width:60%;} 
#selUnidade {position:absolute;left:0%;top:22%;width:60%;} 

#lblTipoProcedimento {position:absolute;left:0%;top:32%;width:60%;} 
#selTipoProcedimento {position:absolute;left:0%;top:38%;width:60%;} 

#lblSerie {position:absolute;left:0%;top:48%;width:60%;}
#selSerie {position:absolute;left:0%;top:54%;width:60%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='operacao_servico_cadastrar'){
    document.getElementById('selStaOperacaoServico').focus();
  } else if ('<?=$_GET['acao']?>'=='operacao_servico_consultar'){
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
  
  atualizarCampos('<?=$objOperacaoServicoDTO->getNumStaOperacaoServico()?>');
}

function validarCadastro() {

  if (!infraSelectSelecionado('selStaOperacaoServico')) {
    alert('Selecione um Tipo da Operação.');
    document.getElementById('selStaOperacaoServico').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

function atualizarCampos(tipo){

  switch(parseInt(tipo,10)){

    case <?=OperacaoServicoRN::$TS_CONFIRMAR_DISPONIBILIZACAO_PUBLICACAO?>:
    case <?=OperacaoServicoRN::$TS_REGISTRAR_OUVIDORIA?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_TIPOS_PROCEDIMENTO_OUVIDORIA?>:

      document.getElementById('lblUnidade').style.visibility = 'hidden';
      document.getElementById('selUnidade').style.visibility = 'hidden';
      document.getElementById('selUnidade').options[0].selected = true;

      document.getElementById('lblTipoProcedimento').style.visibility = 'hidden';
      document.getElementById('selTipoProcedimento').style.visibility = 'hidden';
      document.getElementById('selTipoProcedimento').options[0].selected = true;

      document.getElementById('lblSerie').style.visibility = 'hidden';
      document.getElementById('selSerie').style.visibility = 'hidden';
      document.getElementById('selSerie').options[0].selected = true;
      break;

    case <?=OperacaoServicoRN::$TS_GERAR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_CONSULTAR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_CONSULTAR_PROCEDIMENTO_INDIVIDUAL?>:
    case <?=OperacaoServicoRN::$TS_ENVIAR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_LANCAR_ANDAMENTO?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_ANDAMENTOS?>:
    case <?=OperacaoServicoRN::$TS_EXCLUIR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_ENVIAR_EMAIL?>:

      document.getElementById('lblUnidade').style.visibility = 'visible';
      document.getElementById('selUnidade').style.visibility = 'visible';

      document.getElementById('lblTipoProcedimento').style.visibility = 'visible';
      document.getElementById('selTipoProcedimento').style.visibility = 'visible';
    
      document.getElementById('lblSerie').style.visibility = 'hidden';
      document.getElementById('selSerie').style.visibility = 'hidden';
      document.getElementById('selSerie').options[0].selected = true;
      break;
      
    case <?=OperacaoServicoRN::$TS_INCLUIR_DOCUMENTO?>:
    case <?=OperacaoServicoRN::$TS_CONSULTAR_DOCUMENTO?>:
    case <?=OperacaoServicoRN::$TS_CANCELAR_DOCUMENTO?>:
    case <?=OperacaoServicoRN::$TS_BLOQUEAR_DOCUMENTO?>:
    case <?=OperacaoServicoRN::$TS_EXCLUIR_DOCUMENTO?>:
    case <?=OperacaoServicoRN::$TS_CONSULTAR_PUBLICACAO?>:
    case <?=OperacaoServicoRN::$TS_AGENDAR_PUBLICACAO?>:
    case <?=OperacaoServicoRN::$TS_ALTERAR_PUBLICACAO?>:
    case <?=OperacaoServicoRN::$TS_CANCELAR_PUBLICACAO?>:

      document.getElementById('lblUnidade').style.visibility = 'visible';
      document.getElementById('selUnidade').style.visibility = 'visible';

      document.getElementById('lblTipoProcedimento').style.visibility = 'visible';
      document.getElementById('selTipoProcedimento').style.visibility = 'visible';
    
      document.getElementById('lblSerie').style.visibility = 'visible';
      document.getElementById('selSerie').style.visibility = 'visible';
      break;
      
    case <?=OperacaoServicoRN::$TS_GERAR_BLOCO?>:
    case <?=OperacaoServicoRN::$TS_CONSULTAR_BLOCO?>:
    case <?=OperacaoServicoRN::$TS_EXCLUIR_BLOCO?>:    
    case <?=OperacaoServicoRN::$TS_DISPONIBILIZAR_BLOCO?>:
    case <?=OperacaoServicoRN::$TS_CANCELAR_DISPONIBILIZACAO_BLOCO?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_EXTENSOES_PERMITIDAS?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_USUARIOS?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_PAISES?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_ESTADOS?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_CIDADES?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_CARGOS?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_CONTATOS?>:
    case <?=OperacaoServicoRN::$TS_ATUALIZAR_CONTATOS?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_HIPOTESES_LEGAIS?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_TIPOS_CONFERENCIA?>:
    case <?=OperacaoServicoRN::$TS_ADICIONAR_ARQUIVO?>:
    case <?=OperacaoServicoRN::$TS_ADICIONAR_CONTEUDO_ARQUIVO?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_MARCADORES_UNIDADE?>:
    case <?=OperacaoServicoRN::$TS_DEFINIR_MARCADOR?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_ANDAMENTOS_MARCADORES?>:
    case <?=OperacaoServicoRN::$TS_LISTAR_FERIADOS?>:
    case <?=OperacaoServicoRN::$TS_DEFINIR_CONTROLE_PRAZO?>:
    case <?=OperacaoServicoRN::$TS_CONCLUIR_CONTROLE_PRAZO?>:
    case <?=OperacaoServicoRN::$TS_REMOVER_CONTROLE_PRAZO?>:

      document.getElementById('lblUnidade').style.visibility = 'visible';
      document.getElementById('selUnidade').style.visibility = 'visible';

      document.getElementById('lblTipoProcedimento').style.visibility = 'hidden';
      document.getElementById('selTipoProcedimento').style.visibility = 'hidden';
      document.getElementById('selTipoProcedimento').options[0].selected = true;
    
      document.getElementById('lblSerie').style.visibility = 'hidden';
      document.getElementById('selSerie').style.visibility = 'hidden';
      document.getElementById('selSerie').options[0].selected = true;
      break;

    case <?=OperacaoServicoRN::$TS_INCLUIR_DOCUMENTO_BLOCO?>:
    case <?=OperacaoServicoRN::$TS_RETIRAR_DOCUMENTO_BLOCO?>:

      document.getElementById('lblUnidade').style.visibility = 'visible';
      document.getElementById('selUnidade').style.visibility = 'visible';

      document.getElementById('lblTipoProcedimento').style.visibility = 'visible';
      document.getElementById('selTipoProcedimento').style.visibility = 'visible';
      document.getElementById('lblSerie').style.visibility = 'visible';
      document.getElementById('selSerie').style.visibility = 'visible';
      break;
      
    case <?=OperacaoServicoRN::$TS_INCLUIR_PROCEDIMENTO_BLOCO?>:
    case <?=OperacaoServicoRN::$TS_RETIRAR_PROCEDIMENTO_BLOCO?>:
    case <?=OperacaoServicoRN::$TS_REABRIR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_CONCLUIR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_ATRIBUIR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_BLOQUEAR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_DESBLOQUEAR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_RELACIONAR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_REMOVER_RELACIONAMENTO_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_SOBRESTAR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_REMOVER_SOBRESTAMENTO_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_ANEXAR_PROCEDIMENTO?>:
    case <?=OperacaoServicoRN::$TS_DESANEXAR_PROCEDIMENTO?>:

      document.getElementById('lblUnidade').style.visibility = 'visible';
      document.getElementById('selUnidade').style.visibility = 'visible';

      document.getElementById('lblTipoProcedimento').style.visibility = 'visible';
      document.getElementById('selTipoProcedimento').style.visibility = 'visible';
      document.getElementById('lblSerie').style.visibility = 'hidden';
      document.getElementById('selSerie').style.visibility = 'hidden';
      break;
      
  }
}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmOperacaoServicoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('30em');
?>

  <label id="lblStaOperacaoServico" for="selStaOperacaoServico" accesskey="" class="infraLabelObrigatorio">Tipo da Operação:</label>
  <select id="selStaOperacaoServico" name="selStaOperacaoServico" onchange="atualizarCampos(this.value)" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelStaOperacaoServico?>
  </select>
  
  <label id="lblUnidade" for="selUnidade" accesskey="" class="infraLabelOpcional">Unidade:</label>
  <select id="selUnidade" name="selUnidade" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelUnidade?>
  </select>
  
  <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelOpcional">Tipo do Processo:</label>
  <select id="selTipoProcedimento" name="selTipoProcedimento" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelTipoProcedimento?>
  </select>
  
  <label id="lblSerie" for="selSerie" accesskey="" class="infraLabelOpcional">Tipo do Documento:</label>
  <select id="selSerie" name="selSerie" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelSerie?>
  </select>
  
  <input type="hidden" id="hdnIdOperacaoServico" name="hdnIdOperacaoServico" value="<?=$objOperacaoServicoDTO->getNumIdOperacaoServico();?>" />
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