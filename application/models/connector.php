<?php 
class Connector extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function addNewUser($name, $email, $password){
		$data = array(
			'name'=>$name,
			'email'=>$email,
			'password'=>$password,			
		);	

		try{
			return $this->db->insert('users', $data);
		} catch (Exception $e){
			return NULL;
		}		
	}	
	
	function login($email, $password){
		$query_string = "SELECT id as uid
						 FROM users
						 WHERE email = '" . $email .
						 "' AND password = '" . $password . "'"; 		
		return $this->db->query($query_string);
	}

	function addNewFriendship($uid1, $uid2){
		$data1 = array('uid_1'=>$uid1, 'uid_2'=>$uid2);
		$data2 = array('uid_1'=>$uid2, 'uid_2'=>$uid1);
		try{
			$this->db->insert('friends', $data2);
			return $this->db->insert('friends', $data1);			
		}catch (Exception $e){
			return NULL;
		}
	}

	function getFriendships($uid1){
		$query_string = "SELECT * FROM friends WHERE uid_1 = " . $uid1;
		return $this->db->query($query_string);
	}

	function getUserIDFromEmail($email){
		$query_string = "SELECT id FROM users WHERE email = '". $email ."'";		
		$result = $this->db->query($query_string);
		//TODO: CHECK WHAT THIS ACTUALLY RETURNS
		return $result->row(0)->id;
	}

	function getUserByID($uid){
		$query_string = "SELECT * FROM users WHERE id = ". $uid;		
		$result = $this->db->query($query_string);		
		return $result;	
	}

	function getUserByEmail($email){
		$query_string = "SELECT * FROM users WHERE email = '". $email . "'";		
		$result = $this->db->query($query_string);		
		return $result;	
	}

	function addDailyEntry($uid, $cost, $start, $duration, $usage){
		$data = array("uid"=>$uid, "start"=>$start, "cost"=>$cost, "duration"=>$duration, "usage"=>$usage);
		try{
			$this->db->insert('daily_data', $data);
		} catch (Exception $e){
			return;
		}
	}

	function getUsageBetween($uid, $startDate, $endDate){
		$query = "SELECT `start` as `date`, SUM(`usage`) / 1000 as 'usage', SUM(`cost`) / 100000 as 'cost' FROM daily_data WHERE `uid` = ". $uid . " AND `start` BETWEEN '" . $startDate . "' AND '". $endDate . "'";		
		$result = $this->db->query($query);		
		return $result;	
	}	

	function getRelatedUsers($uid, $houseSize, $numMembers){
		$houseSizeLowerDeviation = $houseSize - ($houseSize * 0.15);
		$houseSizeHigherDeviation = $houseSize + ($houseSize * 0.15);
		$numMembersLower = $numMembers - 2;
		$numMembersHigher = $numMembers + 2;
		$query = "SELECT * from `users` WHERE `uid` <> " . $uid . " AND `house_size` BETWEEN " . $houseSizeLowerDeviation . " AND " . 
		          $houseSizeHigherDeviation . " AND  `num_family_members` BETWEEN " . $numMembersLower . " AND " . $numMembersHigher;  

		$result = $result = $this->db->query($query);
		return $result; 
	}

	function getRelatedUsersWithHeat($uid, $houseSize, $numMembers, $heatType){
		$houseSizeLowerDeviation = $houseSize - ($houseSize * 0.15);
		$houseSizeHigherDeviation = $houseSize + ($houseSize * 0.15);
		$numMembersLower = $numMembers - 2;
		$numMembersHigher = $numMembers + 2;
		$query = "SELECT * from `users` WHERE `uid` <> " . $uid . " AND `house_size` BETWEEN " . $houseSizeLowerDeviation . " AND " . 
		          $houseSizeHigherDeviation . " AND  `num_family_members` BETWEEN " . $numMembersLower . " AND " . $numMembersHigher . " AND `heat_type` = '" . $heatType . "'";  
		$result = $result = $this->db->query($query);
		return $result; 
	}

}

?>
