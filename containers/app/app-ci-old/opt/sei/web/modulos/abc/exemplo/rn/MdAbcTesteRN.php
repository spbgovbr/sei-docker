<?
require_once dirname(__FILE__).'/../../../../SEI.php';

class MdAbcTesteRN extends InfraRN {

  protected function inicializarObjInfraIBanco(){
    return BancoSEI::getInstance();
  }

  public function lancarAndamentosManual($arrIdProtocolo, $strTextoAndamento){
    try{

      BancoSEI::getInstance()->abrirConexao();
      BancoSEI::getInstance()->abrirTransacao();

      SessaoSEI::getInstance()->validarAuditarPermissao('md_abc_andamento_lancar', __METHOD__, array($arrIdProtocolo, $strTextoAndamento));

      $objSeiRN = new SeiRN();

      foreach($arrIdProtocolo as $dblIdProtocolo) {

        $objEntradaLancarAndamentoAPI = new EntradaLancarAndamentoAPI();
        $objEntradaLancarAndamentoAPI->setIdProcedimento($dblIdProtocolo);
        $objEntradaLancarAndamentoAPI->setIdTarefa(TarefaRN::$TI_ATUALIZACAO_ANDAMENTO); //ID=65

        $arrObjAtributoAndamentoAPI = array();

        $objAtributoAndamentoAPI = new AtributoAndamentoAPI();
        $objAtributoAndamentoAPI->setNome('DESCRICAO');
        $objAtributoAndamentoAPI->setValor($strTextoAndamento);
        $objAtributoAndamentoAPI->setIdOrigem(null);
        $arrObjAtributoAndamentoAPI[] = $objAtributoAndamentoAPI;

        $objEntradaLancarAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);

        $objSeiRN->lancarAndamento($objEntradaLancarAndamentoAPI);
      }

      BancoSEI::getInstance()->confirmarTransacao();
      BancoSEI::getInstance()->fecharConexao();

    }catch(Exception $e){
      
      try{
        BancoSEI::getInstance()->cancelarTransacao();
      }catch(Exception $e2){}

      try{
        BancoSEI::getInstance()->fecharConexao();
      }catch(Exception $e2){}
      
      throw new InfraException('Erro lançando andamentos do módulo ABC.',$e);
    }  
  }

  protected function lancarAndamentosAutomaticoControlado($arrParametros){
    try{

      SessaoSEI::getInstance()->validarAuditarPermissao('md_abc_andamento_lancar', __METHOD__, $arrParametros);

      $arrIdProtocolo = $arrParametros[0];
      $strTextoAndamento = $arrParametros[1];

      $objSeiRN = new SeiRN();

      foreach($arrIdProtocolo as $dblIdProtocolo) {

        $objEntradaLancarAndamentoAPI = new EntradaLancarAndamentoAPI();
        $objEntradaLancarAndamentoAPI->setIdProcedimento($dblIdProtocolo);
        $objEntradaLancarAndamentoAPI->setIdTarefa(TarefaRN::$TI_ATUALIZACAO_ANDAMENTO); //ID=65

        $arrObjAtributoAndamentoAPI = array();

        $objAtributoAndamentoAPI = new AtributoAndamentoAPI();
        $objAtributoAndamentoAPI->setNome('DESCRICAO');
        $objAtributoAndamentoAPI->setValor($strTextoAndamento);
        $objAtributoAndamentoAPI->setIdOrigem(null);
        $arrObjAtributoAndamentoAPI[] = $objAtributoAndamentoAPI;

        $objEntradaLancarAndamentoAPI->setAtributos($arrObjAtributoAndamentoAPI);

        $objSeiRN->lancarAndamento($objEntradaLancarAndamentoAPI);
      }

    }catch(Exception $e){
      throw new InfraException('Erro lançando andamentos do módulo ABC.',$e);
    }
  }
}
?>