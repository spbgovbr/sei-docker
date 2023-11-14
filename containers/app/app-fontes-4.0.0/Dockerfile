FROM ubi8/ubi-minimal:latest

LABEL io.k8s.display-name="SEI - Imagem para cópia dos arquivos-fonte" \
      io.k8s.description="Imagem para cópia dos arquivos-fonte do SEI." \
      maintainer="Sandro Tanaka <tanaka@redhat.com>" \
      vendor="Red Hat Inc" \
      sei-version="4.0.0"

COPY opt /opt

#ADD opt /tmp/opt

CMD ["ls /opt"]