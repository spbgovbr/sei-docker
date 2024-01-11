<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 25/09/2009 - criado por fbv@trf4.gov.br
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

  PaginaSEI::getInstance()->prepararSelecao('bloco_selecionar_processo');
  PaginaSEI::getInstance()->prepararSelecao('bloco_selecionar_documento');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  if ($_GET['acao_origem'] == 'painel_controle_visualizar' || $_GET['acao_origem'] == 'grupo_bloco_listar') {

    if (isset($_GET['sta_estado'])) {

      PaginaSEI::getInstance()->salvarCampo('txtPalavrasPesquisaBloco', '');

      $arrEstadoUrl = explode(',', $_GET['sta_estado']);

      PaginaSEI::getInstance()->salvarCampo('chkSinEstadoGerado', (in_array(BlocoRN::$TE_ABERTO, $arrEstadoUrl) ? 'S' : 'N'));
      PaginaSEI::getInstance()->salvarCampo('chkSinEstadoDisponibilizado', (in_array(BlocoRN::$TE_DISPONIBILIZADO, $arrEstadoUrl) ? 'S' : 'N'));
      PaginaSEI::getInstance()->salvarCampo('chkSinEstadoRecebido', (in_array(BlocoRN::$TE_RECEBIDO, $arrEstadoUrl) ? 'S' : 'N'));
      PaginaSEI::getInstance()->salvarCampo('chkSinEstadoRetornado', (in_array(BlocoRN::$TE_RETORNADO, $arrEstadoUrl) ? 'S' : 'N'));
      PaginaSEI::getInstance()->salvarCampo('chkSinEstadoConcluido', (in_array(BlocoRN::$TE_CONCLUIDO, $arrEstadoUrl) ? 'S' : 'N'));

      if (isset($_GET['id_grupo_bloco'])) {
        if ($_GET['id_grupo_bloco'] == '-1') {
          PaginaSEI::getInstance()->salvarCampo('selGrupoBloco', 'null');
        } else {
          PaginaSEI::getInstance()->salvarCampo('selGrupoBloco', $_GET['id_grupo_bloco']);
        }
      } else {
        PaginaSEI::getInstance()->salvarCampo('selGrupoBloco', '');
      }

      PaginaSEI::getInstance()->salvarCampo('selUnidadeGeradora', '');
      PaginaSEI::getInstance()->salvarCampo('hdnMeusBlocos', BlocoRN::$TA_TODAS);
      PaginaSEI::getInstance()->salvarCampo('chkSinPrioridade', 'N');
      PaginaSEI::getInstance()->salvarCampo('chkSinRevisao', 'N');
      PaginaSEI::getInstance()->salvarCampo('chkSinComentario', 'N');
    }

  }else if ($_GET['acao_origem'] == 'bloco_escolher'){

    PaginaSEI::getInstance()->salvarCampo('txtPalavrasPesquisaBloco', '');
    PaginaSEI::getInstance()->salvarCampo('selGrupbtnExcluiroBloco', '');
    PaginaSEI::getInstance()->salvarCampo('selUnidadeGeradora', '');
    PaginaSEI::getInstance()->salvarCampo('hdnMeusBlocos', BlocoRN::$TA_TODAS);

    PaginaSEI::getInstance()->salvarCampo('chkSinEstadoGerado', 'S');
    PaginaSEI::getInstance()->salvarCampo('chkSinEstadoDisponibilizado', 'S');
    PaginaSEI::getInstance()->salvarCampo('chkSinEstadoRecebido', 'S');
    PaginaSEI::getInstance()->salvarCampo('chkSinEstadoRetornado', 'S');
    PaginaSEI::getInstance()->salvarCampo('chkSinEstadoConcluido', 'N');

    PaginaSEI::getInstance()->salvarCampo('chkSinPrioridade', 'N');
    PaginaSEI::getInstance()->salvarCampo('chkSinRevisao', 'N');
    PaginaSEI::getInstance()->salvarCampo('chkSinComentario', 'N');

  }else {

    PaginaSEI::getInstance()->salvarCamposPost(array('txtPalavrasPesquisaBloco', 'selGrupoBloco', 'selUnidadeGeradora', 'hdnMeusBlocos'));

    if (isset($_POST['hdnFlagBlocos'])) {
      PaginaSEI::getInstance()->salvarCampo('chkSinEstadoGerado', PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEstadoGerado']));
      PaginaSEI::getInstance()->salvarCampo('chkSinEstadoDisponibilizado', PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEstadoDisponibilizado']));
      PaginaSEI::getInstance()->salvarCampo('chkSinEstadoRecebido', PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEstadoRecebido']));
      PaginaSEI::getInstance()->salvarCampo('chkSinEstadoRetornado', PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEstadoRetornado']));
      PaginaSEI::getInstance()->salvarCampo('chkSinEstadoConcluido', PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEstadoConcluido']));

      PaginaSEI::getInstance()->salvarCampo('chkSinPrioridade', PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPrioridade']));
      PaginaSEI::getInstance()->salvarCampo('chkSinRevisao', PaginaSEI::getInstance()->getCheckbox($_POST['chkSinRevisao']));
      PaginaSEI::getInstance()->salvarCampo('chkSinComentario', PaginaSEI::getInstance()->getCheckbox($_POST['chkSinComentario']));
    }
  }

  switch($_GET['acao']){
    case 'bloco_excluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjBlocoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objBlocoDTO = new BlocoDTO();
          $objBlocoDTO->setNumIdBloco($arrStrIds[$i]);
          $arrObjBlocoDTO[] = $objBlocoDTO;
        }
        $objBlocoRN = new BlocoRN();
        $objBlocoRN->excluirRN1275($arrObjBlocoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'bloco_disponibilizar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjBlocoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objBlocoDTO = new BlocoDTO();
          $objBlocoDTO->setNumIdBloco($arrStrIds[$i]);
          $arrObjBlocoDTO[] = $objBlocoDTO;
        }
        $objBlocoRN = new BlocoRN();
        $objBlocoRN->disponibilizar($arrObjBlocoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($arrStrIds)));
      die;

    case 'bloco_cancelar_disponibilizacao':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjBlocoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objBlocoDTO = new BlocoDTO();
          $objBlocoDTO->setNumIdBloco($arrStrIds[$i]);
          $arrObjBlocoDTO[] = $objBlocoDTO;
        }
        $objBlocoRN = new BlocoRN();
        $objBlocoRN->cancelarDisponibilizacao($arrObjBlocoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($arrStrIds)));
      die;
      
    case 'bloco_retornar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjBlocoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objBlocoDTO = new BlocoDTO();
          $objBlocoDTO->setNumIdBloco($arrStrIds[$i]);
          $arrObjBlocoDTO[] = $objBlocoDTO;
        }
        $objBlocoRN = new BlocoRN();
        $objBlocoRN->retornar($arrObjBlocoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;

    case 'bloco_concluir':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjBlocoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objBlocoDTO = new BlocoDTO();
          $objBlocoDTO->setNumIdBloco($arrStrIds[$i]);
          $arrObjBlocoDTO[] = $objBlocoDTO;
        }
        $objBlocoRN = new BlocoRN();
        $objBlocoRN->concluir($arrObjBlocoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      } 
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao']));
      die;
      
    case 'bloco_reabrir':
    	try {
    		$objBlocoDTO = new BlocoDTO();
    		$objBlocoDTO->setNumIdBloco($_GET['id_bloco']);
    		$objBlocoDTO->retNumIdBloco();
    		$objBlocoDTO->retStrStaEstado();
    		$objBlocoDTO->retStrDescricao();
    		$objBlocoRN = new BlocoRN();
    		$objBlocoDTO = $objBlocoRN->consultarRN1276($objBlocoDTO);
    		
        if ($objBlocoDTO===null){
          throw new InfraException("Registro não encontrado.");
        }
    		
    		$objBlocoRN->reabrir($objBlocoDTO);
    		PaginaSEI::getInstance()->setStrMensagem('Bloco "'.$_GET['id_bloco'].'" reaberto com sucesso.');
    	}catch(Exception $e){
    		PaginaSEI::getInstance()->processarExcecao($e);
    	}
    	header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_bloco'])));
    	die;

    case 'bloco_selecionar_processo':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Bloco','Selecionar Blocos');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='bloco_interno_cadastrar' ||
          $_GET['acao_origem']=='bloco_reuniao_cadastrar'){    
              if (isset($_GET['id_bloco'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_bloco']);
        }
      }
      break;
      
    case 'bloco_selecionar_documento':
      $strTitulo = PaginaSEI::getInstance()->getTituloSelecao('Selecionar Bloco de Assinatura','Selecionar Blocos de Assinatura');

      //Se cadastrou alguem
      if ($_GET['acao_origem']=='bloco_assinatura_cadastrar' ||
          $_GET['acao_origem']=='bloco_interno_cadastrar' ||
          $_GET['acao_origem']=='bloco_reuniao_cadastrar'){    
        
        if (isset($_GET['id_bloco'])){
          PaginaSEI::getInstance()->adicionarSelecionado($_GET['id_bloco']);
        }
      }
      break;

    case 'bloco_priorizar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjBlocoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objBlocoDTO = new BlocoDTO();
          $objBlocoDTO->setNumIdBloco($arrStrIds[$i]);
          $arrObjBlocoDTO[] = $objBlocoDTO;
        }
        $objBlocoRN = new BlocoRN();
        $objBlocoRN->priorizar($arrObjBlocoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($arrStrIds)));
      die;

    case 'bloco_revisar':
      try{
        $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
        $arrObjBlocoDTO = array();
        for ($i=0;$i<count($arrStrIds);$i++){
          $objBlocoDTO = new BlocoDTO();
          $objBlocoDTO->setNumIdBloco($arrStrIds[$i]);
          $arrObjBlocoDTO[] = $objBlocoDTO;
        }
        $objBlocoRN = new BlocoRN();
        $objBlocoRN->revisar($arrObjBlocoDTO);
        PaginaSEI::getInstance()->setStrMensagem('Operação realizada com sucesso.');
      }catch(Exception $e){
        PaginaSEI::getInstance()->processarExcecao($e);
      }
      header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao_origem'].'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($arrStrIds)));
      die;


    case 'bloco_assinatura_listar':
    	$strTitulo = 'Blocos de Assinatura';
      break;

    case 'bloco_interno_listar':
    	$strTitulo = 'Blocos Internos';
      break;

    case 'bloco_reuniao_listar':
      $strTitulo = 'Blocos de Reunião';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }
  
  $arrComandos = array();

  $objBlocoDTOPesquisa = new BlocoDTO();
  $objBlocoDTOPesquisa->retNumIdBloco();
  $objBlocoDTOPesquisa->retNumIdUnidade();
  $objBlocoDTOPesquisa->retStrDescricao();
  $objBlocoDTOPesquisa->retStrStaTipo();
  $objBlocoDTOPesquisa->retStrStaEstado();
  //$objBlocoDTOPesquisa->retStrAnotacao();
  //$objBlocoDTOPesquisa->retStrIdxBloco();
  $objBlocoDTOPesquisa->retStrStaEstadoDescricao();
  $objBlocoDTOPesquisa->retStrTipoDescricao();
  $objBlocoDTOPesquisa->retStrSiglaUnidade();
  $objBlocoDTOPesquisa->retStrDescricaoUnidade();
  $objBlocoDTOPesquisa->retNumDocumentos();
  $objBlocoDTOPesquisa->retArrObjRelBlocoUnidadeDTO();
  $objBlocoDTOPesquisa->retObjRelBlocoUnidadeDTO();

    
  if(($_GET['acao']=='bloco_assinatura_listar')){
    $objBlocoDTOPesquisa->setStrStaTipo(BlocoRN::$TB_ASSINATURA);
    $objBlocoDTOPesquisa->retNumDocumentos();
  }else if(($_GET['acao']=='bloco_interno_listar')){
    $objBlocoDTOPesquisa->setStrStaTipo(BlocoRN::$TB_INTERNO);
  }else if(($_GET['acao']=='bloco_reuniao_listar')){
    $objBlocoDTOPesquisa->setStrStaTipo(BlocoRN::$TB_REUNIAO);
  }else if($_GET['acao']=='bloco_selecionar_processo'){
    $objBlocoDTOPesquisa->setStrStaTipo(array(BlocoRN::$TB_REUNIAO,BlocoRN::$TB_INTERNO),InfraDTO::$OPER_IN);
  }else if($_GET['acao']=='bloco_selecionar_documento'){
    $objBlocoDTOPesquisa->setStrStaTipo(BlocoRN::$TB_ASSINATURA);
  }

  $strTipoAtribuicao = BlocoRN::$TA_TODAS;

  if (PaginaSEI::getInstance()->isBolPaginaSelecao()) {
    PaginaSEI::getInstance()->salvarCampo('chkSinEstadoGerado', 'S');
    PaginaSEI::getInstance()->salvarCampo('chkSinEstadoDisponibilizado', 'N');
    PaginaSEI::getInstance()->salvarCampo('chkSinEstadoRecebido', 'N');
    PaginaSEI::getInstance()->salvarCampo('chkSinEstadoRetornado', 'S');
    PaginaSEI::getInstance()->salvarCampo('chkSinEstadoConcluido', 'N');
  }

  $arrEstadosSelecionados = array();

  $strSinEstadoGerado = PaginaSEI::getInstance()->recuperarCampo('chkSinEstadoGerado', 'S');
  if ($strSinEstadoGerado == 'S') {
    $arrEstadosSelecionados[] = BlocoRN::$TE_ABERTO;
  }

  $strSinEstadoDisponibilizado = PaginaSEI::getInstance()->recuperarCampo('chkSinEstadoDisponibilizado', 'S');
  if ($strSinEstadoDisponibilizado == 'S') {
    $arrEstadosSelecionados[] = BlocoRN::$TE_DISPONIBILIZADO;
  }

  $strSinEstadoRecebido = PaginaSEI::getInstance()->recuperarCampo('chkSinEstadoRecebido', 'S');
  if ($strSinEstadoRecebido == 'S') {
    $arrEstadosSelecionados[] = BlocoRN::$TE_RECEBIDO;
  }

  $strSinEstadoRetornado = PaginaSEI::getInstance()->recuperarCampo('chkSinEstadoRetornado', 'S');
  if ($strSinEstadoRetornado == 'S') {
    $arrEstadosSelecionados[] = BlocoRN::$TE_RETORNADO;
  }

  $strSinEstadoConcluido = PaginaSEI::getInstance()->recuperarCampo('chkSinEstadoConcluido', 'N');
  if ($strSinEstadoConcluido == 'S') {
    $arrEstadosSelecionados[] = BlocoRN::$TE_CONCLUIDO;
  }

  if (count($arrEstadosSelecionados)){
    $objBlocoDTOPesquisa->setStrStaEstado($arrEstadosSelecionados, InfraDTO::$OPER_IN);
  }else{
    $objBlocoDTOPesquisa->setStrStaEstado(null);
  }

  $strTipoAtribuicao = PaginaSEI::getInstance()->recuperarCampo('hdnMeusBlocos', BlocoRN::$TA_TODAS);

  $objBlocoDTOPesquisa->setStrStaTipoAtribuicao($strTipoAtribuicao);

  $strSinPrioridade = PaginaSEI::getInstance()->recuperarCampo('chkSinPrioridade', 'N');
  if ($strSinPrioridade == 'S') {
    $objBlocoDTOPesquisa->setStrSinPrioridadeRelBlocoUnidade($strSinPrioridade);
  }

  $strSinRevisao = PaginaSEI::getInstance()->recuperarCampo('chkSinRevisao', 'N');
  if ($strSinRevisao == 'S') {
    $objBlocoDTOPesquisa->setStrSinRevisaoRelBlocoUnidade($strSinRevisao);
  }

  $strSinComentario = PaginaSEI::getInstance()->recuperarCampo('chkSinComentario', 'N');
  if ($strSinComentario == 'S') {
    $objBlocoDTOPesquisa->setStrSinComentarioRelBlocoUnidade($strSinComentario);
  }

	$strPalavrasPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtPalavrasPesquisaBloco');
	if ($strPalavrasPesquisa!=''){
    $objBlocoDTOPesquisa->setStrPalavrasPesquisa($strPalavrasPesquisa);
  }

  $numIdGrupoBloco = trim(PaginaSEI::getInstance()->recuperarCampo('selGrupoBloco'));
  if ($numIdGrupoBloco != ''){
    $objBlocoDTOPesquisa->setNumIdGrupoBlocoRelBlocoUnidade($numIdGrupoBloco);
  }

  $numIdUnidadeGeradora = trim(PaginaSEI::getInstance()->recuperarCampo('selUnidadeGeradora'));
  if ($numIdUnidadeGeradora != ''){
    $objBlocoDTOPesquisa->setNumIdUnidade($numIdUnidadeGeradora);
  }

  PaginaSEI::getInstance()->prepararOrdenacao($objBlocoDTOPesquisa, 'IdBloco', InfraDTO::$TIPO_ORDENACAO_DESC);
  
  //$objBlocoDTOPesquisa->setOrdNumIdBloco(InfraDTO::$TIPO_ORDENACAO_DESC);
  
  if (!PaginaSEI::getInstance()->isBolPaginaSelecao()){
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('bloco_cadastrar');
    if ($bolAcaoCadastrar){
      if(($_GET['acao']=='bloco_assinatura_listar')){
        $strAcaoNovo = 'bloco_assinatura_cadastrar';
      }else if(($_GET['acao']=='bloco_reuniao_listar')){
        $strAcaoNovo = 'bloco_reuniao_cadastrar';
      }else if(($_GET['acao']=='bloco_interno_listar')){
        $strAcaoNovo = 'bloco_interno_cadastrar';
      }
       
      $arrComandos[] = '<button type="button" accesskey="N" id="btnNovo" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$strAcaoNovo.'&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton d-none d-md-inline-block"><span class="infraTeclaAtalho">N</span>ovo</button>';
    }
  }else{
  	if ($_GET['acao']=='bloco_selecionar_documento'){
  	  if (SessaoSEI::getInstance()->verificarPermissao('bloco_assinatura_cadastrar')){
  		  $arrComandos[] = '<button type="button" accesskey="N" id="btnNovoAssinatura" value="Novo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_assinatura_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
  	  }
  	}else{
  	  
  	  if (SessaoSEI::getInstance()->verificarPermissao('bloco_interno_cadastrar')){
  		  $arrComandos[] = '<button type="button" accesskey="I" id="btnNovoInterno" value="Novo Bloco Interno" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_interno_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton">Novo Bloco <span class="infraTeclaAtalho">I</span>nterno</button>';
  	  }
  	  
  	  if (SessaoSEI::getInstance()->verificarPermissao('bloco_reuniao_cadastrar')){
  		  $arrComandos[] = '<button type="button" accesskey="R" id="btnNovoReuniao" value="Novo Bloco de Reunião" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_reuniao_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton">Novo Bloco de <span class="infraTeclaAtalho">R</span>eunião</button>';
  	  }
  	}
  }

  PaginaSEI::getInstance()->prepararPaginacao($objBlocoDTOPesquisa);

  $objBlocoRN = new BlocoRN();
  $arrObjBlocoDTO = $objBlocoRN->pesquisar($objBlocoDTOPesquisa);

  PaginaSEI::getInstance()->processarPaginacao($objBlocoDTOPesquisa);

  $numRegistros = count($arrObjBlocoDTO);

  $bolAcaoBlocoAlterarGrupo = SessaoSEI::getInstance()->verificarPermissao('bloco_alterar_grupo');
  if ($numRegistros){
    if ($bolAcaoBlocoAlterarGrupo){
      $arrComandos[] = '<button type="button" accesskey="A" id="btnBlocoAlterarGrupo" value="Alterar Grupo" onclick="acaoBlocoAlterarGrupo();" class="infraButton d-none d-md-inline-block"><span class="infraTeclaAtalho">A</span>lterar Grupo</button>';
      $strLinkBlocoAlterarGrupo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_alterar_grupo&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
    }
  }

  if (SessaoSEI::getInstance()->verificarPermissao('grupo_bloco_listar')){
    $arrComandos[] = '<button type="button" accesskey="L" id="btnGrupoBlocoListar" value="Listar Grupos" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_bloco_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']).'\'" class="infraButton d-none d-md-inline-block"><span class="infraTeclaAtalho">L</span>istar Grupos</button>';
  }

  $arrParaAssinar = array();
  $arrParaConcluir = array();
  $arrParaRetornar = array();
  $arrParaExcluir = array();

  if ($numRegistros > 0){

    $bolCheck = false;

    if (PaginaSEI::getInstance()->isBolPaginaSelecao()){
      $bolAcaoDocumentoAssinar = false;
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('bloco_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('bloco_alterar');
      $bolAcaoRelBlocoProtocolListar = false;
      $bolAcaoBlocoDisponibilizar = false;
      $bolAcaoBlocoCancelarDisponibilizacao = false;
      $bolAcaoImprimir = false;
      $bolAcaoExcluir = false;
      $bolAcaoRetornar = false;
      $bolAcaoBlocoConcluir = false;
      $bolAcaoReabrir = false;
      $bolAcaoPriorizar = false;
      $bolAcaoRevisar = false;
      $bolAcaoAtribuir = false;
      $bolAcaoComentar = false;
      $bolCheck = true;
    }else{
      $bolAcaoDocumentoAssinar = SessaoSEI::getInstance()->verificarPermissao('documento_assinar');
      $bolAcaoConsultar = SessaoSEI::getInstance()->verificarPermissao('bloco_consultar');
      $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('bloco_alterar');
      $bolAcaoRelBlocoProtocolListar = SessaoSEI::getInstance()->verificarPermissao('rel_bloco_protocolo_listar');
      $bolAcaoBlocoDisponibilizar = SessaoSEI::getInstance()->verificarPermissao('bloco_disponibilizar');
      $bolAcaoBlocoCancelarDisponibilizacao = SessaoSEI::getInstance()->verificarPermissao('bloco_cancelar_disponibilizacao');
      $bolAcaoImprimir = true;
      $bolAcaoExcluir = SessaoSEI::getInstance()->verificarPermissao('bloco_excluir');
      $bolAcaoRetornar = SessaoSEI::getInstance()->verificarPermissao('bloco_retornar');
      $bolAcaoConcluir = SessaoSEI::getInstance()->verificarPermissao('bloco_concluir');
      $bolAcaoReabrir = SessaoSEI::getInstance()->verificarPermissao('bloco_reabrir');
      $bolAcaoPriorizar = SessaoSEI::getInstance()->verificarPermissao('bloco_priorizar');
      $bolAcaoRevisar = SessaoSEI::getInstance()->verificarPermissao('bloco_revisar');
      $bolAcaoAtribuir = SessaoSEI::getInstance()->verificarPermissao('bloco_atribuir');
      $bolAcaoComentar = SessaoSEI::getInstance()->verificarPermissao('bloco_comentar');
    }

    if ($bolAcaoBlocoDisponibilizar){
      $strLinkDisponibilizar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_disponibilizar&acao_origem='.$_GET['acao']);
    }
    
    if ($bolAcaoBlocoCancelarDisponibilizacao){
      $strLinkCancelarDisponibilizacao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_cancelar_disponibilizacao&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoDocumentoAssinar){
      $bolCheck = true;
      $strLinkAssinar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=documento_assinar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
    }

    if ($bolAcaoRetornar){
      $bolCheck = true;
      $strLinkRetornarBloco = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_retornar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoConcluir){
      $bolCheck = true;
      $strLinkConcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_concluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoAtribuir){
      $strLinkAtribuir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_atribuir&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
    }

    if ($bolAcaoComentar){
      $strLinkComentar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_comentar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao']);
    }

    if ($bolAcaoExcluir){
      $bolCheck = true;
      $strLinkExcluir = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_excluir&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoImprimir){
      $bolCheck = true;
      $arrComandos[] = '<button type="button" accesskey="I" id="btnImprimir" value="Imprimir" onclick="infraImprimirTabela();" class="infraButton d-none d-md-inline-block"><span class="infraTeclaAtalho">I</span>mprimir</button>';
    }

    if ($bolAcaoPriorizar){
      $strLinkPriorizar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_priorizar&acao_origem='.$_GET['acao']);
    }

    if ($bolAcaoRevisar){
      $strLinkRevisar = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_revisar&acao_origem='.$_GET['acao']);
    }


    $strResultado = '';

    $strSumarioTabela = 'Tabela de Blocos.';
    $strCaptionTabela = 'Blocos';

    $strResultado .= '<table id="tblBlocos" width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    if ($bolCheck) {
      $strResultado .= '<th class="infraTh" width="1%">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    }
    $strResultado .= '<th class="infraTh" width="6%">'.PaginaSEI::getInstance()->getThOrdenacao($objBlocoDTOPesquisa,'Número','IdBloco',$arrObjBlocoDTO).'</th>'."\n";

    if (PaginaSEI::getInstance()->isBolPaginaSelecao()){
      if ($_GET['acao']=='bloco_selecionar_processo'){
        $strResultado .= '<th class="infraTh" width="15%">Tipo</th>'."\n";
      }
    }else{

      $strResultado .= '<th class="infraTh" width="7%">Sinalizações</th>'."\n";
      $strResultado .= '<th class="infraTh d-none d-md-table-cell" width="7%">Atribuição</th>'."\n";

      $strResultado .= '<th class="infraTh d-none d-md-table-cell" width="8%">Estado</th>'."\n";
      $strResultado .= '<th class="infraTh" width="8%">Geradora</th>'."\n";

      if ($objBlocoDTOPesquisa->getStrStaTipo() != BlocoRN::$TB_INTERNO){
        $strResultado .= '<th class="infraTh d-none d-md-table-cell" width="8%">Disponibilização</th>'."\n";
      }
    }

    //$strResultado .= '<th class="infraTh" width="20%">'.PaginaSEI::getInstance()->getThOrdenacao($objAcompanhamentoDTO,'Processo','IdProtocolo',$arrObjAcompanhamentoDTO).'</th>'."\n";

    $strResultado .= '<th class="infraTh d-none d-md-table-cell" width="7%">Grupo</th>'."\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objBlocoDTOPesquisa,'Descrição','Descricao',$arrObjBlocoDTO).'</th>'."\n";

    //if ($objBlocoDTOPesquisa->getStrStaTipo() == BlocoRN::$TB_ASSINATURA){
    //  $strResultado .= '<th class="infraTh">Documentos</th>'."\n";
    //  $strResultado .= '<th class="infraTh">Sem Assinatura</th>'."\n";
    //}

    $strResultado .= '<th class="infraTh" width="15%">Ações</th>'."\n";


    $strResultado .= '</tr>'."\n";
    $strCssTr='';

    for($i = 0;$i < $numRegistros; $i++){

      $objBlocoDTO = $arrObjBlocoDTO[$i];

      if ($objBlocoDTO->getStrStaEstado()!=BlocoRN::$TE_DISPONIBILIZADO){
        $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      }else{
        $strCssTr = '<tr class="trVermelha">';
      }

      $strResultado .= $strCssTr;
      
      if ($bolCheck){
        $strResultado .= '<td>'.PaginaSEI::getInstance()->getTrCheck($i,$objBlocoDTO->getNumIdBloco(),$objBlocoDTO->getNumIdBloco()).'</td>';
      }
            
      if (PaginaSEI::getInstance()->isBolPaginaSelecao()){
        $strResultado .= '<td align="center"><a href="javascript:void(0);" onclick="infraTransportarItem('.$i.')" class="ancoraPadraoPreta '.(($objBlocoDTO->getStrStaEstado() == BlocoRN::$TE_ABERTO || $objBlocoDTO->getStrStaEstado() == BlocoRN::$TE_RETORNADO) ? 'ancoraBlocoAberto' : 'ancoraBlocoConcluido').'">'.$objBlocoDTO->getNumIdBloco().'</a></td>';
        
        if ($_GET['acao']=='bloco_selecionar_processo'){
          $strResultado .= '<td align="center">'.PaginaSEI::tratarHTML($objBlocoDTO->getStrTipoDescricao()).'</td>';
        }
      }else{
        $strResultado .= '<td align="center"><a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_bloco='.$objBlocoDTO->getNumIdBloco()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" class="ancoraPadraoPreta '.(($objBlocoDTO->getStrStaEstado() == BlocoRN::$TE_ABERTO || $objBlocoDTO->getStrStaEstado() == BlocoRN::$TE_RETORNADO) ? 'ancoraBlocoAberto' : 'ancoraBlocoConcluido').'">'.$objBlocoDTO->getNumIdBloco().'</a></td>';

        $strResultado .= "\n".'<td align="center" >'."\n";

        $strImgPrioridade = Icone::BLOCO_PRIORIDADE1;
        $strTextoPrioridade = 'Não prioritário';

        $strImgRevisao = Icone::BLOCO_REVISAO1;
        $strTextoRevisao = 'Não revisado';

        $strImgComentario = Icone::BLOCO_COMENTARIO1;
        $strTituloComentario = '';
        $strTextoComentario = 'Sem comentário';

        $strUsuarioAtribuicao = '&nbsp;';

        $strGrupoBloco = '&nbsp;';

        if ($objBlocoDTO->getObjRelBlocoUnidadeDTO() != null) {

          $objRelBlocoUnidadeDTO = $objBlocoDTO->getObjRelBlocoUnidadeDTO();

          if ($objRelBlocoUnidadeDTO->getStrSinPrioridade() == 'S') {
            $strImgPrioridade = Icone::BLOCO_PRIORIDADE2;
            $strTextoPrioridade = 'Prioritário';
          }

          if ($objRelBlocoUnidadeDTO->getNumIdUsuarioPrioridade() != null) {
            $strTextoPrioridade .= ' por '.$objRelBlocoUnidadeDTO->getStrSiglaUsuarioPrioridade().' em '.substr($objRelBlocoUnidadeDTO->getDthPrioridade(), 0, 16);
          }

          if ($objRelBlocoUnidadeDTO->getStrSinRevisao() == 'S') {
            $strImgRevisao = Icone::BLOCO_REVISAO2;
            $strTextoRevisao = 'Revisado';
          }

          if ($objRelBlocoUnidadeDTO->getNumIdUsuarioRevisao() != null) {
            $strTextoRevisao .= ' por '.$objRelBlocoUnidadeDTO->getStrSiglaUsuarioRevisao().' em '.substr($objRelBlocoUnidadeDTO->getDthRevisao(), 0, 16);
          }

          if ($objRelBlocoUnidadeDTO->getStrSinComentario() == 'S') {
            $strImgComentario = Icone::BLOCO_COMENTARIO2;
            $strTextoComentario = $objRelBlocoUnidadeDTO->getStrTextoComentario();
          }

          if ($objRelBlocoUnidadeDTO->getNumIdUsuarioComentario() != null) {
            $strTituloComentario = $objRelBlocoUnidadeDTO->getStrSiglaUsuarioComentario().' em '.substr($objRelBlocoUnidadeDTO->getDthComentario(), 0, 16);
          }

          if ($objRelBlocoUnidadeDTO->getNumIdUsuarioAtribuicao() != null) {
            $strUsuarioAtribuicao .= '<a href="javascript:void(0);" alt="'.PaginaSEI::tratarHTML($objRelBlocoUnidadeDTO->getStrNomeUsuarioAtribuicao()).'" title="'.PaginaSEI::tratarHTML($objRelBlocoUnidadeDTO->getStrNomeUsuarioAtribuicao()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objRelBlocoUnidadeDTO->getStrSiglaUsuarioAtribuicao()).'</a>';
          }

          if ($objRelBlocoUnidadeDTO->getNumIdGrupoBloco() != null){
            $strGrupoBloco = PaginaSEI::tratarHTML($objRelBlocoUnidadeDTO->getStrNomeGrupoBloco());
          }
        }

        $strAcaoPriorizar = 'onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);';
        if ($bolAcaoPriorizar){
          $strAcaoPriorizar .= 'acaoPriorizar(\''.$objBlocoDTO->getNumIdBloco().'\');';
        }
        $strAcaoPriorizar .= '"';

        $strResultado .= '<a '.$strAcaoPriorizar.' '.PaginaSEI::montarTitleTooltip($strTextoPrioridade).' tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.$strImgPrioridade.'" class="infraImg" /></a>&nbsp;&nbsp;';

        $strAcaoRevisar = 'onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);';
        if ($bolAcaoRevisar){
          $strAcaoRevisar .= 'acaoRevisar(\''.$objBlocoDTO->getNumIdBloco().'\');';
        }
        $strAcaoRevisar .= '"';

        $strResultado .= '<a '.$strAcaoRevisar.' '.PaginaSEI::montarTitleTooltip($strTextoRevisao).' tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.$strImgRevisao.'" class="infraImg" /></a>&nbsp;';

        $strAcaoComentar = 'onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);';
        if ($bolAcaoComentar){
          $strAcaoComentar .= 'acaoComentar(\''.$objBlocoDTO->getNumIdBloco().'\');';
        }
        $strAcaoComentar .= '"';

        $strResultado .= '<a '.$strAcaoComentar.' '.PaginaSEI::montarTitleTooltip($strTextoComentario, $strTituloComentario).' tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.$strImgComentario.'" class="infraImg" /></a>&nbsp;';

        $strResultado .= '</td>'."\n";

        $strResultado .= '<td class="d-none d-md-table-cell" align="center">'.$strUsuarioAtribuicao.'</td>';


        $strResultado .= '<td class="d-none d-md-table-cell" align="center">'.PaginaSEI::tratarHTML($objBlocoDTO->getStrStaEstadoDescricao()).'</td>';

        $strResultado .= '<td align="center"><a href="javascript:void(0);" alt="'.PaginaSEI::tratarHTML($objBlocoDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objBlocoDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objBlocoDTO->getStrSiglaUnidade()).'</a></td>';

        if ($objBlocoDTOPesquisa->getStrStaTipo() != BlocoRN::$TB_INTERNO){

          $strResultado .= '<td class="d-none d-md-table-cell" align="center">';
          foreach($objBlocoDTO->getArrObjRelBlocoUnidadeDTO() as $objRelBlocoUnidadeDTO){

            $strLinkUnidade = '<a href="javascript:void(0);" alt="'.PaginaSEI::tratarHTML($objRelBlocoUnidadeDTO->getStrDescricaoUnidade()).'" title="'.PaginaSEI::tratarHTML($objRelBlocoUnidadeDTO->getStrDescricaoUnidade()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($objRelBlocoUnidadeDTO->getStrSiglaUnidade()).'</a>';

            if ($objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_DISPONIBILIZADO || $objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_RECEBIDO){
              if ($objRelBlocoUnidadeDTO->getStrSinRetornado()=='N') {
                $strResultado .= '<div class="divUnidade"><div class="divUnidadeIcone"><img src="'.Icone::BLOCO_AGUARDANDO_DEVOLUCAO.'" height="16" width="16" title="Aguardando Devolução" />'.'</div><div class="divUnidadeRotulo">'.$strLinkUnidade.'</div></div>';
              }else{
                $strResultado .= '<div class="divUnidade"><div class="divUnidadeIcone"><img src="'.Icone::BLOCO_DEVOLVIDO.'" height="16" width="16" title="Devolvido" />'.'</div><div class="divUnidadeRotulo">'.$strLinkUnidade.'</div></div>';
              }
            }else{
              $strResultado .= '<div style="padding-left:1.5em;text-align:center;">'.$strLinkUnidade.'</div>';
            }

          }
          $strResultado .= '</td>';
        }
      }

      $strResultado .= '<td class="d-none d-md-table-cell" align="center">'.$strGrupoBloco.'</td>';

      $strResultado .= '<td>'.nl2br(PaginaSEI::tratarHTML($objBlocoDTO->getStrDescricao())).'</td>';

      $strResultado .= '<td align="center">';
      
      $strResultado .= PaginaSEI::getInstance()->getAcaoTransportarItem($i,$objBlocoDTO->getNumIdBloco(),'Infra','','Escolher este Bloco');

      if ($bolAcaoDocumentoAssinar &&
          $objBlocoDTO->getStrStaTipo()==BlocoRN::$TB_ASSINATURA &&
          $objBlocoDTO->getStrStaEstado()!=BlocoRN::$TE_DISPONIBILIZADO &&
          $objBlocoDTO->getStrStaEstado()!=BlocoRN::$TE_CONCLUIDO &&
          $objBlocoDTO->getNumDocumentos()>0){
        $arrParaAssinar[] = $objBlocoDTO->getNumIdBloco();
        $strResultado .= '<a href="javascript:void(0);" onclick="infraLimparFormatarTrAcessada(this.parentNode.parentNode);acaoAssinar(\''.$objBlocoDTO->getNumIdBloco().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::DOCUMENTO_ASSINAR.'" title="Assinar Documentos do Bloco" alt="Assinar Documentos do Bloco" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoAtribuir && $objBlocoDTO->getStrStaEstado()!=BlocoRN::$TE_CONCLUIDO){
        $strResultado .= '<a onclick="acaoAtribuir(\''.$objBlocoDTO->getNumIdBloco().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::BLOCO_USUARIO.'" title="Atribuir Bloco" alt="Atribuir Bloco" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoRelBlocoProtocolListar){  
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_bloco_protocolo_listar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_bloco='.$objBlocoDTO->getNumIdBloco()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::BLOCO_CONSULTAR_PROTOCOLOS.'" title="Processos/Documentos do Bloco" alt="Processos/Documentos do Bloco" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoBlocoDisponibilizar &&
          $objBlocoDTO->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() && //bloco da unidade
          $objBlocoDTO->getStrStaTipo()!=BlocoRN::$TB_INTERNO && //não pode ser interno
          ($objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_ABERTO || $objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_RETORNADO)){ //deve estar aberto ou retornado
        $strResultado .= '<a onclick="acaoDisponibilizar(\''.$objBlocoDTO->getNumIdBloco().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::BLOCO_DISPONIBILIZAR.'" title="Disponibilizar Bloco" alt="Disponibilizar Bloco" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoBlocoCancelarDisponibilizacao &&
          $objBlocoDTO->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() && //bloco da unidade
          $objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_DISPONIBILIZADO){ //deve estar disponibilizado
        $strResultado .= '<a onclick="acaoCancelarDisponibilizacao(\''.$objBlocoDTO->getNumIdBloco().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::BLOCO_CANCELAR_DISPONIBILIZACAO.'" title="Cancelar Disponibilização" alt="Cancelar Disponibilização" class="infraImg" /></a>&nbsp;';
      }

      
      if ($bolAcaoAlterar && $objBlocoDTO->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()){
      	if($objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_ABERTO || $objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_RETORNADO){
      	  if ($objBlocoDTO->getStrStaTipo()==BlocoRN::$TB_ASSINATURA){
      	    $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_assinatura_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_bloco='.$objBlocoDTO->getNumIdBloco()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Bloco" alt="Alterar Bloco" class="infraImg" /></a>&nbsp;';
      	  }else if ($objBlocoDTO->getStrStaTipo()==BlocoRN::$TB_REUNIAO){
      	    $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_reuniao_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_bloco='.$objBlocoDTO->getNumIdBloco()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Bloco" alt="Alterar Bloco" class="infraImg" /></a>&nbsp;';
      	  }else if ($objBlocoDTO->getStrStaTipo()==BlocoRN::$TB_INTERNO){
      	    $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_interno_alterar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_bloco='.$objBlocoDTO->getNumIdBloco()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeAlterar().'" title="Alterar Bloco" alt="Alterar Bloco" class="infraImg" /></a>&nbsp;';
      	  }
      	}
      }

      if ($bolAcaoRetornar && $objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_RECEBIDO){
        $arrParaRetornar[] = $objBlocoDTO->getNumIdBloco();
      	$strResultado .= '<a onclick="acaoRetornarBloco(\''.$objBlocoDTO->getNumIdBloco().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::BLOCO_DEVOLVER.'" title="Devolver Bloco" alt="Devolver Bloco" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoReabrir && $objBlocoDTO->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() && $objBlocoDTO->getStrStaEstado()==BlocoRN::$TE_CONCLUIDO){
        $strResultado .= '<a href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=bloco_reabrir&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_bloco='.$objBlocoDTO->getNumIdBloco()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::BLOCO_REABRIR.'" title="Reabrir Bloco" alt="Reabrir Bloco" class="infraImg" /></a>&nbsp;';
      }

      if ($bolAcaoConcluir &&
          $objBlocoDTO->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual() &&
          $objBlocoDTO->getStrStaEstado()!=BlocoRN::$TE_DISPONIBILIZADO &&
          $objBlocoDTO->getStrStaEstado()!=BlocoRN::$TE_CONCLUIDO){
        $arrParaConcluir[] = $objBlocoDTO->getNumIdBloco();
      	$strResultado .= '<a onclick="acaoConcluir(\''.$objBlocoDTO->getNumIdBloco().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.Icone::BLOCO_CONCLUIR.'" title="Concluir Bloco" alt="Concluir Bloco" class="infraImg" /></a>&nbsp;';
      }
      
      if ($bolAcaoExcluir &&
          $objBlocoDTO->getNumIdUnidade()==SessaoSEI::getInstance()->getNumIdUnidadeAtual()  &&
          $objBlocoDTO->getStrStaEstado()!=BlocoRN::$TE_DISPONIBILIZADO){
        $arrParaExcluir[] = $objBlocoDTO->getNumIdBloco();
      	$strResultado .= '<a onclick="acaoExcluir(\''.$objBlocoDTO->getNumIdBloco().'\');" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'"><img src="'.PaginaSEI::getInstance()->getIconeExcluir().'" title="Excluir Bloco" alt="Excluir Bloco" class="infraImg" /></a>&nbsp;';
      }
      $strResultado .= '</td>'."\n";

      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';

    if (InfraArray::contar($arrParaExcluir)){
      array_unshift($arrComandos, '<button type="button" accesskey="E" id="btnExcluir" value="Excluir" onclick="acaoExclusaoMultipla();" class="infraButton  d-none d-md-inline-block"><span class="infraTeclaAtalho">E</span>xcluir</button>');
    }

    if (InfraArray::contar($arrParaConcluir)){
      array_unshift($arrComandos, '<button type="button" accesskey="C" id="btnConcluir" value="Concluir" onclick="acaoConclusaoMultipla();" class="infraButton"><span class="infraTeclaAtalho">C</span>oncluir</button>');
    }

    if (InfraArray::contar($arrParaRetornar)){
      array_unshift($arrComandos, '<button type="button" accesskey="D" id="btnDevolver" value="Devolver" onclick="acaoRetornoMultipla();" class="infraButton"><span class="infraTeclaAtalho">D</span>evolver</button>');
    }

    if ($bolAcaoAtribuir) {
      array_unshift($arrComandos, '<button type="button" accesskey="" id="btnAtribuir" value="Atribuir" onclick="acaoAtribuicaoMultipla();" class="infraButton d-none d-md-inline-block">Atribuir</button>');
    }

    if (InfraArray::contar($arrParaAssinar)){
      array_unshift($arrComandos, '<button type="button" accesskey="A" id="btnAssinar" value="Assinar" onclick="acaoAssinaturaMultipla();" class="infraButton"><span class="infraTeclaAtalho">A</span>ssinar</button>');
    }
  }

  array_unshift($arrComandos, '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>');

  $strDesabilitarEstado = '';
  if ($_GET['acao']=='bloco_selecionar_processo'){
    $strDesabilitarEstado = 'disabled="disabled"';
  }else if ($_GET['acao']=='bloco_selecionar_documento'){
    $strDesabilitarEstado = 'disabled="disabled"';
  }
  
  if (PaginaSEI::getInstance()->isBolPaginaSelecao()){
    array_unshift($arrComandos, '<button type="button" accesskey="O" id="btnTransportarSelecao" value="OK" onclick="selecionar();" class="infraButton" style="width:5em;"><span class="infraTeclaAtalho">O</span>K</button>');
    //$arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }else{
    //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_bloco'])).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
  }

  $strItensSelGrupoBloco = str_replace('&nbsp;','Nenhum', GrupoBlocoINT::montarSelectUnidade('','Todos', $numIdGrupoBloco));
  $strItensSelUnidadeGeradora = BlocoINT::montarSelectGeradora('', 'Todas', $numIdUnidadeGeradora, $objBlocoDTOPesquisa->getStrStaTipo());

  $strActionPadrao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_documento='.$_GET['id_documento']);

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
} 

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
?>
<style>

  #tblBlocos td {vertical-align: top;}

  div.divUnidade{
    display: table;
    padding:.2em 0 .2em 0;
    text-align: center;
  }

  div.divUnidadeIcone{
    display: table-cell;
    padding-right:.3em;
    vertical-align: top;
  }

  div.divUnidadeIcone img{
    margin-top:2px;
  }

  div.divUnidadeRotulo{
    display:table-cell;
  }


div.infraAreaDados{
  max-width:1024px;
  margin:0;
}

a.ancoraBlocoAberto, a.ancoraBlocoConcluido{font-size:1rem !important;font-weight:600;}
a.ancoraBlocoAberto {color:#57b952;}
a.ancoraBlocoConcluido {color:#ef383f;}

#divPesquisa1, #divPesquisa2 {height:10em;overflow:visible;}
#lblPalavrasPesquisaBloco {}
#txtPalavrasPesquisaBloco {width:100%;}

#lblGrupoBloco {}
#selGrupoBloco {width:100%;}

#divLinkVisualizacao {    margin-top: 30px;}
#divLinkVisualizacao a {padding:0px;}

#lblUnidadeGeradora {}
#selUnidadeGeradora {width:100%;}

#fldSinalizacao {height:120px;width:150px;position: relative;}
#divSinPrioridade {position:absolute;left:4%;top:25%;}
#divSinRevisao {position:absolute;left:4%;top:50%;}
#divSinComentario {position:absolute;left:4%;top:75%;}

#fldEstado {height:120px;width:250px;position: relative;}
#divSinEstadoGerado {position:absolute;left:2%;top:25%;}
#divSinEstadoDisponibilizado {position:absolute;left:2%;top:50%;}
#divSinEstadoRecebido {position:absolute;left:2%;top:75%;}
#divSinEstadoRetornado {position:absolute;left:55%;top:25%;}
#divSinEstadoConcluido {position:absolute;left:55%;top:50%;}


<? if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){ ?>
  #divSinPrioridade {top:10% !important;}
  #divSinRevisao {top:40% !important;}
  #divSinComentario {top:70% !important;}

  #divSinEstadoGerado {top:10%;}
  #divSinEstadoDisponibilizado {top:40%;}
  #divSinEstadoRecebido {top:70%;}
  #divSinEstadoRetornado {top:10%;}
  #divSinEstadoConcluido {top:40%;}
<? } ?>

<? if ($_GET['acao']=='bloco_interno_listar' ){ ?>
#fldEstado {width:125px;}
#divSinEstadoGerado {left:4%;}
#divSinEstadoDisponibilizado {display:none;}
#divSinEstadoRecebido {display:none;}
#divSinEstadoRetornado {display:none;}
#divSinEstadoConcluido {left:4%;}
<? }else if (PaginaSEI::getInstance()->isBolPaginaSelecao()) { ?>
#lblPalavrasPesquisaBloco {}
#txtPalavrasPesquisaBloco {width:100%;}

#lblGrupoBloco {}
#selGrupoBloco {width:100%;}

#lblUnidadeGeradora {left:42%;}
#selUnidadeGeradora {left:42%;width:100%;}

#fldSinalizacao {display:none;}
#fldEstado {display:none;}
  <? } ?>

</style>
<?
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){

  infraOcultarMenuSistemaEsquema();

  if ('<?=$_GET['acao']?>'=='bloco_selecionar_processo' || '<?=$_GET['acao']?>'=='bloco_selecionar_documento'){
    infraReceberSelecao();
    document.getElementById('btnTransportarSelecao').focus();
  }else{
    document.getElementById('sbmPesquisar').focus();
  }

  infraEfeitoTabelas();

}

<? if ($bolAcaoRetornar){ ?>
function acaoRetornarBloco(id){
  if (confirm("Confirma a devolução do Bloco \""+id+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBlocoLista').action='<?=$strLinkRetornarBloco?>';
    document.getElementById('frmBlocoLista').submit();
  }
}

function acaoRetornoMultipla(){

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Bloco selecionado.');
    return;
  }

  if (!verificarSelecionados([<?=implode(',',$arrParaRetornar)?>], 'Nenhum bloco selecionado pode ser devolvido.', 'Os blocos a seguir não podem ser devolvidos e serão ignorados: ')){
    return;
  }

  if (confirm("Confirma devolução dos Blocos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBlocoLista').action='<?=$strLinkRetornarBloco?>';
    document.getElementById('frmBlocoLista').submit();
  }
}

<? } ?>

<? if ($bolAcaoConcluir){ ?>
function acaoConcluir(id){
  if (confirm("Confirma conclusão do Bloco \""+id+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBlocoLista').action='<?=$strLinkConcluir?>';
    document.getElementById('frmBlocoLista').submit();
  }
}

function acaoConclusaoMultipla(){

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Bloco selecionado.');
    return;
  }

  if (!verificarSelecionados([<?=implode(',',$arrParaConcluir)?>], 'Nenhum bloco selecionado pode ser concluído.', 'Os blocos a seguir não podem ser concluídos e serão ignorados: ')){
    return;
  }

  if (confirm("Confirma conclusão dos Blocos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBlocoLista').action='<?=$strLinkConcluir?>';
    document.getElementById('frmBlocoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoExcluir){ ?>
function acaoExcluir(id){
  if (confirm("Confirma exclusão do Bloco \""+id+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBlocoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmBlocoLista').submit();
  }
}

function acaoExclusaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Bloco selecionado.');
    return;
  }

  if (!verificarSelecionados([<?=implode(',',$arrParaExcluir)?>], 'Nenhum bloco selecionado pode ser excluído.', 'Os blocos a seguir não podem ser excluídos e serão ignorados: ')){
    return;
  }

  if (confirm("Confirma exclusão dos Blocos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBlocoLista').action='<?=$strLinkExcluir?>';
    document.getElementById('frmBlocoLista').submit();
  }
}
<? } ?>


<? if ($bolAcaoBlocoDisponibilizar){ ?>
function acaoDisponibilizar(id){
  //if (confirm("Confirma disponibilização do bloco \""+id+"\" para assinatura?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBlocoLista').action='<?=$strLinkDisponibilizar?>';
    document.getElementById('frmBlocoLista').submit();
  //}
}

function acaoDisponibilizacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Bloco selecionado.');
    return;
  }
  //if (confirm("Confirma disponibilização para assinatura dos blocos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBlocoLista').action='<?=$strLinkDisponibilizar?>';
    document.getElementById('frmBlocoLista').submit();
  //}
}
<? } ?>


<? if ($bolAcaoBlocoCancelarDisponibilizacao){ ?>
function acaoCancelarDisponibilizacao(id){
  if (confirm("Confirma cancelamento de disponibilização do Bloco \""+id+"\"?")){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBlocoLista').action='<?=$strLinkCancelarDisponibilizacao?>';
    document.getElementById('frmBlocoLista').submit();
  }
}

function acaoCancelarDisponibilizacaoMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Bloco selecionado.');
    return;
  }
  if (confirm("Confirma cancelamento de disponibilização dos Blocos selecionados?")){
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBlocoLista').action='<?=$strLinkCancelarDisponibilizacao?>';
    document.getElementById('frmBlocoLista').submit();
  }
}
<? } ?>

<? if ($bolAcaoDocumentoAssinar){ ?>
function acaoAssinar(id){
  infraAbrirJanelaModal('<?=$strLinkAssinar?>',600,450);
  document.getElementById('hdnInfraItemId').value=id;
  document.getElementById('frmBlocoLista').target='modal-frame';
  document.getElementById('frmBlocoLista').action='<?=$strLinkAssinar?>';
  document.getElementById('frmBlocoLista').submit();
  document.getElementById('frmBlocoLista').target='_self';
  document.getElementById('frmBlocoLista').action='<?=$strActionPadrao?>';
}

function acaoAssinaturaMultipla(){

  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum Bloco selecionado.');
    return;
  }

  if (!verificarSelecionados([<?=implode(',',$arrParaAssinar)?>], 'Nenhum bloco selecionado pode ser assinado.', 'Os blocos a seguir não podem ser assinados e serão ignorados: ')){
    return;
  }

  infraAbrirJanelaModal('<?=$strLinkAssinar?>',600,450);

  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmBlocoLista').target='modal-frame';
  document.getElementById('frmBlocoLista').action='<?=$strLinkAssinar?>';
  document.getElementById('frmBlocoLista').submit();
  document.getElementById('frmBlocoLista').target='_self';
  document.getElementById('frmBlocoLista').action='<?=$strActionPadrao?>';
}
<? } ?>


<? if ($bolAcaoPriorizar){ ?>
  function acaoPriorizar(id){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBlocoLista').action='<?=$strLinkPriorizar?>';
    document.getElementById('frmBlocoLista').submit();
  }

  function acaoPriorizacaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum Bloco selecionado.');
      return;
    }
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBlocoLista').action='<?=$strLinkPriorizar?>';
    document.getElementById('frmBlocoLista').submit();
  }
<? } ?>

