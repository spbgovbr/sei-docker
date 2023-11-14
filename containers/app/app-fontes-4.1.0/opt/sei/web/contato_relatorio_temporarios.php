<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
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
  
  //PaginaSEI::getInstance()->salvarCamposPost(array('txtPalavrasPesquisaContatos','selGrupoContato','txtNascimentoInicio','txtNascimentoFim', 'selTipoContato'));
  PaginaSEI::getInstance()->salvarCamposPost(array('txtTextoPesquisaContatos'));
  
  switch($_GET['acao']){

    case 'contato_desativar_temporario':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjContatoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objContatoDTO = new ContatoDTO();
          $objContatoDTO->setNumIdContato($arrStrIds[$i]);
          $arrObjContatoDTO[] = $objContatoDTO;
        }
        $objContatoRN = new ContatoRN();
        $objContatoRN->desativarRN0451($arrObjContatoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'contato_excluir_temporario':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjContatoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objContatoDTO = new ContatoDTO();
          $objContatoDTO->setNumIdContato($arrStrIds[$i]);
          $arrObjContatoDTO[] = $objContatoDTO;
        }
        $objContatoRN = new ContatoRN();
        $objContatoRN->excluirRN0326($arrObjContatoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'contato_substituir_temporario':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjContatoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objContatoDTO = new ContatoDTO();
          $objContatoDTO->setNumIdContato($arrStrIds[$i]);
          $arrObjContatoDTO[] = $objContatoDTO;
        }
        $objContatoSubstituirDTO = new ContatoSubstituirDTO();
        $objContatoSubstituirDTO->setNumIdContato($_POST['hdnIdContatoSubstituicao']);
        $objContatoSubstituirDTO->setArrObjContato($arrObjContatoDTO);
        
        $objContatoRN = new ContatoRN();
        $objContatoRN->substituir($objContatoSubstituirDTO);
        
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_relatorio_temporarios&acao_origem='.$_GET['acao']));
      die;

    case 'contato_relatorio_temporarios':
      $strTitulo = 'Relatório de Contatos Temporários';
      
      break;
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
  $numIdTipoContato = $objInfraParametro->getValor('ID_TIPO_CONTATO_TEMPORARIO');
  
  $objContatoDTO = new ContatoDTO();
  $objContatoDTO->retNumIdContato();
  $objContatoDTO->retStrNome();
  $objContatoDTO->retStrSiglaUsuarioCadastro();
  $objContatoDTO->retStrNomeUsuarioCadastro();
  $objContatoDTO->retStrSiglaUnidadeCadastro();
  $objContatoDTO->retStrDescricaoUnidadeCadastro();  
  $objContatoDTO->retDthCadastro();

  $strTextoPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtTextoPesquisaContatos');
  if ($strTextoPesquisa!='') {
    $objContatoDTO->setStrPalavrasPesquisa($strTextoPesquisa);
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objContatoDTO, 'Nome', InfraDTO::$TIPO_ORDENACAO_ASC);
  
  $objContatoDTO->setNumIdTipoContato($numIdTipoContato);

  $arrComandos = array();  
  
  PaginaSEI::getInstance()->prepararPaginacao($objContatoDTO,100);
  
  $objContatoRN = new ContatoRN();
  $arrObjContatoDTO = $objContatoRN->pesquisarRN0471($objContatoDTO);
  
  PaginaSEI::getInstance()->processarPaginacao($objContatoDTO);

  // Link do Ajax
	$strLinkAjaxContatos = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_contexto_substituicao');
  
  $numRegistros = count($arrObjContatoDTO);

  $bolCheck = true;

  $bolAcaoContextoSubstituir = SessaoSEI::getInstance()->verificarPermissao('contato_substituir_temporario');
  $bolAcaoAlterarTemporario = SessaoSEI::getInstance()->verificarPermissao('contato_alterar_temporario');
  $bolAcaoDesativarContexto = SessaoSEI::getInstance()->verificarPermissao('contato_desativar_temporario');
  $bolAcaoExcluirContexto = SessaoSEI::getInstance()->verificarPermissao('contato_excluir_temporario');
  $bolAcaoImprimir = true;

  $arrComandos[] = '<button type="button" accesskey="P" onclick="pesquisar();" id="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($bolAcaoContextoSubstituir){
    $bolCheck = true;
    $arrComandos[] = '<button type="button" accesskey="S" id="btnSubstituir" value="Substituir" onclick="acaoSubstituiçãoMultipla();" class="infraButton"><span class="infraTeclaAtalho">S</span>ubstituir</button>';
    $strLinkSubstituirContexto = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_substituir_temporario&acao_origem='.$_GET['acao']);
  }

  if ($bolAcaoExcluirContexto){
    $bolCheck = true;
    $arrComandos[] = '<input type="button" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton" />';
    $strLinkExcluirContexto = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_excluir_temporario&acao_origem='.$_GET['acao']);
  }

  if ($bolAcaoDesativarContexto){
    $bolCheck = true;
    $arrComandos[] = '<input type="button" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton" />';
    $strLinkDesativarContexto = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_desativar_temporario&acao_origem='.$_GET['acao']);
  }


  if ($bolAcaoImprimir){
    $bolCheck = true;
    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

  }

  if ($numRegistros >0){    
    
    $strResultado = '';
    
    $strSumarioTabela = 'Tabela de Contatos Temporários.';
    $strCaptionTabela = 'Contatos Temporários';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objContatoDTO,'Nome','Nome',$arrObjContatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objContatoDTO,'Usuário','SiglaUsuarioCadastro',$arrObjContatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objContatoDTO,'Unidade','SiglaUnidadeCadastro',$arrObjContatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objContatoDTO,'Data/Hora','Cadastro',$arrObjContatoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="center">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjContatoDTO[$i]->getNumIdContato(),$arrObjContatoDTO[$i]->getStrNome()).'</td>';
      }
      
      $strResultado .= '<td>'.PaginaSEI::tratarHTML($arrObjContatoDTO[$i]->getStrNome()).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjContatoDTO[$i]->getStrNomeUsuarioCadastro()).'" title="'.PaginaSEI::tratarHTML($arrObjContatoDTO[$i]->getStrNomeUsuarioCadastro()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjContatoDTO[$i]->getStrSiglaUsuarioCadastro()).'</a></td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjContatoDTO[$i]->getStrDescricaoUnidadeCadastro()).'" title="'.PaginaSEI::tratarHTML($arrObjContatoDTO[$i]->getStrDescricaoUnidadeCadastro()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjContatoDTO[$i]->getStrSiglaUnidadeCadastro()).'</a></td>';
      $strResultado .= '<td align="center">'.$arrObjContatoDTO[$i]->getDthCadastro().'</td>';
      $strResultado .= '<td align="center">';
      
      $strId = $arrObjContatoDTO[$i]->getNumIdContato();
      $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjContatoDTO[$i]->getStrNome());
      
      if ($bolAcaoAlterarTemporario){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_alterar_temporario&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_tipo_contato='.$numIdTipoContato.'&id_contato='.$arrObjContatoDTO[$i]->getNumIdContato()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Contato Temporário" alt="Alterar Contato Temporário" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativarContexto){
        $strResultado .= '<a href="#ID-'.$arrObjContatoDTO[$i]->getNumIdContato().'"  onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Contato" alt="Desativar Contato" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoExcluirContexto){
        $strResultado .= '<a href="#ID-'.$arrObjContatoDTO[$i]->getNumIdContato().'"  onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Contato" alt="Excluir Contato" class="infraImg" /></a>&nbsp;';
      }
      
      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  
  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
	
  $strLinkPesquisar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_relatorio_temporarios&acao_origem='.$_GET['acao']);
  
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
#lblTextoPesquisaContatos {position:absolute;left:0%;top:0%;}
#txtTextoPesquisaContatos {position:absolute;left:0%;top:20%;width:50%;}

