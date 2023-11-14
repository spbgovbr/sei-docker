<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 06/11/2018 - criado por cjy
 *
 * Versão do Gerador de Código: 1.42.0
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////
  ///a tela apresenta os campos da cpad: orgao, sigla e descricao.
  /// o orgao nao pode ser alterado em uma cpad ja cadastrada
  ///
  ///  abaixo podem ser inseridos usuarios para fazerem parte da comissao.
  /// devem ser informado o usuario e o cargo
  /// o cargo nao é buscado da relacionada ao usuario
  /// ao inserir, é validado se o usuario ja existe na lista
  /// podem haver dois usuarios com o mesmo cargo
  /// um dos usuarios deve ser o presidente
  ///
  ///  nao pode ser cadastrada/alterada uma cpad sem composicao
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selOrgao',  'hdnComposicao','hdnNumIdUsuarioPresidente'));

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_cpad'));

  $arrComandos = array();
  $strCampoOrgaoReadonly = "";

  $objCpadRN = new CpadRN();
  $objCpadDTO = new CpadDTO();

  //alterar e consultar buscam dados da cpad
  if($_GET['acao'] == 'cpad_alterar' || $_GET['acao'] == 'cpad_consultar'){
    $objCpadDTO->setNumIdCpad($_GET['id_cpad']);
    $objCpadDTO->retTodos();
    // o consultar retorna, alem dos dados da cpad, a ultima versao e seus componentes
    $objCpadDTO = $objCpadRN->consultar($objCpadDTO);
    if ($objCpadDTO==null){
      throw new InfraException("Registro não encontrado.");
    }

    //seta na tabela da tela
    $arrComposicao = array();

    //retorna composicao da ultima versao
    $arrObjCpadComposicaoDTO = $objCpadDTO->getObjCpadVersaoAtual()->getArrObjCpadComposicao();
    foreach ($arrObjCpadComposicaoDTO as $objCpadComposicaoDTO) {
      //se for o presidente, seta a variavel que será utilizada no hidden
      if ($objCpadComposicaoDTO->getStrSinPresidente() == "S") {
        $hdnNumIdUsuarioPresidente = $objCpadComposicaoDTO->getNumIdUsuario();
      }
      //array da composicao que sera usado na tabela
      $arrComposicao[] = array(
        $objCpadComposicaoDTO->getNumIdCpadComposicao(),
        $objCpadComposicaoDTO->getNumIdUsuario(),
        $objCpadComposicaoDTO->getNumIdCargo(),
        '',
        $objCpadComposicaoDTO->getStrNomeUsuario(),
        $objCpadComposicaoDTO->getStrExpressaoCargo(),

      );
    }
    //gera a tabela
    $strComposicao = PaginaSEI::getInstance()->gerarItensTabelaDinamica($arrComposicao);
    //orgao nao pode ser alterado em uma cpad
    $strCampoOrgaoReadonly = "disabled='disabled'";
  //se for cadastro
  }else if($_GET['acao'] == 'cpad_cadastrar'){
    //inicializa atributos no cadastro
    $objCpadDTO->setNumIdCpad(null);
    $objCpadDTO->setStrSigla(null);
    $objCpadDTO->setNumIdOrgao(null);
    $objCpadDTO->setStrDescricao(null);
  }

  //se for submetido o cadastro ou alteracao
  if(isset($_POST['sbmCadastrarCpad']) || isset($_POST['sbmAlterarCpad'])) {

    //retorna usuarios da composicao, pelo campo hidden, na variavel que é usada no campo
    $strComposicao = $_POST['hdnComposicao'];
    //retorna composicao para array
    $arr = PaginaSEI::getInstance()->getArrItensTabelaDinamica($_POST['hdnComposicao']);
    //retorna usuario presidente
    $hdnNumIdUsuarioPresidente = $_POST['hdnNumIdUsuarioPresidente'];

    /// orgao interessa apenas no cadastro, pois nao pode ser alterado na alteracao
    if(isset($_POST['sbmCadastrarCpad'])) {
      $numIdOrgao = PaginaSEI::getInstance()->recuperarCampo('selOrgao');
      if ($numIdOrgao !== '') {
        $objCpadDTO->setNumIdOrgao($numIdOrgao);
      } else {
        $objCpadDTO->setNumIdOrgao(null);
      }
    }
    //retorna outros campos
    $objCpadDTO->setStrSigla($_POST['txtSigla']);
    $objCpadDTO->setStrDescricao($_POST['txtDescricao']);

    //seta composicao a partir do array definido a partir da tela
    $arrComposicao = array();
    foreach($arr as $ordem => $linha){
      //inicializa CpadComposicaoDTO
      $objCpadComposicaoDTO = new CpadComposicaoDTO();
      $objCpadComposicaoDTO->setNumIdUsuario($linha[1]);
      $objCpadComposicaoDTO->setNumIdCargo($linha[2]);
      if($hdnNumIdUsuarioPresidente == $linha[1]){
        $objCpadComposicaoDTO->setStrSinPresidente("S");
      }else{
        $objCpadComposicaoDTO->setStrSinPresidente("N");
      }
      $objCpadComposicaoDTO->setNumOrdem($ordem+1);
      $arrObjComposicao[] = $objCpadComposicaoDTO;
    }
    //inicializa CpadVersaoDTO, que conterá a composicao da tela e será usado na RN
    $objCpadVersaoDTO = new CpadVersaoDTO();
    $objCpadVersaoDTO->setArrObjCpadComposicao($arrObjComposicao);
    $objCpadDTO->setObjCpadVersaoAtual($objCpadVersaoDTO);
  }

  switch($_GET['acao']){
    case 'cpad_cadastrar':
      $strTitulo = 'Nova CPAD';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarCpad" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmCadastrarCpad'])) {
        try{

          $objCpadDTO->setStrSinAtivo("S");
          $objCpadDTO = $objCpadRN->cadastrar($objCpadDTO);

          PaginaSEI::getInstance()->adicionarMensagem('CPAD "'.$objCpadDTO->getStrSigla().'" cadastrada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_cpad='.$objCpadDTO->getNumIdCpad().PaginaSEI::getInstance()->montarAncora($objCpadDTO->getNumIdCpad())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'cpad_alterar':

      $strTitulo = 'Alterar CPAD';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarCpad" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objCpadDTO->getNumIdCpad())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarCpad'])) {
        try{

          $objCpadRN->alterar($objCpadDTO);

          PaginaSEI::getInstance()->adicionarMensagem('CPAD "'.$objCpadDTO->getStrSigla().'" alterada com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($objCpadDTO->getNumIdCpad())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'cpad_consultar':
      $strTitulo = 'Consultar CPAD';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_cpad'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  //select de orgao
  $strItensSelOrgao = OrgaoINT::montarSelectSiglaRI1358('null','&nbsp;',$objCpadDTO->getNumIdOrgao());
  //ajax de usuario
  $strLinkAjaxUsuario = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=usuario_auto_completar');
  //ajax de cargo
  $strLinkAjaxCargo = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=cargo_auto_completar');

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
  #lblOrgao {position:absolute;left:0%;top:0%;width:35%;}
  #selOrgao {position:absolute;left:0%;top:14%;width:35%;}

  #lblSigla {position:absolute;left:36%;top:0%;width:35%;}
  #txtSigla {position:absolute;left:36%;top:14.5%;width:35%;}

  #lblDescricao {position:absolute;left:0%;top:35%;width:71%;}
  #txtDescricao {position:absolute;left:0%;top:49%;width:71%;}

  #lblComposicao {position:absolute;left:0%;top:76%;width:95%;}

  #lblUsuario {position:absolute;left:0%;top:0%;width:35%;}
  #txtUsuario {position:absolute;left:0%;top:35%;width:35%;}

  #lblCargo {position:absolute;left:36%;top:0%;width:35%;}
  #txtCargo {position:absolute;left:36%;top:35%;width:35%;}


  #btnTransportar {position:absolute;left:72%;top:35%;}

  <?if(0){?></style><?}?>
<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
<?if(0){?><script type="text/javascript"><?}?>

  //objeto do ajax de usuario
  var objAutoCompletarUsuario;
  //numero da linha que é a inserida quando clica-se para adicionar um usuario na composicao
  var linhaInserida = 1;
  //numero da coluna que contém o radio do presidente
  var colunaPresidente = 3;
  //numero da coluna escondida que contém o id do usuario
  var colunaUsuario = 1;

  function inicializar(){
    //flag para indicar se mostra as setas para mudar a ordem dos registros
    //apenas se for consulta ela vai para false
    var bolOrdenar = true;

    if ('<?=$_GET['acao']?>'=='cpad_cadastrar'){
      document.getElementById('selOrgao').focus();
    } else if ('<?=$_GET['acao']?>'=='cpad_consultar'){
      //nao permite mudar ordem
      bolOrdenar = false;
      infraDesabilitarCamposAreaDados();
    }else{
      document.getElementById('btnCancelar').focus();
    }

    //campo de ajax de usuario
    objAutoCompletarUsuario = new infraAjaxAutoCompletar('hdnIdUsuario','txtUsuario','<?= $strLinkAjaxUsuario ?>');
    objAutoCompletarUsuario.limparCampo = true;
    objAutoCompletarUsuario.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtUsuario').value;
    };

    //campo de ajax de cargo
    objAutoCompletarCargo = new infraAjaxAutoCompletar('hdnIdCargo','txtCargo','<?= $strLinkAjaxCargo ?>');
    objAutoCompletarCargo.limparCampo = true;
    objAutoCompletarCargo.prepararExecucao = function(){
      return 'palavras_pesquisa='+document.getElementById('txtCargo').value;
    };

    //tabela composicao
    objTabelaComposicao = new infraTabelaDinamica('tblComposicao','hdnComposicao', false, true, bolOrdenar);
    //'guarda' a funcao original de adicionar, que será chamada antes da criada espeficiamente para a tela
    fncAdicionar = objTabelaComposicao.adicionar;
    objTabelaComposicao.adicionar = function(arrColunas,bolMarcarLinha){
      fncAdicionar(arrColunas,bolMarcarLinha);
      //assim pode ser chamada uma funcao criada espeficiamente para a tela, para tratamento de presidente, que coloca na a primeira coluna um radio
      substituirColunasPresidente(linhaInserida);
    }

    objTabelaComposicao.subirLinha =function(objSeta){
      alterarLinha(objSeta,-1);
    }
    objTabelaComposicao.descerLinha =function(objSeta){
      alterarLinha(objSeta,+1);
    }

    function alterarLinha(objSeta, numDeslocamento){
      var tabela = objSeta.parentNode.parentNode.parentNode
      var trLinhaAtual=objSeta.parentNode.parentNode;

      var numLinhaAtual = trLinhaAtual.rowIndex;
      var numLinhaDeslocada = trLinhaAtual.rowIndex+numDeslocamento;

      var trLinhaDeslocada=tabela.rows[numLinhaDeslocada];
      if(numLinhaAtual > numLinhaDeslocada) {
        tabela.insertBefore(trLinhaAtual, trLinhaDeslocada);
      }else{
        tabela.insertBefore(trLinhaDeslocada, trLinhaAtual);
      }
      objTabelaComposicao.atualizarSetas();
      objTabelaComposicao.atualizaHdn();
    }

    //redefine como vazia, pois será tudo realizado na removerLinha criada espeficiamente para a tela
    this.removerLinhaHidden = function(linha) {
    };

    //'guarda' a funcao original de remover, que será chamada junto de outras criadas espeficiamente para a tela
    fncRemover = objTabelaComposicao.removerLinha;
    objTabelaComposicao.removerLinha = function(numLinha){
      //retorna o id do usuario removido, pela linha excluid na tabela e pela coluna escondida que contem o id do usuario
      //a coluna escondida que contem o id do usuario tem um div, e o id fica dentro dele
      idUsuario = $("#tblComposicao tr:eq("+numLinha+") td:eq("+(colunaUsuario)+") div").html();
      //testa se o usuário excluido foi o presidente, limpando o hidden
      if(idUsuario == $("#hdnNumIdUsuarioPresidente").val()){
        $("#hdnNumIdUsuarioPresidente").val("");
      }
      //chama funcao original
      fncRemover(numLinha);
      //atualiza o hidden que contem todos os dados da tabela
      var arrDados = this.hdn.value.split('¥');
      arrDados.splice(arrDados.length-numLinha,1);
      var tmp = '';
      for ( var i = 0; i < arrDados.length; i++) {
        if (tmp != '') {
          tmp += '¥';
        }
        tmp += arrDados[i];
      }
      this.hdn.value = tmp;


    }

    objTabelaComposicao.gerarEfeitoTabela=true;
    infraEfeitoTabelas(true);

    //trata a tecla enter quando digitada nos campos de ajax de usuario ou cargo
    infraAdicionarEvento(document.getElementById('txtUsuario'),'keyup',tratarEnterComposicao);
    infraAdicionarEvento(document.getElementById('txtCargo'),'keyup',tratarEnterComposicao);

    //ao carregar a tela, substitui a primeira coluna visivel das linhas da tabela por um radio referente ao presidente
    // serve para o caso de consulta ou alteracao
    substituirColunasPresidente();
  }

  //funcao que substitui a primeira coluna visivel da tabela por um radio referente ao presidente
  //pode ser passado qual linha será substituida (no caso da insercao de um usuario na composicao, ou substitui em todas as linhas quando carrega a tela de consulta ou alteracao
  function substituirColunasPresidente(numLinha){
    var trLinha = "";
    //se for numerico, substituira na linha inserida
    if($.isNumeric(numLinha)){
      trLinha = ":eq("+numLinha+") ";
    }
    //percorre todas as linhas, ou apenas a linha inserida no caso de ser passado como parametro e definido acima o numero da linha
    $("#tblComposicao tr"+trLinha+" ").each(function(){
      //objeto da linha
      trTabela = this;
      //retorna o id do usuario da linha, a partir da coluna oculta dessa linha
      //o valor fica dentro de um div
      idUsuario = $(trTabela).find(" td:eq("+colunaUsuario+") div").html();
      //retorna o div que terá o conteudo substituido por um radio
      divPresidente = $(trTabela).find(" td:eq("+colunaPresidente+") div");
      //variavel para deixar checked o radio, caso esse usuario seja o presidente
      var strChecked = "";
      //testa se esse usuario é o presidente
      if(idUsuario == $("#hdnNumIdUsuarioPresidente").val()){
        strChecked = "checked='checked'";
      }
      //variavel que contem o radio do presidente
      //seu valor é o id do usuario da linha
      //o onchange chama uma funcao para alterar o valor do hidden que contem o presidente
      //se for a tela de consultar, deixa disabled
      var inputRadioPresidente = "<input type='radio' name='rdoPresidente' value='"+idUsuario+"'  "+strChecked+" onchange='mudarPresidente("+idUsuario+")' <?=($_GET['acao'] == 'cpad_consultar' ? ' disabled=\'disabled\' ' : '')?> />"
      //substitui conteudo do div
      $(divPresidente).html(inputRadioPresidente);

    });
  }

  //funcao chamada para mudar o presidente, alterando o hidden
  function mudarPresidente(idUsuario){
    $("#hdnNumIdUsuarioPresidente").val(idUsuario);
  }

  //nao realiza nenhuma funcao quando digita enter, mas poderia fazer incluir na composicao
  function tratarEnterComposicao(ev){

  }

  //validacoes para adicionar um usuario na composicao
  function transportarUsuario(){
    //testa se foi escolhido um usuario
    if (infraTrim(document.getElementById('txtUsuario').value)=='') {
      alert('Usuário não informado.');
      document.getElementById('txtUsuario').focus();
      return false;
    }
    //testa se foi escolhido um cargo
    if (infraTrim(document.getElementById('txtCargo').value)=='') {
      alert('Cargo não informado.');
      document.getElementById('txtCargo').focus();
      return false;
    }

    //hidden do ajax de usuario
    var idUsuario = document.getElementById('hdnIdUsuario').value;
    //hidden do ajax de cargo
    var idCargo = document.getElementById('hdnIdCargo').value;
    //text do ajax de usuario
    var usuario = document.getElementById('txtUsuario').value;
    //text do ajax de cargo
    var cargo = document.getElementById('txtCargo').value;

    //bool para controle se pode adicionar o usuario (senao, ele ja existe)
    var bolAdicionar = true;
    //percorre todas as colunas que tem id de usuarios da tabela da composicao
    $("#tblComposicao td:eq("+(colunaUsuario)+") div").each(function(){
      if($(this).html() == idUsuario){
        //usuario ja presente na composicao
        bolAdicionar = false;
      }
    });

    if(!bolAdicionar){
      alert('Usuário já foi adicionado.');
    }else{
      //adiciona na tabela
      objTabelaComposicao.adicionar([null, idUsuario, idCargo, "", usuario, cargo]);

      //depois de incluir limpa os campos e hidden
      document.getElementById('hdnIdUsuario').value = '';
      document.getElementById('hdnIdCargo').value = '';
      objAutoCompletarUsuario.limpar();
      objAutoCompletarCargo.limpar();
      //seta o foco
      document.getElementById('txtUsuario').focus();
    }

  }


  function validarCadastro() {
    if (!infraSelectSelecionado('selOrgao')) {
      alert('Selecione um Órgão.');
      document.getElementById('selOrgao').focus();
      return false;
    }

    if (infraTrim(document.getElementById('txtSigla').value)=='') {
      alert('Informe a Sigla.');
      document.getElementById('txtSigla').focus();
      return false;
    }

    if (infraTrim(document.getElementById('txtDescricao').value)=='') {
      alert('Informe a Descrição.');
      document.getElementById('txtDescricao').focus();
      return false;
    }

    if (infraTrim(document.getElementById('hdnComposicao').value)=='') {
      alert('Composição da comissão não definida.');
      return false;
    }

    if (infraTrim(document.getElementById('hdnNumIdUsuarioPresidente').value)=='') {
      alert('Presidente não informado.');
      return false;
    }

    return true;
  }

  function OnSubmitForm() {
    return validarCadastro();
  }

  <?if(0){?></script><?}?>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmCpadCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    PaginaSEI::getInstance()->abrirAreaDados('14em');
    ?>
    <label id="lblOrgao" for="selOrgao"  accesskey="" class="infraLabelObrigatorio">Órgão:</label>
    <select id="selOrgao" name="selOrgao" <?=$strCampoOrgaoReadonly ?> class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
      <?=$strItensSelOrgao?>
    </select>

    <label id="lblSigla" for="txtSigla" accesskey="" class="infraLabelObrigatorio">Sigla:</label>
    <input type="text" id="txtSigla" name="txtSigla" class="infraText" value="<?=PaginaSEI::tratarHTML($objCpadDTO->getStrSigla());?>" onkeypress="return infraMascaraTexto(this,event,30);" maxlength="30" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblDescricao" for="txtDescricao" accesskey="" class="infraLabelObrigatorio">Descrição:</label>
    <input type="text" id="txtDescricao" name="txtDescricao" class="infraText" value="<?=PaginaSEI::tratarHTML($objCpadDTO->getStrDescricao());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

    <label id="lblComposicao" accesskey="" class="infraLabelTitulo">&nbsp;&nbsp;Composição</label>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    PaginaSEI::getInstance()->abrirAreaDados('6em',($_GET['acao'] == 'cpad_consultar' ? 'style=\'display:none\'' : '') );
    ?>

      <label id="lblUsuario" for="txtUsuario" accesskey="" class="infraLabelOpcional">Usuário:</label>
      <input type="text" id="txtUsuario" name="txtUsuario" class="infraText" value="" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <label id="lblCargo" for="txtCargo" accesskey="" class="infraLabelOpcional">Cargo:</label>
      <input type="text" id="txtCargo" name="txtCargo" class="infraText" value="" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <input type="button" name="btnTransportar" id="btnTransportar" onclick="transportarUsuario()" value="Adicionar" class="infraButton"/>
    <?
    PaginaSEI::getInstance()->fecharAreaDados();
    ?>


    <div id="divTabelaComposicao" class="infraAreaTabela">
      <table id="tblComposicao" class="infraTable" width="95%">
        <caption class="infraCaption"><?= PaginaSEI::getInstance()->gerarCaptionTabela("Composição da Comissão", 0) ?></caption>
        <tr>
          <th style="display:none;">ID CPAD COMPOSICAO</th>
          <th style="display:none;">ID USUARIO</th>
          <th style="display:none;">ID GRUPO</th>
          <th class="infraTh" style="width:10%;" align="center">Presidente</th>
          <th class="infraTh" style="width:40%;">Usuário</th>
          <th class="infraTh" style="width:40%;" align="center">Cargo</th>
          <? if ($_GET['acao'] != 'cpad_consultar') { ?>
            <th class="infraTh" style="width:10%;">Ações</th>
          <? } ?>
        </tr>
      </table>
    </div>

    <input type="hidden" id="hdnIdCpad" name="hdnIdCpad" value="<?=$objCpadDTO->getNumIdCpad();?>" />
    <input type="hidden" id="hdnIdUsuario" name="hdnIdUsuario" value="" />
    <input type="hidden" id="hdnIdCargo" name="hdnIdCargo" value="" />
    <input type="hidden" id="hdnComposicao" name="hdnComposicao" value="<?= $strComposicao ?>"/>
    <input type="hidden" id="hdnNumIdUsuarioPresidente" name="hdnNumIdUsuarioPresidente" value="<?= $hdnNumIdUsuarioPresidente ?>"/>

    <?
    //PaginaSEI::getInstance()->montarAreaDebug();
    //  PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
