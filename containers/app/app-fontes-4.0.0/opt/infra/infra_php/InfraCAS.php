<?php
/**
 * Description of CAStorfacade
 *
 * Criado em 26/01/2011
 *
 * @author Leandro Oliveira
 * 	Splenda Consulting
 */
abstract class InfraCAS {
	private $host;
	private $port;
	private $lifepoint;
	private $autodelete;

	function __construct($_host, $_port) {
		$this->host=$_host;
		$this->port=$_port;
		$this->autodelete = FALSE;
		$this->lifepoint = array(array(365,3,FALSE),array(10950,2,TRUE));
	}

	public abstract function getServidor();

	public abstract function getPorta();

	public function setHost($_host ){
		$this->host = $_host;
	}
	
	public function getHost( ){
		return $this->host;
	}
	
	public function setPort($_port){
		$this->port = $_port;
	}
	
	public function getPort(){
		return $this->port;
	}

	public function setLifepoint($_lifepoint){
		$this->lifepoint = $_lifepoint;
	}
	
	public function getLifepoint(){
		return $this->lifepoint;
	}

	public function setAutodelete($_autodelete){
		$this->autodelete = $_autodelete;
	}
	
	public function getAutodelete(){
		return $this->autodelete;
	}	
	
	function lifepoint($dias, $reps, $delete) {
		$array = array($dias, $reps, $delete);
		$this->lifepoint[] = $array;
	}

	function lifepoint_clear() {
		$this->lifepoint = array();
	}

	public function write($conteudo, $anchor, $metadados = array()) {
		return $this->_write($conteudo, $anchor, "", $metadados);
	}

	public function delete($uuid) {
		$this->_request($this->_newRequest("DELETE", $uuid), "", array(200));
	}

	public function read($uuid, $modo= "default") {
		return $this->_read($uuid, $modo);
	}

	public function info($uuid) {
		$i = 0;
		$array = array();
		foreach (explode("\n", $this->_request($this->_newRequest("HEAD", $uuid), "", array(200))) as $value) {
			if (strlen(trim($value)) != 0) {
				$array[$i++] = $value;
			}
		}

		unset($array[count($array) - 1]);
		unset($array[0]);
		return $array;
	}

	public function status() {
		$response = $this->_request($this->_newRequest("GET", ""),"", array(200));
		return substr($response, strpos($response, "\r\n\r\n"));
	}

	public function writeFromFile($nomeDoArquivo,$anchor, $metadados=array()) {
		return $this->_write("", $anchor, $nomeDoArquivo, $metadados);
	}

	public function readToFile($nomeDoArquivo, $uuid, $modo = "default") {
		return $this->_read($uuid, $modo, $nomeDoArquivo);
	}

	private function _read($uuid, $modo, $file="") {
		$request = $this->_newRequest(
		"GET", $uuid . (
		strcmp($modo, "default") == 0 ? "" : "?" . $modo . "=yes"
		));

		$response = strcmp($file, "") == 0 ?
		$this->_request($request, "", array(206, 200)) :
		$this->_request($request, "", array(206, 200), $file, false);

		$response = substr($response, strpos($response,  "\x0D\x0A\x0D\x0A")+4);
		return $response;
	}