<? if ($bolAcaoRevisar){ ?>
  function acaoRevisar(id){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBlocoLista').action='<?=$strLinkRevisar?>';
    document.getElementById('frmBlocoLista').submit();
  }

  function acaoRevisaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum Bloco selecionado.');
      return;
    }
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBlocoLista').action='<?=$strLinkRevisar?>';
    document.getElementById('frmBlocoLista').submit();
  }
<? } ?>

<? if ($bolAcaoAtribuir){ ?>
  function acaoAtribuir(id){
    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBlocoLista').action='<?=$strLinkAtribuir?>';
    document.getElementById('frmBlocoLista').submit();
  }

  function acaoAtribuicaoMultipla(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum Bloco selecionado.');
      return;
    }
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBlocoLista').action='<?=$strLinkAtribuir?>';
    document.getElementById('frmBlocoLista').submit();
  }
<? } ?>

<? if ($bolAcaoComentar){ ?>
  function acaoComentar(id){

    infraAbrirJanelaModal('<?=$strLinkComentar?>',700,400);

    document.getElementById('hdnInfraItemId').value=id;
    document.getElementById('frmBlocoLista').target='modal-frame';
    document.getElementById('frmBlocoLista').action='<?=$strLinkComentar?>';
    document.getElementById('frmBlocoLista').submit();
    document.getElementById('frmBlocoLista').target='_self';
    document.getElementById('frmBlocoLista').action='<?=$strActionPadrao?>';
  }

  function acaoComentarMultiplo(){
    if (document.getElementById('hdnInfraItensSelecionados').value==''){
      alert('Nenhum Bloco selecionado.');
      return;
    }
    document.getElementById('hdnInfraItemId').value='';
    document.getElementById('frmBlocoLista').action='<?=$strLinkComentar?>';
    document.getElementById('frmBlocoLista').submit();
  }
<? } ?>

