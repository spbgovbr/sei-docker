<?
	/**
	 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
	 * 30/05/2016 - CRIADO POR cle@trf4.jus.br
	 * 11/10/2016 - ATUALIZADO POR bio@trf4.jus.br
	 * Documentação da API: https://www.zabbix.com/documentation/2.4/manual/api
	 * @package infra_php
	 */

	require_once 'phpzabbix/ZabbixApi.class.php';
	use ZabbixApi\ZabbixApi;

	abstract class InfraZabbix implements InfraIMonitoramento {

		private $objZabbixApi = '';

		public function __construct() {
			$this->abrirConexao();
		}

		private function abrirConexao() {
			$this->objZabbixApi = new ZabbixApi($this->getServidor(), $this->getUsuario(), $this->getSenha());
		}

		public function listarHosts($strNome='', $strIdGrupo='', $strOrdem='', $strLimite='', $strIdsHost='', $bolSomenteAtivos=false, $bolBuscarAtributos=false) {
			$arrCriterios = $this->montarArrayCriterios('item', $strOrdem, $strLimite, $strNome, $strIdGrupo, '', '', $strIdsHost, '', $bolSomenteAtivos, $bolBuscarAtributos);
			$arrResultado = $this->objZabbixApi->hostGet($arrCriterios);
			return $this->montarArrayResultadoFinal($arrResultado);
		}

		public function listarGrupos($strNome='', $strIdGrupo='', $strOrdem='', $strLimite='', $bolBuscarHosts=false) {
			$arrCriterios = $this->montarArrayCriterios('grupo', $strOrdem, $strLimite, $strNome, $strIdGrupo, '', '', '', '', '', $bolBuscarHosts);
			$arrResultado = $this->objZabbixApi->hostgroupGet($arrCriterios);
			return $this->montarArrayResultadoFinal($arrResultado);
		}

		public function listarAlertas($strMensagem='', $strDestinatario='', $strOrdem='', $strLimite='') {
			$arrCriterios = $this->montarArrayCriterios('alerta', $strOrdem, $strLimite, '', '', $strMensagem, $strDestinatario);
			$arrResultado = $this->objZabbixApi->alertGet($arrCriterios);
			return $this->montarArrayResultadoFinal($arrResultado);
		}

		public function listarAtributos($strNome='', $strApplicationName='', $strOrdem='', $strLimite='', $bolSomenteAtivos=false, $bolSomenteAssociadosTemplate=false, $bolBuscarApplications=false) {
			$arrCriterios = $this->montarArrayCriterios('atributo', $strOrdem, $strLimite, $strNome, '', '', '', '', $strApplicationName, $bolSomenteAtivos, $bolBuscarApplications, $bolSomenteAssociadosTemplate);
			$arrResultado = $this->objZabbixApi->itemGet($arrCriterios);
			return $this->montarArrayResultadoFinal($arrResultado);
		}

		public function listarPrototipos($strNome='', $strApplicationName='', $strOrdem='', $strLimite='', $bolSomenteAtivos=false, $bolSomenteAssociadosTemplate=false, $bolBuscarApplications=false) {
			$arrCriterios = $this->montarArrayCriterios('atributo', $strOrdem, $strLimite, $strNome, '', '', '', '', $strApplicationName, $bolSomenteAtivos, $bolBuscarApplications, $bolSomenteAssociadosTemplate);
			$arrResultado = $this->objZabbixApi->itemprototypeGet($arrCriterios);
			return $this->montarArrayResultadoFinal($arrResultado);
		}

		public function listarTemplates($strNome='', $strIdGrupo='', $strOrdem='', $strLimite='', $bolBuscarHosts=false) {
			$arrCriterios = $this->montarArrayCriterios('template', $strOrdem, $strLimite, $strNome, $strIdGrupo, '', '', '', '', '', $bolBuscarHosts);
			$arrResultado = $this->objZabbixApi->templateGet($arrCriterios);
			return $this->montarArrayResultadoFinal($arrResultado);
		}

		private function montarArrayCriterios($strTipo, $strOrdem='', $strLimite='', $strNome='', $strIdGrupo='', $strMensagem='', $strDestinatario='', $strIdsHost='', $strApplicationName='', $bolSomenteAtivos=false, $bolBuscarRelacionados=false, $bolSomenteAssociadosTemplate=false) {
			$arrCriterios = array();
			switch($strTipo) {
				case 'item':
					$arrCriterios['output'] = array('hostid', 'host', 'name', 'status', 'items');
					if ($strOrdem != '') {
						$arrCriterios['sortfield'] = 'name';
						$arrCriterios['sortorder'] = $strOrdem;
					}
					if ($bolSomenteAtivos) {
						$arrCriterios['status'] = '0';
					}
					if ($bolBuscarRelacionados) {
						$arrCriterios['selectItems'] = array("key_", "lastvalue", "flags");
						$arrCriterios['output'][] = 'items';
					}
					break;
				case 'grupo':
					$arrCriterios['output'] = array('groupid', 'name');
					if ($strOrdem != '') {
						$arrCriterios['sortfield'] = 'name';
						$arrCriterios['sortorder'] = $strOrdem;
					}
					if ($bolBuscarRelacionados) {
						$arrCriterios['selectHosts'] = 'hostid';
						$arrCriterios['output'][] = 'hosts';
					}
					break;
				case 'alerta':
					$arrCriterios['output'] = array('alertid', 'status', 'message');
					if ($strOrdem != '') {
						$arrCriterios['sortfield'] = 'alertid';
						$arrCriterios['sortorder'] = $strOrdem;
					}
					break;
				case 'atributo':
					$arrCriterios['output'] = array('key_', 'name', 'units', 'status');
					if ($strOrdem != '') {
						$arrCriterios['sortfield'] = 'name';
						$arrCriterios['sortorder'] = $strOrdem;
					}
					if ($bolSomenteAtivos) {
						$arrCriterios['status'] = '0';
					}
					if ($bolBuscarRelacionados) {
						$arrCriterios['selectApplications'] = array("applicationid", "name");
						$arrCriterios['output'][] = 'aaplications';
					}
					if ($bolSomenteAssociadosTemplate) {
						$arrCriterios['templated'] = true;
					}
					break;
				case 'template':
					$arrCriterios['output'] = array('templateid', 'name');
					if ($strOrdem != '') {
						$arrCriterios['sortfield'] = 'name';
						$arrCriterios['sortorder'] = $strOrdem;
					}
					if ($bolBuscarRelacionados) {
						$arrCriterios['selectHosts'] = 'hostid';
						$arrCriterios['output'][] = 'hosts';
					}
					break;
			}

			if ($strLimite != '') {
				$arrCriterios['limit'] = $strLimite;
			}
			if ($strNome != '') {
				$arrCriterios['search'] = array('name'=>$strNome);
			}
			if ($strIdGrupo != '') {
				$arrCriterios['groupids'] = $strIdGrupo;
			}
			if ($strMensagem != '') {
				$arrCriterios['search'] = array('message'=>$strMensagem);
			}
			if ($strDestinatario != '') {
				$arrCriterios['sendto'] = $strDestinatario;
			}
			if ($strIdsHost != '') {
				$arrCriterios['hostids'] = $strIdsHost;
			}
			if ($strApplicationName != '') {
				$arrCriterios['application'] = $strApplicationName;
			}

			return $arrCriterios;
		}

		private function montarArrayResultadoFinal($arrResultado) {
			$arrResultadoFinal = array();

			if (count($arrResultado) > 0) {
				for ($i=0; $i<count($arrResultado); $i++) {

					if (isset($arrResultado[$i]->hostid)) {
						$arrResultadoFinal[$i]['id_item'] = $arrResultado[$i]->hostid;
					}

					if (isset($arrResultado[$i]->itemid)) {
						$arrResultadoFinal[$i]['id_atributo'] = $arrResultado[$i]->itemid;
					}

					if (isset($arrResultado[$i]->key_)) {
						$arrResultadoFinal[$i]['chave_atributo'] = $arrResultado[$i]->key_;
					}

					if (isset($arrResultado[$i]->groupid)) {
						$arrResultadoFinal[$i]['id_grupo'] = $arrResultado[$i]->groupid;
					}

					if (isset($arrResultado[$i]->templateid)) {
						$arrResultadoFinal[$i]['id_template'] = $arrResultado[$i]->templateid;
					}

					if (isset($arrResultado[$i]->host)) {
						$arrResultadoFinal[$i]['item'] = $arrResultado[$i]->host;
					}

					if (isset($arrResultado[$i]->name)) {
						$arrResultadoFinal[$i]['nome'] = $arrResultado[$i]->name;
					}

					if (isset($arrResultado[$i]->units)) {
						$arrResultadoFinal[$i]['unidade'] = $arrResultado[$i]->units;
					}

					if (isset($arrResultado[$i]->status)) {
						$arrResultadoFinal[$i]['situacao'] = $arrResultado[$i]->status;
					}

					if (isset($arrResultado[$i]->message)) {
						$arrResultadoFinal[$i]['mensagem'] = $arrResultado[$i]->message;
					}

					if (isset($arrResultado[$i]->hosts) && (count($arrResultado[$i]->hosts)>0)) {
						$arrHosts = array();
						foreach ($arrResultado[$i]->hosts as $objHost) {
							$arrHosts[] = $objHost->hostid;
						}
						$arrResultadoFinal[$i]['items'] = $arrHosts;
					}

					if (isset($arrResultado[$i]->items) && (count($arrResultado[$i]->items)>0)) {
						$arrAtributos = array();
						$arrSinDescoberto = array('0' => 'N', '4' => 'S');
						foreach ($arrResultado[$i]->items as $objItem) {
							$arrAtributos[] = array('chave_atributo' => $objItem->key_,
									'valor' => $objItem->lastvalue,
									'sin_descoberto' => $arrSinDescoberto[$objItem->flags]);
						}
						$arrResultadoFinal[$i]['atributos'] = $arrAtributos;
					}

					if (isset($arrResultado[$i]->applications) && (count($arrResultado[$i]->applications)>0)) {
						$arrApplications = array();
						foreach ($arrResultado[$i]->applications as $objApplication) {
							$arrApplications[] = array('id_application' => $objApplication->applicationid,
									'nome' => $objApplication->name);
						}
						$arrResultadoFinal[$i]['applications'] = $arrApplications;
					}

				}
			}

			return $arrResultadoFinal;
		}

		public function __destruct() {
			if ($this->objZabbixApi) {
				$this->objZabbixApi->userLogout();
			}
		}

	}
?>