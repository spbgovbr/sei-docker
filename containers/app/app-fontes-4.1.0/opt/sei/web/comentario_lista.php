<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/10/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.41.0
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

  PaginaSEI::getInstance()->prepararSelecao('comentario_selecionar');

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('arvore', 'pagina_simples', 'id_rel_protocolo_protocolo','id_comentario','id_procedimento'));

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('hdnVisualizacao'));

  $bolRecarregar=false;

  if (isset($_GET['recarregar'])) {
    $bolRecarregar=true;
  }

  if (isset($_GET['arvore'])) {
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
  }

  if (isset($_GET['pagina_simples'])) {
    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);
  }

  switch($_GET['acao']){
    case 'comentario_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjComentarioDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objComentarioDTO = new ComentarioDTO();
          $objComentarioDTO->setNumIdComentario($arrStrIds[$i]);
          $arrObjComentarioDTO[] = $objComentarioDTO;
        }
        $objComentarioRN = new ComentarioRN();
        $objComentarioRN->excluir($arrObjComentarioDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].'&resultado=1'));
      die;

/*
    case 'comentario_desativar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjComentarioDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objComentarioDTO = new ComentarioDTO();
          $objComentarioDTO->setNumIdComentario($arrStrIds[$i]);
          $arrObjComentarioDTO[] = $objComentarioDTO;
        }
        $objComentarioRN = new ComentarioRN();
        $objComentarioRN->desativar($arrObjComentarioDTO);
        PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'comentario_reativar':
      $strTitulo = 'Reativar Comentários';
      if ($_GET['acao_confirmada']=='sim'){
        try{
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $arrObjComentarioDTO = array();
          for ($i=0;$i<count($arrStrIds);$i++){
            $objComentarioDTO = new ComentarioDTO();
            $objComentarioDTO->setNumIdComentario($arrStrIds[$i]);
            $arrObjComentarioDTO[] = $objComentarioDTO;
          }
          $objComentarioRN = new ComentarioRN();
          $objComentarioRN->reativar($arrObjComentarioDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
        header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
        die;
      }
      break;

 */
    case 'comentario_selecionar':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Comentário','Selecionar Comentários');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='comentario_cadastrar'){
        if (isset($_GET['id_comentario'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_comentario']);
        }
      }
      break;

    case 'comentario_listar':
      if ($_GET['acao_origem'] == 'arvore_visualizar' && SessaoSEI::getInstance()->verificarPermissao('comentario_cadastrar')) {

        $dto = new ComentarioDTO();
        $dto->setNumMaxRegistrosRetorno(1);
        $dto->retNumIdComentario();

        if ($_GET['id_rel_protocolo_protocolo']!='') {
          $dto->setDblIdRelProtocoloProtocolo($_GET['id_rel_protocolo_protocolo']);
        }else{
          $dto->setDblIdProcedimento($_GET['id_procedimento']);
        }

        $objComentarioRN = new ComentarioRN();
        if ($objComentarioRN->consultar($dto) == null) {
          header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=comentario_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao']));
          die;
        }
      }

      $strTitulo = 'Comentários';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  if ($_GET['acao'] == 'comentario_selecionar'){
    $arrComandos[] = '<button type="button" accesskey="T" id="btnTransportarSelecao" value="Transportar" onclick="infraTransportarSelecao();" class="infraButton"><span class="infraTeclaAtalho">T</span>ransportar</button>';
  }

  /* if ($_GET['acao'] == 'comentario_listar' || $_GET['acao'] == 'comentario_selecionar'){ */

  /* } */

  $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('comentario_cadastrar');
  if ($bolAcaoCadastrar){
    $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=comentario_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
  }

  $objComentarioRN = new ComentarioRN();
  $objProtocoloRN = new ProtocoloRN();

  $strVisualizacao = PaginaSEI::getInstance()->recuperarCampo('hdnVisualizacao','P');

  if ($_GET['id_rel_protocolo_protocolo']!='') {

    $strVisualizacao = 'A';

    $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
    $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
    $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo($_GET['id_rel_protocolo_protocolo']);

    $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
    $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

    if ($objRelProtocoloProtocoloDTO == null){
      throw new InfraException('Protocolo não encontrado no processo.');
    }

    $objProtocoloDTO = new ProtocoloDTO();
    $objProtocoloDTO->retDblIdProtocolo();
    $objProtocoloDTO->retStrProtocoloFormatado();
    $objProtocoloDTO->retStrStaProtocolo();
    $objProtocoloDTO->retStrNomeTipoProcedimentoProcedimento();
    $objProtocoloDTO->retStrNomeSerieDocumento();
    $objProtocoloDTO->retStrNumeroDocumento();
    $objProtocoloDTO->setDblIdProtocolo($objRelProtocoloProtocoloDTO->getDblIdProtocolo2());
    $objProtocoloDTOArvore = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

    if ($objProtocoloDTOArvore == null){
      throw  new InfraException('Protocolo não encontrado.');
    }

  }else {

    if ($strVisualizacao == 'A'){
      $strVisualizacao = 'P';
    }

    $objProtocoloDTO = new ProtocoloDTO();
    $objProtocoloDTO->retDblIdProtocolo();
    $objProtocoloDTO->retStrProtocoloFormatado();
    $objProtocoloDTO->retStrNomeTipoProcedimentoProcedimento();
    $objProtocoloDTO->setDblIdProtocolo($_GET['id_procedimento']);
    $objProtocoloDTOProcedimento = $objProtocoloRN->consultarRN0186($objProtocoloDTO);

    if ($objProtocoloDTOProcedimento == null) {
      throw new InfraException('Processo não encontrado.');
    }
  }

  $objComentarioDTO = new ComentarioDTO();
  $objComentarioDTO->retNumIdComentario();
  $objComentarioDTO->retDblIdProcedimento();
  $objComentarioDTO->retDblIdRelProtocoloProtocolo();
  $objComentarioDTO->retNumIdUnidade();
  $objComentarioDTO->retStrDescricao();
  $objComentarioDTO->retDthComentario();
  $objComentarioDTO->retStrSiglaUsuario();
  $objComentarioDTO->retStrNomeUsuario();
  $objComentarioDTO->retStrSiglaUnidade();
  $objComentarioDTO->retStrNomeUnidade();

  if ($strVisualizacao == 'T') {
    $objComentarioDTO->retDblIdProtocolo2();
  }

  $objComentarioDTO->setDblIdProcedimento($_GET['id_procedimento']);

  if ($strVisualizacao == 'P') {
    $objComentarioDTO->setDblIdRelProtocoloProtocolo(null);
  }else if ($strVisualizacao == 'A') {
    $objComentarioDTO->setDblIdRelProtocoloProtocolo($_GET['id_rel_protocolo_protocolo']);
  }else {

    $objComentarioDTO->setDblIdRelProtocoloProtocolo(null);
    $arrObjProtocoloDTO = array();

    $objComentarioDTOProtocolos = new ComentarioDTO();
    $objComentarioDTOProtocolos->setDistinct(true);
    $objComentarioDTOProtocolos->retDblIdRelProtocoloProtocolo();
    $objComentarioDTOProtocolos->retDblIdProtocolo2();
    $objComentarioDTOProtocolos->setDblIdProcedimento($_GET['id_procedimento']);
    $objComentarioDTOProtocolos->setDblIdRelProtocoloProtocolo(null, InfraDTO::$OPER_DIFERENTE);
    $arrIdProtocolosComComentario = InfraArray::converterArrInfraDTO($objComentarioRN->listar($objComentarioDTOProtocolos),'IdProtocolo2');

    if (count($arrIdProtocolosComComentario)) {

      $objPesquisaProtocoloDTO = new PesquisaProtocoloDTO();
      $objPesquisaProtocoloDTO->setStrStaTipo(ProtocoloRN::$TPP_TODOS);
      $objPesquisaProtocoloDTO->setStrStaAcesso(ProtocoloRN::$TAP_AUTORIZADO);
      $objPesquisaProtocoloDTO->setDblIdProtocolo($arrIdProtocolosComComentario);

      $objProtocoloRN = new ProtocoloRN();
      $arrObjProtocoloDTO = InfraArray::indexarArrInfraDTO($objProtocoloRN->pesquisarRN0967($objPesquisaProtocoloDTO), 'IdProtocolo');

      if (count($arrObjProtocoloDTO)) {

        $objComentarioDTO->unSetDblIdRelProtocoloProtocolo();

        $objComentarioDTO->adicionarCriterio(array('IdRelProtocoloProtocolo', 'IdProtocolo2'),
                                             array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IN),
                                             array(null, array_keys($arrObjProtocoloDTO)),
                                             InfraDTO::$OPER_LOGICO_OR);
      }
    }
  }

  /*
    if ($_GET['acao'] == 'comentario_reativar'){
      //Lista somente inativos
      $objComentarioDTO->setBolExclusaoLogica(false);
      $objComentarioDTO->setStrSinAtivo('N');
    }
   */
  PaginaSEI::getInstance()->prepararOrdenacao($objComentarioDTO, 'Comentario', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSEI::getInstance()->prepararPaginacao($objComentarioDTO);

  $arrObjComentarioDTO = $objComentarioRN->listar($objComentarioDTO);

  PaginaSEI::getInstance()->processarPaginacao($objComentarioDTO);
  $numRegistros = count($arrObjComentarioDTO);


  if ($numRegistros > 0){

    $bolCheck = false;

    if ($_GET['acao']=='comentario_selecionar'){
      $bolAcaoReativar = false;
      //$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('comentario_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('comentario_alterar');
      $bolAcaoImprimir = false;
      //$bolAcaoGerarPlanilha = false;
      $bolAcaoExcluir = false;
      $bolAcaoDesativar = false;
      $bolCheck = true;
/*     }else if ($_GET['acao']=='comentario_reativar'){
      $bolAcaoReativar = SessaoSEI::getInstance()->verificarPermissao('comentario_reativar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('comentario_consultar');
      $bolAcaoAlterar = false;
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('comentario_excluir');
      $bolAcaoDesativar = false;
 */ }else{
      $bolAcaoReativar = false;
      //$bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('comentario_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('comentario_alterar');
      $bolAcaoImprimir = true;
      //$bolAcaoGerarPlanilha = SessaoSEI::getInstance()->verificarPermissao('infra_gerar_planilha_tabela');
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('comentario_excluir');
      $bolAcaoDesativar = SessaoSEI::getInstance()->verificarPermissao('comentario_desativar');
    }

    /*
    if ($bolAcaoDesativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="t" id="btnDesativar" value="Desativar" onclick="acaoDesativacaoMultipla();" class="infraButton">Desa<span class="infraTeclaAtalho">t</span>ivar</button>';
      $strLinkDesativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=comentario_desativar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoReativar){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="R" id="btnReativar" value="Reativar" onclick="acaoReativacaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">R</span>eativar</button>';
      $strLinkReativar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=comentario_reativar&acao_origem='.$_GET['acao'].'&acao_confirmada=sim');
    }
     */

    if ($bolAcaoExcluir){
      //$bolCheck = true;
      //$arrComandos[] = '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">E</span>xcluir</button>';
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=comentario_excluir&acao_origem='.$_GET['acao']);
    }

    /*
    if ($bolAcaoGerarPlanilha){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="P" id="btnGerarPlanilha" value="Gerar Planilha" onclick="infraGerarPlanilhaTabela(\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=infra_gerar_planilha_tabela').'\');" class="infraButton">Gerar <span class="infraTeclaAtalho">P</span>lanilha</button>';
    }
    */


    $strResultado = '';

    /* if ($_GET['acao']!='comentario_reativar'){ */
      $strSumarioTabela = 'Tabela de Comentários.';
      $strCaptionTabela = 'Comentários';
    /* }else{
      $strSumarioTabela = 'Tabela de Comentários Inativos.';
      $strCaptionTabela = 'Comentários Inativos';
    } */

    $strResultado .= '<table id="tblComentarios" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<th class="infraTh" width="1%" style="display:none">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";

    if ($strVisualizacao=='T') {
      $strResultado .= '<th class="infraTh" width="15%">Data</th>'."\n";
      $strResultado .= '<th class="infraTh" width="20%">Protocolo</th>'."\n";
      $strResultado .= '<th class="infraTh">Tipo</th>'."\n";
      $strResultado .= '<th class="infraTh">Unidade</th>'."\n";
      $strResultado .= '<th class="infraTh">Usuário</th>'."\n";
    }else{

      if ($strVisualizacao == 'A') {
        if ($objProtocoloDTOArvore->getStrStaProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO || $objProtocoloDTOArvore->getStrStaProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){
          $strTitulo = 'Comentários - '.$objProtocoloDTOArvore->getStrNomeSerieDocumento().' '.$objProtocoloDTOArvore->getStrNumeroDocumento(). ' ('.$objProtocoloDTOArvore->getStrProtocoloFormatado().')';
        }else{
          $strTitulo = 'Comentários - '.$objProtocoloDTOArvore->getStrNomeTipoProcedimentoProcedimento().' ('.$objProtocoloDTOArvore->getStrProtocoloFormatado().')';
        }
      }

      $strResultado .= '<th class="infraTh" width="30%">Data</th>'."\n";
      $strResultado .= '<th class="infraTh" width="30%">Unidade</th>'."\n";
      $strResultado .= '<th class="infraTh">Usuário</th>'."\n";

    }

    $strResultado .= '<th class="infraTh" width="10%">Ações</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';


    for($i = 0;$i < $numRegistros; $i++){

      if ($arrObjComentarioDTO[$i]->getDblIdRelProtocoloProtocolo() == null) {
        $strProtocoloFormatado = $objProtocoloDTOProcedimento->getStrProtocoloFormatado();
        $strTipoProtocolo = $objProtocoloDTOProcedimento->getStrNomeTipoProcedimentoProcedimento();
        $strLinkProtocolo = '<a href="javascript:void(0);" class="protocoloNormal" title="'.PaginaSEI::tratarHTML($strTipoProtocolo).'">'.$strProtocoloFormatado.'</a>';
      }else{

        if ($strVisualizacao == 'A'){
          $objProtocoloDTO = $objProtocoloDTOArvore;
        }else{
          $objProtocoloDTO = $arrObjProtocoloDTO[$arrObjComentarioDTO[$i]->getDblIdProtocolo2()];
        }

        $strProtocoloFormatado = $objProtocoloDTO->getStrProtocoloFormatado();
        if ($objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_DOCUMENTO_GERADO || $objProtocoloDTO->getStrStaProtocolo()==ProtocoloRN::$TP_DOCUMENTO_RECEBIDO){
          $strTipoProtocolo = $objProtocoloDTO->getStrNomeSerieDocumento().' '.$objProtocoloDTO->getStrNumeroDocumento();
          $strLinkProtocolo = '<a class="ancoraPadraoAzul" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_visualizar&acao_origem='.$_GET['acao'].'&id_documento='.$objProtocoloDTO->getDblIdProtocolo()).'" target="_blank" title="'.PaginaSEI::tratarHTML($strTipoProtocolo).'">'.$strProtocoloFormatado.'</a>';
        }else{
          $strTipoProtocolo = $objProtocoloDTO->getStrNomeTipoProcedimentoProcedimento();
          $arrParametrosRepasseLink = SessaoSEI::getInstance()->getArrParametrosRepasseLink();
          SessaoSEI::getInstance()->setArrParametrosRepasseLink(null);
          $strLinkProtocolo = '<a class="ancoraPadraoAzul" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&id_procedimento='.$objProtocoloDTO->getDblIdProtocolo()).'" target="_blank" title="'.PaginaSEI::tratarHTML($strTipoProtocolo).'">'.$strProtocoloFormatado.'</a>';
          SessaoSEI::getInstance()->setArrParametrosRepasseLink($arrParametrosRepasseLink);
        }
      }

      if ($i){
        $strResultado .= '<tr class="trSeparadorComentario"><td colspan="6">&nbsp;</td></tr>';
      }
      //$strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= '<tr class="infraTrEscura">';

      $strResultado .= '<td align="center" style="display:none">'.PaginaSEI::getInstance()->getTrCheck($i,$arrObjComentarioDTO[$i]->getNumIdComentario(),$arrObjComentarioDTO[$i]->getNumIdComentario()).'</td>';
      $strResultado .= '<td  align="center">'.PaginaSEI::tratarHTML($arrObjComentarioDTO[$i]->getDthComentario()).'</td>';

      if ($strVisualizacao=='T') {
        $strResultado .= '<td  align="center">'.$strLinkProtocolo.'</td>';
        $strResultado .= '<td  align="center">'.$strTipoProtocolo.'</td>';
      }

      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjComentarioDTO[$i]->getStrNomeUnidade()).'" title="'.PaginaSEI::tratarHTML($arrObjComentarioDTO[$i]->getStrNomeUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjComentarioDTO[$i]->getStrSiglaUnidade()).'</a></td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaSEI::tratarHTML($arrObjComentarioDTO[$i]->getStrNomeUsuario()).'" title="'.PaginaSEI::tratarHTML($arrObjComentarioDTO[$i]->getStrNomeUsuario()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjComentarioDTO[$i]->getStrSiglaUsuario()).'</a></td>';
      $strResultado .= '<td align="center">';
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i, $arrObjComentarioDTO[$i]->getNumIdComentario());

