<?
  /*
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 11/04/2008 - criado por cle@trf4.gov.br
  * @package infra_php
  */

  abstract class InfraPaginaIntranet extends InfraPagina {
  private $numURLRandomica;
    
 	public abstract function getMenuSuperior();
 	public abstract function getLayoutIntranet();
 	public abstract function getStrURLSistema();
 	public function getDiretorioImagens() {}

  public function __construct() {
   	parent::__construct();
    $this->numURLRandomica = rand();
 	}
  
  public function getArquivoCssGlobal(){
    return 'infra-global-intranet.css';
  }

  public function getArquivoCssMenuGlobal() {
    return 'menu-global-intranet.css';
  }
  
  public function montarJavaScript() {
    parent::montarJavascript();
    echo "<script type=\"text/javascript\" charset=\"iso-8859-1\" src=\"".
			     parent::getDiretorioJavaScriptGlobal()."/InfraUtilIntranet.js?".$this->numURLRandomica."\"></script>";
  }
   
  function abrirBody($strAtributos="", $bolExibirMensagens=true) {
    $this->bolExibirMensagens = $bolExibirMensagens;
    echo "<body ";
 		if (!$this->esconderMenuAutomaticamente()){
 	    $strAtributos = $this->complementarAtributo($strAtributos,'onload','infraProcessarMouseDown();');
 		}
 		//Esconder combos mostrar menu somente IE
   	if ($this->getNumTipoBrowser() == parent::$TIPO_BROWSER_IE56) {
   	  $strAtributos = $this->complementarAtributo($strAtributos,'onload','infraProcessarMouseOver();');
 		}
    echo " ".$strAtributos.">\n";
		echo "<div class=\"infraAreaGlobal\" id=\"divInfraAreaGlobal\">\n";    
		if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA) {
      $this->montarBarraSuperior();
  		if ($this->getLayoutIntranet()) {
  			$this->montarAreaEsquerda($this->getMenuSuperiorInferior());
  		} else {
    		$this->montarAreaEsquerda($this->getMenuSuperior());
	  	}
	  	$this->montarAreaAbas();
	  	$arrAcoesBarraSistema = $this->getArrStrAcoesBarraSistema();
	  	if (is_array($arrAcoesBarraSistema)) {
	  		echo "<div style=\"float:right;\">";
	  		for ($i=0; $i<count($arrAcoesBarraSistema); $i++) {
	  			echo $arrAcoesBarraSistema[$i];
	  		}
	  		echo "&nbsp;</div>";
	  	}		
	  	echo "<div id=\"divInfraAreaTelaD\" class=\"infraAreaTelaD\">\n";
		}
  }
	
	function montarBarraSuperior() {
		echo "<div class=\"barraSuperior\">\n";
		echo "<img src=\"/infra_css/imagens_intranet/canto_sup_dir_menu_sup.gif\" align=\"right\" alt=\"Layout: canto superior direito cabeçalho\"/>";
		echo "</div>\n";
	}
		
	function montarAreaEsquerda($strCaminhoMenu) {
		echo "<div class=\"areaEsquerda\">\n";
		echo "<div class=\"logotipo\">\n";
		echo "<a href=\"".$this->getStrURLSistema()."\">";
		if ($this->getLayoutIntranet()) {
			$strImagemLogo = "/infra_css/imagens_intranet/logo_intranet.gif";
		} else {
			$strImagemLogo = $this->getDiretorioImagens()."logotipo_sistema.jpg";
		}
		echo "<img src=\"".$strImagemLogo."\" alt=\"Logotipo do Sistema\"/>";
		echo "</a></div>\n";
		echo "<div class=\"botoesZoom\">\n";
		echo "<a href=\"javascript:alterarTamanhoFonte('-');\">";
		echo "<img src=\"/infra_css/imagens_intranet/botao_diminuir_fonte_verde.gif\" align=\"right\" alt=\"Botão diminuir fonte\"/>";
		echo "</a>";
		echo "<a href=\"javascript:alterarTamanhoFonte('p');\">";
		echo "<img src=\"/infra_css/imagens_intranet/botao_fonte_padrao_verde.gif\" align=\"right\" alt=\"Botão fonte padrão\"/>";
		echo "</a>";
		echo "<a href=\"javascript:alterarTamanhoFonte('+');\">";
		echo "<img src=\"/infra_css/imagens_intranet/botao_aumentar_fonte_verde.gif\" align=\"right\" alt=\"Botão aumentar fonte\"/>";
		echo "</a>";
 		echo "<a href=\"menu_textual.php\">";
		echo "<img src=\"/infra_css/imagens_intranet/botao_menu_textual_verde.gif\" align=\"right\" alt=\"Menu textual\"/>";
		echo "</a>";
		echo "</div>\n";
		if (is_array($strCaminhoMenu)) {
			$arrMenu = $strCaminhoMenu[0];
		} else {
  		$arrMenu = file($strCaminhoMenu);
		}
		echo "<div class=\"menuSuperior\">\n";
		echo "<img src=\"/infra_css/imagens_intranet/canto_sup_dir_menu_sup.gif\" align=\"right\" alt=\"Layout: canto superior direito menu superior\"/>";
		echo '<div id="divInfraMenu" class="infraMenu">'."\n";
		echo parent::montarMenuArray($arrMenu);
    echo '</div>';		
		echo "<img src=\"/infra_css/imagens_intranet/canto_inf_esq_menu_sup.gif\" align=\"left\" alt=\"Layout: canto inferior esquerdo menu superior\"/>";
		echo "</div>\n";
		if (is_array($strCaminhoMenu)) {
			$arrMenu = $strCaminhoMenu[1];
  		echo "<div class=\"menuMeio\">\n";
	  	echo "<img src=\"/infra_css/imagens_intranet/canto_sup_esq_menu_meio.gif\" align=\"left\" alt=\"Layout: canto superior esquerdo menu central\"/>";
  		echo '<div id="divInfraMenu1" class="infraMenu">'."\n";
  		echo parent::montarMenuArray($arrMenu,1);
      echo '</div>';		
	  	echo "<img src=\"/infra_css/imagens_intranet/canto_inf_dir_menu_meio.gif\" align=\"right\" alt=\"Layout: canto inferior direito menu central\"/>";
	  	echo "</div>\n";
		}
		if (!is_array($strCaminhoMenu)) {
  		$this->montarMenuIntranet();
		}
		$this->montarMenuPortal();
		echo "</div>\n";
	}

	function montarAreaAbas() {
		echo "<div class=\"areaAbas\">";
		if ($this->getLayoutIntranet()) {
			echo "<a href=\"http://intranet.trf4.gov.br\"><img src=\"/infra_css/imagens_intranet/aba_destaque_trf4r.gif\" alt=\"Tribunal Regional Federal da 4ª Região\"/></a>";
      echo "<a href=\"http://intranet.trf4.gov.br\" target=\"_top\"><img src=\"/infra_css/imagens_intranet/aba_pequeno_trf4r.gif\" alt=\"Tribunal Regional Federal da 4ª Região\"/></a>";
			echo "<a href=\"http://intranet.jfrs.gov.br\" target=\"_top\"><img src=\"/infra_css/imagens_intranet/aba_pequeno_jfrs.gif\" alt=\"Justiça Federal - Seção Rio Grande do Sul\"/></a>";
			echo "<a href=\"http://intranet.jfsc.gov.br\" target=\"_top\"><img src=\"/infra_css/imagens_intranet/aba_pequeno_jfsc.gif\" alt=\"Justiça Federal - Seção Santa Catarina\"/></a>";
			echo "<a href=\"http://intranet.jfpr.gov.br\" target=\"_top\"><img src=\"/infra_css/imagens_intranet/aba_pequeno_jfpr.gif\" alt=\"Justiça Federal - Seção Paraná\"/></a>";
		} else {
      echo "<img src=\"".$this->getDiretorioImagens()."cabecalho_sistema.jpg\" alt=\"Cabeçalho do Sistema\"/>";			
		}
    echo "</div>\n";
	}

  public static function montarAreaAvisos($arrObjAvisoDTO) {
		echo "<div class=\"colunaAvisos\">";
		for ($i=0; $i < count($arrObjAvisoDTO); $i++) {
			$strLink = $arrObjAvisoDTO[$i]->getStrLink();
			if (($i % 2) == 0) {
				echo "<div class=\"caixaAvisoVerdeEscuro\">";
				echo "<img align=\"right\" alt=\"Layout: canto superior direito aviso\" src=\"/infra_css/imagens_intranet/canto_sup_dir_menu_sup.gif\"/>";
				echo "<div class=\"textoAvisos\">";
				if ($arrObjAvisoDTO[$i]->getStrTitulo() != " ") {
					echo "<strong style=\"color: rgb(255, 255, 255);\">".$arrObjAvisoDTO[$i]->getStrTitulo()."</strong><br/><br/>";
				}
				if ($strLink == "") {
					echo $arrObjAvisoDTO[$i]->getStrConteudo();
				} else {
					echo "<a target=\"_blank\" href=\"".$strLink."\">".$arrObjAvisoDTO[$i]->getStrConteudo()."</a>";
				}
				echo "</div>";
				echo "<img align=\"left\" alt=\"Layout: canto inferior esquerdo aviso\" src=\"/infra_css/imagens_intranet/canto_inf_esq_menu_sup.gif\"/></div>";
			} else {
				echo "<div class=\"caixaAvisoVerdeClaro\">";
				echo "<img align=\"left\" alt=\"Layout: canto superior esquerdo aviso\" src=\"/infra_css/imagens_intranet/canto_sup_esq_menu_meio.gif\"/>";
				echo "<div class=\"textoAvisos\">";
				if ($arrObjAvisoDTO[$i]->getStrTitulo() != " ") {
					echo "<strong style=\"color: rgb(255, 255, 255);\">".$arrObjAvisoDTO[$i]->getStrTitulo()."</strong><br/><br/>";
				}
				if ($strLink == "") {
					echo $arrObjAvisoDTO[$i]->getStrConteudo();
				} else {
					echo "<a target=\"_blank\" href=\"".$strLink."\">".$arrObjAvisoDTO[$i]->getStrConteudo()."</a>";
				}
				echo "</div>";
				echo "<img align=\"right\" alt=\"Layout: canto inferior direito aviso\" src=\"/infra_css/imagens_intranet/canto_inf_dir_menu_meio.gif\"/></div>";
			}
		}
		echo "</div>";
  }
  
  //MENU TEXTUAL BASEADO NO VETOR
	function montarMenuTextual($arrMenu) {
	  //print_r($arrMenu);die;
		$numLimite = count($arrMenu);
		for ($i=0; $i<$numLimite; $i++) {	
			$strLinhaAtual = explode("^", $arrMenu[$i]);
			for ($j=0; $j<strlen($strLinhaAtual[0]); $j++) {
			  echo "&nbsp;&nbsp;&nbsp;";
			}
			//MONTA O LINK DE ACORDO COM O INÍCIO DA URL DO MENU
			if ($strLinhaAtual[1] == "#") {
			  echo $strLinhaAtual[2]."<br/>";
			} else {
		  	if (substr($strLinhaAtual[1],0,4) == "java") {
		  		echo "<a href=\"".$strLinhaAtual[1]."\" title=\"".$strLinhaAtual[2]."\">";
	  		} else if ((substr($strLinhaAtual[1],0,4) == "http") || (substr($strLinhaAtual[1],0,4) == "mail")) {
	  			echo "<a href=\"".$strLinhaAtual[1]."\" title=\"".$strLinhaAtual[2]."\" target=\"_blank\">";
	  		} else {
	  			echo "<a href=\"".$strLinhaAtual[1]."\" title=\"".$strLinhaAtual[2]."\">";
	  		}
		  	echo $strLinhaAtual[2]."</a><br/>";
			}
		}
	}

	function montarMenuIntranet() {
		echo "<div class=\"menuInferior\">\n";
		echo "<img src=\"/infra_css/imagens_intranet/canto_sup_dir_menu_inferior.gif\" align=\"right\" alt=\"Layout: canto superior direito menu cinza\"/>";		
		echo "<p style=\"font-weight:bold;\">Intranet</p><p style=\"height:30px;text-align:center;padding-top:2px;\">";
		echo "<a href=\"http://intranet.trf4.gov.br\" target=\"_blank\">";
		echo "<img src=\"/infra_css/imagens_intranet/icone_intra_trf4r.gif\" alt=\"Intranet do Tribunal Regional Federal da 4ª Região\"/></a>&nbsp;";
		echo "<a href=\"http://intranet.jfrs.gov.br\" target=\"_blank\">";
		echo "<img src=\"/infra_css/imagens_intranet/icone_intra_jfrs.gif\" alt=\"Intranet da Justiça Federal do Rio Grande do Sul\"/></a>&nbsp;";
		echo "<a href=\"http://intranet.jfsc.gov.br\" target=\"_blank\">";
		echo "<img src=\"/infra_css/imagens_intranet/icone_intra_jfsc.gif\" alt=\"Intranet da Justiça Federal de Santa Catarina\"/></a>&nbsp;";
		echo "<a href=\"http://intranet.jfpr.gov.br\" target=\"_blank\">";
		echo "<img src=\"/infra_css/imagens_intranet/icone_intra_jfpr.gif\" alt=\"Intranet da Justiça Federal do Paraná\"/></a>";
		echo "</p>";
		echo "<img src=\"/infra_css/imagens_intranet/canto_inf_esq_menu_inferior.gif\" align=\"left\" alt=\"Layout: canto inferior esquerdo menu cinza\"/>";
		echo "</div>\n";
	}
	
	function montarMenuPortal() {
		echo "<div class=\"menuInferior\">\n";
		echo "<img src=\"/infra_css/imagens_intranet/canto_sup_dir_menu_inferior.gif\" align=\"right\" alt=\"Layout: canto superior direito menu cinza\"/>";		
		echo "<p style=\"font-weight:bold;\">Portal</p><p style=\"height:30px;text-align:center;padding-top:2px;\">";
		echo "<a href=\"http://www.trf4.gov.br\" target=\"_blank\">";
		echo "<img src=\"/infra_css/imagens_intranet/icone_intra_trf4r.gif\" alt=\"Site do Tribunal Regional Federal da 4ª Região\"/></a>&nbsp;";
		echo "<a href=\"http://www.jfrs.gov.br\" target=\"_blank\">";
		echo "<img src=\"/infra_css/imagens_intranet/icone_intra_jfrs.gif\" alt=\"Site da Justiça Federal do Rio Grande do Sul\"/></a>&nbsp;";
		echo "<a href=\"http://www.jfsc.gov.br\" target=\"_blank\">";
		echo "<img src=\"/infra_css/imagens_intranet/icone_intra_jfsc.gif\" alt=\"Site da Justiça Federal de Santa Catarina\"/></a>&nbsp;";
		echo "<a href=\"http://www.jfpr.gov.br\" target=\"_blank\">";
		echo "<img src=\"/infra_css/imagens_intranet/icone_intra_jfpr.gif\" alt=\"Site da Justiça Federal do Paraná\"/></a>";
		echo "</p>";
		echo "<img src=\"/infra_css/imagens_intranet/canto_inf_esq_menu_inferior.gif\" align=\"left\" alt=\"Layout: canto inferior esquerdo menu cinza\"/>";
		echo "</div>\n";
	}
			
  function fecharBody($mostrarEndereco=0) {
    echo "</div>\n";
    if ($this->getTipoPagina() == self::$TIPO_PAGINA_COMPLETA){
    	echo "<div class=\"barraInferior\">\n";
  		echo "<img src=\"/infra_css/imagens_intranet/canto_esq_rodape.gif\" align=\"left\" alt=\"Layout: canto superior esquerdo rodapé\"/>";
  		echo "<img src=\"/infra_css/imagens_intranet/canto_dir_rodape.gif\" align=\"right\" alt=\"Layout: canto inferior direito rodapé\"/>";
  		if ($mostrarEndereco) {
  			echo "Rua Otávio Francisco Caruso da Rocha, 300 - Bairro Praia de Belas ";
  			echo "- CEP 90010-395 - Porto Alegre (RS) - PABX (51) 3213 3000";
  		}
  		echo "</div>\n";
    }
		echo "</div>\n";
		if ($_COOKIE["intTamanhoFonte"] != "") {
			$numTamanhoFonte = $_COOKIE['intTamanhoFonte'];
		} else {
		  $numTamanhoFonte = 0;
		}
		echo "<script type=\"text/javascript\" charset=\"iso-8859-1\">".
		     "document.getElementById(\"divInfraAreaGlobal\").style.fontSize = tamanhos[".
				 $numTamanhoFonte."];contador = ".$numTamanhoFonte.";</script>";
	  echo '<div id="infraDivImpressao" class="infraImpressao"></div>'."\n";      
    if ($this->bolExibirMensagens) {
      $strAlert = '';
      if (isset($_GET['msg'])) {
        if ($_GET['msg']!='') {
          $strAlert .= 'alert(\''.str_replace('\&quot;','"',InfraString::formatarJavaScript($_GET['msg'])).'\');';
        }
      }
      if ($this->getStrMensagens()!='') {
        $strAlert .= 'alert(\''.str_replace('\&quot;','"',InfraString::formatarJavaScript($this->getStrMensagens())).'\');';
      }
      if ($strAlert != '') {
        $this->abrirJavaScript();
        echo $strAlert;
        $this->fecharJavascript();
      }
    }
		echo "</body>\n";
		
    if ($this->getObjInfraSessao() != null){
      $this->adicionarSessao('infra_global', self::$POS_SESSAO_MSG, '');
    }
	}
	
	public function montarLinkAdministrar($strLink) {
	  $str = "<a href=\"".$strLink."\" title=\"Administração do Sistema\" tabindex=\"".
	         parent::getProxTabBarraSistema()."\"><img src=\"/infra_css/imagens_intranet/administrar.gif\" ".
	         "alt=\"Administração do Sistema\" class=\"infraImg\" /></a>";
	  return $str;
	}
	
	public function montarLinkSair($strLink = null, $strIcone = null) {
	  $str = "<a href=\"".$strLink."\" title=\"Sair do Sistema\" tabindex=\"".
	         parent::getProxTabBarraSistema()."\"><img src=\"/infra_css/imagens_intranet/sair.gif\" ".
	         "alt=\"Sair do Sistema\" class=\"infraImg\" /></a>";
	  return $str;
	}

}
?>