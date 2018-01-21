<?php 
/*
Comments
=========
Biosangam 2018, MNNIT ALLAHABAD
BACKEND CODE BY MANJIT RAJ
*/
//contains our database information
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

class dbase{
	public $host = 'localhost';
	public $user = 'root';
	public $pass = 'Abhishek_2';
	public $db = 'biosangam_mnnit';
    
    public function connect(){
		$mycon = mysqli_connect($this->host,$this->user,$this->pass,$this->db);
		if($mycon){
			return $mycon;
		}else{
			die(trigger_error(mysqli_connect_error()));
		}
	}
}

/*
user status
============
10 - inactive user,abstract not submitted
20 = active user, abstract submitted


*/


 ?>