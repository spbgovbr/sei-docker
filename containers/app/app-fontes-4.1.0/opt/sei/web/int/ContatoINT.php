<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 09/01/2008 - criado por marcio_db
*
* Versão do Gerador de Código: 1.12.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class ContatoINT extends InfraINT {

  //TRC = Tipo Relatorio Chave
  public static $TRC_DADOS_COMPLETOS = 'D';
  public static $TRC_RESIDENCIAL = 'C';
  public static $TRC_ETIQUETAS = 'E';
  public static $TRC_RELACAO = 'R';
  public static $TRC_OUTRO = 'O';

  //TRD = Tipo Relatorio Descrição
  public static $TRD_DADOS_COMPLETOS = 'Dados Completos';
  public static $TRD_RESIDENCIAL = 'Residencial';
  public static $TRD_ETIQUETAS = 'Etiquetas';
  public static $TRD_RELACAO = 'Relação';
  public static $TRD_OUTRO = 'Outro';

  //TRA = Tipo Coluna Atributo
  public static $TRA_NOME = 'Nome';
  public static $TRA_TRATAMENTO = 'ExpressaoTratamentoCargo';
  public static $TRA_TITULO_ABREVIADO = 'AbreviaturaTituloContato';
  public static $TRA_CATEGORIA = 'NomeCategoria';
  public static $TRA_CARGO = 'ExpressaoCargo';
  public static $TRA_ORGAO = 'NomeContatoAssociado';
  public static $TRA_ENDERECO = 'Endereco';
  public static $TRA_CEP = 'Cep';
  public static $TRA_CIDADE = 'NomeCidade';
  public static $TRA_UF = 'SiglaUf';
  public static $TRA_TITULO = 'ExpressaoTituloContato';
  public static $TRA_FUNCAO = 'Funcao';
  public static $TRA_GENERO = 'StaGenero';
  public static $TRA_ANIVERSARIO = 'Nascimento';
  public static $TRA_TELEFONE_RESIDENCIAL = 'TelefoneResidencial';
  public static $TRA_TELEFONE_COMERCIAL = 'TelefoneComercial';
  public static $TRA_TELEFONE_CELULAR = 'TelefoneCelular';
  public static $TRA_EMAIL = 'Email';
  public static $TRA_OBSERVACAO = 'Observacao';
  public static $TRA_CONJUGE = 'Conjuge';

  //TRN = Tipo Coluna Nome
  public static $TRN_NOME = 'Nome';
  public static $TRN_TRATAMENTO = 'Tratamento';
  public static $TRN_TITULO_ABREVIADO = 'Título Abreviado';
  public static $TRN_CATEGORIA = 'Categoria';
  public static $TRN_CARGO = 'Cargo';
  public static $TRN_ORGAO = 'Órgão';
  public static $TRN_ENDERECO = 'Endereço';
  public static $TRN_CEP = 'CEP';
  public static $TRN_CIDADE = 'Cidade';
  public static $TRN_UF = 'Estado';
  public static $TRN_TITULO = 'Título';
  public static $TRN_FUNCAO = 'Função';
  public static $TRN_GENERO = 'Gênero';
  public static $TRN_ANIVERSARIO = 'Aniversário';
  public static $TRN_TELEFONE_RESIDENCIAL = 'Telefone Residencial';
  public static $TRN_TELEFONE_COMERCIAL = 'Telefone Comercial';
  public static $TRN_TELEFONE_CELULAR = 'Telefone Celular';
  public static $TRN_EMAIL = 'Email';
  public static $TRN_OBSERVACAO = 'Observação';
  public static $TRN_CONJUGE = 'Cônjuge';

  public static function buscarEtiquetasRI0516($arrNumIdContatos,$opcao){

    $objContatoDTO = new ContatoDTO();
    $objContatoDTO->setNumIdContato($arrNumIdContatos,InfraDTO::$OPER_IN);
    
    $objContatoDTO->retNumIdContato();
    $objContatoDTO->retNumIdContatoAssociado();
    $objContatoDTO->retStrStaNaturezaContatoAssociado();
    $objContatoDTO->retStrExpressaoTratamentoCargo();
    $objContatoDTO->retStrExpressaoTituloContato();
    $objContatoDTO->retStrNomeCategoria();
    $objContatoDTO->retStrAbreviaturaTituloContato();
    $objContatoDTO->retStrExpressaoVocativoCargo();
    $objContatoDTO->retStrExpressaoCargo();
    $objContatoDTO->retStrNome();
    $objContatoDTO->retStrFuncao();
    $objContatoDTO->retStrNomeContatoAssociado();
    $objContatoDTO->retStrStaNatureza();
    $objContatoDTO->retNumIdPais();
    $objContatoDTO->retStrStaGeneroContatoAssociado();

    $objContatoDTO->setOrdStrNomeContatoAssociado(InfraDTO::$TIPO_ORDENACAO_ASC);
    $objContatoDTO->setOrdStrStaNaturezaContatoAssociado(InfraDTO::$TIPO_ORDENACAO_DESC);
    $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
    
    $objContatoRN = new ContatoRN();
    $arrObjContatoDTO = $objContatoRN->listarComEndereco($objContatoDTO);
    
    $arrLinhas = array();

    for ($i=0;$i<count($arrObjContatoDTO);$i++){

      $expNome = '';
	    $tratamento = '';
	    $cargo = '';
      $endereco = '';
      $complemento = '';
      $cidade = '';
      $titulo = '';
      $tituloAbv = '';
      $vocativo = '';
      $contextoContato = '';
      $doDaPJ = '';

      if($arrObjContatoDTO[$i]->getNumIdContatoAssociado() != $arrObjContatoDTO[$i]->getNumIdContato()) {
        if (!InfraString::isBolVazia($arrObjContatoDTO[$i]->getStrStaGeneroContatoAssociado())) {
          if ($arrObjContatoDTO[$i]->getStrStaGeneroContatoAssociado() == ContatoRN::$TG_MASCULINO) {
            $doDaPJ = ' do ';
          } else if ($arrObjContatoDTO[$i]->getStrStaGeneroContatoAssociado() == ContatoRN::$TG_FEMININO) {
            $doDaPJ = ' da ';
          }
        }
      }

      if (!InfraString::isBolVazia($arrObjContatoDTO[$i]->getStrNome())){
        $expNome = $arrObjContatoDTO[$i]->getStrNome().'<br />';
      }

      if (!InfraString::isBolVazia($arrObjContatoDTO[$i]->getStrExpressaoCargo())){
        $cargo = $arrObjContatoDTO[$i]->getStrExpressaoCargo();
      }

      //if (!InfraString::isBolVazia($arrObjContatoDTO[$i]->getStrFuncao())){
      //  $cargo .= " ".$arrObjContatoDTO[$i]->getStrFuncao();
      //}

      if ($arrObjContatoDTO[$i]->getStrStaNatureza() == ContatoRN::$TN_PESSOA_FISICA){
        if (!InfraString::isBolVazia($arrObjContatoDTO[$i]->getStrExpressaoTratamentoCargo())){
          $tratamento = $arrObjContatoDTO[$i]->getStrExpressaoTratamentoCargo().'<br />';
        }
        if (!InfraString::isBolVazia($arrObjContatoDTO[$i]->getStrExpressaoTituloContato())){
          $titulo = $arrObjContatoDTO[$i]->getStrExpressaoTituloContato().' ';
        }
        if (!InfraString::isBolVazia($arrObjContatoDTO[$i]->getStrAbreviaturaTituloContato())){
          $tituloAbv = $arrObjContatoDTO[$i]->getStrAbreviaturaTituloContato().' ';
        }
      }

      $strEndereco = $arrObjContatoDTO[$i]->getStrEndereco();
      $strComplemento = $arrObjContatoDTO[$i]->getStrComplemento();
      $strCep = $arrObjContatoDTO[$i]->getStrCep();
      $strNomeCidade = $arrObjContatoDTO[$i]->getStrNomeCidade();
      $strSiglaUf = $arrObjContatoDTO[$i]->getStrSiglaUf();
      $strNomeUf = $arrObjContatoDTO[$i]->getStrNomeUf();
      $strNomePais = $arrObjContatoDTO[$i]->getStrNomePais();
      $numIdPais = $arrObjContatoDTO[$i]->getNumIdPais();

      if (!InfraString::isBolVazia($strEndereco)){
        $endereco = $strEndereco.'<br />';
      }

      if (!InfraString::isBolVazia($strComplemento)){
        $complemento = $strComplemento.'<br />';
      }

      $separador = '';
      if (!InfraString::isBolVazia($strCep)){
        $cidade .= $separador.$strCep;
        $separador = ' - ';
      }

      if (!InfraString::isBolVazia($strNomeCidade)){
        $cidade .= $separador.$strNomeCidade;
        $separador = ' - ';
      }

      if (!InfraString::isBolVazia($numIdPais) && $numIdPais == PaisINT::buscarIdPaisBrasil()) {
        if (!InfraString::isBolVazia($strSiglaUf)) {
          $cidade .= $separador . $strSiglaUf;
          $separador = ' - ';
        }
      }else{
        if (!InfraString::isBolVazia($strNomeUf)) {
          $cidade .= $separador . $strNomeUf;
          $separador = ' - ';
        }
      }

      if (!InfraString::isBolVazia($numIdPais) && $numIdPais != PaisINT::buscarIdPaisBrasil()){
        $cidade .= $separador.$strNomePais;
      }

      if (!InfraString::isBolVazia($arrObjContatoDTO[$i]->getStrExpressaoVocativoCargo())){
        $vocativo = $arrObjContatoDTO[$i]->getStrExpressaoVocativoCargo().'<br />';
      }

      if($arrObjContatoDTO[$i]->getNumIdContatoAssociado() != $arrObjContatoDTO[$i]->getNumIdContato()) {
        if (!InfraString::isBolVazia($arrObjContatoDTO[$i]->getStrNomeContatoAssociado())) {
          $contextoContato .= $arrObjContatoDTO[$i]->getStrNomeContatoAssociado().'<br />';
        }
      }

      $arrColunas = array();
      $arrColunas[] = $arrObjContatoDTO[$i]->getNumIdContato();
      if($opcao == 1){
        $arrColunas[] = $tratamento.$tituloAbv.$expNome.$cargo.$doDaPJ.$contextoContato.$endereco.$complemento.$cidade;
      }else if($opcao==2){
        $arrColunas[] = $tratamento.$tituloAbv.$expNome.$cargo.$doDaPJ.$contextoContato;
      }

      $arrLinhas[] = $arrColunas;
    }

    return PaginaSEI::getInstance()->gerarItensTabelaDinamica(array_reverse($arrLinhas));
  }

  public static function montarSelectContatosGrupoRI0495($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $numIdGrupoContato){

    if (InfraString::isBolVazia($numIdGrupoContato)) {
      return '';
    }

    $objContatoDTO = new ContatoDTO();
    $objContatoDTO->retNumIdContato();
    $objContatoDTO->retStrNome();
    $objContatoDTO->setNumIdGrupoContato($numIdGrupoContato);
    $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objContatoRN = new ContatoRN();
    $arrObjContatoDTO = $objContatoRN->pesquisarRN0471($objContatoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjContatoDTO, 'IdContato', 'Nome');
  }

  public static function montarSelectDestinatarios($arr){
    $ret = '';

    if (InfraArray::contar($arr)) {

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setBolExclusaoLogica(false);
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retStrSigla();
      $objContatoDTO->retStrNome();
      $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objContatoDTO->setNumIdContato($arr, InfraDTO::$OPER_IN);

      $objContatoRN = new ContatoRN();
      $arrObjContatoDTO = $objContatoRN->listarRN0325($objContatoDTO);

      foreach($arrObjContatoDTO as $objContatoDTO){
        $objContatoDTO->setStrNome(ContatoINT::formatarNomeSiglaRI1224($objContatoDTO->getStrNome(),$objContatoDTO->getStrSigla()));
      }

      $ret = parent::montarSelectArrInfraDTO(null, null, null, $arrObjContatoDTO, 'IdContato', 'Nome');
    }
    return $ret;
  }

  public static function formatarNomeSiglaRI1224($strNome, $strSigla){
    $str = $strNome;

    if (!InfraString::isBolVazia($strSigla)){
      $str .= ' ('.$strSigla.')';
    }

    return $str;
  }

  public static function autoCompletarAcessoExterno($strPalavrasPesquisa,$numIdGrupoContato){

    $arrRet = array();

    $objPesquisaTipoContatoDTO = new PesquisaTipoContatoDTO();
    $objPesquisaTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_RESUMIDA);

    $objTipoContatoRN = new TipoContatoRN();
    $arrIdTipoContatoAcesso = $objTipoContatoRN->pesquisarAcessoUnidade($objPesquisaTipoContatoDTO);

    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->setBolExclusaoLogica(false);
    $objOrgaoDTO->retStrSigla();

    $objOrgaoRN = new OrgaoRN();
    $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

    $arrChavesParametros = array();
    foreach($arrObjOrgaoDTO as $objOrgaoDTO){
      $arrChavesParametros[] = $objOrgaoDTO->getStrSigla().'_ID_TIPO_CONTATO_USUARIOS_EXTERNOS';
    }

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $arrValoresParametros = $objInfraParametro->listarValores($arrChavesParametros,false);

    foreach($arrValoresParametros as $numValorParametro){
      $numValorParametro = trim($numValorParametro);
      if (is_numeric($numValorParametro) && !in_array($numValorParametro, $arrIdTipoContatoAcesso)){
        $arrIdTipoContatoAcesso[] = $numValorParametro;
      }
    }

    if (count($arrIdTipoContatoAcesso)) {

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retStrSigla();
      $objContatoDTO->retStrNome();
      $objContatoDTO->retStrNomeRegistroCivil();

      $objContatoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

      if ($numIdGrupoContato != '') {
        $objContatoDTO->setNumIdGrupoContato($numIdGrupoContato);
      }

      $objContatoDTO->adicionarCriterio(array('StaAcessoTipoContato', 'IdTipoContato'),
                                        array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IN),
                                        array(TipoContatoRN::$TA_NENHUM, $arrIdTipoContatoAcesso),
                                        InfraDTO::$OPER_LOGICO_OR);

      $objContatoDTO->setStrSinAtivoTipoContato('S');
      $objContatoDTO->setNumMaxRegistrosRetorno(50);
      $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objContatoRN = new ContatoRN();
      $arrObjContatoDTO = $objContatoRN->pesquisarRN0471($objContatoDTO);

      foreach ($arrObjContatoDTO as $objContatoDTO){

        if ($objContatoDTO->getStrNome() != $objContatoDTO->getStrNomeRegistroCivil()) {
          $objContatoDTO2 = clone($objContatoDTO);
          $objContatoDTO2->setStrNome(ContatoINT::formatarNomeSiglaRI1224($objContatoDTO->getStrNomeRegistroCivil(), $objContatoDTO->getStrSigla()));
          $arrRet[] = $objContatoDTO2;
        }

        $objContatoDTO->setStrNome(ContatoINT::formatarNomeSiglaRI1224($objContatoDTO->getStrNome(), $objContatoDTO->getStrSigla()));
        $arrRet[] = $objContatoDTO;
      }
    }

    return $arrRet;
  }

  public static function autoCompletarContextoRI1225($strPalavrasPesquisa,$numIdGrupoContato){

    $arrObjContatoDTO = array();

    $objPesquisaTipoContatoDTO = new PesquisaTipoContatoDTO();
    $objPesquisaTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_RESUMIDA);

    $objTipoContatoRN = new TipoContatoRN();
    $arrIdTipoContatoAcesso = $objTipoContatoRN->pesquisarAcessoUnidade($objPesquisaTipoContatoDTO);

    if (count($arrIdTipoContatoAcesso)) {

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retStrSigla();
      $objContatoDTO->retStrNome();
      $objContatoDTO->retStrSiglaContatoAssociado();

      $objContatoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

      if ($numIdGrupoContato != '') {
        $objContatoDTO->setNumIdGrupoContato($numIdGrupoContato);
      }

      $objContatoDTO->adicionarCriterio(array('StaAcessoTipoContato', 'IdTipoContato'),
                                        array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IN),
                                        array(TipoContatoRN::$TA_NENHUM, $arrIdTipoContatoAcesso),
                                        InfraDTO::$OPER_LOGICO_OR);

      $objContatoDTO->setStrSinAtivoTipoContato('S');
      $objContatoDTO->setNumMaxRegistrosRetorno(50);
      $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objContatoRN = new ContatoRN();
      $arrObjContatoDTO = $objContatoRN->pesquisarRN0471($objContatoDTO);

      $arrTemp = array();
      foreach($arrObjContatoDTO as $objContatoDTO){
        if ($objContatoDTO->getStrSigla()!=null && $objContatoDTO->getStrSiglaContatoAssociado()!= null) {
          $strChave = strtolower($objContatoDTO->getStrNome().'-'.$objContatoDTO->getStrSigla());
          if (!isset($arrTemp[$strChave])) {
            $arrTemp[$strChave] = array($objContatoDTO);
          } else {
            $arrTemp[$strChave][] = $objContatoDTO;
          }
        }
      }

      foreach($arrTemp as $arr){
        if (count($arr) == 1){
          $arr[0]->setStrNome($arr[0]->getStrNome().' ('.$arr[0]->getStrSigla().')');
        }else{
          foreach($arr as $dto){
            $dto->setStrNome($dto->getStrNome().' ('.$dto->getStrSigla().' / '.$dto->getStrSiglaContatoAssociado().')');
          }
        }
      }
    }

    return $arrObjContatoDTO;
  }

  public static function autoCompletarPesquisa($strPalavrasPesquisa){

    $arrRet = array();

    $objContatoDTO = new ContatoDTO();
    $objContatoDTO->setBolExclusaoLogica(false);
    $objContatoDTO->retNumIdContato();
    $objContatoDTO->retStrSigla();
    $objContatoDTO->retStrNome();
    $objContatoDTO->retStrNomeRegistroCivil();
    $objContatoDTO->retStrSinSistemaTipoContato();

    $objContatoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

    $objContatoDTO->setNumMaxRegistrosRetorno(50);
    $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objContatoRN = new ContatoRN();
    $arrRet = InfraArray::indexarArrInfraDTO($objContatoRN->pesquisarRN0471($objContatoDTO),'IdContato');

    $arrIdContatoSistema = array();
    foreach ($arrRet as $objContatoDTO) {

      if ($objContatoDTO->getStrSinSistemaTipoContato()=='S'){
        $arrIdContatoSistema[] = $objContatoDTO->getNumIdContato();
      }

      //$objContatoDTO->setStrNome(ContatoINT::formatarNomeSiglaRI1224($objContatoDTO->getStrNome(), $objContatoDTO->getStrSigla()));
    }

    if (count($arrIdContatoSistema)){

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setDistinct(true);
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->retStrIdOrigem();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->retDblCpfContato();
      $objUsuarioDTO->setNumIdContato($arrIdContatoSistema, InfraDTO::$OPER_IN);
      $objUsuarioDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objUsuarioDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objUsuarioRN = new UsuarioRN();
      $arrObjUsuarioDTO = $objUsuarioRN->pesquisar($objUsuarioDTO);

      $arrFiltro = array();
      foreach($arrObjUsuarioDTO as $objUsuarioDTO){

        $strChave = '-';

        if ($objUsuarioDTO->getStrIdOrigem()!=null){
          $strChave = 'IDO'.$objUsuarioDTO->getStrIdOrigem();
        }else if ($objUsuarioDTO->getDblCpfContato()!=null){
          $strChave = 'CPF'.$objUsuarioDTO->getDblCpfContato();
        }

        $arrFiltro[$strChave][$objUsuarioDTO->getStrNome()][] = $objUsuarioDTO;
      }

      foreach($arrFiltro as $arrPorNome) {
        foreach ($arrPorNome as $strNome => $arrObjUsuarioDTO) {

          $numIdContatoAgrupador = $arrObjUsuarioDTO[0]->getNumIdContato();

          if (isset($arrRet[$numIdContatoAgrupador])) {
            $arrRet[$numIdContatoAgrupador]->setStrSigla(implode(',',array_unique(InfraArray::converterArrInfraDTO($arrObjUsuarioDTO, 'Sigla'))));
          }

          $numUsuarios = InfraArray::contar($arrObjUsuarioDTO);
          for($i=1; $i < $numUsuarios; $i++){
            if ($arrObjUsuarioDTO[$i]->getNumIdContato()!=$numIdContatoAgrupador) {
              unset($arrRet[$arrObjUsuarioDTO[$i]->getNumIdContato()]);
            }
          }
        }
      }
    }

    $arrRet = array_values($arrRet);

    foreach($arrRet as $objContatoDTO){

      if ($objContatoDTO->getStrNomeRegistroCivil()!=null && $objContatoDTO->getStrNome()!=$objContatoDTO->getStrNomeRegistroCivil()) {
        $objContatoDTO2 = clone($objContatoDTO);
        $objContatoDTO2->setStrNome(ContatoINT::formatarNomeSiglaRI1224($objContatoDTO->getStrNomeRegistroCivil(), $objContatoDTO->getStrSigla()));
        $arrRet[] = $objContatoDTO2;
      }

      $objContatoDTO->setStrNome(ContatoINT::formatarNomeSiglaRI1224($objContatoDTO->getStrNome(), $objContatoDTO->getStrSigla()));
    }

    return $arrRet;
  }

  public static function autoCompletarUsuariosPesquisa($strPalavrasPesquisa, $strSinUsuariosInternos, $strSinUsuariosExternos){

    $ret = array();

    if ($strSinUsuariosInternos=='S' || $strSinUsuariosExternos=='S'){

      $objUsuarioDTO = new UsuarioDTO();
      $objUsuarioDTO->setDistinct(true);
      $objUsuarioDTO->setBolExclusaoLogica(false);
      $objUsuarioDTO->retNumIdContato();
      $objUsuarioDTO->retStrIdOrigem();
      $objUsuarioDTO->retStrSigla();
      $objUsuarioDTO->retStrNome();
      $objUsuarioDTO->retStrNomeRegistroCivil();
      $objUsuarioDTO->retDblCpfContato();
      $objUsuarioDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

      $arrStaTipo = array();
      if ($strSinUsuariosInternos=='S'){
        $arrStaTipo[] = UsuarioRN::$TU_SIP;
      }

      if ($strSinUsuariosExternos=='S') {
        $arrStaTipo[] = UsuarioRN::$TU_EXTERNO;
      }

      $objUsuarioDTO->setStrStaTipo($arrStaTipo, InfraDTO::$OPER_IN);

      $objUsuarioDTO->setNumMaxRegistrosRetorno(50);

      $objUsuarioDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);
      $objUsuarioDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objUsuarioRN = new UsuarioRN();
      $arrObjUsuarioDTO = $objUsuarioRN->pesquisar($objUsuarioDTO);

      $arrFiltro = array();
      foreach($arrObjUsuarioDTO as $objUsuarioDTO){
        $arrFiltro[$objUsuarioDTO->getStrIdOrigem().'¥'.$objUsuarioDTO->retDblCpfContato()][$objUsuarioDTO->getStrNome()][$objUsuarioDTO->getStrSigla()] = $objUsuarioDTO;
      }

      foreach($arrFiltro as $arrPorNome) {
        foreach ($arrPorNome as $strNome => $arrPorSigla) {

          $objUsuarioDTO = $arrPorSigla[key($arrPorSigla)];

          $objContatoDTO = new ContatoDTO();
          $objContatoDTO->setNumIdContato($objUsuarioDTO->getNumIdContato());
          $objContatoDTO->setStrNome(ContatoINT::formatarNomeSiglaRI1224($strNome, implode(', ', array_keys($arrPorSigla))));
          $ret[] = $objContatoDTO;

          if ($objUsuarioDTO->getStrNome()!=$objUsuarioDTO->getStrNomeRegistroCivil()){
            $objContatoDTO = new ContatoDTO();
            $objContatoDTO->setNumIdContato($objUsuarioDTO->getNumIdContato());
            $objContatoDTO->setStrNome(ContatoINT::formatarNomeSiglaRI1224($objUsuarioDTO->getStrNomeRegistroCivil(), implode(', ', array_keys($arrPorSigla))));
            $ret[] = $objContatoDTO;
          }
        }
      }
    }

    return $ret;
  }

  public static function autoCompletarContextoSubstituicao($strPalavrasPesquisa){

    $objPesquisaTipoContatoDTO = new PesquisaTipoContatoDTO();
    $objPesquisaTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_RESUMIDA);

    $objTipoContatoRN = new TipoContatoRN();
    $arrIdTipoContatoAcesso = $objTipoContatoRN->pesquisarAcessoUnidade($objPesquisaTipoContatoDTO);

    $objInfraParametro = new InfraParametro(BancoSEI::getInstance());
    $arrIdTipoContatoAcesso[] = $objInfraParametro->getValor('ID_TIPO_CONTATO_TEMPORARIO');

    $objContatoDTO = new ContatoDTO();
    $objContatoDTO->retNumIdContato();
    $objContatoDTO->retStrNomeTipoContato();
    $objContatoDTO->retStrSigla();
    $objContatoDTO->retStrNome();

    $objContatoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

    $objContatoDTO->adicionarCriterio(array('StaAcessoTipoContato', 'IdTipoContato'),
                                      array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IN),
                                      array(TipoContatoRN::$TA_NENHUM, $arrIdTipoContatoAcesso),
                                      InfraDTO::$OPER_LOGICO_OR);

    $objContatoDTO->setStrSinAtivoTipoContato('S');
    $objContatoDTO->setNumMaxRegistrosRetorno(50);
    $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objContatoRN = new ContatoRN();
    $arrObjContatoDTO = $objContatoRN->pesquisarRN0471($objContatoDTO);

    foreach ($arrObjContatoDTO as $objContatoDTO) {
      $objContatoDTO->setStrNome(ContatoINT::formatarNomeSiglaRI1224($objContatoDTO->getStrNome(), $objContatoDTO->getStrSigla()).' - '.$objContatoDTO->getStrNomeTipoContato());
    }

    return $arrObjContatoDTO;
  }

  public static function montarContatoAssociado($bolSip, $numIdSip, $bolSei, $numIdSei, $bolOrigem, $strIdOrigem, $bolPessoaFisica, $numIdContato, $strSigla, $strNome, $strNomeSocial, $bolReadOnlySiglaNome, $strForm){

    if (PaginaSEI::getInstance()->isBolAjustarTopFieldset()) {

      if ($bolSip){
        $strTopLabelSip = '5%';
        $strTopTextSip = '17%';
        if ($bolSei) {
          $strTopLabelSei = '35%';
          $strTopTextSei = '47%';
          if ($bolOrigem){
            $strTopLabelOrigem = '65%';
            $strTopTextOrigem = '77%';
          }
        } else if ($bolOrigem) {
          $strTopLabelOrigem = '35%';
          $strTopTextOrigem = '47%';
        }
      }

      $strTopLabelSigla = '5%';
      $strTopTextSigla = '17%';
      $strTopLabelNome = '35%';
      $strTopTextNome = '47%';
      $strTopLabelNomeSocial = '65%';
      $strTopTextNomeSocial = '77%';

    } else {

      if ($bolSip){
        $strTopLabelSip = '15%';
        $strTopTextSip = '26%';
        if ($bolSei) {
          $strTopLabelSei = '41%';
          $strTopTextSei = '52%';
          if ($bolOrigem){
            $strTopLabelOrigem = '67%';
            $strTopTextOrigem = '78%';
          }
        } else if ($bolOrigem) {
          $strTopLabelOrigem = '41%';
          $strTopTextOrigem = '52%';
        }
      }

      $strTopLabelSigla = '15%';
      $strTopTextSigla = '26%';
      $strTopLabelNome = '41%';
      $strTopTextNome = '52%';
      $strTopLabelNomeSocial = '67%';
      $strTopTextNomeSocial = '78%';
    }

    $strHtml = '<div id="divContatoAssociado" class="infraAreaDados" style="height:20em">';

   if ($bolSip || $bolSei || $bolOrigem) {
     $strHtml .= '<fieldset id="fldCodigo" class="infraFieldset" style="position:absolute;left:0%;top:0%;height:85%;width:20%;">
                  <legend class="infraLegend">Códigos</legend>'."\n";

     if ($bolSip) {
       $strHtml .= '<label id = "lblCodigoSip" for="txtCodigoSip" class="infraLabelObrigatorio" style = "position:absolute;left:10%;top:'.$strTopLabelSip.';width:70%;" > SIP:</label >
                    <input type = "text" id = "txtCodigoSip" name = "txtCodigoSip" class="infraText infraReadOnly" style = "position:absolute;left:10%;top:'.$strTopTextSip.';width:70%;" value = "' . PaginaSEI::tratarHTML($numIdSip) . '" tabindex = "' . PaginaSEI::getInstance()->getProxTabDados() . '" readonly = "readonly" />'."\n";
     }

     if ($bolSei) {
       $strHtml .= '<label id="lblCodigoSei" for="txtCodigoSei" class="infraLabelOpcional" style="position:absolute;left:10%;top:' . $strTopLabelSei . ';width:70%;">SEI:</label>
                    <input type="text" id="txtCodigoSei" name="txtCodigoSei" class="infraText" style="position:absolute;left:10%;top:' . $strTopTextSei . ';width:70%;" value="' . PaginaSEI::tratarHTML($numIdSei) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" onkeypress="return infraMascaraNumero(this, event)" />' . "\n";
     }

     if ($bolOrigem) {
       $strHtml .= '<label id="lblCodigoOrigem" for="txtCodigoOrigem" class="infraLabelOpcional" style="position:absolute;left:10%;top:' . $strTopLabelOrigem . ';width:70%;">Origem:</label>
                    <input type="text" id="txtCodigoOrigem" name="txtCodigoOrigem" class="infraText" style="position:absolute;left:10%;top:' . $strTopTextOrigem . ';width:70%;" value="' . PaginaSEI::tratarHTML($strIdOrigem) . '" tabindex="' . PaginaSEI::getInstance()->getProxTabDados() . '" readonly="readonly" />' . "\n";
     }

     $strHtml .= '</fieldset>';
   }

    $strHtml .= '
      <fieldset id="fldContatoAssociado" class="infraFieldset" style="position:absolute;left:'.($bolSip?'25%':'0').';top:0%;height:85%;width:70%;">
      <legend class="infraLegend">Contato Associado</legend>
  
      <label id="lblSiglaContatoAssociado" for="txtSiglaContatoAssociado" class="infraLabelObrigatorio" style="position:absolute;left:3%;top:'.$strTopLabelSigla.';width:45%">Sigla:</label>
      <input type="text" id="txtSiglaContatoAssociado" name="txtSiglaContatoAssociado" class="infraText' . ($bolReadOnlySiglaNome ? ' infraReadOnly' : '') . '" style="position:absolute;left:3%;top:'.$strTopTextSigla.';width:45%" value="'.PaginaSEI::tratarHTML($strSigla).'" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'" ' . ($bolReadOnlySiglaNome ? 'readonly="true"' : '') . ' />
  
      <label id="lblNomeContatoAssociado" for="txtNomeContatoAssociado" class="infraLabelObrigatorio" style="position:absolute;left:3%;top:'.$strTopLabelNome.';width:80%">Nome:</label>
      <input type="text" id="txtNomeContatoAssociado" name="txtNomeContatoAssociado" class="infraText' . ($bolReadOnlySiglaNome ? ' infraReadOnly' : '') . '" style="position:absolute;left:3%;top:'.$strTopTextNome.';width:80%" value="'.PaginaSEI::tratarHTML($strNome).'" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'" ' . ($bolReadOnlySiglaNome ? 'readonly="true"' : '') . ' />';

   if ($bolPessoaFisica){
     $strHtml .= '
      <label id="lblNomeSocialContatoAssociado" for="txtNomeSocialContatoAssociado" class="infraLabelOpcional" style="position:absolute;left:3%;top:'.$strTopLabelNomeSocial.';width:80%">Nome Social:</label>
      <input type="text" id="txtNomeSocialContatoAssociado" name="txtNomeSocialContatoAssociado" class="infraText' . ($bolReadOnlySiglaNome ? ' infraReadOnly' : '') . '" style="position:absolute;left:3%;top:'.$strTopTextNomeSocial.';width:80%" value="'.PaginaSEI::tratarHTML($strNomeSocial).'" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'" ' . ($bolReadOnlySiglaNome ? 'readonly="true"' : '') . ' />';
   }

    $strHtml .= '
      <div id="divOpcoesContato" style="position:absolute;left:90%;top:30%;">
        <img id="imgAlterarContato" onclick="seiCadastroContato(\''.$numIdContato.'\', \'txtNomeContatoAssociado\', \''.$strForm.'\',\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=contato_alterar&acao_origem='.$_GET['acao']).'\')" src="'.Icone::CONTATO_ALTERAR.'" alt="Alterar Dados do Contato Associado" title="Alterar Dados do Contato Associado" class="infraImg" width="48" height="48" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"/>
      </div>
      </fieldset>
    
      <input type="hidden" id="hdnContatoObject" name="hdnContatoObject" value="" />
      <input type="hidden" id="hdnContatoIdentificador" name="hdnContatoIdentificador" value="" />
      </div>
      <br />';

    echo $strHtml;
  }

  public static function autoCompletarAssociado($strPalavrasPesquisa, $numIdTipoContato){

    $arrObjContatoDTO = array();

    $objPesquisaTipoContatoDTO = new PesquisaTipoContatoDTO();
    $objPesquisaTipoContatoDTO->setStrStaAcesso(TipoContatoRN::$TA_CONSULTA_RESUMIDA);

    if ($numIdTipoContato!=null){
      $objPesquisaTipoContatoDTO->setArrIdTipoContato(array($numIdTipoContato));
    }

    $objTipoContatoRN = new TipoContatoRN();
    $arrIdTipoContatoAcesso = $objTipoContatoRN->pesquisarAcessoUnidade($objPesquisaTipoContatoDTO);

    if (count($arrIdTipoContatoAcesso)) {
      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->retNumIdContato();
      $objContatoDTO->retStrNome();
      $objContatoDTO->setNumIdContatoAssociado($objContatoDTO->getObjInfraAtributoDTO('IdContato'));
      $objContatoDTO->setStrStaNatureza(ContatoRN::$TN_PESSOA_JURIDICA);

      if ($numIdTipoContato!=null){
        $objContatoDTO->setNumIdTipoContato($numIdTipoContato);
      }

      $objContatoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);

      $objContatoDTO->adicionarCriterio(array('StaAcessoTipoContato', 'IdTipoContato'),
                                        array(InfraDTO::$OPER_DIFERENTE, InfraDTO::$OPER_IN),
                                        array(TipoContatoRN::$TA_NENHUM, $arrIdTipoContatoAcesso),
                                        InfraDTO::$OPER_LOGICO_OR);

      $objContatoDTO->setStrSinAtivoTipoContato('S');
      $objContatoDTO->setNumMaxRegistrosRetorno(50);
      $objContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objContatoRN = new ContatoRN();
      $arrObjContatoDTO = $objContatoRN->pesquisarRN0471($objContatoDTO);
    }

    return $arrObjContatoDTO;
  }

  public static function montarSelectTipoRelatorio($strTipoRelatorio){
    $arrTipoRelatorioDTO = array();

    $objTipoRelatorioDTO = new TipoRelatorioDTO();
    $objTipoRelatorioDTO->setStrStaRelatorioChave(ContatoINT::$TRC_DADOS_COMPLETOS);
    $objTipoRelatorioDTO->setStrStaRelatorioDescricao(ContatoINT::$TRD_DADOS_COMPLETOS);
    $arrTipoRelatorioDTO[] = $objTipoRelatorioDTO;
    $objTipoRelatorioDTO = new TipoRelatorioDTO();
    $objTipoRelatorioDTO->setStrStaRelatorioChave(ContatoINT::$TRC_RESIDENCIAL);
    $objTipoRelatorioDTO->setStrStaRelatorioDescricao(ContatoINT::$TRD_RESIDENCIAL);
    $arrTipoRelatorioDTO[] = $objTipoRelatorioDTO;
    $objTipoRelatorioDTO = new TipoRelatorioDTO();
    $objTipoRelatorioDTO->setStrStaRelatorioChave(ContatoINT::$TRC_ETIQUETAS);
    $objTipoRelatorioDTO->setStrStaRelatorioDescricao(ContatoINT::$TRD_ETIQUETAS);
    $arrTipoRelatorioDTO[] = $objTipoRelatorioDTO;
    $objTipoRelatorioDTO = new TipoRelatorioDTO();
    $objTipoRelatorioDTO->setStrStaRelatorioChave(ContatoINT::$TRC_RELACAO);
    $objTipoRelatorioDTO->setStrStaRelatorioDescricao(ContatoINT::$TRD_RELACAO);
    $arrTipoRelatorioDTO[] = $objTipoRelatorioDTO;
    $objTipoRelatorioDTO = new TipoRelatorioDTO();
    $objTipoRelatorioDTO->setStrStaRelatorioChave(ContatoINT::$TRC_OUTRO);
    $objTipoRelatorioDTO->setStrStaRelatorioDescricao(ContatoINT::$TRD_OUTRO);
    $arrTipoRelatorioDTO[] = $objTipoRelatorioDTO;


    return parent::montarSelectArrInfraDTO('null', "&nbsp;", $strTipoRelatorio, $arrTipoRelatorioDTO, 'StaRelatorioChave', 'StaRelatorioDescricao');
  }

  public static function montarSelectColunasRelatorio($strTipoRelatorio){

    $arrColunasRelatorioDTO = array();

    switch ($strTipoRelatorio){
      case ContatoINT::$TRC_DADOS_COMPLETOS:
        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TRATAMENTO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TRATAMENTO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TITULO_ABREVIADO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TITULO_ABREVIADO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_NOME);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_NOME);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CARGO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CARGO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_ORGAO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_ORGAO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_ENDERECO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_ENDERECO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CEP);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CEP);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CIDADE);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CIDADE);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_UF);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_UF);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;
        break;
      case ContatoINT::$TRC_ETIQUETAS:

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TRATAMENTO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TRATAMENTO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TITULO_ABREVIADO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TITULO_ABREVIADO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_NOME);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_NOME);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CARGO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CARGO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_ORGAO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_ORGAO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        break;
      case ContatoINT::$TRC_RESIDENCIAL:

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TRATAMENTO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TRATAMENTO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TITULO_ABREVIADO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TITULO_ABREVIADO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_NOME);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_NOME);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CARGO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CARGO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_ORGAO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_ORGAO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_ENDERECO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_ENDERECO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CEP);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CEP);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CIDADE);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CIDADE);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_UF);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_UF);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        break;
      case ContatoINT::$TRC_RELACAO:

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TITULO_ABREVIADO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TITULO_ABREVIADO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_NOME);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_NOME);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CARGO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CARGO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
        $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_ORGAO);
        $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_ORGAO);
        $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

        break;
      case ContatoINT::$TRC_OUTRO:
        $arrColunasRelatorioDTO = self::montarTodasColunasRelatorio();
        break;
    }


    return parent::montarSelectArrInfraDTO(null, null, null, $arrColunasRelatorioDTO, 'ColunaAtributo', 'ColunaNome');

  }

  public static function montarTodasColunasRelatorio(){
    $arrColunasRelatorioDTO = array();

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CATEGORIA);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CATEGORIA);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TRATAMENTO);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TRATAMENTO);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TITULO_ABREVIADO);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TITULO_ABREVIADO);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TITULO);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TITULO);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_FUNCAO);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_FUNCAO);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_NOME);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_NOME);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CARGO);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CARGO);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_ORGAO);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_ORGAO);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_ENDERECO);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_ENDERECO);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CEP);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CEP);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CIDADE);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CIDADE);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_UF);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_UF);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_GENERO);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_GENERO);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_ANIVERSARIO);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_ANIVERSARIO);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TELEFONE_RESIDENCIAL);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TELEFONE_RESIDENCIAL);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TELEFONE_COMERCIAL);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TELEFONE_COMERCIAL);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_TELEFONE_CELULAR);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_TELEFONE_CELULAR);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_EMAIL);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_EMAIL);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_OBSERVACAO);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_OBSERVACAO);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    $objColunaRelatorioDTO = new TipoColunasRelatorioDTO();
    $objColunaRelatorioDTO->setStrColunaAtributo(ContatoINT::$TRA_CONJUGE);
    $objColunaRelatorioDTO->setStrColunaNome(ContatoINT::$TRN_CONJUGE);
    $arrColunasRelatorioDTO[] = $objColunaRelatorioDTO;

    return $arrColunasRelatorioDTO;
  }

  public static function montarConteudoExcel($arrObjContatoDTO, $arrObjColunaRelatorioDTO){

    $csv = '';

    $sep = '';
    foreach ($arrObjColunaRelatorioDTO as $key => $objColunaRelatorioDTO){
      $csv .= $sep.'"'.str_replace('"','""',$objColunaRelatorioDTO->getStrColunaNome()).'"';
      $sep = ';';
    }
    
    $csv .= "\n";
    
    foreach ($arrObjContatoDTO as $keyContato => $objContatoDTO) {
      $sep = '';
      foreach ($arrObjColunaRelatorioDTO as $keyColuna => $objColunaRelatorioDTO) {
        $csv .= $sep.'"'.str_replace('"','""',$objContatoDTO->get($objColunaRelatorioDTO->getStrColunaAtributo())).'"';
        $sep = ';';
      }
      $csv .= "\n";
    }

    return $csv;
  }

}
?>