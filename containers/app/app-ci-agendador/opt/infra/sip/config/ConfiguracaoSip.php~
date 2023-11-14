<?

class ConfiguracaoSip extends InfraConfiguracao  {
	
	private static $instance = null;
	
	public static function getInstance(){
		if (ConfiguracaoSip::$instance == null) {
			ConfiguracaoSip::$instance = new ConfiguracaoSip();
		}
		return ConfiguracaoSip::$instance;
	}
	
	public function getArrConfiguracoes(){
		return array(
            'Sip' => array(
                'URL' => getenv('HOST_URL').'/sip',
                'Producao' => false,
                'NumLoginSemCaptcha' => 3,
                'TempoLimiteValidacaoLogin' => 60,
                'Modulos' => array(
                    //'ABCExemploIntegracao' => 'abc/exemplo',
                ),
            ),

			'PaginaSip' => array(
				'NomeSistema' => 'SIP',
				'NomeSistemaComplemento' => '',
			),

			'SessaoSip' => array(
				'SiglaOrgaoSistema' => 'ABC',
				'SiglaSistema' => 'SIP',
				'PaginaLogin' => getenv('SEI_HOST_URL') . '/sip/login.php',
				'SipWsdl' => getenv('HOST_URL') . '/sip/controlador_ws.php?servico=sip',
                'ChaveAcesso' => getenv('SIP_CHAVE_ACESSO'), //ATENCAO: gerar uma nova chave para o SIP após a instalação (ver documento de instalação)
                'https' => false,
			),
			
			'BancoSip'  => array(
				'Servidor' => getenv('DATABASE_HOST'),
				'Porta' => getenv('DATABASE_PORT'),
				'Banco' => getenv('SIP_DATABASE_NAME'),
				'Usuario' => getenv('SIP_DATABASE_USER'),
				'Senha' => getenv('SIP_DATABASE_PASSWORD'),
				'Tipo' => getenv('DATABASE_TYPE'), //MySql, SqlServer ou Oracle
				'PesquisaCaseInsensitive' => false,				
			), 	
			
//			'BancoAuditoriaSip'  => array(
//                'Servidor' => getenv('DATABASE_HOST'),
//                'Porta' => getenv('DATABASE_PORT'),
//                'Banco' => getenv('SIP_DATABASE_NAME'),
//                'Usuario' => getenv('SIP_DATABASE_USER'),
//                'Senha' => getenv('SIP_DATABASE_PASSWORD'),
//                'Tipo' => getenv('DATABASE_TYPE'), //MySql, SqlServer ou Oracle
//                'PesquisaCaseInsensitive' => false,
//            ),
			
			'CacheSip' => array(
				'Servidor' => 'memcached',			
				'Porta' => '11211',
				'Timeout' => 2,
				'Tempo' => 3600,				
			),

            'InfraMail' => array(
                'Tipo' => '2', //1 = sendmail (neste caso não é necessário configurar os atributos abaixo), 2 = SMTP
                'Servidor' => 'smtp',
                'Porta' => '1025',
                'Codificacao' => '8bit', //8bit, 7bit, binary, base64, quoted-printable
                'Autenticar' => false, //se true então informar Usuario e Senha
                'Usuario' => '',
                'Senha' => '',
                'Seguranca' => '', //TLS, SSL ou vazio
                'MaxDestinatarios' => 25, //numero maximo de destinatarios por mensagem
                'MaxTamAnexosMb' => 15, //tamanho maximo dos anexos em Mb por mensagem
                'Protegido' => '', //campo usado em desenvolvimento, se tiver um email preenchido entao todos os emails enviados terao o destinatario ignorado e substituído por este valor (evita envio incorreto de email)
                /*  Abaixo chave opcional desativada com exemplo de preenchimento
                'Dominios' => array(	// Opcional. Permite especificar o conjunto de atributos acima individualmente para cada domínio de conta remetente. Se não existir um domínio mapeado então utilizará os atributos gerais da chave InfraMail.
                    'abc.jus.br' => array(
                        'Tipo' => '2',
                        'Servidor' => '10.1.3.12',
                        'Porta' => '25',
                        'Codificacao' => '8bit',
                        'Autenticar' => false,
                        'Usuario' => '',
                        'Senha' => '',
                        'Seguranca' => 'TLS',
                        'MaxDestinatarios' => 25,
                        'MaxTamAnexosMb' => 15,
                        'Protegido' => '',
                        ),
                    ),
                    */
            ),
        );
    }
}
?>