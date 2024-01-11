function infraMapa(divId, pontos, descItemSingular, descItemPlural) {

    var me = this;
    this.descItemSingular = descItemSingular;
    this.descItemPlural = descItemPlural;

    this.zoomed = function () {
        d3.select("#grp_estados").attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")")
            .selectAll("path").attr("stroke-width", 200 / d3.event.scale);
        d3.select("#grp_itens").attr("transform", "translate(" + d3.event.translate + ")scale(" + d3.event.scale + ")")
            .selectAll("path, circle")
            .attr("transform", function (d) {
                if (d.angulo) return "translate(" + (Math.cos(d.angulo) * d.raio / me.zoom.scale()) + "," + ((Math.sin(d.angulo) * d.raio - 6000) / me.zoom.scale()) + ")scale(" + 6 / me.zoom.scale() + ")";
                return "scale(" + 6 / me.zoom.scale() + ")";
            });

    }

    this.zoom = d3.behavior.zoom()
        .translate([-20000, 0])
        .scale(1)
        .scaleExtent([1, 8])
        .on("zoom", this.zoomed);

    this.svg = d3.select("#" + divId)
        .append("svg")
        .style("overflow", "hidden")
        .attr("id", "svg2")
        .attr("width", "100%")
        .attr("height", 600)
        .attr("viewBox", "0 0 220000 194010");

    this.svg.call(this.zoom);


    this.group = this.svg.append("g").attr("id", "grp_estados");

    d3.json(INFRA_PATH_JS + "/mapa/infra_mapa_regioes_jf.json", function (json) {
        json.path.forEach(function (a) {
            me.group.append("path")
                .attr("id", a.id)
                .attr("class", a.class)
                .attr("d", a.d)
                .attr("stroke-width", 200);
        });
    });

    this.grp2 = this.svg.append("g").attr("id", "grp_itens");

    this.tooltip = d3.select("#" + divId).append("div")
        .attr("class", "infraMapaTooltip")
        .style("display", "none");

    this.tooltipContent = this.tooltip.append("div")
        .style("position", "absolute")
        .style("z-index", 2)
        .style("padding", "8px");

    this.tooltipPath = this.tooltip.append("svg")
        .attr("class", "infraMapaTooltipCaminho");

    this.tooltipPath.append("path");

    this.tooltipName = this.tooltipContent.append("div")
        .attr("class", "infraMapaTooltipNome")
        .style("color", "#fff");

    this.tooltipDescription = this.tooltipContent.append("div")
        .attr("class", "infraMapaTooltipDescricao")
        .style("color", "#98c1de");

    this.idx = 0;

    pontos.children.forEach(function (d) {
        if (!d.id_ponto) {
            var index = 0;
            var raio = 10000;
            var qtd = d._children.length;
            var pos_ini = Math.PI * 2 / qtd;
            if (qtd > 12) {
                raio = qtd * 2550 / Math.PI;
            }
            d._children.forEach(function (e) {
                e.angulo = pos_ini * (++index);
                e.raio = raio;
            })
        }
    });

    this.tree = d3.layout.tree().sort(null).size([360, 100]);

    this.atualizar = function () {

        var nodes = me.tree.nodes(pontos);

        var node = me.grp2.selectAll("g").data(nodes.slice(1), function (d) {
            return d.id || (d.id = ++me.idx);
        });

        var grp3 = node.enter().append("g").attr("transform", function (d) {
            if (d.cx) {
                return "translate(" + (d.cx) + "," + (d.cy) + ")";
            }
            return "translate(" + (d.parent.cx) + "," + (d.parent.cy) + ")";
        })
            .on("mouseover", me.mouseover)
            .on("click", me.click)
            .on("mouseout", me.mouseout);

        grp3.each(function (data, idx) {

            var obj = null;

            if (data.angulo) {
                obj = d3.select(this).append("circle").attr("r", 400);
            } else {
                obj = d3.select(this).append("path").attr("d", "m0,0c-38,-190 -107,-348 -189,-495c-61,-108 -132,-209 -198,-314c-21,-35 -40,-72 -62,-109c-42,-73 -76,-157 -74,-267c2,-107 33,-193 78,-264c73,-115 197,-210 362,-235c135,-20 262,14 352,66c73,43 130,100 173,168c45,70 76,154 78,263c1,55 -7,107 -20,150c-13,43 -33,79 -52,118c-36,75 -82,144 -127,214c-136,206 -264,417 -320,706z");

                if (data.children) {
                    d3.select(this).append("path").attr("d", "m-200,-1200l400,0").style("stroke", "#000").style("stroke-width", 150).style("stroke-linecap", "round");
                } else if (data._children) {
                    d3.select(this).append("path").attr("d", "m-200,-1200l400,0").style("stroke", "#000").style("stroke-width", 150).style("stroke-linecap", "round");
                    d3.select(this).append("path").attr("data-show", 1).attr("d", "m0,-1000l0,-400").style("stroke", "#000").style("stroke-width", 150).style("stroke-linecap", "round");
                }
            }

            obj.attr("transform", "scale(" + 6 / me.zoom.scale() + ")")
                .attr("href", function (d) {
                    if (d.href) return d.href;
                    return null;
                })
                .attr("fill", "white")
                .attr("stroke-miterlimit", 10)
                .attr("stroke-width", 37)
                .attr("stroke", "#000000");
        });

        node.selectAll("path,circle").transition().duration(750)
            .style("display", function (d) {
                if (d.depth == 1 && d3.select(this).attr("data-show") != null) {
                    if (d.children) {
                        return "none";
                    } else {
                        return "";
                    }
                }
                return "";
            })
            .attr("fill", function (d) {
                if (d.cor) return d.cor;
                if (d.children != null) return "#50e67e";
                return "#91d68e";
            })
            .attr("transform", function (d) {
                if (d.angulo) return "translate(" + (Math.cos(d.angulo) * d.raio / me.zoom.scale()) + "," + ((Math.sin(d.angulo) * d.raio - 6000) / me.zoom.scale()) + ")scale(" + 6 / me.zoom.scale() + ")";
                return "scale(" + 6 / me.zoom.scale() + ")";
            });
        node.exit().selectAll("circle").transition()
            .duration(750)
            .attr("transform", function (d) {
                return "scale(" + 6 / me.zoom.scale() + ")";
            });
        node.exit().transition().duration(750).remove();
        node.each(function (data) {
            if (data.depth === 1 && data.children) {

            }
        });

        nodes.forEach(function (d) {
            d.x0 = d.x;
            d.dy = d.y;
        });
    }


    this.click = function (d) {
        this.parentNode.appendChild(this);
        if (!d.id_ponto) {
            if (d._children) {
                d.children = d._children;
                d._children = null;
            } else {
                d._children = d.children;
                d.children = null;
            }
            setTimeout(me.atualizar, 0);
        } else {
            infraAbrirJanelaModal(d.href, 800, 600);
        }
    }

    this.mouseover = function (d) {
        if (d.id_ponto) {
            me.tooltipName.text('');
            me.tooltipDescription.text(d.descricao);
        } else {
            me.tooltipName.text(d.local);
            if (d.children) {
                me.tooltipDescription.text(d.children.length + " " + me.descItemPlural);
            } else {
                me.tooltipDescription.text(d._children.length + " " + me.descItemPlural);
            }
        }
        me.tooltip.style("display", null);
        var tooltipRect = me.tooltipContent.node().getBoundingClientRect();
        me.tooltipPath
            .attr("width", tooltipRect.width + 4)
            .attr("height", tooltipRect.height + 10)
            .style("margin-left", "-2px")
            .style("margin-top", "-10px")
            .select("path")
            .attr("transform", "translate(2,10)")
            .attr("d", "M0,6a6,6 0 0,1 6,-6H" + (tooltipRect.width / 2 - 6) + "l6,-6l6,6H" + (tooltipRect.width - 6) + "a6,6 0 0,1 6,6v" + (tooltipRect.height - 12) + "a6,6 0 0,1 -6,6H6a6,6 0 0,1 -6,-6z");

        if (INFRA_IE > 0) {
            me.tooltip
                .style("left", (d3.event.x - tooltipRect.width / 2) + "px")
                .style("top", (d3.event.y + 13) + "px");
        } else if (INFRA_FF > 0) {
            me.tooltip
                .style("left", (d3.event.layerX - tooltipRect.width / 2) + "px")
                .style("top", (d3.event.layerY + 13) + "px");
        } else {
            me.tooltip
                .style("left", (d3.event.offsetX - tooltipRect.width / 2) + "px")
                .style("top", (d3.event.offsetY + 13) + "px");
        }
    }

    this.mouseout = function () {
        me.tooltip.style("display", "none");
    }

    me.atualizar();
}