#lblContatoSubstituicao {position:absolute;left:0%;top:50%;}
#txtContatoSubstituicao {position:absolute;left:0%;top:70%;width:50%;}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

var objAutoCompletarInteressadoRI1225 = null;

function inicializar(){

	//document.getElementById('btnSubstituir').style.display = 'none';

	objAutoCompletarInteressadoRI1225 = new infraAjaxAutoCompletar('hdnIdContatoSubstituicao','txtContatoSubstituicao','<?=$strLinkAjaxContatos?>');
  //objAutoCompletarInteressadoRI1225.maiusculas = true;
  //objAutoCompletarInteressadoRI1225.mostrarAviso = true;
  //objAutoCompletarInteressadoRI1225.tempoAviso = 1000;
  //objAutoCompletarInteressadoRI1225.tamanhoMinimo = 3;
  objAutoCompletarInteressadoRI1225.limparCampo = false;
  //objAutoCompletarInteressadoRI1225.bolExecucaoAutomatica = false;
  
  objAutoCompletarInteressadoRI1225.prepararExecucao = function(){
  	//document.getElementById('btnSubstituir').style.display = 'block';
    return 'palavras_pesquisa='+document.getElementById('txtContatoSubstituicao').value;
  };

  objAutoCompletarInteressadoRI1225.selecionar('<?=$_POST['hdnIdContatoSubstituicao']?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($_POST['txtContatoSubstituicao'],false)?>');
  
  infraEfeitoTabelas();
}

