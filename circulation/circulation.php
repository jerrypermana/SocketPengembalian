<?php 
class circulation extends Db_config{

	// View Fines
	function view_fines($itemCode){
		$query = $this->query("SELECT l.due_date, mtype.fine_each_day, l.item_code, m.member_name, m.member_id FROM loan AS l INNER JOIN member AS m ON l.member_id=m.member_id INNER JOIN mst_member_type AS mtype ON m.member_type_id=mtype.member_type_id WHERE l.is_lent='1' AND l.is_return='0' AND l.item_code='$itemCode'");
		$myquery = $this->fetch_row($query);
		
		if($myquery == true){
			$due_date 		= $myquery[0];
			$fine_each_day 	= $myquery[1];
			$item_code 		= $myquery[2];
			$member_name	= $myquery[3];
			$member_id		= $myquery[4];
			$paid			= 0;
			$status			= true;
			
			if($due_date < $this->configure['time']['Y-m-d']){
				$paid = $this->countFines($due_date, $fine_each_day, $this->configure['time']['Y-m-d']);
				$description = 'Overdue fines for item '.$item_code;
				
				// Insert Fines To Database
				$this->InsertFines($member_id, $paid,$description);
			}
		}else{
			$due_date 		= 'NULL';
			$fine_each_day 	= 'NULL';
			$item_code 		= 'NULL';
			$member_name	= 'NULL';
			$member_id		= 'NULL';
			$paid			= 'NULL';
			$status			= false;
		}
		$result = array($due_date,$fine_each_day, $item_code, $member_name,	$member_id,	$paid, $status);
		return $result;
	}
	
	// Take Title
	function takeTitle($itemCode){
		$query		 	= $this->query('SELECT b.title FROM biblio AS b LEFT JOIN item AS i ON b.biblio_id=i.biblio_id WHERE i.item_code=\''.$itemCode.'\'');
		$myquery 		= $this->fetch_row($query);
		return $myquery[0];
	}
	
	// Calculating  Fines
	function countFines($due_date, $fine_each_day, $date_now){
		$holiday_count = 0;
		$deff 		   = $this->defiasi_day($date_now, $due_date);
		$holiday_query = $this->query("SELECT holiday_date FROM holiday");
		while($myHoliday = $this->fetch_row($holiday_query)){
			if($myHoliday[0] >= $due_date AND $myHoliday[0] <= $date_now){
				$holiday_count++;
			}
		}
		$count_days_fines = $deff-$holiday_count;
		if($count_days_fines < 0){	$count_days_fines;	}
		$paid = $count_days_fines * $fine_each_day;
		return $paid;
	}
	
	// Counting difference in days
	function defiasi_day($firstDate, $lastDate){
		// Split First Date
		$splitOne 	= explode("-", $firstDate);
		$dayOne 	= $splitOne[2];
		$monthOne 	= $splitOne[1];
		$yearOne 	= $splitOne[0];
		
		// Split Last Date
		$splitTwo 	= explode("-", $lastDate);
		$dayTwo 	= $splitTwo[2];
		$monthTwo 	= $splitTwo[1];
		$yearTwo 	= $splitTwo[0];

		// Count Deff
		$DateOne = GregorianToJD($monthOne, $dayOne, $yearOne);
		$DateTwo = GregorianToJD($monthTwo, $dayTwo, $yearTwo);

		$Deff = $DateOne - $DateTwo;
		return $Deff;
		
	}
	
	// function for Update Return to Database
	function UpdateReturn($itemCode, $memberID){
		$Update = $this->query("UPDATE loan SET is_return='1', 
								return_date='".$this->configure['time']['Y-m-d']."' WHERE member_id='$memberID' AND item_code='$itemCode' AND is_lent='1' AND is_return='0'");
		if($Update){
			return true;
		}else{
			return false;
		}
	}
	
	// insert fines to database
	function InsertFines($member_id, $debet,$description){
		$Insert_FINES = $this->query("INSERT INTO fines (fines_date, member_id, debet, description) VALUES('".$this->configure['time']['Y-m-d']."', '$member_id', '$debet', '$description')");
	}
}
?>