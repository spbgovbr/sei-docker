function infraGraficoLinhas(divId) {
    var me = this;
    this.limparDiv = true;
    this.titulo = '';
    this.arrEixoX = null;
    this.arrEixoXRotulos = null;
    this.arrDados = null;
    this.div = document.getElementById(divId);

    this.altura = 200;
    this.largura = 750;
    this.margem = 5;
    this.objGrafico = null;
    this.objRetangulo = null;
    this.objTxt = null;

    this.exibirTitulo = function () {
        if (!me.objGrafico) return;
        if (me.titulo != '') {
            if (me.objTxt) {
                me.objTxt.attr({"text": me.titulo});
            } else {
                me.objTxt = me.objGrafico.text(me.largura / 2, me.margem + 25, me.titulo).attr({"font-size": 12});
            }
        }
    };
    this.exibir = function () {
        if (me.limparDiv) {
            $(me.div).children().remove();
            me.objGrafico = null;
            me.objLinhas = null;
            me.objRetangulo = null;
            me.objTxt = null;
        }
        if (me.objGrafico == null) {
            me.objGrafico = Raphael(me.div, me.largura, me.altura);
        } else {
            me.objGrafico.clear();
            me.objLinhas = null;
            me.objRetangulo = null;
            me.objTxt = null;
        }

        me.objRetangulo = me.objGrafico.rect(me.margem, me.margem, me.largura - me.margem * 2, me.altura - me.margem * 2, 10);
        me.objRetangulo.attr({"fill": "90-#ccf:5-#fff:95", "fill-opacity": 0.5});

        me.exibirTitulo();

        var valormax = 0;
        me.arrEixoX = [];
        for (var i = me.arrDados.length; i;) {
            me.arrEixoX[--i] = i;
            if (me.arrDados[i] > valormax) valormax = me.arrDados[i];
        }

        me.objLinhas = me.objGrafico.linechart(me.margem + 15, me.margem + 35, me.largura - me.margem * 2 - 40, me.altura - me.margem * 2 - 50, me.arrEixoX, me.arrDados,
            {
                nostroke: false,
                axis: '0 0 1 1',
                symbol: 'circle',
                axisxstep: me.arrDados.length - 1,
                axisystep: valormax,
                smooth: false
            })
            .hoverColumn(me.fin, me.fout);


        if (me.arrEixoXRotulos != null) {
            $.each(me.objLinhas.axis[0].text.items, function (index, label) {
                label.attr({'text': me.arrEixoXRotulos[index]});
            });
        }


    };
    this.fin = function () {
        this.flag = me.objGrafico.popup(this.x, this.y[0] - 4, this.values[0]).insertBefore(this);
    };
    this.fout = function () {
        this.flag.animate({opacity: 0}, 300, function () {
            this.remove();
        });
    };

}