#imagem retirada e adaptada originalmente de https://github.com/spbgovbr/sei-vagrant
FROM oracleinanutshell/oracle-xe-11g

ENV TERM xterm
ENV ORACLE_ALLOW_REMOTE=true
ENV NLS_LANG=PORTUGUESE_BRAZIL.WE8MSWIN1252

ADD https://raw.githubusercontent.com/spbgovbr/sei-db-ref-executivo/master/oracle/v4.0.0/sei_4_0_0_BD_Ref_Exec.dmp /tmp/sei_oracle.dmp
ADD https://raw.githubusercontent.com/spbgovbr/sei-db-ref-executivo/master/oracle/v4.0.0/sip_4_0_0_BD_Ref_Exec.dmp /tmp/sip_oracle.dmp

COPY assets/pre-install.sql /tmp/pre-install.sql
COPY assets/sei-config.sql /tmp/sei-config.sql
COPY assets/sip-config.sql /tmp/sip-config.sql
COPY install.sh /install.sh


RUN bash /install.sh
