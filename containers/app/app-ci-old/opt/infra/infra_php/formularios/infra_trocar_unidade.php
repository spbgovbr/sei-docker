<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 14/09/2020 - criado por mga
 *
 */

try {
  //require_once 'Infra.php';

  session_start();

  //////////////////////////////////////////////////////////////////////////////
  InfraDebug::getInstance()->setBolLigado(false);
  InfraDebug::getInstance()->setBolDebugInfra(true);
  InfraDebug::getInstance()->limpar();
  //////////////////////////////////////////////////////////////////////////////

  SessaoInfra::getInstance()->validarLink();

  //SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

  PaginaInfra::getInstance()->setTipoSelecao(InfraPagina::$TIPO_SELECAO_SIMPLES);

  switch($_GET['acao']){
    case 'infra_trocar_unidade':
      $strTitulo = 'Trocar Unidade';
      break;

    default:
      throw new InfraException("Ação '".$_GET['acao']."' não reconhecida.");
  }

  $arrComandos = array();

  $arrComandos[] = '<button type="submit" accesskey="P" id="sbmPesquisar" name="sbmPesquisar" value="Pesquisar" class="infraButton"><span class="infraTeclaAtalho">P</span>esquisar</button>';

  $strInfraSiglaUnidade = $_POST['txtInfraSiglaUnidade'];
  $strInfraDescricaoUnidade = $_POST['txtInfraDescricaoUnidade'];
  $numIdOrgao = $_POST['selInfraOrgaoUnidade'];

  $arrUnidades = array();
  $arrOrgaos = array();
  if (SessaoInfra::getInstance()!=null && SessaoInfra::getInstance()->getObjInfraSessaoDTO()!=null) {
    $arrUnidades = SessaoInfra::getInstance()->getArrUnidades();

    $arrIdOrgaos = array();
    foreach($arrUnidades as $unidade){
      $arrIdOrgaos[$unidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORGAO]] = true;
    }

    $arrOrgaos = array();
    foreach(SessaoInfra::getInstance()->getArrOrgaos() as $id => $orgao){
      if (isset($arrIdOrgaos[$id])){
        $arrOrgaos[$id] = $orgao;
      }
    }

    if ($strInfraSiglaUnidade!=''){
      $strFiltro = InfraString::prepararIndexacao($strInfraSiglaUnidade);
      $arrTemp = array();
      foreach($arrUnidades as $unidade){
        if (strpos(InfraString::prepararIndexacao($unidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_SIGLA]), $strFiltro)!==false){
          $arrTemp[] = $unidade;
        }
      }
      $arrUnidades = $arrTemp;
    }

    if ($strInfraDescricaoUnidade!=''){

      $strFiltro = InfraString::prepararIndexacao($strInfraDescricaoUnidade);
      $arrPalavrasPesquisa = explode(' ', $strFiltro);
      $numPalavrasPesquisa = count($arrPalavrasPesquisa);

      $arrTemp = array();
      foreach($arrUnidades as $unidade){

        $strDescricaoOrgaoPesquisa = InfraString::prepararIndexacao($unidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_DESCRICAO]);

        for($i=0;$i<$numPalavrasPesquisa;$i++){
          if (strpos($strDescricaoOrgaoPesquisa, $arrPalavrasPesquisa[$i])===false){
            break;
          }
        }

        if ($i == $numPalavrasPesquisa){
          $arrTemp[] = $unidade;
        }
      }
      $arrUnidades = $arrTemp;
    }


    if ($numIdOrgao!=''){
      $arrTemp = array();
      foreach($arrUnidades as $unidade){
        if ($unidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORGAO] == $numIdOrgao){
          $arrTemp[] = $unidade;
        }
      }
      $arrUnidades = $arrTemp;
    }
  }

  $numRegistros = count($arrUnidades);

  if ($numRegistros > 0){

    $strResultado = '';

    $strSumarioTabela = 'Tabela de Unidades com Permissão.';
    $strCaptionTabela = 'Unidades com Permissão';

    $strResultado .= '<table width="99%" class="infraTable" summary="'.$strSumarioTabela.'">'."\n";
    $strResultado .= '<caption class="infraCaption">'.PaginaInfra::getInstance()->gerarCaptionTabela($strCaptionTabela,$numRegistros).'</caption>';
    $strResultado .= '<tr>';
    $strResultado .= '<th class="infraTh" width="1%">'.PaginaInfra::getInstance()->getThCheck().'</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Sigla</th>'."\n";
    $strResultado .= '<th class="infraTh">Descrição</th>'."\n";
    $strResultado .= '<th class="infraTh" width="15%">Órgão</th>'."\n";
    $strResultado .= '</tr>'."\n";
    $strCssTr='';
    $i = 0;

    foreach($arrUnidades as $unidade){

      $numIdUnidade = $unidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID];
      $strSigla = $unidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_SIGLA];
      $strDescricao = $unidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_DESCRICAO];
      $orgao = $arrOrgaos[$unidade[InfraSip::$WS_LOGIN_PERMISSAO_UNIDADES_ID_ORGAO]];
      $strSiglaOrgao = $orgao[InfraSip::$WS_LOGIN_ORGAO_SIGLA];
      $strDescricaoOrgao = $orgao[InfraSip::$WS_LOGIN_ORGAO_DESCRICAO];

      $strCssTr = ($strCssTr=='<tr class="infraTrClara">')?'<tr class="infraTrEscura">':'<tr class="infraTrClara">';
      $strResultado .= $strCssTr;

      $strValor = 'N';
      if (SessaoInfra::getInstance()->getNumIdUnidadeAtual() == $numIdUnidade){
        $strValor = 'S';
      }

      $strResultado .= '<td align="center">'.PaginaInfra::getInstance()->getTrCheck($i++,$numIdUnidade,$strSigla,$strValor,'Infra','onclick="selecionarUnidade('.$numIdUnidade.')"').'</td>';
      $strResultado .= '<td align="center">'.PaginaInfra::getInstance()->tratarHTML($strSigla).'</td>';
      $strResultado .= '<td>'.PaginaInfra::getInstance()->tratarHTML($strDescricao).'</td>';
      $strResultado .= '<td align="center"><a alt="'.PaginaInfra::getInstance()->tratarHTML($strDescricaoOrgao).'" title="'.PaginaInfra::getInstance()->tratarHTML($strDescricaoOrgao).'" class="ancoraOrgao">'.PaginaInfra::getInstance()->tratarHTML($strSiglaOrgao).'</a></td>';

      $strResultado .= '</tr>'."\n";
    }
    $strResultado .= '</table>';
  }

  //$arrComandos[] = '<button type="button" accesskey="F" id="btnFechar" value="Fechar" onclick="location.href=\''.SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.PaginaInfra::getInstance()->getAcaoRetorno().'&acao_origem='.$_GET['acao']).'\'" class="infraButton"><span class="infraTeclaAtalho">F</span>echar</button>';

  $arr = array();
  foreach($arrOrgaos as $orgao){
    $arr[$orgao[InfraSip::$WS_LOGIN_ORGAO_ID]] = $orgao[InfraSip::$WS_LOGIN_ORGAO_SIGLA];
  }
  $strItensSelInfraOrgaoUnidade = InfraINT::montarSelectArray('','Todos',$numIdOrgao,$arr);

}catch(Exception $e){
  PaginaInfra::getInstance()->processarExcecao($e);
}

PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema().' - '.$strTitulo);
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>

tr.infraTrClara, tr.infraTrEscura{
 cursor:pointer;
}

a.ancoraOrgao{
  text-decoration:none;
  font-size:1em;
}

a.ancoraOrgao:hover{
  text-decoration:underline;
}

#lblInfraSiglaUnidade {position:absolute;left:0%;top:0%;}
#txtInfraSiglaUnidade {position:absolute;left:0%;top:40%;width:20%;}

#lblInfraDescricaoUnidade {position:absolute;left:25%;top:0%;}
#txtInfraDescricaoUnidade {position:absolute;left:25%;top:40%;width:45%;}

#lblInfraOrgaoUnidade {position:absolute;left:75%;top:0%;}
#selInfraOrgaoUnidade {position:absolute;left:75%;top:40%;width:20%;}

<?
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->abrirJavaScript();
?>

function inicializar(){
  document.getElementById('txtInfraSiglaUnidade').focus();
  infraEfeitoImagens();
  infraEfeitoTabelas(true);
}

function validarForm(){
  return true;
}

function selecionarUnidade(idUnidade){
  var frm = document.getElementById('frmInfraSelecaoUnidade');
  var input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'selInfraUnidades';
  input.value = idUnidade;
  frm.appendChild(input);
  frm.submit();
}

<?
PaginaInfra::getInstance()->fecharJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo,'onload="inicializar();"');
?>
  <form id="frmInfraSelecaoUnidade" method="post"  onsubmit="return validarForm();"  action="<?=SessaoInfra::getInstance()->assinarLink('controlador.php?acao='.$_GET['acao'].'&acao_origem='.$_GET['acao'])?>">
    <?
    PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
    PaginaInfra::getInstance()->abrirAreaDados('5em');
    ?>
    <label id="lblInfraSiglaUnidade" for="txtInfraSiglaUnidade" class="infraLabelOpcional">Sigla:</label>
    <input type="text" id="txtInfraSiglaUnidade" name="txtInfraSiglaUnidade" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strInfraSiglaUnidade)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

    <label id="lblInfraDescricaoUnidade" for="txtInfraDescricaoUnidade" class="infraLabelOpcional">Descrição:</label>
    <input type="text" id="txtInfraDescricaoUnidade" name="txtInfraDescricaoUnidade" class="infraText" value="<?=PaginaInfra::getInstance()->tratarHTML($strInfraDescricaoUnidade)?>" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>" />

    <label id="lblInfraOrgaoUnidade" for="selInfraOrgaoUnidade" accesskey="" class="infraLabelObrigatorio">Órgão:</label>
    <select id="selInfraOrgaoUnidade" name="selInfraOrgaoUnidade" class="infraSelect" onchange="this.form.submit()" tabindex="<?=PaginaInfra::getInstance()->getProxTabDados()?>">
      <?=$strItensSelInfraOrgaoUnidade?>
    </select>

    <?
    PaginaInfra::getInstance()->fecharAreaDados();

    PaginaInfra::getInstance()->montarAreaTabela($strResultado,$numRegistros);
    //PaginaInfra::getInstance()->montarAreaDebug();
    PaginaInfra::getInstance()->montarBarraComandosInferior($arrComandos);
    ?>
  </form>

<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
?>