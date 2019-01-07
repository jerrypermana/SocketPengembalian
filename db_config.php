<?php 

class Db_config{
	
	/* Define Database Related*/
	public $Host_DB = '';
	public $User_DB = '';
	public $Pass_DB = '';
	public $Name_DB = '';
	public $configure = '';
	
	function __construct(){
		global $config;

		$this->Host_DB 		= $config['db']['DB_HOST'];
		$this->User_DB 		= $config['db']['DB_USERNAME'];
		$this->Pass_DB 		= $config['db']['DB_PASSWORD'];
		$this->Name_DB 		= $config['db']['DB_NAME'];
		$this->configure	= $config;
	} 
	
	// Fungsi Untuk Memcbuka Koneksi Ke Database
	protected function ConnectMysql()
	{
		$connection = mysql_connect($this->Host_DB,$this->User_DB,$this->Pass_DB) or die ($this->configure['caution']['db_failed']);
		return $connection;
	}
	 // Fungsi Untuk Memilih Database Yang Akan Di gunakan
	private function DataBase()
	{
		$connectdb = mysql_select_db($this->Name_DB) or die ($this->configure['caution']['db_not_found']);
		return $connectdb;
	}
	 // Fungsi Untuk Menutup Koneksi Dari Database
	function CloseLink()
	{
		$tutup = mysql_close($this->ConnectMysql()) or die ($this->configure['caution']['db_close']);
		return $tutup;
	}
	 // Fungsi Membuka Koneksi Dan Memilih Database
	function OpenLink()
	{
		$this->ConnectMysql();
		$this->DataBase();
	}
	// mysql query
	function query($query){
		$myquery = mysql_query($query);
		return $myquery;
	}
	 // Fungsi untuk melakukan fetch assoc
	function fetch_assoc($asc){
		$assoc = mysql_fetch_assoc($asc);
		return $assoc; 
	}
    // Fungsi untuk melakukan fetch row
	function fetch_row($rw){
		$row = mysql_fetch_row($rw);
		return $row; 
	}
	function num_rows($n_row){
		$num_row = mysql_num_rows($n_row);
		return $num_row;
	}
	function print_display($conten){
		if($conten == "start"){
			print "=================Start Transaction=====================\n";			
		}elseif($conten == "end"){
			print "==================End Transaction======================\n";
		}else{
			print $conten."\n";	
		}
	}
}
?>