<?
try{
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  SessaoSEI::getInstance()->validarLink();

  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  global $SEI_MODULOS;

  InfraAjax::decodificarPost();

  $xml = null;

  switch($_GET['acao_ajax']){

    case 'montar_auto_texto_editor':
      $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
      $xml = $objTextoPadraoInternoRN->obterAutoTextos();
      break;

    case 'texto_padrao_editor_listar':
      $strOptions = TextoPadraoInternoINT::montarSelectSigla('null',' ',null);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

    case 'hipotese_legal_select_nome_base_legal':
      $strOptions = HipoteseLegalINT::montarSelectNomeBaseLegal($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'], $_POST['staNivelAcesso']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

    case 'tipo_procedimento_obter_sugestoes':
      $objTipoProcedimentoDTO = TipoProcedimentoINT::obterSugestoesHipoteseLegalGrauSigilo(explode(',',$_POST['idTipoProcedimento']));
      $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objTipoProcedimentoDTO, array('IdHipoteseLegalSugestao', 'StaGrauSigiloSugestao'));
      break;

    case 'cidade_montar_select_id_cidade_nome':
      $strOptions = CidadeINT::montarSelectIdCidadeNome($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idUf'],$_POST['idPais']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

   case 'uf_montar_select_sigla':
      $strOptions = UfINT::montarSelectSiglaRI0416($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idPais']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

    case 'uf_montar_select_sigla_nome':
      $strOptions = UfINT::montarSelectSiglaNome($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idPais']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

    case 'bloco_assinatura_montar_select':
      $strOptions = BlocoINT::montarSelectAssinatura($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

    case 'assinatura_verificar_confirmacao':
      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->setBolExclusaoLogica(false);
      $objAssinaturaDTO->retNumIdAtividade();
      $objAssinaturaDTO->setStrAgrupador($_GET['agrupador']);
      $objAssinaturaDTO->setNumMaxRegistrosRetorno(1);

      $objAssinaturaRN = new AssinaturaRN();
      $objAssinaturaDTO = $objAssinaturaRN->consultarRN1322($objAssinaturaDTO);

      $xml = InfraAjax::gerarXMLComplementosArray(array('assinaturaConfirmada' => ($objAssinaturaDTO->getNumIdAtividade()!=null?'S':'N')));
      break;

    case 'assinaturas_documento':
      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->retStrNome();
      $objAssinaturaDTO->retStrTratamento();
      $objAssinaturaDTO->setDblIdDocumento($_POST['idDocumento']);
      $objAssinaturaDTO->setOrdNumIdAssinatura(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objAssinaturaRN = new AssinaturaRN();
      $arrObjAssinaturaDTO = $objAssinaturaRN->listarRN1323($objAssinaturaDTO);
      $strAssinaturas = AssinaturaINT::montarHtmlAssinaturas($arrObjAssinaturaDTO);

      $xml = InfraAjax::gerarXMLComplementosArray(array('assinaturas'=>base64_encode($strAssinaturas)));
      break;

    case 'documento_verificar_assinatura':

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->retNumIdAssinatura();
      $objAssinaturaDTO->setDblIdDocumento($_GET['id_documento']);
      $objAssinaturaDTO->setNumMaxRegistrosRetorno(1);

      $objAssinaturaRN = new AssinaturaRN();
      $objAssinaturaDTO = $objAssinaturaRN->consultarRN1322($objAssinaturaDTO);

      $xml = InfraAjax::gerarXMLComplementosArray(array('SinAssinado'=>($objAssinaturaDTO!=null?'S':'N')));
      break;

    case 'situacao_montar_select_nome':
      $strOptions = SituacaoINT::montarSelectNomeCompleto($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['sinInativos']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

   case 'secao_imprensa_nacional_montar_select_nome':
     $strOptions = SecaoImprensaNacionalINT::montarSelectNome($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idVeiculoImprensaNacional']);
     $xml = InfraAjax::gerarXMLSelect($strOptions);
     break;

   case 'assunto_auto_completar_RI1223':
      $arrObjAssuntoDTO = AssuntoINT::autoCompletarAssuntosRI1223($_POST['palavras_pesquisa']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjAssuntoDTO,'IdAssunto', 'CodigoEstruturado');
      break;

    case 'assunto_auto_completar_mapeamento':
      $arrObjAssuntoDTO = AssuntoINT::autoCompletarAssuntosMapeamento($_POST['palavras_pesquisa'], $_POST['id_tabela_assuntos']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjAssuntoDTO,'IdAssunto', 'CodigoEstruturado');
      break;

    case 'cargo_auto_completar':
      $arrObjCargoDTO=CargoINT::autoCompletarExpressao($_POST['palavras_pesquisa']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjCargoDTO,'IdCargo', 'Expressao');
      break;

    case 'cargo_montar_select_genero':
      $strOptions = CargoINT::montarSelectGenero($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['staGenero']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

    case 'tipo_relatorio_montar_select_colunas':
      $strOptions = ContatoINT::montarSelectColunasRelatorio($_POST['strTipoRelatorio']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

    case 'serie_auto_completar':
      $arrObjSerieDTO=SerieINT::autoCompletarSerie($_POST['palavras_pesquisa'],$_POST['sta_aplicabilidade']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjSerieDTO,'IdSerie', 'Nome');
      break;

    case 'contato_auto_completar_acesso_externo':
      $arrObjContatoDTO = ContatoINT::autoCompletarAcessoExterno(utf8_decode(urldecode($_POST['palavras_pesquisa'])),$_POST['id_grupo_contato']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjContatoDTO,'IdContato', 'Nome');
      break;

   case 'contato_auto_completar_contexto_RI1225':
      $arrObjContatoDTO = ContatoINT::autoCompletarContextoRI1225(utf8_decode(urldecode($_POST['palavras_pesquisa'])),$_POST['id_grupo_contato']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjContatoDTO,'IdContato', 'Nome');
      break;

   case 'contato_auto_completar_pesquisa':
     $arr = ContatoINT::autoCompletarPesquisa($_POST['palavras_pesquisa']);
     $xml = InfraAjax::gerarXMLItensArrInfraDTO($arr, 'IdContato', 'Nome');
     break;

   case 'contato_auto_completar_usuario_pesquisa':
     $arr = ContatoINT::autoCompletarUsuariosPesquisa($_POST['palavras_pesquisa'],$_POST['sin_usuario_interno'],$_POST['sin_usuario_externo']);
     $xml = InfraAjax::gerarXMLItensArrInfraDTO($arr, 'IdContato', 'Nome');
     break;

   case 'contato_auto_completar_contexto_substituicao':
     $arrObjContatoDTO = ContatoINT::autoCompletarContextoSubstituicao($_POST['palavras_pesquisa']);
     $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjContatoDTO,'IdContato', 'Nome');
     break;

    case 'contato_auto_completar_associado':
      $arrObjContatoDTO = ContatoINT::autoCompletarAssociado($_POST['palavras_pesquisa']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjContatoDTO,'IdContato', 'Nome');
      break;

    case 'contato_associado_dados':

      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->retStrNome();
      $objContatoDTO->setNumIdContato($_POST['id_contato_associado']);

      $objContatoRN = new ContatoRN();
      $arrObjContatoDTO = $objContatoRN->listarComEndereco($objContatoDTO);

      if (count($arrObjContatoDTO)){

        $objContatoDTO = $arrObjContatoDTO[0];

        $arr = array('IdContato' => $objContatoDTO->getNumIdContato(),
            'Nome' => $objContatoDTO->getStrNome(),
            'Endereco' => $objContatoDTO->getStrEndereco(),
            'Complemento' => $objContatoDTO->getStrComplemento(),
            'Bairro' => $objContatoDTO->getStrBairro(),
            'SiglaUf' => $objContatoDTO->getStrSiglaUf(),
            'NomeCidade' => $objContatoDTO->getStrNomeCidade(),
            'NomePais' => $objContatoDTO->getStrNomePais(),
            'Cep' => $objContatoDTO->getStrCep());

        $xml = InfraAjax::gerarXMLComplementosArray($arr);
      }
      break;

    case 'cargo_dados':
      $objCargoDTO = new CargoDTO();
      $objCargoDTO->setBolExclusaoLogica(false);
      $objCargoDTO->retStrExpressaoTratamento();
      $objCargoDTO->retStrExpressaoVocativo();
      $objCargoDTO->retNumIdTitulo();
      $objCargoDTO->setNumIdCargo($_POST['id_cargo']);

      $objCargoRN = new CargoRN();
      $objCargoDTO = $objCargoRN->consultarRN0301($objCargoDTO);

      if ($objCargoDTO!=null){
        $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objCargoDTO,array('ExpressaoTratamento','ExpressaoVocativo','IdTitulo'));
      }
      break;

    case 'unidade_dados':
      $objUnidadeDTO = new UnidadeDTO();
      $objUnidadeDTO->setBolExclusaoLogica(false);
      $objUnidadeDTO->retStrSigla();
      $objUnidadeDTO->retStrDescricao();
      $objUnidadeDTO->retStrSinAtivo();
      $objUnidadeDTO->setNumIdUnidade($_POST['id_unidade']);

      $objUnidadeRN = new UnidadeRN();
      $objUnidadeDTO = $objUnidadeRN->consultarRN0125($objUnidadeDTO);

      if ($objUnidadeDTO!=null){
        $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objUnidadeDTO,array('Sigla','Descricao','SinAtivo'));
      }
      break;

   case 'acesso_externo_dados_destinatario':
      $arr = AcessoExternoINT::obterDadosDestinatario($_POST['id_procedimento'],$_POST['id_contato']);
      $xml = InfraAjax::gerarXMLComplementosArray($arr);
      break;

   case 'unidade_auto_completar_outras':
      $arrObjUnidadeDTO = UnidadeINT::autoCompletarUnidades($_POST['palavras_pesquisa'],false);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUnidadeDTO,'IdUnidade', 'Sigla');
      break;

   case 'unidade_auto_completar_envio_processo':
      $arrObjUnidadeDTO = UnidadeINT::autoCompletarEnvioProcesso($_POST['palavras_pesquisa'],$_POST['id_orgao']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUnidadeDTO,'IdUnidade', 'Sigla');
      break;

   case 'tipo_procedimento_auto_completar':
      $arrObjTipoProcedimentoDTO = TipoProcedimentoINT::autoCompletarTipoProcedimento($_POST['palavras_pesquisa']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjTipoProcedimentoDTO,'IdTipoProcedimento', 'Nome');
      break;

   case 'unidade_auto_completar_todas':
      $arrObjUnidadeDTO = UnidadeINT::autoCompletarUnidades($_POST['palavras_pesquisa'],true,$_POST['id_orgao']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUnidadeDTO,'IdUnidade', 'Sigla');
      break;

   case 'usuario_auto_completar':
      $arrObjUsuarioDTO = UsuarioINT::autoCompletarUsuarios($_POST['id_orgao'],$_POST['palavras_pesquisa'],false,false,true,false);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario', 'Sigla');
      break;

    case 'usuario_auto_grupo_mensagem':
      $arrObjUsuarioDTO = UsuarioINT::autoCompletarUsuarios(null,$_POST['palavras_pesquisa'],true,false,true,false);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario', 'Sigla');
      break;

   case 'usuario_auto_completar_sigla':
      $arrObjUsuarioDTO = UsuarioINT::autoCompletarUsuarios($_POST['id_orgao'],$_POST['palavras_pesquisa'],false,false,false,$_POST['inativos']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario', 'Nome', 'Sigla');
      break;

   case 'usuario_auto_completar_outros':
      $arrObjUsuarioDTO = UsuarioINT::autoCompletarUsuarios($_POST['id_orgao'],$_POST['palavras_pesquisa'],true,false,true,false);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario', 'Sigla');
      break;

   case 'usuario_auto_completar_contato':
      $arrObjUsuarioDTO = UsuarioINT::autoCompletarUsuarios($_POST['id_orgao'],$_POST['palavras_pesquisa'],false,false,true,false);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdContato', 'Sigla');
      break;

   case 'usuario_externo_auto_completar':
      $arrObjUsuarioDTO = UsuarioINT::autoCompletarUsuarios($_POST['id_orgao'],$_POST['palavras_pesquisa'],false,true,true,false);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario', 'Sigla');
      break;

   case 'usuario_externo_auto_completar_contato':
     $arrObjUsuarioDTO = UsuarioINT::autoCompletarUsuarios($_POST['id_orgao'],utf8_decode(urldecode($_POST['palavras_pesquisa'])),false,true,true,false);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdContato', 'Sigla');
      break;


   case 'email_auto_completar':
      $arrObjEmailUtilizadoDTO=EmailUtilizadoINT::autoCompletarEmail($_GET['id_unidade'],$_POST['palavras_pesquisa']);
      // Transforma em um objeto JSON e envia para o elemento HTML
      $arrItens = array ();
      foreach ( $arrObjEmailUtilizadoDTO as $arrObj ) {
        $arrItens [] = array (
            'id' => utf8_encode($arrObj->getStrEmail() ),
            'text' => utf8_encode($arrObj->getStrEmail() )
        );
      }
      $json= json_encode ( $arrItens );
      InfraAjax::enviarJSON($json);
      die;
      break;

    case 'email_remover':
      $objEmailUtilizadoDTO=new EmailUtilizadoDTO();
      $objEmailUtilizadoRN=new EmailUtilizadoRN();
      $objEmailUtilizadoDTO->setNumIdUnidade($_GET['id_unidade']);

      $objEmailUtilizadoDTO->setStrEmail(InfraString::removerFormatacaoXML(urldecode($_POST['email'])));

      $objEmailUtilizadoDTO->retNumIdEmailUtilizado();
      $arrObjEmailUtilizadoDTO=$objEmailUtilizadoRN->listar($objEmailUtilizadoDTO);

      if ($arrObjEmailUtilizadoDTO!=null){
        $objEmailUtilizadoRN->excluir($arrObjEmailUtilizadoDTO);
        $resultado="true";
      } else {
        $resultado="false";
      }
      $xml=InfraAjax::gerarXMLComplementosArray(array("resultado"=>$resultado));
      break;

   case 'lembrete_atualizar':
     $objLembreteDTO=new LembreteDTO();
     $objLembreteDTO->setNumIdUsuario($_GET['id_usuario']);
     $objLembreteDTO->setNumIdLembrete($_POST['id']);
     if ($_POST['operacao']!='D'){
       $objLembreteDTO->setNumPosicaoX(str_replace('px','',$_POST['posX']));
       $objLembreteDTO->setNumPosicaoY(str_replace('px','',$_POST['posY']));
       $objLembreteDTO->setNumAltura($_POST['height']);
       $objLembreteDTO->setNumLargura($_POST['width']);
       $objLembreteDTO->setStrConteudo(utf8_decode(urldecode($_POST['content'])));
       $objLembreteDTO->setStrCor($_POST['backgroundcolor']);
       $objLembreteDTO->setStrCorTexto($_POST['textcolor']);
       $objLembreteDTO->setStrSinAtivo('S');
     }

     $resultado=LembreteINT::atualizarLembrete($_POST['operacao'],$objLembreteDTO);
     $xml=InfraAjax::gerarXMLComplementosArray(array("resultado"=>$resultado));
     break;

    case 'usuario_assinatura_auto_completar':
      $arrObjUsuarioDTO = UsuarioINT::autoCompletarUsuariosAssinatura($_POST['id_orgao'],$_POST['palavras_pesquisa'],$_POST['inativos']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjUsuarioDTO, 'IdUsuario', 'Sigla');
      break;

   case 'assinante_carregar_cargo_funcao':
      $strOptions = AssinanteINT::montarSelectCargoFuncaoUnidadeUsuarioRI1344('null', ' ', 'null', $_POST['id_usuario']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

   case 'unidade_montar_select_sigla_descricao':
      $strOptions = UnidadeINT::montarSelectSiglaDescricao($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'], $_POST['idOrgao']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

   case 'texto_padrao_buscar_conteudo':
      $objTextoPadraoInternoDTO = TextoPadraoInternoINT::obterDados($_POST['id_texto_padrao_interno']);
      $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objTextoPadraoInternoDTO,array('Conteudo'));
      break;

    case 'contato_cadastro_contexto_temporario':
      $objContatoDTO = new ContatoDTO();
      $objContatoDTO->setStrNome(utf8_decode(urldecode($_POST['nome'])));

      $objContatoRN = new ContatoRN();
      $objContatoDTO = $objContatoRN->cadastrarContextoTemporario($objContatoDTO);

      $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objContatoDTO,array('IdContato'));
      break;

   case 'documento_recebido_duplicado':
      $objDocumentoDTO = DocumentoINT::verificarDocumentoRecebidoDuplicado($_POST['dta_elaboracao'],$_POST['id_serie'],$_POST['numero']);
      $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objDocumentoDTO,array('IdDocumento','ProtocoloDocumentoFormatado'));
      break;

    case 'data_disponibilizacao_RI1054':
      $objPublicacaoDTO = PublicacaoINT::sugerirDataDisponibilizacaoRI1054($_POST['idOrgao'],$_POST['idVeiculoPublicacao']);
      $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objPublicacaoDTO,array('Disponibilizacao'));
      break;

    case 'localizador_RI0683':
      $objLocalizadorDTO = LocalizadorINT::sugestaodelocalizadorRI0683($_POST['idTipoLocalizador']);
      $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objLocalizadorDTO,array('SiglaTipoLocalizador', 'SeqLocalizador'));
      break;

   case 'serie_RI0954':
      $objSerieDTO = SerieINT::obterDadosRI0954($_POST['idSerie']);
      $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objSerieDTO,array('Descricao'));
      break;

    case 'auto_texto_RI0986':
      $objAutoTextoDTO = AutoTextoINT::obterDadosRI0986($_POST['idAutoTexto']);
      $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objAutoTextoDTO,array('Conteudo'));
      break;

    case 'procedimento_anexacao_verificar':
      $objProcedimentoDTO = new ProcedimentoDTO();
      $objProcedimentoDTO->setDblIdProcedimento($_POST['idProcedimento']);

      $objProcedimentoRN = new ProcedimentoRN();
      $xml = InfraAjax::gerarXMLComplementosArray(array('Anexacao' => ($objProcedimentoRN->verificarAnexacao($objProcedimentoDTO)?'S':'N')));
      break;

    case 'protocolo_RI1023':
      $arr = ProcedimentoINT::pesquisarDigitadoRI1023($_POST['idProcedimento']);
      $xml = InfraAjax::gerarXMLComplementosArray($arr);
      break;

    case 'protocolo_link_editor':
      $arr = ProtocoloINT::pesquisarLinkEditor($_POST['idProcedimento'],$_POST['idDocumento'],$_POST['idProtocoloDigitado']);
      $xml = InfraAjax::gerarXMLComplementosArray($arr);
      break;

    case 'bloco_link_editor':
      $arr = BlocoINT::pesquisarLinkEditor($_POST['id_bloco']);
      $xml = InfraAjax::gerarXMLComplementosArray($arr);
      break;

   case 'protocolo_RI1132':
       $strOptions = LocalizadorINT::conjuntoPorIdentificacaoRI1132($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado'],$_POST['idTipoLocalizador'],$_POST['idLocalizador']);
       $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

   case 'grupo_serie_RI0953':
      $objGrupoSerieDTO = GrupoSerieINT::obterDadosRI0953($_POST['idGrupoSerie']);
      $xml = InfraAjax::gerarXMLComplementosArrInfraDTO($objGrupoSerieDTO,array('Descricao'));
      break;

   case 'destinatario_auto_completar_nome':
      $arrObjDestinatarioDTO = DestinatarioINT::autoCompletarNome($_POST['nome']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjDestinatarioDTO,'IdDestinatario','Nome');
      break;

   case 'usuario_unidades_permissao':
      $strOptions = UsuarioINT::montarSelectUnidadesPermissao($_POST['id_usuario'],$_POST['id_unidade'],$_POST['sin_todos']);
      $xml = InfraAjax::gerarXMLSelect($strOptions);
      break;

   case 'acesso_pesquisar_credencial_processo':
      $arr = AcessoINT::pesquisarCredenciaisProcesso($_POST['IdUsuario'],$_POST['IdUnidade'],$_POST['IdProtocolo']);
      $xml = InfraAjax::gerarXMLComplementosArray($arr);
      break;

   case 'novidade_salvar':
      $objInfraDadoUsuario = new InfraDadoUsuario(SessaoSEI::getInstance());
      $objInfraDadoUsuario->setValor('NOVIDADE_ULTIMA',$_POST['dth_ultima_exibida']);
      $xml = InfraAjax::gerarXMLComplementosArray(array());
      break;

   case 'upload_buscar':
      $objEditorRN = new EditorRN();
      $arr = array('base64' => $objEditorRN->buscarImagemUpload($_POST['img']));
      $xml = InfraAjax::gerarXMLComplementosArray($arr);
      break;

    case 'marcador_montar_opcoes':
      $strOptions = MarcadorINT::montarSelectMarcador($_POST['primeiroItemValor'],$_POST['primeiroItemDescricao'],$_POST['valorItemSelecionado']);
      $xml = InfraAjax::gerarXMLComplementosArray(array('marcadores' => base64_encode($strOptions)));
      break;

    case 'orgao_auto_completar':
      $arrObjOrgaoDTO = OrgaoINT::autoCompletarOrgaos($_POST['palavras_pesquisa']);
      $xml = InfraAjax::gerarXMLItensArrInfraDTO($arrObjOrgaoDTO,'IdOrgao', 'Sigla');
      break;

    case 'processar_tag':
      $objEditorRN=new EditorRN();
      $objEditorDTO=new EditorDTO();
      $objEditorDTO->setDblIdDocumento($_GET['id_documento']);
      $str=$_POST['txtTag'];
      $objEditorDTO->setStrNomeTag($str);
      if($str!=='link_acesso_externo_processo'){
        $str=array('result'=>utf8_encode($objEditorRN->processarTag($objEditorDTO)));
      }

      InfraAjax::enviarJSON(json_encode($str));
      die;
      break;

    case 'instalacao_federacao_verificar_conexao':
      $objInstalacaoFederacaoDTO = new InstalacaoFederacaoDTO();
      $objInstalacaoFederacaoDTO->setStrIdInstalacaoFederacao($_POST['IdInstalacaoFederacao']);

      $objInstalacaoFederacaoRN = new InstalacaoFederacaoRN();
      $ret = $objInstalacaoFederacaoRN->verificarConexao($objInstalacaoFederacaoDTO);
      $xml = InfraAjax::gerarXMLComplementosArray(array('Resultado' => $ret));
      break;

    case 'corfirmacao_atualizacao_documento':
      $strAtualizacaoConteudoModulos = '';

      if (count($SEI_MODULOS)) {
        $objDocumentoAPI = new DocumentoAPI();
        $objDocumentoAPI->setIdDocumento($_GET['id_documento']);

        foreach ($SEI_MODULOS as $seiModulo) {
          if (($strConfirmacaoModulo = $seiModulo->executar('confirmarAtualizacaoConteudoDocumento', $objDocumentoAPI))!=null) {
            $strAtualizacaoConteudoModulos .= $strConfirmacaoModulo . "\n\n";
          }
        }
      }
      $strSinConfirmar=($strAtualizacaoConteudoModulos!='')?'S':'N';
      $xml = InfraAjax::gerarXMLComplementosArray(array('Mensagem' => $strAtualizacaoConteudoModulos,'Confirmar'=>$strSinConfirmar));
      break;

   default:

     foreach($SEI_MODULOS as $objModulo){
       if (($xml = $objModulo->executar('processarControladorAjax', $_GET['acao_ajax']))!=null){
         break;
       }
     }

     if ($xml == null){

       if ($_GET['acao_ajax'] == '' && $_GET['msg'] != '') {
         $objInfraException = new InfraException();
         $objInfraException->lancarValidacao($_GET['msg']);
       }

       throw new InfraException("Aчуo '" . $_GET['acao_ajax'] . "' nуo reconhecida pelo controlador AJAX.");
     }
  }

  //LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

  InfraAjax::enviarXML($xml);

}catch(Throwable $e){

  //LogSEI::getInstance()->gravar(InfraDebug::getInstance()->getStrDebug());

  if (!($e instanceof InfraException && $e->contemValidacoes())){
	  LogSEI::getInstance()->gravar('ERRO AJAX: '.InfraException::inspecionar($e)."\n\nGET:\n".print_r($_GET,true)."\nPOST:\n".print_r($_POST,true));
  }
  InfraAjax::processarExcecao($e);
}
?>