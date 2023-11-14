<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/11/2008 - criado por mga
*
* Versão do Gerador de Código: 1.25.0
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

  //PaginaSEI::getInstance()->prepararSelecao('publicacao_selecionar');
  
  //PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  
  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  $numRegistros = 0;

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  if (isset($_GET['id_procedimento'])){
    $strParametros .= '&id_procedimento='.$_GET['id_procedimento'];
  }
  
  if (isset($_GET['id_documento'])){
    $strParametros .= '&id_documento='.$_GET['id_documento'];
  }

  
  $strAncora = '';
  if (PaginaSEI::getInstance()->getAcaoRetorno()=='documento_listar'){
    $strAncora = PaginaSEI::getInstance()->montarAncora($_GET['id_documento']);
  }else if (PaginaSEI::getInstance()->getAcaoRetorno()=='protocolo_pesquisa_antiga'){
    $strAncora = PaginaSEI::getInstance()->montarAncora($_GET['id_documento']);
  }
  
  
//echo $strAncora;
  switch($_GET['acao']){
    case 'publicacao_cancelar_agendamento': 
      try{       
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjPublicacaoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objPublicacaoDTO = new PublicacaoDTO();
          $objPublicacaoDTO->setNumIdPublicacao($arrStrIds[$i]);
          $arrObjPublicacaoDTO[] = $objPublicacaoDTO;
        }
        $objPublicacaoRN = new PublicacaoRN();
        $objPublicacaoRN->cancelarAgendamentoRN1043($objPublicacaoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros.$strAncora));
      die;

    case 'publicacao_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Publicação','Selecionar Publicações');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='publicacao_agendar'){
        if (isset($_GET['id_publicacao'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_publicacao']);
        }
      }
      break;

    case 'publicacao_listar':
      $strTitulo = 'Publicações e Agendamentos';
      
      $objPublicacaoDTO = new PublicacaoDTO();
  		$objPublicacaoDTO->setDblIdDocumento($_GET['id_documento']);
  		
		  $objPublicacaoRN = new PublicacaoRN();
		  $arrObjPublicacaoDTO = $objPublicacaoRN->listarPublicacoesDocumentoRN1101($objPublicacaoDTO);
				  
		  $numRegistros = count($arrObjPublicacaoDTO);
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'publicacao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';    
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objPublicacaoDTO, 'Disponibilizacao', InfraDTO::$TIPO_ORDENACAO_ASC);

  if (SessaoSEI::getInstance()->verificarPermissao('publicacao_agendar')){
	  
    $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
    $objRelProtocoloProtocoloDTO->retDblIdProtocolo1();
    $objRelProtocoloProtocoloDTO->setDblIdProtocolo2($_GET['id_documento']);
    $objRelProtocoloProtocoloDTO->setStrStaAssociacao(RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO);
    
    $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
    $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);
    
    if ($objRelProtocoloProtocoloDTO==null){
      throw new InfraException("Processo do documento não encontrado.");
    }
	}

  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='publicacao_selecionar'){
    	
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('publicacao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('publicacao_alterar_agendamento');
      $bolAcaoConsultarDocumento = SessaoSEI::getInstance()->verificarPermissao('documento_consultar');
      $bolAcaoImprimir = false;
      $bolAcaoCancelar = false;
      $bolCheck = true;
    }else{    	

      $arrObjPublicacaoAPITodas = array();
      $arrObjPublicacaoAPIAgendadas = array();

      foreach($arrObjPublicacaoDTO as $objPublicacaoDTO){
        $objPublicacaoAPI = new PublicacaoAPI();
        $objPublicacaoAPI->setIdPublicacao($objPublicacaoDTO->getNumIdPublicacao());
        $objPublicacaoAPI->setIdDocumento($objPublicacaoDTO->getDblIdDocumento());
        $objPublicacaoAPI->setEstado($objPublicacaoDTO->getStrStaEstado());
        $arrObjPublicacaoAPITodas[] = $objPublicacaoAPI;

        if ($objPublicacaoAPI->getEstado() == PublicacaoRN::$TE_AGENDADO){
          $arrObjPublicacaoAPIAgendadas[] = $objPublicacaoAPI;
        }
      }

      $arrIntegracaoAcoesPublicacao = array();
      foreach ($SEI_MODULOS as $seiModulo) {
        if (($arr = $seiModulo->executar('montarAcaoPublicacao', $arrObjPublicacaoAPITodas)) != null){
          foreach($arr as $key => $arrAcoes) {
            $arrIntegracaoAcoesPublicacao[$key] = array_merge($arrIntegracaoAcoesPublicacao[$key] ?: array(), $arrAcoes);
          }
        }
      }

      $arrOcultarAlteracaoPublicacao = array();
      $arrOcultarCancelamentoAgendamento = array();

      foreach ($SEI_MODULOS as $seiModulo) {

        if (($arr = $seiModulo->executar('ocultarAcaoAlterarPublicacao', $arrObjPublicacaoAPITodas)) != null){
          $arrOcultarAlteracaoPublicacao = array_unique(array_merge($arrOcultarAlteracaoPublicacao, $arr));
        }

        if (($arr = $seiModulo->executar('ocultarAcaoCancelarAgendamentoPublicacao', $arrObjPublicacaoAPIAgendadas)) != null){
          $arrOcultarCancelamentoAgendamento = array_unique(array_merge($arrOcultarCancelamentoAgendamento, $arr));
        }

      }

      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('publicacao_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('publicacao_alterar_agendamento');
      $bolAcaoConsultarDocumento = SessaoSEI::getInstance()->verificarPermissao('documento_consultar');
      $bolAcaoImprimir = true;
      $bolAcaoCancelar = SessaoSEI::getInstance()->verificarPermissao('publicacao_cancelar_agendamento');
    }

    if ($bolAcaoCancelar){
      $bolCheck = true;
//      $arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkCancelar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=publicacao_cancelar_agendamento&acao_origem='.$_GET['acao'].$strParametros.$strAncora);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

    }

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Publicações.';
    $strCaptionTabela = 'Publicações';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="10%">Documento</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Tipo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Veículo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Data</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Motivo</th>'."\n";
    $strResultado .= '<th class="infraTh">Imprensa Nacional</th>'."\n";
    $strResultado .= '<th class="infraTh">Resumo</th>'."\n";
    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    
    $i = 0;
    foreach($arrObjPublicacaoDTO as $objPublicacaoDTO){

      $objDocumentoDTO = $objPublicacaoDTO->getObjDocumentoDTO();
      
      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      if ($bolCheck){
        $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i++,$objPublicacaoDTO->getNumIdPublicacao(),$objPublicacaoDTO->getDtaDisponibilizacao()).'</td>';
      }

      $strClassProtocolo = 'protocoloAberto';
      $strResultado .= '<td align="center" valign="top">';
      if ($bolAcaoConsultarDocumento){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento='.$objDocumentoDTO->getDblIdDocumento()) .'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="'.$strClassProtocolo.'">'.PaginaSEI::tratarHTML($objPublicacaoDTO->getStrProtocoloFormatadoProtocolo()).'</a>';
      }else{
        $strResultado .= '<span class="'.$strClassProtocolo.'">'.PaginaSEI::tratarHTML($objPublicacaoDTO->getStrProtocoloFormatadoProtocolo()).'</span>';
      }      
      $strResultado .= '</td>';
      
      $strResultado .= '<td align="center" valign="top">'.DocumentoINT::formatarIdentificacao($objDocumentoDTO).'</td>';
      $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($objPublicacaoDTO->getStrNomeVeiculoPublicacao()).'</td>';
      $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($objPublicacaoDTO->getDtaDisponibilizacao()).'</td>';
      $strResultado .= '<td align="center" valign="top">'.PaginaSEI::tratarHTML($objPublicacaoDTO->getStrDescricaoMotivo()).'</td>';
      $strResultado .= '<td align="center" valign="top">'.PublicacaoINT::montarDadosImprensaNacional($objPublicacaoDTO).'</td>';
      $strResultado .= '<td valign="top">'.nl2br(PaginaSEI::tratarHTML($objPublicacaoDTO->getStrResumo())).'</td>';
      $strResultado .= '<td align="center" valign="top">';

      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$objPublicacaoDTO->getNumIdPublicacao());

      if (is_array($arrIntegracaoAcoesPublicacao) && isset($arrIntegracaoAcoesPublicacao[$objPublicacaoDTO->getNumIdPublicacao()])) {
        foreach ($arrIntegracaoAcoesPublicacao[$objPublicacaoDTO->getNumIdPublicacao()] as $strIconeIntegracao) {
          $strResultado .= '&nbsp;' . $strIconeIntegracao;
        }
      }

      if ($objPublicacaoDTO->getStrStaEstado()==PublicacaoRN::$TE_AGENDADO){

	      if ($bolAcaoAlterar && !in_array($objPublicacaoDTO->getNumIdPublicacao(), $arrOcultarAlteracaoPublicacao)){
	        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=publicacao_alterar_agendamento&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_publicacao='.$objPublicacaoDTO->getNumIdPublicacao().$strParametros.$strAncora).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::PUBLICACAO_ALTERAR.'" title="Alterar Agendamento" alt="Alterar Agendamento" class="infraImg" /></a>&nbsp;';
	      }

	      if ($bolAcaoCancelar && !in_array($objPublicacaoDTO->getNumIdPublicacao(), $arrOcultarCancelamentoAgendamento)){
	        $strResultado .= '<a href="#ID-'.$objPublicacaoDTO->getNumIdPublicacao().'" onclick="acaoCancelar(\''.$objPublicacaoDTO->getNumIdPublicacao().'\',\''.$objPublicacaoDTO->getDtaDisponibilizacao().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::PUBLICACAO_CANCELAR.'" title="Cancelar Agendamento" alt="Cancelar Agendamento" class="infraImg" /></a>&nbsp;';
	      }

      }else{
        if ($bolAcaoAlterar && !in_array($objPublicacaoDTO->getNumIdPublicacao(), $arrOcultarAlteracaoPublicacao)){
          $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=publicacao_alterar_agendamento&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_publicacao='.$objPublicacaoDTO->getNumIdPublicacao().$strParametros.$strAncora).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::PUBLICACAO_ALTERAR.'" title="Alterar Dados de Publicação" alt="Alterar Dados de Publicação" class="infraImg" /></a>&nbsp;';
        }
      }
	    
      $strResultado .= '</td></tr>'."\n";
    }
    $strResultado .= '</table>';
  }
  if ($_GET['acao'] == 'publicacao_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.$strAncora).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao_origem'].'&id_procedimento='.$_GET['id_procedimento'].'&id_documento='.$_GET['id_documento'].'&montar_visualizacao=0');
  
}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){

  parent.parent.parent.infraOcultarAviso();

  //atualiza ações do documento
  <?if ($_GET['acao_origem']=='publicacao_cancelar_agendamento'){?>
    parent.parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
  <?}?>
  
  if ('<?=$_GET['acao']?>'=='publicacao_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }
  
  infraEfeitoTabelas();
}

<? if ($bolAcaoCancelar){ ?>
function acaoCancelar(id,desc){
  if (confirm("Confirma cancelamento do agendamento em \""+desc+"\"?")){
    parent.parent.parent.infraExibirAviso();
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmPublicacaoLista').action='<?=$strLinkCancelar?>';
    document.getElementById('frmPublicacaoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum agendamento selecionado.');
    return;
  }
  if (confirm("Confirma cancelamento dos agendamentos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmPublicacaoLista').action='<?=$strLinkCancelar?>';
    document.getElementById('frmPublicacaoLista').submit();
  }
}
<? } ?>

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmPublicacaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>