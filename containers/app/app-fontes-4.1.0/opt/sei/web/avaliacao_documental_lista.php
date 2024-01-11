<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 22/06/2016 - criado por mga
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

  PaginaSEI::getInstance()->salvarCamposPost(array('txtProtocoloPesquisa','selTipoProcedimento','txtGeracaoDe','txtGeracaoA','txtPeriodoDe','txtPeriodoA','txtConclusaoDe','txtConclusaoA','selDestinacao','selAssuntos', 'hdnAssuntos','rdoAvaliacao','txtAvaliador','hdnAvaliador','chkSinDiscordancia'));

  $strParametros = '';

  $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();
  $objProtocoloRN = new ProtocoloRN();

  switch($_GET['acao']){


    case 'avaliacao_documental_pesquisar':
      $strTitulo = 'Avaliação Documental';
      break;

    case 'avaliacao_documental_listar':
      $strTitulo = 'Avaliação Documental';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="submit" accesskey="S" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  //filtros da pesquisa

  //campos para Processos aguardando avaliação e  Processos já avaliados
  //tipo de procedimento
  $numIdTipoProcedimento = PaginaSEI::getInstance()->recuperarCampo('selTipoProcedimento');
  //data de geracao do processo(autuacao)
  $dtaGeracaoDe = PaginaSEI::getInstance()->recuperarCampo('txtGeracaoDe');
  $dtaGeracaoA = PaginaSEI::getInstance()->recuperarCampo('txtGeracaoA');
  //data de conclusao do processo
  $dtaConclusaoDe = PaginaSEI::getInstance()->recuperarCampo('txtConclusaoDe');
  $dtaConclusaoA = PaginaSEI::getInstance()->recuperarCampo('txtConclusaoA');
  //destinação (guarda permanente ou eliminacao)
  $strStaDestinacao = PaginaSEI::getInstance()->recuperarCampo('selDestinacao');
  $strSinAvaliacao = PaginaSEI::getInstance()->recuperarCampo('rdoAvaliacao');
  //assuntos
  $arrAssuntos = PaginaSEI::getInstance()->getArrValuesSelect(PaginaSEI::getInstance()->recuperarCampo('hdnAssuntos'));
  $hdnAssuntos = PaginaSEI::getInstance()->recuperarCampo('hdnAssuntos');

  //campos apenas para Processos já avaliados
  //apenas processos com cpad avaliacao 'negado'
  $strSinDiscordancia = PaginaSEI::getInstance()->getCheckbox(PaginaSEI::getInstance()->recuperarCampo('chkSinDiscordancia')) ;
  //flag para tratar checkbox
  if (isset($_POST['hdnFlag'])) {
    PaginaSEI::getInstance()->salvarCampo('chkSinDiscordancia', $strSinDiscordancia);
  }
  //data da avaliacao documental
  $dtaPeriodoDe = PaginaSEI::getInstance()->recuperarCampo('txtPeriodoDe');
  $dtaPeriodoA = PaginaSEI::getInstance()->recuperarCampo('txtPeriodoA');
  //ajax de usuario da avaliacao documental
  $numIdAvaliador = PaginaSEI::getInstance()->recuperarCampo('hdnAvaliador');
  $strNomeAvaliador = PaginaSEI::getInstance()->recuperarCampo('txtAvaliador');
  //numero do protocolo
  $txtProtocoloPesquisa = PaginaSEI::getInstance()->recuperarCampo('txtProtocoloPesquisa');

  //dto criado para essa pesquisa
  $objPesquisaAvaliacaoDocumentalDTO = new PesquisaAvaliacaoDocumentalDTO();
  //distinct, pois a busca pode retornar o mesmo processo mais de uma vez, quando forem escolhidos varios assuntos e/ou quando um assunto estiver presente no processo, em um documento seu, em um processo anexado e/ou no documento de um processo anexado
  //atributos retornados
  $objPesquisaAvaliacaoDocumentalDTO->setDistinct(true);
  $objPesquisaAvaliacaoDocumentalDTO->retDblIdProtocolo();
  $objPesquisaAvaliacaoDocumentalDTO->retNumIdAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retDtaGeracao();
  $objPesquisaAvaliacaoDocumentalDTO->retStrProtocoloFormatado();
  $objPesquisaAvaliacaoDocumentalDTO->retStrNomeTipoProcedimento();
  $objPesquisaAvaliacaoDocumentalDTO->retDtaConclusaoProcedimento();
  $objPesquisaAvaliacaoDocumentalDTO->retStrStaAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retStrNomeUsuarioAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retStrSiglaUsuarioAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retNumIdUsuarioAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retDtaAvaliacaoDocumental();
  //filtros
  //filtra apenas por processos do orgao do usuario
  $objPesquisaAvaliacaoDocumentalDTO->setNumIdOrgaoUnidadeGeradoraProtocolo(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
  //periodo de geracao
  $objPesquisaAvaliacaoDocumentalDTO->setDtaGeracaoInicio($dtaGeracaoDe);
  $objPesquisaAvaliacaoDocumentalDTO->setDtaGeracaoFim($dtaGeracaoA);
  //periodo de conclusao
  $objPesquisaAvaliacaoDocumentalDTO->setDtaConclusaoInicio($dtaConclusaoDe);
  $objPesquisaAvaliacaoDocumentalDTO->setDtaConclusaoFim($dtaConclusaoA);
  //check de apenas processos avaliados
  //nao é um atributo mapeado para o banco no dto
  $objPesquisaAvaliacaoDocumentalDTO->setStrSinAvaliacao($strSinAvaliacao);
  // se for apenas processos já avaliados, seta filtros de data da avaliacao documental, usuario que avaliaou e se busca apenas processos que tiveram a avaliacao cpad 'negado'
  if($strSinAvaliacao == "S"){
    $objPesquisaAvaliacaoDocumentalDTO->setDtaPeriodoInicio($dtaPeriodoDe);
    $objPesquisaAvaliacaoDocumentalDTO->setDtaPeriodoFim($dtaPeriodoA);
    $objPesquisaAvaliacaoDocumentalDTO->setNumIdUsuarioAvaliacaoDocumental($numIdAvaliador);
    $objPesquisaAvaliacaoDocumentalDTO->setStrSinDiscordancia($strSinDiscordancia);
    //senao faz unset nos campos
  }else{
    $objPesquisaAvaliacaoDocumentalDTO->unSetDtaPeriodoInicio();
    $objPesquisaAvaliacaoDocumentalDTO->unSetDtaPeriodoFim();
    $objPesquisaAvaliacaoDocumentalDTO->unSetNumIdUsuarioAvaliacaoDocumental();
    $objPesquisaAvaliacaoDocumentalDTO->unSetStrSinDiscordancia();
  }
  //destinacao (guarda permanente ou eliminacao)
  if(!InfraString::isBolVazia($strStaDestinacao)) {
    $objPesquisaAvaliacaoDocumentalDTO->adicionarCriterio(
      array('StaDestinacaoAssunto', 'StaDestinacaoAssunto2'),
      array(InfraDTO::$OPER_IGUAL, InfraDTO::$OPER_IGUAL),
      array($strStaDestinacao, $strStaDestinacao),
      array(InfraDTO::$OPER_LOGICO_OR));

  }
  //testa se setou filtro de tipo de procedimento
  if ($numIdTipoProcedimento!= "null"){
    $objPesquisaAvaliacaoDocumentalDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
  }
  //se setou um protocolo para pesquisar
  if(!InfraString::isBolVazia($txtProtocoloPesquisa)){
    //retira a formatacao (o false é para nao retirar letras, pois tem processos em orgaos que tem letras)
    $objPesquisaAvaliacaoDocumentalDTO->setStrProtocoloFormatadoPesquisa(InfraUtil::retirarFormatacao(trim( $txtProtocoloPesquisa), false));
  }



  //busca assuntos definidos pela lupa
  if(count($arrAssuntos)){
    $arrObjRelProtocoloAssunto = array();
    foreach ($arrAssuntos as $idAssunto){
      $objRelProtocoloAssunto = new RelProtocoloAssuntoDTO();
      //a chave é o id assunto
      $objRelProtocoloAssunto->setNumIdAssunto($idAssunto);
      $arrObjRelProtocoloAssunto[]=$objRelProtocoloAssunto;
    }
    //array com os assuntos, que serão tratados na RN
    $objPesquisaAvaliacaoDocumentalDTO->setArrObjRelProtocoloAssuntoDTO($arrObjRelProtocoloAssunto);
  }
  //ordenacao desc pela data de conclusao
  PaginaSEI::getInstance()->prepararOrdenacao($objPesquisaAvaliacaoDocumentalDTO, 'ConclusaoProcedimento', InfraDTO::$TIPO_ORDENACAO_DESC);
  PaginaSEI::getInstance()->prepararPaginacao($objPesquisaAvaliacaoDocumentalDTO);
  //array de retorno
  $arrObjPesquisaAvaliacaoDocumentalDTO = array();
  try {
    //pesquisa
    $arrObjPesquisaAvaliacaoDocumentalDTO = $objProtocoloRN->pesquisarProtocolosAvaliacaoDocumental($objPesquisaAvaliacaoDocumentalDTO);
  }catch(Exception $e){
    PaginaSEI::getInstance()->processarExcecao($e);
  }
  //paginacao
  PaginaSEI::getInstance()->processarPaginacao($objPesquisaAvaliacaoDocumentalDTO);

  //testes e bools para indicar a marcação ou do radio 'Processos aguardando avaliação' ou do ' Processos já avaliados'
  //padrão é marcar o 'Processos aguardando avaliação'
  $bolMarcarAvaliados = false;
  $bolMarcarNaoAvaliados = false;
  $strVisibilityAvaliados = 'visibility:hidden;';
  if ($strSinAvaliacao != "") {
    if ($strSinAvaliacao == "S" ) {
      $bolMarcarAvaliados = true;
      $strVisibilityAvaliados = '';
    } else if ($strSinAvaliacao == "N") {
      $bolMarcarNaoAvaliados = true;
    }
  }else{
    $bolMarcarNaoAvaliados = true;
  }

  $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('avaliacao_dcoumental_alterar');

  $numRegistros = InfraArray::contar($arrObjPesquisaAvaliacaoDocumentalDTO);
  if ($numRegistros) {

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Processos.';
    $strCaptionTabela = 'Processos';

    $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" style="display: none;">'.PaginaSEI::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="20%">Processo</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO,'Autuação','Geracao',$arrObjPesquisaAvaliacaoDocumentalDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO,'Conclusão','ConclusaoProcedimento',$arrObjPesquisaAvaliacaoDocumentalDTO).'</th>' . "\n";
    $strResultado .= '<th class="infraTh">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO,'Tipo','NomeTipoProcedimento',$arrObjPesquisaAvaliacaoDocumentalDTO).'</th>' . "\n";
    //se forem apenas processos já avaliados, mostra duas colunas a mais
    if($strSinAvaliacao == 'S'){
      $strResultado .= '<th class="infraTh" width="10%">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO,'Avaliação','AvaliacaoDocumental',$arrObjPesquisaAvaliacaoDocumentalDTO).'</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="5%">'.PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO,'Avaliador','SiglaUsuarioAvaliacaoDocumental',$arrObjPesquisaAvaliacaoDocumentalDTO).'</th>' . "\n";
    }


    $strResultado .= '<th class="infraTh" width="5%">Ações</th>' . "\n";
    $strResultado .= '</tr>' . "\n";
    $strCssTr = '';

    $bolAcaoAlterar = SessaoSEI::getInstance()->verificarPermissao('avaliacao_documental_alterar');
    $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('avaliacao_documental_cadastrar');

    $strCssTr = "";
    for ($i = 0; $i < $numRegistros; $i++) {
      //se houve pelo menos uma avaliacao cpad 'negado', ativa ou nao, pinta a linha de vermelho
      $strLinhaVermelha = "";
      if ($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getStrSinDiscordancia() == "S") {
        $strLinhaVermelha = " trVermelha ";
      }

      if (($i+1) % 2 == 0) {
        $strCssTr = '<tr class="infraTrEscura' . $strLinhaVermelha . '">';
      } else {
        $strCssTr = '<tr class="infraTrClara' . $strLinhaVermelha . '">';
      }
      $strResultado .= $strCssTr;
      //id da avaliacao documental
      $strId = $arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getNumIdAvaliacaoDocumental();
      //id do processo
      $strIdProtocolo = $arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getDblIdProtocolo();
      $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getStrProtocoloFormatado());
      //coluna escondida
      $strResultado .= '<td valign="top" style="display: none;">'.PaginaSEI::getInstance()->getTrCheck($i,$strIdProtocolo,$strDescricao).'</td>';
      $strResultado .= '<td width="17%" align="center"><a target="_blank" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getDblIdProtocolo()).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela().'" title="'.PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getStrNomeTipoProcedimento()).'" class="protocoloNormal" style="font-size:1em !important;">'.PaginaSEI::tratarHTML($strDescricao).'</a></td>'."\n";
      $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getDtaGeracao()) . '</td>' . "\n";
      $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getDtaConclusaoProcedimento()) . '</td>' . "\n";
      $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getStrNomeTipoProcedimento()) . '</td>' . "\n";
      //se forem apenas processos já avaliados, mostra duas colunas a mais
      if($strSinAvaliacao == 'S'){
        $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getDtaAvaliacaoDocumental()) . '</td>' . "\n";
        $strResultado .= '<td align="center">    <a alt="'.PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getStrNomeUsuarioAvaliacaoDocumental()).'" title="'.PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getStrNomeUsuarioAvaliacaoDocumental()).'" class="ancoraSigla">'.PaginaSEI::tratarHTML($arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getStrSiglaUsuarioAvaliacaoDocumental()).'</a></td>';
      }

      $strResultado .= '<td align="center">';
      //aproveita o bool indicando se o radio de 'Processos aguardando avaliação' foi marcado
      if($bolMarcarNaoAvaliados){
        //se tem permissao para cadastrar, mostra icone com link para cadastrar avaliacao documental, passando o id do processo
        if($bolAcaoCadastrar) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=avaliacao_documental_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getDblIdProtocolo()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="'.Icone::AVALIACAO_DOCUMENTAL.'" title="Cadastrar Avaliação Documental" alt="Cadastrar Avaliação Documental" class="infraImg" /></a>&nbsp;';
        }
        //radio 'Processos já avaliados' foi marcado
      }else{
        //se tem permissao para alterar, mostra icone com link para alterar avaliacao documental, passando o id do processo
        if($bolAcaoAlterar) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=avaliacao_documental_alterar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $arrObjPesquisaAvaliacaoDocumentalDTO[$i]->getDblIdProtocolo()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="'.Icone::AVALIACAO_DOCUMENTAL.'" title="Alterar Avaliação Documental" alt="Alterar Avaliação Documental" class="infraImg" /></a>&nbsp;';
        }
      }

      $strResultado .= '</td>' . "\n";
      $strResultado .= '</tr>' . "\n";
    }
    $strResultado .= '</table>' . "\n";
  }

  //links para selecao de assuntos
  $strLinkAssuntosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_selecionar&tipo_selecao=2&id_object=objLupaAssuntos');
  $strLinkAjaxAssuntoRI1223 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assunto_auto_completar_RI1223');
  //select de tipos de processos
  $strSelTipoProcedimento = TipoProcedimentoINT::montarSelectNome('null', 'Todos', $numIdTipoProcedimento);

  //strings indicando qual option do select de destinacao deve ser marcado
  //padrao é 'todos'
  $strChkGuardaPermanente = '';
  $strChkEliminacao = '';
  $strChkTodos = '';
  if ($strStaDestinacao==AssuntoRN::$TD_ELIMINACAO){
    $strChkGuardaPermanente = '';
    $strChkEliminacao = 'selected="selected"';
    $strChkTodos = '';

  }else if ($strStaDestinacao==AssuntoRN::$TD_GUARDA_PERMANENTE){
    $strChkGuardaPermanente = 'selected="selected"';
    $strChkEliminacao = '';
    $strChkTodos = '';
  }else{
    $strChkGuardaPermanente = '';
    $strChkEliminacao = '';
    $strChkTodos = 'selected="selected"';
  }

  $strLinkAjaxAvaliador = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar');
  $strLinkConsultarAssunto = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_consultar&acao_origem='.$_GET['acao']);

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
  .calendario{position:relative;top:2px;}
  .combo{position:relative;top:2px;}

  #lblTipoProcedimento {position:absolute;left:0%;top:0%;}
  #selTipoProcedimento {position:absolute;left:0%;top:40%;width:50%}

  #lblProtocoloPesquisa{position:absolute;left:55%;top:0%;}
  #txtProtocoloPesquisa{position:absolute;left:55%;top:40%;width:30%}

  #lblAssuntos {position:absolute;left:0%;top:3%;}
  #txtAssunto {position:absolute;left:0%;top:19%;width:96.7%;}
  #selAssuntos {position:absolute;left:0%;top:41%;width:97%;height:51%;}
  #divOpcoesAssuntos {position:absolute;left:98%;top:41%;}

  #lblDestinacao {position:absolute;left:0%;top:0%;}
  #selDestinacao {width:45%;position:absolute;left:0%;top:13%;}

  #lblGeracaoDe {position:absolute;top:35%;left:0%;}
  #txtGeracaoDe {position:absolute;top:48%;left:0%;width:15%;}
  #imgCalGeracaoDe{position:absolute;top:50%;left:16%;}
  #lblGeracaoA  {position:absolute;top:50%;left:19%;}
  #txtGeracaoA  {position:absolute;top:48%;left:22%;width:15%;}
  #imgCalGeracaoA{position:absolute;top:50%;left:38%;}

  #lblConclusaoDe {position:absolute;top:68%;left:0%;}
  #txtConclusaoDe {position:absolute;top:81%;left:0%;width:15%;}
  #imgCalConclusaoDe{position:absolute;top:83%;left:16%;}
  #lblConclusaoA  {position:absolute;top:83%;left:19%;}
  #txtConclusaoA  {position:absolute;top:81%;left:22%;width:15%;}
  #imgCalConclusaoA{position:absolute;top:83%;left:38%;}

  #lblAvaliador {position:absolute;top:0%;left:50%;<?=$strVisibilityAvaliados?>}
  #txtAvaliador {position:absolute;top:15%;left:50%;width:45%;<?=$strVisibilityAvaliados?>}

  #lblPeriodoDe {position:absolute;top:35%;left:50%;<?=$strVisibilityAvaliados?>}
  #txtPeriodoDe {position:absolute;top:50%;left:50%;width:15%;<?=$strVisibilityAvaliados?>}
  #imgCalPeriodoDe{position:absolute;top:52%;left:66%;<?=$strVisibilityAvaliados?>}
  #lblPeriodoA  {position:absolute;top:52%;left:69%;<?=$strVisibilityAvaliados?>}
  #txtPeriodoA  {position:absolute;top:50%;left:72%;width:15%;<?=$strVisibilityAvaliados?>}
  #imgCalPeriodoA{position:absolute;top:50%;left:88%;<?=$strVisibilityAvaliados?>}

  #divDiscordancia {position:absolute;left:50%;top:83%;<?=$strVisibilityAvaliados?>}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
  var objAutoCompletarAvaliador;


  function inicializar(){
    objAutoCompletarAssuntoRI1223 = new infraAjaxAutoCompletar('hdnIdAssunto','txtAssunto','<?= $strLinkAjaxAssuntoRI1223 ?>');
    //objAutoCompletarAssuntoRI1223.maiusculas = true;
    //objAutoCompletarAssuntoRI1223.mostrarAviso = true;
    //objAutoCompletarAssuntoRI1223.tempoAviso = 1000;
    //objAutoCompletarAssuntoRI1223.tamanhoMinimo = 3;
    objAutoCompletarAssuntoRI1223.limparCampo = true;
    //objAutoCompletarAssuntoRI1223.bolExecucaoAutomatica = false;

    objAutoCompletarAssuntoRI1223.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtAssunto').value;
    };

    objAutoCompletarAssuntoRI1223.processarResultado = function(id,descricao,complemento){
      if (id!=''){
      objLupaAssuntos.adicionar(id,descricao,document.getElementById('txtAssunto'));
      }
    };

    //Inicializa campos hidden com valores das listas
    objLupaAssuntos = new infraLupaSelect('selAssuntos','hdnAssuntos','<?=$strLinkAssuntosSelecao?>');

    objLupaAssuntos.processarAlteracao = function (pos, texto, valor){
      seiConsultarAssunto(valor, 'selAssuntos','frmAvaliacaoDocumental','<?=$strLinkConsultarAssunto?>');
    }

    document.getElementById('selAssuntos').ondblclick = function(e){
      objLupaAssuntos.alterar();
    };

    //campo Avaliador
    objAutoCompletarAvaliador = new infraAjaxAutoCompletar('hdnAvaliador','txtAvaliador','<?= $strLinkAjaxAvaliador ?>');
    objAutoCompletarAvaliador.limparCampo = true;
    objAutoCompletarAvaliador.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtAvaliador').value;
    };
    objAutoCompletarAvaliador.selecionar('<?=$numIdAvaliador?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($strNomeAvaliador)?>');
    objAutoCompletarAvaliador.processarResultado = function(id, descricao, complemento){
      if (id!=''){
        document.getElementById('hdnAvaliador').value = id;
        document.getElementById('txtAvaliador').value = descricao;
      }
    };

    infraEfeitoTabelas();
  }

  function onSubmitForm(){
    return true;
  }

  function mudarAvaliacao(){
    if(document.getElementById('rdoAvaliados').checked){
      document.getElementById('lblAvaliador').style.visibility = 'visible';
      document.getElementById('txtAvaliador').style.visibility = 'visible';
      document.getElementById('lblPeriodoDe').style.visibility = 'visible';
      document.getElementById('txtPeriodoDe').style.visibility = 'visible';
      document.getElementById('imgCalPeriodoDe').style.visibility = 'visible';
      document.getElementById('lblPeriodoA').style.visibility = 'visible';
      document.getElementById('txtPeriodoA').style.visibility = 'visible';
      document.getElementById('imgCalPeriodoA').style.visibility = 'visible';
      document.getElementById('divDiscordancia').style.visibility = 'visible';
    }else{
      document.getElementById('lblAvaliador').style.visibility = 'hidden';
      document.getElementById('txtAvaliador').style.visibility = 'hidden';
      document.getElementById('lblPeriodoDe').style.visibility = 'hidden';
      document.getElementById('txtPeriodoDe').style.visibility = 'hidden';
      document.getElementById('imgCalPeriodoDe').style.visibility = 'hidden';
      document.getElementById('lblPeriodoA').style.visibility = 'hidden';
      document.getElementById('txtPeriodoA').style.visibility = 'hidden';
      document.getElementById('imgCalPeriodoA').style.visibility = 'hidden';
      document.getElementById('divDiscordancia').style.visibility = 'hidden';
    }
  }

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmAvaliacaoDocumental" onsubmit="return onSubmitForm();" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
    <?
    //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

    ?>

    <div id="divAvaliacao" class="infraAreaDados" style="height:3em;">
      <input type="radio" onchange="mudarAvaliacao()" name="rdoAvaliacao"
             id="rdoNaoAvaliados" <?= ($bolMarcarNaoAvaliados ? 'checked="checked"' : '') ?>
             value="N" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label  for="rdoNaoAvaliados" class="infraLabelRadio"
              tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Processos aguardando avaliação</label>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="radio" onchange="mudarAvaliacao()" name="rdoAvaliacao"
             id="rdoAvaliados" <?= ($bolMarcarAvaliados ? 'checked="checked"' : '') ?>
             value="S" class="infraRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label  for="rdoAvaliados" class="infraLabelRadio"
              tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>">Processos já avaliados</label>
    </div>

    <div id="divProcedimento"  class="infraAreaDados" style="height:5em;">
      <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelOpcional">Tipo do Processo:</label>
      <select id="selTipoProcedimento" name="selTipoProcedimento"  class="infraSelect combo" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
        <?=$strSelTipoProcedimento?>
      </select>

      <label id="lblProtocoloPesquisa" for="txtProtocoloPesquisa" accesskey="" class="infraLabelOpcional">Processo:</label>
      <input type="text" id="txtProtocoloPesquisa" name="txtProtocoloPesquisa" class="infraText" maxlength="50" value="<?=PaginaSEI::tratarHTML($txtProtocoloPesquisa)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>

    <div id="divAssuntos"  class="infraAreaDados" style="height:12em;">
      <label id="lblAssuntos" for="txtAssunto" accesskey="u" class="infraLabelOpcional">Ass<span class="infraTeclaAtalho">u</span>ntos:</label>
      <input type="text" id="txtAssunto" name="txtAssunto" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnIdAssunto" name="hdnIdAssunto" class="infraText" value="" />
      <select id="selAssuntos" name="selAssuntos" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
      </select>
      <div id="divOpcoesAssuntos">
        <img id="imgPesquisarAssuntos"  onclick="objLupaAssuntos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Pesquisa de Assuntos" title="Pesquisa de Assuntos" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <img id="imgRemoverAssuntos" onclick="objLupaAssuntos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Assuntos Selecionados" title="Remover Assuntos Selecionados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      </div>
    </div>
    <input type="hidden" id="hdnAssuntos" name="hdnAssuntos" value="<?=$hdnAssuntos?>" />

    <div id="divNaoAvaliados" class="infraAreaDados" style="height:15em;">
      <label id="lblDestinacao" for="selDestinacao" accesskey="" class="infraLabelOpcional">Destinação:</label>
      <select id="selDestinacao" name="selDestinacao"  class="infraSelect combo" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
        <option value="" <?=$strChkTodos?>>Todos</option>
        <option value="<?=AssuntoRN::$TD_GUARDA_PERMANENTE?>" <?=$strChkGuardaPermanente?>>Guarda Permanente</option>
        <option value="<?=AssuntoRN::$TD_ELIMINACAO?>" <?=$strChkEliminacao?>>Eliminação</option>
      </select>

      <label id="lblGeracaoDe" for="txtGeracaoDe" accesskey="" class="infraLabelOpcional">Autuação:</label>
      <input type="text" id="txtGeracaoDe" name="txtGeracaoDe" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaGeracaoDe)?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <img id="imgCalGeracaoDe" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg calendario" onclick="infraCalendario('txtGeracaoDe',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblGeracaoA" for="txtGeracaoA" accesskey="" class="infraLabelOpcional">&nbsp;&nbsp;a&nbsp;&nbsp;</label>
      <input type="text" id="txtGeracaoA" name="txtGeracaoA" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaGeracaoA)?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <img id="imgCalGeracaoA"  title="Selecionar Data Final" alt="Selecionar Data Final" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg calendario" onclick="infraCalendario('txtGeracaoA',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />


      <label id="lblConclusaoDe" for="txtConclusaoDe" accesskey="" class="infraLabelOpcional">Conclusão:</label>
      <input type="text" id="txtConclusaoDe" name="txtConclusaoDe" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaConclusaoDe)?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <img id="imgCalConclusaoDe" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg calendario" onclick="infraCalendario('txtConclusaoDe',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblConclusaoA" for="txtConclusaoA" accesskey="" class="infraLabelOpcional">&nbsp;&nbsp;a&nbsp;&nbsp;</label>
      <input type="text" id="txtConclusaoA" name="txtConclusaoA" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaConclusaoA)?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <img id=  "imgCalConclusaoA" title="Selecionar Data Final" alt="Selecionar Data Final" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg calendario" onclick="infraCalendario('txtConclusaoA',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <label id="lblAvaliador" for="txtAvaliador" accesskey="" class="infraLabelOpcional">Usuário:</label>
      <input type="text" id="txtAvaliador" name="txtAvaliador" class="infraText" value="" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <input type="hidden" id="hdnAvaliador" name="hdnAvaliador" value="<?=$numIdAvaliador?>" />

      <label id="lblPeriodoDe" for="txtPeriodoDe" accesskey="" class="infraLabelOpcional">Data da Avaliação:</label>
      <input type="text" id="txtPeriodoDe" name="txtPeriodoDe" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaPeriodoDe)?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <img id="imgCalPeriodoDe" title="Selecionar Data Inicial" alt="Selecionar Data Inicial" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg calendario" onclick="infraCalendario('txtPeriodoDe',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblPeriodoA" for="txtPeriodoA" accesskey="" class="infraLabelOpcional">&nbsp;&nbsp;a&nbsp;&nbsp;</label>
      <input type="text" id="txtPeriodoA" name="txtPeriodoA" class="infraText" value="<?=PaginaSEI::tratarHTML($dtaPeriodoA)?>" onkeypress="return infraMascaraData(this, event)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <img id=  "imgCalPeriodoA" title="Selecionar Data Final" alt="Selecionar Data Final" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg calendario" onclick="infraCalendario('txtPeriodoA',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <div id="divDiscordancia" class="infraDivCheckbox" >
        <input type="checkbox" id="chkSinDiscordancia" name="chkSinDiscordancia"  class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox($strSinDiscordancia) ?>  tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
        <label id="lblSinDiscordancia" for="chkSinDiscordancia" accesskey="" class="infraLabelCheckbox">Somente com discordância não resolvida</label>
      </div>

    </div>

    <input type="hidden" name="hdnFlag" value="1" />

    <input type="hidden" id="hdnAssuntoIdentificador" name="hdnAssuntoIdentificador" value="" />

    <?

    PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    PaginaSEI::getInstance()->montarAreaDebug();
    PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);

    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>