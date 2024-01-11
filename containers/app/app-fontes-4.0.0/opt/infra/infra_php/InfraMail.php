<?
  /**
  * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
  * 17/08/2007 - CRIADO POR cle@trf4.gov.br
  * @package infra_php
  */

  require_once dirname(__FILE__).'/mail/class.phpmailer.php';
  require_once dirname(__FILE__).'/mail/class.smtp.php';
  require_once dirname(__FILE__).'/mail/language/phpmailer.lang-br.php';

  class InfraMail {
    /**
     * Constante usada para avaliar a configuração do tipo de email
     * @access private
     * @name $TM_SEND_MAIL
     */
  	public static $TM_SEND_MAIL = '1';
  	
  	/**
  	 * Constante usada para avaliar a configuração do tipo de email
  	 * @access private
  	 * @name $TM_SMTP
  	 */
    public static $TM_SMTP = '2';
  	
    private function __construct(){}
    
    /**
     * Envia email com endereço do destinatário obtido por consulta na tabela infra_parametro.
     *
     * @param Obj $objInfraIBanco	- Instância de alguma classe de BD específico (ex. BancoCSRHSQLServer::getInstance()).
     * @param string $strNomeInfraParametro - Nome padronizado para busca na tabela infra_parametro (ex. 'mail_concurso_remocao')
     * @param string $strDe
     * @param string $strPara
     * @param string $strAssunto
     * @param string $varAnexo
     * @param string $strConteudo
     * @param string $strCopiaOculta
     * @return void
     */
    public static function enviarProtegido($objInfraIBanco, $strNomeInfraParametro,  $strDe, $strPara, $strAssunto, $strCorpo, $varAnexo=null, $strConteudo="text/plain", $strCopiaOculta=null) {
     	$objInfraParametro = new InfraParametro($objInfraIBanco);
     	$strEmailProtecao = $objInfraParametro->getValor($strNomeInfraParametro,false);
     	if ($strEmailProtecao != ''){
     		$strPara = $strEmailProtecao;
     	}
    	return self::enviar($strDe, $strPara, $strAssunto, $strCorpo, $varAnexo, $strConteudo, $strCopiaOculta);
    }
    
    /**
     * Envia email.
     *
     * @param string $strDe
     * @param string $strPara
     * @param string $strAssunto
     * @param string $strCorpo
     * @param string $varAnexo
     * @param string $strConteudo
     * @param string $strCopiaOculta
     * @return void
     */    
    public static function enviar($strDe, $strPara, $strAssunto, $strCorpo, $varAnexo=null, $strConteudo="text/plain", $strCopiaOculta=null) {
      
      $strCabecalho = '';
      $strCabecalho .= "From: ".$strDe."\r\n";
      $strCabecalho .= "Reply-To: ".$strDe."\r\n";
      
      if (!InfraString::isBolVazia($strCopiaOculta)){
        $strCabecalho .= 'Bcc: '.$strCopiaOculta."\r\n";
      }
      
      $strCabecalho .= "MIME-Version: 1.0\r\n";
      
      if ($varAnexo==null){
        
        $strCabecalho .= "Content-type: ".$strConteudo."; charset=iso-8859-1\r\n";
        
      }else{
        
        //CABEÇALHO DA MENSAGEM COM ANEXO
        $strRand = md5(time());
        $strMimeBoundary = "==Multipart_Boundary_x{$strRand}x";
        $strCabecalho .= "Content-Type: multipart/mixed;\n boundary=\"{$strMimeBoundary}\"\r\n";
        $strCabecalho .= "\r\nThis is a multi-part message in MIME format.\r\n";

        $strTemp = '';
        $strTemp .= "--{$strMimeBoundary}\r\n";
        $strTemp .= "Content-type: ".$strConteudo."; charset=iso-8859-1\n";
        $strTemp .= "Content-Transfer-Encoding: base64\r\n";
        $strTemp .= chunk_split(base64_encode($strCorpo))."\r\n";
        
      	//COMPATIBILIDADE COM CÓDIGOS LEGADOS: LISTA DE ARQUIVOS (MESMO SE É SÓ UM) EM ARRAY
      	if (!is_array($varAnexo)) {
      	  $strArquivo = $varAnexo;
      	  $varAnexo = array($strArquivo => $strArquivo);
      	}
      	
      	if (count($varAnexo) > 0){
         	if (isset($varAnexo['tmp_name'])) {
         		//SE É UM CAMPO FILES[''], GUARDA AS INFORMAÇÕES NO ARRAY
            $arrNomeAnexo[0] = $varAnexo['name'];
         		$arrCaminhoArquivo[0] = $varAnexo['tmp_name'];
            $arrTipoArquivo[0] = $varAnexo['type'];
         	} else {
         		//SE É UM ARRAY DE CAMINHOS, GUARDA AS INFORMAÇÕES DE CADA UM NO ARRAY (A CHAVE DO ARRAY TEM O NOME REAL)
         		foreach ($varAnexo as $strNome=>$strCaminho) {
       		  	$arrNomeAnexo[] = $strNome;
              $arrCaminhoArquivo[] = $strCaminho;
              $arrTipoArquivo[] = InfraUtil::getStrMimeType($strCaminho);
           	}
          }
       
          //ANEXOS
          for ($i=0; $i<InfraArray::contar($arrNomeAnexo); $i++) {
          	$strArquivo = fopen($arrCaminhoArquivo[$i],'rb');
            $strDados = fread($strArquivo,filesize($arrCaminhoArquivo[$i]));
            fclose($strArquivo);
            $strDados = chunk_split(base64_encode($strDados));
            $strTemp .= "--{$strMimeBoundary}\r\n";
            $strTemp .= "Content-Type: {$arrTipoArquivo[$i]}; name=\"{$arrNomeAnexo[$i]}\"\n";
            $strTemp .= "Content-Transfer-Encoding: base64\n";
            $strTemp .= "Content-Disposition: attachment;\n filename=\"{$arrNomeAnexo[$i]}\"\r\n";
            $strTemp .= $strDados."\r\n";
          }
      	}
      	
      	$strCorpo = $strTemp;
      }
      
      //verifica se esta no formato "Nome <e-mail>"
      $posMenor = strpos($strDe,'<');
      $posMaior = strpos($strDe,'>');
      
      if ($posMenor !== false && $posMaior !== false && $posMenor < $posMaior){
        $strReturnPath = substr($strDe,($posMenor+1),$posMaior-$posMenor-1);
      }else{
        $strReturnPath = $strDe;  
      }
      
      //die($strCabecalho.'#'.$strCorpo);      
      
      return mail($strPara, $strAssunto, $strCorpo, $strCabecalho,'-r "'.$strReturnPath.'"');
    }
        
    /**
     * Envia email buscando informações na classe de configuração de cada sistema (ex. ConfiguracaoCSRH.php)
     *
     * @param Var $varConfiguracao	- Instância da classe de configuração ou um array com a configuração
     * @param string $strDe
     * @param string $strPara
     * @param string $strCC
     * @param string $strCCO
     * @param string $strAssunto
     * @param string $strCorpo
     * @param string $strTipoCorpo
     * @param string $strAnexos
     * @param InfraLog $objInfraLog
     * @param null|string $strReplyTo Se não definido, ao invés de usar o replyTo como 'De', usa o valor parametrizado.
     * @return void
     */    
    public static function enviarConfigurado(InfraConfiguracao $objInfraConfiguracao, $strDe, $strPara, $strCC, $strCCO, $strAssunto, $strCorpo, $strTipoCorpo="text/plain", $arrAnexos=null, $objInfraLog = null, $strReplyTo = null) {
      try{

        self::validarEmail($objInfraConfiguracao, $strDe, $strPara, $strCC, $strCCO, $strAssunto, $strCorpo, $strTipoCorpo, $arrAnexos,$strReplyTo);

        $arrConfig = self::obterConfiguracao($objInfraConfiguracao, $strDe);

        $numTipoMail = $arrConfig['Tipo'];
        $strCodificacao = $arrConfig['Codificacao'];
        $strServidor = $arrConfig['Servidor'];
        $numPorta  = $arrConfig['Porta'];
        $bolAutenticar = $arrConfig['Autenticar'];
        $strUsuario = $arrConfig['Usuario'];
        $strSenha = $arrConfig['Senha'];
        $strEmailProtegido = $arrConfig['Protegido'];

        $objPhpMailer = new PHPMailer(true);

        if (isset($arrConfig['Seguranca'])){
          if (strtolower($arrConfig['Seguranca'])=='ssl'){
            $objPhpMailer->SMTPSecure = 'ssl';
          }else if (strtolower($arrConfig['Seguranca'])=='tls'){
            $objPhpMailer->SMTPSecure = 'tls';
          }else if ($arrConfig['Seguranca']==''){
            $objPhpMailer->SMTPAutoTLS = false;
          }else{
            throw new InfraException('Tipo de segurança para o envio de e-mail inválida ['.$arrConfig['Seguranca'].'].');
          }
        }else{
          $objPhpMailer->SMTPSecure = 'tls';
        }

        if ($numTipoMail!=InfraMail::$TM_SEND_MAIL && $numTipoMail!=InfraMail::$TM_SMTP){
          if (!isset($arrConfig['Tipo'])){
            throw new InfraException('Não foi possível localizar as configurações de envio.');
          }else{
            throw new InfraException('Tipo de envio do e-mail inválido ['.$numTipoMail.'].');
          }

        }

        $objPhpMailer->Encoding = $strCodificacao;

        if ($numTipoMail==InfraMail::$TM_SEND_MAIL){
          $objPhpMailer->isSendMail();
        }else{

          $objPhpMailer->IsSMTP(); // telling the class to use SMTP
          $objPhpMailer->Host = $strServidor;
          $objPhpMailer->Port = $numPorta;

          if ($bolAutenticar){
            $objPhpMailer->SMTPAuth   = true;
            $objPhpMailer->Username   = $strUsuario;
            $objPhpMailer->Password   = $strSenha;
          }
        }

        $arr = InfraMail::decomporEmail($strDe);
        $objPhpMailer->SetFrom($arr[0],$arr[1]);

        if (InfraString::isBolVazia($strReplyTo)) {
            $objPhpMailer->addReplyTo($arr[0], $arr[1]);
        } else {
            $arrReplyTo = InfraMail::decomporEmail($strReplyTo);
            $objPhpMailer->addReplyTo($arrReplyTo[0], $arrReplyTo[1]);
        }

       	if ($strEmailProtegido != ''){
       		$strPara = $strEmailProtegido;
       	}

        if (!InfraString::isBolVazia($strPara)) {
          $arrPara = explode(';', $strPara);
          foreach ($arrPara as $strItemPara) {
            if ($strItemPara!='') {
              $arr = InfraMail::decomporEmail($strItemPara);
              $objPhpMailer->AddAddress($arr[0], $arr[1]);
            }
          }
        }

        if (!InfraString::isBolVazia($strCC) && $strEmailProtegido == ''){
          $arrCC = explode(';',$strCC);
          foreach($arrCC as $strItemCC){
            if ($strItemCC!='') {
              $arr = InfraMail::decomporEmail($strItemCC);
              $objPhpMailer->AddCC($arr[0], $arr[1]);
            }
          }
        }

        if (!InfraString::isBolVazia($strCCO) && $strEmailProtegido == ''){
          $arrCCO = explode(';',$strCCO);
          foreach($arrCCO as $strItemCCO){
            if ($strItemCCO!='') {
              $arr = InfraMail::decomporEmail($strItemCCO);
              $objPhpMailer->AddBCC($arr[0], $arr[1]);
            }
          }
        }

        $objPhpMailer->ContentType = $strTipoCorpo;
        $objPhpMailer->Subject = $strAssunto;
        $objPhpMailer->Body = $strCorpo;

      	if ($arrAnexos!=null){
          foreach($arrAnexos as $strNomeAnexo => $strCaminhoAnexo){
            $objPhpMailer->AddAttachment($strCaminhoAnexo,$strNomeAnexo);
          }
      	}

        $objPhpMailer->Send();

      }catch(Exception $e){

        $objInfraException = new InfraException();

        if (strpos(strtoupper($e->__toString()),'COULD NOT CONNECT TO SMTP HOST')!==false ||
            strpos(strtoupper($e->__toString()),'CONNECTION TIMED OUT')!==false ||
            strpos(strtoupper($e->__toString()),'UNABLE TO CONNECT')!==false ||
            strpos(strtoupper($e->__toString()),'NO ROUTE TO HOST')!==false ||
            strpos(strtoupper($e->__toString()),'NAME OR SERVICE NOT KNOWN')!==false){
          $objInfraException->lancarValidacao('Falha na conexão com o servidor de e-mails.', null, $e);
        }

        if (strpos(strtoupper($e->__toString()),'CONNECTION REFUSED')!==false){
          $objInfraException->lancarValidacao('O servidor de e-mails recusou a conexão.', null, $e);
        }

        if (strpos(strtoupper($e->__toString()),'COULD NOT AUTHENTICATE')!==false){
          $objInfraException->lancarValidacao('Falha na autenticação com o servidor de e-mails.', null, $e);
        }

        if (strpos(strtoupper($e->__toString()),'DATA NOT ACCEPTED')!==false){
          $objInfraException->lancarValidacao('O servidor de e-mails não aceitou os dados enviados.', null, $e);
        }

        if (strpos(strtoupper($e->__toString()),'INVALID ADDRESS')!==false){
          $objInfraException->lancarValidacao('Endereço eletrônico inválido.', null, $e);
        }

        if (strpos(strtoupper($e->__toString()),'THE FOLLOWING RECIPIENTS FAILED')!==false){

          preg_match('/The following recipients failed: ([^:]+):/', $e->__toString(), $match);

          $strMsg = 'Não foi possível enviar para o(s) destinatário(s): '.$match[1];

          if (strpos(strtoupper($e->__toString()),'QUOTA EXCEEDED')!==false) {
            $strMsg = 'Conta de email cheia.'."\n\n".$strMsg;
          }

          $objInfraException->lancarValidacao($strMsg, null, $e);
        }

        if (strpos(strtoupper($e->__toString()),'QUOTA EXCEEDED')!==false) {
          $objInfraException->lancarValidacao('Conta de email cheia.', null, $e);
        }

        throw new InfraException('Erro enviando correspondência eletrônica.', $e);
      }
    }
    
    /**
     * Se o endereço de email está no formato "Nome <e-mail>", divide a string e retorna um array com dois os valores, 
     * Se não, passa apenas o endereço na posição 0, e '' na posiçao 1 do array retornado
     * 
     *  @param string $strEmail
     *  @return array 
     */    
    private static function decomporEmail($strEmail){
      
      $posMenor = strpos($strEmail,'<');
      $posMaior = strpos($strEmail,'>');
      
      if ($posMenor !== false && $posMaior !== false && $posMenor < $posMaior){
        $strDescricao = substr($strEmail,0,$posMenor);
        $strEndereco = substr($strEmail,($posMenor+1),$posMaior-$posMenor-1);
      }else{
        $strDescricao = '';
        $strEndereco = $strEmail;  
      }
      
      return array($strEndereco, $strDescricao);
    }

      /**
       * @param InfraConfiguracao $objInfraConfiguracao
       * @param $strDe
       * @param $strPara
       * @param $strCC
       * @param $strCCO
       * @param $strAssunto
       * @param $strCorpo
       * @param string $strTipoCorpo
       * @param null $arrAnexos
       * @param null|string $strReplyTo Se não definido, ao invés de usar o replyTo como 'De', usa o valor parametrizado.
       */
    public static function validarEmail(InfraConfiguracao $objInfraConfiguracao, $strDe, $strPara, $strCC, $strCCO, $strAssunto, $strCorpo, $strTipoCorpo="text/plain", $arrAnexos=null, $strReplyTo=null){
      $objInfraException = new InfraException();

      if (InfraString::isBolVazia($strDe)){
        $objInfraException->lancarValidacao('Remetente do e-mail não informado.');
      }

      if (!InfraUtil::validarEmail($strDe)){
        $objInfraException->lancarValidacao('E-mail do remetente "'.$strDe.'" inválido.');
      }

      $arrConfig = self::obterConfiguracao($objInfraConfiguracao, $strDe);

      //postconf | grep smtpd_recipient_limit
      $numMaxDestinatarios = (isset($arrConfig['MaxDestinatarios'])?$arrConfig['MaxDestinatarios']:null);

      //zmprov getAllConfig | grep MessageSize
      $numMaxTamMbAnexos = (isset($arrConfig['MaxTamAnexosMb'])?$arrConfig['MaxTamAnexosMb']:null);
      if (!InfraString::isBolVazia($strReplyTo)){
          if (!InfraUtil::validarEmail($strReplyTo)){
              $objInfraException->lancarValidacao('E-mail do Reply To "'.$strReplyTo.'" inválido.');
          }
      }
      if (InfraString::isBolVazia($strPara) && InfraString::isBolVazia($strCC) && InfraString::isBolVazia($strCCO)){
        $objInfraException->lancarValidacao('Nenhum destinatário de e-mail informado.');
        return;
      }

      $arr = array();

      if (!InfraString::isBolVazia($strPara)) {
        $arr = array_merge($arr, explode(';',$strPara));
      }

      if (!InfraString::isBolVazia($strCC)){
        $arr = array_merge($arr, explode(';',$strCC));
      }

      if (!InfraString::isBolVazia($strCCO)){
        $arr = array_merge($arr, explode(';',$strCCO));
      }

      $numDestinatarios = count($arr);

      $numDestinatariosValidos = 0;

      for ($i = 0; $i < $numDestinatarios; $i++) {

        if ($arr[$i] != '') {
          if (!InfraUtil::validarEmail($arr[$i])) {
            $objInfraException->lancarValidacao('E-mail do destinatário "' . $arr[$i] . '" inválido.');
          }
          $numDestinatariosValidos++;
        }
      }

      if ($numDestinatariosValidos==0) {
        $objInfraException->lancarValidacao('Nenhum e-mail de destinatário informado.');
      }

      if ($numMaxDestinatarios!=null && $numDestinatariosValidos > $numMaxDestinatarios){
        $objInfraException->lancarValidacao('Número de destinatários ('.$numDestinatariosValidos.') excede o limite permitido ('.$numMaxDestinatarios.').');
        return;
      }

      if (InfraString::isBolVazia($strAssunto)){
        $objInfraException->lancarValidacao('Assunto não informado.');
      }

      /*
      if (InfraString::isBolVazia($strCorpo)){
        $objInfraException->lancarValidacao('Mensagem não informada.');
      }
      */

      if ($arrAnexos!=null) {

        foreach ($arrAnexos as $strNome => $strAnexo) {
          if (!file_exists($strAnexo)){
            $objInfraException->lancarValidacao('Anexo '.$strNome.' ['.$strAnexo.'] não encontrado para envio.');
          }
        }

        if ($numMaxTamMbAnexos!=null) {
          $numTamanho = 0;

          foreach ($arrAnexos as $strAnexo) {
            $numTamanho += filesize($strAnexo);
          }

          if ($numTamanho > ($numMaxTamMbAnexos * 1024 * 1024)) {
            $objInfraException->lancarValidacao('O tamanho dos anexos é ' . round($numTamanho / (1024 * 1024), 1) . 'Mb (máximo permitido '.$numMaxTamMbAnexos.'Mb).');
          }
        }
      }
    }

    private static function obterConfiguracao(InfraConfiguracao $objInfraConfiguracao, $strDe){

      $arrConfig = $objInfraConfiguracao->getValor('InfraMail');

      $strDominio = trim(str_replace('>','',substr($strDe,strrpos($strDe,'@')+1)));
      if ($objInfraConfiguracao->isSetValor('InfraMail','Dominios')){
        $arrDominios = $objInfraConfiguracao->getValor('InfraMail','Dominios');
        if (isset($arrDominios[$strDominio])){
          $arrConfig = $arrDominios[$strDominio];
        }
      }

      //die('<pre>'.$strDominio.'<br />'.print_r($arrConfig,true).'</pre>');

      return $arrConfig;
    }
  }
?>