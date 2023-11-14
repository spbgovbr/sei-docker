<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 15/12/2011 - criado por tamir_db
*
* Versão do Gerador de Código: 1.32.1
*
* Versão no CVS: $Id$
*/

try {
  
  //require_once 'Infra.php';
  
  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  PaginaInfra::getInstance()->verificarSelecao('infra_agendamento_tarefa_selecionar');

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  PaginaInfra::getInstance()->salvarCamposPost(array('selStaPeriodicidadeExecucao'));

  $objInfraAgendamentoTarefaDTO = new InfraAgendamentoTarefaDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'infra_agendamento_tarefa_cadastrar':
      $strTitulo = 'Novo Agendamento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarInfraAgendamentoTarefa" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objInfraAgendamentoTarefaDTO->setNumIdInfraAgendamentoTarefa(null);
      $objInfraAgendamentoTarefaDTO->setStrDescricao($_POST['txtDescricao']);
      $objInfraAgendamentoTarefaDTO->setStrComando($_POST['txtComando']);
      
      $strStaPeriodicidadeExecucao = PaginaInfra::getInstance()->recuperarCampo('selStaPeriodicidadeExecucao');
      if ($strStaPeriodicidadeExecucao!==''){
        $objInfraAgendamentoTarefaDTO->setStrStaPeriodicidadeExecucao($strStaPeriodicidadeExecucao);
      }else{
        $objInfraAgendamentoTarefaDTO->setStrStaPeriodicidadeExecucao(null);
      }

      $objInfraAgendamentoTarefaDTO->setStrPeriodicidadeComplemento($_POST['txtPeriodicidadeComplemento']);
      $objInfraAgendamentoTarefaDTO->setStrParametro($_POST['txtParametro']);
      $objInfraAgendamentoTarefaDTO->setStrEmailErro($_POST['txtEmailErro']);
      $objInfraAgendamentoTarefaDTO->setDthUltimaExecucao(null);
      $objInfraAgendamentoTarefaDTO->setDthUltimaConclusao(null);
      $objInfraAgendamentoTarefaDTO->setStrSinAtivo('S');
      //$objInfraAgendamentoTarefaDTO->setNumIdOrgao(SessaoInfra::getInstance()->getNumIdOrgaoUnidadeAtual());
      $objInfraAgendamentoTarefaDTO->setStrSinSucesso('N');

      if (isset($_POST['sbmCadastrarInfraAgendamentoTarefa'])) {
        try{
          $objInfraAgendamentoTarefaRN = new InfraAgendamentoTarefaRN();
          $objInfraAgendamentoTarefaDTO = $objInfraAgendamentoTarefaRN->cadastrar($objInfraAgendamentoTarefaDTO);
          PaginaInfra::getInstance()->adicionarMensagem('Tarefa cadastrada com sucesso.');
          header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_infra_agendamento_tarefa='.$objInfraAgendamentoTarefaDTO->getNumIdInfraAgendamentoTarefa().PaginaInfra::getInstance()->montarAncora($objInfraAgendamentoTarefaDTO->getNumIdInfraAgendamentoTarefa())));
          die;
        }catch(Exception $e){
          PaginaInfra::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'infra_agendamento_tarefa_alterar':
      $strTitulo = 'Alterar Agendamento';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarInfraAgendamentoTarefa" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_infra_agendamento_tarefa'])){
        $objInfraAgendamentoTarefaDTO->setNumIdInfraAgendamentoTarefa($_GET['id_infra_agendamento_tarefa']);
        $objInfraAgendamentoTarefaDTO->setBolExclusaoLogica(false);
        $objInfraAgendamentoTarefaDTO->retTodos();
        $objInfraAgendamentoTarefaRN = new InfraAgendamentoTarefaRN();
        $objInfraAgendamentoTarefaDTO = $objInfraAgendamentoTarefaRN->consultar($objInfraAgendamentoTarefaDTO);
        if ($objInfraAgendamentoTarefaDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
      } else {
        $objInfraAgendamentoTarefaDTO->setNumIdInfraAgendamentoTarefa($_POST['hdnIdInfraAgendamentoTarefa']);
        $objInfraAgendamentoTarefaDTO->setStrDescricao($_POST['txtDescricao']);
        $objInfraAgendamentoTarefaDTO->setStrComando($_POST['txtComando']);
        //$objInfraAgendamentoTarefaDTO->setStrSinAtivo('S');
        $objInfraAgendamentoTarefaDTO->setStrStaPeriodicidadeExecucao($_POST['selStaPeriodicidadeExecucao']);
        $objInfraAgendamentoTarefaDTO->setStrPeriodicidadeComplemento($_POST['txtPeriodicidadeComplemento']);
        $objInfraAgendamentoTarefaDTO->setStrParametro($_POST['txtParametro']);
        $objInfraAgendamentoTarefaDTO->setStrEmailErro($_POST['txtEmailErro']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($objInfraAgendamentoTarefaDTO->getNumIdInfraAgendamentoTarefa())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarInfraAgendamentoTarefa'])) {
        try{
          $objInfraAgendamentoTarefaRN = new InfraAgendamentoTarefaRN();
          $objInfraAgendamentoTarefaRN->alterar($objInfraAgendamentoTarefaDTO);
          PaginaInfra::getInstance()->adicionarMensagem('Tarefa alterada com sucesso.');
          header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($objInfraAgendamentoTarefaDTO->getNumIdInfraAgendamentoTarefa())));
          die;
        }catch(Exception $e){
          PaginaInfra::getInstance()->processarExcecao($e);
        }
      }

      $objInfraAgendamentoTarefaDTO->setDthUltimaExecucao(null);
      $objInfraAgendamentoTarefaDTO->setDthUltimaConclusao(null);
      $objInfraAgendamentoTarefaDTO->setStrSinSucesso(null);

      break;

    case 'infra_agendamento_tarefa_consultar':
      $strTitulo = 'Consultar Agendamento';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaInfra::getInstance()->montarAncora($_GET['id_infra_agendamento_tarefa'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objInfraAgendamentoTarefaDTO->setNumIdInfraAgendamentoTarefa($_GET['id_infra_agendamento_tarefa']);
      $objInfraAgendamentoTarefaDTO->setBolExclusaoLogica(false);
      $objInfraAgendamentoTarefaDTO->retTodos();
      $objInfraAgendamentoTarefaRN = new InfraAgendamentoTarefaRN();
      $objInfraAgendamentoTarefaDTO = $objInfraAgendamentoTarefaRN->consultar($objInfraAgendamentoTarefaDTO);
      if ($objInfraAgendamentoTarefaDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelStaPeriodicidadeExecucao = InfraAgendamentoTarefaINT::montarSelectStaPeriodicidadeExecucao('null','&nbsp;',$objInfraAgendamentoTarefaDTO->getStrStaPeriodicidadeExecucao());

  $strHelp = 'Os parâmetros devem ser informados separados por vírgula, sempre sem espaços: idOrgao=1 ou idOrgao=4,dtaInicio=27/10/2003,...<br /><br />';
  $strAjudaParametros = PaginaInfra::getInstance()->formatarParametrosJavaScript($strHelp);

  $strHelp = '<br />';
  $strHelp .= ' * Minuto - informe o minuto (0 a 59) de execução, ex.:<br />';
  $strHelp .= ' 0, 2, 05, 10, 45,...<br /><br />';
  $strHelp .= ' * Hora - informe a hora (0 a 23) de execução com ou sem o minuto, ex.:<br />';
  $strHelp .= ' 0, 06, 12, 18:30, 20:15,...<br /><br />';
  $strHelp .= ' * Dia da Semana - informe o dia da semana (1-segunda, 2-terça, 3-quarta, 4-quinta, 5-sexta, 6-sábado, 7-domingo) e a hora de execução, ex.:<br />';
  $strHelp .= ' 1/0, 3/22:50, 7/10:15...<br /><br />';
  $strHelp .= ' * Dia do Mês - informe o dia do mês (1 a 31) e a hora de execução, ex.:<br />';
  $strHelp .= ' 10/0, 20/12:20, 30/01:05...<br /><br />';
  $strHelp .= ' * Dia do Ano: informe o dia do mês (1 a 31), o mês (1 a 12) e a hora de execução, ex.:<br />';
  $strHelp .= ' 10/1/0, 20/6/13:45, 15/12/03:45...<br /><br />';

  $strAjudaComplementoPeriodicidade = PaginaInfra::getInstance()->formatarParametrosJavaScript($strHelp);

}catch(Exception $e){
  PaginaInfra::getInstance()->processarExcecao($e);
}

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>
#lblDescricao {position:absolute;left:0%;top:0%;width:85%;}
#txtDescricao {position:absolute;left:0%;top:5%;width:85%;height:22%;}

#lblComando {position:absolute;left:0%;top:30%;width:85%;}
#txtComando {position:absolute;left:0%;top:35%;width:85%;}

#lblParametro {position:absolute;left:0%;top:44%;width:85%;}
#txtParametro {position:absolute;left:0%;top:49%;width:81%;}
#imgAjudaParametro {position:absolute;left:83%;top:49%;}

#lblStaPeriodicidadeExecucao {position:absolute;left:0%;top:58%;width:25%;}
#selStaPeriodicidadeExecucao {position:absolute;left:0%;top:63%;width:25%;}

#lblPeriodicidadeComplemento {position:absolute;left:30%;top:58%;width:55%;}
#txtPeriodicidadeComplemento {position:absolute;left:30%;top:63%;width:51%;}
#imgAjudaPeriodicidadeComplemento {position:absolute;left:83%;top:63%;}

#lblEmailErro {position:absolute;left:0%;top:72%;width:85%;}
#txtEmailErro {position:absolute;left:0%;top:77%;width:85%;}

#lblUltimaExecucao {position:absolute;left:0%;top:86%;width:25%;visibility:hidden;}
#txtUltimaExecucao {position:absolute;left:0%;top:91%;width:25%;visibility:hidden;}

#lblUltimaConclusao {position:absolute;left:30%;top:86%;width:25%;visibility:hidden;}
#txtUltimaConclusao {position:absolute;left:30%;top:91%;width:25%;visibility:hidden;}

#lblStatus {position:absolute;left:60%;top:86%;width:25%;visibility:hidden;}
#txtStatus {position:absolute;left:60%;top:91%;width:25%;visibility:hidden;}

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>
function inicializar(){
  if ('<?=$_GET['acao']?>'=='infra_agendamento_tarefa_cadastrar'){
    document.getElementById('txtDescricao').focus();
  } else if ('<?=$_GET['acao']?>'=='infra_agendamento_tarefa_consultar'){
    document.getElementById('lblUltimaExecucao').style.visibility = 'visible';
    document.getElementById('txtUltimaExecucao').style.visibility = 'visible';
    document.getElementById('lblUltimaConclusao').style.visibility = 'visible';
    document.getElementById('txtUltimaConclusao').style.visibility = 'visible';
    document.getElementById('lblStatus').style.visibility = 'visible';
    document.getElementById('txtStatus').style.visibility = 'visible';
    infraDesabilitarCamposAreaDados();
  }else{
    document.getElementById('btnCancelar').focus();
  }
  infraEfeitoTabelas();
}

function validarCadastro() {
  if (infraTrim(document.getElementById('txtDescricao').value)=='') {
    alert('Informe a Descrição.');
    document.getElementById('txtDescricao').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtComando').value)=='') {
    alert('Informe o Comando.');
    document.getElementById('txtComando').focus();
    return false;
  }

  if (!infraSelectSelecionado('selStaPeriodicidadeExecucao')) {
    alert('Selecione uma Periodicidade de Execução.');
    document.getElementById('selStaPeriodicidadeExecucao').focus();
    return false;
  }

  if (infraTrim(document.getElementById('txtPeriodicidadeComplemento').value)=='') {
    alert('Informe o Complemento da Periodicidade.');
    document.getElementById('txtPeriodicidadeComplemento').focus();
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInfraAgendamentoTarefaCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaInfra::getInstance()->montarAreaValidacao();
PaginaInfra::getInstance()->abrirAreaDados('35em');
?>

  <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
  <textarea id="txtDescricao" name="txtDescricao" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>"><?=PaginaInfra::getInstance()->tratarHTML($objInfraAgendamentoTarefaDTO->getStrDescricao());?></textarea>
  
  <label id="lblComando" for="txtComando" accesskey="" class="infraLabelObrigatorio">Comando:</label>
  <input type="text" id="txtComando" name="txtComando" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraAgendamentoTarefaDTO->getStrComando());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

  <label id="lblParametro" for="txtParametro" accesskey="" class="infraLabelOpcional">Parâmetros:</label>
  <input type="text" id="txtParametro" name="txtParametro" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraAgendamentoTarefaDTO->getStrParametro());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <img  id="imgAjudaParametro" src="<?=PaginaInfra::getInstance()->getIconeInformacao()?>" alt="" onmouseover="return infraTooltipMostrar('<?=$strAjudaParametros?>', 'Parâmetros');" onmouseout="return infraTooltipOcultar();" class="infraImgNormal" />

  <label id="lblStaPeriodicidadeExecucao" for="selStaPeriodicidadeExecucao" accesskey="" class="infraLabelObrigatorio">Periodicidade de Execução:</label>
  <select id="selStaPeriodicidadeExecucao" name="selStaPeriodicidadeExecucao" class="infraSelect" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>">
  <?=$strItensSelStaPeriodicidadeExecucao?>
  </select> 
  
  <label id="lblPeriodicidadeComplemento" for="txtPeriodicidadeComplemento" accesskey="" class="infraLabelObrigatorio">Complemento da Periodicidade:</label>
  <input type="text" id="txtPeriodicidadeComplemento" name="txtPeriodicidadeComplemento" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraAgendamentoTarefaDTO->getStrPeriodicidadeComplemento());?>" onkeypress="return infraMascaraTexto(this,event,200);" maxlength="200" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  <img  id="imgAjudaPeriodicidadeComplemento" src="<?=PaginaInfra::getInstance()->getIconeInformacao()?>" alt="" onmouseover="return infraTooltipMostrar('<?=$strAjudaComplementoPeriodicidade?>', 'Complemento da Periodicidade');" onmouseout="return infraTooltipOcultar();" class="infraImgNormal" />

  <label id="lblEmailErro" for="txtEmailErro" accesskey="" class="infraLabelOpcional">Email  de Erro (separar múltiplos endereços por ponto e vírgula):</label>
  <input type="text" id="txtEmailErro" name="txtEmailErro" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($objInfraAgendamentoTarefaDTO->getStrEmailErro());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />
  
	<label id="lblUltimaExecucao" for="txtUltimaExecucao" accesskey="" class="infraLabelOpcional">Data/Hora Última Execução:</label>
  <input type="text" id="txtUltimaExecucao" name="txtUltimaExecucao" class="infraText" value="<?=$objInfraAgendamentoTarefaDTO->getDthUltimaExecucao();?>" readonly="readonly" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

	<label id="lblUltimaConclusao" for="txtUltimaConclusao" accesskey="" class="infraLabelOpcional">Data/Hora Última Conclusão:</label>
  <input type="text" id="txtUltimaConclusao" name="txtUltimaConclusao" class="infraText" value="<?=$objInfraAgendamentoTarefaDTO->getDthUltimaConclusao();?>" readonly="readonly" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

	<label id="lblStatus" for="txtStatus" accesskey="" class="infraLabelOpcional">Status da Última Execução:</label>
  <input type="text" id="txtStatus" name="txtStatus" class="infraText" value="<?=$objInfraAgendamentoTarefaDTO->getStrSinSucesso()=='S'?'Sucesso':'Falha';?>" readonly="readonly" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

  <input type="hidden" id="hdnIdInfraAgendamentoTarefa" name="hdnIdInfraAgendamentoTarefa" value="<?=$objInfraAgendamentoTarefaDTO->getNumIdInfraAgendamentoTarefa();?>" />
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  //PaginaInfra::getInstance()->montarAreaDebug();
  //PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>