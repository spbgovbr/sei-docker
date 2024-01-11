<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 02/10/2009 - criado por fbv@trf4.gov.br
*
* Versão do Gerador de Código: 1.29.1
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

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_bloco', 'id_grupo_bloco', 'sta_estado', 'nao_assinados'));

  PaginaSEI::getInstance()->salvarCamposPost(array('txtPalavrasPesquisaRelBlocoProtocolo'));

  if($_GET['acao_origem']=='bloco_selecionar_processo'){
  	PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  PaginaSEI::getInstance()->prepararSelecao('rel_bloco_protocolo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }

  switch($_GET['acao']){
    case 'rel_bloco_protocolo_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjRelBlocoProtocoloDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $arrStrIdComposto = explode('-',$arrStrIds[$i]);
          $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
          $objRelBlocoProtocoloDTO->setDblIdProtocolo($arrStrIdComposto[0]);
          $objRelBlocoProtocoloDTO->setNumIdBloco($arrStrIdComposto[1]);
          $arrObjRelBlocoProtocoloDTO[] = $objRelBlocoProtocoloDTO;
        }
        $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
        $objRelBlocoProtocoloRN->excluirRN1289($arrObjRelBlocoProtocoloDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].$strParametros));
      die;

    case 'rel_bloco_protocolo_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Processo/Documento','Selecionar Processos/Documentos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='rel_bloco_protocolo_cadastrar'){
        if (isset($_GET['id_protocolo']) && isset($_GET['id_bloco'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_protocolo'].'-'.$_GET['id_bloco']);
        }
      }
      break;

    case 'rel_bloco_protocolo_listar':

      $strTitulo = 'Documentos em Bloco';

      if (isset($_GET['id_bloco']) && $_GET['id_bloco']!='') {

        $strTitulo = 'Documentos do Bloco '.$_GET['id_bloco'];

        $objBlocoDTO = new BlocoDTO();
        $objBlocoDTO->retStrStaTipo();
        $objBlocoDTO->retStrStaEstado();
        $objBlocoDTO->retStrTipoDescricao();
        $objBlocoDTO->retNumIdUnidade();
        $objBlocoDTO->setNumIdBloco($_GET['id_bloco']);

        $objBlocoRN = new BlocoRN();
        $arrObjBlocoDTORet = $objBlocoRN->pesquisar($objBlocoDTO);

        if (count($arrObjBlocoDTORet)==0){
          throw new InfraException('Bloco '.$_GET['id_bloco'].' não encontrado.', null, null, false);
        }

        $objBlocoDTO = $arrObjBlocoDTORet[0];

        if ($objBlocoDTO->getNumIdUnidade()!=SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objBlocoDTO->getStrStaEstado()!=BlocoRN::$TE_RECEBIDO){
          throw new InfraException('Unidade ' . SessaoSEI::getInstance()->getStrSiglaUnidadeAtual() . ' não têm acesso ao bloco ' . $_GET['id_bloco'] . '.', null, null, false);
        }

        $strTitulo = '';

        switch($objBlocoDTO->getStrStaTipo()){

          case BlocoRN::$TB_ASSINATURA:
            $strTitulo = 'Documentos do Bloco de '.$objBlocoDTO->getStrTipoDescricao();
            break;

          case BlocoRN::$TB_REUNIAO:
            $strTitulo = 'Processos do Bloco de '.$objBlocoDTO->getStrTipoDescricao();
            break;

          case BlocoRN::$TB_INTERNO:
            $strTitulo = 'Processos do Bloco '.$objBlocoDTO->getStrTipoDescricao();
            break;

        }
        $strTitulo .= ' '.$_GET['id_bloco'];

      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'rel_bloco_protocolo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  $objBlocoDTO = new BlocoDTO();
  $objBlocoDTO->retNumIdBloco();
  $objBlocoDTO->retStrStaTipo();
  $objBlocoDTO->retStrStaEstado();
  $objBlocoDTO->retNumIdUnidade();
  $objBlocoDTO->retStrDescricao();
  $objBlocoDTO->retStrSiglaUnidade();
  $objBlocoDTO->retStrDescricaoUnidade();

  if (isset($_GET['id_bloco']) && $_GET['id_bloco']!=''){
    $objBlocoDTO->setNumIdBloco($_GET['id_bloco']);
  }else{

    $objBlocoDTO->setStrStaTipo(BlocoRN::$TB_ASSINATURA);

    if (isset($_GET['id_grupo_bloco'])){
      if ($_GET['id_grupo_bloco']=='-1'){
        $objBlocoDTO->setNumIdGrupoBlocoRelBlocoUnidade(null);
      }else {
        $objBlocoDTO->setNumIdGrupoBlocoRelBlocoUnidade($_GET['id_grupo_bloco']);
      }
    }


    $objBlocoDTO->setStrStaEstado(explode(',',$_GET['sta_estado']), InfraDTO::$OPER_IN);

  }

  $objBlocoRN = new BlocoRN();
  $arrObjBlocoDTO = InfraArray::indexarArrInfraDTO($objBlocoRN->pesquisar($objBlocoDTO),'IdBloco');

  $objBlocoDTOTitulo = null;

  if (count($arrObjBlocoDTO)) {

    if (count($arrObjBlocoDTO) == 1){
      $objBlocoDTOTitulo = array_values($arrObjBlocoDTO)[0];
    }

    $objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
    $objRelBlocoUnidadeDTO->retNumIdBloco();
    $objRelBlocoUnidadeDTO->retNumIdUnidade();
    $objRelBlocoUnidadeDTO->setNumIdBloco(array_keys($arrObjBlocoDTO), InfraDTO::$OPER_IN);

    $objRelBlocoUnidadeRN = new RelBlocoUnidadeRN();
    $arrObjRelBlocoUnidadeDTO = InfraArray::indexarArrInfraDTO($objRelBlocoUnidadeRN->listarRN1304($objRelBlocoUnidadeDTO), 'IdBloco', true);

    $arrIdNaoAssinados = array();

    if (isset($_GET['nao_assinados']) && $_GET['nao_assinados']=='1'){
      $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
      $objRelBlocoProtocoloDTO->retDblIdProtocolo();
      $objRelBlocoProtocoloDTO->retArrObjAssinaturaDTO();
      $objRelBlocoProtocoloDTO->setNumIdBloco(array_keys($arrObjBlocoDTO), InfraDTO::$OPER_IN);

      $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
      $arrObjRelBlocoProtocoloDTO = $objRelBlocoProtocoloRN->listarProtocolosBloco($objRelBlocoProtocoloDTO);

      foreach($arrObjRelBlocoProtocoloDTO as $objRelBlocoProtocoloDTO){
        if (count($objRelBlocoProtocoloDTO->getArrObjAssinaturaDTO())==0) {
          $arrIdNaoAssinados[] = $objRelBlocoProtocoloDTO->getDblIdProtocolo();
        }
      }
    }

    $objRelBlocoProtocoloDTO = new RelBlocoProtocoloDTO();
    $objRelBlocoProtocoloDTO->retDblIdProtocolo();
    $objRelBlocoProtocoloDTO->retNumIdBloco();
    $objRelBlocoProtocoloDTO->retNumSequencia();
    $objRelBlocoProtocoloDTO->retNumIdUnidadeBloco();
    $objRelBlocoProtocoloDTO->retStrProtocoloFormatadoProtocolo();
    $objRelBlocoProtocoloDTO->retStrStaProtocoloProtocolo();
    $objRelBlocoProtocoloDTO->retStrAnotacao();

    $objRelBlocoProtocoloDTO->setNumIdBloco(array_keys($arrObjBlocoDTO), InfraDTO::$OPER_IN);

    if (isset($_GET['nao_assinados']) && $_GET['nao_assinados']=='1'){
      if (InfraArray::contar($arrIdNaoAssinados)){
        $objRelBlocoProtocoloDTO->setDblIdProtocolo($arrIdNaoAssinados,InfraDTO::$OPER_IN);
      }else{
        $objRelBlocoProtocoloDTO->setDblIdProtocolo(null);
      }
    }else{
      foreach($arrObjBlocoDTO as $objBlocoDTO){
        if ($objBlocoDTO->getStrStaTipo() == BlocoRN::$TB_ASSINATURA){
          $objRelBlocoProtocoloDTO->retArrObjAssinaturaDTO();
          break;
        }
      }
    }

    $strPalavrasPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtPalavrasPesquisaRelBlocoProtocolo');
    if ($strPalavrasPesquisa!=''){
      $objRelBlocoProtocoloDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
    }

    $objRelBlocoProtocoloDTO->setOrdNumIdBloco(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objRelBlocoProtocoloDTO->setOrdNumSequencia(InfraDTO::$TIPO_ORDENACAO_ASC);

    PaginaSEI::getInstance()->prepararPaginacao($objRelBlocoProtocoloDTO,500);

    $objRelBlocoProtocoloRN = new RelBlocoProtocoloRN();
    $arrObjRelBlocoProtocoloDTO = $objRelBlocoProtocoloRN->listarProtocolosBloco($objRelBlocoProtocoloDTO);

    PaginaSEI::getInstance()->processarPaginacao($objRelBlocoProtocoloDTO);
    $numRegistros = count($arrObjRelBlocoProtocoloDTO);

    if ($numRegistros > 0) {

      $bolCheck = false;

      if ($_GET['acao'] == 'rel_bloco_protocolo_selecionar') {
        $bolAcaoDocumentoVisualizar = false;
        $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_consultar');
        $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_alterar');
        $bolAcaoDocumentoAssinar = false;
        $bolAcaoAcompanhamentoCadastrar = false;
        $bolAcaoImprimir = false;
        $bolAcaoExcluir = false;
        $bolCheck = true;
      } else {
        $bolAcaoDocumentoVisualizar = SessaoSEI::getInstance()->verificarPermissao('documento_visualizar');
        $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_consultar');
        $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_alterar');
        $bolAcaoDocumentoAssinar = SessaoSEI::getInstance()->verificarPermissao('documento_assinar');
        $bolAcaoAcompanhamentoCadastrar = SessaoSEI::getInstance()->verificarPermissao('acompanhamento_cadastrar');
        $bolAcaoImprimir = true;
        $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_excluir');
      }

      $bolPodeAssinar = false;
      $bolIncluirEmAcompanhamento = true;
      $bolExcluirMultiplo = false;
      foreach($arrObjBlocoDTO as $objBlocoDTO) {
        if ($objBlocoDTO->getStrStaTipo() == BlocoRN::$TB_ASSINATURA &&
            (($objBlocoDTO->getNumIdUnidade() == SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objBlocoDTO->getStrStaEstado()!=BlocoRN::$TE_DISPONIBILIZADO && $objBlocoDTO->getStrStaEstado()!=BlocoRN::$TE_CONCLUIDO) || $objBlocoDTO->getStrStaEstado() == BlocoRN::$TE_RECEBIDO)){
          $bolPodeAssinar = true;
        }

        if (!($objBlocoDTO->getStrStaTipo() == BlocoRN::$TB_INTERNO && $objBlocoDTO->getNumIdUnidade() == SessaoSEI::getInstance()->getNumIdUnidadeAtual())){
          $bolIncluirEmAcompanhamento = false;
        }

        if ($objBlocoDTO->getNumIdUnidade() == SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objBlocoDTO->getStrStaEstado() != BlocoRN::$TE_DISPONIBILIZADO){
          $bolExcluirMultiplo = true;
        }
      }

      if ($bolAcaoDocumentoAssinar && $bolPodeAssinar) {
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="A" id="btnAssinar" value="Assinar" onclick="acaoAssinaturaMultipla();" class="infraButton"><span class="infraTeclaAtalho">A</span>ssinar</button>';
        $strLinkAssinar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_assinar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_bloco='.$_GET['id_bloco']);
      }

      if ($bolAcaoAcompanhamentoCadastrar && $bolIncluirEmAcompanhamento){
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="" id="btnAcompanhamento" value="Acompanhamento" onclick="acaoAcompanhamentoMultipla();" class="infraButton">Incluir em Acompanhamento Especial</button>';
        $strLinkAcompanhamento = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=acompanhamento_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=acompanhamento_listar');
      }

      if ($bolAcaoExcluir && $bolExcluirMultiplo) {
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="R" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>etirar do Bloco</button>';
        $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_excluir&acao_origem='.$_GET['acao'].$strParametros);
      }


      if ($bolAcaoImprimir) {
        $bolCheck = true;
        $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton"><span class="infraTeclaAtalho">I</span>mprimir</button>';
      }


      $strResultado = '';
      $strArrJs = '';

      if (count($arrObjBlocoDTO) > 1) {
        $strSumarioTabela = 'Tabela de Documentos.';
        $strCaptionTabela = 'Documentos';
      }else{
        $strSumarioTabela = 'Tabela de Processos/Documentos.';
        $strCaptionTabela = 'Processos/Documentos';
      }

      $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
      $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros).'</caption>';
      $strResultado .= '<tr>';
      if ($bolCheck) {
        $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
      }

      if (count($arrObjBlocoDTO) > 1) {
        $strResultado .= '<th class="infraTh" width="5%">Bloco</th>'."\n";
      }

      $strResultado .= '<th class="infraTh" width="5%">Seq.</th>'."\n";

      if (count($arrObjBlocoDTO) > 1) {
        $strResultado .= '<th class="infraTh" width="5%">Unidade</th>'."\n";
      }

      $strResultado .= '<th class="infraTh" width="20%">Processo</th>'."\n";

      if ($objBlocoDTO->getStrStaTipo() == BlocoRN::$TB_ASSINATURA) {
        $strResultado .= '<th class="infraTh" width="10%">Documento</th>'."\n";
      }

      //$strResultado .= '<th class="infraTh" width="10%">Data</th>'."\n";
      $strResultado .= '<th class="infraTh" width="10%">Tipo</th>'."\n";

      if ($objBlocoDTO->getStrStaTipo() == BlocoRN::$TB_ASSINATURA) {
        $strResultado .= '<th class="infraTh" width="20%">Assinaturas</th>'."\n";
      }

      $strResultado .= '<th class="infraTh">Anotações</th>'."\n";
      $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
      $strResultado .= '</tr>'."\n";

      $strCssTr = '';
      $n = 0;
      $numPosicao = 0;

      foreach ($arrObjRelBlocoProtocoloDTO as $objRelBlocoProtocoloDTO) {

        $objBlocoDTO = $arrObjBlocoDTO[$objRelBlocoProtocoloDTO->getNumIdBloco()];
        $objProtocoloDTO = $objRelBlocoProtocoloDTO->getObjProtocoloDTO();

        $strCssTr = ($strCssTr == 'class="infraTrClara"') ? 'class="infraTrEscura"' : 'class="infraTrClara"';
        $strResultado .= '<tr id="trPos'.$numPosicao.'" '.$strCssTr.'>';

        if ($bolCheck) {
          $strResultado .= '<td>'.PaginaSEI::getInstance()->getTrCheck($n++, $objRelBlocoProtocoloDTO->getDblIdProtocolo().'-'.$objRelBlocoProtocoloDTO->getNumIdBloco(), $objRelBlocoProtocoloDTO->getStrProtocoloFormatadoProtocolo()).'</td>';
        }

        if (count($arrObjBlocoDTO) > 1) {
          $strResultado .= '<td align="center"><a '.PaginaSEI::montarTitleTooltip($objBlocoDTO->getStrDescricao()).' onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_bloco='.$objBlocoDTO->getNumIdBloco()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="ancoraPadraoPreta" style="color:'.(($objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_ABERTO || $objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_RETORNADO)?'green':'red').';">'.$objBlocoDTO->getNumIdBloco().'</a></td>';
        }

        $strResultado .= '<td align="center">'.$objRelBlocoProtocoloDTO->getNumSequencia().'</td>';

        if (count($arrObjBlocoDTO) > 1) {
          $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($objBlocoDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objBlocoDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objBlocoDTO->getStrSiglaUnidade()).'</a></td>';
        }

        $strClassProtocolo = '';
        if ($objProtocoloDTO->getStrSinAberto() == 'S') {
          $strClassProtocolo = 'protocoloAberto';
        } else {
          $strClassProtocolo = 'protocoloFechado';
        }

        if ($objBlocoDTO->getStrStaTipo() == BlocoRN::$TB_ASSINATURA) {


          $strResultado .= '<td  valign="middle" class="tdIdProcedimento'.$objProtocoloDTO->getDblIdProcedimentoDocumentoProcedimento().'" align="center"><a   onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$objProtocoloDTO->getDblIdProcedimentoDocumentoProcedimento().'&id_documento='.$objRelBlocoProtocoloDTO->getDblIdProtocolo()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="'.$strClassProtocolo.'  aIdProcedimento'.$objProtocoloDTO->getDblIdProcedimentoDocumentoProcedimento().'" alt="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoDocumento()).'" title="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoDocumento()).'">'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrProtocoloFormatadoProcedimentoDocumento()).'</a></td>';
          $strResultado .= "\n".'<td align="center">';

          if ($bolAcaoDocumentoVisualizar && ($objBlocoDTO->getNumIdUnidade() == SessaoSEI::getInstance()->getNumIdUnidadeAtual() || $objBlocoDTO->getStrStaEstado() == BlocoRN::$TE_RECEBIDO)) {
            $strResultado .= '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);infraAbrirJanelaModal(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_navegar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_bloco='.$objRelBlocoProtocoloDTO->getNumIdBloco().'&seq='.$objRelBlocoProtocoloDTO->getNumSequencia().'&posicao='.$numPosicao).'\',900,650);" href="#" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="'.$strClassProtocolo.'" title="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeSerieDocumento()).'">'.PaginaSEI::tratarHTML($objRelBlocoProtocoloDTO->getStrProtocoloFormatadoProtocolo()).'</a>';
            $strArrJs .= 'arrBloco['.$numPosicao.']="'.$objRelBlocoProtocoloDTO->getNumIdBloco().'";'."\n";
            $strArrJs .= 'arrSequencial['.$numPosicao.']="'.$objRelBlocoProtocoloDTO->getNumSequencia().'";'."\n";
            $strArrJs .= 'arrLinkDocumento['.$numPosicao.']="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&id_documento='.$objRelBlocoProtocoloDTO->getDblIdProtocolo()).'";'."\n";
            $strArrJs .= 'arrLinkProcedimento['.$numPosicao.']="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=bloco_navegar&id_procedimento='.$objProtocoloDTO->getDblIdProcedimentoDocumentoProcedimento().'&id_documento='.$objRelBlocoProtocoloDTO->getDblIdProtocolo()).'";'."\n";
            $strArrJs .= 'arrLinkAssinatura['.$numPosicao.']="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_assinar&acao_origem=bloco_navegar&acao_retorno=bloco_navegar&id_procedimento='.$objProtocoloDTO->getDblIdProcedimentoDocumentoProcedimento().'&id_documento='.$objRelBlocoProtocoloDTO->getDblIdProtocolo().'&id_bloco='.$objRelBlocoProtocoloDTO->getNumIdBloco()).'";'."\n";
            $numPosicao++;
          } else if ($bolAcaoDocumentoVisualizar) {
            $strResultado .= '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$objRelBlocoProtocoloDTO->getDblIdProtocolo()) .'" target="_blank" class="'.$strClassProtocolo.'" title="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeSerieDocumento()).'">'.PaginaSEI::tratarHTML($objRelBlocoProtocoloDTO->getStrProtocoloFormatadoProtocolo()).'</a>';
          }else{
            $strResultado .= '<span class="'.$strClassProtocolo.'">'.PaginaSEI::tratarHTML($objRelBlocoProtocoloDTO->getStrProtocoloFormatadoProtocolo()).'</span>';
          }

          $strResultado .= '</td>';
        } else {
          $strResultado .= '<td align="center">';
          $strResultado .= '<a onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$objRelBlocoProtocoloDTO->getDblIdProtocolo()).'" target="_blank" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="'.$strClassProtocolo.'" alt="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento()).'" title="'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento()).'">'.PaginaSEI::tratarHTML($objRelBlocoProtocoloDTO->getStrProtocoloFormatadoProtocolo()).'</a>';
          $strResultado .= '</td>';
        }

        //$strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($objProtocoloDTO->getDtaGeracao()).'</td>';

        if ($objRelBlocoProtocoloDTO->getStrStaProtocoloProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
          $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento()).'</td>';
        } else {
          $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($objProtocoloDTO->getStrNomeSerieDocumento()).'</td>';
        }

        if ($objBlocoDTO->getStrStaTipo() == BlocoRN::$TB_ASSINATURA){
          $strResultado .= '<td align="justified">';

          if (!(isset($_GET['nao_assinados']) && $_GET['nao_assinados']=='1')) {
            $strAssinaturas = AssinaturaINT::montarHtmlAssinaturas($objRelBlocoProtocoloDTO->getArrObjAssinaturaDTO());
            $strResultado .= $strAssinaturas;
          }

          $strResultado .= '</td>';
        }

        //$strResultado .= '<td>'.BlocoINT::montarTexto($n,$objRelBlocoProtocoloDTO->getStrAnotacao(),250).'</td>';
        $strResultado .= '<td>'.nl2br(InfraString::formatarXML($objRelBlocoProtocoloDTO->getStrAnotacao())).'</td>';


        $strResultado .= '<td align="center">';

        if ($bolAcaoDocumentoAssinar || $bolAcaoExcluir) {
          $strId = $objRelBlocoProtocoloDTO->getDblIdProtocolo().'-'.$objRelBlocoProtocoloDTO->getNumIdBloco();
          $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($objRelBlocoProtocoloDTO->getStrProtocoloFormatadoProtocolo());
        }

        $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($n, $objRelBlocoProtocoloDTO->getDblIdProtocolo().'-'.$objRelBlocoProtocoloDTO->getNumIdBloco());

        /*if ($bolAcaoConsultar){
          $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_consultar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_protocolo='.$objRelBlocoProtocoloDTO->getDblIdProtocolo().'&id_bloco='.$objRelBlocoProtocoloDTO->getNumIdBloco())).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeConsultar().'" title="Consultar Rel_Bloco_Protocolo" alt="Consultar Rel_Bloco_Protocolo" class="infraImg" /></a>&nbsp;';
        }*/

        if ($bolAcaoDocumentoAssinar && $bolPodeAssinar) {
          $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);acaoAssinar(\''.$strId.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::DOCUMENTO_ASSINAR.'" title="Assinar Documento" alt="Assinar Documento" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoAlterar && ($objBlocoDTO->getNumIdUnidade() == SessaoSEI::getInstance()->getNumIdUnidadeAtual() || $objBlocoDTO->getStrStaEstado() == BlocoRN::$TE_RECEBIDO)) {
          $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);acaoAlterar(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_documento='.$objRelBlocoProtocoloDTO->getDblIdProtocolo().'&id_bloco_anotacao='.$objRelBlocoProtocoloDTO->getNumIdBloco()).'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::BLOCO_ANOTACAO.'" title="Anotações" alt="Anotações" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoExcluir &&
            $objBlocoDTO->getStrStaEstado() != BlocoRN::$TE_DISPONIBILIZADO &&
            $objRelBlocoProtocoloDTO->getNumIdUnidadeBloco() == SessaoSEI::getInstance()->getNumIdUnidadeAtual()
        ) {
          $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoExcluir(\''.$strId.'\',\''.$strDescricao.'\',\''.$objRelBlocoProtocoloDTO->getStrStaProtocoloProtocolo().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Retirar Processo/Documento do Bloco" alt="Retirar Processo/Documento do Bloco" class="infraImg" /></a>&nbsp;';
        }
        $strResultado .= '</td>'."\n";

        $strResultado .= '</tr>'."\n";
      }
      $strResultado .= '</table>';
    }
  }

  $arrComandos[] = '<button type="button" onclick="pesquisar();" accesskey="P" id="btnPesquisar" name="btnPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  if ($_GET['acao'] == 'rel_bloco_protocolo_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_bloco'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strActionPadrao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros);

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
#lblPalavrasPesquisa {position:absolute;left:0%;top:10%;width:65%;}
#txtPalavrasPesquisaRelBlocoProtocolo {position:absolute;left:0%;top:48%;width:65%;}
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();

