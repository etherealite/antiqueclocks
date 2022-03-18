#  contains dependencies and config shared by all stages
FROM wordpress:apache as shared

ENV webroot /usr/src/wordpress
# install globally persistant dependencies
RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		less mariadb-client \
	; \
	rm -rf /var/lib/apt/lists/*

COPY --from=wordpress:cli /usr/local/bin/wp /usr/local/bin/wp

RUN set -eux; \
	# replace the web root from the apache config files
    find /etc/apache2 -name '*.conf' -type f -exec sed -ri -e "s!/var/www/html!$webroot!g" -e "s!Directory /var/www/!Directory $webroot!g" '{}' +; \
	# change the binded port in apache config and v-hosts
    sed -ri -e "s/Listen 80/Listen 8080/g" /etc/apache2/ports.conf; \
    sed -ri -e 's/VirtualHost \*:80/VirtualHost \*:8080/g' /etc/apache2/sites-available/000-default.conf; \
	# remove bundled themes and plugins
	rm -rf $webroot/wp-content



# contains dependencies shared by development environment stage and builder stage
FROM shared as build-deps

# install PHP composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

RUN set -eux; \
	apt-get update; \
	apt-get install -y --no-install-recommends \
		git \
	; \
    rm -rf /var/lib/apt/lists/*;



# local development environment image
FROM build-deps as dev-env

ARG package_list="apt-utils \
        openssh-client \
        gnupg2 \
        dirmngr \
        iproute2 \
        procps \
        lsof \
        htop \
        net-tools \
        psmisc \
        curl \
        wget \
        rsync \
        ca-certificates \
        unzip \
        zip \
        nano \
        vim-tiny \
        less \
        jq \
        lsb-release \
        apt-transport-https \
        dialog \
        libc6 \
        libgcc1 \
        libkrb5-3 \
        libgssapi-krb5-2 \
        libicu[0-9][0-9] \
        liblttng-ust0 \
        libstdc++6 \
        zlib1g \
        locales \
        sudo \
        ncdu \
        man-db \
        strace \
        manpages \
        manpages-dev \
        init-system-helpers"


RUN set -eux; \
    # install development system packages
	apt-get update; \
	apt-get install -y --no-install-recommends \
		bindfs ${package_list} \
	; \
    rm -rf /var/lib/apt/lists/*; \
    # install and configure xdebug
    pecl install xdebug; \
    docker-php-ext-enable xdebug; \
    touch /var/log/xdebug; \
    chown www-data:www-data /var/log/xdebug; \
    { \
        echo 'xdebug.mode=debug'; \
        echo 'xdebug.start_with_request=yes'; \
        echo 'xdebug.connect_timeout_ms=60'; \
    } >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
    # let www-data be a login shell
    mkdir -p /home/www-data && chown www-data:www-data /home/www-data; \
    usermod --shell /bin/bash -d /home/www-data www-data; \
    echo 'www-data ALL=(ALL) NOPASSWD: ALL' >> /etc/sudoers; \
    # move upstream image source code out of the web root
    mv /usr/src/wordpress /usr/local/src/wordpress; \
    rm /usr/src/*; \
    # create self signed cert for local development only
	mkdir -p /usr/local/etc/certs; \
	printf "[dn]\nCN=localhost\n[req]\ndistinguished_name = dn\n[EXT]\nsubjectAltName=DNS:localhost\nkeyUsage=digitalSignature\nextendedKeyUsage=serverAuth" \
		| openssl req -x509 -out /usr/local/etc/certs/localhost.crt -keyout /usr/local/etc/certs/localhost.key \
			-newkey rsa:2048 -nodes -sha256 \
			-subj '/CN=localhost' -extensions EXT -config /dev/fd/3 3<&0 \
    ; \
	chmod +r /usr/local/etc/certs/localhost.key; \
    # enable ssl apache vhost
    sed -ri -e "s/Listen 443/Listen 8443/g" /etc/apache2/ports.conf; \
    sed -ri \
        -e 's/VirtualHost _default_:443/VirtualHost \*:8443/g' \ 
        -e 's!SSLCertificateFile.*?$!SSLCertificateFile /usr/local/etc/certs/localhost.crt!g' \
        -e 's!SSLCertificateKeyFile.*$!SSLCertificateKeyFile /usr/local/etc/certs/localhost.key!g' \
        /etc/apache2/sites-available/default-ssl.conf \
    ; \
    a2enmod ssl && a2ensite default-ssl.conf;


CMD sleep infinity;



# build prodction release files
FROM build-deps as builder

COPY . /usr/build

RUN set -eux; \
    cd /usr/build; \
    git clean -f -d -X ./; \
    composer install



# image to run on live site
FROM shared as production

COPY --chown=www-data:www-data --from=builder /usr/build /usr/src

CMD apache2-foreground