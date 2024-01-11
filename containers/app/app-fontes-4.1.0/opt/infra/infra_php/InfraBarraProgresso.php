<?php
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 14/03/2008 - criado por mga
*
*
*/

class InfraBarraProgresso
{
    // private vars
    private $code;    // unique code
    private $status = 'new';    // current status (new,show,hide)
    private $step = 0;    // current step
    private $position = array(); // current bar position

    // public vars
    private $min = 0;    // minimal steps
    private $max = 100;    // maximal steps

    private $width = 500;    // bar width
    private $height = 25;    // bar height
    private $padding = 1;    // bar padding
    private $corFundo = '#c0c0c0';
    private $corBarra = '#0033ff';
    private $rotulo = '';

    private $tagAbrirJS = "<script type=\"text/javascript\" charset=\"iso-8859-1\" >\n<!--//--><![CDATA[//><!--\n";
    private $tagFecharJS = " //--><!]]>\n</script>\n";

    public function __construct($width = null, $height = null, $padding = null, $corBarra = null, $corFundo = null)
    {
        $this->code = substr(md5(microtime()), 0, 6);
        if ($width != null) {
            $this->width = $width;
        }
        if ($height != null) {
            $this->height = $height;
        }
        if ($padding != null) {
            $this->padding = $padding;
        }
        if ($corBarra != null) {
            $this->corBarra = $corBarra;
        }
        if ($corFundo != null) {
            $this->corFundo = $corFundo;
        }

        ob_implicit_flush();
    }

    // private functions

    private function calcularPosicao($step)
    {
        $bar = $this->width;
        $pixel = 0;
        $div = ($this->max - $this->min);

        if ($div > 0) {
            $pixel = round(($step - $this->min) * $bar / $div);
            if ($step <= $this->min) {
                $pixel = 0;
            }
            if ($step >= $this->max) {
                $pixel = $bar;
            }
        }

        $position['width'] = $pixel;
        $position['height'] = $this->height;
        return $position;
    }

    private function setNumPosicao($step)
    {
        if ($step > $this->max) {
            $step = $this->max;
        }
        if ($step < $this->min) {
            $step = $this->min;
        }
        $this->step = $step;
    }

    private function getStrHtml()
    {
        $html = '';
        $js = '';

        $this->setNumPosicao($this->step);
        $this->position = $this->calcularPosicao($this->step);

        $style_brd = '';
        $style_brd .= 'width:' . $this->width . 'px;';
        $style_brd .= 'height:' . $this->height . 'px;';
        $style_brd .= 'padding:' . $this->padding . 'px;';
        $style_brd .= 'background:' . $this->corFundo . ';';

        $style_bar = '';
        $style_bar .= 'width:' . $this->position['width'] . 'px;';
        $style_bar .= 'height:' . $this->position['height'] . 'px;';
        $style_bar .= 'background:' . $this->corBarra . ';';

        $style_lbl = 'width:' . $this->width . 'px;';

        //$html .= '<br /><br />';
        $html .= '<div id="infraBP' . $this->code . '" class="infraBarraProgresso">' . "\n";
        $html .= '<div id="infraBPRotulo' . $this->code . '" class="infraBarraProgressoRotulo" style="' . $style_lbl . '">' . $this->rotulo . '</div>' . "\n";
        $html .= '<div id="infraBPBorda' . $this->code . '" class="infraBarraProgressoBorda" style="' . $style_brd . '">' . "\n";
        $html .= '<div id="infraBPMiolo' . $this->code . '" class="infraBarraProgressoMiolo" style="' . $style_bar . '"></div>';
        $html .= '</div>' . "\n";
        $html .= '</div>' . "\n";

        $js .= 'function infraBPPosicao' . $this->code . '(item,pixel) {' . "\n";
        $js .= ' pixel = parseInt(pixel);' . "\n";
        $js .= ' switch(item) {' . "\n";
        $js .= '  case "width": document.getElementById("infraBPMiolo' . $this->code . '").style.width=(pixel) + \'px\'; break;' . "\n";
        $js .= '  case "height": document.getElementById("infraBPMiolo' . $this->code . '").style.height=(pixel) + \'px\'; break;' . "\n";
        $js .= ' }' . "\n";
        $js .= '}' . "\n";

        $js .= 'function infraBPSetRotulo' . $this->code . '(text) {' . "\n";
        $js .= ' name = "infraBPRotulo" + "' . $this->code . '";' . "\n";
        $js .= ' document.getElementById(name).innerHTML=text;' . "\n";
        $js .= '}' . "\n";


        $html .= $this->tagAbrirJS;
        $html .= $js;
        $html .= $this->tagFecharJS;

        return $html;
    }

