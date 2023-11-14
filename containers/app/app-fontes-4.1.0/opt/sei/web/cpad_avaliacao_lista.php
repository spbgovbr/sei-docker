<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/11/2018 - criado por cjy
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

  PaginaSEI::getInstance()->prepararSelecao('cpad_avaliacao_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selTipoProcedimento','selAssuntos', 'hdnAssuntos'));

  //a tela só tem listagem
  switch($_GET['acao']){

    case 'cpad_avaliacao_listar':
      $strTitulo = 'Avaliações CPAD';
      //se clicou no botao de concordar
      if($_GET['concordar'] == 1){
        try{
          //lista ids de avaliacao documental selecionados
          $arrStrIds = PaginaSEI::getInstance()->getArrStrItensSelecionados();
          $objCpadAvaliacaoRN = new CpadAvaliacaoRN();
          for ($i=0;$i<count($arrStrIds);$i++){
            //inicializa dto de nova avaliacao cpad
            $objCpadAvaliacaoDTO = new CpadAvaliacaoDTO();
            $objCpadAvaliacaoDTO->setNumIdCpadAvaliacao(null);
            //id da avaliacao documental selecionada
            $objCpadAvaliacaoDTO->setNumIdAvaliacaoDocumental($arrStrIds[$i]);
            //concorda
            $objCpadAvaliacaoDTO->setStrStaCpadAvaliacao(CpadAvaliacaoRN::$TA_CPAD_AVALIADO);
            //data atual
            $objCpadAvaliacaoDTO->setDthAvaliacao(InfraData::getStrDataHoraAtual());
            //cadastrada como ativa
            $objCpadAvaliacaoDTO->setStrSinAtivo('S');
            //a justificativa é preenchida na avaliacao documental, quando a avaliacao cpad for negada
            $objCpadAvaliacaoDTO->setStrJustificativa(null);
            //cdastra
            $objCpadAvaliacaoRN->cadastrar($objCpadAvaliacaoDTO);
          }
          PaginaSEI::getInstance()->adicionarMensagem('Operação realizada com sucesso.');

          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_avaliacao_listar&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($arrStrIds)));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();
  $arrComandos[] = '<button type="submit" accesskey="S" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  //é possivel concordar em bloco com avaliacoes documentais
  $strLinkBotaoConcordar= SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_avaliacao_listar&concordar=1&acao_origem='.$_GET['acao']);

  //filtro tipo de processo
  $numIdTipoProcedimento = PaginaSEI::getInstance()->recuperarCampo('selTipoProcedimento');
  //filtro assuntos
  $arrAssuntos = PaginaSEI::getInstance()->getArrValuesSelect(PaginaSEI::getInstance()->recuperarCampo('hdnAssuntos'));
  $hdnAssuntos = PaginaSEI::getInstance()->recuperarCampo('hdnAssuntos');

  //verifica se o usuario está na composicao da ultima versao
  $objCpadComposicaoRN = new CpadComposicaoRN();
  $objCpadComposicaoDTO = new CpadComposicaoDTO();
  $objCpadComposicaoDTO->retNumIdCpadComposicao();
  //id da ultima versao (ativa)
  $objCpadComposicaoDTO->retNumIdCpadVersao();
  //filtra pela cpad do orgao, pois o usuario pode estar em cpad de outro orgao
  $objCpadComposicaoDTO->setNumIdOrgaoCpad(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
  //filtra pela ultima versao, que é a ativa
  $objCpadComposicaoDTO->setStrSinAtivoCpadVersao("S");
  //filtra pelo id do usuario
  $objCpadComposicaoDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());
  //consulta
  $objCpadComposicaoDTO = $objCpadComposicaoRN->consultar($objCpadComposicaoDTO);

  //se está na composicao
  if($objCpadComposicaoDTO != null) {
    //dto para pesquisa de avaliacao documental
    //há comentarios no dto, principalmente a respeito de como são feitos os relacionamentos com outras tabelas
    $objPesquisaAvaliacaoDocumentalDTO = new PesquisaAvaliacaoDocumentalDTO();
    //distinct, pois pode haver mais de uma avaliacao cpad, se houve discordancias e 'bate-volta' da avaliacao documental e avaliacao cpad, entao o processo pode vir repetido
    $objPesquisaAvaliacaoDocumentalDTO->setDistinct(true);
    //campos retornados
    $objPesquisaAvaliacaoDocumentalDTO->retDblIdProtocolo();
    $objPesquisaAvaliacaoDocumentalDTO->retNumIdAvaliacaoDocumental();
    $objPesquisaAvaliacaoDocumentalDTO->retDtaGeracao();
    $objPesquisaAvaliacaoDocumentalDTO->retStrProtocoloFormatado();
    $objPesquisaAvaliacaoDocumentalDTO->retStrNomeTipoProcedimento();
    $objPesquisaAvaliacaoDocumentalDTO->retStrNomeUsuarioAvaliacaoDocumental();
    $objPesquisaAvaliacaoDocumentalDTO->retStrSiglaUsuarioAvaliacaoDocumental();
    $objPesquisaAvaliacaoDocumentalDTO->retDtaConclusaoProcedimento();
    $objPesquisaAvaliacaoDocumentalDTO->retStrStaAvaliacaoDocumental();
    $objPesquisaAvaliacaoDocumentalDTO->retDtaAvaliacaoDocumental();
    //filtra apenas pelos processos do orgao atual
    $objPesquisaAvaliacaoDocumentalDTO->setNumIdOrgaoUnidadeGeradoraProtocolo(SessaoSEI::getInstance()->getNumIdOrgaoUnidadeAtual());
    //apenas processos com status de avaliado (quando foi realizada uma avaliacao documental
    $objPesquisaAvaliacaoDocumentalDTO->setStrStaAvaliacaoDocumental(AvaliacaoDocumentalRN::$TA_AVALIADO);
    //filtro de tipo de procedimento
    if ($numIdTipoProcedimento != '' && $numIdTipoProcedimento != "null") {
      $objPesquisaAvaliacaoDocumentalDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
    }
    //filtro de assuntos
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
    //sempre que é feita uma avaliacao cpad, essa nova fica com ativo igual a 'S'.
    //como é um left join (parametro 'opciional') com a avaliacao cpad, sempre retornará todos os processos que tem avaliacao documental
    //contudo o join em seguida é com a composicao cpad e versao cpad, entao só retornar essas colunas se houver avaliacao cpad nesse processo para esse componente/usuario e para essa versao, respectivamente
    //importam apenas os processos que nao tem avaliacao nesses filtros, portanto por ultimo é filtrado (no where) pelo id da cpad da tabela cpad como igual a nulo (poderia ser outro campo/tabela)
    //foi feito assim para simular um 'not in'
    //obs.: caso uma avaliacao cpad seja 'negado', após ser realizada a justificativa na avaliacao documental, essa avaliacao cpad fica com ativo igual a 'N', assim o processo novamente é retornado na listagem de avaliacoes cpad a serem realizadas pelo usuario
    $objPesquisaAvaliacaoDocumentalDTO->setStrSinAtivoCpadAvaliacao("S");
    //se está em uma composicao
    $objPesquisaAvaliacaoDocumentalDTO->setNumIdUsuarioCpadComposicao(SessaoSEI::getInstance()->getNumIdUsuario());
    //(composicao da) ultima versao (ativa)
    $objPesquisaAvaliacaoDocumentalDTO->setNumIdCpadVersao($objCpadComposicaoDTO->getNumIdCpadVersao());
    //aqui foi usado esse campo/tabela, mas poderia ser outro
    //fitlra apenas pelos registros nulos, pois quer retornar apenas os que nao tiveram avaliacao cpad com os filtros anteriores
    $objPesquisaAvaliacaoDocumentalDTO->setNumIdCpad(null, InfraDTO::$OPER_IGUAL);

    //ordenamento e paginacao
    PaginaSEI::getInstance()->prepararOrdenacao($objPesquisaAvaliacaoDocumentalDTO, 'ConclusaoProcedimento', InfraDTO::$TIPO_ORDENACAO_DESC);
    PaginaSEI::getInstance()->prepararPaginacao($objPesquisaAvaliacaoDocumentalDTO);

    //pesquisa
    $objProtocoloRN = new ProtocoloRN();
    $arrObjProtocoloDTO = array();
    try {
      $arrObjProtocoloDTO = $objProtocoloRN->pesquisarProtocolosCpadAvaliacao($objPesquisaAvaliacaoDocumentalDTO);
    } catch (Exception $e) {
      PaginaSEI::getInstance()->processarExcecao($e);
    }

    PaginaSEI::getInstance()->processarPaginacao($objPesquisaAvaliacaoDocumentalDTO);

    //a listagem na tela é padrao, a nao ser pelas acoes, que tem apenas o link para o cpad avaliacao cadastro, para realizar a avaliacao cpad
    $numRegistros = InfraArray::contar($arrObjProtocoloDTO);
    if ($numRegistros) {

      $arrComandos[] = '<button type="button" accesskey="" id="btnConcordar" value="Concordar" onclick="acaoConcordarMultipla();" class="infraButton">Concordar</button>';

      $strResultado = '';

      $strSumarioTabela = 'Tabela de Processos.';
      $strCaptionTabela = 'Processos';

      $strResultado .= '<table width="99%" class="infraTable" summary="' . $strSumarioTabela . '">' . "\n";
      $strResultado .= '<caption class="infraCaption">' . PaginaSEI::getInstance()->gerarCaptionTabela($strCaptionTabela, $numRegistros) . '</caption>';
      $strResultado .= '<tr>';
      $strResultado .= '<th class="infraTh"  width="1%">' . PaginaSEI::getInstance()->getThCheck() . '</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="20%">Processo</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO, 'Autuação', 'Geracao', $arrObjProtocoloDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO, 'Conclusão', 'ConclusaoProcedimento', $arrObjProtocoloDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh">' . PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO, 'Tipo', 'NomeTipoProcedimento', $arrObjProtocoloDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO, 'Avaliador', 'SiglaUsuarioAvaliacaoDocumental', $arrObjProtocoloDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">' . PaginaSEI::getInstance()->getThOrdenacao($objPesquisaAvaliacaoDocumentalDTO, 'Data', 'AvaliacaoDocumental', $arrObjProtocoloDTO) . '</th>' . "\n";
      $strResultado .= '<th class="infraTh" width="10%">Ações</th>' . "\n";
      $strResultado .= '</tr>' . "\n";
      $strCssTr = '';

      $bolAcaoCadastrar = SessaoSEI::getInstance()->verificarPermissao('cpad_avaliacao_cadastrar');

      $strCssTr = "";
      for ($i = 0; $i < $numRegistros; $i++) {

        $strCssTr = ($strCssTr == '<tr class="infraTrClara">') ? '<tr class="infraTrEscura">' : '<tr class="infraTrClara">';
        $strResultado .= $strCssTr;

        $strId = $arrObjProtocoloDTO[$i]->getNumIdAvaliacaoDocumental();
        $strIdProcedimento = $arrObjProtocoloDTO[$i]->getDblIdProtocolo();
        $strDescricao = PaginaSEI::getInstance()->formatarParametrosJavaScript($arrObjProtocoloDTO[$i]->getStrProtocoloFormatado());
        $strResultado .= '<td align="center">' . PaginaSEI::getInstance()->getTrCheck($i, $strId, $strDescricao) . '</td>';
        $strResultado .= '<td align="center"><a target="_blank" href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=procedimento_trabalhar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_procedimento=' . $arrObjProtocoloDTO[$i]->getDblIdProtocolo()) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '" title="'.PaginaSEI::tratarHTML($arrObjProtocoloDTO[$i]->getStrNomeTipoProcedimento()).'" class="protocoloNormal" style="font-size:1em !important;">' . PaginaSEI::tratarHTML($strDescricao) . '</a></td>' . "\n";
        $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjProtocoloDTO[$i]->getDtaGeracao()) . '</td>' . "\n";
        $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjProtocoloDTO[$i]->getDtaConclusaoProcedimento()) . '</td>' . "\n";
        $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjProtocoloDTO[$i]->getStrNomeTipoProcedimento()) . '</td>' . "\n";
        $strResultado .= '<td align="center">    <a alt="' . PaginaSEI::tratarHTML($arrObjProtocoloDTO[$i]->getStrNomeUsuarioAvaliacaoDocumental()) . '" title="' . PaginaSEI::tratarHTML($arrObjProtocoloDTO[$i]->getStrNomeUsuarioAvaliacaoDocumental()) . '" class="ancoraSigla">' . PaginaSEI::tratarHTML($arrObjProtocoloDTO[$i]->getStrSiglaUsuarioAvaliacaoDocumental()) . '</a></td>';
        $strResultado .= '<td align="center">' . PaginaSEI::tratarHTML($arrObjProtocoloDTO[$i]->getDtaAvaliacaoDocumental()) . '</td>' . "\n";
        $strResultado .= '<td align="center">';
        if ($bolAcaoCadastrar) {
          $strResultado .= '<a href="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=cpad_avaliacao_cadastrar&acao_origem=' . $_GET['acao'] . '&acao_retorno=' . $_GET['acao'] . '&id_avaliacao_documental=' . $strId . '&id_procedimento=' . $strIdProcedimento) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabTabela() . '"><img src="'.Icone::AVALIACAO_DOCUMENTAL.'" title="Avaliação CPAD" alt="Avaliação CPAD" class="infraImg" /></a>&nbsp;';
        }

        $strResultado .= '</td>' . "\n";
        $strResultado .= '</tr>' . "\n";
      }
      $strResultado .= '</table>' . "\n";
    }
  }


  $strSelTipoProcedimento = TipoProcedimentoINT::montarSelectNome('null', 'Todos', $numIdTipoProcedimento);
