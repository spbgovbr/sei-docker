<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4Є REGIГO
*
* 13/07/2015 - criado por mga
*
* Versгo do Gerador de Cуdigo: 1.14.0
*
* Versгo no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class UnidadeBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }
  
  public function migrar(MigracaoUnidadeDTO $objMigracaoUnidadeDTO){
    try {

      $numRefreshEtapa = 500000;
      $numRefresh = 10000;

      $arrCorBarraProgresso=array('cor_fundo'=>'#5c9ccc','cor_borda'=>'#4297d7');
      $prb = InfraBarraProgresso2::newInstance('Migracao',$arrCorBarraProgresso);

      $prb->setNumMin(0);
      $prb->setNumMax(0);

      $objUnidadeRN = new UnidadeRN();

      $objUnidadeDTOOrigem = new UnidadeDTO();
      $objUnidadeDTOOrigem->setBolExclusaoLogica(false);
      $objUnidadeDTOOrigem->retStrSigla();
      $objUnidadeDTOOrigem->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());
      $objUnidadeDTOOrigem = $objUnidadeRN->consultarRN0125($objUnidadeDTOOrigem);

      $objUnidadeDTODestino = new UnidadeDTO();
      $objUnidadeDTODestino->setBolExclusaoLogica(false);
      $objUnidadeDTODestino->retStrSigla();
      $objUnidadeDTODestino->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
      $objUnidadeDTODestino = $objUnidadeRN->consultarRN0125($objUnidadeDTODestino);

      $strTextoMigrado = $objMigracaoUnidadeDTO->getStrPrefixoMigracao();

      if ($strTextoMigrado != '') {
        $strTextoMigrado = $strTextoMigrado.' ';
      }

      if ($objMigracaoUnidadeDTO->getStrSinAcompanhamentoEspecial() == 'S') {

        $objGrupoAcompanhamentoDTO = new GrupoAcompanhamentoDTO();
        $objGrupoAcompanhamentoDTO->retNumIdGrupoAcompanhamento();
        $objGrupoAcompanhamentoDTO->retStrNome();
        $objGrupoAcompanhamentoDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());

        $objGrupoAcompanhamentoRN = new GrupoAcompanhamentoRN();
        $arrObjGrupoAcompanhamentoDTO = InfraArray::indexarArrInfraDTO($objGrupoAcompanhamentoRN->listar($objGrupoAcompanhamentoDTO), 'IdGrupoAcompanhamento');

        $prb->setNumMax(count($arrObjGrupoAcompanhamentoDTO));
        $prb->setStrRotulo('Migrando Grupos de Acompanhamentos Especiais...');
        $prb->setNumPosicao(0);
//        usleep($numRefreshEtapa);

        $objGrupoAcompanhamentoBD = new GrupoAcompanhamentoBD(BancoSEI::getInstance());

        foreach($arrObjGrupoAcompanhamentoDTO as $objGrupoAcompanhamentoDTO){

          $prb->setStrRotulo('Grupo de Acompanhamento Especial ' . $objGrupoAcompanhamentoDTO->getStrNome() . '...');
          $prb->moverProximo();
//          usleep($numRefresh);

          $strNomeGrupoAcompanhamento = substr($strTextoMigrado . $objGrupoAcompanhamentoDTO->getStrNome(), 0, $objGrupoAcompanhamentoRN->getNumMaxTamanhoNome());

          $dto = new GrupoAcompanhamentoDTO();
          $dto->retNumIdGrupoAcompanhamento();
          $dto->setStrNome($strNomeGrupoAcompanhamento);
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
          $dto->setNumMaxRegistrosRetorno(1);

          $dto = $objGrupoAcompanhamentoRN->consultar($dto);

          if ($dto != null) {
            //substitui pelo ID existente na unidade destino
            $objGrupoAcompanhamentoDTO->setNumIdGrupoAcompanhamento($dto->getNumIdGrupoAcompanhamento());
          } else {
            //muda nome e unidade do grupo
            $dto = new GrupoAcompanhamentoDTO();
            $dto->setStrNome($strNomeGrupoAcompanhamento);
            $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
            $dto->setNumIdGrupoAcompanhamento($objGrupoAcompanhamentoDTO->getNumIdGrupoAcompanhamento());
            $objGrupoAcompanhamentoBD->alterar($dto);
          }
        }

        $objAcompanhamentoDTO = new AcompanhamentoDTO();
        $objAcompanhamentoDTO->retNumIdAcompanhamento();
        $objAcompanhamentoDTO->retStrProtocoloFormatado();
        $objAcompanhamentoDTO->retNumIdGrupoAcompanhamento();
        $objAcompanhamentoDTO->retDblIdProtocolo();
        $objAcompanhamentoDTO->retStrObservacao();
        $objAcompanhamentoDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());
        $objAcompanhamentoDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objAcompanhamentoRN = new AcompanhamentoRN();
        $arrObjAcompanhamentoDTO = $objAcompanhamentoRN->listar($objAcompanhamentoDTO);

        $prb->setNumMax(count($arrObjAcompanhamentoDTO));
        $prb->setNumPosicao(0);
        $prb->setStrRotulo('Migrando Acompanhamentos Especiais...');
