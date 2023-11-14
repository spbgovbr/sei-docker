<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 16/08/2012 - criado por mkr@trf4.jus.br
*
* Versão do Gerador de Código: 1.33.0
*
* Versão no CVS: $Id$
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

  PaginaSEI::getInstance()->verificarSelecao('documento_modelo_selecionar');

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  $strParametros = '';
  if(isset($_GET['arvore'])){
    PaginaSEI::getInstance()->setBolArvore($_GET['arvore']);
    $strParametros .= '&arvore='.$_GET['arvore'];
  }
  
  $numIdProtocolo = '';
  if(isset($_GET['id_protocolo'])){
    $numIdProtocolo = $_GET['id_protocolo'];
    $strParametros .= '&id_protocolo='.$_GET['id_protocolo'];
  }
  
  $numIdGrupoProtocoloModelo = '';
  if(isset($_GET['id_grupo_protocolo_modelo'])){
    $numIdGrupoProtocoloModelo = $_GET['id_grupo_protocolo_modelo'];
  }else if (isset($_POST['selGrupoProtocoloModelo'])){
    $numIdGrupoProtocoloModelo = $_POST['selGrupoProtocoloModelo'];
  }
  
  $objProtocoloModeloDTO = new ProtocoloModeloDTO();

  $strDesabilitar = '';

  $arrComandos = array();

  switch($_GET['acao']){
    case 'protocolo_modelo_cadastrar':

      $strTitulo = 'Novo Favorito';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmCadastrarProtocoloModelo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      //$arrComandos[] = '<button type="button" accesskey="N" name="btnNovoGrupo" id="btnNovoGrupo" value="Novo Grupo" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_protocolo_modelo_cadastrar&acao_origem='.$_GET['acao'].'$&acao_retorno='.$_GET['acao'].$strParametros).'\';" class="infraButton"><span class="infraTeclaAtalho">N</span>ovo Grupo</button>';
      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      $objProtocoloModeloDTO->setDblIdProtocoloModelo(null);
      $objProtocoloModeloDTO->setNumIdUnidade(SessaoSEI::getInstance()->getNumIdUnidadeAtual());
      $objProtocoloModeloDTO->setNumIdGrupoProtocoloModelo($_POST['selGrupoProtocoloModelo']);
      $objProtocoloModeloDTO->setDblIdProtocolo($_POST['hdnIdProtocolo']);
      $objProtocoloModeloDTO->setNumIdUsuario(SessaoSEI::getInstance()->getNumIdUsuario());      
      $objProtocoloModeloDTO->setStrDescricao($_POST['txaDescricao']);

      if (isset($_POST['sbmCadastrarProtocoloModelo'])) {
        try{
          $objProtocoloModeloRN = new ProtocoloModeloRN();
          $objProtocoloModeloDTO = $objProtocoloModeloRN->cadastrar($objProtocoloModeloDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Favorito "'.$objProtocoloModeloDTO->getStrDescricao().'" cadastrado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].'&id_protocolo_modelo='.$objProtocoloModeloDTO->getDblIdProtocoloModelo().$strParametros.PaginaSEI::getInstance()->montarAncora($objProtocoloModeloDTO->getDblIdProtocoloModelo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    case 'protocolo_modelo_alterar':
      $strTitulo = 'Alterar Favorito';
      $arrComandos[] = '<button type="submit" accesskey="S" name="sbmAlterarProtocoloModelo" value="Salvar" class="infraButton"><span class="infraTeclaAtalho">S</span>alvar</button>';
      $strDesabilitar = 'disabled="disabled"';

      if (isset($_GET['id_protocolo_modelo'])){
        $objProtocoloModeloDTO->setDblIdProtocoloModelo($_GET['id_protocolo_modelo']);
        $objProtocoloModeloDTO->retTodos();
        $objProtocoloModeloRN = new ProtocoloModeloRN();
        $objProtocoloModeloDTO = $objProtocoloModeloRN->consultar($objProtocoloModeloDTO);
        if ($objProtocoloModeloDTO==null){
          throw new InfraException("Registro não encontrado.");
        }
        $numIdGrupoProtocoloModelo = $objProtocoloModeloDTO->getNumIdGrupoProtocoloModelo();
        $numIdProtocolo = $objProtocoloModeloDTO->getDblIdProtocolo();
      } else {
        $objProtocoloModeloDTO->setDblIdProtocoloModelo($_POST['hdnIdProtocoloModelo']);
        $objProtocoloModeloDTO->setNumIdGrupoProtocoloModelo($_POST['selGrupoProtocoloModelo']); 
        $objProtocoloModeloDTO->setStrDescricao($_POST['txaDescricao']);
      }

      $arrComandos[] = '<button type="button" accesskey="C" name="btnCancelar" id="btnCancelar" value="Cancelar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objProtocoloModeloDTO->getDblIdProtocoloModelo())).'\';" class="infraButton"><span class="infraTeclaAtalho">C</span>ancelar</button>';

      if (isset($_POST['sbmAlterarProtocoloModelo'])) {
        try{
          $objProtocoloModeloRN = new ProtocoloModeloRN();
          $objProtocoloModeloRN->alterar($objProtocoloModeloDTO);
          PaginaSEI::getInstance()->adicionarMensagem('Favorito "'.$objProtocoloModeloDTO->getStrDescricao().'" alterado com sucesso.');
          header('Location: '.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].$strParametros.PaginaSEI::getInstance()->montarAncora($objProtocoloModeloDTO->getDblIdProtocoloModelo())));
          die;
        }catch(Exception $e){
          PaginaSEI::getInstance()->processarExcecao($e);
        }
      }
      break;

    /*
    case 'protocolo_modelo_consultar':
      $strTitulo = 'Consultar Favorito';
      $arrComandos[] = '<button type="button" accesskey="F" name="btnFechar" value="Fechar" onclick="location.href=\''.SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.PaginaSEI::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao'].PaginaSEI::getInstance()->montarAncora($_GET['id_protocolo_modelo'])).'\';" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';
      $objProtocoloModeloDTO->setDblIdProtocoloModelo($_GET['id_protocolo_modelo']);
      $objProtocoloModeloDTO->setBolExclusaoLogica(false);
      $objProtocoloModeloDTO->retTodos();
      $objProtocoloModeloRN = new ProtocoloModeloRN();
      $objProtocoloModeloDTO = $objProtocoloModeloRN->consultar($objProtocoloModeloDTO);
      if ($objProtocoloModeloDTO===null){
        throw new InfraException("Registro não encontrado.");
      }
      break;
     */

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $strItensSelGrupoProtocoloModelo = GrupoProtocoloModeloINT::montarSelectNome('null','&nbsp;',$numIdGrupoProtocoloModelo,SessaoSEI::getInstance()->getNumIdUnidadeAtual());

  if (SessaoSEI::getInstance()->verificarPermissao('grupo_protocolo_modelo_cadastrar')) {
    $strImgNovoGrupoProtocoloModelo = '<img id="imgNovoGrupoProtocoloModelo" onclick="cadastrarGrupoProtocoloModelo();" src="'.PaginaSEI::getInstance()->getIconeMais().'" alt="Novo Grupo de Favoritos" title="Novo Grupo de Favoritos" class="infraImg" tabindex="'.PaginaSEI::getInstance()->getProxTabDados().'"/>';
    $strLinkNovoGrupoProtocoloModelo = SessaoSEI::getInstance()->assinarLink('controlador.php?acao=grupo_protocolo_modelo_cadastrar&acao_origem='.$_GET['acao'].'&acao_retorno='.$_GET['acao'].'&pagina_simples=1');
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
#lblSelGrupoProtocoloModelo {position:absolute;left:0%;top:0%;width:50%;}
#selGrupoProtocoloModelo {position:absolute;left:0%;top:10%;width:50%;}
#imgNovoGrupoProtocoloModelo {position:absolute;left:50.5%;top:11%;}

#lblDescricao {position:absolute;left:0%;top:25%;width:95%;}
#txaDescricao {position:absolute;left:0%;top:35%;width:95%;}

<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>
function inicializar(){
  infraEfeitoTabelas();
}

function cadastrarGrupoProtocoloModelo(){
  infraAbrirJanela('<?=$strLinkNovoGrupoProtocoloModelo?>','janelaGrupoProtocoloModelo',700,300,'location=0,status=1,resizable=1,scrollbars=1');
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
<form id="frmProtocoloModeloCadastro" method="post" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'].$strParametros)?>">
<?
PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
//PaginaSEI::getInstance()->montarAreaValidacao();
PaginaSEI::getInstance()->abrirAreaDados('20em');
?>
  <label id="lblGrupoProtocoloModelo" for="selGrupoProtocoloModelo" accesskey="G" class="infraLabelOpcional"><span class="infraTeclaAtalho">G</span>rupo:</label>
  <select id="selGrupoProtocoloModelo" name="selGrupoProtocoloModelo" class="infraSelect" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>">
  <?=$strItensSelGrupoProtocoloModelo?>
  </select>
  <?=$strImgNovoGrupoProtocoloModelo?>

  <label id="lblDescricao" for="txaDescricao" class="infraLabelOpcional">Descrição:</label>
  <textarea id="txaDescricao" name="txaDescricao" rows="<?=PaginaSEI::getInstance()->isBolNavegadorFirefox()?'5':'6'?>" onkeypress="return infraLimitarTexto(this,event,1000);" class="infraTextarea" tabindex="<?=PaginaSEI::getInstance()->getProxTabDados()?>"><?=PaginaSEI::tratarHTML($objProtocoloModeloDTO->getStrDescricao());?></textarea>

  <input type="hidden" id="hdnIdProtocoloModelo" name="hdnIdProtocoloModelo" value="<?=$objProtocoloModeloDTO->getDblIdProtocoloModelo();?>" />
  <input type="hidden" id="hdnIdProtocolo" name="hdnIdProtocolo" value="<?=$numIdProtocolo;?>" />
  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  //PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>