//links para selecao de assuntos
  $strLinkAssuntosSelecao = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_selecionar&tipo_selecao=2&id_object=objLupaAssuntos');
  $strLinkAjaxAssuntoRI1223 = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=assunto_auto_completar_RI1223');
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
  #lblTipoProcedimento {position:absolute;left:0%;top:0%;}
  #selTipoProcedimento {position:absolute;left:0%;top:40%;width:60%}

  #lblAssuntos{position:absolute;left:0%;top:2%;}
  #txtAssunto {position:absolute;left:0%;top:18%;width:90%;}
  #selAssuntos {position:absolute;left:0%;top:40%;width:90%;height:50%;}
  #divOpcoesAssuntos {position:absolute;left:91%;top:40%;}

<?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

function inicializar(){
  //inicializacao e configuracao do objeto para assuntos
  objAutoCompletarAssuntoRI1223 = new infraAjaxAutoCompletar('hdnIdAssunto','txtAssunto','<?= $strLinkAjaxAssuntoRI1223 ?>');
  objAutoCompletarAssuntoRI1223.limparCampo = true;
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
    seiConsultarAssunto(valor, 'selAssuntos','frmCpadAvaliacaoLista','<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=assunto_consultar&acao_origem='.$_GET['acao'])?>');
  }

  document.getElementById('selAssuntos').ondblclick = function(e){
    objLupaAssuntos.alterar();
  };

  infraEfeitoTabelas(true);
}

