#!/bin/bash

export APP_DB_PORTA_BASE64=$(echo -n "$APP_DB_PORTA" | base64)
export APP_DB_ROOT_PASSWORD_BASE64=$(echo -n "$APP_DB_ROOT_PASSWORD" | base64)
export APP_DB_ROOT_USERNAME_BASE64=$(echo -n "$APP_DB_ROOT_USERNAME" | base64)
export APP_DB_SEI_BASE_BASE64=$(echo -n "$APP_DB_SEI_BASE" | base64)
export APP_DB_SEI_PASSWORD_BASE64=$(echo -n "$APP_DB_SEI_PASSWORD" | base64)
export APP_DB_SEI_USERNAME_BASE64=$(echo -n "$APP_DB_SEI_USERNAME" | base64)
export APP_DB_SIP_BASE_BASE64=$(echo -n "$APP_DB_SIP_BASE" | base64)
export APP_DB_SIP_PASSWORD_BASE64=$(echo -n "$APP_DB_SIP_PASSWORD" | base64)
export APP_DB_SIP_USERNAME_BASE64=$(echo -n "$APP_DB_SIP_USERNAME" | base64)
export APP_MAIL_AUTENTICAR_BASE64=$(echo -n "$APP_MAIL_AUTENTICAR" | base64)
export APP_MAIL_PORTA_BASE64=$(echo -n "$APP_MAIL_PORTA" | base64)
export APP_MAIL_SEGURANCA_BASE64=$(echo -n "$APP_MAIL_SEGURANCA" | base64)
export APP_MAIL_SENHA_BASE64=$(echo -n "$APP_MAIL_SENHA" | base64)
export APP_MAIL_USUARIO_BASE64=$(echo -n "$APP_MAIL_USUARIO" | base64)
export APP_SEI_CHAVE_ACESSO_BASE64=$(echo -n "$APP_SEI_CHAVE_ACESSO" | base64)
export APP_SIP_CHAVE_ACESSO_BASE64=$(echo -n "$APP_SIP_CHAVE_ACESSO" | base64)

envsubst < orquestrators/rancher-kubernetes/templates/secrets-template.yaml > orquestrators/rancher-kubernetes/topublish/secrets.yaml