    // public functions
    public function setStrRotulo($value)
    {
        $this->rotulo = $value;
        if ($this->status != 'new') {
            echo $this->tagAbrirJS;
            echo 'infraBPSetRotulo' . $this->code . '("' . $value . '");' . "\n";
            echo $this->tagFecharJS;
            $this->flush();
        }
    }

    public function exibir()
    {
        if ($this->max < $this->min) {
            throw new InfraException('Valor máximo da barra de progresso é menor que o mínimo.');
        }

        $this->status = 'show';
        echo $this->getStrHtml();
        $this->flush();
    }

    public function mover($step)
    {
        if ($step > $this->max || $step < $this->min) {
            throw new InfraException('Valor da barra de progresso fora dos limites máximo e mínimo.');
        }

        $last_step = $this->step;
        $this->setNumPosicao($step);

        $js = '';

        $new_position = $this->calcularPosicao($this->step);
        if ($new_position['width'] != $this->position['width']) {
            $js .= 'infraBPPosicao' . $this->code . '("width",' . $new_position['width'] . ');';
        }
        if ($new_position['height'] != $this->position['height']) {
            $js .= 'infraBPPosicao' . $this->code . '("height",' . $new_position['height'] . ');';
        }
        $this->position = $new_position;

        if ($js != '') {
            echo $this->tagAbrirJS;
            echo $js;
            echo $this->tagFecharJS;
            $this->flush();
        }
    }

    public function setNumMin($valor)
    {
        $this->min = $valor;
    }

    public function getNumMin()
    {
        return $this->min;
    }

    public function setNumMax($valor)
    {
        $this->max = $valor;
    }

    public function getNumMax()
    {
        return $this->max;
    }

    public function moverProximo()
    {
        if ($this->step < $this->max) {
            $this->mover($this->step + 1);
        }
    }

    public function moverInicio()
    {
        $this->mover($this->min);
    }

    function ocultar()
    {
        if ($this->status == 'show') {
            $this->status = 'hide';
            echo $this->tagAbrirJS;
            echo 'document.getElementById("infraBPBorda' . $this->code . '").style.visibility="hidden";document.getElementById("infraBPMiolo' . $this->code . '").style.visibility="hidden";';
            echo 'document.getElementById("infraBPRotulo' . $this->code . '").style.visibility="hidden";';
            echo $this->tagFecharJS;
            $this->flush();
        }
    }

    function reexibir()
    {
        if ($this->status == 'hide') {
            $this->status = 'show';
            echo $this->tagAbrirJS;
            echo 'document.getElementById("infraBPBorda' . $this->code . '").style.visibility="visible";document.getElementById("infraBPMiolo' . $this->code . '").style.visibility="visible";';
            echo 'document.getElementById("infraBPRotulo' . $this->code . '").style.visibility="visible";';
            echo $this->tagFecharJS;
            $this->flush();
        }
    }

    private function flush()
    {
        //para encher o buffer e fazer o flush
        //echo str_repeat(' ',4096);

        try {
            flush();
        } catch (Exception $e) {
        }

        try {
            ob_flush();
        } catch (Exception $e) {
        }

        usleep(10000);
    }
}