<? if ($bolAcaoBlocoAlterarGrupo){ ?>
  function acaoBlocoAlterarGrupo(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
  alert('Nenhum Bloco selecionado.');
  return;
  }
  document.getElementById('hdnInfraItemId').value='';
  document.getElementById('frmBlocoLista').action='<?=$strLinkBlocoAlterarGrupo?>';
  document.getElementById('frmBlocoLista').submit();
  }
<? } ?>


function tratarDigitacao(ev){
  if (infraGetCodigoTecla(ev) == 13){
    document.getElementById('frmBlocoLista').submit();
  }
  return true;
}

function selecionar(){
  objInput = document.getElementsByTagName('input');
  for (var i = 0; i < objInput.length; i++) {  
    if (objInput[i].type == 'radio' && objInput[i].checked) {
      break;
    }
  }
  
  if (i==objInput.length){
    alert('Nenhum Bloco selecionado.');
    return;
  }
  
  infraTransportarSelecao();
}

function verificarSelecionados(blocosValidos, msgNenhum, msgIgnorados){
  var i = 0;
  var j = 0;

  var selecionados = document.getElementById('hdnInfraItensSelecionados').value.split(',');
  var erros = [];
  var blocosProcessamento = [];

  for (i = 0; i < selecionados.length; i++) {
    if (!infraInArray(selecionados[i], blocosValidos)){
      erros.push(selecionados[i]);
    }else{
      blocosProcessamento.push(selecionados[i]);
    }
  }

  if (blocosProcessamento.length == 0){
    alert(msgNenhum);
    return false;
  }

  if (erros.length){
    alert(msgIgnorados + erros.join(', '));
    var nroItens = document.getElementById('hdnInfraNroItens').value;
    for(i = 0; i < erros.length; i++){
      for(j = 0; j < nroItens; j++){
        chk = document.getElementById('chkInfraItem'+j);
        if (chk.value == erros[i]){
          chk.checked = false;
          infraSelecionarItens(chk);
        }
      }
    }
  }

  document.getElementById('hdnInfraItensSelecionados').value = blocosProcessamento.join(',');

  return true;
}