//        usleep($numRefreshEtapa);

        $objAcompanhamentoBD = new AcompanhamentoBD(BancoSEI::getInstance());

        foreach($arrObjAcompanhamentoDTO as $objAcompanhamentoDTO){

          $prb->setStrRotulo('Acompanhamento Especial ' . $objAcompanhamentoDTO->getStrProtocoloFormatado() . '...');
          $prb->moverProximo();
//          usleep($numRefresh);

          $dto = new AcompanhamentoDTO();
          $dto->setDblIdProtocolo($objAcompanhamentoDTO->getDblIdProtocolo());
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());

          if ($objAcompanhamentoDTO->getNumIdGrupoAcompanhamento() != null) {
            $dto->setNumIdGrupoAcompanhamento($arrObjGrupoAcompanhamentoDTO[$objAcompanhamentoDTO->getNumIdGrupoAcompanhamento()]->getNumIdGrupoAcompanhamento());
          }else{
            $dto->setNumIdGrupoAcompanhamento(null);
          }

          if ($objAcompanhamentoRN->contar($dto) == 0) {

            if ($objAcompanhamentoDTO->getNumIdGrupoAcompanhamento() != null) {
              $objAcompanhamentoDTO->setNumIdGrupoAcompanhamento($arrObjGrupoAcompanhamentoDTO[$objAcompanhamentoDTO->getNumIdGrupoAcompanhamento()]->getNumIdGrupoAcompanhamento());
            }

            $objAcompanhamentoDTO->setStrObservacao(substr($strTextoMigrado . $objAcompanhamentoDTO->getStrObservacao(), 0, $objAcompanhamentoRN->getNumMaxTamanhoObservacao()));
            $objAcompanhamentoDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
            $objAcompanhamentoBD->alterar($objAcompanhamentoDTO);
          }
        }
        unset($arrObjGrupoAcompanhamentoDTO);
        unset($arrObjAcompanhamentoDTO);
      }

      if ($objMigracaoUnidadeDTO->getStrSinAssinatura() == 'S') {
        //cargos para assinatura associados com a unidade
        $objRelAssinanteUnidadeDTO = new RelAssinanteUnidadeDTO();
        $objRelAssinanteUnidadeDTO->retNumIdAssinante();
        $objRelAssinanteUnidadeDTO->retNumIdUnidade();
        $objRelAssinanteUnidadeDTO->retStrCargoFuncaoAssinante();
        $objRelAssinanteUnidadeDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());

        $objRelAssinanteUnidadeRN = new RelAssinanteUnidadeRN();
        $arrObjRelAssinanteUnidadeDTO = $objRelAssinanteUnidadeRN->listarRN1380($objRelAssinanteUnidadeDTO);

        $prb->setNumMax(count($arrObjRelAssinanteUnidadeDTO));
        $prb->setStrRotulo('Migrando Assinaturas da Unidade...');
        $prb->setNumPosicao(0);
