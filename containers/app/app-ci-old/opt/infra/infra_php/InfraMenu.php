<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 
 * 28/06/2006 - criado por MGA
 *
 * @package infra_php
 */


abstract class InfraMenu {
 	
	public abstract function getStrSiglaSistema();
	
	public function __construct(){
	}
  
	public function montar($strNome){
		if (!isset($_SESSION['INFRA_MENU'])){
			throw new InfraException('Menu não foi carregado na sessão.');
		}
		
		if (!is_array($_SESSION['INFRA_MENU'])){
			throw new InfraException('Menu da sessão não é um array.');
		}
		
		if (!isset($_SESSION['INFRA_MENU'][$this->getStrSiglaSistema()])){
			throw new InfraException('Menu do sistema não foi carregado na sessão.');
		}
		
		if (!is_array($_SESSION['INFRA_MENU'][$this->getStrSiglaSistema()])){
			throw new InfraException('Menu do sistema na sessão não é um array.');
		}
		
		if (!isset($_SESSION['INFRA_MENU'][$this->getStrSiglaSistema()][$strNome])){
			throw new InfraException('Menu '.$strNome.' não foi carregado na sessão.');
		}

		if (!is_array($_SESSION['INFRA_MENU'][$this->getStrSiglaSistema()][$strNome])){
			throw new InfraException('Menu '.$strNome.' na sessão não é um array.');
		}
		
		return $this->montarMenu($_SESSION['INFRA_MENU'][$this->getStrSiglaSistema()][$strNome]);
	}
	
	//MENU BASEADO NO VETOR
	private function montarMenu($arrMenu) {
		$numLimite = InfraArray::contar($arrMenu);
		for ($i=0; $i<$numLimite; $i++) {	
			$strLinhaAtual = explode("^", $arrMenu[$i]);
			$strProximaLinha = explode("^", $arrMenu[$i+1]);
			//MONTA O LINK DE ACORDO COM O INÍCIO DA URL DO MENU
			if (substr($strLinhaAtual[1],0,4) == "java") {
				echo "<li><a href=\"".$strLinhaAtual[1]."\" title=\"".$strLinhaAtual[2]."\">";
			} else if ((substr($strLinhaAtual[1],0,4) == "http") || (substr($strLinhaAtual[1],0,4) == "mail")) {
				echo "<li><a href=\"".$strLinhaAtual[1]."\" title=\"".$strLinhaAtual[2]."\" target=\"_blank\">";
			} else {
				echo "<li><a href=\"".$this->strURL.$strLinhaAtual[1]."\" title=\"".$strLinhaAtual[2]."\">";
			}
			echo $strLinhaAtual[3]."</a>";
			if (strlen($strLinhaAtual[0]) == strlen($strProximaLinha[0])) {
				echo "</li>\n";
			}
			if (strlen($strLinhaAtual[0]) < strlen($strProximaLinha[0])) {
				echo "<ul>\n";
			}
			if (strlen($strLinhaAtual[0]) > strlen($strProximaLinha[0])) {
				echo "</li>\n";
				//CASO O NÍVEL POSTERIOR TENHA MAIS DE UM NÍVEL EM RELAÇÃO AO ATUAL (PODE SER UM for{})
				if ((strlen($strLinhaAtual[0]) - strlen($strProximaLinha[0])) == 2) {
					echo "</ul>\n</li>\n";
				}
				if ((strlen($strLinhaAtual[0]) - strlen($strProximaLinha[0])) == 3) {
					echo "</ul>\n</li>\n</ul>\n</li>\n";
				}
				if ((strlen($strLinhaAtual[0]) - strlen($strProximaLinha[0])) == 4) {
					echo "</ul>\n</li>\n</ul>\n</li>\n</ul>\n</li>\n";
				}
				//NÃO COLOCAR NO ÚLTIMO ITEM DO MENU
				if ($i < $numLimite-1) {
					echo "</ul>\n</li>";
				}
			}
		}
	}
} 
 
?>
