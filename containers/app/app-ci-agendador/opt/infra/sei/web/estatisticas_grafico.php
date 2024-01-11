<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 28/09/2010 - criado por jonatas_db
*
* Versão no CVS: $Id$
*/

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  
	//PaginaSEI::getInstance()->setBolAutoRedimensionar(false);
  //////////////////////////////////////////////////////////////////////////////
  
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();

  //////////////////////////////////////////////////////////////////////////////
  PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);
  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $arrComandos = array();
  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';
  
  $strTitulo = 'Estatísticas de Arquivamento da Unidade';
  
  $bolAcervo= $_POST['hdnAcervo']=='acervo';
  switch($_GET['acao']) {

    case 'estatisticas_grafico_exibir':

      break;

    default:
      throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
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
.divAreaGrafico DIV { margin:0px; }

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
?>

<script type="text/javascript" src="/infra_js/raphaeljs/raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.raphael-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.bar-min.js"></script>
<script type="text/javascript" src="/infra_js/raphaeljs/g.line-min.js"></script>
<?
PaginaSEI::getInstance()->abrirJavaScript();
?>
//<script>
  function infraGraficoLinhas(divId){
    var me=this;
    this.limparDiv=true;
    this.titulo='';
    this.arrEixoX=null;
    this.arrEixoXRotulos=null;
    this.arrDados=null;
    this.div=document.getElementById(divId);

    this.altura=200;
    this.largura=750;
    this.margem=5;
    this.objGrafico=null;
    this.objRetangulo=null;
    this.objTxt=null;

    this.exibirTitulo=function(){
      if (!me.objGrafico) return;
      if(me.titulo!=''){
        if (me.objTxt){
          me.objTxt.attr({"text":me.titulo});
        } else {
          me.objTxt=me.objGrafico.text(me.largura/2,me.margem+25,me.titulo).attr({"font-size":12});
        }
      }
    };
    this.exibir=function(){
      if(me.limparDiv){
        $(me.div).children().remove();
        me.objGrafico=null;
        me.objLinhas=null;
        me.objRetangulo=null;
        me.objTxt=null;
      }
      if (me.objGrafico==null){
        me.objGrafico = Raphael(me.div,me.largura,me.altura);
      } else {
        me.objGrafico.clear();
        me.objLinhas=null;
        me.objRetangulo=null;
        me.objTxt=null;
      }

      me.objRetangulo=me.objGrafico.rect(me.margem,me.margem,me.largura-me.margem*2,me.altura-me.margem*2,10);
      me.objRetangulo.attr({ "fill": "90-#ccf:5-#fff:95", "fill-opacity": 0.5 });

      me.exibirTitulo();

      var valormax=0;
      me.arrEixoX=[];
      for(var i=me.arrDados.length;i;){
        me.arrEixoX[--i]=i;
        if(me.arrDados[i]>valormax) valormax=me.arrDados[i];
      }

      if (valormax>10) valormax=5;
      me.objLinhas = me.objGrafico.linechart(me.margem+20, me.margem+35, me.largura-me.margem*2-40, me.altura-me.margem*2-50, me.arrEixoX, me.arrDados,
          {nostroke: false, axis: '0 0 1 1', symbol: 'circle', axisxstep:me.arrDados.length-1,axisystep:valormax, smooth: false})
          .hoverColumn(me.fin,me.fout)
          .clickColumn(me.clickPopup);


      if(me.arrEixoXRotulos!=null) {
        $.each(me.objLinhas.axis[0].text.items , function ( index, label ) {
          label.attr({'text': me.arrEixoXRotulos[index]});
        });
      }


    };
    this.fin = function () {
      this.flag = me.objGrafico.popup(this.x, this.y[0]-4, this.values[0]).insertBefore(this);
    };
    this.fout = function () {
      this.flag.animate({ opacity: 0}, 300, function () { this.remove();  });
    };
    this.clickPopup = function(){
      if (this.values[0]!=0) {
        eval(me.arrLinks[this.axis]);
      }
    }
  }




var r,lines;
var grafico=null;


function inicializar(){

  infraAviso();
  infraEfeitoTabelas();
  seiRedimensionarGraficos();


  var k=<?=$_GET['num_grafico']?>-1;

  var arrGraficos=window.opener.arrGraficos;
  var div=document.getElementById('divGrafico');
  var linha=<?=$_GET['num_linha']?>;

  $('#divInfraBarraLocalizacao').html(arrGraficos[k].titulo+" no período");

  grafico=new infraGraficoLinhas();
  grafico.titulo="Tipo de documento: "+arrGraficos[k].rotulos[linha];
  grafico.arrDados=arrGraficos[k].dados[linha];
  grafico.div=div;
  grafico.arrEixoXRotulos=window.opener.arrEixo;
  grafico.exibir();
  var tabela=$(window.opener.document.getElementById('divTabelas')).find('.infraTable').eq(k);
  grafico.arrLinks=[];
  grafico.tr=tabela.find('tbody > tr').eq(linha);
  $(grafico.tr).find('td').map(
      function(i){
        var link=$(this).find('a').attr('onclick');
        if(link&&link.substr(0,3)=='abr') window.grafico.arrLinks[i-1]=link;
      });

}

function abrirDetalhe(link){
 infraAbrirJanela(link,'janelaEstatisticasDetalhe',750,550,'location=0,status=1,resizable=1,scrollbars=1');
}





//</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
  //PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);

  ?>
  <div id="divGrafico" class="divAreaGrafico">
  </div>
  <?
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);


PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>