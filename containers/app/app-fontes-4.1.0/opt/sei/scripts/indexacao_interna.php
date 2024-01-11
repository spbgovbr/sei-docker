<?
try{

  require_once dirname(__FILE__).'/../web/SEI.php';

  session_start();

  SessaoSEI::getInstance(false);

  if ($argc > 2){
    die("USO: ".basename(__FILE__) ." [orgaos,unidades,usuarios,contatos,assuntos,acompanhamentos_especiais,blocos,grupos_email,observacoes_protocolos,favoritos]\n");
  }

  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(false);
  InfraDebug::getInstance()->setBolEcho(true);
  InfraDebug::getInstance()->limpar();

  $objIndexacaoDTO = new IndexacaoDTO();

  if ($argc == 1){
    $objIndexacaoDTO->setStrSinOrgaos('S');
    $objIndexacaoDTO->setStrSinUnidades('S');
    $objIndexacaoDTO->setStrSinUsuarios('S');
    $objIndexacaoDTO->setStrSinContatos('S');
    $objIndexacaoDTO->setStrSinAssuntos('S');
    $objIndexacaoDTO->setStrSinAcompanhamentos('S');
    $objIndexacaoDTO->setStrSinBlocos('S');
    $objIndexacaoDTO->setStrSinGruposEmail('S');
    $objIndexacaoDTO->setStrSinObservacoes('S');
    $objIndexacaoDTO->setStrSinFavoritos('S');
  }else{

    $objIndexacaoDTO->setStrSinOrgaos('N');
    $objIndexacaoDTO->setStrSinUnidades('N');
    $objIndexacaoDTO->setStrSinUsuarios('N');
    $objIndexacaoDTO->setStrSinContatos('N');
    $objIndexacaoDTO->setStrSinAssuntos('N');
    $objIndexacaoDTO->setStrSinAcompanhamentos('N');
    $objIndexacaoDTO->setStrSinBlocos('N');
    $objIndexacaoDTO->setStrSinGruposEmail('N');
    $objIndexacaoDTO->setStrSinObservacoes('N');
    $objIndexacaoDTO->setStrSinFavoritos('N');

    $arrOpcoes = explode(',',$argv[1]);

    foreach($arrOpcoes as $opcao){

      switch ($opcao) {

        case 'orgaos':
          $objIndexacaoDTO->setStrSinOrgaos('S');
          break;

        case 'unidades':
          $objIndexacaoDTO->setStrSinUnidades('S');
          break;

        case 'usuarios':
          $objIndexacaoDTO->setStrSinUsuarios('S');
          break;

        case 'contatos':
          $objIndexacaoDTO->setStrSinContatos('S');
          break;

        case 'assuntos':
          $objIndexacaoDTO->setStrSinAssuntos('S');
          break;

        case 'acompanhamentos_especiais':
          $objIndexacaoDTO->setStrSinAcompanhamentos('S');
          break;

        case 'blocos':
          $objIndexacaoDTO->setStrSinBlocos('S');
          break;

        case 'grupos_email':
          $objIndexacaoDTO->setStrSinGruposEmail('S');
          break;

        case 'observacoes_protocolos':
          $objIndexacaoDTO->setStrSinObservacoes('S');
          break;

        case 'favoritos':
          $objIndexacaoDTO->setStrSinFavoritos('S');
          break;

        default:
          die('OPCAO INVALIDA: '.$opcao."\n");
      }
    }
  }

  $objIndexacaoRN = new IndexacaoRN();
  $objIndexacaoRN->gerarIndexacaoInterna($objIndexacaoDTO);

}catch(Exception $e){
  if ($e instanceof InfraException && $e->contemValidacoes()){
    die(InfraString::excluirAcentos($e->__toString())."\n");
  }

  echo(InfraException::inspecionar($e));

  try{LogSEI::getInstance()->gravar(InfraException::inspecionar($e));	}catch (Exception $e){}
}
?>