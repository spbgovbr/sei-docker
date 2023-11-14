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
 	          'URL' => 'http://sei-tjrs.apps.cluster-h7b9k.h7b9k.sandbox753.opentlc.com/sei',
 	          'Producao' => true,
 	          'RepositorioArquivos' => '/dados',
 	          'WebServices' => true,
 	          'Modulos' => array(
 	             'ClasseModulo1' => 'DiretorioModulo1',
 	             'ClasseModulo2' => 'DiretorioModulo2'
 	           ),
        ),

 	      'PaginaSEI' => array(
 	          'NomeSistema' => 'SEI',
 	          'NomeSistemaComplemento' => '',
 	          'LogoMenu' => '',
            'Login' => true,
            'Ouvidoria' => true,
            'PublicacaoInterna' => true,
            'UsuariosExternos' => true,
            'ValidacaoDocumentos' =>  true,
            'ConsultaProcessual' =>  true
            ),
 	       
 	      'SessaoSEI' => array(
 	          'SiglaOrgaoSistema' => 'ABC',
 	          'SiglaSistema' => 'SEI',
 	          'PaginaLogin' => 'http://sip-tjrs.apps.cluster-h7b9k.h7b9k.sandbox753.opentlc.com/sip/login.php',
 	          'SipWsdl' => 'http://sip-tjrs.apps.cluster-h7b9k.h7b9k.sandbox753.opentlc.com/sip/controlador_ws.php?servico=sip',
 	          'ChaveAcesso' => '7babf862e12bd48f3101075c399040303d94a493c7ce9306470f719bb453e0428c6135dc', //ATEN��O: gerar uma nova chave para o SEI ap�s a instala��o (ver documento de instala��o)
 	          'https' => false),
 	       
 	      'BancoSEI'  => array(
 	          'Servidor' => 'sei-db.tjrs.svc.cluster.local',
 	          'Porta' => '3306',
 	          'Banco' => 'sei',
 	          'Usuario' => 'sei',
 	          'Senha' => 'sei',
 	          'Tipo' => 'MySql'), //MySql, SqlServer, Oracle ou PostgreSql

 	      /*
        'BancoAuditoriaSEI'  => array(
 	          'Servidor' => '[servidor BD]',
 	          'Porta' => '',
 	          'Banco' => '',
 	          'Usuario' => '',
 	          'Senha' => '',
 	          'Tipo' => ''), //MySql, SqlServer, Oracle ou PostgreSql
        */

  			'CacheSEI' => array('Servidor' => 'memcached.tjrs.svc.cluster.local',
					                	'Porta' => '11211'),

        'Federacao' => array(
          'Habilitado' => false
         ),

        'Manutencao' => array(
            'Ativada' => false,
            'Usuarios' => array('siglaUsuario1/siglaOrgao1','siglaUsuario2/siglaOrgao2'),
            'Mensagem' => 'Sistema em Manuten��o',
            'Detalhes' => 'Previs�o de retorno at� as <b>XXhs.</b>'
          ),

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

 	      'JODConverter' => array('Servidor' => 'http://jdoconverternew.tjrs.svc.cluster.local:8080/converter/service'),

 	      'Solr' => array(
 	          'Servidor' => 'http://[Servidor Solr]:8080/solr',
 	          'CoreProtocolos' => 'sei-protocolos',
 	          'CoreBasesConhecimento' => 'sei-bases-conhecimento',
 	          'CorePublicacoes' => 'sei-publicacoes'),

 	      'InfraMail' => array(
						'Tipo' => '1', //1 = sendmail (neste caso n�o � necess�rio configurar os atributos abaixo), 2 = SMTP
						'Servidor' => '[Servidor E-Mail]',
						'Porta' => '25',
						'Codificacao' => '8bit', //8bit, 7bit, binary, base64, quoted-printable
						'MaxDestinatarios' => 999, //numero maximo de destinatarios por mensagem
						'MaxTamAnexosMb' => 999, //tamanho maximo dos anexos em Mb por mensagem
						'Seguranca' => 'TLS', //TLS, SSL ou vazio
						'Autenticar' => false, //se true ent�o informar Usuario e Senha
						'Usuario' => 'aaa',
						'Senha' => 'aaa',
						'Protegido' => '' //campo usado em desenvolvimento, se tiver um email preenchido entao todos os emails enviados terao o destinatario ignorado e substitu�do por este valor evitando envio incorreto de email
				)
 	  );
 	}
}
?>