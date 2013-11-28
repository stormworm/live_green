<?php 
class Connector extends CI_Model {

	function __construct(){
		parent::__construct();
		$this->load->database();
	}
	
	function addNewUser($name, $email, $password, $picture, $house_size, $num_family_members, $heat_type){
		$data = array(
			'name'=>$name,
			'email'=>$email,
			'password'=>$password,
			'picture'=>$picture,
			'house_size'=>$house_size,
			'num_family_members'=>$num_family_members,
			'heat_type'=>$heat_type	
		);	

		try{
			return $this->db->insert('users', $data);
		} catch (Exception $e){
			return NULL;
		}		
	}	
	
	function login($email, $password){
		$query_string = "SELECT *
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

	function addDailyEntry($uid, $cost, $start, $duration){
		$data = array("start"=>$start, "cost"=>$cost, "duration"=>$duraction);
		try{
			$this->db->insert('daily_data', $data);
		} catch (Exception $e){
			return;
		}
	}

}

?>
