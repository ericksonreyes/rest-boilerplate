#!/bin/bash
clear 

cd storage/app
#rm -Rf *
#curl -Lo php-7.2.13.tar.gz:Q!
 http://us3.php.net/get/php-7.2.13.tar.gz/from/this/mirror
#tar xfz php-7.2.13.tar.gz
cd php-7.2.13
CC
./configure --with-config-file-path=/usr/local/etc --prefix=/usr  \
  --enable-maintainer-zts \
  --enable-pthreads=shared

make clean
make
make install

export PATH="/usr/local/bin:$PATH"


cd ext
# mkdir pthreads
cd pthreads
# git clone https://github.com/krakjoe/pthreads.git .
phpize && ./configure && make install

cd ../..
make clean
rm -f configure && ./buildconf --force


./configure --with-config-file-path=/usr/local/etc --prefix=/usr  \
  --with-curl \
  --with-openssl \
  --with-xmlrpc \
  --with-gd \
  --with-jpeg-dir \
  --with-png-dir \
  --with-mysqli \
  --with-iconv \
  --with-pgsql \
  --with-pdo-mysql \
  --with-freetype-dir \
  --with-ldap \
  --with-zlib \
  --with-xsl \
  --with-zlib \
  --with-zlib-dir=/usr
  --enable-fpm \ 
  --enable-cgi \ 
  --enable-intl \
  --enable-mbstring \
  --enable-soap \
  --enable-zip \
  --enable-embedded-mysqli \
  --enable-pcntl \
  --enable-pthreads \ 
  --enable-maintainer-zts \
  --enable-phar \
  --enable-ftp


  --enable-cgi \ 
  --enable-fpm \ 
  --enable-libxml=shared \
  --enable-bcmath=shared \ 
  --enable-calendar=shared \ 
  --enable-ctype=shared \ 
  --enable-dom=shared \ 
  --enable-exif=shared \ 
  --enable-fileinfo=shared \ 
  --enable-filter=shared \ 
  --enable-ftp=shared \ 
  --enable-hash=shared \ 
  --enable-intl=shared \
  --enable-json=shared \ 
  --enable-mbstring \ 
  --enable-mbregex \ 
  --enable-mbregex-backtrack \ 
  --enable-pcntl=shared \ 
  --enable-pdo \ 
  --enable-phar=shared \ 
  --enable-posix=shared \ 
  --enable-session=shared \ 
  --enable-shmop=shared \ 
  --enable-simplexml=shared \ 
  --enable-soap=shared \ 
  --enable-sockets=shared \ 
  --enable-sysvmsg=shared \ 
  --enable-sysvsem=shared \ 
  --enable-sysvshm=shared \ 
  --enable-tokenizer=shared \ 
  --enable-wddx=shared \ 
  --enable-xml=shared \ 
  --enable-xmlreader=shared \ 
  --enable-xmlwriter=shared \ 
  --enable-zip=shared \ 
  --with-openssl=shared \ 
  --with-kerberos \ 
  --with-pcre-regex \ 
  --with-sqlite3 \ 
  --with-zlib=shared \ 
  --with-bz2=shared \ 
  --with-curl=shared \ 
  --with-gd=shared \ 
  --with-jpeg-dir=/usr \ 
  --with-png-dir=/usr \ 
  --with-zlib-dir=/usr \ 
  --with-xpm-dir=/usr \ 
  --with-freetype-dir=/usr \ 
  --with-gettext=shared \ 
  --with-gmp=shared \ 
  --with-mhash=shared \ 
  --with-iconv=shared \ 
  --with-imap=shared \ 
  --with-imap-ssl \ 
  --with-ldap=shared \ 
  --with-mcrypt=shared \ 
  --with-msql=shared \ 
  --with-mysql=shared,mysqlnd \ 
--with-mysql-sock=/No-MySQL-hostname-was-specified \ --with-mysqli=shared,mysqlnd \ --with-pdo-mysql=shared,mysqlnd \ --with-pdo-pgsql=shared \ --with-pdo-sqlite \ --with-pgsql=shared \ --with-pspell=shared \ --with-readline=shared \ --with-tidy=shared \ --with-xmlrpc=shared \ --with-xsl=shared'



make
make install