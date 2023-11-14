<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 18/11/2010 - criado por mga
*
* Versão do Gerador de Código: 1.30.0
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
  PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);

  $strParametros = '';
  $bolAcervo=true;
  $arrComandos = array();

  if (isset($_GET['tipo'])){
  	$strParametros .= '&tipo='.$_GET['tipo'];
  }
  if (isset($_GET['id_tipo_localizador'])){
    $strParametros .= '&id_tipo_localizador='.$_GET['id_tipo_localizador'];
  }
  if (isset($_GET['id_serie'])){
  	$strParametros .= '&id_serie='.$_GET['id_serie'];
  }
  if (isset($_GET['sta_estado'])){
  	$strParametros .= '&sta_estado='.$_GET['sta_estado'];
  }
  if (isset($_GET['de']) && $_GET['de']!=''){
    $strParametros .= '&de='.$_GET['de'];
    $strParametros .= '&ate='.$_GET['ate'];
    $bolAcervo=false;
  }
  if (isset($_GET['ano'])){
  	$strParametros .= '&ano='.$_GET['ano'];
  }
  if (isset($_GET['mes'])){
    $strParametros .= '&mes='.$_GET['mes'];
  }


  if (!$bolAcervo){
    $strTitulo.=' no período';
    $dtaInicio=$_GET['de'];
    $dtaFim=InfraData::calcularData(1,InfraData::$UNIDADE_DIAS,InfraData::$SENTIDO_ADIANTE,$_GET['ate']);
    if (isset($_GET['mes'])){
      $mes=$_GET['mes'];
      $ano=$_GET['ano'];
      $dta='01/'.$mes.'/'.$ano;
      if (InfraData::compararDatasSimples($dtaInicio,$dta)>0){
        $dtaInicio=$dta;
      }
      $dta=InfraData::calcularData(1,InfraData::$UNIDADE_MESES,InfraData::$SENTIDO_ADIANTE,$dta);
      if (InfraData::compararDatasSimples($dta,$dtaFim)>0){
        $dtaFim=$dta;
      }
    }
  }
  switch($_GET['acao']){

    case 'estatisticas_detalhar_arquivamento':

    	if (InfraString::isBolVazia($_GET['tipo'])){
    		throw new InfraException('Detalhe da estatística não informado.');
    	}

      $objArquivamentoRN=new ArquivamentoRN();
      $objArquivamentoDTO=new ArquivamentoDTO();
      $objEstatisticasRN=new EstatisticasRN();
      $objEstatisticasArquivamentoDTO=new EstatisticasArquivamentoDTO();
      $objEstatisticasArquivamentoDTO->setNumIdUnidadeAtividade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());

    	
    	switch($_GET['tipo']){
        case 'localizadores':
          $strTitulo = 'Localizadores utilizados';
          break;

        case 'recebidos':
          $strTitulo = 'Documentos recebidos';

          if ($bolAcervo) {
            $objArquivamentoDTO->setNumIdUnidadeRecebimento(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_RECEBIDO);
            $objArquivamentoDTO->setNumTipoFkRecebimento(InfraDTO::$TIPO_FK_OBRIGATORIA);
            $objArquivamentoDTO->retDthAberturaRecebimento();
            $objArquivamentoDTO->setOrdDthAberturaRecebimento(InfraDTO::$TIPO_ORDENACAO_DESC);
          } else {
            $strTitulo = 'Documentos recebidos no período';
            $objEstatisticasArquivamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_RECEBIMENTO_ARQUIVO);
            $objEstatisticasArquivamentoDTO->setStrNome('DOCUMENTO');
          }
          break;

    		case 'arquivados':
    	    $strTitulo = 'Documentos arquivados';
          if ($bolAcervo) {
            $objArquivamentoDTO->setNumIdUnidadeArquivamento(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objArquivamentoDTO->setStrStaArquivamento(array(ArquivamentoRN::$TA_ARQUIVADO,ArquivamentoRN::$TA_SOLICITADO_DESARQUIVAMENTO),InfraDTO::$OPER_IN);
            $objArquivamentoDTO->setNumTipoFkArquivamento(InfraDTO::$TIPO_FK_OBRIGATORIA);
            $objArquivamentoDTO->retStrNomeTipoLocalizador();
            $objArquivamentoDTO->retNumIdLocalizador();
            $objArquivamentoDTO->retStrSiglaTipoLocalizador();
            $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
            $objArquivamentoDTO->retDthAberturaArquivamento();
            $objArquivamentoDTO->setOrdDthAberturaArquivamento(InfraDTO::$TIPO_ORDENACAO_DESC);
          } else {
            $strTitulo = 'Documentos arquivados no período';
            $objEstatisticasArquivamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_ARQUIVAMENTO);
            $objEstatisticasArquivamentoDTO->setStrNome('DOCUMENTO');
            $objEstatisticasArquivamentoDTO->retNumSeqLocalizador();
            $objEstatisticasArquivamentoDTO->retNumIdLocalizadorArquivamento();
            $objEstatisticasArquivamentoDTO->retStrNomeTipoLocalizador();
            $objEstatisticasArquivamentoDTO->retStrSiglaTipoLocalizador();
          }
          break;

        case 'desarquivados':
          $strTitulo = 'Documentos desarquivados e não devolvidos';
          if ($bolAcervo) {
            $objArquivamentoDTO->setNumIdUnidadeDesarquivamento(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objArquivamentoDTO->setStrStaArquivamento(ArquivamentoRN::$TA_DESARQUIVADO);
            $objArquivamentoDTO->setNumTipoFkDesarquivamento(InfraDTO::$TIPO_FK_OBRIGATORIA);
            $objArquivamentoDTO->retStrNomeTipoLocalizador();
            $objArquivamentoDTO->retNumIdLocalizador();
            $objArquivamentoDTO->retStrSiglaTipoLocalizador();
            $objArquivamentoDTO->retNumSeqLocalizadorLocalizador();
            $objArquivamentoDTO->retDthAberturaDesarquivamento();
            $objArquivamentoDTO->retNumIdAtividadeDesarquivamento();
            $objArquivamentoDTO->setOrdDthAberturaDesarquivamento(InfraDTO::$TIPO_ORDENACAO_DESC);
          } else {
            $strTitulo = 'Documentos desarquivados no período';
            $objEstatisticasArquivamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_DESARQUIVAMENTO);
            $objEstatisticasArquivamentoDTO->setStrNome('DOCUMENTO');
            $objEstatisticasArquivamentoDTO->retNumSeqLocalizador();
            $objEstatisticasArquivamentoDTO->retNumIdLocalizadorArquivamento();
            $objEstatisticasArquivamentoDTO->retStrNomeTipoLocalizador();
            $objEstatisticasArquivamentoDTO->retStrSiglaTipoLocalizador();
            $objEstatisticasArquivamentoDTO->retNumIdAtividade();
          }
          break;

        case 'eliminados_fisicos':
          $strTitulo = 'Documentos físicos eliminados';
          if ($bolAcervo) {
            $objArquivamentoDTO->setNumIdUnidadeEliminacao(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
            $objArquivamentoDTO->setStrStaEliminacao(ArquivamentoRN::$TE_ELIMINADO);
            $objArquivamentoDTO->setNumTipoFkEliminacao(InfraDTO::$TIPO_FK_OBRIGATORIA);
            $objArquivamentoDTO->retDthAberturaEliminacao();
            $objArquivamentoDTO->setOrdDthAberturaEliminacao(InfraDTO::$TIPO_ORDENACAO_DESC);
          } else {
            $strTitulo = 'Documentos físicos eliminados no período';
            $objEstatisticasArquivamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_DESARQUIVAMENTO_PARA_ELIMINACAO);
            $objEstatisticasArquivamentoDTO->setStrNome('DOCUMENTO');
          }
          break;

    		default:
    		  throw new InfraException('Tipo do detalhe da estatística não informado.');
    	}
    	break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  if (!$bolAcervo){
    $objEstatisticasArquivamentoDTO->adicionarCriterio(array('AberturaAtividade', 'AberturaAtividade'),
        array(InfraDTO::$OPER_MAIOR_IGUAL, InfraDTO::$OPER_MENOR_IGUAL),
        array($dtaInicio, $dtaFim),
        array(InfraDTO::$OPER_LOGICO_AND));
  }

  if ($_GET['tipo']=='localizadores'){
    $objLocalizadorRN=new LocalizadorRN();
    $objLocalizadorDTO=new LocalizadorDTO();
    $objEstatisticasRN=new EstatisticasRN();

    if (isset($_GET['id_tipo_localizador'])){
      $objLocalizadorDTO->setNumIdTipoLocalizador($_GET['id_tipo_localizador']);
      $objEstatisticasArquivamentoDTO->setNumIdTipoLocalizadorAndamento($_GET['id_tipo_localizador']);
    }
    if (isset($_GET['sta_estado'])){
      $objLocalizadorDTO->setStrStaEstado($_GET['sta_estado']);
      $objEstatisticasArquivamentoDTO->setStrStaEstadoLocalizadorAndamento($_GET['sta_estado']);
    }

    if ($bolAcervo){
      $objLocalizadorDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
    } else {
      $objEstatisticasArquivamentoDTO->setNumIdTarefaAtividade(TarefaRN::$TI_ARQUIVAMENTO);
      $objEstatisticasArquivamentoDTO->setStrNome('LOCALIZADOR');
      $objEstatisticasArquivamentoDTO->setDistinct(true);
      $objEstatisticasArquivamentoDTO->retStrIdOrigem();

      $ret = $objEstatisticasRN->listarArquivamento($objEstatisticasArquivamentoDTO);

      if (InfraArray::contar($ret)>0){
        $arrIdLocalizador=InfraArray::converterArrInfraDTO($ret,'IdOrigem');
        $objLocalizadorDTO=new LocalizadorDTO();
        $objLocalizadorDTO->setNumIdLocalizador($arrIdLocalizador,InfraDTO::$OPER_IN);
      } else {
        $objLocalizadorDTO->setNumIdLocalizador(null);
      }
    }

    $objLocalizadorDTO->retNumIdLocalizador();
    $objLocalizadorDTO->retStrStaEstado();
    $objLocalizadorDTO->retStrNomeTipoLocalizador();
    $objLocalizadorDTO->retNumSeqLocalizador();
    $objLocalizadorDTO->retStrSiglaTipoLocalizador();
    $objLocalizadorDTO->setOrdStrNomeTipoLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objLocalizadorDTO->setOrdNumSeqLocalizador(InfraDTO::$TIPO_ORDENACAO_ASC);

    PaginaSEI::getInstance()->prepararPaginacao($objLocalizadorDTO);
    $arrObjLocalizadorDTO=$objLocalizadorRN->listarRN0622($objLocalizadorDTO);
    PaginaSEI::getInstance()->processarPaginacao($objLocalizadorDTO);
    $numRegistros = InfraArray::contar($arrObjLocalizadorDTO);

    if ($numRegistros > 0){

      $bolCheck = true;
      $bolAcaoImprimir = true;
      $bolAcaoProcedimentoTrabalhar = SessaoSEI::getInstance()->verificarPermissao('procedimento_trabalhar');
      $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');

      if ($bolAcaoImprimir){
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

      }

      $strResultado = '';

      $strSumarioTabela = 'Tabela de Registros de Detalhamento.';
      $strCaptionTabela = 'Registros de Detalhamento';

      $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
      $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
      $strResultado .= '<tr>';
      if ($bolCheck) {
        $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
      }

      $strResultado .= '<th class="infraTh" width="15%">Tipo</th>'."\n";
      $strResultado .= '<th class="infraTh" width="10%">Localizador</th>'."\n";
      $strResultado .= '<th class="infraTh" width="10%">Estado</th>'."\n";
//      $strResultado .= '<th class="infraTh" width="10%">Documentos</th>'."\n";
      $strResultado .= '</tr>'."\n";
      $strCssTr='';
      for($i = 0;$i < $numRegistros; $i++){

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        if ($bolCheck){
          $strResultado .= '<td valign="top" align="center">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjLocalizadorDTO[$i]->getNumIdLocalizador(),$arrObjLocalizadorDTO[$i]->getNumIdLocalizador()).'</td>';
        }

        $strResultado .= '<td valign="top" align="center">';
        $strResultado .= PaginaSEI::tratarHTML($arrObjLocalizadorDTO[$i]->getStrNomeTipoLocalizador());
        $strResultado .= '</td>';

        $strResultado .= '<td valign="top" align="center">';
        $strLocalizador = LocalizadorINT::montarIdentificacaoRI1132($arrObjLocalizadorDTO[$i]->getStrSiglaTipoLocalizador(), $arrObjLocalizadorDTO[$i]->getNumSeqLocalizador());
        $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_protocolos_listar&acao_origem='.$_GET['acao'].'&id_localizador=' . $arrObjLocalizadorDTO[$i]->getNumIdLocalizador() );
        $strResultado .= '<a href="javascript:void(0);" onclick="abrirDetalhe(\'' . $strLink . '\');" alt="' . $strLocalizador . '" title="' . $strLocalizador . '" class="ancoraSigla">' . $strLocalizador . '</a>';

        $strResultado .= '</td>';

        $strResultado .= '<td valign="top" align="center">';
        $strEstado=$arrObjLocalizadorDTO[$i]->getStrStaEstado()==LocalizadorRN::$EA_ABERTO?'Aberto':'Fechado';
        $strResultado .= $strEstado;
        $strResultado .= '</td>';
        $strResultado .= '</tr>'."\n";
      }
      $strResultado .= '</table>';
    }

  } else {
  //registros de protocoloDTO - documentos arquivados ou recebidos

    if (isset($_GET['id_serie'])){
      $objArquivamentoDTO->setNumIdSerieDocumento($_GET['id_serie']);
      $objEstatisticasArquivamentoDTO->setNumIdSerieDocumento($_GET['id_serie']);
    }


    if($bolAcervo){
      $objArquivamentoDTO->retStrNomeSerieDocumento();
      $objArquivamentoDTO->retStrProtocoloFormatadoDocumento();
      $objArquivamentoDTO->retDblIdProtocolo();
      $objArquivamentoDTO->retNumIdSerieDocumento();
      $objArquivamentoDTO->retStrNumeroDocumento();
      PaginaSEI::getInstance()->prepararPaginacao($objArquivamentoDTO);
      $ret=$objArquivamentoRN->listar($objArquivamentoDTO);
      PaginaSEI::getInstance()->processarPaginacao($objArquivamentoDTO);
      if ($_GET['tipo']=='desarquivados'){
        $arrIdAtividade=InfraArray::converterArrInfraDTO($ret,'IdAtividadeDesarquivamento');
      }
    } else {
      $objEstatisticasArquivamentoDTO->retStrNomeSerie();
      $objEstatisticasArquivamentoDTO->retDblIdDocumento();
      $objEstatisticasArquivamentoDTO->retDthAberturaAtividade();
      $objEstatisticasArquivamentoDTO->retNumIdSerieDocumento();
      $objEstatisticasArquivamentoDTO->retStrNumeroDocumento();
      $objEstatisticasArquivamentoDTO->retStrProtocoloFormatado();
      $objEstatisticasArquivamentoDTO->setDistinct(true);
      $objEstatisticasArquivamentoDTO->setOrdDthAberturaAtividade(InfraDTO::$TIPO_ORDENACAO_DESC);
      PaginaSEI::getInstance()->prepararPaginacao($objEstatisticasArquivamentoDTO);
      $ret = $objEstatisticasRN->listarArquivamento($objEstatisticasArquivamentoDTO);
      PaginaSEI::getInstance()->processarPaginacao($objEstatisticasArquivamentoDTO);
      if ($_GET['tipo']=='desarquivados'){
        $arrIdAtividade=InfraArray::converterArrInfraDTO($ret,'IdAtividade');
      }
    }


    if (InfraArray::contar($arrIdAtividade)>0){
      $objAtributoAndamentoDTO=new AtributoAndamentoDTO();
      $objAtributoAndamentoDTO->setNumIdAtividade($arrIdAtividade,InfraDTO::$OPER_IN);
      $objAtributoAndamentoDTO->setStrNome('USUARIO');
      $objAtributoAndamentoDTO->retStrValor();
      $objAtributoAndamentoDTO->retNumIdAtividade();
      $objAtributoAndamentoRN=new AtributoAndamentoRN();

      $arrObjAtributoAndamentoDTO=$objAtributoAndamentoRN->listarRN1367($objAtributoAndamentoDTO);
      $arrObjAtributoAndamentoDTO=InfraArray::indexarArrInfraDTO($arrObjAtributoAndamentoDTO,'IdAtividade');
    }

    $numRegistros = InfraArray::contar($ret);

    if ($numRegistros > 0){

      $bolCheck = true;
      $bolAcaoImprimir = true;
      $bolAcaoProcedimentoTrabalhar = SessaoSEI::getInstance()->verificarPermissao('procedimento_trabalhar');
      $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');

      if ($bolAcaoImprimir){
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';

      }

      $strResultado = '';
      $strSumarioTabela = 'Tabela de Registros de Detalhamento.';
      $strCaptionTabela = 'Registros de Detalhamento';

      $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
      $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
      $strResultado .= '<tr>';
      if ($bolCheck) {
        $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
      }

      $strResultado .= '<th class="infraTh" width="15%">Documento</th>'."\n";
      $strResultado .= '<th class="infraTh" >Tipo do Documento</th>'."\n";
      $strResultado .= '<th class="infraTh" >Número</th>'."\n";
      if ($_GET['tipo']=='arquivados') {
        $strResultado .= '<th class="infraTh" width="35%">Tipo do Localizador</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="10%">Localizador</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="12%">Arquivamento</th>' . "\n";
      }elseif ($_GET['tipo']=='desarquivados'){
        $strResultado .= '<th class="infraTh" width="10%">Retirado por</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="10%">Localizador</th>' . "\n";
        $strResultado .= '<th class="infraTh" width="12%">Desarquivamento</th>'."\n";
      }elseif ($_GET['tipo']=='recebidos'){
        $strResultado .= '<th class="infraTh" >Recebimento</th>'."\n";
      }elseif ($_GET['tipo']=='eliminados_fisicos'){
        $strResultado .= '<th class="infraTh" >Eliminação</th>'."\n";
      }
      $strResultado .= '</tr>'."\n";
      $strCssTr='';
      for($i = 0;$i < $numRegistros; $i++){
        if (!$bolAcervo){//estatisticaArquivamentoDTO
          $idProtocolo=$ret[$i]->getDblIdDocumento();
          $protocoloFormatado=$ret[$i]->getStrProtocoloFormatado();
          $nomeSerie=PaginaSEI::tratarHTML($ret[$i]->getStrNomeSerie());
          if ($ret[$i]->isSetNumSeqLocalizador()) {
            $seqLocalizador=$ret[$i]->getNumSeqLocalizador();
            $idLocalizador=$ret[$i]->getNumIdLocalizadorArquivamento();
          }
          $dthArquivamento=$ret[$i]->getDthAberturaAtividade();
          if ($_GET['tipo']=='desarquivados'){
            $idAtividade=$ret[$i]->getNumIdAtividade();
          }

        } else {//arquivamentoDTO
          $idProtocolo=$ret[$i]->getDblIdProtocolo();
          $protocoloFormatado=$ret[$i]->getStrProtocoloFormatadoDocumento();
          $nomeSerie=PaginaSEI::tratarHTML($ret[$i]->getStrNomeSerieDocumento());
          if ($ret[$i]->isSetNumSeqLocalizadorLocalizador()) {
            $seqLocalizador = $ret[$i]->getNumSeqLocalizadorLocalizador();
            $idLocalizador=$ret[$i]->getNumIdLocalizador();
          }
          if ($_GET['tipo']=='arquivados') {
            $dthArquivamento = $ret[$i]->getDthAberturaArquivamento();
          } elseif ($_GET['tipo']=='desarquivados'){
            $dthArquivamento = $ret[$i]->getDthAberturaDesarquivamento();
            $idAtividade=$ret[$i]->getNumIdAtividadeDesarquivamento();
          } elseif ($_GET['tipo']=='recebidos'){
            $dthArquivamento = $ret[$i]->getDthAberturaRecebimento();
          } elseif ($_GET['tipo']=='eliminados_fisicos'){
            $dthArquivamento = $ret[$i]->getDthAberturaEliminacao();
          }

        }

        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        if ($bolCheck){
          $strResultado .= '<td valign="top">'.PaginaSEI::getInstance()->getTrCheck($i,$idProtocolo,$idProtocolo).'</td>';
        }

        $strResultado .= '<td valign="top" align="center">';
        if ($bolAcaoDocumentoVisualizar && $_GET['tipo']!='eliminados_fisicos'){
          $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_documento='.$idProtocolo).'" target="_blank" class="protocoloNormal" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.$nomeSerie.'">'.$protocoloFormatado.'</a>';
        }else{
          $strResultado .= PaginaSEI::tratarHTML($protocoloFormatado);
        }
        $strResultado .= '</td>';

        $strResultado .= '<td valign="top" align="center">';
        $strResultado .= '<a alt="'.$nomeSerie.'" title="'.$nomeSerie.'" class="ancoraSigla">'.$nomeSerie.'</a>';
        $strResultado .= '</td>';

        $strResultado .= '<td valign="top" align="center">';
        $strResultado .= PaginaSEI::tratarHTML($ret[$i]->getStrNumeroDocumento());
        $strResultado .= '</td>';

        if ($_GET['tipo']=='arquivados' ||$_GET['tipo']=='desarquivados') {
          $strResultado .= '<td valign="top" align="center">';
          if ($_GET['tipo']=='arquivados'){
            $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($ret[$i]->getStrNomeTipoLocalizador()) . '" title="' . PaginaSEI::tratarHTML($ret[$i]->getStrNomeTipoLocalizador()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($ret[$i]->getStrNomeTipoLocalizador()) . '</a>';
          } else {
            $strUsuario=explode('¥',$arrObjAtributoAndamentoDTO[$idAtividade]->getStrValor());

            $strResultado .= '<a alt="' . PaginaSEI::tratarHTML($strUsuario[1]);
            $strResultado .= '" title="' . PaginaSEI::tratarHTML($strUsuario[1]). '" class="ancoraSigla">';
            $strResultado .= PaginaSEI::tratarHTML($strUsuario[0]) . '</a>';
          }

          $strResultado .= '</td>';
          $strResultado .= '<td valign="top" align="center">';
          $strLocalizador = LocalizadorINT::montarIdentificacaoRI1132($ret[$i]->getStrSiglaTipoLocalizador(), $seqLocalizador);
          $strLink = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=localizador_protocolos_listar&acao_origem='.$_GET['acao'].'&id_localizador=' . $idLocalizador );


          $strResultado .= '<a href="javascript:void(0);" onclick="abrirDetalhe(\'' . $strLink . '\');" alt="' . $strLocalizador . '" title="' . $strLocalizador . '" class="ancoraSigla">' . $strLocalizador . '</a>';
          $strResultado .= '</td>';
        }
        $strResultado .= '<td valign="top" align="center">'.$dthArquivamento.'</td>';
        $strResultado .= '</tr>'."\n";
      }
      $strResultado .= '</table>';
    }
  }

  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton" style="width:8em"><span class="infraTeclaAtalho">F</span>echar</button>';

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
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  //document.getElementById('btnFechar').focus();
  infraEfeitoTabelas();
}
  function abrirDetalhe(link){
  infraAbrirJanelaModal(link,750,550);
  }
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmEstatisticasLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  //PaginaSEI::getInstance()->abrirAreaDados('5em');
  //PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>