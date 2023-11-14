<?
/**
* TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
*
* 20/05/2019 - criado por cjy
*
* Versão do Gerador de Código: 1.42.0
*/

require_once dirname(__FILE__).'/../SEI.php';

class AndamentoInstalacaoINT extends InfraINT {

  public static function montarDescricao($strNomeTarefaInstalacao, array $arrObjAtributoInstalacao){
    if(InfraArray::contar($arrObjAtributoInstalacao) > 0) {
      foreach ($arrObjAtributoInstalacao as $objAtributoInstalacaoDTO) {
        if (strpos($objAtributoInstalacaoDTO->getStrValor(), '¥') !== false) {
          $arrValor = explode('¥', $objAtributoInstalacaoDTO->getStrValor());
          $strSubstituicao = '<a href="javascript:void(0);" alt="' . $arrValor[1] . '" title="' . $arrValor[1] . '" class="ancoraSigla">' . $arrValor[0] . '</a>';
        } else {
          $strSubstituicao = $objAtributoInstalacaoDTO->getStrValor();
        }
        $strNomeTarefaInstalacao = str_replace('@' . $objAtributoInstalacaoDTO->getStrNome() . '@', $strSubstituicao, $strNomeTarefaInstalacao);
      }
    }
    return $strNomeTarefaInstalacao;
  }
}
