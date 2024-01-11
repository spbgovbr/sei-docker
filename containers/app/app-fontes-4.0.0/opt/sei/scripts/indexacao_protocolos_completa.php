<?
	try{

		require_once dirname(__FILE__).'/../web/SEI.php';

    session_start();
		
		SessaoSEI::getInstance(false);

		if ($argc > 3){
      die("USO: ".basename(__FILE__) ." [data inicial opcional no formato dd/mm/aaaa] [data final opcional no formato dd/mm/aaaa]\n");
		}

		InfraDebug::getInstance()->setBolLigado(false);
		InfraDebug::getInstance()->setBolDebugInfra(false);
		InfraDebug::getInstance()->setBolEcho(true);
		InfraDebug::getInstance()->limpar();

		$objIndexacaoDTO = new IndexacaoDTO();

		if (isset($argv[1])) {
      $objIndexacaoDTO->setDthInicio($argv[1]);
		}else{
      $objIndexacaoDTO->setDthInicio(null);
    }

    if (isset($argv[2])) {
      $objIndexacaoDTO->setDthFim($argv[2]);
    }else{
      $objIndexacaoDTO->setDthFim(null);
    }

		$objIndexacaoRN = new IndexacaoRN();
		$objIndexacaoRN->gerarIndexacaoCompleta($objIndexacaoDTO);

	}catch(Exception $e){
		if ($e instanceof InfraException && $e->contemValidacoes()){
			die(InfraString::excluirAcentos($e->__toString())."\n");
		}

		echo(InfraException::inspecionar($e));

		try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
	}
?>