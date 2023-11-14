/**
* version 1.1.0
* upgrades 
* - fade animation on open and close
* - open method. can open modal without binding link
* - possible to open modal with "POST". nessecary when need to send large amount of data to modal
*/

(function ($) {

    var callbackCloseFunction;
    var disableScroll;
    var maximized;
    var openedWidth;
    var openedMarginLeft;
    var openedHeight;
    var openedMarginTop;


    $.modalLinkDefaults = {
            height: 600,
            width: 900,
            showTitle: true,
            showClose: true,
            overlayOpacity: 0.6,
            method: "GET", // GET, POST, REF, CLONE
            disableScroll: true,
            onHideScroll: function () { },
            onShowScroll: function () { }
    };

    function hideBodyScroll(cb) {
        var w = $("body").outerWidth();
        $("body").css({ overflow: "hidden" });
        var w2 = $("body").outerWidth();
        $("body").css({ width: w });

        if (typeof cb == "function") {
            var scrollbarWidth = w2 - w;
            cb(scrollbarWidth);
        }
    }

    function showBodyScroll(cb) {
        var $body = $("body");
        var w = $body.outerWidth();
        $body.css({ width: '', overflow: '' });
        var w2 = $body.outerWidth();

        if (typeof cb == "function") {
            var scrollbarWidth = w - w2; 
            cb(scrollbarWidth);
        }
    }

    /**
     * Helper method for appending parameter to url
     */
    function addUrlParam(url, name, value) {
        return appendUrl(url, name + "=" + value);
    }

    /**
     * Hepler method for appending querystring to url
     */
    function appendUrl(url, data) {
        return url + (url.indexOf("?") < 0 ? "?" : "&") + data;
    }

    function buildOptions(option) {
        
    }
    
    function resolveBooleanValue($link) {
        
        for (var i = 1; i < arguments.length; i++) {
            var val = arguments[i];
            
            if (typeof val == "boolean") {
                return val;
            }
            
            if (typeof val == "string") {
                var attrValue = $link.attr(val);
                if (attrValue) {
                    if (attrValue.toLowerCase() === "true") {
                        return true;
                    }
                    if (attrValue.toLowerCase() === "false") {
                        return false;
                    }
                }
            }
        }
    }

    var methods = {
        init: function (options) {
            maximized = false;
            
            var settings = $.extend({}, $.modalLinkDefaults);
            $.extend(settings, options);

            return this.each(function () {
                var $link = $(this);
                
                // already bound
                if ($link.hasClass("sparkling-modal-link"))
                    return;
                
                // mark as bound
                $link.addClass("sparkling-modal-link");

                $link.click(function (e) {
                    e.preventDefault();
                    methods.open($link, settings);
                    return false;
                });
            });
        },
        
        close: function (cb) {

            var $container = $(".sparkling-modal-container");

            var $link = $container.data("modallink");

            if (!$link) {
                return;
            }

            if(typeof callbackCloseFunction == "function"){
                callbackCloseFunction();
            }else if (callbackCloseFunction!=null){
                var iframe = document.getElementById("modal-frame");
                if (iframe != null){
                    iframe.contentWindow[callbackCloseFunction]();
                }
            }

            $link.trigger("modallink.close");

            var $overlay = $container.find(".sparkling-modal-overlay");
            var $content = $container.find(".sparkling-modal-frame");

            $overlay.fadeTo("fast", 0);

          $content.fadeTo("fast", 0, function () {
            $container.remove();
            showBodyScroll(cb);

            if (typeof cb == "function") {
              cb();
            }
          });
        },

        resize: function () {

            if(!maximized){
                maximized = true;

                $( ".sparkling-modal-frame" ).animate({
                    width:"100%",
                    height:"100%",
                    left: "0px",
                    marginLeft: "0px",
                  top: "0px",
                    marginTop: "0px",
                }, 200, function() {
                    // Animation complete.
                });

                $(".sparkling-modal-content")
                  .css("width","100%")
                  .css("height","100%");

                $("#modal-frame")
                    .css("width","100%")
                    .css("height","100%");
                $(".sparkling-modal-size").html("<img onmouseover=\"this.src='/infra_css/imagens/restaurar_janela_vermelho.png'\" onmouseout=\"this.src='/infra_css/imagens/restaurar_janela_branco.png'\" src='/infra_css/imagens/restaurar_janela_branco.png'/>");

            }else{
                maximized = false;

                $("#modal-frame").css({ width: openedWidth, height: openedHeight });
                $(".sparkling-modal-content")
                  .css("width","")
                  .css("height","");
                $(".sparkling-modal-frame")
                  .css("left","50%")
                  .css("marginLeft",openedMarginLeft)
                  .css("top","50%")
                  .css("marginTop",openedMarginTop)
                  .css("width","")
                  .css("height","");
              /*  $( ".sparkling-modal-frame" ).animate({
                    width:"",
                    height:"",
                    left: "50%",
                    marginLeft: openedMarginLeft,
                    top: "50%",
                    marginTop: openedMarginTop,
                }, 200, function() {


                });*/

                $(".sparkling-modal-size").html("<img onmouseover=\"this.src='/infra_css/imagens/maximizar_janela_vermelho.png'\" onmouseout=\"this.src='/infra_css/imagens/maximizar_janela_branco.png'\" src='/infra_css/imagens/maximizar_janela_branco.png'/>");

            }
        },
        
        open: function ($link, options) {
            if(options.callbackClose){
                callbackCloseFunction = options.callbackClose;
            }

            disableScroll = options.disableScroll;

            options = options || {};
            var url, title, showTitle, showClose, disableScroll;

            url = options.url || $link.attr("href");
            title = options.title 
                || $link.attr("data-ml-title")
                || $link.attr("title")
                || $link.text();
                        
            showTitle = resolveBooleanValue($link, 
                options.showTitle, 
                "data-ml-show-title", 
                $.modalLinkDefaults.showTitle);
                           
            showClose = resolveBooleanValue($link,
                options.showClose,
                "data-ml-show-close",
                $.modalLinkDefaults.showClose);
                           
            disableScroll = resolveBooleanValue($link,
                options.disableScroll,
                "data-ml-disable-scroll",
                $.modalLinkDefaults.disableScroll);
            
            var settings = $.extend({}, $.modalLinkDefaults);
            $.extend(settings, options);

            var dataWidth = $link.attr("data-ml-width");
            if (dataWidth) {
                settings.width = parseInt(dataWidth);
            }
            if(settings.width > $( document  ).width()){
                settings.width = $( document  ).width()-20;
            }
            openedWidth = settings.width;

            var dataHeight = $link.attr("data-ml-height");
            if (dataHeight) {
                settings.height = parseInt(dataHeight);
            }
            if(settings.height +100> window.innerHeight ){
              settings.height = window.innerHeight -100;
            }
            openedHeight = settings.height;

            if (settings.method !== "CLONE" && url.length > 0 && url[0] === "#") {
                settings.method = "REF";
            }

            if (settings.method == "GET" || settings.method == "POST") {
                //url = addUrlParam(url, "__inmodal", "true");
            }

            var data = {};

            if (typeof settings.data != 'undefined') {
                if (typeof settings.data == "function") {
                    data = settings.data();
                }
                else {
                    data = settings.data;
                }
            }

            var $container = $("<div class=\"sparkling-modal-container\"></div>");
            $container.data("modallink", $link);

            var $overlay = $("<div class=\"sparkling-modal-overlay\"  ></div>");
            $overlay.css({ position: 'fixed', top: 0, left: 0, opacity: 0, width: '100%', height: '100%', zIndex: 1999 });
            $overlay.appendTo($container);
           // $overlay.click(methods.close);

            openedMarginTop = -settings.height / 2;
            openedMarginLeft = -settings.width / 2;
            var $content = $("<div class=\"sparkling-modal-frame\"></div>")
                .css("opacity", 0)
                .css({ zIndex: 2000, position: 'fixed', display: 'inline-block' })
                .css({ left: '50%', marginLeft: openedMarginLeft })
                .css({ top: '50%', marginTop: openedMarginTop })
                .appendTo($container);

            $("body").append($container);

            if (showTitle) {
                
                var $title = $("<div class=\"sparkling-modal-title\"></div>");
                $title.appendTo($content);

                var $sizeButton = $("<div class=\"sparkling-modal-size\"><img onmouseover=\"this.src='/infra_css/imagens/maximizar_janela_vermelho.png'\" onmouseout=\"this.src='/infra_css/imagens/maximizar_janela_branco.png'\" src='/infra_css/imagens/maximizar_janela_branco.png'/></div>");
                $sizeButton.click(methods.resize);
                $sizeButton.appendTo($title);

                if (showClose) {
                    var $closeButton = $("<div class=\"sparkling-modal-close\"><img onmouseover=\"this.src='/infra_css/imagens/fechar_janela_vermelho.png'\" onmouseout=\"this.src='/infra_css/imagens/fechar_janela_branco.png'\" src='/infra_css/imagens/fechar_janela_branco.png'/></div>");
                    $closeButton.appendTo($title);

                    $closeButton.click(methods.close);
                }
                
                $title.append("<div style=\"clear: both;\"></div>");
            }
            var $iframeContainer = $("<div class=\"sparkling-modal-content\" ></div>");
            $iframeContainer.appendTo($content);

            var $iframe;
            if (settings.method == "REF") {
                $iframe = $("<div />");
                $iframe.css("overflow", "auto");

                var $ref = $(url);
                var id = "ref_" + new Date().getTime();
                var $ph = $("<div id='" + id + "' />");
                $ph.insertAfter($ref);

                $ref.appendTo($iframe);

                $link.on("modallink.close", function() {
                    $ph.replaceWith($ref);
                });

            } else {
              var scroll = "";
              if(disableScroll && disableScroll == true){
                scroll = "no";
              }
              $iframe = $("<iframe frameborder=0   scrolling='"+scroll+"'  id='modal-frame' name='modal-frame'></iframe>");
            }

            $iframe.appendTo($iframeContainer);
            $iframe.css({ width: settings.width, height: settings.height });

            if (settings.method == "CLONE") {
                console.log(url);
                var $inlineContent = $(url);
                console.log($inlineContent);

                var iFrameDoc = $iframe[0].contentDocument || $iframe[0].contentWindow.document;
                iFrameDoc.write($inlineContent.html());
                iFrameDoc.close();
            }
            else if (settings.method == "GET") {
                if (typeof data == "object") {
                    for (var i in data) {
                        if (data.hasOwnProperty(i)) {
                            url = addUrlParam(url, i, data[i]);
                        }
                    }
                } else if (typeof data != "undefined") {
                    url = appendUrl(url, data);
                }
                
                $iframe.attr("src", url);
            }

            openedMarginTop = -($content.outerHeight(false) / 2);
            $content.css({ marginTop: openedMarginTop });

            $overlay.fadeTo("fast", settings.overlayOpacity);
            $content.fadeTo("fast", 1);

            if (settings.method == "POST") {
                
                var $form = settings.$form;
                if ($form && $form instanceof jQuery)
                {
                    var originalTarget = $form.attr("target"); 
                    $form
                        .attr("target", "modal-frame")
                        .data("submitted-from-modallink", true)
                        .submit()
                }
                else
                {
                    $form = $("<form />")
                        .attr("action", url)
                        .attr("method", "POST")
                        .attr("target", "modal-frame")
                        .css({ display: 'none'});

                    $("<input />").attr({ type: "hidden", name: "__sparklingModalInit", value: 1 }).appendTo($form);

                    if ($.isArray(data)) {
                        for (var i in data) {
                            $("<input />").attr({ type: "hidden", name: data[i].name, value: data[i].value }).appendTo($form);
                        }
                    }
                    else
                    {
                        for (var i in data) {
                            $("<input />").attr({ type: "hidden", name: i, value: data[i] }).appendTo($form);
                        }
                    }

                    $form
                        .appendTo("body")
                        .submit();

                    $form.remove();
                }
            }

            if (disableScroll) {
                hideBodyScroll(settings.onHideScroll);
            }
        }
    };

    $.fn.modalLink = function(method) {
        // Method calling logic
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.modalLink');
            return this;
        }
    };
    
    $.modalLink = function(method) {
        // Method calling logic
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.modalLink');
            return this;
        }
    };

    $.modalLink.open = function(url, options) {
        options = $.extend({}, options);
        options.url = url;
        methods["open"].call(this, $("<a />"), options);
    };

})(jQuery);


$(document).keyup(function(e) {

    if (e.keyCode == 27) {
        $.modalLink("close");
    }   
});