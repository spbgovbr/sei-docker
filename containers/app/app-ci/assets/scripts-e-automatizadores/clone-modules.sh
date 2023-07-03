#!/bin/bash

mkdir -p /sei-modulos

cd /sei-modulos

git clone https://github.com/supergovbr/mod-sei-estatisticas.git
git clone https://github.com/supergovbr/mod-sei-pen.git
git clone https://github.com/supergovbr/mod-wssei.git
git clone https://github.com/anatelgovbr/mod-sei-peticionamento.git peticionamento
git clone https://github.com/supergovbr/mod-sei-resposta.git
git clone https://${GITUSER_REPO_MODULOS}:${GITPASS_REPO_MODULOS}@github.com/supergovbr/mod-gestao-documental.git
git clone https://${GITUSER_REPO_MODULOS}:${GITPASS_REPO_MODULOS}@github.com/supergovbr/mod-sei-loginunico.git
git clone https://${GITUSER_REPO_MODULOS}:${GITPASS_REPO_MODULOS}@github.com/supergovbr/mod-sei-assinatura-avancada.git
