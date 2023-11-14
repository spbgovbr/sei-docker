<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 25/08/2017 - criado por mga@trf4.jus.br
 *
 */

try {
  require_once dirname(__FILE__).'/SEI.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  //InfraDebug::getInstance()->setBolLigado(false);
  //InfraDebug::getInstance()->setBolDebugInfra(true);
  //InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoSEI::getInstance()->validarLink();

  //PaginaSEI::getInstance()->setTipoPagina(InfraPagina::$TIPO_PAGINA_SIMPLES);

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);

  $strDesabilitar = '';

  $arrComandos = array();

  $bolFlagOK = false;
  $strLinkRetorno = '';

  switch($_GET['acao']){

    case 'painel_controle_configurar':

      $strTitulo = 'Configurar Painel de Controle';

      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmSalvar" value="Salvar" class="infraButton" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      $arrComandos[] = '<button type="button" accesskey="V" id="btnVoltar" value="Voltar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"><span class="infraTeclaAtalho">V</span>oltar</button>';


      if ($_GET['acao_origem']!='painel_controle_configurar'){

        $objPainelControleRN = new PainelControleRN();
        $objPainelControleDTO = $objPainelControleRN->carregarConfiguracoes();

      }else{

        $objPainelControleDTO = new PainelControleDTO();
        //$objPainelControleDTO->setStrSinPainelProcessos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelProcessos']));
        $objPainelControleDTO->setStrSinPainelProcessos('S');
        $objPainelControleDTO->setStrSinPainelTiposProcessos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelTiposProcessos']));
        $objPainelControleDTO->setStrSinVerTiposProcessosZerados(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinTiposProcessosZerados']));
        $objPainelControleDTO->setStrSinPainelTiposPrioritarios(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelTiposPrioritarios']));
        $objPainelControleDTO->setStrSinVerTiposPrioritariosZerados(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinTiposPrioritariosZerados']));
        $objPainelControleDTO->setStrSinPainelControlesPrazos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelControlesPrazos']));
        $objPainelControleDTO->setStrSinPainelRetornosProgramados(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelRetornosProgramados']));
        $objPainelControleDTO->setStrSinPainelBlocos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelBlocos']));
        $objPainelControleDTO->setStrSinPainelGruposBlocos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelGruposBlocos']));
        $objPainelControleDTO->setStrSinVerBlocosSemGrupo(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinBlocosSemGrupo']));
        $objPainelControleDTO->setStrSinVerGruposBlocosZerados(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinGruposBlocosZerados']));
        $objPainelControleDTO->setStrSinPainelMarcadores(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelMarcadores']));
        $objPainelControleDTO->setStrSinVerProcessosSemMarcador(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinProcessosSemMarcador']));
        $objPainelControleDTO->setStrSinVerMarcadoresZerados(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinMarcadoresZerados']));
        $objPainelControleDTO->setStrSinPainelAtribuicoes(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelAtribuicoes']));
        $objPainelControleDTO->setStrSinVerProcessosSemAtribuicao(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinProcessosSemAtribuicao']));
        $objPainelControleDTO->setStrSinVerAtribuicoesZeradas(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAtribuicoesZeradas']));
        $objPainelControleDTO->setStrSinPainelAcompanhamentos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelAcompanhamentos']));
        $objPainelControleDTO->setStrSinVerProcessosSemAcompanhamento(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinProcessosSemAcompanhamento']));
        $objPainelControleDTO->setStrSinVerAcompanhamentosZerados(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAcompanhamentosZerados']));
        $objPainelControleDTO->setStrSinPainelPaginaInicial(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinPainelPaginaInicial']));
      }

      if (isset($_POST['sbmSalvar'])) {
        try{

          $objPainelControleRN = new PainelControleRN();
          $objPainelControleRN->salvarConfiguracoes($objPainelControleDTO);
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_acesso_externo'])));
          die;

        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
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

#btnTiposProcessos,
#btnTiposPrioritarios,
#btnGruposBlocosSelecao,
#divSinBlocosSemGrupo,
#divSinGruposBlocosZerados,
#divSinTiposProcessosZerados,
#divSinTiposPrioritariosZerados,
#btnMarcadoresSelecao,
#divSinProcessosSemMarcador,
#divSinMarcadoresZerados,
#btnAtribuicoesSelecao,
#divSinProcessosSemAtribuicao,
#divSinAtribuicoesZeradas,
#btnAcompanhamentosSelecao,
#divSinProcessosSemAcompanhamento,
#divSinAcompanhamentosZerados {
  margin-left:5em
}


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function inicializar(){
  document.getElementById('btnVoltar').focus();
}

function OnSubmitForm() {
  return true;
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmAcessoExternoCancelar" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
    //PaginaSEI::getInstance()->montarAreaValidacao();
    ?>

    <div id="divPainelProcessos" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:none;">
      <input type="checkbox" id="chkSinPainelProcessos" name="chkSinPainelProcessos" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelProcessos())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelProcessos" for="chkSinPainelProcessos" accesskey="" class="infraLabelCheckbox">Processos abertos</label>
    </div>

    <div id="divPainelControlesPrazos" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinPainelControlesPrazos" name="chkSinPainelControlesPrazos" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelControlesPrazos())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelControlesPrazos" for="chkSinPainelControlesPrazos" accesskey="" class="infraLabelCheckbox">Controles de prazos</label>
    </div>

    <div id="divPainelRetornosProgramados" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinPainelRetornosProgramados" name="chkSinPainelRetornosProgramados" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelRetornosProgramados())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelRetornosProgramados" for="chkSinPainelRetornosProgramados" accesskey="" class="infraLabelCheckbox">Retornos programados</label>
    </div>

    <div id="divPainelBlocos" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinPainelBlocos" name="chkSinPainelBlocos" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelBlocos())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelBlocos" for="chkSinPainelBlocos" accesskey="" class="infraLabelCheckbox">Blocos de assinatura abertos</label>
    </div>

    <div id="divPainelGruposBlocos" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinPainelGruposBlocos" name="chkSinPainelGruposBlocos" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelGruposBlocos())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelGruposBlocos" for="chkSinPainelGruposBlocos" accesskey="" class="infraLabelCheckbox">Grupos de blocos de assinatura abertos</label>
    </div>

      <input type="button" id="btnGruposBlocosSelecao" onclick="infraAbrirJanelaModal('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_usuario_grupo_bloco_configurar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'])?>',800,600,false)" value="Configurar Minha Seleção" class="infraButton"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <br><br>

      <div id="divSinBlocosSemGrupo" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinBlocosSemGrupo" name="chkSinBlocosSemGrupo" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinVerBlocosSemGrupo())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinBlocosSemGrupo" for="chkSinBlocosSemGrupo" accesskey="" class="infraLabelCheckbox">Exibir blocos sem grupo definido</label>
      </div>

      <div id="divSinGruposBlocosZerados" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinGruposBlocosZerados" name="chkSinGruposBlocosZerados" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinVerGruposBlocosZerados())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinGruposBlocosZerados" for="chkSinGruposBlocosZerados" accesskey="" class="infraLabelCheckbox">Exibir grupos sem blocos</label>
      </div>

    <div id="divPainelTiposProcessos" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinPainelTiposProcessos" name="chkSinPainelTiposProcessos" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelTiposProcessos())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelTiposProcessos" for="chkSinPainelTiposProcessos" accesskey="" class="infraLabelCheckbox">Processos abertos por tipo</label>
    </div>

      <input type="button" id="btnTiposProcessos" onclick="infraAbrirJanelaModal('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_usuario_tipo_proced_configurar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'])?>',800,600,false)" value="Configurar Minha Seleção" class="infraButton"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <br><br>

      <div id="divSinTiposProcessosZerados" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinTiposProcessosZerados" name="chkSinTiposProcessosZerados" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinVerTiposProcessosZerados())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinTiposProcessosZerados" for="chkSinTiposProcessosZerados" accesskey="" class="infraLabelCheckbox">Exibir tipos de processos sem processos</label>
      </div>

    <div id="divPainelTiposPrioritarios" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinPainelTiposPrioritarios" name="chkSinPainelTiposPrioritarios" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelTiposPrioritarios())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelTiposPrioritarios" for="chkSinPainelTiposPrioritarios" accesskey="" class="infraLabelCheckbox">Processos abertos por prioridade</label>
    </div>

      <input type="button" id="btnTiposPrioritarios" onclick="infraAbrirJanelaModal('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_usuario_tipo_prioridade_configurar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'])?>',800,600,false)" value="Configurar Minha Seleção" class="infraButton"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <br><br>

      <div id="divSinTiposPrioritariosZerados" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinTiposPrioritariosZerados" name="chkSinTiposPrioritariosZerados" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinVerTiposPrioritariosZerados())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinTiposPrioritariosZerados" for="chkSinTiposPrioritariosZerados" accesskey="" class="infraLabelCheckbox">Exibir tipos de prioridade sem processos</label>
      </div>


    <div id="divPainelMarcadores" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinPainelMarcadores" name="chkSinPainelMarcadores" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelMarcadores())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelMarcadores" for="chkSinPainelMarcadores" accesskey="" class="infraLabelCheckbox">Marcadores em processos</label>
    </div>

        <input type="button" id="btnMarcadoresSelecao" onclick="infraAbrirJanelaModal('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_usuario_marcador_configurar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'])?>',800,600,false)" value="Configurar Minha Seleção" class="infraButton"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        <br><br>

        <div id="divSinProcessosSemMarcador" class="infraDivCheckbox">
          <input type="checkbox" id="chkSinProcessosSemMarcador" name="chkSinProcessosSemMarcador" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinVerProcessosSemMarcador())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <label id="lblSinProcessosSemMarcador" for="chkSinProcessosSemMarcador" accesskey="" class="infraLabelCheckbox">Exibir processos sem marcador definido</label>
        </div>

        <div id="divSinMarcadoresZerados" class="infraDivCheckbox">
          <input type="checkbox" id="chkSinMarcadoresZerados" name="chkSinMarcadoresZerados" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinVerMarcadoresZerados())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <label id="lblSinMarcadoresZerados" for="chkSinMarcadoresZerados" accesskey="" class="infraLabelCheckbox">Exibir marcadores sem processos</label>
        </div>


    <div id="divPainelAtribuicoes" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinPainelAtribuicoes" name="chkSinPainelAtribuicoes" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelAtribuicoes())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelAtribuicoes" for="chkSinPainelAtribuicoes" accesskey="" class="infraLabelCheckbox">Atribuições de processos</label>
    </div>

        <input type="button" id="btnAtribuicoesSelecao" onclick="infraAbrirJanelaModal('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_usuario_usuario_unidade_configurar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'])?>',800,600,false)" value="Configurar Minha Seleção" class="infraButton"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

        <br><br>

        <div id="divSinProcessosSemAtribuicao" class="infraDivCheckbox">
          <input type="checkbox" id="chkSinProcessosSemAtribuicao" name="chkSinProcessosSemAtribuicao" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinVerProcessosSemAtribuicao())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <label id="lblSinProcessosSemAtribuicao" for="chkSinProcessosSemAtribuicao" accesskey="" class="infraLabelCheckbox">Exibir processos sem atribuição definida</label>
        </div>

        <div id="divSinAtribuicoesZeradas" class="infraDivCheckbox">
          <input type="checkbox" id="chkSinAtribuicoesZeradas" name="chkSinAtribuicoesZeradas" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinVerAtribuicoesZeradas())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
          <label id="lblSinAtribuicoesZeradas" for="chkSinAtribuicoesZeradas" accesskey="" class="infraLabelCheckbox">Exibir usuários sem processos</label>
        </div>


    <div id="divPainelAcompanhamentos" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinPainelAcompanhamentos" name="chkSinPainelAcompanhamentos" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelAcompanhamentos())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelAcompanhamentos" for="chkSinPainelAcompanhamentos" accesskey="" class="infraLabelCheckbox">Grupos de acompanhamentos especiais em processos</label>
    </div>

      <input type="button" id="btnAcompanhamentosSelecao" onclick="infraAbrirJanelaModal('<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao=rel_usuario_grupo_acomp_configurar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'])?>',800,600,false)" value="Configurar Minha Seleção" class="infraButton"  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />

      <br><br>

      <div id="divSinProcessosSemAcompanhamento" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinProcessosSemAcompanhamento" name="chkSinProcessosSemAcompanhamento" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinVerProcessosSemAcompanhamento())?>  tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinProcessosSemAcompanhamento" for="chkSinProcessosSemAcompanhamento" accesskey="" class="infraLabelCheckbox">Exibir processos sem grupo definido</label>
      </div>

      <div id="divSinAcompanhamentosZerados" class="infraDivCheckbox">
        <input type="checkbox" id="chkSinAcompanhamentosZerados" name="chkSinAcompanhamentosZerados" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinVerAcompanhamentosZerados())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
        <label id="lblSinAcompanhamentosZerados" for="chkSinAcompanhamentosZerados" accesskey="" class="infraLabelCheckbox">Exibir grupos sem processos</label>
      </div>

    <div id="divPainelPaginaInicial" class="infraDivCheckbox infraAreaDados" style="height:2.5em;display:block;">
      <input type="checkbox" id="chkSinPainelPaginaInicial" name="chkSinPainelPaginaInicial" class="infraCheckbox" <?=PaginaSEI::getInstance()->setCheckbox($objPainelControleDTO->getStrSinPainelPaginaInicial())?>   tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>" />
      <label id="lblSinPainelPaginaInicial" for="chkSinPainelPaginaInicial" accesskey="" class="infraLabelCheckbox">Utilizar como página inicial</label>
    </div>

    <?
    PaginaSEI::getInstance()->montarAreaDebug();
    //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>