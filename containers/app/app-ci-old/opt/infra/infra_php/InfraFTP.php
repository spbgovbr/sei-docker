<?
/**
 * Classe abstrata que implementa o protocolo FTP.
 * Esta classe deve ser extendida por cada aplicaчуo.
 *
 * criado em 08/04/2014 - bmy@trf4.gov.br
 * alterado em
 *
 * Observaчѕes:
 *
 */
abstract class InfraFTP implements InfraIProtocoloComunicacao {

  private $objConexao;
  
	public function __construct() {
	}

	/**
	 * Abrir conexуo com servidor FTP.
	 *
	 * @param boolean $bolConexaoSegura Parametro que define a utilizaчуo de SSL ou nуo na conexуo.
	 * @param string $strIdConexao ID da conexуo
	 *
	 */
function abrirConexao($bolConexaoSegura=true, $strIdConexao=null){
		try {
			if ($bolConexaoSegura) {
				if ($this->getPorta($strIdConexao) == 0) {
					$objConexao = ftp_ssl_connect($this->getServidor($strIdConexao));
				} else {
					$objConexao = ftp_ssl_connect($this->getServidor($strIdConexao), $this->getPorta($strIdConexao));
				}
			} else {
				if ($this->getPorta($strIdConexao) == 0) {
					$objConexao = ftp_connect($this->getServidor($strIdConexao));
				} else {
					$objConexao = ftp_connect($this->getServidor($strIdConexao), $this->getPorta($strIdConexao));
				}
			}

			if (!$objConexao) {
				throw new InfraException('Nуo foi possэvel abrir a conexуo FTP [(SSL: '.$bolConexaoSegura.') '.$this->getServidor($strIdConexao).':'.$this->getPorta($strIdConexao).'].');
			} else {
				$objValidacao = ftp_login($objConexao, $this->getUsuario($strIdConexao), $this->getSenha($strIdConexao));
				if (!$objValidacao) {
					throw new InfraException('Combinaчуo usuсrio/senha invсlida [Conexуo '.$this->getServidor($strIdConexao).'].');
				} else {
					ftp_pasv($objConexao, true);
					$this->objConexao = $objConexao;
				}
			}
		} catch(Exception $e) {
			throw $e;
		}
	}

	function mostrarDiretorioLocal() {
		try {
			return ftp_pwd($this->objConexao);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function mostrarTamanhoArquivo($strArquivoRemoto) {
		try {
			return ftp_size($this->objConexao, $strArquivoRemoto);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function listarArquivos($strDiretorio) {
		try {
		  ftp_chdir($this->objConexao, $strDiretorio);
			return ftp_nlist($this->objConexao, $strDiretorio);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function listarArquivosDetalhes($strDiretorio) {
		try {
			return ftp_rawlist($this->objConexao, $strDiretorio);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function enviarArquivo($strArquivoLocal, $strArquivoRemoto) {
		try {
      ftp_chdir($this->objConexao, dirname($strArquivoRemoto));
			return ftp_put($this->objConexao, basename($strArquivoRemoto), $strArquivoLocal, FTP_BINARY);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function receberArquivo($strArquivoLocal, $strArquivoRemoto) {
		try {
			return ftp_get($this->objConexao, $strArquivoLocal, $strArquivoRemoto, FTP_BINARY);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function renomearArquivo($strNomeArquivo, $strNovoNomeArquivo) {
		try {
			return ftp_rename($this->objConexao, $strNomeArquivo, $strNovoNomeArquivo);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function apagarArquivo($strArquivoRemoto) {
		try {
			return ftp_delete($this->objConexao, $strArquivoRemoto);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function criarDiretorio($strDiretorioRemoto) {
		try {
			return ftp_mkdir($this->objConexao, $strDiretorioRemoto);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function apagarDiretorio($strDiretorioRemoto) {
		try {
			return ftp_rmdir($this->objConexao, $strDiretorioRemoto);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function executarComando($strComando) {
		try {
			return ftp_exec($this->objConexao, $strComando);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function mostrarTipoSistemaRemoto() {
		try {
			return ftp_systype($this->objConexao);
		} catch(Exception $e) {
			throw $e;
		}
	}

	function fecharConexao() {
		try {
			return ftp_close($this->objConexao);
		} catch(Exception $e) {
			throw $e;
		}
	}
}
?>