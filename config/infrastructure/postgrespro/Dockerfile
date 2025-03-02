FROM centos:8

ENV TZ="Europe/Moscow"
ENV LANG="ru_RU.UTF-8"

RUN ln -sf /usr/share/zoneinfo/$TZ /etc/localtime \
### CentOS Linux 8 had reached the End Of Life. Change the repos to the permanently archived mirrors to vault.centos.org
    && cd /etc/yum.repos.d/ \
    && sed -i 's/mirrorlist/#mirrorlist/g' /etc/yum.repos.d/CentOS-* \
    && sed -i 's|#baseurl=http://mirror.centos.org|baseurl=http://vault.centos.org|g' /etc/yum.repos.d/CentOS-* \
### Install Locale pack
    && dnf install glibc-langpack-ru redhat-rpm-config wget -y \
    && echo "LANG="$LANG > /etc/locale.conf \
### Install Postgres Pro \
    && wget -q https://repo.postgrespro.ru/pgpro-14/keys/pgpro-repo-add.sh \
    && chmod 775 ./pgpro-repo-add.sh \
    && ./pgpro-repo-add.sh \
    && yum makecache \
    && yum install postgrespro-std-14 postgrespro-std-14-client postgrespro-std-14-devel -y \
### Install Oracle client
    && yum install libaio -y \
    && rpm -i https://download.oracle.com/otn_software/linux/instantclient/216000/oracle-instantclient-basic-21.6.0.0.0-1.el8.x86_64.rpm \
    && rpm -i https://download.oracle.com/otn_software/linux/instantclient/216000/oracle-instantclient-devel-21.6.0.0.0-1.el8.x86_64.rpm \
    && rpm -i https://download.oracle.com/otn_software/linux/instantclient/216000/oracle-instantclient-sqlplus-21.6.0.0.0-1.el8.x86_64.rpm \
### Install Postgres extension Oracle FDW \
    && yum install make gcc -y \
    && wget -q https://github.com/laurenz/oracle_fdw/archive/refs/tags/ORACLE_FDW_2_4_0.tar.gz \
    && tar -xvzf./ORACLE_FDW_2_4_0.tar.gz \
    && cd ./oracle_fdw-ORACLE_FDW_2_4_0/ \
    && PATH=$PATH:/opt/pgpro/std-14/bin && export PATH \
    && make && make install && cd .. \
### Clean up
    && rm ./pgpro-repo-add.sh \
    && rm ./ORACLE_FDW_2_4_0.tar.gz \
    && rm -R ./oracle_fdw-ORACLE_FDW_2_4_0/ \
    && yum remove make gcc -y \
    && yum clean all

USER postgres

CMD ["/opt/pgpro/std-14/bin/postgres", "-D", "/var/lib/pgpro/std-14/data"]
