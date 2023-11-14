﻿CKEDITOR.plugins.add("symbol",{availableLangs:{en:1,"pt-br":1},lang:"en,pt-br",requires:"dialog",icons:"symbol",init:function(a){var c=this;CKEDITOR.dialog.add("symbol",this.path+"dialogs/symbol.js");a.addCommand("symbol",{exec:function(){if(a.config.removeSymbolRanges&&0<a.config.removeSymbolRanges.length)for(var d=a.config.removeSymbolRanges.length-1;0<=d;d--){var e=a.config.removeSymbolRanges[d];e<a.config.symbolRanges.length&&a.config.symbolRanges.splice(e,1)}var b=a.langCode,b=c.availableLangs[b]?
b:c.availableLangs[b.replace(/-.*/,"")]?b.replace(/-.*/,""):"en";CKEDITOR.scriptLoader.load(CKEDITOR.getUrl(c.path+"dialogs/lang/"+b+".js"),function(){CKEDITOR.tools.extend(a.lang.symbol,c.langEntries[b]);a.openDialog("symbol")})},modes:{wysiwyg:1},canUndo:!1});a.ui.addButton&&a.ui.addButton("Symbol",{label:a.lang.symbol.toolbar,command:"symbol",toolbar:"insert"})}});
CKEDITOR.config.symbolRanges=[["Arrows","2190-21FF"],["Combining Diacritical Marks","0300-036F"],["Combining Diacritical Marks for Symbols","20D0-20FF"],["Control Pictures","2400-243F"],["Currency Symbols","20A0-20CF"],["Cyrillic","0400-04FF"],["Cyrillic Supplementary","0500-052F"],["Dingbats","2700-27BF"],["Enclosed Alphanumerics","2460-24FF"],["General Punctuation","2000-206F"],["Geometric Shapes","25A0-25FF"],["Greek and Coptic","0370-03FF"],["Greek Extended","1F00-1FFF"],["IPA Extensions","0250-02AF"],
["Latin Extended Additional","1E00-1EFF"],["Latin Extended-A","0100-017F"],["Latin Extended-B","0180-024F"],["Latin-1 Supplement","00A0-00B0,00B1,00B2-00FF"],["Letterlike Symbols","2100-214F"],["Mathematical Operators","2200-22FF"],["Miscellaneous Mathematical Symbols-A","27C0-27EF"],["Miscellaneous Mathematical Symbols-B","2980-29FF"],["Miscellaneous Symbols","2600-26FF"],["Miscellaneous Symbols and Arrows","2B00-2BFF"],["Miscellaneous Technical","2300-23FF"],["Number Forms","2150-218F"],["Phonetic Extensions",
"1D00-1D7F"],["Spacing Modifier Letters","02B0-02FF"],["Superscripts and Subscripts","2070-209F"],["Supplemental Arrows-A","27F0-27FF"],["Supplemental Arrows-B","2900-297F"],["Supplemental Mathematical Operators","2A00-2AFF"]];