function acaoConcordarMultipla(){
  if (document.getElementById('hdnInfraItensSelecionados').value==''){
    alert('Nenhum processo selecionado.');
    return;
  }
  if (confirm("Concordar com as avaliações selecionadas?")) {
    document.getElementById('hdnInfraItemId').value = '';
    document.getElementById('frmCpadAvaliacaoLista').action = '<?=$strLinkBotaoConcordar?>';
    document.getElementById('frmCpadAvaliacaoLista').submit();
  }
}

<?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmCpadAvaliacaoLista" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  ?>
  <div id="divTipoProcedimento" class="infraAreaDados" style="height:5em;">
    <label id="lblTipoProcedimento" for="selTipoProcedimento" accesskey="" class="infraLabelOpcional">Tipo do Processo:</label><br/>
    <select id="selTipoProcedimento" name="selTipoProcedimento"  onchange="this.form.submit()" class="infraSelect combo" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
      <?=$strSelTipoProcedimento?>
    </select>
  </div>

  <div id="divAssuntos" class="infraAreaDados" style="height:12em;">
    <label id="lblAssuntos" for="txtAssunto" accesskey="" class="infraLabelOpcional">Assuntos:</label>
    <input type="text" id="txtAssunto" name="txtAssunto" class="infraText" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    <input type="hidden" id="hdnIdAssunto" name="hdnIdAssunto" class="infraText" value="" />
    <select id="selAssuntos" name="selAssuntos" class="infraSelect" multiple="multiple" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" >
    </select>
    <div id="divOpcoesAssuntos">
      <img id="imgPesquisarAssuntos"  onclick="objLupaAssuntos.selecionar(700,500);" src="<?=PaginaSEI::getInstance()->getIconePesquisar()?>" alt="Pesquisa de Assuntos" title="Pesquisa de Assuntos" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <img id="imgRemoverAssuntos" onclick="objLupaAssuntos.remover();" src="<?=PaginaSEI::getInstance()->getIconeRemover()?>" alt="Remover Assuntos Selecionados" title="Remover Assuntos Selecionados" class="infraImg" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
    </div>
    <input type="hidden" id="hdnAssuntos" name="hdnAssuntos" value="<?=$hdnAssuntos?>" />
  </div>

  <input type="hidden" id="hdnAssuntoIdentificador" name="hdnAssuntoIdentificador" value="" />

  <?
  PaginaSEI::getInstance()->montarAreaTabela($strResultado,$numRegistros,true);
  PaginaSEI::getInstance()->montarAreaDebug();
  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
