<?php
/**
 * Very simple script for updating bind name server subdomains with dynamic IP-addresses.
 * @author Kristjan Kaitsa <kristjan.kaitsa@eesti.ee>
 * @copyright MIT License Kristjan Kaitsa 2013
 */
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT); // flatfile is depricated

define('TTL',							1800);
define('HOST_TYPE',						'A');
define('SERVER',						'ns1.deemon.eu');
define('HTTP_AUTHENTICATION',			true); // otherwise parameters from url are used
define('UID_ROOT',						0);
define('KEY_FILE',						'/etc/bind/Kmine.ahju.eu.+165+13843.private');
define('PATH_NSUPDATE',					'/usr/bin/nsupdate');

/* Simple list of users, passwords and their domains */
$authentications = array(
	'foo' => array(
		'password' => 'bar',
		'hostname' => 'foobar.mine.ahju.eu',
	),
);


// return codes
define('CODE_SUCCESSFUL',				'ok');
define('CODE_SUCCESSFUL_NOT_CHANGED',	'nochange');
define('CODE_AUTHENTICATION_FAILTURE',	'unauth');
define('CODE_ERROR',					'abuse');

// flatfile
define('PATH_DATADIR',					__DIR__.'/data/');
define('FILE_HOSTNAMES',				'hostnames.txt');

define('FIELD_ID',						0);
define('FIELD_HOSTNAME',				1);
define('FIELD_IP_ADDRESS',				2);
define('FIELD_OWNER',					3);
define('FIELD_UPDATED',					4);
