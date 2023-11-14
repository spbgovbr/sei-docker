<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/09/2012 - criado por mga
*
* Versão do Gerador de Código: 1.33.0
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

  PaginaSEI::getInstance()->prepararSelecao('numeracao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selStaNumeracao','selSerie','selOrgao','selUnidade'));

  switch($_GET['acao']){
    case 'numeracao_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjNumeracaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objNumeracaoDTO = new NumeracaoDTO();
          $objNumeracaoDTO->setNumIdNumeracao($arrStrIds[$i]);
          $arrObjNumeracaoDTO[] = $objNumeracaoDTO;
        }
        $objNumeracaoRN = new NumeracaoRN();
        $objNumeracaoRN->excluir($arrObjNumeracaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

/* 
    case 'numeracao_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjNumeracaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objNumeracaoDTO = new NumeracaoDTO();
          $objNumeracaoDTO->setNumIdNumeracao($arrStrIds[$i]);
          $arrObjNumeracaoDTO[] = $objNumeracaoDTO;
        }
        $objNumeracaoRN = new NumeracaoRN();
        $objNumeracaoRN->desativar($arrObjNumeracaoDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'numeracao_reativar':
      $strTitulo = 'Reativar Numerações';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjNumeracaoDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objNumeracaoDTO = new NumeracaoDTO();
            $objNumeracaoDTO->setNumIdNumeracao($arrStrIds[$i]);
            $arrObjNumeracaoDTO[] = $objNumeracaoDTO;
          }
          $objNumeracaoRN = new NumeracaoRN();
          $objNumeracaoRN->reativar($arrObjNumeracaoDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        } 
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      } 
      break;

 */
    case 'numeracao_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Numeração','Selecionar Numerações');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='numeracao_cadastrar'){
        if (isset($_GET['id_numeracao'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_numeracao']);
        }
      }
      break;

    case 'numeracao_listar':
      $strTitulo = 'Numerações';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'numeracao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'numeracao_listar' || $_GET['acao'] == 'numeracao_selecionar'){ */
    //$bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('numeracao_cadastrar');
    //if ($bolAcaoCadastrar){
    //  $arrComandos[] = '<button type="button" accesskey="N" id="btnNova" value="Nova" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=numeracao_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'])).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ova</button>';
    //}
  /* } */

    
  $objNumeracaoDTO = new NumeracaoDTO();
  $objNumeracaoDTO->retNumIdNumeracao();
  $objNumeracaoDTO->retNumSequencial();
  $objNumeracaoDTO->retNumAno();
  $objNumeracaoDTO->retNumIdSerie();
  $objNumeracaoDTO->retStrNomeSerie();
  $objNumeracaoDTO->retNumIdOrgao();
  $objNumeracaoDTO->retStrSiglaOrgao();
  $objNumeracaoDTO->retStrDescricaoOrgao();
  $objNumeracaoDTO->retNumIdUnidade();
  $objNumeracaoDTO->retStrSiglaUnidade();
  $objNumeracaoDTO->retStrDescricaoUnidade();
  
  $strStaNumeracaoSerie = PaginaSEI::getInstance()->recuperarCampo('selStaNumeracao');
  $objNumeracaoDTO->setStrStaNumeracaoSerie($strStaNumeracaoSerie);

  $numIdSerie = PaginaSEI::getInstance()->recuperarCampo('selSerie');
  if ($numIdSerie!==''){
    $objNumeracaoDTO->setNumIdSerie($numIdSerie);
  }
  
  $numIdOrgao = PaginaSEI::getInstance()->recuperarCampo('selOrgao');
  $numIdUnidade = PaginaSEI::getInstance()->recuperarCampo('selUnidade');
  
  if ($strStaNumeracaoSerie == SerieRN::$TN_SEM_NUMERACAO || $strStaNumeracaoSerie == SerieRN::$TN_INFORMADA){
    
    $numRegistros = 0;  
    
  }else{
  
    if($strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_UNIDADE){
      if ($numIdUnidade!==''){
        $objNumeracaoDTO->setNumIdUnidade($numIdUnidade);
      }
      
      $numIdOrgao = null;
      $objNumeracaoDTO->setNumIdOrgao($numIdOrgao);
      
      $objNumeracaoDTO->setNumAno(null);
            
     }else if($strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ORGAO){
      
      $numIdUnidade = null; 
      $objNumeracaoDTO->setNumIdUnidade($numIdUnidade);
      
      if ($numIdOrgao!==''){
        $objNumeracaoDTO->setNumIdOrgao($numIdOrgao);
      }
      $objNumeracaoDTO->setNumAno(null);
            
     }else if($strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ANUAL_UNIDADE){

      if ($numIdUnidade!==''){
        $objNumeracaoDTO->setNumIdUnidade($numIdUnidade);
      }
      
      $numIdOrgao = null;
      $objNumeracaoDTO->setNumIdOrgao($numIdOrgao);
            
      $objNumeracaoDTO->setNumAno(null,InfraDTO::$OPER_DIFERENTE);
            
     }else if($strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ANUAL_ORGAO){
       
       $numIdUnidade = null;
       $objNumeracaoDTO->setNumIdUnidade($numIdUnidade);
       
       if ($numIdOrgao!==''){
         $objNumeracaoDTO->setNumIdOrgao($numIdOrgao);
       }
       $objNumeracaoDTO->setNumAno(null,InfraDTO::$OPER_DIFERENTE);
     }
  
    /* 
    if ($_GET['acao'] == 'numeracao_reativar'){
      //Lista somente inativos
      $objNumeracaoDTO->setBolExclusaoLogica(false);
      $objNumeracaoDTO->setStrSinAtivo('N');
    }
    */
    PaginaSEI::getInstance()->prepararOrdenacao($objNumeracaoDTO, 'NomeSerie', InfraDTO::$TIPO_ORDENACAO_ASC);
    PaginaSEI::getInstance()->prepararPaginacao($objNumeracaoDTO);
  
    $objNumeracaoRN = new NumeracaoRN();
    $arrObjNumeracaoDTO = $objNumeracaoRN->listar($objNumeracaoDTO);
  
    PaginaSEI::getInstance()->processarPaginacao($objNumeracaoDTO);
    $numRegistros = count($arrObjNumeracaoDTO);

  }
  
  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='numeracao_selecionar'){
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;
      $bolAcaoAjustar = SessaoSEI::getInstance()->verificarPermissao('numeracao_ajustar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='numeracao_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('numeracao_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('numeracao_consultar');
      $bolAcaoAjustar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('numeracao_excluir');
      $bolAcaoDesativar = false;
 */    }else{
      $bolAcaoReativar = false;
      $bolAcaoConsultar = false;
      $bolAcaoAjustar = SessaoSEI::getInstance()->verificarPermissao('numeracao_ajustar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('numeracao_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('numeracao_desativar');
    }

    /* 
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=numeracao_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=numeracao_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=numeracao_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela')).'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */
    
    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }
    

    $strResultado = '';

    /* if ($_GET['acao']!='numeracao_reativar'){ */
      $strSumarioTabela = 'Tabela de Numerações.';
      $strCaptionTabela = 'Numerações';
    /* }else{
      $strSumarioTabela = 'Tabela de Numerações Inativas.';
      $strCaptionTabela = 'Numerações Inativas';
    } */

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objNumeracaoDTO,'Tipo do Documento','NomeSerie',$arrObjNumeracaoDTO).'</th>'."\n";
    
    if($strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ORGAO || $strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ANUAL_ORGAO){ 
      $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objNumeracaoDTO,'Órgão','SiglaOrgao',$arrObjNumeracaoDTO).'</th>'."\n";
    }
    
    if ($strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_UNIDADE || $strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ANUAL_UNIDADE){
      $strResultado .= '<th class="infraTh" width="15%">'.PaginaSEI::getInstance()->getThOrdenacao($objNumeracaoDTO,'Unidade','SiglaUnidade',$arrObjNumeracaoDTO).'</th>'."\n";
    }
    
    if ($strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ANUAL_ORGAO || $strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ANUAL_UNIDADE){
      $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objNumeracaoDTO,'Ano','Ano',$arrObjNumeracaoDTO).'</th>'."\n";
    }
        
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objNumeracaoDTO,'Sequencial','Sequencial',$arrObjNumeracaoDTO).'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    $arrNumeracaoDuplicada = array();
    foreach($arrObjNumeracaoDTO as $objNumeracaoDTO){
      $strChave = $objNumeracaoDTO->getNumAno().'-'.$objNumeracaoDTO->getNumIdSerie().'-'.$objNumeracaoDTO->getNumIdOrgao().'-'.$objNumeracaoDTO->getNumIdUnidade();
      if (!isset($arrNumeracaoDuplicada[$strChave])){
        $arrNumeracaoDuplicada[$strChave] = 1;
      }else{
        $arrNumeracaoDuplicada[$strChave]++;
      }
    }

    for($i = 0;$i < $numRegistros; $i++){

      $strChave = $arrObjNumeracaoDTO[$i]->getNumAno().'-'.$arrObjNumeracaoDTO[$i]->getNumIdSerie().'-'.$arrObjNumeracaoDTO[$i]->getNumIdOrgao().'-'.$arrObjNumeracaoDTO[$i]->getNumIdUnidade();

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjNumeracaoDTO[$i]->getNumIdNumeracao(),$arrObjNumeracaoDTO[$i]->getNumSequencial()).'</td>';
      }

      $strResultado .= '<td align="left" valign="top">'.PaginaSEI::tratarHTML($arrObjNumeracaoDTO[$i]->getStrNomeSerie()).'</td>';
      
      if($strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ORGAO || $strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ANUAL_ORGAO){
  			$strResultado .= "\n".'<td align="center"  valign="top">';
  			$strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjNumeracaoDTO[$i]->getStrDescricaoOrgao()).'" title="'.PaginaSEI::tratarHTML($arrObjNumeracaoDTO[$i]->getStrDescricaoOrgao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjNumeracaoDTO[$i]->getStrSiglaOrgao()).'</a>';
  			$strResultado .= '</td>';
      }
      
			if ($strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_UNIDADE || $strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ANUAL_UNIDADE){
  			$strResultado .= "\n".'<td align="center"  valign="top">';
  			$strResultado .= '<a alt="'.PaginaSEI::tratarHTML($arrObjNumeracaoDTO[$i]->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjNumeracaoDTO[$i]->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjNumeracaoDTO[$i]->getStrSiglaUnidade()).'</a>';
  			$strResultado .= '</td>';
			}
      
			if ($strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ANUAL_ORGAO || $strStaNumeracaoSerie == SerieRN::$TN_SEQUENCIAL_ANUAL_UNIDADE){
			  $strResultado .= '<td align="center" valign="top">'.$arrObjNumeracaoDTO[$i]->getNumAno().'</td>';
			}
			
      $strResultado .= '<td align="center" valign="top">'.$arrObjNumeracaoDTO[$i]->getNumSequencial().'</td>';
      
      $strResultado .= '<td align="center">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$arrObjNumeracaoDTO[$i]->getNumIdNumeracao());

      if ($bolAcaoConsultar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=numeracao_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_numeracao='.$arrObjNumeracaoDTO[$i]->getNumIdNumeracao()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Numeração" alt="Consultar Numeração" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAjustar){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=numeracao_ajustar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_numeracao='.$arrObjNumeracaoDTO[$i]->getNumIdNumeracao().'&sequencial_original='.$arrObjNumeracaoDTO[$i]->getNumSequencial()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Ajustar Numeração" alt="Ajustar Numeração" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir){
        $strId = $arrObjNumeracaoDTO[$i]->getNumIdNumeracao();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjNumeracaoDTO[$i]->getNumSequencial());
      }