//        usleep($numRefreshEtapa);

        $objRelAssinanteUnidadeBD = new RelAssinanteUnidadeBD($this->getObjInfraIBanco());

        foreach($arrObjRelAssinanteUnidadeDTO as $objRelAssinanteUnidadeDTO){

          $prb->setStrRotulo('Assinatura da Unidade ' . $objRelAssinanteUnidadeDTO->getStrCargoFuncaoAssinante() . '...');
          $prb->moverProximo();
          usleep($numRefresh);

          $dto = new RelAssinanteUnidadeDTO();
          $dto->setNumIdAssinante($objRelAssinanteUnidadeDTO->getNumIdAssinante());
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());

          if ($objRelAssinanteUnidadeRN->contarRN1381($dto) == 0) {
            $objRelAssinanteUnidadeBD->cadastrar($dto);
          }

          $objRelAssinanteUnidadeBD->excluir($objRelAssinanteUnidadeDTO);

        }
        unset($arrObjRelAssinanteUnidadeDTO);
      }

      if ($objMigracaoUnidadeDTO->getStrSinGrupoBloco() == 'S') {

        $objGrupoBlocoDTO = new GrupoBlocoDTO();
        $objGrupoBlocoDTO->retNumIdGrupoBloco();
        $objGrupoBlocoDTO->retStrNome();
        $objGrupoBlocoDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());

        $objGrupoBlocoRN = new GrupoBlocoRN();
        $arrObjGrupoBlocoDTO = InfraArray::indexarArrInfraDTO($objGrupoBlocoRN->listar($objGrupoBlocoDTO), 'IdGrupoBloco');

        $prb->setNumMax(count($arrObjGrupoBlocoDTO));
        $prb->setStrRotulo('Migrando Grupos de Blocos...');
        $prb->setNumPosicao(0);
