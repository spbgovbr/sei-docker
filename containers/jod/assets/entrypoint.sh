#!/usr/bin/env sh

/usr/bin/soffice --headless --nologo --nofirststartwizard --accept="socket,host=127.0.0.1,port=8100;urp" & > /dev/null 2>&1
/opt/jodconverter-tomcat-2.2.2/bin/catalina.sh run
