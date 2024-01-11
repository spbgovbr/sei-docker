<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 31/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.13.1
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

  //PaginaSEI::getInstance()->prepararSelecao('procedimento_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strAncora = PaginaSEI::getInstance()->montarAncora($_GET['id_procedimento']);
  
  PaginaSEI::getInstance()->salvarCamposPost(array('hdnVerProcessosSobrestados'));

  
  
  $arrComandos = array();

  switch($_GET['acao']){  	      

    case 'procedimento_remover_sobrestamento':
      try{
        
        $arrProcessos = PaginaSEI::getInstance()->getArrStrItensSelecionados();

        $arrObjRelProtocoloProtocoloDTO = array();
        foreach($arrProcessos as $dblIdProcesso){
          $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
          $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($dblIdProcesso);
          $arrObjRelProtocoloProtocoloDTO[] = $objRelProtocoloProtocoloDTO;
        }
        
        $objProcedimentoRN = new ProcedimentoRN();
        $objProcedimentoRN->removerSobrestamentoRN1017($arrObjRelProtocoloProtocoloDTO);
        
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strAncora));
      die;      
    	
      
    case 'procedimento_sobrestado_listar':
      $strTitulo = 'Processos Sobrestados';
      break;    	
 
    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objPesquisaPendenciaDTO = new PesquisaPendenciaDTO();
  $objPesquisaPendenciaDTO->setStrStaEstadoProcedimento(ProtocoloRN::$TE_PROCEDIMENTO_SOBRESTADO);
  $objPesquisaPendenciaDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
  $objPesquisaPendenciaDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      
  PaginaSEI::getInstance()->prepararPaginacao($objPesquisaPendenciaDTO);
  $objProcedimentoRN = new ProcedimentoRN();
  $arrObjProcessoSobrestadoDTO = $objProcedimentoRN->listarSobrestados($objPesquisaPendenciaDTO);
  PaginaSEI::getInstance()->processarPaginacao($objPesquisaPendenciaDTO);

  $numRegistros = count($arrObjProcessoSobrestadoDTO);	
  
  if ($numRegistros > 0){

    $bolAcaoRemoverSobrestamento = SessaoSEI::getInstance()->verificarPermissao('procedimento_remover_sobrestamento');
    
    if ($bolAcaoRemoverSobrestamento){
      $strLinkRemoverSobrestamento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_remover_sobrestamento&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
      $arrComandos[] = '<button type="button" accesskey="R" name="btnRemoverSobrestamento" onclick="acaoRemocaoSobrestamentoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>emover Sobrestamento</button>';
    }
   
    $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    
    
    
    $strSumarioTabela = 'Tabela de Processos.';
    $strCaptionTabela = 'Processos';

    $strResultado = '';
    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh">Processo</th>'."\n";
    $strResultado .= '<th class="infraTh">Usuário</th>'."\n";
    $strResultado .= '<th class="infraTh">Data de Sobrestamento</th>'."\n";
    $strResultado .= '<th class="infraTh">Motivo</th>'."\n";
    $strResultado .= '<th class="infraTh">Vinculação</th>'."\n";
    $strResultado .= '<th class="infraTh">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    for($i = 0;$i < $numRegistros; $i++){

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjProcessoSobrestadoDTO[$i]->getDblIdProcedimento(),$arrObjProcessoSobrestadoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</td>'."\n";
      $strResultado .= '<td width="17%" align="center"><a onclick="abrirProcesso(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjProcessoSobrestadoDTO[$i]->getDblIdProcedimento()).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjProcessoSobrestadoDTO[$i]->getStrNomeTipoProcedimento()).'" class="protocoloNormal" style="font-size:1em !important;">'.PaginaSEI::tratarHTML($arrObjProcessoSobrestadoDTO[$i]->getStrProtocoloProcedimentoFormatado()).'</a></td>'."\n";
  		$strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjProcessoSobrestadoDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjProcessoSobrestadoDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjProcessoSobrestadoDTO[$i]->getStrSiglaUsuario()).'</a></td>';
      $strResultado .= '<td width="17%" align="center">'.$arrObjProcessoSobrestadoDTO[$i]->getDthData().'</td>';
      $strResultado .= '<td align="left">'.PaginaSEI::tratarHTML($arrObjProcessoSobrestadoDTO[$i]->getStrMotivo()).'</td>';
      $strResultado .= '<td width="17%" align="center">';
      if ($arrObjProcessoSobrestadoDTO[$i]->getStrProtocoloProcedimentoFormatadoVinculado()!=null) {
        $strResultado .= '<a onclick="abrirProcesso(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjProcessoSobrestadoDTO[$i]->getDblIdProcedimentoVinculado()).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjProcessoSobrestadoDTO[$i]->getStrNomeTipoProcedimentoVinculado()).'" class="protocoloNormal" style="font-size:1em !important;">'.PaginaSEI::tratarHTML($arrObjProcessoSobrestadoDTO[$i]->getStrProtocoloProcedimentoFormatadoVinculado()).'</a>'."\n";
      }
      $strResultado .= '</td>';

      $strResultado .= '<td width="10%" align="center">';
      

      if($bolAcaoRemoverSobrestamento){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($arrObjProcessoSobrestadoDTO[$i]->getDblIdProcedimento()).'" onclick="acaoRemoverSobrestamento(\''.$arrObjProcessoSobrestadoDTO[$i]->getDblIdProcedimento().'\',\''.$arrObjProcessoSobrestadoDTO[$i]->getStrProtocoloProcedimentoFormatado().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::PROCESSO_REMOVER_SOBRESTAMENTO.'" title="Remover Sobrestamento" alt="Remover Sobrestamento" class="infraImg" /></a>&nbsp;';
      }
      
      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  
  }
  
  
  
  //$arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  

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
table.tabelaProcessos {
background-color:white;
border:0px solid white;
border-spacing:.1em;
}

