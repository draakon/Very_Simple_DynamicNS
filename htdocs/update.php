<?php
/**
 * Very simple script for updating bind name server subdomains with dynamic IP-addresses.
 * @author Kristjan Kaitsa <kristjan.kaitsa@eesti.ee>
 * @copyright MIT License Kristjan Kaitsa 2013
 */
require_once 'config.php';

function failure($message = CODE_AUTHENTICATION_FAILTURE) {
	header('WWW-Authenticate: Basic realm="Dynamic DNS Update"');
	header('HTTP/1.1 401 Unauthorized');
	die($message);
}

header("Content-Type: text/plain");

if ( ( HTTP_AUTHENTICATION === true ) && (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) )	failure(CODE_AUTHENTICATION_FAILTURE);

$username = strtolower( HTTP_AUTHENTICATION ? $_SERVER['PHP_AUTH_USER'] : $_GET['username'] );
$password = (string)( HTTP_AUTHENTICATION ? $_SERVER['PHP_AUTH_PW'] : $_GET['password'] );
	
$hostname = @strtolower( $_GET['hostname'] );

if ( empty($username) || empty($password) || empty($hostname) || empty($_GET['myip']) )	failure(CODE_ERROR);

if ( !( $ipAddress = filter_var( $_GET['myip'], FILTER_VALIDATE_IP ) ) ) failure(CODE_ERROR);

if ( ( $authentications[ $username ]['password'] === $password ) && ( $authentications[ $username ]['hostname'] === strtolower( $hostname ) ) ) {
	
	require_once 'include/flatfile.php';
	$db = new Flatfile();
	$db->datadir = PATH_DATADIR;
	
	$row = $db->selectUnique(FILE_HOSTNAMES, FIELD_HOSTNAME, $hostname);
	
	if ( empty($row) ) {
		$newRecord  = array(
			FIELD_ID => 0,
			FIELD_HOSTNAME => $hostname,
			FIELD_IP_ADDRESS => $ipAddress,
			FIELD_OWNER => $username,
			FIELD_UPDATED => true,
		);
		
		$db->insertWithAutoId(FILE_HOSTNAMES, FIELD_ID, $newRecord);
	}
	elseif ($row[FIELD_IP_ADDRESS] == $ipAddress) exit(CODE_SUCCESSFUL_NOT_CHANGED);
	elseif ($row[FIELD_IP_ADDRESS] != $ipAddress) {
		if ( $row[FIELD_OWNER] !== $username ) die(CODE_ERROR);
		$row[FIELD_IP_ADDRESS] = $ipAddress;
		$row[FIELD_UPDATED] = true;
		$db->updateRowById(FILE_HOSTNAMES, FIELD_ID, $row);
	}
	
	exit(CODE_SUCCESSFUL);
}
else failure(CODE_AUTHENTICATION_FAILTURE);
