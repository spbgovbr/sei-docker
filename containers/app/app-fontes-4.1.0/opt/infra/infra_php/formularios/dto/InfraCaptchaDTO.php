<?
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 *
 * 30/04/2021 - criado por mgb29
 *
 * Versão do Gerador de Código: 1.43.0
 */

//require_once dirname(__FILE__).'/../Infra.php';

class InfraCaptchaDTO extends InfraDTO
{

    public function getStrNomeTabela()
    {
        return 'infra_captcha';
    }

    public function montar()
    {
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Dia', 'dia');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Mes', 'mes');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_NUM, 'Ano', 'ano');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_STR, 'Identificacao', 'identificacao');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'Acertos', 'acertos');
        $this->adicionarAtributoTabela(InfraDTO::$PREFIXO_DBL, 'Erros', 'erros');

        $this->adicionarAtributo(InfraDTO::$PREFIXO_STR, 'Data');

        $this->configurarPK('Dia', InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarPK('Mes', InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarPK('Ano', InfraDTO::$TIPO_PK_INFORMADO);
        $this->configurarPK('Identificacao', InfraDTO::$TIPO_PK_INFORMADO);
    }
}
