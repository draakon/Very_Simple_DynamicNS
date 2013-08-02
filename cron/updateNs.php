#!/usr/bin/php
<?php
/**
 * Very simple script for updating bind name server subdomains with dynamic IP-addresses.
 * @author Kristjan Kaitsa <kristjan.kaitsa@eesti.ee>
 * @copyright MIT License Kristjan Kaitsa 2013
 */
define('PATH_HTDOCS',		'/var/www/deemon.eu/main/dyndns/');

require_once PATH_HTDOCS.'config.php';

if ( posix_getuid() !== UID_ROOT ) die('Run this script as root.');

require_once PATH_HTDOCS.'include/flatfile.php';
$db = new Flatfile();
$db->datadir = PATH_DATADIR;

$updatedRecords = $db->selectWhere( FILE_HOSTNAMES, new SimpleWhereClause( FIELD_UPDATED, '=', true) );
if (empty($updatedRecords)) exit(0);

$commandsArray = array();
$commandsArray[] = 'server ' . SERVER;
foreach ( $updatedRecords as $record ) {
	$commandsArray[] = 'update delete '.$record[FIELD_HOSTNAME].' '.HOST_TYPE;
	$commandsArray[] = 'update add '.$record[FIELD_HOSTNAME].' '.TTL.' '.HOST_TYPE.' '.$record[FIELD_IP_ADDRESS];
	$record[FIELD_UPDATED] = false;
	$db->updateRowById(FILE_HOSTNAMES, $record[FIELD_ID], $record);
}
$commandsArray[] = 'send';
$commandsArray[] = 'quit';

$nsupdateHandle = popen(PATH_NSUPDATE.' -k '.KEY_FILE, 'w');
fputs($nsupdateHandle, implode("\n", $commandsArray)."\n");
plcose($nsupdateHandle);
