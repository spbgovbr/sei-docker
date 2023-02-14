#!/bin/bash

mkdir -p /sei-modulos

cd /sei-modulos

git clone https://github.com/spbgovbr/mod-sei-estatisticas.git
git clone https://github.com/spbgovbr/mod-wssei.git
git clone https://github.com/spbgovbr/mod-sei-resposta.git
git clone https://${GITUSER_REPO_MODULOS}:${GITPASS_REPO_MODULOS}@github.com/spbgovbr/mod-gestao-documental.git
git clone https://${GITUSER_REPO_MODULOS}:${GITPASS_REPO_MODULOS}@github.com/spbgovbr/mod-sei-loginunico.git
git clone https://${GITUSER_REPO_MODULOS}:${GITPASS_REPO_MODULOS}@github.com/spbgovbr/mod-sei-assinatura-avancada.git