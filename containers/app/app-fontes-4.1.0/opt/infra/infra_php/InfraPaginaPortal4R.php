<?php
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
* 13/08/2012 - criado por cle@trf4.gov.br
* @package infra_php
*/

abstract class InfraPaginaPortal4R extends InfraPagina
{

    public function __construct()
    {
        parent::__construct();
        $this->numURLRandomica = rand();
    }

    public function getArrStrAcoesBarraSistema()
    {
        return null;
    }

    public function getArquivoCssGlobal()
    {
        return 'infra-global-portal4r.css';
    }

    public function montarStyle()
    {
        parent::montarStyle();
        if ($this->getNumTipoBrowser() == self::$TIPO_BROWSER_IE56 || $this->getNumTipoBrowser(
            ) == self::$TIPO_BROWSER_IE7 || $this->getNumTipoBrowser() == self::$TIPO_BROWSER_IE8) {
            echo '<link href="' . $this->getDiretorioCssLocal() . '/infra-ie5678.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        }
        if (self::isBolMobile()) {
            echo '<link href="' . $this->getDiretorioCssGlobal(
                ) . '/infra-global-portal4r-mobile.css?' . $this->getNumVersao(
                ) . '" rel="stylesheet" type="text/css" media="all" />' . "\n";
        }
    }

    public function montarJavaScript()
    {
        parent::montarJavascript();
        echo '<script type="text/javascript" charset="iso-8859-1" src="' . $this->getDiretorioJavaScriptGlobal(
            ) . '/InfraUtilPaginaPortal4R.js?' . $this->numURLRandomica . '\"></script>' . "\n";
    }

    public function abrirBody($strAtributos = "", $bolExibirMensagens = true)
    {
        //$this->bolExibirMensagens = $bolExibirMensagens;
        echo "<body ";
        echo " " . $strAtributos . ">\n";
        echo "<div class=\"areaGlobalTopo\" id=\"divAreaGlobalTopo\">";
        $this->montarBarraSuperior();
        echo '</div><!--divAreaGlobalTopo-->';
        echo "<div class=\"areaGlobalConteudo\" id=\"divAreaGlobalConteudo\">";
        $this->montarBotoes();
    }

    /*public static function montarBarraSuperior() {
	  	echo '<div class="linkOrgaoTopo"><a href="'.InfraPaginaPortal4R::$strURL.'trf4/controlador.php?acao=principal"><img src="'.InfraPaginaPortal4R::$strURL.'trf4/imagens/link_TRF4.jpg" border="0" /></a></div>'.
    	  	 '<div class="linkOrgaoTopo"><a href="http://www.jfrs.jus.br" target="_blank"><img src="'.InfraPaginaPortal4R::$strURL.'trf4/imagens/link_JFRS.jpg" border="0" /></a></div>'.
	  	     '<div class="linkOrgaoTopo"><a href="http://www.jfsc.jus.br" target="_blank"><img src="'.InfraPaginaPortal4R::$strURL.'trf4/imagens/link_JFSC.jpg" border="0" /></a></div>'.
	  	     '<div class="linkOrgaoTopo"><a href="http://www.jfpr.jus.br" target="_blank"><img src="'.InfraPaginaPortal4R::$strURL.'trf4/imagens/link_JFPR.jpg" border="0" /></a></div>'.
	  	     '<div class="bannerTopo" id="divBannerTopo"><img src="'.InfraPaginaPortal4R::$strURL.'trf4/imagens/barra_superior.jpg" /></div>';
	  }
	  
	  
  	public static function montarBarraSuperior() {
	  	echo '<div class="linkOrgaoTopo"><a href="'.ConfiguracaoPortal4R::getInstance()->getValor('Portal4R','URL').'trf4/controlador.php?acao=principal"><img src="'.ConfiguracaoPortal4R::getInstance()->getValor('Portal4R','URL').'trf4/imagens/link_TRF4.jpg" border="0" /></a></div>'.
    	  	 '<div class="linkOrgaoTopo"><a href="http://www.jfrs.jus.br" target="_blank"><img src="'.ConfiguracaoPortal4R::getInstance()->getValor('Portal4R','URL').'trf4/imagens/link_JFRS.jpg" border="0" /></a></div>'.
	  	     '<div class="linkOrgaoTopo"><a href="http://www.jfsc.jus.br" target="_blank"><img src="'.ConfiguracaoPortal4R::getInstance()->getValor('Portal4R','URL').'trf4/imagens/link_JFSC.jpg" border="0" /></a></div>'.
	  	     '<div class="linkOrgaoTopo"><a href="http://www.jfpr.jus.br" target="_blank"><img src="'.ConfiguracaoPortal4R::getInstance()->getValor('Portal4R','URL').'trf4/imagens/link_JFPR.jpg" border="0" /></a></div>'.
	  	     '<div class="linkOrgaoTopo" style="position:relative;left:40%;margin-top:5px;">
	  	     		<form id="frmPesquisaPortal" name="frmPesquisaPortal" method="post" action="controlador.php?acao=pesquisar_portal">
	  	     		<input type="text" id="q" name="q" value="'.str_replace('\\','',str_replace('"','&quot;',$_POST['q'])).'" style="width:200px;border:1px solid black" />
	  	     		<input id="partialfields" name="partialfields" type="hidden" value="" />
	  	     		<button type="submit" name="" value="">Pesquisar</button>
	  	     		<a href="ajuda/ajuda_solr.html" target="_blank" title="Ajuda para Pesquisa"><img src="imagens/interrogacao1.jpg" /></a>
	  	     		</form>
	  	     	</div>'.
	  	     '<div class="bannerTopo" id="divBannerTopo"><img src="'.ConfiguracaoPortal4R::getInstance()->getValor('Portal4R','URL').'trf4/imagens/barra_superior.jpg" /></div>';
	  }
	  
	  public static function montarBotoes() {
	    echo '<div id="divBotoesMenu">'.
	  	     '<div class="botoesAcessibilidade" id="divBotoesAcessibilidade">'.
	  	     '<div class="controleFonte" style="border:1px solid #2D2D2D;border-radius:5px;width:22px;text-align:center;float:left;margin-right:2px;margin-top:1px;"><a href="javascript:window.location.href=\''.InfraPaginaPortal4R::$strURL.'trf4/controlador.php?acao=menu_textual_visualizar\';">M</a></div>'.
	  	     '<div class="controleFonte" style="border:1px solid #2D2D2D;border-radius:5px;width:22px;text-align:center;float:left;margin-right:2px;margin-top:1px;"><a href="javascript:alterarTamanhoFonte(\'+\');">A+</a></div>'.
	  	     '<div class="controleFonte" style="border:1px solid #2D2D2D;border-radius:5px;width:22px;text-align:center;float:left;margin-right:2px;margin-top:1px;"><a href="javascript:alterarTamanhoFonte(\'p\');">A</a></div>'.
	  	     '<div class="controleFonte" style="border:1px solid #2D2D2D;border-radius:5px;width:22px;text-align:center;float:left;margin-top:1px;"><a href="javascript:alterarTamanhoFonte(\'-\');">A-</a></div>'.
	  	     '</div>';
 		  include(InfraPaginaPortal4R::$strCaminhoFisico.'trf4/infra/menu.php');
		  echo '</div>';
	  }*/

    public function fecharBody()
    {
        echo '<div style="clear:both;color:#78a483;font-size:10px;font-weight:bold;text-align:center;width:100%;">' .
            'Rua Otávio Francisco Caruso da Rocha, 300 - Bairro Praia de Belas - CEP 90010-395 - Porto Alegre (RS) - ' .
            'PABX (51) 3213 3000<br /><br />' .
            '<span style="color:#000;font-weight:bold;">Horário de atendimento ao público: das 13h às 18h</span>' .
            '<br /><br />' .
            '<a href="' . ConfiguracaoPortal4R::getInstance()->getValor(
                'Portal4R',
                'URL'
            ) . 'trf4/controlador.php?acao=pagina_visualizar&id_pagina=937"><img src="' . ConfiguracaoPortal4R::getInstance(
            )->getValor(
                'Portal4R',
                'URL'
            ) . 'trf4/imagens/icone_enderecos_telefones.jpg" alt="Endereços e Telefones" /></a>';

        if (!self::isBolMobile()) {
            echo '<br /><br /><hr /></div>';
            include '/var/www/html/trf4/infra/rodape.php';
        } else {
            echo '</div>';
        }

        echo '<div style="clear:both;"><br /><br /></div>' .
            '</div><!--divAreaGlobalConteudo-->';

        if ($this->getBolExibirMensagens()) {
            $strAlert = '';
            if (isset($_GET['msg'])) {
                if ($_GET['msg'] != '') {
                    $strTemp = $_GET['msg'];
                    $strTemp = str_replace("'", '\\\\\\\'', $strTemp);
                    $strTemp = str_replace('\\n', '\\\\n', $strTemp);
                    $strTemp = str_replace('\\\\\\n', '\\\\n', $strTemp);
                    $strAlert .= 'self.setTimeout(\'alert(\\\'' . $strTemp . '\\\')\',100);';
                }
            }
            $strMensagens = $this->getStrMensagens();
            if ($strMensagens != '') {
                $strTemp = $strMensagens;
                $strTemp = str_replace("'", '\\\\\\\'', $strTemp);
                $strTemp = str_replace('\\n', '\\\\n', $strTemp);
                $strTemp = str_replace('\\\\\\n', '\\\\n', $strTemp);
                $strAlert .= 'self.setTimeout(\'alert(\\\'' . $strTemp . '\\\')\',100);';
            }
            if ($strAlert != '') {
                $this->abrirJavaScript();
                echo $strAlert;
                $this->fecharJavascript();
            }
        }
        echo '</body>';
    }

    public static function isBolMobile()
    {
        $strUserAgent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match(
                '/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',
                $strUserAgent
            ) ||
            preg_match(
                '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
                substr($strUserAgent, 0, 4)
            )) {
            if ($_GET['bolTI']) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}