//          if ($bolAcaoConsultar) {
//            $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=comentario_consultar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_comentario=' . $arrObjComentarioDTO[$i]->getNumIdComentario()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeConsultar() . '" title="Consultar Comentário" alt="Consultar Comentário" class="infraImg" /></a>&nbsp;';
//          }

      if(SessaoSEI::getInstance()->getNumIdUnidadeAtual() == $arrObjComentarioDTO[$i]->getNumIdUnidade()) {

        if ($bolAcaoAlterar) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=comentario_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_comentario=' . $arrObjComentarioDTO[$i]->getNumIdComentario()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeAlterar() . '" title="Alterar Comentário" alt="Alterar Comentário" class="infraImg" /></a>&nbsp;';
        }

        if ($bolAcaoDesativar || $bolAcaoReativar || $bolAcaoExcluir) {
          $strId = $arrObjComentarioDTO[$i]->getNumIdComentario();
          $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjComentarioDTO[$i]->getNumIdComentario());
        }
        /*
              if ($bolAcaoDesativar){
                $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoDesativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeDesativar().'" title="Desativar Comentário" alt="Desativar Comentário" class="infraImg" /></a>&nbsp;';
              }

              if ($bolAcaoReativar){
                $strResultado .= '<a href="'.PaginaSEI::getInstance()->montarAncora($strId).'" onclick="acaoReativar(\''.$strId.'\',\''.$strDescricao.'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeReativar().'" title="Reativar Comentário" alt="Reativar Comentário" class="infraImg" /></a>&nbsp;';
              }
         */

        if ($bolAcaoExcluir) {
          $strResultado .= '<a href="' . PaginaSEI::getInstance()->montarAncora($strId) . '" onclick="acaoExcluir(\'' . $strId . '\',\'' . $strDescricao . '\');" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="' . PaginaSEI::getInstance()->getIconeExcluir() . '" title="Excluir Comentário" alt="Excluir Comentário" class="infraImg" /></a>&nbsp;';
        }
      }
      $strResultado .= '</td></tr>'."\n";

      $strResultado .= '<tr class="trComentario">';
      $strResultado .= '<td colspan="6">'.nl2br(PaginaSEI::tratarHTML($arrObjComentarioDTO[$i]->getStrDescricao())).'</td>';
      $strResultado .= '</tr>'."\n";
    }

    $strResultado .= '</table>';
  }

