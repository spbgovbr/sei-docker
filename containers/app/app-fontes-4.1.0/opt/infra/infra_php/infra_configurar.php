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

            //$arrComandos[] = '<input type="button" name="btnAplicar" value="Aplicar" onclick="this.form.submit();" class="infraButton" />';
            $arrComandos[] = '<input type="button" value="Fechar" onclick="document.getElementById(\'divInfraBarraLocalizacao\').innerHTML=\'\';document.getElementById(\'divInfraAreaTelaD\').innerHTML=\'\';" class="infraButton" />';

            /*
            if (isset($_POST['btnAplicar'])) {
              try{
              }catch(Exception $e){
                PaginaInfra::getInstance()->processarExcecao($e);
              }
            }
            */
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
            <option value="azul_celeste" <?= $strEsquema == 'azul_celeste' ? ' selected="selected" ' : '' ?>>Azul
                Celeste
            </option>
            <option value="cereja" <?= $strEsquema == 'cereja' ? ' selected="selected" ' : '' ?>>Cereja</option>
            <option value="verde_limao" <?= $strEsquema == 'verde_limao' ? ' selected="selected" ' : '' ?>>Verde Limão
            </option>
            <option value="vermelho" <?= $strEsquema == 'vermelho' ? ' selected="selected" ' : '' ?>>Vermelho</option>
        </select>
        <?php
        PaginaInfra::getInstance()->fecharAreaDados();
        ?>
    </form>
<?php
PaginaInfra::getInstance()->fecharBody();
PaginaInfra::getInstance()->fecharHtml();
