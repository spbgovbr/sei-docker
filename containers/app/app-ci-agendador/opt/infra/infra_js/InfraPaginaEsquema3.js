var INFRA_ICONE_AGUARDAR = INFRA_PATH_SVG + '/aguarde.svg';
var INFRA_ICONE_ALTERAR = INFRA_PATH_SVG + '/alterar.svg';
var INFRA_ICONE_REMOVER = INFRA_PATH_SVG + '/remover.svg';
var INFRA_ICONE_MOVER_ACIMA = INFRA_PATH_SVG + '/mover_acima.svg';
var INFRA_ICONE_MOVER_ABAIXO = INFRA_PATH_SVG + '/mover_abaixo.svg';

var btnTopo;
var divInfraMoverTopo;

$( document ).ready(function() {

  $( ".infraCheckbox" ).each(function( index ) {
    if($(this).css("display") != "none"){
      var div = $('<div class="infraCheckboxDiv " ></div>');

      var id = $(this).attr("id");
      var label =  $('<label class="infraCheckboxLabel " for="'+id+'"></label>');

      $(this).removeClass("infraCheckbox");
      $(this).addClass("infraCheckboxInput");


      $(this).wrap(div);
      label.insertAfter($(this));
    }
  });

  $( ".infraRadio" ).each(function( index ) {
    if($(this).css("display") != "none"){
      var div = $('<div class="infraRadioDiv " ></div>');

      var id = $(this).attr("id");
      var label =  $('<label class="infraRadioLabel" for="'+id+'"></label>');

      $(this).removeClass("infraRadio");
      $(this).addClass("infraRadioInput");

      $(this).wrap(div);
      label.insertAfter($(this));
    }
  });

  $(document).bind('keydown', 'alt+m', infraFocarPesquisaMenu);

  //botao topo
  btnTopo = document.getElementById("btnInfraTopo");

  if(!divInfraMoverTopo){
    if(document.getElementById("divInfraAreaTelaD")){
      divInfraMoverTopo = document.getElementById("divInfraAreaTelaD");
    }
  }

  if(divInfraMoverTopo) {
    divInfraMoverTopo.onscroll = function () {
      infraTestarScrollTopo();
    };
  }else{
    window.onscroll = function() {infraTestarScrollTopo()};
  }

});

function infraTestarScrollTopo() {

  show = false;
  var div = $('<div id="divInfraBtnTopo" style="height: 60px;"  ></div>');

  if(divInfraMoverTopo) {
    if ( divInfraMoverTopo.scrollTop > 20) {
      show = true;
    }
  }else{
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      show = true;
    }
  }

  if(show ){
    $(btnTopo).show('fast');
    if($("#divInfraBtnTopo").length == 0) {
      if (divInfraMoverTopo) {
        $(divInfraMoverTopo).append(div);
      } else {
        $(document.body).append(div);
      }
    }
    $(btnTopo).attr("exibido","true");
  }else {
    $(btnTopo).hide('fast');
    $("#divInfraBtnTopo").remove();
    $(btnTopo).attr("exibido","false");

  }



}

// When the user clicks on the button, scroll to the top of the document
function infraMoverParaTopo() {
  if(divInfraMoverTopo) {
    $(divInfraMoverTopo).animate({scrollTop: 0}, 600);
  }else{
    $(document.body).animate({scrollTop: 0}, 600);
    $(document.documentElement).animate({scrollTop: 0}, 600);
  }
}

function infraIsBreakpointBootstrap( alias ) {
  return $('#infraDivBootstrap-' + alias).is(':visible');
}


