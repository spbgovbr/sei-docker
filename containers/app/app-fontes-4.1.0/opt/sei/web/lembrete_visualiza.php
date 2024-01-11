<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 26/08/2014 - criado por bcu
*
* Versão do Gerador de Código: 1.33.1
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->salvarCamposPost(array('selUsuario'));

  $jsonLembretes = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'lembrete_visualizar':
      $strTitulo = 'Meus Lembretes';
      //$arrComandos[] = '<button type="button" accesskey="S" id="sbmSalvar" name="sbmSalvar" value="Salvar" onclick="save();" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="N" name="sbmNovo" value="Novo" onclick="novo();" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo</button>';
      $arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      break;


    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $objLembreteDTO = new LembreteDTO();
  $objLembreteDTO->retNumIdLembrete();
  $objLembreteDTO->retStrConteudo();
  $objLembreteDTO->retNumPosicaoX();
  $objLembreteDTO->retNumPosicaoY();
  $objLembreteDTO->retNumLargura();
  $objLembreteDTO->retNumAltura();
  $objLembreteDTO->retStrCor();
  $objLembreteDTO->retStrCorTexto();
  $objLembreteDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());

  $objLembreteRN = new LembreteRN();
  $arrObjLembreteDTO=$objLembreteRN->listar($objLembreteDTO);

  $arrLembrete=array();
  foreach($arrObjLembreteDTO as $objLembreteDTO) {
    $arrLembrete[] = array(
        'idSei' => $objLembreteDTO->getNumIdLembrete(),
        'content' => utf8_encode($objLembreteDTO->getStrConteudo()),
        'posX' => intval($objLembreteDTO->getNumPosicaoX()),
        'posY' => intval($objLembreteDTO->getNumPosicaoY()),
        'height' => intval($objLembreteDTO->getNumAltura()),
        'width' => intval($objLembreteDTO->getNumLargura()),
        'features' =>  array('addArrow' => 'none'),
        'cssclases' => array('note' => 'seiNota'),
        'style' => array('backgroundcolor' => $objLembreteDTO->getStrCor(),
                         'textcolor' => $objLembreteDTO->getStrCorTexto()
        ),
    );
  }
  $jsonLembretes = json_encode($arrLembrete);

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}
  $linkAjaxLembrete=SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=lembrete_atualizar&id_usuario='.SessaoSEI::getInstance()->getNumIdUsuario());
  $linkAncora=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=lembrete_reativar&acao_origem=lembrete_visualizar&acao_retorno=lembrete_visualizar');

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->adicionarStyle('js/postitall/jquery.postitall.css');
PaginaSEI::getInstance()->adicionarStyle('js/postitall/jquery.minicolors.css'); //colorpicker
PaginaSEI::getInstance()->abrirStyle();
?>
.PIAconfigBox .minicolors {font-size:14px}
div.PIAconfigBox label.configOcultar {display:none;}
div.PIAconfigBox input.configOcultar {display:none;}
.PIAtitle {display:none;}
.PIAeditable p {margin:0px;color:inherit;}
.PIAeditable div {color:inherit;}
.PIAeditable {word-wrap: break-word;}
.modificada {border:1px dotted black !important;}

.seiNota {
  background-color: #FFFC7F;
  font-family : arial,verdana,helvetica,sans-serif;
  font-size : 14px;
  border:1px solid transparent;
  min-height: 100px;
  min-width: 75px;
  text-color: #000000;
  text-shadow: 0px 0px 0px transparent;
  -moz-text-shadow: 0px 0px 0px transparent;
}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->adicionarJavaScript('js/postitall/jquery.ui.touch-punch.min.js');
PaginaSEI::getInstance()->adicionarJavaScript('js/postitall/jquery.postitall.js');
PaginaSEI::getInstance()->adicionarJavaScript('js/postitall/jquery.minicolors.min.js');
//PaginaSEI::getInstance()->abrirJavaScript();
?>
<script>

var saveTimer;
var bolSalvando = false;
var lembretes=<?=$jsonLembretes;?>;
var idLembrete = 0;


var iR=infraResize;
var infraResize=function(){
  var aDD=$('#divInfraAreaDadosDinamica');
  var aT=$('#divInfraAreaTela');
  aDD.css('height',100);
  iR();
  aDD.css('height',parseInt(aT.css('height'))-60);
};


