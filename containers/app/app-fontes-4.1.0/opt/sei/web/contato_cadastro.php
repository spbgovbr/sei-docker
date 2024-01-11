<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO*
 *
 * 04/06/2018 - cjy - adicao dos campos numero_passaporte e id_pais_passaporte
 * 12/06/2018 - cjy - insercao de estado e cidade textualmente, para paises estrangeiros
 *
 */


try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  PaginaSEI::getInstance()->verificarSelecao('contato_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  SessaoSEI::getInstance()->setArrParametrosRepasseLink(array('id_tipo_contato', 'id_tipo_contato_associado', 'bloquear_tipo_contato'));

  if (isset($_POST['selTipoContato'])){
    $numIdTipoContato = $_POST['selTipoContato'];
  }else{
    $numIdTipoContato = $_GET['id_tipo_contato'];
  }

  if (isset($_POST['hdnContatoAssociadoIdentificador'])){
    $numIdContatoAssociado = $_POST['hdnContatoAssociadoIdentificador'];
  }else if (isset($_POST['hdnIdContatoAssociado'])){
    $numIdContatoAssociado = $_POST['hdnIdContatoAssociado'];
  }else{
    $numIdContatoAssociado = $_GET['id_contato_associado'];
  }

  if (isset($_POST['rdoNatureza'])){
    $strStaNatureza = $_POST['rdoNatureza'];
  }else{
    $strStaNatureza = $_GET['sta_natureza'];
  }

  if ($_GET['sin_endereco_associado']){
    $strSinEnderecoAssociado = $_GET['sin_endereco_associado'];
  }else{
    $strSinEnderecoAssociado = PaginaSEI::getInstance()->getCheckbox($_POST['chkSinEnderecoAssociado']);
  }

  if (isset($_POST['selPais'])){
    $numIdPais = $_POST['selPais'];
  }else{
    $numIdPais = $_GET['id_pais'];
  }

  if (isset($_POST['selCidade'])){
    $numIdCidade = $_POST['selCidade'];
  }else{
    $numIdCidade = $_GET['id_cidade'];
  }

  if (isset($_POST['selUf'])){
    $numIdUf = $_POST['selUf'];
  }else{
    $numIdUf = $_GET['id_uf'];
  }

  if (isset($_POST['selPaisPassaporte'])){
    $numIdPaisPassaporte = $_POST['selPaisPassaporte'];
  }else{
    $numIdPaisPassaporte = $_GET['id_pais_passaporte'];
  }

  $strSinBloquearIdentificacao = 'N';
  if (isset($_GET['bloquear_identificacao']) && $_GET['bloquear_identificacao']=='S'){
    $strSinBloquearIdentificacao = 'S';
  }

  $strSinBloquearExterno = 'N';
  if (isset($_GET['bloquear_externo']) && $_GET['bloquear_externo']=='S'){
    $strSinBloquearExterno = 'S';
  }

  $strSinBloquearTipoContato = 'N';
  if (isset($_GET['bloquear_tipo_contato']) && $_GET['bloquear_tipo_contato']=='S'){
    $strSinBloquearTipoContato = 'S';
  }

  if (isset($_POST['hdnContatoIdentificador'])) {

    PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

    if ($_POST['hdnContatoIdentificador']!='') {

      if (!is_numeric($_POST['hdnContatoIdentificador'])) {
        throw new InfraException('Identificador de contato inválido.');
      }

      PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setBolExclusaoLogica(false);
      $objContatoDTO->retNumIdTipoContato();
      $objContatoDTO->setNumIdContato($_POST['hdnContatoIdentificador']);

      $objContatoRN = new ContatoRN();
      $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);

      if ($objContatoDTO == null) {
        throw new InfraException('Contato não encontrado.');
      }

      $_GET['id_contato'] = $_POST['hdnContatoIdentificador'];

      if ($_GET['acao_origem'] != 'usuario_alterar') {

        $objPesquisaTipoContatoDTO = new PesquisaTipoContatoDTO();
        $objPesquisaTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_ALTERACAO);

        $objTipoContatoRN = new TipoContatoRN();

        if (!in_array($objContatoDTO->getNumIdTipoContato(), $objTipoContatoRN->pesquisarAcessoUnidade($objPesquisaTipoContatoDTO))) {
          $_GET['acao'] = 'contato_consultar';
        }
      }
    }
  }

  $objContatoDTO = new ContatoDTO();
  $objContatoRN = new ContatoRN();

  $strDesabilitar = '';

  $arrComandos = array();

  $bolCadastro = false;
  $numTipoConsulta = ContatoRN::$TAC_AMBOS;

  switch($_GET['acao']){
    case 'contato_cadastrar':
      $strTitulo = 'Novo Contato';

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarContato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      //limpa post quando abrindo formulário para cadastro
      if ($_POST['hdnFlagContato']=='' && $_POST['hdnContatoObject']!='') {
        $arrKeysPost = array_keys($_POST);
        foreach($arrKeysPost as $keyPost){
          if (in_array(substr($keyPost,0,3), array('txt','txa','sel','rdo'))){
            unset($_POST[$keyPost]);
          }
        }
      }

      $objContatoDTO->setNumIdContato(null);
      $objContatoDTO->setNumIdTipoContato($numIdTipoContato);
      $objContatoDTO->setNumIdContatoAssociado($numIdContatoAssociado);
      $objContatoDTO->setStrNomeContatoAssociado($_POST['txtContatoAssociado']);
      $objContatoDTO->setStrStaNatureza($strStaNatureza);
      $objContatoDTO->setNumIdCargo($_POST['selCargo']);
      $objContatoDTO->setStrExpressaoTratamentoCargo($_POST['txtTratamento']);
      $objContatoDTO->setStrExpressaoVocativoCargo($_POST['txtVocativo']);
      $objContatoDTO->setStrNome($_POST['txtNome']);
      $objContatoDTO->setStrNomeSocial($_POST['txtNomeSocial']);
      $objContatoDTO->setDtaNascimento($_POST['txtNascimento']);
      $objContatoDTO->setStrSigla($_POST['txtSigla']);
      $objContatoDTO->setStrStaGenero($_POST['rdoStaGenero']);
      $objContatoDTO->setDblCpf($_POST['txtCpf']);
      $objContatoDTO->setStrMatricula($_POST['txtMatricula']);
      $objContatoDTO->setStrMatriculaOab($_POST['txtMatriculaOab']);
      $objContatoDTO->setDblCnpj($_POST['txtCnpj']);
      $objContatoDTO->setDblRg($_POST['txtRg']);
      $objContatoDTO->setStrOrgaoExpedidor($_POST['txtOrgaoExpedidor']);

      if ($strStaNatureza == ContatoRN::$TN_PESSOA_FISICA) {
        $objContatoDTO->setStrTelefoneComercial($_POST['txtTelefoneComercialPF']);
        $objContatoDTO->setStrTelefoneCelular($_POST['txtTelefoneCelularPF']);
        $objContatoDTO->setStrTelefoneResidencial($_POST['txtTelefoneResidencialPF']);
      } else {
        $objContatoDTO->setStrTelefoneComercial($_POST['txtTelefoneComercialPJ']);
        $objContatoDTO->setStrTelefoneCelular($_POST['txtTelefoneCelularPJ']);
        $objContatoDTO->setStrTelefoneResidencial(null);
      }
      $objContatoDTO->setStrEmail($_POST['txtEmail']);
      $objContatoDTO->setStrSitioInternet($_POST['txtSitioInternet']);
      $objContatoDTO->setStrEndereco($_POST['txtEndereco']);
      $objContatoDTO->setStrComplemento($_POST['txtComplemento']);
      $objContatoDTO->setStrBairro($_POST['txtBairro']);
      $objContatoDTO->setStrNomeCidade($_POST['txtCidade']);
      $objContatoDTO->setStrNomeUf($_POST['txtUf']);
      if (isset($_POST['selPais'])) {
        $objContatoDTO->setNumIdPais($_POST['selPais']);
      } else {
        $objContatoDTO->setNumIdPais(PaisINT::buscarIdPaisBrasil());
      }
      $objContatoDTO->setNumIdUf($_POST['selUf']);
      $objContatoDTO->setNumIdCidade($_POST['selCidade']);

      $objContatoDTO->setStrCep($_POST['txtCep']);
      $objContatoDTO->setStrObservacao($_POST['txaObservacao']);
      $objContatoDTO->setStrSinEnderecoAssociado($strSinEnderecoAssociado);
      $objContatoDTO->setStrSinAtivo('S');

      $objContatoDTO->setStrNumeroPassaporte($_POST['txtNumeroPassaporte']);
      if (isset($_POST['selPaisPassaporte'])) {
        $objContatoDTO->setNumIdPaisPassaporte($_POST['selPaisPassaporte']);
      } else {
        $objContatoDTO->setNumIdPaisPassaporte(PaisINT::buscarIdPaisBrasil());
      }

      $objContatoDTO->setNumIdTitulo($_POST['selTitulo']);
      $objContatoDTO->setNumIdCategoria($_POST['selCategoria']);

      $objContatoDTO->setStrConjuge($_POST['txtConjuge']);
      $objContatoDTO->setStrFuncao($_POST['txtFuncao']);

      if (isset($_POST['sbmCadastrarContato'])) {
        try{

          $objContatoDTO = $objContatoRN->cadastrarRN0322($objContatoDTO);

          if ($_POST['hdnContatoObject']==null) {
            PaginaSEI::getInstance()->setStrMensagem('Contato "'.$objContatoDTO->getStrNome().'" cadastrado com sucesso.');
            header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_contato='.$objContatoDTO->getNumIdContato().'#ID-'.$objContatoDTO->getNumIdContato()));
            die;
          }else{
            $bolCadastro = true;
          }

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'contato_alterar':
    case 'contato_alterar_temporario':

      $strTitulo = 'Alterar Contato';

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarContato" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';

      if (isset($_GET['id_contato'])){

        $objContatoDTO->setBolExclusaoLogica(false);
        $objContatoDTO->retNumIdContato();
        $objContatoDTO->retNumIdContatoAssociado();
        $objContatoDTO->retStrNomeContatoAssociado();
        $objContatoDTO->retNumIdCargo();
        $objContatoDTO->retStrExpressaoTratamentoCargo();
        $objContatoDTO->retStrExpressaoVocativoCargo();
        $objContatoDTO->retNumIdTitulo();
        $objContatoDTO->retNumIdCategoria();
        $objContatoDTO->retNumIdTipoContato();
        $objContatoDTO->retStrStaNatureza();
        $objContatoDTO->retStrNome();
        $objContatoDTO->retStrNomeRegistroCivil();
        $objContatoDTO->retStrNomeSocial();
        $objContatoDTO->retStrSigla();
        $objContatoDTO->retStrStaGenero();
        $objContatoDTO->retDblCpf();
        $objContatoDTO->retDblCnpj();
        $objContatoDTO->retDblRg();
        $objContatoDTO->retStrOrgaoExpedidor();
        $objContatoDTO->retStrMatricula();
        $objContatoDTO->retStrMatriculaOab();
        $objContatoDTO->retStrTelefoneComercial();
        $objContatoDTO->retStrTelefoneResidencial();
        $objContatoDTO->retStrTelefoneCelular();
        $objContatoDTO->retStrEmail();
        $objContatoDTO->retStrSitioInternet();
        $objContatoDTO->retStrEndereco();
        $objContatoDTO->retStrComplemento();
        $objContatoDTO->retStrBairro();
        $objContatoDTO->retStrNomeCidade();
        $objContatoDTO->retStrNomeUf();
        $objContatoDTO->retNumIdPais();
        $objContatoDTO->retNumIdUf();
        $objContatoDTO->retNumIdCidade();
        $objContatoDTO->retStrCep();
        $objContatoDTO->retStrObservacao();
        $objContatoDTO->retDtaNascimento();
        $objContatoDTO->retStrSinEnderecoAssociado();
        $objContatoDTO->retStrSinSistemaTipoContato();
        $objContatoDTO->retStrNumeroPassaporte();
        $objContatoDTO->retNumIdPaisPassaporte();
        $objContatoDTO->retStrConjuge();
        $objContatoDTO->retStrFuncao();

        $objContatoDTO->setNumIdContato($_GET['id_contato']);

        $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);
        if ($objContatoDTO==null){
          throw new InfraException("Registro não encontrado.");
        }

        if ($objContatoDTO->getStrSinSistemaTipoContato()=='S'){
          $strSinBloquearIdentificacao = 'S';

          $objUsuarioDTO = new UsuarioDTO();
          $objUsuarioDTO->setBolExclusaoLogica(false);
          $objUsuarioDTO->setNumMaxRegistrosRetorno(1);
          $objUsuarioDTO->retNumIdUsuario();
          $objUsuarioDTO->setStrStaTipo(array(UsuarioRN::$TU_EXTERNO, UsuarioRN::$TU_EXTERNO_PENDENTE), InfraDTO::$OPER_IN);
          $objUsuarioDTO->setNumIdContato($objContatoDTO->getNumIdContato());

          $objUsuarioRN = new UsuarioRN();
          if ($objUsuarioRN->consultarRN0489($objUsuarioDTO)!=null && !SessaoSEI::getInstance()->verificarPermissao('usuario_externo_alterar')){
            $strSinBloquearExterno = 'S';
          }
        }

        if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_FISICA){
          $objContatoDTO->setStrNome($objContatoDTO->getStrNomeRegistroCivil());
        }

      } else{

        $objContatoDTO->setNumIdContato($_POST['hdnIdContato']);
        $objContatoDTO->setNumIdContatoAssociado($numIdContatoAssociado);
        $objContatoDTO->setStrNomeContatoAssociado($_POST['txtContatoAssociado']);
        $objContatoDTO->setNumIdTipoContato($numIdTipoContato);
        $objContatoDTO->setStrStaNatureza($strStaNatureza);
        $objContatoDTO->setNumIdCargo($_POST['selCargo']);
        $objContatoDTO->setStrExpressaoTratamentoCargo($_POST['txtTratamento']);
        $objContatoDTO->setStrExpressaoVocativoCargo($_POST['txtVocativo']);
        $objContatoDTO->setStrNome($_POST['txtNome']);
        $objContatoDTO->setDtaNascimento($_POST['txtNascimento']);
        $objContatoDTO->setStrSigla($_POST['txtSigla']);
        $objContatoDTO->setStrStaGenero($_POST['rdoStaGenero']);
        $objContatoDTO->setDblCpf($_POST['txtCpf']);
        $objContatoDTO->setDblRg($_POST['txtRg']);
        $objContatoDTO->setStrOrgaoExpedidor($_POST['txtOrgaoExpedidor']);
        $objContatoDTO->setStrMatricula($_POST['txtMatricula']);
        $objContatoDTO->setStrMatriculaOab($_POST['txtMatriculaOab']);
        $objContatoDTO->setDblCnpj($_POST['txtCnpj']);
        $objContatoDTO->setDblRg($_POST['txtRg']);
        $objContatoDTO->setStrOrgaoExpedidor($_POST['txtOrgaoExpedidor']);

        if ($strStaNatureza == ContatoRN::$TN_PESSOA_FISICA) {
          $objContatoDTO->setStrTelefoneComercial($_POST['txtTelefoneComercialPF']);
          $objContatoDTO->setStrTelefoneResidencial($_POST['txtTelefoneResidencialPF']);
          $objContatoDTO->setStrTelefoneCelular($_POST['txtTelefoneCelularPF']);
        }else{
          $objContatoDTO->setStrTelefoneComercial($_POST['txtTelefoneComercialPJ']);
          $objContatoDTO->setStrTelefoneCelular($_POST['txtTelefoneCelularPJ']);
          $objContatoDTO->setStrTelefoneResidencial(null);
        }

        $objContatoDTO->setStrEmail($_POST['txtEmail']);
        $objContatoDTO->setStrSitioInternet($_POST['txtSitioInternet']);
        $objContatoDTO->setStrEndereco($_POST['txtEndereco']);
        $objContatoDTO->setStrComplemento($_POST['txtComplemento']);
        $objContatoDTO->setStrBairro($_POST['txtBairro']);
        $objContatoDTO->setStrNomeCidade($_POST['txtCidade']);
        $objContatoDTO->setStrNomeUf($_POST['txtUf']);
        $objContatoDTO->setNumIdPais($numIdPais);
        $objContatoDTO->setNumIdUf($numIdUf);
        $objContatoDTO->setNumIdCidade($numIdCidade);
        $objContatoDTO->setStrCep($_POST['txtCep']);
        $objContatoDTO->setStrObservacao($_POST['txaObservacao']);
        $objContatoDTO->setStrSinEnderecoAssociado($strSinEnderecoAssociado);
        $objContatoDTO->setStrSinAtivo('S');
        $objContatoDTO->setStrNumeroPassaporte($_POST['txtNumeroPassaporte']);
        $objContatoDTO->setNumIdPaisPassaporte($numIdPaisPassaporte);
        $objContatoDTO->setNumIdTitulo($_POST['selTitulo']);
        $objContatoDTO->setNumIdCategoria($_POST['selCategoria']);
        $objContatoDTO->setStrConjuge($_POST['txtConjuge']);
        $objContatoDTO->setStrFuncao($_POST['txtFuncao']);
        $objContatoDTO->setStrNomeSocial($_POST['txtNomeSocial']);
      }
      $objContatoDTO->setDblCpf(InfraUtil::formatarCpf($objContatoDTO->getDblCpf()));
      $objContatoDTO->setDblCnpj(InfraUtil::formatarCnpj($objContatoDTO->getDblCnpj()));

      if ($_POST['hdnContatoObject']==null) {
        $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'#ID-'.$objContatoDTO->getNumIdContato().'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }else{
        $arrComandos[] = '<button type="button" accesskey="C" id="btnCancelar" value="Fechar" onclick="infraFecharJanelaModal();" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';
      }

      if (isset($_POST['sbmAlterarContato'])) {
        try{

          $objContatoRN->alterarRN0323($objContatoDTO);

          if ($_POST['hdnContatoObject']==null) {
            PaginaSEI::getInstance()->adicionarMensagem('Contato "' . $objContatoDTO->getStrNome() . '" alterado com sucesso.');
            header('Location: ' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao'] . '#ID-' . $objContatoDTO->getNumIdContato()));
            die;
          }else{
            $bolCadastro = true;
          }

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'contato_consultar':
      $strTitulo = "Consultar Contato";
      $strDesabilitar = 'disabled="disabled"';

      if ($_POST['hdnContatoObject']==null) {
        $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\'' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=' . PaginaSEI::getInstance()->getAcaoRetorno() . '&acao_origem=' . $_GET['acao']) . '#ID-' . $_GET['id_contato'] . '\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      }else{
        //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="window.close();" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      }

      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retNumIdContatoAssociado();
      $objContatoDTO->retNumIdTipoContatoAssociado();
      $objContatoDTO->retStrNomeContatoAssociado();
      $objContatoDTO->retNumIdCargo();
      $objContatoDTO->retStrExpressaoTratamentoCargo();
      $objContatoDTO->retStrExpressaoVocativoCargo();
      $objContatoDTO->retNumIdTitulo();
      $objContatoDTO->retNumIdCategoria();
      $objContatoDTO->retNumIdTipoContato();
      $objContatoDTO->retStrStaNatureza();
      $objContatoDTO->retStrNome();
      $objContatoDTO->retStrNomeRegistroCivil();
      $objContatoDTO->retStrNomeSocial();
      $objContatoDTO->retStrSigla();
      $objContatoDTO->retStrStaGenero();
      $objContatoDTO->retDblCpf();
      $objContatoDTO->retDblRg();
      $objContatoDTO->retStrOrgaoExpedidor();
      $objContatoDTO->retDtaNascimento();
      $objContatoDTO->retStrMatricula();
      $objContatoDTO->retStrMatriculaOab();
      $objContatoDTO->retStrTelefoneComercial();
      $objContatoDTO->retStrTelefoneResidencial();
      $objContatoDTO->retStrTelefoneCelular();
      $objContatoDTO->retDblCnpj();
      $objContatoDTO->retStrEmail();
      $objContatoDTO->retStrSitioInternet();
      $objContatoDTO->retStrEndereco();
      $objContatoDTO->retStrComplemento();
      $objContatoDTO->retStrBairro();
      $objContatoDTO->retStrNomeCidade();
      $objContatoDTO->retStrNomeUf();
      $objContatoDTO->retNumIdPais();
      $objContatoDTO->retNumIdUf();
      $objContatoDTO->retNumIdCidade();
      $objContatoDTO->retStrCep();
      $objContatoDTO->retStrObservacao();
      $objContatoDTO->retStrSinEnderecoAssociado();
      $objContatoDTO->retStrNumeroPassaporte();
      $objContatoDTO->retNumIdPaisPassaporte();
      $objContatoDTO->retStrConjuge();
      $objContatoDTO->retStrFuncao();
      $objContatoDTO->setNumIdContato($_GET['id_contato']);

      $objContatoDTO->setBolExclusaoLogica(false);
      $objContatoDTO = $objContatoRN->consultarRN0324($objContatoDTO);
      if ($objContatoDTO===null){
        throw new InfraException("Registro não encontrado.");
      }

      if ($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_FISICA){
        $objContatoDTO->setStrNome($objContatoDTO->getStrNomeRegistroCivil());
      }

      $numTipoConsulta = $objContatoRN->removerDadosPrivados(array($objContatoDTO));

      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  if ($strSinBloquearTipoContato=='S' || $strSinBloquearIdentificacao=='S' || $_GET['acao']=='contato_consultar') {
    $strItensSelTipoContato = TipoContatoINT::montarSelectNomeUnico($objContatoDTO->getNumIdTipoContato());
  }else{
    $strItensSelTipoContato = TipoContatoINT::montarSelectNomeRI0898('null','&nbsp;',$objContatoDTO->getNumIdTipoContato());
  }

  $strItensSelCargo = CargoINT::montarSelectGenero('null','&nbsp;',$objContatoDTO->getNumIdCargo(),$objContatoDTO->getStrStaGenero());
  $strItensSelCategoria = CategoriaINT::montarSelectNome('null','&nbsp;',$objContatoDTO->getNumIdCategoria());
  $strItensSelTitulo = TituloINT::montarSelectExpressaoAbreviatura('null','&nbsp;',$objContatoDTO->getNumIdTitulo());
  //$strItensSelVocativo = VocativoINT::montarSelectExpressaoRI0469('null','&nbsp;',$objContatoDTO->getNumIdVocativo());
  $strItensSelPais = PaisINT::montarSelectNome('null','&nbsp',$objContatoDTO->getNumIdPais());
  $strItensSelUf = UfINT::montarSelectSiglaRI0416('null','&nbsp;',$objContatoDTO->getNumIdUf(),$objContatoDTO->getNumIdPais());
  $strItensSelCidade = CidadeINT::montarSelectIdCidadeNome('null','&nbsp;',$objContatoDTO->getNumIdCidade(),$objContatoDTO->getNumIdUf(),$objContatoDTO->getNumIdPais());
  $strLinkAjaxPaisUf = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=uf_montar_select_sigla');
  $strLinkAjaxUfCidade = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=cidade_montar_select_id_cidade_nome');
  $strLinkAjaxAutoCompletarContatoAssociado = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_auto_completar_associado');
  $strLinkAjaxDadosContatoAssociado = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=contato_associado_dados');
  $strLinkAjaxCargo = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=cargo_montar_select_genero');
  $strLinkAjaxDadosCargo = SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=cargo_dados');
  $strItensSelPaisPassaporte = PaisINT::montarSelectNome('null','&nbsp',$objContatoDTO->getNumIdPaisPassaporte());

  $strDisplayEndereco = 'display:none';
  $strDisplayEnderecoAssociado = 'display:none;';
  if ($objContatoDTO->getStrStaNatureza()!=null) {
    if ($numTipoConsulta == ContatoRN::$TAC_AMBOS) {
      if ($objContatoDTO->getStrSinEnderecoAssociado() == 'S') {
        $strDisplayEnderecoAssociado = '';
      } else {
        $strDisplayEndereco = '';
      }
    } else {
      if ($numTipoConsulta == ContatoRN::$TAC_SOMENTE_ASSOCIADO) {
        if ($objContatoDTO->getStrSinEnderecoAssociado() == 'S') {
          $strDisplayEnderecoAssociado = '';
        }
      } else if ($numTipoConsulta == ContatoRN::$TAC_SOMENTE_CONTATO) {
        if ($objContatoDTO->getStrSinEnderecoAssociado() == 'N') {
          $strDisplayEndereco = '';
        }
      }
    }
  }

  $strTagReadOnlyIdentificacao = '';
  $strCssReadOnlyIdentificacao = '';
  $strTagDisabledIdentificacao = '';
  if ($strSinBloquearIdentificacao=='S'){
    $strTagReadOnlyIdentificacao = 'readonly="readonly"';
    $strCssReadOnlyIdentificacao = ' infraReadOnly';
    $strTagDisabledIdentificacao = 'disabled="disabled"';
  }

  $strTagReadOnlyExterno = '';
  $strCssReadOnlyExterno = '';
  $strTagDisabledExterno = '';
  if ($strSinBloquearExterno=='S'){
    $strTagReadOnlyExterno = 'readonly="readonly"';
    $strCssReadOnlyExterno = ' infraReadOnly';
    $strTagDisabledExterno = 'disabled="disabled"';
  }

  $bolExibirEmail = true;
  $strReadOnlyContatoAssociado = '';
  $strCssReadOnlyContatoAssociado = '';
  $strDisplayContatoAssociado = 'block';
  if ($objContatoDTO->getNumIdContato()!=null) {

    $objUsuarioDTO = new UsuarioDTO();
    $objUsuarioDTO->setBolExclusaoLogica(false);
    $objUsuarioDTO->retNumIdUsuario();
    $objUsuarioDTO->setStrStaTipo(array(UsuarioRN::$TU_EXTERNO, UsuarioRN::$TU_EXTERNO_PENDENTE), InfraDTO::$OPER_IN);
    $objUsuarioDTO->setNumIdContato($objContatoDTO->getNumIdContato());
    $objUsuarioDTO->setNumMaxRegistrosRetorno(1);

    $objUsuarioRN = new UsuarioRN();
    if ($objUsuarioRN->consultarRN0489($objUsuarioDTO)!=null){
      $bolExibirEmail = false;
    }else{

      $objUsuarioDTO->setStrStaTipo(UsuarioRN::$TU_SIP);

      if ($objUsuarioRN->consultarRN0489($objUsuarioDTO)!=null) {

        $strReadOnlyContatoAssociado = 'readonly="readonly"';
        $strCssReadOnlyContatoAssociado = ' infraReadOnly';

      }else {

        $objUnidadeDTO = new UnidadeDTO();
        $objUnidadeDTO->setBolExclusaoLogica(false);
        $objUnidadeDTO->retNumIdUnidade();
        $objUnidadeDTO->setNumIdContato($objContatoDTO->getNumIdContato());
        $objUnidadeDTO->setNumMaxRegistrosRetorno(1);

        $objUnidadeRN = new UnidadeRN();
        if ($objUnidadeRN->consultarRN0125($objUnidadeDTO) != null) {

          $bolExibirEmail = false;
          $strReadOnlyContatoAssociado = 'readonly="readonly"';
          $strCssReadOnlyContatoAssociado = ' infraReadOnly';

        } else {

          $objOrgaoDTO = new OrgaoDTO();
          $objOrgaoDTO->setBolExclusaoLogica(false);
          $objOrgaoDTO->retNumIdOrgao();
          $objOrgaoDTO->setNumIdContato($objContatoDTO->getNumIdContato());
          $objOrgaoDTO->setNumMaxRegistrosRetorno(1);

          $objOrgaoRN = new OrgaoRN();
          if ($objOrgaoRN->consultarRN1352($objOrgaoDTO) != null) {
            $strDisplayContatoAssociado = 'none';
          }
        }
      }
    }
  }

  if ($_GET['acao']=='contato_cadastrar' && $_GET['acao_origem']!=$_GET['acao'] && $objContatoDTO->getNumIdContatoAssociado()!=null){

    $dto = new ContatoDTO();
    $dto->retStrNome();
    $dto->setNumIdContato($objContatoDTO->getNumIdContatoAssociado());

    $objContatoRN = new ContatoRN();
    $dto = $objContatoRN->consultarRN0324($dto);

    if ($dto!=null){
      $objContatoDTO->setStrNomeContatoAssociado($dto->getStrNome());
    }

  }else if ($objContatoDTO->getNumIdContato()==$objContatoDTO->getNumIdContatoAssociado()){
    $objContatoDTO->setNumIdContatoAssociado(null);
    $objContatoDTO->setStrNomeContatoAssociado(null);
  }

}catch(Exception $e){
  PaginaSEI::getInstance()->processarExcecao($e);
}

PaginaSEI::getInstance()->montarDocType();
PaginaSEI::getInstance()->abrirHtml();
PaginaSEI::getInstance()->abrirHead();
PaginaSEI::getInstance()->montarMeta();
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>
    #divGeral{height:18em;}
    #lblTipoContato {position:absolute;left:0%;top:0%;width:68.5%;}
    #selTipoContato {position:absolute;left:0%;top:10%;width:68.5%;}

    #lblSigla {position:absolute;left:0%;top:26%;width:33%;}
    #txtSigla {position:absolute;left:0%;top:36%;width:33%;}

    #lblNome {position:absolute;left:0%;top:51%;width:68%;}
    #txtNome {position:absolute;left:0%;top:61%;width:68%;}

    #divPessoaFisicaPublico1 {display:none;}
    #lblNomeSocial {position:absolute;left:0%;top:76%;width:68%;}
    #txtNomeSocial {position:absolute;left:0%;top:86%;width:68%;}

    #fldNatureza {position:absolute;left:70%;top:5%;height:40%;width:24%;max-width:180px;}
    #divOptPessoaFisica {position:absolute;left:12%;top:35%;}
    #divOptPessoaJuridica {position:absolute;left:12%;top:65%;}

    #fldStaGenero {position:absolute;left:70%;top:55%;height:40%;width:24%;max-width:180px;}
    #divOptFeminino {position:absolute;left:12%;top:35%;}
    #divOptMasculino {position:absolute;left:12%;top:65%;}

    #divContatoAssociado {height:5em;display:none;}
    #lblContatoAssociado {position:absolute;left:0%;top:10%;width:68%;}
    #txtContatoAssociado {position:absolute;left:0%;top:45%;width:68%;}
    #divSinEnderecoAssociado {position:absolute;left:70%;top:45%;}

    #divEndereco {height:14em;<?=$strDisplayEndereco?>}

    #lblEndereco {position:absolute;left:0%;top:1%;width:94.5%;}
    #txtEndereco {position:absolute;left:0%;top:14%;width:94.5%;}

    #lblComplemento {position:absolute;left:0%;top:34%;width:68%;}
    #txtComplemento {position:absolute;left:0%;top:47%;width:68%;}

    #lblBairro {position:absolute;left:70%;top:34%;width:24.6%;}
    #txtBairro {position:absolute;left:70%;top:47%;width:24.6%;}

    #lblPais {position:absolute;left:0%;top:67%;width:23%;}
    #selPais {position:absolute;left:0%;top:80%;width:23%;}

    #lblUf {position:absolute;left:24%;top:67%;width:9%;}
    #selUf {position:absolute;left:24%;top:80%;width:9%;}
    #txtUf {position:absolute;left:24%;top:80%;width:9%;}

    #lblCidade {position:absolute;left:35%;top:67%;width:33%;}
    #selCidade {position:absolute;left:35%;top:80%;width:33.3%;}
    #txtCidade {position:absolute;left:35%;top:80%;width:33.3%;}

    #lblCep {position:absolute;left:70%;top:67%;width:15%;}
    #txtCep {position:absolute;left:70%;top:80%;width:15%;}

    #divEnderecoAssociado {height:14em;<?=$strDisplayEnderecoAssociado?>}

    #lblEnderecoAssociado {position:absolute;left:0%;top:0%;width:94.5%;}
    #txtEnderecoAssociado {position:absolute;left:0%;top:14%;width:94.5%;}

    #lblComplementoAssociado {position:absolute;left:0%;top:34%;width:68%;}
    #txtComplementoAssociado {position:absolute;left:0%;top:47%;width:68%;}

    #lblBairroAssociado {position:absolute;left:70%;top:34%;width:24.6%;}
    #txtBairroAssociado {position:absolute;left:70%;top:47%;width:24.6%;}

    #lblNomePaisAssociado {position:absolute;left:0%;top:67%;width:22%;}
    #txtNomePaisAssociado {position:absolute;left:0%;top:80%;width:22%;}

    #lblUfAssociado {position:absolute;left:24%;top:67%;width:8%;}
    #txtUfAssociado {position:absolute;left:24%;top:80%;width:8%;}

    #lblCidadeAssociado {position:absolute;left:35%;top:67%;width:33%;}
    #txtCidadeAssociado {position:absolute;left:35%;top:80%;width:33%;}

    #lblCepAssociado {position:absolute;left:70%;top:67%;width:15%;}
    #txtCepAssociado {position:absolute;left:70%;top:80%;width:15%;}

    #divPessoaFisicaPublico2 {height:10em;display:none;}

    #lblIdCargo {position:absolute;left:0%;top:0%;width:45.5%;}
    #selCargo {position:absolute;left:0%;top:20%;width:45.5%;}

    #lblVocativo {position:absolute;left:47%;top:0%;width:21.2%;}
    #txtVocativo {position:absolute;left:47%;top:20%;width:21.2%;}

    #lblTratamento {position:absolute;left:70%;top:0%;width:21.5%;}
    #txtTratamento {position:absolute;left:70%;top:20%;width:21.5%;}

    #lblIdCategoria {position:absolute;left:0%;top:50%;width:45.5%;}
    #selCategoria {position:absolute;left:0%;top:70%;width:45.5%;}

    #lblFuncao {position:absolute;left:47%;top:50%;width:21%;}
    #txtFuncao  {position:absolute;left:47%;top:70%;width:21%;}

    #lblTitulo {position:absolute;left:70%;top:50%;width:24.6%;}
    #selTitulo {position:absolute;left:70%;top:70%;width:24.6%;}

    #divPessoaFisicaPrivado {height:5em;display:none;}

    #lblCpf {position:absolute;left:0%;top:0%;width:22%;}
    #txtCpf {position:absolute;left:0%;top:40%;width:22%;}

    #lblRg {position:absolute;left:24%;top:0%;width:21%;}
    #txtRg {position:absolute;left: 24%;top:40%;width:21%;}

    #lblOrgaoExpedidor {position:absolute;left:47%;top:0%;width:21%;}
    #txtOrgaoExpedidor {position:absolute;left:47%;top:40%;width:21%;}

    #lblNascimento {position:absolute;left:70%;top:0%;width:21.5%;}
    #txtNascimento {position:absolute;left:70%;top:40%;width:21.5%;}
    #imgCalNascimento {position:absolute;left:93%;top:40%;}

    #divPessoaFisicaPublico3 {height:5em;display:none;}
    #lblMatricula {position:absolute;left:0%;top:0%;width:22%;}
    #txtMatricula {position:absolute;left:0%;top:40%;width:22%;}

    #lblMatriculaOab {position:absolute;left:24%;top:0%;width:21%;}
    #txtMatriculaOab {position:absolute;left:24%;top:40%;width:21%;}

    #lblNumeroPassaporte {position:absolute;left:47%;top:0%;width:21%;}
    #txtNumeroPassaporte  {position:absolute;left:47%;top:40%;width:21%;}

    #lblPaisPassaporte {position:absolute;left:70%;top:0%;width:24.6%;}
    #selPaisPassaporte {position:absolute;left:70%;top:40%;width:24.6%;}

    #divPessoaFisicaTelefones {height:5em;display:none;}
    #lblTelefoneComercialPF {position:absolute;left:0%;top:0%;width:45%;}
    #txtTelefoneComercialPF {position:absolute;left:0%;top:40%;width:45%;}

    #lblTelefoneCelularPF {position:absolute;left:47%;top:0%;width:21%;}
    #txtTelefoneCelularPF {position:absolute;left:47%;top:40%;width:21%;}

    #lblTelefoneResidencialPF {position:absolute;left:70%;top:0%;width:21%;}
    #txtTelefoneResidencialPF {position:absolute;left:70%;top:40%;width:21%;}

    #divPessoaJuridica {height:10em;display:none;}

    #lblCnpj {position:absolute;left:0%;top:2%;width:21%;}
    #txtCnpj {position:absolute;left:0%;top:20%;width:21%;}

    #lblSitioInternet {position:absolute;left:24%;top:2%;width:21%;}
    #txtSitioInternet {position:absolute;left:24%;top:20%;width:21%;}

    #lblTelefoneCelularPJ {position:absolute;left:47%;top:2%;width:21%;}
    #txtTelefoneCelularPJ {position:absolute;left:47%;top:20%;width:21%;}

    #lblTelefoneComercialPJ {position:absolute;left:0%;top:52%;width:68%;}
    #txtTelefoneComercialPJ {position:absolute;left:0%;top:70%;width:68%;}

    #divConjuge {height:5em;display:none;}
    #lblConjuge {position:absolute;left:0%;top:0%;width:68%;}
    #txtConjuge {position:absolute;left:0%;top:40%;width:68%;}

    #divEmail {height:5em;display:none;}
    #lblEmail {position:absolute;left:0%;top:0%;width:68%;}
    #txtEmail {position:absolute;left:0%;top:40%;width:68%;}

    #divObservacao {height:8em;display:none;}
    #lblObservacao {position:absolute;left:0%;top:0%;width:94%;}
    #txaObservacao {position:absolute;left:0%;top:22%;width:94%;}

<?
PaginaSEI::getInstance()->fecharStyle();

if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()){
  PaginaSEI::getInstance()->abrirStyle();
  ?>
    #divOptPessoaFisica {top:10%;}
    #divOptPessoaJuridica {top:50%;}

    #divOptFeminino {top:10%;}
    #divOptMasculino {top:50%;}
  <?
  PaginaSEI::getInstance()->fecharStyle();
}

PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
    //<script>

    var objAjaxPaisUf = null;
    var objAjaxUfCidade = null;
    var objAutoCompletarContatoAssociado = null;
    var objAjaxDadosContatoAssociado = null;
    var objAjaxCargo = null;
    var objAjaxDadosCargo = null;

    function inicializar(){

      <?if ($bolCadastro){ ?>

        <? if ($_GET['arvore']=='1'){ ?>
        var janelaOrigem = infraObterJanelaOrigemModal();
        <? }else{ ?>
        var janelaOrigem = window.parent;
        <? } ?>

        var obj = janelaOrigem.document.getElementById('<?=$_POST['hdnContatoObject']?>');

        if (obj.type == 'text'){
          obj.value = '<?=PaginaSEI::formatarParametrosJavaScript($objContatoDTO->getStrNome(),false)?>';
        }else{
          obj.options[obj.selectedIndex].text = '<?=PaginaSEI::formatarParametrosJavaScript(ContatoINT::formatarNomeSiglaRI1224($objContatoDTO->getStrNome(), $objContatoDTO->getStrSigla()),false)?>';
        }

        if (typeof(janelaOrigem.finalizarCadastroContato) == 'function'){
          janelaOrigem.finalizarCadastroContato('<?=$_POST['hdnContatoObject']?>','<?=$objContatoDTO->getNumIdContato()?>','<?=PaginaSEI::formatarParametrosJavaScript($objContatoDTO->getStrSigla(),false)?>','<?=PaginaSEI::formatarParametrosJavaScript($objContatoDTO->getStrNome(),false)?>');
        }

        infraFecharJanelaModal();
        return;

      <?}else{?>

        if ('<?=$_GET['acao']?>'=='contato_cadastrar'){
          <?if (isset($_GET['id_contato_associado'])){?>
            document.getElementById('txtNome').focus();
          <?}else{?>
            document.getElementById('selTipoContato').focus();
          <?}?>
        } else if ('<?=$_GET['acao']?>'=='contato_consultar'){
            infraDesabilitarCamposAreaDados();
        }

        objAjaxPaisUf = new infraAjaxMontarSelectDependente('selPais','selUf','<?=$strLinkAjaxPaisUf?>');

        objAjaxPaisUf.prepararExecucao = function(){
            infraSelectLimpar(document.getElementById('selCidade'));
            return infraAjaxMontarPostPadraoSelect('null','','null') + '&idPais='+document.getElementById('selPais').value;
        };

        objAjaxPaisUf.finalizarExecucao = function(){
          document.getElementById('txtCep').value = "";
            if (document.getElementById('selPais').value!=<?=PaisINT::buscarIdPaisBrasil()?>){
                objAjaxUfCidade.executar();
                document.getElementById('txtCep').onkeypress = mascaraCepGeral;
            }else{

                document.getElementById('txtCep').onkeypress = mascaraCepBrasil;
            }
        };

        objAjaxUfCidade = new infraAjaxMontarSelectDependente('selUf','selCidade','<?=$strLinkAjaxUfCidade?>');
        objAjaxUfCidade.prepararExecucao = function(){
            return infraAjaxMontarPostPadraoSelect('null','','null') + '&idUf='+document.getElementById('selUf').value + '&idPais=' + document.getElementById('selPais').value;
        };


        objAjaxDadosContatoAssociado = new infraAjaxComplementar(null,'<?=$strLinkAjaxDadosContatoAssociado?>');
        objAjaxDadosContatoAssociado.prepararExecucao = function(){
            if (document.getElementById('hdnIdContatoAssociado').value!='') {
                return 'id_contato_associado=' + document.getElementById('hdnIdContatoAssociado').value;
            }else{
                return false;
            }
        };

        objAjaxDadosContatoAssociado.processarResultado = function(arr){

            document.getElementById('txtEnderecoAssociado').value = '';
            document.getElementById('txtComplementoAssociado').value = '';
            document.getElementById('txtBairroAssociado').value = '';
            document.getElementById('txtNomePaisAssociado').value = '';
            document.getElementById('txtUfAssociado').value = '';
            document.getElementById('txtCidadeAssociado').value = '';
            document.getElementById('txtCepAssociado').value = '';

            if (arr!=null){

                if (arr['Endereco']!=undefined){
                    document.getElementById('txtEnderecoAssociado').value = arr['Endereco'];
                }
                if (arr['Complemento']!=undefined){
                    document.getElementById('txtComplementoAssociado').value = arr['Complemento'];
                }
                if (arr['Bairro']!=undefined){
                    document.getElementById('txtBairroAssociado').value = arr['Bairro'];
                }
                if (arr['NomePais']!=undefined){
                    document.getElementById('txtNomePaisAssociado').value = arr['NomePais'];
                }
                if (arr['NomeCidade']!=undefined){
                    document.getElementById('txtCidadeAssociado').value = arr['NomeCidade'];
                }
                if (arr['SiglaUf']!=undefined){
                    document.getElementById('txtUfAssociado').value = arr['SiglaUf'];
                }
                if (arr['Cep']!=undefined){
                    document.getElementById('txtCepAssociado').value = arr['Cep'];
                }
            }
        };


        objAutoCompletarContatoAssociado = new infraAjaxAutoCompletar('hdnIdContatoAssociado','txtContatoAssociado','<?=$strLinkAjaxAutoCompletarContatoAssociado?>');
        //objAutoCompletarContatoAssociado.maiusculas = true;
        //objAutoCompletarContatoAssociado.mostrarAviso = true;
        //objAutoCompletarContatoAssociado.tempoAviso = 1000;
        //objAutoCompletarContatoAssociado.tamanhoMinimo = 3;
        objAutoCompletarContatoAssociado.limparCampo = false;
        //objAutoCompletarContatoAssociado.bolExecucaoAutomatica = false;

        objAutoCompletarContatoAssociado.prepararExecucao = function(){
            return 'palavras_pesquisa='+document.getElementById('txtContatoAssociado').value+'&id_tipo_contato=<?=$_GET['id_tipo_contato_associado']?>';
        };

        objAutoCompletarContatoAssociado.processarResultado = function(id,descricao,complemento){
            if (id!=''){
                document.getElementById('hdnIdContatoAssociado').value = id;
                document.getElementById('txtContatoAssociado').value = descricao;
                tratarEnderecoAssociado();
            }
        };
        objAutoCompletarContatoAssociado.selecionar('<?=$objContatoDTO->getNumIdContatoAssociado()?>','<?=PaginaSEI::getInstance()->formatarParametrosJavaScript($objContatoDTO->getStrNomeContatoAssociado(),false);?>');

        objAjaxCargo = new infraAjaxMontarSelect('selCargo','<?=$strLinkAjaxCargo?>');
        objAjaxCargo.prepararExecucao = function(){

            var genero = '';
            if (document.getElementById('optFeminino').checked){
                genero = 'F';
            }else if (document.getElementById('optMasculino').checked){
                genero = 'M';
            }

            return infraAjaxMontarPostPadraoSelect('null','','null') + '&staGenero=' + genero;
        };

        objAjaxDadosCargo = new infraAjaxComplementar('selCargo','<?=$strLinkAjaxDadosCargo?>');
        objAjaxDadosCargo.prepararExecucao = function(){
            return 'id_cargo=' + document.getElementById('selCargo').value;
        };

        objAjaxDadosCargo.processarResultado = function(arr){

            document.getElementById('txtTratamento').value = '';
            document.getElementById('txtVocativo').value = '';
            infraSelectSelecionarItem(document.getElementById("selTitulo"),'');

            if (arr!=null){

                if (arr['ExpressaoTratamento']!=undefined){
                    document.getElementById('txtTratamento').value = arr['ExpressaoTratamento'];
                }

                if (arr['ExpressaoVocativo']!=undefined){
                    document.getElementById('txtVocativo').value = arr['ExpressaoVocativo'];
                }

                if (arr['IdTitulo']!=undefined){
                  infraSelectSelecionarItem(document.getElementById("selTitulo"),arr['IdTitulo']);
                }
            }
        };

        if (document.getElementById('selPais').value!=<?=PaisINT::buscarIdPaisBrasil()?>){
            document.getElementById('txtCep').onkeypress = mascaraCepGeral;
        }else{
            document.getElementById('txtCep').onkeypress = mascaraCepBrasil;
        }

        formatarExibicao();

      <?}?>

      trocarPais(true);
    }

    function OnSubmitForm() {
        return validarCadastroRI0146();
    }

    function validarCadastroRI0146(){
        if (!infraSelectSelecionado('selTipoContato')) {
            alert('Selecione um Tipo.');
            document.getElementById('selTipoContato').focus();
            return false;
        }

        if (!document.getElementById('optPessoaFisica').checked && !document.getElementById('optPessoaJuridica').checked){
            alert('Informe a Natureza.');
            document.getElementById('optPessoaFisica').focus();
            return false;
        }

        if (infraTrim(document.getElementById('txtNome').value)==''){
            alert('Informe o Nome.');
            document.getElementById('txtNome').focus();
            return false;
        }

        if (document.getElementById('optPessoaFisica').checked) {
            if (!infraValidarData(document.getElementById('txtNascimento'))) {
                return false;
            }

            if (infraTrim(document.getElementById('txtCpf').value)!='') {
                if (!infraValidarCpf(document.getElementById('txtCpf').value)) {
                    alert('CPF inválido.');
                    document.getElementById('txtCpf').focus();
                    return false;
                }
            }

            if (!infraValidarOAB(document.getElementById('txtMatriculaOab'))){
                return false;
            }

        }else {
            if (infraTrim(document.getElementById('txtCnpj').value)!='') {
                if (!infraValidarCnpj(document.getElementById('txtCnpj').value)) {
                    alert('CNPJ inválido.');
                    document.getElementById('txtCnpj').focus();
                    return false;
                }
            }
        }
        /*if(infraTrim($("#txtCidade").val()) != "" && infraTrim($("#txtUf").val()) == ""){
          alert('Informe o estado.');
          $("#txtUf").focus();
          return false;
        }*/

        return true;
    }

    function tratarEnderecoAssociado(){

        formatarExibicao();

        if (document.getElementById('chkSinEnderecoAssociado').checked){
            objAjaxDadosContatoAssociado.executar();
        }
    }

    function formatarExibicao(){

        if (document.getElementById('optPessoaFisica').checked){

            document.getElementById('lblSigla').innerText = 'Sigla:';
            document.getElementById('lblNome').innerText = 'Nome:';

            document.getElementById('divContatoAssociado').style.display = '<?=$strDisplayContatoAssociado?>';
            document.getElementById('divPessoaFisicaPublico1').style.display = 'block';
            document.getElementById('divPessoaFisicaPublico2').style.display = 'block';
            document.getElementById('divPessoaFisicaPublico3').style.display = 'block';
            document.getElementById('divPessoaFisicaTelefones').style.display = 'block';
            document.getElementById('divConjuge').style.display = 'none';
            document.getElementById('divPessoaJuridica').style.display = 'none';

          <?if ($numTipoConsulta==ContatoRN::$TAC_AMBOS){?>

            document.getElementById('divPessoaFisicaPrivado').style.display = 'block';
            document.getElementById('divPessoaJuridicaTelefonesPrivados').style.display = 'none';
            document.getElementById('divPessoaFisicaTelefonesPrivados').style.display = 'block';
            document.getElementById('divConjuge').style.display = 'block';

            if (document.getElementById('chkSinEnderecoAssociado').checked) {
                document.getElementById('divEndereco').style.display = 'none';
                document.getElementById('divEnderecoAssociado').style.display = 'block';
            }else{
                document.getElementById('divEndereco').style.display = 'block';
                document.getElementById('divEnderecoAssociado').style.display = 'none';
            }

          <? if ($bolExibirEmail){ ?>
            document.getElementById('divEmail').style.display = 'block';
          <? } ?>

            document.getElementById('divObservacao').style.display = 'block';

            if ('<?=$_GET['acao']?>'!='contato_consultar') {
                if (document.getElementById('optFeminino').checked || document.getElementById('optMasculino').checked) {
                    document.getElementById('selCargo').disabled = false;
                } else {
                    document.getElementById('selCargo').disabled = true;
                }
            }

          <?}else{?>

          <? if ($numTipoConsulta==ContatoRN::$TAC_NENHUM){ ?>
            document.getElementById('divPessoaFisicaPrivado').style.display = 'none';
            document.getElementById('divPessoaJuridicaTelefonesPrivados').style.display = 'none';
            document.getElementById('divPessoaFisicaTelefonesPrivados').style.display = 'none';
            document.getElementById('divConjuge').style.display = 'none';
            document.getElementById('divEndereco').style.display = 'none';
            document.getElementById('divEnderecoAssociado').style.display = 'none';
            document.getElementById('divObservacao').style.display = 'none';
          <? }else if ($numTipoConsulta==ContatoRN::$TAC_SOMENTE_ASSOCIADO){ ?>
            document.getElementById('divPessoaFisicaPrivado').style.display = 'none';
            document.getElementById('divPessoaJuridicaTelefonesPrivados').style.display = 'none';
            document.getElementById('divPessoaFisicaTelefonesPrivados').style.display = 'none';
            document.getElementById('divConjuge').style.display = 'none';
            document.getElementById('divEndereco').style.display = 'none';
            if (document.getElementById('chkSinEnderecoAssociado').checked) {
                document.getElementById('divEnderecoAssociado').style.display = 'block';
            }else{
                document.getElementById('divEnderecoAssociado').style.display = 'none';
            }
            document.getElementById('divObservacao').style.display = 'none';
          <? }else if ($numTipoConsulta==ContatoRN::$TAC_SOMENTE_CONTATO){ ?>
            document.getElementById('divPessoaFisicaPrivado').style.display = 'block';
            document.getElementById('divPessoaJuridicaTelefonesPrivados').style.display = 'block';
            document.getElementById('divPessoaFisicaTelefonesPrivados').style.display = 'block';
            document.getElementById('divConjuge').style.display = 'block';

            if (!document.getElementById('chkSinEnderecoAssociado').checked) {
                document.getElementById('divEndereco').style.display = 'block';
            }else{
                document.getElementById('divEndereco').style.display = 'none';
            }

            document.getElementById('divEnderecoAssociado').style.display = 'none';
            document.getElementById('divObservacao').style.display = 'block';
          <? } ?>
          <?}?>

            document.getElementById('txtCnpj').value = '';
            document.getElementById('txtSitioInternet').value = '';

        }else if (document.getElementById('optPessoaJuridica').checked){

            document.getElementById('lblSigla').innerText = 'Sigla / Nome Fantasia:';
            document.getElementById('lblNome').innerText = 'Nome / Razão Social:';

            document.getElementById('divContatoAssociado').style.display = '<?=$strDisplayContatoAssociado?>';
            document.getElementById('divPessoaFisicaPublico1').style.display = 'none';
            document.getElementById('divPessoaFisicaPublico2').style.display = 'none';
            document.getElementById('divPessoaFisicaPublico3').style.display = 'none';
            document.getElementById('divPessoaFisicaTelefones').style.display = 'none';
            document.getElementById('divConjuge').style.display = 'none';
            document.getElementById('divPessoaFisicaPrivado').style.display = 'none';

            <?if ($numTipoConsulta==ContatoRN::$TAC_AMBOS || $numTipoConsulta==ContatoRN::$TAC_SOMENTE_CONTATO){?>
              document.getElementById('divPessoaJuridicaTelefonesPrivados').style.display = 'block';
            <?}else{?>
              document.getElementById('divPessoaJuridicaTelefonesPrivados').style.display = 'none';
            <?}?>

            document.getElementById('divPessoaFisicaTelefonesPrivados').style.display = 'none';
            document.getElementById('divConjuge').style.display = 'none';


            document.getElementById('divPessoaJuridica').style.display = 'block';

            if (document.getElementById('chkSinEnderecoAssociado').checked) {
                document.getElementById('divEndereco').style.display = 'none';
                document.getElementById('divEnderecoAssociado').style.display = 'block';
            }else{
                document.getElementById('divEndereco').style.display = 'block';
                document.getElementById('divEnderecoAssociado').style.display = 'none';
            }

          <? if ($bolExibirEmail){ ?>
            document.getElementById('divEmail').style.display = 'block';
          <? } ?>

            document.getElementById('divObservacao').style.display = 'block';

            ////
            //document.getElementById('optMasculino').checked = false;
            //document.getElementById('optFeminino').checked = false;
            document.getElementById('selCargo').selectedIndex = -1;
            document.getElementById('txtTratamento').value = '';
            document.getElementById('txtVocativo').value = '';
            document.getElementById('txtCpf').value = '';
            document.getElementById('txtRg').value = '';
            document.getElementById('txtOrgaoExpedidor').value = '';
            document.getElementById('txtNascimento').value = '';
            document.getElementById('txtMatricula').value = '';
            document.getElementById('txtMatriculaOab').value = '';
            document.getElementById('txtTelefoneComercialPF').value = '';
            document.getElementById('txtTelefoneCelularPF').value = '';
            document.getElementById('txtNumeroPassaporte').value = '';
            document.getElementById('selPaisPassaporte').value = '<?=PaisINT::buscarIdPaisBrasil()?>';
            document.getElementById('txtConjuge').value = '';

        }else{

            document.getElementById('lblSigla').innerText = 'Sigla:';
            document.getElementById('lblNome').innerText = 'Nome:';
            document.getElementById('divContatoAssociado').style.display = 'none';
            document.getElementById('divPessoaFisicaPublico1').style.display = 'none';
            document.getElementById('divPessoaFisicaPublico2').style.display = 'none';
            document.getElementById('divPessoaFisicaPublico3').style.display = 'none';
            document.getElementById('divPessoaFisicaTelefones').style.display = 'none';
            document.getElementById('divConjuge').style.display = 'none';
            document.getElementById('divPessoaFisicaPrivado').style.display = 'none';
            document.getElementById('divPessoaJuridicaTelefonesPrivados').style.display = 'none';
            document.getElementById('divPessoaFisicaTelefonesPrivados').style.display = 'none';
            document.getElementById('divPessoaJuridica').style.display = 'none';
            document.getElementById('divEndereco').style.display = 'none';
            document.getElementById('divEnderecoAssociado').style.display = 'none';
            document.getElementById('divEmail').style.display = 'none';
            document.getElementById('divObservacao').style.display = 'none';
        }
    }

    function mascaraCepBrasil(event){
        return infraMascaraCEP(document.getElementById('txtCep'), event);
    }

    function mascaraCepGeral(event){
        return infraMascaraTexto(document.getElementById('txtCep'),event,15)
    }

    function trocarGenero(){
        document.getElementById('selCargo').disabled = false;
        document.getElementById('txtTratamento').value = '';
        document.getElementById('txtVocativo').value = '';
        objAjaxCargo.executar();
    }

    function trocarPais(bolInicializacao){
      if($("#selPais").val()=="<?=PaisINT::buscarIdPaisBrasil()?>"){
        $("#txtUf").hide();
        $("#txtCidade").hide();
        $("#selUf").show();
        $("#selCidade").show();
      }else{
        $("#txtUf").show();
        $("#txtCidade").show();
        $("#selUf").hide();
        $("#selCidade").hide();
        $("#selUf").val(null);
        $("#selCidade").val(null);
        if(!bolInicializacao){
          $("#txtUf").val(null);
          $("#txtCidade").val(null);
        }
      }
    }
    // function infraMascaraNumeroPassaporte(object,event){
    //     numeroPassaporte = object.value;
    //     if(numeroPassaporte != null && numeroPassaporte != ""){
    //         numeroPassaporte = numeroPassaporte.toUpperCase().replace(/[^A-Z0-9-\s]/i,"");
    //         object.value = numeroPassaporte;
    //     }
    // }

    //</script>
<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
    <form id="frmContatoCadastro" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].'&id_tipo_contato='.$objContatoDTO->getNumIdTipoContato().'&sta_natureza='.$objContatoDTO->getStrStaNatureza().'&id_pais='.$objContatoDTO->getNumIdPais().'&id_uf='.$objContatoDTO->getNumIdUf().'&id_cidade='.$objContatoDTO->getNumIdCidade().'&id_pais_passaporte='.$objContatoDTO->getNumIdPaisPassaporte().'&bloquear_identificacao='.$strSinBloquearIdentificacao.'&bloquear_externo='.$strSinBloquearExterno.'&bloquear_tipo_contato='.$strSinBloquearTipoContato.'&arvore='.$_GET['arvore'])?>">
      <?
      //PaginaSEI::getInstance()->montarBarraLocalizacao($strTitulo);
      PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
      //PaginaSEI::getInstance()->montarAreaValidacao();
      ?>
        <div id="divGeral" class="infraAreaDados">
            <label id="lblTipoContato" for="selTipoContato" class="infraLabelObrigatorio">Tipo:</label>
            <select <?=$strTagDisabledIdentificacao?> id="selTipoContato" name="selTipoContato" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strDesabilitar;?>>
              <?=$strItensSelTipoContato?>
            </select>

            <fieldset id="fldNatureza" class="infraFieldset">
                <legend class="infraLegend">Natureza</legend>

                <div id="divOptPessoaFisica" class="infraDivRadio">
                    <input <?=$strTagDisabledIdentificacao?> type="radio" name="rdoNatureza" id="optPessoaFisica" onchange="formatarExibicao()" value="<?=ContatoRN::$TN_PESSOA_FISICA?>" <?=($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_FISICA?'checked="checked"':'')?> class="infraRadio"/>
                    <span <?=$strTagDisabledIdentificacao?> id="spnPessoaFisica"><label id="lblPessoaFisica" for="optPessoaFisica" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Pessoa Física</label></span>
                </div>

                <div id="divOptPessoaJuridica" class="infraDivRadio">
                    <input <?=$strTagDisabledIdentificacao?> type="radio" name="rdoNatureza" id="optPessoaJuridica" onchange="formatarExibicao()" value="<?=ContatoRN::$TN_PESSOA_JURIDICA?>" <?=($objContatoDTO->getStrStaNatureza()==ContatoRN::$TN_PESSOA_JURIDICA?'checked="checked"':'')?> class="infraRadio"/>
                    <span <?=$strTagDisabledIdentificacao?> id="spnPessoaJuridica"><label id="lblPessoaJuridica" for="optPessoaJuridica" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Pessoa Jurídica</label></span>
                </div>

            </fieldset>

            <label id="lblSigla" for="txtSigla" class="infraLabelOpcional">Sigla:</label>
            <input type="text" id="txtSigla" name="txtSigla" maxlength="100" class="infraText<?=$strCssReadOnlyIdentificacao?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrSigla());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strTagReadOnlyIdentificacao?> />

            <label id="lblNome" for="txtNome" class="infraLabelObrigatorio">Nome:</label>
            <input type="text" id="txtNome" name="txtNome" class="infraText<?=$strCssReadOnlyIdentificacao?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrNome());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strTagReadOnlyIdentificacao?> />

            <div id="divPessoaFisicaPublico1">
              <label id="lblNomeSocial" for="txtNomeSocial" class="infraLabelOpcional">Nome Social:</label>
              <input type="text" id="txtNomeSocial" name="txtNomeSocial" class="infraText<?=$strCssReadOnlyIdentificacao?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrNomeSocial());?>" onkeypress="return infraMascaraTexto(this,event,250);" maxlength="250" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strTagReadOnlyIdentificacao?> />
            </div>

            <fieldset id="fldStaGenero" class="infraFieldset">
              <legend class="infraLegend">&nbsp;Gênero&nbsp;</legend>

              <div id="divOptFeminino" class="infraDivRadio">
                <input type="radio" name="rdoStaGenero" id="optFeminino" value="F" <?=($objContatoDTO->getStrStaGenero()==ContatoRN::$TG_FEMININO?'checked="checked"':'')?> class="infraRadio" onchange="trocarGenero()" />
                <label id="lblFeminino" for="optFeminino" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Feminino</label>
              </div>

              <div id="divOptMasculino" class="infraDivRadio">
                <input type="radio" name="rdoStaGenero" id="optMasculino" value="M" <?=($objContatoDTO->getStrStaGenero()==ContatoRN::$TG_MASCULINO?'checked="checked"':'')?> class="infraRadio" onchange="trocarGenero()" />
                <label id="lblMasculino" for="optMasculino" class="infraLabelRadio" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">Masculino</label>
              </div>

            </fieldset>
        </div>

        <div id="divContatoAssociado" class="infraAreaDados">

            <label id="lblContatoAssociado" class="infraLabelOpcional">Pessoa Jurídica Associada:</label>
            <input type="text" id="txtContatoAssociado" name="txtContatoAssociado" class="infraText<?=$strCssReadOnlyContatoAssociado?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrNomeContatoAssociado())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" <?=$strReadOnlyContatoAssociado?> />
            <input type="hidden" id="hdnIdContatoAssociado" name="hdnIdContatoAssociado" value="<?=$objContatoDTO->getNumIdContatoAssociado()?>" />

            <div id="divSinEnderecoAssociado" class="infraDivCheckbox">
                <input type="checkbox" id="chkSinEnderecoAssociado" name="chkSinEnderecoAssociado" onchange="tratarEnderecoAssociado()" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objContatoDTO->getStrSinEnderecoAssociado())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
                <label id="lblSinEnderecoAssociado" for="chkSinEnderecoAssociado" class="infraLabelCheckbox">Usar endereço associado</label>
            </div>
        </div>

        <div id="divEndereco" class="infraAreaDados">

            <label id="lblEndereco" for="txtEndereco" class="infraLabelOpcional">Endereço:</label>
            <input type="text" id="txtEndereco" <?=$strTagReadOnlyExterno?> name="txtEndereco" class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrEndereco());?>" onkeypress="return infraMascaraTexto(this,event,130);" maxlength="130" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblComplemento" for="txtComplemento" class="infraLabelOpcional">Complemento:</label>
            <input type="text" id="txtComplemento" name="txtComplemento" <?=$strTagReadOnlyExterno?> class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrComplemento());?>" onkeypress="return infraMascaraTexto(this,event,130);" maxlength="130" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblBairro" for="txtBairro" class="infraLabelOpcional">Bairro:</label>
            <input type="text" id="txtBairro" name="txtBairro" <?=$strTagReadOnlyExterno?> class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrBairro());?>" onkeypress="return infraMascaraTexto(this,event,70);" maxlength="70" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblPais" for="selPais" class="infraLabelOpcional">País:</label>
            <select id="selPais" name="selPais" <?=$strTagDisabledExterno?> class="infraSelect" onchange="trocarPais(false)" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
              <?=$strItensSelPais?>
            </select>

            <label id="lblUf" for="selUf" class="infraLabelOpcional">Estado:</label>
            <select id="selUf" name="selUf" <?=$strTagDisabledExterno?> class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
              <?=$strItensSelUf?>
            </select>
            <input type="text" id="txtUf" name="txtUf" <?=$strTagReadOnlyExterno?> class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrNomeUf());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblCidade" for="selCidade" class="infraLabelOpcional">Cidade:</label>
            <select id="selCidade" name="selCidade" <?=$strTagDisabledExterno?> class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
              <?=$strItensSelCidade?>
            </select>
            <input type="text" id="txtCidade" name="txtCidade" <?=$strTagReadOnlyExterno?> class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrNomeCidade());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblCep" for="txtCep" class="infraLabelOpcional">CEP:</label>
            <input type="text" id="txtCep" name="txtCep" <?=$strTagReadOnlyExterno?> class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrCep());?>" maxlength="15" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        </div>

        <div id="divEnderecoAssociado" class="infraAreaDados">

            <label id="lblEnderecoAssociado" for="txtEnderecoAssociado" class="infraLabelOpcional">Endereço:</label>
            <input type="text" id="txtEnderecoAssociado" name="txtEnderecoAssociado" disabled="disabled" class="infraText infraReadOnly" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblComplementoAssociado" for="txtComplementoAssociado" class="infraLabelOpcional">Complemento:</label>
            <input type="text" id="txtComplementoAssociado" name="txtComplementoAssociado" disabled="disabled" class="infraText infraReadOnly" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblBairroAssociado" for="txtBairroAssociado" class="infraLabelOpcional">Bairro:</label>
            <input type="text" id="txtBairroAssociado" name="txtBairroAssociado" disabled="disabled" class="infraText infraReadOnly" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblNomePaisAssociado" for="txtNomePaisAssociado" class="infraLabelOpcional">País:</label>
            <input type="text" id="txtNomePaisAssociado" name="txtNomePaisAssociado" disabled="disabled" class="infraText infraReadOnly" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblUfAssociado" for="selUfAssociado" class="infraLabelOpcional">Estado:</label>
            <input type="text" id="txtUfAssociado" name="txtUfAssociado" disabled="disabled" class="infraText infraReadOnly" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblCidadeAssociado" for="txtCidadeAssociado" class="infraLabelOpcional">Cidade:</label>
            <input type="text" id="txtCidadeAssociado" name="txtCidadeAssociado" disabled="disabled" class="infraText infraReadOnly" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblCepAssociado" for="txtCepAssociado" class="infraLabelOpcional">CEP:</label>
            <input type="text" id="txtCepAssociado" name="txtCepAssociado" disabled="disabled" class="infraText infraReadOnly" value="" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        </div>

        <div id="divPessoaFisicaPublico2" class="infraAreaDados">

          <label id="lblIdCargo" for="selCargo" class="infraLabelOpcional">Cargo:</label>
          <select id="selCargo" name="selCargo" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <?=$strItensSelCargo?>
          </select>

          <label id="lblTratamento" for="txtTratamento" class="infraLabelOpcional">Tratamento:</label>
          <input type="text" id="txtTratamento" name="txtTratamento" disabled="disabled" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrExpressaoTratamentoCargo())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

          <label id="lblVocativo" for="txtVocativo"  class="infraLabelOpcional">Vocativo:</label>
          <input type="text" id="txtVocativo" name="txtVocativo" disabled="disabled" class="infraText infraReadOnly" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrExpressaoVocativoCargo())?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

          <label id="lblIdCategoria" for="selCategoria" class="infraLabelOpcional">Categoria:</label>
          <select id="selCategoria" name="selCategoria" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <?=$strItensSelCategoria?>
          </select>

          <label id="lblFuncao" for="txtFuncao" class="infraLabelOpcional">Função:</label>
          <input type="text" id="txtFuncao" name="txtFuncao" class="infraText" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrFuncao());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

          <label id="lblTitulo" for="txtTitulo"  class="infraLabelOpcional">Título:</label>
          <select id="selTitulo" name="selTitulo" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <?=$strItensSelTitulo?>
          </select>

        </div>

        <div id="divPessoaFisicaPrivado" class="infraAreaDados">
            <label id="lblCpf" for="txtCpf" class="infraLabelOpcional">CPF:</label>
            <input type="text" id="txtCpf" name="txtCpf" <?=$strTagReadOnlyExterno?> onkeypress="return infraMascaraCpf(this, event)" class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML(InfraUtil::formatarCpf($objContatoDTO->getDblCpf()));?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblRg" for="txtRg" class="infraLabelOpcional">RG:</label>
            <input type="text" id="txtRg" name="txtRg" <?=$strTagReadOnlyExterno?> class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getDblRg());?>" onkeypress="return infraMascaraNumero(this,event, 10);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblOrgaoExpedidor" for="txtOrgaoExpedidor" class="infraLabelOpcional">Órgão Expedidor:</label>
            <input type="text" id="txtOrgaoExpedidor" name="txtOrgaoExpedidor" <?=$strTagReadOnlyExterno?> class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrOrgaoExpedidor());?>" onkeypress="return infraMascaraTexto(this,event, 50);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblNascimento" for="txtNascimento" class="infraLabelOpcional">Data de Nascimento:</label>
            <input type="text" id="txtNascimento" name="txtNascimento" onkeypress="return infraMascaraData(this, event)" class="infraText" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getDtaNascimento());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            <img id="imgCalNascimento" title="Selecionar Data" alt="Selecionar Data" src="<?=PaginaSEI::getInstance()->getIconeCalendario()?>" class="infraImg" onclick="infraCalendario('txtNascimento',this);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        </div>

        <div id="divPessoaFisicaPublico3" class="infraAreaDados">
            <label id="lblMatricula" for="txtMatricula" class="infraLabelOpcional">Matrícula:</label>
            <input type="text" id="txtMatricula" name="txtMatricula" maxlength="10" class="infraText" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrMatricula());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

            <label id="lblMatriculaOab" for="txtMatriculaOab" class="infraLabelOpcional">OAB:</label>
            <input type="text" id="txtMatriculaOab" name="txtMatriculaOab" class="infraText" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrMatriculaOab());?>" onkeypress="return infraMascaraTexto(this,event,10);" maxlength="10" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

          <label id="lblNumeroPassaporte" for="txtNumeroPassaporte" class="infraLabelOpcional">Número do Passaporte:</label>
          <input type="text" id="txtNumeroPassaporte" name="txtNumeroPassaporte" <?=$strTagReadOnlyExterno?> onblur="return infraMascaraNumeroPassaporte(this,event);" onkeyup="return infraMascaraNumeroPassaporte(this,event);" maxlength="15" class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrNumeroPassaporte());?>" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

          <label id="lblPaisPassaporte" for="selPaisPassaporte" class="infraLabelOpcional">País de Emissão:</label>
          <select id="selPaisPassaporte" name="selPaisPassaporte" <?=$strTagDisabledExterno?> class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
            <?=$strItensSelPaisPassaporte?>
          </select>
        </div>

        <div id="divPessoaFisicaTelefones" class="infraAreaDados">
            <label id="lblTelefoneComercialPF" for="txtTelefoneComercialPF" class="infraLabelOpcional">Telefone Comercial:</label>
            <input type="text" id="txtTelefoneComercialPF" name="txtTelefoneComercialPF" <?=$strTagReadOnlyExterno?> class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrTelefoneComercial());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            <div id="divPessoaFisicaTelefonesPrivados">
              <label id="lblTelefoneCelularPF" for="txtTelefoneCelularPF" class="infraLabelOpcional">Telefone Celular:</label>
              <input type="text" id="txtTelefoneCelularPF" name="txtTelefoneCelularPF" <?=$strTagReadOnlyExterno?> class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrTelefoneCelular());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

              <label id="lblTelefoneResidencialPF" for="txtTelefoneResidencialPF" class="infraLabelOpcional">Telefone Residencial:</label>
              <input type="text" id="txtTelefoneResidencialPF" name="txtTelefoneResidencialPF" <?=$strTagReadOnlyExterno?> class="infraText<?=$strCssReadOnlyExterno?>" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrTelefoneResidencial());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
            </div>
        </div>

        <div id="divPessoaJuridica" class="infraAreaDados">

          <label id="lblCnpj" for="txtCnpj" class="infraLabelOpcional">CNPJ:</label>
          <input type="text" id="txtCnpj" name="txtCnpj" class="infraText" value="<?=PaginaSEI::tratarHTML(InfraUtil::formatarCnpj($objContatoDTO->getDblCnpj()));?>" onkeypress="return infraMascaraCnpj(this,event);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

          <label id="lblSitioInternet" for="txtSitioInternet" class="infraLabelOpcional">Sítio na Internet:</label>
          <input type="text" id="txtSitioInternet" name="txtSitioInternet" class="infraText" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrSitioInternet());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

          <div id="divPessoaJuridicaTelefonesPrivados">
            <label id="lblTelefoneCelularPJ" for="txtTelefoneCelularPJ" class="infraLabelOpcional">Telefone Celular:</label>
            <input type="text" id="txtTelefoneCelularPJ" name="txtTelefoneCelularPJ" class="infraText" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrTelefoneCelular());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          </div>

          <label id="lblTelefoneComercialPJ" for="txtTelefoneComercialPJ" class="infraLabelOpcional">Telefone Comercial:</label>
          <input type="text" id="txtTelefoneComercialPJ" name="txtTelefoneComercialPJ" class="infraText" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrTelefoneComercial());?>" onkeypress="return infraMascaraTexto(this,event,50);" maxlength="50" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>

        <div id="divConjuge" class="infraAreaDados">
          <label id="lblConjuge" for="txtConjuge" class="infraLabelOpcional">Cônjuge:</label>
          <input type="text" id="txtConjuge" name="txtConjuge" class="infraText" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrConjuge());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>

        <div id="divEmail" class="infraAreaDados">
            <label id="lblEmail" for="txtEmail" class="infraLabelOpcional">E-mail (separe múltiplos com ponto e vírgula ";"):</label>
            <input type="text" id="txtEmail" name="txtEmail" class="infraText" value="<?=PaginaSEI::tratarHTML($objContatoDTO->getStrEmail());?>" onkeypress="return infraMascaraTexto(this,event,100);" maxlength="100" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        </div>

        <div id="divObservacao" class="infraAreaDados">
            <label id="lblObservacao" for="txaObservacao" class="infraLabelOpcional">Observação:</label>
            <textarea id="txaObservacao" name="txaObservacao" rows="3" class="infraTextarea" onkeypress="return infraLimitarTexto(this,event,250);" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objContatoDTO->getStrObservacao());?></textarea>
        </div>

        <input type="hidden" id="hdnIdContato" name="hdnIdContato" value="<?=$objContatoDTO->getNumIdContato();?>" />
        <input type="hidden" id="hdnContatoObject" name="hdnContatoObject" value="<?=$_POST['hdnContatoObject']?>" />
        <input type="hidden" id="hdnFlagContato" name="hdnFlagContato" value="1 />
      <?
      PaginaSEI::getInstance()->montarAreaDebug();
      //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
      ?>
    </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>