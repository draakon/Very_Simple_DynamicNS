Very Simple DynamicNS v0.1a
=======
Kristjan Kaitsa

### 1. Description
Very simple script (one night code) for management of hostnames with dynamic IP-address.
Meant to work with embed routers such as Thomson TG784.

PHP Flatfile package is used: http://lukeplant.me.uk/resources/flatfile/

### 2. Requirements

Requires web server with php installed and configured (for example apache2 + php5-cgi),
bind9 name server with bind9utils (necessary for doing dynamic updates with nsupdate).

Tested in the following environment:
* Linux Debian 6.0.7 "squueze"
* Apache/2.2.16 (Debian) mod_ssl/2.2.16 OpenSSL/0.9.8o mod_fcgid/2.3.6
* PHP Version 5.3.3-7+squeeze15 (API: FastCGI)
* bind9 1:9.7.3.dfsg-1~squeeze9
* bind9utils 1:9.7.3.dfsg-1~squeeze9

### 3. Installation
Apache, PHP and Bind9 should be already configured.

* Add new zone file for your dynamic domains (example in /bind directory)
* Generate cryptographic key, example:
# dnssec-keygen -b 512 -a HMAC-SHA512 -v 10 -n HOST mine.ahju.eu.
- Replace mine.ahju.eu. with name of your zone. Add -r /dev/urandom parameter if the
generation takes too long (the key will be cryptographically less random though).
* Protect generated keys with chmod 400.
* Copy key from generated .private file to your named.conf (example included).
* Extract/move/copy content htdocs to desired place that is accessible from web.
* Set required parameters in the config.php file (includes username, password and hostname).
* Execute following commands in dyndns directory (htdocs):
 ```
chown root:www-data config.php .htaccess update.php data
chmod 440 .htaccess
chmod 640 config.php update.php
chmod 770 data
chown root:root updateNs.php
chmod 400 updateNs.php
 ```
* Setup root cron to run updateNs.php for example every 15 minutes.

4. Notes

.htaccess file is only necessary if PHP is running on FastCGI, not as Apache module.
Otherwise HTTP authentication variables aren't accessible.