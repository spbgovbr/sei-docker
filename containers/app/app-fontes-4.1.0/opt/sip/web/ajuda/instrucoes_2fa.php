<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/11/2006 - criado por mga
*
*
*/

try {
    require_once dirname(__FILE__).'/../Sip.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoSip::getInstance(false);

    switch ($_GET['acao']) {
        case 'instrucoes_2fa':
            break;

        default:
            throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
    }

    $objSistemaDTO = LoginINT::obterSistema($_GET['sigla_sistema'], $_GET['sigla_orgao_sistema']);

    if ($objSistemaDTO->getStrLogo() != null) {
        $strLogo = '<div style="position:absolute; top:30px; left:100px;overflow:hidden;margin:auto;width:150px;height:150px;background-image: url(\'data:image/png;base64,'.$objSistemaDTO->getStrLogo(
            ).'\');background-position: center center;background-repeat: no-repeat;border-radius: 8px;"></div>';
    } else {
        $strLogo = '<div style="position:absolute;overflow:hidden;top:30px; left:0px;width:350px;text-align:center;"><h1>'.$objSistemaDTO->getStrSigla().'</h1></div>';;
    }
} catch (Exception $e) {
    PaginaSip::getInstance()->processarExcecao($e);
}

function montarImagemAjuda($strImagem, $strTitle, $strLogo = null, $strExtra = null)
{
    $ret = '';
    $ret .= '<p class="imagem">';
    $ret .= '<div style="position:relative;left:10%;"><img src="ajuda/'.$strImagem.'" title="'.$strTitle.'" />';

    if ($strLogo != null) {
        $ret .= $strLogo;
    }

    if ($strExtra != null) {
        $ret .= $strExtra;
    }

    $ret .= '</div>';
    $ret .= '</p>';
    return $ret;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="pt-br">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Pragma" content="no-cache"/>
  <title>SIP - Autenticação em 2 Fatores</title>
  <style type="text/css">
      <!--
      /*--><![CDATA[/
      ><!--*/

      .titulo1 {
          font-family: Verdana, Arial, Helvetica, Sans;
          font-size: 16px;
          font-weight: bold;
          color: #ffffff;
          background: #336699;
          position: relative;
          padding: 2pt;
          border-width: 0px;
      }

      .titulo2 {
          font-family: Verdana, Arial, Helvetica, Sans;
          font-size: 14px;
          font-weight: bold;
          color: #ffffff;
          background: #16609B;
          position: relative;
          padding: 6pt;
          border-width: 0px;
          border-radius: 4px;
      }

      .texto {
          font-family: Verdana, Arial, Helvetica, Sans;
          font-size: 14px;
          text-align: justify;
          text-indent: .6in;
          color: #000000;
          background: #FFFFFF;
          position: relative;
          padding: 5pt;
          border-width: 0px;
      }

      p.imagem {
          position: relative;
          left: 10%;
      }

      /*]]>*/
      -->
  </style>
</head>
<body>
<br/>

<p style='text-align:center;font-weight:bold;font-size:16pt;'>Autenticação em 2 Fatores</p>

<blockquote>
  <blockquote>
    <p class="texto">
      A autenticação em 2 fatores, ou 2FA, fornece segurança adicional, pois junta algo que você sabe (a sua senha) com
      algo que você possui (o seu smartphone). Somente com a combinação dos dois será possível efetuar o login. Após
      validar a senha, será preciso informar um código de 6 dígitos, que será gerado pelo aplicativo no smartphone.
    </p>
  </blockquote>
</blockquote>

<blockquote>

  <div class="titulo2">1. Gerando um Código para Ativação</div>
  <blockquote>

    <p class="texto">
      Na tela de login do sistema, após informar seu usuário e senha, clique no link "Autenticação em dois fatores":
    </p>

      <?= montarImagemAjuda('2fa_login.png', 'Login', $strLogo) ?>

    <p class="texto">
      Clique em Prosseguir na tela de apresentação da autenticação em dois fatores:
    </p>

      <?= montarImagemAjuda('2fa_prosseguir.png', 'Prosseguir', $strLogo) ?>

    <p class="texto">
      A mensagem abaixo será exibida e se você nunca fez este procedimento apenas ignore-a:
    </p>

      <?= montarImagemAjuda('2fa_remover_conta.png', 'Remover Conta') ?>

  </blockquote>

  <div class="titulo2">2. Instalação do Aplicativo de Autenticação</div>
  <blockquote>
    <p class="texto">
      Será gerado um código QR como este:
    </p>

      <?= montarImagemAjuda('2fa_geracao.png', 'Geração do código QR', $strLogo) ?>

    <p class="texto">
      Para lê-lo, instale em seu smartphone um aplicativo próprio para autenticação em duas etapas, como o Google
      Authenticator, Microsoft Authenticator, FreeOTP, Authy, etc. Os exemplos abaixo usam o Google Authenticator.
      Acesse a Apple Store ou o Google Play para instalar.
    </p>

    <p class="imagem">
      <a target="_blank" href="https://www.apple.com/br/ios/app-store"><img src="ajuda/app_store.png" width="150"
                                                                            title="Apple Store"/></a>
      <a target="_blank" href="https://play.google.com/store/apps?hl=pt_BR"><img src="ajuda/google_play.jpg" width="150"
                                                                                 title="Google Play"/></a>
    </p>

  </blockquote>

  <div class="titulo2">3. Leitura do Código</div>
  <blockquote>

    <p class="texto">
      Abra o aplicativo Google Authenticator:
    </p>

    <p class="imagem">
      <img src="ajuda/google_authenticator_icone.png" title="Google Authenticator" width="100"/>
    </p>

    <p class="texto">
      Encontre a opção para leitura de código. Pode ser necessário permitir que o aplicativo tenha acesso a câmera do
      smartphone:
    </p>

    <p class="imagem">
      <img src="ajuda/google_authenticator1.png" title="Google Authenticator"/>
      &nbsp;
      <img src="ajuda/google_authenticator2.png" title="Google Authenticator"/>
      &nbsp;
      <img src="ajuda/google_authenticator3.png" title="Google Authenticator"/>
    </p>

    <p class="texto">
      Aponte a câmera para o código QR que está sendo exibido na tela e adicione a conta no aplicativo.
    </p>
  </blockquote>


  <div class="titulo2">4. Configuração Manual do Código</div>
  <blockquote>

    <p class="texto">
      <b>Execute este passo apenas se você não consegue ler o código QR.</b> Por exemplo, se estiver acessando esta
      página pelo smartphone ou se a câmera do seu celular não estiver funcionando. No aplicativo localize a opção
      "Entrada manual" ou "Inserir chave de configuração":
    </p>

    <p class="imagem">
      <img src="ajuda/google_authenticator1.png" title="Google Authenticator"/>
      &nbsp;
      <img src="ajuda/google_authenticator4.png" title="Google Authenticator"/>
    </p>

    <p class="texto">
      Clique sobre o código alfanumérico que está sendo exibido logo abaixo do código QR para copiá-lo. Em seguida,
      cole-o no aplicativo de autenticação e clique em "Adicionar":
    </p>

      <?= montarImagemAjuda(
          '2fa_copiar_codigo.png',
          'Copiar Chave de Configuração',
          $strLogo,
          '&nbsp;&nbsp;<img src="ajuda/google_authenticator5.png" title="Google Authenticator" />'
      ) ?>


  </blockquote>

  <div class="titulo2">5. Finalização do Cadastro</div>
  <blockquote>
    <p class="texto">
      Informe um endereço de e-mail que <b>não seja associado com a instituição</b>. Por exemplo, pode ser do gmail,
      hotmail, yahoo, etc. É imprescindível que a senha de acesso ao e-mail seja diferente da senha de acesso ao
      sistema:
    </p>

      <?= montarImagemAjuda('2fa_email.png', 'Informar E-mail', $strLogo) ?>

    <p class="texto">
      Clique em "Enviar" para que um link de ativação seja enviado para o endereço de e-mail fornecido. Somente após
      receber o e-mail e clicar no link é que o mecanismo de autenticação em 2 fatores estará ativado.
    </p>
  </blockquote>

  <div class="titulo2">6. Login com a Autenticação em 2 Fatores</div>
  <blockquote>
    <p class="texto">
      Se a autenticação em 2 fatores estiver ativada, então, após informar o usuário e senha, será exibida outra tela
      solicitando o código numérico. Abra o aplicativo de autenticação no seu smartphone e veja o código gerado. Informe
      o valor no campo Código de Acesso e clique em Validar:
    </p>

      <?= montarImagemAjuda(
          '2fa_validacao.png',
          'Validação do Código QR',
          $strLogo,
          '<img src="ajuda/google_authenticator.png" title="Google Authenticator" height="300" style="margin-left:50px;"/>'
      ) ?>

    <!--
    <p class="imagem">
      <img src="google_authenticator.png" title="Google Authenticator" height="300" />
    </p>
    -->

    <p class="texto">
      De agora em diante, sempre que fizer login, será preciso consultar o seu smartphone, porque o código muda a cada
      30 segundos. O sistema aceitará qualquer um dos códigos gerados nos últimos 90 segundos por isso é importante que
      o seu smartphone esteja com o horário correto.
    </p>
  </blockquote>

  <div class="titulo2">Liberando Dispositivos</div>
  <blockquote>
    <p class="texto">
      Para dispositivos usados com frequência, pode ser conveniente liberá-los da validação a cada login. Para isso, na
      tela onde é solicitado o código numérico, marque a opção "Não usar 2FA neste dispositivo e navegador". Essa
      sinalização precisará ser realizada para cada navegador utilizado. O código poderá ser solicitado novamente se for
      feita a limpeza dos cookies do navegador ou se a liberação perder a validade de acordo com o período estabelecido
      pela instituição.
    </p>
  </blockquote>

  <div class="titulo2">Cancelando Dispositivos Liberados</div>
  <blockquote>
    <p class="texto">
      Para cancelar as liberações, em todos os dispositivos, acesse o link "Autenticação em 2 fatores" disponível na
      tela inicial de login e clique no botão "Cancelar Dispositivos Liberados":
    </p>

      <?= montarImagemAjuda('2fa_cancelar_dispositivos.png', 'Cancelamento Dispositivos Liberados', $strLogo) ?>

  </blockquote>

  <div class="titulo2">Desativando a Autenticação em 2 Fatores</div>
  <blockquote>
    <p class="texto">
      Se não conseguir validar o código por algum motivo (perda do aparelho, defeito, roubo, erro no aplicativo, etc.),
      é possível requisitar a desativação da autenticação em 2 fatores na mesma tela onde é solicitado o código
      numérico, ou então por meio do link "Autenticação em 2 fatores" disponível na tela inicial de login. Clique no
      botão "Desativar 2FA" para que um e-mail com o link de desativação seja enviado para o endereço que foi fornecido
      no momento da leitura do código QR. Somente após receber o e-mail e clicar no link é que o mecanismo de
      autenticação em 2 fatores será desativado.
    </p>
  </blockquote>


  <div class="titulo2">Solução de Problemas</div>
  <blockquote>
    <p class="texto">
      Caso esteja recebendo a mensagem "Código inválido." ou "Código não reconhecido.", é possível que o horário no seu
      smartphone esteja desatualizado.

      Primeiro verifique se o aparelho está configurado para obter a hora automaticamente pela rede. Abaixo estão
      exemplos de como fazer isso em diferentes sistemas.
    </p>
    <p class="imagem">
      <img src="ajuda/2fa_data_1.png" title="Ajuste Data" style="margin-left:50px;border:1px solid #d0d0d0;"/>
      <img src="ajuda/2fa_data_2.png" title="Ajuste Data" style="margin-left:50px;border:1px solid #d0d0d0;"/>
      <img src="ajuda/2fa_data_3.png" title="Ajuste Data" style="margin-left:50px;border:1px solid #d0d0d0;"/>
    </p>

    <p class="texto">
      Após, apenas em dispositivos Android, também é necessário seguir os passos abaixo para sincronizar o horário no
      Google Authenticator:
    </p>
    <p class="imagem">
      <img src="ajuda/2fa_hora_1.png" title="Ajuste Hora" style="margin-left:50px;border:1px solid #d0d0d0;"/>
      <img src="ajuda/2fa_hora_2.png" title="Ajuste Hora" style="margin-left:50px;border:1px solid #d0d0d0;"/>
      <img src="ajuda/2fa_hora_3.png" title="Ajuste Hora" style="margin-left:50px;border:1px solid #d0d0d0;"/>
    </p>

  </blockquote>


</blockquote>
</body>
</html>

