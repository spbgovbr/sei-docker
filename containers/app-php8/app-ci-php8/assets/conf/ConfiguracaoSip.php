<?

class ConfiguracaoSip extends InfraConfiguracao
{

  private static $instance = null;

  public static function getInstance(): ConfiguracaoSip
  {
    if (ConfiguracaoSip::$instance == null) {
      ConfiguracaoSip::$instance = new ConfiguracaoSip();
    }
    return ConfiguracaoSip::$instance;
  }

  public function getArrConfiguracoes(): array
  {
    return array(
      'Sip' => array(
        'URL' => getenv('APP_PROTOCOLO').'://'.getenv('APP_HOST').'/sip',
        'Producao' => true
      ),

      'PaginaSip' => array('NomeSistema' => 'SIP'),

      'SessaoSip' => array(
        'SiglaOrgaoSistema' => getenv('APP_ORGAO'),
        'SiglaSistema' => 'SIP',
        'PaginaLogin' => getenv('APP_PROTOCOLO').'://'.getenv('APP_HOST').'/sip/login.php',
        'SipWsdl' => getenv('APP_PROTOCOLO').'://'.getenv('APP_HOST').'/sip/controlador_ws.php?servico=sip',
        'ChaveAcesso' => getenv('APP_SIP_CHAVE_ACESSO'),
        'https' => (getenv('APP_PROTOCOLO') == 'https' ? true : false)),

      'BancoSip'  => array(
          'Servidor' => getenv('APP_DB_HOST'),
          'Porta' => getenv('APP_DB_PORTA'),
          'Banco' => getenv('APP_DB_SIP_BASE'),
          'Usuario' => getenv('APP_DB_SIP_USERNAME'),
          'Senha' => getenv('APP_DB_SIP_PASSWORD'),
          'UsuarioScript' => getenv('APP_DB_SIP_USERNAME'),
          'SenhaScript' => getenv('APP_DB_SIP_PASSWORD'),
          'Tipo' => getenv('APP_DB_TIPO')), //MySql, SqlServer, Oracle ou PostgreSql

      /*
      'BancoAuditoriaSip'  => array(
          'Servidor' => '[Servidor BD]',
          'Porta' => '',
          'Banco' => '',
          'Usuario' => '',
          'Senha' => '',
          'Tipo' => ''), //MySql, SqlServer, Oracle ou PostgreSql
      */

	'CacheSip' => array('Servidor' => 'memcached',
			                'Porta' => '11211'),

      'hCaptcha' => array(
        'ChaveSecreta' => '',
        'ChaveSite' => ''
      ),

      'ReCaptchaV2' => array(
        'ChaveSecreta' => '',
        'ChaveSite' => ''
      ),

      'ReCaptchaV3' => array(
        'ChaveSecreta' => '',
        'ChaveSite' => '',
        'Score' => 0.5
      ),

		'InfraMail' => array(
				'Tipo' => '2', //1 = sendmail (neste caso nao e necessario configurar os atributos abaixo), 2 = SMTP
				'Servidor' => 'smtp',
				'Porta' => '25',
				'Codificacao' => '8bit', //8bit, 7bit, binary, base64, quoted-printable
				'MaxDestinatarios' => 999, //numero maximo de destinatarios por mensagem
				'MaxTamAnexosMb' => 999, //tamanho maximo dos anexos em Mb por mensagem
				'Seguranca' => '', //TLS, SSL ou vazio
				'Autenticar' => false, //se true entao informar Usuario e Senha
				'Usuario' => '',
				'Senha' => '',
				'Protegido' => '' //campo usado em desenvolvimento, se tiver um email preenchido entao todos os emails enviados terao o destinatario ignorado e substituido por este valor (evita envio incorreto de email)
		    )
        );
  }
}

?>