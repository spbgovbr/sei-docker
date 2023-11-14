/* This notice must remain at all times.

pie.js
Copyright (c) Balamurugan S, 2005. sbalamurugan @ hotmail.com
Development support by Jexp, Inc http://www.jexp.com 

This package is free software. It is distributed under GPL - legalese removed, it means that you can use this for any purpose, but cannot charge for this software. Any enhancements you make to this piece of code, should be made available free to the general public!

Latest version can be downloaded from http://www.sbmkpm.com

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 

pie.js provides a simple mechanism to draw pie charts. It  uses 
wz_jsgraphics.js  which is copyright of its author.

Usage:
var p = new pie();
p.add("title1",value1);
p.add("title2",value2);

p.render("canvas_name", "graph title");

//where canvas_name is a div defined INSIDE body tag
<body>
<div id="canvas_name" style="overflow: auto; position:relative;height:400px;width:400px;"></div>
*/

hD="0123456789ABCDEF";

function d2h(d) {
  var h = hD.substr(d&15,1);
  while(d>15) {d>>=4;h=hD.substr(d&15,1)+h;}
  return h;
}

function h2d(h) {
  return parseInt(h,16);
}

function pie() {
  this.ct = 0;
  this.data      = new Array();
  this.x_name    = new Array();
  this.color     = new Array();
  this.max       = 0;
  this.c_array = new Array();
  this.c_array[0] = new Array(255, 192, 95);
  this.c_array[1] = new Array(80, 127, 175);
  this.c_array[2] = new Array(159, 87, 112);
  this.c_array[3] = new Array(111, 120, 96);
  this.c_array[4] = new Array(224, 119, 96);
  this.c_array[5] = new Array(80, 159, 144);
  this.c_array[6] = new Array(207, 88, 95);
  this.c_array[7] = new Array(64, 135, 96);
  this.c_array[8] = new Array(239, 160, 95);
  this.c_array[9] = new Array(144, 151, 80);
  this.c_array[10] = new Array(255, 136, 80);

  this.getColor = function() {
    if (this.ct >= (this.c_array.length-1))
      this.ct = 0;
    else
      this.ct++;
    return "#" + d2h(this.c_array[this.ct][0]) + d2h(this.c_array[this.ct][1]) + d2h(this.c_array[this.ct][2]);
  }

  this.add = function(x_name, value, color) {
    this.x_name.push(x_name);  
    this.data.push(parseInt(value,10));
    
    if (color==undefined){
       this.color.push(this.getColor());
    }else{
       this.color.push(color);; 
    }
      
    
    this.max += parseInt(value,10);
  }

  this.fillArc = function(x, y, r, st_a, en_a, jg) {
    //var number_of_steps = Math.round(2.1 * Math.PI * r );
    var number_of_steps = 200; //en_a - st_a ;
    var angle_increment = 2 * Math.PI / number_of_steps;
    var xc = new Array();
    var yc = new Array();
    st_r = st_a*Math.PI / 180;
    en_r = en_a*Math.PI / 180;
    for (angle = st_r; angle <= en_r; angle += angle_increment) {
      if (en_r < angle + angle_increment)
        angle = en_r;
      var y2 = Math.sin(angle) * r ;
      var x2 = Math.cos(angle) * r ;
      xc.push(x+x2);
      yc.push(y-y2);
      //jg.drawLine(x+x2, y-y2, x+x2, y-y2);
    }
    xc.push(x);
    yc.push(y);
    jg.fillPolygon(xc, yc);
    //jg.setColor("black");
    //jg.drawLine(x, y, x+ln_x, y-ln_y);
  }

  this.render = function(canvas, title) {
   var jg = new jsGraphics(canvas);
   var r  = 100;
   var sx = 220;
   var sy = 140;
   var hyp = 120;
   var fnt    = 10;
   // shadow
   jg.setColor("gray");
   //this.fillArc(sx+5, sy+5, r, 0, 360, jg);
   jg.fillEllipse(sx+5-r, sy+5-r, 2*r, 2*r);
   var st_angle = 0;
   for (i = 0; i<this.data.length; i++) {
     var angle = Math.round(this.data[i]/this.max*360);
     var pc = Math.round(this.data[i]/this.max*100);
     jg.setColor(this.color[i]);	 
     this.fillArc(sx, sy, r, st_angle, st_angle+angle, jg);
     var ang_rads = (st_angle+(angle/2))*2*Math.PI/360;
     var my  = Math.sin(ang_rads) * hyp;
     var mx  = Math.cos(ang_rads) * hyp;
     st_angle += angle;
     mxa = (mx < 0 ? 50 : 0);
     jg.setColor("black");
     jg.drawString(this.x_name[i]+" ("+pc+"%"+")",sx+mx-mxa,sy-my);
   }
   jg.setColor("black");
   jg.drawEllipse(sx-r, sy-r, 2*r, 2*r);
   jg.setFont("Verdana", fnt,  Font.BOLD);
   jg.drawStringRect(title, 0, sx+r+fnt+14, sx+2*r, "right");
   jg.paint();
  }
}