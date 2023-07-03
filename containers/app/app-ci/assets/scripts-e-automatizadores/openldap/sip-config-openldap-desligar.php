<?php
    
require_once '/opt/sip/web/Sip.php';

$c = BancoSip::getInstance();
$c->abrirConexao();

        
$objOrgaoDTO = new OrgaoDTO();
$objOrgaoDTO->setNumIdOrgao(0);
$objOrgaoDTO->setStrSinAutenticar('N');
$objOrgaoBD = new OrgaoBD(BancoSip::getInstance());
$objOrgaoBD->alterar($objOrgaoDTO);    
    
?>