/* 
      if ($bolAcaoDesativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Numeração" alt="Desativar Numeração" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoReativar){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Numeração" alt="Reativar Numeração" class="infraImg" /></a>&nbsp;';
      }
 */

      if ($bolAcaoExcluir && $arrNumeracaoDuplicada[$strChave] > 1){
        $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Numeração" alt="Excluir Numeração" class="infraImg" /></a>&nbsp;';
      }

      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'numeracao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }
  
  $strItensSelStaNumeracao = SerieINT::montarSelectStaNumeracaoRI0797('null','&nbsp;',$strStaNumeracaoSerie);
  
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

#lblStaNumeracao {position:absolute;left:0%;top:0%;width:35%;}
#selStaNumeracao {position:absolute;left:0%;top:40%;width:35%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  if ('<?=$_GET['acao']?>'=='numeracao_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  infraEfeitoTabelas();
}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação da Numeração \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmNumeracaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmNumeracaoLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Numeração selecionada.');
    return;
  }
  if (confirm("Confirma desativação das Numerações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmNumeracaoLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmNumeracaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação da Numeração \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmNumeracaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmNumeracaoLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Numeração selecionada.');
    return;
  }
  if (confirm("Confirma reativação das Numerações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmNumeracaoLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmNumeracaoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão da Numeração \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmNumeracaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmNumeracaoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhuma Numeração selecionada.');
    return;
  }
  if (confirm("Confirma exclusão das Numerações selecionadas?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmNumeracaoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmNumeracaoLista').submit();
  }
}
<? } ?>

function onSubmitForm(){
  if (!infraSelectSelecionado('selStaNumeracao')){
    alert('Informe o Tipo de Numeração.');
    return false;
  }
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmNumeracaoLista" onsubmit="return onSubmitForm();" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>
   
  <label id="lblStaNumeracao" for="selStaNumeracao" accesskey="" class="infraLabelObrigatorio">Tipo de Numeração:</label>
  <select id="selStaNumeracao" name="selStaNumeracao" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelStaNumeracao?>
  </select>

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  //PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>