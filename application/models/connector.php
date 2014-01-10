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

	function setInvite($uid1, $uid2){
		$data1 = array('uid_1'=>$uid1, 'uid_2'=>$uid2, 'is_invite'=>2);
		$data2 = array('uid_1'=>$uid2, 'uid_2'=>$uid1, 'is_invite'=>1);
		try{
			
			$query_string = "SELECT * FROM friends WHERE uid_1 = " . $uid2 . " AND uid_2 = " . $uid1 . " AND is_invite = 2";
			$res3 = $this->db->query($query_string);

			if ($res3->num_rows() == 1){
				$this->addNewFriendship($uid1, $uid2);
				return 1;
			}
			//repeat request
			$query_string = "SELECT * FROM friends WHERE uid_1 = " . $uid1 . " AND uid_2 = " . $uid2 . " AND is_invite = 2";
			$res1 = $this->db->query($query_string);			

			//alrady friends
			$query_string = "SELECT * FROM friends WHERE uid_1 = " . $uid1 . " AND uid_2 = " . $uid2 . " AND is_invite = 0";
			$res2 = $this->db->query($query_string);
			
			if ($res1->num_rows() == 0 and $res2->num_rows() == 0){
				$this->db->insert('friends', $data2);
				return $this->db->insert('friends', $data1);
			} else {
				return null;
			}
		}catch (Exception $e){
			return NULL;
		}
	}

	function removeFriendship($uid1, $uid2){
		$data1 = array('uid_1'=>$uid1, 'uid_2'=>$uid2);
		$data2 = array('uid_1'=>$uid2, 'uid_2'=>$uid1);
		$this->db->delete('friends', $data1);
		return $this->db->delete('friends', $data2);
	}

	function addNewFriendship($uid1, $uid2){
		$data1 = array('uid_1'=>$uid1, 'uid_2'=>$uid2, 'is_invite'=>0);
		$data2 = array('uid_1'=>$uid2, 'uid_2'=>$uid1, 'is_invite'=>0);		
		try{
			$this->db->update('friends', $data2, array('uid_1'=>$uid2, 'uid_2'=>$uid1));
			$this->db->update('friends', $data1, array('uid_1'=>$uid1, 'uid_2'=>$uid2));			
			return true;
		}catch (Exception $e){
			return NULL;
		}
	}

	function getFriendships($uid1){
		$query_string = "SELECT uid_2 as uid, is_invite as status FROM friends WHERE uid_1 = " . $uid1;
		return $this->db->query($query_string);
	}

	function getUserIDFromEmail($email){
		$query_string = "SELECT id FROM users WHERE email = '". $email ."'";		
		$result = $this->db->query($query_string);
		if ($result->result_array() != null){
			return $result->row(0)->id;
		}		
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