//  if ($_GET['acao'] == 'comentario_selecionar'){
//    $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
//  }else{
//    $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
//  }

   $strLinkMontarArvore = '';
   if (($_GET['acao_origem']=='comentario_cadastrar' || $_GET['acao_origem']=='comentario_excluir') && $_GET['resultado']=='1') {

     if ($_GET['id_rel_protocolo_protocolo']!=''){
       $objRelProtocoloProtocoloDTO = new RelProtocoloProtocoloDTO();
       $objRelProtocoloProtocoloDTO->retStrStaAssociacao();
       $objRelProtocoloProtocoloDTO->retDblIdProtocolo2();
       $objRelProtocoloProtocoloDTO->setDblIdRelProtocoloProtocolo($_GET['id_rel_protocolo_protocolo']);

       $objRelProtocoloProtocoloRN = new RelProtocoloProtocoloRN();
       $objRelProtocoloProtocoloDTO = $objRelProtocoloProtocoloRN->consultarRN0841($objRelProtocoloProtocoloDTO);

       $strPosicionar = '';
       if ($objRelProtocoloProtocoloDTO!=null){
         if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao()==RelProtocoloProtocoloRN::$TA_PROCEDIMENTO_ANEXADO){
           $strPosicionar = '&id_procedimento_anexado='.$objRelProtocoloProtocoloDTO->getDblIdProtocolo2();
         }else if ($objRelProtocoloProtocoloDTO->getStrStaAssociacao()==RelProtocoloProtocoloRN::$TA_DOCUMENTO_ASSOCIADO){
           $strPosicionar = '&id_documento='.$objRelProtocoloProtocoloDTO->getDblIdProtocolo2();
         }
       }
     }

     $strLinkMontarArvore = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_visualizar&acao_origem='.$_GET['acao'].$strPosicionar.'&montar_visualizacao=0');
   }

  if ($strVisualizacao=='T' || $strVisualizacao=='P') {
    if ($strVisualizacao == 'T') {
      $strLinkVisualizacao = '<a id="ancVisualizacao" href="javascript:void(0);" onclick="verComentarios(\'P\');" class="ancoraPadraoPreta">Ver somente do processo</a>';
    } else {
      $strLinkVisualizacao = '<a id="ancVisualizacao" href="javascript:void(0);" onclick="verComentarios(\'T\');" class="ancoraPadraoPreta">Ver todos</a>';
    }
  }


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
<?if(0){?><style><?}?>

