<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 13/10/2009 - criado por mga
*
* Versão do Gerador de Código: 1.29.1
*
* Versão no CVS: $Id$
*/

require_once dirname(__FILE__).'/../SEI.php';

class OrgaoINT extends InfraINT {

  public static function montarSelectSiglaRI1358($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->retNumIdOrgao();
    $objOrgaoDTO->retStrSigla();

    if ($strValorItemSelecionado!=null){
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->adicionarCriterio(array('SinAtivo','IdOrgao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }

    $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    $objOrgaoRN = new OrgaoRN();
    $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjOrgaoDTO, 'IdOrgao', 'Sigla');
  }
  
  public static function montarSelectSiglaPublicacao($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->retNumIdOrgao();
    $objOrgaoDTO->retStrSigla();
    $objOrgaoDTO->setStrSinPublicacao('S');
  
    if ($strValorItemSelecionado!=null){
      $objOrgaoDTO->setBolExclusaoLogica(false);
      $objOrgaoDTO->adicionarCriterio(array('SinAtivo','IdOrgao'),array(InfraDTO::$OPER_IGUAL,InfraDTO::$OPER_IGUAL),array('S',$strValorItemSelecionado),InfraDTO::$OPER_LOGICO_OR);
    }
  
    $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);
  
    $objOrgaoRN = new OrgaoRN();
    $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);
  
    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjOrgaoDTO, 'IdOrgao', 'Sigla');
  }
  
  public static function montarSelectSiglaOuvidoria($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado){
  	 	
	  	$objUnidadeDTO = new UnidadeDTO();
	  	$objUnidadeDTO->retNumIdOrgao();
	  	$objUnidadeDTO->retStrSiglaOrgao();
	  	$objUnidadeDTO->setStrSinOuvidoria('S');
	    $objUnidadeDTO->setOrdStrSiglaOrgao(InfraDTO::$TIPO_ORDENACAO_ASC);
	  	
	  	$objUnidadeRN = new UnidadeRN();
	  	$arrObjUnidadeDTO = $objUnidadeRN->listarRN0127($objUnidadeDTO);

    return parent::montarSelectArrInfraDTO($strPrimeiroItemValor, $strPrimeiroItemDescricao, $strValorItemSelecionado, $arrObjUnidadeDTO, 'IdOrgao', 'SiglaOrgao');
  }

  public static function autoCompletarOrgaos($strPalavrasPesquisa){

    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->retNumIdOrgao();
    $objOrgaoDTO->retStrSigla();
    $objOrgaoDTO->retStrDescricao();

    $objOrgaoDTO->setOrdStrSigla(InfraDTO::$TIPO_ORDENACAO_ASC);

    if ($strPalavrasPesquisa!=''){
      $objOrgaoDTO->setStrPalavrasPesquisa($strPalavrasPesquisa);
    }

    $objOrgaoRN = new OrgaoRN();
    $arrObjOrgaoDTO = $objOrgaoRN->pesquisar($objOrgaoDTO);

    return $arrObjOrgaoDTO;
  }
  
  public static function montarRestricaoOrgaoUnidade($numIdTipoProcedimento, $numIdSerie, &$strCss, &$strHtml, &$strJsGlobal, &$strJsInicializar){

    if ($numIdTipoProcedimento!=null) {

      $objTipoProcedRestricaoDTO = new TipoProcedRestricaoDTO();
      $objTipoProcedRestricaoDTO->setDistinct(true);
      $objTipoProcedRestricaoDTO->retNumIdOrgao();
      $objTipoProcedRestricaoDTO->retStrSiglaOrgao();
      $objTipoProcedRestricaoDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
      $objTipoProcedRestricaoDTO->setOrdStrSiglaOrgao(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objTipoProcedRestricaoRN = new TipoProcedRestricaoRN();
      $arrObjTipoProcedRestricaoDTO = $objTipoProcedRestricaoRN->listar($objTipoProcedRestricaoDTO);

      $strItensSelOrgaosRestricao = parent::montarSelectArrInfraDTO(null, null, null, $arrObjTipoProcedRestricaoDTO, 'IdOrgao', 'SiglaOrgao');

      $objTipoProcedRestricaoDTO = new TipoProcedRestricaoDTO();
      $objTipoProcedRestricaoDTO->retNumIdOrgao();
      $objTipoProcedRestricaoDTO->retNumIdUnidade();
      $objTipoProcedRestricaoDTO->retStrSiglaUnidade();
      $objTipoProcedRestricaoDTO->retStrDescricaoUnidade();
      $objTipoProcedRestricaoDTO->setNumIdTipoProcedimento($numIdTipoProcedimento);
      $objTipoProcedRestricaoDTO->setNumTipoFkUnidade(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objTipoProcedRestricaoDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

      $arrObjRestricaoDTO = InfraArray::indexarArrInfraDTO($objTipoProcedRestricaoRN->listar($objTipoProcedRestricaoDTO), 'IdOrgao', true);
      
    }else if ($numIdSerie!=null){

      $objSerieRestricaoDTO = new SerieRestricaoDTO();
      $objSerieRestricaoDTO->setDistinct(true);
      $objSerieRestricaoDTO->retNumIdOrgao();
      $objSerieRestricaoDTO->retStrSiglaOrgao();
      $objSerieRestricaoDTO->setNumIdSerie($numIdSerie);
      $objSerieRestricaoDTO->setOrdStrSiglaOrgao(InfraDTO::$TIPO_ORDENACAO_ASC);

      $objSerieRestricaoRN = new SerieRestricaoRN();
      $arrObjSerieRestricaoDTO = $objSerieRestricaoRN->listar($objSerieRestricaoDTO);

      $strItensSelOrgaosRestricao = parent::montarSelectArrInfraDTO(null, null, null, $arrObjSerieRestricaoDTO, 'IdOrgao', 'SiglaOrgao');

      $objSerieRestricaoDTO = new SerieRestricaoDTO();
      $objSerieRestricaoDTO->retNumIdOrgao();
      $objSerieRestricaoDTO->retNumIdUnidade();
      $objSerieRestricaoDTO->retStrSiglaUnidade();
      $objSerieRestricaoDTO->retStrDescricaoUnidade();
      $objSerieRestricaoDTO->setNumIdSerie($numIdSerie);
      $objSerieRestricaoDTO->setNumTipoFkUnidade(InfraDTO::$TIPO_FK_OBRIGATORIA);
      $objSerieRestricaoDTO->setOrdStrSiglaUnidade(InfraDTO::$TIPO_ORDENACAO_ASC);

      $arrObjRestricaoDTO = InfraArray::indexarArrInfraDTO($objSerieRestricaoRN->listar($objSerieRestricaoDTO), 'IdOrgao', true);
    }

    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->setBolExclusaoLogica(false);
    $objOrgaoDTO->retNumIdOrgao();

    $objOrgaoRN = new OrgaoRN();
    $arrObjOrgaoDTO = $objOrgaoRN->listarRN1353($objOrgaoDTO);
    $strHtmlOrgaoUnidades = '';
    foreach ($arrObjOrgaoDTO as $objOrgaoDTO) {

      $numIdOrgao = $objOrgaoDTO->getNumIdOrgao();

      $strValor = '';
      if (isset($arrObjRestricaoDTO[$numIdOrgao])) {
        $arr = array();
        foreach ($arrObjRestricaoDTO[$numIdOrgao] as $objResticaoDTO) {
          $arr[] = array($objResticaoDTO->getNumIdUnidade(), UnidadeINT::formatarSiglaDescricao($objResticaoDTO->getStrSiglaUnidade(), $objResticaoDTO->getStrDescricaoUnidade()));
        }
        $strValor = PaginaSEI::getInstance()->gerarItensLupa($arr);
      }

      $strHtmlOrgaoUnidades .= '<input type="hidden" id="hdnOrgao' . $numIdOrgao . '" name="hdnOrgao' . $numIdOrgao . '" value="' . $strValor . '" />' . "\n";
      $strHtmlOrgaoUnidades .= '<input type="hidden" id="lnkOrgao' . $numIdOrgao . '" name="lnkOrgao' . $numIdOrgao . '" value="' . SessaoSEI::getInstance()->assinarLink('controlador.php?acao=unidade_selecionar_orgao&tipo_selecao=2&id_object=objLupaUnidades&id_orgao=' . $numIdOrgao) . '" />' . "\n";
    }

    $strCss ='
#lblOrgaos {position:absolute;left:0%;top:0%;width:20%;}
#txtOrgao {position:absolute;left:0%;top:13%;width:19.5%;}
#selOrgaos {position:absolute;left:0%;top:29%;width:20%;}
#divOpcoesOrgaos {position:absolute;left:21%;top:29%;}

#lblUnidades {position:absolute;left:25%;top:0%;}
#txtUnidade {position:absolute;left:25%;top:13%;width:54.5%;}
#selUnidades {position:absolute;left:25%;top:29%;width:55%;}
#divOpcoesUnidades {position:absolute;left:81%;top:29%;}
';

    $strJsGlobal = '
var objLupaOrgaos = null;
var objAutoCompletarOrgao = null;
var objLupaUnidades = null;
var objAutoCompletarUnidade = null;

function trocarOrgaoRestricao(){
  document.getElementById(\'hdnUnidades\').value = document.getElementById(\'hdnOrgao\' + document.getElementById(\'selOrgaos\').value).value;
  objLupaUnidades.montar();
}
';

    $strJsInicializar = '
  objLupaOrgaos	= new infraLupaSelect(\'selOrgaos\',\'hdnOrgaos\',\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=orgao_selecionar&tipo_selecao=2&id_object=objLupaOrgaos').'\');
  objLupaOrgaos.processarRemocao = function(itens){
    objLupaUnidades.limpar();
    for(var i=0;i < itens.length;i++){
      document.getElementById(\'hdnOrgao\' + itens[i].value).value = \'\';
    }
    return true;
  }

  objLupaOrgaos.finalizarSelecao = function(){
    objLupaUnidades.limpar();
  }

  objAutoCompletarOrgao = new infraAjaxAutoCompletar(\'hdnIdOrgao\',\'txtOrgao\',\''.SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=orgao_auto_completar').'\');
  objAutoCompletarOrgao.limparCampo = true;
  objAutoCompletarOrgao.prepararExecucao = function(){
    return \'palavras_pesquisa=\'+document.getElementById(\'txtOrgao\').value;
  };

  objAutoCompletarOrgao.processarResultado = function(id,descricao,complemento){
    if (id!=\'\'){
      objLupaOrgaos.adicionar(id,descricao,document.getElementById(\'txtOrgao\'));
      objLupaUnidades.limpar();
     }
  };

  objLupaUnidades = new infraLupaSelect(\'selUnidades\',\'hdnUnidades\',\'\');
  objLupaUnidades.validarSelecionar = function(){
    if (document.getElementById(\'selOrgaos\').selectedIndex==-1){
      alert(\'Nenhum Órgão selecionado.\');
      return false;
    }
    objLupaUnidades.url = document.getElementById(\'lnkOrgao\' + document.getElementById(\'selOrgaos\').value).value;
    return true;
  }

  objLupaUnidades.finalizarRemocao = function(){
    document.getElementById(\'hdnOrgao\' + document.getElementById(\'selOrgaos\').value).value = document.getElementById(\'hdnUnidades\').value;
    return true;
  }

  objLupaUnidades.finalizarSelecao = function(){
    document.getElementById(\'hdnOrgao\' + document.getElementById(\'selOrgaos\').value).value = document.getElementById(\'hdnUnidades\').value;
  }

  objAutoCompletarUnidade = new infraAjaxAutoCompletar(\'hdnIdUnidade\',\'txtUnidade\',\''.SessaoSEI::getInstance()->assinarLink('controlador_ajax.php?acao_ajax=unidade_auto_completar_todas').'\');
  objAutoCompletarUnidade.limparCampo = true;
  objAutoCompletarUnidade.prepararExecucao = function(){
    if (document.getElementById(\'selOrgaos\').selectedIndex==-1){
      alert(\'Nenhum Órgão selecionado.\');
      return false;
    }
    return \'palavras_pesquisa=\'+document.getElementById(\'txtUnidade\').value+\'&id_orgao=\'+document.getElementById(\'selOrgaos\').value;
  };

  objAutoCompletarUnidade.processarResultado = function(id,descricao,complemento){
    if (id!=\'\'){
      objLupaUnidades.adicionar(id,descricao,document.getElementById(\'txtUnidade\'));
      document.getElementById(\'hdnOrgao\' + document.getElementById(\'selOrgaos\').value).value = document.getElementById(\'hdnUnidades\').value;
    }
  };

  if (document.getElementById(\'selOrgaos\').options.length){
    document.getElementById(\'selOrgaos\').disabled = false;
    document.getElementById(\'selOrgaos\').options[0].selected = true;
    trocarOrgaoRestricao();
  }
  
  ';

    $strHtml = '
    <div id="divRestricao" class="infraAreaDados" style="height:16em;">
    <label id="lblOrgaos" for="selOrgaos" class="infraLabelOpcional">Restringir aos Órgãos:</label>
    <input type="text" id="txtOrgao" name="txtOrgao" class="infraText" />
    <input type="hidden" id="hdnIdOrgao" name="hdnIdOrgao" class="infraText" value="" />
    <select id="selOrgaos" name="selOrgaos" size="6" multiple="multiple" class="infraSelect" onchange="trocarOrgaoRestricao()" >
    '.$strItensSelOrgaosRestricao.'
    </select>
    <div id="divOpcoesOrgaos">
      <img id="imgLupaOrgaos" onclick="objLupaOrgaos.selecionar(700,500);" src="'.PaginaSEI::getInstance()->getIconePesquisar().'" alt="Selecionar Órgãos" title="Selecionar Órgãos" class="infraImgNormal"  />
      <br />
      <img id="imgExcluirOrgaos" onclick="objLupaOrgaos.remover();" src="'.PaginaSEI::getInstance()->getIconeRemover().'" alt="Remover Órgãos Selecionados" title="Remover Órgãos Selecionados" class="infraImgNormal"  />
    </div>
    <input type="hidden" id="hdnOrgaos" name="hdnOrgaos" value="'.$_POST['hdnOrgaos'].'" />
    <label id="lblUnidades" for="selUnidades" class="infraLabelOpcional">Restringir às Unidades:</label>
    <input type="text" id="txtUnidade" name="txtUnidade" class="infraText" />
    <input type="hidden" id="hdnIdUnidade" name="hdnIdUnidade" class="infraText" value="" />
    <select id="selUnidades" name="selUnidades" size="6" multiple="multiple" class="infraSelect" >
    </select>
    <div id="divOpcoesUnidades">
      <img id="imgLupaUnidades" onclick="objLupaUnidades.selecionar(700,500);" src="'.PaginaSEI::getInstance()->getIconePesquisar().'" alt="Selecionar Unidades" title="Selecionar Unidades" class="infraImg"  />
      <br />
      <img id="imgExcluirUnidades" onclick="objLupaUnidades.remover();" src="'.PaginaSEI::getInstance()->getIconeRemover().'" alt="Remover Unidades Selecionadas" title="Remover Unidades Selecionadas" class="infraImg"  />
    </div>
    <input type="hidden" id="hdnUnidades" name="hdnUnidades" value="'.$_POST['hdnUnidades'].'" />
    '.$strHtmlOrgaoUnidades.'
    </div>';
  }

  public static function processarRestricaoOrgaoUnidade($strNomeClasseDTO){

    $arrRestricaoOrgaos = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnOrgaos']);

    $reflectionClass = new ReflectionClass($strNomeClasseDTO);

    $arr = array();
    foreach($arrRestricaoOrgaos as $numIdOrgaoRestricao){
      if ($_POST['hdnOrgao'.$numIdOrgaoRestricao]==''){
        $dto = $reflectionClass->newInstance();
        $dto->setNumIdOrgao($numIdOrgaoRestricao);
        $dto->setNumIdUnidade(null);
        $arr[] = $dto;
      }else{
        $arrRestricaoOrgaoUnidades = PaginaSEI::getInstance()->getArrValuesSelect($_POST['hdnOrgao'.$numIdOrgaoRestricao]);
        foreach($arrRestricaoOrgaoUnidades as $numIdUnidadeRestricao){
          $dto = $reflectionClass->newInstance();
          $dto->setNumIdOrgao($numIdOrgaoRestricao);
          $dto->setNumIdUnidade($numIdUnidadeRestricao);
          $arr[] = $dto;
        }
      }
    }
    return $arr;
  }

  public static function formatarOrgaoInstalacaoFederacao($strSiglaOrgao, $strDescricaoOrgao, $strSiglaInstalacaoFederacao){
    return $strSiglaOrgao.' - '.$strDescricaoOrgao.' ('.$strSiglaInstalacaoFederacao.')';
  }
}
?>