	private function _request($headers, $content, $codes, $file="", $write=true) {
		//5 segundos de timeout de conexao 
		$castor = fsockopen($this->host, $this->port, $errno, $errstr, 5);
		
	  if($castor == false){
      $responseCode = '999';
      throw new InfraException($responseCode);
    }
    		
		//5 segundos de timeout de leitura
		stream_set_timeout($castor, 5);
		$writeTofile = null;
		$debug = _CAStor_Facade_isDebugEnabled();
		$debugContent = $content;
		$debugHeaders = '';
		$result = '';

		preg_match('@^(\w+).*@i', $headers, $matches);
		$verb = $matches[1];
		$post = strcmp(strtolower($verb), "post") == 0;

		if ($post) {
			$headers .= "Expect: 100-continue\r\n";
		}

		fwrite($castor, $headers . "\r\n");
		
		$status = socket_get_status($castor); 

		if ($post) {
			while (!feof($castor) && !$status['timed_out']) {
				$line = fgets($castor/* , defined('CASTOR_FACADE_BUFFER') ? CASTOR_FACADE_BUFFER : 128 */);
				$result .= $line;

				if ($debug) {
					$debugHeaders.=$line;
				}

				if (strcmp("\r\n", $line) == 0) {
					break;
				}
				$status = socket_get_status($castor); 
			}

			$responseCode = trim(substr($result, 9, 3));
			if ($status['timed_out']) {
				$responseCode = '999';
			}
			//print "<BR><PRE>" .  serialize($result) ."</PRE><HR>";
			//flush();
			if (strcmp("301", $responseCode) == 0) {
				return $this->_reRequest($headers, $content, $codes, $result, $file, $write);
			} elseif (strcmp("100", $responseCode) != 0) {
				throw new InfraException($responseCode);
			}

			if (strcmp($file, "") == 0) {
				fwrite($castor, $content);
				$status = socket_get_status($castor);
				if ($status['timed_out']) {
					$responseCode = '999';
				}
			} else if ($write) {
				$file = fopen($file, 'r');

				while (!feof($file) && !$status['timed_out']) {
					$line = fgets($file, 4096);
					//$line = fgets($file, 128);
					fwrite($castor, $line);
					$status = socket_get_status($castor);
					if ($debug) {
						$debugContent.=$line;
					}
				}
				fclose($file);
				if ($status['timed_out']) {
					$responseCode = '999';
				}
			}

			if ($debug) {
				echo str_replace("\r\n", '<br/>', "<p style='border:solid 1px'>SEND<hr/>" . $debugContent . "</p>");
			}
		}

		if (!$write) {
			fwrite($castor, $content);
			$status = socket_get_status($castor);
			if ($status['timed_out']) {
				$responseCode = '999';
			}
			$writeTofile = fopen($file, 'w');
		}
		if (strcmp("3", $responseCode) != 0) {
			$result = '';
			$writing = $writeTofile != null;
			$writingContent = false;
			$debugContent = '';
			$tam = 99999999999999999;
			$lidos = 0;
			$status = socket_get_status($castor);
			while ( !$status['timed_out'] && $tam > $lidos && !feof($castor)) {
				if ($writingContent) {
					$tamLeitura = 0;
					if ($tam - $lidos >= 4096) {
						$tamLeitura = 4096;
					} else {
						$tamLeitura = $tam - $lidos;
					}
					$line = fread($castor, $tamLeitura);
					$lidos += strlen($line);
				} else {
					$line = fgets($castor/* , defined('CASTOR_FACADE_BUFFER') ? CASTOR_FACADE_BUFFER : 128 */);
				}				
				if (!$writingContent && strpos(strtolower($line), strtolower('Content-Length:')) === 0) {
					$tam = intval(substr($line, 15));
				}				
				$result .= $line;
				if ($debug) {
					$debugContent.=$line;
				}
				if ($writingContent && $writing) {
					fwrite($writeTofile, $line);
				} else
				if (strcmp("\r\n", $line) == 0) {
					$writingContent = true;
				}
				$status = socket_get_status($castor);
			}
	
	
			fclose($castor);
			if ($writing) {
				fclose($writeTofile);
			}
	
			if ($debug) {
				echo str_replace("\r\n", '<br/>', "<p style='border:solid 1px'>" . $debugContent . "</p>");
			}
		}
		
		$responseCode = trim(substr($result, 9, 3));
		if ($status['timed_out']) {
			$responseCode = '999';
		}

		if (strcmp("301", $responseCode) == 0) {
			return $this->_reRequest($headers, $content, $codes, $result, $file, $write);
		} else if (strcmp("307", $responseCode) == 0) {
			$localHost = $this->host;
			$localPort = $this->port;
			$return = $this->_reRequest($headers, $content, $codes, $result, $file, $write);

			$this->host = $localHost;
			$this->port = $localPort;
			return $return;
		}

		foreach ($codes as $code) {
			if ($responseCode == $code) {
				return $result;
			}
		}
		if ($responseCode == '') {
			$responseCode = '999';
		}
		throw new InfraException($responseCode);
	}

