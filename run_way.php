<?php 
class messaGe extends Db_config{
	
	public $finesValue = '';
	
	function message_Process($inPut_mess){
		$chunK_mes = $this->chunk($inPut_mess);
		return $this->mess_Filter($chunK_mes,$inPut_mess);
	}
	
	function mess_Filter($chunK_mes,$inPut_mess){
		$cir = new circulation();
	
		if($chunK_mes[0] == '99'){
			// message combine
			$message = '98YYYNYY100001'.$this->configure['time']['Ymd'].'    '.$this->configure['time']['hours'].'2.00AOUNSYIAH|BXYYYNYYYYNYYNNNYN|'.$chunK_mes[1];
			return $message;
		}
		
		if($chunK_mes[0] == '17'){
			// chunk item code
			$ItemCode = $this->chunkItemcode($inPut_mess, $chunK_mes[0]);
			
			// fines value
			$fines_value = $this->finesValue;
			
			// fines filter
			if($this->finesValue == '0'){ $title=$cir->takeTitle($ItemCode).'(Denda : '.$this->finesValue.')'; }
			else{ $title=$cir->takeTitle($ItemCode).'(Denda : '.$this->finesValue.')';}
			
			// message combine
			$message = '18000001'.$this->configure['time']['Ymd'].'    '.$this->configure['time']['hours'].'CF0|AB'.$ItemCode.'|AJ' .$title.'|APPustaka UNSYIAH|CK001|'.$chunK_mes[1];
			return $message;
		}
		
		if($chunK_mes[0] == '09'){
			// chunk item code
			$ItemCode = $this->chunkItemcode($inPut_mess, $chunK_mes[0]);
			
			// take title
			$title=$cir->takeTitle($ItemCode);
			
			// fines value
			$view_fines_info = $cir->view_fines($ItemCode);
			if($view_fines_info[6] == true){
				$due_date 			= $view_fines_info[0];
				$fine_each_day 		= $view_fines_info[1];
				$item_code 			= $view_fines_info[2];
				$member_name		= $view_fines_info[3];
				$member_id			= $view_fines_info[4];
				$this->finesValue 	= $view_fines_info[5];
				
				// print to display
				$this->print_display("Member Name :".$member_name);
				$this->print_display("Item Code :".$item_code);
				$this->print_display("Member ID".$member_id);
				$this->print_display("Title Book :".$title);
				
				// fines filter
				if($this->finesValue == '0'){ $title=$cir->takeTitle($ItemCode).'(Denda : '.$this->finesValue.')'; }
				else{ $title=$cir->takeTitle($ItemCode).'(Denda : '.$this->finesValue.')';}
				
				$update = $cir->UpdateReturn($item_code, $member_id);
				if($update == true){
					
					$message = '101YNN'.$this->configure['time']['Ymd'].'    '.$this->configure['time']['hours'].'AOUNSYIAH|AB'.$item_code.'|AJ'.$title.'|AQUNSYIAH|BXYYYNYYYYNYYNNNYN|DA'.$member_name.'|'.$chunK_mes[1];
				}else{
					$memberName = 'NULL';
					$message = '100NNN'.$this->configure['time']['Ymd'].'    '.$this->configure['time']['hours'].'AOUNSYIAH|AB'.$item_code.'|AJ'.$title.'|AQUNSYIAH|BXYYYNYYYYNYYNNNYN|DA'.$memberName.'|'.$chunK_mes[1];
				}
			}else{
				$memberName = 'NULL';
				$message = '100NNN'.$this->configure['time']['Ymd'].'    '.$this->configure['time']['hours'].'AOUNSYIAH|AB'.$ItemCode.'|AJ'.$title.'|AQUNSYIAH|BXYYYNYYYYNYYNNNYN|DA'.$memberName.'|'.$chunK_mes[1];				
			}
			return $message;
		}
	}
	
	function chunk($input){
		$input 			= trim($input);
		$frontDigit 	= substr($input,0,2);
		if($frontDigit == '99'){$backDigit 	= substr($input,10,9).chr(13);}
		else{$backDigit = substr($input,-9,9).chr(13);}
		$result = array($frontDigit,$backDigit);
		return $result;
	}
	function chunkItemcode($input, $Code){
		$input 				= explode('|',$input);
		if($Code == '17'){	$DataItem 			= substr($input[1],2,15);	}
		if($Code == '09'){	$DataItem 			= substr($input[2],2,15);	}		
		return $DataItem;
	}
}
?>