if ($strArrJs!=''){
  echo "arrBloco=[];\n";
  echo "arrSequencial=[];\n";
  echo "arrLinkDocumento=[];\n";
  echo "arrLinkProcedimento=[];\n";
  echo "arrLinkAssinatura=[];\n";
  echo "arrDocumentosVisualizados=[];\n";
  echo $strArrJs;
}
?>
var bolCarregamentoTela = true;
function inicializar(){

  if ('<?=$_GET['acao_origem']?>' != 'rel_bloco_protocolo_listar'){
    infraOcultarMenuSistemaEsquema();
  }

  if ('<?=$_GET['acao']?>'=='rel_bloco_protocolo_selecionar'){
    infraReceberSelecao();
    document.getElementById('btnFecharSelecao').focus();
  }else{
    document.getElementById('btnFechar').focus();
  }


  infraEfeitoTabelas();

}
<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc,tipo){

  var descTipo = '';

  if (tipo == '<?=ProtocoloRN::$TP_PROCEDIMENTO?>'){
    descTipo = 'processo';
  }else{
    descTipo = 'documento';
  }

  if (confirm("Confirma retirada do " + descTipo + " \"" + desc + "\" do bloco?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmRelBlocoProtocoloLista').target = '_self';
    document.getElementById('frmRelBlocoProtocoloLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmRelBlocoProtocoloLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum protocolo selecionado.');
    return;
  }
  if (confirm("Confirma retirada dos protocolos selecionados do bloco?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmRelBlocoProtocoloLista').target = '_self';
    document.getElementById('frmRelBlocoProtocoloLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmRelBlocoProtocoloLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoDocumentoAssinar){ ?>
function acaoAssinar(id){
  infraAbrirJanelaModal('<?=$strLinkAssinar?>',600,450);
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmRelBlocoProtocoloLista').target='modal-frame';
  document.getElementById('frmRelBlocoProtocoloLista').action='<?=$strLinkAssinar?>';
  document.getElementById('frmRelBlocoProtocoloLista').submit();
  document.getElementById('frmRelBlocoProtocoloLista').target='_self';
  document.getElementById('frmRelBlocoProtocoloLista').action='<?=$strActionPadrao?>';
}

function acaoAssinaturaMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum documento selecionado.');
    return;
  }

  infraAbrirJanelaModal('<?=$strLinkAssinar?>',600,450);

  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmRelBlocoProtocoloLista').target='modal-frame';
  document.getElementById('frmRelBlocoProtocoloLista').action='<?=$strLinkAssinar?>';
  document.getElementById('frmRelBlocoProtocoloLista').submit();
  document.getElementById('frmRelBlocoProtocoloLista').target='_self';
  document.getElementById('frmRelBlocoProtocoloLista').action='<?=$strActionPadrao?>';

}
<? } ?>

<? if ($bolAcaoAlterar){ ?>
function acaoAlterar(link){
  infraAbrirJanelaModal(link,700,400);
}
<? } ?>

<? if ($bolAcaoAcompanhamentoCadastrar){ ?>

function acaoAcompanhamentoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum processo selecionado.');
  return;
  }

  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmRelBlocoProtocoloLista').target = '_blank';
  document.getElementById('frmRelBlocoProtocoloLista').action = '<?=$strLinkAcompanhamento?>';
  document.getElementById('frmRelBlocoProtocoloLista').submit();
}
<? } ?>

