<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 19/10/2018 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
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

  PaginaSEI::getInstance()->verificarSelecao('avaliacao_documental_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  //////////////////////////////////////////////////////////////////////////////
  /// a tela apresenta campos da avaliacao documental: data e avaliador (usuario)
  /// se é cadastro, esses campos sao carregados com a hora atual e com o usuario atual
  /// se for alteracao, busca a data e quem realizou a avaliacao documental
  ///
  /// tambem apresenta os campos processo e tipo do processo
  ///
  /// o usuário deve obrigatoriamente informar um assunto para a avaliacao documental
  /// ou esse assunto pode ser selecionado na tabela de assuntos
  ///   essa tabela de assuntos lista os assuntos cadastrados/vinculados ao processo, aos seus documentos, a processos anexados ou a documentos dos processos anexados
  /// ou esse assunto pode ser buscado pela lupa
  ///   essa lupa lista outros assuntos alem dos presentes na tabela, assim o usuario pode escolher um assunto 'novo'
  ///
  /// quando é alteracao, a tela tambem lista avaliacoes cpad negadas, ativas e nao ativas
  /// nas ativas, exibe um textarea para o usuario digitar uma justificativa
  /// nas nao ativas, exibe o texto com a justificativa informada anteriormente
  /// ao submeter a alteracao, todas as avaliacoes cpad negadas ativas devem ser justificadas
  //////////////////////////////////////////////////////////////////////////////


  //retorna o id do procedimento, se veio do link (get) ou da submicao do form (post)
  $idProcedimento = null;
  if($_GET['id_procedimento'] != null){
    $idProcedimento=$_GET['id_procedimento'];
  }else if($_POST['id_procedimento'] != null){
    $idProcedimento=$_POST['id_procedimento'];
  }
  //retorna id do assunto selecionado
  $idAssunto = $_POST['hdnAssunto'];
  //retorna descricao do assunto selecionado
  $descricaoAssunto = $_POST['txtAssunto'];

  //id do orgao da sessao atual, que será usado como filtro para buscar o procedimento, que terá dados exibidos na tela
  $idOrgaoAtual = SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual();
    //se for alteracao, exibe a data e usuario que realizou a avaliacao documental; se for cadastro, exibe a data atual e o usuario da sessao
  //tambem exibe o numero do processo (com link) e o tipo do processo
  $objPesquisaAvaliacaoDocumentalDTO = new PesquisaAvaliacaoDocumentalDTO();
  $objPesquisaAvaliacaoDocumentalDTO->retDblIdProtocolo();
  $objPesquisaAvaliacaoDocumentalDTO->retDtaGeracao();
  $objPesquisaAvaliacaoDocumentalDTO->retStrProtocoloFormatado();
  $objPesquisaAvaliacaoDocumentalDTO->retStrNomeTipoProcedimento();
  $objPesquisaAvaliacaoDocumentalDTO->retDtaConclusaoProcedimento();
  $objPesquisaAvaliacaoDocumentalDTO->retStrStaAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retStrNomeUsuarioAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retStrSiglaUsuarioAvaliacaoDocumental();
  $objPesquisaAvaliacaoDocumentalDTO->retDtaAvaliacaoDocumental();
  //filtra pelo id do processo e orgao da sessao, pois podem haver avaliacoes documentais de diferentes orgaos para o mesmo processo
  $objPesquisaAvaliacaoDocumentalDTO->setDblIdProtocolo($idProcedimento);
  $objPesquisaAvaliacaoDocumentalDTO->setNumIdOrgaoAvaliacaoDocumental($idOrgaoAtual);

  $objProtocoloRN = new ProtocoloRN();
  $objPesquisaAvaliacaoDocumentalDTO = $objProtocoloRN->consultarProtocoloAvaliacaoDocumental($objPesquisaAvaliacaoDocumentalDTO );

  $objAvaliacaoDocumentalDTO = new AvaliacaoDocumentalDTO();
  $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();

  $arrComandos = array();

  $strDesabilitar = '';
  $strExibirLupa = '';

  switch($_GET['acao']){
    case 'avaliacao_documental_cadastrar':
      $strTitulo = 'Nova Avaliação Documental';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarAvaliacaoDocumental" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_procedimento='.$idProcedimento.PaginaSEI::getInstance()->montarAncora($idProcedimento)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      //inicializa e carrega campos quando é cadastro
      $objAvaliacaoDocumentalDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
      $objAvaliacaoDocumentalDTO->setStrSiglaUsuario(SessaoSEI::getInstance()->getStrSiglaUsuario());
      $objAvaliacaoDocumentalDTO->setStrNomeUsuario(SessaoSEI::getInstance()->getStrNomeUsuario());
      $objAvaliacaoDocumentalDTO->setDtaAvaliacao(InfraData::getStrDataAtual());
      $objAvaliacaoDocumentalDTO->setNumIdAssunto(null);

      if (isset($_POST['sbmCadastrarAvaliacaoDocumental'])) {
        try{
          //na submicao, retorna o id do processo e o id do assunto selecionado
          $objAvaliacaoDocumentalDTO->setDblIdProcedimento($idProcedimento);
          //seta o id do assunto escolhido
          $objAvaliacaoDocumentalDTO->setNumIdAssunto($idAssunto);
          //id assunto original é o assunto selecionado, guardado caso tenha mudança na tabela de assuntos, assim mostra o que foi escolhido na epoca
          $objAvaliacaoDocumentalDTO->setNumIdAssuntoOriginal($idAssunto);
          //id do assunto proxy será buscado depois, a partir do id assunto
          $objAvaliacaoDocumentalDTO->setNumIdAssuntoProxy(null);
          //id da unidade atual
          $objAvaliacaoDocumentalDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
          //o status inicial da avaliacao é 'avaliado'
          $objAvaliacaoDocumentalDTO->setStrStaAvaliacao(AvaliacaoDocumentalRN::$TA_AVALIADO);
          $objAvaliacaoDocumentalDTO = $objAvaliacaoDocumentalRN->cadastrar($objAvaliacaoDocumentalDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Avaliação Documental "'.$objAvaliacaoDocumentalDTO->getDblIdProcedimento().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_procedimento='.$idProcedimento.PaginaSEI::getInstance()->montarAncora($idProcedimento)));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;


    case 'avaliacao_documental_consultar':
    case 'avaliacao_documental_alterar':

      if($_GET['acao'] == 'avaliacao_documental_consultar'){
        $strTitulo = 'Consultar Avaliação Documental';
        $arrComandos[] = '<button type="button" accesskey="F" id="btnFecharSelecao" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
        $strDesabilitar = 'disabled="disabled"';
        $strExibirLupa = 'visibility:hidden';
      }else{
        $strTitulo = 'Alterar Avaliação Documental';
        $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarAvaliacaoDocumental" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_procedimento='.$idProcedimento.PaginaSEI::getInstance()->montarAncora($idProcedimento)).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }

      //na alteracao, sao buscados os dados da avaliacao documental
      $objAvaliacaoDocumentalDTO->retNumIdAvaliacaoDocumental();
      $objAvaliacaoDocumentalDTO->retDblIdProcedimento();
      $objAvaliacaoDocumentalDTO->retStrSiglaUsuario();
      $objAvaliacaoDocumentalDTO->retStrNomeUsuario();
      $objAvaliacaoDocumentalDTO->retDtaAvaliacao();
      $objAvaliacaoDocumentalDTO->retNumIdAssunto();
      $objAvaliacaoDocumentalDTO->retNumIdAssuntoOriginal();
      $objAvaliacaoDocumentalDTO->retStrCodigoEstruturadoAssunto();
      $objAvaliacaoDocumentalDTO->retStrCodigoEstruturadoAssuntoOriginal();
      $objAvaliacaoDocumentalDTO->retStrDescricaoAssuntoOriginal();
      $objAvaliacaoDocumentalDTO->retStrDescricaoAssunto();
      //busca pelo id do processo e orgao atual, mas poderia ser pelo id da avaliacao documental
      $objAvaliacaoDocumentalDTO->setDblIdProcedimento($idProcedimento);
      $objAvaliacaoDocumentalDTO->setNumIdOrgao($idOrgaoAtual);
      //consulta
      $objAvaliacaoDocumentalDTO=$objAvaliacaoDocumentalRN->consultar($objAvaliacaoDocumentalDTO);
      //seta dados para os campos de assunto
      $descricaoAssunto = AssuntoINT::formatarCodigoDescricaoRI0568($objAvaliacaoDocumentalDTO->getStrCodigoEstruturadoAssunto(),$objAvaliacaoDocumentalDTO->getStrDescricaoAssunto());
      $idAssunto = $objAvaliacaoDocumentalDTO->getNumIdAssunto();

      //busca as avaliacoes cpad 'negado' realizadas para essa avaliacao documental
      $objCpadAvaliacaoRN = new CpadAvaliacaoRN();
      $objCpadAvaliacaoDTO = new CpadAvaliacaoDTO();
      $objCpadAvaliacaoDTO->retNumIdCpadAvaliacao();
      //quando foi a avaliacao cpad
      $objCpadAvaliacaoDTO->retDthAvaliacao();
      //usuario da avaliacao cpad
      $objCpadAvaliacaoDTO->retNumIdUsuario();
      $objCpadAvaliacaoDTO->retStrSiglaUsuario();
      $objCpadAvaliacaoDTO->retStrNomeUsuario();
      //se ativa ou nao
      $objCpadAvaliacaoDTO->retStrSinAtivo();
      //deve retornar as avaliacoes ativas e as nao ativas
      $objCpadAvaliacaoDTO->setBolExclusaoLogica(false);
      //negado
      $objCpadAvaliacaoDTO->retStrStaCpadAvaliacao();
      //motivo de negado
      $objCpadAvaliacaoDTO->retStrMotivo();
      //justificativa, caso exista
      $objCpadAvaliacaoDTO->retStrJustificativa();
      //filtra pelo id da avaliacao documental
      $objCpadAvaliacaoDTO->setNumIdAvaliacaoDocumental($objAvaliacaoDocumentalDTO->getNumIdAvaliacaoDocumental());
      //apenas as negadas sao listadas
      $objCpadAvaliacaoDTO->setStrStaCpadAvaliacao(CpadAvaliacaoRN::$TA_CPAD_NEGADO);
      //ordena pela data do cadastro da avaliacao cpad decrescente, para mostrar as ultimas primeiro
      $objCpadAvaliacaoDTO->setOrdDthAvaliacao(InfraDTO::$TIPO_ORDENACAO_DESC);
      //busca lista
      $arrObjCpadAvaliacaoDTO = $objCpadAvaliacaoRN->listar($objCpadAvaliacaoDTO);

      //busca historico de avaliacoes que concordaram (apenas as ultimas/ativas)
      $objCpadAvaliacaoDTO_Concordancias = new CpadAvaliacaoDTO();
      //campos de retorno
      $objCpadAvaliacaoDTO_Concordancias->retNumIdCpadAvaliacao();
      $objCpadAvaliacaoDTO_Concordancias->retDthAvaliacao();
      $objCpadAvaliacaoDTO_Concordancias->retNumIdUsuario();
      $objCpadAvaliacaoDTO_Concordancias->retStrSiglaUsuario();
      $objCpadAvaliacaoDTO_Concordancias->retStrNomeUsuario();
      //avaliacoes cpad dessa avaliacao documental
      $objCpadAvaliacaoDTO_Concordancias->setNumIdAvaliacaoDocumental($objAvaliacaoDocumentalDTO->getNumIdAvaliacaoDocumental());
      //apenas as que concordaram
      $objCpadAvaliacaoDTO_Concordancias->setStrStaCpadAvaliacao(CpadAvaliacaoRN::$TA_CPAD_AVALIADO);
      $objCpadAvaliacaoDTO_Concordancias->setOrdDthAvaliacao(InfraDTO::$TIPO_ORDENACAO_DESC);

      //busca
      $arrObjCpadAvaliacaoDTO_Concordancias = $objCpadAvaliacaoRN->listar($objCpadAvaliacaoDTO_Concordancias);

      //submicao da alteracao
      if (isset($_POST['sbmAlterarAvaliacaoDocumental'])) {
        try{
          //atualiza a data da avaliacao
          $objAvaliacaoDocumentalDTO->setDtaAvaliacao(InfraData::getStrDataHoraAtual());
          //atualiza o assunto
          $objAvaliacaoDocumentalDTO->setNumIdAssunto($_POST['hdnAssunto']);
          //id assunto original é guardado caso tenha mudança na tabela de assuntos, assim mostra o que foi escolhido na epoca
          $objAvaliacaoDocumentalDTO->setNumIdAssuntoOriginal($_POST['hdnAssunto']);
          //atualiza o usuario
          $objAvaliacaoDocumentalDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
          //array que contem as avalicoes cpad negadas, que serao atualizadas
          //todas as avaliacoes cpad negadas que ainda nao foram justificadas devem ser justificadas
          $arrObjCpadAvaliacaoDTO_Atualizacao = array();
          if(InfraArray::contar($arrObjCpadAvaliacaoDTO) > 0){
            foreach ($arrObjCpadAvaliacaoDTO as $objCpadAvaliacaoDTO){
              //testa se a avaliacao cpad (negada) é ativa, o que indica que deve ser justificada e que a tela exibiu um textarea para o usuario informar a justificativa
              //o usuário sempre deve justificar todas as avaliacoes cpad (negadas) ativas para submeter o formulario
              //as avaliacoes cpad (negadas) nao ativas sao exibidas como historico
              if($objCpadAvaliacaoDTO->getStrSinAtivo() == "S"){
                $objCpadAvaliacaoDTO->setStrJustificativa($_POST['txaJustificativa' . $objCpadAvaliacaoDTO->getNumIdCpadAvaliacao()]);
                //insere no array
                $arrObjCpadAvaliacaoDTO_Atualizacao[] = $objCpadAvaliacaoDTO;
              }
            }
          }
          //seta array
          $objAvaliacaoDocumentalDTO->setArrObjCpadAvaliacaoDTO($arrObjCpadAvaliacaoDTO_Atualizacao);

          $objAvaliacaoDocumentalRN = new AvaliacaoDocumentalRN();
          //altera uma avaliacao documental já avaliada
          //esse metodo chama o alterar padrao primeiramente
          $objAvaliacaoDocumentalRN->alterarAvaliado($objAvaliacaoDocumentalDTO);

          PaginaSEI::getInstance()->adicionarMensagem('Avaliação Documental "'.$objAvaliacaoDocumentalDTO->getDblIdProcedimento().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_procedimento='.$idProcedimento.PaginaSEI::getInstance()->montarAncora($idProcedimento)));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  //links ajax de assuntos
  $strLinkAssuntosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_selecionar&id_object=objLupaAssuntos&tipo_selecao=1');
  $strLinkAjaxAssuntoRI1223 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assunto_auto_completar_RI1223');

  //busca os assuntos
  $objRelProtocoloAssuntoDTO = new RelProtocoloAssuntoDTO();
  $objRelProtocoloAssuntoDTO->setDblIdProtocolo($idProcedimento);
  $arrObjRelProtocoloAssuntoDTO = $objAvaliacaoDocumentalRN->listarAssuntosProcesso($objRelProtocoloAssuntoDTO);

  //tabela dos assuntos
  $numRegistrosAssunto = count($arrObjRelProtocoloAssuntoDTO);
  if ($numRegistrosAssunto) {

    $strResultadoAssunto = '';

    $strSumarioTabela = 'Tabela de Assuntos.';
    $strCaptionTabela = 'Assuntos';

    $strResultadoAssunto .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
    $strResultadoAssunto .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistrosAssunto) . '</caption>';
    $strResultadoAssunto .= '<tr>';
    $strResultadoAssunto .= '<th class="infraTh" width="1%"></th>' . "\n";
    $strResultadoAssunto .= '<th class="infraTh" width="15%">Protocolo</th>' . "\n";
    $strResultadoAssunto .= '<th class="infraTh" width="10%">Código</th>' . "\n";
    $strResultadoAssunto .= '<th class="infraTh" width="30%" >Descrição</th>' . "\n";
    $strResultadoAssunto .= '<th class="infraTh" width="6%">Prazo Corrente</th>' . "\n";
    $strResultadoAssunto .= '<th class="infraTh" width="8%">Prazo Intermediário</th>' . "\n";
    $strResultadoAssunto .= '<th class="infraTh" width="10%">Destinação Final</th>' . "\n";
    $strResultadoAssunto .= '<th class="infraTh" >Observações</th>' . "\n";
    $strResultadoAssunto .= '</tr>' . "\n";
    $strCssTr = '';

    for ($i = 0; $i < $numRegistrosAssunto; $i++) {


      $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
      $strResultadoAssunto .= $strCssTr;

      $strId = $arrObjRelProtocoloAssuntoDTO[$i]->getNumIdAssunto();
      if ($arrObjRelProtocoloAssuntoDTO[$i]->getStrStaDestinacaoAssunto()==AssuntoRN::$TD_ELIMINACAO){
        $strDestinacao = 'Eliminação';

      }else if ($arrObjRelProtocoloAssuntoDTO[$i]->getStrStaDestinacaoAssunto()==AssuntoRN::$TD_GUARDA_PERMANENTE){
        $strDestinacao = 'Guarda Permanente';
      }

      $strResultadoAssunto .= '<td align="center"><input type="radio" '.($objAvaliacaoDocumentalDTO->getNumIdAssunto() == $strId ? "checked='checked'" : '').' '.$strDesabilitar.' onchange="mudarAssunto(this.value,this.getAttribute(\'cod\'),this.getAttribute(\'desc\'))" name="rdoIdAssunto" value="'.$strId.'" cod="'.PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrCodigoEstruturadoAssunto()).'" desc="' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrDescricaoAssunto()) . '" /></td>'."\n";
      //testa se o assunto é de um procedimento ou documento, para mostrar/montar o link
      if($arrObjRelProtocoloAssuntoDTO[$i]->getStrStaProtocolo() == ProtocoloRN::$TP_PROCEDIMENTO) {
        $strResultadoAssunto .= '<td align="center"><a target="_blank" title="'.PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrTipoProtocolo()).'"  href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $arrObjRelProtocoloAssuntoDTO[$i]->getDblIdProtocolo()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="protocoloNormal" style="font-size:1em !important;">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrProtocoloFormatadoProtocolo()) . '</a></td>' . "\n";
      }else{
        $strResultadoAssunto .= '<td align="center"><a target="_blank" title="'.PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrTipoProtocolo()).'" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_documento=' . $arrObjRelProtocoloAssuntoDTO[$i]->getDblIdProtocolo()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" class="protocoloNormal" style="font-size:1em !important;">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrProtocoloFormatadoProtocolo()) . '</a></td>' . "\n";
      }
      $strResultadoAssunto .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrCodigoEstruturadoAssunto()) . '</td>' . "\n";
      $strResultadoAssunto .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrDescricaoAssunto()) . '</td>' . "\n";
      $strResultadoAssunto .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getNumPrazoCorrenteAssunto()) . '</td>' . "\n";
      $strResultadoAssunto .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getNumPrazoIntermediarioAssunto()) . '</td>' . "\n";
      $strResultadoAssunto .= '<td align="center">' . PaginaSEI::tratarHTML($strDestinacao) . '</td>' . "\n";
      $strResultadoAssunto .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjRelProtocoloAssuntoDTO[$i]->getStrObservacoesAssunto()) . '</td>' . "\n";
      $strResultadoAssunto .= '</tr>' . "\n";
    }
    $strResultadoAssunto .= '</table>' . "\n";
  }

  //se for a alteracao de uma avaliacao documental, lista as avaliacoes cpad negadas,  justificadas ou nao, e as concordancias ativas
  if($_GET['acao'] == 'avaliacao_documental_alterar' || $_GET['acao'] == 'avaliacao_documental_consultar') {

    $numRegistros = InfraArray::contar($arrObjCpadAvaliacaoDTO);
    if ($numRegistros) {

      $strResultado = '';

      $strSumarioTabela = 'Lista de Discordâncias CPAD.';
      $strCaptionTabela = 'Discordâncias';

      $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh" style="display: none;">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Data</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Usuário</th>' . "\n";
      $strResultado .= '<th class="infraTh" >Motivo</th>' . "\n";
      $strResultado .= '<th class="infraTh" >Justificativa</th>' . "\n";
      $strResultado .= '</tr>' . "\n";

      $strArrJustificativas = "";
      $strCssTr = "";
      $virgula = "";
      for ($i = 0; $i < $numRegistros; $i++) {

        if ($i % 2 == 0) {
          $strCssTr = '<tr class="infraTrEscura">';
        } else {
          $strCssTr = '<tr class="infraTrClara">';
        }
        $strResultado .= $strCssTr;

        $strId = $arrObjCpadAvaliacaoDTO[$i]->getNumIdCpadAvaliacao();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjCpadAvaliacaoDTO[$i]->getStrMotivo());
        $strResultado .= '<td valign="top" style="display: none;">' . PaginaSEI::getInstance()->getTrCheck($i, $strId, $strDescricao) . '</td>';
        $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getDthAvaliacao()) . '</td>' . "\n";
        $strResultado .= '<td align="center">    <a alt="' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getStrNomeUsuario()) . '" title="' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getStrSiglaUsuario()) . '</a></td>';
        $strResultado .= '<td align="left">' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getStrMotivo()) . '</td>' . "\n";
        //se nao for ativo, já foi justificada, então exibe a justificativa
        if ($arrObjCpadAvaliacaoDTO[$i]->getStrSinAtivo() == 'N') {
          $strResultado .= '<td align="left">' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO[$i]->getStrJustificativa()) . '</td>' . "\n";
        //se for ativa, deve ser justificada
        } else {
          //string que contem o id do textarea, que concatena o id da avaliacao cpad
          //na submicao, será iterado pelo array de avaliacoes cpad negadas e será buscado o campo pelo mesmo id
          $strArrJustificativas .= $virgula."txaJustificativa" . $arrObjCpadAvaliacaoDTO[$i]->getNumIdCpadAvaliacao();
          $virgula = ',';
          $strResultado .= '<td align="center" style="padding: 1%;"><textarea id="txaJustificativa' . $arrObjCpadAvaliacaoDTO[$i]->getNumIdCpadAvaliacao() . '" name="txaJustificativa' . $arrObjCpadAvaliacaoDTO[$i]->getNumIdCpadAvaliacao() . '" rows="3" style="width:100%;" class="infraTextarea"  tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '">' . $arrObjCpadAvaliacaoDTO[$i]->getStrJustificativa() . '</textarea></td>';
        }
        $strResultado .= '</tr>' . "\n";
      }
      $strResultado .= '</table>' . "\n";
    }

    //tabela de avaliacoes concordancias
    $numRegistrosConcordancias = InfraArray::contar($arrObjCpadAvaliacaoDTO_Concordancias);
    if ($numRegistrosConcordancias) {
      $strResultadoConcordanciasConcordancias = '';

      $strSumarioTabela = 'Lista de Concordâncias CPAD.';
      $strCaptionTabela = 'Concordâncias';

      $strResultadoConcordancias .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
      $strResultadoConcordancias .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistrosConcordancias) . '</caption>';
      $strResultadoConcordancias .= '<tr>';
      $strResultadoConcordancias .= '<th class="infraTh" style="display: none;">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      $strResultadoConcordancias .= '<th class="infraTh" width="10%">Data</th>' . "\n";
      $strResultadoConcordancias .= '<th class="infraTh" width="10%">Usuário</th>' . "\n";
      $strResultadoConcordancias .= '<th class="infraTh" >Nome</th>' . "\n";
      $strResultadoConcordancias .= '</tr>' . "\n";

      $strArrJustificativas = "";
      $strCssTr = "";
      $virgula = "";
      for ($i = 0; $i < $numRegistrosConcordancias; $i++) {

        if ($i % 2 == 0) {
          $strCssTr = '<tr class="infraTrEscura">';
        } else {
          $strCssTr = '<tr class="infraTrClara">';
        }
        $strResultadoConcordancias .= $strCssTr;

        $strId = $arrObjCpadAvaliacaoDTO_Concordancias[$i]->getNumIdCpadAvaliacao();
        $strResultadoConcordancias .= '<td valign="top" style="display: none;">' . PaginaSEI::getInstance()->getTrCheck($i, $strId, $strDescricao) . '</td>';
        $strResultadoConcordancias .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO_Concordancias[$i]->getDthAvaliacao()) . '</td>' . "\n";
        $strResultadoConcordancias .= '<td align="center">    <a alt="' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO_Concordancias[$i]->getStrNomeUsuario()) . '" title="' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO_Concordancias[$i]->getStrNomeUsuario()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO_Concordancias[$i]->getStrSiglaUsuario()) . '</a></td>';
        $strResultadoConcordancias .= '<td align="left">'. PaginaSEI::tratarHTML($arrObjCpadAvaliacaoDTO_Concordancias[$i]->getStrNomeUsuario()) . '</td>';
        $strResultadoConcordancias .= '</tr>' . "\n";
      }
      $strResultadoConcordancias .= '</table>' . "\n";
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

  #lblData {position:absolute;left:0%;top:0%;width:30%;}
  #txtData {position:absolute;left:0%;top:9.5%;width:30%;}

  #lblAvaliador {position:absolute;left:32%;top:0%;width:50%;}
  #txtAvaliador {position:absolute;left:32%;top:9.5%;width:50%;}

  #lblProcesso {position:absolute;left:0%;top:25%;width:30%;}
  #ancProcesso {position:absolute;left:0%;top:34.5%;width:30%;font-size:.875rem;}

  #lblTipoProcedimento {position:absolute;left:32%;top:25%;width:50%;}
  #txtTipoProcedimento {position:absolute;left:32%;top:34.5%;width:50%;}

  #lblAssunto {position:absolute;left:0%;top:50%;width:82%;}
  #txtAssunto {position:absolute;left:0%;top:59.5%;width:82%;}
  #imgPesquisarAssuntos{position:absolute;left:83%;top:60%;<?=$strExibirLupa?>}

  #lblAssuntoOriginal {position:absolute;left:0%;top:75%;width:82%;}
  #txtAssuntoOriginal {position:absolute;left:0%;top:84.5%;width:82%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>


