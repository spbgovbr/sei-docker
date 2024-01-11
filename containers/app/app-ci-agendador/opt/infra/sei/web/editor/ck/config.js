/*
 Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
*/
CKEDITOR.editorConfig=function(a){a.language="pt-br";a.skin="moonocolor";a.autoGrow_minHeight=10;a.autoGrow_onStartup=!0;a.dialog_noConfirmCancel=!0;a.scayt_sLang="pt_BR";a.defaultLanguage="pt-br";a.sharedSpaces={top:"divComandos"};a.scayt_autoStartup=!0;a.scayt_uiTabs="0,0,0";a.linkShowAdvancedTab=!1;a.linkShowTargetTab=!1};
CKEDITOR.on("dialogDefinition",function(a){"image"==a.data.name&&(a=a.data.definition,a.removeContents("Link"),a.removeContents("advanced"),a.minHeight=200,a.minWidth=250,a=a.getContents("info"),a.get("ratioLock").style="margin-top:20px;width:40px;height:40px;",a.get("txtUrl").hidden=!0,a.get("txtAlt").hidden=!0,a.get("htmlPreview").hidden=!0,a.remove("txtHSpace"),a.remove("txtVSpace"),a.remove("cmbAlign"))});