function tratarDigitacao(ev){
  if (infraGetCodigoTecla(ev) == 13){
    document.getElementById('frmRelBlocoProtocoloLista').submit();
  }
  return true;
}
function pesquisar(){
  document.getElementById('frmRelBlocoProtocoloLista').submit();
}
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmRelBlocoProtocoloLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  if ($objBlocoDTOTitulo!=null && trim($objBlocoDTOTitulo->getStrDescricao())!='') {
    ?>
    <label id="lblDescricao" for="txaDescricao" accesskey="" class="infraLabelOpcional">Descrição:</label><br/>
    <div class="card" style="width:65%;">
      <div class="card-body" style="font-size:.875rem;padding:.25rem .5rem;">
        <?=PaginaSEI::tratarHTML($objBlocoDTOTitulo->getStrDescricao())?>
      </div>
    </div>
    <?
  }
  PaginaSEI::getInstance()->abrirAreaDados('5em');
  ?>

  <label id="lblPalavrasPesquisa" for="txtPalavrasPesquisaRelBlocoProtocolo" accesskey="" class="infraLabelOpcional">Palavras-chave para pesquisa:</label>
  <input type="text" id="txtPalavrasPesquisaRelBlocoProtocolo" name="txtPalavrasPesquisaRelBlocoProtocolo" class="infraText" value="<?=PaginaSEI::tratarHTML($strPalavrasPesquisa)?>" onkeypress="return tratarDigitacao(event);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

  <?
  PaginaSEI::getInstance()->fecharAreaDados();

  /*PaginaSEI::getInstance()->abrirAreaDados('10em');
  PaginaSEI::getInstance()->fecharAreaDados();*/
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>