function inicializar(){
  //objeto de lupa para assuntos
  objLupaAssuntos = new infraLupaText('txtAssunto','hdnAssunto','<?=$strLinkAssuntosSelecao?>');
  //objeto de ajaxpara assuntos
  objAutoCompletarAssuntoRI1223 = new infraAjaxAutoCompletar('hdnAssunto','txtAssunto','<?=$strLinkAjaxAssuntoRI1223?>');
  objAutoCompletarAssuntoRI1223.limparCampo = true;
  objAutoCompletarAssuntoRI1223.prepararExecucao = function(){
    return 'palavras_pesquisa='+document.getElementById('txtAssunto').value;
  };

  <? if ($_GET['acao']=='avaliacao_documental_cadastrar'){ ?>
  document.getElementById('txtAssunto').focus();
  <? } ?>

  infraEfeitoTabelas();
}

function validarCadastro() {
  //um assunto deve ser marcado ou deve ser informado no campo de lupa
  //se for um assunto novo (informado na lupa) na RN ele será cadastrado na rel_protocolo_assunto
  if ($('#hdnAssunto').val() == null || $('#hdnAssunto').val() == "") {
    alert('Selecione um Assunto.');
    return false;
  }

  <?
  //se for alteracao, tem que verificar as justificativas
  if($_GET['acao'] == 'avaliacao_documental_alterar') {
  ?>
    arrJustificativas = $("#hdnStrArrJustificativas").val().split(",");
    for (i = 0; i < arrJustificativas.length; i++) {
      idTxaJustificativa = "#"+arrJustificativas[i];
      if($(idTxaJustificativa).val() == ""){
        alert("Todas as Justificativas devem ser preenchidas");
        return false;
      }
    }
  <?
  }
  ?>

  return true;
}

