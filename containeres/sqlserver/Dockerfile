#imagem retirada e adaptada originalmente de https://github.com/spbgovbr/sei-vagrant
FROM microsoft/mssql-server-linux

ENV TERM xterm
ENV ACCEPT_EULA=Y
ENV SA_PASSWORD='yourStrong(!)Password'

ADD https://raw.githubusercontent.com/spbgovbr/sei-db-ref-executivo/master/sqlserver/v4.0.0/sei_4_0_0_BD_Ref_Exec.bak /tmp/sei_sqlserver.bak
ADD https://raw.githubusercontent.com/spbgovbr/sei-db-ref-executivo/master/sqlserver/v4.0.0/sip_4_0_0_BD_Ref_Exec.bak /tmp/sip_sqlserver.bak
COPY assets/restore.sql /tmp/restore.sql
COPY install.sh /install.sh

RUN bash /install.sh
