﻿CKEDITOR.plugins.add("simpleLink",{init:function(c){c.addCommand("simpleLinkDialog",new CKEDITOR.dialogCommand("simpleLinkDialog"));c.ui.addButton("SimpleLink",{label:"Inserir um Link",command:"simpleLinkDialog",icon:this.path+"images/icon.png"});CKEDITOR.dialog.add("simpleLinkDialog",function(c){return{title:"Propriedades do Link",minWidth:400,minHeight:200,contents:[{id:"general",label:"Settings",elements:[{type:"textarea",id:"contents",label:"Texto Visível",validate:CKEDITOR.dialog.validate.notEmpty("O texto visível não pode ser vazio."),
required:!0,commit:function(a){a.contents=this.getValue()}},{type:"text",id:"url",label:"URL",validate:function(){if(!/^([a]|[^a])+$/.test(this.getValue()))return"A URL não pode ser vazia.";if(CKEDITOR.config.url_sei_re&&(new RegExp(CKEDITOR.config.url_sei_re)).test(this.getValue()))return"Para esta URL utilize o botão Inserir Link SEI."},required:!0,commit:function(a){a.url=this.getValue()}},{type:"select",id:"style",label:"Estilo",items:[["\x3cnenhum\x3e",""],["Negrito","b"],["Sublinhado","u"],
["Itálico","i"]],commit:function(a){a.style=this.getValue()}},{type:"checkbox",id:"newPage",label:"Abrir em nova página.","default":!0,commit:function(a){a.newPage=this.getValue()}}]}],onOk:function(){var a={},b=c.document.createElement("a");this.commitContent(a);b.setAttribute("href",a.url);a.newPage&&b.setAttribute("target","_blank");switch(a.style){case "b":b.setStyle("font-weight","bold");break;case "u":b.setStyle("text-decoration","underline");break;case "i":b.setStyle("font-style","italic")}b.setHtml(a.contents);
c.insertElement(b)}}})}});