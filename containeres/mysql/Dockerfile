#imagem retirada e adaptada originalmente de https://github.com/spbgovbr/sei-vagrant
FROM mysql:5.7 as builder

ENV MYSQL_ROOT_PASSWORD=root

COPY assets/my.cnf /etc/mysql/mysql.conf.d/sei.cnf
COPY assets/pre-install.sql /docker-entrypoint-initdb.d/1_pre-install.sql
ADD https://raw.githubusercontent.com/spbgovbr/sei-db-ref-executivo/master/mysql/v4.0.0/sei_4_0_0_BD_Ref_Exec.sql /docker-entrypoint-initdb.d/2_sei_4_0_0_BD_Ref_Exec.sql
ADD https://raw.githubusercontent.com/spbgovbr/sei-db-ref-executivo/master/mysql/v4.0.0/sip_4_0_0_BD_Ref_Exec.sql /docker-entrypoint-initdb.d/3_sip_4_0_0_BD_Ref_Exec.sql
COPY assets/pos-install.sql /docker-entrypoint-initdb.d/9_pos-install.sql

RUN chmod 644 /etc/mysql/mysql.conf.d/sei.cnf && \
    chmod 644 /docker-entrypoint-initdb.d/*
RUN sed -i '1i use sei; \n' /docker-entrypoint-initdb.d/2_sei_4_0_0_BD_Ref_Exec.sql
RUN sed -i '1i use sip; \n' /docker-entrypoint-initdb.d/3_sip_4_0_0_BD_Ref_Exec.sql

RUN ["sed", "-i", "s/exec \"$@\"/echo \"not running $@\"/", "/usr/local/bin/docker-entrypoint.sh"]
RUN ["/usr/local/bin/docker-entrypoint.sh", "mysqld", "--datadir", "/initialized-db"]



FROM mysql:5.7
COPY --from=builder /initialized-db /var/lib/mysql
