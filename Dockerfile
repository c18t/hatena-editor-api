FROM alpine

# アプリフォルダ
ENV APP_DIR /var/opt/app

# Listen port(CMD実行時に参照)
ENV PORT 8000

# 作業ディレクトリ
WORKDIR ${APP_DIR}

# ローカルのソースファイルをコピー
COPY . ${APP_DIR}

RUN set -x && \
: "依存するパッケージをインストール" && \
  apk update && \
  apk --update add \
    bash \
    wget \
    curl \
    git \
    php \
    php-curl \
    php-json \
    php-mbstring \
    php-openssl \
    php-phar \
    php-dom \
    ruby && \
    rm /var/cache/apk/* && \
: "AsciiDoctorをインストール(RDocで失敗するけど無視)" && \
  gem install asciidoctor; \
: "Composerをインストール" && \
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer && \
: "アプリをインストール" && \
  composer install && \
: "hatenaユーザーを作成" && \
  adduser -D -s /bin/bash hatena && \
: "アプリディレクトリの所有者を変更" && \
  chown -R hatena:hatena ${APP_DIR}

# ユーザーを変更
USER hatena

# 公開ポート
EXPOSE ${PORT}

# アプリを実行
ENTRYPOINT [ "/bin/bash", "-c" ]
CMD [ "php -S $(ip address show dev eth0 | awk '/inet/ { print substr($2, 0, match($2, /\\//)-1) }'):${PORT} -t public/" ]