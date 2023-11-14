<?
/*
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 04/10/2012 - CRIADO POR MKR
*
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

  SessaoSEI::getInstance()->validarPermissao($_GET['acao']);
  
  $arrComandos = array();
  
  switch($_GET['acao']){
    case 'indexar':
      
      $strTitulo = 'Indexação';

      $objIndexacaoRN = new IndexacaoRN();

      if (isset($_GET['acao_interna'])){
        
        switch($_GET['acao_interna']){
          
          case 'gerar_indexacao_completa':
            PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo);
            try{

              $objIndexacaoDTO = new IndexacaoDTO();
              $objIndexacaoDTO->setDthInicio($_POST['txtDtaInicialCompleta']);
              $objIndexacaoDTO->setDthFim(null);
              $objIndexacaoRN->gerarIndexacaoCompleta($objIndexacaoDTO);

            }catch(Exception $e){
              PaginaSEI::getInstance()->processarExcecao($e);
            }
            PaginaSEI::getInstance()->finalizarBarraProgresso2(null, false);
            break;
            
          case 'gerar_indexacao_parcial':
            PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo);
            try{

              $objIndexacaoDTO = new IndexacaoDTO();
              $objIndexacaoDTO->setDthInicio($_POST['txtDthInicial']);
              $objIndexacaoDTO->setDthFim($_POST['txtDthFinal']);

              $objIndexacaoRN->gerarIndexacaoParcial($objIndexacaoDTO);

            }catch(Exception $e){
              PaginaSEI::getInstance()->processarExcecao($e);
            }
            PaginaSEI::getInstance()->finalizarBarraProgresso2(null, false);
            break;

          case 'gerar_indexacao_processo':
            PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo);
            try{

              $objIndexacaoDTO = new IndexacaoDTO();
              $objIndexacaoDTO->setStrProtocoloFormatadoPesquisa($_POST['txtProtocoloFormatado']);
              $objIndexacaoRN->gerarIndexacaoProcesso($objIndexacaoDTO);

            }catch(Exception $e){
              PaginaSEI::getInstance()->processarExcecao($e);
            }
            PaginaSEI::getInstance()->finalizarBarraProgresso2(null, false);
            break;

            case 'gerar_indexacao_bases_conhecimento':
              PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo);
              try{

                $objIndexacaoRN->gerarIndexacaoBasesConhecimento();

              }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
              }
              PaginaSEI::getInstance()->finalizarBarraProgresso2(null, false);
              break;
            
            case 'gerar_indexacao_publicacao':
              PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo);
              try{

                $objIndexacaoRN->gerarIndexacaoPublicacao();

              }catch(Exception $e){
                PaginaSEI::getInstance()->processarExcecao($e);
              }
              PaginaSEI::getInstance()->finalizarBarraProgresso2(null, false);
              break;

          case 'gerar_indexacao_controle_interno':
            PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo);
            try{

              $objIndexacaoRN->gerarIndexacaoControleInterno();

            }catch(Exception $e){
              PaginaSEI::getInstance()->processarExcecao($e);
            }
            PaginaSEI::getInstance()->finalizarBarraProgresso2(null, false);
            break;

          case 'gerar_indexacao_interna':
            PaginaSEI::getInstance()->prepararBarraProgresso2($strTitulo);

            try{

              $objIndexacaoDTO = new IndexacaoDTO();
              $objIndexacaoDTO->setStrSinOrgaos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinOrgaos']));
              $objIndexacaoDTO->setStrSinUnidades(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinUnidades']));
              $objIndexacaoDTO->setStrSinUsuarios(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinUsuarios']));
              $objIndexacaoDTO->setStrSinContatos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinContatos']));
              $objIndexacaoDTO->setStrSinAssuntos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAssuntos']));
              $objIndexacaoDTO->setStrSinAcompanhamentos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAcompanhamentos']));
              $objIndexacaoDTO->setStrSinBlocos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinBlocos']));
              $objIndexacaoDTO->setStrSinGruposEmail(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinGruposEmail']));
              $objIndexacaoDTO->setStrSinObservacoes(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinObservacoes']));
              $objIndexacaoDTO->setStrSinFavoritos(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinFavoritos']));

              $objIndexacaoRN->gerarIndexacaoInterna($objIndexacaoDTO);

            }catch(Exception $e){
              PaginaSEI::getInstance()->processarExcecao($e);
            }
            PaginaSEI::getInstance()->finalizarBarraProgresso2(null, false);
            break;


            default:
              throw new InfraException("Ação interna '".$_GET['acao_interna']."' não reconhecida.");
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
PaginaSEI::getInstance()->montarTitle(PaginaSEI::getInstance()->getStrNomeSistema().' - Indexação');
PaginaSEI::getInstance()->montarStyle();
PaginaSEI::getInstance()->abrirStyle();
?>


<?
PaginaSEI::getInstance()->fecharStyle();
PaginaSEI::getInstance()->montarJavaScript();
PaginaSEI::getInstance()->abrirJavaScript();
?>

function OnSubmitForm() {
  return validarForm();
}

function validarForm() {
  return true;
}

function gerarIndexacaoInterna(){

  if (!document.getElementById('chkSinOrgaos').checked &&
      !document.getElementById('chkSinUnidades').checked &&
      !document.getElementById('chkSinUsuarios').checked &&
      !document.getElementById('chkSinContatos').checked &&
      !document.getElementById('chkSinAssuntos').checked &&
      !document.getElementById('chkSinAcompanhamentos').checked &&
      !document.getElementById('chkSinBlocos').checked &&
      !document.getElementById('chkSinGruposEmail').checked &&
      !document.getElementById('chkSinObservacoes').checked &&
      !document.getElementById('chkSinFavoritos').checked){
    alert('Nenhuma opção para Indexação Interna selecionada.');
    return;
  }

  infraAbrirBarraProgresso(document.getElementById('frmIndexacao'),'<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_interna=gerar_indexacao_interna')?>', 800, 500);
}

<?
PaginaSEI::getInstance()->fecharJavaScript();
PaginaSEI::getInstance()->fecharHead();
PaginaSEI::getInstance()->abrirBody($strTitulo);
?>
<form id="frmIndexacao" method="post" onsubmit="return OnSubmitForm();" action="<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
  <?
  //PaginaSEI::getInstance()->montarBarraLocalizacao('Importar Sistema');
  PaginaSEI::getInstance()->montarBarraComandosSuperior($arrComandos);
  PaginaSEI::getInstance()->abrirAreaDados('100em');
  ?>
  <label class="infraLabelOpcional">Data inicial:</label><br />
  <input type="text" id="txtDtaInicialCompleta" name="txtDtaInicialCompleta" value="<?=PaginaSEI::tratarHTML($_POST['txtDtaInicialCompleta'])?>" onkeypress="return infraMascaraData(this, event)" class="infraText" /> (dd/mm/aaaa) <br /><br />
	<button type="button" name="btnGerarIndexacaoCompleta" onclick="infraAbrirBarraProgresso(this.form,'<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_interna=gerar_indexacao_completa')?>', 600, 250);" value="Gerar Indexação Completa de Processos e Documentos" class="infraButton">Gerar Indexação Completa de Processos e Documentos</button><br /><br />

  <hr /><br />

  <label class="infraLabelOpcional">Processos (separados por vírgula):</label><br />
  <input type="text" id="txtProtocoloFormatado" name="txtProtocoloFormatado" value="<?=PaginaSEI::tratarHTML($_POST['txtProtocoloFormatado'])?>" class="infraText" style="width:90%" /><br /><br />
  <button type="button" name="btnGerarIndexacaoProcesso" onclick="infraAbrirBarraProgresso(this.form,'<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_interna=gerar_indexacao_processo')?>', 600, 200);" value="Gerar Indexação de Processos e Documentos" class="infraButton">Gerar Indexação de Processos e Documentos</button><br /><br />

	<hr /><br />

  <button type="button" name="btnGerarIndexacaoPublicacao" onclick="infraAbrirBarraProgresso(this.form,'<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_interna=gerar_indexacao_publicacao')?>', 600, 250);" value="Gerar Indexação Publicações" class="infraButton" style="visibility:visible">Gerar Indexação de Publicações</button><br /><br />

  <hr /><br />

  <button type="button" name="btnGerarIndexacaoBasesConhecimento" onclick="infraAbrirBarraProgresso(this.form,'<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_interna=gerar_indexacao_bases_conhecimento')?>', 600, 200);" value="Gerar Indexação Bases de Conhecimento" class="infraButton" style="visibility:visible">Gerar Indexação de Bases de Conhecimento</button><br /><br />

  <hr /><br />

  <button type="button" name="btnGerarIndexacaoControleInterno" onclick="infraAbrirBarraProgresso(this.form,'<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_interna=gerar_indexacao_controle_interno')?>', 600, 200);" value="Gerar Indexação Controle Interno" class="infraButton" style="visibility:visible">Gerar Indexação Controle Interno</button><br /><br />

  <hr /><br />

  <label class="infraLabelOpcional">Data/Hora inicial:</label><br />
  <input type="text" id="txtDthInicial" name="txtDthInicial" value="<?=PaginaSEI::tratarHTML($_POST['txtDthInicial'])?>" onkeypress="return infraMascara(this, event, '##/##/#### ##:##')" class="infraText" /> (dd/mm/aaaa hh::mm) <br /><br />
  <label class="infraLabelOpcional">Data/Hora final:</label> <br />
  <input type="text" id="txtDthFinal" name="txtDthFinal" value="<?=PaginaSEI::tratarHTML($_POST['txtDthFinal'])?>" onkeypress="return infraMascara(this, event, '##/##/#### ##:##')" class="infraText" /> (dd/mm/aaaa hh::mm) <br /><br />
  <button type="button" name="btnGerarIndexacaoParcial" onclick="infraAbrirBarraProgresso(this.form,'<?=SessaoSEI::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_interna=gerar_indexacao_parcial')?>', 600, 200);" value="Gerar Indexação Parcial de Processos, Documentos e Publicações" class="infraButton">Gerar Indexação Parcial de Processos, Documentos e Publicações</button><br /><br />

  <hr /><br />
  <button type="button" name="btnGerarIndexacaoInterna" onclick="gerarIndexacaoInterna()" value="Gerar Indexação Interna" class="infraButton" style="visibility:visible">Gerar Indexação Interna</button><br /><br />

  <div id="divSinOrgaos" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinOrgaos" name="chkSinOrgaos" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinOrgaos']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <label id="lblSinOrgaos" for="chkSinOrgaos" class="infraLabelCheckbox">Órgãos</label>
  </div>

  <br />

  <div id="divSinUnidades" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinUnidades" name="chkSinUnidades" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinUnidades']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <label id="lblSinUnidades" for="chkSinUnidades" class="infraLabelCheckbox">Unidades</label>
  </div>

  <br />

  <div id="divSinUsuarios" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinUsuarios" name="chkSinUsuarios" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinUsuarios']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <label id="lblSinUsuarios" for="chkSinUsuarios" class="infraLabelCheckbox">Usuários</label>
  </div>

  <br />

  <div id="divSinContatos" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinContatos" name="chkSinContatos" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinContatos']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <label id="lblSinContatos" for="chkSinContatos" class="infraLabelCheckbox">Contatos</label>
  </div>

  <br />

  <div id="divSinAssuntos" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinAssuntos" name="chkSinAssuntos" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAssuntos']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <label id="lblSinAssuntos" for="chkSinAssuntos" class="infraLabelCheckbox">Assuntos</label>
  </div>

  <br />

  <div id="divSinAcompanhamentos" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinAcompanhamentos" name="chkSinAcompanhamentos" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinAcompanhamentos']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <label id="lblSinAcompanhamentos" for="chkSinAcompanhamentos" class="infraLabelCheckbox">Acompanhamentos Especiais</label>
  </div>

  <br />

  <div id="divSinBlocos" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinBlocos" name="chkSinBlocos" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinBlocos']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <label id="lblSinBlocos" for="chkSinBlocos" class="infraLabelCheckbox">Blocos</label>
  </div>

  <br />

  <div id="divSinGruposEmail" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinGruposEmail" name="chkSinGruposEmail" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinGruposEmail']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <label id="lblSinGruposEmail" for="chkSinGruposEmail" class="infraLabelCheckbox">Grupos de E-mail</label>
  </div>

  <br />

  <div id="divSinObservacoes" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinObservacoes" name="chkSinObservacoes" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinObservacoes']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <label id="lblSinObservacoes" for="chkSinObservacoes" class="infraLabelCheckbox">Observações em Protocolos</label>
  </div>

  <br />

  <div id="divSinFavoritos" class="infraDivCheckbox">
    <input type="checkbox" id="chkSinFavoritos" name="chkSinFavoritos" class="infraCheckbox" <?= PaginaSEI::getInstance()->setCheckbox(PaginaSEI::getInstance()->getCheckbox($_POST['chkSinFavoritos']))?> tabindex="<?= PaginaSEI::getInstance()->getProxTabDados() ?>"/>
    <label id="lblSinFavoritos" for="chkSinFavoritos" class="infraLabelCheckbox">Favoritos</label>
  </div>

  <?
  PaginaSEI::getInstance()->fecharAreaDados();
  PaginaSEI::getInstance()->montarAreaDebug();
  //PaginaSEI::getInstance()->montarBarraComandosInferior($arrComandos);
  ?>
</form>
<?
PaginaSEI::getInstance()->fecharBody();
PaginaSEI::getInstance()->fecharHtml();
?>