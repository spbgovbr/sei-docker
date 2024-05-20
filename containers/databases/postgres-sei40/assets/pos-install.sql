\c sei;
ALTER DATABASE sei OWNER TO sei_user;
GRANT ALL PRIVILEGES ON DATABASE sei TO sei_user;
GRANT ALL PRIVILEGES ON SCHEMA public TO sei_user;
GRANT ALL ON ALL TABLES IN SCHEMA public TO sei_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO sei_user;
update orgao set sigla='ABC', descricao='ORGAO ABC' where id_orgao=0;
delete from auditoria_protocolo;

\c sip;
ALTER DATABASE sip OWNER TO sip_user;
GRANT ALL PRIVILEGES ON DATABASE sip TO sip_user;
GRANT ALL PRIVILEGES ON SCHEMA public TO sip_user;
GRANT ALL ON ALL TABLES IN SCHEMA public TO sip_user;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO sip_user;
update orgao set sigla='ABC', descricao='ORGAO ABC' where id_orgao=0;
update sistema set pagina_inicial='http://localhost:8000/sip' where sigla='SIP';
update sistema set pagina_inicial='http://localhost:8000/sei/inicializar.php', web_service='http://localhost:8000/sei/controlador_ws.php?servico=sip' where sigla='SEI';
update orgao set sin_autenticar='N' where id_orgao=0;