#!/bin/sh

for fl in *.php
do
echo "$fl"
mv "$fl" "$fl.old"
sed -f - "$fl.old" > "$fl" <<_EoF_
s/"imagens\/alterar.gif/"'.Pagina$1::getDiretorioImagensGlobal().'\/alterar.gif/g
s/"imagens\/calendario.gif/"<?=Pagina$1::getDiretorioImagensGlobal()?>\/calendario.gif/g
s/"imagens\/consultar.gif/"'.Pagina$1::getDiretorioImagensGlobal().'\/consultar.gif/g
s/"imagens\/desativar.gif/"'.Pagina$1::getDiretorioImagensGlobal().'\/desativar.gif/g
s/"imagens\/excluir.gif/"'.Pagina$1::getDiretorioImagensGlobal().'\/excluir.gif/g
s/"imagens\/reativar.gif/"'.Pagina$1::getDiretorioImagensGlobal().'\/reativar.gif/g
_EoF_

done

