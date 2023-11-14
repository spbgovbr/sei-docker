<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/03/2023 - criado por mgb29
*
* Versão do Gerador de Código: 1.43.2
*/

try {
  //require_once dirname(__FILE__).'/Infra.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  PaginaInfra::getInstance()->prepararSelecao('infra_erro_php_selecionar');

  SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  PaginaInfra::getInstance()->salvarCamposPost(array('selStaTipo','txtArquivo'));

  switch($_GET['acao']){
    case 'infra_erro_php_excluir':
      try{
        $arrStrIds = PaginaInfra::getInstance()->getArrStrItensSelecionados();
        $arrObjInfraErroPhpDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objInfraErroPhpDTO = new InfraErroPhpDTO();
          $objInfraErroPhpDTO->setStrIdInfraErroPhp($arrStrIds[$i]);
          $arrObjInfraErroPhpDTO[] = $objInfraErroPhpDTO;
        }
        $objInfraErroPhpRN = new InfraErroPhpRN();
        $objInfraErroPhpRN->excluir($arrObjInfraErroPhpDTO);
        PaginaInfra::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaInfra::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'infra_erro_php_selecionar':
      $strTitulo = PaginaInfra::getInstance()->getTituloSelecao('Selecionar Erro do PHP','Selecionar Erros do PHP');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='infra_erro_php_cadastrar'){
        if (isset($_GET['id_infra_erro_php'])){
          PaginaInfra::getInstance()->adicionarSelecionado($_GET['id_infra_erro_php']);
        }
      }
      break;

    case 'infra_erro_php_listar':
      $strTitulo = 'Erros do PHP';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'infra_erro_php_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'infra_erro_php_listar' || $_GET['acao'] == 'infra_erro_php_selecionar'){ */
    $bolAcaoCadastrar = SessaoInfra::getInstance()->verificarPermissao('infra_erro_php_cadastrar');
    if ($bolAcaoCadastrar){
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_erro_php_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  /* } */

  $objInfraErroPhpDTO = new InfraErroPhpDTO();
  $objInfraErroPhpDTO->retStrIdInfraErroPhp();
  $objInfraErroPhpDTO->retNumStaTipo();
  $objInfraErroPhpDTO->retStrArquivo();
  $objInfraErroPhpDTO->retNumLinha();
  $objInfraErroPhpDTO->retStrErro();
  $objInfraErroPhpDTO->retDthCadastro();

  $strStaTipo = PaginaInfra::getInstance()->recuperarCampo('selStaTipo');
  if ($strStaTipo!==''){
    $objInfraErroPhpDTO->setNumStaTipo($strStaTipo);
  }


  $strArquivo = PaginaInfra::getInstance()->recuperarCampo('txtArquivo');
  if (!InfraString::isBolVazia($strArquivo)) {
      $objInfraErroPhpDTO->setStrArquivo('%'.$strArquivo.'%', InfraDTO::$OPER_LIKE);
  }

  PaginaInfra::getInstance()->prepararOrdenacao($objInfraErroPhpDTO, 'Cadastro', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaInfra::getInstance()->prepararPaginacao($objInfraErroPhpDTO);

  $objInfraErroPhpRN = new InfraErroPhpRN();
  $arrObjInfraErroPhpDTO = $objInfraErroPhpRN->listar($objInfraErroPhpDTO);

  PaginaInfra::getInstance()->processarPaginacao($objInfraErroPhpDTO);

  /** @var InfraErroPhpDTO[] $arrObjInfraErroPhpDTO */

  $strResultado = '';
  $numRegistros = count($arrObjInfraErroPhpDTO);

  $bolAcaoExcluir = false;

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='infra_erro_php_selecionar'){
      $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_erro_php_consultar');
      $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_erro_php_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolCheck = true;
    }else{
      $bolAcaoConsultar = SessaoInfra::getInstance()->verificarPermissao('infra_erro_php_consultar');
      $bolAcaoAlterar = SessaoInfra::getInstance()->verificarPermissao('infra_erro_php_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoInfra::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoInfra::getInstance()->verificarPermissao('infra_erro_php_excluir');
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_erro_php_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */

    $strResultado = '';

    /* if ($_GET['acao']!='infra_erro_php_reativar'){ */
      $strSumarioTabela = 'Tabela de Erros.';
      $strCaptionTabela = 'Erros';
    /* }else{
      $strSumarioTabela = 'Tabela de Erros do PHP Inativos.';
      $strCaptionTabela = 'Erros do PHP Inativos';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaInfra::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaInfra::getInstance()->getThCheck().'</th>'."\n";
    }
    //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraErroPhpDTO,'Tipo','StaTipo',$arrObjInfraErroPhpDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraErroPhpDTO,'Arquivo','Arquivo',$arrObjInfraErroPhpDTO).'</th>'."\n";
    //$strResultado .= '<th class="infraTh">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraErroPhpDTO,'Linha','Linha',$arrObjInfraErroPhpDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh">Erro</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaInfra::getInstance()->getThOrdenacao($objInfraErroPhpDTO,'Data/Hora','Cadastro',$arrObjInfraErroPhpDTO).'</th>'."\n";

    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaInfra::getInstance()->getTrCheck($i,$arrObjInfraErroPhpDTO[$i]->getStrIdInfraErroPhp(),$arrObjInfraErroPhpDTO[$i]->getStrIdInfraErroPhp()).'</td>';
      }
      //$strResultado .= '<td>'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraErroPhpDTO[$i]->getNumStaTipo()).'</td>';
      //$strResultado .= '<td>'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraErroPhpDTO[$i]->getStrArquivo()).'</td>';
      //$strResultado .= '<td>'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraErroPhpDTO[$i]->getNumLinha()).'</td>';

      $strLog = $arrObjInfraErroPhpDTO[$i]->getStrArquivo().' (linha '.$arrObjInfraErroPhpDTO[$i]->getNumLinha().'):'."\n".$arrObjInfraErroPhpDTO[$i]->getStrErro();
      $strLog = PaginaInfra::getInstance()->tratarHTML($strLog);
      $strLog = str_replace('\n', '', $strLog);
      $strLog = str_replace("\n", '<br />', $strLog);
      $strLog = str_replace('&lt;br /&gt;', '<br />', $strLog);
      $strResultado .= '<td valign="top">' . $strLog . '</td>';

      $strResultado .= '<td align="center">'.PaginaInfra::getInstance()->tratarHTML($arrObjInfraErroPhpDTO[$i]->getDthCadastro()).'</td>';
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaInfra::getInstance()->getAcaoTransportarItem($i,$arrObjInfraErroPhpDTO[$i]->getStrIdInfraErroPhp());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_erro_php_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_infra_erro_php='.$arrObjInfraErroPhpDTO[$i]->getStrIdInfraErroPhp()).'" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeConsultar().'" title="Consultar Erro do PHP" alt="Consultar Erro do PHP" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAlterar){
        $strResultado .= '<a href="'.SessaoInfra::getInstance()->assinarLink('controlador.php?acao=infra_erro_php_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_infra_erro_php='.$arrObjInfraErroPhpDTO[$i]->getStrIdInfraErroPhp()).'" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeAlterar().'" title="Alterar Erro do PHP" alt="Alterar Erro do PHP" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluir){
        $strId = $arrObjInfraErroPhpDTO[$i]->getStrIdInfraErroPhp();
        $strDescricao = PaginaInfra::getInstance()->formatarParametrosJavaScript($arrObjInfraErroPhpDTO[$i]->getDthCadastro());
      }

      if ($bolAcaoExcluir){
        $strResultado .= '<a href="'.PaginaInfra::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaInfra::getInstance()->getProxTabTabela().'"><img src="'.PaginaInfra::getInstance()->getIconeExcluir().'" title="Excluir Erro do PHP" alt="Excluir Erro do PHP" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'infra_erro_php_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelStaTipo = InfraErroPhpINT::montarSelectStaTipo('','Todos',$strStaTipo);
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
<?if(0){?><style><?}?>
#lblStaTipo {position:absolute;left:0%;top:0%;width:50%;}
#selStaTipo {position:absolute;left:0%;top:40%;width:50%;}

#lblArquivo {position:absolute;left:0%;top:0%;}
#txtArquivo {position:absolute;left:0%;top:40%;width:50%;}


<?if(0){?></style><?}?>
<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='infra_erro_php_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas(true);
}

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Erro do PHP \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmInfraErroPhpLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmInfraErroPhpLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Erro do PHP selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Erros do PHP selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmInfraErroPhpLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmInfraErroPhpLista').submit();
  }
}
<? } ?>

<?if(0){?></script><?}?>
<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmInfraErroPhpLista" method="post" action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaInfra::getInstance()->abrirAreaDados('5em');
  ?>
  <label id="lblStaTipo" for="selStaTipo" accesskey="" class="infraLabelOpcional">Tipo:</label>
  <select id="selStaTipo" name="selStaTipo" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" >
  <?=$strItensSelStaTipo?>
  </select>

  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->abrirAreaDados('5em');
  ?>
    <label id="lblArquivo" for="txtArquivo" accesskey="" class="infraLabelOpcional">Arquivo:</label>
    <input type="text" id="txtArquivo" name="txtArquivo" class="infraText" value="<?= PaginaInfra::getInstance()->tratarHTML($strArquivo) ?>" maxlength="255" tabindex="<?= PaginaInfra::getInstance()->getProxTabDados() ?>"/>
  <?
  PaginaInfra::getInstance()->fecharAreaDados();
  PaginaInfra::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaInfra::getInstance()->montarAreaDebug();
  PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
