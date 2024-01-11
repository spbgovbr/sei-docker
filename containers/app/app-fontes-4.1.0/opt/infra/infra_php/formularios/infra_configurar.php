<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 07/08/2009 - criado por mga
 *
 * Versão do Gerador de Código: 1.27.1
 *
 * Versão no CVS: $Id$
 */

try {
    //require_once 'Infra.php';

    session_start();

    //////////////////////////////////////////////////////////////////////////////
    //InfraDebug::getInstance()->setBolLigado(false);
    //InfraDebug::getInstance()->setBolDebugInfra(true);
    //InfraDebug::getInstance()->limpar();
    //////////////////////////////////////////////////////////////////////////////

    SessaoInfra::getInstance()->validarLink();

    PaginaInfra::getInstance()->verificarSelecao('infra_configurar_selecionar');

    //SessaoInfra::getInstance()->validarPermissao($_GET['acao']);

    $arrComandos = array();

    switch ($_GET['acao']) {
        case 'infra_configurar':
            $strTitulo = 'Configurações';

            //$arrComandos[] = '<button type="button" name="btnAplicar" value="Aplicar" onclick="this.form.submit();" class="infraButton" />';
            $arrComandos[] = '<button type="button" value="Fechar" onclick="document.getElementById(\'divInfraBarraLocalizacao\').innerHTML=\'\';document.getElementById(\'divInfraAreaTelaD\').innerHTML=\'\';" class="infraButton" >Fechar</button>';

            if (isset($_POST['selInfraCores'])) {
                if (SessaoInfra::getInstance()->getObjInfraIBanco()) {
                    $objInfraDadoUsuario = new InfraDadoUsuario(SessaoInfra::getInstance());
                    $objInfraDadoUsuario->setValor('INFRA_ESQUEMA_CORES', $_POST['selInfraCores']);
                }

                SessaoInfra::getInstance()->setAtributo('infra_esquema_cores', $_POST['selInfraCores']);
            }

            break;

        default:
            throw new InfraException("Ação '" . $_GET['acao'] . "' não reconhecida.");
    }
} catch (Exception $e) {
    PaginaInfra::getInstance()->processarExcecao($e);
}
PaginaInfra::getInstance()->montarDocType();
PaginaInfra::getInstance()->abrirHtml();
PaginaInfra::getInstance()->abrirHead();
PaginaInfra::getInstance()->montarMeta();
PaginaInfra::getInstance()->montarTitle(PaginaInfra::getInstance()->getStrNomeSistema());
PaginaInfra::getInstance()->montarStyle();
PaginaInfra::getInstance()->abrirStyle();
?>
    #lblInfraCores {position:absolute;left:0%;top:0%;width:50%;}
    #selInfraCores {position:absolute;left:0%;top:40%;width:50%;}
<?php
PaginaInfra::getInstance()->fecharStyle();
PaginaInfra::getInstance()->montarJavaScript();
PaginaInfra::getInstance()->fecharHead();
PaginaInfra::getInstance()->abrirBody($strTitulo);
?>
    <form id="frmInfraConfigurar" method="post">
        <?php
        PaginaInfra::getInstance()->montarBarraComandosSuperior($arrComandos);
        PaginaInfra::getInstance()->abrirAreaDados('5em');
        $strEsquema = PaginaInfra::getInstance()->getStrEsquemaCores();
        ?>
        <label id="lblInfraCores" for="selInfraCores" accesskey="E" class="infraLabelOpcional"><span
                class="infraTeclaAtalho">E</span>squema de Cores:</label>
        <br/>
        <select id="selInfraCores" name="selInfraCores"
                onchange="infraEsquemaCoresSistema(this.value);this.form.submit();" class="infraSelect"
                tabindex="<?= PaginaInfra::getInstance()->getProxTabDados() ?>'">
            <?= InfraINT::montarSelectArray(null, null, $strEsquema, PaginaInfra::getInstance()->listarEsquemas()) ?>
        </select>
        <?
        PaginaInfra::getInstance()->fecharAreaDados();
        ?>
    </form>
<?
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
