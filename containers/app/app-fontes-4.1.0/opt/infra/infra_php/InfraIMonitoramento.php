<?php
/**
 * TRIBUNAL REGIONAL FEDERAL DA 4ª REGIÃO
 * 30/05/2016 - CRIADO POR cle@trf4.jus.br
 * @package infra_php
 */

interface InfraIMonitoramento
{

    public function getServidor();

    public function getUsuario();

    public function getSenha();

    public function listarHosts();

    public function listarGrupos();

    public function listarAlertas();

}