function OnSubmitForm() {
  return validarCadastro();
}

//quando clica em outro assunto no radio da tabela, atualiza o txt
function mudarAssunto(id, cod, desc){
  $("#txtAssunto").val(cod + ' - ' + desc);
  $("#hdnAssunto").val(id);
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmAvaliacaoDocumentalCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();

PaginaSEI::getInstance()->abrirAreaDados('20em');
?>

    <label id="lblData" for="txtData" accesskey="" class="infraLabelOpcional">Data:</label>
    <input type="text" id="txtData" name="txtData" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($objAvaliacaoDocumentalDTO->getDtaAvaliacao())?>"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblAvaliador" for="selAvaliador" accesskey="" class="infraLabelOpcional">Avaliador:</label>
    <input type="text" id="txtAvaliador" name="txtAvaliador" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($objAvaliacaoDocumentalDTO->getStrSiglaUsuario()." - ".$objAvaliacaoDocumentalDTO->getStrNomeUsuario())?>"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblProcesso" for="selProcesso" accesskey="" class="infraLabelOpcional">Processo:</label>
    <a id="ancProcesso" target="_blank" href="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&id_procedimento='.$idProcedimento).'" tabindex="'.PaginaSEI::getInstance()->getProxTabTabela()?>" ><?=PaginaSEI::tratarHTML($objPesquisaAvaliacaoDocumentalDTO->getStrProtocoloFormatado())?></a>

    <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelOpcional">Tipo do Processo:</label>
    <input type="text" id="txtTipoProcedimento" name="txtTipoProcedimento" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($objPesquisaAvaliacaoDocumentalDTO->getStrNomeTipoProcedimento())?>"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblAssunto" for="txtAssunto" accesskey="u" class="infraLabelOpcional">Classificação para Destinação Final (informe um assunto ou selecione na tabela abaixo):</label>
    <input type="text" id="txtAssunto" name="txtAssunto" class="infraText" value="<?=PaginaSEI::tratarHTML($descricaoAssunto)?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <input type="hidden" id="hdnAssunto" name="hdnAssunto" value="<?=$idAssunto?>" />
    <img id="imgPesquisarAssuntos" onclick="objLupaAssuntos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Pesquisa de Assuntos" title="Pesquisa de Assuntos" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <?
  //se for alteracao e o id assunto original for diferente do id assunto encontrado a partir do assunto proxy, quer dizer que a tabela de assuntos mudou e houve alteracao/migracao de assunto
  //assim é exibido o assunto original, para ter um historico
  if($_GET['acao'] != 'avaliacao_documental_cadastrar' && $objAvaliacaoDocumentalDTO->getNumIdAssunto() != $objAvaliacaoDocumentalDTO->getNumIdAssuntoOriginal()){?>
      <label id="lblAssuntoOriginal" for="txtAssuntoOriginal" accesskey="" class="infraLabelOpcional">Assunto Original da Avaliação:</label><br/>
      <input type="text" readonly id="txtAssuntoOriginal" name="txtAssuntoOriginal" readonly="readonly" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML(AssuntoINT::formatarCodigoDescricaoRI0568($objAvaliacaoDocumentalDTO->getStrCodigoEstruturadoAssuntoOriginal(),$objAvaliacaoDocumentalDTO->getStrDescricaoAssuntoOriginal()))?>"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
  <?}?>

  <input type="hidden" name="hdnStrArrJustificativas" id="hdnStrArrJustificativas" value="<?=$strArrJustificativas?>">
<?
PaginaSEI::getInstance()->fecharAreaDados();
PaginaSEI::getInstance()->montarAreaTabela($strResultadoAssunto,$numRegistrosAssunto);
?>
<br/>
<?
PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros);
?>
<br/>
<?
PaginaSEI::getInstance()->montarAreaTabela($strResultadoConcordancias,$numRegistrosConcordancias);
?>
  <input type="hidden" id="id_procedimento" name="id_procedimento" value="<?=$idProcedimento?>" />
  <?
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
