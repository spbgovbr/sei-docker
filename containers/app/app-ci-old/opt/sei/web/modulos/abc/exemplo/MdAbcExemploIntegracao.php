<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 29/04/2016 - criado por mga@trf4.jus.br
 *
 */

 /*
 No SIP criar os recursos md_abc_processo_processar, md_abc_documento_processar e md_abc_andamento_lancar e adicionar em um novo perfil chamado MD_ABC_Básico.
*/

class MdAbcExemploIntegracao extends SeiIntegracao{

  public function __construct(){
  }

  public function getNome(){
    return 'Módulo de exemplos ABC';
  }

  public function getVersao() {
    return '1.0.0';
  }

  public function getInstituicao(){
    return 'TRF4 - Tribunal Regional Federal da 4ª Região';
  }

  public function inicializar($strVersaoSEI){
    /*
    if (substr($strVersaoSEI, 0, 2) != '3.'){
      die('Módulo "'.$this->getNome().'" ('.$this->getVersao().') não é compatível com esta versão do SEI ('.$strVersaoSEI.').');
    }
    */
  }

  public function montarIconeSistema(){

    $arrIcones = array();

    if (SessaoSEI::getInstance()->verificarPermissao('md_abc_processo_processar')) {
      $arrIcones[] = '<a class="align-self-center d-none d-md-block" href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_abc_processo_processar').'" title="Botão Sistema Padrão ABC" tabindex="'.PaginaSEI::getInstance()->getProxTabBarraSistema().'">
                <img src="modulos/abc/exemplo/svg/sistema.svg" class="infraImg" title="Botão Sistema Padrão ABC" />
                </a>
            
               <span title="Controle de Processos"  class="nav-link d-flex d-md-none" >
                 <img src="modulos/abc/exemplo/svg/sistema.svg" class="infraImg" title="Botão Sistema Móvel ABC" />
                 <a class="align-self-center text-white pl-1"  href="'.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_abc_processo_processar').'" title="Botão Sistema Móvel ABC" tabindex="' . PaginaSEI::getInstance()->getProxTabBarraSistema() . '" >
                  Botão Sistema Móvel ABC
                 </a>
               </span>';
    }
    return $arrIcones;
  }

  public function montarBotaoControleProcessos(){

    $arrBotoes = array();

    /*
     Função javascript disponível na página de Controle de Processos:
     acaoControleProcessos(link, requerSelecionado, aceitaSigiloso)

     onde:
     link - link para a página
     requerSelecionado - true/false, indica necessidade ou não de selecionar processos para executar a acao
     aceitaSigiloso - true/false, indica se o usuario podera selecionar processos sigilosos
    */

    if (SessaoSEI::getInstance()->verificarPermissao('md_abc_andamento_lancar')) {
      $arrBotoes[] = '<a href="#" onclick="return acaoControleProcessos(\''  . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_abc_andamento_lancar&acao_origem=procedimento_controlar&acao_retorno=procedimento_controlar') . '\', true, false);" tabindex="' . PaginaSEI::getInstance()->getProxTabBarraComandosSuperior() . '" ><img  src="modulos/abc/exemplo/svg/exemplo.svg" alt="Botão Controle de Processos ABC" title="Botão Controle de Processos ABC" /></a>';
    }

    return $arrBotoes;
  }

  public function montarIconeControleProcessos($arrObjProcedimentoAPI){

    $arrIcones = array();

    foreach($arrObjProcedimentoAPI as $objProcedimentoAPI) {
      $arrIcones[$objProcedimentoAPI->getIdProcedimento()][] = '<a href="javascript:void(0);" '.PaginaSEI::montarTitleTooltip('Ícone Controle de Processos ABC','Módulo ABC').'><img src="modulos/abc/exemplo/svg/exemplo.svg" class="imagemStatus" /></a>';
    }

    return $arrIcones;
  }

  public function montarIconeAcompanhamentoEspecial($arrObjProcedimentoAPI){

    $arrIcones = array();

    foreach($arrObjProcedimentoAPI as $objProcedimentoAPI) {
      $arrIcones[$objProcedimentoAPI->getIdProcedimento()][] = '<a href="javascript:void(0);" '.PaginaSEI::montarTitleTooltip('Ícone Acompanhamento Especial ABC','Módulo ABC').'><img src="modulos/abc/exemplo/svg/exemplo.svg" class="imagemStatus" /></a>';
    }

    return $arrIcones;
  }

  public function montarIconeProcesso(ProcedimentoAPI $objProcedimentoAPI){

    $arrObjArvoreAcaoItemAPI = array();

    if (SessaoSEI::getInstance()->verificarPermissao('md_abc_processo_processar') && $objProcedimentoAPI->getCodigoAcesso() > 0 && $objProcedimentoAPI->getSinAberto()=='S') {

      $dblIdProcedimento = $objProcedimentoAPI->getIdProcedimento();

      $objArvoreAcaoItemAPI = new ArvoreAcaoItemAPI();
      $objArvoreAcaoItemAPI->setTipo('MD_ABC_PROCESSO');
      $objArvoreAcaoItemAPI->setId('MD_ABC_PROC_' . $dblIdProcedimento);
      $objArvoreAcaoItemAPI->setIdPai($dblIdProcedimento);
      $objArvoreAcaoItemAPI->setTitle('Ícone Processo ABC');
      $objArvoreAcaoItemAPI->setIcone('modulos/abc/exemplo/svg/exemplo.svg');

      $objArvoreAcaoItemAPI->setTarget(null);
      $objArvoreAcaoItemAPI->setHref('javascript:alert(\'Ícone Processo ABC\');');

      //$objArvoreAcaoItemAPI->setTarget('_blank');
      //$objArvoreAcaoItemAPI->setTarget('ifrVisualizacao');
      //$objArvoreAcaoItemAPI->setHref(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_abc_processo_processar&id_procedimento=' . $dblIdProcedimento . '&arvore=1'));

      $objArvoreAcaoItemAPI->setSinHabilitado('S');

      $arrObjArvoreAcaoItemAPI[] = $objArvoreAcaoItemAPI;
    }

    return $arrObjArvoreAcaoItemAPI;
  }

  public function montarBotaoProcesso(ProcedimentoAPI $objProcedimentoAPI){

    $arrBotoes = array();

    if (SessaoSEI::getInstance()->verificarPermissao('md_abc_processo_processar') && $objProcedimentoAPI->getSinAberto()=='S' && $objProcedimentoAPI->getCodigoAcesso() > 0) {
      $arrBotoes[] = '<a href="#" onclick="location.href=\\\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_abc_processo_processar&id_procedimento=' . $objProcedimentoAPI->getIdProcedimento() . '&arvore=1') . '\\\';" tabindex="' . PaginaSEI::getInstance()->getProxTabBarraComandosSuperior() . '" ><img  src="modulos/abc/exemplo/svg/exemplo.svg" alt="Botão Processo ABC" title="Botão Processo ABC" /></a>';
    }

    return $arrBotoes;
  }

  public function montarIconeDocumento(ProcedimentoAPI $objProcedimentoAPI, $arrObjDocumentoAPI){

    $arrIcones = array();

    if ($objProcedimentoAPI->getCodigoAcesso() > 0 &&  $objProcedimentoAPI->getSinAberto()=='S') {

      $bolAcaoAbcDocumentoProcessar = SessaoSEI::getInstance()->verificarPermissao('md_abc_documento_processar');

      foreach ($arrObjDocumentoAPI as $objDocumentoAPI) {

        if ($objDocumentoAPI->getCodigoAcesso() > 0) {

          $dblIdDocumento = $objDocumentoAPI->getIdDocumento();

          $arrIcones[$dblIdDocumento] = array();

          if ($bolAcaoAbcDocumentoProcessar) {
            $objArvoreAcaoItemAPI = new ArvoreAcaoItemAPI();
            $objArvoreAcaoItemAPI->setTipo('MD_ABC_DOCUMENTOS');
            $objArvoreAcaoItemAPI->setId('MD_ABC_DOC_' . $dblIdDocumento);
            $objArvoreAcaoItemAPI->setIdPai($dblIdDocumento);
            $objArvoreAcaoItemAPI->setTitle('Ícone Documento ABC');
            $objArvoreAcaoItemAPI->setIcone('modulos/abc/exemplo/svg/exemplo.svg');

            $objArvoreAcaoItemAPI->setTarget(null);
            $objArvoreAcaoItemAPI->setHref('javascript:alert(\'Ícone Documento ABC\');');

            //$objArvoreAcaoItemAPI->setTarget('_blank');
            //$objArvoreAcaoItemAPI->setTarget('ifrVisualizacao');
            //$objArvoreAcaoItemAPI->setHref(SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_abc_documento_processar&id_procedimento=' . $dblIdProcedimento . '&id_documento=' . $dblIdDocumento . '&arvore=1'));

            $objArvoreAcaoItemAPI->setSinHabilitado('S');

            $arrIcones[$dblIdDocumento][] = $objArvoreAcaoItemAPI;
          }
        }
      }
    }

    return $arrIcones;
  }

  public function montarBotaoDocumento(ProcedimentoAPI $objProcedimentoAPI, $arrObjDocumentoAPI){

    $arrBotoes = array();

    if ($objProcedimentoAPI->getCodigoAcesso() > 0 && $objProcedimentoAPI->getSinAberto()=='S'){

      $dblIdProcedimento = $objProcedimentoAPI->getIdProcedimento();

      $bolAcaoAbcDocumentoProcessar = SessaoSEI::getInstance()->verificarPermissao('md_abc_documento_processar');
      //$bolAcaoAbcDocumentoProcessar2 = SessaoSEI::getInstance()->verificarPermissao('md_abc_documento_processar2');
      //$bolAcaoAbcDocumentoProcessar3 = SessaoSEI::getInstance()->verificarPermissao('md_abc_documento_processar3');
      //$bolAcaoAbcDocumentoProcessarN = SessaoSEI::getInstance()->verificarPermissao('md_abc_documento_processarN');

      foreach ($arrObjDocumentoAPI as $objDocumentoAPI) {

        if ($objDocumentoAPI->getCodigoAcesso() > 0) {

          $dblIdDocumento = $objDocumentoAPI->getIdDocumento();

          $arrBotoes[$dblIdDocumento] = array();

          if ($bolAcaoAbcDocumentoProcessar) {
            $arrBotoes[$dblIdDocumento][] = '<a href="#" onclick="location.href=\\\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=md_abc_documento_processar&id_procedimento=' . $dblIdProcedimento . '&id_documento=' . $dblIdDocumento . '&arvore=1') . '\\\';" tabindex="' . PaginaSEI::getInstance()->getProxTabBarraComandosSuperior() . '" ><img  src="modulos/abc/exemplo/svg/exemplo.svg" alt="Botão Documento ABC" title="Botão Documento ABC" /></a>';
          }

          /*
          if ($bolAcaoAbcDocumentoProcessar2) {
            $arrBotoes[$dblIdDocumento][] = '...';
          }

          if ($bolAcaoAbcDocumentoProcessar3) {
            $arrBotoes[$dblIdDocumento][] = '...';
          }

          if ($bolAcaoAbcDocumentoProcessarN) {
            $arrBotoes[$dblIdDocumento][] = '...';
          }
          */

        }
      }
    }

    return $arrBotoes;
  }

  public function alterarIconeArvoreDocumento(ProcedimentoAPI $objProcedimentoAPI, $arrObjDocumentoAPI){
    $arrIcones = array();

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numIdSerieAbc = $objInfraParametro->getValor('MD_ABC_ID_SERIE_TESTE', false);

    foreach ($arrObjDocumentoAPI as $objDocumentoAPI) {
      if ($objDocumentoAPI->getIdSerie()==$numIdSerieAbc) {
        $arrIcones[$objDocumentoAPI->getIdDocumento()] = 'modulos/abc/exemplo/svg/exemplo.svg';
      }
    }

    return $arrIcones;
  }

  public function adicionarElementoMenu(){
    return '<div style="text-align:center;"><img src="modulos/abc/exemplo/svg/exemplo.svg" alt="Imagem Menu" title="Imagem Menu" width="150" height="150" /></div>';
  }

  public function montarMenuPublicacoes(){

    $strURL = ConfiguracaoSEI::getInstance()->getValor('SEI','URL');

    $arrMenu = array();
    $arrMenu[] = '-^'.$strURL.'/publicacoes/controlador_publicacoes.php?acao=md_abc_publicacao_exemplo^Formulário Exemplo ABC - Publicações^Publicação ABC';
    $arrMenu[] = '-^#^Sites de busca sugeridos^Sites de Busca';
    $arrMenu[] = '--^http://www.google.com^Página do Google^Google^_blank';
    $arrMenu[] = '--^http://br.search.yahoo.com^Página do Yahoo!^Yahoo!^_blank';
    return $arrMenu;
  }

  public function montarMenuUsuarioExterno(){

    $strURL = ConfiguracaoSEI::getInstance()->getValor('SEI','URL');

    $arrMenu = array();
    $arrMenu[] = '-^'.$strURL.'/controlador_externo.php?acao=md_abc_usuario_externo_exemplo^Formulário Exemplo ABC - Usuário Externo^Usuário Externo ABC';
    $arrMenu[] = '-^#^Sites de busca sugeridos^Sites de Busca';
    $arrMenu[] = '--^http://www.google.com^Página do Google^Google^_blank';
    $arrMenu[] = '--^http://br.search.yahoo.com^Página do Yahoo!^Yahoo!^_blank';
    return $arrMenu;
  }

  public function montarAcaoControleAcessoExterno($arrObjAcessoExternoAPI){

    $arrIcones = array();

    foreach($arrObjAcessoExternoAPI as $objAcessoExternoAPI) {
      $arrIcones[$objAcessoExternoAPI->getIdAcessoExterno()][] = '<a href="javascript:void(0);" '.PaginaSEI::montarTitleTooltip('Ícone Ação Acesso Externo ABC','Módulo ABC').'><img src="modulos/abc/exemplo/svg/exemplo.svg" class="imagemStatus" /></a>';
    }

    return $arrIcones;
  }

  public function montarAcaoDocumentoAcessoExternoAutorizado($arrObjDocumentoAPI){
    $arrIcones = array();
    foreach($arrObjDocumentoAPI as $objDocumentoAPI) {
      $arrIcones[$objDocumentoAPI->getIdDocumento()][] = '<a href="javascript:void(0);" '.PaginaSEI::montarTitleTooltip('Ícone Ação Documento Acesso Externo Autorizado ABC','Módulo ABC').'><img src="modulos/abc/exemplo/svg/exemplo.svg" class="imagemStatus" /></a>';
    }
    return $arrIcones;
  }

  public function montarAcaoDocumentoAcessoExternoNegado($arrObjDocumentoAPI){
    $arrIcones = array();
    foreach($arrObjDocumentoAPI as $objDocumentoAPI) {
      $arrIcones[$objDocumentoAPI->getIdDocumento()][] = '<a href="javascript:void(0);" '.PaginaSEI::montarTitleTooltip('Ícone Ação Documento Acesso Externo Negado ABC','Módulo ABC').'><img src="modulos/abc/exemplo/svg/exemplo.svg" class="imagemStatus" /></a>';
    }
    return $arrIcones;
  }

  public function montarAcaoProcessoAnexadoAcessoExternoAutorizado($arrObjProcedimentoAPI){
    $arrIcones = array();
    foreach($arrObjProcedimentoAPI as $objProcedimentoAPI) {
      $arrIcones[$objProcedimentoAPI->getIdProcedimento()][] = '<a href="javascript:void(0);" '.PaginaSEI::montarTitleTooltip('Ícone Ação Processo Anexado Acesso Externo Autorizado ABC','Módulo ABC').'><img src="modulos/abc/exemplo/svg/exemplo.svg" class="imagemStatus" /></a>';
    }
    return $arrIcones;
  }

  public function montarAcaoProcessoAnexadoAcessoExternoNegado($arrObjProcedimentoAPI){
    $arrIcones = array();
    foreach($arrObjProcedimentoAPI as $objProcedimentoAPI) {
      $arrIcones[$objProcedimentoAPI->getIdProcedimento()][] = '<a href="javascript:void(0);" '.PaginaSEI::montarTitleTooltip('Ícone Ação Processo Anexado Acesso Externo Negado ABC','Módulo ABC').'><img src="modulos/abc/exemplo/svg/exemplo.svg" class="imagemStatus" /></a>';
    }
    return $arrIcones;
  }

  public function montarBotaoAcessoExternoAutorizado(ProcedimentoAPI $objProcedimentoAPI){
    $arrBotoes = array();
    $arrBotoes[] = '<button type="button" id="btnExemploABC1" name="btnExemploABC1" value="ABC 1" class="infraButton">ABC 1</button>';
    $arrBotoes[] = '<button type="button" id="btnExemploABC2" name="btnExemploABC2" value="ABC 2" class="infraButton">ABC 2</button>';
    return $arrBotoes;
  }

  public function montarBotaoControleAcessoExterno(){
    $arrBotoes = array();
    $arrBotoes[] = '<button type="button" id="btnExemploABC1" name="btnExemploABC1" value="ABC 1" class="infraButton">ABC 1</button>';
    $arrBotoes[] = '<button type="button" id="btnExemploABC2" name="btnExemploABC2" value="ABC 2" class="infraButton">ABC 2</button>';
    return $arrBotoes;
  }

  public function montarAcaoPublicacao($arrObjPublicacaoAPI){
    $arrIcones = array();
    foreach($arrObjPublicacaoAPI as $objPublicacaoAPI) {
      $arrIcones[$objPublicacaoAPI->getIdPublicacao()][] = '<a href="javascript:void(0);" '.PaginaSEI::montarTitleTooltip('Ícone Ação Publicação ABC','Módulo ABC').'><img src="modulos/abc/exemplo/svg/exemplo.svg" class="imagemStatus" /></a>';
    }
    return $arrIcones;
  }

  public function montarAcaoVeiculoPublicacao($arrObjVeiculoPublicacaoDTO){
    $arrIcones = array();
    foreach($arrObjVeiculoPublicacaoDTO as $objVeiculoPublicacaoAPI) {
      $arrIcones[$objVeiculoPublicacaoAPI->getIdVeiculoPublicacao()][] = '<a href="javascript:void(0);" '.PaginaSEI::montarTitleTooltip('Ícone Ação Veículo Publicação AB-C','Módulo ABC').'><img src="modulos/abc/exemplo/svg/exemplo.svg" class="imagemStatus" /></a>';
    }
    return $arrIcones;
  }

  public function montarBotaoVeiculoPublicacao($arrObjVeiculoPublicacaoAPI){
    $arrBotoes = array();
    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numIdVeiculoPublicacaoAbc = $objInfraParametro->getValor('MD_ABC_ID_VEICULO_PUBLICACAO', false);
    foreach($arrObjVeiculoPublicacaoAPI as $objVeiculoPublicacaoAPI) {
      if (true || $objVeiculoPublicacaoAPI->getIdVeiculoPublicacao()==$numIdVeiculoPublicacaoAbc) {
        $arrBotoes[$objVeiculoPublicacaoAPI->getIdVeiculoPublicacao()]['btnMdABCExemplo1'] = '<button type="button" id="btnMdABCExemplo1" name="btnMdABCExemplo1" onclick="alert(\'Alert ABC 1\');" value="ABC 1" class="infraButton">ABC 1</button>';
        $arrBotoes[$objVeiculoPublicacaoAPI->getIdVeiculoPublicacao()]['btnMdABCExemplo2'] = '<button type="button" id="btnMdABCExemplo2" name="btnMdABCExemplo2" on-click="if(this.form.onsubmit()){this.form.action=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=....').'\';this.form.submit();}" value="ABC 2" class="infraButton">ABC 2</button>';
      }
    }
    return $arrBotoes;
  }

  public function montarDadosImprensaNacional(PublicacaoAPI $objPublicacaoAPI){
    //return 'Texto Imprensa Nacional ABC';
  }

  public function montarTextoInformativoPublicacao(PublicacaoAPI $objPublicacaoAPI){
    return 'Texto Informativo Publicação ABC';
  }

  public function obterProximaDataPublicacao(PublicacaoAPI $objPublicacaoAPI){
    $ret = null;
    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numIdVeiculoPublicacaoAbc = $objInfraParametro->getValor('MD_ABC_ID_VEICULO_PUBLICACAO', false);
    if (true || $objPublicacaoAPI->getIdVeiculoPublicacao()==$numIdVeiculoPublicacaoAbc) {
      //busca proximo dia que nao seja fim de semana
      $ret = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, InfraData::getStrDataAtual());
      while(InfraData::obterDescricaoDiaSemana($ret)=='sábado' || InfraData::obterDescricaoDiaSemana($ret)=='domingo'){
        $ret = InfraData::calcularData(1, InfraData::$UNIDADE_DIAS, InfraData::$SENTIDO_ADIANTE, $ret);
      }
    }
    return $ret;
  }


  public function processarControlador($strAcao){

    switch($strAcao) {

      case 'md_abc_processo_processar':
        require_once dirname(__FILE__).'/processo_exemplo.php';
        return true;

      case 'md_abc_documento_processar':
        require_once dirname(__FILE__).'/documento_exemplo.php';
        return true;

      case 'md_abc_andamento_lancar':
        require_once dirname(__FILE__).'/controle_processos_exemplo.php';
        return true;
    }

    return false;
  }

  public function processarControladorAjax($strAcao){

    $xml = null;

    switch($strAcao) {
      case 'md_abc_auto_completar':
        $arrObjAssuntoDTO = AssuntoINT::autoCompletarAssuntosRI1223($_POST['palavras_pesquisa']);
        $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjAssuntoDTO,'IdAssunto', 'CodigoEstruturado');
        break;
    }
    return $xml;
  }

  public function processarControladorPublicacoes($strAcao){

    switch($strAcao) {

      case 'md_abc_publicacao_exemplo':
        require_once dirname(__FILE__) . '/publicacao_exemplo.php';
        return true;
    }

    return false;
  }

  public function processarControladorExterno($strAcao){

    switch($strAcao) {

      case 'md_abc_usuario_externo_exemplo':
        require_once dirname(__FILE__) . '/usuario_externo_exemplo.php';
        return true;
    }

    return false;
  }

  public function verificarAcessoProtocolo($arrObjProcedimentoAPI, $arrObjDocumentoAPI){

    $ret = null;

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numIdSerieAbc = $objInfraParametro->getValor('MD_ABC_ID_SERIE_TESTE', false);

    foreach($arrObjDocumentoAPI as $objDocumentoAPI){
      if ($objDocumentoAPI->getIdSerie() == $numIdSerieAbc &&
          $objDocumentoAPI->getSubTipo() == DocumentoRN::$TD_EDITOR_INTERNO &&
          $objDocumentoAPI->getNivelAcesso() != ProtocoloRN::$NA_SIGILOSO){

        $ret[$objDocumentoAPI->getIdDocumento()] = SeiIntegracao::$TAM_PERMITIDO;
      }
    }

    return $ret;
  }

  public function verificarAcessoProtocoloExterno($arrObjProcedimentoAPI, $arrObjDocumentoAPI){

    $ret = null;

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numIdSerieAbc = $objInfraParametro->getValor('MD_ABC_ID_SERIE_TESTE', false);

    foreach($arrObjDocumentoAPI as $objDocumentoAPI){

      if ($objDocumentoAPI->getIdSerie() == $numIdSerieAbc && $objDocumentoAPI->getSinPublicado()=='N'){
        $ret[$objDocumentoAPI->getIdDocumento()] = SeiIntegracao::$TAM_NEGADO;
      }
    }

    return $ret;
  }

  public function montarMensagemProcesso(ProcedimentoAPI $objProcedimentoAPI){
    $strMsg = null;

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $numIdTipoProcAbc = $objInfraParametro->getValor('MD_ABC_ID_TIPO_PROCEDIMENTO_TESTE', false);

    if ($objProcedimentoAPI->getSinAberto()=='S' && $objProcedimentoAPI->getIdTipoProcedimento()==$numIdTipoProcAbc) {
      $strMsg = 'Mensagem do módulo ABC...';
    }

    return $strMsg;
  }

}
?>