#tblComentarios{
  border:0;
  border-spacing:0;
}

tr.infraTrEscura td {
  border:1px solid #ccc;
}

tr.trComentario td {
  background-color: white;
  border-left:1px solid #ccc;
  border-right:1px solid #ccc;
  border-bottom:1px solid #ccc;
}


tr.trSeparadorComentario td {
  background-color: white;
}

  <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){

  <?if ($strLinkMontarArvore!='') { ?>
  parent.parent.document.getElementById('ifrArvore').src = '<?=$strLinkMontarArvore?>';
  <?}?>

  infraEfeitoTabelas();

}

<? if ($bolAcaoDesativar){ ?>
function acaoDesativar(id,desc){
  if (confirm("Confirma desativação do Comentário \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmComentarioLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmComentarioLista').submit();
  }
}

function acaoDesativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Comentário selecionado.');
    return;
  }
  if (confirm("Confirma desativação dos Comentários selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmComentarioLista').action='<?=$strLinkDesativar?>';
    document.getElementById('frmComentarioLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoReativar){ ?>
function acaoReativar(id,desc){
  if (confirm("Confirma reativação do Comentário \""+desc+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmComentarioLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmComentarioLista').submit();
  }
}

function acaoReativacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Comentário selecionado.');
    return;
  }
  if (confirm("Confirma reativação dos Comentários selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmComentarioLista').action='<?=$strLinkReativar?>';
    document.getElementById('frmComentarioLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id,desc){
  if (confirm("Confirma exclusão do Comentário?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmComentarioLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmComentarioLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Comentário selecionado.');
    return;
  }
  if (confirm("Confirma exclusão dos Comentários selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmComentarioLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmComentarioLista').submit();
  }
}
<? } ?>

function verComentarios(valor){
  document.getElementById('hdnVisualizacao').value = valor;
  document.getElementById('frmComentarioLista').submit();
}

  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmComentarioLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?

  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  echo $strLinkVisualizacao;
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
  <input type="hidden" id="hdnVisualizacao" name="hdnVisualizacao" value="<?=$strVisualizacao?>" />
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
