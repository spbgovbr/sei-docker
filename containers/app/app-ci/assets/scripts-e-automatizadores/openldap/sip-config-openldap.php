<?php
    
require_once '/opt/sip/web/Sip.php';

$c = BancoSip::getInstance();
$c->abrirConexao();

$objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();
$objServidorAutenticacaoBD = new ServidorAutenticacaoBD(BancoSip::getInstance());

$objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();
$objServidorAutenticacaoDTO->setStrNome('MeuOpenLdap');
$qtd = $objServidorAutenticacaoBD->contar($objServidorAutenticacaoDTO);

if($qtd){
    echo "Servidor MeuOpenLdap ja cadastrado no SIP. Qualquer alteracao devera ser feita diretamente na tela do SIP\n";
    echo "Caso tenha problema ao logar, como esquecimento de senha, sete a VAR OPENLDAP_DESLIGAR_NO_ORGAO_0=true e OPENLDAP_PRESENTE=false\n";
    echo "Isso vai forcar o instalador a desligar o Ldap no Orgao 0\n";
    
}else{
    
    $objServidorAutenticacaoBD = new ServidorAutenticacaoBD(BancoSip::getInstance());
    $objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();
    $objServidorAutenticacaoDTO->setNumIdServidorAutenticacao(null);
    $objServidorAutenticacaoDTO->setStrNome("MeuOpenLdap");
    $objServidorAutenticacaoDTO->setStrStaTipo('LDAP');
    $objServidorAutenticacaoDTO->setStrEndereco('openldap');
    $objServidorAutenticacaoDTO->setNumPorta(389);
    $objServidorAutenticacaoDTO->setStrSufixo(null);
    $objServidorAutenticacaoDTO->setStrUsuarioPesquisa('cn=admin,dc=pen,dc=gov,dc=br');
    $objServidorAutenticacaoDTO->setStrSenhaPesquisa(getenv('OPENLDAP_ADMIN_PASSWORD'));
    $objServidorAutenticacaoDTO->setStrContextoPesquisa('dc=pen,dc=gov,dc=br');
    $objServidorAutenticacaoDTO->setStrAtributoFiltroPesquisa('uid');
    $objServidorAutenticacaoDTO->setStrAtributoRetornoPesquisa('distinguishedName');
    $objServidorAutenticacaoDTO->setNumVersao(3);
    $ret = $objServidorAutenticacaoBD->cadastrar($objServidorAutenticacaoDTO);
    
    echo "Servidor MeuOpenLdap Cadastrado no SIP com sucesso!!!\n";
    
    echo "Vamos agora associar o servidor ao Orgao 0\n";
        
    echo "Apagar associacoes do orgao 0\n";
    
    $objRelOrgaoAutenticacaoDTO = new RelOrgaoAutenticacaoDTO();
    $objRelOrgaoAutenticacaoDTO->retNumIdOrgao();
    $objRelOrgaoAutenticacaoDTO->retNumIdServidorAutenticacao();
    $objRelOrgaoAutenticacaoDTO->setNumIdOrgao(0);

    $objRelOrgaoAutenticacaoRN = new RelOrgaoAutenticacaoRN();
    $r = $objRelOrgaoAutenticacaoRN->listar($objRelOrgaoAutenticacaoDTO);

    $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD(BancoSip::getInstance());
    for($i=0;$i<count($r);$i++){
        $objRelOrgaoAutenticacaoBD->excluir($r[$i]);
    }
    
    echo "Cadastrar associcao\n";
        

    $objServidorAutenticacaoDTO = new ServidorAutenticacaoDTO();
    $objServidorAutenticacaoDTO->retNumIdServidorAutenticacao();
    $objServidorAutenticacaoDTO->setStrNome('MeuOpenLdap');
    
    $objServidorAutenticacaoBD = new ServidorAutenticacaoBD(BancoSip::getInstance());
    $ret = $objServidorAutenticacaoBD->consultar($objServidorAutenticacaoDTO);
    
    $objRelOrgaoAutenticacaoBD = new RelOrgaoAutenticacaoBD(BancoSip::getInstance());
    $objRelOrgaoAutenticacaoDTO = new RelOrgaoAutenticacaoDTO();
    $objRelOrgaoAutenticacaoDTO->setNumIdOrgao(0);
    $objRelOrgaoAutenticacaoDTO->setNumIdServidorAutenticacao($ret->getNumIdServidorAutenticacao());
    $objRelOrgaoAutenticacaoDTO->setNumSequencia(0);
    $ret = $objRelOrgaoAutenticacaoBD->cadastrar($objRelOrgaoAutenticacaoDTO);
    
    echo "Vamos agora ativar o Orgao Zero para autenticar\n";
        
    $objOrgaoDTO = new OrgaoDTO();
    $objOrgaoDTO->setNumIdOrgao(0);
    $objOrgaoDTO->setStrSinAutenticar('S');
    $objOrgaoBD = new OrgaoBD(BancoSip::getInstance());
    $objOrgaoBD->alterar($objOrgaoDTO);    
    
    
}
    
?>