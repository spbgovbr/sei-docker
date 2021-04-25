<?

class ConfiguracaoSEI extends InfraConfiguracao  {

 	private static $instance = null;

 	public static function getInstance(){
 	  if (ConfiguracaoSEI::$instance == null) {
 	    ConfiguracaoSEI::$instance = new ConfiguracaoSEI();
 	  }
 	  return ConfiguracaoSEI::$instance;
 	}

 	public function getArrConfiguracoes(){
 	  return array(

 	      'SEI' => array(
 	          'URL' => getenv('APP_PROTOCOLO').'://'.getenv('APP_HOST').'/sei',
 	          'Producao' => false,
 	          'RepositorioArquivos' => '/dados',
              'Modulos' => array(/*novomodulo*/), 
              ),
              
          /*extramodulesconfig*/

 	      'PaginaSEI' => array(
 	          'NomeSistema' => 'SEI',
 	          'NomeSistemaComplemento' => getenv('APP_NOMECOMPLEMENTO'),
 	          'LogoMenu' => ''),

 	      'SessaoSEI' => array(
 	          'SiglaOrgaoSistema' => getenv('APP_ORGAO'),
 	          'SiglaSistema' => 'SEI',
 	          'PaginaLogin' => getenv('APP_PROTOCOLO').'://'.getenv('APP_HOST').'/sip/login.php',
 	          'SipWsdl' => getenv('APP_PROTOCOLO').'://'.getenv('APP_HOST').'/sip/controlador_ws.php?servico=sip',
 	          'ChaveAcesso' => getenv('APP_SEI_CHAVE_ACESSO'), 
 	          'https' => false),

 	      'BancoSEI'  => array(
 	          'Servidor' => 'db',
 	          'Porta' => getenv('APP_DB_PORTA'),
 	          'Banco' => getenv('APP_DB_SEI_BASE'),
 	          'Usuario' => getenv('APP_DB_SEI_USERNAME'),
 	          'Senha' => getenv('APP_DB_SEI_PASSWORD'),
 	          'UsuarioScript' => getenv('APP_DB_SEI_USERNAME'),
 	          'SenhaScript' => getenv('APP_DB_SEI_PASSWORD'),
 	          'Tipo' => getenv('APP_DB_TIPO')), //MySql, SqlServer, Oracle ou PostgreSql

 	      /*
        'BancoAuditoriaSEI'  => array(
 	          'Servidor' => '[servidor BD]',
 	          'Porta' => '',
 	          'Banco' => '',
 	          'Usuario' => '',
 	          'Senha' => '',
 	          'Tipo' => ''), //MySql, SqlServer, Oracle ou PostgreSql
        */

  			'CacheSEI' => array('Servidor' => 'memcached',
					                	'Porta' => '11211'),

        'Federacao' => array(
          'Habilitado' => false
         ),

 	      'JODConverter' => array('Servidor' => 'http://jod:8080/converter/service'),

 	      'Solr' => array(
 	          'Servidor' => 'http://solr:8983/solr',
 	          'CoreProtocolos' => 'sei-protocolos',
 	          'CoreBasesConhecimento' => 'sei-bases-conhecimento',
 	          'CorePublicacoes' => 'sei-publicacoes'),

 	      'InfraMail' => array(
						'Tipo' => '2', //1 = sendmail (neste caso n�o � necess�rio configurar os atributos abaixo), 2 = SMTP
						'Servidor' => 'smtp',
						'Porta' => '25',
						'Codificacao' => '8bit', //8bit, 7bit, binary, base64, quoted-printable
						'MaxDestinatarios' => 999, //numero maximo de destinatarios por mensagem
						'MaxTamAnexosMb' => 999, //tamanho maximo dos anexos em Mb por mensagem
						'Seguranca' => '', //TLS, SSL ou vazio
						'Autenticar' => false, //se true ent�o informar Usuario e Senha
						'Usuario' => '',
						'Senha' => '',
						'Protegido' => '' //campo usado em desenvolvimento, se tiver um email preenchido entao todos os emails enviados terao o destinatario ignorado e substitu�do por este valor evitando envio incorreto de email
				)
 	  );
 	}
}
?>