	private function _reRequest($headers, $content, $codes, $redirect, $file, $write) {
		//print "<BR>reRequest<BR><PRE>" .  serialize($redirect) ."</PRE><HR>";
		//flush();

		preg_match('@^(\w+) (.*) HTTP/1.1\r\n(.*)@is', $headers, $old_matches);

		$verb = $old_matches[1];
		$oldPath = $old_matches[2];

		preg_match('@^Location: http://([\w\d\.]+)(:(\d{1,5}))?(.*)@im', $redirect, $new_matches);

		$new_path = trim($new_matches[4]);

		$sub = _CAStor_Facade_cutFromTo($redirect, "http://", "Content-Length: ");

		$oldHost = $this->host;
		//$this->host = substr($sub, 0, strpos($sub, "/"));
		//$this->port = intval(substr(_CAStor_Facade_cutFromTo($sub, "http://", "auth"), strpos($sub, ":") + 1, strpos($sub, "/") - 1));
		$this->host = $new_matches[1];
		$this->port = intval($new_matches[3] ? $new_matches[3] : 80);


		if (_CAStor_Facade_isDebugEnabled ()) {
			echo "<hr/>";
		}

		$new_header = str_replace($oldHost, $this->host, $headers);
		$new_header = str_replace($verb . " " . $oldPath,
		$verb . " " . $new_path,
		$new_header);

		return $this->_request($new_header, $content, $codes, $file, $write);
	}

	private function _write($conteudo, $anchor, $file, $metadados) {
		$headers = $this->_newRequest("POST", $anchor ? "?alias=yes" : "")
		. "Content-Length: " . strlen($file == "" ? $conteudo : file_get_contents($file)) . "\r\n";

		$now = InfraData::getStrDataHoraAtual();
		foreach ($this->getLifepoint() as $lf) {
			$then = InfraData::calcularData($lf[0],InfraData::$UNIDADE_DIAS,InfraData::$SENTIDO_ADIANTE,$now);
			$now = $then;
			$deletable = ", deletable=" . ($lf[2] == TRUE ? "True" : "False");
			$headers .= addslashes("Lifepoint: [" . gmdate("D, d M Y H:i:s", InfraData::getTimestamp($then)) . " GMT] reps=" . $lf[1] . $deletable . "\r\n");
		}

		if ($this->autodelete) {
			$headers .= addslashes("Lifepoint: [] delete") . "\r\n";
		}

		foreach ($metadados as $metadado) {
			$headers .= $metadado . "\r\n";
		}

		$result = $this->_request($headers, $conteudo, array(201), $file, true);
		preg_match('@^Content-UUID: ([\w\d]+)\r\n@im', $result, $matches);

		return $matches[1];
	}

	private function _newRequest($method, $parameter) {
		$request = $method . " /" . $parameter . " HTTP/1.1\r\n"
		. "Host: " . $this->host . "\r\n"
		. "User-Agent: CAStor PHPToolkit\r\n"
		. "Connection: close\r\n";
		return $request;
	}
}

function _CAStor_Facade_cutFromTo($subject, $from, $to) {
	$from_index = strpos($subject, $from);
	$start_from_value = $from_index + strlen($from);
	$out = substr($subject, $start_from_value);
	$to_index = strpos($subject, $to);
	return trim(substr($out, 0, $to_index - $start_from_value));
}

function _CAStor_Facade_isDebugEnabled() {
	return defined('CASTOR_FACADE_DEBUG') && CASTOR_FACADE_DEBUG;
}

?>