//        usleep($numRefreshEtapa);

        $objGrupoBlocoBD = new GrupoBlocoBD(BancoSEI::getInstance());

        foreach ($arrObjGrupoBlocoDTO as $objGrupoBlocoDTO) {

          $prb->setStrRotulo('Grupo de Bloco '.$objGrupoBlocoDTO->getStrNome().'...');
          $prb->moverProximo();
//          usleep($numRefresh);

          $strNomeGrupoBloco = substr($strTextoMigrado.$objGrupoBlocoDTO->getStrNome(), 0, $objGrupoBlocoRN->getNumMaxTamanhoNome());

          $dto = new GrupoBlocoDTO();
          $dto->retNumIdGrupoBloco();
          $dto->setStrNome($strNomeGrupoBloco);
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
          $dto->setNumMaxRegistrosRetorno(1);

          $dto = $objGrupoBlocoRN->consultar($dto);

          if ($dto != null) {
            //substitui pelo ID existente na unidade destino
            $objGrupoBlocoDTO->setNumIdGrupoBloco($dto->getNumIdGrupoBloco());
          } else {
            //muda nome e unidade do grupo
            $dto = new GrupoBlocoDTO();
            $dto->setStrNome($strNomeGrupoBloco);
            $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
            $dto->setNumIdGrupoBloco($objGrupoBlocoDTO->getNumIdGrupoBloco());
            $objGrupoBlocoBD->alterar($dto);
          }
        }
      }
      
      if ($objMigracaoUnidadeDTO->getStrSinBlocoInterno() == 'S') {

        $objBlocoDTO = new BlocoDTO();
        $objBlocoDTO->retNumIdBloco();
        $objBlocoDTO->retStrDescricao();
        $objBlocoDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());
        $objBlocoDTO->setStrStaTipo(BlocoRN::$TB_INTERNO);
        $objBlocoDTO->setOrdNumIdBloco(InfraDTO::$TIPO_ORDENACAO_DESC);

        $objBlocoRN = new BlocoRN();
        $arrObjBlocoDTO = $objBlocoRN->listarRN1277($objBlocoDTO);

        $prb->setNumMax(count($arrObjBlocoDTO));
        $prb->setStrRotulo('Migrando Blocos Internos...');
        $prb->setNumPosicao(0);
        usleep($numRefreshEtapa);

        $objBlocoBD = new BlocoBD($this->getObjInfraIBanco());

        $objRelBlocoUnidadeBD = new RelBlocoUnidadeBD(BancoSEI::getInstance());

        foreach($arrObjBlocoDTO as $objBlocoDTO){

          $prb->setStrRotulo('Bloco Interno ' . $objBlocoDTO->getNumIdBloco() . '...');
          $prb->moverProximo();
          usleep($numRefresh);

          $objRelBlocoUnidadeDTO = new RelBlocoUnidadeDTO();
          $objRelBlocoUnidadeDTO->retTodos();
          $objRelBlocoUnidadeDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());
          $objRelBlocoUnidadeDTO->setNumIdBloco($objBlocoDTO->getNumIdBloco());
          $objRelBlocoUnidadeDTO = $objRelBlocoUnidadeBD->consultar($objRelBlocoUnidadeDTO);

          if ($objRelBlocoUnidadeDTO!=null){
            $objRelBlocoUnidadeBD->excluir($objRelBlocoUnidadeDTO);
            $objRelBlocoUnidadeDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());

            if ($objMigracaoUnidadeDTO->getStrSinGrupoBloco()=='N'){
              $objRelBlocoUnidadeDTO->setNumIdGrupoBloco(null);
            }

            $objRelBlocoUnidadeBD->cadastrar($objRelBlocoUnidadeDTO);
          }

          $objBlocoDTO->setStrDescricao(substr($strTextoMigrado . $objBlocoDTO->getStrDescricao(), 0, $objBlocoRN->getNumMaxTamanhoDescricao()));
          $objBlocoDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
          $objBlocoBD->alterar($objBlocoDTO);
        }

        unset($arrObjBlocoDTO);
      }

      if ($objMigracaoUnidadeDTO->getStrSinGrupoContato()=='S') {

        $objGrupoContatoDTO = new GrupoContatoDTO();
        $objGrupoContatoDTO->retNumIdGrupoContato();
        $objGrupoContatoDTO->retStrNome();
        $objGrupoContatoDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());
        $objGrupoContatoDTO->setStrStaTipo(GrupoContatoRN::$TGC_UNIDADE);
        $objGrupoContatoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objGrupoContatoRN = new GrupoContatoRN();
        $arrObjGrupoContatoDTO = $objGrupoContatoRN->listarRN0477($objGrupoContatoDTO);

        $prb->setNumMax(count($arrObjGrupoContatoDTO));
        $prb->setStrRotulo('Migrando Grupos de Contatos...');
        $prb->setNumPosicao(0);
        usleep($numRefreshEtapa);

        $objGrupoContatoBD = new GrupoContatoBD(BancoSEI::getInstance());
        $objRelGrupoContatoBD = new RelGrupoContatoBD(BancoSEI::getInstance());
        $objRelGrupoContatoRN = new RelGrupoContatoRN();

        foreach($arrObjGrupoContatoDTO as $objGrupoContatoDTO){

          $prb->setStrRotulo('Grupo de Contato ' . $objGrupoContatoDTO->getStrNome() . '...');
          $prb->moverProximo();
          usleep($numRefresh);

          $strNomeGrupoContato = substr($strTextoMigrado . $objGrupoContatoDTO->getStrNome(), 0, $objGrupoContatoRN->getNumMaxTamanhoNome());

          $dto = new GrupoContatoDTO();
          $dto->retNumIdGrupoContato();
          $dto->setStrNome($strNomeGrupoContato);
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
          $dto->setNumMaxRegistrosRetorno(1);

          $objGrupoContatoDTODestino = $objGrupoContatoRN->consultarRN0474($dto);

          if ($objGrupoContatoDTODestino != null) {

            $objRelGrupoContatoDTO = new RelGrupoContatoDTO();
            $objRelGrupoContatoDTO->retNumIdContato();
            $objRelGrupoContatoDTO->retNumIdGrupoContato();
            $objRelGrupoContatoDTO->setNumIdGrupoContato($objGrupoContatoDTO->getNumIdGrupoContato());
            $arrObjRelGrupoContatoDTO = $objRelGrupoContatoRN->listarRN0463($objRelGrupoContatoDTO);

            foreach($arrObjRelGrupoContatoDTO as $objRelGrupoContatoDTO){

              $dto = new RelGrupoContatoDTO();
              $dto->setNumIdContato($objRelGrupoContatoDTO->getNumIdContato());
              $dto->setNumIdGrupoContato($objGrupoContatoDTODestino->getNumIdGrupoContato());

              if ($objRelGrupoContatoRN->contarRN0465($dto)==0) {
                $objRelGrupoContatoBD->cadastrar($dto);
              }

              $objRelGrupoContatoBD->excluir($objRelGrupoContatoDTO);
            }

            $objGrupoContatoBD->excluir($objGrupoContatoDTO);

            unset($arrObjRelGrupoContatoDTO);

          } else {

            $dto = new GrupoContatoDTO();
            $dto->setStrNome($strNomeGrupoContato);
            $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
            $dto->setNumIdGrupoContato($objGrupoContatoDTO->getNumIdGrupoContato());
            $objGrupoContatoBD->alterar($dto);
          }
        }

        unset($arrObjGrupoContatoDTO);
      }


      if ($objMigracaoUnidadeDTO->getStrSinGrupoEmail()=='S') {

        //grupos de email da unidade
        $objGrupoEmailDTO = new GrupoEmailDTO();
        $objGrupoEmailDTO->retNumIdGrupoEmail();
        $objGrupoEmailDTO->retStrNome();
        $objGrupoEmailDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());
        $objGrupoEmailDTO->setStrStaTipo(GrupoEmailRN::$TGE_UNIDADE);
        $objGrupoEmailDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objGrupoEmailRN = new GrupoEmailRN();
        $arrObjGrupoEmailDTO = $objGrupoEmailRN->listar($objGrupoEmailDTO);

        $prb->setNumMax(count($arrObjGrupoEmailDTO));
        $prb->setStrRotulo('Migrando Grupos de E-mail...');
        $prb->setNumPosicao(0);
        usleep($numRefreshEtapa);

        $objGrupoEmailBD = new GrupoEmailBD(BancoSEI::getInstance());
        $objEmailGrupoEmailBD = new EmailGrupoEmailBD(BancoSEI::getInstance());
        $objEmailGrupoEmailRN = new EmailGrupoEmailRN();

        foreach($arrObjGrupoEmailDTO as $objGrupoEmailDTO){

          $prb->setStrRotulo('Grupo de E-mail ' . $objGrupoEmailDTO->getStrNome() . '...');
          $prb->moverProximo();
          usleep($numRefresh);

          $strNomeGrupoEmail = substr($strTextoMigrado . $objGrupoEmailDTO->getStrNome(), 0, $objGrupoEmailRN->getNumMaxTamanhoNome());

          $dto = new GrupoEmailDTO();
          $dto->retNumIdGrupoEmail();
          $dto->setStrNome($strNomeGrupoEmail);
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
          $dto->setNumMaxRegistrosRetorno(1);

          $objGrupoEmailDTODestino = $objGrupoEmailRN->consultar($dto);

          if ($objGrupoEmailDTODestino != null) {

            $objEmailGrupoEmailDTO = new EmailGrupoEmailDTO();
            $objEmailGrupoEmailDTO->retNumIdEmailGrupoEmail();
            $objEmailGrupoEmailDTO->retStrEmail();
            $objEmailGrupoEmailDTO->setNumIdGrupoEmail($objGrupoEmailDTO->getNumIdGrupoEmail());
            $arrObjEmailGrupoEmailDTO = $objEmailGrupoEmailRN->listar($objEmailGrupoEmailDTO);

            foreach($arrObjEmailGrupoEmailDTO as $objEmailGrupoEmailDTO){

              $dto = new EmailGrupoEmailDTO();
              $dto->setStrEmail($objEmailGrupoEmailDTO->getStrEmail());
              $dto->setNumIdGrupoEmail($objGrupoEmailDTODestino->getNumIdGrupoEmail());

              if ($objEmailGrupoEmailRN->contar($dto)==0) {
                $objEmailGrupoEmailDTO->setNumIdGrupoEmail($objGrupoEmailDTODestino->getNumIdGrupoEmail());
                $objEmailGrupoEmailBD->alterar($objEmailGrupoEmailDTO);
              }else{
                $objEmailGrupoEmailBD->excluir($objEmailGrupoEmailDTO);
              }
            }

            $objGrupoEmailBD->excluir($objGrupoEmailDTO);

            unset($arrObjEmailGrupoEmailDTO);

          } else {
            //muda nome e unidade do grupo
            $dto = new GrupoEmailDTO();
            $dto->setStrNome($strNomeGrupoEmail);
            $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
            $dto->setNumIdGrupoEmail($objGrupoEmailDTO->getNumIdGrupoEmail());
            $objGrupoEmailBD->alterar($dto);
          }
        }
        unset($arrObjGrupoEmailDTO);
      }

      if ($objMigracaoUnidadeDTO->getStrSinGrupoUnidade()=='S') {

        $objGrupoUnidadeDTO = new GrupoUnidadeDTO();
        $objGrupoUnidadeDTO->retNumIdGrupoUnidade();
        $objGrupoUnidadeDTO->retStrNome();
        $objGrupoUnidadeDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());
        $objGrupoUnidadeDTO->setStrStaTipo(GrupoUnidadeRN::$TGU_UNIDADE);
        $objGrupoUnidadeDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objGrupoUnidadeRN = new GrupoUnidadeRN();
        $arrObjGrupoUnidadeDTO = $objGrupoUnidadeRN->listar($objGrupoUnidadeDTO);

        $prb->setNumMax(count($arrObjGrupoUnidadeDTO));
        $prb->setStrRotulo('Migrando Grupos de Envio...');
        $prb->setNumPosicao(0);
        usleep($numRefreshEtapa);

        $objGrupoUnidadeBD = new GrupoUnidadeBD(BancoSEI::getInstance());
        $objRelGrupoUnidadeUnidadeBD = new RelGrupoUnidadeUnidadeBD(BancoSEI::getInstance());
        $objRelGrupoUnidadeUnidadeRN = new RelGrupoUnidadeUnidadeRN();

        foreach($arrObjGrupoUnidadeDTO as $objGrupoUnidadeDTO){

          $prb->setStrRotulo('Grupo de Envio ' . $objGrupoUnidadeDTO->getStrNome() . '...');
          $prb->moverProximo();
          usleep($numRefresh);

          $strNomeGrupoUnidade = substr($strTextoMigrado . $objGrupoUnidadeDTO->getStrNome(), 0, $objGrupoUnidadeRN->getNumMaxTamanhoNome());

          $dto = new GrupoUnidadeDTO();
          $dto->retNumIdGrupoUnidade();
          $dto->setStrNome($strNomeGrupoUnidade);
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
          $dto->setNumMaxRegistrosRetorno(1);

          $objGrupoUnidadeDTODestino = $objGrupoUnidadeRN->consultar($dto);

          if ($objGrupoUnidadeDTODestino != null) {

            $objRelGrupoUnidadeUnidadeDTO = new RelGrupoUnidadeUnidadeDTO();
            $objRelGrupoUnidadeUnidadeDTO->retNumIdUnidade();
            $objRelGrupoUnidadeUnidadeDTO->retNumIdGrupoUnidade();
            $objRelGrupoUnidadeUnidadeDTO->setNumIdGrupoUnidade($objGrupoUnidadeDTO->getNumIdGrupoUnidade());
            $arrObjRelGrupoUnidadeUnidadeDTO = $objRelGrupoUnidadeUnidadeRN->listar($objRelGrupoUnidadeUnidadeDTO);

            foreach($arrObjRelGrupoUnidadeUnidadeDTO as $objRelGrupoUnidadeUnidadeDTO){

              $dto = new RelGrupoUnidadeUnidadeDTO();
              $dto->setNumIdUnidade($objRelGrupoUnidadeUnidadeDTO->getNumIdUnidade());
              $dto->setNumIdGrupoUnidade($objGrupoUnidadeDTODestino->getNumIdGrupoUnidade());

              if ($objRelGrupoUnidadeUnidadeRN->contar($dto)==0) {
                $objRelGrupoUnidadeUnidadeBD->cadastrar($dto);
              }

              $objRelGrupoUnidadeUnidadeBD->excluir($objRelGrupoUnidadeUnidadeDTO);
            }

            $objGrupoUnidadeBD->excluir($objGrupoUnidadeDTO);

            unset($arrObjRelGrupoUnidadeUnidadeDTO);

          } else {
            //muda nome e unidade do grupo
            $dto = new GrupoUnidadeDTO();
            $dto->setStrNome($strNomeGrupoUnidade);
            $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
            $dto->setNumIdGrupoUnidade($objGrupoUnidadeDTO->getNumIdGrupoUnidade());
            $objGrupoUnidadeBD->alterar($dto);
          }
        }

        unset($arrObjGrupoUnidadeDTO);
      }

      if ($objMigracaoUnidadeDTO->getStrSinMarcadores() == 'S') {

        $objMarcadorDTO = new MarcadorDTO();
        $objMarcadorDTO->retNumIdMarcador();
        $objMarcadorDTO->retStrNome();
        $objMarcadorDTO->retStrDescricao();
        $objMarcadorDTO->retStrStaIcone();
        $objMarcadorDTO->retStrSinAtivo();
        $objMarcadorDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());
        $objMarcadorDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objMarcadorRN = new MarcadorRN();
        $arrObjMarcadorDTO = $objMarcadorRN->listar($objMarcadorDTO);

        $prb->setNumMax(count($arrObjMarcadorDTO));
        $prb->setStrRotulo('Migrando Marcadores...');
        $prb->setNumPosicao(0);
