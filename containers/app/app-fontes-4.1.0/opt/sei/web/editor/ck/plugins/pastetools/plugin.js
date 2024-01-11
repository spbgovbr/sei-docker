﻿/*
 Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
*/
(function(){function n(a,b){return CKEDITOR.tools.array.filter(a,function(a){return a.canHandle(b)}).sort(function(a,c){return a.priority===c.priority?0:a.priority-c.priority})}function k(a,b){var d=a.shift();d&&d.handle(b,function(){k(a,b)})}function p(a){var b=CKEDITOR.tools.array.reduce(a,function(a,c){return CKEDITOR.tools.array.isArray(c.filters)?a.concat(c.filters):a},[]);return CKEDITOR.tools.array.filter(b,function(a,c){return CKEDITOR.tools.array.indexOf(b,a)===c})}function l(a,b){var d=
0,c,e;if(!CKEDITOR.tools.array.isArray(a)||0===a.length)return!0;c=CKEDITOR.tools.array.filter(a,function(a){return-1===CKEDITOR.tools.array.indexOf(m,a)});if(0<c.length)for(e=0;e<c.length;e++)(function(a){CKEDITOR.scriptLoader.queue(a,function(e){e&&m.push(a);++d===c.length&&b()})})(c[e]);return 0===c.length}var m=[],q=CKEDITOR.tools.createClass({$:function(){this.handlers=[]},proto:{register:function(a){"number"!==typeof a.priority&&(a.priority=10);this.handlers.push(a)},addPasteListener:function(a){a.on("paste",
function(b){var d=n(this.handlers,b),c;if(0!==d.length){c=p(d);c=l(c,function(){return a.fire("paste",b.data)});if(!c)return b.cancel();k(d,b)}},this,null,3)}}});CKEDITOR.plugins.add("pastetools",{requires:["clipboard","ajax"],beforeInit:function(a){a.pasteTools=new q;a.pasteTools.addPasteListener(a)}});CKEDITOR.plugins.pastetools={filters:{},loadFilters:l,createFilter:function(a){var b=CKEDITOR.tools.array.isArray(a.rules)?a.rules:[a.rules],d=a.additionalTransforms;return function(a,e){var f=new CKEDITOR.htmlParser.basicWriter,
g=new CKEDITOR.htmlParser.filter,h;d&&(a=d(a,e));CKEDITOR.tools.array.forEach(b,function(b){g.addRules(b(a,e,g))});h=CKEDITOR.htmlParser.fragment.fromHtml(a);g.applyTo(h);h.writeHtml(f);return f.getHtml()}},getClipboardData:function(a,b){var d;return CKEDITOR.plugins.clipboard.isCustomDataTypesSupported||"text/html"===b?(d=a.dataTransfer.getData(b,!0))||"text/html"!==b?d:a.dataValue:null},getConfigValue:function(a,b){if(a&&a.config){var d=CKEDITOR.tools,c=a.config,e=d.object.keys(c),f=["pasteTools_"+
b,"pasteFromWord_"+b,"pasteFromWord"+d.capitalize(b,!0)],f=d.array.find(f,function(a){return-1!==d.array.indexOf(e,a)});return c[f]}},getContentGeneratorName:function(a){if((a=/<meta\s+name=["']?generator["']?\s+content=["']?(\w+)/gi.exec(a))&&a.length)return a=a[1].toLowerCase(),0===a.indexOf("microsoft")?"microsoft":0===a.indexOf("libreoffice")?"libreoffice":"unknown"}};CKEDITOR.pasteFilters=CKEDITOR.plugins.pastetools.filters})();