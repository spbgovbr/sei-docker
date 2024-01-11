function infraCriarCookie(nome,valor,dias) {
  
  var expires = '';
  
	if (dias) {
		var date = new Date();
		date.setTime(date.getTime()+(dias*24*60*60*1000));
		expires = ";expires=" + date.toGMTString();
	}
	
  document.cookie = nome + '=' + escape(valor) + expires + ';SameSite=Lax;path=/';
  
}

function infraLerCookie(nome) {
	var nameEQ = nome + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function infraRemoverCookie(nome) {
	infraCriarCookie(nome,"",-1);
}

function infraCookiesHabilitados(){
  
  infraCriarCookie('infra_cookie','teste',1);
  
  if (infraLerCookie('infra_cookie')!=null){
    infraRemoverCookie('infra_cookie');
    return true;
  }
  
  return false;  
}

function infraObterNomeCookiePrivado(nome){
  return document.getElementById('hdnInfraPrefixoCookie').value+'_'+nome;
}

function infraCriarCookiePrivado(nome,valor,dias) {
  infraCriarCookie(infraObterNomeCookiePrivado(nome),valor,dias);
}

function infraLerCookiePrivado(nome){
  return infraLerCookie(infraObterNomeCookiePrivado(nome));
}

function infraRemoverCookiePrivado(nome){
  infraRemoverCookie(infraObterNomeCookiePrivado(nome));
}
function infraProcessarCookieForm(idForm){
	$('#'+idForm+' select, #'+idForm+' input').each(function(){
		$(this).change(function(){
			infraRemoverCookie($(this).attr('id'));
			if($(this).attr('type') == 'checkbox'){
				if($(this).is(':checked')){
					infraCriarCookie($(this).attr('id'), $(this).val(), 365);
				}
			} else {
				infraCriarCookie($(this).attr('id'), $(this).val(), 365);
			}
		});
		var valor = unescape(infraLerCookie($(this).attr('id')));
		if(valor != 'null'){
			if($(this).attr('type') == 'checkbox'){
				if(valor == 'on') {
					$(this).attr('checked', 'checked');
				}
			} else {
				$(this).val(valor);
			}
		}
	});
}