//        usleep($numRefreshEtapa);

        foreach($arrObjMarcadorDTO as $objMarcadorDTO){

          $prb->setStrRotulo('Marcador ' . $objMarcadorDTO->getStrNome() . '...');
          $prb->moverProximo();
//          usleep($numRefresh);

          $strNomeMarcador = substr($strTextoMigrado . $objMarcadorDTO->getStrNome(), 0, $objMarcadorRN->getNumMaxTamanhoNome());

          $dto = new MarcadorDTO();
          $dto->retNumIdMarcador();
          $dto->setStrNome($strNomeMarcador);
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
          $dto->setNumMaxRegistrosRetorno(1);

          $dto = $objMarcadorRN->consultar($dto);

          if ($dto == null) {

            $dto = new MarcadorDTO();
            $dto->setNumIdMarcador(null);
            $dto->setStrNome($strNomeMarcador);
            $dto->setStrDescricao($objMarcadorDTO->getStrDescricao());
            $dto->setStrStaIcone($objMarcadorDTO->getStrStaIcone());
            $dto->setStrSinAtivo($objMarcadorDTO->getStrSinAtivo());
            $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());

            $objMarcadorRN->cadastrar($dto);
          }
        }
        unset($arrObjMarcadorDTO);
      }

      if ($objMigracaoUnidadeDTO->getStrSinModelo()=='S') {

        $objGrupoProtocoloModeloDTO = new GrupoProtocoloModeloDTO();
        $objGrupoProtocoloModeloDTO->retNumIdGrupoProtocoloModelo();
        $objGrupoProtocoloModeloDTO->retStrNome();
        $objGrupoProtocoloModeloDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());

        $objGrupoProtocoloModeloRN = new GrupoProtocoloModeloRN();
        $arrObjGrupoProtocoloModeloDTO = InfraArray::indexarArrInfraDTO($objGrupoProtocoloModeloRN->listar($objGrupoProtocoloModeloDTO), 'IdGrupoProtocoloModelo');

        $prb->setNumMax(count($arrObjGrupoProtocoloModeloDTO));
        $prb->setStrRotulo('Migrando Grupos de Modelos...');
        $prb->setNumPosicao(0);
        usleep($numRefreshEtapa);

        $objGrupoProtocoloModeloBD = new GrupoProtocoloModeloBD(BancoSEI::getInstance());

        foreach($arrObjGrupoProtocoloModeloDTO as $objGrupoProtocoloModeloDTO){

          $prb->setStrRotulo('Grupo de Modelos ' . $objGrupoProtocoloModeloDTO->getStrNome() . '...');
          $prb->moverProximo();
          usleep($numRefresh);

          $strNomeGrupoProtocoloModelo = substr($strTextoMigrado . $objGrupoProtocoloModeloDTO->getStrNome(), 0, $objGrupoProtocoloModeloRN->getNumMaxTamanhoNome());

          $dto = new GrupoProtocoloModeloDTO();
          $dto->retNumIdGrupoProtocoloModelo();
          $dto->setStrNome($strNomeGrupoProtocoloModelo);
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
          $dto->setNumMaxRegistrosRetorno(1);

          $dto = $objGrupoProtocoloModeloRN->consultar($dto);

          if ($dto != null) {
            //substitui pelo ID existente na unidade destino
            $objGrupoProtocoloModeloDTO->setNumIdGrupoProtocoloModelo($dto->getNumIdGrupoProtocoloModelo());
          } else {
            //muda nome e unidade do grupo
            $dto = new GrupoProtocoloModeloDTO();
            $dto->setStrNome($strNomeGrupoProtocoloModelo);
            $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
            $dto->setNumIdGrupoProtocoloModelo($objGrupoProtocoloModeloDTO->getNumIdGrupoProtocoloModelo());
            $objGrupoProtocoloModeloBD->alterar($dto);
          }
        }

        $objProtocoloModeloDTO = new ProtocoloModeloDTO();
        $objProtocoloModeloDTO->retDblIdProtocoloModelo();
        $objProtocoloModeloDTO->retStrProtocoloFormatado();
        $objProtocoloModeloDTO->retNumIdGrupoProtocoloModelo();
        $objProtocoloModeloDTO->retDblIdProtocolo();
        $objProtocoloModeloDTO->retStrDescricao();
        $objProtocoloModeloDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());
        $objProtocoloModeloDTO->setOrdDblIdProtocolo(InfraDTO::$TIPO_ORDENACAO_ASC);

        $objProtocoloModeloRN = new ProtocoloModeloRN();
        $arrObjProtocoloModeloDTO = $objProtocoloModeloRN->listar($objProtocoloModeloDTO);

        $prb->setNumMax(count($arrObjProtocoloModeloDTO));
        $prb->setStrRotulo('Migrando Favoritos...');
        $prb->setNumPosicao(0);
        usleep($numRefreshEtapa);

        $objProtocoloModeloBD = new ProtocoloModeloBD(BancoSEI::getInstance());

        foreach($arrObjProtocoloModeloDTO as $objProtocoloModeloDTO){

          $prb->setStrRotulo('Favorito ' . $objProtocoloModeloDTO->getStrProtocoloFormatado() . '...');
          $prb->moverProximo();
          usleep($numRefresh);

          $dto = new ProtocoloModeloDTO();
          $dto->setDblIdProtocolo($objProtocoloModeloDTO->getDblIdProtocolo());
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());

          if ($objProtocoloModeloDTO->getNumIdGrupoProtocoloModelo() != null) {
            $dto->setNumIdGrupoProtocoloModelo($arrObjGrupoProtocoloModeloDTO[$objProtocoloModeloDTO->getNumIdGrupoProtocoloModelo()]->getNumIdGrupoProtocoloModelo());
          }else{
            $dto->setNumIdGrupoProtocoloModelo(null);
          }

          if ($objProtocoloModeloRN->contar($dto) == 0) {

            if ($objProtocoloModeloDTO->getNumIdGrupoProtocoloModelo() != null) {
              $objProtocoloModeloDTO->setNumIdGrupoProtocoloModelo($arrObjGrupoProtocoloModeloDTO[$objProtocoloModeloDTO->getNumIdGrupoProtocoloModelo()]->getNumIdGrupoProtocoloModelo());
            }

            $objProtocoloModeloDTO->setStrDescricao(substr($strTextoMigrado . $objProtocoloModeloDTO->getStrDescricao(), 0, $objProtocoloModeloRN->getNumMaxTamanhoDescricao()));
            $objProtocoloModeloDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
            $objProtocoloModeloBD->alterar($objProtocoloModeloDTO);
          }
        }

        unset($arrObjGrupoProtocoloModeloDTO);
        unset($arrObjProtocoloModeloDTO);
      }

      if ($objMigracaoUnidadeDTO->getStrSinTextoPadrao()=='S') {

        $objTextoPadraoInternoDTO = new TextoPadraoInternoDTO();
        $objTextoPadraoInternoDTO->retNumIdTextoPadraoInterno();
        $objTextoPadraoInternoDTO->retStrNome();
        $objTextoPadraoInternoDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeOrigem());
        $objTextoPadraoInternoDTO->setOrdStrNome(InfraDTO::$TIPO_ORDENACAO_DESC);

        $objTextoPadraoInternoRN = new TextoPadraoInternoRN();
        $arrObjTextoPadraoInternoDTO = $objTextoPadraoInternoRN->listar($objTextoPadraoInternoDTO);

        $prb->setNumMax(count($arrObjTextoPadraoInternoDTO));
        $prb->setStrRotulo('Migrando Textos Padrгo...');
        $prb->setNumPosicao(0);
        usleep($numRefreshEtapa);

        $objTextoPadraoInternoBD = new TextoPadraoInternoBD($this->getObjInfraIBanco());

        foreach($arrObjTextoPadraoInternoDTO as $objTextoPadraoInternoDTO){

          $prb->setStrRotulo('Texto Padrгo ' . $objTextoPadraoInternoDTO->getStrNome() . '...');
          $prb->moverProximo();
          usleep($numRefresh);

          $strNomeTextoPadrao = substr($strTextoMigrado . $objTextoPadraoInternoDTO->getStrNome(), 0, $objTextoPadraoInternoRN->getNumMaxTamanhoNome());

          $dto = new TextoPadraoInternoDTO();
          $dto->setStrNome($strNomeTextoPadrao);
          $dto->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());

          if ($objTextoPadraoInternoRN->contar($dto) == 0) {
            $objTextoPadraoInternoDTO->setStrNome($strNomeTextoPadrao);
            $objTextoPadraoInternoDTO->setNumIdUnidade($objMigracaoUnidadeDTO->getNumIdUnidadeDestino());
            $objTextoPadraoInternoBD->alterar($objTextoPadraoInternoDTO);
          }
        }
        unset($arrObjTextoPadraoInternoDTO);
      }

      $prb->setStrRotulo('Migraзгo de '.$objUnidadeDTOOrigem->getStrSigla().' para '.$objUnidadeDTODestino->getStrSigla().' finalizada.');

      sleep(1);

    }catch(Exception $e){
      throw new InfraException('Erro migrando unidade.',$e);
    }
  }
  
}
?>