function verBlocos(valor){
  document.getElementById('hdnMeusBlocos').value = valor;
  document.getElementById('frmBlocoLista').submit();
}

function validarPesquisa() {

  if (!document.getElementById('chkSinEstadoGerado').checked &&
      !document.getElementById('chkSinEstadoDisponibilizado').checked &&
      !document.getElementById('chkSinEstadoRecebido').checked &&
      !document.getElementById('chkSinEstadoRetornado').checked &&
      !document.getElementById('chkSinEstadoConcluido').checked){
    alert('Nenhum Estado selecionado.');
    return false;
  }

  return true;
}

function OnSubmitForm() {
  return validarPesquisa();
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmBlocoLista" method="post" onsubmit="return OnSubmitForm();" action="<?=$strActionPadrao?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>
<div class="row">
  <div id="divPesquisa1" class="col-12 col-md-3">
    <label id="lblPalavrasPesquisaBloco" for="txtPalavrasPesquisaBloco" accesskey="" class="infraLabelOpcional">Palavras-chave para pesquisa:</label>
    <input type="text" id="txtPalavrasPesquisaBloco" name="txtPalavrasPesquisaBloco" class="infraText" value="<?=PaginaSEI::tratarHTML($strPalavrasPesquisa)?>" onkeypress="return tratarDigitacao(event);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <div id="divLinkVisualizacao">
    <?  if ($strTipoAtribuicao == BlocoRN::$TA_MINHAS) { ?>
      <a id="ancVisualizacao" href="javascript:void(0);" onclick="verBlocos('<?=BlocoRN::$TA_TODAS?>');" class="ancoraPadraoPreta" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Ver todos os blocos</a>
    <? } else { ?>
      <a id="ancVisualizacao" href="javascript:void(0);" onclick="verBlocos('<?=BlocoRN::$TA_MINHAS?>');" class="ancoraPadraoPreta" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Ver blocos atribuídos a mim</a>
    <? } ?>
    </div>

  </div>
  <div id="divPesquisa2" class="col-12 col-md-3">

  <label id="lblGrupoBloco" for="selGrupoBloco" class="infraLabelOpcional">Grupo</label>
    <select id="selGrupoBloco" name="selGrupoBloco" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
      <?=$strItensSelGrupoBloco?>
    </select>

    <label id="lblUnidadeGeradora" for="selUnidadeGeradora" class="infraLabelOpcional">Geradora:</label>
    <select id="selUnidadeGeradora" name="selUnidadeGeradora" onchange="this.form.submit();" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
      <?=$strItensSelUnidadeGeradora?>
    </select>
  </div>
  <div class="col-md-6 col-12 ">
    <div class="d-flex flex-row flex-md-row ">
        <fieldset id="fldSinalizacao" class=" mr-2 flex-grow-1 mr-md-2 flex-md-grow-0 infraFieldset">
        <legend class="infraLegend"> Sinalizações </legend>

        <div id="divSinPrioridade" class="infraDivCheckbox">
          <input type="checkbox" id="chkSinPrioridade" name="chkSinPrioridade" onchange="this.form.submit()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinPrioridade)?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <label id="lblSinPrioridade" for="chkSinPrioridade" accesskey="" class="infraLabelCheckbox" >Prioritários</label>
        </div>

        <div id="divSinRevisao" class="infraDivCheckbox">
          <input type="checkbox" id="chkSinRevisao" name="chkSinRevisao" onchange="this.form.submit()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinRevisao)?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <label id="lblSinRevisao" for="chkSinRevisao" accesskey="" class="infraLabelCheckbox" >Revisados</label>
        </div>
        <div id="divSinComentario" class="infraDivCheckbox">
          <input type="checkbox" id="chkSinComentario" name="chkSinComentario" onchange="this.form.submit()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinComentario)?> tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <label id="lblSinComentario" for="chkSinComentario" accesskey="" class="infraLabelCheckbox" >Comentados</label>
        </div>
      </fieldset>
        <fieldset id="fldEstado" class="ml-md-4 infraFieldset">
          <legend class="infraLegend">Estado</legend>

          <div id="divSinEstadoGerado" class="infraDivCheckbox">
            <input type="checkbox" id="chkSinEstadoGerado" name="chkSinEstadoGerado" onchange="this.form.submit()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinEstadoGerado)?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
            <label id="lblSinEstadoGerado" for="chkSinEstadoGerado" accesskey="" class="infraLabelCheckbox">Gerado</label>
          </div>

          <div id="divSinEstadoDisponibilizado" class="infraDivCheckbox">
            <input type="checkbox" id="chkSinEstadoDisponibilizado" name="chkSinEstadoDisponibilizado" onchange="this.form.submit()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinEstadoDisponibilizado)?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
            <label id="lblSinEstadoDisponibilizado" for="chkSinEstadoDisponibilizado" accesskey="" class="infraLabelCheckbox">Disponibilizado</label>
          </div>

          <div id="divSinEstadoRecebido" class="infraDivCheckbox">
            <input type="checkbox" id="chkSinEstadoRecebido" name="chkSinEstadoRecebido" onchange="this.form.submit()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinEstadoRecebido)?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
            <label id="lblSinEstadoRecebido" for="chkSinEstadoRecebido" accesskey="" class="infraLabelCheckbox">Recebido</label>
          </div>

          <div id="divSinEstadoRetornado" class="infraDivCheckbox">
            <input type="checkbox" id="chkSinEstadoRetornado" name="chkSinEstadoRetornado" onchange="this.form.submit()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinEstadoRetornado)?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
            <label id="lblSinEstadoRetornado" for="chkSinEstadoRetornado" accesskey="" class="infraLabelCheckbox">Retornado</label>
          </div>

          <div id="divSinEstadoConcluido" class="infraDivCheckbox">
            <input type="checkbox" id="chkSinEstadoConcluido" name="chkSinEstadoConcluido" onchange="this.form.submit()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($strSinEstadoConcluido)?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"/>
            <label id="lblSinEstadoConcluido" for="chkSinEstadoConcluido" accesskey="" class="infraLabelCheckbox">Concluído</label>
          </div>

        </fieldset>
    </div>
  </div>
</div>
  <input type="hidden" id="hdnMeusBlocos" name="hdnMeusBlocos" value="<?=$strTipoAtribuicao?>" />
  <input type="hidden" id="hdnFlagBlocos" name="hdnFlagBlocos" value="1" />
  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>