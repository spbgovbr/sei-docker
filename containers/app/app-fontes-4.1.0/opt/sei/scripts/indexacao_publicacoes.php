<?
	try{

		require_once dirname(__FILE__).'/../web/SEI.php';

    session_start();
		
		SessaoSEI::getInstance(false);

		if ($argc > 1){
			die("Este script nao aceita parametros.\n");
		}

		InfraDebug::getInstance()->setBolLigado(false);
		InfraDebug::getInstance()->setBolDebugInfra(false);
		InfraDebug::getInstance()->setBolEcho(true);
		InfraDebug::getInstance()->limpar();

		$objIndexacaoRN = new IndexacaoRN();
		$objIndexacaoRN->gerarIndexacaoPublicacao();

	}catch(Exception $e){
		if ($e instanceof InfraException && $e->contemValidacoes()){
			die(InfraString::excluirAcentos($e->__toString())."\n");
		}

		echo(InfraException::inspecionar($e));

		try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
	}
?>