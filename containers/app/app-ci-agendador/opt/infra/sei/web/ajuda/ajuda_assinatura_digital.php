<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/03/2010 - criado por mga
 *
 * Versão do Gerador de Código: 1.29.1
 *
 * Versão no CVS: $Id$
 */

try {
  require_once dirname(__FILE__).'/../SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  PaginaSEI::getInstance()->setTipoPagina(PaginaSEI::$TIPO_PAGINA_SIMPLES);

  switch($_GET['acao']){

    case 'assinatura_digital_ajuda':
      $strTitulo = '';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - Novidades');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->fecharHead();
?>
<body id="bodyAjuda">
<div>
<blockquote>

    <br />

    <p style='text-align:center;font-weight:bold;font-size:16pt;'>Assinatura com Certificado Digital</p>

    <div class="ajudaTitulo">Realizando Assinatura com Certificado Digital</div>
    <blockquote>

      <div class="ajudaTexto">

      <p>a) Na tela de Assinatura de Documentos do SEI clique na opção "Certificado Digital":</p>

      <p><img border=0 width="50%" height="50%" src="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/ajuda/ajuda_assinatura_digital/assinatura_01.png"></p>

      <p>b) Na próxima tela clique no botão "Disponibilizar dados para o assinador":</p>

      <p><img border=0 width="50%" height="50%" src="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/ajuda/ajuda_assinatura_digital/assinatura_02.png"></p>

      <p>c) No programa Assinador de Documentos com Certificado Digital do SEI solicite o processamento clicando no botão "Processar dados de assinatura...":</p>

      <p><img border=0 width="50%" height="50%" src="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/ajuda/ajuda_assinatura_digital/assinador_02.png"></p>

      <p>d) Após a exibição dos documentos proceda com a assinatura por meio do botão "Assinar Documentos":</p>

      <p><img border=0 width="50%" height="50%" src="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/ajuda/ajuda_assinatura_digital/assinador_03.png"></p>

      <p><i>OBS: o programa assinador pode ser mantido aberto para realização de outras assinaturas.</i></p>

      </div>
  </blockquote>


  <div class="ajudaTitulo">Instalando o Assinador de Documentos com Certificado Digital do SEI</div>
  <blockquote>
    <div class="ajudaTexto">
      <p>É necessário que o Java 8 ou superior esteja instalado.</p>

      <p>Executar os procedimentos abaixo de acordo com o Sistema Operacional utilizado:</p>
      <ul>
        <li><b>Windows</b></li>

        <p>a) Clique <a target="_blank" href="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/assinador/instalacao_assinador_sei_<?=ASSINADOR_VERSAO?>.exe">aqui</a> para fazer o download da versão <?=ASSINADOR_VERSAO?> do programa de instalação;</p>

        <p>b) Após execute o arquivo baixado:</p>
        <p><img border=0 width="50%" height="50%" src="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/ajuda/ajuda_assinatura_digital/setup_windows_01.png"></p>
        <p><img border=0 width="50%" height="50%" src="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/ajuda/ajuda_assinatura_digital/setup_windows_02.png"></p>
        <p><img border=0 width="50%" height="50%" src="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/ajuda/ajuda_assinatura_digital/setup_windows_03.png"></p>
        <p><img border=0 width="50%" height="50%" src="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/ajuda/ajuda_assinatura_digital/setup_windows_04.png"></p>

        <p>c) Se tudo estiver correto o assinador será executado automaticamente após a instalação:</p>
        <p><img border=0 width="50%" height="50%" src="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/ajuda/ajuda_assinatura_digital/assinador_01.png"></p>

        <li><b>Linux ou MacOS</b></li>

        <p>Clique <a target="_blank" href="<?=ConfiguracaoSEI::getInstance()->getValor('SEI','URL')?>/assinador/assinador_sei_<?=ASSINADOR_VERSAO?>.jar">aqui</a> para fazer o download do arquivo JAR.</p>

        <p>Para executar com o Java Runtime faça um duplo clique sobre o arquivo ou utilize a linha de comando:</p>

        <pre>java -jar assinador_sei_<?=ASSINADOR_VERSAO?>.jar</pre>

        <p><i>OBS: esta opção também pode ser utilizada no Windows caso ocorra algum problema com o programa de instalação.</i></p>

      </ul>
    </div>
  </blockquote>
</blockquote>
</div>
</body>
<?
PaginaSEI::getInstance()->fecharHtml();
?>