function inicializar(){

  $.PostItAll.changeConfig('global', {
    randomColor : false,
    minimized : false,
    expand : false,
    showInfo : false,
    pasteHtml : false,
    htmlEditor : false,
    hidden: false,
    blocked: false,
    fixed: false,
    askOnHide : false,
    showMeta : false,
    exportNote : false,
    addArrow : 'none',
    askOnDelete : false,
    autoPosition : false,
    addNew : false
  });

  var dIADD=$('#divInfraAreaDadosDinamica')
      //.css('overflow','auto')
      .css('position','relative');
  var d=$('#the_notes');
  var divWidth=parseInt(dIADD.css('width'));
  for(i in lembretes) {

    var l=lembretes[i];
    l.posX+='px';
    l.posY+='px';
    l.onChange = function(id) {modificar(id);}
    l.onDelete = function(id) {desativar(id);}
    $.PostItAll.new(l);
  }

  //intervalo que salva alteracoes- default = 1s
  saveTimer = setInterval(function(){salvar();},1000);
}
function novo() {
  $.PostItAll.new({idSei: null,
                   cssclases : {note: "seiNota"},
                   features: {addArrow: 'none'},
                   onChange: function(id) {modificar(id)},
                   onDelete: function(id) {desativar(id);}
                  });
}

function salvar(){
  if (!bolSalvando) {
    bolSalvando = true;
    $('.PIApostit').each(function () {
      if ($(this).hasClass('modificada')) {

        var opt = $(this).postitall('options');

        if (opt.content=="" || opt.content=='<br>'){
          return;
        }

        x = opt.posX.replace('px','');
        if (x < 2){
          $(this).postitall('options',{posX:2})
          opt.posX = 2;
        }

        y = opt.posY.replace('px','');
        if (y < 2){
          $(this).postitall('options',{posY:2})
          opt.posY = 2;
        }

        if (opt.idSei == null) {
          opt.operacao = 'N';
        } else {
          opt.operacao = 'A';
        }

        var objAjax = new infraAjaxComplementar(null, '<?=$linkAjaxLembrete;?>');
        objAjax.mostrarAviso = false;
        objAjax.limparCampo = false;
        objAjax.async = true;
        objAjax.prepararExecucao = function () {
          var str = "";
          str += 'operacao=' + opt.operacao;
          str += '&id=' + opt.idSei;
          str += '&backgroundcolor=' + opt.style.backgroundcolor;
          str += '&textcolor=' + opt.style.textcolor;
          str += '&posX=' + opt.posX;
          str += '&posY=' + opt.posY;
          str += '&width=' + opt.width;
          str += '&height=' + opt.height;
          str += "&content=" + encodeURIComponent(opt.content);
          return str;
        };

        objAjax.processarResultado = function (arr) {

          if (opt.operacao=="N") {
            opt.idSei = arr['resultado'];
          }

          if (arr['resultado']=='false'){
            alert('Erro salvando lembrete [' + opt.idSei + '].');
          }

        };
        objAjax.executar();

        $(this).removeClass('modificada');
      }
    });

    bolSalvando = false;
  }
}

function modificar(id){
  $(id).addClass('modificada');
}

function desativar(id){
  var idSei = $(id).postitall('options').idSei;
  if (idSei != null) {
    var objAjax = new infraAjaxComplementar(null, '<?=$linkAjaxLembrete;?>');
    objAjax.mostrarAviso = false;
    objAjax.limparCampo = false;
    objAjax.async = true;
    objAjax.prepararExecucao = function () {
      var str = "";
      str += 'operacao=D';
      str += '&id=' + idSei;
      return str;
    };

    objAjax.processarResultado = function (arr) {
      if (arr['resultado']=='false') {
        alert('Erro desativando lembrete [' + idSei + '].');
      }
    };
    objAjax.executar();
  }
}

</script>
<?
//PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
?>
  <a id="ancListar" href="<?=$linkAncora;?>"  class="ancoraPadraoPreta">Ver fechados</a>
  <br /><br />
<?
PaginaSEI::getInstance()->abrirAreaDados();
?>
  <div id="the_notes"></div>
<?
PaginaSEI::getInstance()->fecharAreaDados();
//PaginaSEI::getInstance()->montarAreaDebug();
//PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>