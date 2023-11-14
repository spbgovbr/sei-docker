<?
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  try {
    require_once dirname(__FILE__).'/Sip.php';
    session_start();

    SessaoSip::getInstance(false);

    $objSipRestWS = new SipRestWS();

    switch($_GET['acao_rest_ws']) {
      case 'verificar_estado':
        echo $objSipRestWS->verificarEstado($_GET['sigla_orgao'], $_GET['sigla_sistema'], $_GET['chave']);
        break;

      case 'autenticar':
        echo $objSipRestWS->autenticar($_GET['id_orgao'], $_POST['txtUsuario'], $_POST['txtSenha'], $_GET['sigla_orgao'], $_GET['sigla_sistema'], $_GET['chave']);
        break;

      case 'carregar_recursos':
        echo $objSipRestWS->carregarRecursos($_GET['id_sistema'], $_GET['perfil'], $_GET['recurso'], $_GET['id_orgao'], $_POST['txtUsuario'], $_POST['txtSenha'], $_GET['sigla_orgao'], $_GET['sigla_sistema'], $_GET['chave']);
        break;

      default:
        break;
    }
  } catch(Exception $e) {
    LogSip::getInstance()->gravar($e->__toString());
  }
?>