<?php 
require "configure.php";
require "db_config.php";
require "run_way.php";
require "circulation/circulation.php";


/* CLASS DEFINE*/
$DB 		= new Db_config($config);
$DB->OpenLink();
$messClass 	= new messaGe(); 

// create socket
$sockeT = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die($config['caution']['socket_create']);

// bind socket to port
$result = socket_bind($sockeT, RFID_HOST, RFID_PORT) or die($config['caution']['socket_bind']);

// listen socket port
$result = socket_listen($sockeT, 5) or die($config['caution']['socket_listen']);

do { 
// accept socket
	$DB->print_display(RFID_HOST);
	$DB->print_display(RFID_HOST);
	
	$preF = socket_accept($sockeT) or die($config['caution']['socket_accept']);
	do {
		$DB->print_display("start");
		
		//$inComing = socket_read($preF, 1048) or die($config['caution']['socket_read']);
		$inComing = socket_read($preF, 1048) or die("Could not read input \n");
			$meS = $messClass->message_Process($inComing);
			$DB->print_display($meS);
			socket_write($preF,$meS, strlen($meS)) or die($config['caution']['socket_write']);
	}while(true);
	if(socket_close($preF)){
		do {
		$DB->print_display("start");
		
		//$inComing = socket_read($preF, 1048) or die($config['caution']['socket_read']);
		$inComing = socket_read($preF, 1048) or die("Could not read input \n");
			$meS = $messClass->message_Process($inComing);
			$DB->print_display($meS);
			socket_write($preF,$meS, strlen($meS)) or die($config['caution']['socket_write']);
	}while(true);
	}
	//socket_close($preF);
}
while(true);
socket_close($sockeT);
?>

