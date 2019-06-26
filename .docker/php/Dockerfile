FROM kibatic/symfony:7.3

LABEL maintainer="Henny Krijnen <henny.krijnen@notive.nl>"

RUN apt-get -qq update > /dev/null

RUN DEBIAN_FRONTEND=noninteractive apt-get -qq -y --no-install-recommends install \
    php7.3-mysql