<? if ($bolAcaoExcluirContexto){ ?>

function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do contato '"+desc+"' ?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmContatoRelatorioTemporarios').action='<?=$strLinkExcluirContexto?>';
    document.getElementById('frmContatoRelatorioTemporarios').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum item selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos itens selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmContatoRelatorioTemporarios').action='<?=$strLinkExcluirContexto?>';
    document.getElementById('frmContatoRelatorioTemporarios').submit();
  }
}

function acaoSubstituiçãoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum item selecionado.');
    return;
  }
  if (document.getElementById('hdnIdContatoSubstituicao').value==''){
    alert('Contato para Substituição não selecionado.');
    return;
  }
  
  if (confirm("Confirma a substituição dos itens selecionados?")){
  
    infraExibirAviso();
  
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmContatoRelatorioTemporarios').action='<?=$strLinkSubstituirContexto?>';
    document.getElementById('frmContatoRelatorioTemporarios').submit();
  }
}
<? } ?>

<? if ($bolAcaoDesativarContexto){ ?>

function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do contato '"+desc+"' ?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmContatoRelatorioTemporarios').action='<?=$strLinkDesativarContexto?>';
    document.getElementById('frmContatoRelatorioTemporarios').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum item selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos itens selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmContatoRelatorioTemporarios').action='<?=$strLinkDesativarContexto?>';
    document.getElementById('frmContatoRelatorioTemporarios').submit();
  }
}
<? } ?>

function pesquisar(){
  document.getElementById('frmContatoRelatorioTemporarios').action='<?=$strLinkPesquisar?>';
  document.getElementById('frmContatoRelatorioTemporarios').submit();
}

function tratarEnter(ev){
 var key = infraGetCodigoTecla(ev);
 if (key == 13){
   pesquisar();
 }
 return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmContatoRelatorioTemporarios" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
	//PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);  
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('10em');
	?>
  <label id="lblTextoPesquisaContatos" class="infraLabelOpcional" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Texto para Pesquisa:</label>
  <input type="text" name="txtTextoPesquisaContatos" id="txtTextoPesquisaContatos" onkeyup="return tratarEnter(event);" class="infraText" value="<?=PaginaSEI::tratarHTML($strTextoPesquisa)?>"/>
  
  <label id="lblContatoSubstituicao" class="infraLabelOpcional" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Contato para Substituição:</label>
  <input type="text" name="txtContatoSubstituicao" id="txtContatoSubstituicao" class="infraText"/>
  <input type="hidden" name="hdnIdContatoSubstituicao" id="hdnIdContatoSubstituicao" class="infraText"/>
  
	<?  
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>