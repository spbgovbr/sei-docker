<?
	try{

		require_once dirname(__FILE__).'/../web/SEI.php';

    session_start();
		
		SessaoSEI::getInstance(false);

		if ($argc != 5){
			die("USO: ".basename(__FILE__) ." [data/hora inicial no formato dd/mm/aaaa hh:mm] [data/hora final no formato dd/mm/aaaa hh:mm]\n");
		}

		InfraDebug::getInstance()->setBolLigado(false);
		InfraDebug::getInstance()->setBolDebugInfra(false);
		InfraDebug::getInstance()->setBolEcho(true);
		InfraDebug::getInstance()->limpar();

		$objIndexacaoDTO = new IndexacaoDTO();
	  $objIndexacaoDTO->setDthInicio($argv[1] . ' ' . $argv[2]);
		$objIndexacaoDTO->setDthFim($argv[3] . ' ' . $argv[4]);

		$objIndexacaoRN = new IndexacaoRN();
		$objIndexacaoRN->gerarIndexacaoParcial($objIndexacaoDTO);

	}catch(Exception $e){
		if ($e instanceof InfraException && $e->contemValidacoes()){
			die(InfraString::excluirAcentos($e->__toString())."\n");
		}

		echo(InfraException::inspecionar($e));

		try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
	}
?>