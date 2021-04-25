#!/usr/bin/env bash
set -e

# Variáveis de ambiente
export ACCEPT_EULA=Y
export SA_PASSWORD='yourStrong(!)Password'

# Instalação do FreeTDS para acesso ao SQL Server
apt-get -y --allow-unauthenticated update
apt-get -y --allow-unauthenticated install libodbc1 freetds-dev freetds-bin

/opt/mssql/bin/sqlservr &
sleep 30

tsql -S localhost -U sa -P 'yourStrong(!)Password' < /tmp/restore.sql

# Remover arquivos temporários
rm -rf /tmp/*

exit 0
