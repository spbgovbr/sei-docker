<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 30/07/2008 - criado por mga
*
* Versão do Gerador de Código: 1.21.0
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class DocumentoBD extends InfraBD {

  public function __construct(InfraIBanco $objInfraIBanco){
  	 parent::__construct($objInfraIBanco);
  }

  public function removerVersoes(DocumentoDTO $objDocumentoDTO){
    try{

      $objVersaoSecaoDocumentoDTO = new VersaoSecaoDocumentoDTO();
      $objVersaoSecaoDocumentoDTO->retDblIdVersaoSecaoDocumento();
      $objVersaoSecaoDocumentoDTO->retNumIdUsuario();
      $objVersaoSecaoDocumentoDTO->retNumIdUnidade();
      $objVersaoSecaoDocumentoDTO->retDthAtualizacao();
      $objVersaoSecaoDocumentoDTO->retNumVersao();
      $objVersaoSecaoDocumentoDTO->retStrSinUltima();
      $objVersaoSecaoDocumentoDTO->setDblIdDocumentoSecaoDocumento($objDocumentoDTO->getDblIdDocumento());
      $objVersaoSecaoDocumentoDTO->setOrdNumVersao(InfraDTO::$TIPO_ORDENACAO_DESC);

      $objVersaoSecaoDocumentoRN = new VersaoSecaoDocumentoRN();
      $arr = InfraArray::indexarArrInfraDTO($objVersaoSecaoDocumentoRN->listar($objVersaoSecaoDocumentoDTO), 'SinUltima', true);

      if (isset($arr['S'])){

        $numVersao = $arr['S'][0]->getNumVersao();
        $numIdUsuario = $arr['S'][0]->getNumIdUsuario();
        $numIdUnidade = $arr['S'][0]->getNumIdUnidade();
        $dthAtualizacao = $arr['S'][0]->getDthAtualizacao();

        $arrIdAtualizacao = array();
        foreach($arr['S'] as $dto){
          if ($dto->getNumVersao()!=$numVersao){
            $arrIdAtualizacao[] = $dto->getDblIdVersaoSecaoDocumento();
          }
        }

        if (count($arrIdAtualizacao)){
          $sql = 'update versao_secao_documento '.
                 'set versao='.$numVersao .', id_usuario='.$numIdUsuario.', id_unidade='.$numIdUnidade.', dth_atualizacao='.$this->getObjInfraIBanco()->formatarGravacaoDth($dthAtualizacao).' '.
                 'where '.$this->formatarIn('id_versao_secao_documento', $arrIdAtualizacao, InfraDTO::$PREFIXO_DBL);
          $this->getObjInfraIBanco()->executarSql($sql);
        }
      }

      if (isset($arr['N'])){
        $arrIdExclusao = InfraArray::converterArrInfraDTO($arr['N'],'IdVersaoSecaoDocumento');
        $sql = 'delete from versao_secao_documento where '.$this->formatarIn('id_versao_secao_documento', $arrIdExclusao, InfraDTO::$PREFIXO_DBL);
        $this->getObjInfraIBanco()->executarSql($sql);
      }

    }catch(Exception $e){
      throw new InfraException('Erro removendo versões do documento.',$e);
    }
  }

  public function eliminar(array $arrObjDocumentoDTO)
  {
    try {

      $arrIdDocumentos = InfraArray::converterArrInfraDTO($arrObjDocumentoDTO, "IdDocumento");

      $strInIdDocumento = $this->formatarIn('id_documento', $arrIdDocumentos, InfraDTO::$PREFIXO_DBL);
      $strInIdProtocolo = str_replace('id_documento', 'id_protocolo', $strInIdDocumento);
      $strInSecaoDocumentoIdDocumento = str_replace('id_documento', 'secao_documento.id_documento', $strInIdDocumento);
      //$strInIdProtocolo1 = str_replace('id_documento', 'id_protocolo_1', $strInIdDocumento);
      //$strInIdProtocolo2 = str_replace('id_documento', 'id_protocolo_2', $strInIdDocumento);

      $objAssinaturaDTO = new AssinaturaDTO();
      $objAssinaturaDTO->setBolExclusaoLogica(false);
      $objAssinaturaDTO->retNumIdAtividade();
      $objAssinaturaDTO->setDblIdDocumento($arrIdDocumentos, InfraDTO::$OPER_IN);
      $objAssinaturaRN = new AssinaturaRN();
      $arrIdAtividadesAssinatura = InfraArray::converterArrInfraDTO($objAssinaturaRN->listarRN1323($objAssinaturaDTO), 'IdAtividade');

      $objPublicacaoDTO = new PublicacaoDTO();
      $objPublicacaoDTO->retNumIdAtividade();
      $objPublicacaoDTO->setDblIdDocumento($arrIdDocumentos, InfraDTO::$OPER_IN);
      $objPublicacaoRN = new PublicacaoRN();
      $arrIdAtividadesPublicacao = InfraArray::converterArrInfraDTO($objPublicacaoRN->listarRN1045($objPublicacaoDTO), 'IdAtividade');

      $arrIdAtividadesExcluir = array_merge($arrIdAtividadesAssinatura, $arrIdAtividadesPublicacao);

      $sql = 'delete from assinatura where '.$strInIdDocumento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from publicacao where '.$strInIdDocumento;
      $this->getObjInfraIBanco()->executarSql($sql);

      if (count($arrIdAtividadesExcluir)){
        $sql = ' delete from atributo_andamento where '.$this->formatarIn('id_atividade', $arrIdAtividadesExcluir, InfraDTO::$PREFIXO_NUM);
        $this->getObjInfraIBanco()->executarSql($sql);

        $sql = ' delete from atividade where '.$this->formatarIn('id_atividade', $arrIdAtividadesExcluir, InfraDTO::$PREFIXO_NUM);
        $this->getObjInfraIBanco()->executarSql($sql);
      }

      $sql = ' delete from versao_secao_documento'.
             ' where versao_secao_documento.id_secao_documento in ('.
             '   select secao_documento.id_secao_documento'.
             '   from secao_documento'.
             '   where '.$strInSecaoDocumentoIdDocumento.
             ' )';
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from secao_documento  where '.$strInIdDocumento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from documento_conteudo  where '.$strInIdDocumento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'update acesso_externo set id_documento=null where '.$strInIdDocumento;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from rel_acesso_ext_protocolo where '.$strInIdProtocolo;
      $this->getObjInfraIBanco()->executarSql($sql);

      // $sql = 'delete from comentario where '.$strInIdProtocolo;
      // $this->getObjInfraIBanco()->executarSql($sql);

      //$sql = 'delete from arquivamento where '.$strInIdProtocolo;
      //$this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from participante where '.$strInIdProtocolo;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from observacao where '.$strInIdProtocolo;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from rel_protocolo_assunto where '.$strInIdProtocolo;
      $this->getObjInfraIBanco()->executarSql($sql);

      //$sql = 'delete from rel_protocolo_protocolo where '.$strInIdProtocolo1;
      //$this->getObjInfraIBanco()->executarSql($sql);

      //$sql = 'delete from rel_protocolo_protocolo where '.$strInIdProtocolo2;
      //$this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from acesso where '.$strInIdProtocolo;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from anexo where '.$strInIdProtocolo;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from rel_bloco_protocolo where '.$strInIdProtocolo;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from protocolo_modelo where '.$strInIdProtocolo;
      $this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'delete from rel_protocolo_atributo where '.$strInIdProtocolo;
      $this->getObjInfraIBanco()->executarSql($sql);

      //$sql = 'delete from documento where '.$strInIdDocumento;
      //$this->getObjInfraIBanco()->executarSql($sql);

      //$sql = 'delete from protocolo where id_protocolo '.$strInDocumento;
      //$this->getObjInfraIBanco()->executarSql($sql);

      $sql = 'update protocolo set sin_eliminado=\'S\' where '.$strInIdProtocolo;
      $this->getObjInfraIBanco()->executarSql($sql);

    } catch (Exception $e) {
      throw new InfraException('Erro eliminando Documento.', $e);
    }
  }
}
?>