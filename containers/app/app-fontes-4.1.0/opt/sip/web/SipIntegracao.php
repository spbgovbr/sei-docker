<?

abstract class SipIntegracao {
  public abstract function getNome();

  public abstract function getVersao();

  public abstract function getInstituicao();

  public function inicializar() {
    return null;
  }

  public function processarControlador($strAcao) {
    return null;
  }

  public function processarControladorAjax($strAcaoAjax) {
    return null;
  }

  public function processarControladorWebServices($strServico) {
    return null;
  }

  public function executar($func, ...$params) {
    try {
      $ret = call_user_func_array(array($this, $func), $params);
    } catch (Throwable $e) {
      throw new InfraException('Erro processando operaзгo "' . $func . '" no mуdulo "' . $this->getNome() . '".', $e);
    }
    return $ret;
  }
}

?>