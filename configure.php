<?php 

date_default_timezone_set('Asia/Jakarta');

set_time_limit(0);


/* RFID RELATED*/
define('RFID_HOST', 'middleware-pc');
define('RFID_PORT', '9890');


/* DATABASE RELATED*/
$config['db']['DB_HOST'] = '192.168.1.6';
$config['db']['DB_NAME'] = 'uilis';
$config['db']['DB_PORT'] = '3306';
$config['db']['DB_USERNAME'] = 'middleware4RFID';
$config['db']['DB_PASSWORD'] = 'user4middleRFID';

/* TIME RELATED*/
$config['time']['Ymd'] 			= date("Ymd");
$config['time']['Y-m-d'] 		= date("Y-m-d");
$config['time']['Y-m-d time'] 	= date("Y-m-d H:i:s");
$config['time']['hours']		= date("His");


/* CAUTION RELATED */
/* Socket Caution*/
$config['caution']['socket_create'] = "Could not create socket\n";
$config['caution']['socket_bind'] 	= "Could not bind to socket\n";
$config['caution']['socket_listen'] = "Could not set up socket listener\n";
$config['caution']['socket_accept'] = "Could not accept incoming connection\n";
$config['caution']['socket_read'] 	= "Could not read input\n";
$config['caution']['socket_write'] 	= "Could not write output\n";

/* DB Caution */
$config['caution']['db_failed'] 	= "Connection Failed\n";
$config['caution']['db_not_found'] 	= "Database Not Found \n";
$config['caution']['db_close'] 		= "Connection Close\n";
?>