table.tabelaProcessos tr{
margin:0;
border:0;
padding:0;
}

table.tabelaProcessos img{
width:1.1em;
height:1.1em;
}

table.tabelaProcessos a{
text-decoration:none;
}

table.tabelaProcessos a:hover{
text-decoration:underline;
}


table.tabelaProcessos caption{
font-size: 1em;
text-align: right;
color: #666;
}

th.tituloProcessos{
font-size:1em;
font-weight: bold;
text-align: center;
color: #000;
background-color: #dfdfdf;
border-spacing: 0;
}

a.processoNaoVisualizado{
  color:red;
}

#divTabelaRecebido {
margin:2em;
float:left;
display:inline;
width:40%;
}

#divTabelaRecebido table{
width:100%;
}

#divTabelaGerado {
margin:2em;
float:right;
display:inline;
width:40%;
}

#divTabelaGerado table{
width:100%;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>


function inicializar(){

  infraEfeitoTabelas();
}


<? if ($bolAcaoRemoverSobrestamento){ ?>
function acaoRemoverSobrestamento(id,desc){
  if (confirm("Confirma remoção do sobrestamento do processo \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmProcedimentoSobrestar').action='<?=$strLinkRemoverSobrestamento?>';
    document.getElementById('frmProcedimentoSobrestar').submit();
  }
}

function acaoRemocaoSobrestamentoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum processo selecionado.');
    return;
  }
  if (confirm("Confirma remoção do sobrestamento dos processos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmProcedimentoSobrestar').action='<?=$strLinkRemoverSobrestamento?>';
    document.getElementById('frmProcedimentoSobrestar').submit();
  }
}
<? } ?>


function abrirProcesso(link){
  document.getElementById('divInfraBarraComandosSuperior').style.visibility = 'hidden';
  document.getElementById('divInfraAreaTabela').style.visibility = 'hidden';
  infraOcultarMenuSistemaEsquema();
  document.getElementById('frmProcedimentoSobrestar').action = link;
  document.getElementById('frmProcedimentoSobrestar').submit();
}

function verProcessos(valor){
  document.getElementById('hdnVerProcessosSobrestados').value = valor;
  document.getElementById('frmProcedimentoSobrestar').submit();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProcedimentoSobrestar" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>
  <br />
  <br />
  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  
  <br />
  <br />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>